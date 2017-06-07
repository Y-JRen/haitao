<?php
class Shop_Models_API_Cart 
{
    /**
     * 
     * @var Shop_Models_DB_Cart
     */
	protected $_db = null;
	protected $_session = 'cart';
	protected $user;
	protected $_goodsAPI;
	/**
	 * 顺风运费单
	 */
	protected $_fare=array(
				'0.0-0.5' => "1656/0"  ,
				'0.5-1.0' => "1848/0"  ,
				'1.0-1.5' => "2040/0" ,
				'1.5-2.0' => "2232/0" ,
				'2.0-2.5' => "2424/0" ,
				'2.5-3.0' => "2553/0" ,
				'3.0-3.5' => "2783/0" ,
				'3.5-4.0' => "3013/0" ,
				'4.0-4.5' => "3243/0" ,
				'4.5-5.0' => "3473/0" ,
				'5.0-5.5' => "3588/0" ,
				'5.5-6.0' => "3703/0" ,
				'6.0-6.5' => "3818/0" ,
				'6.5-7.0' => "3933/0" ,
				'7.0-7.5' => "4048/0" ,
				'7.5-8.0' => "4163/0" ,
				'8.0-8.5' => "4278/0" ,
				'8.5-9.0' => "4393/0" ,
				'9.0-9.5' => "4508/0" ,
				'9.5-10.0' => "5628/0" ,
				'10.0-10.5' => "5684/0" ,
				'10.5-11.0' => "5740/0" ,
				'11.0-11.5' => "5796/0" ,
				'11.5-12.0' => "5852/0" ,
				'12.0-12.5' => "5908/0" ,
				'12.5-13.0' => "5964/0" ,
				'13.0-13.5' => "6020/0" ,
				'13.5-14.0' => "6076/0" ,
				'14.0-14.5' => "6132/0" ,
				'14.5-15.0' => "7293/0" ,
				'15.0-15.5' => "7359/0" ,
				'15.5-16.0' => "7425/0" ,
				'16.0-16.5' => "7491/0" ,
				'16.5-17.0' => "7557/0" ,
				'17.0-17.5' => "7623/0" ,
				'17.5-18.0' => "7689/0" ,
				'18.0-18.5' => "7755/0" ,
				'18.5-19.0' => "7821/0" ,
				'19.0-19.5' => "7887/0" ,
				'19.5-100.0' => "432/1",
				'100.0-300.0' => "432/1",
				'300.0-99999' => "414/1",
			);
	/**
	 * 顺风运费系数
	 */
	protected $_ratio = 0.053;

	public function __construct(){
		$this -> _db = new Shop_Models_DB_Cart();
        $this -> _session = new Zend_Session_Namespace($this -> _userCertificationName);
        $this -> user = Shop_Models_API_Auth :: getInstance() -> getAuth();
        $this -> _goodsAPI = new Shop_Models_API_Goods();
	}
	
	/**
     * 放入购物车
     *
     * @param   string     $productSN
     * @param   int     $number
     * @return  array
     */
    public function buy($productSN, $number,&$fcart=array())
    {
        $_goods = new Shop_Models_DB_Goods();
        $data = $_goods -> getProductInfo(" and product_sn='{$productSN}'");
        $productID = $data['product_id'];
        if ($productID) {
            
            if(!empty($fcart)){
                $cart = $fcart;
            }else{
                $cart = self :: makeCartGoodsToArray();
            }
            
            $goodsInfo = $_goods -> getGoodsProductInfo(" and product_sn='{$productSN}'", 't3.product_id,limit_number');
            if ( $goodsInfo['limit_number']!= 0 && ($cart[$goodsInfo['product_id']] + $number) > $goodsInfo['limit_number'] ) {
    	        return "该商品只能购买{$goodsInfo['limit_number']}件！";
    	    }
            //继续购物中如果购买的商品已经存在，那么就直接累加商品数量,否则添加一条记录
            if (isset($cart[$productID])) {
                $cart[$productID] += $number;
            } else {
                $cart[$productID] = $number;
            }
            
            $this -> setCartCookie($cart);
            $fcart = $cart;
            
            unset($_SESSION['price_point'],$_SESSION['price_account']);//商品改动删除积分抵扣，帐户余额
        }
    }

    /**
     * 删除购物车中的指定数量的商品
     *
     * @param   int     $productID
     * @param   int     $number
     * @return  void
     */
    public function del($productID, $number, $usePlugin = true)
    {
        $cart = self :: makeCartGoodsToArray();
        if (isset($cart[$productID])) {
            unset($cart[$productID]);
        }
        $this -> setCartCookie($cart);
    }
    /**
     * 修改购物车中的指定商品的数量
     *
     * @param   int     $productID
     * @return void
     */
    public function change($productID, $number)
    {
        $_goods = new Shop_Models_DB_Goods();
        $goodsInfo = $_goods -> getGoodsProductInfo(" and t2.product_id='{$productID}'", 't2.product_id,limit_number');
        if ($goodsInfo['limit_number']!=0 && $number > $goodsInfo['limit_number'] ) {
    	    return "该商品只能购买{$goodsInfo['limit_number']}件！";
    	}
        $cart = self :: makeCartGoodsToArray();
        if (isset($cart[$productID])) {
            $cart[$productID] = $number;
            unset($_SESSION['price_point'],$_SESSION['price_account']);//商品改动删除积分抵扣，帐户余额
        }
        if ($outof = substr(str_replace(',' . $productID . ',', ',', ',' . $_COOKIE['outof'] . ','), 1, -1)) {
            setcookie('outof', $outof, time () + 86400 * 365, '/');
        } else {
            setcookie('outof', '', time () + 86400 * 365, '/');
        }
        $this -> setCartCookie($cart);
    }
	/**
     * 设置COOKIE
     *
     * @param   array   $cart
     * @return  void
     */
    public function setCartCookie($cart = null)
    {
        setcookie('cart', $this -> makeCartGoodsToString($cart), time () + 86400 * 365, '/');
    }
	/**
     * 变形购物车cookie中的商品ID串至数组
     *
     * @return  array
     */
    public static function makeCartGoodsToArray()
    {
        $cart = array();
        if ($_COOKIE['cart']) {
            $tmp = explode('|', $_COOKIE['cart']);
            if (is_array($tmp) && count($tmp)) {
                foreach ($tmp as $temp) {
                    $item = explode(',', $temp);
                    $productID = intval($item[0]);
                    $productNumber = intval($item[1]);
                    if ($productNumber > 0) {
                        $cart[$productID] = $productNumber;
                    }
                }
            }
        }
        return $cart;
    }

