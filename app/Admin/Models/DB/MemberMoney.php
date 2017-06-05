<?php

class Admin_Models_DB_MemberMoney
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
     * 会员账户余额历史记录表名
     * 
     * @var    string
     */
	private $_table = 'shop_member_money';
	
	/**
     * 管理员表
     * 
     * @var    string
     */
	private $_adminTable = 'shop_admin';
	
	/**
     * 会员表
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
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}
	
	
	/**
     * 取得会员账户余额历史记录信息
     *
     * @param    array    $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getMoney($where = null, $page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}else{
                $whereSql = ' WHERE 1=1';
        }
		$sql = 'SELECT A.*,B.money as total_money,B.nick_name as nick_name,C.admin_name FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _memberTable . '` AS B ON A.member_id=B.member_id  LEFT JOIN `' . $this -> _adminTable . '` AS C ON A.admin_id=C.admin_id ' . $whereSql . ' ORDER BY A.add_time DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得会员账户余额历史记录个数
     *
     * @param    array    $where
     * @return   int
     */
	public function getMoneyCount($where = null)
	{
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}else{
                $whereSql = ' WHERE 1=1';
        }
		$sql = 'SELECT count(*) as count FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _memberTable . '` AS B ON A.member_id=B.member_id  LEFT JOIN `' . $this -> _adminTable . '` AS C ON A.admin_id=C.admin_id ' . $whereSql . ' ORDER BY A.add_time DESC ';
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 添加会员账户余额历史记录
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addMoney(array $data)
	{
		$row = array (
                      'member_id' => $data['member_id'],
                      'user_name' => ($data['user_name']) ? $data['user_name'] : '',
                      'admin_id' => $data['admin_id'],
                      'money_type' => $data['money_type'],
                      'money_total' => $data['money_total'] + $data['money'],
                      'money' => $data['money'],
                      'batch_sn' => $data['batch_sn'],
                      'note' => $data['note'],
                      'add_time' => $data['add_time']
                      );
        $this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 删除指定ID的会员账户余额历史记录
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteMoneyById($id)
	{
		$where = $this -> _db -> quoteInto('money_id = ?', $id);
		return $this -> _db -> delete($this -> _table, $where);
	}
	
	/**
     * 删除指定日期以前的会员账户余额历史记录
     *
     * @param    int      $timestamp
     * @return   int      lastInsertId
     */
	public function deleteMoneyByTime($timestamp)
	{
		$where = $this -> _db -> quoteInto('add_time < ?', $timestamp);
		return $this -> _db -> delete($this -> _table, $where);
	}
}