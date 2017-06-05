<?php

class Custom_Model_Stock_Base
{
	protected $_stockConfig;
	protected $_logicArea;
	
	/**
     * 构造函数
     *
     * @param   int     $logicArea
     * @param   object  $stockConfig
     * @return  void
     */
	function __construct($logicArea, $stockConfig)
	{
	    $this -> _logicArea = $logicArea;
	    $this -> _stockConfig = $stockConfig;
	}
	
	/**
     * 获得对象实列
     *
     * @param   int     $logicArea
     * @return  object
     */
	public static function getInstance($logicArea = null)
	{
	    $stockConfig = new Custom_Model_Stock_Config();
	    if ($logicArea) {
	        if ($logicArea == 99) {
	            $config = new Custom_Model_Stock_Config();
	            foreach ($config -> _logicArea as $key => $value) {
	                if ($key > 20) {
	                    $logicArea = $key;
	                    break;
	                }
	            }
	            if ($logicArea == 99) {
	                die('error');
	            }
	        }
	        else {
        	    if (!$stockConfig -> _logicArea[$logicArea]) {
        	        return false;
        	    }
            }
            
    	    $class = 'Custom_Model_Stock_Area'.$logicArea;
    	    if (@class_exists($class)) {
    	        $object = new $class($logicArea, $stockConfig);
    	    }
    	    else {
    	        if ($logicArea > 20) {
    	            $object = new Custom_Model_Stock_AreaDistribution($logicArea, $stockConfig);
    	        }
    	        else {
    	            $object = new Custom_Model_Stock_Base($logicArea, $stockConfig);
    	        }
    	    }
	    }
	    else {
	        $object = new Custom_Model_Stock_Base($logicArea, $stockConfig);
	    }
	    
	    return $object;
	}
	
	/**
     * 获得区域ID
     *
     * @return  int
     */
	public function getArea()
	{
	    return $this -> _logicArea;
	}
	
	/**
     * 获得区域名称
     *
     * @return  string
     */
	public function getAreaName()
	{
	    return $this -> _stockConfig -> _logicArea[$this -> _logicArea];
	}
	
	/**
     * 获得区域
     *
     * @param   int     $logicArea
     * @return  string/array
     */
	public function getConfigLogicArea($logicArea = null)
	{
	    if ($logicArea) return $this -> _stockConfig -> _logicArea[$logicArea];
	    return $this -> _stockConfig -> _logicArea;
	}
	
	/**
     * 获得库存状态
     *
     * @param   int     $logicStatus
     * @param   array   $values
     * @return  string/array
     */
	public function getConfigLogicStatus($logicStatus = null, $values = null)
	{
	    if ($logicStatus)   return $this -> _stockConfig -> _logicStatus[$logicStatus];
	    
	    if ($values) {
	        foreach ($this -> _stockConfig -> _logicStatus as $key => $name) {
	            if (in_array($key, $values)) {
	                $result[$key] = $name;
	            }
	        }
	        return $result;
	    }
	    else {
	        return $this -> _stockConfig -> _logicStatus;
	    }
	}
	
	/**
     * 获得库存操作
     *
     * @param   string  $operate
     * @return  string/array
     */
	public function getConfigOperate($operate = null)
	{
	    if ($operate)   return $this -> _stockConfig -> _operates[$operate];
	    return $this -> _stockConfig -> _operates;
	}
	
	/**
     * 获得入库动作
     *
     * @param   string  $action
     * @return  string/array
     */
	public function getConfigInAction($action = null)
	{
	    if ($action)   return $this -> _stockConfig -> _inActions[$action];
	    return $this -> _stockConfig -> _inActions;
	}
	
	/**
     * 获得出库动作
     *
     * @param   string  $action
     * @return  string/array
     */
	public function getConfigOutAction($action = null)
	{
	    if ($action)   return $this -> _stockConfig -> _outActions[$action];
	    return $this -> _stockConfig -> _outActions;
	}
	
	/**
     * 获得商品状态动作
     *
     * @param   string  $action
     * @return  string/array
     */
	public function getConfigStatusAction($action = null)
	{
	    if ($action)   return $this -> _stockConfig -> _statusActions[$action];
	    return $this -> _stockConfig -> _statusActions;
	}
	
	/**
     * 获得调拨动作
     *
     * @param   string  $action
     * @return  string/array
     */
	public function getConfigAllocationAction($action = null)
	{
	    if ($action)   return $this -> _stockConfig -> _allocationActions[$action];
	    return $this -> _stockConfig -> _allocationActions;
	}
	
