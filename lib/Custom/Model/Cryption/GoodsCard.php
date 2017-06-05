<?php

class Custom_Model_Cryption_GoodsCard
{
	/**
     * 密码字符范围
     * 
     * @var string
     */
	private $_pCode = '1234567890';
	
	/**
     * 生成卡号密码
     *
     * @param    string    $number
     * @return   array
     */
    public function encrypt($number)
    {
	    $total = 0;
	    for ($i = 0; $i <= strlen($number); $i++) {
		    $single = ($i % 2 == 0) ? 1 * substr($number, $i, 1) : 2 * substr($number, $i, 1);
		    $single = ($single >= 10) ? substr($single, 0, 1) + substr($single, 1, 1) : $single;
		    $total += $single;
	    }
	    $check = (($total % 10) == 0) ? 0 : 10 - ($total % 10);
	    for ($i = 0; $i < 10; $i++)
		{
			$pwd .= $this -> _pCode[mt_rand(0, 9)];
		}
	    return array('sn' => $number . $check, 'pwd' => $pwd);
	}
	
	/**
     * 卡号校验
     *
     * @param    string   $number
     * @return   boolean
     */
    public function decrypt($number)
    {
    	$sum = 0;
    	$odd = strlen($number) % 2;
    	
    	if (!is_numeric($number)) {
    		return false;
    	}
    	for ($i = 0; $i < strlen($number); $i++)
    	{
            $sum += $odd ? $number[$i] : (($number[$i] * 2 > 9) ? $number[$i] * 2 - 9 : $number[$i] * 2);
            $odd = !$odd;
        }
        return ($sum % 10 == 0) ? true : false;
    }
}