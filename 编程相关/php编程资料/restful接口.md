#RESTFUL �ӿڴ���

    <?php
    /**
    * RESTful V2.0
    * �̳и����yaf_controller��֧��RESTful����
    *
    * @author skc <skai.cpp@gmail.com>
    */
    class Seedit_Restful2 extends Yaf_Controller_Abstract {

        private $_result;
        private $_type;
        private $_code = '';
        private $_msg  = '';
        private $_callback = 'callback';

        private function init_data() {

            $this->_method = $this->getRequest()->getMethod();
            $this->_type = $this->getRequest()->getParam("_type");
            //�����ύ��������ȡ����
            switch($this->_method) {
                case 'GET'   :
                        $param = $_GET;
                        //�ṩ������
                        if(isset($param['__method'])) {
                            $this->_method = $param['__method'];
                            //ɾ���������
                            unset($param['__method']);
                        }
                        if(isset($param['__c'])) {
                            $this->_callback = $param['__c'];
                            //ɾ���������
                            unset($param['__c']);
                        }
                        break;
                case 'POST'  :
                        $this->_contents = file_get_contents('php://input');
                        $param = $_POST;

                        //�ṩ������
                        if(isset($param['__method'])) {
                            $this->_method = $param['__method'];
                            //ɾ���������
                            unset($param['__method']);
                        }
                        break;
                case 'PUT'   :
                        $this->_contents = file_get_contents('php://input');
                        parse_str($this->_contents,$param);
                        break;
                case 'DELETE':
                        $this->_contents = file_get_contents('php://input');
                        parse_str($this->_contents,$param);
                        $param = array_merge($param,$_GET);
                        break;
            }

            //���հ汾��Ϣ����
            if(isset($_GET['__v']))
                $param['__v'] = $_GET['__v'];
            if(isset($_GET['__t']))
                $param['__t'] = $_GET['__t'];

            //���ݲ���
            $this->getRequest()->setParam($param);
        }

        private function run() {

            $allowType = array("json","jsonp","html","test","iframe");
            //����ʽ�Ƿ���ȷ
            if(!in_array($this->_type,$allowType)) {
                $this->set_error('TYPE_ERROR');
            }

            //����method���͵�����Ӧ����
            switch($this->_method) {
                case 'GET'   : //������Ƴ�c����ֻ��get post put���������ƺ�׺Ҳû��Action
                        if(method_exists($this,'get')) {
                            $this->_result = $this->get();
                        } else {
                            $this->set_error('METHOD_GET_ERROR');
                        }
                        break;
                case 'POST'  :
                        if(method_exists($this,'post')) {
                            $this->_result = $this->post();
                        } else {
                            $this->set_error('METHOD_POST_ERROR');
                        }
                        break;
                case 'PUT'   :
                        if(method_exists($this,'put')) {
                            $this->_result = $this->put();
                        } else {
                            $this->set_error('METHOD_PUT_ERROR');
                        }
                        break;
                case 'DELETE':
                        if(method_exists($this,'delete')) {
                            $this->_result = $this->delete();
                        } else {
                            $this->set_error('METHOD_DELETE_ERROR');
                        }
                        break;
                default :
               $this->set_error('METHOD_ERROR');
            }

            //����doc��html���ͣ����ص����ݱ���Ϊ����
            if($this->_type !== "doc" && $this->_type !== "html")  {
                if(!is_array($this->_result) && !is_object($this->_result)) {
                        $this->set_error('SYSTEM_ERROR','�ӿڷ��ص����ݱ���Ϊ��������');
                }
            }
        }

        private function init_header() {
            switch($this->_type) {
                case 'jsonp':
                case 'json' :
                case 'test' :
                    header("Content-Type:application/json;charset=UTF-8");
                    break;
                case 'xml' :
                    header("Content-Type:application/xml;charset=UTF-8");
                    break;
                case 'html' :
                    header("Content-Type:text/html;charset=UTF-8");
                    break;
                case 'doc' :
                    header("Content-Type:text/html;charset=UTF-8");
                    break;
                case 'iframe' :
                    header("Content-Type:text/html;charset=UTF-8");
                    break;
            }
            header("Server: Seedit_Restful V2.0");
        }

        private function error_message($msg) {
            $this->_msg = $msg;
        }

        private function error_code($error_code) {
            $this->_code = $error_code;

        }

        //��ӡjson����
        private function jsonData($data) {
            return json_encode($data);
        }

        //��ӡjsonp����
        private function jsonpData($data,$callback) {
            return $callback."(".json_encode($data).")";
        }

        //��ӡ�ĵ�
        private function outPutDoc() {
            $controllerName = $this->getRequest()->getControllerName();
            $moduleName     = $this->getRequest()->getModuleName();
            if($moduleName == "Index") {
                $dir = APPLICATION_PATH . '/application/controllers';
            } else {
                $dir = APPLICATION_PATH . '/application/modules/'.$moduleName.'/controllers';
            }
            $controllerName = str_replace("_","/",$controllerName);
            $file     = $dir."/".$controllerName.".php";
            $contents = file_get_contents($file);
            $contents = explode("\n",$contents);

            $doc      = array();
            $flags = 0;
            foreach ($contents as $key => $value) {
                if($flags == 0 && trim($value) == "/*") {
                    $flags = 1;
                } elseif($flags == 1) {
                    if(trim($value) != "*/") {
                        $doc[] = $value;
                    }else {
                        $flags = 0;
                    }
                }
            }
        $doc = implode("\n",$doc);
        $html = "<!DOCTYPE html>
                <html>
                <head>
                <meta charset='utf-8'>
                <link href='".STATIC_SEEDIT_COM."/min/f=/markdown-js/lib/markdown.css' rel='stylesheet'>
                </head>
                <body>
                <textarea id='text-input' disabled='false' hidden>
                $doc
                </textarea>
                <div id='preview'>
                </div>
                <script src='".STATIC_SEEDIT_COM."/min/f=/markdown-js/lib/markdown.js'></script>
                <script>
                function Editor(input, preview) {
                    this.update = function () {
                      preview.innerHTML = markdown.toHTML(input.value);
                    }
                    input.editor = this;
                    this.update();
                }
                var $ = function (id) { return document.getElementById(id); };
                new Editor($('text-input'), $('preview'));
                </script>
                </body>
                </html> ";
        echo $html;
        exit;
    }

    /******************* protected *********************/

    //��ֹGET�������
    protected function not_tunnel($method = "GET") {
        if(!SEEDIT_DEBUG)
            return true;

        if($this->getRequest()->getMethod() == $this->_method)
            return true;

        switch ($method) {
            case 'GET':
                if($this->getRequest()->getMethod() == "GET")
                    $this->set_error("METHOD_GET_SET_ERROR");
                break;
        }
        return true;
    }


    //�׳�����,����������
    protected function set_error($code,$message=NULL) {
        if(empty($message))
            throw new Seedit_Restful_Exception($code);
        else
            throw new Seedit_Restful_Exception($code,$message);

    }

    //��ȡ����
    //$name ��������
    //$default_value Ĭ��ֵ
    //$type ��������(int,string)
    protected function getParam($name,$default_value = NULL,$type = NULL) {
        $value = $this->getRequest()->getParam($name,$default_value);

        if($value === NULL || ($value !== $default_value && !isset($value) ))
            $this->set_error('PARAM_ERROR', $name."�����Ǳ����");

        if($type === 'int') {
            if(!is_numeric($value))
                $this->set_error('PARAM_ERROR', $name."�����Ǳ���������");

            $value = intval($value);
        }

        if($type === 'string') {
            if(!is_string($value) || empty($value))
                $this->set_error('PARAM_ERROR', $name."�����Ǳ������ַ���������Ϊ��");
        }

        if($type === 'json') {
            $value = json_decode(stripcslashes($value),true);
            if(json_last_error() != JSON_ERROR_NONE) 
                $this->set_error('PARAM_ERROR', $name."��������Ч��json��ʽ");
        }
        return $value;
    }

    //���ݸ�ʽ��׺�����Ϣ
    protected function returnData() {
        $code = Yaf_Registry::get("code");
        //�ж��Ƿ��д��������
        if($this->_code != '') {
            $error = $code[$this->_code];
            $data['error_code'] = $error[0];
            $data['error_message'] = !empty($this->_msg)?$this->_msg:$error[1];
        } else {
            //������ȷ��������
            $data = array(
                "error_code" => 0,
                "data"       => $this->_result
            );
        }

        switch($this->_type) {
            case 'json' :
                        $reBody = $this->jsonData($data);
                        break;
            case 'jsonp':
                        $reBody = $this->jsonpData($data,$this->_callback);
                        break;
            case 'html' :
                        //���html��ʽ������Ϊ�ַ����������ޱ�����ôֱ�Ӵ�ӡ
                        if($data['error_code'] === 0 && is_string($this->_result)) {
                            $reBody = $this->_result;
                        } else {
                            $reBody = var_export($data,TRUE);
                        }
                        break;
            case "iframe" :
                       $reBody = "<script>document.domain = '". SEEDIT_COM ."';</script>";
                       $reBody .= "<textarea>". $this->jsonData($data). "</textarea>";
                       break;
            case 'doc' :
                        if(SEEDIT_DEBUG) {
                            $this->outPutDoc();
                        }
                        break;
            default :
                        $reBody = var_export($data,TRUE);
        }
        $this->getResponse()->setBody($reBody);

        return true;
    }

    //http�������
    protected function httpCache($time) {
        $timestamp = time() + $time;
        $tsstring  = gmdate('D, d M Y H:i:s ', $timestamp) . 'GMT';
        $etag      = $language . $timestamp;

        $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
        $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
        if ((($if_none_match && $if_none_match == $etag) || (!$if_none_match)) &&
            ($if_modified_since && $if_modified_since == $tsstring))
        {
            header('HTTP/1.1 304 Not Modified');
            exit();
        }
        else
        {
            header("Last-Modified: $tsstring");
            header("ETag: \"{$etag}\"");
        }
    }
    //���Կ��
    private function test($redata) {
        $redata = json_decode(json_encode($redata));
        switch($this->_method) {
            case "GET" :
                return $this->getTest($redata);
        }
    }

    protected function setTestMessage($message) {
        $this->_testMessage = $message;
    }

    protected function getTest($redata) {
        $check_data = $this->reGet;
        if($redata->error_code === 0 ) {
            $queue = array();
            array_push($queue,array($redata->data,$check_data));

            //�����������
            while(!empty($queue)) {
                @list($data,$check_data) = array_pop($queue);
                foreach($data as $key => $value) {
                    //�������ݼ��
                    if(is_array($data)) {
                        if(gettype($value) === "object" || gettype($value) === "array") {
                            array_push($queue,array($value,$check_data));
                        } elseif($check_data !== gettype($value)) {
                            $this->setTestMessage($value . " is ". gettype($value));
                            return false;
                        }

                        continue;
                    }

                    if(isset($check_data[$key])) {
                        if($check_data[$key] !== gettype($value)) {
                            $this->setTestMessage($key . " type is ". gettype($value));
                            return false;
                        }
                        if(gettype($value) === "object" || gettype($value) === "array") {
                            if(isset($check_data["_".$key]))
                                array_push($queue,array($value,$check_data["_".$key]));
                        }
                    }
                }
            }
        }
        return true;
    }

    private function getAllFiles($path) {
        $files = array();
        $dirs = array();
        if(!is_dir($path)) {
            return array();
        }
        do{
            $dp = opendir($path);
            while ($file = readdir($dp)){
                if($file !="." && $file !=".."){
                    $path_file = $path .'/' . $file;
                    if(is_file($path_file)){
                        $files[] =  $path_file;
                    }elseif(is_dir($path_file)) {
                        array_push($dirs,$path_file);
                    }
                }
            }
            closedir($dp);
            $path = array_pop($dirs);
        }while($path);
        return $files;
    }
    
    /******** public **********/
    //��ʼ������
    public function init() {
        Yaf_Dispatcher::getInstance()->disableView();
        try {
            $this->init_data();
            $this->init_header();
            $this->run();
        } catch(Seedit_Restful_Exception $e) {
            $this->error_message($e->getMessage());
            $this->error_code($e->getErrorCode());
            //$this->returnData();
            return false;
        }
    }

    //�ṩ����Ŀ���,������������ڿͻ����������
    public function indexAction() {
        $this->returnData();
    }

    /*
     * ��ȡ�ӿ��б�
     * ����:
     *      $path   �ӿ�Ŀ¼
     *      $domain �ӿ���
     *      $name   �ӿ���Ŀ����
     *
     */
    public function getApiList($path,$domain,$api_name="�ӿ��ĵ�",$replace='') {
        if(!SEEDIT_DEBUG)
            return false;

        $files = self::getAllFiles($path);
        foreach($files as $file) {
            $contents = file_get_contents($file,false,NULL,-1,200);
            if(preg_match("#controllers/([^\.]*)\.php#",$file,$matchs)) {
                $url = strtolower($matchs[1]);
                $replace && $url = str_replace('/',$replace,$url);
                $url = $domain .'/'. $url . '.doc';
                if(preg_match("/#(.*)/",$contents,$matchs)) {
                    $name = $matchs[1];
                    $api_list[] = array(
                        "url"  => $url,
                        "name" => $name
                    );
                }
            }
        }
        $doc = "\n#".$api_name."\n\n";
        foreach($api_list as $item) {
            $doc .= "##".$item['name']."\n\n";
            $doc .= "[".$item['url']."](".$item['url'].")\n\n";
        }
        $html = "<!DOCTYPE html>
                <html>
                <head>
                <meta charset='utf-8'>
                <base target=\"_blank\">
                <link href='".STATIC_SEEDIT_COM."/min/f=/markdown-js/lib/markdown.css' rel='stylesheet'>
                </head>
                <body>
                <textarea id='text-input' disabled='false' hidden> $doc </textarea>
                <div id='preview'>
                </div>
                <script src='".STATIC_SEEDIT_COM."/min/f=/markdown-js/lib/markdown.js'></script>
                <script>
                function Editor(input, preview) {
                    this.update = function () {
                      preview.innerHTML = markdown.toHTML(input.value);
                    }
                    input.editor = this;
                    this.update();
                }
                var $ = function (id) { return document.getElementById(id); };
                new Editor($('text-input'), $('preview'));
                </script>
                </body>
                </html> ";
        echo $html;
        exit;
    }

    public function verifyMobile() {
        switch($this->_method) {
            case 'GET'   : 
            case 'DELETE':
                        return true;
            case 'POST'  :
            case 'PUT'   :
                        if(empty($_GET['seedit_signature'])){
                            $this->set_error("PARAM_ERROR", "seedit_signature��������!");
                        }
                        if(!$this->signature($this->_contents,$_GET['seedit_signature'])) {
                            $this->set_error('VERIFY_ERROR');
                        }
                        break;
            default : 
                $this->set_error('METHOD_ERROR');
        }
    }
    //��ʾ�ɰ汾��������
    public function verifyMobile_return_oldversion() {
        switch($this->_method) {
            case 'GET'   : 
            case 'DELETE':
                        return true;
            case 'POST'  :
            case 'PUT'   :
                        if(empty($_GET['seedit_signature'])){
                            $this->set_error("PARAM_ERROR", "seedit_signature��������!");
                        }
                        if(!$this->signature($this->_contents,$_GET['seedit_signature'])) {
                            $this->set_error('OLDVERSION_ERROR');
                        }
                        break;
            default : 
                $this->set_error('METHOD_ERROR');
        }
    }
    //��Ϣ��֤
    private function signature($contents,$seedit_signature) {
        $method = 'aes128';
        $pass = 'uOMBf9tOzPVIOSa5';
        $iv   = 'u1ONgBvywXDHN4zF';

        $seedit_signature = strtr($seedit_signature,' ','+');
        $decrypt = openssl_decrypt($seedit_signature, $method, $pass,0,$iv);
        if(!$decrypt)
            return false;


        $contents_md5 = md5($contents);
        $decrypt_md5 = substr($decrypt,0,32);
        if($contents_md5 !== $decrypt_md5) {
            return false;
        }

        $decrypt_time = substr($decrypt,33,10);
        $decrypt_imei = substr($decrypt,44);

        $cache = new Seedit_Cache("Seedit_Restful");
        $set_cache = true;
        $cache_value = $cache->get($decrypt_imei);
        if($cache_value) {
            $last_time = substr($cache_value,0,10);
            $check_time = (int)substr($cache_value,11);
            if($last_time === $decrypt_time ) {
                if($check_time < time() + 300) {
                    return false;
                }
            } else {
                $set_cache = false;
            }
        }

        if($set_cache)  {
            $cache_value = $decrypt_time.'_'.time();
            $cache->set($decrypt_imei,$cache_value,1800);
        }

        return true;
    }
    }