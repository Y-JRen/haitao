<?php

class Admin_Models_DB_Supplier
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
	private $_pageSize = 50;
	
	/**
     * table name
     * @var    string
     */
	private $_table = 'shop_supplier';


	/**
     * table name
     * @var    string
     */
	private $_goods_table = 'shop_goods';
	
	/**
     * table name
     * @var    string
     */
	private $_product_table = 'shop_product';


	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * 根据供应商Id取产品
     *
     * @param    string    $product_ids
     * @return   array
     */
	public function getProductSupplierData($product_ids)
	{
        if($product_ids){
          return  $this -> _db -> fetchAll("SELECT * FROM $this->_product_table where  product_id in ({$product_ids})");
        }
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
	public function fetch($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
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
			$orderBy = "ORDER BY supplier_id";
		}
        return array('list'=>$this -> _db -> fetchAll("SELECT $fields FROM `$this->_table` $whereSql $orderBy $limit"),'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM $this->_table $whereSql"));
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
	public function getSupplier($where = null, $fields = '*')
	{
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
        return $this -> _db -> fetchAll("SELECT $fields FROM `$this->_table` $whereSql ORDER BY CONVERT(supplier_name USING gbk)");
	}

	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		$row = array (
						'supplier_name' => $data['supplier_name'],
						'company' => $data['company'],
						'addr' => $data['addr'],
						'tel' => $data['tel'],
						'contact' => $data['contact'],
						'supplier_desc' => $data['supplier_desc'],
						'status' => $data['status'],
						'corporate' => $data['corporate'],
						'registration_num' => $data['registration_num'],
						'business_type' => $data['business_type'],
						'start_time' => $data['start_time'],
						'end_time' => $data['end_time'],
						'bank_name' => $data['bank_name'],
						'start_time' => strtotime($data['start_time']),
						'end_time' => strtotime($data['end_time']),
						'bank_sn' => $data['bank_sn'],
						'bank_account' => $data['bank_account'],
						'email' => $data['email'],
						'mobile' => $data['mobile'],
						'add_time' => $data['add_time']
                      );
        
        $this -> _db -> insert($this -> _table, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function update($data, $id)
	{
		$set = array (
						'company' => $data['company'],
						'addr' => $data['addr'],
						'tel' => $data['tel'],
						'contact' => $data['contact'],
						'corporate' => $data['corporate'],
						'registration_num' => $data['registration_num'],
						'business_type' => $data['business_type'],
						'start_time' => $data['start_time'],
						'end_time' => $data['end_time'],
						'bank_name' => $data['bank_name'],
						'bank_sn' => $data['bank_sn'],
						'bank_account' => $data['bank_account'],
						'email' => $data['email'],
						'mobile' => $data['mobile'],
						'start_time' => strtotime($data['start_time']),
						'end_time' => strtotime($data['end_time']),
						'supplier_desc' => $data['supplier_desc'],
						'status' => $data['status']
                      );
        $where = $this -> _db -> quoteInto('supplier_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function updateField($set, $where)
	{
	    $this -> _db -> update($this -> _table, $set, $where);
	}
	
	/**
     * 删除数据
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('supplier_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table, $where);
		}
	}
	/**
     * 更新状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function updateStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('supplier_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('supplier_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
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
	public function getProduct($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = " WHERE $where";
		}
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY product_id DESC";
		}
        return array('list'=>$this -> _db -> fetchAll("SELECT $fields FROM $this->_product_table $whereSql $orderBy $limit"),'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM $this->_product_table $whereSql"));
	}


	/**
     * 更新供应商商品数据
     *
     * @param    int      $id
     * @return   void
     */
	public function editSupplierProduct($supplier_id, $product_ids)
	{
		return $this -> _db -> update($this -> _table, array('product_ids'=> $product_ids), "supplier_id = {$supplier_id}" );
	}
	
	/**
     * 根据供应商ID删除产品
     *
     * @param    int
	 *
     * @return   boolean
     */
	public function deleteSupplierProductBySupplierId($supplier_id)
	{
		$supplier_id = intval($supplier_id);
		if ($supplier_id < 1) {
			$this->_error = '供应商ID不正确';
			return false;
		}

		return (bool) $this->_db->update('shop_supplier_product', array('is_deleted' => 1), "supplier_id = '{$supplier_id}'");
	}

	/**
     * 根据供应商ID真正删除产品
     *
     * @param    int
	 *
     * @return   boolean
     */
	public function reallyDeleteSupplierProductBySupplierId($supplier_id)
	{
		$supplier_id = intval($supplier_id);
		if ($supplier_id < 1) {
			$this->_error = '供应商ID不正确';
			return false;
		}

		$param_str = "supplier_id = '{$supplier_id}' AND is_deleted = 1";

		return (bool) $this->_db->delete('shop_supplier_product', $param_str);
	}

	/**
     * 插入供应商产品
     *
     * @param    array
	 *
     * @return   boolean
     */
	public function insertSupplierProductInfo($param)
	{
		$sql = "INSERT INTO `shop_supplier_product` (`supplier_id`, `product_id`) VALUES('{$param['supplier_id']}', '{$param['product_id']}') 
				ON DUPLICATE KEY UPDATE is_deleted = 0";

		return (bool) $this->_db->execute($sql);
	}

}