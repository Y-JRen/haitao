<?php
class Custom_Model_Check
{
    /**
     * 是否为Email
     *
     * @param    string    $string
     * @return   bool
     */
    public static function isEmail($string)
    {
    	return preg_match('/^[a-z’0-9]+([._-][a-z’0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$/', $string);
    }
    
    /**
     * 是否为手机号码
     *
     * @param    string    $string
     * @return   bool
     */
    public static function isMobile($string)
    {
    	return preg_match('/^1[30|31|32|33|34|35|36|37|38|39|50|51|52|57|58|59|88|87|82|83|55|56|86|85|45|53|89]\d{9}$/', $string);
    }
    
    /**
     * 是否为电话号码
     *
     * @param    string    $string
     * @return   bool
     */
    public static function isTel($string)
    {
    	return preg_match('/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/', $string);
    }
    /**
     * 是否是用户名
     * @param unknown_type $string
     */
    public static function isUserName($string)
    {
    	return preg_match('/^[A-Za-z0-9_\-\\.\\@\\u4e00-\\u9fa5]{3,30}$/', $string);
    }
    
    /**
     * 是否为QQ号码
     *
     * @param    string    $string
     * @return   bool
     */
    public static function isQq($string)
    {
    	return preg_match('/^\d{4,}$/', $string);
    }
    
    /**
     * 是否为邮政编码号码
     *
     * @param    string    $string
     * @return   bool
     */
    public static function isZip($string)
    {
    	return preg_match('/^\d{6}$/', $string);
    }
    
    /**
     * 是否为传真号码
     *
     * @param    string    $string
     * @return   bool
     */
    public static function isFax($string)
    {
    	return preg_match('/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/', $string);
    }


    /**
     * 检查id是否存在于数组中
     *
     * @param $id
     * @param $ids
     * @param $s
     */
     public static function check_in($id, $ids = '', $s = ',') {
        if(!$ids) return false;
        $ids = explode($s, $ids);
        return is_array($id) ? array_intersect($id, $ids) : in_array($id, $ids);
    }

	/**
	 * 是否身份证'格式'（15位或18位）
	 * 不能验证身份证号码是否有效
	 * 
	 * @param string $string
	 * 
	 * @return bool
	 */
	public static function isIdcard($string) {
		return preg_match('/(^\d{15}$)|(^\d{17}([0-9]|X)$)/i', $string);
	}
}