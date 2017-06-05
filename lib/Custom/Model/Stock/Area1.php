<?php
class Custom_Model_Stock_Area1 extends Custom_Model_Stock_Base
{
	public function __construct($logicArea, $stockConfig)
	{
		parent::__construct($logicArea, $stockConfig);
	}
	
	/**
     * 获得入库类型
     *
     * @param   int     $type
     * @return  string/array
     */
	public function getConfigInType($type = null)
	{
	    $keyArray = array(1,2,3,4,6,8,9,12,13,14,17,18,20);
	    return parent::getConfigInType($type, $keyArray);
	}
	
	/**
     * 获得出库类型
     *
     * @param   int     $type
     * @return  string/array
     */
	public function getConfigOutType($type = null)
	{
	    $keyArray = array(1,2,3,4,6,9,11,12,14,17,18,20);
	    return parent::getConfigOutType($type, $keyArray);
	}
	
	/**
     * 获得可手工新增入库类型
     *
     * @return  array
     */
	public function getConfigAddInType($type = null)
	{
	    $keyArray = array(2,8,17);
	    return parent::getConfigInType(null, $keyArray);
	}
	
	/**
     * 获得可手工新增出库类型
     *
     * @param   int     $type
     * @return  array
     */
	public function getConfigAddOutType($type = null) 
	{
	    if ($type == 2) {
	        $keyArray = array(10);
	    }
	    else if ($type == 3) {
	        $keyArray = array(18);
	    }
	    else {
	        $keyArray = array(2,3,4,11,12);
	    }
	    return parent::getConfigOutType(null, $keyArray);
	}
}

