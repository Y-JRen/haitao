<?php
$payments['easipay'] = '东方支付';
class Custom_Model_Payment_Easipay extends Custom_Model_Payment_Abstract
{
	protected $_callback = array(
			'frontEndUrl'	=>	'http://www.cnsc.com.cn/payment/respond/pay_type/easipay',//前台通知URL
			'backEndUrl'	=>	'http://www.cnsc.com.cn/payment/sync/pay_type/easipay',//后台通知URL
			'refunf_backURL'=>	'http://www.cnsc.com.cn/payment/get-refund-status/pay_type/easipay',//退款后台通知url 
	);
	
	public function __construct($batchSN = null,$order_type = 2,$amount=0.00,$business='',$config = array())
	{
		parent::__construct($batchSN ,$order_type,$amount,$business,$config);
		$this -> _authApi = new Shop_Models_API_Auth();
		$this -> xml_api = new Custom_Model_Xml();
		$payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'easipay')));
		$this -> payment = unserialize($payment['config']);
	}
	
	
	function getFields($config=null){
		$html .= '<table><tr><th width="150">';
		$html .= '东方支付商户号';
		$html .= '</th><td>';
		$html .= '<input type="text" name="payment[config][sender_code]" value="'.$config['sender_code'].'">';
		$html .= '</td></tr><tr><th>';
		$html .= '企业名称';
		$html .= '</th><td>';
		$html .= '<input type="text" name="payment[config][coName]" value="'.$config['coName'].'">';
		$html .= '</td></tr><tr><th>';
		$html .= '东方支付密钥';
		$html .= '</th><td>';
		$html .= '<input type="text" name="payment[config][secret_key]" value="'.$config['secret_key'].'">';
		$html .= '</td></tr><tr><th>';
		$html .= '东方支付支付地址';
		$html .= '</th><td>';
		$html .= '<input type="text" name="payment[config][pay_url]" value="'.$config['pay_url'].'" />';
		$html .= '</td></tr><tr><th>';
		$html .= '东方支付退款地址';
		$html .= '</th><td>';
		$html .= '<input type="text" name="payment[config][refund]" value="'.$config['refund'].'" />';
		$html .= '</td></tr><tr><th>';
		$html .= '东方支付单笔查询地址';
		$html .= '</th><td>';
		$html .= '<input type="text" name="payment[config][search]" value="'.$config['search'].'" />';
		$html .= '</td></tr><tr><th>';
		$html .= '东方支付对账地址';
		$html .= '</th><td>';
		$html .= '<input type="text" name="payment[config][recon]" value="'.$config['recon'].'" />';
		$html .= '</td></tr></table>';
		return $html;
	}

	
	function getCode($arg=null) {
		$time = $this -> time;
		$order = $this -> order;
		$payment = $this -> payment;
		if ($arg['pay_hidden'] == false) {
			$type = '';
		} else {
			$type = 'none';
		}
		if ($arg['target'] == true) {
			$target = 'target="_blank"';
		} else {
			$target = '';
		}

		$payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
		
		$button = '<form id="form_tenpay"  action="/payment/go/pay_type/easipay" style="margin:0px;padding:0px"  '.$target.' method="post">';
		$button .= "<input type='hidden' name='batch_sn' value='{$order['batch_sn']}' />";
		$button .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text"  size="6" id="pay_amount" readonly value="'.$payAmount.'"></div> ';
		$button .= '<input type="submit" style="width:100px;display:block;cursor:pointer;font-size: 15px;text-align: center;border-radius: 5px;height: 31px;border: 1px solid #666;color:#fff;background:#990000;" class="buttons4" value="立即支付"/></form>';
		return $button;

	}

	function respond(){
		 $respond = $this -> getRespond();
        if ( is_array($respond) ) {
            if ($respond['return']['result']) {
                $this -> returnRes['stats'] = true;
                $this -> returnRes['thisPaid'] = $respond['amount'];
                $this -> returnRes['difference'] = $respond['return']['remainder'];  //差额
            } else {
                $this -> returnRes['stats'] = false;
                $this -> returnRes['msg'] = $respond['return']['msg'] != '' ? $respond['return']['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
            }
        }
        else {
            $this -> returnRes['stats'] = false;
            if ( $respond == 'error' ) {
                $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！.';
            }
            else if ( $respond == 'sign_fail' ) {
                $this -> returnRes['msg'] = '支付失败[签名认证失败]';
            }
            else if ( $respond == 'pay_fail' ) {
                $this -> returnRes['msg'] = '支付失败[支付操作失败]';
            }
        }
        
        return $this -> returnRes;
	}
	
	function sync(){
		$this -> setOpenWriteLog();
        $this -> writeLog(null, 'easipay');
        $tmp=$this -> getRespond();
        if (!is_array($tmp)){
        	
        }else{
        	
        }
        exit;
	}
	
	private function getRespond(){
		
		$request = $_REQUEST; 
		if(!$this -> checkSign($request['TRX_CONTENT'],$request['SIGNATURE'])){
			return 'sign_fail';
		}
		$xml_api = new Custom_Model_Xml();
		$tmp = $xml_api -> xml2array(base64_decode($request['TRX_CONTENT']));
		$tmp = $tmp['EasipayB2CResponse']['ResData'];
		
		if($tmp['RTN_CODE'] != '000000'){
			return array();
		}
		
		$batch_sn = $tmp['BILL_NO'];
		$amount = $tmp['PAY_AMOUNT'];
		$pay_log_id = $batch_sn;
		$serial_number = $tmp['TRX_SERNO'];
		
		$apiReturnRes = $this ->_api-> update($pay_log_id, $amount, 'easipay',$serial_number);
		
		return array('return'=>$apiReturnRes);
	}
	
	function auth()
	{
		$order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
		$seriesNo = $this -> _api -> createPaySeries('easipay', $order['batch_sn'], $order['price_order']);
		$sn = $order['batch_sn'] . $seriesNo;
		
		$xml = $this->easipay_xml($order, $sn);
		$content = base64_encode($xml);
		$sign = $this->getSign($xml);
		$this -> log("跨境通支付：SENDER_CODE:{$this->payment['sender_code']}&TRX_CONTENT:{$content}&SIGNATURE:{$sign}");
		echo "
		<input type='hidden' name='SENDER_CODE' value='{$this->payment['sender_code']}' />
		<input type='hidden' name='TRX_CONTENT' value='{$content}' />
		<input type='hidden' name='SIGNATURE' value='{$sign}' />
		";
	}
	
	
	public function getRefundRespond()
	{
		$request = $_REQUEST;
		$this -> log($request);
		if(!$this -> checkSign($request['TRX_CONTENT'],$request['SIGNATURE'])){
			$this -> log('签名错误');
			return "sign error";
		}else{
			$response_cnt = base64_decode($request['TRX_CONTENT']);
			$response_cnt = $this -> xml_api ->xml2array($response_cnt);
			if($response_cnt['EasipayB2CResponse']['ResData']['REFUND_STATE'] === 'S'){//退款成功
				$this -> log('退款成功');
				return true;
			}else{//退款失败
				$this -> log('退款失败');
				return false;
			}
		}
	}
	
	function log($content)
	{
		$db = Zend_Registry::get('db');
		$array = array(
				'content'=>$content,
				'add_time'=>time()
		);
		$db -> insert('log',$array);
	}
	
	/**
	 * 单笔订单查询
	 * @param string $trx_type 交易类型 00：支付 01：退款
	 * @param string $sn 交易订单号
	 * @param string $refund_sn 退款流水号
	 */
	public function searchOrder($trx_type, $sn, $refund_sn=null)
	{
		$xml = $this -> search_xml($trx_type, $sn, $refund_sn);
		//$xml = str_replace('<![CDATA[', '',$xml);
		//$xml = str_replace(']]>', '',$xml);
		$content = urlencode(base64_encode($xml));
		$sign = $this->getSign($xml);
		
		$str = "SENDER_CODE={$this->payment['sender_code']}&TRX_CONTENT={$content}&SIGNATURE={$sign}";
		$result = $this -> _authApi -> http($this->payment['search'], "POST",$str);
		$tmp = $this -> xml_api ->xml2array($result);
		var_dump($tmp);
	}
	
	/**
	 * 退款请求
	 */
	public function orderRefund($data)
	{
		$xml = $this -> refund_xml($data['pay_log_id'], $data['price_goods']+$data['price_logistic'], $data['price_goods'], $data['price_logistic'], null);
		$content = urlencode(base64_encode($xml));
		$sign = $this->getSign($xml);
		
		$str = "SENDER_CODE={$this->payment['sender_code']}&TRX_CONTENT={$content}&SIGNATURE={$sign}";
		$result = $this -> _authApi -> http($this->payment['refund'], "POST",$str);
		$tmp = $this -> xml_api ->xml2array($result);
		var_dump($tmp);die();
		$tmp = $tmp['EasipayB2CResponse']['ResData'];
		if($tmp['RTN_CODE'] == '000000'){
			//要记录流水号satrt
			var_dump($tmp);die();
			//end
		}
	}
	
	
	/**
	 * 交易对账
	 * @param int $type 1：交易对账 2：实扣税费对账 3：保证金对账 4：外币账户对账
	 */
	public function reconOrder($type)
	{
		$xml = $this -> recon_xml($type);
		$content = base64_encode($xml);
		$sign = $this->getSign($xml);
		
		$str = "SENDER_CODE={$this->payment['sender_code']}&TRX_CONTENT={$content}&SIGNATURE={$sign}";
		$result = $this -> _authApi -> http($this->payment['recon'], "POST",$str);
		var_dump($result);
		
	}
	
	
	
	
	
	
	
	
	
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/**
	 * 支付请求的xml
	 * @param array $order 订单信息
	 * @param string $sn 订单号
	 */
	private function easipay_xml($order,$sn)
	{
		$order_goods = $this -> _api -> getOrderBatchGoods(array('batch_sn'=>$order['batch_sn']));
		$goods_count = count($order_goods);
		$add_time = date('Y-m-d/*/H:i:s',$order['add_time']);
		$add_time = str_replace('/*/', 'T',$add_time);
		$time_out = str_replace('/*/', 'T',date('Y-m-d/*/H:i:s',time()+86400));
		//$order[price_goods] = $order[price_goods] + $order['price_adjust'];
		$str = '<?xml version="1.0" encoding="UTF-8"?>'
			 . "<EasipayB2CRequest><CnyPayRequest>"
			 . "<BILLNO><![CDATA[{$sn}]]></BILLNO>"//商户平台订单号，必须是唯一
			 . "<CALLBACKURL><![CDATA[{$this->_callback[frontEndUrl]}]]></CALLBACKURL>"//线上回调地址
			 . "<BGURL><![CDATA[{$this->_callback[backEndUrl]}]]></BGURL>"//线下后台通知商户地址
			 . "<REQ_TIME><![CDATA[{$add_time}]]></REQ_TIME>"//时间格式:yyyy-MM-ddTHH:mm:ss(例如：2014-06-10T10:31:54)
			 . "<SRC_NCODE><![CDATA[{$this -> payment[sender_code]}]]></SRC_NCODE>"//一级商户代码
			 . "<REC_NCODE><![CDATA[{$this -> payment[sender_code]}]]></REC_NCODE>"//二级商户代码
			 . "<PAY_BIZ><![CDATA[30]]></PAY_BIZ>"//支付业务 预留字段，暂时填写30
			 . "<PAY_AMOUNT><![CDATA[{$order[price_order]}]]></PAY_AMOUNT>"//这笔交易的总金额 2位小数
			 . "<PAY_CURRENCY><![CDATA[CNY]]></PAY_CURRENCY>"//如果计价币种为空,则以人民币结算。
			 . "<TRADE_TYPE><![CDATA[010204]]></TRADE_TYPE>"//贸易类型
			 . "<CARGO_SUM><![CDATA[{$goods_count}]]></CARGO_SUM>"//商品总数
			 . "<TXUSE><![CDATA[99]]></TXUSE>"//资金用途
			 . "<TRX_DESC><![CDATA[]]></TRX_DESC>"//整个交易的简单描述，如果为空，则以贸易类型描述
			 . "<BILL_LINK><![CDATA[]]></BILL_LINK>"//能够直接访问商户订单地址的链接,如果不传，则支付平台不做跳转
			 . "<BILL_TIMEOUT><![CDATA[{$time_out}]]></BILL_TIMEOUT>"//订单过期时间 时间格式:yyyy-MM-ddTHH:mm:ss(例如：2014-06-10T10:31:54)
			 . "<SPT1><![CDATA[]]></SPT1>"
			 . "<SPT2><![CDATA[]]></SPT2>"
			 . "<SPT3><![CDATA[]]></SPT3>"
			 . "<PayDetail>"//货款
			 . "<BILL_TYPE><![CDATA[10]]></BILL_TYPE>"//子交易种类
			 . "<BILL_DATE><![CDATA[{$add_time}]]></BILL_DATE>"//时间格式:yyyy-MM-ddTHH:mm:ss(例如：2014-06-10T10:31:54)
			 . "<PAY_AMOUNT><![CDATA[{$order[price_goods]}]]></PAY_AMOUNT>"//商户提交给支付平台的货款，精确到2 位小数点，单位为元
			 . "<PAY_CURRENCY><![CDATA[CNY]]></PAY_CURRENCY>"//支付币种
			 . "<CRT_CURRENCY><![CDATA[JPY]]></CRT_CURRENCY>"//收款币种
			 . "<BORDER_MARK><![CDATA[00]]></BORDER_MARK>"//是否跨境结算(外汇结算)标志
			 . "<CRT_CODE_TYPE><![CDATA[10]]></CRT_CODE_TYPE>"//收款方代码类型
			 . "<CRT_CODE><![CDATA[{$this -> payment[sender_code]}]]></CRT_CODE>"//收款方代码
			 . "<BILL_DESC><![CDATA[]]></BILL_DESC>"//子交易描述
			 . "<SSPT1><![CDATA[]]></SSPT1>"
			 . "<SSPT2><![CDATA[]]></SSPT2>"
			 . "</PayDetail>"
			 . "<PayDetail>"//运费
			 . "<BILL_TYPE><![CDATA[20]]></BILL_TYPE>"
			 . "<BILL_DATE><![CDATA[{$add_time}]]></BILL_DATE>"
			 . "<PAY_AMOUNT><![CDATA[{$order[price_logistic]}]]></PAY_AMOUNT>"
			 . "<PAY_CURRENCY><![CDATA[CNY]]></PAY_CURRENCY>"
			 . "<CRT_CURRENCY><![CDATA[JPY]]></CRT_CURRENCY>"
			 . "<BORDER_MARK><![CDATA[00]]></BORDER_MARK>"
			 . "<CRT_CODE_TYPE><![CDATA[10]]></CRT_CODE_TYPE>"
			 . "<CRT_CODE><![CDATA[{$this -> payment[sender_code]}]]></CRT_CODE>"
			 . "<BILL_DESC><![CDATA[]]></BILL_DESC>"
			 . "<SSPT1><![CDATA[]]></SSPT1>"
			 . "<SSPT2><![CDATA[]]></SSPT2>"
			 . "</PayDetail>"
			 . "<PayDetail>"//行邮税
			 . "<BILL_TYPE><![CDATA[30]]></BILL_TYPE>"
			 . "<BILL_DATE><![CDATA[{$add_time}]]></BILL_DATE>"
			 . "<PAY_AMOUNT><![CDATA[{$order[tax]}]]></PAY_AMOUNT>"
			 . "<PAY_CURRENCY><![CDATA[CNY]]></PAY_CURRENCY>"
			 . "<CRT_CURRENCY><![CDATA[CNY]]></CRT_CURRENCY>"
			 . "<BORDER_MARK><![CDATA[00]]></BORDER_MARK>"
			 . "<CRT_CODE_TYPE><![CDATA[20]]></CRT_CODE_TYPE>"
			 . "<CRT_CODE><![CDATA[2216]]></CRT_CODE>"
			 . "<BILL_DESC><![CDATA[]]></BILL_DESC>"
			 . "<SSPT1><![CDATA[]]></SSPT1>"
			 . "<SSPT2><![CDATA[]]></SSPT2>"
			 . "</PayDetail>"
			 . "</CnyPayRequest></EasipayB2CRequest>";
		return $str;
	}
	
	/**
	 * 退款请求
	 * @param string $sn 需退款的订单号
	 * @param float $amount 退款总金额
	 * @param float $price_goods 退货款
	 * @param float $fare 运费
	 * @param int $addtime 订单添加时间
	 */
	private function refund_xml($sn,$amount,$price_goods,$fare,$addtime)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><EasipayB2CRequest>';
		$xml_api = $this -> xml_api;
		$tmp = array(
					'SRC_NCODE'			=>$this -> payment['sender_code'],//一级商户代码
					'BILL_NO'			=>$sn,//原交易订单号
					'REFUND_AMOUNT'		=>$amount,//退款总金额
					'CARGO_AMOUNT'		=>$price_goods,//退货款
					'TRANSPORT_AMOUNT'	=>$fare,//退运费
					'RDO_TIME'			=>$add_time?date('Ymd',$add_time):"",//原交易记账时间
					'BGURL'				=>$this->_callback['refunf_backURL'],//后台通知地址
				);
		$xml .= $xml_api -> array2xml($tmp,'ReqData');
		$xml .= '</EasipayB2CRequest>';
		return $xml;
	}
	
	/**
	 * 单笔交易查询
	 * @param string $trx_type 交易类型
	 * @param string $sn 交易订单号
	 * @param string $refund_sn 退款流水号
	 */
	private function search_xml($trx_type,$sn,$refund_sn = null)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><EasipayB2CRequest>';
		$xml_api = $this -> xml_api;
		$tmp = array(
				'SRC_NCODE'			=>$this -> payment['sender_code'],//一级商户代码
				'TRX_TYPE'			=>$trx_type,//交易类型
				'BILL_NO'			=>$sn,//原交易订单号
		);
		if($trx_type=='01'){
			$tmp['REFTRX_SERNO'] = $refund_sn;//退款流水号
		}
		$xml .= $xml_api -> array2xml($tmp,'ReqData');
		$xml .= '</EasipayB2CRequest>';
		return $xml;
	}
	
	/**
	 * 对账
	 * @param int $type 1：交易对账 2：实扣税费对账 3：保证金对账 4：外币账户对账
	 */
	private function recon_xml($type)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><EasipayB2CRequest>';
		$xml_api = $this -> xml_api;
		$tmp = array(
				'SRC_NCODE'			=>$this -> payment['sender_code'],//一级商户代码
				'DATE'				=>date('Ymd'),//对账时间
				'ACCOUNT_TYPE'		=>$type,//对账类别
		);
		$xml .= $xml_api -> array2xml($tmp,'ReqData');
		$xml .= '</EasipayB2CRequest>';
		return $xml;
	}
	
	
	
/*************************************************************************************************/	
	/**
	 * 加签
	 * @param xml $xml
	 * @param bool $has_code 是否加sender_code
	 */
	private function getSign($xml,$has_code=true)
	{
		$secrentkey = $this -> payment['secret_key'];
		if(strlen($secrentkey) != 128){
			return false;
		}
		$prefix = substr($secrentkey, 0,64);
		$suffix = substr($secrentkey, 64,127);
		if($has_code == false){
			$sign = $prefix.'^'.base64_encode($xml).'^'.$suffix;
		}else{
			$sign = $prefix.'^'.$this -> payment['sender_code'].'^'.base64_encode($xml).'^'.$suffix;
		}
		return md5($sign);
	}
	
	/**
	 * 验签
	 * @param base64_encode(xml) $data
	 * @param string $signature
	 * @return boolean
	 */
	function checkSign($data,$signature)
	{
		$xml = base64_decode($data);
		$sign = $this -> getSign($xml,false);
		if($sign == $signature){
			return true;
		}
		return false;
	}
	
}