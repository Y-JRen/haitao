<?php
class Custom_Model_Cryption_EncryptCode{
	/**
	 * 认证字符串
	 *
	 * @var string
	 */
	private $_authCode = '800613';
	
	
	/**
	 * 数据加密
	 *
	 * @param string $data      	
	 * @return string
	 */
	public function encrypt($txt) {
		if ($txt) {			
			srand((double)microtime() * 1000000);
			$encrypt_key = md5(rand(0, 32000));
			$ctr = 0;
			$tmp = '';
			for($i = 0;$i < strlen($txt); $i++)
			{
			$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
			$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
			}
			return base64_encode($this->codeKey($tmp));
		   	
		}
	}
	
	/**
	 * 数据解密
	 *
	 * @param string $data        	
	 * @return array 会员信息
	 */
	public function decrypt($txt) {
		if ($txt) {
			$txt = $this->codeKey(base64_decode($txt));
			$tmp = '';
			for ($i = 0;$i < strlen($txt); $i++)
			{
			$md5 = $txt[$i];
			$tmp .= $txt[++$i] ^ $md5;
			}
			return $tmp;
		}
	}
	
	private function codeKey($txt)
	{
			$encrypt_key = md5($this->_authCode);
			$ctr = 0;
			$tmp = '';
			for($i = 0; $i < strlen($txt); $i++)
			{
				$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
				$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
			}
			return $tmp;
	}
}