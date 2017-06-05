<?php

class Admin_Models_DB_Attribute
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
	private $_pageSize = 100;
	
	/**
     * table name
     * @var    string
     */
	private $_table = 'shop_attr';
	
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
			$orderBy = "ORDER BY parent_id, attr_sort, attr_id";
		}
		
		$sql = "SELECT $fields FROM `$this->_table` $whereSql $orderBy $limit";
		
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
		$row = array ('attr_title' => $data['attr_title'],
                      'parent_id' => $data['parent_id'],
			          'attr_status' => $data['attr_status'],
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
		$set = array ('attr_title' => $data['attr_title'],
                      'attr_status' => $data['attr_status'],
                      );
                      
        $where = $this -> _db -> quoteInto('attr_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	/**
     * 更改属性名称
     *
     * @param    int    $id
     * @return   void
     */
	public function setAttrTitle($attr_id, $attr_title)
	{
	    $where = $this -> _db -> quoteInto('attr_id = ?', $attr_id);
	    $this -> _db -> update($this -> _table, array('attr_title' => $attr_title), $where);
	}
	
	/**
     * 删除数据
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('attr_id = ? or parent_id = ?', $id);
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
		$set = array ('attr_status' => $status);
		$where = $this -> _db -> quoteInto('attr_id = ?', $id);
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
		$where = $this -> _db -> quoteInto('attr_id = ?', $id);
		if ($id > 0) {
			$fields = array('attr_title', 'attr_sort');
			if(in_array($field, $fields)){
		        $this -> _db -> update($this -> _table, $set, $where);
		    }
		    return true;
		}
	}


}