#php����ͼƬ

�����и�������php��ͼƬ���з�ɫ����ת�ң�֮ǰ��֪���ɲ����У�����������imagefilter��������������ת�Ҵ´����࣬��ǿ��

imagefilter($im, IMG_FILTER_GRAYSCALE)

��ȻҲ������css�������ñ��

    <style type="text/css">
    img { 
    -webkit-filter: grayscale(1);/* Webkit */ 
    filter:gray;/* IE6-9 */ 
    filter: grayscale(1);/* W3C */ 
    } 
    </style>


phpתɫ���룺

    <?php
    /**
    * ��Ҫ����ͼƬ�Ĵ�����
    */
    //ͼƬ�ķ�ɫ����
    function color($url) {
    //��ȡͼƬ����Ϣ
    list($width, $height, $type, $attr)= getimagesize($url);
    $imagetype = strtolower(image_type_to_extension($type,false));
    $fun = 'imagecreatefrom'.($imagetype == 'jpg'?'jpeg':$imagetype);
    $img = $fun($url);
    for ($y=0; $y < $height; $y++) { 
        for ($x=0; $x <$width; $x++) { 
        //��ȡ��ɫ������ֵ
        $index = imagecolorat($img, $x, $y);
        //��ȡ��ɫ������
        $color = imagecolorsforindex($img, $index);
        //��ɫֵ�ķ�ת
        $red = 256 - $color['red'];
        $green = 256 - $color['green'];
        $blue = 256 - $color['blue'];
        $hex = imagecolorallocate($img, $red, $green, $blue);
        //��ÿһ�����ط�����ɫֵ
        imagesetpixel($img, $x, $y, $hex);
    }
    }
    //���ͼƬ
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