<?php

class Shop_Models_DB_Member
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	protected $_db = null;
	
	/**
     * 普通会员表名
     * 
     * @var    string
     */
	protected $_table = 'shop_member';
	
	/**
     * 会员等级表名
     * 
     * @var    string
     */
	protected $_tableMemberRank = 'shop_member_rank';
	
	/**
     * 登录记录表
     * 
     * @var    string
     */
	protected $_tableLog = 'shop_member_login_log';
	
	/**
     * 会员基表名
     * 
     * @var    string
     */
	protected $_tableUser = 'shop_user';
	
	/**
     * 地区表名
     * 
     * @var    string
     */
	protected $_tableArea = 'shop_area';
	/**
     * 物流公司
     * 
     * @var    string
     */
	protected $_tableLogistic = 'shop_logistic';

	/**
     * 用户抵用券 
     * 
     * @var    string
     */
	protected $_tableGiftCard = 'shop_gift_card';

	/**
     * 用户抵用券LOG 
     * 
     * @var    string
     */
	protected $_tableGiftCardLog = 'shop_gift_card_use_log';
	
	/**
     * 用户礼金券
     * 
     * @var    string
     */
	protected $_couponTable = 'shop_coupon_card';
	
	/**
     * 用户礼金券记录表
     * 
     * @var    string
     */
	protected $_couponLogTable = 'shop_coupon_card_create_log';

	/**
     * 暂存架表
     * 
     * @var    string
     */
	protected $_memberFavoriteTable = 'shop_member_favorite';
	/**
     * 商品表
     * 
     * @var    string
     */
	protected $_goodsTable = 'shop_goods';
	/**
     * 数据表
     * 
     * @var    string
     */
	protected $_msg = 'shop_msg';
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry :: get('config') -> view -> page_size;
	}
	
	/**
     * 普通会员注册
     *
     * @param    array    $data
     * @return   int
     */
	public function register($data)
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
							'parent_id'=>$data['parent_id'],
                            'rank_id' => $data['rank_id'],
                            'nick_name' => $data['nick_name'],
                            'status' => 1
                            );
        $data['tj_user_id'] && $memberRow['tj_user_id'] = (int)$data['tj_user_id'];
        $data['tj_user_name'] && $memberRow['tj_user_name'] = $data['tj_user_name'];
        $data['discount'] && $memberRow['discount'] = $data['discount'];
        $data['parent_id'] && $memberRow['parent_id'] = $data['parent_id'];
        $data['parent_user_name'] && $memberRow['parent_user_name'] = $data['parent_user_name'];
        $data['parent_param'] && $memberRow['parent_param'] = $data['parent_param'];
        $data['sex'] && $memberRow['sex'] = $data['sex'];
        $data['real_name'] && $memberRow['real_name'] = $data['real_name'];
        $data['home_phone'] && $memberRow['home_phone'] = $data['home_phone'];
        $data['birthday'] && $memberRow['birthday'] = $data['birthday'];
		$data['is_share'] && $memberRow['is_share'] = $data['is_share'];
        $data['share_id'] && $memberRow['share_id'] = $data['share_id'];
        $data['mobile'] && $memberRow['mobile'] = $data['mobile'];
        if ($data['email']) {
            $memberRow['email'] = $data['email'];
        } 
        return $this -> _db -> insert($this -> _table, $memberRow);
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
			$where = "(A.user_name =  {$username} OR B.mobile = {$username} OR B.email = {$username})  and A.password =  $password  and B.status=1 ";
			$stmt = $this -> _db -> fetchRow('SELECT A.*, B.* FROM `' . $this -> _tableUser . '` AS A INNER JOIN `' . $this -> _table . '` as B ON A.user_id=B.user_id  WHERE ' . $where);
			if ($stmt != null) {
				$stmt['last_login'] = time();
				$stmt['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
				$stmt['login_count'] = $stmt['login_count'] + 1;
				$where = 'user_name = ' . $username . ' and password = ' . $password;
				$set = array (
                              'last_login' => $stmt['last_login'],
                              'last_login_ip' => $stmt['last_login_ip'],
                              'login_count' => $stmt['login_count']
                              );
		        $this -> _db -> update($this -> _tableUser, $set, $where);
		        return $stmt;
			}
		}
        return null;
	}
	
	/**
     * 记录会员登录信息
     *
     * @param    array    $data
     * @return   int
     */
	public function loginLog($data)
	{
		$userRow = $data;
        $this -> _db -> insert($this -> _tableLog, $userRow);
        return  $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新会员信息
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateMember($data, $id)
	{
		$where = $this -> _db -> quoteInto('user_id = ?', $id);
		$userSet = array (
                          'update_time' => $data['update_time']
                          );
		$this -> _db -> update($this -> _tableUser, $userSet, $where);
		$memberSet = array (
                      'nick_name'       => $data['nick_name'],
                      'real_name'       => $data['real_name'],
                      'sex'             => $data['sex'],
                      'email'           => $data['email'],
                      'msn'             => $data['msn'],
                      'qq'              => $data['qq'],
                      'office_phone'    => $data['office_phone'],
                      'home_phone'      => $data['home_phone'],
                      'mobile'          => $data['mobile'],
				      'id_card'         => $data['id_card'],
			          'passport_number' => $data['passport_number'],
					  
                      );
		
		if(isset($data['ischecked'])&&$data['ischecked']==0){
			$memberSet['ischecked']=0;
		}
		
		if(isset($data['check_mobile'])){
			$memberSet['check_mobile']= $data['check_mobile'];
		}
        $data['birthday'] && $memberSet['birthday'] = $data['birthday'];
        $data['question'] && $memberSet['question'] = $data['question'];
        $data['answer'] && $memberSet['answer'] = $data['answer'];
        return $this -> _db -> update($this -> _table, $memberSet, $where);
	}

	/**
     * 昵称更新
     *
     * @param    array    $data
     * @param    int      $id
     * @return   bool
     */
    public function upNickname($nick_name,$user_id)
    {
		$where = $this -> _db -> quoteInto('user_id = ?', $user_id);
		$memberSet = array ('nick_name' => $nick_name);
        return $this -> _db -> update($this -> _table, $memberSet, $where);
    }
    
    
    public function updateEmail($email,$user_id)
    {
    	$where = $this -> _db -> quoteInto('user_id = ?', $user_id);
    	$memberSet = array ('email' => $email);
    	return $this -> _db -> update($this -> _table, $memberSet, $where);
    }
    /**
     * 头像更新
     *
     * @param    array    $data
     * @param    int      $id
     * @return   bool
     */
    public function upPhoto($photo,$user_id)
    {
        $where = $this -> _db -> quoteInto('user_id = ?', $user_id);
        $memberSet = array ('photo' => $photo);
        return $this -> _db -> update($this -> _table, $memberSet, $where);
    }
    

	/**
     * 更新会员最后一次购买 的地址 和 支付方式
     *
     * @param    array    $data
     * @param    int      $id
     * @return   bool
     */
    public function updateMemberCartInfo($data, $id)
    {
		$where = $this -> _db -> quoteInto('user_id = ?', $id);	
        return $this -> _db -> update($this -> _table,$data, $where);
    }
	/**
     * 修改密码
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updatePassword($data, $id)
	{
		$where = $this -> _db -> quoteInto('user_id = ?', $id);
		$userSet = array (
		                  'password' => $data['password'],
                          'update_time' => $data['update_time']
                          );
		return $this -> _db -> update($this -> _tableUser, $userSet, $where);
	}
	
	/**
     * 更新会员等级
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateMemberRank($data, $id)
	{
		$where = $this -> _db -> quoteInto('user_id = ?', $id);
		$memberSet = array (
		                  'discount' => $data['discount'],
		                  'rank_id' => $data['rank_id'],
                          'rank_to_time' => time(),
                          'rank_update_time' => time()
                          );
		return $this -> _db -> update($this -> _table, $memberSet, $where);
	}
	
	/**
     * 更新会员等级
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateBackPoint($id)
	{
		$where = $this -> _db -> quoteInto('user_id = ?', $id);
		$memberSet = array (
		                  'is_backpoint' => 1,
                          );
		return $this -> _db -> update($this -> _table, $memberSet, $where);
	}
	
	/**
     * 取得搜索会员信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getMember($where = null)
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
		}
		
		$sql = 'SELECT A.*, B.* FROM `' . $this -> _tableUser . '` AS A INNER JOIN `' . $this -> _table . '` as B ON A.user_id=B.user_id '
              . $whereSql . " ORDER BY A.user_id DESC LIMIT 1";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 根据用户模糊搜索会员信息 
     *
     * @param    string   $keywords
     * @param    string   $num
     * @return   array
     */
	public function searchUserList($keywords, $num)
	{
        $where = " where 1  and (user_name LIKE '%" . $keywords . "%')" ;
		$sql = 'SELECT user_id  FROM `' . $this -> _tableUser .'`'. $where . " ORDER BY user_id DESC LIMIT " .$num ;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得会员人数
     *
     * @param    string    $where
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
			$sql = 'SELECT count(A.user_id) as count FROM `' . $this -> _tableUser . '` AS A INNER JOIN `' . $this -> _table . '` as B ON A.user_id=B.user_id ON B.rank_id=C.rank_id ' . $whereSql;
		} else {
			$sql = 'SELECT count(member_id) as count FROM `' . $this -> _table . '`';
		}
		
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 取得子地区
     * @param    int    $areaID
     * @return   array
     */
	public function listArea($areaID){
        $sql = 'select * from `' . $this -> _tableArea . '` where parent_id='.$areaID.' and area_id <> 3984 and area_id <> 3983 and area_id <> 3982';
        return $this->_db->fetchAll($sql);
    }
    
    /**
     * 取得地区名称
     *
     * @param    int    $areaID
     * @return   array
     */
    public function getArea($areaID){
        $sql = 'select * from `' . $this->_tableArea . '` where area_id=' . $areaID;
        return $this->_db->fetchRow($sql);
    }

	/**
     * 取地区名
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaName($areaID)
    {
        return $this -> _db -> fetchOne("select area_name from `{$this->_tableArea}` where area_id={$areaID}");
    }
	/**
     * 根据用户id获取用户信息
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getMemberByuid($uid)
    {
        return $this -> _db -> fetchRow("select user_id,email,ischecked,nick_name,photo from `{$this -> _table}` where user_id={$uid}");
    }
	

    
    /**
     * 根据Email取得会员信息
     *
     * @param    string   $email
     * @return   array
     */
	public function getMemberByEmail($email)
	{
		$where = " WHERE A.user_name='" . $email . "' OR B.email='" . $email . "'";
		
		$sql = 'SELECT A.*, B.* FROM `' . $this -> _tableUser . '` AS A INNER JOIN `' . $this -> _table . '` as B ON A.user_id=B.user_id ' . $where . " LIMIT 1";
		return $this -> _db -> fetchRow($sql);
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
		$oldMoney = $this -> _db -> fetchOne('SELECT money FROM ' . $this -> _table . ' WHERE member_id=' . $id);
		$money += $oldMoney;
		$set = array('money' => $money);
		$where = $this -> _db -> quoteInto('member_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
	/**
     * 邮箱激活成功激活用户
     *
     * @param    int    $id
     *
     * 
     */
	public function emailchecked($uid)
	{
		$set = array('ischecked' => 1);
		$where = $this -> _db -> quoteInto('user_id = ?', $uid);
		return $this -> _db -> update($this -> _table, $set, $where);
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
		$user = $this -> _db -> fetchRow('SELECT point,rank_id FROM ' . $this -> _table . ' WHERE member_id=' . $id);
		$newPoint = $point + $user['point'];
		$set = array('point' => $newPoint);
		if($point > 0){
			$updatetime = time();
			$set['point_update_time'] = $updatetime;
			$user['rank_id'] > 1 && $set['rank_update_time'] = $updatetime;
		}
		$where = $this -> _db -> quoteInto('member_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
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

		$sql = "UPDATE `{$this->_table}` SET experience = experience + {$experience} WHERE member_id = '{$member_id}'";

		return (bool) $this->_db->execute($sql);
	}
	/**
     * 取配送公司列表
     *
     * @param    array    $where
     * @return   array
     */
    public function getLogistic($where)
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
            return $this -> _db -> fetchRow('select * from ' . $this -> _tableLogistic . $whereSql);
        } else {
            return false;
        }
    }

	/**
     * 用户礼品卡信息
     *
     * @param    void
     * @return   array
     */
	public function getGiftCard($user_id, $page, $pageSize)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
        $whereSql="  where user_id= ".$user_id;
        $sqlcount = "SELECT count(*) from ".$this->_tableGiftCard.$whereSql ;
        $sql = "SELECT * from ".$this->_tableGiftCard.$whereSql ;
        $info  = $this -> _db -> fetchAll($sql);
        $total = $this -> _db -> fetchOne($sqlcount);
		return array('info' => $info, 'total' => $total);

	}
	
	
	/**
	 * 用户礼品卡信息
	 *
	 * @param    void
	 * @return   array
	 */
	public function getGiftCardList($where='', $page, $pageSize)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page!=null) {
			$offset = ($page-1)*$pageSize;
			$limit = " LIMIT $pageSize OFFSET $offset";
		}

		$sqlwhere = 'WHERE 1=1 ';
		if($where)
		{
		   if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
			    	if (is_array($value)) {	
			    		$exkey = key($value);
			    		$comparison = array('eq'=>'=','neq'=>'<>','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE','in'=>'IN','notin'=>'NOT IN');
			    		$exp = $comparison[$exkey];
			    		$exVal = current($value);		    		    		
			    		$sqlwhere .= " AND $key $exp '$exVal'";
			    	}else{
				        $sqlwhere .= " AND $key='$value'";
			    	}
			    }
			}
		}		
		$sqlcount = "SELECT count(*) FROM {$this->_tableGiftCard}  ".$sqlwhere ;
		$sql = "SELECT * FROM {$this->_tableGiftCard}  ".$sqlwhere ;
		
		$info  = $this -> _db -> fetchAll($sql);
		$total = $this -> _db -> fetchOne($sqlcount);
		return array('info' => $info, 'total' => $total);
	
	}

	/**
     * 用户礼品卡历史信息
     *
     * @param    void
     * @return   array
     */
	public function getGiftCardLog($where='', $page, $pageSize)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		$offset = ($page-1)*$pageSize;
		$limit = " LIMIT $pageSize OFFSET $offset";
		$sqlwhere = ' WHERE 1=1  ';
		if($where)
		{
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
				{
					if (is_array($value)) {
						$exkey = key($value);
						$comparison = array('eq'=>'=','neq'=>'<>','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE','in'=>'IN','notin'=>'NOT IN');
						$exp = $comparison[$exkey];
						$exVal = current($value);
						$sqlwhere .= " AND $key $exp '$exVal'";
					}else{
						$sqlwhere .= " AND $key='$value'";
					}
				}
			}
		}
		
        $logsqlcount = "SELECT count(*) from ".$this->_tableGiftCardLog.$sqlwhere ;
        $logsql = "SELECT * from ".$this->_tableGiftCardLog.$sqlwhere.' ORDER BY add_time DESC'.$limit ;
        
        $logInfo  = $this -> _db -> fetchAll($logsql);
        $logTotal = $this -> _db -> fetchOne($logsqlcount);
		return array('logInfo' => $logInfo, 'logTotal' => $logTotal);

	}
	
	/**
     * 用户礼金券信息
     *
     * @param    void
     * @return   array
     */
	public function getCoupon($where, $page, $pageSize)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		
		if ($where) {
			$whereSql = ' WHERE 1=1 ';
			
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='".$value."'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		
		$sql = 'SELECT A.card_price, A.card_sn, A.card_pwd, A.status, A.add_time, B.card_type,B.card_price as coupon_price,B.start_date,B.end_date,B.min_amount,B.goods_info FROM `' . $this -> _couponTable . '` AS A LEFT JOIN `' . $this -> _couponLogTable . '` AS B ON A.log_id=B.log_id ' . $whereSql . ' ORDER BY A.status ASC, A.add_time DESC' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 用户礼金券数量
     *
     * @param    string   $where
     * @return   array
     */
	public function getCouponCount($where = null)
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
		
		$sql = 'SELECT COUNT(1) as count FROM `' . $this -> _couponTable . '` AS A LEFT JOIN `' . $this -> _couponLogTable . '` AS B ON A.log_id=B.log_id' . $whereSql;
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 用户礼金券信息
     *
     * @param    $userId    int
     * @return   array
     */
	public function getActiveCoupon($userId)
	{
		$sql = "SELECT A.card_sn FROM `" . $this -> _couponTable . "` AS A LEFT JOIN `" . $this -> _couponLogTable . "` AS B ON A.log_id=B.log_id WHERE A.user_id='" . $userId . "' AND A.status=0 AND A.is_repeat=0 AND B.status=0 AND B.end_date > '".date('Y-m-d')."'";
		return $this -> _db -> fetchOne($sql);
	}
	/**
     * 暂存架列表
     *
     * @param    void
     * @return   array
     */
	public function getFavorite($user_id, $page, $pageSize)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
        $sqlcount = "SELECT count(*) from {$this->_memberFavoriteTable} a,{$this->_goodsTable} b where user_id={$user_id} and a.goods_id=b.goods_id ";
        $sql = "SELECT b.goods_name,b.price,b.goods_img,b.goods_sn,from_unixtime(a.add_time, '%Y-%m-%d %H:%i:%s' ) as add_time,a.goods_id,a.favorite_id from {$this->_memberFavoriteTable} a,{$this->_goodsTable} b where user_id={$user_id} and a.goods_id=b.goods_id order by add_time desc {$limit}";
        $info  = $this -> _db -> fetchAll($sql);
        $total = $this -> _db -> fetchOne($sqlcount);
		return array('info' => $info, 'total' => $total);

	}
	/**
     * 取邮编
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaZip($areaID)
    {
        return $this -> _db -> fetchOne("select zip from `{$this->_tableArea}` where area_id={$areaID}");
    }
	/**
	 * 得到有效的优惠券数量
	 * 
	 * @param array $where
	 */
	public function getValidCoupon($where) {
	    $currentDate = date( 'Y-m-d', time() );
		$sql = "SELECT count(1) as count FROM {$this->_couponTable} as t1 left join {$this->_couponLogTable} as t2 on t1.log_id = t2.log_id where t1.user_id ={$where['user_id']} and ( (t1.is_repeat=0 and t1.status=0) or (t1.is_repeat=1) ) and t2.end_date >= '{$currentDate}'";
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 得到我的问答
     * 
     * @param array $search
     * @param string $fields
     * @param int $page
     * @param int $pageSize
     * 
     * @return array
     */
	public function getMsg($search=null, $fields='*', $page=null, $pageSize=null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page!=null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		$where = ' where 1 and ';
		if($search){
			$search['user_id'] && $where .= "user_id={$search['user_id']}";
		}
        $sqlcount = "SELECT count(*) from {$this->_msg} $where";
        $sql = "SELECT $fields from {$this->_msg} $where {$limit}";
        $info  = $this -> _db -> fetchAll($sql);
        $total = $this -> _db -> fetchOne($sqlcount);
		return array('datas' => $info, 'tot' => $total);
	}
	
	/**
     * 更新某张表
     * 
     * @param string $table
     * @param array $set
     * @param string $where
     */
    public function updateTable($table=null, $set=array(), $where=null) {
    	$table = $this -> _table;
    	if($table == null){return null;} $table = trim($table); if($table == ''){return false;}
    	if($where == null){return null;} $where = trim($where); if($where == ''){return false;}
    	if(!is_array($set)){return false;}
    	if(empty($set)){return false;}
    	$this -> _db -> update($table, $set, $where);
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
	
	/**
	 * 删除用户收藏
	 * @param string $favorite_ids
	 * @param int $user_id
	 */
	public function delFavourites($favorite_ids,$user_id)
	{
		return $this -> _db -> delete($this -> _memberFavoriteTable, " favorite_id in ({$favorite_ids}) and user_id={$user_id} ");
	}
}