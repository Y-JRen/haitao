<?php

class Admin_Models_DB_GiftCard
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 礼品卡记录表名
     * 
     * @var    string
     */
	private $_table = 'shop_gift_card';
	
	/**
     * 礼品卡发放记录表名
     * 
     * @var    string
     */
	private $_logTable = 'shop_gift_card_create_log';
	
	/**
     * 礼品卡使用历史记录表名
     * 
     * @var    string
     */
	private $_useLogTable = 'shop_gift_card_use_log';
	
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
     * 取得礼品卡发放记录
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
		
		$sql = 'SELECT * FROM `' . $this -> _logTable . '` ' . $whereSql . ' ORDER BY log_id DESC' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得礼品卡发放记录
     *
     * @param    string   $where
     * @return   array
     */
	public function getLogCount($where = null)
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
		
		$sql = 'SELECT COUNT(log_id) as count FROM `' . $this -> _logTable . '` ' . $whereSql;
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 取得礼品卡列表
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getCard($where = null, $page = null, $pageSize = null)
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
		
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql . ' ORDER BY card_id DESC' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得礼品卡数量
     *
     * @param    string   $where
     * @return   array
     */
	public function getCardCount($where = null)
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
		
		$sql = 'SELECT COUNT(card_id) as count FROM `' . $this -> _table . '` ' . $whereSql;
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 取得未使用礼品卡列表
     *
     * @param    string   $where
     * @return   array
     */
	public function getUnuseCard($where = null, $page = null, $pageSize = null)
	{
		if ($where) {
			$whereSql = ' WHERE (using_time IS NULL OR using_time=0) AND status=0';
			
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='".$value."'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得礼品卡使用历史
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getCardLog($where = null, $page = null, $pageSize = null)
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

		$sql = "SELECT t1.*,t2.card_pwd,t2.card_price,t2.status,t2.card_real_price,t3.logistic_time,t3.status_logistic FROM {$this -> _useLogTable} as t1 
		        inner join {$this -> _table} as t2 on t1.card_sn = t2.card_sn 
		        left join shop_order_batch as t3 on t1.batch_sn = t3.batch_sn 
		        {$whereSql} ORDER BY add_time DESC {$limit}";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加日志
     *
     * @param    array   $data
     * @return   void
     */
	public function addCardLog($data)
	{
	    $this -> _db -> insert($this -> _useLogTable, $data);
	}
	
	/**
     * 取得礼品卡使用历史数量
     *
     * @param    string   $where
     * @return   array
     */
	public function getCardLogCount($where = null)
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
		
		$sql = "SELECT COUNT(t1.log_id) as count,sum(price) as price FROM {$this -> _useLogTable} as t1 
		        inner join {$this -> _table} as t2 on t1.card_sn = t2.card_sn 
		        left join shop_order_batch as t3 on t1.batch_sn = t3.batch_sn 
		        {$whereSql}";
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
     * 取得礼品卡最后发放记录ID
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
     * 添加礼品卡发放记录
     *
     * @param    array    $data
     * @return   int      affected rows
     */
	public function addLog(array $data)
	{
		$row = array('card_type' => $data['card_type'],
                     'card_price' => $data['card_price'],
                     'range_from' => $data['range_from'],
                     'range_end' => $data['range_end'],
                     'number' => $data['number'],
                     'admin_name' => $data['admin_name'],
                     'add_time' => $data['add_time'],
                     'end_date' => $data['end_date'],
                    );
        $data['parent_id'] && $row['parent_id'] = $data['parent_id'];
        $data['note'] && $row['note'] = $data['note'];
        $insertLog = $this -> _db -> insert($this -> _logTable, $row);
        $lastInsertId = $this -> _db -> lastInsertId();
        
        !isset($data['status']) && $data['status'] = 0;
        if ($lastInsertId && $data['card_sn']) {
			foreach ($data['card_sn'] as $card) {
				$cardMsg[] = "('" . $lastInsertId . "', '" . $data['card_type'] . "', '" . $data['card_price'] . "', '" . $data['card_price'] . "', '" . $card['sn'] . "', '" . $card['pwd'] . "', '" . $data['order_batch_goods_id'] . "', '" . $data['add_time'] . "', '" . $data['end_date'] . "', '". $data['status'] . "','{$data['buyer_id']}')";
			}
			$sql = 'INSERT INTO `' . $this -> _table . '`(`log_id`, `card_type`, `card_price`, `card_real_price`, `card_sn`, `card_pwd`, `order_batch_goods_id`, `add_time`, `end_date`, `status`,`buyer_id`) VALUES ' . implode(',', $cardMsg);
			return $this -> _db -> execute($sql, $this -> _table);
		}
	}
	
	/**
     * 更新礼品卡发放记录状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   int    lastInsertId
     */
	public function updateLogStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('log_id = ?', $id);
		$this -> _db -> update($this -> _table, $set, $where);
		return $this -> _db -> update($this -> _logTable, $set, $where);
	}

	/**
     * 取得礼品卡信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getGiftInfo($where = null)
	{
		if ($where) {
			$whereSql = ' WHERE 1=1';
			
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='$value'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql;
		return $this -> _db -> fetchRow($sql);
	}



	/**
     * 使用礼品卡
     *
     * @param    array    $data
     * @return   int      affected rows
     */
	public function useGift($data)
	{
		$gift = $this -> getGiftInfo(array('card_sn' => $data['card_sn'], 'card_pwd' => $data['card_pwd'], 'status' => 0));
		if ($gift && $gift['card_price'] >= $data['card_price']) {
			$price = $gift['card_real_price'] - $data['card_price'];
			$set = array (
			              'using_time' => $data['add_time'],
			              'user_id' => $data['user_id'],
        	              'user_name' => $data['user_name'],
        	              'card_real_price' => $price
        	              );
        	$price <= 0 && $set['status'] = 1;
        	$where = $this -> _db -> quoteInto('card_sn = ?', $data['card_sn']);
			$useCard = $this -> _db -> update($this -> _table, $set, $where);
			if ($useCard) {
				$row = array (
        	                  'card_type' => $data['card_type'],
        	                  'card_sn' => $data['card_sn'],
        	                  'price' => $data['card_price'],
        	                  'user_id' => $data['user_id'],
        	                  'user_name' => $data['user_name'],
        	                  'add_time' => $data['add_time']
        	                  );
        	    return $this -> _db -> insert($this -> _logTable, $row);
			}
		}
	}

	/**
     * 会员礼品卡信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getGiftCardList($where = null, $page = null, $pageSize = null)
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
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql . ' ORDER BY card_id DESC' . $limit;
		$countsql = 'SELECT COUNT(card_id) as count FROM `' . $this -> _table . '` ' . $whereSql;
		return array('list' => $this -> _db -> fetchAll($sql),'total' => $this -> _db -> fetchOne($countsql) );
	}
	
	/**
     * 获得已使用的汇总信息
     *
     * @param    array  $logIDArray
     * @return   array
     */
    public function getUsedSum($logIDArray)
    {
        $sql = "select log_id,count(*) as num from {$this -> _table} where log_id in (".implode(',', $logIDArray).") and user_id > 0 group by log_id";
        $datas = $this -> _db -> fetchAll($sql);
        if ($datas) {
            foreach ($datas as $data) {
                $result[$data['log_id']]['bind_count'] = $data['num'];
            }
        }
        
        $sql = "select log_id,sum(card_price-card_real_price) as amount from {$this -> _table} where log_id in (".implode(',', $logIDArray).") group by log_id";
        $datas = $this -> _db -> fetchAll($sql);
        if ($datas) {
            foreach ($datas as $data) {
                $result[$data['log_id']]['consume_amount'] = $data['amount'];
            }
        }
        
        $sql = "select log_id,count(*) as num from {$this -> _table} where log_id in (".implode(',', $logIDArray).") and card_price > card_real_price group by log_id";
        $datas = $this -> _db -> fetchAll($sql);
        if ($datas) {
            foreach ($datas as $data) {
                $result[$data['log_id']]['consume_count'] = $data['num'];
            }
        }
        
        return $result;
    }
    
    /**
     * 更改礼品卡
     *
     * @param    string     $where
     * @param    array      $data
     * @return   void
     */
	public function updateCard($where, $data)
	{
	    $this -> _db -> update($this -> _table, $data, $where);
	}
	
	/**
     * 删除礼品卡
     *
     * @param    string  $where
     * @return   void
     */
	public function deleteCard($where)
	{
	    $this -> _db -> delete($this -> _table, $where);
	}
}