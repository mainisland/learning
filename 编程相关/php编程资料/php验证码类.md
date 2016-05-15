#php 验证码类

根据token值去获取验证码，用户并通过token + code值去验证图片验证码的是否正确；

    <?php
    /*
    * Seedit_Captcha
    *
    * 验证码类库
    *
    *
    */
    class Seedit_Captcha {
        //验证码图片的宽度
        private $width;
        //验证码图片的高度
        private $height;
        //验证码的个数
        private $codenum;
        //验证码
        private $code;
        //验证码token值
        private $token;
        //图片
        private $image;
        //memcache
        private $memcache;

    function __construct($width = 80,$height=20,$codenum =4) {
        $this->width = $width;
        $this->height = $height;
        $this->codenum = $codenum;
        //初始化的时候就把memcache加载进来
        $this->memcache = new Seedit_Cache("SEEDIT_CAPTCHA");
    }

    //随机生成验证码,并生成一个token值
    public function createCode() {
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        for($i=0;$i<$this->codenum;$i++) {
            $this->code .= $str{rand(0,strlen($str)-1)};
        }
        $this->token = uniqid('seedit_code',true);
        $this->_saveCode($this->token,$this->code);
        return $this->token;
    }

    //输出图像的方法
    public function showImage($token) {
        $this->createImage();
        $this->code = $this->memcache->get($token);
        $this->outputText();
        $this->setDisturbColor();
        $this->outputImage();
    }

    //获取token值
    public function getToken() {
        return $this->token;
    }

    //检验验证码是否正确
    public function checkCode($token,$str) {
        if(empty($str)) {
            return false;
        }
        if(strtolower($this->memcache->get($token)) === strtolower($str)) {
            return true;
        }
        return false;
    }

    //根据token重新生产一个code
    public function refreshCode($token){
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        for($i=0;$i<$this->codenum;$i++) {
            $this->code .= $str{rand(0,strlen($str)-1)};
        }
        $this->token = $token;
        $this->_saveCode($this->token,$this->code);
        return $this->token;
    }

    //保存token和code到memcache里面
    private function _saveCode($key,$value) {
        $expires = 10*60;
        return $this->memcache->set($key,$value,$expires);
    }

    //创建图像
    private function createImage() {
        $this->image = imagecreate($this->width, $this->height);
        $background = imagecolorallocate($this->image, 255, 255, 255);
        $border = imagecolorallocate($this->image, 0, 0, 0);
        imagerectangle($this->image, 0, 0, $this->width-1, $this->height-1, $border);
    }


    //设置干扰元素
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

    //随机颜色、位置、字符
    private function outputText() {
        for($i=0;$i<$this->codenum;$i++) {
            $fontcolor = imagecolorallocate($this->image, rand(0,128), rand(0,128), rand(0,128));
            $fontsize = rand(floor($this->height / 5), floor($this->height / 3));
            $x = floor($this->width/$this->codenum)*$i+10;
            $y = rand(3,$this->height-20);
            imagechar($this->image,$fontsize,$x,$y,$this->code{$i},$fontcolor);
        }
    }

    //输出图像(只提供一种格式)
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
            die("暂时不允许创建图像！");
        }
        imagedestroy($this->image);
    }

    }
    
    //===============测试验证码方法start=====================
    public function indexAction() {
        if($this->getRequest()->isPost()) {
            Yaf_Dispatcher::getInstance()->disableView();
            $code = $_POST['code'];
            $token = $_COOKIE['code_token'];    //演示将验证码放入cookie中
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