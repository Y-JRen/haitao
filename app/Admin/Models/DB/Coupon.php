<?php
class Admin_Models_DB_Coupon
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 礼券使用记录表名
     * 
     * @var    string
     */
	private $_couponTable = 'shop_coupon_card';
	
	/**
     * 礼券发放记录表名
     * 
     * @var    string
     */
	private $_logTable = 'shop_coupon_card_create_log';
	
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
     * 取得礼券发放记录
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getLog($where = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		//$whereSql = ' WHERE is_system=0';
		$whereSql = ' WHERE 1';
		
		if ($where) {
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='".$value."'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		$sql = 'SELECT * FROM `' . $this -> _logTable . '` ' . $whereSql . ' ORDER BY range_end DESC' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得礼券发放记录
     *
     * @param    string   $where
     * @return   array
     */
	public function getLogCount($where = null)
	{
		//$whereSql = ' WHERE is_system=0';
		$whereSql = ' WHERE 1';
		if ($where) {
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='".$value."'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		
		$sql = 'SELECT COUNT(log_id) as count FROM `' . $this -> _logTable . '` ' . $whereSql;
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 取得礼券使用记录
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getHistory($where = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		
		if ($where) {
			$whereSql = ' WHERE 1=1';
			
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='".$value."'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		
		$sql = "SELECT t1.* FROM {$this -> _couponTable} as t1 left join {$this -> _logTable} as t2 on t1.log_id = t2.log_id $whereSql ORDER BY t1.card_id DESC {$limit}";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得礼券发放记录
     *
     * @param    string   $where
     * @return   array
     */
	public function getHistoryCount($where = null)
	{
		if ($where) {
			$whereSql = ' WHERE 1=1';
			
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='".$value."'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		
		$sql = "SELECT COUNT(card_id) as count FROM {$this -> _couponTable} as t1 left join {$this -> _logTable} as t2 on t1.log_id = t2.log_id {$whereSql}";
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 取得礼券发放记录
     *
     * @param    void
     * @return   int
     */
	public function getPosition()
	{
		$sql = 'SELECT IFNULL(MAX(range_end), 0) FROM `' . $this -> _logTable . '`';
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 添加礼券发放记录
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addLog(array $data)
	{
		$row = array (
                      'card_type' => $data['card_type'],
                      'is_repeat' => $data['is_repeat'],
                      'card_price' => $data['card_price'],
                      'range_from' => $data['range_from'],
                      'range_end' => $data['range_end'],
                      'number' => $data['number'],
                      'goods_info' => $data['goods_info'],
                      'freight' => $data['freight'] ? $data['freight'] : 0,
                      'admin_name' => $data['admin_name'],
                      'add_time' => $data['add_time'],
			          'start_date' => $data['start_date'],
                      'end_date' => $data['end_date'],
                      'exclusive_except' => $data['exclusive_except'] ? $data['exclusive_except'] : 0,
                      'aid' => $data['aid'] ? $data['aid'] : 0,
                      'price_recount' => $data['price_recount'] ? $data['price_recount'] : 0,
                      'status' => 0
                      );
        $data['min_amount'] && $row['min_amount'] = $data['min_amount'];
        $data['parent_id'] && $row['parent_id'] = $data['parent_id'];
        $data['note'] && $row['note'] = $data['note'];
        $data['is_system'] && $row['is_system'] = $data['is_system'];
        $data['is_affiliate'] && $row['is_affiliate'] = intval($data['is_affiliate']);
        $data['is_limit_user'] && $row['is_limit_user'] = intval($data['is_limit_user']);
        if ($this -> _db -> insert($this -> _logTable, $row)) {
        	return $this -> _db -> lastInsertId();
        }
        return false;
	}
	
	/**
     * 系统添加礼券
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addSysCoupon(array $data)
	{
		$row = array (
                      'log_id' => $data['log_id'],
                      'card_type' => $data['card_type'],
                      'is_repeat' => $data['is_repeat'],
                      'card_price' => $data['card_price'],
                      'card_sn' => $data['card_sn'],
                      'card_pwd' => $data['card_pwd'],
                      'user_id' => $data['user_id'],
                      'user_name' => $data['user_name'],
                      'add_time' => $data['add_time'],
                      'status' => 0
                      );
        return $this -> _db -> insert($this -> _couponTable, $row);
	}
	
	/**
     * 更新礼券发放记录状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   int    lastInsertId
     */
	public function updateLogStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('log_id = ?', $id);
		return $this -> _db -> update($this -> _logTable, $set, $where);
	}

	/**
     * 取得会员礼金券信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getCouponList($where = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		if ($where) {
			$whereSql = ' WHERE 1=1';
			
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='".$value."'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		$sql = 'SELECT A.*, B.end_date,B.start_date,B.card_price as coupon_price FROM `' . $this -> _couponTable . '` AS A LEFT JOIN `' . $this -> _logTable . 
            '` AS B ON A.log_id=B.log_id ' . $whereSql . ' ORDER BY A.status ASC, A.add_time DESC' . $limit;
		$countsql = 'SELECT COUNT(card_id) as count FROM `' . $this -> _couponTable . '` AS A LEFT JOIN `' . $this -> _logTable . '` AS B ON A.log_id=B.log_id ' . $whereSql;
		return array('list' => $this -> _db -> fetchAll($sql),'total' => $this -> _db -> fetchOne($countsql) );
	}

	/**
     * 设置券
     *
     * @return void
     */
    public function setCoupon($card_sn)
    {
		$set = array ('status' => 1);
		$where = $this -> _db -> quoteInto('card_sn = ?', $card_sn);
		return $this -> _db -> update($this -> _couponTable, $set, $where);
	}

}