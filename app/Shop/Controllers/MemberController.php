<?php
class MemberController extends Zend_Controller_Action
{
	/**
     * 认证 API
     * 
     * @var Admin_Models_API_Auth
     */
	protected $_auth = null;
	
	/**
	 * 
	 * @var Shop_Models_API_Member
	 */
	protected $_member = null;
	private $_page_size = '20';
	/**
     * 站点留言类型
     * 
     * @var array
     */
	protected $_shopMsgType = array('留言', '投诉', '询问', '售后', '求购');
	
	/**
     * 未选择地区
     */
	const NO_AREA = '请选择地区!';
	
	/**
     * 未填写收货人
     */
	const NO_CONSIGNEE = '请填写收货人!';
	
	/**
     * 未填写详细地址
     */
	const NO_ADDRESS = '请填写详细地址!';
	
	/**
     * 邮政编码格式错误
     */
	const ERROR_ZIP = '请填写正确的邮政编码!';
	
	/**
     * 电话格式错误
     */
	const ERROR_PHONE = '请填写正确的电话号码!';
	
	/**
     * 传真格式错误
     */
	const ERROR_FAX = '请填写正确的传真格式!';
	
	/**
     * 收货地址多于限制个数
     */
	const TOO_MANY_ADDRESS = '您最多只能有规定个数的收货地址!';
	
	/**
     * 编辑地址成功
     */
	const EDIT_ADDRESS_SUCESS = '编辑收货地址成功!';
	
	/**
     * 编辑会员信息成功
     */
	const EDIT_MEMBER_SUCESS = '修改个人信息成功!';
	/**
	 * 编辑会员头像成功
	 */
	const EDIT_AVATAER_SUCESS = '修改头像成功!';
	
	/**
     * Email地址不正确
     */
	const ERROR_EMAIL = '请输入正确的Email地址!';
	
	/**
     * 手机号码不正确
     */
	const ERROR_MOBILE = '请输入正确的手机号码!';
	
	/**
     * MSN不正确
     */
	const ERROR_MSN = '请输入正确的MSN!';
	
	/**
     * QQ码不正确
     */
	const ERROR_QQ = '请输入正确的QQ号码!';
	
	/**
     * 办公室电话不正确
     */
	const ERROR_OFFICE_PHONE = '请输入正确的办公室电话!';
	
	/**
     * 住宅电话不正确
     */
	const ERROR_HOME_PHONE = '请输入正确的住宅电话!';
	
	/**
     * 密码输入不一致
     */
	const NO_SAME_PASSWORD = '密码输入不一致!';
	
	/**
     * 密码格式不正确
     */
	const ERROR_PASSWORD = '密码必须为6-20位的字母和数字的组合!';
	
	/**
     * 密码不正确
     */
	const ERROR_OLD_PASSWORD = '您输入的原密码不正确!';
	
	/**
     * 没有输入密码
     */
	const NO_PASSWORD = '请输入密码!';
	
	/**
	 * 邮箱已被使用
	 */
	const EXISITS_EMAIL = '邮箱已被使用';
	
	/**
	 * 手机号已被使用
	 */
	const EXISITS_MOBILE = '手机号已被使用';
	
	/**
     * 密码修改成功
     */
	const EDIT_PASSWORD_SUCESS = '修改密码成功!';
	
	/**
     * 密码修改成功
     */
	const NO_MESSAGE = '请输入留言内容!';
	
	/**
     * 密码修改成功
     */
	const TO_LONG_MESSAGE = '留言内容必须在255个字以内!';
	
	/**
     * 密码修改成功
     */
	const ADD_MESSAGE_SUCESS = '添加留言成功!';
	
	/**
     * 未选择支付方式
     */
	const NO_PAYMENT = '请选择支付方式!';

    const HAS_CONFIRM = '已确认订单不能修改支付方式'; 
    const IS_CANCEL = '被取消的订单不能修改支付方式'; 
    const HAS_PAY = '该订单已经完成支付，不能修改支付方式'; 
    const NO_EDIT = '该订单已经被锁定，如果需要修改，请联系客服';

	const UNABLE_BIRTHDAY  = '对不起,你不能对生日再做修改! 如需更改请与管理员联系.';

	/**
     * 修改支付方式成功
     */
	const SET_ORDERPAYMENT_SUCESS = '修改支付方式成功!';
	
	/**
     * 修改收货地址成功
     */
	const SET_ORDERADDRESS_SUCESS = '修改收货地址成功!';
	/**
     * 该订单不能取消
     */
    const NOT_CANCEL = '不能取消';
	/**
     * 订单取消成功
     */
    const SET_ORDERCANCEL_SUCESS = '订单取消成功';
	/**
     * 订单设置满意不退货成功
     */
    const SET_FAV_SUCESS = '订单设置满意不退货成功';
    
    /**
     * 卡号或密码不正确
     */
    const CARD_ERROR = '卡号或密码错误,无法充值';
    
    /**
     * 会员不存在
     */
    const NO_USER = '该会员不存在,请提供正确的充值会员名称';
    
    /**
     * 该卡已经过期
     */
    const CARD_EXPIRED = '该卡已经过期,无法充值';
    
    /**
     * 卡已经被使用
     */
    const CARD_USED = '该卡已经被使用,无法充值';
    
