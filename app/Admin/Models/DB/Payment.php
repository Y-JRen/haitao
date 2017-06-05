<?php
class Admin_Models_DB_Payment
{
	private $_db = null;
	
	private $_table = 'shop_payment';
	
	public function __construct()
	{
		$this->_db = Zend_Registry::get('db');
        $this->_pageSize = Zend_Registry::get('config')->view->page_size;
    }
	
	/**
     * 添加
     *
     * @param    array $data
     * @return   int
     */
     public function add($data){
        $this->_db->insert($this->_table, $data);
		return $this->_db->lastInsertId();
    }

	/**
     * 取支付方式数量
     *
     * @return   int
     */
    public function getCount($where=null){
		if ($where != null) {
			$whereSql = " WHERE 1=1  ".$where ;
		}	
		return $this->_db->fetchOne('SELECT count(*) as count FROM `'.$this->_table.'` '.$whereSql);
    }
	/**
     * 取支付方式列表
     *
     * @param    string $fields
     * @param    string $orderBy
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function get($where=null,$fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = ' LIMIT '.$pageSize.' OFFSET '.$offset;
		}
		if ($where != null) {
			$whereSql  = " WHERE 1=1  ".$where;
		}		
		
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY id ,sort DESC  ";
		}
		$sql = 'SELECT '.$fields.' FROM `'.$this->_table.'` '.$whereSql.' '.$orderBy.' '.$limit;
		return $this->_db->fetchAll($sql);
	}
	/**
     * 编辑列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxupdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table, $set, $where);
		}
	}
	/**
     * 删除
     *
     * @param    int $id
     * @return   int
     */

    public function del($id){
		$where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this->_db->delete($this->_table, $where);
		}
    }
	/**
     * 取某ID的支付方式
     *
     * @param    int $id
     * @return   array
     */
    public function getPaymentByID($id){
        return $this->_db->fetchRow('select * from ' .$this->_table.' where id='.$id);
    }
	/**
     * 编辑文章
     *
     * @param    array $data
     * @return   int
     */
    public function edit($data){
        $id = $data['id'];
        unset($data['id']);
        $where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table, $data, $where);
		}
    }

}