<?php

class Shop_Models_DB_EmailTemplate
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
	}
	
	/**
     * 取得邮件模板
     *
     * @param    string  $where
     * @return   array
     */
	public function getTemplate($where = null)
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
}