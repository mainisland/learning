#php ��֤����

����tokenֵȥ��ȡ��֤�룬�û���ͨ��token + codeֵȥ��֤ͼƬ��֤����Ƿ���ȷ��

    <?php
    /*
    * Seedit_Captcha
    *
    * ��֤�����
    *
    *
    */
    class Seedit_Captcha {
        //��֤��ͼƬ�Ŀ��
        private $width;
        //��֤��ͼƬ�ĸ߶�
        private $height;
        //��֤��ĸ���
        private $codenum;
        //��֤��
        private $code;
        //��֤��tokenֵ
        private $token;
        //ͼƬ
        private $image;
        //memcache
        private $memcache;

    function __construct($width = 80,$height=20,$codenum =4) {
        $this->width = $width;
        $this->height = $height;
        $this->codenum = $codenum;
        //��ʼ����ʱ��Ͱ�memcache���ؽ���
        $this->memcache = new Seedit_Cache("SEEDIT_CAPTCHA");
    }

    //���������֤��,������һ��tokenֵ
    public function createCode() {
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        for($i=0;$i<$this->codenum;$i++) {
            $this->code .= $str{rand(0,strlen($str)-1)};
        }
        $this->token = uniqid('seedit_code',true);
        $this->_saveCode($this->token,$this->code);
        return $this->token;
    }

    //���ͼ��ķ���
    public function showImage($token) {
        $this->createImage();
        $this->code = $this->memcache->get($token);
        $this->outputText();
        $this->setDisturbColor();
        $this->outputImage();
    }

    //��ȡtokenֵ
    public function getToken() {
        return $this->token;
    }

    //������֤���Ƿ���ȷ
    public function checkCode($token,$str) {
        if(empty($str)) {
            return false;
        }
        if(strtolower($this->memcache->get($token)) === strtolower($str)) {
            return true;
        }
        return false;
    }

    //����token��������һ��code
    public function refreshCode($token){
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        for($i=0;$i<$this->codenum;$i++) {
            $this->code .= $str{rand(0,strlen($str)-1)};
        }
        $this->token = $token;
        $this->_saveCode($this->token,$this->code);
        return $this->token;
    }

    //����token��code��memcache����
    private function _saveCode($key,$value) {
        $expires = 10*60;
        return $this->memcache->set($key,$value,$expires);
    }

    //����ͼ��
    private function createImage() {
        $this->image = imagecreate($this->width, $this->height);
        $background = imagecolorallocate($this->image, 255, 255, 255);
        $border = imagecolorallocate($this->image, 0, 0, 0);
        imagerectangle($this->image, 0, 0, $this->width-1, $this->height-1, $border);
    }


    //���ø���Ԫ��
    private function setDisturbColor() {
        $number = floor(($this->width*$this->height)/15);
        $disturbnum = ($number > 250) ? 250:$number;
        for($i=0;$i<$disturbnum;$i++) {
            $color = imagecolorallocate($this->image, rand(0,255), rand(0,255), rand(0,255));
            imagesetpixel($this->image, $this->width-2, rand(1,$this->height-2), $color);
        }
        for($i=0;$i<8;$i++) {
            $color = imagecolorallocate($this->image, rand(128,255), rand(125,255), rand(100,255));
            imagearc($this->image, rand(-10,$this->width), rand(0,$this->height), rand(30,300), rand(20,200), 50, 30, $color);
        }
    }

    //�����ɫ��λ�á��ַ�
    private function outputText() {
        for($i=0;$i<$this->codenum;$i++) {
            $fontcolor = imagecolorallocate($this->image, rand(0,128), rand(0,128), rand(0,128));
            $fontsize = rand(floor($this->height / 5), floor($this->height / 3));
            $x = floor($this->width/$this->codenum)*$i+10;
            $y = rand(3,$this->height-20);
            imagechar($this->image,$fontsize,$x,$y,$this->code{$i},$fontcolor);
        }
    }

    //���ͼ��(ֻ�ṩһ�ָ�ʽ)
    private function outputImage() {
        if (imagetypes() & IMG_JPG) {
            header('Content-type:image/jpeg');
            imagejpeg($this->image);
        } elseif (imagetypes() & IMG_GIF) {
            header('Content-type: image/gif');
            imagegif($this->image);
        } elseif (imagetype() & IMG_PNG) {
            header('Content-type: image/png');
            imagepng($this->image);
        } else {
            die("��ʱ��������ͼ��");
        }
        imagedestroy($this->image);
    }

    }
    
    //===============������֤�뷽��start=====================
    public function indexAction() {
        if($this->getRequest()->isPost()) {
            Yaf_Dispatcher::getInstance()->disableView();
            $code = $_POST['code'];
            $token = $_COOKIE['code_token'];    //��ʾ����֤�����cookie��
            $im = new Seedit_Captcha();
            $isfalse = $im->checkCode($token,$code);
            $array = array();
            if($isfalse) {
                $array = array(
                    'code' => $code,
                    'token' => $token,
                    'isfalse' => $isfalse
                );
            }
            echo json_encode($array);
        }
    }

    public function imageAction() {
        Yaf_Dispatcher::getInstance()->disableView();
        $image = new Seedit_Captcha(100,30,4);
        $token = $image->createCode();
        setcookie('code_token',$token);
        $image->showImage($token);
    }