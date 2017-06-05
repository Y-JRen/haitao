<?php

class Admin_Models_API_InStock
{
	/**
     * DB对象
     */
	private $_db = null;
	
	/**
     * Admin certification
     * @var array
     */
	public $_auth = null;
	
    /**
     * 错误信息
     */
	private $error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_InStock();
    	$this -> _order = new Admin_Models_API_Order();
    	$this -> _transport = new Admin_Models_DB_Transport();
		$this -> _op = new Admin_Models_DB_StockOp();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> _stockAPI = new Admin_Models_API_Stock();
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @param    string    $orderBy
     * @return   array
     */
	public function get($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		return $this -> _db -> get($where, $fields, $page, $pageSize, $orderBy);
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @param    string    $orderBy
     * @return   array
     */
    Public function getPlan($where = null, $fields = 'a.*,b.*,p.*,pb.batch_no', $page=null, $pageSize = null, $orderBy = null)
	{
		$datas = $this -> _db -> getPlan($where, $fields, $page, $pageSize, $orderBy);
		foreach($datas as $num => $data){
			if (strstr($data['bill_no'], '_')) {
			    $datas[$num]['parent_id'] = 1;
			}
		}
		return $datas;
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @param    string    $orderBy
     * @return   array
     */
    Public function getPlanList($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		$datas = $this -> _db -> getPlanList($where, $fields, $page, $pageSize, $orderBy);
		foreach($datas as $num => $data){
			if (strstr($data['bill_no'], '_')) {
			    $datas[$num]['parent_id'] = 1;
			}
		}
		return $datas;
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @param    string    $orderBy
     * @return   array
     */
    Public function getDetail($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		$datas = $this -> _db -> getDetail($where, $fields, $page, $pageSize, $orderBy);
		return $datas;
	}
    
    /**
     * 获取总数
     *
     * @return   int
     */
	public function getCount()
	{
		return $this -> _db -> total;
	}
	
	/**
     * 获取供货商列表
     *
     * @return   array
     */
	public function getSupplier($where = null)
	{
        return $this -> _db -> getSupplier($where);
	}
	
	/**
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
        $where && $where = " and $where";
        return $this -> _op -> getOp("item='instock' $where");
	}
	
	/**
     * 添加操作日志记录
     *
     * @return   int      lastInsertId
     */
	public function addOp($item_id, $admin_name, $op_type, $remark = null, $item_value = null)
	{
		$this -> _op -> insertOp('instock', $item_id, $admin_name, $op_type, $remark, $item_value);
	}
	
	/**
     * 添加单据接口
     *
     * @param    array      $bill        单据信息
     * @param    array      $details     详细信息
     * @param    int        $logicArea   逻辑区
     * @param    int        $update      是否更新逻辑区库存
     * @return   void
     */
	public function insertApi($bill, $details, $logicArea = 1, $update = false)
	{
		if (is_array($details)) {
		    $lastId = $this -> _db -> insert($bill);
		    if ($update && $bill['bill_status'] > 0) {
		        $stockAPI = new Admin_Models_API_Stock();
		    }
			foreach($details as $row) {
		        $row['instock_id'] = $lastId;
				$this -> _db -> insertPlan($row);
				if ($update && $bill['bill_status'] > 0) {
				    $where = array('lid' => $logicArea,
				                   'status_id' => $row['status_id'] ? $row['status_id'] : 1,
				                   'product_id' => $row['product_id'],
				                   'batch_id' => $row['batch_id'],
				                  );
				    $stockAPI -> addStockInNumber($row['plan_number'], $where);
			    }
		    }
	    }
	    
		return $lastId;
	}
	
	/**
     * 申请取消接口
     *
     * @param    string      $item_no
     * @param    string      $remark
     * @return   void
     */
	public function cancelApi($item_no, $remark = null)
	{
	    $r = array_shift($this -> get("item_no='$item_no' and lid=3 and bill_type=1", "instock_id", 1, 1));
	    if ($r) {
		    $id = $r['instock_id'];
		    $set = array ('is_cancel' => 1);
		    $this -> _db -> update($set, "instock_id=$id");
		    $this -> addOp($id, $this->_auth['admin_name'], 'cancel', $remark, '取消退换货');
		    return true;
	    }
	}
	
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   string
     */
	public function add($data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if (!$data['ids']) {
			$this -> error = 'no_goods';
			return false;
		} else {
	        foreach($data['ids'] as $key => $val) {
	        	if ($data['number'][$key] == 0) {
					$this -> error = 'no_number';
					return false;
				}
	        }
		}
		$add_time = time();
		$tmpStr = $data['logic_area'] == 1? 'H' : 'J';
		$bill_no = Custom_Model_CreateSn::createSn($tmpStr);
		
	    $row = array('lid' => $data['logic_area'],
		             'bill_no' => $bill_no,
                     'bill_type' => $data['bill_type'],
                     'supplier_id' => (int)$data['supplier_id'],
			         'remark' => $data['remark'],
                     'recipient' => $data['recipient'],
                     'purchase_type' => $data['purchase_type'] ? $data['purchase_type'] : 1,
                     'add_time' => $add_time,
                     'admin_name' => $this -> _auth['admin_name'],
                     'distribution_id' => $data['bill_type'] == 16 ? $data['distribution_id'] : 0,
                    );
        if ($data['delivery_date']) {
            $row['delivery_date'] = strtotime($data['delivery_date']);
        }
	    $lastId = $this -> _db -> insert($row);
	    
	    foreach($data['ids'] as $key => $val) {
			$detail = array('instock_id' => $lastId,
	                        'product_id' => $data['product_id'][$key],
	                        'batch_id' => $data['batch_id'][$key],
	                        'plan_number' => $data['number'][$key],
	                        'shop_price' => $data['price'][$key],
	                       );
	        if (in_array($data['bill_type'], array(8 ,17))) {
	            $detail['status_id'] = 2;
	        }
			$this -> _db -> insertPlan($detail);
	    }
	    
	    if ($data['bank_name']) {
	        $supplierAPI = new Admin_Models_DB_Supplier();
	        $set = array('bank_name' => $data['bank_name'],
	                     'bank_sn' => $data['bank_sn'],
	                     'bank_account' => $data['bank_account'],
	                    );
	        if(!empty($data['supplier_id'])){
	            $supplierAPI -> updateField($set, "supplier_id = ".$data['supplier_id']);
	        }
	        
	       
	    }

		return $bill_no;
	}
	
	/**
     * 申请取消
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function cancel($data, $id)
	{
		$set = array ('is_cancel' => 1, 'lock_name' => '');
		if($this -> _db -> update($set, "instock_id =$id")) {
		    $this -> addOp($id, $this -> _auth['admin_name'], 'cancel', $data['remark']);
		}
		return true;
	}
	
	/**
     * 申请取消审核
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function cancelCheck($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    $set = array ('lock_name' => '');
        
        if ($data['is_check'] == 1) {
        	$set['is_cancel'] = 2;
        	$set['bill_status'] = 8;
        	if ($data['bill_status'] > 2) {
        	    $stockAPI = new Admin_Models_API_Stock();
		        foreach ($data['product_id'] as $k => $v) {
		            $where = array('lid' => $data['logic_area'],
		                           'status_id' => $data['status_id'][$k],
		                           'product_id' => (int)$v,
		                           'batch_id' => $data['batch_id'][$k],
		                          );
		            $stockAPI -> reduceStockInNumber((int)$data['plan_number'][$k], $where);
				}
			}
        }
        else {
        	$set['is_cancel'] = 0;
        }
        
        if ($data['bill_type'] == 1 && ($data['is_check'] == 1)) {
            $this -> _order -> toStock($data['item_no'], 'returnSigned', array('is_check' => 0, 'bill_no' => $data['bill_no'], 'remark' => $data['remark']));
            $this -> delete($data['bill_no']);
        }
        else {
    	    if ($this -> _db -> update($set, "instock_id =$id")) {
    	    	$item_value = ($data['is_check'] == 1) ? '同意取消' : '拒绝取消';
    	        $this -> addOp($id, $this -> _auth['admin_name'], 'cancel-check', $data['remark'], $item_value);
    	    }
	    }
	    

	    
	    return true;
	}
	
	/**
     * 审核
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function check($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
	    $set = array ('lock_name' => '');
        if ($data['is_check'] == 1) {
    	    foreach ($data['product_id'] as $k => $v) {
    	        $where = array('lid' => $data['logic_area'],
    	                       'batch_id' => $data['batch_id'][$k] ? $data['batch_id'][$k] : 0,
    	                       'product_id' => (int)$v,
    	                       'status_id' => $data['status_id'][$k],
    	                       );
    	        $this -> _stockAPI -> addStockInNumber($data['plan_number'][$k], $where);
			}
	        $set['bill_status'] = 3;
	        
	        if ($data['bill_type'] == 18) {
        	    $replenishmentAPI = new Admin_Models_API_Replenishment();
        	    $replenishmentAPI -> traceStatus($data['bill_no'], 2);
        	}
	        
        }
        else {
        	$set['bill_status'] = 2;
        	
        	if ($data['bill_type'] == 18) {
        	    $replenishmentAPI = new Admin_Models_API_Replenishment();
        	    $replenishmentAPI -> traceStatus($data['bill_no'], 9);
        	}
        }
        
	    if ($this -> _db -> update($set, "instock_id = {$id}")) {
	    	$item_value = ($data['is_check'] == 1) ? '同意' : '已拒绝';
	        $this -> addOp($id, $this -> _auth['admin_name'], 'check', $data['remark'], $item_value);
	    }
	    
	    //调整入库单/虚拟入库单
	    if ($data['is_check'] == 1 && in_array($data['bill_type'], array(8 ,17))) {
	        $this -> _db -> update(array('bill_status' => 6), "instock_id = {$id}");
	        $this -> receiveByBillNo($data['bill_no']);
	    }
	    
	    return true;
	}
	
	/**
     * 确认
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function confirm($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
	    $set = array ('bill_status' => 6, 'lock_name' => '');
	    if ($this -> _db -> update($set, "instock_id =$id")) {
	        $this -> addOp($id, $this -> _auth['admin_name'], 'confirm', $data['remark']);
	    }
	    return true;
	}
	
	/**
     * 收货
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function receive($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        $logicArea = $data['logic_area'];
        $remark = '';
        if ($data['total_number'] == 0) {
			$this -> error = 'no_number';
			return false;
		}
		foreach ($data['plan_number'] as $k => $v) {
			$num = array_sum($data['number'][$k]);
			if ($num > $v) {
				$this -> error = 'wrong_number';
			    return false;
			}
		}
        $r = array_shift($this -> get("bill_no='{$data['bill_no']}' and bill_status=7", "a.instock_id", 1, 1));
	    if ($r) {
	    	$this -> error = 'error';
	    	return false;
	    }
        
	    foreach ($data['number'] as $k => $v) {
	        foreach ($v as $key => $num) {
		       	$product_id = substr($k, 0, strpos($k, '_'));
		       	$batch_id = $data['batch_id'][$k] ? $data['batch_id'][$k] : 0;
		       	$status_id = $data['status'][$k][$key];
                
		       	if ($status_id != $data['old_status'][$k]) {
		       	    $where = array('lid' => $logicArea,
		       	                   'batch_id' => $batch_id,
		       	                   'product_id' => $product_id,
		       	                   'status_id' => $data['old_status'][$k],
		       	                  );
		       	    $this -> _stockAPI -> reduceStockInNumber($num, $where);
		       	    
		       	    $where = array('lid' => $logicArea,
		       	                   'batch_id' => $batch_id,
		       	                   'product_id' => $product_id,
		       	                   'status_id' => $status_id,
		       	                  );
		       	    $this -> _stockAPI -> addStockInNumber($num, $where);
		       	}
                
		       	if ($data['bill_type'] == 1 || $data['bill_type'] == 10) {
		       	    $batch_id = $data['new_batch_id'][$product_id] ? $data['new_batch_id'][$product_id] : 0;
                    
		       	    $where = array('lid' => $logicArea,
		       	                   'batch_id' => 0,
		       	                   'product_id' => $product_id,
		       	                   'status_id' => $status_id,
		       	                  );
		       	    $this -> _stockAPI -> reduceStockInNumber($num, $where);
		        	    
		       	    $where = array('lid' => $logicArea,
		       	                   'batch_id' => $batch_id,
		       	                   'product_id' => $product_id,
		       	                   'status_id' => $status_id,
		       	                  );
		       	    $this -> _stockAPI -> addStockInNumber($num, $where);
		       	    
		       	    $this -> _db -> update(array('batch_id' => $batch_id), "instock_id = {$id} and batch_id = 0 and product_id = {$product_id}", 'plan');
		       	}
                
		       	$where = array('lid' => $logicArea,
		       	               'batch_id' => $batch_id,
		       	               'product_id' => $product_id,
		       	               'status_id' => $status_id,
		       	              );
                $this -> _stockAPI -> addStockRealInNumber($num, $where, $data['bill_no']);
                
                if ($data['bill_type'] == 16) {
                    $distributionBill[$product_id] = array('price' => $data['price'][$k],
                                                           'number' => $num,
                                                          );
                    
                    $data['price'][$k] = $this -> _db -> getCostByPid($product_id);
                }
                
		       	$row = array('instock_id' => $id,
		       	             'batch_id' => $batch_id,
		       	             'product_id' => $product_id,
		       	             'status_id' => $status_id,
		       	             'number' => $num,
		       	             'shop_price' => $data['price'][$k],
		       	            );
		       	$this -> _db -> insertDetail($row);
                
                if (in_array($data['bill_type'], array(1, 2, 3, 4, 5, 11, 12, 13, 14, 15, 17, 18, 19, 20))) {    //退货/采购/返修/归还/分销采购/分销拒收/送检/丢件/组装/分销入库/虚拟/补货/成本
                    $productAPI = new Admin_Models_API_Product();
                    $productAPI -> calculateProductCostByIn($product_id, $num, $data['price'][$k]);
                }
	        }
        }
        
        $details = array();
	    foreach ($data['plan_number'] as $k => $v) {
	        $product_id = substr($k, 0, strpos($k, '_'));
	        $batch_id = $data['batch_id'][$k] ? $data['batch_id'][$k] : 0;
	     	$num = array_sum($data['number'][$k]);
	       	if ($num < $v) {
	       	    $lnum = (int)$v - (int)$num;
	       	    if ($data['bill_type'] == 1 || $data['bill_type'] == 10) {
		  	        $batch_id = 0;
		   	    }
	       	    $details[] = array('batch_id' => $batch_id,
	       	                       'product_id' => $product_id,
	       	                       'status_id' => $data['old_status'][$k],
	       	                       'plan_number' => $lnum,
	       	                       'shop_price' => $data['price'][$k]
	       	                      );
	       	}
	       	
	       	if ($data['bill_type'] == 1 || $data['bill_type'] == 10) {
		  	    $batch_id = $data['new_batch_id'][$product_id] ? $data['new_batch_id'][$product_id] : 0;
		   	}
		   	
	       	$this -> _db -> update(array('real_number' => $num), "instock_id = {$id} and batch_id = {$batch_id} and product_id = {$product_id}", 'plan');
	    }
        
	    if (count($details) > 0) {
	        $add_time = time ();
		 	$bill_no = $data['bill_no'];
		   	if (strstr($bill_no, '_')) {
		   		$r = explode('_', $bill_no);
		   		$bill_no = $r[0].'_'.($r[1]+1);
		   	} else {
		   	    $bill_no .= '_1';
		   	}
            
		   	$inStockData = array_shift($this -> get("bill_no='{$data['bill_no']}' and bill_status=6", "b.supplier_id,b.delivery_date,b.item_no", 1, 1));
		   	
	        $row = array ('lid' => $logicArea,
		                  'bill_status' => '6',
			              'bill_no' => $bill_no,
			              'bill_type' => $data['bill_type'],
				          'recipient' => $data['recipient'],
			              'item_no' => $inStockData['item_no'] ? $inStockData['item_no'] : $data['bill_no'],
			              'supplier_id' => $inStockData['supplier_id'],
			              'add_time' => $add_time,
			              'admin_name' => $this -> _auth['admin_name'],
			              'delivery_date' => $inStockData['delivery_date'],
			              'remark' => $data['new_remark'],
			             );
			$this -> insertApi($row, $details, $logicArea, false);
	    }
	    
	    $set = array ('bill_status' => 7, 'lock_name' => '', 'finish_time' => time());


		if ($data['check_recipient'] == '1'){
				if($data['logistic_code']){
					$set['logistic_code'] = $data['logistic_code'];
				}
				if($data['logistic_no']){
			  
					$set['logistic_no'] = $data['logistic_no'];
				}	
		}

        //接口处理
	    if ($data['bill_type'] == 1 || $data['bill_type'] == 13) {
	    	$where = "bill_no='{$data['item_no']}'";
			$r = array_shift($this -> _transport -> get($where));
			if ($r['logistic_no'] && $r['logistic_status'] == 3) {
				$logistic_status = 4;
			    $row = array('item_no' => $data['item_no'],
			    	         'logistic_no' => $r['logistic_no'],
						     'logistic_code' => $r['logistic_code'],
					         'logistic_status' => $logistic_status,
					         'op_time' => time(),
					         'admin_name' => $this -> _auth['admin_name'],
					         'remark' => '',
						    );

			    $this -> _transport -> insertTrack($row);
			    $row = array('logistic_status' => $logistic_status);
			    if ($r['is_cod']) {
			        $row['amount'] = 0;
			    }
			    $this -> _transport -> update($row, $where);
			}
		    
		    $this -> _order -> toStock($data['item_no'], 'returnSigned', array('bill_no' => $data['bill_no'], 'is_check' => 1, 'remark' => $data['remark']));
	    }
	    
	    //生成财务付款单
	    if ($data['bill_type'] == 2 || $data['bill_type'] == 5 || $data['bill_type'] == 18) {
	        $totalAmount = 0;
	        foreach ($data['number'] as $product_id => $numberInfo) {
	            foreach ($numberInfo as $number) {
	                $totalAmount += $number * $data['price'][$product_id];
	            }
	        }
            if ($totalAmount > 0) {
    	        if (!$inStockData) {
    	            $inStockData = array_shift($this -> get("bill_no='{$data['bill_no']}' and bill_status=6", "b.supplier_id,b.delivery_date", 1, 1));
    	        }
    	        if ($inStockData['supplier_id']) {
    	            $supplierAPI = new Admin_Models_API_Supplier();
    	            $supplierData = array_shift($supplierAPI -> getSupplier("supplier_id = {$inStockData['supplier_id']}"));
    	        }
    		    $row = array('bill_no' => $data['bill_no'],
    		                 'type' => 1,
    		                 'supplier_id' => $inStockData['supplier_id'],
    		                 'amount' => $totalAmount,
    		                 'real_amount' => 0,
    		                 'paper_no' => $data['paper_no'],
    		                 'bank_name' => $supplierData['bank_name'],
    		                 'bank_sn' => $supplierData['bank_sn'],
    		                 'bank_account' => $supplierData['bank_account'],
    		                 'add_time' => time(),
    		                 'update_time' => time()
    		                );
    		    
    		    $this -> _db -> insertPayment($row);
    		}
    		
    		if ($data['bill_type'] == 18) {
    		    $replenishmentAPI = new Admin_Models_API_Replenishment();
        	    $replenishmentAPI -> traceStatus($data['bill_no'], 3);
    		}
		}
		
		//生成虚拟出库单
	    if ($data['bill_type'] == 17) {
	        $outstockAPI = new Admin_Models_API_OutStock();
            
            $row = array('lid' => $logicArea,
            		     'bill_no' => $data['bill_no'],
                         'bill_type' => 17,
                         'bill_status' => 0,
                         'add_time' => time(),
                         'admin_name' => $this -> _auth['admin_name'],
                        );
            $details = array();
	        foreach ($data['plan_number'] as $k => $v) {
	            $product_id = substr($k, 0, strpos($k, '_'));
	            $batch_id = $data['batch_id'][$k];
	            
                $details[] = array('product_id' => $product_id,
	                               'batch_id' => $batch_id,
	                               'status_id' => $data['old_status'][$k],
	                               'number' => $v,
	                               'shop_price' => $data['price'][$k],
	                               'cost' => $data['price'][$k],
	                              );
	        }
            
            $outstockAPI -> insertApi($row, $details, $row['lid'], false);
	    }
	    
	    //生成盘亏调整出库单
	    if ($data['bill_type'] == 13) {
	        $outstockAPI = new Admin_Models_API_OutStock();
            
            $row = array('lid' => $logicArea,
            		     'bill_no' => $data['bill_no'],
                         'bill_type' => 11,
                         'bill_status' => 0,
                         'add_time' => time(),
                         'admin_name' => $this -> _auth['admin_name'],
                        );
            $details = array();
	        foreach ($data['plan_number'] as $k => $v) {
	            $product_id = substr($k, 0, strpos($k, '_'));
	            $batch_id = $data['batch_id'][$k];
	            
                $details[] = array('product_id' => $product_id,
	                               'batch_id' => $batch_id,
	                               'status_id' => $data['old_status'][$k],
	                               'number' => $v,
	                               'shop_price' => $data['price'][$k],
	                               'cost' => $data['price'][$k],
	                              );
	        }
            
            $outstockAPI -> insertApi($row, $details, $row['lid'], false);
	    }
	    if ($this -> _db -> update($set, "instock_id =$id")) {
	        $this -> addOp($id, $this -> _auth['admin_name'], 'receive', $data['remark']);
	    }
	    
	    return true;
	}
	
	/**
     * 收货(按收货单号)
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function receiveByBillNo($billNo)
	{
	    $r = array_shift($this -> get("bill_no='{$billNo}' and bill_status=7", "a.instock_id", 1, 1));
	    if ($r) {
	    	$this -> error = 'error';
	    	return false;
	    }
	    
	    $details = $this -> getPlan("b.bill_no='{$billNo}'");
	    if (!$details)  return false;
	    
	    $data = array('bill_no' => $billNo,
	                  'item_no' => $details[0]['item_no'],
	       	          'logic_area' => $details[0]['lid'],
	       	          'bill_type' => $details[0]['bill_type'],
	       	          'total_number' => 0,
	       	         );
	    foreach ($details as $detail) {
	        $key = $detail['product_id'].'_'.$detail['batch_id'];
	        
	        $data['total_number'] += $detail['plan_number'];
	        $data['plan_number'][$key] = $detail['plan_number'];
	        $data['old_status'][$key] = $detail['status_id'];
	        $data['batch_id'][$key] = $detail['batch_id'];
	        $data['price'][$key] = $detail['shop_price'];
	        $data['number'][$key] = array($detail['plan_number']);
	        $data['status'][$key] = array($detail['status_id']);
	    }
        
        return $this -> receive($data, $details[0]['instock_id']);
	}
	
	/**
     * 强制完成
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function setover($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        $stockAPI = new Admin_Models_API_Stock();
        
        $logicArea = $data['logic_area'];
		foreach ($data['plan_number'] as $k => $v) {
		    $where = array('lid' => $logicArea,
		                   'status_id' => $data['status_id'][$k],
		                   'product_id' => $data['product_id'][$k],
		                   'batch_id' => $data['batch_id'][$k],
		                  );
		    $stockAPI -> reduceStockInNumber($v, $where);
	    }
	    
	    $this -> _db -> update(array('bill_status' => 9), "instock_id =$id");
        
        //分销入库单自动生成分销拒收入库单
        if ($data['bill_type'] == 15) {
            $bill = array('lid'         => 1,
                          'item_no'     => $data['bill_no'],
				          'bill_no'     => Custom_Model_CreateSn::createSn(),
        				  'bill_type'   => 11,
        				  'bill_status' => 3,
        				  'remark'      => '分销拒收入库单',
        				  'add_time'    => time(),
        				  'admin_name'  => $this->_auth['admin_name']
        				 );
            foreach ($data['plan_number'] as $k => $v) {
                $details[] = array('product_id' => $data['product_id'][$k],
                                   'batch_id' => $data['batch_id'][$k],
                                   'status_id' => $data['status_id'][$k],
                                   'shop_price' => $data['shop_price'][$k],
                                   'plan_number' => $v,
                                  );
            }
            
            $this -> insertApi($bill, $details, 1, true);
        }
        
	    return true;
	}
	
	/**
     * 锁定/解锁数据
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function lock($datas, $val)
	{
		if (is_array($datas['ids'])) {
			foreach($datas['ids'] as $v){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($val) {
			    	$set = array('lock_name' => $admin_name);
			    	$where = "lock_name=''";
			    } else {
			    	$set = array('lock_name' => '');
			    	$where = ($this -> _auth['group_id'] > 1 ) ? "lock_name='$admin_name'" : '1';
			    }
	    		$this -> _db -> update($set, "$where and instock_id=$v");
			}
		}
        return true;
	}
	
	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'forbidden'=>'禁止操作!',
			         'no_supplier'=>'请选择供应商!',
			         'no_goods'=>'请选择商品!',
			         'no_number'=>'请填写数量!',
			         'wrong_number'=>'数量有误!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
	/**
     * 出入库查询动作
     * 
     * @param    array  $where
     * @param    int    $act
     * @param    int    $page
     * @param    int    $logicArea
     *
     * @return   void
     */
    public function search($where, $act, $page, $logicArea)
    {
		$whereSql = "b.lid = '{$logicArea}'";
		if (is_array($where)) {
	        $where['bill_type'] && $whereSql .= " and bill_type='{$where['bill_type']}'";
            $where['recipient'] && $whereSql .= " and recipient='{$where['recipient']}'";
	        $where['bill_status']<>'' && $whereSql .= " and bill_status='{$where['bill_status']}'";
	        $where['admin_name'] && $whereSql .= " and b.admin_name LIKE '%" . $where['admin_name'] . "%'";
	        $where['supplier_id'] && $whereSql .= " and b.supplier_id='{$where['supplier_id']}'";
		    $where['bill_no'] && $whereSql .= " and (bill_no LIKE '%" . $where['bill_no'] . "%' or item_no LIKE '%" . $where['bill_no'] . "%')";
 	        $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    $where['product_sn'] && $whereSql .= " and p.product_sn LIKE '%" . $where['product_sn'] . "%'";
		    $where['local_sn'] && $whereSql .= " and local_sn LIKE '%" . $where['local_sn'] . "%'";
		    $where['goods_name'] && $whereSql .= " and product_name LIKE '%" . $where['goods_name'] . "%'";
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (b.add_time between $fromdate and $todate)";
	        }
	        if ($where['fromdate2'] && $where['todate2']) {
			    $fromdate = strtotime($where['fromdate2']);
			    $todate = strtotime($where['todate2']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (b.finish_time between $fromdate and $todate)";
	        }
	        if ($where['is_lock']) {
		    	$lock_name = $where['is_lock'] == 'yes' ? $this -> _auth['admin_name'] : '';
		    	$whereSql .= " and lock_name = '" . $lock_name . "'";
		    }
		} else {
			$whereSql = $where;
		}
		switch($act){
			case 'check-list':
				$whereSql .= " and bill_status=0 and is_cancel=0";
				break;
			case 'confirm-list':
				$whereSql .= " and bill_status=3 and is_cancel=0";
				break;
			case 'receive-list':
				$whereSql .= " and bill_status=6 and is_cancel=0";
				break;
			case 'cancel-list':
				$whereSql .= " and is_cancel=1";
				break;
			case 'setover-list':
				$whereSql .= " and bill_status<9 and bill_status != 7 and  LOCATE('_', bill_no)>0 and is_cancel=0";
				break;
		}
        
        $datas = $this -> getPlanList($whereSql, '*', $page);
        $total = $this -> getCount();
        $result['datas'] = $datas;
        $result['total'] = $total;
        $result['sum'] = $this -> _db -> getSumAmount($whereSql);
        $result['sum']['no_tax_amount'] = round($result['sum']['no_tax_amount'], 2);
        $result['sum']['real_no_tax_amount'] = round($result['sum']['real_no_tax_amount'], 2);
        
        return $result;
    }
    
    /**
     * 删除入库单
     *
     * @return   void
     */
	public function delete($bill_no)
	{
	    $this -> _db -> delete($bill_no);
	}
	
	/**
     * 获得主记录
     *
     * @return   void
     */
	public function getMain($where = 1)
	{
	    return $this -> _db -> getMain($where);
	}
	
	/*更新发票，付款信息*/
    public function updateInvAndNum($params){
        $instock_id = $params['instock_id'];
        $prods = $params['product_id'];
        $index = count($prods);
        for ($i = 0; $i < $index; $i++) {
            $proud_id = $prods[$i];
            $data['prod_pay_num'] = $params['prod_pay_num'][$i];
            $data['invoice_num'] = $params['invoice_num'][$i];
            $where = " instock_id = $instock_id and product_id = $proud_id ";
            $this -> _db -> updatedetail($data, $where);
        }
    }
}