	/**
     * 变形商品数组至字符串
     *
     * @param   array   $cart
     * @return  string
     */
    public function makeCartGoodsToString($cart)
    {
        if (is_array($cart) && count($cart)) {
            foreach ($cart as $productID => $number) {
                $tmp[] = $productID . ',' . $number;
            }
            $cartStr = implode('|', $tmp);
        }
        return $cartStr;
    }
	/**
     * 取购物车商品列表
     *
     * @param   int     $productID
     * @return  array
     */
    public function getCartProduct($cart = null)
    {
        $_goods = new Shop_Models_API_Goods();
        $discount = $this -> user['discount'];
        $outof = $_COOKIE['outof'];
        $number = $amount = $weight = $volume = 0;
        $cart = $cart !== null ? $cart : self :: makeCartGoodsToArray();
        if (is_array($cart) && count($cart)) {
        	$fields = 'a.product_id,product_sn, product_name,a.cat_id,p_weight,p_length,p_height,p_width,'
        	        . 'produce_cycle, product_img,product_arr_img,goods_img,is_vitual, a.cost,price,price_seg,goods_img,goods_name,tax,g.goods_id,g.fare,g.shop_price,g.onsale,a.goods_style,a.lid,a.p_status';
            $tmpProducts = $_goods -> getProduct("a.product_id in(".implode(',', array_keys($cart)).")",$fields);
           
            if ($tmpProducts) {
                foreach ($tmpProducts as $k => $v) {
                    $tmpProduct[$v['product_id']] = $v;
                }
                foreach ($cart as $k => $v) {
                    if($tmpProduct[$k]){
                        $products['data'][] = $tmpProduct[$k];
                    }
                }
            }
            $stockApi = new Admin_Models_API_Stock();
            if (is_array($products['data']) && count($products['data'])) {
                foreach ($products['data'] as $k => $product) {
					if($product['tax'] > 50){
					    $product['price'] = $product['price']+$product['tax'];
					}
                	$products['data'][$k]['allow_modify'] = 1;
                	$products['data'][$k]['number'] = $cart[$product['product_id']];
                	$products['data'][$k]['org_price'] = $products['data'][$k]['price'];
                	$cookie_num += $cart[$product['product_id']];
                	//下架商品不计算总金额
                	if($product['onsale'] == 1){
                		continue;
                	}
                	//冻结产品不计算
                	if($product['p_status'] == 1){
                	    continue;
                	}
                	//库存是否充足
                	$stockApi -> setLogicArea($product['lid']);
                	if(!$stockApi -> checkPreSaleProductStock($product['product_id'],$cart[$product['product_id']],true)){
                	    $products['data'][$k]['stock'] = 0;
                	}else{
                	    $products['data'][$k]['stock'] = 1;
                	}

					//处理数量价格区间
					$products['data'][$k]['price_seg'] = unserialize($products['data'][$k]['price_seg']);
                    $products['data'][$k]['price'] = $_goods -> getPrice($products['data'][$k]['price_seg'], $products['data'][$k]['price'], $products['data'][$k]['number']);
             
                	$number += $cart[$product['product_id']];
                    $amount += $products['data'][$k]['price'] * $cart[$product['product_id']];
                    $products['data'][$k]['amount'] += $amount;
                    $weight += $product['p_weight'] * $cart[$product['product_id']];
                    $volume += round($product['p_length'] * $product['p_width'] * $product['p_height'] * $cart[$product['product_id']] * 0.001, 3);
                    if (strstr($outof, $product['product_id'])) {
                        $products['data'][$k]['outofstock'] = true;
                    }
                    //是否包含虚拟商品
                    if ($product['is_vitual']) {
                        $hasVitual = 1;
                        if (!isset($onlyVitual)) {
                            $onlyVitual = 1;
                        }
                    }
                    else {
                        $onlyVitual = 0;
                    }
                }
            }
        }

        $products['cookie_num'] = $cookie_num;
        $products['number'] = $number;
        $products['goods_amount'] = $amount;
        $products['amount'] = $amount;
        $products['weight'] = $weight;
        $products['volume'] = $volume;
        $products['has_vitual'] = $hasVitual;
        $products['only_vitual'] = $onlyVitual;

        $card = new Shop_Models_API_Card();
        $products = $card -> setCardMsg($products);
        return $products;
    }

    //购物卡 购买
    public function getCartGiftCard($cart)
    {
    	$_goods = new Shop_Models_API_Goods();
    	$amount = $number = 0;
    	if (is_array($cart) && count($cart)) {
    		$tmpProducts = $_goods -> getProduct("a.product_id in(" . implode(',', array_keys($cart)) . ")", 'a.product_id,product_sn, product_name,a.cat_id,p_weight,p_length,p_height,p_width,produce_cycle, product_img,product_arr_img,goods_id,goods_sn,goods_name, view_cat_id, market_price,price, staff_price, goods_img, price_seg,cat_sn,c.cat_name,limit_number,is_vitual, a.cost');
    		if ($tmpProducts) {
    			foreach ($tmpProducts as $k => $v) {
    				$tmpProduct[$v['product_id']] = $v;
    			}
    			foreach ($cart as $k => $v) {
    				$products['data'][] = $tmpProduct[$k];
    			}
    		}
    		if (is_array($products['data']) && count($products['data'])) {
    			foreach ($products['data'] as $k => $product) {
    	
    				$products['data'][$k]['allow_modify'] = 1;
    				$products['data'][$k]['number'] = $cart[$product['product_id']];
    				$products['data'][$k]['org_price'] = $products['data'][$k]['price'];
    				$amount += $products['data'][$k]['price'] * $cart[$product['product_id']];
    				$number += $cart[$product['product_id']];
    			}
    		}
    	}
    	
    	$products['number'] = $number;
    	$products['amount'] = $amount;
    	$products['goods_amount'] = $amount;
    	return $products;
    }
    
