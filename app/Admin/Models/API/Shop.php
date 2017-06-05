<?php
class Admin_Models_API_Shop {
	private $_db;
	private $_auth;
	private $_pageSize = 20;
	
	private $_table = 'shop_shop';
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * 获取店铺数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['shop_id'] && $wheresql .= "shop_id = '{$where['shop_id']}' and ";
		    $where['shop_name'] && $wheresql .= "shop_name like '%{$where['shop_name']}%' and ";
		    $where['shop_type'] && $wheresql .= "shop_type = '{$where['shop_type']}' and ";
		    $where['commission_type'] && $wheresql .= "commission_type = '{$where['commission_type']}' and ";
		    $where['status'] !== null && $wheresql .= "status = '{$where['status']}' and ";
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
		
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($wheresql != null) {
			$whereSql = "WHERE $wheresql";
		}
		
		if ($orderBy != null) {
			$orderBy = "ORDER BY $orderBy";
		}
		else {
			$orderBy = "ORDER BY shop_id";
		}
		
		return array('list' => $this -> _db -> fetchAll("SELECT $fields FROM {$this->_table} $whereSql $orderBy $limit"),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table} $whereSql"));
	}
}
	
?>