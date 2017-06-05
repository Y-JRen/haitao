<?php

class Admin_Models_DB_StockOp
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * table name
     * @var    string
     */
	private $_table_stock_op = 'shop_stock_op';
	
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
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
		if ($where != null) $where = "WHERE $where";
		$sql = "SELECT * FROM `$this->_table_stock_op` $where ORDER BY op_id DESC";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加操作日志记录
     *
     * @return   int      lastInsertId
     */
	public function insertOp($item, $item_id, $admin_name, $op_type, $remark = null, $item_value = null)
	{
		$op_time = time ();
		$row = array (
                      'item' => $item,
                      'item_id' => $item_id,
                      'item_value' => $item_value,
                      'admin_name' => $admin_name,
                      'op_type' => $op_type,
                      'remark' => $remark,
                      'op_time' => $op_time,
                      );
        $this -> _db -> insert($this -> _table_stock_op, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
}