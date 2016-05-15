#phpͼƬ�ϴ���

    <?php
    //����config_appȫ�ֱ���
    include ini_get("yaf.library") ."/../config/application/global.php";

    include 'Image.php';
    include 'Seedit_Aes.php';

    //��ȡ���ӹ����Ĳ���
    $format = isset($_GET['__format'])?$_GET['__format']:"json";
    switch($format) {
        case 'jsonp':
        case 'json' :
            header("Content-Type:application/json;charset=UTF-8");
            break;
        case 'html' :
            header("Content-Type:text/html;charset=UTF-8");
            break;
        case 'iframe' :
            header("Content-Type:text/html;charset=UTF-8");
            break;
        default:
            header("Content-Type:application/json;charset=UTF-8");
            break;
    }
    if(!isset($_FILES['file']) || empty($_FILES['file'])) {
        $result = array(
            "error_code"    => 102,
            "error_message" => "�ϴ�����Ϊ��"
        );
        //die(json_encode($result));
        returnData($format,$result);
        exit;
    }

    //�ļ���Ϣ
    $pic  = $_FILES['file'];
    //������
    $class = $_POST['class'];

    //ǿ���ļ���
    $forcename = isset($_POST['forcename']) ? $_POST['forcename'] : '';
    //��Կ
    $authkey  = isset($_POST['authkey']) ? $_POST['authkey'] : '';

    //����ķ���
    $class_all = array(
        "app",        //������APP ͼƬ����
        "cms",        //cmsͼƬ
        "app_avatar", //�������ͷ��
        "crazy_app",  //�������ͼƬר��
        "crazy_app/bc",  //�������B��ͼƬ
        "user",       //�������û��ϴ�
        "tmp",         //��ʱ�ļ�
        "yyk"           //ҽԺ��ͼƬ
    );

    //����ϴ�����
    if(!in_array($class, $class_all)) {
        $result = array(
            "error_code"    => 101,
            "error_message" => "�ϴ�������Ч"
        );
        //die(json_encode($result));
        returnData($format,$result);
        exit;
    }

    //�����Կ
    if($forcename !='' && defined('IMAGE_APP_AUTHKEY')) {
        if($_POST['authkey'] != md5(IMAGE_APP_AUTHKEY.$forcename)) {
            $result = array(
                "error_code"    => 102,
                "error_message" => "��Կ����ȷ"
            );
            //die(json_encode($result));
            returnData($format,$result);
            exit;
        }
    }

    $image = new Image();
    $image->max_size = 8192;
    $image->init($pic, $class, $forcename);

    if(empty($image->file)) {
        //�ϴ�ʧ��
        $result = array(
            "error_code"    => 100,
            "error_message" => "�ļ��ϴ�ʧ��"
        );
        //die(json_encode($result));
        returnData($format,$result);
        exit;
    }

    //����ͼƬ
    $image->save();
    $image_url = IMAGE_SEEDIT_COM.substr($image->file['local_target'],9);


    //���ûص�
    if(isset($_POST['callback'])) {
        $callback = Seedit_Aes::decode($_POST['callback'],IMAGE_APP_AUTHKEY);
        if($callback) {
            $callback = json_decode($callback,true);
            if(isset($callback['uri'])) {
                $callback['params']['image_url'] = $image_url;

                $gmclient= new GearmanClient();
                $gmclient->addServer(CONFIG_GEARMAN_SERVER,CONFIG_GEARMAN_PORT);
                $data = array(
                    'Name' => 'call_restful',
                    'Args'  => array(
                        $callback['uri'],
                        http_build_query($callback['params']),
                        'POST'
                    )
                );
                $job_handle = $gmclient->doBackground("execphp", json_encode($data));
            }
        }
    }

    //�ϴγɹ�
    $result = array(
        "error_code" => 0,
        "data"       => array(
            "url" => $image_url
        )
    );
    //die(json_encode($result));
    returnData($format,$result);
    exit;


    //����ֵ����
    function returnData($type='json',$result) {
        switch($type) {
            case 'jsonp':
                echo "callback"."(".json_encode($data).")";
                break;
            case 'json' :
                echo json_encode($result);
                break;
            case 'html' :
                if(is_array($result)) {
                    print_r($result);
                } else {
                    echo $result;
                }
                break;
            case 'iframe' :
                echo "<script>document.domain = '", SEEDIT_COM ,"';</script>";
                echo "<textarea>", json_encode($result) , "</textarea>";
                break;
            default:
                echo json_encode($result);
                break;
        }
    }

    <?php
    /**
     * image.class.php
     *
     * ͼƬ�ϴ���
     *
     */
    class Image
    {
        /**
         * �ļ���Ϣ
         */
        var $file = array();
        /**
         * ����Ŀ¼
         */
        var $dir = 'public';
        /**
         * �������
         */
        var $error_code = 0;
        /**
         * �ļ��ϴ����KB
         */
        var $max_size = 8192;

        function Image()
        {

        }

        /**
         * �����ϴ��ļ�
         * @param array $file �ϴ����ļ�
         * @param string $dir �����Ŀ¼
         * @return bool
         */
    function init($file, $dir = 'tmp', $forcename = '')
    {
        if(!is_array($file) || empty($file) || !$this->isUploadFile($file['tmp_name']) || trim($file['name']) == '' || $file['size'] == 0) {
            $this->file = array();
            $this->error_code = -1;
            return false;
        } else {
            $file['size']       = intval($file['size']);
            $file['name']       =  trim($file['name']);
            $file['thumb']      = '';
            $file['ext']        = $this->fileExt($file['name']);
            $file['name']       =  htmlspecialchars($file['name'], ENT_QUOTES);
            $file['is_image']   = $this->isImageExt($file['ext']);
            $file['is_convert'] = false;

            $info = $this->getImageInfo($file['tmp_name']);
            if($info['type'] != 'jpg' && $info['type'] != 'jpeg') {
                $file['is_convert'] = true;
            }

            $file['file_dir']     = $this->getTargetDir($dir, $forcename);
            $file['prefix']       = $this->get_target_filename($dir, $forcename);
            $file['target']       = $file['file_dir'].'/'.$file['prefix'].'.jpg';
            $file['local_target'] = $file['target'];
            $this->file = $file;
            $this->error_code = 0;
            return true;
        }
    }

    /**
     * �����ļ�
     * @return bool
     */
    function save()
    {
        if(empty($this->file) || empty($this->file['tmp_name']))
            $this->error_code = -101;
        elseif(!$this->file['is_image'])
            $this->error_code = -102;
        elseif(!$this->saveFile($this->file['tmp_name'], $this->file['local_target'],$this->file['is_convert']))
            $this->error_code = -103;
        elseif($this->file['is_image'] && (!$this->file['image_info'] = $this->getImageInfo($this->file['local_target'], true)))
        {
            $this->error_code = -104;
            @unlink($this->file['local_target']);
        }
        else
        {
            $this->error_code = 0;
            return true;
        }
        return false;
    }

    /**
     * ��ȡ�������
     * @return number
     */
    function error()
    {
        return $this->error_code;
    }

    /**
     * ��ȡ�ļ���չ��
     * @return string
     */
    function fileExt($file_name)
    {
        return addslashes(strtolower(substr(strrchr($file_name, '.'), 1, 10)));
    }

    /**
     * ������չ���ж��ļ��Ƿ�Ϊͼ��
     * @param string $ext ��չ��
     * @return bool
     */
    function isImageExt($ext)
    {
        static $img_ext  = array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'giff');
        return in_array($ext, $img_ext) ? 1 : 0;
    }

    /**
     * ��ȡͼ����Ϣ
     * @param string $target �ļ�·��
     * @return mixed
     */
    function getImageInfo($target)
    {
        static $infos = array();
        if(!isset($infos[$target]))
        {
            $infos[$target] = false;
            $ext = Image::fileExt($target);
            $is_image = Image::isImageExt($ext);

            if(!$is_image && $ext != 'tmp')
                return false;
            elseif(!is_readable($target))
                return false;
            elseif($image_info = @getimagesize($target))
            {
                $file_size = floatval(@filesize($target));
                $file_size = $file_size / 1024;
                if($file_size > $this->max_size)
                    return false;

                list($width, $height, $type) = !empty($image_info) ? $image_info : array('', '', '');
                if($is_image && !in_array($type, array(1,2,3,6,13)))
                    return false;

                $image_info['type'] = strtolower(substr(image_type_to_extension($image_info[2]),1));
                $infos[$target] = $image_info;
                return $image_info;
            }
            else
                return false;
        }
        return $infos[$target];
    }

    /**
     * ��ȡ�Ƿ�����ϴ��ļ�
     * @param string $source �ļ�·��
     * @return bool
     */
    function isUploadFile($source)
    {
        return $source && ($source != 'none') && (is_uploaded_file($source) || is_uploaded_file(str_replace('\\\\', '\\', $source)));
    }

    /**
     * ��ȡ�����·��
     * @param string $dir ָ���ı���Ŀ¼
     * @return string
     */
    function getTargetDir($dir, $forcename = '')
    {
        if($dir == 'temp') {
            $dir = '../public/temp/'.date('Y/m/d/H');
        } elseif($forcename != '') {
            $forcedir = $this->formatforcename($forcename);
            $dir = '../public/'.$dir.'/'.$forcedir;
        } else {
            $dir = '../public/'.$dir.'/'.date('Y/m/d');
        }

        if(!is_dir($dir))
            mkdir($dir,0755,true);
        return $dir;
    }

    //��ȡ�����ļ�·�����ļ���
    function formatforcename($forcename, $type = 'path') {
        $result = '';
        $tmp = explode('/', $forcename);
        if(count($tmp) < 2) {
            $tmp = array(
                substr(md5($forcename), 0, 1),
                substr(md5($forcename), 1, 1),
                substr(md5($forcename), 2, 1),
                $forcename
            );
        }
        if($type == 'path') {
            unset($tmp[count($tmp)-1]);
            $result = implode($tmp, '/');
        } else {
            $result = $tmp[count($tmp)-1];
        }

        return $result;
    }

    //��ȡ�ļ���
    function get_target_filename($dir, $forcename) {
        if($forcename != '') {
            $filename = $this->formatforcename($forcename, 'name');
        } else {
            $filename = md5(microtime(true)).rand(100000,999999);
        }
        return $filename;
    }

    /**
     * �����ļ�
     * @param string $source Դ�ļ�·��
     * @param string $target Ŀ¼�ļ�·��
     * @return bool
     */
    private function saveFile($source, $target,$is_convert = false)
    {
        if(!Image::isUploadFile($source))
            $succeed = false;
        elseif($is_convert && $this->convertType($source,$target))
            $succeed = true;
        elseif(@copy($source, $target))
            $succeed = true;
        elseif(function_exists('move_uploaded_file') && @move_uploaded_file($source, $target))
            $succeed = true;
        elseif (@is_readable($source) && (@$fp_s = fopen($source, 'rb')) && (@$fp_t = fopen($target, 'wb')))
        {
            while (!feof($fp_s))
            {
                $s = @fread($fp_s, 1024 * 512);
                @fwrite($fp_t, $s);
            }
            fclose($fp_s);
            fclose($fp_t);
            $succeed = true;
        }

        if($succeed)
        {
            $this->error_code = 0;
            @chmod($target, 0644);
            @unlink($source);
        }
        else
        {
            $this->error_code = 0;
        }

        return $succeed;
    }

    public function convertType($source,$target)
    {
        $info  = Image::getImageInfo($source);
        if($info !== false)
        {
            $width  = $info[0];
            $height = $info[1];
            $type = $info['type'];

            // ����ԭͼ
            $createFun = 'imagecreatefrom'.($type=='jpg'?'jpeg':$type);
            if(!function_exists($createFun))
                $createFun = 'imagecreatefromjpeg';

            $srcImg = $createFun($source);

            if('gif'==$type || 'png'==$type)
            {
                $background_color = imagecolorallocate($srcImg,0,0,0);
                imagecolortransparent($srcImg,$background_color);
            }

            // ��jpegͼ�����ø���ɨ��
            if('jpg'==$type || 'jpeg'==$type)
                imageinterlace($srcImg,1);

            // ����ͼƬ
            if(function_exists('imagefilter'))
                imagefilter($srcImg, IMG_FILTER_CONTRAST,-2);

            if($source != $target)
            {
                imagejpeg($srcImg,$target,IMAGE_CREATE_QUALITY);
                imagedestroy($srcImg);
            }
            else
            {
                $temp_path = substr($target,0,-4);
                $temp_path .= '_temp.'.substr($target,-3,3);
                imagejpeg($srcImg,$temp_path,IMAGE_CREATE_QUALITY);
                imagedestroy($srcImg);
                @unlink($target);
                $content = @file_get_contents($temp_path);
                @file_put_contents($target,$content);
                @unlink($temp_path);
            }
            return true;
        }
        return false;
    }

    public function thumb($image,$maxWidth=200,$maxHeight=50,$gen = 0,$interlace=true,$filepath = '')
    {
        $info  = Image::getImageInfo($image);
        if($info !== false)
        {
            $srcWidth  = $info[0];
            $srcHeight = $info[1];
            $type = $info['type'];

            $interlace  =  $interlace? 1:0;
            unset($info);

            if($maxWidth > 0 && $maxHeight > 0)
            {
                //$scale = min($maxWidth/$srcWidth, $maxHeight/$srcHeight); // �������ű���
                //��Ϊ�Կ������
                $scale = $maxWidth/$srcWidth;
            }
            elseif($maxWidth == 0)
                $scale = $maxHeight/$srcHeight;
            elseif($maxHeight == 0)
                $scale = $maxWidth/$srcWidth;

            if($scale >= 1)
            {
                // ����ԭͼ��С��������
                $width   =  $srcWidth;
                $height  =  $srcHeight;
            }
            else
            {
                // ����ͼ�ߴ�
                $width  = (int)($srcWidth*$scale);
                $height = (int)($srcHeight*$scale);
            }

            if($gen == 1)
            {
                $width = $maxWidth;
                $height = $maxHeight;
            }
            elseif($gen == 2)
            {
                $height = $height > $maxHeight ? $maxHeight : $height;
            }

            $paths = pathinfo($image);
            $ext = Image::fileExt($image);

            if(empty($filepath))
                $thumbname = str_replace('.'.$paths['extension'],'',$image).'_'.$maxWidth.'x'.$maxHeight.'.'.$ext;
            else
                $thumbname = $filepath;

            $thumburl = str_replace(FANWE_ROOT,'',$thumbname);

            // ����ԭͼ
            $createFun = 'imagecreatefrom'.($type=='jpg'?'jpeg':$type);
            if(!function_exists($createFun))
                $createFun = 'imagecreatefromjpeg';

            $srcImg = $createFun($image);

            //��������ͼ
            if($type!='gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($width, $height);
            else
                $thumbImg = imagecreate($width, $height);

            $x = 0;
            $y = 0;

            if($maxWidth > 0 && $maxHeight > 0)
            {
                if($gen == 1)
                {
                    $resize_ratio = $maxWidth/$maxHeight;
                    $src_ratio = $srcWidth/$srcHeight;
                    if($src_ratio >= $resize_ratio)
                    {
                        $x = ($srcWidth - ($resize_ratio * $srcHeight)) / 2;
                        $width = ($height * $srcWidth) / $srcHeight;
                    }
                    else
                    {
                        $y = ($srcHeight - ( (1 / $resize_ratio) * $srcWidth)) / 2;
                        $height = ($width * $srcHeight) / $srcWidth;
                    }
                }
            }

            // ����ͼƬ
            if(function_exists("imagecopyresampled"))
                imagecopyresampled($thumbImg, $srcImg, 0, 0, $x, $y, $width, $height, $srcWidth,$srcHeight);
            else
                imagecopyresized($thumbImg, $srcImg, 0, 0, $x, $y, $width, $height,  $srcWidth,$srcHeight);
            if('gif'==$type || 'png'==$type) {
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  ָ��һ����ɫ
                imagecolortransparent($thumbImg,$background_color);  //  ����Ϊ͸��ɫ����ע�͵������������ɫ��ͼ
            }

            // ��jpegͼ�����ø���ɨ��
            if('jpg'==$type || 'jpeg'==$type)
                imageinterlace($thumbImg,$interlace);

            // ����ͼƬ
            if(function_exists('imagefilter'))
                imagefilter($thumbImg, IMG_FILTER_CONTRAST,-1);

            // ����ͼƬ
            imagejpeg($thumbImg,$thumbname,IMAGE_CREATE_QUALITY);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return array('url'=>$thumburl,'path'=>$thumbname);
         }
         return false;
    }

    public function water($source,$water,$alpha=80,$position="4")
    {
        //����ļ��Ƿ����
        if(!file_exists($source)||!file_exists($water))
            return false;

        //ͼƬ��Ϣ
        $sInfo = Image::getImageInfo($source);
        $wInfo = Image::getImageInfo($water);

        //���ͼƬС��ˮӡͼƬ��������ͼƬ
        if($sInfo["0"] < $wInfo["0"] || $sInfo['1'] < $wInfo['1'])
            return false;

        //����ͼ��
        $sCreateFun="imagecreatefrom".$sInfo['type'];
        if(!function_exists($sCreateFun))
            $sCreateFun = 'imagecreatefromjpeg';
        $sImage=$sCreateFun($source);

        $wCreateFun="imagecreatefrom".$wInfo['type'];
        if(!function_exists($wCreateFun))
            $wCreateFun = 'imagecreatefromjpeg';
        $wImage=$wCreateFun($water);

        //�趨ͼ��Ļ�ɫģʽ
        imagealphablending($wImage, true);

        switch (intval($position))
        {
            case 0: break;
            //����
            case 1:
                $posY=0;
                $posX=0;
                //���ɻ��ͼ��
                imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo[0],$wInfo[1],$alpha);
                break;
            //����
            case 2:
                $posY=0;
                $posX=$sInfo[0]-$wInfo[0];
                //���ɻ��ͼ��
                imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo[0],$wInfo[1],$alpha);
                break;
            //����
            case 3:
                $posY=$sInfo[1]-$wInfo[1];
                $posX=0;
                //���ɻ��ͼ��
                imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo[0],$wInfo[1],$alpha);
                break;
            //����
            case 4:
                $posY=$sInfo[1]-$wInfo[1];
                $posX=$sInfo[0]-$wInfo[0];
                //���ɻ��ͼ��
                imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo[0],$wInfo[1],$alpha);
                break;
            //����
            case 5:
                $posY=$sInfo[1]/2-$wInfo[1]/2;
                $posX=$sInfo[0]/2-$wInfo[0]/2;
                //���ɻ��ͼ��
                imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo[0],$wInfo[1],$alpha);
                break;
        }

        //���û�и��������ļ�����Ĭ��Ϊԭͼ����
        @unlink($source);
        //����ͼ��
        imagejpeg($sImage,$source,IMAGE_CREATE_QUALITY);
        imagedestroy($sImage);
        imagedestroy($wImage);
    }
    }

    if(!function_exists('image_type_to_extension'))
    {
    function image_type_to_extension($imagetype)
    {
        if(empty($imagetype))
            return false;

        switch($imagetype)
        {
            case IMAGETYPE_GIF    : return '.gif';
            case IMAGETYPE_JPEG   : return '.jpeg';
            case IMAGETYPE_PNG    : return '.png';
            case IMAGETYPE_SWF    : return '.swf';
            case IMAGETYPE_PSD    : return '.psd';
            case IMAGETYPE_BMP    : return '.bmp';
            case IMAGETYPE_TIFF_II : return '.tiff';
            case IMAGETYPE_TIFF_MM : return '.tiff';
            case IMAGETYPE_JPC    : return '.jpc';
            case IMAGETYPE_JP2    : return '.jp2';
            case IMAGETYPE_JPX    : return '.jpf';
            case IMAGETYPE_JB2    : return '.jb2';
            case IMAGETYPE_SWC    : return '.swc';
            case IMAGETYPE_IFF    : return '.aiff';
            case IMAGETYPE_WBMP   : return '.wbmp';
            case IMAGETYPE_XBM    : return '.xbm';
            default               : return false;
        }
    }
    }
    ?>
