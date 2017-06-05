<?php

class Admin_Models_DB_Auth
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 管理员表名
     * 
     * @var    string
     */
	private $_table = 'shop_admin';
	
	/**
     * 管理员登录记录表
     * 
     * @var    string
     */
	private $_tableLog = 'shop_admin_login_log';
	
	/**
     * 管理组表名
     * 
     * @var    string
     */
	private $_groupTable = 'shop_admin_group';
	
	/**
     * 权限表名
     * 
     * @var    string
     */
	private $_privilegeTable = 'privilege';
	
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
     * 认证登录用户并返回用户信息
     *
     * @param    string    $username
     * @param    string    $password
     * @return   array
     */
	public function certification($username, $password)
	{
		
		if ($username != null && $password != null) {
			$username = $this -> _db -> quote($username);
			$password = $this -> _db -> quote($password);
			$where = 'A.admin_name = ' . $username . ' and A.password = ' . $password . ' and A.status=1';
			$stmt = $this -> _db -> fetchRow('SELECT A.*,B.group_id AS gid,B.group_name,B.group_menu,B.group_privilege,B.type as group_type FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _groupTable . '` AS B ON A.group_id=B.group_id  WHERE ' . $where);
			
			if ($stmt != null) {
				$stmt['privilege'] = ($stmt['privilege']) ? $stmt['privilege'] : $stmt['group_privilege'];
				$stmt['last_login'] = time();
				$stmt['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
				$where = 'admin_name = ' . $username . ' and password = ' . $password . ' and status=1';
				$set = array (
                              'last_login' => $stmt['last_login'],
                              'last_login_ip' => $stmt['last_login_ip']
                              );
		        $this -> _db -> update($this -> _table, $set, $where);
			}
		}
		return $stmt;
	}
	
	/**
     * 更新管理员默认仓库
     *
     * @param    string $username
     * @param    int    $lid
     * @return   void
     */
	public function updateLid($username, $lid) {
	    $this -> _db -> update($this -> _table, array('lid' => $lid), "admin_name = '{$username}'");
	}
	
	/**
     * 记录管理员登录信息
     *
     * @param    array    $data
     * @return   int
     */
	public function loginLog($data)
	{
		$adminRow = $data;
        $this -> _db -> insert($this -> _tableLog, $adminRow);
        return  $this -> _db -> lastInsertId();
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
	public function getLog($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY log_id DESC ";
		}
        $table=  " `$this->_tableLog` ";
		return array('list'=>$this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit"),'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql"));
	}

}