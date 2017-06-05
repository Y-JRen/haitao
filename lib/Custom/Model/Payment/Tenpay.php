<?php
$payments['tenpay'] = '财付通';
class Custom_Model_Payment_Tenpay extends Custom_Model_Payment_Abstract
{
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '财付通商户号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][tenpay_account]" value="'.$config['tenpay_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '财付通密钥';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][tenpay_key]" value="'.$config['tenpay_key'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '自定义签名';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][magic_string]" value="'.$config['magic_string'].'">';
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
   
        $target = 'target="_blank"';      

		$payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
		$params = $this->check($order['batch_sn'], $payAmount, $payment);	

        $button = '<form id="form_tenpay"  action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" style="margin:0px;padding:0px"  '.$target.' method="post">';
        $button .= "<div style='display:none;' id='htm_tenpay'>{$params}</div>";
        $button .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_tenpay" name="amount" readonly value="'.$payAmount.'"></div> ';
        $button .= '<input type="submit" class="buttons4" value="支付订单"/></form>';     
        return $button;
		
    }

    function getButton($arg=null) {
        $time = $this -> time;

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
		$payAmount = $this->amount;
        $button = '<form id="form_tenpay"  action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" style="margin:0px;padding:0px" '.$target.' method="post">';
        $button .= "<div style='display:none;' id='htm_tenpay'></div>";
        $button .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_tenpay" readonly value="'.$payAmount.'"></div> ';
        $button .= '<input type="button" onClick="authTenpay();" value="财付通支付" readOnly   class="buttons4" />';
        $button .= '</form>';
        $button .= '
<script>
function authTenpay(){
    $.ajax({
        url: "/payment/check",
        type: "post",
		data:{order_sn:'.$this->order_sn.',amount:$("#pay_tenpay").val(),pay_id:'.$this->pay_id.',business:\''.$this->business.'\'},
        beforeSend: function(){},
        success: function(data){
        	alert(data);
			return;
        	$("#htm_tenpay").html(data);$("#form_tenpay").submit();}
        });
}
</script>';
        return $button;
    }

	function doShow($show_url) {
		$strHtml = "<html><head>\r\n" .
			"<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">" .
			"<script language=\"javascript\">\r\n" .
				"window.location.href='" . $show_url . "';\r\n" .
			"</script>\r\n" .
			"</head><body></body></html>";
		echo $strHtml;
		exit;
	}

    function respond($business=''){

        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'tenpay')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }
		
       /*取返回参数*/
        $cmd_no         = $_GET['cmdno'];
        $pay_result     = $_GET['pay_result'];
        $pay_info       = $_GET['pay_info'];
        $bill_date      = $_GET['date'];
        $bargainor_id   = $_GET['bargainor_id'];
        $transaction_id = $_GET['transaction_id'];
        $sp_billno      = $_GET['sp_billno'];
        $total_fee      = $_GET['total_fee'];
        $fee_type       = $_GET['fee_type'];
        $attach         = $_GET['attach'];
		$spbill_create_ip=$_SERVER['REMOTE_ADDR'];
        $sign           = $_GET['sign'];

