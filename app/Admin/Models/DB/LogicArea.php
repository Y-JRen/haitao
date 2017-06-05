<?php

class Admin_Models_DB_LogicArea
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
	private $_table = 'shop_logic_area';
	private $_table_product = 'shop_product';
	private $_table_stock_status = 'shop_stock_status';
	private $_table_logic_status = 'shop_logic_status';
	
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
     * 获取数据集
     *
     * @return   array
     */
	public function fetch($where = null)
	{
		$where && $where = " where $where";
		$sql = "SELECT * FROM `$this->_table` $where";
		
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		if ($this -> fetch("name='{$data['name']}'")) {
			return false;
		}
		
		$row = array (
                      'name' => $data['name'],
                      'remark' => $data['remark'],
			          'disabled' => $data['disabled'],
                      'add_time' => $data['add_time'],
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
                      'name' => $data['name'],
                      'remark' => $data['remark'],
			          'disabled' => $data['disabled'],
                      );
                      
        $where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	/**
     * 删除数据
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table, $where);
		}
	}
	
	/**
     * 更新状态
     *
     * @param    int    $id
     * @param    int    $disabled
     * @return   void
     */
	public function updateStatus($id, $disabled)
	{
		$set = array ('disabled' => $disabled);
		$where = $this -> _db -> quoteInto('id = ?', $id);
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
		$where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}

}