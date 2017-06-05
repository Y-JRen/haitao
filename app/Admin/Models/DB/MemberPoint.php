<?php

class Admin_Models_DB_MemberPoint
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
     * 会员积分表名
     * 
     * @var    string
     */
	private $_table = 'shop_member_point';
	
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
     * 会员表
     * 
     * @var    string
     */
	private $_userTable = 'shop_user';

	/**
     * 订单批次表
     * 
     * @var    string
     */
	private $_orderBatchTable = 'shop_order_batch';


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
     * 取得会员积分信息
     *
     * @param    array    $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getPoint($where = null, $page = null, $pageSize = null)
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
		$sql = 'SELECT A.*,B.point as total_point,B.nick_name as nick_name,C.batch_sn FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _memberTable . '` AS B ON A.member_id=B.member_id left join `' . $this -> _orderBatchTable . '` C on A.order_id=C.order_batch_id  ' . $whereSql . ' ORDER BY A.add_time DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}


	/**
     * 取得会员积分信息
     *
     * @param    array    $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getExperience($where = null, $page = null, $pageSize = null)
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
		$sql = 'SELECT A.*,A.remark as note , unix_timestamp(A.created_ts) as add_time, B.experience as total_experience,B.nick_name as nick_name FROM `shop_member_experience` AS A LEFT JOIN `' . $this -> _memberTable . '` AS B ON A.member_id=B.member_id ' . $whereSql . ' ORDER BY A.created_ts DESC ' . $limit;

		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得会员积分信息
     *
     * @param    array    $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getPointFrequency($where = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "  LIMIT $pageSize OFFSET $offset ";
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
		$sql = "SELECT A.member_id as member_id ,count(A.point) as total_num,A.point,B.nick_name as nick_name FROM " . $this -> _table . " AS A LEFT JOIN " . $this -> _memberTable . " AS B ON A.member_id=B.member_id  "  . $whereSql ." GROUP BY A.member_id  ORDER BY  total_num DESC  ". $limit ;

		$point= $this -> _db -> fetchAll($sql);
		$countsql = " SELECT COUNT(distinct(member_id))  FROM " . $this -> _table. " as A " . $whereSql ;
		$total= $this -> _db -> fetchOne($countsql);
		return array('point' => $point, 'total' => $total);
	}
	
	/**
     * 取得会员积分记录个数
     *
     * @param    mixed    $where
     * @return   int
     */
	public function getPointCount($where = null)
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
		$sql = 'SELECT count(*) as count FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _memberTable . '` AS B ON A.member_id=B.member_id  LEFT JOIN `' . $this -> _userTable . '` AS U  ON B.user_id=U.user_id ' . $whereSql . ' ORDER BY A.add_time DESC ';
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}

	/**
     * 获取经验值总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getExperienceCount($params)
	 {	
		list($_condition, $_join) = $this->getExperienceCondition($params);

		$sql = "SELECT count(*) as count FROM `shop_member_experience` e ". implode(" ", $_join) ." WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 获取经验值列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function getExperienceList($params, $limit)
	 {	
		list($_condition, $_join) = $this->getExperienceCondition($params);

		$field = array(
			'm.member_id',
			'nick_name',
			'created_ts',
			'experience_total',
			'e.experience',
			'remark',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_member_experience` e ". implode(" ", $_join) ." WHERE ". implode(' AND ', $_condition) ." ORDER BY experience_id desc limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getExperienceCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		$_condition[] = " 1 = 1";
		!empty($params['start_ts'])  && $_condition[] = "created_ts >= '{$params['start_ts']} 00:00:00'";
		!empty($params['end_ts'])    && $_condition[] = "created_ts <= '{$params['end_ts']} 23:59:59'";
		!empty($params['nick_name']) && $_condition[] = "nick_name like '%{$params['nick_name']}%'";

		$_join[] = "LEFT JOIN `shop_member` m on e.member_id = m.member_id";
		return array($_condition, $_join);
	 }
	
	/**
     * 添加会员积分记录
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addPoint(array $data)
	{
		$row = array (
                      'member_id' => $data['member_id'],
					  'user_name' => ($data['user_name']) ? $data['user_name'] : '',
                      'admin_id' => $data['admin_id'],
			          'admin_name' => ($data['admin_name']) ? $data['admin_name'] : '',
                      'order_id' => $data['order_id'],
                      'point_type' => $data['point_type'],
                      'point_total' => $data['point_total'] + $data['point'],
                      'point' => $data['point'],
                      'batch_sn' => $data['batch_sn'],
                      'note' => $data['note'],
                      'add_time' => $data['add_time']
                      );
        
        $this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}

	/**
     * 添加会员经验值记录
     *
     * @param    array
     *
	 * @return   int
     */
	public function addExperience($param)
	{
		$param = array (
			  'member_id'        => intval($param['member_id']),
			  'experience'       => intval($param['experience']),
			  'experience_total' => intval($param['experience_total']),
			  'batch_sn'         => strval($param['batch_sn']),
			  'remark'           => $param['remark'],
			  'created_by'       => $param['created_by'],
		  );
        
        $this->_db->insert('shop_member_experience', $param);

		return $this->_db->lastInsertId();
	}
	
	/**
     * 删除指定ID的会员积分记录
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteMoneyById($id)
	{
		$where = $this -> _db -> quoteInto('point_id = ?', $id);
		return $this -> _db -> delete($this -> _table, $where);
	}
	
	/**
     * 删除指定日期以前的会员积分历史记录
     *
     * @param    int      $timestamp
     * @return   int      lastInsertId
     */
	public function deletePointByTime($timestamp)
	{
		$where = $this -> _db -> quoteInto('add_time < ?', $timestamp);
		return $this -> _db -> delete($this -> _table, $where);
	}

	/**
	* 返回错误信息
	*
	* @return   string
	*/
	public function getError()
	{
		return $this->_error;	
	}
}