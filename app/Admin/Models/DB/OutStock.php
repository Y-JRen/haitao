<?php

class Admin_Models_DB_OutStock
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * page size
     * @var    int
     */
	private $_pageSize = null;
	
	/**
     * table name
     * @var    string
     */
	private $_table_outstock = 'shop_outstock';
	private $_table_outstock_detail = 'shop_outstock_detail';
	private $_table_stock_status = 'shop_stock_status';
	private $_table_supplier = 'shop_supplier';
	private $_table_product = 'shop_product';
	private $_table_product_batch = 'shop_product_batch';
	private $_table_goods = 'shop_goods';
	private $_table_goods_cat = 'shop_goods_cat';
	private $_table_stock_op = 'shop_stock_op';
	private $_table_purchase_payment = 'shop_purchase_payment';
	private $_table_transport = 'shop_transport';

	/**
     * Creates a db instance.
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
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = 'a.*,b.*,p.*,s.supplier_name', $page = null, $pageSize = null, $orderBy = null)
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
		          LEFT JOIN `$this->_table_goods_cat` gc ON gc.cat_id=p.cat_id 
	              LEFT JOIN `$this->_table_supplier` s ON s.supplier_id=b.supplier_id 
		         ";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count from (SELECT a.outstock_id FROM $table $whereSql GROUP BY a.outstock_id) a");
		return $this -> _db -> fetchAll("SELECT $fields,product_name as goods_name FROM $table $whereSql GROUP BY a.outstock_id $orderBy $limit");
	}
	
	/**
     * 获取成本合计
     *
     * @param    string    $where
     * @return   array
     */
	public function getSumCost($where = null)
	{
	    if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
	    $table = "`$this->_table_outstock_detail` a 
		          INNER JOIN `$this->_table_outstock` b ON a.outstock_id=b.outstock_id 
		          INNER JOIN `$this->_table_product` p ON p.product_id=a.product_id 
		          LEFT JOIN `$this->_table_goods_cat` gc ON gc.cat_id=p.cat_id 
	              LEFT JOIN `$this->_table_supplier` s ON s.supplier_id=b.supplier_id";
		return $this -> _db -> fetchRow("SELECT sum(a.cost * a.number) as cost,sum(round(a.cost / (1 + p.invoice_tax_rate / 100) * a.number, 2)) as no_tax_cost FROM $table $whereSql");
		
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
	public function getDetail($where = null, $fields = 'p.*,a.*,b.*,pb.batch_no', $page = null, $pageSize = null, $orderBy = null)
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
			      INNER JOIN `$this->_table_goods_cat` gc ON gc.cat_id=p.cat_id 
			      LEFT JOIN `$this->_table_product_batch` pb ON pb.batch_id=a.batch_id";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields,product_name as goods_name FROM $table $whereSql $orderBy $limit");
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
	public function getOutDetail($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
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
			      INNER JOIN `$this->_table_goods_cat` gc ON gc.cat_id=p.cat_id 
			      LEFT JOIN  `$this->_table_stock_op` op ON a.outstock_id=op.item_id AND item='outstock' AND op_type='send'
		         ";
		
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		
		return $this -> _db -> fetchAll("SELECT $fields,product_name as goods_name FROM $table $whereSql $orderBy $limit");
	}
	
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert($data)
	{
        $this -> _db -> insert($this -> _table_outstock, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
     * 添加运输单数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertTransport($data)
	{
        $this -> _db -> insert($this -> _table_transport, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

	/**
     * 添加详细数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertDetail($data)
	{
        $this -> _db -> insert($this -> _table_outstock_detail, $data);
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
	    $this -> _db -> update($this -> _table_outstock, $data, $where);
	    return true;
	}
	
	/**
     * 更新明细数据
     *
     * @param    array    $data
     * @param    string   $where
     * @return   void
     */
	public function updateDetail($data, $where)
	{
	    $this -> _db -> update($this -> _table_outstock_detail, $data, $where);
	    return true;
	}
	
	/**
     * 获取供货商列表
     *
     * @return   array
     */
	public function getSupplier($where = null)
	{
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		$sql = "SELECT supplier_id,supplier_name,status FROM `$this->_table_supplier` $whereSql  ORDER BY CONVERT(supplier_name USING gbk)";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加采购退款数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertPayment($data)
	{
        $this -> _db -> insert($this -> _table_purchase_payment, $data);
	}
	
	/**
     * 删除出库单
     *
     * @return   void
     */
	public function delete($bill_no, $outstock_id = null)
	{
	    if (!$outstock_id) {
	        $outstock_id = $this -> _db -> fetchOne("select outstock_id from {$this -> _table_outstock} where bill_no = '{$bill_no}'");
	        if (!$outstock_id)  return false;
	    }
	    
	    $this -> _db -> delete($this -> _table_outstock, "outstock_id = {$outstock_id}");
	    $this -> _db -> delete($this -> _table_outstock_detail, "outstock_id = {$outstock_id}");
	}
	/*根据产品id获取商品出库价*/
	public function getCostByPid($prodid){ 
	    $sql = "SELECT cost FROM shop_product WHERE product_id= ".$prodid;
	    $row = $this -> _db ->fetchRow($sql);
	    return $row['cost'];
	}


	/**
	 * 根据条件获取明细数据
	 *
	 * @param    array
	 *
	 * @return   array
	 */
	public function getSaleReportDetailInfosByCondition($params)
	{
		$_condition = array();
		!empty($params['start_ts'])     && $_condition[] = "finish_time >= '{$params['start_ts']}'";
		!empty($params['end_ts'])       && $_condition[] = "finish_time <= '{$params['end_ts']}'";
		!empty($params['bill_types'])   && $_condition[] = "bill_type  IN('". implode("','", $params['bill_types']) ."')";
		!empty($params['bill_status'])  && $_condition[] = "bill_status = '{$params['bill_status']}'";
		isset($params['is_cancel'])     && $_condition[] = "is_cancel = '{$params['is_cancel']}'";
		!empty($params['product_sn'])   && $_condition[] = "product_sn = '{$params['product_sn']}'";
		!empty($params['product_name']) && $_condition[] = "product_name like '%{$params['product_name']}%'";
		!empty($params['supplier_id'])  && $_condition[] = "s.supplier_id = '{$params['supplier_id']}'";
		if (count($_condition) < 1) {
			$this->_error = '没有相关条件';
			return false;
		}

		$_condition[] = "d.product_id > 0";
		//$_condition[] = "sp.is_deleted = 0";

		$sql = "SELECT `finish_time`, GROUP_CONCAT(d.id) AS detail_id, d.`product_id`, GROUP_CONCAT(number) AS numbers, `product_sn`, `product_name`, GROUP_CONCAT(distinct(s.`supplier_id`)) as supplier_id, GROUP_CONCAT(bill_type) as bill_types FROM `shop_outstock` o 
				LEFT JOIN `shop_outstock_detail` d ON o.outstock_id = d.outstock_id
				LEFT JOIN `shop_product` p ON d.product_id = p.product_id
				LEFT JOIN `shop_supplier_product` sp ON d.product_id = sp.product_id
				LEFT JOIN `shop_supplier` s ON sp.supplier_id = s.supplier_id
				WHERE ".implode(' AND ', $_condition). " GROUP BY d.product_id";

		$infos = $this->_db->fetchAll($sql);

		if (empty($infos)) {
			return array();
		}

		$ids = array();
		foreach ($infos as $key => $info) {
			$detail_ids  = explode(',', $info['detail_id']);
			$numbers     = explode(',', $info['numbers']);
            $bill_types  = explode(',', $info['bill_types']);
            $sale_out    = 0;
            $fenxiao_out = 0;
			$number      = 0;
			foreach ($detail_ids as $ke => $detail) {
				if (!isset($ids[$detail])) {
					$ids[$detail] = $detail;
					$number += $numbers[$ke];
                    if ($bill_types[$ke] == '1') {
                        $sale_out += $numbers[$ke];
                    } else if ($bill_types[$ke] == '15') {
                        $fenxiao_out += $numbers[$ke];
                    }
				}
			}

			$infos[$key]['number'] = $number;
            $infos[$key]['sale_number'] = $sale_out;
            $infos[$key]['fenxiao_number'] = $fenxiao_out;
		}

		return $infos;
	}

    /**
	 * 根据订单编号区出库单数据
	 *
	 * @param    string
	 *
	 * @return   array
	 */
    public function getOutStockInfoByBillNo($bill_no)
    {
        $sql = "SELECT `outstock_id`, `bill_no`, `item_no`, `lid` FROM `shop_outstock` WHERE bill_no = '{$bill_no}' LIMIT 1";

        return $this->_db->fetchRow($sql);
    }

	/**
	 * 返回错误信息
	 *
	 * @return   string
	 */
	public function getError()
	{
		return $this->_error;
	}
}