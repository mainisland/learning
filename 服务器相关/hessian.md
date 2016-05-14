#Hessian

��������Remoting Onhttpg���ߣ����ö�����RPCЭ�飬������֧�֣�


##php����java�ӿ�ʵ������
<?php
include_once CSC_LIBS_DIR . '/hessian/HessianClient.php';

class BaseHessianClient extends HessianClient {

	private static $_self;
	protected $aliases;
	protected $HessianOptions;
	protected $infoException;

	/**
	 * �Ը��๹�캯�����и��죬���Դ���Ĳ���ֱ�Ӵ������н��г�ʼ��
	 * @param string $url
	 * @param string $options
	 */
	public function __construct($url='', $options = null){
		$this->options = $this->transformOptions();
		$this->typemap = new HessianTypeMap($this->options->typeMap);
		$this->factory = new HessianFactory();
	}
	
	/**
	 * ������ʼ������
	 */
	public static final function initialize() {
		$className = get_called_class();
		return new $className;
	}

	/**
	 * �����¼�������������ת��������֮�����Զ�̵Ĳ�������
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
			if ( $Ex->getMessage() == 'CscBizException' ) { // �����ϢΪCscBizException������php�����账��
				$this->infoException = $Ex->detail;
				return 'CscBizException';
			}

// 			throw new CHttpException(500, Yii::t('yii', 'Has CscAppException by {class}::{action}', array('{class}'=>get_called_class(),'{action}'=>$method)));
			// ������Ҫ���ݣ�java���쳣���Ѵ�����쳣��Ϣ����message�� .  By Bear
			throw new CHttpException(500, $Ex->getMessage()); 
		}
	}
	
	/**
	 * ����HessianOptions����
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
	 * ����HessianOptions�����ʼ������
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
	 * ִ�з����¼�����
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
	 * ���������б���ת��
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



[�ο��ĵ�](http://hessian.caucho.com/)