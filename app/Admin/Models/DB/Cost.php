<?php
class Admin_Models_DB_Cost 
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;
	
	private $_table = 'shop_cost';
	
	private $_table_detail = 'shop_cost_detail';
	
	private $_table_prod = 'shop_product';
	
	private $totle = 0;
	
	/**
     * page size
     * @var    int
     */
	private $_pageSize = null;
	
	/**
     * Creates a db instance.
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
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $page = null, $pageSize = 10, $orderBy = null)
	{
	    $res_arr = array();
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = " WHERE $where ";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY stauts ASC ";
		}
		
		$res_arr['totle'] = $this -> _db -> fetchOne("SELECT count(*) as count from ".$this->_table." $whereSql ");
		$res_arr['datas'] =  $this -> _db -> fetchAll("SELECT $fields  FROM ".$this->_table." $whereSql  $orderBy $limit");
		return $res_arr;
	}
	
	
	
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert($data)
	{
        $this -> _db -> insert($this->_table, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
     * 添加详细数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertDetail($data)
	{
        $this -> _db -> insert($this ->_table_detail, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
	 * 更新数据
	 *
	 * @param    array    $data
	 * @param    string   $where
	 * @return   void
	 */
	public function update($data, $where)
	{
	    $this -> _db -> update($this -> _table, $data, $where); 
	}

    /*根据单据id查询信息*/
	public function getInfoById($id){
	    $cost_arr = array();
	    $cost_arr['cost_obj']  = $this -> _db -> fetchRow("SELECT *  from ".$this->_table." where bill_id = ".$id);
	    $cost_arr['details']  = $this -> _db -> fetchAll("SELECT pd.product_sn,pd.product_name as goods_name,pd.goods_style,pd.p_status,pd.product_id,ct.cost,ct.precost from shop_cost_detail as ct , shop_product as pd WHERE ct.product_id = pd.product_id AND ct.bill_id =  ".$id);
	    return $cost_arr;
	}
	
}