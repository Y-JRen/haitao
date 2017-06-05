<?php

class Admin_Models_DB_Status
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
	private $_table_status = 'shop_status';
	private $_table_status_detail = 'shop_status_detail';
	private $_table_stock_status = 'shop_stock_status';
	private $_table_logic_status = 'shop_logic_status';
	private $_table_product = 'shop_product';
	private $_table_product_batch = 'shop_product_batch';
	private $_table_goods = 'shop_goods';
	private $_table_goods_cat = 'shop_goods_cat';
	
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
	public function get($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
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
			$orderBy = "ORDER BY a.sid DESC";
		}
		
		$table = "`$this->_table_status_detail` a 
		          INNER JOIN `$this->_table_status` b ON a.sid=b.sid 
		          INNER JOIN `$this->_table_product` p ON p.product_id=a.product_id 
			      INNER JOIN `$this->_table_goods_cat` gc ON gc.cat_id=p.cat_id ";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count from (SELECT a.sid FROM $table $whereSql GROUP BY a.sid) a");
		
		return $this -> _db -> fetchAll("SELECT $fields,product_name as goods_name FROM $table $whereSql GROUP BY a.sid $orderBy $limit");
	}
	
	/**
     * 获取详细数据集
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
			$orderBy = "ORDER BY a.sid DESC";
		}
		
		
		$table = "`$this->_table_status_detail` a 
		          INNER JOIN `$this->_table_status` b ON a.sid=b.sid 
		          INNER JOIN `$this->_table_product` p ON p.product_id=a.product_id
		          LEFT JOIN `$this->_table_product_batch` pb ON a.batch_id = pb.batch_id";
		
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
        $this -> _db -> insert($this -> _table_status, $data);
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
        $this -> _db -> insert($this -> _table_status_detail, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    string   $where
     * @return   void
     */
	public function update($data, $where, $type = null)
	{
	    if ($type == null) {
	        $this -> _db -> update($this -> _table_status, $data, $where);
	    }else{
	    	$this -> _db -> update($this -> _table_status_detail, $data, $where);
	    }
	    return true;
	}
	
}