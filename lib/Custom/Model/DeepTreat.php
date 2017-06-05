<?php

class Custom_Model_DeepTreat
{
    /**
     * 递归处理数据
     *
     * @param    mixed    $data         需处理的数据
     * @param    string   $function     处理函数
     * @return   mixed
     */
    public static function filterArray($data, $function)
    {
    	if (!$data) {
    		return;
    	}
    	if (is_string($data)) {
    		$result = $function($data);
    	} else {
            while(list($k,$v) = each($data)){
                if (is_array($v) || is_object($v)){
                    $result[$k] = self::filterArray($v, $function);
                } else {
                    $result[$k] = $function($v);
                }
            }
    	}
        return $result;
    }
}