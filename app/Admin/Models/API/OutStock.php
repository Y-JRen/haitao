<?php

class Admin_Models_API_OutStock
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
		$this -> _db = new Admin_Models_DB_OutStock();
    	$this -> _order = new Admin_Models_API_Order();
    	$this -> _transport = new Admin_Models_DB_Transport();
    	$this -> _product = new Admin_Models_DB_Product();
		$this -> _op = new Admin_Models_DB_StockOp();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
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
    public function getDetail($where = null, $fields = 'p.*,a.*,b.*,pb.batch_no', $page=null, $pageSize = null, $orderBy = null)
	{
		$datas = $this -> _db -> getDetail($where, $fields, $page, $pageSize, $orderBy);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['transport'] = array_shift($this -> _transport -> get("bill_no='{$data['bill_no']}'"));
        }
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
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
        $where && $where = " and $where";
        return $this -> _op -> getOp("item='outstock' $where");
	}
	
	/**
     * 添加操作日志记录
     *
     * @return   int      lastInsertId
     */
	public function addOp($item_id, $admin_name, $op_type, $remark = null, $item_value = null)
	{
		$this -> _op -> insertOp('outstock', $item_id, $admin_name, $op_type, $remark, $item_value);
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
		$r = $this -> get("bill_no='{$bill['bill_no']}' and is_cancel=0", "a.outstock_id");
	    if ($r) {
	    	return false;
	    }

		if (is_array($details)) {
		    $stockAPI = new Admin_Models_API_Stock();
		    $stockAPI -> setLogicArea($logicArea);
		    if ($logicArea > 20) {
		        foreach($details as $row) {
    		        if (!$stockAPI -> checkVirtualProductStock($logicArea, $row['product_id'], $row['number'])) {
    		            return false;
    		        }
		        }
		    }
            
		    $lastId = $this -> _db -> insert($bill);
		    if (!$lastId) {
		        return false;
		    }
			foreach($details as $row) {
		        $row['outstock_id'] = $lastId;
		        
		        if (isset($row['batch_id'])) {
		            if ($bill['bill_type'] != 17) {
		                $row['cost'] = $this -> _db -> getCostByPid($row['product_id']);
		            }
		            $this -> _db -> insertDetail($row);
    				if ($update) {
    				    $where = array('lid' => $logicArea,
            	                       'batch_id' => $row['batch_id'],
            	                       'product_id' => $row['product_id'],
            	                       'status_id' => $row['status_id'],
            	                       );
        	            $stockAPI -> addStockOutNumber($row['number'], $where);
    				}
		        }
		        else {
		            if ($logicArea > 20) {
		                $outStocks = $stockAPI -> createVirtualOutStock($logicArea, $row['product_id'], $row['number'], $bill['bill_no']);
		            }
		            else {
    		            $outStocks = $stockAPI -> createSaleOutStock($row['product_id'], $row['number'], $update);
    		        }
    		        
    		        if (!$outStocks)    return false;
    		        
    		        foreach ($outStocks as $stock) {
    		            $row['number'] = $stock['number'];
    		            $row['batch_id'] = $stock['batch_id'] ? $stock['batch_id'] : 0;
    		            if ($bill['bill_type'] != 17) {
    		                $row['cost'] = $this -> _db -> getCostByPid($row['product_id']);
    		            }
    		            $this -> _db -> insertDetail($row);
    		        }
		        }
		    }
	    }
        
		return $lastId;
	}
	
	/**
     * 申请取消接口
     *
     * @param    string      $bill_no
     * @param    string      $remark
     * @return   void
     */
	public function cancelApi($bill_no, $remark = null, $type = 'cancel')
	{
	    $return = false;

	    $transportAPI = new Admin_Models_API_Transport();
	    $isSplit = $transportAPI -> isSplitOrder($bill_no);
	    if ($isSplit) {
	        $where = "bill_no like '{$bill_no}-%'";
	    }
	    else {
	        $isMerge = $transportAPI -> isMergeOrder($bill_no);
	        if ($isMerge) {
	            $where = "bill_no like '%{$bill_no}%'";
	        }
	        else {
	            $where = "bill_no = '{$bill_no}'";
	        }
	    }

	    $r = $this -> get($where.' and bill_type=1 and is_cancel=0', "a.outstock_id");
	    if ($r) {
	        $set = array ('is_cancel' => 1);
	        if($type == 'back') {
                $set['is_back'] = 1;
                $item_value = '订单申请返回';
            }else{
            	$item_value = '订单申请取消';
            }
	        foreach ($r as $item) {
		        $this -> _db -> update($set, "outstock_id = {$item['outstock_id']}");
		        $this -> addOp($item['outstock_id'], $this->_auth['admin_name'], 'cancel', $remark, $item_value);
		    }
            
		    $return = true;
	    }
        
        if ($return) {
	        $this -> _transport -> update(array('is_cancel' => 1), $where.' and is_cancel=0');
	    }
	    
	    return $return;
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

		if (!$data['remark']) {
			$this -> error = 'no_remark';
			return false;
		}
		if (!$data['ids']) {
			$this -> error = 'no_goods';
			return false;
		} 
		else {
		    $stockAPI = new Admin_Models_API_Stock();
	        foreach($data['ids'] as $key => $val) {
	        	if ($data['number'][$key] == 0) {
					$this -> error = 'no_number';
					return false;
				}
				if ($data['number'][$key] > $data['stock_number'][$key]) {
					$this -> error = 'less_able_number';
					return false;
				}
				$productData[$val][$data['batch_id'][$key]][$data['status_id'][$key]]['number'] += $data['number'][$key];
				$productData[$val][$data['batch_id'][$key]][$data['status_id'][$key]]['price'] = $data['price'][$key];
				
	        }
            
	        if (!$productData)  return false;
	        $amount = 0;
	        foreach ($productData as $productID => $data1) {
	            foreach ($data1 as $batchID => $data2) {
	                foreach ($data2 as $statusID => $info) {
	                    $where = array('lid' => $data['logic_area'],
        				               'status_id' => $statusID,
        				               'product_id' => $productID,
        				               'batch_id' => $batchID,
        				              );
        				$stockData = array_shift($stockAPI -> getProductOutStock($where));
        				if ($stockData['stock_number'] < $info['number']) {
        				    $this -> error = 'less_able_number';
        				    return false;
        			    }
        			    $amount += $stockData['cost'] * $info['number'];
	                }
	            }
	        }
		}
        
		$details = array();
		$add_time = time();
		$strTmp = $data['logic_area'] ==1 ? 'H' : 'J';
		$bill_no = Custom_Model_CreateSn::createSn($strTmp);
	    $row = array ('lid' => $data['logic_area'],
		              'bill_no' => $bill_no,
                      'bill_type' => $data['bill_type'],
                      'supplier_id' => (int)$data['supplier_id'],
			          'remark' => $data['remark'],
                      'recipient' => $data['bill_type'] == 10 ? $data['recipient'] : '',
                      'amount' => $data['bill_type'] == 2 ? $amount : 0,
                      'add_time' => $add_time,
                      'admin_name' => $this -> _auth['admin_name'],
                      );
	    foreach ($productData as $productID => $data1) {
	        foreach ($data1 as $batchID => $data2) {
	            foreach ($data2 as $statusID => $info) {
	                $tempDetail = array('product_id' => $productID,
        	                            'batch_id' => $batchID,
        	                            'status_id' => $statusID,
        	                            'number' => $info['number'],
        	                            'shop_price' => $info['price'] ? $info['price'] : 0,
        	                           );
        	        if ($data['bill_type'] == 2) {
        	            $product = array_shift($this -> _product -> fetch("product_id = '{$productID}'", 'p.cost'));
        	            $tempDetail['cost'] = $product['cost'] ? $product['cost'] : 0;
        	        }
	                $details[] = $tempDetail;
	            }
	        }
	    }
        
        //占用出库单
        if ($data['bill_type'] == 18) {
            $row['bill_status'] = 3;
            
            foreach ($productData as $productID => $data1) {
    	        foreach ($data1 as $batchID => $data2) {
    	            foreach ($data2 as $statusID => $info) {
    	                $where = array('lid' => $data['logic_area'],
    	                               'product_id' => $productID,
    	                               'batch_id' => $batchID,
    	                               'status_id' => $statusID,
    	                              );
    	                $stockAPI -> addStockOutNumber($info['number'], $where);
    	            }
    	        }
    	    }
        }
        
	    $this -> insertApi($row, $details, $data['logic_area'], false);
		
        //插入物流配送信息
		if($data['check_transport']=='1' && $data['bill_type']=='10' && isset($data['transport']) && count($data['transport']) > 0 ){
            $transport = $data['transport'];
            $sel = Zend_Json::decode(stripslashes($data['logistic']));
            $transport['province'] = $sel['province'];
            $transport['city'] = $sel['city'];
            $transport['area'] = $sel['area'];
            $transport['search_mod'] = $sel['search_mod'];
            $transport['logistic_name'] = $sel['logistic_name'];
            $transport['logistic_code'] = $sel['logistic_code'];
            $transport['logistic_fee_service'] = $sel['fee_service'];
            $transport['zip'] = $sel['zip'];
            $sel['price'] && $transport['logistic_price'] = $sel['price'];
            $sel['cod_price'] && $transport['logistic_price_cod'] = $sel['cod_price'];
            $transport['is_assign'] = 1;
            $transport['logistic_status'] = 1;
            $transport['add_time'] = time ();
            $transport['bill_no'] = $bill_no;
            $transport['admin_name'] = $this -> _auth['admin_name'];
            $transport['logistic_list'] = stripslashes($transport['logistic_list']);
            $this -> _db -> insertTransport($transport);
        }
        
		return true;
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
		$bill = array_shift($this -> _db -> get("b.outstock_id = '{$id}'"));
		if (!$bill) return false;
		
		$set = array ('is_cancel' => 1, 'lock_name' => '');
		if($this -> _db -> update($set, "outstock_id =$id")) {
		    $this -> addOp($id, $this -> _auth['admin_name'], 'cancel', $data['remark']);
		}
		
		if ($bill['bill_type'] == 18) {
		    $tempData = array('logic_area' => $bill['lid'],
		                      'bill_no' => $bill['bill_no'],
		                      'bill_type' => $bill['bill_type'],
		                      'remark' => $data['remark'],
		                      'is_check' => 1,
		                     );
		    $details = $this -> _db -> getDetail("b.outstock_id = '{$id}'");
		    foreach ($details as $detail) {
		        $tempData['product_id'][] = $detail['product_id'];
		        $tempData['batch_id'][] = $detail['batch_id'];
		        $tempData['status'][] = $detail['status_id'];
		        $tempData['number'][] = $detail['number'];
		    }
		    $this -> cancelCheck($tempData, $id);
		}
		
		return true;
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
        
        $stockAPI = new Admin_Models_API_Stock();
	    $set = array ('is_back' => 0, 'lock_name' => '');
        if ($data['is_check'] == 1) {
        	$set['is_cancel'] = 2;
        	$set['bill_status'] = 8;
        	foreach ($data['product_id'] as $k => $v) {
                $where = array('lid' => $data['logic_area'],
                               'status_id' => $data['status'][$k],
                               'product_id' => (int)$v,
                               'batch_id' => $data['batch_id'][$k],
                              );
                $stockAPI -> reduceStockOutNumber((int)$data['number'][$k], $where);
            }
            
            if ($data['bill_type'] == 1) {
                if ($data['is_back']) {
                    $this -> holdSaleOutStock($data['bill_no']);
                }
                $this -> _db -> delete(null, $id);
            }
        }
        else {
        	$set['is_cancel'] = 0;
        }
        
        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($data['bill_no']);
        
        if ($data['bill_type'] == 1) {
            $transport = array_shift($transportAPI -> get("bill_no = '{$data['bill_no']}'"));
            $transportAPI -> delete("bill_no = '{$data['bill_no']}' and is_cancel = 1");
			$transportAPI->deleteTransportSourceByTransportId($transport['tid']);
            if ($isSplit) {
                $this -> _order -> toStock($isSplit['batch_sn'], 'back', array('is_check' => $data['is_check'], 'remark' => $data['remark'], 'prepared' => 1));
            }
            else {
                $isMerge = $transportAPI -> isMergeOrder($data['bill_no'], 2);
                if ($isMerge) {
                    foreach ($isMerge as $batch_sn) {
                        $this -> _order -> toBeShippingBack($batch_sn);
                        $this -> _order -> toStock($batch_sn, 'back', array('is_check' => $data['is_check'], 'remark' => $data['remark'], 'prepared' => 1));
                    }
                }
                else {
        	        $this -> _order -> toStock($data['bill_no'], 'back', array('is_check' => $data['is_check'], 'remark' => $data['remark'], 'prepared' => 1));
        	    }
        	}
        }
        else {
            if ($this -> _db -> update($set, "outstock_id =$id")) {
    	    	$item_value = ($data['is_check'] == 1) ? '同意取消' : '拒绝取消';
    	        $this -> addOp($id, $this -> _auth['admin_name'], 'cancel-check', $data['remark'], $item_value);
    	    }
    	    
    	    $this -> _transport -> update(array ('is_cancel' => $set['is_cancel']), "bill_no = '{$data['bill_no']}' and is_cancel = 1");
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
	public function cancelCheckAdv($data, $id)
	{
	    $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($data['bill_no']);
        
        if ($isSplit) {
            $datas = $this -> getDetail("b.bill_no like '{$isSplit['batch_sn']}-%' and b.is_cancel = 1");
            if (!$datas)    return false;
            
            foreach ($datas as $stock) {
                $outstockInfo[$stock['outstock_id']][] = array('product_id' => $stock['product_id'],
                                                               'batch_id' => $stock['batch_id'],
                                                               'status_id' => $stock['status_id'],
                                                               'number' => $stock['number'],
                                                               'is_back' => $stock['is_back'],
                                                               'bill_no' => $stock['bill_no'],
                                                               'is_check' => $data['is_check'],
                                                               'logic_area' => $data['logic_area'],
                                                               'bill_type' => $data['bill_type'],
                                                               'remark' => $data['remark'],
                                                              );
            }
            
            foreach ($outstockInfo as $outstock_id => $outstock) {
                $data = '';
                $data['is_back'] = $outstock[0]['is_back'];
                $data['is_check'] = $outstock[0]['is_check'];
                $data['logic_area'] = $outstock[0]['logic_area'];
                $data['bill_type'] = $outstock[0]['bill_type'];
                $data['bill_no'] = $outstock[0]['bill_no'];
                $data['remark'] = $outstock[0]['remark'];
                foreach ($outstock as $detail) {
                    $data['product_id'][] = $detail['product_id'];
                    $data['batch_id'][] = $detail['batch_id'];
                    $data['status'][] = $detail['status_id'];
                    $data['number'][] = $detail['number'];
                }
                
                $this -> cancelCheck($data, $outstock_id);
            }
        }
        else {
            $this -> cancelCheck($data, $id);
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
            $stockAPI = new Admin_Models_API_Stock();
            $stockAPI -> setLogicArea($data['logic_area']);
            foreach ($data['product_id'] as $k => $v) {
        	    $where = array('lid' => $data['logic_area'],
        	                   'status_id' => $data['status'][$k],
        	                   'product_id' => (int)$v,
        	                   //'batch_id' => $data['batch_id'][$k],
        	                  );
        	    //$stockData = $stockAPI -> getProductOutStock($where);
        	    $stockData = $stockAPI -> getSaleProductOutStock($where);
				//if ($stockData[0]['stock_number'] < $data['number'][$k]) {
				if ($stockData[0]['able_number'] < $data['number'][$k]) {
                    $log_params = array(
                        'product_id'  => (int) $v,
                        'batch_sn'    => $data['bill_no'],
                        'need_number' => $data['number'][$k],
                        'able_number' => $stockData[0]['able_number'],
                        'created_id'  => $this->_auth['admin_id'],
                        'created_by'  => $this->_auth['admin_name'],
                        'created_ts'  => date('Y-m-d H:i:s'),
                    );

                    $stockAPI->addStockRemindLog($log_params);
				    $this -> error = 'less_able_number';
				    return false;
			    }
			}
            
            foreach ($data['product_id'] as $k => $v) {
        	    $where = array('lid' => $data['logic_area'],
        	                   'status_id' => $data['status'][$k],
        	                   'product_id' => (int)$v,
        	                   'batch_id' => $data['batch_id'][$k],
        	                  );
        	    $stockAPI -> addStockOutNumber((int)$data['number'][$k], $where);
			}
            
            $set['bill_status'] = 3;
        }
        else {
        	$set['bill_status'] = 2;
        }
        
	    if ($this -> _db -> update($set, "outstock_id =$id")) {
	    	$item_value = ($data['is_check'] == 1) ? '同意' : '已拒绝';
	        $this -> addOp($id, $this -> _auth['admin_name'], 'check', $data['remark'], $item_value);
	    }
	    
        if ($data['is_check'] == 1) {
            //调整出库单/虚拟出库单
            if (in_array($data['bill_type'], array(11, 17))) {
                $this -> sendByBillNo($data['bill_no']);
            }
        }
        
        
        //采购退货单
        if ($data['is_check'] == 1 && $data['bill_type'] == 2 && $data['amount'] > 0) {
		    $row = array('bill_no' => $data['bill_no'],
		                 'type' => 2,
		                 'supplier_id' => (int)$data['supplier_id'],
		                 'amount' => $data['amount'],
		                 'real_amount' => 0,
		                 'add_time' => time(),
		                 'update_time' => time()
		                );
		    $this -> _db -> insertPayment($row);
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
	    $set = array ('bill_status' => 4, 'lock_name' => '');
	    if ($this -> _db -> update($set, "outstock_id=$id and lock_name='{$this->_auth['admin_name']}'")) {
	        $this -> addOp($id, $this -> _auth['admin_name'], 'confirm', $data['remark']);
	    }
	    return true;
	}
	
	/**
     * 发货
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function send($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());        
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if ($data['total_number'] == 0) {
			$this -> error = 'wrong_number';
			return false;
		}
		if (in_array($data['bill_type'], array(1))  && !$data['logistic_no']) {
			//$this -> error = 'no_logistic';
			//return false;
		}
		$r = array_shift($this -> get("bill_no='{$data['bill_no']}' and is_cancel=0 and bill_status=5", "a.outstock_id", 1, 1));
	    if ($r) {
	    	$this -> error = 'error';
	    	return false;
	    }

	    $stockAPI = new Admin_Models_API_Stock();
        $logicArea = $data['logic_area'];

        foreach ($data['product_id'] as $k => $v) {
            $status_id = $data['status'][$k];
            
            $where = array('lid' => $logicArea,
                           'status_id' => $data['status'][$k],
                           'product_id' => $v,
                           'batch_id' => $data['batch_id'][$k],
                          );
	        $stockAPI -> addStockRealOutNumber($data['number'][$k], $where, $data['bill_no']);
        }
        
	    $set = array ('bill_status' => 5, 'lock_name' => '', 'finish_time' => time());
	    if ($this -> _db -> update($set, "outstock_id ='{$id}'")) {
	        $this -> addOp($id, $this -> _auth['admin_name'], 'send', $data['remark']);
	    }
        
        //更新出库成本
        if (!in_array($data['bill_type'], array(17))) {
            foreach ($data['product_id'] as $k => $v) {
                $cost = $this -> _db -> getCostByPid($v);
                $this -> _db -> updateDetail(array('cost' => $cost), "outstock_id = '{$data['outstock_id']}' and product_id = '{$v}'");
            }
        }
        
	    //接口处理
	    if ($data['bill_type'] == 1 && $data['logistic_no']) {
	        $sendTime = $data['logistic_time'] ? $data['logistic_time'] : time();
	        $set = array('logistic_no' => $data['logistic_no'], 'send_time' => $sendTime, 'logistic_status' => '1', 'deliver_weigh' => $data['deliver_weigh']);
	        
	        $transport = array_shift($this -> _transport -> get("bill_no = '{$data['bill_no']}'"));
	        if ($transport['logistic_code'] == 'self') {
	            $set['logistic_status'] = 2;
	        }
	        
		    $this -> _transport -> update($set, "bill_no='{$data['bill_no']}' and is_cancel=0");
		    $row = array(
		    	        'item_no' => $data['bill_no'],
		    	        'logistic_no' => $data['logistic_no'],
					    'logistic_code' => $data['logistic_code'],
				        'logistic_status' => '1',
				        'op_time' => time(),
				        'admin_name' => $this -> _auth['admin_name'],
				        'remark' => '物流公司已取货',
					    );
		    $this -> _transport -> insertTrack($row);
		    $this -> _order -> toStock($data['bill_no'], 'shipped', array('logistic_no' => $data['logistic_no'], 'logistic_time' => $sendTime, 'invoice_no' => $transport['invoice_no']));
		    if ($transport['logistic_code'] == 'self') {
		        $this -> _order -> toStock($data['bill_no'], 'signed', array('bill_no' => $data['bill_no']));
		    }
		    
		    $row['province'] = $transport['province'];
		    $row['city'] = $transport['city'];
		    $logisticAPI = new Admin_Models_API_Logistic();
		    $logisticAPI -> pushKuaidi100($row);
	    }
	    
        if ($data['bill_type'] == 10 ) {
            $lid = $data['recipient'] ? $data['recipient'] : 21;
            $details = array();
            $r = $this -> getDetail("a.outstock_id = '{$id}'");
            $add_time = time ();
            $row = array('lid' => $lid,
                         'item_no' => $data['bill_no'],
                         'bill_no' => $data['bill_no'],
                         'bill_type' => '15',
                         'bill_status' => '3',
                         'remark' => $data['remark'],
                         'add_time' => $add_time,
                         'recipient' => $data['recipient'],
                         'admin_name' => $this -> _auth['admin_name'],
                        );
            foreach ($r as $v) {
                $details[] = array ('product_id' => $v['product_id'],
                                    'batch_id' => $v['batch_id'],
                                    'plan_number' => $v['number'],
                                    'shop_price' => $v['cost'],
                                   );
            }
            $inStock = new Admin_Models_API_InStock();
            $inStock -> insertApi($row, $details, $lid, true);

            //运输单接口处理
            if ($data['logistic_no']) {
                $sendTime = $data['logistic_time'] ? $data['logistic_time'] : time();
                $this -> _transport -> update(array('logistic_no' => $data['logistic_no'], 'send_time' => $sendTime, 'logistic_status' => '1', 'deliver_weigh' => $data['deliver_weigh']), "bill_no='{$data['bill_no']}' and is_cancel=0");
                $row = array('item_no' => $data['bill_no'],
                             'logistic_no' => $data['logistic_no'],
                             'logistic_code' => $data['logistic_code'],
                             'logistic_status' => '1',
                             'op_time' => time(),
                             'admin_name' => $this -> _auth['admin_name'],
                             'remark' => '物流公司已取货',
                            );
                $this -> _transport -> insertTrack($row);
                    
                $transport = array_shift($this -> _transport -> get("bill_no = '{$data['bill_no']}'"));
        		$row['province'] = $transport['province'];
        		$row['city'] = $transport['city'];
        		$logisticAPI = new Admin_Models_API_Logistic();
        		$logisticAPI -> pushKuaidi100($row);
            }
        }
        
        //返修出库单/借货出库单/送检出库单生成对应入库单
	    if ($data['bill_type'] == 3 || $data['bill_type'] == 4 || $data['bill_type'] == 12) {
		    $r = $this -> getDetail("a.outstock_id = '{$id}'");
			$bill_no = Custom_Model_CreateSn::createSn();
		    $row = array ('lid' => $logicArea,
			              'item_no' => $data['bill_no'],
			              'bill_no' => $bill_no,
	                      'bill_type' => $data['bill_type'],
	                      'bill_status' => '3',
				          'remark' => $data['remark'],
	                      'add_time' => time(),
	                      'admin_name' => $this -> _auth['admin_name'],
	                     );
	        $details = array();
	        foreach ($r as $v) {
				$details[] = array('product_id' => $v['product_id'],
				                   'batch_id' => $v['batch_id'],
		                           'plan_number' => $v['number'],
		                           'status_id' => 1,
		                           'shop_price' => $v['cost'],
		                          );
	        }
	        $inStock = new Admin_Models_API_InStock();
	    	$inStock -> insertApi($row, $details, $logicArea, true);
	    }
	    
	    //渠道进仓退上海总仓
	    if ($data['bill_type'] == 16) {
		    $details = array();
		    $r = $this -> getDetail("a.outstock_id = '{$id}'");
		    $add_time = time ();
			$bill_no = Custom_Model_CreateSn::createSn();
		    $row = array ('lid' => 1,
			              'item_no' => $data['bill_no'],
			              'bill_no' => $bill_no,
	                      'bill_type' => 10,
	                      'bill_status' => '3',
				          'remark' => $data['remark'],
	                      'add_time' => $add_time,
	                      'admin_name' => $this -> _auth['admin_name'],
	                     );
	        foreach ($r as $v) {
				$details[] = array('product_id' => $v['product_id'],
				                   'batch_id' => 0,
		                           'plan_number' => $v['number'],
		                           'status_id' => 6,
		                           'shop_price' => $v['cost'],
		                          );
	        }
	        $inStock = new Admin_Models_API_InStock();
	    	$inStock -> insertApi($row, $details, 1, true);
	    }
	    
	    //渠道进仓刷单退上海总仓
	    if ($data['bill_type'] == 19) {
		    $details = array();
		    $r = $this -> getDetail("a.outstock_id = '{$id}'");
		    $add_time = time ();
			$bill_no = Custom_Model_CreateSn::createSn();
		    $row = array ('lid' => 1,
			              'item_no' => $data['bill_no'],
			              'bill_no' => $bill_no,
	                      'bill_type' => 19,
	                      'bill_status' => '3',
				          'remark' => $data['remark'],
	                      'add_time' => $add_time,
	                      'admin_name' => $this -> _auth['admin_name'],
	                     );
	        foreach ($r as $v) {
				$details[] = array('product_id' => $v['product_id'],
				                   'batch_id' => 0,
		                           'plan_number' => $v['number'],
		                           'status_id' => 6,
		                           'shop_price' => $v['cost'],
		                          );
	        }
	        $inStock = new Admin_Models_API_InStock();
	    	$inStock -> insertApi($row, $details, 1, true);
	    }
	    
	    //虚拟出库单计算移动成本
	    if ($data['bill_type'] == 17) {
	        $productAPI = new Admin_Models_API_Product();
	        $r = $this -> getDetail("a.outstock_id = '{$id}'");
	        foreach ($r as $v) {
	            $productAPI -> calculateProductCostByOut($v['product_id'], $v['number'], $v['cost']);
	        }
	    }
	    if($data['bill_type'] == 1){
	        $tmp = $this -> _order -> getOrderBatch(array('batch_sn'=>$data['bill_no']));
	        $mobile = $tmp[0]['addr_mobile'];
    	    $smsApi = new Custom_Model_Sms();
    	    //$msg = "亲，您的海淘订单{$data['bill_no']}已从日本仓发货，顺丰快递，运单号{$data['logistic_no']}，近期请保持手机畅通！";
    	    $order_sn = $tmp[0]['external_order_sn'] ? $tmp[0]['external_order_sn'] : $data['bill_no'];
    	    $msg = "您的订单{$order_sn}已从日本发货，运单号为 顺丰{$data['logistic_no']}，预计3-7天送达。如有疑问请致电400-649-3883";
    	    
    	    $tmp = $smsApi -> send($mobile, $msg);
	    }
	    return true;
	}
	
	/**
     * 发货(按发货单号)
     *
     * @param    string     $billNo
     * @param    float      $weight
     * @return   string
     */
	public function sendByBillNo($billNo, $weight = 0)
	{
	    $r = array_shift($this -> get("bill_no='{$billNo}' and is_cancel = 0 and bill_status = 5", "a.outstock_id", 1, 1));
	    if ($r) {
	    	$this -> error = 'error';
	    	return false;
	    }
        
	    $details = $this -> getDetail("b.bill_no = '{$billNo}'");
	    if (!$details)  return false;
        
	    $data = array('bill_no' => $billNo,
	                  'logic_area' => $details[0]['lid'],
	       	          'bill_type' => $details[0]['bill_type'],
	       	          'total_number' => 0,
	       	          'logistic_no' => $details[0]['logistic_no'],
	       	          'deliver_weigh' => $weight ? $weight : 0,
	       	         );
	    
	    if ($details[0]['bill_type'] == 1) {
            $transport = array_shift($this -> _transport -> get("bill_no = '{$billNo}'"));
            $data['logistic_code'] = $transport['logistic_code'];
        }
	    
	    foreach ($details as $index => $detail) {
	        $data['total_number'] += $detail['number'];
	        $data['product_id'][$index] = $detail['product_id'];
	        $data['batch_id'][$index] = $detail['batch_id'];
	        $data['status'][$index] = $detail['status_id'];
	        $data['number'][$index] = $detail['number'];
	    }

	    return $this -> send($data, $details[0]['outstock_id']);
	}
	
	/**
     * 销售出库单批量发货
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function batchSend($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());         
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
     
        $set = array ('bill_status' => 5, 'lock_name' => '');
        if ($this -> _db -> update($set, "outstock_id ='$id'")) {
            $this -> addOp($data['outstock_id'], $this -> _auth['admin_name'], 'send', $data['remark']);
        }
        $sendTime = $data['logistic_time'] ? $data['logistic_time'] : time();
        $this -> _transport -> update(array('logistic_no' => $data['logistic_no'], 'send_time' => $sendTime, 'logistic_status' => '1'), "bill_no='{$data['bill_no']}' and is_cancel=0");
        $row = array(
                    'item_no' => $data['bill_no'],
                    'logistic_no' => $data['logistic_no'],
                    'logistic_code' => $data['transport']['logistic_code'],
                    'logistic_status' => '1',
                    'op_time' => time(),
                    'admin_name' => $this -> _auth['admin_name'],
                    'remark' => '物流公司已取货',
                    );
        $this -> _transport -> insertTrack($row);
        $this -> _order -> toStock($data['bill_no'], 'shipped', array('logistic_no' => $data['logistic_no'], 'logistic_time' => $sendTime));

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
	    		$this -> _db -> update($set, "$where and outstock_id=$v");
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
			         'no_remark'=>'请填写备注!',
			         'less_able_number'=>'可用库存不足!',
			         'wrong_number'=>'数量有误!',
			         'no_logistic'=>'请填写运单号',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
	/**
     * 出出库查询动作
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
		    $where['outstock_id'] && $whereSql .= " and a.outstock_id={$where['outstock_id']}";
	        $where['bill_type'] && $whereSql .= " and bill_type={$where['bill_type']}";
            $where['recipient'] && $whereSql .= " and recipient='{$where['recipient']}'";
	        $where['bill_status']<>'' && $whereSql .= " and bill_status='{$where['bill_status']}'";
	        $where['admin_name'] && $whereSql .= " and admin_name LIKE '%" . $where['admin_name'] . "%'";
	        $where['supplier_id'] && $whereSql .= " and supplier_id='{$where['supplier_id']}'";
		    $where['bill_no'] && $whereSql .= " and (bill_no LIKE '%" . $where['bill_no'] . "%' or item_no LIKE '%" . $where['bill_no'] . "%')";
		    $where['no'] && $whereSql .= " and (bill_no = '{$where['no']}' or item_no = '{$where['no']}')";
 	        $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    $where['product_sn'] && $whereSql .= " and product_sn LIKE '%" . $where['product_sn'] . "%'";
		    $where['local_sn'] && $whereSql .= " and local_sn LIKE '%" . $where['local_sn'] . "%'";
		    $where['goods_name'] && $whereSql .= " and product_name LIKE '%" . $where['goods_name'] . "%'";
		    $where['hideExternal'] && $whereSql .= " and bill_no not like '%:%'";
		    $where['logistic_no'] && $whereSql .= " and logistic_no='{$where['logistic_no']}'";
		    if ($where['scan'] !== '' && $where['scan'] !== null) {
		        $whereSql .= " and scan = '{$where['scan']}'";
		    }
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (b.add_time between $fromdate and $todate)";
	        }

            if ($where['fromdate2'] && $where['todate2']) {
			    $fromdate2 = strtotime($where['fromdate2']);
			    $todate2 = strtotime($where['todate2']) + 86400;
			    if($fromdate2 <= $todate2) $whereSql .= " and (b.finish_time between $fromdate2 and $todate2)";
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
				$whereSql .= " and bill_status=3 and is_cancel=0 and bill_type<>1";
				break;
			case 'send-list':
				$whereSql .= " and bill_status=4 and is_cancel=0";
				break;
			case 'batch-send-list':
				$whereSql .= " and bill_status=4 and is_cancel=0 and logistic_no is not null";
				break;
			case 'cancel-list':
				$whereSql .= " and is_cancel=1";
				break;
			default :
				$whereSql .= " and is_cancel<2";
		}
        $datas = $this -> get($whereSql, 'a.*,b.*,s.supplier_name',$page);
        if ($datas) {
            foreach ($datas as $index => $data) {
                $datas[$index]['bill_no_str'] = str_replace(',', '<br>', $data['bill_no']);
            }
        }
        $total = $this -> getCount();
        $result['datas'] = $datas;
        $result['total'] = $total;
        $result['sum'] = $this -> _db -> getSumCost($whereSql);
        return $result;
    }
    
    /**
     * 删除出库单
     *
     * @return   void
     */
	public function delete($bill_no, $restore = false)
	{
	    if ($restore) {
	        $details = $this -> _db -> getDetail("b.bill_no = '{$bill_no}'");
	        $stockAPI = new Admin_Models_API_Stock();
	        $stockAPI -> setLogicArea($details[0]['lid']);
	        foreach ($details as $detail) {
	            $stockAPI -> restoreSaleOutStock($detail['product_id'], $detail['batch_id'], $detail['number']);
	        }
	    }
	    
	    $this -> _db -> delete($bill_no);
	}
	
	/**
     * 删除出库单(渠道订单使用)
     *
     * @return   void
     */
	public function deleteExternal($bill_no)
	{
	    $details = $this -> _db -> getDetail("b.bill_no = '{$bill_no}'");
	    if (!$this -> _stockAPI) {
	        $this -> _stockAPI = new Admin_Models_API_Stock();
	        $this -> _stockAPI -> setLogicArea($details[0]['lid']);
	    }

	    foreach ($details as $detail) {
	        $this -> _stockAPI -> restoreSaleOutStock($detail['product_id'], $detail['batch_id'], $detail['number'], false);
	    }
	    
	    $this -> _db -> delete($bill_no);
	}
	
	/**
     * 获得合单或拆单的订单号数组
     *
     * @param    string     $bill_no
     * @return   array   
     */
	public function getMergeSplitOrderSNArray($bill_no)
	{
	    $tempArray = explode(',', $bill_no);
	    for ($i = 0; $i < count($tempArray); $i++) {
	        $tempArr = explode('-', $tempArray[$i]);
	        $result[$tempArray[$i]] = $tempArr[0];
	    }
	    return $result;
	}
	
	/**
     * 释放销售产品占用库存
     *
     * @param    string     $bill_no
     * @return   boolean   
     */
    public function releaseSaleOutStock($bill_no) {
        $datas = $this -> getDetail("b.bill_no = '{$bill_no}'");
        if (!$datas)    return false;
        
        $stockAPI = new Admin_Models_API_Stock();
        
        foreach ($datas as $data) {
            $stockAPI -> releaseSaleProductStock($data['product_id'], $data['number']);
        }
        
        return true;
    }
	
	/**
     * 增加销售产品占用库存
     *
     * @param    string     $bill_no
     * @return   boolean   
     */
    public function holdSaleOutStock($bill_no) {
        $datas = $this -> getDetail("b.bill_no = '{$bill_no}'");
        if (!$datas)    return false;
        
        $stockAPI = new Admin_Models_API_Stock();
        
        foreach ($datas as $data) {
            $stockAPI -> holdSaleProductStock($data['product_id'], $data['number']);
        }
        
        return true;
    }
    
    /**
     * 增加产品占用库存
     *
     * @param    string     $bill_no
     * @return   boolean   
     */
    public function holdOutStock($bill_no) {
        $datas = $this -> getDetail("b.bill_no = '{$bill_no}'");
        if (!$datas)    return false;
        
        if (!$this -> _stockAPI) {
            $this -> _stockAPI = new Admin_Models_API_Stock();
        }
        
        foreach ($datas as $data) {
            $this -> _stockAPI -> addStockOutNumber($data['number'], array('lid' => $data['lid'], 'batch_id' => $data['batch_id'], 'product_id' => $data['product_id'], 'status_id' => $data['status_id']));
        }
        
        return true;
    }
    
    /**
     * 更新数据
     *
     * @param    array    $data
     * @param    string   $where
     * @return   void
     */
	public function update($data, $where)
	{
	    $this -> _db -> update($data, $where);
	    return true;
	}
	
	/**
     * 销售单返回到打印
     *
     * @param    string    $billNo
     * @return   void
     */
	public function backToPrint($billNo)
	{
	    $this -> _db -> update(array('bill_status' => 3), "bill_no = '{$billNo}'");
	    
	    $transportAPI = new Admin_Models_DB_Transport();
	    $transportAPI -> update(array('is_confirm' => 0), "bill_no = '{$billNo}'");
	}
}