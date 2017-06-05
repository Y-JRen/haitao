<?php

class Admin_Models_DB_Data
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
     * 取得数据表
     *
     * @return   array
     */
	public function listTables()
	{
		return $this -> _db -> listTables();
	}
	
	/**
     * 取得数据表状态
     *
     * @return   array
     */
	public function showTableStatus()
	{
		$tableStatus = $this -> _db -> fetchAll("SHOW TABLE STATUS");
		
		foreach ($tableStatus as $key => $table)
		{
			$status = $this -> _db -> fetchRow("CHECK TABLE `" . $table['Name'] . "`");
			$tableStatus[$key]['Status'] = $status['Msg_text'];
			unset($status);
		}
		
		return $tableStatus;
	}
	
	/**
     * 修复表
     * 
     * @param    string    $table
     * @return   void
     */
	public function repairTable($table)
	{
		$this -> _db -> execute("REPAIR TABLE `" . $table . "`");
	}
	
	/**
     * 优化表
     * 
     * @param    string    $table
     * @return   void
     */
	public function optimizeTable($table)
	{
		$this -> _db -> execute("OPTIMIZE TABLE `" . $table . "`");
	}
}