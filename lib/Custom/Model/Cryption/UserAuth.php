<?php

class Custom_Model_Cryption_UserAuth
{
	/**
     * 认证字符串
     * 
     * @var string
     */
	private $_authCode = '800613';
	
	/**
     * 加密密码模式
     * 
     * @var string
     */
	private $_cipher = MCRYPT_RIJNDAEL_128;
	
	/**
     * 数据加密
     *
     * @param    array   $data    会员信息
     * @return   string
     */
    public function encrypt($data)
    {
    	if ($data) {
    		//return str_replace('=', '!', str_replace('/', '|', base64_encode('utype=' . $data['utype'] . '&user_id=' . $data['user_id'] . '&user_name=' . $data['user_name'] . '&password=' . $data['password'])));
			$iv = mcrypt_create_iv(mcrypt_get_iv_size($this->_cipher,MCRYPT_MODE_CBC),MCRYPT_RAND);//此处有改动且必须加上这一句否则加密不成功.
    		$string = 'utype=' . $data['utype'] . '&user_id=' . $data['user_id'] . '&user_name=' . $data['user_name'] . '&password=' . $data['password'];
    		return bin2hex(mcrypt_encrypt($this -> _cipher, $this -> _authCode, $string, MCRYPT_MODE_CBC,$iv));
    	}
    }
    
    /**
     * 数据解密
     *
     * @param    string   $data
     * @return   array             会员信息
     */
    public function decrypt($string)
    {
    	if ($string) {
    		//$decrypted_string = str_replace('!', '=', str_replace('|', '/', base64_decode($string)));
			$iv = mcrypt_create_iv(mcrypt_get_iv_size($this->_cipher,MCRYPT_MODE_CBC),MCRYPT_RAND);//此处有改动且必须加上这一句否则解密不成功
    		$decrypted_string = @mcrypt_decrypt($this -> _cipher, $this -> _authCode, pack('H*', $string), MCRYPT_MODE_CBC);
    		@parse_str($decrypted_string, $result);
    		return $result;
    	}
    }
}