<?php

class Admin_Models_API_Status
{
	/**
     * DB对象
     */
	private $_db = null;
	
	/**
     * Admin certification
     * @var array
     */
	private $_auth = null;
	
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
		$this -> _db = new Admin_Models_DB_Status();
		$this -> _op = new Admin_Models_DB_StockOp();
		$this -> _product = new Admin_Models_DB_Product();
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
	public function get($where = null, $fields = 'a.*,b.*,p.*,pb.batch_no', $page=null, $pageSize = null, $orderBy = null)
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
     */	public function getDetail($where = null, $fields = 'a.*,b.*,p.*,pb.batch_no', $page=null, $pageSize = null, $orderBy = null)
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
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
        $where && $where = " and $where";
        return $this -> _op -> getOp("item='status' $where");
	}
	
	/**
     * 添加操作日志记录
     *
     * @return   int      lastInsertId
     */
	public function addOp($item_id, $admin_name, $op_type, $remark = null, $item_value = null)
	{
		$this -> _op -> insertOp('status', $item_id, $admin_name, $op_type, $remark, $item_value);
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
		    $stockAPI = new Admin_Models_API_Stock();
	        foreach($data['ids'] as $key => $val) {
	        	if ($data['number'][$key] == 0) {
					$this -> error = 'no_number';
					return false;
				}
				
				$where = array('lid' => $data['logic_area'],
				               'status_id' => $data['status_id'][$key],
				               'product_id' => $val,
				               'batch_id' => $data['batch_id'][$key],
				              );
			    $stockData = $stockAPI -> getProductOutStock($where);
				if ($stockData[0]['stock_number'] < $data['number'][$key]) {
				    $this -> error = 'less_able_number';
					return false;
				}
	        }
		}
		$add_time = time ();
		$bill_no = Custom_Model_CreateSn::createSn();
	    $row = array('admin_name' => $this -> _auth['admin_name'],
		             'bill_no' => $bill_no,
		             'lid' => $data['logic_area'],
			         'remark' => $data['remark'],
                     'add_time' => $add_time,
                    );
	    $last_id = $this -> _db -> insert($row);
	    
	    foreach($data['ids'] as $key => $val) {
			$row = array('sid' => $last_id,
	                     'product_id' => $val,
	                     'batch_id' => $data['batch_id'][$key],
	                     'ostatus' => $data['status_id'][$key],
	                     'nstatus' => $data['nstatus'][$key],
	                     'number' => $data['number'][$key],
	                    );
			$this -> _db -> insertDetail($row);
			
			$where = array('lid' => $data['logic_area'],
				           'status_id' => $data['status_id'][$key],
				           'product_id' => $val,
				           'batch_id' => $data['batch_id'][$key],
				          );
			$stockAPI -> addStockOutNumber($data['number'][$key], $where);
			
			$where = array('lid' => $data['logic_area'],
				           'status_id' => $data['nstatus'][$key],
				           'product_id' => $val,
				           'batch_id' => $data['batch_id'][$key],
				          );
			$stockAPI -> addStockInNumber($data['number'][$key], $where);
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
		$set = array ('is_cancel' => 1, 'lock_name' => '');
		if($this -> _db -> update($set, "sid =$id")) {
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
            $stockAPI = new Admin_Models_API_Stock();
        	$set['is_cancel'] = 2;
        	$set['bill_status'] = 8;
	        foreach ($data['ids'] as $key => $val) {
	            $where = array('lid' => $data['logic_area'],
    	                       'status_id' => $data['ostatus'][$key],
    	                       'product_id' => $data['product_id'][$key],
    	                       'batch_id' => $data['batch_id'][$key],
    	                      );
    	        $stockAPI -> reduceStockOutNumber($data['number'][$key], $where);
    	        
                $where = array('lid' => $data['logic_area'],
    	                       'status_id' => $data['nstatus'][$key],
    	                       'product_id' => $data['product_id'][$key],
    	                       'batch_id' => $data['batch_id'][$key],
    	                      );
    	        $stockAPI -> reduceStockInNumber($data['number'][$key], $where);
			}
        }
        else {
        	$set['is_cancel'] = 0;
        }
	    if ($this -> _db -> update($set, "sid =$id")) {
	    	$item_value = ($data['is_check'] == 1) ? '同意取消' : '拒绝取消';
	        $this -> addOp($id, $this -> _auth['admin_name'], 'cancel-check', $data['remark'], $item_value);
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
        
        $stockAPI = new Admin_Models_API_Stock();

	    $set = array ('bill_status' => $data['is_check'], 'lock_name' => '');
        if ($data['is_check'] == 1){
    	    foreach ($data['product_id'] as $key => $val) {
    	        $where = array('lid' => $data['logic_area'],
    	                       'status_id' => $data['ostatus'][$key],
    	                       'product_id' => $val,
    	                       'batch_id' => $data['batch_id'][$key],
    	                      );
    	        $stockAPI -> addStockRealOutNumber($data['number'][$key], $where, $data['bill_no']);
				
				$where = array('lid' => $data['logic_area'],
    	                       'status_id' => $data['nstatus'][$key],
    	                       'product_id' => $val,
    	                       'batch_id' => $data['batch_id'][$key],
    	                      );
    	        $stockAPI -> addStockRealInNumber($data['number'][$key], $where, $data['bill_no']);
			}
			
			$set['finish_time'] = time();
        }
        else {
        	foreach ($data['product_id'] as $key => $val) {
        	    $where = array('lid' => $data['logic_area'],
    	                       'status_id' => $data['ostatus'][$key],
    	                       'product_id' => $val,
    	                       'batch_id' => $data['batch_id'][$key],
    	                      );
    	        $stockAPI -> reduceStockOutNumber($data['number'][$key], $where);
	    	    
	    	    $where = array('lid' => $data['logic_area'],
    	                       'status_id' => $data['nstatus'][$key],
    	                       'product_id' => $val,
    	                       'batch_id' => $data['batch_id'][$key],
    	                      );
    	        $stockAPI -> reduceStockInNumber($data['number'][$key], $where);
			}
        }
        
	    if ($this -> _db -> update($set, "sid =$id")) {
	    	$item_value = ($data['is_check'] == 1) ? '同意' : '已拒绝';
	        $this -> addOp($id, $this -> _auth['admin_name'], 'check', $data['remark'], $item_value);
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
	    		$this -> _db -> update($set, "$where and sid=$v");
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
			         'less_able_number'=>'可用库存不足!',
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
     * @param    int    $bill_action
     *
     * @return   void
     */
    public function search($where, $act, $page, $logicArea)
    {
		$whereSql = "b.lid = '{$logicArea}'";
		if (is_array($where)) {
	        $where['bill_status']<>'' && $whereSql .= " and bill_status='{$where['bill_status']}'";
	        $where['admin_name'] && $whereSql .= " and admin_name LIKE '%" . $where['admin_name'] . "%'";
	        $where['bill_no'] && $whereSql .= " and bill_no LIKE '%" . $where['bill_no'] . "%'";
	        $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    $where['product_sn'] && $whereSql .= " and product_sn LIKE '%" . $where['product_sn'] . "%'";
		    $where['local_sn'] && $whereSql .= " and local_sn LIKE '%" . $where['local_sn'] . "%'";
		    $where['product_name'] && $whereSql .= " and product_name LIKE '%" . $where['product_name'] . "%'";
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (b.add_time between $fromdate and $todate)";
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
			case 'cancel-list':
				$whereSql .= " and is_cancel=1";
				break;
		}
		
        $datas = $this -> get($whereSql, 'a.*,b.*',$page);
        $total = $this -> getCount();
        $result['datas'] = $datas;
        $result['total'] = $total;
        return $result;
    }
    
}