    /**
     * 取购物车商品数量
     *
     * @param   void
     * @return  array
     */
    public static function getCartProductCount()
    {
    	$productNumber = 0;
    	if ($_COOKIE['cart']) {
            $tmp = explode('|', $_COOKIE['cart']);

            if (is_array($tmp)) {
                foreach ($tmp as $temp) {
                    $item = explode(',', $temp);

                    if (intval($item[1]) > 0) {
                    	$productNumber += $item[1];
                    }
                }
            }
        }
        if ($_COOKIE['p']) {
        	$packages = explode('|', $_COOKIE['p']);

        	if (is_array($packages)) {
        		foreach ($packages as $key => $package)
        		{
					$productNumber += count(explode(',', $package));
        		}
        	}
        }

        if ($_COOKIE['gift']) {
        	$productNumber += count(explode(',', $_COOKIE['gift']));
        }
        return $productNumber;
    }

	/**
     * 新增地址
     *
     * @param   array     $data
     * @return  int
     */
    public function addAddr($data)
    {
        return $this -> _db -> addAddr($data);
    }
	/**
     * 统计某个用户的地址数量
     *
     * @return  int
     */
    public function countAddr()
    {
        return $this -> _db -> countAddr($this -> user['member_id']);
    }
	/**
     * 更新指定条件的地址
     *
     * @param   array     $where
     * @return  bool
     */
    public function editAddr($where, $data)
    {
        return $this -> _db -> editAddr($where, $data);
    }
	/**
     * 删除指定条件的地址
     *
     * @param   array     $where
     * @return  bool
     */
    public function delAddr($where)
    {
        return $this -> _db -> delAddr($where);
    }
    public function setSetting($key, $value)
    {
        $this -> _session-> cart[$key] = $value;
    }
    public function getSetting($key)
    {
    	return $this -> _session-> cart[$key];
    }
	/**
     * 取指定条件的地址列表
     *
     * @param   array     $where
     * @param   int     $id
     * @return  array
     */
    public function getAddress($where, $id = null)
    {
        $result = $this -> _db -> getAddress($where);
        if ($result) {
            foreach ($result as $index => $data) {
                if ($data['area_id'] == -1) {
                    $result[$index]['area_name'] = '其它区';
                }
            }
        }
        return $result;
    }
	/**
     * 匹配物流公司
     *
     * @param    int      $areaID
     * @return   string
     */
    public function getLogistic($areaID)
    {
        $areaID = intval($areaID);
        if (!$areaID) {
            return false;
        }
        $data = $this -> _db -> getLogistic(array('area_id' => $areaID));
        if ($data) {
            foreach ($data as $k => $v) {
                $tmp[$v['logistic_code']] = $v['cod'];
            }
        }
        if (count($tmp) == 1 && $tmp['ems']) {
            $logistic = array('label' => 'EMS', 'cod' => $tmp['ems']);
        } else if (count($tmp) > 1) {
            unset($tmp['ems']);
            $logistic = array('label' => '普通快递', 'cod' => 0);
            foreach ($tmp as $logisticCode => $cod) {
                if ($cod) {
                    $logistic['cod'] = 1;
                }
            }
        }
        return $logistic;
    }

	/**
     * 支付方式显示判定
     *
     * @param    array    $where
     * @return   array
     */
    public function showPayment($logistic= array())
    {
        $cat_products = $this  -> getCartProduct();
        

    	
        $onlyCod = true;
        if ($cat_products['data']) {
            //判断是否只有虚拟商品
            if ($cat_products['only_vitual']) {
            }
            else {
                //判断订单中是否只有团购商品，如果是，则不能货到付款
                foreach ($cat_products['data'] as $data) {
                    if (!$data['tuan_id']) {
                        $onlyCod = false;
                        break;
                    }
                }
            }
        }
        else    $onlyCod = false;

        if ($onlyCod && ($this -> user['last_pay_type'] == 'cod')) {
            $defaultPayment = 'alipay';
        }
        else    $defaultPayment = $this -> user['last_pay_type'] ? $this -> user['last_pay_type'] : 'alipay';

		$cardAPI = new Shop_Models_API_Card();
		if ($cardAPI -> hasGoodsCard()) {
			$payment = $this -> getPayment(array('pay_type' => 'cod'));
			$defaultPayment='cod';
		}
		else {
			$payment = $this -> getPayment(array('status' => 0));
		}

        if($onlyCod|| $cat_products['goods_amount']=='0' ){
                foreach($payment as $k=>$v){
                    if($v['pay_type'] =='cod'){
                        unset($payment[$k]);
                        break;
                    }
                }
        }
        foreach ($payment as $key => $var) {
            if ($var['pay_type'] != 'cod') {
                if( $var['is_bank'] =='1' || $var['is_bank'] =='2'){
                    $payment['bank_list'][]=$var;
                }else{
                    $payment['list'][]=$var;
                }
            } else {
                $payment['cod']=$var;
            }
            unset($payment[$key]);
        }
        
        return array('defaultPayment'=>$defaultPayment ,'payment'=>$payment);
    }

	/**
     * 取支付方式
     *
     * @param    array    $where
     * @return   array
     */
    public function getPayment($where = null)
    {
        return $this -> _db -> getPayment($where);
    }

	/**
     * 取专享支付方式
     *
     * @param    array    $where
     * @return   array
     */
    public function getOnlyPayment($pay_type = null)
    {
        return $this -> _db -> getOnlyPayment($pay_type);
    }

