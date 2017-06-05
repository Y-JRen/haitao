<?php 

class Custom_Model_Image
{
	public $w_pct = 100;
	public $w_quality = 130;
	public $w_minwidth = 20;
	public $w_minheight = 20;
	public $thumb_enable = true;
	public $watermark_enable = false;
	public $interlace = 0;
	
    function __construct()
    {
    }
    
	function set($w_minwidth = 300, $w_minheight = 390, $w_quality = 100, $w_pct = 130)
	{
		$this->w_minwidth = $w_minwidth;
		$this->w_minheight = $w_minheight;
		$this->w_quality = $w_quality;
		$this->w_pct = $w_pct;
	}
	
    function info($img) 
	{
        $imageinfo = getimagesize($img);
        if($imageinfo === false) return false;
		$imagetype = strtolower(substr(image_type_to_extension($imageinfo[2]),1));
		$imagesize = filesize($img);
		$info = array(
				'width'=>$imageinfo[0],
				'height'=>$imageinfo[1],
				'type'=>$imagetype,
				'size'=>$imagesize,
				'mime'=>$imageinfo['mime']
				);
		return $info;
    }
    
    function thumb($image, $maxwidth, $maxheight, $filename = '') 
    {
		if(!$this->thumb_enable || !$this->check($image)) return false;
        $info  = $this->info($image);
        if($info === false) return false;
		$srcwidth  = $info['width'];
		$srcheight = $info['height'];
		$pathinfo = pathinfo($image);
		$type =  $pathinfo['extension'];
		if(!$type) $type = $info['type'];
		$type = strtolower($type);
		unset($info);
		$scale = min($maxwidth/$srcwidth, $maxheight/$srcheight);
		$createwidth = $width  = round($srcwidth*$scale,0);
		$createheight = $height = round($srcheight*$scale,0);
		$psrc_x = $psrc_y = 0;
		if($autocut)
		{
			if($maxwidth/$maxheight<$srcwidth/$srcheight && $maxheight>=$height)
			{
				$width = $maxheight/$height*$width;
				$height = $maxheight;
			}
			elseif($maxwidth/$maxheight>$srcwidth/$srcheight && $maxwidth>=$width)
			{
				$height = $maxwidth/$width*$height;
				$width = $maxwidth;
			}
			$createwidth = $maxwidth;
			$createheight = $maxheight;
		}
		$createfun = 'imagecreatefrom'.($type=='jpg' ? 'jpeg' : $type);
		$srcimg = $createfun($image);
		if($type != 'gif' && function_exists('imagecreatetruecolor'))
			$thumbimg = imagecreatetruecolor($createwidth, $createheight); 
		else
			$thumbimg = imagecreate($width, $height); 
		if(function_exists('imagecopyresampled'))
			imagecopyresampled($thumbimg, $srcimg, 0, 0, $psrc_x, $psrc_y, $width, $height, $srcwidth, $srcheight); 
		else
			imagecopyresized($thumbimg, $srcimg, 0, 0, $psrc_x, $psrc_y, $width, $height,  $srcwidth, $srcheight); 
		if($type=='gif' || $type=='png')
		{
			$background_color  =  imagecolorallocate($thumbimg,  0, 255, 0);  //  指派一个绿色  
			imagecolortransparent($thumbimg, $background_color);  //  设置为透明色，若注释掉该行则输出绿色的图 
		}
		if($type=='jpg' || $type=='jpeg') imageinterlace($thumbimg, $this->interlace);
		$imagefun = 'image'.($type=='jpg' ? 'jpeg' : $type);
		if(empty($filename)) $filename  = substr($image, 0, strrpos($image, '.')).'_'.$maxwidth.'_'.$maxheight.'.'.$type;
		if ($type=='jpg'){
		    $imagefun($thumbimg, $filename, $this->w_quality);
	    }else{
	    	$imagefun($thumbimg, $filename);
	    }
		imagedestroy($thumbimg);
		imagedestroy($srcimg);
		return $filename;
    }
    
