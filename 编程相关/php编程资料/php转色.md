#php处理图片

今天有个需求用php对图片进行反色，和转灰，之前不知道可不可行，后来看到了imagefilter（）函数，用来转灰绰绰有余，好强大；

imagefilter($im, IMG_FILTER_GRAYSCALE)

当然也有人在css里面设置变灰

    <style type="text/css">
    img { 
    -webkit-filter: grayscale(1);/* Webkit */ 
    filter:gray;/* IE6-9 */ 
    filter: grayscale(1);/* W3C */ 
    } 
    </style>


php转色代码：

    <?php
    /**
    * 主要用于图片的处理函数
    */
    //图片的反色功能
    function color($url) {
    //获取图片的信息
    list($width, $height, $type, $attr)= getimagesize($url);
    $imagetype = strtolower(image_type_to_extension($type,false));
    $fun = 'imagecreatefrom'.($imagetype == 'jpg'?'jpeg':$imagetype);
    $img = $fun($url);
    for ($y=0; $y < $height; $y++) { 
        for ($x=0; $x <$width; $x++) { 
        //获取颜色的所以值
        $index = imagecolorat($img, $x, $y);
        //获取颜色的数组
        $color = imagecolorsforindex($img, $index);
        //颜色值的反转
        $red = 256 - $color['red'];
        $green = 256 - $color['green'];
        $blue = 256 - $color['blue'];
        $hex = imagecolorallocate($img, $red, $green, $blue);
        //给每一个像素分配颜色值
        imagesetpixel($img, $x, $y, $hex);
    }
    }
    //输出图片
    switch ($imagetype) {
        case 'gif':
        imagegif($img);
        break;
    case 'jpeg':
        imagejpeg($img);
        break;
    case 'png':
        imagepng($img);
        break;
    default:
        break;
    }
	}