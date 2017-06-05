<?php

class Shop_Models_DB_MemberPoint
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
     * @return   array
     */
	public function getPoint($where = null, $page=null, $pageSize = null)
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
		
		$sql = 'SELECT A.*,B.point as total_point FROM `' . $this -> _table . '` AS A LEFT JOIN `' . $this -> _memberTable . '` AS B ON A.member_id=B.member_id ' . $whereSql . ' and disable=0 ORDER BY A.add_time DESC ' . $limit;
		
		return  $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得会员积分记录个数
     *
     * @param    string    $where
     * @return   int
     */
	public function getPointCount($where = null)
	{
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE disable=0';
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
			  'batch_sn'         => $param['batch_sn'],
			  'remark'           => $param['remark'],
			  'created_by'       => $param['created_by'],
		  );
        
        $this->_db->insert('shop_member_experience', $param);

		return $this->_db->lastInsertId();
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
		
		$_condition[] = " e.`member_id` = '{$params['member_id']}'";

		$_join[] = "LEFT JOIN `shop_member` m on e.member_id = m.member_id";
		return array($_condition, $_join);
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