<?php
class Custom_Model_AuthImage
{
    /**
     * 图片宽度
     * 
     * @var    int    $width
     */
    private $_width = 90;

    /**
     * 图片高度
     * 
     * @var    int    $height
     */
    private $_height = 25;

    private $_space = 'imgcode';
    
    /**
     * 图片
     * 
     * @var    mixed
     */
    public $image = null;

    /**
     * 对象初始化
     *
     * @param    int    $width
     * @param    int    $height
     * @return   void
     */
    public function __construct($space, $width = null, $height = null)
    {
        $this -> _space = $space;
        $this->_image  = null;
    	if($width > 0) $this -> _width    = $width;
    	if($height > 0) $this -> _height    = $height;
    }

    /**
     * 验证字符串是否正确
     *
     * @param    string    $word    code
     * @return   bool
     */
    public function checkCode($word)
    {
        $word = strtoupper($word);
        $recorded = isset($_SESSION[$this -> _space]['code']) ? base64_decode($_SESSION[$this -> _space]['code']) : md5($word);
        $code = '';
        for ($i = 0; $i < strlen($word); $i++ ) {
            if (strstr('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', $word[$i])) {
                $code .= $word[$i];
            }
        }
        $given = $this->encryptsCode($code);
        
        return (bool)($given === $recorded);
    }
    /**
     * 创建并输出图片
     *
     * @param    void
     * @return   void
     */
    public function createImage()
    {
        $word = $this -> randomCode();
        // 记录字符串
        $_SESSION[$this -> _space]['code'] = base64_encode($this -> encryptsCode($word));
        $this -> image = ImageCreate($this -> _width, $this -> _height);
        ImageColorAllocate($this -> image, 220, 220, 220);
        // 在图片上添加扰乱元素
        $this -> disturbPixel();
        // 在图片上添加字符串
        $this -> drawCode($word);   
        ob_clean();  //关键代码，防止出现'图像因其本身有错无法显示'的问题。
        Header("Content-type: image/PNG");     
        ImagePng($this -> image);
        ImageDestroy($this -> image);
        
    }
    
    public function createCode()
    {
    	$word = Custom_Model_String::rand_string(6,1);
    	$_SESSION[$this -> _space]['code'] = base64_encode($this -> encryptsCode($word));
    	return  $word;
    }
    
    /**
     * 创建扰乱元素
     *
     * @param    void
     * @return   void
     */
    private function disturbPixel()
    {
    	for ($i = 1; $i <= 50; $i++) {
        	$disturbColor = imagecolorallocate ($this -> image, rand(0,255), rand(0,255), rand(0,255));
            imagesetpixel($this -> image, rand(2,128), rand(2,15), $disturbColor);
        }
		for($i=0;$i<3;$i++) {
		   imageline($this -> image,rand(0,20),rand(0,25),rand(95,100),rand(30,60),$disturbColor);
		}
    }
    
    /**
     * 在图片上添加字符串
     *
     * @param    string    $word
     * @return   void
     */
    private function drawCode($word)
    {
		$font= SYSROOT . '/data/fonts/bboron.ttf';
    	for ($i=0;$i<strlen($word);$i++){
        	$color = imagecolorallocate ($this -> image, rand(0,255), rand(0,128), rand(0,255));
            $x = floor($this -> _width/strlen($word))*$i;
            $y = rand(0,$this -> _height-15);
			imagettftext ($this -> image,14,0, $x, $y+15, $color,$font,$word[$i]);
        }
    }

    /**
     * 编码字符串
     *
     * @param    string    $word
     * @return   string
     */
    private function encryptsCode($word)
    {
        return substr(md5($word), 1, 10);
    }

    /**
     * 创建字符串
     *
     * @param    int    $length    the code length
     * @return   string
     */
    private function randomCode($length = 5)
    {
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        for ($i = 0, $count = strlen($chars); $i < $count; $i++)
        {
            $arr[$i] = $chars[$i];
        }
        mt_srand((double) microtime() * 1000000);
        shuffle($arr);
        return substr(implode('', $arr), 5, $length);
    }
}