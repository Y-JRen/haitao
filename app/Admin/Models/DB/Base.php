<?php
class Admin_Models_DB_Base
{
	/**
     * Zend_Db
     *
     * @var    Zend_Db
     */
	protected $_db = null;

	/**
     * 表名
     *
     * @var    string
     */
	protected $_table = '';

	/*
	 * 异步更新
	 */
	 public function ajaxupdate($id,$field,$val,$pkid){
	 	$set = array ($field => $val);
	 	$where = $this->_db->quoteInto("$pkid = ?", $id);
	 	if ($id > 0) {
		    return $this->_db->update($this->_table, $set, $where);
		}
	 }

	/**
     * 根据id获取对象
     *
     * @param    int $ID
     * @return   array
     */
    public function getByID($id,$pkid){
        return $this->_db->fetchRow('select * from ' .$this->_table." where $pkid=".$id);
    }

	/**
     * 删除专题
     *
     * @param    int $articleID
     * @return   int
     */

    public function del($id ,$pkid){
		$where = $this->_db->quoteInto("$pkid = ?", $id);
		if ($id > 0) {
		    return $this->_db->delete($this->_table, $where);
		}
    }
}