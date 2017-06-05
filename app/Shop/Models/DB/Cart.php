<?php
class Shop_Models_DB_Cart
{

	private $_db = null;
	private $_pageSize = null;

	private $_table_finance = 'shop_finance';
	private $_table_area = 'shop_area';
    private $_table_logistic = 'shop_logistic';
    private $_table_logistic_area = 'shop_logistic_area';
    private $_table_logistic_area_price = 'shop_logistic_area_price';
    private $_table_stock_status = 'shop_stock_status';
    private $_table_order_pay_log = 'shop_order_pay_log';
	private $_table_storage = 'shop_storage';
	private $_table_goods = 'shop_goods';
	private $_table_goods_img = 'shop_goods_img';
	private $_table_member_address = 'shop_member_address';
    private $_table_shipping = 'shop_shipping';
    private $_table_shipping_area = 'shop_shipping_area';
    private $_table_payment = 'shop_payment';
    private $_table_order = 'shop_order';
    private $_table_order_batch = 'shop_order_batch';
    private $_table_order_batch_goods = 'shop_order_batch_goods';

	public function __construct()
    {
		$this -> _db = Zend_Registry :: get('db');
		$this -> _pageSize = Zend_Registry :: get('config') -> view -> page_size;

	}
	/**
     * 新增地址
     *
     * @param   array     $data
     * @return  int
     */
    public function addAddr($data)
    {
        $this -> _db -> insert($this -> _table_member_address, $data);
		$insertID = $this -> _db -> lastInsertId();
        return $insertID;
    }
	/**
     * 更新指定条件的地址
     *
     * @param   array     $where
     * @return  bool
     */
    public function editAddr($where, $data)
    {
        if ($where['member_id']) {
            $condition[] = "member_id = '{$where['member_id']}'";
        }
        if ($where['address_id']) {
            $condition[] = "address_id = '{$where['address_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
		if ($where['member_id'] && $where['address_id']) {
            return $this -> _db -> update($this->_table_member_address, $data, $condition);
        } else {
            return false;
        }
    }
	/**
     * 删除指定条件的地址
     *
     * @param   array     $where
     * @return  bool
     */
    public function delAddr($where){
        if ($where['member_id']) {
            $condition[] = "member_id = '{$where['member_id']}'";
        }
        if ($where['address_id']) {
            $condition[] = "address_id = '{$where['address_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
		if ($where['member_id'] && $where['address_id']) {
            return $this -> _db -> delete($this->_table_member_address, $condition);
        } else {
            return false;
        }
    }
	/**
     * 取得指定条件的地址列表
     *
     * @param   array     $where
     * @return  array
     */
    public function getAddress($where = null){
        if ($where['member_id']) {
            $condition[] = "member_id = '{$where['member_id']}'";
        }
        if ($where['address_id']) {
            $condition[] = "address_id = '{$where['address_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        } else {
            $condition = '';
        }

        if ($condition) {
            $sql = "select a.*,b.area_name as province_name,c.area_name as city_name,d.area_name as area_name from ";
            $sql.= "`{$this->_table_member_address}` a left join `{$this->_table_area}` b on a.province_id=b.area_id ";
            $sql.= "left join `{$this->_table_area}` c on a.city_id=c.area_id left join `{$this->_table_area}` d ";
            $sql.= "on a.area_id=d.area_id  where 1=1 {$condition} order by use_time desc";
            return $this->_db->fetchAll($sql);
        }
        return array();
    }
	/**
     * 统计某个用户的 地址数量
     *
     * @param   int     $userID
     * @return  int
     */
    public function countAddr($userID)
    {
        return $this -> _db -> fetchOne("select count(*) from `{$this->_table_member_address}` where member_id={$userID}");
    }
	/**
     * 取配送地区列表
     *
     * @param   int     $province
     * @param   int     $city
     * @param   int     $area
     * @return  void
     */
    public function getLogistic($where = null)
    {
        if ($where['area_id']) {
            $condition[] = "area_id = '{$where['area_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        $sql = "select logistic_code,cod from {$this->_table_logistic_area} where 1=1 {$condition}";
        return $this->_db->fetchAll($sql);
    }
	/**
     * 取得指定条件的支付方式列表
     *
     * @param   array     $where
     * @return  array
     */
    public function getPayment($where = null)
    {
        if (!is_null($where['status'])) {
            $condition[] = "status='{$where['status']}'";  
        }
        if ($where['pay_type']) {
            $condition[] = "pay_type='{$where['pay_type']}'";  
        }
        if ($where['pay_type!=']) {
            $condition[] = "pay_type!='{$where['pay_type!=']}'";  
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' AND ', $condition);
        }
        return $this -> _db -> fetchAll("select * from `{$this->_table_payment}` where pay_type!='external'  {$condition} order by sort desc");
    }

	/**
     * 取得专享支付方式
     *
     * @param   array     $where
     * @return  array
     */
    public function getOnlyPayment($pay_type)
    {
        $where =" and status=0 and (pay_type='".$pay_type."' or pay_type='cod' )";
        return $this -> _db -> fetchAll("select * from `{$this->_table_payment}` where 1 {$where} order by sort desc");
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
     * 新增订单批次
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderBatch($data)
    {
        $this -> _db -> insert($this->_table_order_batch, $data);
		return $this -> _db -> lastInsertId();
    }
	/**
     * 新增订单商品
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderBatchGoods($data)
    {
        $this -> _db -> insert($this -> _table_order_batch_goods, $data);
		return $this -> _db -> lastInsertId();
    }
	/**
     * 取得指定条件的订单信息
     *
     * @param   array     $where
     * @return  array
     */
    public function getOrder($where = null)
    {
        if ($where['user_id']) {
            $condition[] = "user_id='{$where['user_id']}'";  
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' AND ', $condition);
        }
        return $this -> _db -> fetchAll("select * from `{$this->_table_order}` where 1=1 {$condition} order by order_id desc");
    }
	/**
     * 更新指定条件的订单信息
     *
      * @param   string     $userID
    * @param   string     $orderSN
     * @param   array     $data
     * @return  array
     */
    public function updateOrder($userID, $orderSN, $data)
    {
        if ($data && $orderSN) {
            return $this -> _db -> update($this->_table_order, $data, "user_id='{$userID}' && order_sn='{$orderSN}'");
        }
    }
	/**
     * 取得指定条件的子地区列表
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getArea($where = null)
    {
        if ($where['parent_id']) {
            $condition[] = "parent_id='{$where['parent_id']}'";  
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' AND ', $condition);
        }
        //香港除外 3880
        return $this -> _db -> fetchAll("select * from `{$this->_table_area}` where 1=1 and area_id <> 3880 and area_id <> 3984 and area_id <> 3983 and area_id <> 3982 {$condition}");
    }
	/**
     * 取地区名
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaName($areaID)
    {
        if ($areaID == -1)  return '其它区';
        return $this -> _db -> fetchOne("select area_name from `{$this->_table_area}` where area_id='{$areaID}'");
    }


	/**
     * 取地区ID
     *
     * @param   int     $areaName
     * @return  array
     */
    public function getAreaId($areaName, $parentID = null)
    {
        if ($parentID !== null) {
            $where = " and parent_id = {$parentID}";
        }

        return $this -> _db -> fetchOne("select area_id from `{$this->_table_area}` where area_name='{$areaName}'{$where}");
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
        return $this -> _db -> fetchOne("select zip from `{$this->_table_area}` where area_id='{$areaID}'");
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
     * 取得指定条件的订单批次
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatch($where=null)
    {
        $condition = null;
        
        if ($where['order_id']) {
            $condition[] = "a.order_id = '{$where['order_id']}'";
        }
        if ($where['user_id']) {
            $condition[] = "a.user_id = '{$where['user_id']}'";
        }
        if ($where['batch_sn']) {
            $condition[] = "b.batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['order_sn']) {
            $condition[] = "b.order_sn = '{$where['order_sn']}'";
        }
        if(!is_null($where['status_logistic>'])) {
            $condition[] = "status_logistic>{$where['status_logistic>']}";  
        }
        if (!is_null($where['is_send'])) {
            $condition[] = "is_send = '{$where['is_send']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        if ($condition) {
            $sql = "SELECT a.order_id,a.user_id,a.user_name,a.invoice,a.invoice_content,a.parent_id,a.parent_param,a.proportion,";
            $sql .= "b.* FROM {$this->_table_order} a left join {$this->_table_order_batch} b on a.order_sn = b.order_sn ";
            $sql .= "where 1=1 {$condition} order by order_batch_id desc";
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
	/**
     * 添加一条支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderPayLog()
    {
        $this->_db->insert($this->_table_order_pay_log, $data);
		return $this->_db->lastInsertId();
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
            return $this -> _db -> update($this->_table_order, array('order_sn' => $orderSN, 'batch_sn' => $orderSN), "order_id='{$orderID}'");
        } else {
            return false;
        }
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
       
        if ($where['order_sn']) {
            $condition[] = "order_sn = '{$where['order_sn']}'";
        }
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
       
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        if ($condition) {
            $sql = "SELECT obg.*,b.as_name FROM {$this -> _table_order_batch_goods} as obg
                LEFT JOIN shop_product p on p.product_id=obg.product_id
                LEFT JOIN shop_brand b on b.brand_id=p.brand_id
                where 1=1 {$condition} {$orderBy}";
            return $this -> _db -> fetchAll($sql);
        } else {
            return false;
        }
    }
    
}