	function watermark($source, $target = '', $w_pos = 0, $w_img = '', $w_text = '', $w_font = 5, $w_color = '#ff0000')
	{
		if(!$this->watermark_enable || !$this->check($source)) return false;
		if(!$target) $target = $source;
		$source_info = getimagesize($source);
		$source_w    = $source_info[0];
		$source_h    = $source_info[1];
		if($source_w < $this->w_minwidth || $source_h < $this->w_minheight) return false;
		switch($source_info[2])
		{
			case 1 :
				$source_img = imagecreatefromgif($source);
				break;
			case 2 :
				$source_img = imagecreatefromjpeg($source);
				break;
			case 3 :
				$source_img = imagecreatefrompng($source);
				break;
			default :
				return false;
		}
		if(!empty($w_img) && file_exists($w_img))
		{
			$ifwaterimage = 1;
			$water_info   = getimagesize($w_img);
			$width        = $water_info[0];
			$height       = $water_info[1];
			switch($water_info[2])
			{
				case 1 :
					$water_img = imagecreatefromgif($w_img);
					break;
				case 2 :
					$water_img = imagecreatefromjpeg($w_img);
					break;
				case 3 :
					$water_img = imagecreatefrompng($w_img);
					break;
				default :
					return;
			}
		}
		else
		{
			$ifwaterimage = 0;
			$temp = imagettfbbox(ceil($w_font*2.5), 0, 'fonts/cour.ttf', $w_text);//取得使用 truetype 字体的文本的范围
			$width = $temp[2] - $temp[6];
			$height = $temp[3] - $temp[7];
			unset($temp);
		}
		switch($w_pos)
		{
			case 0:
				$wx = rand(0,($source_w - $width));
				$wy = rand(0,($source_h - $height));
				break;
			case 1:
				$wx = 5;
				$wy = 5;
				break;
			case 2:
				$wx = ($source_w - $width) / 2;
				$wy = 0;
				break;
			case 3:
				$wx = $source_w - $width - 10;
				$wy = 10;
				break;
			case 4:
				$wx = 0;
				$wy = ($source_h - $height) / 2;
				break;
			case 5:
				$wx = ($source_w - $width) / 2;
				$wy = ($source_h - $height) / 2;
				break;
			case 6:
				$wx = ($source_w - $width) / 2;
				$wy = $source_h - $height;
				break;
			case 7:
				$wx = 0;
				$wy = $source_h - $height;
				break;
			case 8:
				$wx = ($source_w - $width) / 2;
				$wy = $source_h - $height;
				break;
			case 9:
				$wx = $source_w - $width;
				$wy = $source_h - $height;
				break;
			default:
				$wx = rand(0,($source_w - $width));
				$wy = rand(0,($source_h - $height));
				break;
		}
		if($ifwaterimage)
		{
			imagecopymerge($source_img, $water_img, $wx, $wy, 0, 0, $width, $height, $this->w_pct);
		}
		else
		{
			if(!empty($w_color) && (strlen($w_color)==7))
			{
				$r = hexdec(substr($w_color,1,2));
				$g = hexdec(substr($w_color,3,2));
				$b = hexdec(substr($w_color,5));
			}
			else
			{
				return;
			}
			imagestring($source_img,$w_font,$wx,$wy,$w_text,imagecolorallocate($source_img,$r,$g,$b));
		}
		switch($source_info[2])
		{
			case 1 :
				imagegif($source_img, $target);
				break;
			case 2 :
				imagejpeg($source_img, $target, $this->w_quality);
				break;
			case 3 :
				imagepng($source_img, $target);
				break;
			default :
				return;
		}
		if(isset($water_info))
		{
			unset($water_info);
		}
		if(isset($water_img))
		{
			imagedestroy($water_img);
		}
		unset($source_info);
		imagedestroy($source_img);
		return true;
	}
	
	function check($image)
	{
		return extension_loaded('gd') && preg_match("/\.(jpg|jpeg|gif|png)/", $image, $m) && file_exists($image) && function_exists('imagecreatefrom'.($m[1] == 'jpg' ? 'jpeg' : $m[1]));
	}
	
