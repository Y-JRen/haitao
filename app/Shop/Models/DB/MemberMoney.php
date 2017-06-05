<?php

class Shop_Models_DB_MemberMoney
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
     * @param    array    $page
     * @param    array    $pageSize
     * @return   array
     */
	public function getMoney($where = null, $page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}else{
                $whereSql = ' WHERE 1=1';
        }
		
		$sql = 'SELECT A.*,B.money as total_money FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _memberTable . '` AS B ON A.member_id=B.member_id ' . $whereSql . ' ORDER BY A.add_time DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得会员账户余额历史记录个数
     *
     * @param    string    $where
     * @return   int
     */
	public function getMoneyCount($where = null)
	{
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}else{
                $whereSql = ' WHERE 1=1';
        }
		
		$count = $this -> _db -> fetchOne('SELECT count(*) as count FROM `' . $this -> _table . '` ' . $whereSql);
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
}