<?php
abstract class Custom_Model_Payment_Abstract
{
    /*
     * 当前时间戳
     */
    protected $time = null;

    /*
     * API对象
     */
    protected $_api = null;

	protected $dbadv = null;

    /*
     * 视图对象
     */
    protected $helper = null;

    /*
     * 前端请求情对象
     */
    protected $_front = null;

    /*
     * 前端请求情对象
     */
    protected $_request = null;
	
	/**
	 * 支付金额
	 */
	protected $amount = 0.00;
	
	/**
	 * 订单编号
	 */
	protected $order_sn;

	/**
	 * 支付方式id
	 */
	protected $pay_id;
	
	/**
	 * 业务模型标识
	 */
	protected $business;
	
	/**
	 * 支付类型
	 */
	protected $pay_type = null;
	
    /*
     * 支付接口回传数据后支付插件的响应结果
     * stats      表示处理成功与否，
     * thisPaid   本次支付金额
     * difference 本次支付后支付后差额 可为正也可为负
     * msg表示    处理后的信息
     */
    public $returnRes = array(
                            'stats' => false,
                            'thisPaid' => 0,
                            'difference' => 0, //差额
                            'msg'   => ''
                            );
							
    protected $_openWriteLog = false;

	public function __construct($batchSN = null,$order_type = 2,$amount=0.00,$business='',$config = array())
    {
        $this -> time = time();
		
        $this -> _api = new Shop_Models_API_Payment();
		$this->dbadv = new Custom_Model_DbComm();
		$this->amount = $amount;
		$this->order_sn = $batchSN;
		$this->business = $business;
		
        $this -> imgBaseUrl = Zend_Registry::get('config') -> view -> imgBaseUrl;
        $this -> _front = Zend_Controller_Front::getInstance();
        $this -> _request = & $this -> _front -> getRequest();
        if ($batchSN) {
        	if($order_type == 4){
				$this->order = $this->dbadv->getRow('shop_money_order',array('order_sn'=>$batchSN));        		
				$payment = $this->dbadv->getRow('shop_payment',array('id'=>$this->order['pay_id']));
        	}else{
	            $this -> order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $batchSN)));
	            $payment = array_shift($this -> _api -> getPayment(array('pay_type' => $this -> order['pay_type'])));
        	}
            $this -> payment = unserialize($payment['config']);
			$this->pay_id = $payment['id'];
        }
    }

    /**
     *写异步请求log
     *
     * @return void
     */
    public function writeLog($logCont = null,$fileName = 'Abstract')
    {
        if ($this -> _openWriteLog) {
            $filename = Zend_Registry::get('systemRoot').'/tmp/log/bank/'.$fileName.'.log';
            if ($logCont == null) {
                error_log(var_export($_REQUEST, true), 3, $filename);
            } else {
                error_log($logCont, 3, $filename);
            }
        }
    }

    protected function setOpenWriteLog()
    {
        $this -> _openWriteLog = true;
    }
    /**
     *
     *
     *
     * @return void
     */
    public function respond()
    {
        return false;
    }

     /**
     * 查询单笔订单详细并更新(如果有必要)
     *
     * @return void
     */
    public function query()
    {
        if ( !$this -> order )      return false;
        if ( !$this -> payment )    return false;

        $user = Shop_Models_API_Auth :: getInstance() -> getAuth();
        if ( !$user )   return false;

        if ( $this -> order['user_id'] != $user['user_id'] )    return false;

        if ( $this -> order['status_pay'] != 0 )    return false;

        return true;
    }

    function getCode($arg=null) {
        /*
         * $arg['pay_hidden']   是否让顾客更改支付金额
         * $arg['target']       是否新开窗口支付
         */
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
		$target = '';
        $order = $this->order;
        $payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
        $def_url  = "<form id='form_pay' action='{$this->payment['pay_url']}' method='post' {$target}>\n";
        $def_url .= "<div style='display:none;' id='htm_pay'></div>";
        $def_url .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_amount" readonly value="'.$payAmount.'"></div> ';
        $def_url .= "<input type='button' onclick='authPay(\"{$payAmount}\");' value='{$this->payTypeName}'   class='buttons4'>";
        $def_url .= "</form>\n";
        $def_url .= '
<script>
function authPay(payAmount){
    if ( $("#pay_amount").val() > payAmount ) {
        alert("您的实际支付金额不能大于订单支付金额!");
        return false;
    }
    $.ajax({
        url: "/payment/auth/pay_type/'.$this->payType.'/",
        type: "post",
        data: {amount:$("#pay_amount").val(),batch_sn:'.$this->order['batch_sn'].'},
        beforeSend: function(){},
        success: function(data) {
            $("#htm_pay").html(data);
            $("#form_pay").submit();';

        $user = Shop_Models_API_Auth :: getInstance() -> getAuth();
        if ( $user ) {
            $def_url .= 'showPayConfirmWin();';
        }

        $def_url .= '}
    });
}
function showPayConfirmWin()
{
    if (window.pageYOffset) {
        y = window.pageYOffset;
    }
    else if (document.documentElement && document.documentElement.scrollTop) {
        y = document.documentElement.scrollTop;
    }
    else if (document.body) {
        y = document.body.scrollTop;
    }
    var a = document.documentElement || document.body;
    $("#show_gray").css("height", String(a.scrollHeight)+"px");
    $("#show_gray").css("display", "block");
    $("#PayConfirmWin").css("top", String(y + 200)+"px");
    $("#PayConfirmWin").css("display", "block");
}
</script>';

        return $def_url;
    }
    
    function getCode2($arg=null,$type='display') 
    {
        $order = $this->order;
        $payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
        $def_url  = "<form id='form_pay' action='/payment/go' method='post' target='_blank'>\n";
        $def_url .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="amount" name="amount" readonly value="'.$payAmount.'"></div> ';
        $def_url .= "<input type='hidden' name='pay_type' value='{$order['pay_type']}'>";
        $def_url .= "<input type='hidden' name='batch_sn' value='{$order['batch_sn']}'>";
        $def_url .= "<input type='submit' value='{$this->payTypeName}' class='buttons4' onclick=\"return showPayConfirmWin()\">";
        $def_url .= "</form>\n";
        $def_url .= '<script>
        function showPayConfirmWin()
{
    if (window.pageYOffset) {
        y = window.pageYOffset;
    }
    else if (document.documentElement && document.documentElement.scrollTop) {
        y = document.documentElement.scrollTop;
    }
    else if (document.body) {
        y = document.body.scrollTop;
    }
    var a = document.documentElement || document.body;
    $("#show_gray").css("height", String(a.scrollHeight)+"px");
    $("#show_gray").css("display", "block");
    $("#PayConfirmWin").css("top", String(y + 200)+"px");
    $("#PayConfirmWin").css("display", "block");
    
    return true;
}
</script>';
        return $def_url;
    }
	/**
	 * 退款申请
	 */
    public function orderRefund($data)
    {
    	return false;
    }
    /**
     * 获得退款返回
     */
    public function getRefundRespond()
    {
    	return false;	
    }
    
}
