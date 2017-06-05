<?php
class Admin_Models_DB_Admin
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 分页大小
     * 
     * @var    int
     */
	private $_pageSize = null;
	
	/**
     * 管理员表名
     * 
     * @var    string
     */
	private $_table = 'shop_admin';
	
	/**
     * 管理组表名
     * 
     * @var    string
     */
	private $_groupTable = 'shop_admin_group';
	
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
     * 取得管理员信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getAdmin($where = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		
		$whereSql = " WHERE A.admin_id !=1";
		
		if ($where != null) {
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}
		
		$sql = 'SELECT A.*, B.group_id AS gid, B.group_name, B.group_menu, B.group_privilege FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _groupTable . '` AS B ON A.group_id=B.group_id  ' . $whereSql . ' ORDER BY A.group_id asc,A.admin_id ASC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得管理员人数
     *
     * @param    string    $where
     * @return   int
     */
	public function getAdminCount($where = null)
	{
		$whereSql = " WHERE A.admin_id !=1";
		
		if ($where != null) {
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}
		if ($where != null && is_array($where)) {
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		$count = $this -> _db -> fetchOne('SELECT count(*) as count FROM `' . $this -> _table . '` AS A ' . $whereSql);
		return $count;
	}
	
	/**
     * 添加管理员
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addAdmin(array $data)
	{
		$row = array (
                      'admin_name' => $data['admin_name'],
                      'real_name' => $data['real_name'],
                      'password' => $data['password'],
                      'group_id' => $data['group_id'],
                      'email' => $data['email'],
                      'status' => 1,
                      'add_time' => $data['add_time'],
                      'add_admin' => $data['add_admin'],
                      'menu' => $data['menu'],
                      'privilege' => $data['privilege'],
                      'lid' => $data['lid'] ? $data['lid'] : 0,
                      );
        $this -> _db -> insert($this -> _table, $row);
        $adminId = $this -> _db -> lastInsertId();
      
        return $adminId;
	}
	
	/**
     * 更新管理员信息
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateAdmin(array $data, $id)
	{
		$data['real_name'] && $set['real_name'] = $data['real_name'];
		$data['email'] && $set['email'] = $data['email'];
		$data['group_id'] && $set['group_id'] = $data['group_id'];
		$data['add_time'] && $set['add_time'] = $data['add_time'];
		$data['menu'] && $set['menu'] = $data['menu'];
		$data['privilege'] && $set['privilege'] = $data['privilege'];
		isset($data['lid']) && $set['lid'] = $data['lid'];
                      
        (strlen($data['password'])) ? $set['password'] = $data['password'] : '';
        
        $where = $this -> _db -> quoteInto('admin_id = ?', $id);
		$adminId = $this -> _db -> update($this -> _table, $set, $where);
		
		if ($adminId) {
			$admin = @array_shift($this -> getAdmin(array('A.admin_id' => $id)));
        }
        return $adminId;
	}
	
	/**
     * 修改密码
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function changePassword(array $data, $id)
	{
        $set['password'] = md5($data['password']);
        $where = $this -> _db -> quoteInto('admin_id = ?', $id);
		$adminId = $this -> _db -> update($this -> _table, $set, $where);
        return $adminId;
	}




	/**
     * 删除指定管理员
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteAdmin($id)
	{
		$where = $this -> _db -> quoteInto('admin_id = ?', $id);
		return  $this -> _db -> delete($this -> _table, $where);
	}
	
	/**
     * 更新管理员状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   int    lastInsertId
     */
	public function updateStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('admin_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
}