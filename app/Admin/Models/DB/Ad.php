<?php
class Admin_Models_DB_Ad
{
	private $_db = null;	
	private $_table = array('ad'=>'shop_ad','board'=>'shop_adboard');
	public function __construct()
	{
		$this->_db = Zend_Registry::get('db');
		$this->_pageSize = Zend_Registry::get('config')->view->page_size;
	}
	
	public function addAd($data)
	{	
		$this->_db->insert($this->_table['ad'], $data);
		return $this->_db->lastInsertId();
	}
	
	public function editAd($data)
	{
		$aid = $data['id'];
		unset($data['id']);
		$where = $this->_db->quoteInto('id = ?', $aid);
		if ($aid > 0) {
			return $this->_db->update($this->_table['ad'], $data, $where);
		}
	}
	
	public  function getAdById($id)
	{
		return $this->_db->fetchRow('select * from ' .$this->_table['ad'].' where id='.$id);
	}

	public  function getAdboardById($id)
	{
		return $this->_db->fetchRow('select * from ' .$this->_table['board'].' where id='.$id);
	}
	
	public function  updateField($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
			return $this->_db->update($this->_table['ad'], $set, $where);
		}
	}
	public function get($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null,$table='ad')
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;	
		if ($page != null) {
			$offset = ($page-1)*$pageSize;
			$limit = "LIMIT $offset,$pageSize" ;
		}
	
		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} 
		}
		
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}
				
		$sql = 'SELECT '.$fields.' FROM '.$this->_table[$table].' '.$whereSql.' '.$orderBy.' '.$limit;		
		//die( $sql);
		return $this->_db->fetchAll($sql);
	}
	
	public function getCount($where,$type='ad')
	{
		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			}
		}
	
		$sql = 'SELECT count(*) as count FROM  '.$this->_table[$type].' '.$whereSql;
		
		return $this->_db->fetchOne($sql);
	}
	
	
	public function addAdboard($data)
	{
		$this->_db->insert($this->_table['board'], $data);
		return $this->_db->lastInsertId();
	}
	
	public function delAdboard($id){
		$where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
			return $this->_db->delete($this->_table['board'], $where);
		}
	}
	
	public function delAd($id){
		$where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
			return $this->_db->delete($this->_table['ad'], $where);
		}
	}
	
	public function editAdboard($data)
	{
		$aid = $data['id'];
		unset($data['id']);
		$where = $this->_db->quoteInto('id = ?', $aid);
		if ($aid > 0) {
			return $this->_db->update($this->_table['board'], $data, $where);
		}
	}
	
}