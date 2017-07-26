<?php
class Admin_Models_API_OMS
{
    protected $evn = 'test';
    protected $wms_id;//OMS系统在电商系统中的代号
    protected $stock_id;//仓库在电商系统中的ID
    protected $owner_id;//电商在第三方OMS系统中的ID
    protected $url;

    public function __construct()
    {
        if($this->evn == 'test'){
            $this->wms_id = 'dcits';//OMS系统在电商系统中的代号
            $this->stock_id = 8;//仓库在电商系统中的ID
            $this->owner_id = 'NWvxgUj';//电商在第三方OMS系统中的ID
            $this->url = 'http://116.228.112.52:8091/interface_HttpServiceEC/DoAction.aspx';
        }else{
            $this->wms_id = '';//OMS系统在电商系统中的代号
            $this->stock_id = 0;//仓库在电商系统中的ID
            $this->owner_id = '';//电商在第三方OMS系统中的ID
            $this->url = '';
        }
    }

    /**
     * 原始订单推送接口 25
     * 应用场景：用户在电商商城下单，电商通知仓库根据订单发货。
     */
    public function sendOMS($order)
    {
        $data = array(
            "company_code"=>"NW2N554",    // 平台企业编码；String类型
            "company_name"=>"上海众馥实业有限公司",    // 平台企业名称；String类型
            "order_id"=>"OrderId",    // 订单号；String类型，建议预留40位
            "order_create_time"=>"2014-11-06 16:16:20", // 下单时间；String类型
            "pay_time"=>"2014-11-06 16:16:20",  // 支付时间；String类型
            "shop_id"=>"", // 店铺id，非必填；String类型
            "transport_service_code"=>"EMS", //物流供应商代号，列表见附件；String类型
            "transport_type"=>"1",   // 物流类型：1:EMS快递包裹，2:EMS国内标准快递；String类型
            "transport_order_id"=>"qaws12345",   // 物流单号；String类型
            "receiver_zip"=>"311251",             // 收货地邮编 非必填；String类型
            "receiver_province"=>"浙江",	// 收货地省/州String类型
            "receiver_city"=>"杭州",// 收货地城市 String类型
            "receiver_county"=>"滨江区",// 收货地区String类型
            "receiver_address"=>"浙江; 杭州; 滨江区;网商路599号",// 收货地地址 String类型
            "receiver_name"=>"阿基米德",//收货人姓名 String类型
            "receiver_mobile"=>"13777387619",// 收货人电话 String类型
            "receiver_phone"=>"0571-12345678",// 收货人电话 String类型
            "lotistic_mark"=>"杭州滨江",//快递公司大头笔，可空
            "logistic_condition"=>"0",// 送达类型：0表示普通 1次日达
            "senderName"=>"国药1健康",  //发件人姓名
            "senderTel"=>"4006033883", //发件人电话
            "senderCompanyName"=>"壹健康全球购", //发件方公司名称
            "senderAddr"=>"上海市青浦区北青公路8228号3区8号", //发件人地址
            "senderZip"=>"210000", //发件地邮编
            "senderCity"=>"上海市", //发件地城市
            "senderProvince"=>"上海市", //发件地省/州名
            "senderCountry"=>"上海市", //发件地国家
            "cargoDescript"=>"", //订单商品信息简述
            "allCargoTotalPrice"=> 532.8, //商品价格, 保留5位小数, (商品集合中每个单项商品CargoTotalPrice 的合计值)说明：商品实际成交价,含非现金抵扣金额
            "allCargoTotalTax"=> 53.28, //代扣税款, 保留5位小数, (商品集合中每个单项商品CargoTotalTax 的合计值)无费用值为0
            "expressPrice"=> 25, //运杂费, 保留5位小数, 不包含在商品价格中的运杂费，无运费值为0
            "otherPrice"=> 0, //非现金抵扣金额, 保留5位小数, 使用积分、虚拟货币、代金券等非现金支付金额(正值)，无则填写0
            "recCountry"=>"中国", //收货地国家    --------新补充的 "serverType": "S02", //业务类型S01：一般进口S02：保税区进口 "custCode": "2238",  //海关关区代码
            "operationCode"=>"1", //操作编码
            "customDeclCo"=>"SHBX",//物流进境申报企业
            "spt"=>"", //扩展字段                  --------新补充的
            "payMethod"=>"GZYLWLZF", //支付方式
            "payMerchantName"=>"广州银联网络支付有限公司", //企业支付名称, 支付企业在海关注册登记的企业名称
            "payMerchantCode"=>"440131T001", //企业支付编号, 支付企业的海关注册登记编号
            "payAmount"=> 611.08, //支付总金额, 保留5位小数，包括全部商品价格、代扣税款、运杂费、减去非现金抵扣金额
            "payCUR"=>"142", //付款币种, 限定为人民币，填写“142”   "payID": "2014030120394812", //支付交易号
            "payTime"=>"20071117020101" , //支付交易时间
            "customs_release_method"=>"" , //放行状态：可自定义,如0拦截1放行，可空
            "channel_id"=>"" , //订单渠道ID，可空
            "channel_name"=>"" , //订单渠道名称，可空
            "neutral_package"=>"0" , //包装类型：0贴标包装，1中性包装，2指定包装，3特殊订单，4其他
            "insuredFee"=>"0", //保价费, 商品保险费用, 保留5位小数,无则填0
            "buyerRegNo"=>"wm43225", //订购人注册号, 订购人的交易平台注册号,必填
            "customerName"=>"王某", //订购人姓名,必填
            "customerIdType"=>"1", //订购人证件类别, 默认‘1’身份证,必填
            "customerId"=>"510265790128567", //订购人证件号,必填
            "customerTel"=>"13600000000", //订购人电话,必填
            "batchNumbers"=>"", //商品批次号
            "assureCod"=>"3110966871",//担保扣税的企业海关注册登记编号，只限清单的电商平台企业、电商企业、物流企业,必填
            "emsNo"=>"H22386000007",//保税模式必填，填写区内仓储企业在海关备案的账册编号，用于保税进口业务在特殊区域辅助系统记账（二线出区核减）,必填
            "note"=>"", //备注
            "version"=>"v2.0", //网关版本, 注意为小写字母,必填, 固定值：老版本v1.2总署版v2.0
            "order_items"=>array(),
        );
        foreach($a as $v){
            $data['order_items'] = array(
                "sku_id"=>"sku id", // sku_id
                "qty"=>1000, // 数量
                "cargoUnitPrice"=>177.60 ,  // 单项购买商品单价,数字串中保留5位小数, 赠品单价填写为0
                "cargoTotalPrice"=>532.80, // 单项购买商品总价,数字串中保留5位小数， 同一编号商品总数总价,等于单价乘以数量
                "cargoTotalTax"=>53.28, // 单项购买商品缴税总价,数字串中保留5位小数，同一编号商品总数备案价格对应的税金额 无费用值为0
                "itemDescribe"=>"", // 交易平台销售商品的描述信息
                "note"=>"" // 备注, 促销活动，商品单价偏离市场价格的，可以在此说明
            );
        }
        $response = $this->_sendOMS(25,$data);
    }