	/**
	 * 图片裁剪函数，支持指定定点裁剪和方位裁剪两种裁剪模式
	 * @param <string>  $src_file       原图片路径
	 * @param <int>     $new_width      裁剪后图片宽度（当宽度超过原图片宽度时，去原图片宽度）
	 * @param <int>     $new_height     裁剪后图片高度（当宽度超过原图片宽度时，去原图片高度）
	 * @param <int>     $type           裁剪方式，1-方位模式裁剪；0-定点模式裁剪。
	 * @param <int>     $pos            方位模式裁剪时的起始方位（当选定点模式裁剪时，此参数不起作用）
	 *                                      1为顶端居左，2为顶端居中，3为顶端居右；
	 *                                      4为中部居左，5为中部居中，6为中部居右；
	 *                                      7为底端居左，8为底端居中，9为底端居右；
	 * @param <int>     $start_x        起始位置X （当选定方位模式裁剪时，此参数不起作用）
	 * @param <int>     $start_y        起始位置Y（当选定方位模式裁剪时，此参数不起作用）
	 * @return <string>                 裁剪图片存储路径
	 */
	function thumbsub($src_file, $new_width, $new_height, $type = 1, $pos = 5, $start_x = 0, $start_y = 0) {
	    $pathinfo = pathinfo($src_file);
	    $dst_file = $pathinfo['dirname'] . '/' . $pathinfo['filename'] .'_'. $new_width . 'x' . $new_height . '.' . $pathinfo['extension'];
	    if (!file_exists($dst_file)) {
	        if ($new_width < 1 || $new_height < 1) {
	            echo "params width or height error !";
	            exit();
	        }
	        if (!file_exists($src_file)) {
	            echo $src_file . " is not exists !";
	            exit();
	        }
	        // 图像类型
	        $img_type = exif_imagetype($src_file);
	        $support_type = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
	        if (!in_array($img_type, $support_type, true)) {
	            echo "只支持jpg、png、gif格式图片裁剪";
	            exit();
	        }
	        /* 载入图像 */
	        switch ($img_type) {
	            case IMAGETYPE_JPEG :
	                $src_img = imagecreatefromjpeg($src_file);
	                break;
	            case IMAGETYPE_PNG :
	                $src_img = imagecreatefrompng($src_file);
	                break;
	            case IMAGETYPE_GIF :
	                $src_img = imagecreatefromgif($src_file);
	                break;
	            default:
	                echo "载入图像错误!";
	                exit();
	        }
	        /* 获取源图片的宽度和高度 */
	        $src_width = imagesx($src_img);
	        $src_height = imagesy($src_img);
	        /* 计算剪切图片的宽度和高度 */
	        $mid_width = ($src_width < $new_width) ? $src_width : $new_width;
	        $mid_height = ($src_height < $new_height) ? $src_height : $new_height;
	        /* 初始化源图片剪切裁剪的起始位置坐标 */
	        switch ($pos * $type) {
	            case 1://1为顶端居左
	                $start_x = 0;
	                $start_y = 0;
	                break;
	            case 2://2为顶端居中
	                $start_x = ($src_width - $mid_width) / 2;
	                $start_y = 0;
	                break;
	            case 3://3为顶端居右
	                $start_x = $src_width - $mid_width;
	                $start_y = 0;
	                break;
	            case 4://4为中部居左
	                $start_x = 0;
	                $start_y = ($src_height - $mid_height) / 2;
	                break;
	            case 5://5为中部居中
	                $start_x = ($src_width - $mid_width) / 2;
	                $start_y = ($src_height - $mid_height) / 2;
	                break;
	            case 6://6为中部居右
	                $start_x = $src_width - $mid_width;
	                $start_y = ($src_height - $mid_height) / 2;
	                break;
	            case 7://7为底端居左
	                $start_x = 0;
	                $start_y = $src_height - $mid_height;
	                break;
	            case 8://8为底端居中
	                $start_x = ($src_width - $mid_width) / 2;
	                $start_y = $src_height - $mid_height;
	                break;
	            case 9://9为底端居右
	                $start_x = $src_width - $mid_width;
	                $start_y = $src_height - $mid_height;
	                break;
	            default://随机
	                break;
	        }
	        // 为剪切图像创建背景画板
	        $mid_img = imagecreatetruecolor($mid_width, $mid_height);
	        //拷贝剪切的图像数据到画板，生成剪切图像
	        imagecopy($mid_img, $src_img, 0, 0, $start_x, $start_y, $mid_width, $mid_height);
	        // 为裁剪图像创建背景画板
	        $new_img = imagecreatetruecolor($new_width, $new_height);
	        //拷贝剪切图像到背景画板，并按比例裁剪
	        imagecopyresampled($new_img, $mid_img, 0, 0, 0, 0, $new_width, $new_height, $mid_width, $mid_height);
	        /* 按格式保存为图片 */
	        switch ($img_type) {
	            case IMAGETYPE_JPEG :
	                imagejpeg($new_img, $dst_file, 100);
	                break;
	            case IMAGETYPE_PNG :
	                imagepng($new_img, $dst_file, 9);
	                break;
	            case IMAGETYPE_GIF :
	                imagegif($new_img, $dst_file, 100);
	                break;
	            default:
	                break;
	        }
	    }
	    return ltrim($dst_file, '.');
	}
	
	/**
	 * download 程序
	 * @param unknown_type $url
	 * @param unknown_type $filename
	 * @return boolean|string
	 */
	function grabImage($url,$filename="") {
	    if($url==""):return false;endif;
	    if($filename=="") {
	        $ext=strrchr($url,".");
	        if($ext!=".gif" && $ext!=".jpg" && $ext!=".png" && $ext!=".jpeg"):return false;endif;
	        $filename=date("dMYHis").$ext;
	    }
	    ob_start();
	    readfile($url);
	    $img = ob_get_contents();
	    ob_end_clean();
	    $size = strlen($img);
	    $fp2=@fopen($filename, 'a');
	    fwrite($fp2,$img);
	    fclose($fp2);
	    //file_put_contents($filename, file_get_contents($url));
	    return $filename;
	}
}
