<?php

class PaymentController extends Zend_Controller_Action
{
    /**
     * 
     * @var Shop_Models_API_Payment
     */
	protected $_api = null;
	
	public function init()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$this -> _api = new Shop_Models_API_Payment();
	}
	
	/**
     * 支付接口返回
     *
     * @return void
     */
    public function respondAction()
    {
        $payType = $this -> _request -> getParam('pay_type', null);
        $business = $this -> _request -> getParam('business', null);
		if($payType){
		    $reslut = $this -> _api -> respond($payType,$business);
		   	$order_sn = explode('-', $reslut['order_sn']);		
		   	$order_sn = trim($order_sn[0]);
			if($order_sn)				
			{	
				$orderinfo = array_shift($this -> _api ->getOrderBatch(array('batch_sn'=>$order_sn)));
				$product = $this -> _api -> getOrderBatchGoods(array('batch_sn' =>$order_sn));
				$payment =  array_shift($this->_api-> getPayment(array('pay_type' => $orderinfo['pay_type'])));			    		
				
				$this -> view -> payment = $payment;
				$this -> view -> product_num = count($product);
				$this -> view -> orderinfo = $orderinfo;
				$this -> view -> product = $product;				
			}
			
			$this -> view -> result = $reslut;
			$this -> view->css_more=',cart.css';
				
		}else{
			$this->_redirect("/");
			exit;
		}
    }
    
    /**
     * 
	 * 异步校验
     * 
     * @return void
     */
    public function syncAction()
    {
        $business = $this -> _request -> getParam('business', null);
        $payType = $this -> _request -> getParam('pay_type', null);
        $this -> _api -> sync($payType,$business);
        exit;
    }
    
    /**
     * 数据校验(主要针对快钱)
     *
     * @return void
     */
    public function authAction()
    {
        $payType = $this -> _request -> getParam('pay_type', null);
        $this -> _api -> auth($payType);   
        exit;
    }
    
    /**
     * 支付校验
     *
     * @return void
     */
    public function checkAction()
    {
        $this -> _api -> check($_POST['order_sn'],$_POST['amount'],$_POST['pay_id'],$_POST['business']);   
        exit;
    }
    
    /**
     * 查询单笔订单
     *
     * @return void
     */
    public function queryAction()
    {
        $payType = $this -> _request -> getParam('pay_type', null);
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        $this -> _api -> query($payType, $batchSN);
        $this->_redirect("/member/order-detail/batch_sn/{$batchSN}");
        
        exit;
    }
	
	public function moneyOrderRespond(){
		
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'alipay')));
        $payment = unserialize($payment['config']);

        $logID = trim($_GET['out_trade_no']);
        $totalFee = $_GET['total_fee'];

        /* 检查数字签名是否正确 */
        ksort($_GET);
        reset($_GET);

        $sign = '';
        foreach ($_GET AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key != 'payment/respond/pay_type/alipay')
            {
                $sign .= "$key=$val&";
            }
        }
        $sign = substr($sign, 0, -1) . $payment['alipay_key'];

        if (md5($sign) != $_GET['sign'])
        {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败[签名认证失败]';
            return $this -> returnRes;
        }
        if ($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS' || $_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
        	
            /* 改变订单状态 */
            $apiReturnRes = $this -> _api -> update($logID, $totalFee, 'alipay');
            if ($apiReturnRes['result']) {
                $this -> returnRes['stats'] = true;
                $this -> returnRes['thisPaid'] = $totalFee;
                $this -> returnRes['difference'] = $apiReturnRes['remainder'];//差额
                $this -> returnRes['msg'] = '';
				
            } else {
            	
                $this -> returnRes['stats'] = false;
                $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
            }
        } else {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
        }
        return $this -> returnRes;		
	}
	

	public function goAction()
	{
		$payType = $this -> _request -> getParam('pay_type', null);
	
		$payment = array_shift($this -> _api -> getPayment(array('pay_type' => $payType)));
		if (!$payment)  exit;
		$config = unserialize($payment['config']);
		echo "<html>
		<head></head>
		<body onload=\"document.getElementById('sbutton').click();\">
		<form id='form_pay' action='{$config[pay_url]}' method='post'>
		<input type='submit' id='sbutton' style='display:none'>";
		$this -> _api -> auth($payType);
		echo "</form>
		</body>
		</html>";
	
		exit;
	}
	
	public function getRefundStatusAction()
	{
		$payType = $this -> _request -> getParam('pay_type',null);
		$payType = ucfirst($payType);
		$class = 'Custom_Model_Payment_' . $payType;
		$pay = new $class();
		$data = is_object($pay) ?  $pay -> getRefundRespond() : false;
		if($data === true){
			exit('success');
		}else{
			exit('failure');
		}
	}
}

