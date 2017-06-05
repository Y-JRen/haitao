<?php
class Admin_Models_API_Kjt
{
	private $version = 'v1.2';
	private $company = array();
	private $order_declare_url = "http://101.231.112.5:8080/cboi/order/orderlist.htm";//订单申报url
	private $order_search_url = "http://101.231.112.5:8080/cboi/order/orderAuditQuery.htm";//订单查询url
	
	
	public function __construct()
	{
		$this -> authApi =  new Shop_Models_API_Auth();
		$this -> paymentApi = new Shop_Models_API_Payment();
		$payment = array_shift($this -> paymentApi -> getPayment(array('pay_type' => 'easipay')));
		$payment = unserialize($payment['config']);
		$this -> company = array(
								'coName' => $payment['coName'],//企业名称
								'coCode' => $payment['sender_code'],//企业代码
								'sign'	 => '0aK2985t4W762F5z05e8wf1Nn752595Z',//私钥
						);
	}
	/**
	 * 订单申报
	 * @param array $order 订单全信息
	 * @param array $order_goods 订单商品全信息
	 */
	public function order_request($order=null,$order_goods=null)
	{
		
		$data = $this -> order_json($order, $order_goods);
		$EData = urlencode($data);
		$SignMsg = $this -> getSign($data);
		$tmp = "EData={$EData}&SignMsg={$SignMsg}";
		$this->log('订单申报：'.$tmp);
		$response = urldecode($this -> authApi -> http($this->order_declare_url, 'POST',$tmp));
		echo "batch_sn=>{$order['batch_sn']}:";
		var_dump($response);echo "<br/>";
		$this->log($order['batch_sn'].'订单申报：'.$response);
		$response = json_decode($response,JSON_FORCE_OBJECT);
		if($response['status'] == 'SUCCESS'){
			$this -> updat_order(1, $order['batch_sn']);
			return true;
		}elseif($response){
			return $response['errorMsg'];
		}
		return false;
	}
	/**
	 * 订单申报返回
	 * @param json $json
	 */
	public function order_response($json=null)
	{
		if($json){
			$json = str_replace('\\', '', $json);
			$this -> log('申报返回：'.$json);
			$data = json_decode($json,JSON_FORCE_OBJECT);
			if($data['status'] == '1'){
				$this -> updat_order(2,substr($data['merchantOrderId'],0,15));
				$this -> log('申报返回：ok');
				return true;
			}else{
				$this -> log('申报返回：海关验证失败');
			}
		}else{
			$this -> log('申报返回：没json');
		}
		return true;
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
	 * 订单申报查询
	 * @param string $sn
	 */
	public function order_search($sn)
	{
		if($sn){
			$array = array(
						'version'=>$this -> version,
						'coCode'=>$this->company['coCode'],
						'merchantOrderId' => $sn,
					);
			$EData = urlencode(json_encode($array));
			$tmp = "EData={$EData}";
			$response = json_decode(urldecode($this -> authApi -> http($this->order_search_url, 'GET',$tmp)),JSON_FORCE_OBJECT);
			if($response['status'] == 'success'){
				$this -> updat_order(2, $sn);
				return true;
			}
		}
		return false;
	}

	/**
	 * 订单申报json
	 * @param array $order 订单全信息
	 * @param array $order_goods 订单商品全信息
	 */
	private function order_json($order=null,$order_goods=null)
	{
		$goods = array();
		$cat = array();
		if(!empty($order_goods)){
			foreach($order_goods as $k => $v){
				$goods[] = array(
								'cargoName'			=>$v['goods_name'],//单项购买商品名称
								'cargoCode'			=>$v['goods_sn'],//单项购买商品编号
								'cargoNum'			=>$v['number'],//单项购买商品数量
								'cargoUnitPrice'	=>$v['sale_price'],//单项购买商品单价
								'cargoTotalPrice'	=>number_format($v['sale_price']*$v['number'],2),//单项购买商品总价
								'cargoTotalTax'		=>$order['tax'] == '0.00'? 0:number_format($v['tax']*$v['number'],2),//单项购买商品行邮税总价
							);
				if( !in_array($v['cat_name'],$cat)){
					$cat[] = $v['cat_name'];
				}
			}
		}
		$tmp = array(
					'version'				=>$this -> version,//网关版本
					'commitTime'			=>date('YmdHis'),//提交时间 数字串，固定长度 格式为：年[4 位]月[2 位]日[2 位]时[2 位]分[2 位]秒[2 位] 20071117020101
					'coName'				=>$this->company['coName'],//企业名称
					'coCode'				=>$this->company['coCode'],//企业代码
					'serialNumber'			=>$order['batch_sn'],//请求方生成唯一流水号
					'merchantOrderId'		=>$order['pay_log_id'],//每商家提交的订单号，必须在自身账户交易中唯一
					'assBillNo'				=>$order['logistic_no'],//物流提供的唯一分运单号
					'orderCommitTime'		=>date('YmdHis',$order['add_time']),//订单提交时间
					'senderName'			=>'耿双博',//发件人姓名
					'senderTel'				=>'03-5295-2507',//发件人电话  字符串 电话格式：区号-号码
					'senderCompanyName'		=>'嘉日（株）',//发件方公司名称
					'senderAddr'			=>'東京都千代田区神田佐久間町１－８　ニュー千代田ビル２ｆ',//发件人地址
					'senderZip'				=>'101-0025',//发件地邮编
					'senderCity'			=>'东京',//发件地城市
					'senderProvince'		=>'东京都',//发件地省/州名
					'senderCountry'			=>'JPN',//发件地国家
					'cargoDescript'			=> implode(',', $cat),//订单商品信息简述
					'allCargoTotalPrice'	=>$order['price_goods'],//全部购买商品合计总价 
					'allCargoTotalTax'		=>$order['tax'] == '0.00' ? 0 : $order['tax'],//全部购买商品行邮税总价
					'expressPrice'			=>$order['price_logistic'],//物流运费
					'otherPrice'			=>0,//其它费用
					'recPerson'				=>$order['addr_consignee'],//收货人姓名
					'recPhone'				=>$order['addr_mobile']?$order['addr_mobile']:$order['addr_tel'],//收货人电话
					'recCountry'			=>'中国',//收货地国家
					'recProvince'			=>$order['addr_province'],//收货地省/州
					'recCity'				=>$order['addr_city'],//收货地城市
					'recAddress'			=>$order['addr_address'],//收货地地址
					'recZip'				=>$order['addr_zip'],//收货地邮编
					'serverType'			=>'S01',//业务类型 S01：一般进口 S02：保税区进口
					'custCode'				=>'2244',//海关关区代码
					'operationCode'			=>1,//操作编码
					'spt'					=>'',//扩展字段 (可空)
					'Cargoes'				=>$goods,
					'payMethod'				=>'EASIPAY',//支付方式 订单支付时选择的第3方支付公司或银行信息 EASIPAY：东方支付 VISA: VISA MASTER: MASTER
					'payMerchantName'		=>$this->company['coName'],//电商企业在支付方申请后的公司名称
					'payMerchantCode'		=>$this->company['coCode'],//企业支付编号
					'payAmount'				=>$order['pay'],//支付总金额
					'payCUR'				=>'CNY',//付款币种
					'payID'					=>$order['serial_number'],//支付交易号
					'payTime'				=>date('YmdHis',$order['order_time']),//支付交易时间
				);
		$this -> arrayRecursive($tmp, 'urlencode', true);
		return urldecode(json_encode($tmp));
	}
	/**
	 * 加签
	 * @param json $data
	 */
	public function getSign($data)
	{
		return strtoupper(md5($data.$this->company['sign']));
	}
	/**
	 * 修改订单申报状态
	 * @param int $type （1：订单申报  2：订单申报返回）
	 * @param string $sn 订单号
	 */
	private function updat_order($type, $sn)
	{
		if($sn){
			$order_api = new Admin_Models_API_Order();
		}else{
			return false;
		}
		switch($type){
		    case 1://订单申报
		    case 2://订单申报返回成功
		    case 3://订单申报返回失败
		        return $order_api -> updateOrderDeclare($type, $sn);
		    default:
		        return false;
		}
	}
	/**
.   *
    *    使用特定function对数组中所有元素做处理
    *    @param  string  &$array     要处理的字符串
    *    @param  string  $function   要执行的函数
    *    @return boolean $apply_to_keys_also     是否也应用到key上
    **/
	private function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
	{
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this -> arrayRecursive($array[$key], $function, $apply_to_keys_also);
			} else {
				$array[$key] = $function($value);
			}
			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
	}
	
}