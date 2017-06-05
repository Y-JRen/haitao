<?php

class Admin_Models_DB_Menu
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
	private $_table = 'shop_menu';
	private $_table_privilege = 'privilege';
	
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
     * ��ȡ��ݼ�
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @return   array
     */
	public function fetch($where = null, $fields = '*', $orderBy = null)
	{
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY parent_id, menu_sort, menu_id";
		}
		
		$sql = "SELECT $fields FROM `$this->_table` $whereSql $orderBy";
		
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * ȡ����ЧȨ��.
     *
     * @param    string    $where
     * @return   array
     */
	public function getPrivilege($where = null)
	{
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		return $this -> _db -> fetchAll('SELECT * FROM `' . $this -> _table_privilege . '`' .$whereSql);
	}
	
	/**
     * ������
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		$row = array (
                      'menu_title' => $data['menu_title'],
                      'menu_path' => $data['menu_path'],
                      'url' => $data['url'],
                      'parent_id' => $data['parent_id'],
                      'privilege' => $data['privilege'],
			          'menu_status' => $data['menu_status'],
			          'is_open' => $data['is_open'],
                      );
        
        $this -> _db -> insert($this -> _table, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		$where = $this -> _db -> quoteInto('menu_id = ?', $lastInsertId);
        $set = array ('menu_path' => $data['menu_path'].$lastInsertId.',');
        $this -> _db -> update($this -> _table, $set, $where);
		return $lastInsertId;
	}
	
	/**
     * �������
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function update($data, $id)
	{
		$set = array (
                      'menu_title' => $data['menu_title'],
                      'menu_path' => $data['menu_path'],
                      'url' => $data['url'],
                      'parent_id' => $data['parent_id'],
                      'privilege' => $data['privilege'],
			          'menu_status' => $data['menu_status'],
			          'is_open' => $data['is_open'],
                      );
                      
        $where = $this -> _db -> quoteInto('menu_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	/**
     * ɾ�����
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('menu_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table, $where);
		}
	}
	
	/**
     * ����״̬
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function updateStatus($id, $status)
	{
		$set = array ('menu_status' => $status);
		$where = $this -> _db -> quoteInto('menu_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	/**
     * ajax�������
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('menu_id = ?', $id);
		if ($id > 0) {
			$fields = array('menu_title', 'menu_sort');
			if(in_array($field, $fields)){
		        $this -> _db -> update($this -> _table, $set, $where);
		    }
		    return true;
		}
	}
	
	/**
     * ������Ĳ˵�·��
     *
     * @param    int      $id
	 * @param    string   $old_menu_path
	 * @param    string   $menu_path
     * @return   void
     */
	public function changeMenu($id, $old_menu_path, $menu_path)
	{
		if ((int)$id > 0) {
		    $datas = $this -> fetch("menu_path like '%,$id,%'");
		    if($datas){
				foreach ($datas as $data){
			        $val = str_replace($old_menu_path, $menu_path, $data['menu_path']);
			        $set = array ('menu_path' => $val);
					$where = $this -> _db -> quoteInto('menu_id = ?', $data['menu_id']);
					$this -> _db -> update($this -> _table, $set, $where);
				}
				return true;
		    }
		}
	}

}