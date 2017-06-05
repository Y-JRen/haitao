<?php
$payments['alipay'] = '支付宝';
class Custom_Model_Payment_Alipay extends Custom_Model_Payment_Abstract
{
    protected $partner;
    protected $securityCode;
    protected $signType;
    protected $mysign;
    protected $_input_charset;
    protected $transport;
    protected $defaultbank;
    
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        if (!isset($this -> pay_type)) {
            $this -> pay_type = 'alipay';
        }
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
    
    public function getFields($config=null)
    {
        $html .= '<table><tr><th width="150">';
        $html .= '支付宝账户';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][alipay_account]" value="' . $config['alipay_account'] . '">';
        $html .= '</td></tr><tr><th>';
        $html .= '交易安全校验码';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][alipay_key]" value="' . $config['alipay_key'] . '">';
        $html .= '</td></tr><tr><th>';
        $html .= '合作者身份ID';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][alipay_partner]" value="' . $config['alipay_partner'] . '">';
        $html .= '</td></tr><tr><th>';
        if ($config['alipay_real_method'] == 0) {
            $selected_1 = 'selected="selected"';
        } else {
            $selected_2 = 'selected="selected"';
        }
        $html .= '选择实体商品接口';
        $html .= '</th><td>';
        $html .= '<select  name="payment[config][alipay_real_method]">';
        $html .= '<option value="0" ' . $selected_1 . '>使用普通实物商品交易接口</option>';
        $html .= '<option value="1" ' . $selected_2 . '>使用即时到帐交易接口</option>';
        $html .= '</select>';
        $html .= '</td></tr><tr><th>';
        if ($config['alipay_virtual_method'] == 0) {
            $selected_1 = 'selected="selected"';
        } else {
            $selected_2 = 'selected="selected"';
        }
        $html .= '选择虚拟商品接口';
        $html .= '</th><td>';
        $html .= '<select  name="payment[config][alipay_virtual_method]"';
        $html .= '<option value="0" ' . $selected_1 . '>使用普通虚拟商品交易接口</option>';
        $html .= '<option value="1" ' . $selected_2 . '>使用即时到帐交易接口</option>';
        $html .= '</select>';
        $html .= '</td></tr><tr><th>';
        if ($config['is_instant'] == 0) {
            $selected_1 = 'selected="selected"';
        } else {
            $selected_2 = 'selected="selected"';
        }
        $html .= '是否开通即时到帐';
        $html .= '</th><td>';
        $html .= '<select  name="payment[config][is_instant]">';
        $html .= '<option value="0" ' . $selected_1 . '>未开通</option>';
        $html .= '<option value="1" ' . $selected_2 . '>已经开通</option>';
        $html .= '</select>';
        $html .= '</td></tr></table>';
        return $html;
    }

    public function getCode($arg=null)
    {
    	
    	if ($arg['pay_hidden'] == true) {
    		$type = 'none';
    	} else {
    		$type = 'block';
    	}
    	
    	if ($arg['target'] == true) {
    		$target = 'target="_blank"';
    	} else {
    		$target = '';
    	}
    	
        $time = $this -> time;
        $order = $this -> order;
        $payment = $this -> payment;

       
		$payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
        $button ='
        <form action="/payment/auth/pay_type/'.$this -> pay_type.'/" method="post" 	'.$target.'>  
        <div style="display:'.$type.';">请确认您的支付金额 :<input type="text" readonly value="'.$payAmount.'" name="amount" /></div>
        <input type="hidden" value="'.$order['batch_sn'].'" name="batch_sn" />
       	<input type="submit" class="buttons4" value="支付订单"/>
        </form>';        
        return $button;
    }

    public function getButton($arg=null)
    {
        $time = $this -> time;

        if ($arg['pay_hidden'] == false) {
            $type = '';
        } else {
            $type = 'none';
        }
        if (strpos(strtoupper($_SERVER["HTTP_USER_AGENT"]),'THEWORLD')) {
            $arg['target'] = false;
        }
        if ($arg['target'] == true) {
            $target = 'window.open(data);';
        } else {
            $target = 'window.location=data;';
        }
		$payAmount = $this->amount;
        $button .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_alipay" readonly  value="'.$payAmount.'"></div> ';
        $button .= '<input type="button" onclick="authAlipay();" value="支付宝在线支付" class="buttons4"/>';
        $button .= '
<script>
function authAlipay(){
    $.ajax({
        url: "/payment/check",
        type: "post",
		data:{order_sn:'.$this->order_sn.',amount:$("#pay_alipay").val(),pay_id:'.$this->pay_id.',business:\''.$this->business.'\'},
        beforeSend: function(){},
        success: function(data){'.$target.'}
   });
}
</script>';
        return $button;
    }

    /**
     * 支付宝返回数据处理函数
     *
     *
     * @return mix
     */
    public function respond($business='')
    {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => $this -> pay_type)));
        $payment = unserialize($payment['config']);

        $logID = trim($_GET['out_trade_no']);
        $totalFee = $_GET['total_fee'];
        $this->returnRes['order_sn'] = $logID;
		
        /* 检查数字签名是否正确 */
        ksort($_GET);
        reset($_GET);

        $sign = '';
        foreach ($_GET AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key != 'payment/respond/pay_type/'.$this -> pay_type)
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
        	
			if($business == 'money_order'){
				
				//更改订单状态
				$obj_db = new Custom_Model_Dbadv();
				$r_money_order = $obj_db->getRow('shop_money_order',array('order_sn'=>$logID));
				
				if($r_money_order['amount'] != $totalFee){
					$this -> returnRes['stats'] = false;
        			$this -> returnRes['msg'] = '支付失败';
					return $this -> returnRes;
				}
				
				if($r_money_order['status'] == -1){
					$obj_db->update('shop_money_order', array('status'=>1),array('order_sn'=>$logID));
					
					//余额变更				
					$r_money_order = $obj_db->getRow('shop_money_order',array('order_sn'=>$logID));
					$r_member = $obj_db->getRow('shop_member',array('member_id'=>$r_money_order['member_id']));
					
					$api_member = new Shop_Models_API_Member();
					$api_member->editAccount($r_money_order['member_id'], 'money', array(
						'accountValue'=>$totalFee,
						'accountType'=>'1',
						'note'=>'用户通过支付宝充值'.$totalFee
					));
					
				    $this -> returnRes['stats'] = true;
		            $this -> returnRes['msg'] = '充值成功！';
										
				}else{
				    $this -> returnRes['stats'] = true;
		            $this -> returnRes['msg'] = '已充值成功，不要重复操作！';
				}
				
				
			}else{
				
	            /* 改变订单状态 */
	            $apiReturnRes = $this -> _api -> update($logID, $totalFee, $this -> pay_type);
				
	            if ($apiReturnRes['result']) {
	                $this -> returnRes['stats'] = true;
	                $this -> returnRes['thisPaid'] = $totalFee;
	                $this -> returnRes['difference'] = $apiReturnRes['remainder'];//差额
	                $this -> returnRes['msg'] = '';
	            } else {
	                $this -> returnRes['stats'] = false;
	                $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
	            }
			}
        } else {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
        }
        return $this -> returnRes;
    }

    public function sync($business = '')
    {
        $this->writeLog($this -> pay_type);
        $logID = trim($_POST['out_trade_no']);
        $batchSN = array_shift(explode('-', $logID));
        $totalFee = floatval($_POST['total_fee']); //交易金额
        $tradeStatus = $_POST['trade_status'];   //交易状态

        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => $this -> pay_type)));
        $payment = unserialize($payment['config']);

        $verifyResult = $this -> notifyVerify($payment['alipay_partner'],$payment['alipay_key'],'MD5','UTF-8','https');
        if($verifyResult){
            switch ($tradeStatus) {
                case 'TRADE_FINISHED' : //支付成功
                case 'WAIT_SELLER_SEND_GOODS' : //支付成功
                case 'TRADE_SUCCESS':
					
					if($business == 'money_order'){
						
						//更改订单状态
						$obj_db = new Custom_Model_Dbadv();
						$r_money_order = $obj_db->getRow('shop_money_order',array('order_sn'=>$logID));
						
						if($r_money_order['amount'] != $totalFee){
							die('fail');
						}
						
						if($r_money_order['status'] == -1){
							$obj_db->update('shop_money_order', array('status'=>1),array('order_sn'=>$logID));
							
							//余额变更				
							$r_money_order = $obj_db->getRow('shop_money_order',array('order_sn'=>$logID));
							$r_member = $obj_db->getRow('shop_member',array('member_id'=>$r_money_order['member_id']));
							
							$api_member = new Shop_Models_API_Member();
							$api_member->editAccount($r_money_order['member_id'], 'money', array(
								'accountValue'=>$totalFee,
								'accountType'=>'1',
								'note'=>'用户通过支付宝充值'.$totalFee
							));
	
							echo 'success';		
													
						}else{
							echo 'error!';		
						}
						
					}else{
	                    $this -> _api -> update($logID, $totalFee, $this -> pay_type);
	                    echo 'success';
					}
                    break;
                default :
                    echo 'success';
                    break;
            }
        }else{
            echo 'fail';
            exit;
        }
    }

    public function auth()
    {
        $time = time();
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => $this -> pay_type)));
        $payment = unserialize($payment['config']);
        if (empty($payment['is_instant']))
        {
            /* 未开通即时到帐 */
            $service = 'trade_create_by_buyer';
        } else {
			//实体商品
			$service =  (!empty($payment['alipay_real_method']) && $payment['alipay_real_method'] == 1) ?
				'create_direct_pay_by_user' : 'trade_create_by_buyer';
        }


        $parameter = array(
            'service'           => $service,
            'partner'           => $payment['alipay_partner'],
            '_input_charset'    => 'utf-8',
            'return_url'        => "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/".$this -> pay_type,//同步返回
			'notify_url'		=> "http://{$_SERVER['HTTP_HOST']}/payment/sync/pay_type/".$this -> pay_type,//异步返回
            /* 业务参数 */
            'subject'           => $order['batch_sn'],
            'out_trade_no'      => $order['batch_sn'] . '-' . $time,//外部交易号
            'price'             => $_POST['amount'],
            'quantity'          => 1,
            'payment_type'      => 1,
            /* 物流参数 */
            'logistics_type'    => 'EXPRESS',
            'logistics_fee'     => 0,
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            "token"	=> $_SESSION['token']?$_SESSION['token']:'0',
            /* 买卖双方信息 */
            'seller_email'      => $payment['alipay_account']
        );
        
        if ($this -> defaultbank) {
            $paramerter['paymethod'] = 'bankPay';
            $paramerter['defaultbank'] = $this -> defaultbank;
        }
        
        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }

        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment['alipay_key'];

        $url = 'https://mapi.alipay.com/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5';
        header("location:".$url);  
       // return $button;

    }

    public function check($order_sn,$amount,$payment)
    {
		
        $time = time();

        if (empty($payment['is_instant']))
        {
            /* 未开通即时到帐 */
            $service = 'trade_create_by_buyer';
        } else {
			//实体商品
			$service =  (!empty($payment['alipay_real_method']) && $payment['alipay_real_method'] == 1) ?
				'create_direct_pay_by_user' : 'trade_create_by_buyer';
        }

        $parameter = array(
            'service'           => $service,
            'partner'           => $payment['alipay_partner'],
            '_input_charset'    => 'utf-8',

            'return_url'        => "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/".$this -> pay_type."/business/".$this->business,//同步返回
			'notify_url'		=> "http://{$_SERVER['HTTP_HOST']}/payment/sync/pay_type/".$this -> pay_type."/business/".$this->business,//异步返回
			
            /* 业务参数 */
            'subject'           => $order_sn,
            'out_trade_no'      => $order_sn . '-' . $time,//外部交易号
            'price'             => $amount,
            'quantity'          => 1,
            'payment_type'      => 1,
            /* 物流参数 */
            'logistics_type'    => 'EXPRESS',
            'logistics_fee'     => 0,
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            "token"	=> $_SESSION['token']?$_SESSION['token']:'0',
            /* 买卖双方信息 */
            'seller_email'      => $payment['alipay_account']
        );

        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }

        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment['alipay_key'];

        echo 'https://mapi.alipay.com/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5';

        return $button;

    }

	/**
     * 异步验证函数
     *
     * @return boolean
     */
	protected function notifyVerify($partner, $securityCode, $signType = 'MD5', $_input_charset = 'GBK', $transport = 'http')
    {
        if($transport == 'https') {
			$gateway = 'https://mapi.alipay.com/gateway.do?';
            $veryfyUrl = $gateway . 'service=notify_verify' . '&partner=' . $partner . '&notify_id='.$_POST['notify_id'];
		} else {
            $gateway = 'http://notify.alipay.com/trade/notify_query.do?';
            $veryfyUrl = $gateway . 'msg_id=' . $_POST['notify_id']. '&email=' . $partner . '&order_no=' . $_POST['out_trade_no'];
        }

		$veryfyResult  = $this->getVerify($veryfyUrl);
		$post          = $this->paraFilter($_POST);
		$sortPost      = $this->argSort($post);
        $arg = '';
		while ( list($key, $val) = each($sortPost) ) {
			$arg .= $key . '=' . $val .'&';
		}
		$prestr = rtrim($arg, '&');  //去掉最后一个&号
		$mysign = MD5($prestr.$securityCode);
        return true;

		//log_result("notify_url_log=".$_POST["sign"]."&".$this->mysign."&".$this->charset_decode(implode(",",$_POST),$this->_input_charset ));
/*
		if (preg_match("true$",$veryfyResult) && $mysign == $_POST['sign']) {
			return true;
		} else {
            return false;
        }
*/

	}
    /**
     * sock请求验证数据有效性
     *
     * @return string
     */
	protected function getVerify($url,$timeOut = '60') {
		$urlarr = parse_url($url);
		$errno = '';
		$errstr = '';
		$transports = '';
		if($urlarr['scheme'] == 'https') {
			$transports = 'ssl://';
			$urlarr['port'] = '443';
		} else {
			$transports = 'tcp://';
			$urlarr['port'] = '80';
		}
		$fp=@fsockopen($transports . $urlarr['host'], $urlarr['port'], $errno, $errstr, $timeOut);
		if(!$fp) {
            $this -> writeLog("ERROR: $errno - $errstr\n", 'alipaySync');
			exit;
		} else {
			fputs($fp, 'POST '.$urlarr['path']." HTTP/1.1\r\n");
			fputs($fp, 'Host: '.$urlarr['host']."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, 'Content-length: '.strlen($urlarr['query'])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr['query'] . "\r\n\r\n");
			while( !feof($fp) ) {
				$info[]=@fgets($fp, 1024);
			}
			fclose($fp);
			$info = implode(',',$info);
            /*
			while ( list($key, $val) = each($_POST) ) {
				$arg .= $key . '=' . $val.'&';
			}
			log_result("notify_url_log=".$url.$this->charset_decode($info,$this->_input_charset));
			log_result("notify_url_log=".$this->charset_decode($arg,$this->_input_charset));
            */
			return $info;
		}
	}

	public function argSort($array) {
		ksort($array);
		reset($array);
		return $array;
	}

	protected function paraFilter($parameter) { //除去数组中的空值和签名模式
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == 'sign' || $key == 'sign_type' || $val == 'pay_type' ||$val == '') {
                continue;
            } else {
                $para[$key] = $parameter[$key];
            }
		}
		return $para;
	}
}