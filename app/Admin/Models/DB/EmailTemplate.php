<?php

class Admin_Models_DB_EmailTemplate
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 邮件模板表名
     * 
     * @var    string
     */
	private $_table = 'shop_email_template';
	
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
     * 取得邮件模板列表数据
     *
     * @param    string  $where
     * @param    int     $page
     * @param    int     $pageSize
     * @return   array
     */
	public function getTemplate($where = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 取得邮件模板数目
     *
     * @param    string    $where
     * @return   int
     */
	public function getTemplateCount($where = null)
	{
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		
		$count = $this -> _db -> fetchOne('SELECT count(*) as count FROM `' . $this -> _table . '` ' . $whereSql);
		return $count;
	}
	
	/**
     * 取得邮件模板
     *
     * @param    string  $where
     * @return   array
     */
	public function getTemplateInfo($where = null)
	{
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql . ' LIMIT 1';
		return $this -> _db -> fetchRow($sql);
	}

	/**
     * 添加模板数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addTemplate(array $data)
	{
		$row = array (
                      'name' => $data['name'],
                      'type' => $data['type'],
                      'title' => $data['title'],
                      'value' => $data['value'],
                      'update_time' => $data['update_time']
                      );
        
        $this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新模板数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateTemplate(array $data ,$id)
	{
		$set = array (
                      'name' => $data['name'],
                      'type' => $data['type'],
                      'title' => $data['title'],
                      'value' => $data['value'],
                      'update_time' => $data['update_time']
                      );
        
        $where = $this -> _db -> quoteInto('template_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
	
	/**
     * 删除邮件模板
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteTemplate($id)
	{
		$where = $this -> _db -> quoteInto('template_id = ?', $id);
		return $this -> _db -> delete($this -> _table, $where);
	}
}