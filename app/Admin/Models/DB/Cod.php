<?php

class Admin_Models_DB_Cod
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
	private $_table_transport = 'shop_transport';
	private $_table_cod_change = 'shop_cod_change';
	private $_table_cod_clear = 'shop_cod_clear';
	
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
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		$fields = "b.bill_no,b.bill_type,b.amount,b.change_amount,b.logistic_name,b.logistic_status,b.logistic_no,b.logistic_code,a.*";
		
		$table = "`$this->_table_cod_change` a LEFT JOIN `$this->_table_transport` b ON a.tid=b.tid";
		
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql order by cid desc $limit");
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
		
		$table = "`$this->_table_cod_clear` a";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		
		return $this -> _db -> fetchAll("SELECT * FROM $table $whereSql order by id desc $limit");
	}
	
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert($data)
	{
        $this -> _db -> insert($this -> _table_cod_change, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
     * 添加结算数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertClear($data)
	{
        $this -> _db -> insert($this -> _table_cod_clear, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
     * 更新数据 (针对 shop_cod_change)
     *
     * @param    array    $data
     * @param    string   $where
     * @return   void
     */
	public function update($data, $where)
	{
        $this -> _db -> update($this -> _table_cod_change, $data, $where);
	    return true;
	}
	
	/**
     * 更新数据 (针对 shop_transport)
     *
     * @param    array    $data
     * @param    string   $where
     * @return   void
     */
	public function updateTransport($data, $where)
	{
        $this -> _db -> update($this -> _table_transport, $data, $where);
	    return true;
	}
	
	/**
     * 获得_transport
     *
     * @param    string $logistic_no
     * @return   array
     */
	public function getTransport($logistic_no)
	{
	    return $this -> _db -> fetchAll("select * from {$this -> _table_transport} where logistic_no = '{$logistic_no}'");
	}
	
}