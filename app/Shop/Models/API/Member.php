<?php
define('POINT_AUTH_EMAIL_TYPE', 11); //邮箱验证 积分类型
 class Shop_Models_API_Member
 {
 	/**
     * 会员帐户 DB
     * 
     * @var Shop_Models_DB_Member
     */
	protected $_db = null;
	
	/**
     * 订单 DB
     * 
     * @var Shop_Models_DB_Order
     */
	protected $_orderDb = null;
	
	/**
     * 配送地址 DB
     * 
     * @var Shop_Models_DB_MemberAddress
     */
	protected $_addressDb = null;
	
	/**
     * 会员等级 API
     * 
     * @var Shop_Models_API_MemberRank
     */
	protected $_memberRank = null;
	
	/**
     * 会员认证信息
     * 
     * @var array
     */
	protected $_auth = null;
	
	/**
     * 订单状态
     * 
     * @var array
     */
	protected $_status = array('正常单', '取消单', '无效单');
	
	/**
     * 订单配送状态
     * 
     * @var array
     */
	protected $_statusLogistic = array('待发货', '待发货', '待发货', '待收货', '已签收', '已拒收');
	/**
     * 订单支付状态
     * 
     * @var array
     */
	protected $_statusPay = array('未付款', '未退款', '已结清');
	
 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
        $this -> _db = new Shop_Models_DB_Member();
        $this -> _orderDb = new Shop_Models_DB_Order();
        $this -> _auth = $this -> getUser();
    }
    
    /**
     * 普通会员认证
     *
     * @param    string    $username
     * @param    string    $password
     * @param    string    $filter
     * @return   array
     */
    public function certification($username, $password, $filter = true)
    {
        if ($filter) {
            $filterChain = new Zend_Filter();
            $filterChain -> addFilter(new Zend_Filter_StringTrim())
                         -> addFilter(new Zend_Filter_StripTags());
            $username = $filterChain -> filter($username);
            $password = $filterChain -> filter($password);
        }
        if (empty($username)) {
        	return false;
        }
        $cert = $this -> _db -> certification($username, $password);
        if ($cert) {
        	$this -> getActiveCoupon($cert['user_id']) && $cert['haveCoupon'] = 1;
			$ipLocation = new Custom_Model_IpLocation();
		    $location = $ipLocation -> getlocation();
		  //  $this -> _db -> loginLog(array('user_id' => $cert['user_id'], 'user_name' => $cert['user_name'], 'member_id' => $cert['member_id'], 'login_time' => $cert['last_login'], 'login_ip' => $cert['last_login_ip'], 'area' => mb_convert_encoding($location['country'], 'UTF-8', 'GBK'), 'other' => mb_convert_encoding($location['area'], 'UTF-8', 'GBK')));        	
		    //获得是否是新人
		    if ($this -> getOrderByStatus0($cert['user_id'])) {
		        $cert['is_new_member'] = 0;
		    }
		    else    $cert['is_new_member'] = 1;
		    setcookie('is_new_member', $cert['is_new_member'], time() + 30 * 365, '/');
        }
        return $cert;
    }
    
     /**
     * 会员注册
     *
     * @param    array    $data
     * @return   array
     */
    public function register($data , $needFilter = true)
    {
        if ($needFilter) {
            $filterChain = new Zend_Filter();
            $filterChain -> addFilter(new Zend_Filter_StringTrim())
                         -> addFilter(new Zend_Filter_StripTags());
            
            $data = Custom_Model_Filter :: filterArray($data, $filterChain);
        }
        
        // 共享登录不检查昵称重复
        if (!$data['is_share'] && $data['nick_name']) {
            if ($this -> getMemberByNickName($data['nick_name'])) {
                return 'nickNameExists';
            }
        }
        
        $data['add_time'] = time();


        $data['discount'] = 1;
        if($data['tj_user_name'] && $data['tj_user_name'] != $data['user_name']) {
            if($result = $this -> getMemberByUserName($data['tj_user_name'])){
                $pinfo = array_shift($result);
                $data['tj_user_id'] = $pinfo['user_id'];
            }
        }

        if (isset($data['share_rank_id']) && isset($data['share_discount'])) { 
            $data['rank_id'] = $data['share_rank_id'];
            $data['discount'] = $data['share_discount'];
            unset($data['share_rank_id'],$data['share_discount']);
        } else {
            $data['rank_id'] = 1;  // 普通会员
            $data['discount'] = 1; 
        }
        $result = $this -> _db -> register($data);
        if (is_numeric($result) && $result > 0) {
            return 'addUserSucess';
        } else {
            return 'error';
        }
    }
    
    /**
     * 编辑个人信息
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function editMember($data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
		$profileMember=$this -> _auth;

		$authApi = new Shop_Models_API_Auth();
		if ($data['birthday']) {
			if( $profileMember['birthday']=='0000-00-00' || !$profileMember['birthday']){
					$data['birthday'] = implode('-', $data['birthday']);
			}else{
				return 'unable';
			}
		}

		if ($data['email'] && !Custom_Model_Check::isEmail($data['email'])) {
			return 'errorEmail';
		}elseif($authApi->check($data['email'],true)){
			return 'emailExist';
		}
		
		if ($data['mobile'] && !Custom_Model_Check::isMobile($data['mobile'])) {
			return 'errorMobile';
		}elseif($authApi->check($data['mobile'],true)){
			return 'mobileExist';
		}
		
		if ($data['msn'] && !Custom_Model_Check::isEmail($data['msn'])) {
			return 'errorMsn';
		}
		
		if ($data['qq'] && !Custom_Model_Check::isQq($data['qq'])) {
			return 'errorQq';
		}
		
		if ($data['office_phone'] && !Custom_Model_Check::isTel($data['office_phone'])) {
			return 'errorOfficePhone';
		}
		
		if ($data['home_phone'] && !Custom_Model_Check::isTel($data['home_phone'])) {
			return 'errorHomePhone';
		}
		//根据当前用户id查询用户原来的email地址,如果新的email和原来的不相等，则须重新验证
		
		$mem=$this->_db->getMemberByuid($this -> _auth['user_id']);
		
		//已验证邮箱  不能修改
		if($mem['ischecked']){
			unset($data['email']);
		}else{
			if(trim($data['email'])!=trim($mem['email'])){
				$data['ischecked']=0;
			}
		}
		
		$data['update_time'] = time();
		$result = $this -> _db -> updateMember($data, $this -> _auth['user_id']);

		if (is_numeric($result) && $result >= 0) {
		    return 'editMemberSucess';
		} else {
			return 'error';
		}
	}
	/**
	 * 更新头像
	 */
	public function upPhoto($photo){
	    $result = $this -> _db -> upPhoto($photo, $this -> _auth['user_id']);
	    if (is_numeric($result) && $result >= 0) {
	        return 'editMemberSucess';
	    } else {
	        return 'error';
	    }
	}
    /**
     * 更新会员等级
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function editMemberRank($data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());            
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
		$this -> _db -> updateMemberRank($data, $this -> _auth['user_id']);
	}
	 /**
     * 邮箱验证成功,激活用户
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function editchecked($uid)
	{
		$res  = $this -> _db -> emailchecked($uid);
		if ($res) {
			$pointDb = new Shop_Models_DB_MemberPoint();
			$point = 200;
			$auth = Shop_Models_API_Auth :: getInstance();
			$userInfo  = $auth->getAuth();		  
			$tmp = array('member_id' => $userInfo['member_id'],
					'user_name' => $userInfo['user_name'],
					'accountType' => 1,
					'accountValue' => $point,
					'accountTotalValue' => $userInfo['point'],
					'point_type'=>POINT_AUTH_EMAIL_TYPE, //邮箱验证送积分
					'note' => '邮箱激活送积分');
					
		   $num = $pointDb->getPointCount(array('member_id'=>$userInfo['member_id'],'point_type'=>POINT_AUTH_EMAIL_TYPE));
		   if(!$num) $this-> editAccount($userInfo['member_id'], 'point', $tmp);
		}			
	    return $res;	
	}
	/**
	 * 更新邮件
	 * @param unknown $email
	 */
	public function editEmail($email)
	{
		return  $this -> _db -> updateEmail($email, $this -> _auth['user_id']);
	}
    /**
     * 更新会员最后一次购买 的地址 和 支付方式
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function editMemberCartInfo($data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if (!$data['last_address_id']) {
            unset($data['last_address_id']);
        }
        if (!$data['last_pay_type']) {
            unset($data['last_pay_type']);
        }
        if (!$data['last_invoice']) {
        	unset($data['last_invoice']);
        }
        
        if ($data){
            $this -> _db -> updateMemberCartInfo($data, $this -> _auth['user_id']);
        }
	}
	
	/**
     * 修改密码
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function editPassword($data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());          
        $data = Custom_Model_Filter::filterArray($data, $filterChain);

		if ($data['password'] != '' && $data['confirm_password'] != '') {
		    if ($data['password'] != $data['confirm_password']) {
		        return 'noSamePassword';
			} elseif (!preg_match('/^[a-zA-Z0-9]{6,20}$/i', $data['password'])) {
				return 'errorPassword';
		    }
		} else {
			return 'noPassword';
		}
		
		$member = Shop_Models_API_Auth :: getInstance() -> getAuth();
		$tenpaypos = strpos($member['nick_name'], 'QQ:');
		$alipaypos = strpos($member['nick_name'], '来自支付宝：');
		if( ( !($tenpaypos === false) || !($alipaypos === false ) || !($xiaoipos === false) ) && $member['password'] ==  md5(substr(md5($member['user_name']), 0, 16)) ){
			$member['setPwd']= '1';
		};

		if (!$member['setPwd']) {
			if ($member['password'] != Custom_Model_Encryption::getInstance() -> encrypt($data['old_password'])) {
			    return 'errorOldPassword';
		    }
		}
		$data['password'] = Custom_Model_Encryption::getInstance() -> encrypt($data['password']);
		$data['update_time'] = time();
		$result = $this -> _db -> updatePassword($data, $this -> _auth['user_id']);
		if (is_numeric($result) && $result >= 0) {
		    return 'editPasswordSucess';
		} else {
			return 'error';
		}
	}
    
    /**
     * 根据ID获取会员信息
     *
     * @param    void
     * @return   array
     */
    public function getUser()
    {
        $auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
        if ($auth['member_id']) {
        	$r = array_shift($this -> _db -> getMember(array('B.member_id' => $auth['member_id'])));
        	
		    if ($this -> getOrderByStatus0($auth['user_id'])) {
		        $r['is_new_member'] = 0;
		    }
		    else    $r['is_new_member'] = 1;
        	setcookie('is_new_member', $r['is_new_member'], time() + 30 * 365, '/');
        	return $r;
        }
    }

    /**
     * 根据 UserId  获取指定的会员信息
     *
     * @param    void
     * @return   array
     */
    public function getMemberByUserId($user_id)
    {
        return array_shift($this -> _db -> getMember(array('A.user_id' => $user_id )));
    }

    /**
     * 根据昵称获取会员信息
     *
     * @param    string    $nickname
     * @return   array
     */
    public function getMemberByNickName($nickname)
    {
        if ($nickname) {
        	return $this -> _db -> getMember(array('B.nick_name' => $nickname));
        }
    }
    
    /**
     * 根据用户名获取会员信息
     *
     * @param    string    $nickname
     * @return   array
     */
    public function getMemberByUserName($userName)
    {
        if ($userName) {
        	return $this -> _db -> getMember(array('A.user_name' => $userName));
        }
    }
	
    /**
     * 用户搜索
     *
     * @param    string    $keywords
     * @return   array
     */
    public function searchUserList($keywords, $num)
    {
        if ($keywords) {
        	return $this -> _db -> searchUserList($keywords, $num);
        }
    }

    /**
     * 根据Email获取会员信息
     *
     * @param    string    $email
     * @return   array
     */
    public function getMemberByEmail($email)
    {
        if ($email) {
        	return $this -> _db -> getMemberByEmail($email);
        }
    }
	
	  /**
     * 根据用户uid获取会员信息
     *
     * @param    string    $email
     * @return   array
     */
    public function getMemberByuid($uid)
    {
        if ($uid) {
        	return $this -> _db -> getMemberByuid($uid);
        }
    }
	
    
    /**
     * 取得地区ID的子地区信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getChildAreaById($id = null)
	{
		if (!is_numeric($id)) {
			return;
		}
		$area = $this -> _db -> listArea($id);
		if ($area) {
			
			foreach ($area as $key => $value)
		    {
			    $result[$value['area_id']]['area_name'] = $value['area_name'];
			    $result[$value['area_id']]['code'] = $value['code'];
		    }
		}
		return $result;
	}
	
	/**
     * 取得指定会员ID的会员送货地址信息
     *
     * @param    void
     * @return   array
     */
	public function getAddress()
	{
        $this -> _addressDb = new Shop_Models_DB_MemberAddress();
		$address = $this -> _addressDb -> getAddress(array('member_id' => $this -> _auth['member_id']));
		if (is_array($address)) {
			foreach ($address as $key => $value)
		    {
			    $address[$key]['province_msg'] = ($value['province_id']) ? $this -> _db -> getArea($value['province_id']) : '';
			    $address[$key]['city_option'] = ($value['province_id']) ? $this -> getChildAreaById($value['province_id']) : '';
			    $address[$key]['city_msg'] = ($value['city_id']) ? $this -> _db -> getArea($value['city_id']) : '';
			    $address[$key]['area_option'] = ($value['city_id']) ? $this -> getChildAreaById($value['city_id']) : '';
			    $address[$key]['area_msg'] = ($value['area_id']) ? $this -> _db -> getArea($value['area_id']) : '';
		    }
		    return $address;
		}
	}
	public function getAddressById($aid)
	{
		$this -> _addressDb = new Shop_Models_DB_MemberAddress();
		$rows = $this->_addressDb->getAddressById($aid);
		$rows[province_msg] = $this -> _db -> getArea($rows['province_id']);
		$rows[city_option] = $this -> _db -> getArea($rows['province_id']);
		$rows[city_msg] = $this -> _db -> getArea($rows['city_id']);
		$rows[area_option] = $this -> _db -> getArea($rows['city_id']);
		$rows[area_msg] = $this -> _db -> getArea($rows['area_id']);
		return $rows;
	}
	public function getDefaultAddress()
	{
		$member_id = $this->_auth['member_id'];
		$this -> _addressDb = new Shop_Models_DB_MemberAddress();
		$address = $this -> _addressDb -> getDefaultAddress($member_id);
	    $address['province_msg'] = ($address['province_id']) ? $this -> _db -> getArea($address['province_id']) : '';
		$address['city_option'] = ($address['province_id']) ? $this -> getChildAreaById($address['province_id']) : '';
		$address['city_msg'] = ($address['city_id']) ? $this -> _db -> getArea($address['city_id']) : '';
		$address['area_option'] = ($address['city_id']) ? $this -> getChildAreaById($address['city_id']) : '';
		$address['area_msg'] = ($address['area_id']) ? $this -> _db -> getArea($address['area_id']) : '';
		return $address;
	}
	
    /**
     * 编辑会员送货地址信息
     *
     * @param    int    $time
     * @return   bool
     */
	public function editAddressUseTime($addressID, $time)
	{
        $this -> _addressDb = new Shop_Models_DB_MemberAddress();
		$this -> _addressDb -> updateAddressUseTime($addressID, $time, $this -> _auth['member_id']);
	}
	
	/**
     * 编辑会员送货地址信息
     *
     * @param    array    $data
     * @return   array
     */
	public function editAddress($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
       
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        $tel = $data[phone_qh].'-'.$data[phone_no];
        if($data[phone_fj]){$tel.= '-'.$data[phone_fj];}
        if(empty($data[province_id]) || empty($data[city_id]) || empty($data[area_id]))
        {
        	return 'noArea';
        }else if(empty($data[address])){
        	return 'noAddress';
        }else if(empty($data[consignee])){
        	return 'noConsignee';
        }else if($data[mobile] && !Custom_Model_Check::isMobile($data[mobile]) ){
        	return 'errorMobile';
        }else if(empty($data[zip])){
            return 'errorzip';
        }else{
        	$arr = array(
        		'member_id'    => $this -> _auth['member_id'],
        		'consignee'    => $data[consignee],
        		'province_id'  => $data[province_id],
        		'city_id'      => $data[city_id],
        		'area_id'      => $data[area_id],
        		'address'      => $data[address],
        		'eng_address'  => $data[eng_address],
        		'mobile'       => $data[mobile],
        		'phone'        => $tel,
        	    'zip'          => $data[zip],
        	);

           $arr['add_time'] = time();
           $address_id = $data['address_id'];
           $this -> _addressDb = new Shop_Models_DB_MemberAddress();
	       if ($address_id || $address_id != 0) {
		   		$this -> _addressDb -> updateAddress($arr, $address_id);
		   } else {
		        $this -> _addressDb -> addAddress($arr);
		   }
		   return 'editSucess';
        }
		
	}
	
	/**
     * 根据会员ID获取会员所有订单信息
     *
     * @param array $search
     * 		$search.timesection	（1:最近一个月  ; 2:往期订单 ; 3:所有订单）
     * 		$search.ordertype	（1:所有 ; 2:待确认订单 ; 3:已取消订单 ; 4:需付款订单 ; 5:已付款代发货订单 ; 6:已发货订单 ; 7:已完成订单）
     * 		
     * @param    int    $page
     * @return   array
     */
    public function getAllOrder($search, $page, $pageSize = 10)
    {
    	if ( $this -> _auth['user_id'] > 0) {
    		$where['user_id'] = $this -> _auth['user_id'];
    		$where['timesection'] = 3;
    		$search['ordertype'] && $where['ordertype'] = $search['ordertype'];
    		$search['sn'] && $where['sn'] = $search['sn'];
    	    
    		$orderInfo = $this -> _orderDb -> getUserOrder($where, $page, $pageSize);
    		if ($orderInfo) {
    			foreach ($orderInfo['data'] as $key => $value)
    			{
    			    $payAmount = bcsub($value['price_pay'], bcadd(bcadd(bcadd(bcadd($value['price_payed'], $value['account_payed'], 2), $value['point_payed'], 2), $value['gift_card_payed'], 2), $value['price_from_return'], 2), 2);
    				
    				$orderInfo['data'][$key]['pay_amount'] = $payAmount; 
    				$orderInfo['data'][$key]['add_time'] = date('Y-m-d H:i:s', $orderInfo['data'][$key]['add_time']);
    				$orderInfo['data'][$key]['deal_status'] = $this -> getOrderStatus($value);
    			}
    		}
    		return array('order' => $orderInfo['data'], 'total' => $orderInfo['tot']);
    	}
    }
    
    /**
     * 
     * 获取不同状态下面的订单数量
     * 
     */
    public function getOrderByStatusCount($array=array())
    {
    	if ( $this -> _auth['user_id'] > 0) {
    		$array['user_id'] = $this -> _auth['user_id'];
    		return $this -> _orderDb -> getOrderCount($array);
    	}
    }
    

    /**
     * 根据订单ID获取订单信息
     *
     * @param    string    $batchSN
     * @return   array
     */
    public function getOrderByBatchSN($batchSN)
    {
    	if ($this -> _auth['user_id'] > 0) {
    		return @array_shift($this -> _orderDb -> getOrder(array('b.batch_sn' => $batchSN, 'user_id' => $this -> _auth['user_id'])));
    	}
    }
    
    /**
     * 获取已发货和已签收订单信息
     *
     * @param    string    $batchSN
     * @return   array
     */
    public function getOrderByStatus0($user_id)
    {
    	if ($user_id > 0) {
    		return $this -> _orderDb -> getOrder(array('b.status' => '0', 'user_id' => $user_id));
    	}
    }
    
    /**
     * 根据订单ID获取有订单详细信息
     *
     * @param    int    $id
     * @return   array
     */
    public function getOrderDetailById($id)
    {
    	if ($id > 0) {
    		$orderInfo = $this -> _orderDb -> getOrderDetail(array('B.order_batch_id' =>  $id, 'A.user_id' => $this -> _auth['user_id']));
    		if ($orderInfo) {
    			
    			foreach ($orderInfo as $key => $value)
    			{
    			    $orderInfo[$key]['deal_status'] = $this -> getOrderStatus($value);
    			    $orderInfo[$key]['status_logistic'] = $this -> _statusLogistic[$value['status_logistic']];
    			    $orderInfo[$key]['status_pay'] = $this -> _statusPay[$value['status_pay']];
    			    $orderInfo[$key]['subtotal_sale_price'] = sprintf('%.2f', $value['sale_price']*($value['number'] - $value['return_number']));
    			}
    			return $orderInfo;
    		}
    	}
    }
    
    

    
    /**
     * 根据订单ID获取有订单详细信息
     *
     * @param    string    $batchSN
     * @return   array
     */
    public function getOrderBatch($batchSN)
    {
    	if ($batchSN) {
    		$orderInfo = $this -> _orderDb -> getOrderBatch(array('batch_sn' => $batchSN, 'user_id' => $this -> _auth['user_id']));    		
    		if ($orderInfo) {    			
    			foreach ($orderInfo as $key => $value)
    			{
    			    $orderInfo[$key]['deal_status'] = $this -> getOrderStatus($value);
    			    $orderInfo[$key]['status_logistic_label'] = $this -> _statusLogistic[$value['status_logistic']];
    			    $orderInfo[$key]['status_pay_label'] = $this -> _statusPay[$value['status_pay']];
                    $orderInfo[$key]['logistic_list'] = Zend_Json::decode($value['logistic_list']);
    			}
    			return $orderInfo;
    		}
    	}
        return array();
    }


    /**
     * 根据订单batchSN获取有订单详细信息
     *
     * @param    string    $batchSN
     * @return   array
     */
    public function getOrderByBatch($batchSN)
    {
    	if ($batchSN) {
    		$orderInfo = $this -> _orderDb -> getOrderBatch(array('batch_sn' =>  $batchSN));    		
    		if ($orderInfo) {    			
    			foreach ($orderInfo as $key => $value)
    			{
    			    $orderInfo[$key]['deal_status'] = $this -> getOrderStatus($value);
    			    $orderInfo[$key]['status_logistic_label'] = $this -> _statusLogistic[$value['status_logistic']];
    			    $orderInfo[$key]['status_pay_label'] = $this -> _statusPay[$value['status_pay']];
                    $orderInfo[$key]['logistic_list'] = Zend_Json::decode($value['logistic_list']);
    			}
    			return $orderInfo;
    		}
    	}
        return array();
    }

    /**
     * 取得其它支付方式列表
     *
     * @param    int    $id
     * @return   array
     */
    public function getOtherPaymentList($orderInfo)
    {
    	if ( !$this -> _cartAPI ) {
    	    $this -> _cartAPI = new Shop_Models_API_Cart();
    	}
    	$payment = $this -> _cartAPI -> getPayment(array('status' => 0));
    	
    	if ($payment) {
    		
    		foreach ($payment as $key => $value)
    		{
    			if ($value['pay_type'] != $orderInfo['pay_type']) {
    				$result[$value['pay_type']] = $value['name'];
    			}
    		}
            if (!$orderInfo['logistic_list']['other']['cod']) {
                unset($result['cod']);
            }
    		return $result;
    	}
    }
    
    /**
     * 订单其他支付方式
     * @param unknown $orderInfo
     * @return unknown
     */
    public function getOrderPaymentList($orderInfo)
    {
    	if ( !$this -> _cartAPI ) {
    		$this -> _cartAPI = new Shop_Models_API_Cart();
    	}
    	$payment = $this -> _cartAPI -> getPayment(array('status' => 0));
    	$paymentlist = array();
    	if($payment) 
    	{
	         foreach ($payment as $key => $var) {
	         	if ($var['pay_type'] != $orderInfo['pay_type']) {
		            if ($var['pay_type'] != 'cod') {
		                if( $var['is_bank'] =='1' || $var['is_bank'] =='2'){
		                    $paymentlist['bank_list'][]=$var;
		                }else{
		                    $paymentlist['list'][]=$var;
		                }
		            } else {
		                $paymentlist['cod']=$var;
		            }		          
	         	}
	        }
	        
	        if (!$orderInfo['logistic_list']['other']['cod']) {
	        	unset($paymentlist['cod']);
	        }
    	} 
       return $paymentlist;
    }
    
    /**
     * 取得订单总体状态
     *
     * @param    array    $order
     * @return   string
     */
    public function getOrderStatus($order)
    {
    	if ($order['status'] != 0) {
    		$deal_status = $this -> _status[$order['status']];
    	} 
    	elseif ($order['status_pay'] == 0) {
		    $deal_status = $this -> _statusPay[$order['status_pay']];
        } 
        else if($order['status_pay']==2){
        	$deal_status = $this -> _statusLogistic[$order['status_logistic']];
        } 
        else if($order['status_pay']==1 && $order['status_logistic'] == 5){
        	$deal_status = $deal_status = $this -> _statusLogistic[$order['status_logistic']];
        }
        else {
		    $deal_status = $this -> _statusLogistic[$order['status_logistic']] . ',' . $this -> _statusPay[$order['status_pay']];
		}
		
		return $deal_status;
    }
	
	/**
     * 取得账户余额信息
     *
     * @param    void
     * @return   array
     */
	public function getAllMoney($page, $pageSize)
	{
		$moneyDb = new Shop_Models_DB_MemberMoney();
		$money = $moneyDb -> getMoney(array('A.member_id' => $this -> _auth['member_id']), $page, $pageSize);
		$total = $moneyDb -> getMoneyCount(array('member_id' => $this -> _auth['member_id']));
		return array('money' => $money, 'total' => $total);
	}
	
	/**
     * 取得积分信息
     *
     * @param    void
     * @return   array
     */
	public function getAllPoint($page, $pageSize)
	{
		$pointDb = new Shop_Models_DB_MemberPoint();
		$point = $pointDb -> getPoint(array('A.member_id' => $this -> _auth['member_id']), $page, $pageSize);
		$total = $pointDb -> getPointCount(array('member_id' => $this -> _auth['member_id']));
		
		return array('point' => $point, 'total' => $total);
	}
	
	/**
     * 取得会员留言
     *
     * @param    void
     * @return   array
     */
	public function getAllMessage($type, $page, $pageSize)
	{
		$method = 'get' . ucfirst($type) . 'Msg';
		$messageApi = new Shop_Models_API_Msg();
    	$where = "user_id=" . $this -> _auth['user_id'];
        $message = $messageApi -> getShopMsg($where, '*', $page, $pageSize);
        $total = $messageApi -> getCount();
		return array('message' => $message, 'total' => $total);
	}
	
	/**
     * 添加留言
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addMessage($data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        if (!$data['msg_content']) {
			return 'noMessage';
		}
		
		if (Custom_Model_String :: strLeng($data['msg_content']) > 255) {
			return 'toLongMesssage';
		}
		$addData['type'] = $data['msg_type'];
		$data['order_sn'] && $addData['order_sn'] = $data['order_sn'];
		$addData['content'] = $data['msg_content'];
		$addData['add_time'] = time();
		$addData['user_id'] = $this -> _auth['user_id'];
		$addData['user_name'] = $this -> _auth['user_name'];
		$addData['real_name'] = $this -> _auth['rea_name'];
		$addData['ip'] = $_SERVER['REMOTE_ADDR'];
		$addData['status'] = 1;
		
		if ( !$this -> _messageAPI ) {
		    $this -> _messageAPI = new Shop_Models_API_Msg();
		}
		$result = $this -> _messageAPI -> shopMsg($addData);
		
		if ($result) {
		    return 'addMessageSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 更新会员订单支付方式
     *
     * @param    array    $data
     * @return   bool
     */
	public function setOrderPayment($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        $batchSN = $data['batch_sn'];
        $order = array_shift($this -> _orderDb -> getOrderBatch(array('batch_sn' => $batchSN, 'user_id' => $this -> _auth['user_id'])));
        if (!$order['batch_sn']) {
			return 'error';
		} else if (!$data['pay_type']) {
			return 'noPayment';
		} else if ($order['status_logistic'] != 0) {
            return 'hasConfirm';
        } else if ($order['status'] != 0) {
            return 'isCancel';
        } else if ($order['status_pay'] != 0) {
            return 'hasPay';
        }
        if ( !$this -> _cartAPI ) {
            $this -> _cartAPI = new Shop_Models_API_Cart();
        }
		$payment = array_shift($this -> _cartAPI -> getPayment(array('pay_type' => $data['pay_type'])));
		if ($this -> _orderDb -> setOrderPayment(array('pay_type' => $data['pay_type'], 'pay_name' => $payment['name']), $order['batch_sn']) >= 0) {
			return 'setOrderPaymentSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 更新会员订单短信号码
     *
     * @param    array    $data
     * @return   bool
     */
	public function setOrderSmsNo($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        $batchSN = $data['batch_sn'];
        $order = array_shift($this -> _orderDb -> getOrderBatch(array('batch_sn' => $batchSN, 'user_id' => $this -> _auth['user_id'])));
        if (!$order['batch_sn']) {
			return false;
		}
		
		$this -> _orderDb -> setOrderSmsNo($data['sms_no'], $batchSN);
	}
	
	/**
     * 更新会员订单收货信息
     *
     * @param    array    $data
     * @return   bool
     */
	public function setOrderAddress($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        if (!is_numeric($data['addr_province_id']) || !is_numeric($data['addr_city_id']) || !is_numeric($data['addr_area_id'])) {
		    return 'noArea';
		}
		
		if ($data['addr_consignee'] == '') {
		    return 'noConsignee';
		}
		
		if ($data['addr_address'] == '') {
		    return 'noAddress';
		}
		
		if ($data['addr_tel'] != '' && !Custom_Model_Check::isTel($data['addr_tel'])) {
			return 'errorPhone';
		}
		
		if ($data['addr_mobile'] != '' && !Custom_Model_Check::isMobile($data['addr_mobile'])) {
			return 'errorMobile';
		}
        $order = array_shift($this -> _orderDb -> getOrderBatch(array('batch_sn' => $data['batch_sn'], 'user_id' => $this -> _auth['user_id'])));
		if (!$order) {
            return 'error';
        }
        if ($order['status_logistic'] > 0 || $order['status'] > 0) {
            return 'noEdit';
        }
        $data['addr_province'] = $this -> _db -> getAreaName($data['addr_province_id']);
		$data['addr_city'] = $this -> _db -> getAreaName($data['addr_city_id']);
		$data['addr_area'] = $this -> _db -> getAreaName($data['addr_area_id']);
        $data['addr_zip'] = $this -> _db -> getAreaZip($data['addr_area_id']);
		if ($this -> _orderDb -> setOrderAddress($data, $order['batch_sn']) >= 0) {
			return 'setOrderAddressSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 生成支付方式按钮
     *
     * @param    array    $order
     * @return   bool
     */
	public function getPayInfo($order) 
    {
		if ( !$this -> _cartAPI ) {
		    $this -> _cartAPI = new Shop_Models_API_Cart();
		}
		$payment = array_shift($this -> _cartAPI -> getPayment(array('pay_type' => $order['pay_type'])));
        $code = ucfirst($payment['pay_type']);
        if (file_exists(Zend_Registry::get('systemRoot') . '/lib/Custom/Model/Payment/'.$code.'.php')) {
            $class = 'Custom_Model_Payment_'.$code;
            $pay = new $class($order['batch_sn']);
            return $pay -> getCode(array('bank'=>true,'target'=>true,'pay_hidden'=>true));
        }
    }
	
	/**
     * 生成支付按钮
     *
     * @param    array    $order
     * @return   bool
     */
	public function createPayButton($pay_id,$order_sn,$amount,$business='') 
    {
		if ( !$this -> _cartAPI ) {
		    $this -> _cartAPI = new Shop_Models_API_Cart();
		}
		$payment = $this -> _cartAPI -> getRow('shop_payment',array('id'=>$pay_id));
        $code = ucfirst($payment['pay_type']);
		$code = $code == 'Tenpay' ? 'Tenpay2' : $code; 
        if (file_exists(Zend_Registry::get('systemRoot') . '/lib/Custom/Model/Payment/'.$code.'.php')) {
            $class = 'Custom_Model_Payment_'.$code;
            $pay = new $class($order_sn,4,$amount,$business);
            return $pay -> getButton(array('bank'=>true),unserialize($payment['config']));
        }
    }
	
	
	/**
     * 订单取消
     *
     * @param    array    $order
     * @return   bool
     */
    public function cancelOrder($batchSN)
    {
        $order = array_shift($this -> _orderDb -> getOrderBatch(array('batch_sn' => $batchSN, 'user_id' => $this -> _auth['user_id'])));
        $payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
        
        if ($order['price_payed'] == 0 && $order['status'] == 0 && $order['status_logistic'] == 0 && $payAmount>0) {
            $result = $this -> _orderDb -> setOrderCancel($batchSN);
        } else {
            return 'noCancel';
        }
		if (is_numeric($result) && $result >= 0) {
            //api 抵用券接口(整退)
            $this -> unUseCard($batchSN);
            //api 积分接口(整退)
            $this -> unPointPrice($batchSN, '客户中心取消订单');
            //api 帐户余额接口
            $this -> unAccountPrice($batchSN, '客户中心取消订单');
            /*
            //api 联盟接口
            $this -> union($order['batch_sn']);
			*/
            $product = $this -> _orderDb -> getOrderBatchGoods(array('batch_sn' => $order['batch_sn'], 'product_id>' => 0, 'number>' => 0));
            if (is_array($product) && count($product)) {
                $stockAPI = new Admin_Models_API_Stock();
                $stockAPI -> setLogicArea($order['lid']);
                foreach($product as $k => $v) {
                    //api 释放库存
                    $stockAPI -> releaseSaleProductStock($v['product_id'], $v['number']);
                }
            }
            
            //更新auth状态->is_new_member
            $auth_api = Shop_Models_API_Auth :: getInstance();
            $auth_api -> updateAuth();
            
            $orderAPI = new Admin_Models_DB_Order();
            $log = array('order_sn' => $batchSN,
                         'batch_sn' => $batchSN,
                         'add_time' => time(),
                         'title' => '客户前台取消订单',
                         'admin_name' => 'guest'
                        );
            $orderAPI -> addOrderBatchLog($log);
            
            return 'setOrderCancelSucess';
		} else {
			return 'error';
		}
    }
	/**
     * 抵用券接口(整退)
     *
     * @param    string    $batchSN
     * @return   void
     */
    public function unUseCard($batchSN)//礼券，礼品卡 整退
    {
        $product = $this -> _orderDb -> getOrderBatchGoods(array('batch_sn' => $batchSN, 
                                                                 'card_sn_is_not_null' => true,
                                                                 'number>' => 0));
        if ($product) {
            $cardObj = new Shop_Models_API_Card();
            foreach ($product as $k => $v) {
                $cardObj -> unUseCard(array('card_sn' => $v['card_sn'], 'card_price' => abs($v['sale_price']), 'add_time' => $v['add_time']));
                $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                $data = array('number' => 0);
                $this -> _orderDb -> updateOrderBatchGoods($where, $data);
            }
        }
        
        if ($this -> unGiftCardPrice($batchSN)) {
            $this -> _orderDb -> updateOrderBatch(array('batch_sn' => $batchSN), array('gift_card_payed' => 0));
        }
    }
    
    /**
     * 退礼品卡接口 整退
     *
     * @param    string    $batchSN
     * @return   boolean
     */
    public function unGiftCardPrice($batchSN)
    {
        $order = array_shift($this -> _orderDb -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order || $order['gift_card_payed'] <= 0)  return false;
        
        $giftCardAPI = new Admin_Models_API_GiftCard();
        $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN));
        $giftCardLog = $giftCardAPI -> setCanReturnCard($giftCardLog['content']);
        if ($giftCardLog) {
            $giftCardAPI = new Shop_Models_API_Card();
            $result = false;
            foreach ($giftCardLog as $giftCard) {
                if ($giftCard['can_return']) {
                    $card = array('card_sn' => $giftCard['card_sn'],
                                  'card_price' => $giftCard['price'],
                                  'add_time' => time(),
                                  'user_id' => $this -> _auth['user_id'],
                                  'user_name' => $this -> _auth['user_name'],
                                  'batch_sn' => $batchSN,
                                 );
                    $giftCardAPI -> unUseCard($card);
                    $result = true;
                }
            }
            
            return $result;
        }
        else {
            return false;
        }
    }
    
    
	/**
     * 退积分接口 整退
     *
     * @param    string    $batchSN
     * @param    string    $note
     * @return   void
     */
    public function unPointPrice($batchSN, $note)
    {
        $order = array_shift($this -> _orderDb -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order || $order['point_payed'] <= 0)  return false;
        $pricePoint = $order['point_payed'];
        
        $pointAPI = new Admin_Models_DB_MemberPoint();
        $pointList = $pointAPI -> getPoint(" and A.batch_sn = '{$batchSN}'");
        if (!$pointList)    return false;
        
        $totalPoint = 0;
        foreach ($pointList as $point) {
            $totalPoint += $point['point'];
        }
        if ($totalPoint > 0)    return false;
        $totalPoint = abs($totalPoint);
        $point = floor($pricePoint * ($totalPoint / $order['point_payed']));
        
        $member = array_shift($this -> getMemberByUserName($order['user_name']));
        $tmp = array('member_id' => $member['member_id'],
		             'user_name' => $order['user_name'],
                     'order_id' => $order['order_batch_id'],
                     'accountType' => 1,
                     'accountValue' => $point,
                     'accountTotalValue' => $member['point'],
                     'batch_sn' => $batchSN,
                     'note' => $note);
        $this -> editAccount($member['member_id'], 'point', $tmp);
        
        $this -> _orderDb -> updateOrderBatch(array('batch_sn' => $batchSN), array('point_payed' => 0));
    }
	/**
     * 帐户余额接口(整退)
     *
     * @param    string    $batchSN
     * @param    string    $note
     * @return   void
     */
    public function unAccountPrice($batchSN, $note)
    {
        $order = array_shift($this -> _orderDb -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order || $order['account_payed'] <= 0)  return false;
        $priceAccount = $order['account_payed'];
        
        $member = array_shift($this -> getMemberByUserName($order['user_name']));
        $tmp = array('member_id' => $member['member_id'],
                     'user_name' => $order['user_name'],   
                     'order_id' => $order['order_batch_id'],
                     'accountType' => 1,
                     'accountValue' => $priceAccount,
                     'accountTotalValue' => $member['money'],
                     'batch_sn' => $batchSN,
                     'note' => $note);
        $this -> editAccount($member['member_id'], 'money', $tmp);
        
        $this -> _orderDb -> updateOrderBatch(array('batch_sn' => $batchSN), array('account_payed' => 0));
    }

    
    public function getOrderBatchGoods($batchSN)
    {
        $data = $this -> _orderDb -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'number>' => 0));
        
        if ($data) {//此处如有修改请同步修改union Information api 订单商品数据处理
            foreach ($data as $k => $v) {
                if ($v['product_id']) {
                    $product[$v['order_batch_goods_id']] = $v;
                    $priceGoods += $v['sale_price'] * ($v['number'] - $v['return_number']);
                    $priceGoodsEq += $v['eq_price'] * ($v['number'] - $v['return_number']);
                    if (($v['number'] - $v['return_number']) > 0) {
                        $priceGoodsEq += $v['eq_price_blance'];
                    }

                    $priceGoodsAll += $v['sale_price'] * $v['number'];
                }
                $productAll[$v['order_batch_goods_id']] = $v;
            }
            foreach ($productAll as $id => $v) {
                if ($v['parent_id']) {
                    if ($productAll[$v['parent_id']]) {
                        $productAll[$v['parent_id']]['child'][] = $v;
                    }
                    unset($productAll[$id]);
                }
            }
            foreach ($productAll as $id => $v) {
                $productAll[$id]['amount'] = $v['amount'] = $v['sale_price'] * ($v['number'] - $v['return_number']);
                if ($v['child']) {
                    foreach ($v['child'] as $x => $y) {
                       $productAll[$id]['child'][$x]['amount'] = $y['amount'] = $y['sale_price'] * ($y['number'] - $y['return_number']);
                    }
                    
                }
                if ($v['type'] == 3) {//帐户余额
                    unset($productAll[$id]);
                    $priceAccount += abs($v['amount']);
                }
                if ($v['type'] == 4) {//积分
                    unset($productAll[$id]);
                    $pricePoint += abs($v['amount']);
                    $point += $v['point'];
                    $pointGoodsID = $v['order_batch_goods_id'];
                }
            }
        }
        
        return array('product_all' => $productAll,
                     'product' => $product,
                     'price_minus' => -abs($priceMinus),//订单立减
                     'price_old' => -abs($priceOld),//礼券
                     'price_coupon' => -abs($priceCoupon),//礼券
                     'price_gift' => -abs($priceGift),//礼品卡
                     'gift_goods_id' => $giftGoodsID,
                     'gift_sn' => $giftSN,//礼品卡
                     'gift_type' => $giftType,//礼品卡
                     'gift_name' => $giftName,//礼品卡

                     'price_point' => -abs($pricePoint),//积分价格
                     'point_goods_id' => $pointGoodsID,
                     'point' => $point,//积分价格

                     'price_account' => $priceAccount,//帐户余额

                     'other' => array('price_goods_all' => $priceGoodsAll,//商品总金额(包含退货商品)
                                      'price_goods' => $priceGoods,//商品总金额(不包含退货商品)
                                      'price_goods_eq' => $priceGoodsEq));//均摊了订单立减，调整金额，礼金券后的商品总金额
    
    }
	/**
     * 取订单商品信息
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function getOrderBatchGoods1($where)
    {
        $data = $this->_orderDb -> getOrderBatchGoods($where);
        if ($data) {
            $stockReport = new Admin_Models_API_StockReport();
            foreach ($data as $k => $v) {
                if ($v['product_id']) {
                    $where = "real_in_number-out_number>0 and status_id=2 and lid in(1,2) and a.product_id={$v['product_id']}";
                    $stock = array_shift($stockReport -> getStockStatus($where));
                    $v['able_number'] = $stock['able_number'];
                }
                $forList[$v['order_batch_goods_id']] = $v;
            }
            foreach ($forList as $id => $v) {
                if ($v['parent_id']) {
                    if ($forList[$v['parent_id']]) {
                        $forList[$v['parent_id']]['child'][] = $v;
                    }
                    unset($forList[$id]);
                }
            }
            foreach ($forList as $id => $v) {
                $forList[$id]['blance'] = $v['blance'] = $v['number'] - $v['return_number'];
                $forList[$id]['amount'] = $v['amount'] = $v['sale_price'] * $v['number'];
                if ($v['child']) {
                    foreach ($v['child'] as $x => $y) {
                       $forList[$id]['child'][$x]['blance'] = $y['blance'] = $y['number'] - $y['return_number'];
                       $forList[$id]['child'][$x]['amount'] = $y['amount'] = $y['sale_price'] * $y['number'];
                    }
                }
                if ($v['type'] == 3) {//帐户余额抵扣
                    unset($forList[$id]);
                }
            }
        }
        return $data = array('product' => $forCount, 'all' => $forList);
    }
    /**
     * 满意不退货
     *
     * @param    int    $batchSN
     * @return void
     */
    public function fav($batchSN)
    {
        if ($batchSN) {
            $filterChain = new Zend_Filter();
            $filterChain -> addFilter(new Zend_Filter_StringTrim())
                         -> addFilter(new Zend_Filter_StripTags());
                         
            $batchSN = Custom_Model_Filter::filterArray($batchSN, $filterChain);
            $sql = "and b.batch_sn='{$batchSN}' and user_id={$this->_auth['user_id']} and (is_fav IS NULL || is_fav=-1) and status=0 and status_logistic>2 and status_logistic<5";
            $order = array_shift($this -> _orderDb -> getOrderBatch($sql));
            if (!$order) {
                return 'error';
            }
            $where = array('batch_sn' => $order['batch_sn']);
            $set = array('is_fav' => 1);
            $result = $this -> _orderDb -> updateOrderBatch($where, $set);
            if (is_numeric($result) && $result > 0 ) {
                //满意不退货赠送积分 开始
                $shopConfig = Zend_Registry::get('shopConfig');
                if (!isset($shopConfig['fav_point'])) {
                    $shopConfig['fav_point'] = 1;
                }
                $priceFromReturn = 0;
                if ($order['is_fav'] != -1) {
                    $priceFromReturn = $order['price_from_return'];
                }
                $point = intval(($order['price_payed'] + $priceFromReturn - $order['price_logistic']) * $shopConfig['fav_point']);
                if ($point < 0) {
                    $point = 0;
                }
                $tmp = array('member_id' => $this -> _auth['member_id'],
                             'user_name' => $this -> _auth['user_name'],
                             'order_id' => $order['order_batch_id'],
                             'accountType' => 1,
                             'accountValue' => $point,
                             'accountTotalValue' => $this -> _auth['point'],
                             'note' => '满意不退货赠送积分');
                $this -> editAccount($this -> _auth['member_id'], 'point', $tmp);
                //满意不退货赠送积分 结束
                

				// 满意不退货赠送经验值
				$experience_param = array(
					'member_id'        => $this->_auth['member_id'],
					'batch_sn'         => $order['batch_sn'],
					'experience'       => $point,
					'experience_total' => $this->_auth['experience'] + $point,
					'created_by'       => 'system',
					'remark'           => '满意不退货赠送经验值',
				);

				$this->editAccount($this->_auth['member_id'], 'experience', $experience_param);
               
				// 添加最后获取经验值时间并更新等级
				$member_db = new Admin_Models_API_Member();

				$member_db->updateMemberInfoByMemberId($this->_auth['member_id'], array('last_experience_time' => date('Y-m-d H:i:s')));

				$member_db->changeMemberRank($this->_auth['member_id']);
            }

			
            return 'setOrderFavSucess';
        } else {
            return 'error';
        }
        
    }
	/**
     * 会员账户余额支付
     *
     * @param    string    $batchSN
     * @param    float     $priceAccount
     * @return void
     */
    public function priceAccountPayed($batchSN, $priceAccount)
    {
        $order = array_shift($this -> _orderDb -> getOrderBatch(array('batch_sn' => $batchSN, 'user_id' => $this -> _auth['user_id'])));
        if (!$order) {
            return 'error';
        }
        $blance = floatval($order['price_pay']) - (floatval($order['price_payed']) + floatval($order['point_payed']) + floatval($order['account_payed']) + floatval($order['gift_card_payed']) + floatval($order['price_from_return']));
        $member = array_shift($this -> getMemberByUserName($this -> _auth['user_name']));
        if ($member['money'] < $priceAccount) {
            return 1;
        } else if ($order['status'] != 0) {
            return 2;
        //} else if ($order['pay_type'] == 'cod') {
        //    return 3;
        }else if ($blance <= 0) {
            return 4;
        } else if ($priceAccount <= 0) {
            return 5;
        } else if ($priceAccount > $blance) {
            return 6;
        }
        
        //api 帐户余额接口 开始
        $member = array_shift($this -> getMemberByUserName($this -> _auth['user_name']));
        $tmp = array('member_id' => $this -> _auth['member_id'],
                     'user_name' => $order['user_name'],   
                     'order_id' => $order['order_batch_id'],
                     'accountValue' => $priceAccount,
                     'accountTotalValue' => $member['money'],
                     'batch_sn' => $batchSN,
                     'note' => '客户支付订单抵扣');
        $this -> editAccount($this -> _auth['member_id'], 'money', $tmp);
        Shop_Models_API_Auth :: getInstance() -> updateAuth();
        //api 帐户余额接口 结束
        
        $where = array('batch_sn' => $order['batch_sn']);
        $set = array('account_payed' => $order['account_payed'] + $priceAccount, 'pay_time' => time());
        $this -> _orderDb -> updateOrderBatch($where, $set);

        //更新支付状态 开始
        //$this -> updateOrderBatchPrice($batchSN);todo
        $order = new Admin_Models_API_Order();
        $order -> orderDetail($batchSN);
        //更新支付状态 结束
        
       
    }

    /**
     * 统计当前订单商品 总重量 体积 数量
     *
     * @param   string      $batchSN
     * @return  array
     */
    public function _orderBatchProductStatus($batchSN)
    {
        $where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0);
	    $product = $this -> _orderDb -> getOrderBatchGoods($where);
        if (is_array($product) && count($product)) {
            foreach($product as $k => $v) {
                $productNumber += $v['number'];
                $productWeight += $v['weight'] * $v['number'];
                $productVolume += $v['length'] * $v['width'] * $v['height'] * $v['number'] * 0.001;
            }
        }
        $data = array('product_number' => $productNumber, 'product_weight' => $productWeight,'product_volume' => $productVolume);
        return $data;
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
		
		$data['member_id'] = $id;
		$data['add_time'] = time();
		if ($type && $id > 0 && $data['accountValue'] && $data['note']) {
			switch ($type) {
			    case 'money':
			        $moneyDb = new Shop_Models_DB_MemberMoney();
			        $data['money'] = ($data['accountType'] == '1') ? $data['accountValue'] : -$data['accountValue'];
			        $data['money_total'] = $data['accountTotalValue'];
			        $data['money_type'] = 0;
					
			        $updateMember = $this -> _db -> updateMoney($id, $data['money']);
			        
			        if ($updateMember > 0) {
			        	$memberMoneyId = $moneyDb -> addMoney($data);
			        }
			        
			        if ($memberMoneyId <= 0) {
			        	$restoreMember = $this -> _db -> updateMoney($id, -$data['money']);
			        	return false;
			        }
					
					return true;
			        break;
			    case 'point':
			        $pointDb = new Shop_Models_DB_MemberPoint();
			        $data['point'] = ($data['accountType'] == '1') ? $data['accountValue'] : -$data['accountValue'];
			        $data['point_total'] = $data['accountTotalValue'];
			        
			        if(!isset($data['point_type']))
			        {
			          $data['point_type'] = 0;
			        }			        
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
					return true;
			        break;
		    }
		}

		if ($type == 'experience') {

			$pointDb = new Shop_Models_DB_MemberPoint();
			$update_experience = $this->_db->updateExperience($id, $data['experience']);
			$member_experience_id = 0;
			if (true === $update_experience) {
				$member_experience_id = $pointDb->addExperience($data);
			}

			if ($member_experience_id == 0) {
				$this->_db->updateExperience($id, -$data['experience']);
				return false;
			}
		}
		return true;
	}
	/**
     * 取配送公司列表
     *
     * @param    array    $where
     * @return   array
     */
    public function getLogistic($where)
    {
        return $this -> _db -> getLogistic($where);
    }

	/**
     * 用户礼品卡信息
     *
     * @param    void
     * @return   array
     */
	public function getGiftCard($page, $pageSize)
	{
		$card = $this -> _db -> getGiftCard($this -> _auth['user_id'], $page, $pageSize);
		return $card;
	}

	public function getGiftCardList($where='',$page, $pageSize)
	{
		$card = $this -> _db -> getGiftCardList($where, $page, $pageSize);
		return $card;
	}
	/**
     * 用户礼品卡历史信息
     *
     * @param    void
     * @return   array
     */
	public function getGiftCardLog($where='',$page, $pageSize)
	{
		$where['user_id'] = $this -> _auth['user_id'];
		return $this -> _db -> getGiftCardLog($where, $page, $pageSize);
	}
	
	/**
     * 用户礼金券信息
     *
     * @param    $page      int
     * @param    $pageSize  int
     * @param    $type      int
     * @return   array
     */
	public function getCoupon($page = null, $pageSize = null, $type = 1)
	{
		$currentDate = date( 'Y-m-d', time() );
		if ($type == 1) {
		    $where = " and B.end_date >= '{$currentDate}' and ((A.status = 0) or (A.is_repeat = 1))";
		}
		else if ($type == 2) {
		    $where = " and (B.end_date < '{$currentDate}' or ((A.status = 1) and (A.is_repeat = 0)))";
		}
		$where .= " and A.user_id = {$this -> _auth['user_id']}";
		$content = $this -> _db -> getCoupon($where, $page, $pageSize);
		$total = $this -> _db -> getCouponCount($where);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得礼金券
     *
     * @param    $where     array
     * @param    $page      int
     * @param    $pageSize  int
     * @return   array
     */
	public function getCouponMsg($where = null, $page = null, $pageSize = null)
	{
		$where || $where = array('A.user_id' => $this -> _auth['user_id']);
		return $this -> _db -> getCoupon($where, $page, $pageSize);
	}
	
	/**
     * 取得可用礼金券
     *
     * @param    $userId    int
     * @return   array
     */
	public function getActiveCoupon($userId)
	{
		if ($userId) {
			return $this -> _db -> getActiveCoupon($userId);
		}
	}
	
	/**
     * 暂存架列表
     *
     * @param    void
     * @return   array
     */
	public function getFavorite($page, $pageSize)
	{
		return $this -> _db -> getFavorite($this -> _auth['user_id'], $page, $pageSize);
	}
	
	/**
	 * 得到有效的优惠券数量
	 * 
	 */
	public function getValidCoupon() {
		if($this -> _auth['user_id']){
			$rs = $this -> _db -> getValidCoupon(array('user_id' =>  $this -> _auth['user_id']));
			return $rs['count'];
		}else{
			return null;
		}
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
    public function getMsg($search=null, $fields='*', $page=null, $pageSize=null) {
    	$where = ' 1 ';
    	if($search){
    		$search['user_id'] && $where .= " and user_id={$search['user_id']} ";
    		$search['type'] && $where .= " and type={$search['type']} ";
    		$search['reply'] && $search['reply']=='yes' && $where .= " and reply!='' ";
    		$search['reply'] && $search['reply']=='no' && $where .= " and reply='' ";
    	}
    	$_api_msg = new Shop_Models_API_Msg();
    	$rs = $_api_msg -> getShopMsg($where, $fields, $page, $pageSize);
    	$tot = $_api_msg -> getCount();
    	return array('tot'=>$tot, 'datas'=>$rs);
    }
    
    /**
     * 修改表
     */
    public function updateTable($table=null, $set=null, $where=null) {
    	$this -> _db -> updateTable($table, $set, $where);
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
		$point_db = new Shop_Models_DB_MemberPoint();
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
	* 返回错误信息
	*
	* @return   string
	*/
	public function getError()
	{
		return $this->_error;	
	}

	/**
	 * 获得用户的默认地址id
	 * @param int $member_id
	 */
	public function getDefaultAddressId($member_id)
	{
		$this -> _addressDb = new Shop_Models_DB_MemberAddress();
		return $this -> _addressDb -> getDefaultAddressId($member_id);
	}
	
	public function deleteAddress($id,$member_id)
	{
		if($id > 0 && $member_id > 0){
			$this -> _addressDb = new Shop_Models_DB_MemberAddress();
			return $this->_addressDb->deleteAddress($id,$member_id);
		}else{
			return false;
		}
	}
	/**
	 * 批量删除用户收藏
	 * @param array $result
	 */
	public function deleteFavorite($facourite_ids)
	{
		$ids = implode(',', $facourite_ids);
		return $this -> _db -> delFavourites($ids,$this->_auth['user_id']);
	}
 }
