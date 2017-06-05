<?php

class Shop_Models_DB_Auth
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 用户基表表名
     * 
     * @var    string
     */
	private $_table = 'shop_user';
	
	/**
     * 普通会员表名
     * 
     * @var    string
     */
	private $_memberTable = 'shop_member';
	
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
     * 取得基表用户信息
     *
     * @param    string    $where
     * @return   array
     */
	public function getUser($where)
	{
		if ($where != null && is_array($where)) {
			$whereSql = "WHERE 1=1";
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='".$value."'";
			}
			$sql = 'SELECT * FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _memberTable . '` as B ON A.user_id=B.user_id ' . $whereSql . ' LIMIT 1';
		    return $this -> _db -> fetchAll($sql);
		}
	}
	/*根据用户名获取用户基本信息及验证用户名信息*/
	public function getUserSamp($user_name){
	    $sql = 'select * from `' . $this -> _table . '` where user_name = "'.$user_name.'"';
	    return $this -> _db -> fetchRow($sql);
	}
	
	/**
	 * 检查用户是否存在
	 * @param string $user 用户名
	 */
	public function checkUser($user)
	{
		$sql = "SELECT * FROM `{$this->_table}` AS A INNER JOIN `{$this->_memberTable}` AS B ON A.user_id = B.user_id WHERE A.user_name = '{$user}' OR B.mobile = '{$user}' OR B.email = '{$user}' LIMIT 1";
		return $this -> _db -> fetchAll($sql);
	}
	/**
	 *检测用户名是否存在（不包括当前用户）
	 */
	public function checkUser2($user,$user_id)
	{
		$sql = "SELECT * FROM `" . $this -> _table . "` AS A LEFT JOIN `"
		. $this -> _memberTable . "` as B ON A.user_id=B.user_id Where A.user_id<>{$user_id} and( A.user_name = '"
		. $user."' OR B.email = '{$user}' OR B.mobile = '{$user}') LIMIT 1";
		return $this -> _db -> fetchAll($sql);
	}
	/**
	 * 更新会员信息
	 * @param string $where
	 * @param array $set
	 */
	public function updateMember($where,$set)
	{
	    return $this -> _db -> update($this->_memberTable, $set, $where);
	}
}