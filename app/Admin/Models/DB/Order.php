<?php
class Admin_Models_DB_Order
{
	private $_db = null;
    public $_pageSize = null;
    private $_table_user = 'shop_user';
	private $_table_area = 'shop_area';
    private $_table_payment = 'shop_payment';
    private $_table_reason = 'shop_reason';
	private $_table_order = 'shop_order';
    private $_table_order_batch = 'shop_order_batch';
	private $_table_order_batch_log = 'shop_order_batch_log';
    private $_table_order_batch_adjust = 'shop_order_batch_adjust';
	private $_table_order_batch_goods = 'shop_order_batch_goods';
    private $_table_order_batch_goods_log = 'shop_order_batch_goods_log';
    private $_table_order_batch_goods_return = 'shop_order_batch_goods_return';
    private $_table_order_batch_goods_return_reason = 'shop_order_batch_goods_return_reason';
    private $_table_order_pay_log = 'shop_order_pay_log';
    private $_table_logistic = 'shop_logistic';
    private $_table_logistic_area = 'shop_logistic_area';
    private $_table_logistic_area_price = 'shop_logistic_area_price';
    private $_table_stock_status = 'shop_stock_status';
    private $_table_member = 'shop_member';
    private $_table_coupon_card = 'shop_coupon_card';
    private $_table_coupon_card_log = 'shop_coupon_card_create_log';
    private $_table_product = 'shop_product';
    private $_table_order_batch_return = 'shop_order_batch_return';
    private $_table_finance_product = 'shop_finance_product';
    private $_table_shop = 'shop_shop';

	public function __construct()
	{
		$this -> _db = Zend_Registry :: get('db');
		$this -> _pageSize = Zend_Registry :: get('config') -> view -> page_size;
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}

	/**
     * 取地区名
     *
     * @param   int     $areaID
     * @return  string
     */
    public function getAreaName($areaID)
    {
        return $this -> _db -> fetchOne("select area_name from `{$this->_table_area}` where area_id={$areaID}");
    }
 
