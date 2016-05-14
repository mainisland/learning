<?php
/*
 * This file is part of the HessianPHP package.
 * (c) 2004-2010 Manuel G�mez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include_once 'HessianFactory.php';
include_once 'HessianTransport.php';

/**
 * Proxy to issue RPC calls to remote Hessian services 
 */
class HessianClient{
	protected $url;
	protected $options;
	protected $typemap;
	protected $factory;
	
	/**
	 * Creates a new Client proxy, takes an url and an optional options object
	 * that can also be an array
	 * @param string $url
	 * @param mixed $options
	 */
	public function __construct($url, $options = null){
		$this->url = $url;
		$this->options = HessianOptions::resolveOptions($options);
		$this->typemap = new HessianTypeMap($this->options->typeMap);
		$this->factory = new HessianFactory();
	}
	
	/**
	 * Issues a call to a remote service. It will raise a HessianFault exception if 
	 * there is an error
	 * @param string $method Name of the method in the remote service
	 * @param array $arguments Optional arguments
	 * @return mixed
	 */
	public function __hessianCall($method, $arguments = array()){
	    
		if(strpos($method, "__") === 0)
			throw new HessianException("Cannot call methods that start with __");
		$transport = $this->factory->getTransport($this->options);
		$writer = $this->factory->getWriter(null, $this->options);
		$writer->setTypeMap($this->typemap);

			

		
		$ctx = new HessianCallingContext();
		$ctx->writer = $writer;
		$ctx->transport = $transport;
		$ctx->options = $this->options;
		$ctx->typemap = $this->typemap;
		$ctx->call = new HessianCall($method, $arguments);
		$ctx->url = $this->url;
		$ctx->payload = $writer->writeCall($method, $arguments);
		$args = array($ctx);
		foreach($this->options->interceptors as $interceptor){
			$interceptor->beforeRequest($ctx);
		}
		$this->__handleCallbacks($this->options->before, $args);
		
		$stream = $transport->getStream($this->url, $ctx->payload, $this->options);
		$parser = $this->factory->getParser($stream, $this->options);
		$parser->setTypeMap($this->typemap);
		// TODO deal with headers, packets and the rest of aditional stuff
		$ctx->parser = $parser;
		$ctx->stream = $stream;
		
		try{
			$result = $parser->parseTop();
		} catch(Exception $e){
			$ctx->error = $e;
		}
		foreach($this->options->interceptors as $interceptor){
			$interceptor->afterRequest($ctx);
		}
		$this->__handleCallbacks($this->options->after, $args);
		
		if($ctx->error instanceof Exception) {
		    Yii::log( $ctx->error, CLogger::LEVEL_ERROR, $this->url.'::'.$method );
			throw $ctx->error;
		}
		
		/// end_log //////
// 		if ( HESSIAN_LOG === 'On' ) {
// 		    list($unusec,$sec) = explode(' ', microtime());
// 		    $endTime = (float)$unusec - (float)$usec;
// 		    $row = sprintf("%s %ss\n", $uuid, $endTime);
// 		    $fp = fopen($filepath, 'a');
// 		    flock($fp,LOCK_EX);
// 		    fwrite($fp, $row);
// 		    flock($fp,LOCK_UN);
// 		    fclose($fp);
// 		}
		/// end_log //////
		
		
		/// begin_log //////
		defined('HESSIAN_LOG')||define('HESSIAN_LOG', Yii::app()->request->getQuery('hessian_log','Off'));
		if ( HESSIAN_LOG === 'On' ||  Yii::app()->request->getQuery('hessian_log') === 'On') {
			$uuid = CSCHelper::uuid();
			$filepath = CSC_PRO_LOG_DIR.'/hessian_'.date('Y_m_d_H_i_s').'.log';
			list($usec,$sec) = explode(' ', microtime());
			$beginTime = date( 'Y-m-d H:i:s', (int)$sec );
			/*
			 * 增加日志文件参数
			* date:2011-11-21
			* anthor:xugx
			*/
			//     		$row = sprintf("\n%s\n%s %s::%s\n", $beginTime, $uuid, $this->url, $method);
			ob_start();
			$row =  array(
					'method' => $method,
					'interface' => $this->url,
					'php param' => $arguments,
					'java return' => $result
			);
			echo "///////////////////////同一秒访问的多个接口日志会写在同一个文件里////////////////////////////////////////\n\r";
			echo "date:".date('Y年m月d日 H时i分s秒')."\n\r";
			print_r($row);
			$row = ob_get_clean();
			$fp = fopen($filepath, 'a');
			flock($fp,LOCK_EX);
			fwrite($fp, $row);
			flock($fp,LOCK_UN);
			fclose($fp);
		}
		/// begin_log //////
		
		return $result;
	}
	
	private function __handleCallbacks($callbacks, $arguments){
		if(!$callbacks)
			return;
		if(is_callable($callbacks)){
			return call_user_func_array($callbacks, $arguments);
		}
		if(!is_array($callbacks))
			return;
		foreach($callbacks as $call){
			if(is_callable($call)){
				return call_user_func_array($call, $arguments);
			}
		}
	}
	
	/**
	 * Magic function wrapper for the remote call. It will fail if called
	 * with methods that start with __ which are conventionally private
	 * @param string $method
	 * @param array $arguments
	 * @return mixed Result of the remote call
	 */
	public function __call($method, $arguments){
		return $this->__hessianCall($method, $arguments); 
	}
	
	/**
	 * Returns this client's current options
	 * @return HessianOptions
	 */
	public function __getOptions(){
		return $this->options;
	}
	
	/**
	 * Returns the current typemap for this client
	 * @return HessianTypeMap
	 */
	public function __getTypeMap(){
		return $this->typemap;
	}
}