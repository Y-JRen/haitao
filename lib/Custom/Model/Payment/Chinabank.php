<?php
$payments['chinabank'] = '网银在线';
class Custom_Model_Payment_Chinabank extends Custom_Model_Payment_Abstract
{
  
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '商户编号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][chinabank_account]" value="'.$config['chinabank_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'MD5 密钥';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][chinabank_key]" value="'.$config['chinabank_key'].'">';
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
        $def_url  = '<form id="form_chinabank" style="text-align:center;" method=post action="https://pay3.chinabank.com.cn/PayGate" '.$target.'>';
        $def_url .= "<div style='display:none;' id='htm_chinabank'></div>";
        $def_url .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_chinabank" readonly value="'.$payAmount.'"></div> ';
        $def_url .= "<input type=button onclick='authChinabank();' value='网银支付' readOnly  class='buttons4' >";
        $def_url .= "</form>";
        $def_url .= '
<script>
function authChinabank(){
    $.ajax({
        url: "/payment/auth/pay_type/chinabank/",
        type: "post",
		data:{amount:$("#pay_chinabank").val(),batch_sn:'.$order['batch_sn'].'},
        beforeSend: function(){},
        success: function(data){$("#htm_chinabank").html(data);$("#form_chinabank").submit();}
        });
}
</script>';

        return $def_url;
    }

    function respond(){
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'chinabank')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }

        $v_oid          = trim($_POST['v_oid']);
        $v_pmode        = trim($_POST['v_pmode']);
        $v_pstatus      = trim($_POST['v_pstatus']);
        $v_pstring      = trim($_POST['v_pstring']);
        $v_amount       = trim($_POST['v_amount']);
        $v_moneytype    = trim($_POST['v_moneytype']);
        $remark1        = trim($_POST['remark1']);
        $remark2        = trim($_POST['remark2']);
        $v_md5str       = trim($_POST['v_md5str']);

        /**
         * 重新计算md5的值
         */
        $key            = $payment['chinabank_key'];

        $md5string = strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
        /* 检查秘钥是否正确 */
        if ($v_md5str==$md5string) {
            if ($v_pstatus == '20')  {
                /* 改变订单状态 */
                $apiReturnRes = $this -> _api -> update($v_oid, $v_amount, 'chinabank');
                if ($apiReturnRes['result']) {
                    $this -> returnRes['stats'] = true;
                    $this -> returnRes['thisPaid'] = $v_amount;
                    $this -> returnRes['difference'] = $apiReturnRes['remainder'];//差额
                } else {
                    $this -> returnRes['stats'] = false;
                    $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
                }
            } else {
                $this -> returnRes['stats'] = false;
                $this -> returnRes['msg'] = '支付失败';
            }
        } else {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
        }
        return $this -> returnRes;
    }

    function sync()
    {
        $this->writeLog('chinabank');
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'chinabank')));
        $payment = unserialize($payment['config']);

        $v_oid     =trim($_POST['v_oid']);
        $v_pmode   =trim($_POST['v_pmode']);
        $v_pstatus =trim($_POST['v_pstatus']);
        $v_pstring =trim($_POST['v_pstring']);
        $v_amount  =trim($_POST['v_amount']);
        $v_moneytype  =trim($_POST['v_moneytype']);
        $remark1   =trim($_POST['remark1' ]);
        $remark2   =trim($_POST['remark2' ]);
        $v_md5str  =trim($_POST['v_md5str' ]);
        /**
         * 重新计算md5的值
         */
        $key            = $payment['chinabank_key'];
        $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key)); //拼凑加密串
        if ($v_md5str==$md5string){
            if($v_pstatus=="20") {
                $this -> _api -> update($v_oid, $v_amount, 'chinabank');
            }
            echo "ok";
        }else{
            echo "error";
        }
    }
    function auth()
    {
        $time = time();
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'chinabank')));
        $payment = unserialize($payment['config']);

        $data_vid           = trim($payment['chinabank_account']);
        $data_orderid       = $order['batch_sn'] . '-' . $time;
        $data_vamount       = round ($_POST['amount'], 2 );
        $data_vmoneytype    = 'CNY';
        $data_vpaykey       = trim($payment['chinabank_key']);
        $data_vreturnurl    = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/chinabank";

        $MD5KEY =$data_vamount.$data_vmoneytype.$data_orderid.$data_vid.$data_vreturnurl.$data_vpaykey;
        $MD5KEY = strtoupper(md5($MD5KEY));

        $def_url = "<input type=HIDDEN name='v_mid' value='".$data_vid."'>";
        $def_url .= "<input type=HIDDEN name='v_oid' value='".$data_orderid."'>";
        $def_url .= "<input type=HIDDEN name='v_amount' value='".$data_vamount."'>";
        $def_url .= "<input type=HIDDEN name='v_moneytype'  value='".$data_vmoneytype."'>";
        $def_url .= "<input type=HIDDEN name='v_url'  value='".$data_vreturnurl."'>";
        $def_url .= "<input type=HIDDEN name='v_md5info' value='".$MD5KEY."'>";
        echo $def_url;
    }


}