    /**
     * 取得订单分页列表
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getOrderBathWithPage($where, $page)
    {
        if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']) + 86400;
            if ($fromDate <= $toDate) {
                $condition[] = "(b.add_time between {$fromDate} and {$toDate})";
            }
        }

        if ($where['logisticfromdate'] && $where['logistictodate']) {
            $logisticfromdate = strtotime($where['logisticfromdate']);
            $logistictodate = strtotime($where['logistictodate']) + 86400;
            if ($logisticfromdate <= $logistictodate) {
                $condition[] = "(b.logistic_time between {$logisticfromdate} and {$logistictodate})";
            }
        }
        
        if ($where['entry'] == 'call') {
            $condition[] = "b.type in (10,11,12)";
        }
        if ($where['entry'] == 'other') {
            $condition[] = "b.type in (5,7,15)";
        }
        if ($where['entry'] == 'b2c') {
            $condition[] = "b.type = 0";
        }
        
        if ($where['batch_sn']) {
            $where['batch_sn']=trim($where['batch_sn']);
            $condition[] = "b.batch_sn like '%{$where['batch_sn']}%'";  
        }
        if ($where['order_batch_id']) {
            $condition[] = "b.order_batch_id='{$where['order_batch_id']}'";  
        }   
        if (isset($where['type']) && $where['type']!='') {
            if ($where['type'] == '0' && $where['user_name'] != 'yumi_jiankang' && $where['user_name'] != 'xinjing_jiankang') {
                $condition[] = "b.type='{$where['type']}' and a.user_name <> 'yumi_jiankang' && a.user_name <> 'xinjing_jiankang'";
            }
            else {
                $condition[] = "b.type='{$where['type']}'";
            }
        }
        if ($where['user_name']) {
            $where['user_name']=trim($where['user_name']);
            $condition[] = "a.user_name like '%{$where['user_name']}%'";  
        }
        if ($where['user_name!=']) {
            $where['user_name!=']=trim($where['user_name!=']);
            $condition[] = "a.user_name not in ({$where['user_name!=']})"; 
        }
        if ($where['pay_type!=']) {
            $condition[] = "b.pay_type!='{$where['pay_type!=']}'";  
        }
        if ($where['parent_id'] != '') {
            $condition[] = "a.parent_id='{$where['parent_id']}'";  
        }
        if (!is_null($where['status']) && $where['status'] !== '') {
            $condition[] = "b.status in ({$where['status']})";
        }
        if (!is_null($where['status_logistic']) && $where['status_logistic'] !== '') {
            $condition[] = "b.status_logistic={$where['status_logistic']}";  
        }
        if (!is_null($where['status_logistic>'])) {
            $condition[] = "b.status_logistic>{$where['status_logistic>']}";  
        }     
        if ($where['clear_pay'] !== null && $where['clear_pay'] !== '') {
            $condition[] = "b.clear_pay={$where['clear_pay']}";  
        }
        if ($where['distribution_type'] !== null && $where['distribution_type'] !== '') {
            $condition[] = "a.distribution_type={$where['distribution_type']}";  
        }    
        if ($where['hang']) {
            $where['hang']=trim($where['hang']);
            $condition[] = "hang='{$where['hang']}'";  
        }
        if (!is_null($where['status_return']) && $where['status_return'] !== '') {
            $condition[] = "b.status_return={$where['status_return']}";  
        }        
        if (!is_null($where['status_pay']) && $where['status_pay'] !== '') {            
            if ($where['status_pay'] == 3) {//部分支付
                $condition[] = "b.status_pay=0 and (price_payed>0 || price_from_return>0)";
            } else {
                $condition[] = "b.status_pay={$where['status_pay']}";
            }
        }

        if (!is_null($where['invoice_type']) && $where['invoice_type'] !== '') {

			if($where['invoice_type']==0){
				$condition[] = "a.invoice_type = 0 ";
			}elseif($where['invoice_type']==1){
				$condition[] = "a.invoice_type = 1 or a.invoice_type = 2 ";
			}
            
        }

        if ($where['min_price']) {
            $condition[] = "b.price_pay>='{$where['min_price']}'";  
        }
        if ($where['max_price']) {
            $condition[] = "b.price_pay<='{$where['max_price']}'";  
        }
        if ($where['is_lock'] == 'yes') {
            $condition[] = "b.lock_name = '{$this->_auth['admin_name']}'";  
        } else if ($where['is_lock'] == 'no') {
            $condition[] = "(b.lock_name ='' or b.lock_name is null)";  
        } 
        if ($where['pay_type']) {
            $condition[] = "b.pay_type like '{$where['pay_type']}%'";  
        }
        if ($where['logistic_code']) {
            $condition[] = "b.logistic_code='{$where['logistic_code']}'";  
        }
        if ($where['logistic_no']) {
            $condition[] = "b.logistic_no='{$where['logistic_no']}'";  
        }   
        if ($where['addr_consignee']) {
            $condition[] = "b.addr_consignee like '%{$where['addr_consignee']}%'";  
        }
        if ($where['addr_mobile']) {
            $condition[] = "b.addr_mobile  like  '%{$where['addr_mobile']}%'"; 
        }
        if ($where['product_sn']) {
            $where['product_sn']=trim($where['product_sn']);
            $condition[] = "c.product_sn='{$where['product_sn']}' and c.number > 0";
        }
        if ($where['goods_name']) {
            $where['goods_name']=trim($where['goods_name']);
            $condition[] =  "c.goods_name like '%{$where['goods_name']}%' and c.number > 0";
        }
        if ($where['card_sn']) {
            $where['card_sn']=trim($where['card_sn']);
            $condition[] =  "c.card_sn like '{$where['card_sn']}%'";
        }
    	if ($where['giftbywho']) {
            $where['giftbywho']=trim($where['giftbywho']);
            $condition[] = "a.giftbywho like '%{$where['giftbywho']}%'";
        }
        if ($where['yes_shop_id']) {
            $condition[] = "(a.shop_id = '1' or a.shop_id = '2')";
        }
        if ($where['no_shop_id']) {
            $condition[] = "a.shop_id != '1' AND a.shop_id != '2' ";
        }
        if ($where['shop_id']) {
            $condition[] = "a.shop_id = '{$where['shop_id']}'";
        }
        if ($where['external_order_sn']) {
            $condition[] = "a.external_order_sn = '{$where['external_order_sn']}'";  
        }
        if ($where['price_order_from']) {
            $condition[] = "(b.price_order >= '{$where['price_order_from']}')";
        }
        if ($where['price_order_to']) {
            $condition[] = "(b.price_order <= '{$where['price_order_to']}')";
        }
        if (!is_null($where['source']) && $where['source'] !== '') {
            $condition[] = "a.source='{$where['source']}'";  
        }
        if ($where['only_show_gift']) {
            $condition[] = "p.is_gift_card = 1 and c.number > 0";
        }  
		$where['price_limit'] && $condition[] = "b.audit_status = '1'";
		$where['lid'] && $condition[] = "a.lid = '{$where['lid']}'";

        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' AND ', $condition);
        }
       
        $sql = "FROM {$this -> _table_order} a 
                left join {$this -> _table_order_batch} as b on a.order_id = b.order_id 
                left join {$this -> _table_shop} as s on a.shop_id = s.shop_id ";
	    
		$sql .= "left join {$this -> _table_order_batch_goods} as c on b.order_batch_id = c.order_batch_id 
				 left join {$this -> _table_product} as p on c.product_id = p.product_id ";
  
        $sql .= "WHERE 1=1 {$condition} ";
		$sqlOfList = "SELECT DISTINCT(b.batch_sn), a.*,b.*,s.shop_name {$sql}";
        
		$sqlOfList .= "ORDER BY b.add_time DESC ";
		$page && $sqlOfList .= "LIMIT {$this -> _pageSize} OFFSET " . (($page - 1) * $this -> _pageSize);
        $sqlOfTotal = "SELECT count(*) AS total, SUM(price_order) AS total_price_order, SUM(balance_amount-balance_point_amount) as total_balance_amount FROM 
                       (SELECT DISTINCT(b.batch_sn), price_order, balance_amount, balance_point_amount {$sql} ) as t1";
        $sqlOfVitual = "SELECT SUM(amount) AS total_amount FROM 
                       (SELECT DISTINCT(b.batch_sn), c.sale_price * (c.number - c.return_number) as amount {$sql} and p.is_gift_card) as t1";
        $data['data'] = $this -> _db -> fetchAll($sqlOfList);
        $tmp = $this -> _db -> fetchRow($sqlOfTotal);
        $data['total'] = $tmp['total'];
        $data['total_price_order'] = $tmp['total_price_order'];
        $data['total_balance_amount'] = $tmp['total_balance_amount'];
        $tmp = $this -> _db -> fetchRow($sqlOfVitual);
        $data['total_vitual_amount'] = $tmp['total_amount'];
        return $data;
    }
    /**
     * 更新订单
     *
     * @param   array   $where
     * @param   array   $data
     * @return  bool
     */
    public function updateOrder($where, $data)
    {
        $condition = '';
        if ($where['order_sn']) {
            $condition[] = "order_sn = '{$where['order_sn']}'";
        }
        if ($where['order_id']) {
            $condition[] = "order_id = '{$where['order_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
        if ($condition) {
            return $this -> _db -> update($this->_table_order, $data, $condition);
        }
    }
	/**
     * 添加订单操作日志
     *
     * @param   array   $data
     * @return  int
     */
    public function addOrderBatchLog($data)
    {
        return $this -> _db -> insert($this->_table_order_batch_log, $data);
    }
	/**
     * 添加订单商品价格修改操作日志
     *
     * @param   array   $data
     * @return  bool
     */
    public function addOrderBatchGoodsLog($data)
    {
        return $this -> _db -> insert($this->_table_order_batch_goods_log, $data);
    }
	/**
     * 取订单商品信息
     *
     * @param   array   $where
     * @param   string   $orderBy
     * @return  array
     */
    public function getOrderBatchGoods($where, $orderBy = null)
    {
        $condition = null;
        $fields = 't1.*,t2.is_vitual,t2.is_gift_card';
        $tables = "{$this -> _table_order_batch_goods} as t1";
        if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']) + 86400;
            if ($fromDate <= $toDate) {
                $condition[] = "(t1.add_time between {$fromDate} and {$toDate})";
            }
        }
        if ($where['order_sn']) {
        	$sn=explode('_', $where['order_sn']);
            $condition[] = "t1.order_sn = '{$sn[0]}'";
        }
        if ($where['batch_sn']) {
            $condition[] = "t1.batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['batch_sn_in']) {
            $condition[] = "t1.batch_sn in ({$where['batch_sn_in']})";
        }
        if ($where['type']) {
            $condition[] = "t1.type = '{$where['type']}'";
        }
        if (!is_null($where['is_send'])) {
            $condition[] = "t1.is_send = '{$where['is_send']}'";
        }
        if ($where['card_sn_is_not_null']) {
            $condition[] = "t1.card_sn IS NOT NULL";
        }
        if (!is_null($where['product_id>'])) {
            $condition[] = "t1.product_id > '{$where['product_id>']}'";
        }
        if (!is_null($where['product_id'])) {
            $condition[] = "t1.product_id = '{$where['product_id']}'";
        }
        if ($where['order_batch_goods_id']) {
            $condition[] = "t1.order_batch_goods_id = '{$where['order_batch_goods_id']}'";
        }
        if (!is_null($where['is_send'])) {
            $condition[] = "t1.is_send = '{$where['is_send']}'";
        }
        if (!is_null($where['parent_id'])) {
            $condition[] = "t1.parent_id = '{$where['parent_id']}'";
        }
        if (!is_null($where['number>'])) {
            $condition[] = "t1.number > '{$where['number>']}'";
        }
        if (!is_null($where['(number-return_number)>'])) {
            $condition[] = "(t1.number-t1.return_number)>'{$where['(number-return_number)>']}'";
        }
        if (!is_null($where['order_batch_goods_id'])) {
            $condition[] = "t1.order_batch_goods_id = '{$where['order_batch_goods_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        
        if ($condition) {
            $sql = "SELECT {$fields} FROM {$tables} left join {$this -> _table_product} as t2 on t1.product_id = t2.product_id where 1 {$condition} {$orderBy}";
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
    
    /**
     * 删除订单商品信息
     *
     * @param   string   $where
     * @return  void
     */
    public function delOrderBatchGoods($where)
    {
        $this -> _db -> delete($this -> _table_order_batch_goods, $where);
    }
    
	/**
     * 取指定订单ID的商品信息
     *
     * @param   array       $inBatchSN
     * @param   boolean     $includeOffer
     * @param   boolean     $includeCoupon
     * @return  array
     */
    public function getOrderBatchGoodsInBatchSN($inBatchSN, $includeOffer = false, $includeCoupon = false)
    {
        if ($inBatchSN) {
            $inBatchSN = "'" . implode("','", $inBatchSN) . "'";
            if (!$includeOffer && !$includeCoupon) {
                $where = 'and t1.product_id > 0';
            }
            else {
                if ($includeOffer && $includeOffer) {
                    $where = " and (t1.product_id > 0 or t1.type in (1,2))";
                }
                else {
                    if ($includeOffer) {
                        $where = " and (t1.product_id > 0 or t1.type = 1)";
                    }
                    if ($includeCoupon) {
                        $where = " and (t1.product_id > 0 or t1.type = 2)";
                    }
                }
            }
            $sql = "SELECT t1.*,t2.is_vitual,t2.is_gift_card FROM {$this -> _table_order_batch_goods} as t1 left join {$this -> _table_product} as t2 on t1.product_id = t2.product_id where t1.batch_sn in ({$inBatchSN}) and t1.number > 0 {$where}";
            return $this -> _db -> fetchAll($sql);
        }
    }
	/**
     * 取订单日志
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatchLog($where)
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
        $sql = "SELECT * FROM {$this -> _table_order_batch_log} where 1=1 {$condition} order by order_batch_log_id desc";
        return $this -> _db -> fetchAll($sql);
    }
	/**
     * 取得指定条件的订单支付日志
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function getOrderBatchPayLog($batchSN)
    {
        $sql = "SELECT * FROM {$this -> _table_order_pay_log} where 1=1 and batch_sn='{$batchSN}' order by add_time desc";
        return $this -> _db -> fetchAll($sql);

    }
	/**
     * 添加一条支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderPayLog($data)
    {
        $this -> _db -> insert($this->_table_order_pay_log, $data);
		return $this -> _db -> lastInsertId();
    }

	/**
     * 删除一条支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function delOrderPayLog($batch_sn)
    {
        $whereitem = $this -> _db -> quoteInto('batch_sn = ?', $batch_sn);
        return $this -> _db -> delete($this -> _table_order_pay_log, $whereitem);
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
        return $this -> _db -> fetchAll("SELECT * FROM {$this -> _table_order_batch_adjust} where 1=1 {$condition}");
    }
	/**
     * 取得指定条件的支付方式
     *
     * @param   array   $where
     * @return  array
     */
    public function getPayment($where)
    {
        if ($where['pay_type']) {
            $condition[] = "pay_type = '{$where['pay_type']}'";
        }
        if (!is_null($where['status'])) {
            $condition[] = "status='{$where['status']}'";  
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
     * 取得指定条件的地区列表
     *
     * @param   array   $where
     * @return  array
     */
    public function getArea($where)
    {
        if ($where['parent_id']) {
            $condition[] = "parent_id = '{$where['parent_id']}'";
        }
        if ($where['area_id']) {
            $condition[] = "area_id = '{$where['area_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        return $this -> _db -> fetchAll("SELECT * FROM {$this->_table_area} where 1=1 {$condition}");
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
            if ($where['fromdate'] && $where['todate']) {
                $fromDate = strtotime($where['fromdate']);
                $toDate = strtotime($where['todate']) + 86400;
                if ($fromDate <= $toDate) {
                    $condition[] = "(b.add_time between {$fromDate} and {$toDate})";
                }
            }
            if ($where['batch_sn']) {
                $condition[] = "b.batch_sn = '{$where['batch_sn']}'";
            }
            if ($where['order_sn']) {
                $condition[] = "b.order_sn = '{$where['order_sn']}'";
            }
            if(!is_null($where['parent_batch_sn'])){
                $condition[] = "parent_batch_sn='{$where['parent_batch_sn']}'";  
            }
            if(!is_null($where['status']) && $where['status'] !== ''){
                $condition[] = "status in ({$where['status']})";  
            }
            if(!is_null($where['status_logistic']) && $where['status_logistic'] !== ''){
                $condition[] = "status_logistic={$where['status_logistic']}";  
            }
            if(!is_null($where['status_pay']) && $where['status_pay'] !== ''){
                $condition[] = "status_pay={$where['status_pay']}";  
            }
            if(!is_null($where['status_return']) && $where['status_return'] !== ''){
                $condition[] = "status_return={$where['status_return']}";  
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
            $sql = "SELECT a.order_id,a.user_id,a.user_name,a.rank_id,a.invoice_type,a.invoice,a.invoice_content,a.parent_id,a.parent_param,a.proportion,a.giftbywho,a.part_pay,a.shop_id,a.external_order_sn,a.distribution_type,a.source,a.lid, ";
            $sql .= "b.* FROM {$this->_table_order} a,{$this->_table_order_batch} b ";
            $sql .= "where 1=1 and a.order_id = b.order_id {$condition} order by order_batch_id desc";
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
	/**
     * 取得指定条件的订单批次以及基础订单表信息
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatchInfo($where=null, $fields='*')
    {
        if ($where['batch_sn']) {
            $condition[] = "a.batch_sn = '{$where['batch_sn']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        $sql = "SELECT {$fields} FROM {$this->_table_order_batch} a left join {$this->_table_order} b on a.order_sn=b.order_sn ";
        $sql .= "where 1=1 {$condition}";
        
        return $this -> _db -> fetchAll($sql);
    }
	/**
     * 添加换货批次
     *
     * @param   array   $data
     * @return  int
     */
    public function addOrderBatch($data)
    {
        $this->_db->insert($this->_table_order_batch, $data);
		return $this->_db->lastInsertId();
    }
	/**
     * 客服调整价格记录
     *
     * @param   array   $data
     * @return  int
     */
    public function addOrderBatchAdjust($data)
    {
        $this->_db->insert($this->_table_order_batch_adjust, $data);
		return $this->_db->lastInsertId();
    }
	/**
     * 取消退换货 删除 新单调整价格记录
     *
     * @param   array   $where
     * @return  int
     */
    public function delOrderBatchAdjust($where)
    {
        $condition = '';
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['add_time']) {
            $condition[] = "add_time = '{$where['add_time']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
		if ($condition) {
		    return $this -> _db -> delete($this -> _table_order_batch_adjust, $condition);
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
            return $this -> _db -> update($this -> _table_order_batch_goods, $data, $condition);
        }
    }
	/**
     * 添加订单商品
     *
     * @param   array   $data
     * @return  int
     */
	public function addOrderBatchGoods($data)
    {
        $this -> _db -> insert($this -> _table_order_batch_goods, $data);
		return $this -> _db -> lastInsertId();
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
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
        if ($condition) {
            return $this -> _db -> update($this -> _table_order_batch, $data, $condition);
        }
    }
	/**
     * 取商品退换货理由列表
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatchGoodsReturn($where)
    {
        $condition = '';
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['add_time']) {
            $condition[] = "add_time = '{$where['add_time']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        if ($condition) {
            $sql = "select * from {$this->_table_order_batch_goods_return} where 1=1 {$condition}";
            return $this -> _db -> fetchAll($sql);
        }    
    }
	/**
     * 删除退换货理由
     *
     * @param   array   $where
     * @return  bool
     */
    public function delOrderBatchGoodsReturn($where)
    {
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['add_time']) {
            $condition[] = "add_time = '{$where['add_time']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
		if ($condition) {
		    return $this -> _db -> delete($this -> _table_order_batch_goods_return, $condition);
		}
    }
	/**
     * 删除退换货理由列表
     *
     * @param   array   $array
     * @return  bool
     */
    public function delOrderBatchGoodsReturnReasonByIDArr($array)
    {
        $strID = implode(',', $array);
        if ($strID) {
            $condition = 'id in('.$strID.')';
            return $this -> _db -> delete($this -> _table_order_batch_goods_return_reason, $condition);
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
        if ($where['area_id'] < 1) {
            
        }
        else {
            $area = array_shift($this -> getArea(array('area_id'=>$where['area_id'])));
            $strategy=unserialize($area['strategy']);
            foreach($strategy as $key=>$var){
                if($var['open']=='1'){
                   $strategyCode[]="'".$key."'";
                }
            }
            $condition[] = " b.logistic_code in (".implode(',', $strategyCode).") ";
            if ($where['area_id']) {
                $condition[] = "b.area_id = '{$where['area_id']}'";
            }
        }
        
        if ($where['pay_type'] && $where['pay_type']=='cod' ) {
            $condition[] = "b.cod = 1 ";
        }
        else {
			$condition[] = " b.logistic_code in ('yt','sf','ems','self','externalself') ";
           //$condition[] = "b.cod = 0";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        if ($condition) {
            $sql = "select b.logistic_code,b.country_id,b.province_id,b.city_id,b.area_id,b.open,b.delivery,b.area,b.cod, a.name as logistic_name,a.cod_rate,a.cod_min,a.fee_service,a.logistic_code from {$this->_table_logistic} a left join {$this->_table_logistic_area} b  on a.logistic_code = b.logistic_code  where 1=1 and b.open=1 {$condition} ORDER BY logistic_id ASC ";
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
	
	/**
     * 新增订单
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrder($data)
    {
        $this -> _db -> insert($this->_table_order, $data);
		return $this -> _db -> lastInsertId();
    }
	/**
     * 新增退换货理由
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderBatchGoodsReturn($data)
    {
        $this -> _db -> insert($this -> _table_order_batch_goods_return, $data);
		return $this -> _db -> lastInsertId();
    }
	/**
     * 新增退换货理由列表
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderBatchGoodsReturnReason($data)
    {
        $this -> _db -> insert($this -> _table_order_batch_goods_return_reason, $data);
		return $this -> _db -> lastInsertId();
    }
	/**
     * 锁定订单
     *
     * @param   string     $admin
     * @param   string     $batchSN
     * @param   array     $data
     * @return  int
     */
    public function lock($admin, $batchSN, $data)
    {
        return $this -> _db -> update($this -> _table_order_batch, $data, "batch_sn='{$batchSN}' and (lock_name='' || lock_name is null || lock_name='{$admin}')");
    }
	/**
     * 超级锁定订单
     *
     * @param   string     $batchSN
     * @param   array     $data
     * @return  int
     */
    public function superLock($batchSN, $data)
    {
        return $this -> _db -> update($this -> _table_order_batch, $data, "batch_sn='{$batchSN}'");
    }

	/**
     * 更新订单SN
     *
     * @param   int     $orderID
     * @param   string     $orderSN
     * @return  int
     */
    public function updateOrderSN($orderID, $orderSN)
    {
        if ($orderID) {
            return $this -> _db -> update($this -> _table_order, array('order_sn' => $orderSN, 'batch_sn' => $orderSN), "order_id='{$orderID}'");
        }
    } 
	/**
     * 取地区物流价格
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaPrice($areaID)
    {
        return $this -> _db -> fetchOne("select price from `{$this->_table_area}` where area_id={$areaID}");
    }
	/**
     * 取邮编
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaZip($areaID)
    {
        return $this -> _db -> fetchOne("select zip from `{$this->_table_area}` where area_id={$areaID}");
    }
    
    /**
     * 得到交易完成的订单列表is_fav != 1
     * 
     * @return array
     */
    public function getCompleteOrder() {
    	$sql = "select a.user_id,a.user_name,b.*,c.member_id from {$this->_table_order} a left join {$this->_table_order_batch} b on a.batch_sn=b.batch_sn left join {$this->_table_member} c on a.user_id=c.user_id where b.status=0 and (b.status_logistic=3 or b.status_logistic=4 )and b.status_pay=2 and b.add_time<".(time()-86400*40)." and (b.is_fav is null or b.is_fav<1) and  a.user_id > '10'  and shop_id = 1 limit 1000";
    	return $this->_db->fetchAll($sql);
    }

    /**
     * 更新优惠券状态
     * 
     * @param   string  $card_sn
     * @param   int     $status
     * @return  void
     */
    public function setCouponStatus($card_sn, $status) {
        $this -> _db -> update($this -> _table_coupon_card, array('status' => $status), "card_sn='{$card_sn}'");
    }
    
    /**
     * 获得用户优惠券信息
     * 
     * @param   string  $card_sn
     * @return  array
     */
    public function getCouponInfo($card_sn) {
    	$sql = "select t1.*,t2.freight from {$this->_table_coupon_card} as t1 left join {$this->_table_coupon_card_log} as t2 on t1.log_id = t2.log_id where t1.card_sn='$card_sn'";
    	return $this->_db->fetchRow($sql);
    }

	/**
     * 获取订单批次数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetchOrderBatch($where = null, $fields = '*',  $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
        $whereSql = " where b.status=0 and b.status_pay=2 and pay_type!='cod' and price_payed >0 " ;
		if ($where != null) {
			$whereSql.= $where;
		}
        $table=" $this->_table_order a left join $this->_table_order_batch b on a.batch_sn=b.batch_sn  ";
        return array('list'=>$this -> _db -> fetchAll(" SELECT $fields FROM $table $whereSql  $limit "),'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql"));
	}
	/**
     * 更新订单批次
     *
     * @param   string     $batchSN
     * @param   array     $data
     * @return  int
     */
    public function clearOrder($data, $where)
    {
        return $this -> _db -> update($this -> _table_order_batch, $data, $where);
    }
    
    public function getOrderMain($where, $field = '*')
	{
	    return $this -> _db -> fetchAll("select {$field} from {$this -> _table_order} where {$where}");
	}

   /**
     * 退换货理由列表
     *
     * @param   array   $where
     * @return  array
     */
    public function getReason($where = null)
    {	
		$sort="";
        if ($where['reason_id']) {
			$limit="limit 1";
			$sort=" desc";
            $condition[] = "reason_id = '{$where['reason_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        $sql = "SELECT * FROM {$this -> _table_reason} where 1=1 {$condition} order by sort $sort $limit";
			return $this -> _db -> fetchAll($sql);
		
    }
	/**
     * 添加退货理由
     *
     * @param   array     $data
     * @return  int
     */
    public function addreason($data)
    {	
		$count=$this->_db->fetchOne("select count(*) from ".$this->_table_reason." where label='".$data['res']."'");
		if($count==0){
			$id=$this ->_db->fetchOne("select reason_id from ".$this->_table_reason." order by reason_id desc");
			$res=array('sort'=>($id+1),'label'=>$data['res']);
			$this -> _db -> insert($this->_table_reason, $res);
			return $this -> _db -> lastInsertId();
		}else{
			return 'isExists';
		}

    }
	/**
	*获取退货订单列表
	**/
	public function getreturnorder($where,$page,$pagesize=20){
		$limit=" limit ".(($page-1)*$pagesize).",$pagesize";
		$wheresql="";
		if(is_array($where)&&!empty($where)){
			if($where['fromdate']&&$where['todate']){
				$wheresql.=" and add_time between ".strtotime($where['fromdate'])." and ".strtotime($where['todate']);
			}
			if($where['reasons']){
				$wheresql.=" and c.reason_id=".intval($where['reasons']);
			}
			if($where['goods_sn']){
				$wheresql.=" and batch_sn in (select distinct(batch_sn) from shop_order_batch_goods where product_sn='".$where['goods_sn']."')";
			}
			if($where['goods_name']){
				$wheresql.=" and batch_sn in (select distinct(batch_sn) from shop_order_batch_goods where goods_name='".$where['goods_name']."')";
			}
		}
			$sql="SELECT batch_sn,add_time as return_time,reason as details ,group_concat(distinct c.label) as reasons   FROM `shop_order_batch_goods_return` as a,shop_order_batch_goods_return_reason as b ,shop_reason as c where a.id=b.id  and  b.reason_id=c.reason_id   ".$wheresql." GROUP BY batch_sn $limit";
			$sqlcount="select count(batch_sn) as countall from (SELECT batch_sn,group_concat(distinct c.label) as reasons   FROM `shop_order_batch_goods_return` as a,shop_order_batch_goods_return_reason as b ,shop_reason as c where a.id=b.id  and  b.reason_id=c.reason_id ".$wheresql." group by batch_sn)as k ";
			$data['total']=$this->_db->fetchone($sqlcount);
			$data['details']=$this->_db->fetchAll($sql);
			return $data;
	}
	
	/**
     * 添加退货日志
     *
     * @param   array     $data
     * @return  int
     */
	public function addOrderReturn($data)
	{
	    $this -> _db -> insert($this -> _table_order_batch_return, $data);
	    return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新退货日志时间
     *
     * @param   array     $data
     * @return  int
     */
	public function updateOrderReturnDate($batchSN)
	{
	    $this -> _db -> update($this -> _table_order_batch_return, array('finish_time' => time()), "batch_sn = '{$batchSN}'");
	}

	/**
     * 根据订单ID获取订单数据
     *
     * @param   array     $data
     * @return  int
     */
	public function getOrderBatchInfoByOrderBatchId($order_batch_id)
	{
		$order_batch_id = intval($order_batch_id);
		if ($order_batch_id < 1) {
			$this->_error = '订单ID不正确';
			return false;
		}

		$sql  = "SELECT * FROM `shop_order_batch` WHERE `order_batch_id` = '{$order_batch_id}' LIMIT 1";
		$order_info = $this->_db->fetchRow($sql);

		if (count($order_info) < 1) {
			$this->_error = '没有找到相关订单';
			return false;
		}

		return $order_info;

	}

	/**
     * 根据订单SN获取订单数据
     *
     * @param   string
	 *
     * @return  array
     */
	public function getOrderBatchInfoByOrderBatchSn($batch_sn)
	{

		$batch_sn = trim($batch_sn);
		if (empty($batch_sn)) {
			$this->_error = '订单SN不正确';
			return false;
		}

		$sql  = "SELECT * FROM `shop_order_batch` WHERE `batch_sn` = '{$batch_sn}' LIMIT 1";
		$order_info = $this->_db->fetchRow($sql);

		if (count($order_info) < 1) {
			$this->_error = '没有找到相关订单';
			return false;
		}

		return $order_info;

	}

	/**
	 * 根据订单ID获取订单商品
	 *
	 * @param    int
	 *
	 * @return   array
	 **/
	public function getOrderBatchGoodsByOrderBatchId($order_batch_id)
	{
		$order_batch_id = intval($order_batch_id);
		if ($order_batch_id < 1) {
			$this->_error = '订单ID不正确';
			return false;
		}

		$sql  = "SELECT * FROM `shop_order_batch_goods` WHERE `order_batch_id` = '{$order_batch_id}'";
		$order_goods = $this->_db->fetchAll($sql);
		if (count($order_goods) < 1) {
			$this->_error = '没有找到相关订单';
			return false;
		}

		return $order_goods;
	}

	/**
	 * 根据订单sn获取订单商品
	 *
	 * @param    int
	 *
	 * @return   array
	 **/
	public function getOrderBatchGoodsByOrderBatchSn($batch_sn)
	{
		$batch_sn = trim($batch_sn);
		if ($batch_sn < 1) {
			$this->_error = '订单SN不正确';
			return false;
		}

		$sql  = "SELECT `order_batch_goods_id`, `order_batch_id`, `batch_sn`, `product_sn`, `sale_price`, `parent_id`, `product_id`, `avg_price`, `number`, `return_number` FROM `shop_order_batch_goods` WHERE `batch_sn` = '{$batch_sn}'";
		$order_goods = $this->_db->fetchAll($sql);
		if (count($order_goods) < 1) {
			$this->_error = '没有找到相关订单';
			return false;
		}

		return $order_goods;
	}
	
	/**
     * 更改订单已支付金额
     *
     * @param   string      $batchSN
     * @param   float       $amount
     * @param   string      $type
     * @return  void
     */
    public function updateOrderPayed($batchSN, $amount, $type)
    {
        if ($type == 'gift_card') {
            $field = 'gift_card_payed';
        }
        else if ($type == 'account') {
            $field = 'account_payed';
        }
        else if ($type == 'point') {
            $field = 'point_payed';
        }
        else {
            $field = 'price_payed';
        }
        $this -> _db -> execute("update {$this -> _table_order_batch} set {$field} = {$field} + {$amount} where batch_sn = '{$batchSN}'");
    }

	/**
	 * 更新订单数据
	 *
	 * @param    int
	 *
	 * @return   array
	 */
	public function updateOrderInfoByOrderId($order_id, $params)
	{
		$order_id = intval($order_id);
		if ($order_id < 1) {
			$this->_error = '订单ID不正确';
			return false;
		}

		if (count($params) < 1) {
			$this->_error = '没有需要更新的数据';
			return false;
		}

		return $this->_db->update('shop_order_batch', $params, "order_batch_id ='{$order_id}'");

	}

	/**
     * 根据客户ID获取订单汇总
     * 
     * @param    int
	 *
     * @return   array
     */
	public function getCustomerOrderCountByCustomerId($customer_id)
	{
		$customer_id = intval($customer_id);

		if ($customer_id < 1) {
			$this->_error = '客户ID不正确';
			return false;
		}

		$_join[] = "LEFT JOIN `shop_order` o ON b.order_id = o.order_id";
		$_join[] = "LEFT JOIN `shop_order_batch_goods` g ON o.order_id = g.order_id";

		$_condition[] = "customer_id = '{$customer_id}'";
		$_condition[] = "b.is_send = 1 AND status = '0' AND status_return = 0 AND status_pay = 2";

		$sql = "SELECT o.order_id ,b.order_batch_id,  price_order, sum(`number`) as number FROM  `shop_order_batch` b ". implode(' ', $_join) .
			   " WHERE ". implode(' AND ', $_condition). " GROUP BY b.order_batch_id";

		$infos =  $this->_db->fetchAll($sql);

		if (empty($infos)) {
			return array();
		}

		$order_info = array(
			'order_count' => count(array_unique($this->getSingleKey($infos, 'order_id'))),
			'price_order' => array_sum($this->getSingleKey($infos, 'price_order')),
			'number'      => array_sum($this->getSingleKey($infos, 'number')),
		);

		return $order_info;
	}

	/**
     * 获取客户产品总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getCustomerProductCount($params)
	 {
		list($_condition, $_join) = $this->getCustomerProductCondition($params);

		$sql = "SELECT COUNT(*) as count FROM `shop_order_batch` b ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	/**
     * 根据客户ID获取订单商品
     * 
     * @param    int
	 *
     * @return   array
     */
	public function browseCustomerProducts($params, $limit)
	{	
		list($_condition, $_join) = $this->getCustomerProductCondition($params);

		$field = array(
			'customer_id',
			'goods_style',
			'goods_name',
			'product_sn',
			'number',
			'b.batch_sn',
			'b.add_time',
			'product_id',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_order_batch` b ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

		return $this->_db->fetchAll($sql);
	}

	/**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getCustomerProductCondition($params)
	 {	
		$_condition[] = "customer_id = '{$params['customer_id']}'";

		$_condition[] = "b.is_send = 1 AND status = '0' AND status_return = 0 AND status_pay = 2";

		$_join[] = "LEFT JOIN `shop_order` o ON b.order_id = o.order_id";
		$_join[] = "LEFT JOIN `shop_order_batch_goods` g ON o.order_id = g.order_id";
		return array($_condition, $_join);
	 }

	 /**
     * 获取客户产品总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getCustomerOrderCount($params)
	 {
		list($_condition, $_join) = $this->getCustomerOrderCondition($params);

		$sql = "SELECT COUNT(distinct(b.order_batch_id)) as count FROM `shop_order_batch` b ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	/**
     * 根据客户ID获取订单商品
     * 
     * @param    int
	 *
     * @return   array
     */
	public function browseCustomerOrderInfos($params, $limit)
	{	
		list($_condition, $_join) = $this->getCustomerOrderCondition($params);

		$field = array(
			'customer_id',
			'price_order',
			'b.batch_sn',
			'b.add_time',
			'b.status',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_order_batch` b ". implode(' ', $_join) .
			   " WHERE ". implode(' AND ', $_condition) ." GROUP BY b.order_batch_id limit {$limit}";

		return $this->_db->fetchAll($sql);
	}

	/**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getCustomerOrderCondition($params)
	 {	
		$_condition[] = "customer_id = '{$params['customer_id']}'";

		$_condition[] = "b.is_send = 1 AND status IN(0,1,2) AND status_return = 0 AND status_pay = 2";

		$_join[] = "LEFT JOIN `shop_order` o ON b.order_id = o.order_id";
		$_join[] = "LEFT JOIN `shop_order_batch_goods` g ON o.order_id = g.order_id";
		return array($_condition, $_join);
	 }

	/**
     * 获取指定的键值
     * 
     * @param    array
	 * @param    string
	 *
     * @return   array
     */
	public function getSingleKey($array, $key)
	{
		$infos = array();
		foreach ($array as $val) {
			isset($val[$key]) && $infos[] = $val[$key];
		}

		return $infos;
	}
	
	/**
     * 添加财务退货产品金额
     *
     * @param   array   $data
     * @return  int
     */
    public function addFinanceReturnProduct($data)
    {
        return $this -> _db -> insert($this->_table_finance_product, $data);
    }
    
    /**
     * 更新财务退货产品金额
     *
     * @param   string   $where
     * @param   array    $data
     * @return  void
     */
    public function updateFinanceReturnProduct($where, $data)
    {
        $this -> _db -> update($this->_table_finance_product, $data, $where);
    }

	public function getError()
	{
		return $this->_error;
	}

}    