    /**
     * 礼品卡充值成功
     */
    const FILL_IN_SUCESS = '恭喜,礼品卡充值成功';
	const SET_ORDERSMSNO_SUCESS = '修改手机短信号码成功!';
	
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _auth = Shop_Models_API_Auth :: getInstance();
		$this -> _member = new Shop_Models_API_Member();
		$auth = $this -> _auth -> getAuth();
		if (!$auth || $auth['ltype']) {
			$this -> _helper -> redirector -> gotoUrl(Zend_Controller_Front::getInstance() -> getBaseUrl(). '/login.html');
		} else {
            $this -> _memberInfo = $this -> _member -> getUser();
            $this -> _memberInfo = $this -> _memberInfo ? $this -> _memberInfo : array();
		    $this -> view -> member = array_merge($auth, $this -> _memberInfo);
		    $this -> view -> action = $this -> _request -> getActionName();
		    $this -> view -> type = $this -> _request -> getParam('type', '');
			$this -> view -> isView = 1;
		}
		$this->view->css_more = ',member.css';
		$this->view->cur_position = 'member';
	}
	
	/**
     * 编辑会员基本资料
     *
     * @return void
     */
    public function profileAction()
    {
    	/*$idcard = '422801198505102237';
    	$wi = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
    	$Ai = array(1,0,X,9,8,7,6,5,4,3,2);
    	$arr = preg_split('//', $idcard,0, PREG_SPLIT_NO_EMPTY);
    	$sum = 0;
    	foreach ($arr as $key=>$val)
    	{
    		$sum = $sum + $val * $wi[$key];
    	}
    	echo $sum.'<br/>';
    	$mod = $sum % 11;
    	echo 'mod:'.$mod.'<br/>';
    	echo 'last:'.$Ai[$mod];
    	
    	echo '<pre>';
    	print_r($arr);
    	exit;*/
        if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $result = $this -> _member -> editMember($this -> _request -> getPost());
            echo "<script>parent.document.getElementById('dataTip').value=' 确定 ';parent.document.getElementById('dataTip').disabled=false;</script>";
            switch ($result) {
        		case 'errorEmail':
        		    Custom_Model_Message::showAlert(self::ERROR_EMAIL);
        		    break;
        		case 'unable':
        		    Custom_Model_Message::showAlert(self::UNABLE_BIRTHDAY);
        		    break;
        		case 'errorMobile':
        		    Custom_Model_Message::showAlert(self::ERROR_MOBILE);
        		    break;
        		case 'errorMsn':
        		    Custom_Model_Message::showAlert(self::ERROR_MSN);
        		    break;
        		case 'errorQq':
        		    Custom_Model_Message::showAlert(self::ERROR_QQ);
        		    break;
        		case 'errorOfficePhone':
        		    Custom_Model_Message::showAlert(self::ERROR_OFFICE_PHONE);
        		    break;
        		case 'errorHomePhone':
        		    Custom_Model_Message::showAlert(self::ERROR_HOME_PHONE);
        		    break;
        		case 'emailExist':
        		    Custom_Model_Message::showAlert(self::EXISITS_EMAIL);
        		    break;
        		case 'mobileExist':
        		    Custom_Model_Message::showAlert(self::EXISITS_MOBILE);
        		    break;
        		case 'editMemberSucess':
        		    $this -> _auth -> updateAuth();
        		    Custom_Model_Message::showAlert(self::EDIT_MEMBER_SUCESS,false, '/member/profile/');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error!');
        	}
        } else {
            $this -> view -> sexRadios = array(1 => '男', 2 => '女');
			$profileMember=$this -> _memberInfo;
			if( $profileMember['birthday']=='0000-00-00' || !$profileMember['birthday']){
				$birthdayAble=1;
				$this -> view -> birthdayAble = $birthdayAble;
			}
        }
        $this -> view -> page_title = '修改个人信息';    
    }
    
    public function verifyemailAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$email = $this -> _request ->getParam('email'); 
    	
    	if($this -> _memberInfo['ischecked'])
    	{
    		echo  Zend_Json::encode(array('status'=>0,'msg'=>'邮箱已验证'));
    		exit();
    	}
    	
    	if ($email && !Custom_Model_Check::isEmail($email)){
    		echo  Zend_Json::encode(array('status'=>0,'msg'=>'邮件格式错误'));
    		exit();
    	} 
    	
    	$code = Custom_Model_Encryption::getInstance() -> encrypt($this->_memberInfo['user_id'].'#'.$this->_memberInfo['email'],'EncryptCode');
    	$verifyUrl = "http://{$_SERVER['HTTP_HOST']}/auth/verifyemail/code/{$code}";
    	$templateValue = array();    	
    	$templateValue['user_name'] = $this -> _memberInfo['user_name'];
    	$templateValue['shop_name'] = Zend_Registry::get('config') -> name;
    	$templateValue['validate_email'] = $verifyUrl;
    	$templateValue['send_date'] = date("Y年m月d日 H:i:s");  
    	
    	//邮件发送 
    	$template = new Shop_Models_API_EmailTemplate();
    	$template = $template -> getTemplateByName('register_validate', $templateValue);
    	$mail = new Custom_Model_Mail();
    	if ($mail -> send($email, $template['title'], $template['value'])) {    	  
    	   echo  Zend_Json::encode(array('status'=>1,'msg'=>'邮件发送失败'));
    	   exit();
    	} else {
    	   $this->_member->editEmail($email);
    	   echo  Zend_Json::encode(array('status'=>1,'msg'=>'邮件发送成功，请到收件箱查看验证邮件！'));
    	   exit();
    	}  	
    	
    }
    
    /**
     * 会员修改密码
     *
     * @return void
     */
    public function passwordAction()
    {
        if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $result = $this -> _member -> editPassword($this -> _request -> getPost());
            switch ($result) {
        		case 'noSamePassword':
        		    Custom_Model_Message::showAlert(self::NO_SAME_PASSWORD,true, '/member/password');
        		    break;
        		case 'errorPassword':
        		    Custom_Model_Message::showAlert(self::ERROR_PASSWORD,true, '/member/password');
        		    break;
        		case 'errorOldPassword':
        		    Custom_Model_Message::showAlert(self::ERROR_OLD_PASSWORD,true, '/member/password');
        		    break;
        		case 'noPassword':
        		    Custom_Model_Message::showAlert(self::NO_PASSWORD,true, '/member/password');
        		    break;
        		case 'editPasswordSucess':
        		    $this -> _auth -> updateAuth();
        		    Custom_Model_Message::showAlert(self::EDIT_PASSWORD_SUCESS ,true, '/member');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error!');
        	}
        } else {
			$member = $this -> _memberInfo;
			$auth = $this -> _auth -> getAuth();
			$this -> view -> member = array_merge($auth, $member);
            $this -> view -> action = 'password';
            $this -> view -> title = '修改密码';
        }
        
        $this->view->nav_1_personal = ' on ';
        $this->view->nav_2_personal_pass = ' c ';
                
    }
	
	/**
     * 会员订单列表
     *
     * @return void
     */
	public function orderAction()
	{
		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$order = $this -> _member -> getAllOrder($this -> _request -> getParams(), $page, 20);
		//全部订单
		$tmp = $this -> _member ->  getOrderByStatusCount();
		$this-> view -> allorder = $tmp;
		//待支付订单
		$tmp = $this -> _member -> getOrderByStatusCount(array('status'=>0,'status_pay'=>0));
		$this-> view -> nopay = $tmp;
		//待收货
		$tmp = $this -> _member ->  getOrderByStatusCount(array('status'=>0,'status_logistic'=>3,'status_pay'=>2));
		$this-> view -> send = $tmp;
		
		//交易完成
		$tmp = $this -> _member ->  getOrderByStatusCount(array('status'=>0,'status_logistic'=>4,'status_pay'=>2));
		$this-> view -> okorder = $tmp;
		
		$orderModel = new Shop_Models_DB_Order();
		
		foreach($order['order'] as $key=>$val)
		{
			$batch_sn = $val['batch_sn'];
			$order['order'][$key]['goods'] = $orderModel->getOrderGoodsByBatchSn($batch_sn);
		}
		/*echo '<pre>';
		print_r($order);
		exit;*/
		$pageNav = new Custom_Model_PageNav($order['total']);
		
		$this -> view -> total = $order['total'];
		$this -> view -> orderInfo = $order['order'];
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        $this -> view -> showTip = 'allow';
        $this -> view -> params = $this -> _request -> getParams();
	}

	/**
     * 会员订单详细
     *
     * @return void
     */
	public function orderDetailAction()
	{
		$batchSN = $this -> _request -> getParam('batch_sn');
		if ($this -> _request -> getPost()) {
			if ($this -> _request -> getPost('change') == 'payment') {
				$result = $this -> _member -> setOrderPayment($this -> _request -> getPost());
			    switch ($result) {
        		    case 'noPayment':
        		        Custom_Model_Message::showAlert(self::NO_PAYMENT, false);
        		        break;
        		    case 'hasConfirm':
        		        Custom_Model_Message::showAlert(self::HAS_CONFIRM, false);
        		        break;
        		    case 'isCancel':
        		        Custom_Model_Message::showAlert(self::IS_CANCEL, false);
        		        break;
        		    case 'hasPay':
        		        Custom_Model_Message::showAlert(self::HAS_PAY, false);
        		        break;
        		    case 'setOrderPaymentSucess':
        		        Custom_Model_Message::showAlert(self::SET_ORDERPAYMENT_SUCESS, false);
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showAlert('error', false);
			    }
			} elseif ($this -> _request -> getPost('change') == 'address') {
                echo "<script>parent.document.getElementById('dosubmit').value=' 确定 ';parent.document.getElementById('dosubmit').disabled=false;</script>";
				$result = $this -> _member -> setOrderAddress($this -> _request -> getPost());
			        switch ($result) {
                    case 'noEdit':
        		        Custom_Model_Message::showAlert(self::NO_EDIT);
                        break;
        		    case 'noArea':
        		        Custom_Model_Message::showAlert(self::NO_AREA);
        		        break;
        		    case 'noConsignee':
        		        Custom_Model_Message::showAlert(self::NO_CONSIGNEE);
        		        break;
        		    case 'noAddress':
        		        Custom_Model_Message::showAlert(self::NO_ADDRESS);
        		        break;
        		    case 'errorPhone':
        		        Custom_Model_Message::showAlert(self::ERROR_PHONE);
        		        break;
        		    case 'errorMobile':
        		        Custom_Model_Message::showAlert(self::ERROR_MOBILE);
        		        break;
        		    case 'setOrderAddressSucess':
        		        Custom_Model_Message::showAlert(self::SET_ORDERADDRESS_SUCESS, false);
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showAlert('error');
			    }
			} elseif ($this -> _request -> getPost('change') == 'sms_no') {
			    $this -> _member -> setOrderSmsNo($this -> _request -> getPost());
			    Custom_Model_Message::showAlert(self::SET_ORDERSMSNO_SUCESS, false);
			}
     	}
     	
		if ($batchSN) {
            //更新订单数据 开始
            $order = new Admin_Models_API_Order();
            $order -> orderDetail($batchSN);
            //更新订单数据 结束
            
			$this -> view -> action = 'order-detail';
            $order = @array_shift($this -> _member -> getOrderBatch($batchSN));
			if(!$order){
				$this -> _redirect('/member/order');
				}
            $order['price_logistic'] = floatval($order['price_logistic']);
            $order['price_pay'] = floatval($order['price_pay']);
            $order['price_adjust'] = floatval($order['price_adjust']);
            $order['price_from_return'] = floatval($order['price_from_return']);        
			$this -> view -> order = $order;
            $this -> view -> payed = $order['price_payed'] + $order['price_from_return'];
            $this -> view -> blance = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
            $data = $this -> _member -> getOrderBatchGoods($batchSN);
            $this -> view -> data = $data;
			$this -> view -> product = $product = $data['product_all'];
			$this -> view -> paymentButton = $this -> _member -> getPayInfo($this -> _member -> getOrderByBatchSN($batchSN));
			$this -> view -> payment = $this -> _member -> getOtherPaymentList($order);
			$this -> view -> logistic = $this -> _member -> getLogistic(array('logistic_code' => $order['logistic_code']));
			$this -> view -> province = $this -> _member -> getChildAreaById(1);
			
			$this->view->css_more = ',user.css,order.css';
			$order['addr_province_id'] ? $this -> view -> city = $this -> _member -> getChildAreaById($order['addr_province_id']) : '';
			if ($order['addr_city_id']) {
			    $area = $this -> _member -> getChildAreaById($order['addr_city_id']);
			    $area[-1] = '其它区';
			    $this -> view -> area = $area;
			}
            $auth = $this -> _auth -> getAuth();
            //$this -> view -> member = array_shift($this -> _member -> getMemberByUserName($auth['user_name']));
		} else {
			exit;
		}
		
		$this->view->css_more = ',member.css';
	}
   
    /**
     * 修改支付方式
     */	
	public function changePaymentAction()
	{

		$batchSN = $this -> _request -> getParam('batch_sn');
		$submitted = $this -> _request -> getParam('submitted');
		if ($submitted) {
			$result = $this -> _member -> setOrderPayment($this -> _request -> getPost());
			switch ($result) {
				case 'noPayment':				
					echo Zend_Json::encode(array('status'=>0,'msg'=>self::NO_PAYMENT));exit;					
					break;
				case 'hasConfirm':
					echo Zend_Json::encode(array('status'=>0,'msg'=>self::HAS_CONFIRM));exit;
					break;
				case 'isCancel':
					echo Zend_Json::encode(array('status'=>0,'msg'=>self::IS_CANCEL));exit;
					break;
				case 'hasPay':
					echo Zend_Json::encode(array('status'=>0,'msg'=>self::HAS_PAY));exit;
					break;
				case 'setOrderPaymentSucess':
					//$paybox = $this -> _member -> getPayInfo($this -> _member -> getOrderByBatchSN($batchSN));
					echo Zend_Json::encode(array('status'=>1,'msg'=>self::SET_ORDERPAYMENT_SUCESS,'link'=>'/flow/order-success/batch_sn/'.$batchSN));					
					exit;
					break;
				case 'error':
					echo Zend_Json::encode(array('status'=>0,'msg'=>'更改失败'));
					exit;	
			}
		}
		
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);	
		//更新订单数据 开始
		$order = new Admin_Models_API_Order();
		$order -> orderDetail($batchSN);
		//更新订单数据 结束
		
		$this -> view -> action = 'order-detail';
		$order = @array_shift($this -> _member -> getOrderBatch($batchSN));
		if(!$order){
			$this -> _redirect('/member/order');
		}
		$order['price_logistic'] = floatval($order['price_logistic']);
		$order['price_pay'] = floatval($order['price_pay']);
		$order['price_adjust'] = floatval($order['price_adjust']);
		$order['price_from_return'] = floatval($order['price_from_return']);
		$this -> view -> order = $order;
		$this -> view -> payed = $order['price_payed'] + $order['price_from_return'];		
		$this -> view -> payment = $this -> _member -> getOrderPaymentList($order);
	
		
	}
	
	/**
     * 会员留言列表
     *
     * @return void
     */
	public function messageAction()
	{
		if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $result = $this -> _member -> addMessage($this -> _request -> getPost());
            echo "<script>parent.document.getElementById('dosubmit').value='提交留言';parent.document.getElementById('dosubmit').disabled=false;parent.document.formMsg.msg_type[0].checked=true;parent.document.formMsg.msg_content.value='';</script>";
            switch ($result) {
        		case 'noMessage':
        		    Custom_Model_Message::showAlert(self::NO_MESSAGE);
        		    break;
        		case 'toLongMesssage':
        		    Custom_Model_Message::showAlert(self::TO_LONG_MESSAGE);
        		    break;
        		case 'addMessageSucess':
        		    Custom_Model_Message::showAlert(self::ADD_MESSAGE_SUCESS);
        			$this -> _redirect('/member/message/type/'.$this -> _request -> getParam('msg_type',0).'/page/1');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error!');
        	}
        } else {
		    $page = (int)$this -> _request -> getParam('page', 1);
            $page = ($page <= 0) ? 1 : $page;
		    $message = $this -> _member -> getAllMessage($type, $page, 5);
		    
		    if ($message['message']) {
		    	foreach ($message['message'] as $key => $value)
		    	{
		    		$message['message'][$key]['type'] = $this -> _shopMsgType[$message['message'][$key]['type']];
		    	}
		    }
		    $pageNav = new Custom_Model_PageNav($message['total'], 5, 'message');
		    $this -> view -> type = $type;
		    if ($type == 'order' && $this -> _request -> getParam('id')) {
			    $this -> view -> order = $this -> _member -> getOrderById($this -> _request -> getParam('id'));
		    }
		    $this -> view -> action = 'message';
		    $this -> view -> msgType = $this -> _shopMsgType;
		    $this -> view -> messageInfo = $message['message'];
            $this -> view -> pageNav = $pageNav -> getPageNavigation();
		}
		
        $this->view->nav_1_note = ' on ';
        $this->view->nav_2_note_list = ' c ';
				
	}
	
	/**
     * 会员收货地址列表
     *
     * @return void
     */
	public function addressAction()
	{
		if ($this -> _request -> isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$result = $this -> _member -> editAddress($this -> _request -> getPost());
			switch ($result) {
        		case 'noArea':
        		    exit(self::NO_AREA);
        		    break;
        		case 'noConsignee':
        		    exit(self::NO_CONSIGNEE);
        		    break;
        		case 'noAddress':
        		    exit(self::NO_ADDRESS);
        		    break;
        		case 'errorZip':
        		    exit(self::ERROR_ZIP);
        		    break;
        		case 'errorPhone':
        		    exit(self::ERROR_PHONE);
        		    break;
        		case 'errorMobile':
        		    exit(self::ERROR_MOBILE);
        		    break;
        		case 'errorEmail':
        		    exit(self::ERROR_EMAIL);
        		    break;
        		case 'errorFax':
        		    exit(self::ERROR_FAX);
        		    break;
        		case 'tooManyAddress':
        		    exit(self::TOO_MANY_ADDRESS);
        		    break;
        		case 'errorzip':
        		    exit(self::ERROR_ZIP);
        		    break;
        		case 'editSucess':
        		    exit('success');
			}
		} 
		//设为默认收货地址
		$aid = intval($this->_request->getParam('aid'));
		$type = $this->_request->getParam('type');
		if($aid && 'default' == $type){
			$addressDb = new Shop_Models_DB_MemberAddress();
			$auth = $this -> _auth -> getAuth();
			$member_id = $auth['member_id']; 
			$addressDb->updateDefaultAddress($aid, $member_id);
			exit('ok');
		}
		//删除收货地址
	    if($aid && 'delete' == $type){
			$addressDb = new Shop_Models_DB_MemberAddress();
			$user = $this -> _auth -> getAuth();
			$addressDb->deleteAddress($aid,$user['member_id']);
			exit('ok');
		}
		/*$address = array();
		$arr = $this -> _member -> getAddress();
		foreach($arr as $val)
		{
			$str = $val[province_msg][area_name].'&nbsp;'.$val[city_msg][area_name].'&nbsp;'.$val[area_msg][area_name];
			$tel = $val[mobile] ? $val[mobile] : $val[phone];
			$address[$val[address_id]]= array(
				consignee     => $val[consignee],
				province_id   => $val[province_id],
				city_id       => $val[city_id],
				area_id       => $val[area_id],
				address       => $val[address],
				phone         => $val[phone],
				mobile        => $val[mobile],
				zip           => $val[zip],
				str           => $str,
				tel           => $tel,
				is_default    => $val[is_default],
				address_id    => $val[address_id],
				
 			);
		}*/
		$this -> view -> memberAddress = $this -> _member -> getAddress();
		$province = $this -> _member -> getChildAreaById(1);
		$arr = array();
		foreach ($province as $key=>$val){
			$arr[$key]=$val['area_name'];
		}
		$num = count($this -> view -> memberAddress);
		$this -> view -> province = $arr;		
		$this -> view -> addressNum = $num;
		$this -> view -> nextNum = 5-$num;
	}
	
	/**
	 * 
	 * 
	 */
	public function getAddressAction()
	{
		$request = $this->getRequest();
		$aid = $request->getParam('aid');
		$province = $this->_member->getChildAreaById(1);
		$addressDbModel = new Shop_Models_DB_MemberAddress();
		$rows = $this->_member->getAddressById($aid);
		$province_id = $rows[province_id];
	
		if($province_id)
			$city = $this->_member->getChildAreaById($province_id);
		$city_id = $rows[city_id];
	
		if($city_id)
			$area = $this ->_member->getChildAreaById($city_id);
		$area_id = $rows[area_id];
		$html  = '<div class="pop_close_btn" onclick="closeMsg(\'#pop_revise_adr\')"></div>';
        $html .= '<div class="pop_name pop_name01"><span>* </span>收货人姓名：</div>';
        $html .= '<div class="pop_input01"><input type="text" name="consignee" id="consignee" value="'.$rows[consignee].'"/></div>';
        $html .= '<div class="pop_name pop_name02"><span>* </span>配送区域：</div>';
        $html .= '<div class="pop_select">';
        $html .= '<select name="province_id" id="province_id" onchange="getCity(this)"><option value="0">请选择省</option>';  
        foreach ($province as $key=>$val)
        {
        	$selected = ($key == $province_id)?'selected="selected"':'';
        	$html .= '<option value="'.$key.'"'.$selected.'>'.$val[area_name].'</option>';	
        }       
        $html  .= '</select><select name="city_id" id="city_id" onchange="getArea(this)"> <option value="">请选择市</option>';  
        foreach($city as $key=>$val){
        	$selected = ($key == $city_id)?'selected="selected"':'';
        	$html .= '<option value="'.$key.'"'.$selected.'>'.$val[area_name].'</option>';
        }
        $html .= '</select> <select name="area_id" id="area_id" onchange="getAreaCode()"><option value="">请选择区</option>';
        foreach($area as $key=>$val){
        	$selected = ($key == $area_id)?'selected="selected"':'';
        	$html .= '<option value="'.$key.'" code="'.$val[code].'"'.$selected.'>'.$val[area_name].'</option>';
        }
        $tel = explode('-', $rows[phone]);
        $html .= '</select></div>';
        $html .= '<div class="pop_name pop_name03"><span>* </span>详细地址：</div>';
        $html .= '<div class="pop_input02"><input type="text" name="address" id="address" size="30" maxlength="100" value="'.$rows[address].'"/></div>' ;
        $html .= '<div class="pop_name pop_name_last"><span> </span>英文地址：</div>';
        $html .= '<div class="pop_input_last"><input type="text" name="eng_address" id="eng_address" size="30" maxlength="100" value="'.$rows[eng_address].'"/></div>' ;
        $html .= '<div class="pop_name pop_nameCode"><span>*</span>邮政编码：</div>';
        $html .= '<div class="pop_inputCode"><input type="text" name="zip" id="postalcode" value="'.$rows[zip].'"></div>';
        $html .= '<div class="pop_name pop_name04"><span>* </span>联系电话：</div>';  
        $html .= '<div class="pop_input03">手机或者固话任填一项</div>';
        $html .= '<div class="pop_name pop_name05"><span>* </span>手机：</div>';   
        $html .= '<div class="pop_input01 pop_input04"><input type="text" name="mobile" id="mobile" size="25" maxlength="20" value="'.$rows[mobile].'"/></div>';   
        $html .= '<div class="pop_name pop_name06">固话：</div>';
        $html .= '<div class="pop_input05"><input type="text" name="phone_qh" id="phone_qh" value="'.$tel[0].'"/></div>';
        $html .= '<div class="pop_input05 pop_input06"><input type="text" name="phone_no" id="phone_no" value="'.$tel[1].'"/></div>';
        $html .= '<div class="pop_input05 pop_input07"><input type="text" name="phone_fj" value="'.$tel[2].'"/></div>';
        $html .= '<input type="hidden" name="address_id" value="'.$aid.'" />';
        $html .= '<div class="pop_baocun_btn"><img src="/public/images/pop_baocun_btn.png" onclick="addressSubmit()"/></div>';
                                      
        echo $html;
		exit;
	}
	
	/**
     * 取得配送区域
     *
     * @return void
     */
     public function areaAction()
     {
     	$this -> _helper -> viewRenderer -> setNoRender();
     	$id = $this -> _request -> getParam('id', null);
     	$area = $this -> _member -> getChildAreaById($id);
     	if ($area) {
     	    exit(Zend_Json :: encode($area));
     	}
     }
     
     /**
     * 更新会员订单支付方式
     *
     * @return void
     */
     public function setOrderPaymentAction()
     {
     	if ($this -> _request -> getPost()) {
     		$result = $this -> _member -> setOrderPayment($this -> _request -> getPost());
			switch ($result) {
        		case 'noPayment':
        		    Custom_Model_Message::showAlert(self::NO_PAYMENT);
        		    break;
        		case 'setOrderPaymentSucess':
        		    Custom_Model_Message::showAlert(self::SET_ORDERPAYMENT_SUCESS);
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error');
			}
     	}
     }
     
     /**
     * 取消订单
     *
     * @return void
     */
     public function cancelOrderAction()
     {
        $this -> _helper -> viewRenderer -> setNoRender();
     	$batchSN = $this -> _request -> getParam('batch_sn', null);
     	if ($batchSN) {
     	    $result = $this -> _member -> cancelOrder($batchSN);
			switch ($result) {
        		case 'noCancel':
        		    Custom_Model_Message::showAlert(self::NOT_CANCEL, false, '/member/order/');
        		    break;
        		case 'setOrderCancelSucess':
        		    Custom_Model_Message::showAlert(self::SET_ORDERCANCEL_SUCESS, false, '/member/order/');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error', false, '/member/order/');
			}
     	}
     	exit('参数错误');
     }
     
	/**
     * 满意不退货
     *
     * @return void
     */
    public function favAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
     	$batchSN = $this -> _request -> getParam('batch_sn', null);
        $result = $this -> _member -> fav($batchSN);
        switch ($result) {
            case 'setOrderFavSucess' :
                Custom_Model_Message::showAlert(self::SET_FAV_SUCESS, false, '/member/order/');
            case 'error':
                Custom_Model_Message::showAlert('错误，操作失败！', false, '/member/order/');
        }
    }
  
    /**
     * 我的收藏
     * @return void
     */

    public function favoriteAction()
    {
  		$page = intval($this -> _request -> getParam('page'));
  		$page = $page ? $page : 1;
		$favorite = $this -> _member -> getFavorite($page, 20);
		$pageNav = new Custom_Model_PageNav($favorite['total'],20);
		$this -> view -> info = $favorite['info'];
        $this -> view -> pageNav = $pageNav -> getPageNavigation(); 
        $this -> view -> total = $favorite['total']; 
    }
	
	/**
     * 会员帐户首页
     *
     * @return void
     */
	public function indexAction()
	{
		//我的信息
		
		//$this -> view -> coupons = $this -> _member -> getValidCoupon();
		
		//订单统计
		$order = $this -> _member -> getOrderByStatusCount(array('status'=>0,'status_pay'=>0));//需付款订单
		$this -> view -> feeOrder = $order;
		$order = $this -> _member -> getOrderByStatusCount(array('status'=>0,'status_logistic'=>3,'status_pay'=>2));//待收货
		$this -> view -> feeOrder2 = $order;
		/*
		//浏览历史
		$goods_api = new Shop_Models_API_Goods();
		$goods = $goods_api -> getHistory();
		$this -> view -> history = array_slice($goods, 0, 3);
		*/
		//收藏
		$favorite = $this -> _member -> getFavorite(1, 3);
		$this -> view -> fav = $favorite['total'];
		//为我推荐
		$goodsApi = new Shop_Models_API_Goods();
		$indextag = $goodsApi ->getGoodsTag('tag_id = 4');
		$this->view->tui =$tui = $indextag['4']['details'];
	}


	/**
     * 会员账户余额支付
     *
     * @return void
     */
    public function priceAccountPayedAction()
    {
		$batchSN = $this -> _request -> getParam('batch_sn');
		$priceAccount = (float)$this -> _request -> getParam('price_account', null);
                
        $result = $this -> _member -> priceAccountPayed($batchSN, $priceAccount);
        switch ($result) {
            case 1 :
                Custom_Model_Message::showAlert("帐户余额不足");
            case 2 :
                Custom_Model_Message::showAlert("取消单和无效单无需再支付");
            case 3 :
                Custom_Model_Message::showAlert("货到付款，无需再支付");
            case 4 :
                Custom_Model_Message::showAlert("该单已经付清，无需再支付");
            case 5 :
                Custom_Model_Message::showAlert("支付金额不能小于0");
            case 6 :
                Custom_Model_Message::showAlert("支付金额不能大于需支付金额");
            case 7 :
                Custom_Model_Message::showAlert('error');
            default :
                Custom_Model_Message::showAlert("帐户余额支付成功",false, '/member/order-detail/batch_sn/'.$batchSN);
        }
        exit;
    }
	
	/**
     * 会员账户余额列表
     *
     * @return void
     */
	public function moneyAction()
	{
		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$money = $this -> _member -> getAllMoney($page, 20);
		$pageNav = new Custom_Model_PageNav($money['total']);
		$this -> view -> money = $this -> _memberInfo['money'];
		$this -> view -> moneyInfo = $money['money'];
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
		
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_blance = ' c ';
		$this->view->cn_amount_tab_money = ' c ';
	}
	
	
	
	/**
     * 会员积分列表
     *
     * @return void
     */
	public function pointAction()
	{
		$rank = $this -> _memberRanks[$this -> _memberInfo['rank_id']];
		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$point = $this -> _member -> getAllPoint($page, 20);
		$pageNav = new Custom_Model_PageNav($point['total']);
		$this -> view -> point = $this -> _memberInfo['point'];
		$this -> view -> pointInfo = $point['point'];
        if($this -> _memberInfo['point']>500){
             $this -> view -> actexchange =1;
             $this -> view -> ableact =floor($this -> _memberInfo['point']/500)*500;
        }
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_point = ' c ';
				  
        
	}

	/**
     * 经验值变动历史
     *
     * @return void
     */
    public function experienceAction()
    {
        $page = (int)$this ->_request->getParam('page', 1);

		$params = array(
			'member_id' => $this->_memberInfo['member_id'],
		);

        $count  = $this->_member->getExperienceCount($params);

		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_member->getExperienceList($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
		
        $this->view->pageNav       = $pageNav->getPageNavigation();
        $this->view->infos         = $infos;
		$this->view->params        = $params;
		$this->view->experience    = $this->_memberInfo['experience'];
		$this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_experience = ' c ';
    }

	/**
     * 站内信列表
     *
     * @return void
     */
	public function insideMessageAction()
	{
		$message_db = new Shop_Models_API_Message();
		$page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
		$params['member_id'] = $this->_memberInfo['member_id'];
		$params['read']      = isset($params['read']) ? intval($params['read']) : '0';
        $count  = $message_db->getCount($params);
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $message_db->browse($params, $limit);
		}

        $this->view->infos  = $infos;
		$this->view->params = $params;
		$this->view->params = $params;
		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
		
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        
        $this->view->nav_1_note = ' on ';
        $this->view->nav_2_note_msg = ' c ';
                
	}

	/**
     * 查看站内消息
     *
     * @return void
     */
	public function viewMessageAction()
	{
		$this ->_helper->viewRenderer->setNoRender();
		$message_db = new Shop_Models_API_Message();
		$message_id = intval($this->_request->getParam('message_id', 0));
		if ($message_id < 1) {
			exit(json_encode(array('success' => 'false', 'message' => 'ID不正确')));
		}

		$info = $message_db->get($message_id);
		if (count($info) < 1) {
			exit(json_encode(array('success' => 'false', 'message' => '没有找到相关记录')));
		}

		$read = $this->_request->getParam('read');
		if ($read != '1') {
			$params['member_id'] = $this->_memberInfo['member_id'];
			$params['message_id'] = $message_id;
			$message_db->updateMessageMemberInfo($params, array('is_read' => 1));
		}
		exit(json_encode(array('success' => 'true', 'data' => $info)));
	}


	/**
     * 用户礼品卡
     *
     * @return void
     */
    public function giftCardAction()
    {
  		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        
        $where = array();
        $user =  $this -> _auth -> getAuth();
        $where['user_id'] =  $user['user_id'];
        $type =  $this -> _request -> getParam('type','0');     
        $where['status'] = array('neq'=>2);
        switch ($type)
        {
        	case 1: //有效        		
        		$where['status'] = 0; 
        		$where['end_date'] = array('gt'=>date('Y-m-d'));
        		break;
        	case 2://作废
        		$where['status'] = 1;
        		break;
        	case 3: //过期
        		$where['end_date'] = array('lt'=>date('Y-m-d'));
        		break;
        }
        $card = $this -> _member -> getGiftCardList($where,$page, 20);
		
		
		$pageNav = new Custom_Model_PageNav($card['total']);
		$this -> view -> info = $card['info'];
		$this -> view -> curtime = date('Y-m-d');
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        $this->view->type =  $type;
        
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_xianjinquan = ' c ';
                
    }
    
    public  function giftBuyAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$page = ($page <= 0) ? 1 : $page;
    	
    	$where = array();
    	$user =  $this -> _auth -> getAuth();
    	$where['buyer_id'] =  $user['user_id'];
    	$where['status'] = array('neq'=>2);
    	$type =  $this -> _request -> getParam('type','0');    	
    	switch ($type)
    	{
    		case 1: //有效
    			$where['status'] = 0;
    			$where['end_date'] = array('gt'=>date('Y-m-d'));
    			break;
    		case 2://作废
    			$where['status'] = 1;
    			break;
    		case 3: //过期
    			$where['end_date'] = array('lt'=>date('Y-m-d'));
    			break;
    	}
    	$card = $this -> _member -> getGiftCardList($where,$page, 20);
    	$this->view->type =  $type;
    	
    	$pageNav = new Custom_Model_PageNav($card['total']);
    	$this -> view -> info = $card['info'];
    	$this -> view -> curtime = date('Y-m-d');
    	$this -> view -> pageNav = $pageNav -> getPageNavigation();
    	$this->view->nav_1_account = ' on ';
    	$this->view->nav_2_account_xianjinquan = ' c ';
    }  

	/**
     * 用户礼品卡历史记录
     *
     * @return void
     */
    public function giftCardLogAction()
    {
  		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        $where = array();
        $card_sn = $this -> _request -> getParam('card_sn');
        if($card_sn){
        	$where['card_sn'] =  $card_sn;
        }
        
        $this->view ->card_sn = $card_sn;
		$card = $this -> _member -> getGiftCardLog($where,$page, 20);
		$pageNavLog = new Custom_Model_PageNav($card['logTotal']);
		$this -> view -> logInfo = $card['logInfo'];
        $this -> view -> pageNavLog = $pageNavLog -> getPageNavigation();
    }
    
    /**
     * 用户礼金券信息
     *
     * @return void
     */
    public function couponAction()
    {
        $auth = $this -> _auth -> getAuth();
        $card_api = new Shop_Models_API_Card();
  		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        $type = (int)$this -> _request -> getParam('type', 1);
  		$auth = $this -> _auth -> getAuth();
		$coupons = $this -> _member -> getCoupon($page, 20, $type);
		if ($coupons['content']) {
			$i = 0;
			$date = date('Y-m-d');
			foreach ($coupons['content'] as $key => $coupon)
			{
				$coupons['content'][$key]['card_sn'] = strtoupper($coupons['content'][$key]['card_sn']);
                
				if ( ($coupon['card_type'] == 0) || ($coupon['card_type'] == 1) ||($coupon['card_type'] == 4) ) {
				    if ($coupon['card_type'] == 4) {
				        $coupons['content'][$key]['coupon_price'] = $coupons['content'][$key]['coupon_price'] / 10;
				    }
				    $coupons['content'][$key]['goods_info'] = unserialize($coupons['content'][$key]['goods_info']);
				}
				else if ($coupon['card_type'] == 3) {
                    $goods_api = new Shop_Models_API_Goods();
				    $goods_info = unserialize($coupons['content'][$key]['goods_info']);
				    if (count($goods_info) == 1) {
				        foreach ($goods_info as $goods_sn => $value) {
				            $goods_data = $goods_api->getGoodsInfo(" and goods_sn='$goods_sn'");
				            $coupons['content'][$key]['goods_name'] = $goods_data['goods_name'];
				        }
				    }
				}
			}
		}
		$pageNav = new Custom_Model_PageNav($coupons['total']);
		$this -> view -> coupons = $coupons['content'];
		$this -> view -> curtime = date('Y-m-d');
		$this -> view -> total = $coupons['total'];
		$this -> view -> type = $type;
        $this -> view -> pageNav = $pageNav -> getPageNavigation();  
    }
    
    public function delFavoritesAction()
    {
    	$result = $this->_request->getParam('favorite_ids',null);
    	if($result){
    		$tmp = explode(',', $result);
    		$rs = $this -> _member -> deleteFavorite($tmp);
    		if($rs){
    			exit("删除成功");
    		}else{
    			exit("系统出错");
    		}
    	}else{
    		exit("参数错误");
    	}
    } 
}