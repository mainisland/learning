#Hessian

轻量级的Remoting Onhttpg工具，采用二进制RPC协议，多语言支持；


##php调用java接口实例代码
	<?php
	include_once CSC_LIBS_DIR . '/hessian/HessianClient.php';

	class BaseHessianClient extends HessianClient {

	private static $_self;
	protected $aliases;
	protected $HessianOptions;
	protected $infoException;

	/**
	 * 对父类构造函数进行改造，忽略传入的参数直接从设置中进行初始化
	 * @param string $url
	 * @param string $options
	 */
	public function __construct($url='', $options = null){
		$this->options = $this->transformOptions();
		$this->typemap = new HessianTypeMap($this->options->typeMap);
		$this->factory = new HessianFactory();
	}
	
	/**
	 * 单例初始化工作
	 */
	public static final function initialize() {
		$className = get_called_class();
		return new $className;
	}

	/**
	 * 进行事件触发及别名的转换工作，之后调用远程的操作方法
	 * @see HessianClient::__call()
	 */
	public function __call($method,$arguments) {
		$this->infoException = null;
		if(!$this->runBeginEvent($method, $arguments)) {
			return false;
		}
		
		try {
			$method = $this->transformMethod($method);
			return $this->__hessianCall($method, $arguments);
		}
		catch (HessianFault $Ex) {
			if ( $Ex->getMessage() == 'CscBizException' ) { // 如果消息为CscBizException，我们php端无需处理
				$this->infoException = $Ex->detail;
				return 'CscBizException';
			}

// 			throw new CHttpException(500, Yii::t('yii', 'Has CscAppException by {class}::{action}', 		array('{class}'=>get_called_class(),'{action}'=>$method)));
			// 这里需要兼容，java抛异常，把错误的异常信息放在message中 .  By Bear
			throw new CHttpException(500, $Ex->getMessage()); 
		}
	}
	
	/**
	 * 更新HessianOptions设置
	 * @param array $options
	 * @throws CHttpException
	 */
	public function setOptions($options) {
		if (!is_array($options)) {
			throw new CHttpException(500, Yii::t('yii','Invalid arguments $options in {class}::{func}',array('{class}'=>__CLASS__,'{func}'=>__FUNCTION__)));
		}
		if ( $options ) {
			$options = array_merge($this->HessianOptions, $options);
			$this->options = $this->transformOptions($options);
			$this->typemap = new HessianTypeMap($this->options->typeMap);
		}
	}
	
	/**
	 * 进行HessianOptions对象初始化工作
	 * @return HessianOptions|NULL
	 */
	private function transformOptions($HessianOptions=null){
		$options = new HessianOptions();
		if( is_null($HessianOptions) ) {
		    $HessianOptions = $this->HessianOptions;
		}
		
		if(!is_array($HessianOptions)) {
		    $HessianOptions = array();
		}
		array_push($HessianOptions, 'com.csc.appframework.exception.dto.CscAppExceptionDTO');
		foreach( $HessianOptions as $item ) {
		    $key = substr($item, strrpos($item,'.')+1);
		    $options->typeMap[$key] = $item;
		}
		return HessianOptions::resolveOptions($options);
	}
	
	/**
	 * 执行方法事件触发
	 * @param string $method
	 * @param array $paramsArg
	 * @return mixed
	 */
	private function runBeginEvent($method, & $paramsArg) {
		$event = 'OnBegin'.ucfirst($method);
		if ( method_exists($this, $event) ) {
			return call_user_func_array(array($this,$event), array(& $paramsArg));
		}
		return true;
	}
	
	/**
	 * 方法名进行别名转换
	 * @param string $methodName
	 * @return string
	 */
	private function transformMethod($methodName) {
		if ( !is_null($this->aliases) && is_array($this->aliases) && array_key_exists($methodName, $this->aliases) ) {
			return $this->aliases[$methodName];
		}
		return $methodName;
	}
	}



[参考文档](http://hessian.caucho.com/)
