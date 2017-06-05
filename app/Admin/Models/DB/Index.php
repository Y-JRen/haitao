<?php

class Admin_Models_DB_Index
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
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
     * 取得Mysql版本
     *
     * @param  void
     * @return void
     */
	public function getVersion()
	{
		return $this -> _db -> fetchOne('select version()');
	}
}