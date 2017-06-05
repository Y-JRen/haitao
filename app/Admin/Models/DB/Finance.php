<?php
class Admin_Models_DB_Finance
{
	private $_db = null;
	
    private $_table_finance = 'shop_finance';
    private $_table_payment = 'shop_payment';
	private $_table_order = 'shop_order';
	private $_table_order_batch = 'shop_order_batch';
    private $_table_order_clear = 'shop_order_clear';
    private $_table_external_order_clear = 'shop_order_external_clear';
    private $_table_pay_log = 'shop_order_pay_log';
    private $_table_pay_series = 'shop_order_pay_series';
    private $_table_transport = 'shop_transport';
    private $_table_purchase_payment = 'shop_purchase_payment';
    private $_table_supplier = 'shop_supplier';
    private $_table_instock = 'shop_instock';
    private $_table_instock_detail = 'shop_instock_detail';
    private $_table_outstock = 'shop_outstock';
    private $_table_outstock_detail = 'shop_outstock_detail';
    private $_table_product = 'shop_product';
    private $_table_distribution_fake = 'shop_distribution_fake';
    private $_table_finance_receivable = 'shop_finance_receivable';
    private $_table_finance_distribution = 'shop_finance_distribution';
    private $_table_finance_distribution_detail = 'shop_finance_distribution_detail';
    private $_table_finance_invoice = 'shop_finance_invoice';
    private $_table_shop = 'shop_shop';

    private $_pageSize = null;
	
