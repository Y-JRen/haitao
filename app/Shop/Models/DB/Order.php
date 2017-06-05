<?php
class Shop_Models_DB_Order
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 订单表名
     * 
     * @var    string
     */
	private $_table = 'shop_order';
	private $_tableOrderBatch = 'shop_order_batch';
	private $_tableLogistic = 'shop_logistic';
	private $_tableLogisticAreaPrice = 'shop_logistic_area_price';
	private $_tableLogisticArea = 'shop_logistic_area';
    private $_tableStockStatus = 'shop_stock_status';
    private $_tableOrderBatchAdjust = 'shop_order_batch_adjust';
	
	/**
     * 订单表名
     * 
     * @var    string
     */
	private $_tableOrderBatchGoods = 'shop_order_batch_goods';
	
	/**
     * 分页
     * 
     * @var    int
     */
	private $_pageSize = 20;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	/**
     * 取得订单列表信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getOrder($where = null, $page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null && is_array($where)) {
			$whereSql = " WHERE 1=1 and b.order_id > 0  ";
			foreach ($where as $key => $value)
			{
				if (is_array($value)) {
				    $whereSql .= " AND $key in ('".implode(',', $value)."')";
				}
				else    $whereSql .= " AND $key='$value'";
			}
		}
        $sql = "SELECT a.order_id,a.user_id,a.user_name,a.invoice,a.invoice_content,a.parent_id,a.parent_param,a.proportion,";
        $sql .= "b.* FROM {$this->_table} a left join {$this->_tableOrderBatch} b on a.order_sn = b.order_sn ";
        $sql .= "{$whereSql} order by order_batch_id desc {$limit}";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得订单详细信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getOrderDetail($where = null)
	{
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		$sql = 'SELECT * FROM `' . $this -> _table . '` as A LEFT JOIN `' . $this -> _tableOrderBatch . '` as B ';
        $sql .= 'ON A.order_sn=B.order_sn LEFT JOIN `' . $this -> _tableOrderBatchGoods . '` as C ';
        $sql .= 'ON B.batch_sn=C.batch_sn ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得订单列表数量信息
     *
     * @param    string    $where
     * @return   int
     */
	public function getOrderCount($where = null)
	{
		if ($where != null && is_array($where)) {
		    $whereSql = ' WHERE 1=1 and b.order_id > 0 ';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
        $sql = "SELECT count(*) as count ";
        $sql .= "FROM {$this->_table} a left join {$this->_tableOrderBatch} b on a.order_sn = b.order_sn {$whereSql}";
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 更新会员订单支付方式
     *
     * @param    array    $data
     * @param    int      $batchSN
     * @return   int      lastInsertId
     */
	public function setOrderPayment($data, $batchSN)
	{
		$where = $this -> _db -> quoteInto('batch_sn = ?', $batchSN);
		$paymentSet = array (
                             'pay_type' => $data['pay_type'],
                             'pay_name' => $data['pay_name']
                             );
		return $this -> _db -> update($this -> _tableOrderBatch, $paymentSet, $where);
	}
	
	/**
     * 更新会员短信号码
     *
     * @param    string   $smsNo
     * @param    int      $batchSN
     */
	public function setOrderSmsNo($smsNo, $batchSN)
	{
		$where = $this -> _db -> quoteInto('batch_sn = ?', $batchSN);
		return $this -> _db -> update($this -> _tableOrderBatch, array('sms_no' => $smsNo), $where);
	}
	
	/**
     * 更新会员收货信息
     *
     * @param    array    $data
     * @param    int      $batchSN
     * @return   int      lastInsertId
     */
	public function setOrderAddress($data, $batchSN)
	{
		$where = $this -> _db -> quoteInto('batch_sn = ?', $batchSN);
		$addressSet = array (
                             'addr_consignee' => $data['addr_consignee'],
                             'addr_province' => $data['addr_province'],
                             'addr_city' => $data['addr_city'],
                             'addr_area' => $data['addr_area'],
                             'addr_province_id' => $data['addr_province_id'],
                             'addr_city_id' => $data['addr_city_id'],
                             'addr_area_id' => $data['addr_area_id'],
                             'addr_address' => $data['addr_address'],
                             'addr_tel' => $data['addr_tel'],
                             'addr_mobile' => $data['addr_mobile'],
                             );
		return $this -> _db -> update($this -> _tableOrderBatch, $addressSet, $where);
	}

	/**
     * 取得指定条件的订单批次
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatch($where=null)
    {
        $condition = null;
        if (is_array($where)) {
            if ($where['user_id']) {
                $condition[] = "user_id = '{$where['user_id']}'";
            }
            if ($where['order_batch_id']) {
                $condition[] = "order_batch_id = '{$where['order_batch_id']}'";
            }
            if ($where['batch_sn']) {
                $condition[] = "b.batch_sn = '{$where['batch_sn']}'";
            }
            if ($where['order_sn']) {
                $condition[] = "b.order_sn = '{$where['order_sn']}'";
            }
            if(!is_null($where['status'])){
                $condition[] = "status={$where['status']}";  
            }
            if(!is_null($where['status_logistic>'])){
                $condition[] = "status_logistic>{$where['status_logistic>']}";  
            }
            if (!is_null($where['is_send'])) {
                $condition[] = "is_send = '{$where['is_send']}'";
            }
            if (is_array($condition) && count($condition)) {
                $condition = 'AND ' . implode(' and ', $condition);
            }
        } else {
            $condition = $where;
        }
        if ($condition) {
            $sql = "SELECT a.order_id,a.user_id,a.user_name,a.invoice,a.invoice_content,a.parent_id,a.parent_param,a.proportion,a.lid,";
            $sql .= "b.* FROM {$this->_table} a, {$this->_tableOrderBatch} b ";
            $sql .= "where 1=1 and a.order_id = b.order_id {$condition} order by order_batch_id desc";
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
	/**
     * 更新订单商品
     *
     * @param   array   $where
     * @param   array   $data
     * @return  bool
     */
    public function updateOrderBatchGoods($where, $data)
    {
        $condition = '';
        if ($where['order_sn']) {
            $condition[] = "order_sn = '{$where['order_sn']}'";
        }
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['order_batch_goods_id']) {
            $condition[] = "order_batch_goods_id = '{$where['order_batch_goods_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
        if ($condition) {
            return $this -> _db -> update($this -> _tableOrderBatchGoods, $data, $condition);
        } else{
            return false;
        }
    }
	/**
     * 取订单商品信息
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatchGoods($where, $orderBy = null)
    {
        $condition = null;
        if ($where['order_sn']) {
            $condition[] = "order_sn = '{$where['order_sn']}'";
        }
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['type']) {
            $condition[] = "type = '{$where['type']}'";
        }
        if (!is_null($where['is_send'])) {
            $condition[] = "is_send = '{$where['is_send']}'";
        }
        if ($where['card_sn_is_not_null']) {
            $condition[] = "card_sn IS NOT NULL";
        }
        if (!is_null($where['product_id>'])) {
            $condition[] = "product_id > '{$where['product_id>']}'";
        }
        if ($where['order_batch_goods_id']) {
            $condition[] = "order_batch_goods_id = '{$where['order_batch_goods_id']}'";
        }
        if (!is_null($where['is_send'])) {
            $condition[] = "is_send = '{$where['is_send']}'";
        }
        if (!is_null($where['parent_id'])) {
            $condition[] = "parent_id = '{$where['parent_id']}'";
        }
        if (!is_null($where['number>'])) {
            $condition[] = "number > '{$where['number>']}'";
        }
        if (!is_null($where['(number-return_number)>'])) {
            $condition[] = "(number-return_number)>'{$where['(number-return_number)>']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        if ($condition) {
            $sql = "SELECT * FROM {$this -> _tableOrderBatchGoods} where 1=1 {$condition} {$orderBy}";
           
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
    
    public function getOrderGoodsByBatchSn($batch_sn)
    {
    	$sql = "SELECT t.product_img,t.product_name,p.goods_id FROM {$this -> _tableOrderBatchGoods} as o inner join shop_product as t on t.product_id=o.product_id inner join `shop_product_goods` p on p.product_id = t.product_id  where o.batch_sn='{$batch_sn}'";
    	return $this -> _db -> fetchAll($sql);
    }
	/**
     * 取订单金额抵扣信息
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatchAdjust($where)
    {
        if ($where['order_sn']) {
            $condition[] = "order_sn = '{$where['order_sn']}'";
        }
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        return $this -> _db -> fetchAll("SELECT * FROM {$this -> _tableOrderBatchAdjust} where 1=1 {$condition}");
    }
	/**
     * 取消订单
     *
     * @param   string   $batchSN
     * @return  int
     */
    public function setOrderCancel($batchSN)
    {
		$where = $this -> _db -> quoteInto('batch_sn = ?', $batchSN);
		$orderSet = array ('status' => 1, 'hang' => 0);
		return $this -> _db -> update($this -> _tableOrderBatch, $orderSet , $where);
    }
	/**
     * 更新指定ID的订单批次信息
     *
     * @param   array   $where
     * @param   array   $data
     * @return  bool
     */
    public function updateOrderBatch($where, $data)
    {
        $condition = '';
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['is_fav'] != '') {
            $condition[] = "is_fav = '{$where['is_fav']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
        if ($condition) {
            return $this->_db->update($this->_tableOrderBatch, $data, $condition);
        }
    }
	/**
     * 取配送地区列表
     *
     * @param   array     $where
     * @return  array
     */
    public function getLogistic($where)
    {
        if ($where['area_id']) {
            $condition[] = "b.area_id = '{$where['area_id']}'";
        }
        if ($where['weight']) {
            $condition[] = "min < {$where['weight']} and max >= {$where['weight']}";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        if ($condition) {
            $sql = "select 
                        b.logistic_code,b.zip,b.code,b.country_id,b.province_id,b.city_id,b.area_id,
                        b.country,b.open,b.delivery, b.province,b.city,b.area,b.cod,b.delivery_keyword,b.non_delivery_keyword,
                        c.price as price,a.name as logistic_name,a.cod_rate,a.cod_min,a.fee_service,a.logistic_code 
                    from {$this->_tableLogistic} a
                        left join {$this->_tableLogisticArea} b  on a.logistic_code = b.logistic_code
                        left join {$this->_tableLogisticAreaPrice} c on b.logistic_code=c.logistic_code and b.area_id=c.area_id

                    where 1=1 {$condition}";
            
            return $this->_db->fetchAll($sql);
        } else {
            return false;
        }
    }

    /**
     * 新增订单商品
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderBatchGoods($data)
    {
        $this -> _db -> insert($this -> _tableOrderBatchGoods, $data);
		return $this -> _db -> lastInsertId();
    }
    
	/**
     * 取得客户订单列表信息(api_member调用)
     *
     * @param    string   $where
     * @return   array
     */
	public function getUserOrder($where = null, $page=null, $pageSize = null)
	{
		if($where['user_id']<1){return null;}
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null && is_array($where)) {
			$whereSql = " WHERE 1=1 and b.order_id > 0   and a.user_id=".$where['user_id'];
			//判断时间段
			if($where['timesection']==1){//一个月内
				$whereSql .= " and b.add_time>".(time()-86400*30);
			}
			if($where['timesection']==2){//一个月以前
				$whereSql .= " and b.add_time<".(time()-86400*30);
			}
			//判断订单状态
			if($where['ordertype']==2){//待确认订单
				$whereSql .= " and status=0 and status_logistic=0";
			}
			if($where['ordertype']==3){//已取消订单
				$whereSql .= " and status=1";
			}
			if($where['ordertype']==4){//需付款订单
				$whereSql .= " and status=0 and status_pay=0";
			}
			if($where['ordertype']==5){//已付款待发货订单
				$whereSql .= " and status=0 and status_logistic=2 and status_pay=2";
			}
			if($where['ordertype']==6){//已发货订单
				$whereSql .= " and status=0 and status_logistic=3 and status_pay=2";
			}
			if($where['ordertype']==7){//已完成订单
				$whereSql .= " and status=0 and status_logistic=4 and status_pay=2";
			}
			if($where['sn']){
				$whereSql .= " and a.order_sn = '{$where['sn']}'" ;
			}
		}
		
        $sql = "SELECT a.order_id,a.user_id,a.user_name,a.invoice,a.invoice_content,a.parent_id,a.parent_param,a.proportion,";
        $sql .= "b.* FROM {$this->_table} a left join {$this->_tableOrderBatch} b on a.order_sn = b.order_sn ";
        $sql .= "{$whereSql} order by order_batch_id desc {$limit}";
        $sqlcount = "SELECT count(a.order_id) FROM {$this->_table} a left join {$this->_tableOrderBatch} b on a.order_sn = b.order_sn {$whereSql}";
		$rs['data'] = $this -> _db -> fetchAll($sql);
		$rs['tot'] = $this-> _db-> fetchOne($sqlcount);
		return $rs;
	}
	
	
}