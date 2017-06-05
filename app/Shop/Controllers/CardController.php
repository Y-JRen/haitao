<?php
class CardController extends Zend_Controller_Action
{
	/**
     * 卡 API
     * 
     * @var Shop_Models_API_Card
     */
	private $_card = null;
	
	public function init()
    {
        $this -> _card = new Shop_Models_API_Card();

	}
	/**
     * 认证卡号和密码
     *
     * @return void
     */
    public function checkCardAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$cardSn = $this -> _request -> getParam('card_sn');
    	$cardPassword = $this -> _request -> getParam('card_password');
    	if ($cardSn && $cardPassword) {
    		$data = $this -> _card -> checkCard($cardSn, $cardPassword, $this -> _request -> getParam('price'), $this -> _request -> getParam('fare'));
    		exit(Zend_Json::encode($data));
    	} else {
        	exit;
        }
    }
 
    /**
     * 删除已使用卡号
     *
     * @return void
     */
    public function deleteCardAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$data = $this -> _card -> deleteCard($this -> _request -> getParam('type'));
    	exit('ok');
    }
    
    public function getCouponAction()
    {
    	if(time()>strtotime('2013-12-13'))
    	{
    		Custom_Model_Message::showAlert('活动已过期' , true,'/');
    	}  
    	  	
    	$card_api = new Shop_Models_API_Card();
    	$start_time='2013-12-12';//有效期截止日期
    	$end_time='2013-12-13';//有效期截止日期
    	$authAPi = Shop_Models_API_Auth :: getInstance();
    	$auth = $authAPi->getAuth();
    	 
    	if(!$auth)
    	{
    		$goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/zt/detail-88.html');
    		$url = "{$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto;
    		Custom_Model_Message::showAlert('请登录，登录后领取优惠券！' , true,$url);
    	}    	
    	
    	if(!$card_api -> getCoupon(array('admin_name' => 'jk_1212_5','user_id' => $auth['user_id']))){
    		$card_api -> addSysCoupon(array('admin_name' => 'jk_1212_5', 'note' => '双12送券','start_date' => $start_time, 'end_date' => $end_time, 'user_id' => $auth['user_id'], 'user_name'=> $auth['user_name'],'card_price' => '5','min_amount' => '0','number' => '1'));
    		Custom_Model_Message::showAlert('领取成功！' , true, '/member/coupon');
    	}else{
    		Custom_Model_Message::showAlert('你已经领取过5元优惠券了！' , true, -1);
    	}
        exit();
    }
    
}