	public function __construct()
	{
		$this -> _db = Zend_Registry :: get('db');
		$this -> _pageSize = Zend_Registry :: get('config') -> view -> page_size;
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	/**
     * 取得指定条件的支付方式
     *
     * @param   array   $where
     * @return  array
     */
    public function getPayment($where)
    {
        if (!is_null($where['status'])) {
            $condition[] = "status='{$where['status']}'";  
        }
        if ($where['pay_type']) {
            $condition[] = "pay_type = '{$where['pay_type']}'";
        }
        if ($where['is_bank!=']) {
            $condition[] = "is_bank!='{$where['is_bank!=']}'";  
        }
        if ($where['pay_type!=']) {
            $condition[] = "pay_type!='{$where['pay_type!=']}'";  
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        return $this -> _db -> fetchAll("SELECT * FROM {$this->_table_payment} where 1=1 {$condition} order by sort");
    }
	/**
     * 添加一条财务申请
     *
     * @param   array     $data
     * @return  int
     */
    public function addFrinance($data)
    {
        $this->_db->insert($this->_table_finance, $data);
		return $this->_db->lastInsertId();
    }
    /**
     * 取得指定条件的财务列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getFinanceWithPage($where, $page = null)
    {
        if ($where['fromdate']) {
            $condition[] = "t1.add_time >= ".strtotime($where['fromdate']);
        }
        if ($where['todate']) {
            $condition[] = "t1.add_time <= ".strtotime($where['todate'].' 23:59:59');
        }
        if ($where['check_fromdate']) {
            $condition[] = "t1.check_time >= ".strtotime($where['check_fromdate']);
        }
        if ($where['check_todate']) {
            $condition[] = "t1.check_time <= ".strtotime($where['check_todate'].' 23:59:59');
        }
        if($where['bank_type']!=''){
            $condition[] = "t1.bank_type={$where['bank_type']}";  
        }
        if($where['status']!=='' && $where['status']!==null){
            $condition[] = "t1.status={$where['status']}";  
        }
        if(!is_null($where['pay>'])){
            $condition[] = "t1.pay>{$where['pay>']}";  
        }
        if(!is_null($where['pay<'])){
            $condition[] = "t1.pay<{$where['pay<']}";  
        }
        if(!is_null($where['pay!='])){
            $condition[] = "t1.pay!={$where['pay!=']}";  
        }
        if(!is_null($where['item'])){
            $condition[] = "t1.item={$where['item']}";  
        }
        if(!is_null($where['item_no']) && $where['item_no'] != ''){
            $condition[] = "t1.item_no='{$where['item_no']}'";  
        }
        if(!is_null($where['status'])){
            $condition[] = "t1.status!={$where['status!=']}";  
        }
        if($where['order_status'] !== null && $where['order_status'] !== '') {
            $condition[] = "t3.status = {$where['order_status']}";
        }
        if($where['shop_id']){
            $condition[] = "t1.shop_id={$where['shop_id']}";  
        }
        if($where['lid']){
            $condition[] = "t2.lid={$where['lid']}";  
        }
        $condition[] = "t1.status<5";
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' AND ', $condition);
        }
        $sql = "FROM {$this -> _table_finance} as t1 inner join {$this -> _table_order_batch} as t3 on t1.item_no = t3.batch_sn inner join {$this -> _table_order} as t2 on t2.order_id = t3.order_id left join {$this -> _table_shop} as t4 on t1.shop_id = t4.shop_id WHERE 1=1 {$condition} ";
		$sqlOfList = "SELECT t1.*,t3.status as order_status,t2.lid,t4.shop_name {$sql}";
		$sqlOfList .= "ORDER BY t1.add_time DESC ";
		if ($page) {
            $sqlOfList .= "LIMIT {$this -> _pageSize} OFFSET " . (($page - 1) * $this -> _pageSize);
        }
        $sqlOfTotal = "SELECT count(*) as count,sum(pay) as pay,sum(point) as point,sum(account) as account,sum(gift) as gift {$sql}";
        $data['data'] = $this -> _db -> fetchAll($sqlOfList);
        $data['total'] = $this -> _db -> fetchRow($sqlOfTotal);
        $data['total']['pay'] = abs($data['total']['pay']);
        $data['total']['point'] = abs($data['total']['point']);
        $data['total']['account'] = abs($data['total']['account']);
        $data['total']['gift'] = abs($data['total']['gift']);
        return $data;
    }	
	/**
     * 更新财务状态
     *
     * @param   array   $where
     * @param   int     $data
     * @return  bool
     */
    public function updateFinance($financeID, $data)
    {
        if ($financeID) {
            return $this->_db->update($this->_table_finance, $data, "finance_id={$financeID}");
        }
    }
	/**
     * 取财务信息
     *
     * @param   array   $where
     * @return  array
     */
    public function getFinance($where)
    {
        if (is_array($where)) {
            if (!is_null($where['finance_id'])) {
                $condition[] = "finance_id = '{$where['finance_id']}'";
            }
            if (!is_null($where['item_no'])) {
                $condition[] = "item_no = '{$where['item_no']}'";
            }
            if (!is_null($where['item'])) {
                $condition[] = "item = '{$where['item']}'";
            }
            if (!is_null($where['status'])) {
                if (is_array($where['status'])) {
                    $condition[] = "status in (".implode(',', $where['status']).")";
                }
                else {
                    $condition[] = "status = '{$where['status']}'";
                }
            }
            if (is_array($condition) && count($condition)) {
                $condition = implode(' and ', $condition);
            }
        } else if (is_string($where)) {
            $condition = $where;
        }
        if ($condition) {
            $sql = "SELECT * FROM {$this -> _table_finance} where {$condition} order by add_time desc";
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
	/**
     * 取最后增加的财务记录
     *
     * @param   int         $item
     * @param   string      $batchSN
     * @param   int         $includeWay35
     * @return  void
     */
    public function getLastFinanceByItemNO($item, $batchSN, $includeWay35 = false)
    {
        if ($includeWay35) {
            $sql = "select * from {$this -> _table_finance} where item = '{$item}' and item_no = '{$batchSN}' and (status in (0,1) or (status = 2 and way in (3,5) and check_time = 0))";
            return $this -> _db -> fetchAll($sql);
        }
        else {
            $sql = "select * from {$this -> _table_finance} where item = '{$item}' and item_no = '{$batchSN}' order by add_time desc limit 1";
            return $this -> _db -> fetchRow($sql);
        }
    }
	/**
     * 删除
     *
     * @param   int     $financeID
     * @return  void
     */
    public function delFinance($financeID)
    {
         return $this -> _db -> delete($this -> _table_finance, 'finance_id=' . $financeID);
    }

	/**
     * 获取库存详细数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getDetail($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY a.outstock_id desc";
		}
		
		
		$table = "`$this->_table_outstock_detail` a 
		          INNER JOIN `$this->_table_outstock` b ON a.outstock_id=b.outstock_id 
		          INNER JOIN `$this->_table_product` p ON p.product_id=a.product_id 
			      INNER JOIN `$this->_table_goods` g ON g.product_id=p.product_id
			      INNER JOIN `$this->_table_goods_cat` gc ON gc.cat_id=p.cat_id 
			      LEFT JOIN  `$this->_table_stock_op` op ON a.outstock_id=op.item_id AND item='outstock' AND op_type='send'
		         ";
		
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
	}

	/**
     * 添加结算数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertClear($data)
	{
        $this -> _db -> insert($this -> _table_order_clear, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
     * 添加外部订单结算数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertExternalClear($data)
	{
        $this -> _db -> insert($this -> _table_external_order_clear, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}


	/**
     * 获取结算列表
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getClear($where = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql .= " WHERE $where";
		}
		$table = "`$this->_table_order_clear` a";

        $data['total']=	$this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
        $data['data']= $this -> _db -> fetchAll("SELECT * FROM $table $whereSql order by id desc $limit");
		return $data;
	}
	
	/**
     * 获取外部结算列表
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getExternalClear($where = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql .= " WHERE $where";
		}
		$table = "{$this -> _table_external_order_clear} as t1 ";
        
        $data['total']=	$this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
        $data['data']= $this -> _db -> fetchAll("SELECT t1.* FROM $table $whereSql order by id desc $limit");
		return $data;
	}

	/**
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function payLogList($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY pay_log_id";
		}
        return array('list' => $this -> _db -> fetchAll("SELECT {$fields} FROM {$this -> _table_pay_log} as t1 inner join {$this -> _table_order_batch} as t2 on t1.batch_sn = t2.batch_sn inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id {$whereSql} {$orderBy} {$limit}"),
                     'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this -> _table_pay_log} as t1 inner join {$this -> _table_order_batch} as t2 on t1.batch_sn = t2.batch_sn inner join {$this ->_table_order} as t3 on t2.order_id = t3.order_id {$whereSql}"));
	}
	
	public function getOrderByOrderSN($orderSN, $shopID)
	{
	    return $this -> _db -> fetchRow("select * from {$this -> _table_order} as t1 left join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id where t1.order_sn = '{$orderSN}' and t1.shop_id = {$shopID}");
	}
	
	public function getExternalOrderSN($orderSNArray)
	{
	    if (!$orderSNArray || count($orderSNArray) == 0)   return false;
	    
	    $datas = $this -> _db -> fetchAll("select external_order_sn,order_sn from {$this -> _table_order} where order_sn in (".implode(',', $orderSNArray).")");
	    if (!$datas)    return false;
	    
	    foreach ($datas as $data) {
	        $result[$data['order_sn']] = $data['external_order_sn'];
	    }
	    
	    return $result;
	}
	
	public function getSettlementSumType1($where)
	{
	    $where = "t1.shop_id in (1,2) and t2.type in (0,6) and t2.status_pay in (1,2) {$where}";
	    return $this -> _db -> fetchAll("select count(*) as count,t2.pay_type,t2.pay_name,t2.clear_pay,sum(t2.price_order) as amount from {$this -> _table_order} as t1 left join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id where {$where} group by t2.pay_type,t2.clear_pay");
	}
	
	public function getSettlementSumType2($where)
	{
	    $where = "t1.shop_id in (0,1,2) and t2.type in (0,1,6) and t2.status_logistic in (3,4) and pay_type = 'cod' {$where}";
	    return $this -> _db -> fetchAll("select count(*) as count,t3.logistic_code,t3.logistic_name,t3.cod_status,sum(t2.price_order) as amount from {$this -> _table_order} as t1 left join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id left join {$this -> _table_transport} as t3 on t1.batch_sn = t3.bill_no where {$where} group by t3.logistic_code,t3.cod_status");
	}
	
	public function getSettlementSumType3($where)
	{
	    $where = "t1.shop_id > 2 and t2.status in (0,3) and t2.status_logistic in (3,4,5) {$where}";
	    return $this -> _db -> fetchAll("select count(*) as count,t3.shop_id,t2.clear_pay,sum(t2.price_order) as amount from {$this -> _table_order} as t1 left join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id  where {$where} group by t1.shop_id,t2.clear_pay");
	}
	
	/**
     * 获取采购入库付款数据
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @param    string    $order
     * @param    string    $type
     * @return   array
     */
	public function getPurchasePayment($where = null, $page = null, $pageSize = null, $order = null, $type = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql .= " WHERE $where";
		}
		if ($order) {
		    $orderBy = "order by {$order}";
		}
        
        if ($type) {
            if ($type == 1) {
                $table1 = "inner join {$this -> _table_instock} as t3 on t1.bill_no = t3.bill_no";
                $field = ',t3.instock_id,t3.purchase_type';
            }
            else if ($type == 2) {
                $table1 = "inner join {$this -> _table_outstock} as t3 on t1.bill_no = t3.bill_no";
                $field = ',t3.outstock_id';
            }
        }
        $data['total'] =	$this -> _db -> fetchRow("SELECT count(*) as count,sum(t1.amount) as amount FROM {$this -> _table_purchase_payment} as t1 left join {$this -> _table_supplier} as t2 on t1.supplier_id = t2.supplier_id {$table1} {$whereSql}");
        $data['data'] = $this -> _db -> fetchAll("SELECT t1.*,t2.supplier_name,t3.bill_type {$field} FROM {$this -> _table_purchase_payment} as t1 left join {$this -> _table_supplier} as t2 on t1.supplier_id = t2.supplier_id {$table1} {$whereSql} {$orderBy} {$limit}");
        
        $sql = "select sum(t4.shop_price / (1 + t5.invoice_tax_rate / 100) * t4.number) from {$this -> _table_purchase_payment} as t1 
                left join {$this -> _table_supplier} as t2 on t1.supplier_id = t2.supplier_id
                inner join {$this -> _table_instock} as t3 on t1.bill_no = t3.bill_no
                inner join {$this -> _table_instock_detail} as t4 on t3.instock_id = t4.instock_id
                inner join {$this -> _table_product} as t5 on t4.product_id = t5.product_id
                {$whereSql}";
        $data['no_tax_sum'] = $this -> _db -> fetchOne($sql);
        
		return $data;
	}
	
	/**
     * 采购入库付款
     *
     * @param    int       $id
     * @param    array     $row
     * @return   boolean
     */
	public function purchasePayment($id, $row)
	{
	    return $this -> _db -> update($this -> _table_purchase_payment, $row, "id = '{$id}'");
	}
	
	/**
     * 获得分销刷单
     *
     * @param   string  $where
     * @param   int     $page
     * @param   int     $pageSize
     * @return  array
     */
    public function getDistributionOrder($where = null, $page = null, $pageSize = null)
    {
        $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		$sql = "select t1.*,t2.distribution_id from {$this -> _table_distribution_fake} as t1
		        inner join {$this -> _table_instock} as t2 on t1.batch_sn = t2.bill_no
		        where {$where}
		        order by t1.add_time desc
		        {$limit}";
        $data['data']= $this -> _db -> fetchAll($sql);
        $sql = "select count(*) as number,sum(amount) as amount,sum(settle_amount) as settle_amount from {$this -> _table_distribution_fake} as t1
		        inner join {$this -> _table_instock} as t2 on t1.batch_sn = t2.bill_no
		        inner join {$this -> _table_shop} as t3 on t2.distribution_id = t3.shop_id
		        where {$where}";
        $data['sum']= $this -> _db -> fetchRow($sql);
        
        return $data;
    }
    
    /**
     * 获得分销刷单汇总金额
     *
     * @param   string  $where
     * @return  float
     */
    public function getDistributionOrderSumAmount($where = null)
    {
        $sql = "select sum(amount-price_order) as margin,sum(amount) as amount,sum(price_order) as price_order from 
                (select DISTINCT(t4.item_no),t3.amount,t1.price_order from {$this -> _table_order_batch} as t1
		        inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
		        left join {$this -> _table_distribution_fake} as t3 on t1.batch_sn = t3.batch_sn
		        inner join {$this -> _table_instock} as t4 on t1.batch_sn = t4.item_no
		        where {$where}) a";

        return $this -> _db -> fetchRow($sql);
    }
    
    /**
     * 分销刷单销账
     *
     * @param   string   $batchSN
     * @param   float    $amount
     * @return  string
     */
    public function distributionWriteOff($batchSN, $amount)
    {
        $set = array('settle_amount' => $amount,
                     'settle_time' => time(),
                     'admin_name' => $this -> _auth['admin_name'],
                     );
        $this -> _db -> update($this -> _table_distribution_fake, $set, "batch_sn = '{$batchSN}'");
    }
    
    /**
     * 获取结款订单数据集
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetchOrder($where = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : 20;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
        $whereSql = " where b.status in (0,4,5) and b.status_pay=2 ";
		if ($where != null) {
			$whereSql.= $where;
		}
		
		$sql = "select *,sum(price_payed) as price_payed from
		        (select a.commission,a.shop_id,b.order_id,b.order_batch_id,b.batch_sn,b.add_time,b.logistic_time,b.status,b.status_logistic,b.status_pay,b.price_payed,b.clear_pay,b.pay_type,b.pay_name,b.pay_time from {$this -> _table_order_batch} as b
		        inner join {$this -> _table_order} as a on a.order_id = b.order_id
		        {$whereSql}
		        order by b.order_batch_id) as c
		        group by order_id
		        having price_payed > 0
		        {$limit}";
		$list = $this -> _db -> fetchAll($sql);
		
		$sql = "select count(*) as count from
		        (select * from
		        (select a.commission,a.shop_id,b.order_id,b.order_batch_id,b.batch_sn,b.add_time,b.logistic_time,b.status,b.status_logistic,b.status_pay,b.price_payed,b.clear_pay,b.pay_type,b.pay_name,b.pay_time from {$this -> _table_order_batch} as b
		        inner join {$this -> _table_order} as a on a.order_id = b.order_id
		        {$whereSql}
		        order by b.order_batch_id) as c
		        group by order_id
		        having price_payed > 0) as d";
		$total = $this -> _db -> fetchOne($sql);
        return array('list' => $list, 'total' => $total);
	}
	
	/**
     * 添加应收记录
     *
     * @param   array       $data
     * @return  void
     */
    public function addFinanceReceivable($data)
    {
        $this -> _db -> insert($this -> _table_finance_receivable, $data);
    }
	
	/**
     * 获得应收记录
     *
     * @param   string      $where
     * @return  array
     */
    public function getFinanceReceivable($where = 1)
    {
        return $this -> _db -> fetchAll("select * from {$this -> _table_finance_receivable} where {$where}");
    }
    
    /**
     * 更新应收记录
     *
     * @param   array       $data
     * @param   array       $where
     * @return  void
     */
    public function updateFinanceReceivable($data, $where)
    {
        $this -> _db -> update($this -> _table_finance_receivable, $data, $where);
    }
    
    /**
     * 删除应收记录
     *
     * @param   string      $where
     * @return  array
     */
    public function deleteFinanceReceivable($where)
    {
        return $this -> _db -> delete($this -> _table_finance_receivable, $where);
    }
    
    /**
     * 添加直供结算
     *
     * @param   array       $data
     * @return  void
     */
    public function addDistributionSettlement($data)
	{
	    $this -> _db -> insert($this -> _table_finance_distribution, $data);
	}
	
	/**
     * 添加直供结算明细
     *
     * @param   array       $data
     * @return  void
     */
    public function addDistributionSettlementDetail($data)
	{
	    $this -> _db -> insert($this -> _table_finance_distribution_detail, $data);
	}
	
	/**
     * 修改直供结算
     *
     * @param   string      $batchSN
     * @param   array       $data
     * @return  void
     */
    public function updateDistributionSettlement($batchSN, $data)
	{
	    $this -> _db -> update($this -> _table_finance_distribution, $data, "batch_sn = '{$batchSN}'");
	}
	
	/**
     * 获得直供结算
     *
     * @param   string  $where
     * @param   int     $page
     * @param   int     $pageSize
     * @return  array
     */
    public function getDistributionSettlement($where = 1, $page = null, $pageSize = null)
	{
	    if ($page) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		$sql = "select t1.*,t2.balance_amount,t2.logistic_time from {$this -> _table_finance_distribution} as t1
		        inner join {$this -> _table_order_batch} as t2 on t1.batch_sn = t2.batch_sn
		        inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
		        where {$where} 
		        order by add_time desc
		        {$limit}";

        return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 获得直供结算合计
     *
     * @param   string  $where
     * @return  array
     */
    public function getDistributionSettlementSum($where = 1)
	{
	    $sql = "select count(*) as count,sum(amount) as amount,sum(settle_amount) as settle_amount,sum(promotion_amount) as promotion_amount,sum(point_amount) as point_amount from {$this -> _table_finance_distribution} as t1
		        inner join {$this -> _table_order_batch} as t2 on t1.batch_sn = t2.batch_sn
		        inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
		        inner join {$this -> _table_shop} as t4 on t3.shop_id = t4.shop_id
		        where {$where}";

		return $this -> _db -> fetchRow($sql);
	}
	
	/**
     * 获得直供结算明细
     *
     * @param   string  $where
     * @return  array
     */
    public function getDistributionSettlementDetail($where = 1)
	{
	    $sql = "select t1.* from {$this -> _table_finance_distribution_detail} as t1
	            inner join {$this -> _table_finance_distribution} as t2 on t1.distribution_id = t2.distribution_id
	            where {$where}
	            order by t1.add_time";

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 获得应收款列表
     *
     * @param   string    $where
     * @param   int       $page
     * @return  array
     */
    public function getReceivableList($where, $page = null)
    {
        if ($page) {
		    $offset = ($page - 1) * 20;
		    $limit = "LIMIT 20 OFFSET $offset";
		}
		$where = str_replace('t1.logistic_time', 'if (t4.time_flag, t4.add_time, t1.logistic_time)', $where);
		$sql = "select t4.*,if(t4.type = 4, if(t1.status_logistic = 5 and t1.logistic_code = t4.pay_type,0,t4.amount),if(t4.pay_type = 'distribution',t5.amount,t4.amount)) as real_amount,t3.shop_name,t1.logistic_time,t1.logistic_no,t1.type,t1.addr_consignee,t2.user_name,t1.status,t2.external_order_sn from {$this -> _table_order_batch} as t1
		        inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
        	    left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
        	    inner join {$this -> _table_finance_receivable} as t4 on t1.batch_sn = t4.batch_sn
        	    left join {$this -> _table_finance_distribution} as t5 on t1.batch_sn = t5.batch_sn
        	    where {$where}
        	    order by t1.logistic_time desc
        	    {$limit}";
        $result['data'] = $this -> _db -> fetchAll($sql);

        $sql = "select count(*) as count,sum(t4.amount) as amount,sum(if(t4.type = 4, if(t1.status_logistic = 5 and t1.logistic_code = t4.pay_type,0,t4.amount),if(t4.pay_type = 'distribution',t5.amount,t4.amount))) as real_amount,sum(t4.settle_amount) as settle_amount,sum(t4.commission) as commission,sum(if(t4.type = 4, if(t1.status_logistic = 5 and t1.logistic_code = t4.pay_type,0,t4.amount),if(t4.pay_type = 'distribution',t5.amount,t4.amount))-t4.settle_amount-t4.commission) as diff from {$this -> _table_order_batch} as t1
		        inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
        	    left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
        	    inner join {$this -> _table_finance_receivable} as t4 on t1.batch_sn = t4.batch_sn
        	    left join {$this -> _table_finance_distribution} as t5 on t1.batch_sn = t5.batch_sn
        	    where {$where}";
        $result['total'] = $this -> _db -> fetchRow($sql);
        
        return $result;
    }
    
    /**
     * 获取结算列表
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getInvoice($where = null, $page = null)
	{
		$pageSize = 20;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql .= " WHERE $where";
		}
        
        $table = "{$this -> _table_finance_invoice} as t1 left join {$this -> _table_order_batch}  as t2 on t1.invoice_no = t2.invoice_no";
        
        $data['total'] = $this -> _db -> fetchRow("SELECT count(*) as count,sum(amount) as amount FROM {$table} {$whereSql}");
        $data['data'] = $this -> _db -> fetchAll("SELECT t1.*,t2.batch_sn,t2.status_logistic FROM {$table} {$whereSql} order by t1.add_time desc {$limit}");
        
		return $data;
	}
	
	/**
     * 更新发票
     *
     * @param   array   $data
     * @param   string  $where
     * @return  void
     */
    public function updateInvoice($data, $where)
    {
        $this -> _db -> update($this -> _table_finance_invoice, $data, $where);
    }
    
    public function fetchReceivableList($where, $payType)
	{
	    $table = "{$this -> _table_finance_receivable} as t1
	              inner join {$this -> _table_order_batch} as t2 on t1.batch_sn = t2.batch_sn
                  inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	              left join {$this -> _table_shop} as t4 on t3.shop_id = t4.shop_id";
	    if (in_array($payType, array('external', 'externalself'))) {
            $field = 't1.id,t1.pay_type,t1.amount,t1.settle_amount,t1.commission,t1.add_time,t1.settle_time,t3.external_order_sn as batch_sn,t2.addr_consignee,t2.type,t3.user_name';
        }
        else if (in_array($payType, array('sf', 'ems'))) {
            $field = 't1.id,t1.pay_type,t1.amount,t1.settle_amount,t1.commission,t1.add_time,t1.settle_time,t2.logistic_no as batch_sn,t2.addr_consignee,t2.type,t3.user_name';
        }
        else {
            $field = 't1.*,t2.addr_consignee,t2.type,t3.user_name';
        }
	    $sql = "select {$field} from {$table} where {$where}";
	    
	    return $this -> _db -> fetchAll($sql);
	}
}
