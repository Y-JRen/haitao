<?php

class Admin_Models_DB_Config
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 系统配置表名
     * 
     * @var    string
     */
	private $_table = 'shop_config';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * 取得系统配置信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getConfig($where = null)
	{
		if (is_array($where)) {
			$whereSql = 'WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		} else {
			$whereSql = $where;
		}
		
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加系统配置信息
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addConfig(array $data)
	{
		$row = array (
                      'parent_id' => $data['parent_id'],
                      'name' => $data['name'],
                      'title' => $data['title'],
                      'type' => $data['type'],
                      'type_options' => $data['type_options'],
                      'notice' => $data['notice']
                      );
        
        $this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新指定系统配置格式
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function editConfig(array $data ,$id)
	{
		$set = array (
		              'parent_id' => $data['parent_id'],
                      'title' => $data['title'],
                      'type' => $data['type'],
                      'type_options' => $data['type_options'],
                      'notice' => $data['notice']
                      );
        
        $where = $this -> _db -> quoteInto('config_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> update($this -> _table, $set, $where);
		}
	}
	
	/**
     * 更新系统配置值
     *
     * @param    array    $data
     * @return   void
     */
	public function updateConfig(array $data)
	{

		foreach ($data as $key => $value)
		{
			$set = array ('value' => $value);
            $where = $this -> _db -> quoteInto('name = ?', $key);

		    $this -> _db -> update($this -> _table, $set, $where);
		}
	}
	
	/**
     * 删除系统配置
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteConfig($id)
	{
		$where = $this -> _db -> quoteInto('config_id = ?', $id);
		return $this -> _db -> delete($this -> _table, $where);
	}
}