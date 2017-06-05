<?php

class Admin_Models_DB_DataAnalysis
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;

	/**
     * 订单表
     * 
     * @var    string
     */
	private $_table_order = 'shop_order';

	/**
     * 用户表
     * 
     * @var    string
     */
	private $_table_user = 'shop_user';

	/**
     * 订单商品表
     * 
     * @var    string
     */
	private $_table = 'shop_order_batch_goods';
	
	/**
     * 订单批次表
     * 
     * @var    string
     */
	private $_table_batch = 'shop_order_batch';
	
	/**
     * 商品表
     * 
     * @var    string
     */
	private $_table_goods = 'shop_goods';
	
	/**
     * 会员表
     * 
     * @var    string
     */
	private $_table_member = 'shop_member';

    /**
     * 运输单表
     * 
     * @var    string
     */
	private $_table_transport = 'shop_transport';
	/**/
	private $_table_order_batch = 'shop_order_batch';
	private $_table_order_batch_goods = 'shop_order_batch_goods';
	private $_table_out_tuan_order = 'shop_out_tuan_order';
	private $_table_out_tuan_goods = 'shop_out_tuan_goods';
	private $_table_goods_cat = 'shop_goods_cat';
	private $_table_finance = 'shop_finance';
	private $_table_product = 'shop_product';
    private $_table_instock = 'shop_instock';
    private $_table_instock_detail = 'shop_instock_detail';
    private $_table_instock_plan = 'shop_instock_plan';
    private $_table_outstock = 'shop_outstock';
    private $_table_outstock_detail = 'shop_outstock_detail';
    private $_table_logistic = 'shop_logistic';
    private $_table_area = 'shop_area';
    private $_table_group_goods = 'shop_group_goods';
    private $_table_purchase_payment = 'shop_purchase_payment';
    private $_table_supplier = 'shop_supplier';
    private $_table_payment = 'shop_payment';
    private $_table_order_batch_return = 'shop_order_batch_return';
    private $_table_transport_source = 'shop_transport_source';
    private $_table_finance_receivable = 'shop_finance_receivable';
    private $_table_finance_product = 'shop_finance_product';

	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}

	/**
     * 取得订单商品信息
     *
     * @param    array    $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getOrderGoods($where = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : "  AND g.is_send=1 AND g.number >0 AND g.goods_id >0 ";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}else{
                $whereSql = '  AND g.is_send=1 AND g.number >0 AND g.goods_id >0 ';
        }
        $extable = $this -> _table ." as g  INNER JOIN   ".$this -> _table_batch." as o  where  g.order_batch_id=o.order_batch_id " ;
		$sql = 'SELECT g.*,o.type,o.pay_name,o.logistic_name,o.addr_city,o.addr_province,o.addr_address FROM ' . $extable . $whereSql .
			' ORDER BY g.goods_id DESC , g.number DESC ' . $limit;
		$list=$this -> _db -> fetchAll($sql);
		$sqlcount = 'SELECT count(g.order_batch_goods_id) as count FROM ' . $extable . $whereSql ;
		$total = $this -> _db  -> fetchOne($sqlcount);
        return array('list' => $list, 'total' => $total);
	}

	/**
     * 导出订单商品信息数据
     *
     * @param    array    $where
     * @return   array
     */
	public function getExportGoods($where = null, $type ="xls")
	{
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " where 1=1  AND g.is_send=1 AND g.number >0 AND g.goods_id >0 ";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}else{
                $whereSql = ' where 1=1  AND g.is_send=1 AND g.number >0 AND g.goods_id >0 ';
        }
		if ($type == "xls") {
			$limitNum=10000;
		} else {
			$limitNum=45000;
		}
        $extable = $this -> _table ." as g  INNER JOIN   ".$this -> _table_batch." as o  ON  g.order_batch_id=o.order_batch_id  LEFT JOIN  ". $this ->_table_order." AS so ON   so.order_sn=o.order_sn  " ;


		$sql = 'SELECT g.*,o.type,o.pay_name,o.logistic_name,o.addr_city,o.addr_province,o.addr_address,so.parent_id FROM ' . $extable . $whereSql .
			' ORDER BY g.goods_id DESC limit '. $limitNum ;

        return $this -> _db -> fetchAll($sql);
	}

	/**
     * 销售订单查询
     *
     * @param    array    $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getSaleOrder($where = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " WHERE 1=1 ";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}else{
                $whereSql = ' WHERE 1=1 ';
        }
		$sql = 'SELECT order_id,order_sn,batch_sn,type,add_time,price_order,price_goods,pay_name,logistic_name,logistic_price,addr_city,addr_province ,addr_address FROM ' 
			. $this -> _table_batch . $whereSql . ' ORDER BY order_id DESC ' . $limit;
		$list=$this -> _db -> fetchAll($sql);

	   if(is_array($list) && count($list)){
            foreach ($list as $k => $v) {
				$inBatchSN[] = $v['batch_sn'];
			}
		   $product = $this -> getOrderBatchGoodsInBatchSN($inBatchSN);
		}
		$sqlcount = 'SELECT count(*) as count FROM ' . $this -> _table_batch . $whereSql ;
		$total = $this -> _db  -> fetchOne($sqlcount);
        return array('list' => $list,'product' => $product, 'total' => $total);
	}


	/**
     * 取指定订单ID的商品信息
     *
     * @param   array   $inBatchSN
     * @return  array
     */
    public function getOrderBatchGoodsInBatchSN($inBatchSN)
    {
        if ($inBatchSN) {
            $inBatchSN = "'" . implode("','", $inBatchSN) . "'";
            return $this -> _db -> fetchAll("SELECT * FROM {$this -> _table} where batch_sn in({$inBatchSN}) and product_id>0");   
        }
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
        if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']) + 86400;
            if ($fromDate <= $toDate) {
                $condition[] = "(b.add_time between {$fromDate} and {$toDate})";
            }
        }
        if ($where['order_sn']) {
            $condition[] = "b.order_sn = '{$where['order_sn']}'";
        }
        if(!is_null($where['pay_name'])){
            $condition[] = "pay_name='{$where['pay_name']}'";
        }
        if(!is_null($where['status']) && $where['status'] !== ''){
            $condition[] = "status={$where['status']}";
        }
        if(!is_null($where['status_logistic']) && $where['status_logistic'] !== ''){
            $condition[] = "status_logistic={$where['status_logistic']}";
        }
        if(!is_null($where['logistic_name']) && $where['logistic_name'] !== ''){
            $condition[] = "logistic_name='{$where['logistic_name']}'";
        }
        if(!is_null($where['addr_province']) && $where['addr_province'] !== ''){
            $condition[] = "addr_province='{$where['addr_province']}'";
        }
        if(!is_null($where['addr_city'])){
            $condition[] = "addr_city='{$where['addr_city']}'";
        }
        if(!is_null($where['type'])){
            $condition[] = "type='{$where['type']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        if ($condition) {
            $sql = "SELECT a.order_id,a.user_id,a.user_name,a.invoice,a.invoice_content,a.parent_id,a.parent_param,a.proportion,";
            $sql .= "b.*,c.user_name as u_name FROM {$this->_table_order} a left join {$this->_table_batch} b on a.order_id = b.order_id left join {$this->_table_user} c on a.parent_id=c.user_id ";
            $sql .= "where 1=1 {$condition} order by b.order_batch_id desc";
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
    
	/**
     * 得到一个订单的产品
     * 
     * @param int $order_id
     * @return array
     */
    public function getOneOrderGoods($order_id) {
    	if($order_id>0){
    		return $this -> _db -> fetchAll('select goods_name,number from '.$this -> _table.' where goods_id!=0 and order_id='.$order_id);
    	}
    	else{
    		return false;
    	}
    }
    
    /**
	 * 获得会员注册日期
	 */
	public function getUserRegTime($sql)
	{
	    return $this -> _db -> fetchAll("select t2.add_time,t1.parent_id from {$this -> _table_member} as t1 left join {$this -> _table_user} as t2 on t1.user_id = t2.user_id where 1 {$sql} order by t2.add_time");
	}
	
	/**
	 * 获得订单日期和用户ID
	 */
	public function getOrder($sql)
	{
	    return $this -> _db -> fetchAll("select t1.add_time,t1.user_id from {$this -> _table_order} as t1 left join {$this -> _table_batch} as t2 on t1.order_id = t2.order_id where {$sql} t2.status = 0 and t2.type in (0,1) order by t1.add_time");
	}
    
    /**
	 * 按订单状态获得用户订单信息
	 */
	public function getUserOrder($whereSql, $haveSql = null)
	{
	    if ($haveSql) {
	        $haveSql = "having {$haveSql}";
	    }
	    $sql = "select t2.user_id,t2.user_name,t1.status,count(*) as order_count,sum(price_pay) as total_amount,sum(price_payed) as real_amount from {$this -> _table_batch} as t1 
	            left join {$this -> _table_order} as t2 on t1.order_id = t2.order_id 
	            where {$whereSql} 1 group by t2.user_id,t1.status {$haveSql}";
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得用户订单商品
	 */
	public function getUserOrderGoods($whereSql)
	{
	    $sql = "select user_id,goods_id,goods_name,number from {$this -> _table} as t1
	            left join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_batch} as t3 on t1.order_id = t3.order_id
	            where t3.status = 0 and t1.goods_id > 0 and t1.type in (0,1,5,6) {$whereSql}";
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得Order Batch
	 */
	public function getAllOrderBatch($whereSql)
	{
	    $sql = "select t1.type,t1.status,t1.status_logistic,t1.status_return,t1.price_order,t1.price_payed,t1.price_logistic,t1.add_time,t2.shop_id,t2.user_name from {$this -> _table_batch} as t1
	            left join {$this -> _table_order} as t2 on t1.order_id = t2.order_id where 1 {$whereSql}";
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得产品出库信息
	 */
	public function getProductOutstock($whereSql, $group = null)
	{
	    if ($group) {
	        if ($group == 1) {
	            $groupBy = ",FROM_UNIXTIME(logistic_time, '%Y-%m')";
	            $field =  ",FROM_UNIXTIME(logistic_time, '%Y-%m') as date";
	        }
	        else if ($group == 2) {
	            $groupBy = ",FROM_UNIXTIME(logistic_time, '%Y-%m-%d')";
	            $field =  ",FROM_UNIXTIME(logistic_time, '%Y-%m-%d') as date";
	        }
	    }
	    $sql = "select product_id,product_sn,product_name,sum(number) as OutStockCount,cost,sum(cost * number) as cost_amount,sum(cost / (1 + invoice_tax_rate / 100) * number) as no_tax_cost_amount,number {$field} from
	            (select distinct(t4.id),t2.logistic_time,t5.product_id,t5.product_sn,product_name,t4.number,t4.cost,t5.invoice_tax_rate from {$this -> _table_outstock} as t1
	            inner join {$this -> _table_transport_source} as t7 on t1.outstock_id = t7.outstock_id
	            inner join {$this -> _table_batch} as t2 on t2.batch_sn = t7.bill_no
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            inner join {$this -> _table_outstock_detail} as t4 on t1.outstock_id = t4.outstock_id
	            inner join {$this -> _table_product} as t5 on t4.product_id = t5.product_id
	            left join {$this -> _table_shop} as t6 on t3.shop_id = t6.shop_id
	            where {$whereSql}) as a
	            group by product_id {$groupBy}";
        
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得产品平圴售价
	 */
	public function getProductReturnAndEqPrice($whereSql, $group = null)
	{
	    if ($group == 1) {
	            $groupBy = ",FROM_UNIXTIME(logistic_time, '%Y-%m')";
	            $field =  ",FROM_UNIXTIME(logistic_time, '%Y-%m') as date";
	        }
	        else if ($group == 2) {
	            $groupBy = ",FROM_UNIXTIME(logistic_time, '%Y-%m-%d')";
	            $field =  ",FROM_UNIXTIME(logistic_time, '%Y-%m-%d') as date";
	        }
	    $whereSql = str_replace('t1.finish_time', 't2.logistic_time', $whereSql);
	    $sql = "select t1.product_id,sum(t1.amount) as amount,sum(t1.number) as number,sum(t2.return_number) as return_number,
	            sum(t1.eq_price * t2.return_number) as return_amount,sum(t2.shop_price * t2.return_number) as return_cost,
	            sum(t2.shop_price * t2.return_number / (1 + t2.invoice_tax_rate / 100)) as return_no_tax_cost {$field} from 
	            (select t2.logistic_time,t1.batch_sn,t1.product_id,sum(t1.eq_price * t1.number) as amount,sum(number) as number,t1.eq_price from {$this -> _table} as t1
	            inner join {$this -> _table_batch} as t2 on t1.batch_sn = t2.batch_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            inner join {$this -> _table_product} as t5 on t1.product_id = t5.product_id
	            left join {$this -> _table_shop} as t6 on t3.shop_id = t6.shop_id
	            where {$whereSql} and t1.product_id > 0
	            group by t1.batch_sn,t1.product_id) as t1
	            left join 
	            (select t1.item_no,t5.product_id,t4.number as return_number,t4.shop_price,t5.invoice_tax_rate from {$this -> _table_instock} as t1
	            inner join {$this -> _table_batch} as t2 on t1.item_no = t2.batch_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            inner join {$this -> _table_instock_detail} as t4 on t1.instock_id = t4.instock_id
	            inner join {$this -> _table_product} as t5 on t4.product_id = t5.product_id
	            left join {$this -> _table_shop} as t6 on t3.shop_id = t6.shop_id
	            where {$whereSql} and t1.bill_type = 1) as t2 
	            on t1.batch_sn = t2.item_no and t1.product_id = t2.product_id
	            group by t1.product_id {$groupBy}";

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得运输单
	 */
	public function getTransport($whereSql)
	{
	    $sql = "select count(*) as number,t1.logistic_status,t1.logistic_code,t4.name as logistic_name,sum(t1.logistic_price) as logistic_price from {$this -> _table_transport} as t1
	            inner join {$this -> _table_batch} as t2 on t1.bill_no = t2.order_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            left join {$this -> _table_logistic} as t4 on t1.logistic_code = t4.logistic_code
	            where t2.status = 0 and logistic_status in (1,2,3,4,5,6) and t1.logistic_code <> 'self' and t1.logistic_code <> 'external' and t1.logistic_code is not null {$whereSql}
	            group by logistic_code,logistic_status";
	    return $this -> _db -> fetchAll($sql);
    }
    
    /**
	 * 获得匹配运输单数量
	 */
	public function getMatchTransportCount($whereSql)
	{
	    $sql = "select t1.logistic_code,count(*) as number from {$this -> _table_transport} as t1
	            inner join {$this -> _table_batch} as t2 on t1.bill_no = t2.order_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            where t2.status = 0 and logistic_status in (1,2,3,4,5,6) and t1.logistic_code <> 'self' and t1.logistic_code is not null and t1.logistic_price > 0 {$whereSql}
	            group by t1.logistic_code";
	    return $this -> _db -> fetchAll($sql);
    }
    
    /**
	 * 按订单类型获得订单信息
	 */
	public function getOrderByType($whereSql)
	{
	    $sql = "select t1.type,count(*) as number,t1.status,t1.status_return,sum(t1.price_pay) as price_pay,sum(t1.price_payed) as price_payed,sum(t1.price_before_return) as price_before_return from {$this -> _table_batch} as t1
	            left join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            where 1 {$whereSql}
	            group by t1.type,t1.status,t1.status_return";
	    return $this -> _db -> fetchAll($sql);
	}
    
	/**
	 * 取表shop_order_batch_goods 
	 * 
	 */
	public function getOrderBatchGoodsInfo($where, $fields=null , $dist=null) {
		if($fields){
			$sql = "select $fields from {$this->_table_order_batch_goods} a left join {$this->_table_order_batch} b on a.order_batch_id=b.order_batch_id left join {$this->_table_order} c on b.order_id=c.order_id left join {$this->_table_user} d on c.user_id=d.user_id where b.status=0 $where";
			return $this -> _db -> fetchAll($sql);
		}else{
			if($dist){
				$sql = "select count(c.user_id) as pct from {$this->_table_order_batch_goods} a left join {$this->_table_order_batch} b on a.order_batch_id=b.order_batch_id left join {$this->_table_order} c on b.order_id=c.order_id where b.status=0 $where";
			}else{
				$sql = "select count(c.user_id) as pct from {$this->_table_order_batch_goods} a left join {$this->_table_order_batch} b on a.order_batch_id=b.order_batch_id left join {$this->_table_order} c on b.order_id=c.order_id where b.status=0 $where";
			}
			return $this -> _db -> fetchOne($sql);
		}
	}
	
	public function getOrderBatchGoodsInfo2($where) {
		$sql1 = "select count(c.user_id) as pct from {$this->_table_order_batch_goods} a left join {$this->_table_order_batch} b on a.order_batch_id=b.order_batch_id left join {$this->_table_order} c on b.order_id=c.order_id where b.status=0 $where group by c.user_id";
		return $this -> _db -> fetchAll($sql1);
	}
	
	/**
	 * 取表shop_out_tuan_order 
	 * 
	 */
	public function getOutTuanOrderInfo($where) {
		$sql = "select count(a.id) as pct from {$this->_table_out_tuan_order} a left join {$this->_table_out_tuan_goods} b on a.goods_id=b.goods_id where a.ischeat=0 and a.isback=0 $where";
		return $this -> _db -> fetchOne($sql);
	}
	
	public function getUserGoods($where) {
	    $sql = "select count(*) as order_count, sum(number) as goods_number, sum(amount) as amount,addr_consignee,addr_mobile,addr_tel,addr_province,addr_city,addr_area,addr_address,addr_province_id,addr_city_id,addr_area_id,user_name,password,min(add_time) as add_time,max(add_time) as last_time,shop_id,parent_id,GROUP_CONCAT(goods_name SEPARATOR '||') as goods_name from (
                select t1.goods_name,t2.add_time,sum(t1.sale_price * t1.number) as amount,sum(t1.number) as number,t1.order_id,t2.addr_consignee,t2.addr_tel,t2.addr_mobile,t2.addr_province,t2.addr_city,t2.addr_area,t2.addr_address,t2.addr_province_id,t2.addr_city_id,t2.addr_area_id,t3.shop_id,t3.parent_id,t4.user_name,t4.password from {$this->_table_order_batch_goods} as t1 
                left join {$this -> _table_batch} as t2 on t2.batch_sn = t1.batch_sn
                left join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
                left join {$this -> _table_user}  as t4 on t3.user_id  = t4.user_id
                where {$where}
                group by order_id ) as a
                group by addr_consignee,addr_mobile";
        
        return $this -> _db -> fetchAll($sql);
	}
	
	public function getFinanceSales($where, $group = null, $flag = 'amount') {
	    if ($group) {
	        if ($flag == 'amount') {
    	        $sql = "select t1.status,sum(t1.balance_amount-t1.balance_point_amount) as amount,count(*) as order_count,{$group} from {$this -> _table_batch} as t1 
        	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
        	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
        	            where {$where}
        	            group by t1.status,{$group}";
            }
            else {
                $tempArray = explode(',', $group);
                $topGroup = array();
                foreach ($tempArray as $temp) {
                    $pos = strpos($temp, '.');
                    if ($pos === false) {
                        $topGroup[] = $temp;
                    }
                    else {
                        $topGroup[] = substr($temp, $pos + 1, strlen($temp));
                    }
                }
                $topGroup = implode(',', $topGroup);
                $sql = "select sum(cost * number) as cost_amount,sum(round(cost / (1 + invoice_tax_rate / 100) * number, 3)) as no_tax_cost_amount,{$topGroup} from
                        (select distinct(t5.id),t5.cost,t5.number,t6.invoice_tax_rate,{$group} from {$this -> _table_batch} as t1 
        	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
        	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
        	            inner join {$this -> _table_transport_source} as t7 on t1.batch_sn = t7.bill_no
        	            inner join {$this -> _table_outstock} as t4 on t4.outstock_id = t7.outstock_id
        	            inner join {$this -> _table_outstock_detail} as t5 on t4.outstock_id = t5.outstock_id
        	            inner join {$this -> _table_product} as t6 on t5.product_id = t6.product_id
        	            where {$where}) as a
        	            group by {$topGroup}";
            }
	    }
	    else {
	        if ($flag == 'amount') {
    	        $sql = "select t1.status,sum(t1.balance_amount-t1.balance_point_amount) as amount,count(*) as order_count from {$this -> _table_batch} as t1 
        	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
        	            where {$where}
        	            group by t1.status";
        	}
            else {
                $sql = "select sum(cost * number) as cost_amount,sum(round(cost / (1 + invoice_tax_rate / 100) * number, 3)) as no_tax_cost_amount from
                        (select distinct(t5.id),t5.cost,t5.number,t6.invoice_tax_rate from {$this -> _table_batch} as t1 
        	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
        	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
        	            inner join {$this -> _table_transport_source} as t7 on t1.batch_sn = t7.bill_no
        	            inner join {$this -> _table_outstock} as t4 on t4.outstock_id = t7.outstock_id
        	            inner join {$this -> _table_outstock_detail} as t5 on t4.outstock_id = t5.outstock_id
        	            inner join {$this -> _table_product} as t6 on t5.product_id = t6.product_id
        	            where {$where}) as a";
            }
	    }
        
	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivable1($where) {
	    $sql = "select sum(amount) as amount,sum(logistic_price_cod) as commission,logistic_code,cod_status from 
	           (select distinct(t3.tid),(t3.amount+t3.change_amount) as amount,t3.logistic_price_cod,t3.logistic_code,t3.cod_status from {$this -> _table_batch} as t1 
	           inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	           inner join {$this -> _table_transport} as t3 on t3.bill_no like concat('%', t1.batch_sn, '%')
	           where {$where}) as a
	           group by logistic_code,cod_status";

        return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivable2($where) {
	    $sql = "select sum(t1.price_order) as amount,sum(t2.commission) as commission,t1.pay_type,t1.clear_pay,t3.name from {$this -> _table_batch} as t1 
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_payment} as t3 on t1.pay_type = t3.pay_type
	            where {$where}
	            group by t1.pay_type,t1.clear_pay";
        
        return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivable3($where) {
	    $sql = "select sum(t1.price_order) as amount,sum(t2.commission) as commission,t1.clear_pay,t2.shop_id from {$this -> _table_batch} as t1 
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            where {$where}
	            group by t2.shop_id,t1.clear_pay";
        
        return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivable4($where) {
	    $sql = "select sum(t1.price_order) as amount,t1.clear_pay,t2.shop_id from {$this -> _table_batch} as t1 
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            where {$where}
	            group by t2.shop_id,t1.clear_pay";
        
        return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivable5($where) {
	    $sql = "select sum(t1.price_order) as amount,sum(t1.price_payed) as payed,t2.shop_id from {$this -> _table_batch} as t1 
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            
	            where {$where}
	            group by t2.shop_id";
        
        return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivable6($where) {
	    $sql = "select sum(t1.price_order) as amount,sum(t1.price_payed) as payed,t2.user_name from {$this -> _table_batch} as t1 
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            where {$where}
	            group by t2.user_name";

        return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivable7($where) {
	    $sql = "select t1.addr_consignee,sum(t1.price_order) as amount,sum(t1.price_payed) as payed from {$this -> _table_batch} as t1 
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            where {$where}
	            group by t1.addr_consignee";

        return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivable($where) {
	    $where = str_replace('t1.logistic_time', 'if (t4.time_flag, t4.add_time, t1.logistic_time)', $where);
	    $sql = "select t4.pay_type,sum(t4.amount) as amount,sum(t4.settle_amount) as settle_amount,sum(t4.commission) as commission from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table_finance_receivable} as t4 on t1.batch_sn = t4.batch_sn
	            where {$where} and t4.pay_type <> 'point'
	            group by t4.pay_type";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivablePreGiftCard($where) {
	     $sql = "select sum(amount) from
	             (select distinct(t4.id),t4.amount from {$this -> _table_batch} as t1
	             inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	             left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	             inner join {$this -> _table_finance_receivable} as t4 on t1.batch_sn = t4.batch_sn
	             inner join {$this -> _table} as t5 on t1.order_batch_id = t5.order_batch_id
	             inner join {$this -> _table_product} as t6 on t5.product_id = t6.product_id
	             where {$where} and t4.pay_type = 'gift' and t1.status_logistic in (3,5) and t6.is_gift_card = 1 and t5.number > 0) a";
        
	    return $this -> _db -> fetchOne($sql);
	}
	
	public function getAccountReceivableReturn($where) {
	    $sql = "select t4.type,t4.way,t4.item,t4.bank_type,t4.pay,t4.point,t4.account,t4.gift,t4.delivery from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table_finance} as t4 on t1.batch_sn = t4.item_no
	            where {$where} and t4.status = 2 and t4.way <> 4";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivableLogisticChangeAmount($where) {
	    $where = str_replace('t4.check_time', 't4.add_time', $where);
	    $sql = "select t2.batch_sn,t2.pay_type,t2.amount,t2.add_time,t3.name from 
	            (select distinct(t4.id),count(*) as number,t4.batch_sn from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table_finance_receivable} as t4 on t1.batch_sn = t4.batch_sn
	            inner join {$this -> _table_finance_receivable} as t5 on t4.batch_sn = t5.batch_sn and t5.add_time <= t4.add_time
	            where {$where} and t4.type = 4
	            group by t4.batch_sn
                having number > 1) as t1
                inner join {$this -> _table_finance_receivable} as t2 on t1.batch_sn = t2.batch_sn
                inner join {$this -> _table_logistic} as t3 on t2.pay_type = t3.logistic_code
                order by t2.batch_sn,t2.add_time desc";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getAccountReceivableReject($where) {
	    $where = str_replace('t4.check_time', 't5.finish_time', $where);
	    $sql = "select logistic_code,pay_type,name,sum(amount) as amount from
	            (select distinct(t4.id),t1.logistic_code,t4.pay_type,t6.name,t4.amount from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table_finance_receivable} as t4 on t1.batch_sn = t4.batch_sn
	            inner join {$this -> _table_order_batch_return} as t5 on t1.batch_sn = t5.batch_sn
	            left join {$this -> _table_logistic} as t6 on t4.pay_type = t6.logistic_code
	            inner join {$this -> _table} as t7 on t1.order_batch_id = t7.order_batch_id
	            inner join {$this -> _table_product} as t8 on t7.product_id = t8.product_id
	            where {$where} and t1.status_logistic = 5 and t1.pay_type in ('cod', 'externalself') and (t4.pay_type <> 'gift' or t8.is_gift_card = 1)
	            group by t1.batch_sn) a
	            group by pay_type";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getFinanceReturn1($where, $group = null) {
	    if ($group) {
	        $groupBy = "group by {$group}";
	        $field = ",{$group}";
	    }
	    
	    $sql = "select count(*) as count {$field} from {$this -> _table_instock} as t1 
	            inner join {$this -> _table_batch} as t2 on t1.item_no = t2.batch_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            left join {$this -> _table_shop} as t4 on t3.shop_id = t4.shop_id
	            where {$where} and t1.bill_status = 7 and t1.bill_type = 1
	            {$groupBy}";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getFinanceReturn2($where, $group = null) {
	    if ($group) {
	        $groupBy = "group by {$group}";
	        $field = ",{$group}";
	    }
	    $sql = "select sum(round(t5.shop_price * t5.number,2)) as cost_amount,sum(round(t5.shop_price / (1 + t6.invoice_tax_rate / 100) * t5.number, 2)) as no_tax_cost_amount {$field} from {$this -> _table_instock} as t1
	            inner join {$this -> _table_batch} as t2 on t1.item_no = t2.batch_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            left join {$this -> _table_shop} as t4 on t3.shop_id = t4.shop_id
	            inner join {$this -> _table_instock_detail} as t5 on t1.instock_id = t5.instock_id
	            inner join {$this -> _table_product} as t6 on t5.product_id = t6.product_id
	            where {$where} and t1.bill_status = 7 and t1.bill_type in (1,13)
	            {$groupBy}";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getFinanceReturn3($where, $group = null) {
	    if ($group) {
	        $groupBy = "group by {$group}";
	        $field = ",{$group}";
	    }
	    $where = str_replace('t1.finish_time', 't1.check_time', $where);
	    $sql = "select  {$field} ,sum(t1.pay+t1.account+t1.gift) as amount from {$this -> _table_finance} as t1
	            inner join {$this -> _table_batch} as t2 on t1.item_no = t2.batch_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            where {$where} and t1.status  = 2 and t1.way <> 6 and t1.check_time > 0 and t1.delivery = 1 and t2.logistic_time > 0 
	            {$groupBy}";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getFinanceReturn4($where, $group = null) {
	    if ($group) {
	        $groupBy = "group by {$group}";
	        $field = ",{$group}";
	    }
	    $group && $groupBy = "group by {$group}";
	    $sql = "select sum(t1.amount) as amount {$field} from {$this -> _table_order_batch_return} as t1
	            inner join {$this -> _table_batch} as t2 on t1.batch_sn = t2.batch_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            left join {$this -> _table_shop} as t4 on t3.shop_id = t4.shop_id
	            where {$where} and finish_time > 0
	            {$groupBy}";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getOrderReturnAmount($where) {
	    $sql = "select t1.add_time,t2.shop_id,t2.user_name,t4.pay as return_amount from {$this -> _table_batch} as t1 
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table_finance} as t4 on t1.batch_sn = t4.item_no
	            where 1 {$where}";
        
	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getUnionInfo() {
	    return $this -> _db -> fetchAll("select user_id,cname from shop_union_normal");
	}
	
	public function getShopData() {
	    return $this -> _db -> fetchAll("select shop_type,shop_id from {$this -> _table_shop}");
	}
	
	public function getProductInOutStock($sql, $stock_type) {
	    
	    $sql = "select t1.bill_type,t2.product_id,sum(t2.number) as number,t3.product_name,t3.product_sn,t3.goods_style from shop_{$stock_type} as t1
	            left join shop_{$stock_type}_detail as t2 on t1.{$stock_type}_id = t2.{$stock_type}_id
	            left join {$this -> _table_product} as t3 on t2.product_id = t3.product_id
	            where {$sql}
	            group by t2.product_id,t1.bill_type
	            order by t2.product_id,t1.bill_type";
	    //die($sql);
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得产品出库
	 */
	public function getGoodsSalable($whereSql) 
	{
	    $sql = "select * from (select t1.product_id,t2.add_time from {$this -> _table_outstock_detail} as t1
	                           inner join {$this -> _table_outstock} as t2 on t1.outstock_id = t2.outstock_id
	                           inner join {$this -> _table_product} as t3 on t1.product_id = t3.product_id
	                           where {$whereSql}
	                           order by t2.add_time desc) as a
	                           group by product_id";

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得所有产品
	 */
	public function getAllProducts($whereSql) 
	{
	    $sql = "select product_id,product_name,product_sn,goods_style from {$this -> _table_product} where {$whereSql} order by product_sn";

	    return $this -> _db -> fetchAll($sql);
	    
	}
	
	/**
	 * 获得当前库存
	 */
	public function getProductStock($whereSql) 
	{
	    $sql = "select t1.product_id,t2.product_name,t2.product_sn,t2.goods_style,(real_in_number - real_out_number) as number from shop_stock_status as t1
	            inner join {$this -> _table_product} as t2 on t1.product_id = t2.product_id
	            where {$whereSql}"; 

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得产品出库
	 */
	public function getStockTurnover($whereSql) 
	{
	    $sql = "select t1.product_id,sum(t1.number) as number from {$this -> _table_outstock_detail} as t1
	            inner join {$this -> _table_outstock} as t2 on t1.outstock_id = t2.outstock_id
	            inner join {$this -> _table_product} as t3 on t1.product_id = t3.product_id
	            where {$whereSql}
	            group by t1.product_id
	            order by t3.product_sn";

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得产品购买统计
	 */
	public function getProductSales1($whereSql) 
	{
	    $sql = "select t1.goods_name as product_name,t1.product_sn,sum(price_order) as Amount,sum(t1.eq_price * (t1.number - t1.return_number)) as ProductAmount,count(*) as OrderCount1 from {$this -> _table_order_batch_goods} as t1
	            inner join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id
	            inner join {$this -> _table_order} as t3 on t1.order_id = t3.order_id
	            inner join {$this -> _table_goods_cat} as t4 on t1.cat_id = t4.cat_id
	            where {$whereSql}
	            group by t1.product_id
	            order by t1.product_sn";

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得产品购买统计
	 */
	public function getProductSales2($whereSql) 
	{
	    $sql = "select a.*, count(*) as number from 
                (SELECT t1.order_id, t1.product_sn,t1.batch_sn
                FROM {$this -> _table_order_batch_goods} AS t1
                INNER JOIN {$this -> _table_order_batch} AS t2 ON t1.order_id = t2.order_id
                INNER JOIN {$this -> _table_order} AS t3 ON t1.order_id = t3.order_id
                INNER JOIN {$this -> _table_goods_cat} AS t4 ON t1.cat_id = t4.cat_id
                WHERE {$whereSql}) as a
                inner join {$this -> _table_order_batch_goods} as b on a.batch_sn = b.batch_sn
                where b.product_id > 0
                group by a.order_id,a.product_sn";

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得产品购买统计
	 */
	public function getProductSales3($whereSql) 
	{
	    $sql = "select product_sn,count(*) as OrderCount4 from
                (select order_id,product_sn from
                (select d.*,e.product_sn as same_product_sn from
                (select a.order_id,a.same_order_id,c.product_sn from 
                (select a.order_id,b.order_id as same_order_id from
                (select t2.order_id,t2.addr_mobile,t2.add_time from {$this -> _table_order_batch_goods} as t1
	            inner join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id
	            inner join {$this -> _table_order} as t3 on t1.order_id = t3.order_id
	            inner join {$this -> _table_goods_cat} as t4 on t1.cat_id = t4.cat_id
	            where {$whereSql}
	            group by t1.order_id) a
                inner join {$this -> _table_order_batch} as b on a.addr_mobile = b.addr_mobile
                where a.order_id <> b.order_id and a.add_time > b.add_time) as a
                inner join shop_order_batch_goods as c on a.order_id = c.order_id
                where c.product_id > 0) as d
                inner join {$this -> _table_order_batch_goods} as e on d.same_order_id = e.order_id
                where e.product_id > 0 and d.product_sn = e.product_sn) as f
                group by order_id,product_sn) as g
                group by product_sn";

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得产品购买统计
	 */
	public function getProductSales4($whereSql, $startTime, $endTime) 
	{
	    $sql = "select a.product_sn,a.product_name,count(*) as UserCount from 
	            (select t1.product_sn,t1.goods_name as product_name,t2.addr_mobile from {$this -> _table_order_batch_goods} as t1
	            inner join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id
	            inner join {$this -> _table_order} as t3 on t1.order_id = t3.order_id
	            inner join {$this -> _table_goods_cat} as t4 on t1.cat_id = t4.cat_id
	            where {$whereSql} and t2.add_time < {$startTime}
	            group by t2.addr_mobile,t1.product_sn) as a
	            left join 
	            (select t1.product_sn,t2.addr_mobile from {$this -> _table_order_batch_goods} as t1
	            inner join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id
	            inner join {$this -> _table_order} as t3 on t1.order_id = t3.order_id
	            inner join {$this -> _table_goods_cat} as t4 on t1.cat_id = t4.cat_id
	            where {$whereSql} and t2.add_time >= {$startTime} and t2.add_time <= {$endTime}
	            group by t2.addr_mobile,t1.product_sn) as b
	            on a.addr_mobile = b.addr_mobile and a.product_sn = b.product_sn
	            where b.product_sn is NULL
	            group by a.product_sn";

	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 产品发货区域
	 */
	public function getProductLogisticArea($whereSql)
	{
	    $sql = "select * from 
	            (select t2.product_sn,t2.goods_name,t1.addr_province_id,t4.area_name,count(*) as count from {$this -> _table_order_batch} as t1
	            inner join {$this -> _table_order_batch_goods} as t2 on t1.order_batch_id = t2.order_batch_id
	            inner join {$this -> _table_order} as t3 on t1.order_id = t3.order_id
	            inner join {$this -> _table_area} as t4 on t1.addr_province_id = t4.area_id
	            where {$whereSql}
	            group by t2.product_sn,t1.addr_province_id) a
	            order by count desc";
        
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 按产品获得采购付款记录
	 */
	public function getPurchasePaymentByProduct($whereSql)
	{
	    $sql = "select t1.id from {$this -> _table_purchase_payment} as t1
	            inner join {$this -> _table_instock} as t2 on t1.bill_no = t2.bill_no
	            inner join {$this -> _table_instock_detail} as t3 on t2.instock_id = t3.instock_id
	            inner join {$this -> _table_product} as t4 on t3.product_id = t4.product_id
	            where {$whereSql}
	            group by t1.id";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        foreach ($datas as $data) {
	            $result[] = $data['id'];
	        }
	        return $result;
	    }
	}
	
	/**
	 * 获得采购付款汇总记录
	 */
	public function getSumSupplierPayment($whereSql, $flag = 1)
	{
	    if ($flag == 1) {
    	    $sql = "select t1.supplier_id,t4.supplier_name,sum(t1.amount) as amount1 from {$this -> _table_purchase_payment} as t1
    	            inner join {$this -> _table_instock} as t2 on t1.bill_no = t2.bill_no
    	            inner join {$this -> _table_supplier} as t4 on t1.supplier_id = t4.supplier_id
    	            where {$whereSql}
    	            group by t1.supplier_id
    	            order by CONVERT(t4.supplier_name USING gbk)";
    	}
    	else {
    	    $sql = "select t1.supplier_id,sum(t3.shop_price / (1 + t5.invoice_tax_rate / 100) * t3.number) as amount2 from {$this -> _table_purchase_payment} as t1
    	            inner join {$this -> _table_instock} as t2 on t1.bill_no = t2.bill_no
    	            inner join {$this -> _table_supplier} as t4 on t1.supplier_id = t4.supplier_id
    	            inner join {$this -> _table_instock_detail} as t3 on t2.instock_id = t3.instock_id
	                inner join {$this -> _table_product} as t5 on t3.product_id = t5.product_id
    	            where {$whereSql}
    	            group by t1.supplier_id";
    	}
    	
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 获得采购付款记录
	 */
	public function getSupplierPayment($whereSql, $flag = 1)
	{
	    if ($flag == 1) {
    	    $sql = "select t1.*,t2.purchase_type,t2.finish_time,t4.supplier_name,t1.amount as amount1 from {$this -> _table_purchase_payment} as t1
    	            inner join {$this -> _table_instock} as t2 on t1.bill_no = t2.bill_no
    	            inner join {$this -> _table_supplier} as t4 on t1.supplier_id = t4.supplier_id
    	            where {$whereSql}";
    	}
    	else {
    	    $sql = "select t1.id,sum(t3.shop_price / (1 + t5.invoice_tax_rate / 100) * t3.number) as amount2 from {$this -> _table_purchase_payment} as t1
    	            inner join {$this -> _table_instock} as t2 on t1.bill_no = t2.bill_no
    	            inner join {$this -> _table_supplier} as t4 on t1.supplier_id = t4.supplier_id
    	            inner join {$this -> _table_instock_detail} as t3 on t2.instock_id = t3.instock_id
	                inner join {$this -> _table_product} as t5 on t3.product_id = t5.product_id
    	            where {$whereSql}
    	            group by t1.id";
    	}
    	
	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getSiteAccountReceivableProductCost($whereSql)
    {
        $sql = "select t2.shop_id,sum(t6.cost * t5.number) as cost_amount from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table_outstock} as t4 on t1.batch_sn = t4.bill_no
	            inner join {$this -> _table_outstock_detail} as t5 on t4.outstock_id = t5.outstock_id
	            inner join {$this -> _table_product} as t6 on t5.product_id = t6.product_id
	            where {$whereSql}
	            group by shop_id";
        
	    return $this -> _db -> fetchAll($sql);
    }
    
	public function getOrderMargin($whereSQL, $benefitRate = null)
	{
	    $sql = "select t1.batch_sn,t1.price_order,t1.add_time,t1.pay_type,t1.logistic_time,t1.shop_name,t2.cost,(t1.price_order - t2.cost) / if(t1.price_order = 0.00,1,t1.price_order) * 100 as benefit_rate from 
	            (select distinct(t1.batch_sn),t1.price_order,t1.add_time,t1.pay_type,t1.logistic_time,t3.shop_name from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table} as t4 on t1.batch_sn = t4.batch_sn
	            where {$whereSQL}) as t1
	            left join 
	            (select t1.batch_sn,sum(cost) as cost from
	            (select t1.batch_sn,sum(t1.cost * t1.number) - sum(t1.cost * if(t2.return_number is null,0,t2.return_number)) as cost from
	            (select t1.batch_sn,t3.product_id,t3.cost,t3.number from 
	            (select distinct(t1.batch_sn),t1.price_order,t1.add_time,t1.pay_type,t1.logistic_time,t3.shop_name from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table} as t4 on t1.batch_sn = t4.batch_sn
	            where {$whereSQL}) as t1
	            inner join {$this -> _table_outstock} as t2 on t1.batch_sn = t2.bill_no
	            inner join {$this -> _table_outstock_detail} as t3 on t2.outstock_id = t3.outstock_id) as t1
	            left join 
	            (select batch_sn,product_id,sum(number) as return_number from
	            (select t1.batch_sn,t3.product_id,t3.plan_number as number from 
	            (select distinct(t1.batch_sn) from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table} as t4 on t1.batch_sn = t4.batch_sn
	            where {$whereSQL}) as t1
	            inner join {$this -> _table_instock} as t2 on t1.batch_sn = t2.item_no
	            inner join {$this -> _table_instock_plan} as t3 on t2.instock_id = t3.instock_id
	            where t2.bill_status in (0,3,6)
	            union
	            select t1.batch_sn,t3.product_id,t3.real_number as number from 
	            (select distinct(t1.batch_sn) from {$this -> _table_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            inner join {$this -> _table} as t4 on t1.batch_sn = t4.batch_sn
	            where {$whereSQL}) as t1
	            inner join {$this -> _table_instock} as t2 on t1.batch_sn = t2.item_no
	            inner join {$this -> _table_instock_plan} as t3 on t2.instock_id = t3.instock_id
	            where t2.bill_status = 7) as t1
	            group by batch_sn,product_id) as t2
	            on t1.batch_sn = t2.batch_sn and t1.product_id = t2.product_id
	            group by t1.batch_sn,t1.product_id) as t1
	            group by t1.batch_sn) as t2
	            on t1.batch_sn = t2.batch_sn";
        if (isset($benefitRate)) {
            $sql .= " where ((t1.price_order - t2.cost) / if(t1.price_order = 0.00,1,t1.price_order) * 100) <= {$benefitRate}";
        }

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getGiftCardUseLog($whereSQL = 1)
	{
	    $sql = "select t1.card_sn,t1.card_price,t1.card_real_price,t1.add_time,t2.log_id,t2.price,t2.add_time as use_time,t3.logistic_time from shop_gift_card as t1
	            left join shop_gift_card_use_log as t2 on t1.card_sn = t2.card_sn
	            left join shop_order_batch as t3 on t2.batch_sn = t3.batch_sn
	            left join shop_order_batch_goods as t4 on t1.order_batch_goods_id = t4.order_batch_goods_id
	            left join shop_order_batch as t5 on t4.order_batch_id = t5.order_batch_id
	            where {$whereSQL} and (t1.log_id <> 8 or t1.user_id > 0) and (t1.log_id <= 8 or t5.status is null or (t5.status in (0,5) and t5.status_logistic in (3,4) and t4.number > 0)) and (t2.use_type is null or t2.use_type = 2)
	            order by use_time desc";
	            
	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getGiftCardSale($fromDate, $toDate, $where = 1)
	{
	    $sql = "select t1.card_price,t2.sale_price,t2.number,t3.status_logistic from shop_gift_card as t1
	            inner join shop_order_batch_goods as t2 on t1.order_batch_goods_id = t2.order_batch_goods_id
	            inner join shop_order_batch as t3 on t2.order_batch_id = t3.order_batch_id
	            inner join shop_product as t4 on t2.product_id = t4.product_id
	            where t3.status in (0,5) and t3.status_logistic in (3,4) and t4.is_gift_card = 1 and t2.number > 0 and t3.logistic_time >= {$fromDate} and t3.logistic_time <= {$toDate} and {$where}";

	    return $this -> _db -> fetchAll($sql);
	}
	
	function getFinanceInOutStock($whereSQL = 1, $stockType)
	{
	    if ($stockType == 'instock') {
	        $sql = "select t4.supplier_name,t1.supplier_id,sum(t2.number) as count,sum(t2.shop_price * t2.number) as cost,sum(round(t2.shop_price / (1 + t3.invoice_tax_rate / 100) * t2.number, 3)) as no_tax_cost from shop_instock as t1
	                inner join shop_instock_detail as t2 on t1.instock_id = t2.instock_id
	                inner join shop_product as t3 on t2.product_id = t3.product_id
	                left join shop_supplier as t4 on t1.supplier_id = t4.supplier_id
	                where {$whereSQL}
	                group by t1.supplier_id";
	    }
	    else {
	        $sql = "select t4.supplier_name,t1.supplier_id,sum(t2.number) as count,sum(t2.cost * t2.number) as cost,sum(round(t2.cost / (1 + t3.invoice_tax_rate / 100) * t2.number, 3)) as no_tax_cost from shop_outstock as t1
	                inner join shop_outstock_detail as t2 on t1.outstock_id = t2.outstock_id
	                inner join shop_product as t3 on t2.product_id = t3.product_id
	                left join shop_supplier as t4 on t1.supplier_id = t4.supplier_id
	                where {$whereSQL}
	                group by t1.supplier_id";
	    }
	    
	    return $this -> _db -> fetchAll($sql);
    }
    
    function getFinanceInOutStockBill($whereSQL = 1, $stockType)
	{
	    if ($stockType == 'instock') {
	        $sql = "select t1.bill_no,t1.supplier_id from shop_instock as t1 where {$whereSQL}";
	    }
	    else {
	        $sql = "select t1.bill_no,t1.supplier_id from shop_outstock as t1 where {$whereSQL}";
	    }

	    $datas = $this -> _db -> fetchAll($sql);
	    if (!$datas)    return false;
	    
	    foreach ($datas as $data) {
	        if (!$data['supplier_id']) {
	            $data['supplier_id'] = 0;
	        }
	        $result[$data['supplier_id']][] = $data['bill_no'];
	    }
	    
	    return $result; 
    }
    
    public function getFinanceProductTax($where, $group = null) {
        $group && $group = ",{$group}";
        
	    $sql = "select t5.invoice_tax_rate,sum(t4.number) as count,sum(t4.finance_price * t4.number) as amount {$group} from {$this -> _table_batch} as t1 
        	    inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
        	    left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
        	    inner join {$this -> _table} as t4 on t1.order_batch_id = t4.order_batch_id
        	    inner join {$this -> _table_product} as t5 on t4.product_id = t5.product_id
        	    where t4.product_id > 0 and t5.is_gift_card = 0 and {$where}
        	    group by t5.invoice_tax_rate {$group}";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getFinanceFreight($where, $group = null) {
	    if ($group) {
	        $group1 = ",{$group}";
	        $group2 = " group by {$group}";
	    }
	    $sql = "select sum(t1.price_logistic) as price_logistic,sum(t1.point_payed * -1) as point_payed {$group1} from {$this -> _table_batch} as t1 
        	    inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
        	    left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
        	    where {$where}
        	    {$group2}";

	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getFinanceReturnTax1($where, $group = null) {
	    if ($group) {
	        $groupBy = "group by {$group}";
	        $field = ",{$group}";
	    }
	    $where = str_replace('t1.finish_time', 't1.check_time', $where);
	    $sql = "select t4.shop_name {$field} ,sum(t5.amount) as amount from {$this -> _table_finance} as t1
	            inner join {$this -> _table_batch} as t2 on t1.item_no = t2.batch_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            left join {$this -> _table_shop} as t4 on t3.shop_id = t4.shop_id
	            inner join {$this -> _table_finance_product} as t5 on t1.finance_id = t5.finance_id
	            inner join {$this -> _table_product} as t6 on t5.product_id = t6.product_id
	            where {$where} and t1.status  = 2 and t1.way <> 6 and t1.check_time > 0 and t1.delivery = 1 and t2.logistic_time > 0 and t5.type = 1
	            {$groupBy}";
        
	    return $this -> _db -> fetchAll($sql);
	}
	
	public function getFinanceReturnTax2($where, $group = null) {
	    if ($group) {
	        $groupBy = "group by {$group}";
	        $field = ",{$group}";
	    }
	    $sql = "select t4.shop_name {$field} ,sum(t5.amount) as amount from {$this -> _table_order_batch_return} as t1
	            inner join {$this -> _table_batch} as t2 on t1.batch_sn = t2.batch_sn
	            inner join {$this -> _table_order} as t3 on t2.order_id = t3.order_id
	            left join {$this -> _table_shop} as t4 on t3.shop_id = t4.shop_id
	            inner join {$this -> _table_finance_product} as t5 on t1.id = t5.finance_id
	            inner join {$this -> _table_product} as t6 on t5.product_id = t6.product_id
	            where {$where} and t1.finish_time > 0 and t2.logistic_time > 0 and t5.type = 2
	            {$groupBy}";

	    return $this -> _db -> fetchAll($sql);
	}
	
}