	/**
     * 获得配送动作
     *
     * @param   string  $action
     * @return  string/array
     */
	public function getConfigTransportAction($action = null)
	{
	    if ($action)   return $this -> _stockConfig -> _transportActions[$action];
	    return $this -> _stockConfig -> _transportActions;
	}
	
	/**
     * 获得配送单类型
     *
     * @param   string  $type
     * @return  string/array
     */
	public function getConfigTransportType($type = null)
	{
	    if ($type)  return $this -> _stockConfig -> _tranTypes[$type];
	    return $this -> _stockConfig -> _tranTypes;
	}
	
	/**
     * 获得入库类型
     *
     * @param   int     $type
     * @return  string/array
     */
	public function getConfigInType($type = null, $keyArray = null)
	{
	    if ($type) {
	        if (!$keyArray || in_array($type, $keyArray)) {
	            return $this -> _stockConfig -> _inTypes[$type];
	        }
	    }
	    else {
	        if ($keyArray) {
	            foreach ($this -> _stockConfig -> _inTypes as $key => $data) {
        	        if (in_array($key, $keyArray)) {
        	            $result[$key] = $data;
        	        }
        	    }
        	    return $result;
	        }
	        else {
	            return $this -> _stockConfig -> _inTypes;
	        }
	    }
	}
	
	/**
     * 获得出库类型
     *
     * @param   int     $type
     * @return  string/array
     */
	public function getConfigOutType($type = null, $keyArray = null)
	{
	    if ($type) {
	        if (!$keyArray || in_array($type, $keyArray)) {
	            return $this -> _stockConfig -> _outTypes[$type];
	        }
	    }
	    else {
	        if ($keyArray) {
	            foreach ($this -> _stockConfig -> _outTypes as $key => $data) {
        	        if (in_array($key, $keyArray)) {
        	            $result[$key] = $data;
        	        }
        	    }
        	    return $result;
	        }
	        else {
	            return $this -> _stockConfig -> _outTypes;
	        }
	    }
	}
	
	/**
     * 获得入库单据状态
     *
     * @param   int     $billStatus
     * @return  string/array
     */
	public function getConfigBillInStatus($billStatus = null)
	{
	    if ($billStatus)    return $this -> _stockConfig -> _billInStatus[$billStatus];
	    return $this -> _stockConfig -> _billInStatus;
	}
	
	/**
     * 获得出库单据状态
     *
     * @param   int     $billStatus
     * @return  string/array
     */
	public function getConfigBillOutStatus($billStatus = null)
	{
	    if ($billStatus)    return $this -> _stockConfig -> _billOutStatus[$billStatus];
	    return $this -> _stockConfig -> _billOutStatus;
	}
	
	/**
     * 获得状态调整状态
     *
     * @param   int     $status
     * @return  string/array
     */
	public function getConfigStatusStatus($status = null)
	{
	    if ($status)    return $this -> _stockConfig -> _statusStatus[$status];
	    return $this -> _stockConfig -> _statusStatus;
	}
	
	/**
     * 获得调拨单状态
     *
     * @param   int     $status
     * @return  string/array
     */
	public function getConfigAllocationStatus($status = null)
	{
	    if ($status)    return $this -> _stockConfig -> _allocationStatus[$status];
	    return $this -> _stockConfig -> _allocationStatus;
	}
	
	/**
     * 获得物流状态
     *
     * @param   int     $logisticStatus
     * @return  string/array
     */
	public function getConfigLogisticStatus($logisticStatus = null)
	{
	    if ($logisticStatus)    return $this -> _stockConfig -> _logisticStatus[$logisticStatus];
	    return $this -> _stockConfig -> _logisticStatus;
	}
	
	/**
     * 获得可手工新增出库类型
     *
     * @param   int     $type
     * @return  array
     */
	public function getConfigAddOutType($type = null)
	{
	    return false;
	}
	
	/**
     * 获得可手工新增入库类型
     *
     * @return  array
     */
	public function getConfigAddInType($type = null)
	{
	    return false;
	}
	
	/**
     * 获得分销仓ID
     *
     * @param   string  $username
     * @return  int
     */
	static public function getDistributionArea($username = null)
	{
	    $distributionArea = array();
        if ($username) {
	        return $distributionArea[$username] ? $distributionArea[$username] : 0;
	    }
	    else {
	        return $distributionArea;
	    }
	}
	
}