        /* 如果pay_result大于0则表示支付失败 */
        if ($pay_result > 0) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
            return $this -> returnRes;
        }
        
        $this -> returnRes['order_sn'] = $sp_billno;

        /* 检查数字签名是否正确 */
        $sign_text  = "cmdno=" . $cmd_no . "&pay_result=" . $pay_result .
                      "&date=" . $bill_date . "&transaction_id=" . $transaction_id .
                      "&sp_billno=" . $sp_billno . "&total_fee=" . $total_fee .
                      "&fee_type=" . $fee_type . "&attach=" . $attach . 
                      "&key=" . $payment['tenpay_key'];
        $sign_md5 = strtoupper(md5($sign_text));
        if ($sign_md5 != $sign) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
        } else {
        	
			if($business == 'money_order'){
				
				//更改订单状态
				$obj_db = new Custom_Model_Dbadv();
				$obj_db->update('shop_money_order', array('status'=>1),array('order_sn'=>$sp_billno));
				
				//余额变更				
				$r_money_order = $obj_db->getRow('shop_money_order',array('order_sn'=>$sp_billno));
				$r_member = $obj_db->getRow('shop_member',array('member_id'=>$r_money_order['member_id']));
				
				$api_member = new Shop_Models_API_Member();
				$api_member->editAccount($r_money_order['member_id'], 'money', array(
					'accountValue'=>$r_member['money'],
					'money'=>$total_fee,
					'note'=>'用户通过支付宝充值'.$total_fee
				));
				
			    $this -> returnRes['stats'] = true;
	            $this -> returnRes['msg'] = '充值成功！';
				
			}else{
	            /* 改变订单状态 */
	            $total_fee = intval($total_fee) / 100;
	            $apiReturnRes = $this -> _api -> update($sp_billno, $total_fee, 'tenpay');
	            if ($apiReturnRes['result']) {
	                $this -> returnRes['stats'] = true;
	                $this -> returnRes['thisPaid'] = $total_fee;
	                $this -> returnRes['difference'] = $apiReturnRes['remainder'];//差额
	            } else {
	                $this -> returnRes['stats'] = false;
	                $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
	            }
			}
			
        }
        return $this -> returnRes;
    }

    function auth() {
    	
        $time = time();
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'tenpay')));
        $payment = unserialize($payment['config']);

        $cmd_no = '1';

        /* 获得订单的流水号，补零到10位 */
        $bill_no = $order['batch_sn'] . '-' . $time;

        /* 交易日期 */
        $today = date('Ymd');

        /* 将商户号+年月日+流水号 */
        $transaction_id = $payment['tenpay_account'].$today.str_pad($time, 10, 0, STR_PAD_LEFT);//todo

        /* 银行类型:支持纯网关和财付通 */
        $bank_type = '0';

        /* 订单描述，用订单号替代 */
        $desc = str_pad($order['order_batch_id'], 13, 0, STR_PAD_LEFT);//todo

        /* 返回的路径 */
        $return_url = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/tenpay";

        /* 总金额 */
        $total_fee = floatval($_POST['amount']) * 100;

        /* 货币类型 */
        $fee_type = '1';

	    $spbill_create_ip=$_SERVER['REMOTE_ADDR'];

        /* 重写自定义签名 */
        $payment['magic_string'] = abs(crc32($payment['magic_string']));

        /* 数字签名 */
        $sign_text = "cmdno=" . $cmd_no . "&date=" . $today . "&bargainor_id=" . $payment['tenpay_account'] .
          "&transaction_id=" . $transaction_id . "&sp_billno=" . $bill_no .
          "&total_fee=" . $total_fee . "&fee_type=" . $fee_type . "&return_url=" . $return_url .
          "&attach=" . $payment['magic_string'] ."&spbill_create_ip=" . $spbill_create_ip . "&key=" . $payment['tenpay_key'];
        $sign = strtoupper(md5($sign_text));
        $purchaser_id = '';
        /* 交易参数 */
        $parameter = array(
            'cmdno'             => $cmd_no,                     // 业务代码, 财付通支付支付接口填  1
            'date'              => $today,                      // 商户日期：如20051212
            'bank_type'         => $bank_type,                  // 银行类型:支持纯网关和财付通
            'desc'              => $desc,                       // 交易的商品名称
            'purchaser_id'      => '',               // 用户(买方)的财付通帐户,可以为空
            'bargainor_id'      => $payment['tenpay_account'],  // 商家的财付通商户号
            'transaction_id'    => $transaction_id,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'sp_billno'         => $bill_no,                    // 商户系统内部的定单号,最多10位
            'total_fee'         => $total_fee,                  // 订单金额
            'fee_type'          => $fee_type,                   // 现金支付币种
            'return_url'        => $return_url,                 // 接收财付通返回结果的URL
            'attach'            => $payment['magic_string'],    // 用户自定义签名
			'spbill_create_ip'  => $spbill_create_ip,           // 用户自定义签名
            'sign'              => $sign                        // MD5签名
        );
        foreach ($parameter AS $key=>$val)
        {
            $button  .= "<input type='hidden' name='$key' value='$val' />";
        }
        echo $button;
    }

    function check($order_sn,$amount,$payment) {
    			
        $time = time();

        $cmd_no = '1';

        /* 获得订单的流水号，补零到10位 */
        $bill_no = $order_sn . '-' . $time;

        /* 交易日期 */
        $today = date('Ymd');

        /* 将商户号+年月日+流水号 */
        $transaction_id = $payment['tenpay_account'].$today.str_pad($time, 10, 0, STR_PAD_LEFT);//todo

        /* 银行类型:支持纯网关和财付通 */
        $bank_type = '0';

        /* 订单描述，用订单号替代 */
        $desc = str_pad($order_sn, 13, 0, STR_PAD_LEFT);//todo

        /* 返回的路径 */
        $return_url = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/tenpay/business/".$this->bussiness;

        /* 总金额 */ 
        $total_fee = floatval($amount) * 100;

        /* 货币类型 */
        $fee_type = '1';

	    $spbill_create_ip=$_SERVER['REMOTE_ADDR'];

        /* 重写自定义签名 */
        $payment['magic_string'] = abs(crc32($payment['magic_string']));

        /* 数字签名 */
        $sign_text = "cmdno=" . $cmd_no . "&date=" . $today . "&bargainor_id=" . $payment['tenpay_account'] .
          "&transaction_id=" . $transaction_id . "&sp_billno=" . $bill_no .
          "&total_fee=" . $total_fee . "&fee_type=" . $fee_type . "&return_url=" . $return_url .
          "&attach=" . $payment['magic_string'] ."&spbill_create_ip=" . $spbill_create_ip . "&key=" . $payment['tenpay_key'];
        $sign = strtoupper(md5($sign_text));
        $purchaser_id = '';

        /* 交易参数 */
        $parameter = array(
            'cmdno'             => $cmd_no,                     // 业务代码, 财付通支付支付接口填  1
            'date'              => $today,                      // 商户日期：如20051212
            'bank_type'         => $bank_type,                  // 银行类型:支持纯网关和财付通
            'desc'              => $desc,                       // 交易的商品名称
            'purchaser_id'      => '',               // 用户(买方)的财付通帐户,可以为空
            'bargainor_id'      => $payment['tenpay_account'],  // 商家的财付通商户号
            'transaction_id'    => $transaction_id,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'sp_billno'         => $bill_no,                    // 商户系统内部的定单号,最多10位
            'total_fee'         => $total_fee,                  // 订单金额
            'fee_type'          => $fee_type,                   // 现金支付币种
            'return_url'        => $return_url,                 // 接收财付通返回结果的URL
            'attach'            => $payment['magic_string'],    // 用户自定义签名
			'spbill_create_ip'  => $spbill_create_ip,           // 用户自定义签名
            'sign'              => $sign                        // MD5签名
        );

        foreach ($parameter AS $key=>$val)
        {
            $button  .= "<input type='hidden' name='$key' value='$val' />";
        }
		
        return  $button;
    }
}