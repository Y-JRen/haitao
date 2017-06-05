<?php

class Admin_Models_API_Cod
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
		$this -> _db = new Admin_Models_DB_Cod();
		$this -> _op = new Admin_Models_DB_StockOp();
		$this -> _transport = new Admin_Models_DB_Transport();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $page=null, $pageSize = null)
	{
		return $this -> _db -> get($where, $page, $pageSize);
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getClear($where = null, $page=null, $pageSize = null)
	{
		$whereSql = "1=1";
		if (is_array($where)) {
		    $where['clear_no'] && $whereSql .= " and clear_no LIKE '%" . $where['clear_no'] . "%'";
		    $where['consignee'] && $whereSql .= " and consignee LIKE '%" . $where['consignee'] . "%'";
		    $where['logistic_code'] && $whereSql .= " and logistic_code = '" . $where['logistic_code'] . "'";
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (clear_time between $fromdate and $todate)";
	        }
	        if ($where['logistic_no']) {
	            $transport = array_shift($this -> _db -> getTransport($where['logistic_no']));
	            if (!$transport)   return false;
	            
	            $whereSql .= " and tids like '%{$transport['tid']}%'";
	        }
		}else {
			$whereSql = $where;
		}

		return $this -> _db -> getClear($whereSql, $page, $pageSize);
	}
	
	/**
     * 结算查看
     *
     * @param    int       $id
     * @return   array
     */
	public function viewClear($id)
	{
		$data = array_shift($this -> _db -> getClear("id=$id"));
		if ($data['tids']){
			$ids = $data['tids'];
			$result['details'] = $this -> _transport -> get("tid in($ids)");
		}
		$result['data'] = $data;
		return $result;
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
     * 更新数据
     *
     * @param    array    $data
     * @param    string   $where
     * @return   array
     */
	public function update($data, $where, $type = null)
	{
        return $this -> _db -> update($data, $where, $type);
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
        return $this -> _op -> getOp("item='cod-change' $where");
	}
	
	/**
     * 添加操作日志记录
     *
     * @return   int      lastInsertId
     */
	public function addOp($item_id, $admin_name, $op_type, $remark = null)
	{
		$this -> _op -> insertOp('cod-change', $item_id, $admin_name, $op_type, $remark);
	}
	
	/**
     * 代收货款变更
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function codChange($data, $id)
	{
		$row = array(
		             'tid' => $id,
		             'tmp_amount' => $data['change_amount'] - $data['old_amount'],
		             'change_remark' => $data['change_remark'],
		             'change_time' => time(),
		             'admin_name' => $this -> _auth['admin_name'],
		             );
	    return $this -> _db -> insert($row);
	}
	
	/**
     * 代收货款结算
     *
     * @param    array    $data
     * @return   void
     */
	public function clear($data)
	{
		if($data['ids']) {
			$add_time = time ();
			$ids = implode(',', $data['ids']);
			$fromdate = strtotime($data['fromdate']);
			$todate = strtotime($data['todate']);
			$row = array(
			             'tids' => $ids,
			             'clear_no' => Custom_Model_CreateSn::createSn(),
			             'logistic_code' => $data['logistic_code'],
			             'adjust_amount' => $data['adjust_amount'],
			             'real_amount' => $data['real_amount'],
			             'commission' => array_sum($data['commission']) ? array_sum($data['commission']) : 0,
			             'adjust_remark' => $data['adjust_remark'],
			             'fromdate' => $fromdate,
			             'todate' => $todate,
			             'clear_time' => $add_time,
			             'admin_name' => $this -> _auth['admin_name'],
			             );
		    
		    foreach ($data['ids'] as $index => $v) {
			    $set = array ('cod_status' => 1, 'logistic_price_cod' => $data['commission'][$index] ? $data['commission'][$index] : 0);
				$this -> _transport -> update($set, "tid = '{$v}'");
			}
			
			$transports = $this -> _transport -> get("tid in ($ids) and logistic_status in (1,3)");
			if ($transports) {
			    $transportAPI = new Admin_Models_API_Transport();
			    foreach ($transports as $transport) {
			        $transport['logistic_status'] = 2;
			        $transportAPI -> track($transport);
			    }
			}
			
		    return $this -> _db -> insertClear($row);
	    }
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
	    $set = array ('is_check' => $data['is_check'], 'lock_name' => '');
	    if ($this -> _db -> update($set, "cid = '{$id}'")) {
	        $this -> addOp($id, $this -> _auth['admin_name'], 'check', $data['remark']);
	        if ($data['is_check'] == 1) {
	        	$logistic = new Admin_Models_DB_Logistic();
	        	foreach ($logistic -> getLogisticList() as $key => $value) {
		    	    $logisticList[$value['logistic_code']] = $value;
		    	}
		    	$r = $logisticList[$data['logistic_code']];
		        $set = array ('change_amount' => $data['change_amount'], 'logistic_price_cod' => ($data['amount']+$data['change_amount'])*$r['cod_rate']);
			    
			    $this -> _transport -> update($set, "tid = {$data['tid']}");
			    
			    $order = new Admin_Models_API_Order();
			    $order -> toStock($data['bill_no'], 'change', array('change_amount' => $data['change_amount']));
		    }
	    }
	    return true;
	}
	
	/**
     * 锁定/解锁数据  (针对 shop_cod_change)
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
	    		$this -> _db -> update($set, "$where and cid=$v");
			}
		}
        return true;
	}
	
	/**
     * 锁定/解锁数据  (针对 shop_transport)
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function lockTransport($datas, $val)
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
	    		$this -> _db -> updateTransport($set, "$where and tid=$v");
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
			         'no_amount'=>'请填写变更后金额!',
			         'no_remark'=>'请填写理由!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
	/**
     * 查询动作
     * 
     * @param    array  $where
     * @param    int    $act
     * @param    int    $page
     *
     * @return   void
     */
    public function search($where, $act, $page)
    {
		$whereSql = "1=1";
		if (is_array($where)) {
	        $where['bill_type'] && $whereSql .= " and bill_type={$where['bill_type']}";
		    $where['send_type'] && $whereSql .= " and logistic_code<>'ems'";
		    $where['is_check'] && $whereSql .= " and is_check=1";
		    $where['cod_status'] != '' && $whereSql .= " and cod_status=".$where['cod_status'];
		    $where['is_cod'] && $whereSql .= " and is_cod=1";
		    $where['is_protect'] && $whereSql .= " and is_protect=1";
		    $where['bill_no'] && $whereSql .= " and bill_no LIKE '%" . $where['bill_no'] . "%'";
		    $where['clear_no'] && $whereSql .= " and clear_no LIKE '%" . $where['clear_no'] . "%'";
		    $where['logistic_no'] && $whereSql .= " and logistic_no LIKE '%" . $where['logistic_no'] . "%'";
		    $where['consignee'] && $whereSql .= " and consignee LIKE '%" . $where['consignee'] . "%'";
		    $where['search_mod'] && $whereSql .= " and search_mod = '" . $where['search_mod'] . "'";
		    $where['province_id'] && $whereSql .= " and province_id = '" . $where['province_id'] . "'";
		    $where['city_id'] && $whereSql .= " and city_id = '" . $where['city_id'] . "'";
		    $where['area_id'] && $whereSql .= " and area_id = '" . $where['area_id'] . "'";
		    $where['logistic_status'] && $whereSql .= " and logistic_status = '" . $where['logistic_status'] . "'";
		    $where['logistic_code'] && $whereSql .= " and logistic_code = '" . $where['logistic_code'] . "'";
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (send_time between $fromdate and $todate)";
	        }
	        if ($where['is_lock']) {
		    	$lock_name = $where['is_lock'] == 'yes' ? $this -> _auth['admin_name'] : '';
		    	$whereSql .= " and a.lock_name = '" . $lock_name . "'";
		    }
		} else {
			$whereSql = $where;
		}
		switch($act){
			case 'check-list':
				$whereSql .= " and is_check=0";
				break;
		}
		
        $datas = $this -> get($whereSql, $page);
        $total = $this -> getCount();
        $result['datas'] = $datas;
        $result['total'] = $total;
        return $result;
    }
    
}