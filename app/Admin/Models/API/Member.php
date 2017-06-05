<?php

class Admin_Models_API_Member 
{
	/**
     * 会员管理 DB
     * 
     * @var Admin_Models_DB_Member
     */
	private $_db = null;
	
	/**
     * 商品分类 DB
     * 
     * @var Admin_Models_DB_Category
     */
	private $_cateDb = null;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Member();
		$this -> _addressDb = new Admin_Models_DB_MemberAddress();
		$this -> _logistic = new Admin_Models_API_Logistic();
	}
	
	/**
     * 根据搜索条件取得会员信息
     *
     * @param    array  $search
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getMemberBySearch($search, $page = null, $pageSize = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
		if ($search != null) {
			($search['reg_fromdate']) ? $where = " and A.add_time >=" . strtotime($search['reg_fromdate']) : "";
			($search['reg_todate']) ? $where .= " and A.add_time <" . (strtotime($search['reg_todate'])+86400) : "";
			($search['log_fromdate']) ? $where .= " and A.last_login >=" . strtotime($search['log_fromdate']) : "";
			($search['log_todate']) ? $where .= " and A.last_login <" . (strtotime($search['log_todate'])+86400) : "";
			($search['rank_id']) ? $where .= " and B.rank_id =" . $search['rank_id'] : "";
			($search['user_name']) ? $where .= " and (A.user_name LIKE '%" . $search['user_name'] . "%')" : "";
			($search['pointfrom']) ? $where .= " and B.point >=" . $search['pointfrom'] : "";
			($search['pointto']) ? $where .= " and B.point <=" .$search['pointto'] : "";
			($search['moneyfrom']) ? $where .= " and B.money >=" .$search['moneyfrom'] : "";
			($search['moneyto']) ? $where .= " and B.money <=" .$search['moneyto'] : "";
            ($search['experiencefrom']) ? $where .= " and B.experience >=" .$search['experiencefrom'] : "";
			($search['experienceto']) ? $where .= " and B.experience <=" .$search['experienceto'] : "";
		}
        
		return $this -> _db -> getMember($where, $page, $pageSize);
	}
	
	/**
     * 根据用户ID取得会员信息
     *
     * @param    array  $user_id_array
     * @return   array
     */
	public function getMemberByUserIDS($user_id_array)
	{
	    if ( !$user_id_array || count($user_id_array) == 0 )    return false;
	    
	    return $this -> _db -> getMember(" and A.user_id in (".implode(',', $user_id_array).")", $page, $pageSize);
	}
	
	/**
     * 取得指定user_id的会员信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getMemberByUserId($id)
	{
		$member = $this -> _db -> getMember(array('A.user_id' => $id));
		
		if (is_array($member)) {
			return array_shift($member);
		}
	}
	
	/**
     * 取得指定member_id的会员信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getMemberByMemberId($id)
	{
		$member = $this -> _db -> getMember(array('B.member_id' => $id));
		
		if (is_array($member)) {
			return array_shift($member);
		}
	}
	
	/**
     * 取得指定用户名的会员信息
     *
     * @param    string    $username
     * @return   array
     */
	public function getMemberByUserName($username)
	{
		$member = $this -> _db -> getMember(array('A.user_name' => $username), null, null);
		
		if (is_array($member)) {
			return array_shift($member);
		}
	}
	
	/**
     * 根据搜索条件取得会员人数
     *
     * @param    string   $search
     * @return   int
     */
	public function getMemberCountBySearch($search)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
		if ($search != null ) {
			($search['reg_fromdate']) ? $where = " and A.add_time >=" . strtotime($search['reg_fromdate']) : "";
			($search['reg_todate']) ? $where .= " and A.add_time <" . (strtotime($search['reg_todate'])+86400) : "";
			($search['log_fromdate']) ? $where .= " and A.last_login >=" . strtotime($search['log_fromdate']) : "";
			($search['log_todate']) ? $where .= " and A.last_login <" . (strtotime($search['log_todate'])+86400) : "";
			($search['rank_id']) ? $where .= " and B.rank_id =" . $search['rank_id'] : "";
			($search['user_name']) ? $where .= " and A.user_name LIKE '%" . $search['user_name'] . "%' OR B.nick_name LIKE '%" . $search['user_name'] . "%'" : "";
			($search['pointfrom']) ? $where .= " and B.point >=" . $search['pointfrom'] : "";
			($search['pointto']) ? $where .= " and B.point <=" .$search['pointto'] : "";
			($search['moneyfrom']) ? $where .= " and B.money >=" .$search['moneyfrom'] : "";
			($search['moneyto']) ? $where .= " and B.money <=" .$search['moneyto'] : "";
            ($search['experiencefrom']) ? $where .= " and B.experience >=" .$search['experiencefrom'] : "";
			($search['experienceto']) ? $where .= " and B.experience <=" .$search['experienceto'] : "";

		}
		
		return $this -> _db -> getMemberCount($where);
	}
	
	/**
     * 取得会员状态显示代码
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	
	/**
     * 添加/编辑会员
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function editMember($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($data['user_name'] == '') {
			return 'noUserName';
		}
		
		if ($data['password'] != '' && $data['confirm_password'] != '') {
		    if ($data['password'] != $data['confirm_password']) {
		        return 'noSamePassword';
			}
		} elseif ($id === null || ($data['password'] != '' || $data['confirm_password'] != '')) {
			return 'noPassword';
		}
		
		if ($data['password'] !='') {
		    $data['password'] = Custom_Model_Encryption::getInstance() -> encrypt($data['password']);
		}
		
		if ($data['birthday']) {
			$data['birthday'] = implode('-', $data['birthday']);
		}
		
		if ($data['address']) {
			foreach ($data['address'] as $adressKey => $adressValue)
		    {
		        foreach ($adressValue as $key => $value)
		        {
		    	    $address[$key][$adressKey] = $value;
		        }
		    }
		}
		
		if ($data['address_number'] && count($address) > (int)$data['address_number']) {
			return 'tooManyAddress';
		}
		
		$data['add_time'] = time();
		$member = $this -> getMemberByUserName($data['user_name']);
		
		if ($id == null) {
			if ($member) {
			    return 'memberExists';
		    }
		    $data['nick_name'] = $data['user_name']; //后台添加的用户，昵称都跟用户名相同
		    $result = $this -> _db -> addMember($data);
		    
		    if ($result > 0) {
		    	if ($data['address']) {
		    	    foreach ($address as $key => $value)
		    	    {
		    		    if ($value['consignee'] && $value['province'] && $value['address']) {
		    			    $value['member_id'] = $result;
		    		        $value['add_time'] = $data['add_time'];
		    		        $this -> _addressDb -> addAddress($value);
		    		    }
		    	    }
		    	}
		    }
		} else {
            $id = intval($id);
			$data['update_time'] = time();
			
			if ($member && $member['user_id'] != $id) {
			    return 'memberExists';
		    }
			$exists = $this -> getMemberByUserId($id);
			if (!$exists) {
			    return 'memberNoExists';
		    }
			$result = $this -> _db -> updateMember($data, $id);
			
			if (!$data['address']) {
				$this -> _addressDb -> deleteAddressByMemberId(array('member_id' => $member['member_id']));
			} else {
				$orgAddress = $this -> _addressDb -> getAddress(array('member_id' => $member['member_id']));
			    if ($orgAddress) {
				
				    foreach ($orgAddress as $key => $value)
			        {
				        $orgAddressId[] = $value['address_id'];
			        }
			    
			        $delAddressId = array_diff($orgAddressId, $data['address']['address_id']);
			    
			        if ($delAddressId) {
			    	
				        foreach ($delAddressId as $value)
				        {
					        $this -> _addressDb -> deleteAddress(array('address_id' => $value));
				        }
			        }
			    }
			    foreach ($address as $key => $value)
		        {
		    	    $value['member_id'] = $member['member_id'];
		    	    $value['add_time'] = $data['add_time'];
		    	
		    	    if ($value['consignee'] && $value['province'] && $value['address']) {
		    		
		    		    if ($value['address_id']) {
		    		        $this -> _addressDb -> updateAddress($value, $value['address_id']);
		    	        } else {
		    		        $this -> _addressDb -> addAddress($value);
		    	        }
		    	    }
		        }

			}
		}
		
		if (is_numeric($result) && $result >= 0) {
		    return ($id === null) ? 'addMemberSucess' : 'editMemberSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 删除会员
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteMemberByUserId($id)
	{
		if ($id) {
			//$id = explode(',', $id);
		    $result = $this -> _db -> deleteMember($id);
		    
		    if (is_numeric($result) && $result > 0) {
		        return 'deleteMemberSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	/**
     * 更改会员状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		$id = (int)$id;
		if ($id > 0) {
			
		    if($this -> _db -> updateStatus($id, $status) <= 0) {
			    exit('failure');
		    }
		}
	}
	/**
     * 取得配送地区ID的子配送地区信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getChildAreaById($id = null)
	{
		if (!is_numeric($id)) {
			return;
		}
		
		$area = $this -> _logistic -> getAreaListByID($id);
		
		if ($area) {
			
			foreach ($area as $key => $value)
		    {
			    $result[$value['area_id']] = $value['area_name'];
		    }
		}
		return $result;
	}
	
	/**
     * 取得指定会员ID的会员送货地址信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getAddressByMemberId($id)
	{
		$address = $this -> _addressDb -> getAddress(array('member_id' => $id));
		if (is_array($address)) {
			
			foreach ($address as $key => $value)
		    {
			    $address[$key]['province_msg'] = ($value['province_id']) ? $this -> _logistic -> getAreaByID($value['province_id']) : '';
			    $address[$key]['city_option'] = ($value['province_id']) ? $this -> getChildAreaById($value['province_id']) : '';
			    $address[$key]['city_msg'] = ($value['city_id']) ? $this -> _logistic -> getAreaByID($value['city_id']) : '';
			    $address[$key]['area_option'] = ($value['city_id']) ? $this -> getChildAreaById($value['city_id']) : '';
			    $address[$key]['area_msg'] = ($value['area_id']) ? $this -> _logistic -> getAreaByID($value['area_id']) : '';
		    }
		    
		    return $address;
		}
	}
	/**
     * 取得账户及积分信息
     *
     * @param    int      $id
     * @param    string   $type
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getAccount($id, $type, $page=null, $pageSize = null)
	{
		if ($type && $id > 0) {
			$member = $this -> getMemberByMemberId($id);
			switch ($type) {
			    case 'money':
			        $moneyDb = new Admin_Models_DB_MemberMoney();
			        $result = $moneyDb -> getMoney(array('A.member_id' => $id), $page, $pageSize);
			        break;
			    case 'point':
			        $pointDb = new Admin_Models_DB_MemberPoint();
			        $result = $pointDb -> getPoint(array('A.member_id' => $id), $page, $pageSize);
			        break;
				case 'experience':
					$pointDb = new Admin_Models_DB_MemberPoint();
			        $result = $pointDb->getExperience(array('A.member_id' => $id), $page, $pageSize);
					break;
			}
			if ($result) {
				//$result[0]['totalValue'] = $member[$type];
				foreach ($result as $key => $value)
				{
					$result[$key]['id'] = $result[$key][$type . '_id'];
					$result[$key]['totalValue'] = $result[$key][$type . '_total'];
					$result[$key]['value'] = ($result[$key][$type] > 0) ? '+' . $result[$key][$type] : $result[$key][$type];
				}
				
				return $result;
			}
		}
	}
	
	/**
     * 取得账户及积分个数
     *
     * @param    int      $id
     * @param    string   $type
     * @return   array
     */
	public function getAccountCount($id, $type)
	{
		if ($type && $id > 0) {
			switch ($type) {
			    case 'money':
			        $moneyDb = new Admin_Models_DB_MemberMoney();
			        return $moneyDb -> getMoneyCount(array('A.member_id' => $id));
			        break;
			    case 'point':
			        $pointDb = new Admin_Models_DB_MemberPoint();
			        return $pointDb -> getPointCount(array('A.member_id' => $id));
			        break;
			}
		}
	}
	
	/**
     * 编辑账户及积分信息
     *
     * @param    int      $id
     * @param    string   $type
     * @param    array    $data
     * @return   array
     */
	public function editAccount($id, $type, $data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);

		$adminCertification = Admin_Models_API_Auth :: getInstance() -> getAuth();;
		$data['admin_id'] = $adminCertification['admin_id'];
		$data['admin_name'] = $adminCertification['admin_name'];
		$member = $this -> getMemberByMemberId($id);
		$data['user_name'] = $member['user_name'];
		$data['member_id'] = $id;
		$data['add_time'] = time();
		if ($type && $id > 0 && $data['accountValue'] && $data['note']) {
			switch ($type) {
			    case 'money':
			        $moneyDb = new Admin_Models_DB_MemberMoney();
			        $data['money'] = ($data['accountType'] == '1') ? $data['accountValue'] : -$data['accountValue'];
			        $data['money_total'] = $data['accountTotalValue'];
			        $data['money_type'] = 0;
			        
			        $updateMember = $this -> _db -> updateMoney($id, $data['money']);
			        
			        if ($updateMember > 0) {
			        	$memberMoneyId = $moneyDb -> addMoney($data);
			        }
			        
			        if ($memberMoneyId > 0) {
			        	return true;
			        } else {
			        	$restoreMember = $this -> _db -> updateMoney($id, -$data['money']);
			        	return false;
			        }
			        break;
			    case 'point':
			        $pointDb = new Admin_Models_DB_MemberPoint();
			        $data['point'] = ($data['accountType'] == '1') ? $data['accountValue'] : -$data['accountValue'];
			        $data['point_total'] = $data['accountTotalValue'];
			        $data['point_type'] = 0;
			        $updatePoint = $this -> _db -> updatePoint($id, $data['point']);
			        if ($updatePoint >= 0) {
			        	$memberPointId = $pointDb -> addPoint($data);
			        }
			        
			        if ($memberPointId > 0) {
			        	return true;
			        } else {
			        	$restoreMember = $this -> _db -> updatePoint($id, -$data['point']);
			        	return false;
			        }
			        
			        break;
		    }
		}

		if ($type == 'experience') {

			$data['experience'] = ($data['accountType'] == '1') ? $data['accountValue'] : -$data['accountValue'];
			$experience_param = array (
			    'member_id'        => $data['member_id'],
			    'experience'       => $data['experience'],
			    'experience_total' => $data['accountTotalValue'] + $data['experience'],
			    'remark'           => $data['note'],
			    'created_by'       => $data['admin_name'],
			);

			$pointDb = new Admin_Models_DB_MemberPoint();
			$update_experience = $this->_db->updateExperience($id, $data['experience']);
			$member_experience_id = 0;
			if (true === $update_experience) {
				$member_experience_id = $pointDb->addExperience($experience_param);
			}

			if ($member_experience_id == 0) {
				$this->_db->updateExperience($id, -$data['experience']);
				return false;
			}

			// 添加最后获取经验值时间并更新等级

			$this->updateMemberInfoByMemberId($data['member_id'], array('last_experience_time' => date('Y-m-d H:i:s')));

			$this->changeMemberRank($data['member_id']);
		}
		return true;
	}

	/**
     * 取得账户余额信息
     *
     * @param    void
     * @return   array
     */
	public function getAllMoney($search,$page, $pageSize)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
        if ($search != null ) {
			($search['fromdate']) ? $where = " and A.add_time >=" . strtotime($search['fromdate']) : "";
			($search['todate']) ? $where .= " and A.add_time <" . (strtotime($search['todate'])+86400) : "";
			($search['nick_name']) ? $where .= " and ( B.nick_name LIKE '%" . $search['nick_name'] . "%')" : "";

			if($search['fromdate'] == null && $search['todate'] == null ) {
					 $where .= "  and  A.add_time >  ". time()."-30*86400"." and  A.add_time <  " .time();
				}
		}
		$moneyDb = new Admin_Models_DB_MemberMoney();
		$money = $moneyDb -> getMoney($where, $page, $pageSize);
		$total = $moneyDb -> getMoneyCount($where);
		return array('money' => $money, 'total' => $total);
	}
	/**
     * 取得积分信息
     *
     * @param    void
     * @return   array
     */
	public function getAllPoint($search,$page, $pageSize)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
        if ($search != null ) {
			($search['fromdate']) ? $where = " and A.add_time >=" . strtotime($search['fromdate']) : "";
			($search['todate']) ? $where .= " and A.add_time <" . (strtotime($search['todate'])+86400) : "";
			($search['nick_name']) ? $where .= " and (  B.nick_name LIKE '%" . $search['nick_name'] . "%')" : "";
			if($search['fromdate'] == null && $search['todate'] == null ) {
					 $where .= "  and  A.add_time >  ". time()."-30*86400"." and  A.add_time <  " .time();
				}
		} 

		$pointDb = new Admin_Models_DB_MemberPoint();
		$point = $pointDb -> getPoint($where, $page, $pageSize);
		$total = $pointDb -> getPointCount($where);
		return array('point' => $point, 'total' => $total);
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
		$point_db = new Admin_Models_DB_MemberPoint();
		$count = $point_db->getExperienceCount($params);

		if (false === $count) {
			$this->_error = $point_db->getError();
			return false;
		}

		return $count;
	}

	/**
     * 获取信息列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function getExperienceList($params, $limit)
	 {	
		$point_db = new Admin_Models_DB_MemberPoint();
		$infos = $point_db->getExperienceList($params, $limit);

		if (false === $infos) {
			$this->_error = $point_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		return $infos;
	 }

	/**
     * 取得积分变动频率信息
     *
     * @param    void
     * @return   array
     */
	public function getPointFrequency($search,$page = null, $pageSize = null)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
        if ($search != null ) {
			($search['fromdate']) ? $where = " and A.add_time >=" . strtotime($search['fromdate']) : "";
			($search['todate']) ? $where .= " and A.add_time <" . (strtotime($search['todate'])+86400) : "";
			if($search['fromdate'] == null && $search['todate'] == null ) {
					 $where .= "  and  A.add_time >  ". time()."-30*86400"." and  A.add_time <  " .time();
		    }
		    ($search['batch_sn']) ? $where = " and A.batch_sn = '{$search['batch_sn']}'" : "";
		}
		$pointDb = new Admin_Models_DB_MemberPoint();
		return  $pointDb -> getPointFrequency($where, $page, $pageSize);
	}
	/**
     * 导出用户收货地址手机号码
     *
     * @return void
     */
    public function exportmobile()
    {
		return  $this -> _addressDb ->getexportAddress(null);
	}

	/**
     * 设置券
     *
     * @return void
     */
    public function setCoupon($card_sn)
    {
		$this -> _couponDb = new Admin_Models_DB_Coupon();
		return  $this -> _couponDb ->setCoupon($card_sn);
	}


     /**
     * 导出用户信息数据
     *
     * @param    array    $search
     * @return   void
     */
	public function getExportUser($search)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());      
        $search = Custom_Model_Filter::filterArray($search, $filterChain);

		if ($search != null) {
			($search['reg_fromdate']) ? $where = " and A.add_time >=" . strtotime($search['reg_fromdate']) : "";
			($search['reg_todate']) ? $where .= " and A.add_time <" . (strtotime($search['reg_todate'])+86400) : "";
			($search['log_fromdate']) ? $where .= " and A.last_login >=" . strtotime($search['log_fromdate']) : "";
			($search['log_todate']) ? $where .= " and A.last_login <" . (strtotime($search['log_todate'])+86400) : "";
			($search['rank_id']) ? $where .= " and B.rank_id =" . $search['rank_id'] : "";
			($search['user_name']) ? $where .= " and (A.user_name LIKE '%" . $search['user_name'] . "%')" : "";
			($search['pointfrom']) ? $where = " and B.point >=" . $search['pointfrom'] : "";
			($search['pointto']) ? $where = " and B.point <=" .$search['pointto'] : "";
			($search['moneyfrom']) ? $where = " and B.money >=" .$search['moneyfrom'] : "";
			($search['moneyto']) ? $where = " and B.money <=" .$search['moneyto'] : "";
		}
		$datas = $this -> _db -> getExportUser($where);
		$excel = new Custom_Model_Excel();
		$excel -> send_header($search['reg_fromdate'] .'--'.$search['reg_todate']. '用户信息.xls');
		$excel -> xls_BOF();
		$title = array('用户ID', '用户名','积分','余额','推荐ID','推荐人', '注册时间', '电话', '手机', 'email');
        $col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
		foreach ($datas as $k => $v)
        {
			$v['add_time'] = date('Y-m-d', $v['add_time']);
			$v['last_login'] = date('Y-m-d', $v['last_login']);
			$row = array($v['user_id'], 
			             $v['user_name'], 
					     $v['point'], 
						 $v['money'], 
						 $v['parent_id'], 
						 $v['un_name'], 
			             $v['add_time'], 
			             $v['office_phone'].'-'.$v['home_phone'], 
				         $v['mobile'], 
                         $v['email']);
			for ($i = 0; $i < $col; $i++) {
			    $excel -> xls_write_label($k+1, $i, $row[$i]);
			}
			flush();
		    ob_flush();
			unset($row);
        }
        unset($datas);
		$excel -> xls_EOF();
	}

	/**
     * 会员礼金券信息
     *
     * @param    void
     * @return   array
     */
	public function getCouponList($search,$page, $pageSize)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
        if ($search != null ) {
			($search['user_name']) ? $where .= " AND A.user_name LIKE '%" . $search['user_name'] . "%' " : "";
            (($search['card_type']!==null) &&($search['card_type']!=='')) ? $where .= " AND A.card_type = {$search['card_type']}" : "";
            if ($search['status']) {
            	if ($search['status'] == 1) {
            		$time = date('Y-m-d', time());
            		$where .= " AND B.end_date >= '{$time}' AND A.status = 0 ";
            	}
            	else if ($search['status'] == 2) {
            		$where .= " AND A.status = 1 ";
            	}
                else if ($search['status'] == 3) {
                	$time = date('Y-m-d', time());
                    $where .= " AND B.end_date < '{$time}' ";
                }
            }
		} 

		$couponDb = new Admin_Models_DB_Coupon();
		return $couponDb -> getCouponList($where, $page, $pageSize);
	}

	/**
     * 会员礼品卡信息
     *
     * @param    void
     * @return   array
     */
	public function getGiftCardList($search,$page, $pageSize)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
        if ($search != null ) {
			($search['user_name']) ? $where .= " AND user_name LIKE '%" . $search['user_name'] . "%' " : "";
			($search['card_sn']) ? $where .= " AND card_sn LIKE '%" . $search['card_sn'] . "%' " : "";
			if (isset($search['username!='])) {
			    $where .= " AND user_name <> '{$search['username!=']}'";
			    if (!$search['username!=']) {
			        $where .= " AND user_name is not null";
			    }
			}
		} 
		$giftCardDb = new Admin_Models_DB_GiftCard();
		return $giftCardDb -> getGiftCardList($where, $page, $pageSize);
	}
	
	/**
     * 通过优惠券获得订单信息
     *
     * @param    array    $where
     * @return   array
     */
	function getOrderBatchGoodsByCardSN($cardSN, $cardType = 'coupon')
	{
	    return $this -> _db -> getOrderBatchGoodsByCardSN($cardSN, $cardType);
	}

	/**
	 * 更改会员等级
	 *
	 * @param    int
	 *
	 * @return   boolean
	 **/
	public function changeMemberRank($member_id)
	{
		if (intval($member_id) < 1) {
			$this->_error = '会员ID不正确';
			return false;
		}

		$member_info = $this->_db->getMemberInfoByMemberId($member_id);

		if (empty($member_info)) {
			$this->_error = '没有相关会员信息';
			return false;
		}

		$rank_info  = $this->_db->getRankInfoByExperience($member_info['experience']);

		if (empty($rank_info)) {
			return true;
		}

		if ($member_info['rank_id'] != $rank_info['rank_id']) {
			$this->_db->updateMemberInfoByMemberId($member_info['member_id'], array('rank_id' => $rank_info['rank_id']));
			$log_params = array(
				'member_id'    => $member_info['member_id'],
				'last_rank_id' => $member_info['rank_id'],
				'rank_id'      => $rank_info['rank_id'],
				'remark'       => '会员等级升级',

			);
			$this->_db->addMemberRankLog($log_params);
		}

		return true;
	}

	/**
	 * 更改会员信息
	 *
	 * @param    int
	 *
	 * @return   boolean
	 **/
	public function updateMemberInfoByMemberId($member_id, $params)
	{
		return (bool) $this->_db->updateMemberInfoByMemberId($member_id, $params);
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