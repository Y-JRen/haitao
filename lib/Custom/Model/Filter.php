<?php

class Custom_Model_Filter
{
    /**
     * 数据过滤
     *
     * @param    mixed    $data
     * @return   object   Zend_Filter
     */
    public static function filterArray($data, Zend_Filter $filterObject)
    {
    	if (is_string($data)) {
    		$result = $filterObject -> filter($data);
    	} else {
            while(list($k,$v) = each($data)){
                if (is_array($v) || is_object($v)){
                    $result[$k] = self::filterArray($v, $filterObject);
                } else {
                    $result[$k] = $filterObject -> filter($v);
                }
            }
    	}
        return $result;
    }
}