	/**
     * 提交订单
     *
     * @param    array    $data
     * @return   bool
     */
    public function addOrder($data ,$address = array())
    {
        
        $tmp = $this -> getCartGoods();
        if(!$tmp){
        	return 'goods_error';
        }else{
            if(array_key_exists('japanese', $tmp['goods'])){
                foreach($tmp['goods']['japanese'] as $v){
                    if($v['stock'] == 0 && $v['onsale'] !=1){
                        return 'stock_error';
                    }    
                }
            }
            if(array_key_exists('hongkong', $tmp['goods'])){
                foreach($tmp['goods']['hongkong'] as $v){
                    if($v['stock'] == 0 && $v['onsale'] !=1){
                        return 'stock_error';
                    }    
                }
            }
        }
        $goods = $tmp['goods'];
        $data = $tmp['data'];
        
        //产品分仓
        $goods = $goods;
        //商品价
        $h_total = $data['hgoods_price'];
        $j_total = $data['jgoods_price'];
        //数量
        $h_number = $data['hnum'];
        $j_number = $data['jnum'];
        
        //行邮税
        $h_tax = $data['htax'];
        $j_tax = $data['jtax'];
        //重量
        $h_weight = $data['hweight'];
        $j_weight = $data['jweight'];
        //运费
        $h_priceLogistic = $this -> getFareByWeight('sf', $h_weight);
        $j_priceLogistic = $this -> getFareByWeight('sf', $j_weight);
        
    	if(($h_total > 1000 && $h_number >1) || ($j_total > 1000 && $j_number >1)){
        	return 'price_error';
        }
        
        $return_data = array();
        $j_pricePay = $j_priceOrder = $j_total + $j_priceLogistic;
        $time = time();
        if(array_key_exists('hongkong', $goods)){
        	$order_sn = Custom_Model_CreateSn::createSn('H');
        	$return_data['H']['order_sn'] = $order_sn;
        	$return_data['H']['order_id'] = $order_id;
        	$return_data['H']['account'] = $h_total + $h_priceLogistic + $h_tax; 
        	$order_id = $this->_createOrder($data, $order_sn, 1);
        	$order_data = array(
        		'order_id'       => $order_id,
        		'order_sn'       => $order_sn,
        		'add_time'       => $time,
        		'price_order'    => $h_total + $h_priceLogistic + $h_tax,
        		'price_goods'    => $h_total,
        		'tax'            => $h_tax,
        		'price_logistic' => $h_priceLogistic,
        		'price_pay'      => $h_total + $h_priceLogistic + $h_tax,
        		'price_payed'    => 0,
        		'account_payed'  => 0,
        	    'point_payed'    => 0,
                'gift_card_payed'=> 0,
                'status_pay'     => 0,
        	);
            $order_batch_id = $this->_createOrderBatch($data, $order_data, $address);
            //添加产品
            $this->_creteOrderGoods($goods['hongkong'], $order_id, $order_batch_id, $order_sn,1);
        }
        
        if(array_key_exists('japanese', $goods)){
        	$order_sn = Custom_Model_CreateSn::createSn('J');
        	$order_id = $this->_createOrder($data, $order_sn, 2);
        	$return_data['J']['order_sn'] = $order_sn;
        	$return_data['J']['order_id'] = $order_id; 
        	$return_data['J']['account'] = $j_total + $j_priceLogistic + $j_tax; 
        	$order_data = array(
        		'order_id'       => $order_id,
        		'order_sn'       => $order_sn,
        		'add_time'       => $time,
        		'price_order'    => $j_total + $j_priceLogistic + $j_tax,
        		'price_goods'    => $j_total ,
        		'tax'            => $j_tax,
        		'price_logistic' => $j_priceLogistic,
        		'price_pay'      => $j_total + $j_priceLogistic + $j_tax,
        		'price_payed'    => 0,
        		'account_payed'  => 0,
        	    'point_payed'    => 0,
                'gift_card_payed'=> 0,
                'status_pay'     => 0,
        	);
            $order_batch_id = $this->_createOrderBatch($data, $order_data, $address);
            //添加产品
            $this->_creteOrderGoods($goods['japanese'], $order_id, $order_batch_id, $order_sn,2);
        }
        
        //清空购物车
        $this -> setCartCookie();
        setcookie('order_note', '', time () + 86400 * 365, '/');
        unset($_SESSION['price_point'], $_SESSION['price_account']);
        $this->setSetting('order_info', serialize($return_data));
    }
    
