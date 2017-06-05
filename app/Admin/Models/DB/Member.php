<?php
class Admin_Models_DB_Member
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
     * 用户表名
     *
     * @var    string
     */
	private $_tableUser = 'shop_user';

	/**
     * 会员表名
     *
     * @var    string
     */
	private $_tableMember = 'shop_member';
	/**
     * 会员等级表名
     *
     * @var    string
     */
	private $_tableMemberRank = 'shop_member_rank';
	private $_table_order_batch_goods = 'shop_order_batch_goods';
    private $_table_order_batch = 'shop_order_batch';

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
     * 取得搜索会员信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getMember($where = null, $page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $whereSql = ($where) ? " WHERE 1=1" : " WHERE B.user_id<=(SELECT user_id FROM `" . $this -> _tableMember . "` ORDER BY user_id DESC LIMIT 1 OFFSET $offset)";
		    $limit = ($where) ? " LIMIT $pageSize OFFSET $offset" : " LIMIT $pageSize";
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
		}
		$sql = 'SELECT A.*,B.* FROM `' . $this -> _tableUser . '` AS A INNER JOIN `' . $this -> _tableMember . '` as B ON A.user_id=B.user_id ' . $whereSql . " ORDER BY A.user_id DESC " . $limit;
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 取得会员人数
     *
     * @param    mixed    $where
     * @return   int
     */
	public function getMemberCount($where = null)
	{
		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}

			$sql = 'SELECT count(*) as count FROM `' . $this -> _tableUser . '` AS A INNER JOIN `' . $this -> _tableMember . '` as B ON A.user_id=B.user_id LEFT JOIN `' . $this -> _tableMemberRank . '` AS C ON B.rank_id=C.rank_id ' . $whereSql;
		} else {
			$sql = 'SELECT count(*) as count FROM `' . $this -> _tableMember . '` AS A INNER JOIN `' . $this -> _tableMember . '` as B ON A.user_id=B.user_id';
		}
		$count = $this -> _db -> fetchOne($sql);

		return $count;
	}

	/**
     * 添加会员
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addMember(array $data)
	{
		$userRow = array (
                          'user_name' => $data['user_name'],
                          'password' => $data['password'],
                          'add_time' => $data['add_time']
                          );
        $this -> _db -> insert($this -> _tableUser, $userRow);
        $userId =  $this -> _db -> lastInsertId();

        $memberRow = array (
                            'user_id' => $userId,
                            'rank_id' => $data['rank_id'],
                            'nick_name' => $data['nick_name'],
                            'real_name' => $data['real_name'],
                            'sex' => $data['sex'],
                            'birthday' => $data['birthday'],
                            'email' => $data['email'],
                            'msn' => $data['msn'],
                            'qq' => $data['qq'],
                            'office_phone' => $data['office_phone'],
                            'home_phone' => $data['home_phone'],
                            'mobile' => $data['mobile'],
                            'status' => 1
                            );
        $memberRow['discount'] = $data['discount'] ? $data['discount'] : 1;
        $data['question'] && $memberRow['question'] = $data['question'];
        $data['answer'] && $memberRow['answer'] = $data['answer'];
        $memberId = $this -> _db -> insert($this -> _tableMember, $memberRow);
        if ($memberId <=0) {
        	$where = $this -> _db -> quoteInto('user_id = ?', $userId);
        	$this -> _db -> delete($this -> _tableUser, $where);
        	return -1;
        }

		return $this -> _db -> lastInsertId();
	}

	/**
     * 更新会员信息
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateMember(array $data, $id)
	{
		$userSet = array (
                          'update_time' => $data['update_time']
                          );
        $data['password'] && $userSet['password'] = $data['password'];
        $where = $this -> _db -> quoteInto('user_id = ?', $id);
		$this -> _db -> update($this -> _tableUser, $userSet, $where);
		$memberSet = array (
                      'rank_id' => $data['rank_id'],
                      //'nick_name' => $data['nick_name'],
                      'real_name' => $data['real_name'],
                      'sex' => $data['sex'],
                      'birthday' => $data['birthday'],
                      'email' => $data['email'],
                      'msn' => $data['msn'],
                      'qq' => $data['qq'],
                      'office_phone' => $data['office_phone'],
                      'home_phone' => $data['home_phone'],
                      'mobile' => $data['mobile']
                      );

        $memberSet['discount'] = $data['discount'] ? $data['discount'] : 1;
        $data['question'] && $memberSet['question'] = $data['question'];
        $data['answer'] && $memberSet['answer'] = $data['answer'];
        return $this -> _db -> update($this -> _tableMember, $memberSet, $where);
	}

	/**
     * 删除指定会员
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteMember($id)
	{
		$member = $this -> getMember(array('A.user_id' => $id));
		if ($member) {
			$where = $this -> _db -> quoteInto('user_id = ?', $id);
			$where2 = $this -> _db -> quoteInto('member_id = ?', $member['member_id']);
		    $this -> _db -> delete('shop_member_address', $where2);
		    $this -> _db -> delete('shop_member_money', $where2);
		    $this -> _db -> delete('shop_member_point', $where2);
		    $this -> _db -> delete($this -> _tableUser, $where);
		    return $this -> _db -> delete($this -> _tableMember, $where);
		}
	}

	/**
     * 更新会员状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   int    lastInsertId
     */
	public function updateStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('user_id = ?', $id);
		return $this -> _db -> update($this -> _tableMember, $set, $where);
	}

	/**
     * 更新会员虚拟账户信息
     *
     * @param    int    $id
     * @param    float  $money
     * @return   int    lastInsertId
     */
	public function updateMoney($id, $money)
	{
		$oldMoney = $this -> _db -> fetchOne('SELECT money FROM ' . $this -> _tableMember . ' WHERE member_id=' . $id);
		$money += $oldMoney;
		$set = array ('money' => $money);
		$where = $this -> _db -> quoteInto('member_id = ?', $id);
		return $this -> _db -> update($this -> _tableMember, $set, $where);
	}

	/**
     * 更新会员积分信息
     *
     * @param    int    $id
     * @param    int    $point
     * @return   int    lastInsertId
     */
	public function updatePoint($id, $point)
	{
		$user = $this -> _db -> fetchRow('SELECT point,rank_id FROM ' . $this -> _tableMember . ' WHERE member_id=' . $id);
		$newPoint = $point + $user['point'];
		$set = array('point' => $newPoint);
		if($point > 0){
			$updatetime = time();
			$set['point_update_time'] = $updatetime;
			$user['rank_id'] > 1 && $set['rank_update_time'] = $updatetime;
		}
		$where = $this -> _db -> quoteInto('member_id = ?', $id);
		return $this -> _db -> update($this -> _tableMember, $set, $where);
	}

	/**
     * 更新会员经验值
     *
     * @param    int    
     * @param    int    
     *
	 * @return   boolean
     */
	public function updateExperience($member_id, $experience)
	{
		$member_id = intval($member_id);
		if ($member_id < 1) {
			$this->_error = '会员ID不正确';
			return false;
		}

		$experience = intval($experience);
		if ($experience == 0) {
			$this->_error = '没有经验值需要操作';
			return false;
		}

		$sql = "UPDATE `{$this -> _tableMember}` SET experience = experience + {$experience} WHERE member_id = '{$member_id}'";

		return (bool) $this->_db->execute($sql);
	}


    /**
     * 取用户信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getExportUser($where = null)
	{
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : "   where 1=1  ";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}else{
                $whereSql = '  where 1=1 ';
        }
		$sql = 'SELECT A.*,B.*,C.*,D.user_name as un_name FROM `' . $this -> _tableUser . '` AS A INNER JOIN `' . $this -> _tableMember . '` as B ON A.user_id=B.user_id LEFT JOIN `' . $this -> _tableMemberRank . '` AS C ON B.rank_id=C.rank_id  LEFT JOIN `' . $this -> _tableUser . '` AS D ON B.parent_id=D.user_id ' . $whereSql . " ORDER BY A.user_id DESC " . $limit;
		return $this -> _db -> fetchAll($sql);

	}

	/**
     * 通过优惠券获得订单信息
     *
     * @param    string    $cardSN
     * @param    string    $cardType
     * @return   array
     */
	function getOrderBatchGoodsByCardSN($cardSN, $cardType)
	{
	    $sql = "select t2.batch_sn,t2.status,t2.price_pay,t2.addr_consignee,t2.addr_mobile from {$this -> _table_order_batch_goods} as t1
	            left join {$this -> _table_order_batch} as t2 on t1.order_batch_id = t2.order_batch_id
	            where t1.card_sn = '{$cardSN}' and card_type = '{$cardType}' order by order_batch_goods_id desc";
	    return $this -> _db -> fetchRow($sql);
	}

	/**
     * 通过条件获取会员信息
     *
     * @param    array
     * 
     * @return   array
     */
	public function getMemberInfoByCondition($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		$_condition = array();
		$_condition[] = "status = '1'";

		!empty($params['start_ts']) && $_condition[] = "last_login >='{$params['start_ts']}'";

		$sql = "SELECT `member_id`, shop_user.`user_id`, `nick_name`, shop_user.user_name
				FROM `shop_member` LEFT JOIN `shop_user` ON shop_member.user_id = shop_user.user_id 
				WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchAll($sql);
	}

	/**
	 * 根据会员ID获取会员信息
	 *
	 * @param    int
	 *
	 * @return   array
	 **/
	public function getMemberInfoByMemberId($member_id)
	{
		$member_id = intval($member_id);
		if ($member_id < 1) {
			$this->_error = '会员ID不正确';
			return false;
		}

		$sql = "SELECT `member_id`, `rank_id`, `experience` FROM `shop_member` WHERE `member_id` = '{$member_id}'";

		return $this->_db->fetchRow($sql);
	}

	/**
	 * 更新会员信息
	 *
	 * @param    int
	 * @param    array
	 *
	 * @return   array
	 **/
	public function updateMemberInfoByMemberId($member_id, $params)
	{
		$member_id = intval($member_id);
		if ($member_id < 1) {
			$this->_error = '会员ID不正确';
			return false;
		}
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (count($params) < 1) {
			$this->_error = '没有需要更新的数据';
			return false;
		}

		return (bool) $this->_db->update('shop_member', $params, "member_id = '{$member_id}'");
	}

	/**
	 * 添加等级操作日志
	 *
	 * @param    array
	 *
	 * @return   boolean
	 **/
	public function addMemberRankLog($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (count($params) < 1) {
			$this->_error = '没有需要更新的数据';
			return false;
		}

		return (bool) $this->_db->insert('shop_member_rank_log', $params);
	}

	/**
	 * 获取经验值对应的等级
	 *
	 * @param    array
	 *
	 * @return   array
	 **/
	public function getRankInfoByExperience($experience)
	{
		$experience = intval($experience);

		$sql = "SELECT `rank_id`, `rank_name` FROM `shop_member_rank` WHERE min_point <= '{$experience}' AND max_point > '{$experience}'";

		return $this->_db->fetchRow($sql);
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