    public function sync($post)
    {
        $this->log($post['notify_type'].' POST:'.json_encode($post));
        if(!$this->check($post)){
            return array('success'=>false,'error_msg'=>'验签失败！');
        }
        $data = json_decode($post['data'], true);
        switch($post['notify_type']){
            case 27:
                $res = $this->do27($data);
                break;
            case 31:
                $res = $this->do31($data);
                break;
            case 803:
                $res = $this->do803($data);
                break;
            default:
                return array('success'=>false,'error_msg'=>'notify_type不正确');
        }
        return $res;
    }

    protected function do27($data)
    {

    }

    protected function do31($data)
    {

    }

    protected function do803($data)
    {

    }

    protected function _sendOMS($notify_type,$data = array())
    {
        $data = preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))",json_encode($str));
        $sign = $this->sign($data);

        $post = array(
            'sign' => $sign,
            'notify_type' => $notify_type,
            'notify_id' => ''.time().rand(10,99),//网络请求流水号，notify_id相同的请求被认为是同一个请求的多次重试 需要保证不同OMS系统之间的notify_id不能重复，可以建议对方加上前缀
            'notify_time' => date('Y-m-d H:i:s'),//请求时间戳
            'wms_id' => $this->wms_id,
            'stock_id' => $this->stock_id,
            'owner_id' => $this->owner_id,
            'data' => $data,
        );

        $client = new Zend_Http_Client($this->url);
        $client->setParameterPost($post);
        $response = $client->request('POST');
        $this->log($notify_type.' RESPONSE:'.json_encode($response).' RESQUEST:'.json_encode($post));
        return $response;
    }

    protected function check($post)
    {
        if($this->checkSign($post['data'], $post['sign'])){
			if($post['wms_id'] == $this->wms_id){
                return true;
            }
    }
        return false;
    }

    /**
     * 加签
     */
    protected function sign($data){
        return md5($data);
    }

    /**
     * 验签
     */
    public function checkSign($data,$sign){
        if($sign == $this->sign($data)){
            return true;
        }
        return false;
    }

    protected function log($data)
    {
        $db = Zend_Registry::get('db');
        $array = array(
            'content'=>$data,
            'add_time'=>time(),
            'type'=>2
        );
        $db -> insert('log',$array);
    }
}
