<?php
class FlowController extends Zend_Controller_Action
{
    /**
     * 
     * @var Shop_Models_API_Cart
     */
	protected $_api = null;
	protected $_session = 'cart';
    protected $user = null;
    protected $_auth = null;
    protected $_member = null;
    const NO_AREA = '请选择地区!';
	const NO_CONSIGNEE = '请填写收货人!';
	const NO_ADDRESS = '请填写详细地址!';
	const ERROR_PHONE = '请填写正确的电话号码!';
	const ERROR_FAX = '请填写正确的传真格式!';
	const ERROR_ZIP = '请填写正确的邮政编码！';
	public function init()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	    $this ->_auth = Shop_Models_API_Auth :: getInstance();
	    $this -> user = $this ->_auth -> getAuth();
		$this -> _api = new Shop_Models_API_Cart();
        $this -> _session = new Zend_Session_Namespace($this -> _userCertificationName);	  
        $this -> view -> auth = $this -> user;
        $this -> _member = new Shop_Models_API_Member();
	}

    public function indexAction()
    {
        //购物车产品显示
		if($this->_cart()){
			$this -> view -> err = 1;
		}else{
			$this -> view -> err = 2;
		}
		$this -> view -> flow_index = 1;
    	$this -> view -> css_more = ',cart.css';
    	$this -> view -> js_more = ',cart.js';
    	

    }
    /**
     * 活动页面商品快速放入购物车
     *
     * @return void
     */
    public function actbuyAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $productSN = $this -> _request -> getParam('product_sn', null);
        $number = $this -> _request -> getParam('number', 1);
        echo $this -> _api -> buy($productSN, $number);
        exit;
    }
    
    public function fillinAction()
    {
       //检测是否登录
       if (!$this -> user) {
        	$this -> _redirect('/login.html');
        }
    	$this -> view -> memberAddress = $this -> _member -> getAddress();
    	$this -> view -> count = count($this -> view -> memberAddress);
    	$province = $this -> _member -> getChildAreaById(1);
		$this -> view -> province = $province;

		//购物车产品显示
		if(!$this->_cart()){
		    Custom_Model_Message::showAlert($this -> view -> alert,true,'/flow/index/');
			//$this -> _redirect('/flow/index/');
		}
		
		$this -> view -> css_more = ',cart.css';
		$this -> view -> js_more = ',cart.js';
		
		$this->view->order_note = $this->_api->getSetting('order_note');
		$this->view->flow_index = 2;
		//print_r($delivery);
		//exit;
        
    }
    
    public function orderAction()
    {
    	//检测是否登录
    	if (!$this -> user) {
    		$this -> _redirect('/login.html');
    	}
    	$request = $this->getRequest();
    	if($request->isPost())
    	{
    		$address_id = intval($request->getPost('selected'));
    		if(empty($address_id)){
    			$this -> _redirect('/flow/fillin');
    		}
    		$this ->_api->setSetting('address_id', $address_id);
    		$order_note = $request->getParam('order_note');
    		$this ->_api->setSetting('order_note', $order_note);
    		$this->view->order_note = $order_note;
    	}
    	if(!$this->_cart()){
    	    Custom_Model_Message::showAlert($this -> view -> alert,true,'/flow/index/');
    		//$this -> _redirect('/flow/index/');
    	}
    	//收货地址
    	if(!$address_id){
	    	$user = $this -> user;
	    	$address_id = $this -> _member -> getDefaultAddressId($user['member_id']);
	    	$this ->_api->setSetting('address_id', $address_id);
	    	if(!$address_id)
	    		$this -> _redirect('/flow/fillin');
    	}
        $this -> view -> address = $this->_member->getAddressById($address_id);
        $this->view->flow_index = 2;
    	$this -> view -> css_more = ',cart.css';
    }
    
    public function addOrderAction()
    {
    	$request = $this->getRequest();
    	if($request->isPost())
    	{
    		$order_note = $request->getParam('order_note');
    		
    		$data = array(
    		          'note'            => $order_note,
					  'invoice_type'    => '0',
					  'invoice'         => '',
      		          'invoice_content' => '',
                      'is_visit'        => 0,
                      'price_account'   => 0,
                      'price_point'     => 0,
                      'sms_no'          => 'sf',
    		);
    				
    		// 新增订单
    		$address = $this->_member->getAddressById($this->_api->getSetting('address_id'));
      		$msg = $this -> _api -> addOrder($data, $address);
      		if($msg == 'goods_error' || $msg == 'price_error'){
      			$this -> _redirect('/flow/index/');
      		}elseif($msg == 'stock_error'){
      		    Custom_Model_Message::showAlert('商品库存不足，请检查购物车！',true,'/flow/index');
      		}
      		else{
      			$this -> _redirect('/flow/payment');
      		}
    	}
    	exit;
    }
    
    public function paymentAction()
    {
    	$order_info = $this->_api->getSetting('order_info');
    	$order_info = unserialize($order_info);
    	$account = 0;
    	if(array_key_exists('H', $order_info))
    	{
    		$account += $order_info['H']['account'];
    		$this -> view -> hongkong = $h_order = $order_info['H'];
    	}
        if(array_key_exists('J', $order_info))
    	{
    		$account += $order_info['J']['account'];
    		$this -> view -> japanese = $j_order = $order_info['J'];
    		$order = array_shift($this->_api->getOrderBatch(array('batch_sn'=>$j_order['order_sn'])));
    		$payment =  array_shift($this->_api-> getPayment(array('pay_type' => $order['pay_type'])));
    		$this -> view -> payment =$payment;
    		$this -> view -> pay_info_J =  $this -> _api -> getPayInfo($order);
    	}
    	$this -> view -> account = $account;
		$this->view->flow_index = 3;
		
		
    	$this -> view -> css_more = ',cart.css';
    }
    
    /**
     * 修改购物车中的指定商品的数量
     *
     * @return void
     */
    public function changeAction()
    {
        $productID = intval($this -> _request -> getParam('product_id', 0));
        $number = intval($this -> _request -> getParam('number', 0));
        $number = $number > 0 ? $number : abs($number);
        if ($productID && $number) {
            echo $this -> _api -> change($productID, $number);
        }
        exit;
    }
    
    
    public function delAction()
    {
        $productID = intval($this -> _request -> getParam('product_id', null));
        $number = intval($this -> _request -> getParam('number', 0));
        $this -> _api -> del($productID, $number);
        header('Location: ' . $this->getFrontController() -> getBaseUrl() . '/flow/index');
    }

    public function clearAction()
    {
        $this -> _api -> setCartCookie('');
        setcookie('p', '', time () + 86400 * 2, '/');
        setcookie('gift', '', time () + 86400 * 2, '/');
        setcookie('groupgoods', '', time () + 86400 * 2, '/');
        setcookie('goods_gift','',-1,'/');        
        setcookie('order_gift','',-1,'/');
        $this -> delNormalCard();
        header('Location: '.$this -> getFrontController() -> getBaseUrl().'/flow/index');
    }
    
    protected function _cart()
    {
    	$this -> _apiCart = new Shop_Models_API_Cart();
    	$tmp = $this -> _apiCart -> getCartGoods();
    	
    	if(!$tmp){
    		header('Location:/');exit;
    	}
    	
    	$goods = $tmp['goods'];
    	$data = $tmp['data'];
    	
    	$this -> view -> oldTax = $data['old_tax'] ;
        $this -> view -> disTax = $data['old_tax'] != $data['tax'] ? $data['old_tax'] - $data['tax'] : null;
        $this -> view -> data = $goods;
        $this -> view -> number = $data['jnum']+$data['hnum'];
        $this -> view -> amount = $data['shop_price']+$data['tax']+$data['shipping_fee'];
        $this -> view -> tax = $data['tax'];
        $this -> view -> shipping_fee = $data['shipping_fee'];
        $this -> view -> shop_price = $data['shop_price'];
        $this -> view -> jnum = $data['jnum'];
        $this -> view -> hnum = $data['hnum'];
        
        if(array_key_exists('japanese', $goods)){
            foreach ($goods['japanese'] as $k => $v){
                if($v['stock'] == 0 && $v['onsale'] !=1 ){
                    $this -> view -> alert = "{$v['product_name']}库存不足!";
                    return false;
                }
            }
        }
        if(array_key_exists('hongkong', $goods)){
            foreach ($goods['hongkong'] as $k => $v){
                if($v['stock'] == 0 && $v['onsale'] !=1){
                    $this -> view -> alert = "{$v['product_name']}库存不足!";
                    return false;
                }
            }
        }
        if(($data['jnum'] + $data['hnum']) == 0  ){
        	$this -> view -> alert = "没有选中商品！";
        	return false;
        }else if($data['jgoods_price'] > 1000 && $data['jnum'] > 1){
        	$this -> view -> alert = "单笔订单多件商品总额不得超过1000元噢~亲！";
        	return false;
        }else if($data['hgoods_price'] > 1000 && $data['hnum'] > 1){
        	$this -> view -> alert = "香港仓库的商品不能超过1000元噢~亲！";
        	return false;
        }else{
        	return true;
        }
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
      * 
      * 编辑收货人地址
      */
     public function editAreaAction()
     {
     	$request = $this->getRequest();
     	$data = $request->getParams();
     	unset($data['controller'],$data['action'], $data['module']);
     	$result = $this -> _member -> editAddress($data);
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
     	exit;
     }
     
     public function deleteAddressAction()
     {
     	$address_id = $this -> _request -> getParam('id',0);
     	if($address_id){
     		$user = $this -> user;
     		$result = $this -> _member -> deleteAddress((int)$address_id,$user['member_id']);
     		if($result >= 1){
     			exit("success");
     		}else{
     			exit("fail");
     		}
     	}else{
     		exit("err");
     	}
     }
     
     public function addressAction()
     {
     	$address_id = $this -> _request -> getParam('address_id',0);
     	if($address_id){
     		$this -> view -> address_id = $address_id;
     		$address = $this->_member->getAddressById($address_id);
     		$this -> view -> address= $address;
     		$this -> view -> tel = explode('-',$address['phone'] );
     	}
     	$province = $this -> _member -> getChildAreaById(1);
     	$city = $this -> _member -> getChildAreaById($address['province_id']);
     	$area = $this -> _member -> getChildAreaById($address['city_id']);
     	$this -> view -> province = $province;
     	$this -> view -> city = $city;
     	$this -> view -> area = $area;
     }
     
}