    protected  function _creteOrderGoods($goods, $order_id, $order_batch_id, $order_sn,$lid=null)
    {
       foreach ($goods as $data){
           if($data['onsale'] == 1 || $data['p_status']){
               continue;
           }
       		$arr = array(
       			'order_id'          => $order_id,
                'order_batch_id'    => $order_batch_id,
                'order_sn'          => $order_sn,
                'batch_sn'          => $order_sn,
                'type'              => 0,                                                                     
                'add_time'          => time(),
                'product_id'        => $data['product_id'],
                'product_sn'        => $data['product_sn'],
                'goods_id'          => $data['goods_id'],
                'goods_name'        => $data['goods_name'],
                'goods_style'       => $data['goods_style'],
                'cat_id'            => $data['cat_id'],
                'cat_name'          => '',
                'weight'            => $data['p_weight'],
                'length'            => $data['p_length'],
                'width'             => $data['p_width'],
                'height'            => $data['p_height'],
                'number'            => $data['number'],
                'price'             => $data['org_price'],
                'sale_price'        => $data['shop_price'],
                'cost'              => $data['cost'],
                'remark'            => '',
       			'tax'   			=> $data['tax'],
       			'fare'  			=> 0,
       		);
            $tmp[$data['product_id']] = $this -> _db -> addOrderBatchGoods($arr);
            $stockAPI = new Admin_Models_API_Stock();
            if($lid){
            	$stockAPI -> setLogicArea($lid);
            }
            $stockAPI -> holdSaleProductStock($data['product_id'], $data['number']);
            //新增购买记录
           // $_msg = new Shop_Models_API_Msg();
		   /// if (intval($data['goods_id'])) 
		    	//$_msg -> insertBuyLog($data['goods_id'], $this -> user['user_name'], $this -> user['rank_name'], $time);
            	
            	
        }
    }
    
   
    //创建订单批次
    protected function _createOrderBatch($data,$arr, $address)
    {
    	 $order_batch_id = $this -> _db -> addOrderBatch(array('order_id'         => $arr['order_id'],
                                                             'order_sn'         => $arr['order_sn'],
                                                             'batch_sn'         => $arr['order_sn'],
                                                             'add_time'         => $arr['add_time'],
                                                             'is_visit'         => intval($data['is_visit']),
                                                             'type'             => 0,
                                                             'note'             => $data['note'],
                                                             'price_order'      => $arr['price_order'],
                                                             'price_goods'      => $arr['price_goods'],
                                                             'price_logistic'   => $arr['price_logistic'],
                                                             'price_pay'        => $arr['price_pay'],
                                                             'price_payed'      => $arr['price_payed'],
                                                             'account_payed'    => $arr['account_payed'],
                                                             'point_payed'      => $arr['point_payed'],
                                                             'gift_card_payed'  => $arr['gift_card_payed'],
                                                             'status_pay'       => $arr['status_pay'],
                                                             'pay_type'         => 'easipay',
                                                             'pay_name'         => '东方支付',
                                                             'addr_consignee'   => $address['consignee'] ? $address['consignee'] : '',
                                                             'addr_province'    => $this -> _db -> getAreaName($address['province_id']),
                                                             'addr_city'        => $this -> _db -> getAreaName($address['city_id']),
                                                             'addr_area'        => $this -> _db -> getAreaName($address['area_id']),
                                                             'addr_province_id' => $address['province_id'] ? $address['province_id'] : 0,
                                                             'addr_city_id'     => $address['city_id'] ? $address['city_id'] : 0,
                                                             'addr_area_id'     => $address['area_id'] ? $address['area_id'] : 0,
                                                             'addr_address'     => $address['address'] ? $address['address'] : '',
    	 													 'addr_eng_address' => $address['eng_address'] ? $address['eng_address'] : '',
                                                             'addr_zip'         => trim($address['zip']) ? trim($address['zip']) : $this -> _db -> getAreaZip($address['area_id']),
                                                             'addr_tel'         => $address['phone'] ? $address['phone'] : '',
                                                             'addr_mobile'      => $address['mobile'] ? $address['mobile'] : '',
                                                             'addr_email'       => $address['email'] ? $address['email'] : '',
                                                             'addr_fax'         => $address['fax'] ?$address['fax'] : '',
                                                             'sms_no'           => $data['sms_no'] ? $data['sms_no'] : '',
                                                             'tax'              => $arr['tax'],
                                                           ));
           return $order_batch_id;
    }
    //创建订单
    protected function _createOrder($data, $order_sn, $lid=1)
    {
    	 $orderID = $this -> _db -> addOrder(array(
    	  							   'order_sn'        => $order_sn,
                                       'batch_sn'        => $order_sn,
                                       'add_time'        => time(),
									   'invoice_type'    => $data['invoice_type'],
									   'invoice'         => $data['invoice'],
        		                       'invoice_content' => $data['invoice_content'],
                                       'operator_id'     => $operator_id,
                                       'user_id'         => $this -> user['user_id'],
                                       'user_name'       => $this -> user['user_name'],
                                       'rank_id'         => $this -> user['rank_id'],
                                       'shop_id'         => '1',
                                       'source'          => 0,
    	 							   'lid'             => $lid,
    	                               'source'          => '1',
                                       ));
        return $orderID;
    }
    
    
   /**
     * 礼品卡 购买
     * @param array $data
     * @param array $unionInfo
     * @return multitype:unknown mixed Ambigous <void, string> number
    */
   public function addOrderGiftCard($data,$unionInfo=Array())
   {

      $address = array_shift($this -> _db -> getAddress(array('address_id' => $this -> getSetting('addr'),'member_id' => $this -> user['member_id'])));   	  
   	  $giftCard = $_SESSION['tmp_giftcard'];
   	  $cart = $this -> getCartGiftCard($giftCard);
  
   	  //取支付信息
   	  $payment = array_shift($this -> _db -> getPayment(array('pay_type' => $data['payment'])));
   	  $product = $cart['data'];//取商品信息 
   	  $priceGoods = $cart['goods_amount'];   	  
   	  $priceLogistic=0;
   	  
   	  $priceCouponCard = $priceGiftCard = 0;

   	  $pricePay = $priceOrder = $cart['amount']; //订单金额
   	  $orderPay  = $cart['amount'];//需支付金额
   	  $pricePayed = 0; //已支付金额
   	  
   	  $time = time();
   	  if ($this -> user['ltype'] == 'phone' &&  $_COOKIE['operator_id'] ) {
   	  	$operator_id = $_COOKIE['operator_id'];
   	  	setcookie('operator_id','', time() + 86400, '/');
   	  }else{
   	  	$operator_id='0';
   	  }
   	  
   	  //新增订单
   	  $orderSN = Custom_Model_CreateSn::createSn();
   	  $source = 1;
   	  
   	  $orderID = $this -> _db -> addOrder(array('order_sn' => $orderSN,
   	  		'batch_sn' => $orderSN,
   	  		'add_time' => $time,
   	  		'invoice_type'=>$data['invoice_type'],
   	  		'invoice'=>$data['invoice'],
   	  		'operator_id' => $operator_id,
   	  		'user_id' => $this -> user['user_id'],
   	  		'user_name' => $this -> user['user_name'],
   	  		'rank_id' => $this -> user['rank_id'],
   	  		'parent_id' => $unionInfo['parent_id'],//联盟
   	  		'parent_param' => $unionInfo['parent_param'],//联盟
   	  		'proportion' => $unionInfo['proportion'],//联盟
   	  		'shop_id' => '1',
   	  		'source' => $source ? $source : 0,
   	  ));
   	  
   	  //新增订单批次
   	  $orderBatchID = $this -> _db -> addOrderBatch(array('order_id' => $orderID,
   	  		'order_sn' => $orderSN,
   	  		'batch_sn' => $orderSN,
   	  		'add_time' => $time,
   	  		'is_visit' => intval($data['is_visit']),
   	  		'type' => 0,
   	  		'note' => $data['note'],
   	  		'price_order' => $priceOrder,
   	  		'price_goods' => $priceGoods,
   	  		'price_logistic' => floatval($priceLogistic),
   	  		'price_pay' => $pricePay,
   	  		'price_payed' => $pricePayed,
   	  		'account_payed' =>  0,
   	  		'point_payed' => 0,
   	  		'gift_card_payed' =>0,
   	  		'status_pay' => ($orderPay==0) ? 2 : 0,
   	  		'pay_type' => $payment['pay_type'],
   	  		'pay_name' => $payment['name'],
   	  		'addr_consignee' => $address['consignee'] ? $address['consignee'] : '',
   	  		'addr_province' => $this -> _db -> getAreaName($address['province_id']),
   	  		'addr_city' => $this -> _db -> getAreaName($address['city_id']),
   	  		'addr_area' => $this -> _db -> getAreaName($address['area_id']),
   	  		'addr_province_id' => $address['province_id'] ? $address['province_id'] : 0,
   	  		'addr_city_id' => $address['city_id'] ? $address['city_id'] : 0,
   	  		'addr_area_id' => $address['area_id'] ? $address['area_id'] : 0,
   	  		'addr_address' => $address['address'] ? $address['address'] : '',
   	  		'addr_zip' => trim($address['zip']) ? trim($address['zip']) : $this -> _db -> getAreaZip($address['area_id']),
   	  		'addr_tel' => $address['phone'] ? $address['phone'] : '',
   	  		'addr_mobile' => $address['mobile'] ? $address['mobile'] : '',
   	  		'addr_email' => $address['email'] ? $address['email'] : '',
   	  		'addr_fax' => $address['fax'] ?$address['fax'] : '',
   	  		'sms_no' => $data['sms_no'] ? $data['sms_no'] : '',
   	  ));
   	  
   	  $_msg = new Shop_Models_API_Msg();
   	  //新增订单商品
   	  $hasCupGoods = false;
   	  $hasZeroGoods = false;
   	  $stockAPI = new Admin_Models_API_Stock();
   	  $productApi =  new Admin_Models_API_Product();
   	  $gifcardApi  = new  Admin_Models_API_GiftCard();
   	  
   	  if ($product) { 
   	  	foreach($product as $data){
   	  		$tmp[$data['product_id']] = $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
   	  				'order_batch_id' => $orderBatchID,
   	  				'order_sn' => $orderSN,
   	  				'batch_sn' => $orderSN,
   	  				'type' => 8,
   	  				'add_time' => $time,
   	  				'product_id' => $data['product_id'],
   	  				'product_sn' => $data['product_sn'],
   	  				'goods_id' => $data['goods_id'],
   	  				'goods_name' => $data['goods_name'],
   	  				'cat_id' => $data['cat_id'],
   	  				'cat_name' => $data['cat_name'],
   	  				'weight' => $data['p_weight'],
   	  				'length' => $data['p_length'],
   	  				'width' => $data['p_width'],
   	  				'height' => $data['p_height'],
   	  				'number' => $data['number'],
   	  				'price' => $data['org_price'],
   	  				'sale_price' => $data['price'],
   	  				'cost'       => $data['cost'],
   	  				'remark' => $data['remark']?$data['remark']:''));
   	  
   	  		$stockAPI -> holdSaleProductStock($data['product_id'], $data['number']);
   	  		//新增购买记录
   	  		if (intval($data['goods_id'])) $_msg -> insertBuyLog($data['goods_id'], $this -> user['user_name'], $this -> user['rank_name'], $time);
   	  		
   	  	}
   	  }
   	  
   	  unset($_SESSION['tmp_giftcard']);
   	  
   	  return array(
   	  		'order_id'=>$orderID,
   	  		'batch_sn'=>$orderSN,
   	  		'price_order'=>$priceOrder,
   	  		'price_goods'=>$priceGoods,
   	  		'pay_name'=>$payment['name'],
   	  		'pay_type'=>$payment['pay_type'],
   	  		'orderPay'=>$orderPay,
   	  		'invoice_type' => $data['invoice_type'],
   	  		'invoice' => $data['invoice']
   	  );
   	  
   }
    
    
    
	/**
     * 设置 客服购物习惯 保存 购物地址 和 支付方式
     *
     * @return   void
     */
    public function habit()
    {
       $addressID = $this ->getSetting('addr');
   
       $payment  = $this -> getSetting('payment');
       $delivery  = $this-> getSetting('delivery'); 
        
       $data = array();    
       $memberApi = new Shop_Models_API_Member();
        if ($addressID) {             
            $addressID && $memberApi -> editAddressUseTime($addressID, time());
            $data['last_address_id'] = $addressID;  
        }
        
        if ($payment)    $data['last_pay_type']  = $payment;
        if ($delivery)   $data['last_invoice']  = serialize($delivery);         
        $memberApi -> editMemberCartInfo($data);
        Shop_Models_API_Auth :: getInstance() -> updateAuth();
    }
	/**
     * 检查订单
     *
     * @return   bool
     */
    public function checkOrder($cart = null, $address = null,$checkAll=false)
    {
        $cart = $cart ? $cart : $this -> getCartProduct();
        if($cart['number'] <= 0){//购物车没有商品
            Custom_Model_Message::showAlert('提示：您的购物车内没有商品！' , true, '/flow/index/');
        }

        /*
            Start::检测库存
        */
        //1.检测正常商品是否有库存
        $product = $cart['data'];

        $stockAPI = new Admin_Models_API_Stock();

        if ($product) {
            $productOutSale = '';
            foreach ($product as $v) {
                if ($v['onsale'] == 1) {    //该产品已经下架
                    Custom_Model_Message::showAlert("{$v['goods_name']} 该商品已经下架", true, '/flow/index/');
                    exit;
                }
                if (!$stockAPI -> checkPreSaleProductStock($v['product_id'], $v['number'], true)) {
                    $stock = $stockAPI -> getSaleProductStock($v['product_id'],true);

                    $log_params = array(
                        'product_id'  => $v['product_id'],
                        'need_number' => $v['number'],
                        'able_number' => $stock['able_number'],
                        'created_by'  => 'guest',
                        'created_ts'  => date('Y-m-d H:i:s'),
                    );
                    $stockAPI->addStockRemindLog($log_params);
                    Custom_Model_Message::showAlert("{$v['goods_name']} 库存已不足！", true, '/flow/index/');
                    exit;
                }
            }

        }
     
        //下单验证 
        if($checkAll)
        {
        	if (!$cart['only_vitual']) {
        		if(!$address){   //没有收货地址
        			$this -> setSetting('addr', null);
        			Custom_Model_Message::showAlert('提示：填写收货地址信息！' , true, '/flow/order/');
        		} else {
        			$this -> setSetting('addr', $address['address_id']);
        		}
        	}        	
        	 
        	$payType = $this -> getSetting('payment');
        	if (!$payType) {    //没有支付方式
        		Custom_Model_Message::showAlert('提示：请选择支付方式！' , true, '/flow/order/');
        	} else if ($payType == 'cod' && $cart['number'] == 1 && $cart['data'][0]['product_id'] == 595) {
        		$this -> setSetting('payment', null);
        		Custom_Model_Message::showAlert('提示：只领取免费商品不能使用货到付款，请更换支付方式！' , true, '/flow/order/');
        	}
        	
        	//这里重新读取是为了防止有人恶意的攻击，修改支付方式，特别是货到付款
        	$logistic = $this -> getSetting('logistic');
        	$payment = array_shift($this -> _db -> getPayment(array('status' => 0, 'pay_type' => $payType)));        	 
        	if(!$payment){  //没有支付方式
        		Custom_Model_Message::showAlert("提示：当前的支付方式不正确。可能是以下原因：\\n\\n 1.您还未选择支付方式；\\n \\n 2.您之前选择的货到付款不支持现在的配送地址；\\n\\n 请重新选择支付方式！" , true, '/flow/order/');
        	}
        }         
        
        
    }
	/**
     * 产生支付代码
     *
     * @return   string
     */
    public function getPayInfo($order)
    {
        if (!$order['pay_type']) {
            $order = array_shift($this -> _db -> getOrderBatch(array('user_id' => $this -> user['user_id'])));
        }
        $code = ucfirst($order['pay_type']);
        $class = 'Custom_Model_Payment_' . $code; 
        $pay = new $class($order['batch_sn']);
        return $pay -> getCode(array('bank' => false, 'pay_hidden' => true,'target'=>true));

    }
	/**
     * 取指定条件的订单信息
     *
     * @return   string
     */
    public function getOrder($where)
    {
        return $this -> _db -> getOrder($where);
    }
	/**
     * 取得指定条件的地区列表
     *
     * @param   array     $where
     * @return  array
     */
    public function getArea($where)
    {
        return $this -> _db -> getArea($where);
    }
    /**
     * 指定条件地区列表JSON数据
     *
     * @param   array     $where
     * @return  string
     */
    public function getAreaJsonData($where)
    {
        return Zend_Json::encode($this -> _db -> getArea($where));
    }

	/**
     * 取得指定条件的订单批次
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatch($where=null)
    {
        $where['user_id'] = $this -> user['user_id'];
        return $this -> _db -> getOrderBatch($where);
    }
	/**
     * 取地区物流价格
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaPrice($areaID)
    {
        return $this -> _db -> getAreaPrice($areaID);
    }
	/**
     * 取订单商品信息
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getOrderBatchGoods($where)
    {
        return $this -> _db -> getOrderBatchGoods($where);
    }
    /**
     * 下单成功发邮件
     *
     * @return void
     */
	public function sendOrderEmail($goodsList,$order)
	{
        $templateValue['user_name'] = $order['user_name'];
		$templateValue['order_sn'] = $order['order_sn'];
		$templateValue['addr_consignee'] = $order['addr_consignee'];
		$templateValue['order_add_time'] = date('Y-m-d  h:i:s',$order['add_time']);
		$templateValue['price_order'] = $order['price_order'];
		$templateValue['price_goods'] = $order['price_goods'];
		$templateValue['price_pay'] = $order['price_pay'];
		$templateValue['pay_name'] = $order['pay_name'];
        $templateValue['shop_name'] = Zend_Registry::get('config') -> name;
        $templateValue['send_date'] = date('Y-m-d',time());
        $templateValue['addr_province']=$order['addr_province'];
        $templateValue['addr_city']=$order['addr_city'];
        $templateValue['addr_area']=$order['addr_area'];
        $templateValue['addr_address']=$order['addr_address'];
        $templateValue['addr_zip']=$order['addr_zip'];
        $templateValue['addr_mobile']=$order['addr_mobile'];
        $templateValue['addr_tel']=$order['addr_tel'];
        $templateValue['logistic_name']=$order['logistic_name'];
        if(!$templateValue['logistic_name']){$templateValue['logistic_name']='普通快递';}
        $templateValue['logistic_no']=$order['logistic_no'];
        $templateValue['logistic_time']=$order['logistic_time'];
        $templateValue['logistic_fee_service']=$order['price_logistic'];
        $tmp='';
        $siteurl = 'http://www.1jiankang.com';
        
        foreach ($goodsList as $v)
        {
        	if($v['type']==0)
        	{
			 $tmp.='<tr>
			<td><a href="'.$siteurl.'/b-'.$v['as_name'].'/detail'.$v['goods_id'].'.html">'.$v['goods_name'].'</a></td>
			<td>'.$v['product_sn'].'</td>
			<td>￥'.$v['price'].'</td>
			<td>'.$v['number'].'</td>
			<td>￥'.$v['price']*$v['number'].'</td>
	        </tr>';
        	}
        }
        unset($goodsList);

        $templateValue['goodsList']=$tmp;
	    $template = new Shop_Models_API_EmailTemplate();
	    $template = $template -> getTemplateByName('add_order', $templateValue);
	    $mail = new Custom_Model_Mail();
	    if ($mail -> send($order['user_name'], $template['title'], $template['value'])) {
		    return 'sendError';
	    } else {
	    	return 'sendPasswordSucess';
	    }
	}
	/**
	 * 得到邮编
	 *
	 * @param $area_id int
	 *
	 * @return 邮编
	 * */
	public function getAreaZip($area_id) {
		return $this -> _db -> getAreaZip($area_id);
	}

    /**
	 * 计算运费
	 *
	 * @param $user_id int
	 * @param $offer_id int
	 *
	 * @return int
	 * */
     public function getLogisticPrice($product) {
        //如果仅包含虚拟商品免运费
        if ($product['only_vitual']) {
            return 0;
        }
        
        $shopConfig = Zend_Registry::get('shopConfig');
        if (!isset($shopConfig['price_logistic'])) {
            $shopConfig['price_logistic'] = 10;
        }
        if (!isset($shopConfig['free_logistic'])) {
            $shopConfig['free_logistic'] = 199;
        }
        
        $cards = $_SESSION['Card']['cardCertification'];//取抵用券信息
        if ($cards) {
            foreach ($cards as $v) {
                $priceCoupon += $v['card_price'];
            }
        }
        
		if (isset($shopConfig['free_logistic']) && ($product['goods_amount']) >= $shopConfig['free_logistic']) {
			$priceLogistic = 0;
            return $priceLogistic;
		} else {
			$priceLogistic = $shopConfig['price_logistic'];//默认物流费用 系统设置里面
		}
		$altlogistic = 0;
		//判断优惠券减免运费
		if($priceLogistic>0 && $product['card']) {
		    foreach($product['card'] as $key) {
    		    if ( ($key['freight'] > 0) && ($altlogistic < $key['freight']) ) {
    		        $altlogistic = $key['freight'];
    		    }
		    }
		}
		
		$priceLogistic -= $altlogistic;
    	if ( $priceLogistic < 0 )   $priceLogistic = 0;
        return $priceLogistic;
     }
     
     /**
      * 通过重量 获得运费
      * @param string	 $name 快递名称
      * @param float	 $weight 重量
      * @return number|boolean
      */
     public function getFareByWeight($name,$weight)
     {
        return 0;
     	if($name == "sf"){
     		foreach($this->_fare as $k => $v){
     			$tmp = explode('-', $k);
     			if($weight <= $tmp[1] && $weight > $tmp[0]){
     			    $fare = explode('/', $v);
     			    if($fare[1] == 0){
     			        return round($fare[0]*($this->_ratio));
     			    }else{
     			        return round($fare[0]*$weight*$this->_ratio);
     			    }	
     			}
     		}
     		return false;
     	}
     }
     
     
     public function getCartGoods()
     {
     	$data = $this -> getCartProduct();
     	
     	//产品分仓
     	$goods = array();
     	$cartGoods = array(
     				'old_tax'		=>0,//原行邮税
     				'tax'			=>0,//总行邮税
     				'shipping_fee'	=>0,//总运费
     				'shop_price'	=>0,//商品总价
     				'jnum'			=>0,//日本商品总数量
     				'hnum'			=>0,//香港商品总数量
     				'jtax'			=>0,//日本商品总行邮税
     				'htax'			=>0,//香港商品总行邮税
     				'jprice'		=>0,//日本仓库总价
     				'hprice'		=>0,//香港仓库总价
     				'jweight'		=>0,//日本商品总重量
     				'hweight'		=>0,//香港商品总重量
     				'jgoods_price'	=>0,//日本仓库商品总价
     				'hgoods_price'	=>0,//香港仓库商品总价
     			);
     	
     	if(!count($data['data'])){
     		return false;
     	}
     	//var_dump($data['data']);die();
     	foreach ($data['data'] as $val){
     	    if($val['p_status'] == 1){
     	        continue;
     	    }
     		if($val['onsale'] == 1){
     		    if(stristr($val['product_sn'],'H')){
     		        $goods['hongkong'][] = $val;
     		    }else{
     		        $goods['japanese'][] = $val;
     		    }
     			continue;
     		}
     		if(stristr($val['product_sn'],'H'))
     		{
     			$goods['hongkong'][] = $val;
     			$cartGoods['htax'] += $val['tax'] * $val['number'];
     			$cartGoods['hnum'] += $val['number'];
     			$cartGoods['hprice'] += $val['price'] * $val['number'];
     			$cartGoods['hweight'] += $val['p_weight'] * $val['number'];
     			$cartGoods['hgoods_price'] += $val['shop_price'] * $val['number'];
     		}else{
     			$goods['japanese'][] = $val;
     			$cartGoods['jtax'] += $val['tax'] * $val['number'];
     			$cartGoods['jnum'] += $val['number'];
     			$cartGoods['jprice'] += $val['price'] * $val['number'];
     			$cartGoods['jweight'] += $val['p_weight'] * $val['number'];
     			$cartGoods['jgoods_price'] += $val['shop_price'] * $val['number'];
     		}
     		$cartGoods['tax'] += $val['tax'] * $val['number'];
     		$cartGoods['shop_price'] += $val['shop_price'] * $val['number'];
     	}
     	$cartGoods['shipping_fee'] = $this -> getFareByWeight('sf', $cartGoods['hweight']+$cartGoods['jweight']);
     	$cartGoods['old_tax'] = $cartGoods['tax'];
     	if($cartGoods['htax'] <= 50){
     		$cartGoods['tax'] -= $cartGoods['htax'];
     		$cartGoods['hprice'] -= $cartGoods['htax'];
     		$cartGoods['htax'] = 0;
     	}
     	if($cartGoods['jtax'] <= 50){
     		$cartGoods['tax'] -= $cartGoods['jtax'];
     		$cartGoods['jprice'] -= $cartGoods['jtax'];
     		$cartGoods['jtax'] = 0;
     	}
     	return array('goods'=>$goods,'data'=>$cartGoods);;
     }
 
}