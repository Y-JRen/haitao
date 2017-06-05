<?php

 class Custom_Model_Stock_Config
 {
 	/**
     * 对象初始化
     *
     */
    public function __construct()
    {
    	$this -> _config = Zend_Registry::get('config');
    	$this -> _dataDir = Zend_Registry::get('systemRoot') . '/data/admin/stock/';
    	
    	/**
	     * 操作区域
	     * 
	     * @var    array
	     */
	    /*
    	*/
    	$this -> _logicArea = array('1' => '香港仓',
    	                            '2' => '日本仓',
    	                           );
    	                       
    	/**
	     * 商品状态
	     * 
	     * @var    array
	     */
    	$this -> _logicStatus = array(
    	                       '1' => '质检',
    	                       '2' => '正常',
    	                       '3' => '残次',
    	                       '4' => '冻结',
    	                       '5' => '报损',
    	                       '6' => '退货',
    	                       );
    	
    	/**
	     * 操作动作
	     * 
	     * @var    array
	     */
    	$this -> _operates = array(
                           'search-list' => 'view',
	                       'check-list' => 'check',
	                       'confirm-list' => 'confirm',
	                       'send-list' => 'send',
	                       'receive-list' => 'receive',
	                       'cancel-list' => 'cancel-check',
                           );
	 	/**
	     * 入库动作
	     * 
	     * @var    array
	     */
	 	$this -> _inActions = array(
		                       'search-list' => '入库单查询',
		                       'check-list' => '入库单审核',
		                       'confirm-list' => '入库单确认',
		                       'receive-list' => '入库单收货',
		                       'cancel-list' => '待取消入库单',
		                       'setover-list' => '入库单结束',
	                           );
	 	/**
	     * 出库动作
	     * 
	     * @var    array
	     */
	 	$this -> _outActions = array(
		                       'search-list' => '出库单查询',
		                       'check-list' => '出库单审核',
		                       'confirm-list' => '出库单确认',
		                       'send-list' => '出库单发货',
                               'batch-send-list' => '批量出库单发货',
		                       'cancel-list' => '待取消出库单',
	                           );
	    /**
	     * 商品状态动作
	     * 
	     * @var    array
	     */
	    $this -> _statusActions = array(
		                       'search-list' => '商品状态更改查询',
		                       'check-list' => '商品状态更改审核',
		                       'cancel-list' => '待取消补货单',
	                           );
        
        /**
	     * 调拨动作
	     * 
	     * @var    array
	     */
        $this -> _allocationActions = array(
		                       'search-list' => '调拨单查询',
		                       'check-list' => '调拨单审核',
		                       'confirm-list' => '调拨单确认',
		                       'send-list' => '调拨单发货',
		                       'receive-list' => '调拨单收货',
		                       'cancel-list' => '待取消调拨单',
	                           );
	    
	 	/**
	     * 入库类型
	     * 
	     * @var    array
	     */
	 	$this -> _inTypes = array(
	                           '1' => '退货入库单',
		                       '2' => '采购入库单',
	                           '3' => '返修入库单',
	                           '4' => '归还入库单',
	                           '5' => '分销采购入库单',
                               '6' => '盘点调整入库单',
							   '8' => '调整入库单',
	                           '9' => '其他入库单',
                               '10' => '分销退货入库单',
                               '11' => '分销拒收入库单',
                               '12' => '送检入库单',
	                           '13' => '丢件虚拟入库单',
                               '14' => '组装入库单',
                               '15' => '分销入库单',
                               '16' => '直供刷单入库单',
                               '17' => '虚拟入库单',
                               '18' => '补货入库单',
							   '19' => '分销刷单入库单',
							   '20' => '成本调整入库单',
	                           );
	 	
	 	/**
	     * 出库类型
	     * 
	     * @var    array
	     */
	 	$this -> _outTypes = array(
	                           '1' => '销售出库单',
		                       '2' => '采购退货单',
	                           '3' => '返修出库单',
	                           '4' => '借货出库单',
	                           '6' => '赠送出库单',
	                           '7' => '内部购买单',
	                           '9' => '其他出库单',
                               '10' => '分销预售出库单',
                               '11' => '调整出库单',
                               '12' => '送检出库单',
                               '14' => '组装出库单',
                               '15' => '分销销售出库单',
                               '16' => '分销退货单',
                               '17' => '虚拟出库单',
                               '18' => '占用出库单',
							   '19' => '分销刷单出库单',
							   '20' => '成本调整出库单',
	                           );
	    
	 	/**
	     * 单据入库状态
	     * 
	     * @var    array
	     */
	 	$this -> _billInStatus = array(
	                           '0' => '未审核',
	                           '2' => '已拒绝',
		                       '3' => '未确认',
	                           '6' => '待收货',
	                           '7' => '已收货',
	                           '8' => '已取消',
	                           '9' => '已删除',
	                           );
	    
	    /**
	     * 单据出库状态
	     * 
	     * @var    array
	     */
	 	$this -> _billOutStatus = array(
	                           '0' => '未审核',
	                           '2' => '已拒绝',
		                       '3' => '未确认',
	                           '4' => '待发货',
	                           '5' => '已发货',
	                           '8' => '已取消',
	                           );
        
	    /**
	     * 状态调整状态
	     * 
	     * @var    array
	     */
	 	$this -> _statusStatus = array(
	                           '0' => '未审核',
	                           '1' => '已审核',
	                           '2' => '已拒绝',
	                           );
	                           
	    /**
	     * 调拨单状态
	     * 
	     * @var    array
	     */
	 	$this -> _allocationStatus = array(
	                           '0' => '未审核',
	                           '2' => '已拒绝',
		                       '3' => '未确认',
		                       '4' => '待发货',
	                           '5' => '已发货',
	                           '6' => '待收货',
	                           '7' => '已收货',
	                           '8' => '已取消',
	                           );
	    
	    
	    /**
	     * 配送动作
	     * 
	     * @var    array
	     */
        $this -> _transportActions = array(
		                       'search-list' => '运输单查询',
		                       'confirm-list' => '运输单确认',
	                           );
	    
	 	/**
	     * 运输单类型
	     * 
	     * @var    array
	     */
	 	$this -> _tranTypes = array(
	                           '1' => '销售单',
		                       '2' => '内部派单',
	                           );
	    
	 	/**
	     * 配送状态
	     * 
	     * @var    array
	     */
	 	$this -> _logisticStatus = array(
	                           '0' => '未出仓',
		                       '1' => '在途',
	                           '2' => '客户已签收',
	                           '3' => '拒收待返货',
	                           '4' => '拒收已返货',
	                           '5' => '投诉',
	                           '6' => '退货已入库',
	                           );
        
        /**
	     * 代收货款动作
	     * 
	     * @var    array
	     */
        $this -> _codActions = array(
                               'search-list' => '代收货款变更查询',
                               'check-list' => '代收货款变更审核',
                               );
	                           
    }
    
    public function getAreaID($name)
    {
        foreach ($this -> _logicArea as $areaID => $areaName) {
            if ($areaName == $name) return $areaID;
        }
            
        return false;
    }
        
    public function getStatusID($name)
    {
        foreach ($this -> _logicStatus as $statusID => $statusName) {
            if ($statusName == $name)   return $statusID;
        }
            
        return false;
    }
 }