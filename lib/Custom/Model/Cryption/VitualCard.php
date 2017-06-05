<?php

class Custom_Model_Cryption_VitualCard
{
	/**
     * 认证字符串
     * 
     * @var string
     */
	private $_authCode = 245514582;
	
	/**
     * 卡号补空字符范围
     * 
     * @var string
     */
	private $_cCode = 'frtouitkgkmd';
	
	/**
     * 密码字符范围
     * 
     * @var string
     */
	private $_pCode = array(array('r', '8', 'z'), 
                            array('v', '3', 'f'), 
                            array('e', 'p', 'k'), 
                            array('5', 'j', 'j'), 
                            array('c', '2', 'c'), 
                            array('q', 'i', 'w'), 
                            array('t', 'h', 'h'), 
                            array('6', '6', 'u'), 
                            array('9', 'y', 'x'), 
                            array('4', 's', 'd'));
	
	/**
     * 生成数据加密串
     *
     * @param    int    $string
     * @return   array
     */
    public function encrypt($string)
    {
    	if ($string) {
    		$str = 'c';
    		$number = intval($string);
			$encodeSn = base_convert($number, 10, 26);
			$encodePwd = strval($this -> _authCode - $number);
			for ($i = 0; $i < strlen($encodePwd); $i++)
			{
				$pwd .= $this -> _pCode[$encodePwd[$i]][mt_rand(0, 2)];
			}
		    $str .= substr($pwd, 0, 2);
			for ($i = 0; $i < 7 - strlen($encodeSn); $i++)
			{
				$str .= $this -> _cCode[mt_rand(0, strlen($this -> _cCode) -1)];
			}
			$str .= $encodeSn;
			return array('sn' => $str, 'pwd' => substr($pwd, 2));
    	}
	}
	
	/**
     * 数据解密
     *
     * @param    string   $data
     * @return   boolean
     */
    public function decrypt($data)
    {
    	if ($data['sn'] && $data['pwd']) {
    		$sn = base_convert(substr($data['sn'], 3), 26, 10);
    		$pwd = substr($data['sn'], 1, 2) . $data['pwd'];
    		for ($i = 0; $i < strlen($pwd); $i++)
			{
				foreach ($this -> _pCode as $key => $code)
				{
					if (in_array($pwd[$i], $code)) {
						$number .= $key;
						break;
					}
				}
			}
			
			if ($sn + $number == $this -> _authCode) {
				return $sn;
			} else {
				return false;
			}
    	}
    }
}