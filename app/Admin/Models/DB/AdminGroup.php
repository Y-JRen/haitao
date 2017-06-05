<?php

class Admin_Models_DB_AdminGroup
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 管理组表名
     * 
     * @var    string
     */
	private $_table = 'shop_admin_group';
	
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
     * 取得管理组信息
     *
     * @param    string $where
     * @return   array
     */
	public function getGroup($where = null,$fields = '*')
	{
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		$sql = "SELECT $fields FROM `" . $this -> _table . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加管理组
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addGroup(array $data)
	{
		$row = array ('group_name' => $data['group_name'],
		              'type' => $data['type'],
                      'remark' => $data['remark'],
                      'group_menu' => $data['group_menu'],
                      'group_privilege' => $data['group_privilege']
                      );
        
        $this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新指定管理组
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateGroup(array $data ,$id)
	{
		$data['group_name'] && $set['group_name'] = $data['group_name'];
		isset($data['type']) && $set['type'] = $data['type'];
		isset($data['remark']) && $set['remark'] = $data['remark'];
		$data['group_menu'] && $set['group_menu'] = $data['group_menu'];
		$data['group_privilege'] && $set['group_privilege'] = $data['group_privilege'];

        $where = $this -> _db -> quoteInto('group_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
	
	/**
     * 删除管理组
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteGroup($id)
	{
		$where = $this -> _db -> quoteInto('group_id = ?', $id);
		return $this -> _db -> delete($this -> _table, $where);
	}
}