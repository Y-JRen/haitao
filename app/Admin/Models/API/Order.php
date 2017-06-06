<?php
class Admin_Models_API_Order
{
	private $_db = null;
	private $_product = null;

	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Order();
        $this -> _auth = Admin_Models_API_Auth :: getInstance()->getAuth();
        $this -> _product = new Admin_Models_API_Product();
        $this -> _finance = new Admin_Models_API_Finance();
	}
    /**
     * 返回订单各个状态标签
     *
     * @param   string   $type
     * @param   int     $id
     * @return  string
     */
    public function status($type, $id)
    {
        $status = array(0 => '有效单',
                        1 => '取消单',
                        2 => '无效单',
                        3 => '渠道刷单',
                        4 => '分销单',
                        5 => '预售单');

        $status_return = array(0 => '正常单',
                               1 => '退货单');

        $status_logistic = array(0 => '未确认',
                                 1 => '已确认',
                                 2 => '待发货',
                                 3 => '已发货在途',
                                 4 => '已发货签收',
                                 5 => '已发货拒收',
                                 6 => '部分签收');

        $status_pay = array(0 => '未收款',
                            1 => '未退款',
                            2 => '已收款');
        $tmp = $$type;
        return $tmp[$id];
    }

	/**
     * 取得指定条件的地区列表
     *
     * @param   array       $where
     * @param   boolean     $includeOtherArea
     * @return  array
     */
    public function getArea($where, $includeOtherArea = false)
    {
        $area = $this -> _db -> getArea($where);
        if (is_array($area) && count($area)) {
            foreach ($area as $k => $v) {
                $data[$v['area_id']] = $v['area_name'];
            }
            $includeOtherArea && $data['-1'] = '其它区';
        }
        return $data;
    }
	/**
     * 取地区名
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaName($areaID)
    {
        return $this -> _db -> getAreaName($areaID);
    }
	/**
     * 取得指定条件的支付方式
     *
     * @param   array   $where
     * @return  array
     */
    public function getPayment($where = null)
    {
        $payment = $this -> _db -> getPayment($where);
        if (is_array($payment) && count($payment)) {
            foreach ($payment as $k => $v) {
                $v['config'] = unserialize($v['config']);
                $data[$v['pay_type']] = $v;
            }
        }
        return $data;
    }
	/**
     * 保存未确认订单信息
     *
     * @param   array   $data
     * @return  bool
     */
    public function saveNotConfirmInfo($batchSN, $data)
    {
		$order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
		$row = array('invoice_type' => $data['invoice_type'],
                     'invoice' => $data['invoice'],
                     'invoice_content' => $data['invoice_content'],
                    );
        $this -> _db -> updateOrder(array('order_sn' => $order['order_sn']), $row);

        if ($data['note_staff']) {
            $time = time();
            $tmp['note_staff'] = $order['note_staff'] .
                                  $this -> _auth['admin_name'] .
                                  '^' . $time .
                                  '^' . $data['note_staff'] . "\n";
        }
        if ($data['pay_type']) {
            $payment = $this -> getPayment(array('pay_type' => $data['pay_type']));
            if ($payment) {
                $payment = array_shift($payment);
                $tmp['pay_type'] = $payment['pay_type'];
                $tmp['pay_name'] = $payment['name'];
            }elseif($data['pay_type']=='cash'){
                $tmp['pay_type'] = 'cash';
                $tmp['pay_name'] = '现金支付';
            }elseif($data['pay_type']=='bank'){
                $tmp['pay_type'] = 'bank';
                $tmp['pay_name'] = '银行打款';
            }elseif($data['pay_type']=='external'){
                $tmp['pay_type'] = 'external';
                $tmp['pay_name'] = '渠道支付';
            }
        }
        if ($data['note_logistic']) {
            $tmp['note_logistic'] = $data['note_logistic'];
        }
        if ($data['note_print']) {
            $tmp['note_print'] = $data['note_print'];
        }
        if (isset($data['price_logistic'])) {
            $tmp['price_logistic'] = $data['price_logistic'] > 0 ? $data['price_logistic'] : 0;
        }
        if (!$order['price_order'] || $data['price_payed'] > 0) {
            $tmp['price_payed'] = $data['price_payed'] - $order['price_from_return'];
            $tmp['pay_time'] = time();
        }
        $data['unlock'] && $tmp['lock_name'] = '';

		
        return $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $tmp);
    }

    /**
     * 投诉
     * @param   string      $batchSN
     * @param   string      $remark
     *
     * @return  bool
     */
    public function complain($batchSN, $remark)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $transport = new Admin_Models_DB_Transport();
        $transport -> update(array('is_complain' => 1, 'complain_time' => time()), "logistic_code='{$order['logistic_code']}' and logistic_no='{$order['logistic_no']}' and is_complain=0");
        $row = array(
                    'item_no' => $batchSN,
                    'logistic_no' => $order['logistic_no'],
                    'logistic_code' => $order['logistic_code'],
                    'logistic_status' => 5,
                    'op_time' => time(),
                    'admin_name' => $this -> _auth['admin_name'],
                    'remark' => $remark);
		    $transport -> insertTrack($row);
    }
	/**
     * 锁定订单
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function lock($datas, $val)
	{
		if (is_array($datas['ids'])) {
			foreach($datas['ids'] as $batchSN){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($val) {
			    	$data = array('lock_name' => $admin_name, 'hang' => 0);
			    } else {
			    	$data = array('lock_name' => '');
			    }
	    		$this -> _db -> lock($this -> _auth['admin_name'], $batchSN, $data);
			}
		}
        return true;
	}
	/**
     * 锁定订单超级权限
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function superLock($datas, $val)
	{
		if (is_array($datas['ids'])) {
			foreach($datas['ids'] as $batchSN){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($val) {
			    	$data = array('lock_name' => $admin_name, 'hang' => 0);
			    } else {
			    	$data = array('lock_name' => '');
			    }
	    		$this -> _db -> superLock($batchSN, $data);
			}
		}
        return true;
	}
    /**
     * 取得指定条件的订单列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getOrderBathWithPage($where=NULL, $page=1)
    {
        $data = $this -> _db -> getOrderBathWithPage($where, $page);
        if(is_array($data['data']) && count($data['data'])){
            foreach ($data['data'] as $k => $v) {
                $inBatchSN[] = $v['batch_sn'];
                $data['data'][$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $data['data'][$k]['status_value'] = $v['status'];
                $data['data'][$k]['status'] = $this -> status('status', $v['status']);
                $data['data'][$k]['status_return'] = $this -> status('status_return', $v['status_return']);
                $data['data'][$k]['status_logistic'] = $this -> status('status_logistic', $v['status_logistic']);
                $data['data'][$k]['status_pay_value'] = $v['status_pay'];
                $data['data'][$k]['status_pay'] = $this -> status('status_pay', $v['status_pay']);
                $data['data'][$k]['blance'] = round($v['price_pay'] - ($v['price_payed']+$v['account_payed']+$v['point_payed']+$v['gift_card_payed']+$v['price_from_return']), 2);
            }
            
            $data['product'] = $this -> _db -> getOrderBatchGoodsInBatchSN($inBatchSN, $where['includeOffer'], $where['includeCoupon']);
        }
        return $data;
    }
	/**
     * 根据指定的条件取批次
     *
     * @param    array    $where
     * @return   array
     */
    public function getOrderBatch($where)
    {
        $order = $this -> _db -> getOrderBatch($where);
        if (is_array($order) && count($order)) {
            foreach ($order as $k => $v) {
                $v['note_staff'] = trim($v['note_staff']);
                if ($v['note_staff']) {
                    $tmp = explode("\n", $v['note_staff']);
                    if (is_array($tmp) && count($tmp)) {
                        unset($noteStaff);
                        foreach ($tmp as $y) {
                            if ($y) {
                                $temp = explode('^', $y);
                                $noteStaff[$temp[1]] = array('admin_name'=>$temp[0],
                                                             'time'=>$temp[1],
                                                             'date'=>date('Y-m-d H:i:s', $temp[1]), 'content'=>$temp[2]);
                            }
                        }
                    }
                }
                $order[$k]['note_staff'] = $noteStaff;
                $order[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $order[$k]['add_time_unix'] = $v['add_time'];
                $order[$k]['hang_time'] = date('Y-m-d H:i:s', $v['hang_time']);
                $order[$k]['logistic_list'] = Zend_Json::decode($v['logistic_list']);
            }
        }
        return $order;
    }
    /**
     * 添加客服备注
     *
     * @param   string      $batchSN
     * @param   string      $noteStaff
     * @return  bool
     */
    public function addNoteStaff($batchSN, $noteStaff)
    {
        $time = time();
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $data['note_staff'] = $order['note_staff'] .
                              $this -> _auth['admin_name'] .
                              '^' . $time .
                              '^' . $noteStaff . "\n";
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => $time,
                     'title' => '添加客服备注',
                     'note' => $noteStaff,
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }

	/**
     * 修改配送地址
     *
     * @param   string      $batchSN
     * @param   array      $data
     * @return  void
     */
    public function editAddress($batchSN, $data)
    {
        $data['addr_zip'] = $this -> _db -> getAreaZip($data['addr_area_id']);
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '修改配送地址',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
     }
	/**
     * 修改支付方式
     *
     * @param   string      $batchSN
     * @param   array      $data
     * @return  void
     */
    public function editPayment($batchSN, $data)
    {
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '修改支付方式',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
	/**
     * 修改订单商品
     *
     * @param    string     $batchSN
     * @param    array      $data
     * @param    string     $note
     * @param    string     $error
     * @return   bool
     */
    public function editOrderBatchGoods($batchSN, $data, $note, &$error=null)
    {
        $productAPI = new Admin_Models_API_Product();
        
        $time = time();
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status'] == 4) {
            $logicArea = Custom_Model_Stock_Base::getDistributionArea($order['user_name']);
        }
        else {
            $logicArea = $order['lid'];
        }
        
        //api 检测是否有库存 开始
        $stockAPI = new Admin_Models_API_Stock();
        $stockAPI -> setLogicArea($logicArea);
        if ($data['old']) {
            foreach ($data['old'] as $orderBatchGoodsID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                $blance = $v['number'] - $product['number'];
                if ($blance > 0 && $product['product_id']) {
                    if (!$stockAPI -> checkPreSaleProductStock($product['product_id'], $blance, true)) {
                        $error = "产品ID{$product['product_id']}库存不足<br>";
                        return false;
                    }
                }
            }
        }
        if ($data['new']) {
            foreach ($data['new'] as $productID => $v) {
                if (!$stockAPI -> checkPreSaleProductStock($productID, $v['number'], true)) {
                    $error = "产品ID{$product['product_id']}库存不足<br>";
                    return false;
                }
                
                $product = array_shift($this -> _product -> get(array('product_id' => $productID)));
                if ($product['is_vitual'] || $product['is_gift_card']) {
                    $error = "新增的产品不能包含虚拟商品或礼品卡<br>";
                    return false;
                }
            }
        }
        //api 检测是否有库存 结束
        
        if ($data['old']) {
            foreach ($data['old'] as $orderBatchGoodsID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                $blance = $v['number'] - $product['number'];
                //客服判断礼券是否应该使用 开始
                if ($product['card_type'] == 'coupon') {
                    if ($v['number'] > 1) {
                        $v['number'] = 1;
                    }
                    $tmp = array('number' => $v['number']);
                    if (!$v['number']) {//释放礼券
                        if ($product['card_type'] == 'coupon') {
                            $this -> unUseCardCoupon($batchSN);
                        }
                    }
                } else {
                    $tmp = array('number' => $v['number'], 'sale_price' => $v['sale_price'],'tax'=>$v['tax']);
                }
                //客服判断礼券是否应该使用 结束
                
                $where = array('batch_sn' => $batchSN, 'order_batch_goods_id' => $orderBatchGoodsID);
                $this -> _db -> updateOrderBatchGoods($where, $tmp);
                //if ($logicArea != 4) {
                    if ($blance > 0) {
                        //api 占有库存
                        $stockAPI -> holdSaleProductStock($product['product_id'], $blance);
                        //处理礼品卡
                        if ($product['is_gift_card']) {
                            $giftCardAPI = new Admin_Models_API_GiftCard();
                            $giftCardInfo = $productAPI -> getGiftcardInfoByProductid($product['product_id']);
                            if ($giftCardInfo) {
                                $giftCardRow = array('number' => $blance,
                                                     'card_price' => $giftCardInfo['amount'],
                                                     'card_type' => 1,
                                                     'end_date' => date('Y-m-d', time() + 3600 * 24 * 365 * 3),
                                                     'order_batch_goods_id' => $orderBatchGoodsID,
                                                     'status' => 2,
                                                    );
                                $giftCardAPI -> addLog($giftCardRow);
                            }
                        }
                    }
                    else if ($blance < 0) {
                        //api 释放库存
                        $stockAPI -> releaseSaleProductStock($product['product_id'], abs($blance));
                        //处理礼品卡
                        if ($product['is_gift_card']) {
                            $giftCardAPI = new Admin_Models_API_GiftCard();
                            if (count($delCardSnArray) == 0) {
                                $giftCardList = array_shift($giftCardAPI -> getCardlist(array('order_batch_goods_id' => $orderBatchGoodsID, 'status' => 2)));
                                if ($giftCardList) {
                                    for ($i = 0; $i < abs($blance); $i++) {
                                        $card = $giftCardList[count($giftCardList) - $i - 1];
                                        if ($card) {
                                            $giftCardAPI -> deleteCard("card_sn = '{$card['card_sn']}'");
                                        }
                                        else {
                                            break;
                                        }
                                    }
                                }
                            }
                            else {
                                foreach ($delCardSnArray as $cardSN) {
                                    $giftCardAPI -> deleteCard("card_sn = '{$cardSN}'");
                                }
                            }
                        }
                    }
                //}
                if ($product['sale_price'] != $v['sale_price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 1,//1未确认订单修改商品、2订单恢复修改商品、3换货修改商品
                                 'batch_sn' => $order['batch_sn'],
                                 'order_batch_goods_id' => $product['order_batch_goods_id'],
                                 'product_sn' => $product['product_sn'],
                                 'number' => intval($v['number']),
                                 'sale_price' => floatval($product['sale_price']),
                                 'edit_price' => floatval($v['sale_price']),
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改已经存在的商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }
            }
        }

        if ($data['new']) {
            foreach ($data['new'] as $productID => $v) {
                $product = array_shift($this -> _product -> get(array('product_id' => $productID)));
                $tmp = array('order_id' => $order['order_id'],
                             'order_batch_id' => $order['order_batch_id'],
                             'order_sn' => $order['order_sn'],
                             'batch_sn' => $order['batch_sn'],
                             'add_time' => $order['add_time'],
                             'product_id' => $product['product_id'],
                             'product_sn' => $product['product_sn'],
                             'goods_name' => $product['product_name'],
                             'goods_style' => $product['goods_style'],
                             'cat_id' => $product['cat_id'],
                             'cat_name' => $product['cat_name'],
                             'weight' => $product['p_weight'],
                             'length' => $product['p_length'],
                             'width' => $product['p_width'],
                             'height' => $product['p_height'],
                             //'price' => $product['price'],
                             'sale_price' => $v['sale_price'],
                             'cost'       => $product['cost'],
                             'number' => $v['number'],
                             'tax'=>$v['tax']);
                $orderBatchGoodsID = $this -> _db -> addOrderBatchGoods($tmp);
                //api 占有库存
                if ($logicArea != 4) {
                    $stockAPI -> holdSaleProductStock($productID, $v['number']);
                }
                
                if ($product['price'] != $v['sale_price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 1,//1未确认订单修改商品、2订单恢复修改商品、3换货修改商品
                                 'batch_sn' => $order['batch_sn'],
                                 'order_batch_goods_id' => $orderBatchGoodsID,
                                 'product_sn' => $product['product_sn'],
                                 'number' => intval($v['number']),
                                 'sale_price' => floatval($product['price']),
                                 'edit_price' => floatval($v['sale_price']),
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改新增加的商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }

            }
        }

        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '修改订单商品',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //更新支付状态
        $this -> orderDetail($batchSN);
    }

    /**
     * 订单挂起
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function hang($batchSN)
    {
        $data = array('lock_name' => '',
                      'hang' => 1,
                      'hang_admin_name' => $this -> _auth['admin_name'],
                      'hang_time' => time());
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '订单挂起',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
    /**
     * 订单无效
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function invalid($batchSN)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status'] != 0) {//防止重复提交 0 正常单; 1 取消单; 2 无效单;
            return false;
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
                                         array('status' => 2,'lock_name' => ''));
	    $product = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0));
        if (is_array($product) && count($product)) {
            $stockAPI = new Admin_Models_API_Stock();
            if ($order['status'] == 4) {
                $logicArea = Custom_Model_Stock_Base::getDistributionArea($order['user_name']);
            }
            else {
                $logicArea = $order['lid'];
            }
            $stockAPI -> setLogicArea($logicArea);
            foreach($product as $k => $v) {
                //api 释放库存
                $stockAPI -> releaseSaleProductStock($v['product_id'], $v['number']);
            }
        }


        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '无效订单',
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //api 抵用券接口（整退）
        $this -> unUseCardCoupon($batchSN);
    }
    /**
     * 订单确认
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function confirm($batchSN)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status_logistic'] != 0) {//防止重复确认订单
            return 'repeat';
        }
        //更新支付状态
        $orderDetail = $this -> orderDetail($batchSN);
        
        //换货单不能有应付金额
        if ($order['parent_batch_sn'] && $orderDetail['other']['price_must_pay'] > 0) {
            return 'exchangeOrdereError';
        }
        
        //包启礼品卡和正常商品不能选择在线支付
        $hasGiftCard = false;
        $onlyGiftCard = true;
        if ($orderDetail['order']['pay_type'] != 'cod') {
            foreach ($orderDetail['product_all'] as $product) {
                if ($product['product_id'] > 0) {
                    if ($product['is_gift_card']) {
                        $hasGiftCard = true;
                    }
                    else {
                        $onlyGiftCard = false;
                    }
                }
            }
            if ($hasGiftCard && !$onlyGiftCard) {
                return 'giftCardError';
            }
        }
        
        if ($order['pay_type'] == 'cod' || $order['status_pay'] > 0  || $order['user_name']=='credit_channel' || $order['user_name']=='distribution_channel') {//货到付款 或者 已结清、未退款 赊销 直供
            $statusLogistic = 2;//待发货
            $title = '订单确认 待发货';
        } else {
            $statusLogistic = 1;//已确认待收款
            $title = '已确认 待收款';
        }
        
        $set = array('status_logistic' => $statusLogistic, 'lock_name' => '');
        
        if ($order['status'] == 4) {
            $title = '订单确认 已签收';
            //api 申请出库单出库
            if (!$this -> virtualOut($batchSN)) {//出库失败
                return 'outFail';
            }
            
            $set['status_logistic'] = 4;//已签收
            $set['logistic_time'] = time();
            
            if ($order['price_order'] > 0) {
                //添加应收款记录
                $financeAPI = new Admin_Models_API_Finance();
                $receiveData = array('batch_sn' => $batchSN,
                                     'type' => 2,
                                     'pay_type' => $order['pay_type'],
                                     'amount' => $order['price_order'],
                                    );
                $financeAPI -> addFinanceReceivable($receiveData);
            }
        }
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
        
       
        
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => $title,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
    /**
     * 确认收款
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function hasPay($batchSN,$pay_money='0.00')
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        //网关支付不允许手动收款
        substr($order['pay_type'], 0 ,6) == 'alipay' && $order['pay_type'] = 'alipay';
        if (in_array($order['pay_type'], array('alipay', 'tenpay', 'phonepay', 'bankcomm'))) {
            return 'can_not_pay_manually';
        }
        
        if($order['part_pay']=='1'){
            if($pay_money > 1 ){
                $pricePayed = floatval($order['price_payed'] + $pay_money);//更改收款金额
                $pay = $pay_money;//支付log记录本次付款金额
                if($order['status_logistic']< 2 ){
                    $status_logistic = '2';
                }else{
                    $status_logistic = $order['status_logistic'];
                }
            }else{
                return 'no_pay_money';
            }
        }else{
            if ($order['status_logistic'] != 1) {//防止重复确认收款
                return 'repeat';
            }

            $pricePayed = floatval($order['price_pay']) - floatval($order['account_payed']) - floatval($order['point_payed']) - floatval($order['gift_card_payed']) - floatval($order['price_from_return']);//更改收款金额
            $pay = $order['price_pay'] - $order['price_from_return'] - $order['price_payed'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'];//支付log记录本次付款金额
            $status_logistic='2';
        }
        
        $set = array('status_logistic' => $status_logistic,
                     'price_payed' => $pricePayed,
                     'pay_time' => time(),
                     'lock_name' => '');
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
        //添加支付log记录
        $this -> addOrderPayLog(array('batch_sn' => $batchSN, 'pay_type' =>'service', 'pay' => $pay));
        
        //添加应收款记录
        $financeAPI = new Admin_Models_API_Finance();
        $receiveData = array('batch_sn' => $batchSN,
                             'pay_type' => $order['pay_type'],
                             'amount' => floatval($order['price_pay']) - floatval($order['account_payed']) - floatval($order['point_payed']) - floatval($order['gift_card_payed']) - floatval($order['price_from_return']),
                            );
        if (in_array($order['pay_type'], array('bank', 'cash'))) {
            $receiveData['type'] = 2;
        }
        else if ($order['pay_type'] == 'external' || $order['pay_type'] == 'externalself') {
            $receiveData['type'] = 3;
        }
        $financeAPI -> addFinanceReceivable($receiveData);
        
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '确认收款 待发货 确认金额 : '.$pay ,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //更新订单金额状态
        $this -> orderDetail($batchSN);
    }
    /**
     * 确认收款订单申请返回
     *
     * @param   string      $batchSN
     * @param   array       $data
     * @return  void
     */
    public function confirmBack($batchSN, $data)
    {
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '代收款订单 申请返回',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
    /**
     * 确认收款订单取消
     *
     * @param   string      $batchSN
     * @param   string      $note
     * @return  void
     */
    public function confirmCancel($batchSN, $note=null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
		if ($order['status'] != 0 && $order['status'] != 4) {//防止重复提交
            return false;
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
                                         array('status' => 1, 'lock_name' => ''));

        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '订单取消' . $note,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        
        $stockAPI = new Admin_Models_API_Stock();
        
        if (in_array($order['lid'], $stockAPI -> getEntityAreaID())) {
    	    $product = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0));
            if (is_array($product) && count($product)) {
                $logicArea = $order['lid'];
                $stockAPI -> setLogicArea($logicArea);
                foreach($product as $k => $v) {
                    //api 释放库存
                    $stockAPI -> releaseSaleProductStock($v['product_id'], $v['number']);
                }
            }
        }
        
        //没有现金退款，直接退接口
        if ($order['price_payed'] + $order['price_from_return'] == 0) {
            //api 抵用券接口(整退)
            $this -> unUseCardCoupon($batchSN);
            //api 积分接口(整退)
            $this -> unPointPrice($batchSN);
            //api 余额接口(整退)
            $this -> unAccountPrice($batchSN);
        }
        $shopConfig = Zend_Registry::get('shopConfig');
        return true;
    }
    /**
     * 确认收款订单批量取消
     *
     * @param   array      $data
     * @return  void
     */
    public function batchCancel($data)
    {
        if ($data) {
            foreach ($data as $batchSN) {
                $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
                if ($order['lock_name'] !== $this -> _auth['admin_name']) {//需要先锁定才能 取消
                    return false;
                } else if ($order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed']> 0) {//需要退款的订单 禁止批量取消
                    return false;
                }
                $this -> confirmCancel($batchSN);
            }
        }
    }
    /**
     * 待发货订单申请返回
     *
     * @param   string      $batchSN
     * @param   string      $noteStaff
     * @return  void
     */
    public function toBeShippingBack($batchSN, $noteStaff = null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status_back'] != 0) {//防止重复提交 status_back 0 默认，1 申请取消，2 申请返回
            return false;
        }
        if ($noteStaff) {
            $time = time();
            $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
            $data['note_staff'] = $order['note_staff'] .
                                  $this -> _auth['admin_name'] .
                                  '^' . $time .
                                  '^' . $noteStaff . "\n";
        }
        $data['status_back'] = 2;
        $data['lock_name'] = '';

        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);

        //api 申请出库返回
        $outStock = new Admin_Models_API_OutStock();
        $result = $outStock -> cancelApi($batchSN, $noteStaff, 'back');
        if (!$result) {//解决物流验证不通过，订单主动返回 异常情况下特殊处理
            $this -> back($batchSN, array('is_check' => 1));
        }
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '待发货订单 申请返回',
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
    /**
     * 待发货订单申请取消
     *
     * @param   string      $batchSN
     * @param   string      $noteStaff
     * @return  void
     */
    public function toBeShippingCancel($batchSN, $noteStaff = null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status_back'] != 0) {//防止重复提交 status_back 0 默认，1 申请取消，2 申请返回
            return false;
        }
        if ($noteStaff) {
            $time = time();
            $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
            $data['note_staff'] = $order['note_staff'] .
                                  $this -> _auth['admin_name'] .
                                  '^' . $time .
                                  '^' . $noteStaff . "\n";
        }
        $data['status_back'] = 1;
        $data['lock_name'] = '';
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //api 申请出库取消
        $outStock = new Admin_Models_API_OutStock();
        $result = $outStock -> cancelApi($batchSN, $noteStaff);
        if (!$result) {
            $this -> back($batchSN, array('is_check' => 1));
        }
        
        //没有现金退款，直接退接口
        if ($order['price_payed'] + $order['price_from_return'] == 0) {
            //api 抵用券接口(整退)
            $this -> unUseCardCoupon($batchSN);
            //api 积分接口(整退)
            $this -> unPointPrice($batchSN);
            //api 余额接口(整退)
            $this -> unAccountPrice($batchSN);
        }
        
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '待发货订单 申请取消',
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        return true;
    }

    /**
     * 客服微调价格
     * @param   string      $batchSN
     * @param   array      $data
     * @return  void
     */
    public function addPriceAdjust($batchSN, $data)
    {
        $time = time();
        //添加订单调整金额日志
        $adjust = array('order_sn' => array_shift(explode('_', $batchSN)),
                        'batch_sn' => $batchSN,
                        'type' => $data['type'],
                        'money' => $data['money'],
                        'note' => $data['note'],
                        'add_time' => $time);
        $this -> _db -> addOrderBatchAdjust($adjust);
        //添加订单日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => $time,
                     'title' => '客服调整金额[￥' . $data['money'] . ']',
                     'note' => $data['note'],
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //更新支付状态
        $this -> orderDetail($batchSN);
    }
    /**
     * 恢复订单
     * @param   string      $batchSN
     * @param   array       $data
     * @param   string      $note
     * @param   string      $error
     * @return  void
     */
    public function undo($batchSN, $data, $note, &$error = null)
    {
        $time = time();
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $stockAPI = new Admin_Models_API_Stock();
        
        //api 检测是否有库存 开始
        if ($order['status'] != 4 && in_array($order['lid'], $stockAPI -> getEntityAreaID())) {
            $stockAPI -> setLogicArea($order['lid']);
            if ($data['old']) {
                foreach ($data['old'] as $orderBatchGoodsID => $v) {
                    $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                    if (!$stockAPI -> checkPreSaleProductStock($product['product_id'], $v['number'], true)) {
                        $error = "{$v['goods_name']}[{$v['product_sn']}]库存不足<br>";
                        return false;
                    }
                }
            }
            if ($data['new']) {
                foreach ($data['new'] as $productID => $v) {
                    if (!$stockAPI -> checkPreSaleProductStock($productID, $v['number'], true)) {
                        $error = "产品ID{$productID}]库存不足<br>";
                        return false;
                    }
                }
            }
        }
        //api 检测是否有库存 结束
        
        if ($data['old']) {
            foreach ($data['old'] as $orderBatchGoodsID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                $blance = $v['number'] - $product['number'];
                $tmp = array('number' => $v['number'], 'sale_price' => $v['sale_price']);
                $where = array('batch_sn' => $batchSN, 'order_batch_goods_id' => $orderBatchGoodsID);
                $this -> _db -> updateOrderBatchGoods($where, $tmp);
                //api 占有库存
                if ($order['status'] != 4 && in_array($order['lid'], $stockAPI -> getEntityAreaID())) {
                    $stockAPI -> holdSaleProductStock($product['product_id'], $v['number']);
                }
                if ($product['sale_price'] != $v['sale_price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 2,//1未确认订单修改商品、2订单恢复修改商品、3换货修改商品
                                 'batch_sn' => $order['batch_sn'],
                                 'order_batch_goods_id' => $product['order_batch_goods_id'],
                                 'product_sn' => $product['product_sn'],
                                 'number' => $v['number'],
                                 'sale_price' => $product['sale_price'],
                                 'edit_price' => $v['sale_price'],
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改已经存在的商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }
            }
        }

        if ($data['new']) {
            foreach ($data['new'] as $productID => $v) {
                $product = array_shift($this -> _product -> get(array('product_id' => $productID)));
                $tmp = array('order_id' => $order['order_id'],
                             'order_batch_id' => $order['order_batch_id'],
                             'order_sn' => $order['order_sn'],
                             'batch_sn' => $order['batch_sn'],
                             'add_time' => $order['add_time'],
                             'product_id' => $product['product_id'],
                             'product_sn' => $product['product_sn'],
                             'goods_id' => $product['goods_id'],
                             'goods_name' => $product['goods_name'],
                             'cat_id' => $product['cat_id'],
                             'cat_name' => $product['cat_name'],
                             'weight' => $product['p_weight'],
                             'length' => $product['p_length'],
                             'width' => $product['p_width'],
                             'height' => $product['p_height'],
                             'price' => $product['price'],
                             'sale_price' => $v['sale_price'],
                             'number' => $v['number']);
                $orderBatchGoodsID = $this -> _db -> addOrderBatchGoods($tmp);
                //api 占有库存
                if ($order['status'] != 4 && in_array($order['lid'], $stockAPI -> getEntityAreaID())) {
                    $stockAPI -> holdSaleProductStock($productID, $v['number']);
                }

                if ($product['price'] != $v['sale_price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 2,//1未确认订单修改商品、2订单恢复修改商品、3换货修改商品
                                 'batch_sn' => $order['batch_sn'],
                                 'order_batch_goods_id' => $orderBatchGoodsID,
                                 'product_sn' => $product['product_sn'],
                                 'number' => $v['number'],
                                 'sale_price' => $product['price'],
                                 'edit_price' => $v['sale_price'],
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改新增加的商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }
            }
        }
        $tmp = array('status' => 0, 'status_logistic' =>0, 'lock_name' => '');
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $tmp);
        //如果有优惠券，先判断优惠券的状态，如果是已使用，则将优惠券订单商品的数量设为0，否则修改优惠券的状态为1
        $coupon_goods = array_shift($this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'card_sn_is_not_null' => '1')));
        if ( $coupon_goods ) {
            if($coupon_goods['card_type']== 'coupon'){
                $coupon_info = $this -> _db -> getCouponInfo( $coupon_goods['card_sn'] );
                if ( ($coupon_info['is_repeat'] == 0) && ($coupon_info['status'] == 1) ) {
                    $this -> _db -> updateOrderBatchGoods(array('order_batch_goods_id' => $coupon_goods['order_batch_goods_id']), array('number' => 0));
                    //如果该优惠券有运费减免，则重新计算运费(如果活动中也有运费减免，结果会不准确，需要客服手工调整运费)
                    if ( $coupon_info['freight'] && (($order['price_logistic'] + $coupon_info['freight']) == 10) ) {
                        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('price_logistic' => $order['price_logistic'] + $coupon_info['freight']));
                    }
                }
                else{
                    $this -> _db -> setCouponStatus( $coupon_goods['card_sn'], 1 );
                    $this -> _db -> updateOrderBatchGoods(array('order_batch_goods_id' => $coupon_goods['order_batch_goods_id']), array('number' => 1));
                }
            }elseif($coupon_goods['card_type']== 'gift'){
                    $cardObj = new Admin_Models_API_GiftCard();
                    $gift = $cardObj -> getGiftInfo(array('card_sn' => $coupon_goods['card_sn']));
                    if($gift['card_real_price']>abs($coupon_goods['sale_price']) &&  $gift['end_date'] > date('Y-m-d') ){
                        $temp= $cardObj -> useGiftCard(array('card_sn' => $coupon_goods['card_sn'],'card_pwd' => $gift['card_pwd'], 'card_type' => $gift['card_type'], 'user_id' => $gift['user_id'],  'user_name' => $gift['user_name'], 'card_price' => abs($coupon_goods['sale_price']), 'add_time' => time()));
                        if($temp){
                             $data = array('number' => 1);
                        }else{
                            $data = array('number' => 0);
                        }
                    }else{
                        $data = array('number' => 0);
                    }
                    $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                    $data = array('number' => 1);
                    $this -> _db -> updateOrderBatchGoods($where, $data);
            }
        }

        //还原订单冻结财务信息
        $this -> undoFinance($batchSN);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '恢复订单',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //更新支付状态
        $this -> orderDetail($batchSN);
    }
    /**
     * 下单
     *
     * @param   string      $type
     * @param   array      $add
     * @return  void
     */
    public function add($type, $add, $addg, $provinceID, $cityID, $areaID, &$error=null ,$giftbywho=null ,$addr_consignee=null,$addr_address=null,$addr_tel=null,$addr_mobile=null,$order_payment=null,$priceLogistic=null,$shopID=null,$externalOrderSN=null,$orderTime=null,$note=null,$logistics_type=null,$part_pay='0',$try_order_id=0,$user_id=0,$user_name='',$note_print=null,$note_logistic=null,$distribution_type=null,$distribution_shop_id=null,$lid=1,$addr_zip=null,$addr_eng_address=null,$credentials_type=0,$credentials_no='')
    {
        
        if (!$addr_consignee) {
            $error = '请填写收货人';
            return false;
        }
        if (!$addr_tel && !$addr_mobile) {
            $error = '请填写联系电话或手机';
            return false;
        }
        //if ($logistics_type != 'self' && $logistics_type != 'externalself') {
            if (!$provinceID) {
                $error = '请选择省份';
                return false;
            } else if (!$cityID) {
                $error = '请选择城市';
                return false;
            } else if (!$areaID) {
                $error = '请选择地区';
                return false;
            }
            else if (!$addr_address) {
                $error = '请填写地址';
                return false;
            }
        //}
        if (!$order_payment) {
            $error = '请选择付款方式';
            return false;
        }
        if (!$type) {
            $error = '请选择下单类型';
            return false;
        }
        if (!$add && !$addg) {
            $error = '请添加商品';
            return false;
        }
        if($$type == 'gift' && $giftbywho == null){
            $error = '请填写赠送人姓名';
            return false;
        }
        if(!$addr_zip){
            $error = '请填写邮编~';
            return false;
        }
        if (in_array($type, array('b2c'))) {
            if ($user_name) {
                $memberAPI = new Admin_Models_API_Member();
                if ($member = $memberAPI -> getMemberByUserName($user_name)) {
                    $userName = $user_name;
                    $userID = $member['user_id'];
                }
                else {
                    $error = '前台账号不存在';
                    return false;
                }
            }
        }
        if ($type == 'gift') {
            $priceLogistic = 0;
            $userID = 1;
            $userName = $type;
            $type = 5;
            $title = '赠送下单';
        }
        else if ($type == 'other') {
            $userID = 2;
            $userName = $type;
            $type = 15;
            $title = '其它下单';
        }
        else if ($type == 'internal') {
            $userID = 8;
            $userName = $type;
            $type = 7;
            $title = '内购下单';
        }
        else if ($type == 'b2c') {
            if (!$userID) {
                $userID = 9;
                $userName = 'haitao';
            }
            $type = 0;
            $title = '官网下单';
            $shopID = 1;
        }
         else if ($type == 'external_renew') {
            $type = 14;
            $userID = 3;
            $userName = 'channel';
            $title = '渠道补单';
        }
        else {
            return false;//todo 异常处理
        }
        
        
        $pay_type = '';
        $pay_name = '';
        if ($order_payment) {
            $payment = explode('|', $order_payment);
            $pay_type = $payment['0'];
            $pay_name = $payment['1'];
        }
        
        $stockAPI = new Admin_Models_API_Stock();
        $stockAPI -> setLogicArea($lid);
        //api 检测是否有库存 开始
        //if ($userID != 10 && $type != 13) {
        if ($type != 13) {
            if ($add) {
                foreach ($add as $productID => $v) {
                    if (!$stockAPI -> checkPreSaleProductStock($productID, $v['number'], true)) {
                        $error .= "产品ID{$productID}库存不足<br>";
                        return false;
                    }
                    $priceGoods += $v['sale_price']* $v['number'];
                    $tax += $v['tax']*$v['number'];
                    $numberGoods += $v['number'];
                }
            }
            if (($numberGoods + $groupnum) < 1){
                $error .= "下单产品数量必须大于1<br>";
                return false;
            }
        }
        else {
            if ($add) {
                foreach ($add as $productID => $v) {
                    $priceGoods += $v['sale_price'] * $v['number'];
                    $tax += $v['tax']*$v['number'];
                    $add[$productID]['sale_price'] = round($add[$productID]['sale_price'], 2);
                }
                $priceGoods = round($priceGoods, 2);
                $tax = round($tax,2);
            }
            if ($addg) {
                foreach ($addg as $goodsID => $v) {
                    $priceGoods += $v['sale_price'] * $v['number'];
                    $tax += $v['tax']*$v['number'];
                    $addg[$goodsID]['sale_price'] = round($addg[$goodsID]['sale_price'], 2);
                }
                $priceGoods = round($priceGoods, 2);
                $tax = round($tax,2);
            }
        }
        //api 检测是否有库存 结束
        
        $pricePay = $priceOrder = $priceGoods + $priceLogistic + $tax;
        
        if ($orderTime) {
            $time = strtotime($orderTime);
        }
        else    $time = time();
        
        $tmpStr = $lid == 1 ? 'H' : 'J';
        $orderSN = Custom_Model_CreateSn::createSn($tmpStr);
        
        if ($this -> _db -> getOrderMain("order_sn = '{$orderSN}'", '1')) {
            return false;
        }

        $vitualCardData = '';
        
        //礼品卡/虚拟商品
        if ($add) {
            $giftCardArray = '';
            $hasVitual = false;
            $onlyGiftCard = true;
            foreach ($add as $productID => $v) {
                $productData[$productID] = array_shift($this -> _product -> get(array('product_id' => $productID)));
                if ($productData[$productID]['is_vitual']) {
                    $hasVitual = true;
                }
                
                if ($productData[$productID]['is_gift_card']) {
                    $giftCardInfo = $this -> _product -> getGiftcardInfoByProductid($productID);
                    if ($giftCardInfo) {
                        $giftCardArray[] = array('info' => $giftCardInfo,
                                                 'number' => $v['number']
                                                );
                    }
                    else {
                        $error .= "商品编码{$giftCardInfo['product_sn']}的礼品卡没有设定面值<br>";
                        return false;
                    }
                }
                else {
                    $onlyGiftCard = false;
                }
                $weight+=($productData[$productID]['p_weight']*$v['number']);
            }
            
            if ($hasVitual) {
                if (!$addr_mobile) {
                    $error .= "包含虚拟商品，手机号码必须填写<br>";
                    return false;
                }
                $sms_no = $addr_mobile;
            }
            
            if ($giftCardArray) {
                if ($pay_type != 'cod' && !$onlyGiftCard) {
                    $error .= "在线支付的礼品卡中不能包含其它商品<br>";
                    return false;
                }
                
                $giftCardAPI = new Admin_Models_API_GiftCard();
                //生成礼品卡，状态未激活
                foreach ($giftCardArray as $giftCard) {
                    $giftCardRow[$giftCard['info']['product_id']] = array('number' => $giftCard['number'],
                                                     'card_price' => $giftCard['info']['amount'],
                                                     'card_type' => 1,
                                                     'end_date' => date('Y-m-d', time() + 3600 * 24 * 365 * 3),
                                                     'order_batch_goods_id' => 0,
                                                     'status' => 2,
                                                    );
                }
            }
        }

        $orderID = $this -> _db -> addOrder(array('order_sn' => $orderSN,
                                                  'batch_sn' => $orderSN,
                                                  'add_time' => $time,
                                                  'user_id' => $userID,
                                                  'user_name' => $userName,
                                                  'giftbywho' => $giftbywho,
                                                  'part_pay' => $part_pay,
                                                  'try_order_id' => $try_id,
        					                      'shop_id' => $shopID ? $shopID : 0,
        				                          'external_order_sn' => $externalOrderSN ? $externalOrderSN : '',
        				                          'distribution_type' => $distributionType ? $distributionType : 0,
        				                          'source' => $source ? $source : 0,
        				                          'lid' => $lid,
        				                         )
        				                   );

        $cartApi = new Shop_Models_API_Cart();
        $priceLogistic = $cartApi -> getFareByWeight('sf', $weight);
        $row = array('order_id' => $orderID,
                     'order_sn' => $orderSN,
                     'batch_sn' => $orderSN,
                     'add_time' => $time,
                     'lock_name' => $this -> _auth['admin_name'],
                     'type' => $type,
                     'price_order' => $priceOrder,
                     'price_goods' => $priceGoods,
                     'price_logistic' => floatval($priceLogistic),
                     'price_pay' => $pricePay,
                     'addr_zip' => $addr_zip,
                     'addr_consignee' => $addr_consignee,
                     'addr_tel' => $addr_tel,
                     'addr_mobile' => $addr_mobile,
                     'pay_type' => $pay_type,
                     'pay_name' => $pay_name,
                     'note' => $note ? $note : null,
                     'note_print' => $note_print ? $note_print : null,
                     'note_logistic' => $note_logistic ? $note_logistic : null,
                     'sms_no' => $sms_no ? $sms_no : null,
        			 'tax' => $tax>50?$tax:0 ,
        			 'credentials_type' => $credentials_type,
        			 'credentials_no' => $credentials_no,
                    );
        if ($logistics_type == 'self' || $logistics_type == 'externalself') {
            if ($logistics_type == 'self') {
                $row['logistic_code'] = 'self';
                $row['logistic_name'] = '客户自提';
                $row['addr_address'] = $addr_address ? $addr_address : '客户自提';
            }
            else {
                $row['logistic_code'] = 'externalself';
                $row['logistic_name'] = '渠道代发货自提';
                $row['addr_address'] = $addr_address ? $addr_address : '渠道代发货自提';
            }
            if ($provinceID) {
                $row['addr_province'] = $this -> _db -> getAreaName($provinceID);
                $row['addr_province_id'] = $provinceID;
            }
            if ($cityID) {
                $row['addr_city'] = $this -> _db -> getAreaName($cityID);
                $row['addr_city_id'] = $cityID;
            }
            if ($areaID) {
                $row['addr_area'] = $areaID == -1 ? '其它区' : $this -> _db -> getAreaName($areaID);
                $row['addr_area_id'] = $areaID;
            }
        }
        else {
            $row['addr_province'] = $this -> _db -> getAreaName($provinceID);
            $row['addr_city'] = $this -> _db -> getAreaName($cityID);
            $row['addr_area'] = $areaID == -1 ? '其它区' : $this -> _db -> getAreaName($areaID);
            $row['addr_province_id'] = $provinceID;
            $row['addr_city_id'] = $cityID;
            $row['addr_area_id'] = $areaID;
            $row['addr_address'] = $addr_address;
            $row['addr_eng_address'] = $addr_eng_address;
        }
        //分销单
        if (substr($userName, -13) == '_distribution') {
            $row['status'] = 4;
            $row['status_logistic'] = 0;
            $row['status_pay'] = 0;
        }

        //新增订单批次
        $orderBatchID = $this -> _db -> addOrderBatch($row);
        
        //新增订单商品
        if ($add) {
            foreach($add as $productID => $v){
                $data = $productData[$productID];
                $tmp[$data['product_id']] = $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
                                                                                     'order_batch_id' => $orderBatchID,
                                                                                     'order_sn' => $orderSN,
                                                                                     'batch_sn' => $orderSN,
                                                                                     'type' => $data['is_vitual'] ? 7 : 0,
                                                                                     'add_time' => $time,
                                                                                     'product_id' => $data['product_id'],
                                                                                     'product_sn' => $data['product_sn'],
                                                                                     //'goods_id' => $data['goods_id'],
                                                                                     'goods_name' => $data['product_name'],
                                                                                     'goods_style' => $data['goods_style'],
                                                                                     'cat_id' => $data['cat_id'] ? $data['cat_id'] : 0,
                                                                                     'cat_name' => $data['cat_name'],
                                                                                     'weight' => $data['p_weight'],
                                                                                     'length' => $data['p_length'],
                                                                                     'width' => $data['p_width'],
                                                                                     'height' => $data['p_height'],
                                                                                     'number' => $v['number'],
                                                                                     //'price' => $data['price'],
                                                                                     'cost'    => $data['cost'],
                                                                                     'sale_price' => $v['sale_price'],
                																	 'tax' => $v['tax'],)
                                                                                     );
                if ((!in_array($userID, array(3,10)) || ($userID == 3 && $type == 14)) && in_array($lid, $stockAPI -> getEntityAreaID())) {   //添加销售产品占有库存
                    $stockAPI -> holdSaleProductStock($productID, $v['number']);
                }
            }
        }


        //添加日志
        $log = array('order_sn' => $orderSN,
                     'batch_sn' => $orderSN,
                     'add_time' => time(),
                     'title' => $title,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        return $orderSN;
    }

    /**
     * 退换货开单
     *
     * @param   string      $batchSN
     * @param   array      $post
     * @param   string      $error
     * @return  void
     */
    public function runReturn($batchSN, $post, &$error=null)
    {
        $time = time();
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        
        $return = $post['return'];
        if (!$return) {//不产生退货 终止
            return false;
        }
        
        $note = $post['note_staff'] . '。' . $post['note'];//修改换货商品价格理由
        if ($post['note_staff']) {//客服备注
            $noteStaff = $order['note_staff'] . $this -> _auth['admin_name'] . '^' . $time . '^' . $post['note_staff'] . "\n";
        } else {
            $noteStaff = $order['note_staff'];
        }
        
        //代收款/直供单/拒收
        if ($order['status_logistic'] == 5 && ($order['pay_type'] == 'cod' || $order['type'] == 16 || $order['user_name'] == 'credit_channel')) {
           $initAmount = $order['price_order'] - $order['price_payed'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'];
        }
        //代收款拒收 退货开单 结束
        
        $change = $post['change'];
        $priceAdjustReturn = $post['price_adjust_return'];
        $noteAdjustReturn = $post['note_adjust_return'];
        
        //初始应退款
        $returnAmount = $order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'] + $order['price_from_return'] - $order['price_pay'];
        
        if ($return) {
            $allReturn = true;
            $returnGoodsAmount = 0;
            foreach ($return as $orderBatchOrderID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchOrderID)));
                
                //总退货金额
                $returnAmount += $v['number'] * ($product['eq_price']+$product['tax']);
                $returnGoodsAmount += $v['number'] * ($product['eq_price']+$product['tax']);
                
                //总退货数量
                $updateReturnNumber[$orderBatchOrderID] += $v['number'];
                $returnNumber += $v['number'];
                
                //原来数量2012.5.9，用于更新组合商品的子商品数量，1399行
                $formerNumer[$orderBatchOrderID] = $v['former_number'];
                
                if ($v['former_number'] > $v['number']) {
                    $allReturn = false;
                }
                
                //出库记录
                if ($v['number']) {
					$addIn[$product['product_id']]['number'] += $v['number'];
					$addIn[$product['product_id']]['former_number'] += $v['former_number']; //仅用于直供
					$addIn[$product['product_id']]['price'] = $product['price'];
					$addIn[$product['product_id']]['order_batch_goods_id'] = $product['order_batch_goods_id'];
					$addIn[$product['product_id']]['type'] = $product['type'];
                }
                //退货理由
                $reason = $v['reason'];
                if ($reason) {
                    $other = trim($reason['other']);
                    unset($reason['other']);
                    $addReason[] = array('public' => array('order_sn' => $product['order_sn'],
                                                           'batch_sn' => $product['batch_sn'],
                                                           'order_batch_goods_id' => $product['order_batch_goods_id'],
                                                           'product_id' => $product['product_id'],
                                                           'add_time' => $time,
                                                           'reason' => $other),
                                                           'private' => $reason);
                }
            }

            //全退时是否退运费
            if ($order['price_logistic'] > 0 && $allReturn && ($order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed']) > 0) {
                $returnPriceLogistic = $post['return_price_logistic'] ? $post['return_price_logistic'] : 2;
            }
            else {
                $returnPriceLogistic = 0;
            }
            $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('return_price_logistic' => $returnPriceLogistic));
        }
        
        //退货数量为0同时没有换货，终止
        if (!$returnNumber && !$change) {
            return false;
        }
        
        if (!$returnNumber && $post['is_lost']) {
            $error = "没有退货商品，不能作丢件处理<br>";
            return false;
        }
        
        //在没有完成现有的退货时，不允许再开退货
        if ($returnNumber) {
            $instockAPI  = new Admin_Models_API_InStock();
            if ($instockAPI -> getMain("item_no = '{$batchSN}' and bill_type in (1,13) and bill_status in (3,6)")) {
                $error = "请先处理完现有的退货单<br>";
                return false;
            }
        }
        
        //直供单特殊处理
        if ($order['type'] == 16 && $order['status_logistic'] == 4  && $returnGoodsAmount > 0) {
            if ($post['not_to_settlement']) {   //修改结算单并生成虚拟退款单
                if ($order['price_order'] - $order['price_payed'] < $returnGoodsAmount) {
                    $error = "退货款大于需支付金额，不能勾选“退货款不作结算”<br>";
                    return false;
                }
                
                $financeAPI = new Admin_Models_API_Finance();
                $settlementDetails = $financeAPI -> getDistributionSettlementDetail($batchSN);
                if ($settlementDetails) {
                    foreach ($settlementDetails as $settlementDetail) {
                        if ($settlementDetail['type'] = 1) {
                            foreach ($settlementDetail['detail'] as $productID => $number) {
                                if ($settlementDetail['amounbt'] > 0) {
                                    $settlementData[$productID] += $number;
                                }
                                else {
                                    $settlementData[$productID] -= $number;
                                }
                            }
                        }
                    }
                    foreach ($addIn as $productID => $data) {
                        if ($settlementData[$productID] && $data['former_number'] - $settlementData[$productID] < $data['number']) {
                            $error = "退货数量大于已结算数量，不能勾选“退货款不作结算”<br>";
                            return false;
                        }
                    }
                }
                
                $settlement = array_shift($financeAPI -> getDistributionSettlement(array('batch_sn' => $batchSN)));
                if ($settlement) {
                    if ($settlement['amount'] - $returnGoodsAmount < 0 || $settlement['amount'] - $returnGoodsAmount < $settlement['settle_amount']) {
                        $error = "扣减结款金额出错<br>";
                        return false;
                    }
                    $financeAPI -> updateDistributionSettlement($batchSN, array('amount' => $settlement['amount'] - $returnGoodsAmount));
                    
                    $data = array('shop_id' => $order['shop_id'] ? $order['shop_id'] : 0,
        						  'type' => 0,
        						  'way' => 5,//直供单虚拟退款
        					      'item' => 1,
        						  'item_no' => $batchSN,
        						  'pay' => -$returnGoodsAmount,
        						  'logistic' => 0,
        						  'point' => 0,
        						  'account' => 0,
        						  'gift' => 0,
        						  'status' => 2,
        						  'bank_type' => 4,
        						  'bank_data' =>'',
        						  'order_data' =>'',
        						  'note' => '直供单系统退款',
        						  'callback' => '',
        						  'add_time' => time(),
        						  'check_time' => 0,
        						 );
        		    $financeID = $financeAPI -> addFrinance($data);
        		    
        		    $returnDetail = array();
        		    foreach ($addIn as $productID => $addInProduct) {
        		        $returnDetail[$productID] = $addInProduct['number'];
        		    }
        		    $data = array('distribution_id' => $settlement['distribution_id'],
        		                  'type' => 2,
        		                  'amount' => $returnGoodsAmount * -1,
        		                  'detail' => serialize($returnDetail),
        		                  'admin_name' => $this -> _auth['admin_name'],
        		                  'add_time' => time(),
        		                 );
        		    $financeAPI -> addDistributionSettlementDetail($data);
                }
                else {
                    $error = "财务结款单找不到<br>";
                    return false;
                }
            }
            else {  //要退款并且可退款，需要跳退款开单
                if ($order['price_payed'] >= $returnGoodsAmount) {
                    $this -> jumpFinanceReturn = true;
                }
            }
        }

        if ($change) {//新增新品
            foreach ($change as $productID => $item) {
                if ($item['number'] > 0) {
                    $product = array_shift($this -> _product -> get(array('product_id' => $productID)));
                    $item['sale_price'] = floatval($item['sale_price']);
                    //总新品应付金额
                    $addChangeMoney += ($item['sale_price']+$item['tax']) * $item['number'];

                    $product['change_number'] = $item['number'];
                    $product['sale_price'] = $item['sale_price'];
                    $product['tax'] = $item['tax']*$item['number'];
                    $addProduct[] = $product;

                    $changeNumber += $item['number'];
                }
            }
        }
        $stockAPI = new Admin_Models_API_Stock();
        if ($order['status'] == 4) {
            $logicArea = Custom_Model_Stock_Base::getDistributionArea($order['user_name']);
        }
        else {
            $logicArea = $order['lid'];
        }
        $stockAPI -> setLogicArea($logicArea);
        
        //api 检测是否有库存 开始
        if ($addProduct) {
            foreach ($addProduct as $k => $v) {
                if (!$stockAPI -> checkPreSaleProductStock($v['product_id'], $v['change_number'])) {
                    $error = "产品ID{$v['product_id']}库存不足<br>";
                    return false;
                }
            }
        }
        //api 检测是否有库存 结束

        //更新 退货数量 / 换货数量 开始
        if ($updateReturnNumber) {
            foreach ($updateReturnNumber as $orderBatchGoodsID => $number) {
                $where = array('order_batch_goods_id' => $orderBatchGoodsID);
                $temp = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                $data = array('return_number' => $temp['return_number'] + $number,
                              'returning_number' => $number);
                $this -> _db -> updateOrderBatchGoods($where, $data);
            }
        }

        //更新 退货数量 / 换货数量 结束

        //新增 退货 理由 开始
        if ($addReason) {
            foreach ($addReason as $v) {
                if (!$v['reason'])  continue;
                $id = $this -> _db -> addOrderBatchGoodsReturn($v['public']);
                if ($v['private']) {
                    foreach ($v['private'] as $reasonID => $tmp) {
                        $data = array('id' => $id, 'reason_id' => $reasonID);
                        $this -> _db -> addOrderBatchGoodsReturnReason($data);
                    }
                }
            }
        }
        //新增 退货 理由 结束
        
        //api 申请入库 开始
        if ($addIn) {
            if ($post['is_lost']) {
                //生成退款单后再做
            }
            else {
                $remark = "退货入库({$order['addr_consignee']} {$order['external_order_sn']})";
                $inType = 1;
                $instockBillNo = $this -> in($batchSN, $addIn, $remark, $inType);
            }
        }
        //api 申请入库 结束
        
        //拒收时增加退货日志
        if (isset($initAmount)) {
            $returnID = $this -> _db -> addOrderReturn(array('order_sn' => $order['order_sn'],
                                                             'batch_sn' => $order['batch_sn'],
                                                             'amount' => $initAmount ? $initAmount : 0,
                                                             'add_time' => time(),
                                                             'finish_time' => 0,
                                                            )
                                                      );
            
            //添加财务产品拒收
            if ($initAmount > 0) {
                $returnInfo = array();
                foreach ($addIn as $productID => $temp) {
                    $returnInfo[$productID]['number'] = $temp['number'];
                }
                $finance = array('item_no' => $batchSN,
                                 'pay' => $initAmount,
                                 'finance_id' => $returnID,
                                 'type' => 2,
                                 );
                $this -> createFinanceReturnProduct($finance, $returnInfo);
            }
        }
        
        //新增 商品
        if ($addProduct) {
            $tmp = array_shift($this -> _db -> getOrderBatch(array('order_sn' => $order['order_sn']), 'add_time desc'));
            $tmp = explode('_', $tmp['batch_sn']);
            $newBatchSN = $order['order_sn'].'_'.(intval($tmp['1']) + 1);
            
            $newTax = 0;
            foreach($addProduct as $v){
            	$newTax += $v['tax'];
            }
            
            $returnAmount -= $priceAdjustReturn;
            $returnMoney = $returnPoint = $returnAccount = $returnGiftCard = 0;
            if ($returnAmount > 0) {
                $orderDetail = $this -> orderDetail($batchSN);
                $newAmount = 0 + $post['price_logistic'];
                foreach ($addProduct as $k => $v) {
                    $newAmount += ($v['sale_price']+$v['tax']) * $v['change_number'];
                }
                
                //计算老单已支付金额转到新单的金额
                if ($newAmount >= $returnAmount ) {
                    $returnMoney = $orderDetail['finance']['price_return_money'];
                    $returnPoint = $orderDetail['finance']['price_return_point'];
                    $returnAccount = $orderDetail['finance']['price_return_account'];
                    $returnGiftCard = $orderDetail['finance']['price_return_gift'];
                    $returnAmount = 0;
                }
                else {
                    //优先顺序 账户余额 -> 积分 -> 礼品卡 -> 现金
                    if ($orderDetail['finance']['price_return_account'] > 0) {
                        if ($newAmount >= $orderDetail['finance']['price_return_account']) {
                            $returnAccount = $orderDetail['finance']['price_return_account'];
                            $newAmount -= $orderDetail['finance']['price_return_account'];
                        }
                        else {
                            $returnAccount = $newAmount;
                            $newAmount = 0;
                        }
                    }
                    if ($newAmount > 0 && $orderDetail['finance']['price_return_point'] > 0) {
                        if ($newAmount >= $orderDetail['finance']['price_return_point']) {
                            $returnPoint = $orderDetail['finance']['price_return_point'];
                            $newAmount -= $orderDetail['finance']['price_return_point'];
                        }
                        else {
                            $returnPoint = $newAmount;
                            $newAmount = 0;
                        }
                    }
                    if ($newAmount > 0 && $orderDetail['finance']['price_return_gift'] > 0) {
                        if ($newAmount >= $orderDetail['finance']['price_return_gift']) {
                            $returnGiftCard = $orderDetail['finance']['price_return_gift'];
                            $newAmount -= $orderDetail['finance']['price_return_gift'];
                        }
                        else {
                            $returnGiftCard = $newAmount;
                            $newAmount = 0;
                        }
                    }
                    if ($newAmount > 0 && $orderDetail['finance']['price_return_money'] > 0) {
                        if ($newAmount >= $orderDetail['finance']['price_return_money']) {
                            $returnMoney = $orderDetail['finance']['price_return_money'];
                            $newAmount -= $orderDetail['finance']['price_return_money'];
                        }
                        else {
                            $returnMoney = $newAmount;
                            $newAmount = 0;
                        }
                    }
                }
            }
            //新增 批次
            $data = array('order_id' => $order['order_id'],
                          'order_sn' => $order['order_sn'],
                          'batch_sn' => $newBatchSN,
                          'parent_batch_sn' => $batchSN,
                          'type' => $order['type'] == 13 ? 14 : $order['type'],
                          'add_time' => $time,
                          'is_freeze' => $returnNumber ? ($post['is_lost'] ? 0 : 1) : 0,//新开的换货单 是被冻结住的，等退货入库后，才解冻，解冻后方可进行新单的订单确认(取消)
                          'is_fav' => is_null($order['is_fav']) ? null : -1,//-1指老单未满意不退货，在新单中要给流转金额的积分
                          'lock_name' => $this -> _auth['admin_name'],
                          'price_logistic' => $post['price_logistic'],
						  'price_payed' => $returnMoney ? $returnMoney : 0,//退款转已付款
						  'clear_pay' => $order['clear_pay'],
						  'pay_type' => $order['pay_type'],
						  'pay_name' => $order['pay_name'],
						  'pay_time' => $order['pay_time'],
                          'addr_consignee' => $order['addr_consignee'],
                          'addr_province_id' => $order['addr_province_id'],
                          'addr_city_id' => $order['addr_city_id'],
                          'addr_area_id' => $order['addr_area_id'],
                          'addr_province' => $order['addr_province'],
                          'addr_city' => $order['addr_city'],
                          'addr_area' => $order['addr_area'],
                          'addr_address' => $order['addr_address'],
                          'addr_zip' => $order['addr_zip'],
                          'addr_tel' => $order['addr_tel'],
                          'addr_mobile' => $order['addr_mobile'],
                          'addr_email' => $order['addr_email'],
                          'addr_fax' => $order['addr_fax'],
            			  'tax' => $newTax);
            $orderBatchID = $this -> _db -> addOrderBatch($data);
            
            //设置 最新批次号
            $this -> _db -> updateOrder(array('order_sn' => $orderSN), array('batch_sn' => $newBatchSN));
            
            //新增 批次商品
            foreach ($addProduct as $k => $v) {
                $data = array('order_id' => $order['order_id'],
                              'order_batch_id' => $orderBatchID,
                              'order_sn' => $order['order_sn'],
                              'batch_sn' => $newBatchSN,
                              'add_time' => $time,
                              'product_id' => $v['product_id'],
					          'goods_name' => $v['product_name'],
					          'goods_style' => $v['goods_style'],
                              'product_sn' => $v['product_sn'],
                              'cat_id' => $v['cat_id'],
                              'cat_name' => $v['cat_name'],
                              'weight' => $v['p_weight'],
                              'length' => $v['p_length'],
                              'width' => $v['p_width'],
                              'height' => $v['p_height'],
                              'number' => $v['change_number'],
                              'price' => $v['price'] ? $v['price'] : 0,
                              'cost'  => $v['cost'],
                              'sale_price' => $v['sale_price'],
                			  'tax'=>$v['tax'],
                			  'fare'=>0,
                		);
                $orderBatchGoodsID = $this -> _db -> addOrderBatchGoods($data);
                //api 占有库存
                $stockAPI -> holdSaleProductStock($v['product_id'], $v['change_number']);
                if ($v['sale_price'] != $v['price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 3,//1未确认订单修改商品、2订单恢复修改商品、3修改换货商品
                                 'batch_sn' => $newBatchSN,
                                 'order_batch_goods_id' => $orderBatchGoodsID,
                                 'product_sn' => $v['product_sn'],
                                 'number' => $v['change_number'],
                                 'sale_price' => $v['price'] ? $v['price'] : 0,
                                 'edit_price' => $v['sale_price'],
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改换货商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }
            }
            
            //账户余额 老单退回 新单转入
            if ($returnAccount) {
                if ($this -> unAccountPrice($batchSN, $returnAccount)) {
                    $memberApi = new Shop_Models_API_Member();
                    $member = array_shift($memberApi -> getMemberByUserName($order['user_name']));
                    $tmp = array('member_id' => $member['member_id'],
                                 'user_name' => $member['user_name'],
                                 'order_id' => $orderBatchID,
                                 'accountValue' => $returnAccount,
                                 'accountTotalValue' => $member['money'],
                                 'note' => '换货单帐户余额抵扣转入',
                                 'batch_sn' => $newBatchSN);
                    $memberApi -> editAccount($member['member_id'], 'money', $tmp);
                    
                    $this -> _db -> updateOrderPayed($newBatchSN, $returnAccount, 'account');
                }
            }
            
            //积分 老单退回 新单转入
            if ($returnPoint) {
                $point = $this -> unPointPrice($batchSN, $returnPoint);
                if ($point) {
                    $memberApi = new Shop_Models_API_Member();
                    $member = array_shift($memberApi -> getMemberByUserName($order['user_name']));
                    $tmp = array('member_id' => $member['member_id'],
                                 'user_name' => $member['user_name'],
                                 'order_id' => $orderBatchID,
                                 'accountValue' => $point,
                                 'accountTotalValue' => $member['point'],
                                 'note' => '换货单积分抵扣转入',
                                 'batch_sn' => $newBatchSN);
                    $memberApi -> editAccount($member['member_id'], 'point', $tmp);
                    
                    $this -> _db -> updateOrderPayed($newBatchSN, $returnPoint, 'point');
                }
            }
            
            //礼品卡 老单退回 新单转入
            if ($returnGiftCard) {
                $cardList = $this -> unUseCardGift($batchSN, $returnGiftCard);
                if ($cardList) {
                    $cardAPI = new Shop_Models_DB_Card();
                    foreach ($cardList as $card) {
                        $card = array('card_type' => $card['card_type'],
                                      'card_price' => $card['card_price'],
                                      'card_sn' => $card['card_sn'],
                                      'card_pwd' => $card['card_pwd'],
                                      'add_time' => time(),
                                      'admin_id' => $this -> _auth['admin_id'],
                                      'admin_name' => $this -> _auth['admin_name'],
                                      'batch_sn' => $newBatchSN,
                                      'use_type' => 1,
                                     );
                        if ($cardAPI -> useGift($card)) {
                            $this -> _db -> updateOrderPayed($newBatchSN, $card['card_price'], 'gift_card');
                        }
                    }
                }
            }
            
            //现金 老单扣减
            if ($returnMoney) {
				$this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
												 array('price_payed' => $order['price_payed'] - $returnMoney));
            }
            
            //自动添加退款记录
            if ($returnMoney || $returnAccount || $returnPoint || $returnGiftCard) {
				$data = array('shop_id' => $order['shop_id'] ? $order['shop_id'] : 0,
							  'type' => 0,//系统
							  'way' => 3,//退货系统退到新换货单上
							  'item' => 1,
							  'item_no' => $batchSN,
							  'pay' => $returnMoney ? -$returnMoney : 0,
							  'logistic' => 0,
							  'point' => $returnPoint ? -$returnPoint : 0,
							  'account' => $returnAccount ? -$returnAccount : 0,
							  'gift' => $returnGiftCard ? -$returnGiftCard : 0,
							  'status' => 2,
							  'bank_type' => 4,
							  'bank_data' =>'',
							  'order_data' =>'',
							  'note' => '退货系统退款',
							  'callback' => '',
							  'add_time' => time());
				$instockAPI  = new Admin_Models_API_InStock();
                if ($instockAPI -> getMain("item_no = '{$batchSN}' and bill_type in (1,13) and bill_status in (3,6)")) {
                    $data['check_time'] = 0;
                }
                else {
                    $data['check_time'] = time();
                    //添加应收款记录
                    $financeAPI = new Admin_Models_API_Finance();
                    $receiveData = array('batch_sn' => $newBatchSN,
                                         'type' => 6,
                                         'pay_type' => 'exchange',
                                         'amount' => abs($returnMoney + $returnPoint + $returnAccount + $returnGiftCard),
                                         'settle_amount' => abs($returnMoney + $returnPoint + $returnAccount + $returnGiftCard),
                                         'settle_time' => time(),
                                        );
                    $financeAPI -> addFinanceReceivable($receiveData);
                }
                
				$this -> _finance -> addFrinance($data);
            }
            
            if ($priceAdjustReturn) {//退换货开单调整金额
                $adjust = array('order_sn' => $order['order_sn'],
                                'batch_sn' => $newBatchSN,
                                'type' => 20,
                                'money' => $priceAdjustReturn,
                                'note' => $noteAdjustReturn,
                                'add_time' => $time);
                $this -> _db -> addOrderBatchAdjust($adjust);
            }
            
            //更新支付状态
            $this -> orderDetail($newBatchSN);
        }
        
        //丢件退货虚拟入库
        if ($addIn) {
            if ($post['is_lost']) {
                $remark = "虚拟退货入库({$order['addr_consignee']} {$order['external_order_sn']})";
                $inType = 13;
                $instockBillNo = $this -> in($batchSN, $addIn, $remark, $inType);
            }
        }
        
        //设置退换货状态
        if (!$changeNumber) {//退货
            if ($priceAdjustReturn) {//退货开单调整金额
                $adjust = array('order_sn' => $order['order_sn'],
                                'batch_sn' => $batchSN,
                                'type' => 10,
                                'money' => $priceAdjustReturn,
                                'note' => $noteAdjustReturn,
                                'add_time' => $time);
                $this -> _db -> addOrderBatchAdjust($adjust);
            }
        }
        
        $set = array('lock_name' => '',
                     'note_staff' => $noteStaff);
        if ($returnNumber) {
            $set['returning_time'] = $time;
            $set['status_return'] = 1;
            $set['tax']=$order['tax']-$returnTax;
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
        
        
        $orderDetail = $this -> orderDetail($batchSN);
        
        $title = '退货开单';
        if ($orderDetail['finance']['price_return_money'] > 0 || $orderDetail['finance']['price_return_point'] > 0 || $orderDetail['finance']['price_return_account'] > 0 || $orderDetail['finance']['price_return_gift'] > 0) {
            if ($orderDetail['finance']['price_return_money'] > 0) {
                $title .= ' [退款：￥'.$orderDetail['finance']['price_return_money'].']';
            }
            if ($orderDetail['finance']['price_return_point'] > 0) {
                $title .= ' [退积分：￥'.$orderDetail['finance']['price_return_point'].']';
            }
            if ($orderDetail['finance']['price_return_account'] > 0) {
                $title .= ' [退账户余额：￥'.$orderDetail['finance']['price_return_account'].']';
            }
            if ($orderDetail['finance']['price_return_gift'] > 0) {
                $title .= ' [礼品卡：￥'.$orderDetail['finance']['price_return_gift'].']';
            }
            
            $newBatchSN = false;
        }
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => $title,
                     'data' => Zend_Json::encode($post),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        
        //如果只有虚拟商品，作收货处理
        if ($instockBillNo && $onlyVitual) {
            $inStockAPI = new Admin_Models_API_InStock();
            $inStockAPI -> receiveByBillNo($instockBillNo);
        }
        
        return $newBatchSN;
    }

	/**
     * 统计当前订单商品 总重量 体积 数量 计算物流配送方式需要用到
     *
     * @param   string      $batchSN
     * @return  array
     */
    public function _orderBatchProductStatus($batchSN)
    {
        $where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0);
	    $product = $this -> _db -> getOrderBatchGoods($where);
        if (is_array($product) && count($product)) {
            foreach($product as $k => $v) {
                $productNumber += $v['number'];
                $productWeight += $v['weight'] * $v['number'];
                $productVolume += $v['length'] * $v['width'] * $v['height'] * $v['number'] * 0.001;
            }
        }
        $data = array('product_number' => $productNumber, 'product_weight' => $productWeight,'product_volume' => $productVolume);
        return $data;
    }

    /**
     * 取得指定条件的订单
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function orderDetail($batchSN)
    {
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));//订单资料
        $this -> _avgDetail($batchSN,$order);//处理订单商品销售均值
        $this -> groupDetail($batchSN);//套装销售均价处理
        $productDetail = $this -> _productDetail($batchSN, $order['status'] == 4 ? Custom_Model_Stock_Base::getDistributionArea($order['user_name']) : 1);//商品资料
		$adjust = $this -> _adjustDetail($batchSN);//调整金额资料
        $batchLog = $this -> _batchLogDetail($batchSN);//订单操作记录
        $payLog = $this -> _batchPayLogDetail($batchSN);//订单支付历史记录
        $financeReturning = $this -> _financeReturningDetail($batchSN);//订单退款记录

        //从新计算产品均值 开始
        if ($productDetail['product']) {
            $priceForEqual = $productDetail['other']['price_goods_all_without_gift_card'] +
                             (-abs($productDetail['price_minus'])) +
                             (-abs($productDetail['price_old'])) +
                             (-abs($productDetail['price_coupon'])) +
                             (-abs($productDetail['price_virtual'])) +
                             $adjust['price_adjust'];
            $used = array();
            foreach ($productDetail['product'] as $k => $v) {
				if ($v['is_gift_card']) {
					$eqPrice = $v['sale_price'];
				}
				else {
					if ($productDetail['other']['price_goods_all_without_gift_card'] != 0) {
						$eqPrice = round(($v['sale_price']) / $productDetail['other']['price_goods_all_without_gift_card'] * $priceForEqual, 2);
					} else {
						if ($productDetail['goods_number'] > 0) {
							$eqPrice = round($priceForEqual / $productDetail['goods_number'], 2);
						}
						else {
							$eqPrice = 0;
						}
					}
					$equalAmount += $eqPrice * $v['number'];
				}
				$this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,
															'order_batch_goods_id' => $v['order_batch_goods_id']),
													  array('eq_price' => $eqPrice, 'eq_price_blance' => 0));
				$tmpID = $v['order_batch_goods_id'];
            	
            }

            $eqPriceBlance = round($priceForEqual - $equalAmount, 2);
            if ($eqPriceBlance) {
                $this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,
                                                            'order_batch_goods_id' => $tmpID),
                                                      array('eq_price_blance' => $eqPriceBlance));
            }
        }
        $productDetail = $this -> _productDetail($batchSN, $order['status'] == 4 ? Custom_Model_Stock_Base::getDistributionArea($order['user_name']) : $order['lid']);

		$product_db       = new Admin_Models_DB_Product();
		if (count($productDetail['product_all']) > 0) {
			foreach ($productDetail['product_all'] as $key => $product) {
				$limit_price = 0;
				if (isset($product['child']) && substr($product['product_sn'], 0, 1) == 'G') {
					$group_params = array(
						'shop_id'    => $order['shop_id'],
						'type'       => '2',
						'start_ts'   => date('Y-m-d H:i:s', $product['add_time']),
						'product_sn' => $product['product_sn'],
					);
				} else if (substr($product['product_sn'], 0, 1) == 'N') {
					$product_params = array(
						'shop_id'    => $order['shop_id'],
						'type'       => '1',
						'start_ts'   => date('Y-m-d H:i:s', $product['add_time']),
						'product_sn' => $product['product_sn'],
					);
					$product_info = $product_db->getProductInfoByProductSn($product['product_sn']);
					if (!empty($product_info)) {
						$productDetail['product_all'][$key]['price_limit'] = $product_info['price_limit'];
					}
					
				}
			}
		}
		//var_dump($productDetail);die();
        //从新计算产品均值 结束
        //从新计算订单各个金额，订单支付状态 开始

        //货到付款的拒收订单，统计订单金额时运费置为0   或者商品全退
        if ($order['status'] == 0 && $order['status_logistic'] == 5 && $order['status_return'] == 1 && $order['pay_type'] == 'cod') {
            $order['price_logistic'] = 0;
        }
		if($productDetail['other']['price_goods'] == 0 && $order['status_return'] == 1){
		    if ($order['return_price_logistic'] == 1) {
			    $order['price_logistic'] = 0;
			}
		}
		
		//$shop_cart_api = new Shop_Models_API_Cart();
		//$order['price_logistic'] = $shop_cart_api -> getFareByWeight('sf',$productDetail['goods_weight']);
		$productDetail['other']['taxAll'] = $productDetail['other']['taxAll'] <= 50 ? 0 : $productDetail['other']['taxAll'];
		
        $priceOrder = round($productDetail['other']['price_goods_eq'], 2) +
                      $order['price_logistic'] + 
                      $productDetail['other']['taxAll'] +
                      $adjust['price_adjust_return'] +              //退货调整金额
                      $adjust['price_adjust_return_logistic_to'] +  //退货 退运费
                      $adjust['price_adjust_return_logistic_back']; //退货 退运费
        $pricePay = $priceOrder;

        //获得礼品卡信息
        $onlyGiftCard = true;
        $orderBatchGoodsIDArray = $giftCardList = '';
		if (count($productDetail['product'])>0){
			foreach ($productDetail['product'] as $product) {
				if ($product['product_id'] > 0) {
					if ($product['is_gift_card']) {
						$orderBatchGoodsIDArray[] = $product['order_batch_goods_id'];
						$orderBatchGoodsData[$product['order_batch_goods_id']] = $product;
					}
					else {
						$onlyGiftCard = false;
					}
				}
			}
		}

        if ($orderBatchGoodsIDArray) {
            $giftCardAPI = new Admin_Models_API_GiftCard();
            $giftCardList = array_shift($giftCardAPI -> getCardlist(array('order_batch_goods_id' => $orderBatchGoodsIDArray, 'status' => array(0,2))));
            
            //包含普通商品的订单，重新计算订单金额
            if (!$onlyGiftCard) {
                $goodsAmount = $giftCardPrice = $giftCardAmount = 0;
                foreach ($productDetail['product'] as $product) {
                    if ($product['is_gift_card']) {
                        $giftCardPrice += $product['sale_price'] * ($product['number'] - $product['return_number']);
                        $amountInfo = $this -> _product -> getGiftcardInfoByProductid($product['product_id']);
                        $giftCardAmount += $amountInfo['amount'];
                    }
                    else {
                        $goodsAmount += $product['sale_price'] * ($product['number'] - $product['return_number']);
                    }
                }
                
                $productDetail['other']['price_goods'] = $productDetail['other']['price_goods'] - $giftCardPrice;
                $priceOrder = $productDetail['other']['price_goods'] + $order['price_logistic'] + $productDetail['other']['taxAll'] + $order['price_adjust'] + $adjust['price_adjust_return'];
                
                if ($giftCardAmount >= $goodsAmount + $order['price_adjust'] + $adjust['price_adjust_return']) {
                    $pricePay = $giftCardPrice + $order['price_logistic']  + $productDetail['other']['taxAll'];
                }
                else {
                    $pricePay = $productDetail['other']['price_goods'] + $order['price_logistic'] + $productDetail['other']['taxAll'] + $order['price_adjust'] + $adjust['price_adjust_return'] - $giftCardAmount + $giftCardPrice;
                }
            }
        }
        
        if (round($order['price_payed'] + $order['price_from_return'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'], 2) < round($pricePay, 2)) {
            $statusPay = 0;//未收款
        } else if (round($order['price_payed'] + $order['price_from_return'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'], 2) == round($pricePay, 2)) {
            //提货卡只要不是已签收，都是未收款
            if ($this -> getOrderGoodsCard($productDetail['product_all']) && $order['status_logistic'] != 4) {
                $statusPay = 0;
            }
            else    $statusPay = 2;//已结清
        } else if (round($order['price_payed'] + $order['price_from_return'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'], 2) > round($pricePay, 2)) {
            $statusPay = 1;//未退款
        }
        
        $set = array('price_order' => $priceOrder,
                     'price_goods' => $productDetail['other']['price_goods'],
                     'price_adjust' => $adjust['price_adjust'],
                     'price_pay' => $pricePay,
                     'status_pay' => $statusPay,
                     'price_logistic' => $order['price_logistic'],
        			 'tax' => $productDetail['other']['taxAll']<=50?0:$productDetail['other']['taxAll'],);
        
        //发货前，如果订单金额是0，或者由积分、余额、礼品卡全额支付，支付方式设置为无需支付
        if (in_array($order['status_logistic'], array(0,1,2))) {
            if ($pricePay <= 0 || ($order['account_payed'] + $order['point_payed'] + $order['gift_card_payed']) >= $pricePay || $order['parent_batch_sn']) {
                $set['pay_type'] = 'no_pay';
                $set['pay_name'] = '无需支付';
            }
            else {
                if ($order['pay_type'] == 'no_pay') {
                    $set['pay_type'] = '';
                    $set['pay_name'] = '';
                }
            }
        }
        
        //根据支付状态修改礼品卡状态
        if ($giftCardList) {
            if ($statusPay == 1 || $statusPay == 2) {
                foreach ($giftCardList as $giftCard) {
                    $tempCardList[$giftCard['order_batch_goods_id']][] = $giftCard;
                }
                foreach ($tempCardList as $orderBatchGoodsID => $giftCardList) {
                    $orderBatchGoods = $orderBatchGoodsData[$orderBatchGoodsID];
                    if ($orderBatchGoods['return_number'] > 0) {
                        $returnNumber = $orderBatchGoods['return_number'];
                        foreach ($giftCardList as $giftCard) {
                            if ($giftCard['status'] == 0) {
                              $giftCardAPI -> updateCard("card_sn = '{$giftCard['card_sn']}'", array('status' => 2));
                            }
                            $returnNumber--;
                            if ($returnNumber <= 0) {
                                break;
                            }
                        }
                    }
                    else {
                        foreach ($giftCardList as $giftCard) {
                            if ($giftCard['status'] == 2) {
                                $giftCardAPI -> updateCard("card_sn = '{$giftCard['card_sn']}'", array('status' => 0));
                            }
                        }
                    }
                }
            }
            else {
                foreach ($giftCardList as $giftCard) {
                    if ($giftCard['status'] == 0) {
                        $giftCardAPI -> updateCard("card_sn = '{$giftCard['card_sn']}'", array('status' => 2));
                    }
                }
            }
            
            //只有礼品卡的已签收，修改订单类型
            if ($onlyGiftCard && $order['status_logistic'] == 4) {
                if ($order['status'] != 5) {
                    $set['status'] = 5;
                }
            }
        }
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        $adjust['price_adjust_return'] && $order['price_adjust_return'] = $adjust['price_adjust_return'];
        //从新计算订单各个金额，订单支付状态 结束
        
        $data = array_merge($productDetail,
                            array('order' => $order),
                            array('adjust' => $adjust),
                            array('batch_log' => $batchLog),
                            array('pay_log' => $payLog));
        $data['other']['price_adjust'] = $order['price_adjust'];
        $data['other']['price_logistic'] = $order['price_logistic'];
        $data['other']['price_order'] = $order['price_order'];
        $data['other']['price_pay'] = $order['price_pay'];
        $data['other']['price_payed'] = $order['price_payed'];
        $data['other']['account_payed'] = $order['account_payed'];
        $data['other']['point_payed'] = $order['point_payed'];
        $data['other']['gift_card_payed'] = $order['gift_card_payed'];
        $data['other']['price_from_return'] = $order['price_from_return'];
        $data['other']['price_before_return'] = $order['price_before_return'];
        $data['other']['price_blance'] = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
        if ($data['other']['price_blance'] > 0) {
            $data['other']['price_must_pay'] = $data['other']['price_blance'];
        }
        //处理退货时的退款，优先顺序：账户余额 -> 积分 -> 礼品卡 -> 现金
        $blanceMoney = $data['other']['price_blance'];
        if ($blanceMoney < 0) {
            $blanceMoney = abs($blanceMoney);
            $returnAccount = $returnPoint = $returnGift = $returnMoney = 0;
            if ($order['account_payed'] > 0) {
                if ($order['account_payed'] >= $blanceMoney) {
                    $returnAccount = $blanceMoney;
                    $blanceMoney = 0;
                }
                else {
                    $returnAccount = $order['account_payed'];
                    $blanceMoney -= $order['account_payed'];
                }
            }
            if ($blanceMoney > 0 && $order['point_payed'] > 0) {
                if ($order['point_payed'] >= $blanceMoney) {
                    $returnPoint = $blanceMoney;
                    $blanceMoney = 0;
                }
                else {
                    $returnPoint = $order['point_payed'];
                    $blanceMoney -= $order['point_payed'];
                }
            }
            if ($blanceMoney > 0 && $order['gift_card_payed'] > 0) {
                if ($order['gift_card_payed'] >= $blanceMoney) {
                    $returnGift = $blanceMoney;
                    $blanceMoney = 0;
                }
                else {
                    $returnGift = $order['gift_card_payed'];
                    $blanceMoney -= $order['gift_card_payed'];
                }
            }
            if ($blanceMoney > 0) {
                $returnMoney = $blanceMoney;
            }
        }
        $data['finance']['price_return_logistic'] = -$adjust['price_adjust_return_logistic_back'];  //退货 退运费
        $data['finance']['price_return_money'] = $returnMoney + $adjust['price_adjust_return_logistic_back'];
        $data['finance']['price_return_point'] = $returnPoint;
        $data['finance']['price_return_gift'] = $returnGift;
        $data['finance']['price_return_account'] = $returnAccount;
        $data['finance']['price_return_all_money'] = $order['price_payed'] + $order['price_from_return'];
        $data['finance']['price_return_all_point'] = abs($order['point_payed']);
        $data['finance']['price_return_all_gift'] = abs($order['gift_card_payed']);
        $data['finance']['price_return_all_account'] = abs($order['account_payed']);
        $data['finance']['price_return_all'] = $data['finance']['price_return_all_money'] + $data['finance']['price_return_all_point'] + $data['finance']['price_return_all_account'] + $data['finance']['price_return_all_gift'];
        $data['finance']['price_return'] = $data['finance']['price_return_money'] + $data['finance']['price_return_point'] + $data['finance']['price_return_account'] + $data['finance']['price_return_gift'];
        
        $status_return = $status_return_all = 0;
        if ($data['finance']['price_return'] > $financeReturning['amount']) {
            $status_return = 1;
        }
        if ($order['price_payed'] > 0) {
            $status_return_all = 1;
        }
        $data['finance']['status_return'] = $status_return;
        $data['finance']['status_return_all'] = $status_return_all;
        
        return $data;
    }


    /**
     * @param   string   $batchSN
     * @return  array
     */
    public function _avgDetail($batchSN,$order)
    {
        $data = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, '(number-return_number)>' => 0 ));

       

        foreach($data as $key => $val) {
            if ($val['product_id'] && in_array($val['type'],array('0','1','6','7'))) {
                $product = array_shift($this -> _product -> get(array('product_id' => $val['product_id'])));
                $data[$key]['cost'] = $product['cost'];

				$this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,'order_batch_goods_id' => $val['order_batch_goods_id']),
				array('cost' =>$product['cost']));		

            }
        }
        //////////

		foreach ($data as $k => $v) {
			if (($v['product_id'] && in_array($val['type'],array('0','1','6','7'))) || (empty($v['parent_id']) && $v['type'] == '5')) {
                $a+= $v['cost']* ($v['number']-$v['return_number']);
			}
		}
		if($order['price_goods'] >0){
			foreach ($data as $k => $v) {
				if (($v['product_id'] && in_array($val['type'],array('0','1','6','7'))) || (empty($v['parent_id']) && $v['type'] == '5')) {
					if($a>0){
						$avg_price =  $v['cost']* ($v['number']-$v['return_number'])/$a * ($order['price_goods']+$order['price_adjust']) / ($v['number']-$v['return_number']);
						$this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,'order_batch_goods_id' => $v['order_batch_goods_id']),
						array('avg_price' => $avg_price));				
					}
				}else if ($v['product_id'] && $v['parent_id'] && $v['type'] == '5') {
					if($a>0){
						$avg_price =  $v['cost']* ($v['number']-$v['return_number'])/$a * ($order['price_goods']+$order['price_adjust']) / ($v['number']-$v['return_number']);
						$this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,'order_batch_goods_id' => $v['order_batch_goods_id']),
						array('avg_price' => $avg_price));
					
					}

				}
			}
		}
	}

    /**
     * 订单组合商品 销售均价处理
     *
     * @param   string   $batchSN
     * @return  array
     */
	public function groupDetail($batchSN){
       $data = $this -> _db -> getOrderBatchGoods(array('type'=>5, 'batch_sn'=>$batchSN,'number>'=>0));//取出订单中的组合商品
	   if ($data) {
			foreach ($data as $k => $v) {
				if ($v['product_id']) {
					$product[$v['order_batch_goods_id']] = $v;//实体商品
				}
				$productAll[$v['order_batch_goods_id']] = $v;//包括实体商品，非实体商品
			}
			foreach ($productAll as $id => $v) {
				if ($v['parent_id']) {
					if ($productAll[$v['parent_id']]) {
						$productAll[$v['parent_id']]['child'][] = $v;
					}
					unset($productAll[$id]);
				}
			}
			foreach ($productAll as $key => $var) {
					$group_goods_sale_price =$var['sale_price']*$var['number'];
                    $totlecost='0';
					foreach($var['child'] as $k => $v){
						$totlecost +=$v['cost']*$v['number'];
						$totlenum+=$v['number'];
					}
					$saleAmount='0';
					foreach($var['child'] as $k => $v){
						if($totlecost >0 ){
						  $v['sale_price'] = round(($v['cost']/$totlecost)*$group_goods_sale_price,2);
						}else{
							$v['sale_price'] = round(($group_goods_sale_price/$totlenum),2);
						}
						$saleAmount += $v['sale_price'] * $v['number'];
						if($v['sale_price']>0){
							$this -> _db -> updateOrderBatchGoods(array('batch_sn'=>$batchSN, 'order_batch_goods_id' => $v['order_batch_goods_id']), array('sale_price'=>$v['sale_price'],'sale_price_blance' => '0'));
						}
						$tmpID = $v['order_batch_goods_id'];
					}
					$sale_price_blance =round($group_goods_sale_price - $saleAmount, 2);
					if ($sale_price_blance) {
						$this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,
																	'order_batch_goods_id' => $tmpID),
															  array('sale_price_blance' => $sale_price_blance));
					}
			}
		}
	}

    /**
     * 取订单商品 并且处理相对应的活动
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function _productDetail($batchSN, $logicArea = 1)
    {
        $data = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'number>' => 0));
        if ($data) {
            $stockAPI = new Admin_Models_API_Stock();
            $stockAPI -> setLogicArea($logicArea);
            foreach ($data as $k => $v) {
                if ($v['product_id']) {
                    //读取库存资料 开始
                    $stock = $stockAPI -> getSaleProductStock($v['product_id'], true);
                    $v['able_number'] = $stock['able_number'];
                    //读取库存资料 结束
                    $product[$v['order_batch_goods_id']] = $v;//实体商品
                }
                $productAll[$v['order_batch_goods_id']] = $v;//包括实体商品，立减等等非实体商品
            }
            //设置 商品的从属关系（礼包，商品买赠，商品折扣等等，可以归属到某一个商品上[具有parent_id]）开始
            foreach ($productAll as $id => $v) {
                if ($v['parent_id']) {
                    if ($productAll[$v['parent_id']]) {
                        $productAll[$v['parent_id']]['child'][] = $v;
                    }
                    unset($productAll[$id]);
                }
            }
            
            //设置 商品的从属关系 结束
            foreach ($productAll as $id => $v) {
                $productAll[$id]['blance'] = $v['blance'] = $v['number'] - $v['return_number'];//该商品可用数量
                $productAll[$id]['amount'] = $v['amount'] = $v['sale_price'] * ($v['number'] - $v['return_number']);//该商品总金额
                //$productAll[$id]['fareAll'] = $v['fareAll'] = $v['fare'] * ($v['number'] - $v['return_number']);//该商品总运费
                $productAll[$id]['taxAll'] = $v['taxAll'] = $v['tax'] * ($v['number'] - $v['return_number']);//该商品总行邮税
                //转换活动信息输出资料 开始
                if ($v['child']) {
                    foreach ($v['child'] as $x => $y) {
                       $productAll[$id]['child'][$x]['blance'] = $y['blance'] = $y['number'] - $y['return_number'];
                       $productAll[$id]['child'][$x]['amount'] = $y['amount'] = $y['sale_price'] * $y['number'];
                       $productAll[$id]['child'][$x]['fareAll'] = $y['fareAll'] = $y['fare'] * ($y['number'] - $y['return_number']);
                       $productAll[$id]['child'][$x]['taxAll'] = $y['taxAll'] = $y['tax'] * ($y['number'] - $y['return_number']);
                    }
                }
            }
            //计算所有商品的各类总价格 开始
            $goodsNumber = 0;
            $goodsWeight = 0;

            foreach ($product as $k => $v) {
            	if ($v['product_id'] > 0 ){
	                $priceGoods += ($v['sale_price']+$v['fare']) * ($v['number'] - $v['return_number']);//商品总金额(不包含退货商品)
	                $priceGoodsEq += $v['eq_price'] * ($v['number'] - $v['return_number']);//均摊了订单立减，调整金额，礼金券后商品总金额
	                $fareAll += $v['fare']*($v['number'] - $v['return_number']);//商品总运费
	                $taxAll += $v['tax'] * ($v['number'] - $v['return_number']);//商品总行邮税
	                if (($v['number'] - $v['return_number']) > 0) {
	                    $priceGoodsEq += $v['eq_price_blance'];
						$priceGoods += $v['sale_price_blance'];
	                }
	                $priceGoodsAll += ($v['sale_price']+$v['fare']) * $v['number'];//商品总金额(包含退货商品)
	                if (!$v['is_gift_card']) {
	                    $priceGoodsAllWithoutGiftCard += ($v['sale_price']+$v['fare']) * $v['number'];
						$priceGoodsAllWithoutGiftCard += $v['sale_price_blance'];
	                }
            	}
            	$goodsNumber += $v['number'] - $v['return_number'];
            	$goodsWeight += $v['weight'] * ($v['number'] - $v['return_number']);
            }
            //计算所有商品的各类总价格 结束

        }
        
        return array('product_all' => $productAll,//只要是 order_goods表的都读进来
                     'product' => $product,//实体商品
                     'price_minus' => -abs($priceMinus),//订单立减
                     'price_coupon' => -abs($priceCoupon),//礼券 新版
                     'goods_number' => $goodsNumber,
                     'goods_weight' => $goodsWeight,
                     'other' => array('price_goods_all' => $priceGoodsAll,//商品总金额(包含退货商品)
                                      'price_goods_all_without_gift_card' => $priceGoodsAllWithoutGiftCard,//商品总金额(不包含礼品卡)
                                      'price_goods_all' => $priceGoodsAll,//商品总金额(包含退货商品)
                                      'price_goods' => $priceGoods,//商品总金额(不包含退货商品)
                                      'price_goods_eq' => $priceGoodsEq,//均摊了订单立减，调整金额，礼金券后的商品总金额[均摊价的累加]
                     				  'fareAll' => $fareAll,//商品的总运费
                     				  'taxAll' => $taxAll ,//商品总行邮税
                     		));
        							  
    }
    /**
     * 处理订单调整金额
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function _adjustDetail($batchSN)
    {
        $adjust = $this -> _db -> getOrderBatchAdjust(array('batch_sn' => $batchSN));
        if ($adjust) {
            $priceAdjustReturn = $priceAdjustReturnLogisticTo = $priceAdjustReturnLogisticBack = $priceAdjustChange = $priceAdjustChangeLogisticTo = $priceAdjustChangeLogisticBack = $priceAdjust = 0;
            foreach ($adjust as $tmp) {
                if ($tmp['type'] == 10) {//退货调整金额
                    $priceAdjustReturn += $tmp['money'];
                } else if ($tmp['type'] == 11) {//退货退邮寄给顾客的运费
                    $priceAdjustReturnLogisticTo += $tmp['money'];
                } else if ($tmp['type'] == 12) {//退货退顾客邮寄回的运费
                    $priceAdjustReturnLogisticBack += $tmp['money'];
                } else if ($tmp['type'] == 20) {//换货调整金额
                    $priceAdjustChange += $tmp['money'];
                } else if ($tmp['type'] == 21) {//换货退邮寄给顾客的运费
                    $priceAdjustChangeLogisticTo += $tmp['money'];
                } else if ($tmp['type'] == 22) {//换货退顾客邮寄回的运费
                    $priceAdjustChangeLogisticBack += $tmp['money'];
                } else {//未确认订单调整金额
                    $priceAdjust += $tmp['money'];
                }
            }
        }
        return array('adjust' => $adjust,
                     'price_adjust' => $priceAdjust + $priceAdjustChange,//这个调整金额是要均摊到各个商品上的
                     'price_adjust_return' => $priceAdjustReturn,//退货亏掉的调整金额 不参与联盟分成计算
                     'price_adjust_return_logistic_to' => $priceAdjustReturnLogisticTo,//退货亏掉的运费 不参与联盟分成计算
                     'price_adjust_return_logistic_back' => $priceAdjustReturnLogisticBack,//退货亏掉的运费 不参与联盟分成计算
                     'price_adjust_change' => $priceAdjustChange,//换货调整金额和未确认单的调整金额概念一致所以合并到 price_adjust
                     'price_adjust_change_logistic_to' => $priceAdjustChangeLogisticTo,//换货时，已加入流转金额到新单
                     'price_adjust_change_logistic_back' => $priceAdjustChangeLogisticBack);//换货时，加入流转金额到新单
    }
	/**
     * 添加一条支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderPayLog($data)
    {
        $time = time();
        $data['pay_log_id'] = $data['batch_sn'] . '-' . $time;
        $data['add_time'] = $time;
        $this -> _db -> addOrderPayLog($data);
    }

	/**
     * 删除指定订单的支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function delOrderPayLog($batch_sn)
    {
        $this -> _db -> delOrderPayLog($batch_sn);
    }

	/**
     * 取得指定条件的订单日志
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function _batchLogDetail($batchSN)
    {
        $data = $this->_db->getOrderBatchLog(array('batch_sn' => $batchSN));
        if (is_array($data) && count($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }
        return $data;

    }
	/**
     * 取得指定条件的订单支付日志
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function _batchPayLogDetail($batchSN)
    {
        $data = $this->_db->getOrderBatchPayLog($batchSN);
        if (is_array($data) && count($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }
        return $data;

    }
    /**
     * 统计订单金额信息
     *
     * @param   string      $orderSN
     * @return  array
     */
    public function orderPriceStatus($orderSN)
    {
        $order = $this -> _db -> getOrderBatch(array('order_sn' => $orderSN, 'status' => 0));

        if ($order) {
            foreach ($order as $batch) {
                if ($batch['status_logistic'] == 5) {
                    continue;//该批次的订单为拒收状态，不参与联盟分成
                }
                $batchSN = $batch['batch_sn'];
                $data = $this -> orderDetail($batchSN);
                $priceGoods += $data['other']['price_goods'];
                $priceOrder += $data['other']['price_order'];
                $priceLogistic += $data['other']['price_logistic'];//运费不给分成
                //退来回运费 和 退货调整金额产生的亏损 这个要补给联盟
                $priceAdjustForReturn += $data['adjust']['price_adjust_return'];
                $priceAdjustForReturn += abs($data['adjust']['price_adjust_return_logistic_to']);
                $priceAdjustForReturn += abs($data['adjust']['price_adjust_return_logistic_back']);

            }
        }
        //注意：$priceAdjustForReturn 是我们损失的费用，已经计算到订单金额里了，所以给联盟分成的时候，需要补上。
        $affiliateAmount = $priceOrder - abs($priceLogistic) + $priceAdjustForReturn;// - abs($priceAccount);帐户余额允许分成
        if ($affiliateAmount < 0) {
            $affiliateAmount = 0;
        }
        $data = array('price_goods' => $priceGoods,
                      'price_order' => $priceOrder,
                      'price_pay' => $priceOrder,
                      'affiliate_amount' => $affiliateAmount);//可分成金额
        return $data;
    }
    /**
     * 申请出库
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function out($batchSN, $splitData = null, $logicArea = 1, $outStockSN = null)
    {
        if (is_array($batchSN)) {   //合并订单
            $batchSNArray = $batchSN;
        }
        else {
            $batchSNArray = array($batchSN);
        }

        $time = time();
        $fields = "a.price_order,a.point_payed,a.batch_sn,a.addr_consignee,a.addr_province,a.addr_city,a.addr_area,a.addr_province_id,a.addr_city_id,a.type,
	        	   a.addr_area_id,a.addr_address,a.addr_zip,a.addr_tel,a.addr_mobile,a.note_print,a.note_logistic,a.logistic_name,a.logistic_code,a.logistic_price,a.logistic_price_cod,a.logistic_list,a.pay_type,b.shop_id,b.invoice,b.invoice_type,b.invoice_content";
	    $bill_no = '';
	    $remark = '';
	    $logistic_price = 0;
	    $logistic_price_cod = 0;
	    $logistic_fee_service = 0;
	    $print_remark = '';
	    $amount = 0;
	    $weight = 0;
	    $volume = 0;
	    $goods_number = 0;
	    $is_assign = 0;
	    $logistic = '';

	    $details = array();

        foreach ($batchSNArray as $batchSN) {
            if (!$batchSN)  continue;

            $this -> _updateOrderBatchLogistic($batchSN);
            $r = array_shift($this -> _db -> getOrderBatchInfo(array('batch_sn' => $batchSN), $fields));
            $bill_no .= $batchSN.',';
            $remark .= $r['note_logistic'] ? $r['note_logistic'].',' : '';
            $logistic_price += $r['logistic_price'];
            $logistic_price_cod += $r['logistic_price_cod'];
            $logistic_fee_service += $r['logistic_fee_service'];
            $print_remark .= $r['note_print'] ? $r['note_print'].',' : '';

            $info = Zend_Json::decode($r['logistic_list']);
            $amount += $info['other']['amount'];
            $weight += empty($info['other']['weight']) ? 1 : $info['other']['weight'];//todo
            $volume += empty($info['other']['volume']) ? 1 : $info['other']['volume'];//todo
            $goods_number += $info['other']['number'];
            $logistic = $info['other']['cod'] ? $info['list'][$info['other']['default_cod']]: $info['list'][$info['other']['default']];

            //自提不用物流派单
            if ($r['logistic_code'] == 'self' || $r['logistic_code'] == 'externalself') {
                $is_assign = 1;
            }
            $bill_type = 1;
            $outstock_bill_type = 1;

            //设置财务结款金额
            $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('balance_amount' => $r['price_order'], 'balance_point_amount' => $r['point_payed']));
            
            $where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0, '(number-return_number)>' => 0);
            $product = $this -> _db -> getOrderBatchGoods($where);
            if ($product) {
                foreach($product as $k => $v) {
                    if ($details[$v['product_id']]) {
                        $details[$v['product_id']]['number'] += $v['number'] - $v['return_number'];
                        $details[$v['product_id']]['shop_price'] = $v['eq_price'];
                    } else {
                        $details[$v['product_id']] = array ('product_id' => $v['product_id'],
                                                            'number' => $v['number'] - $v['return_number'],
                                                            'shop_price' => $v['eq_price'],
                                                            'status_id' => 2);
                    }
                    
                    //设置财务产品均价
                    $this -> _db -> updateOrderBatchGoods(array('order_batch_goods_id' => $v['order_batch_goods_id']), array('finance_price' => $v['eq_price']));
                }
            }
        }

        $bill_no = substr($bill_no, 0, -1);

        $rowOutStock = array ('lid' => $logicArea,
                              'bill_no' => $bill_no,
                              'bill_type' => $outstock_bill_type,
                              'bill_status' => 3,
                              'remark' => substr($remark, 0, -1),
                              'add_time' => $time,
                              'admin_name' => $this -> _auth['admin_name']
                             );

        $row['add_time'] = $time;
        $row['bill_no'] = $bill_no;
        $row['admin_name'] = $this -> _auth['admin_name'];
        $row['logistic_name'] = $r['logistic_name'];
        $row['logistic_code'] = $r['logistic_code'];
        $row['logistic_price'] = $logistic_price;
        $row['logistic_price_cod'] = $logistic_price_cod;
        $row['logistic_list'] = $r['logistic_list'];
        $row['logistic_fee_service'] = $logistic_fee_service;
        $row['consignee'] = $r['addr_consignee'];
        $row['province'] = $r['addr_province'];
        $row['city'] = $r['addr_city'];
        $row['area'] = $r['addr_area'];
        $row['province_id'] = intval($r['addr_province_id']);
        $row['city_id'] = intval($r['addr_city_id']);
        $row['area_id'] = intval($r['addr_area_id']);
        $row['address'] = $r['addr_address'];
        $row['zip'] = $r['addr_zip'] ? $r['addr_zip'] : $this -> _db -> getAreaZip($r['addr_area_id']);
        $row['tel'] = $r['addr_tel'];
        $row['mobile'] = $r['addr_mobile'];
        $row['print_remark'] = substr($print_remark, 0, -1);
        $row['remark'] = substr($remark, 0, -1);
        $row['bill_type'] = $bill_type;
        $row['shop_id'] = intval($r['shop_id']);
        $row['invoice'] = $r['invoice'];
        $row['invoice_type'] = $r['invoice_type'];
        $row['invoice_content'] = $r['invoice_content'];
        $row['amount'] = $amount;
        $row['weight'] = $weight;
        $row['volume'] = $volume;
        $row['goods_number'] = $goods_number;
        $row['is_cod'] = $r['pay_type'] == 'cod' ? 1 : 0;
        $row['search_mod'] = $logistic['search_mod'] ? $logistic['search_mod'] : '';
        $row['is_assign'] = $is_assign; //自提
        $row['lid'] = $logicArea;
        $row['lock_name'] = $this -> _auth['admin_name'];

        $transport = new Admin_Models_DB_Transport();
        $transportAPI = new Admin_Models_API_Transport();
        $outStock = new Admin_Models_API_OutStock();

        if (!$transport -> get("bill_no='{$bill_no}' and is_cancel=0")) {
            if ($splitData) {   //拆单
                $index = 1;
                foreach ($splitData as $split) {
                    $row['bill_no'] = $bill_no.'-'.$index++;
                    $rowOutStock['bill_no'] = $row['bill_no'];
                    $splitDetail = '';
                    $goods_number = 0;
                    foreach ($split as $product) {
                        $splitDetail[$product['product_id']] = array('product_id' => $product['product_id'],
                                                                     'number' => $product['number'],
                                                                     'shop_price' => $details[$product['product_id']]['shop_price'] ? $details[$product['product_id']]['shop_price'] : 0 ,
                                                                     'status_id' => $details[$product['product_id']]['status_id'],
                                                                    );
                        $goods_number += $product['number'];
                    }
                    $row['goods_number'] = $goods_number;
                    $tid = $transport -> insert($row);
					
                    $outstock_id = $outStock -> insertApi($rowOutStock, $splitDetail, $logicArea, true);
                    
					if ($tid > 0 && $outstock_id > 0) {
						$source_params = array(
							'bill_no'      => $row['bill_no'],
							'transport_id' => $tid,
							'outstock_id'  => $outstock_id,
						);
						$transport->insertRelationOrder($source_params);
					}

                    $transportAPI -> addOp($tid, $this -> _auth['admin_name'], 'prepare', '');
                }
                
                return true;
            }
            else {
				$bill_nos = explode(',', $bill_no);
                $tid = $transport -> insert($row);
                $transportAPI -> addOp($tid, $this -> _auth['admin_name'], 'prepare', '');
                if ($tid) {
                    if ($outStockSN) {
                        $outStockData = array_shift($outStock -> get("b.bill_no = '{$outStockSN}'"));
                        $outStock -> update(array('bill_no' => $rowOutStock['bill_no'], 'bill_status' => 3), "bill_no = '{$outStockSN}'");
                        
                        $source_params = array('bill_no' => $rowOutStock['bill_no'],
						        	           'transport_id' => $tid,
									           'outstock_id'  => $outStockData['outstock_id'],
								              );
						$transport -> insertRelationOrder($source_params);
						    
                        return true;
                    }
                    else {
                        $outstock_id =  $outStock -> insertApi($rowOutStock, $details, $logicArea, true);
						if ($tid > 0 && $outstock_id > 0) {
							foreach ($bill_nos as $no) {
								$source_params = array(
									'bill_no'      => $no,
									'transport_id' => $tid,
									'outstock_id'  => $outstock_id,
								);
								$transport->insertRelationOrder($source_params);
							}
						}

						return $outstock_id;
                    }
                }
            }
        }
    }

    /**
     * 申请退货入库
     *
     * @param   string      $batchSN
     * @param   array       $product
     * @param   string      $remark
     * @param   int         $type
     * @return  void
     */
    public function in($batchSN, $product, $remark, $type = 1)
    {
		if (!$product)  return false;
        
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $order['status'] == 4 && $order['lid'] = 1;
        
		$time = time ();
		$billNo = Custom_Model_CreateSn::createSn();
	    $row = array ('lid' => $order['lid'],
		              'bill_no' => $billNo,
		              'item_no' => $batchSN,
                      'bill_type' => $type,
                      'bill_status' => 3,
			          'remark' => $remark,
                      'add_time' => $time,
                      'admin_name' => $this -> _auth['admin_name']);
        
	    $outStockAPI = new Admin_Models_API_OutStock();
	    $details = $outStockAPI -> getDetail("b.bill_no like '%{$batchSN}%'");
        
	    if (!$details)  return false;
        
	    foreach ($details as $detail) {
	        $outStockInfo[$detail['product_id']][] = array('batch_id' => $detail['batch_id'],
	                                                       'number' => $detail['number'],
	                                                      );
            $productCostInfo[$detail['product_id']] = $detail['cost'];
	    }
        
	    $details = array();
        foreach($product as $productID => $v) {
            if (!$outStockInfo[$productID])  return false;

            $number = $v['number'];
            foreach ($outStockInfo[$productID] as $stock) {
                if ($number <= $stock['number']) {
                    $number = 0;
                    break;
                }
                else {
                    $number -= $stock['number'];
                }
            }
            if ($number > 0)    return false;
            
            $details[] = array ('product_id' => $productID,
                                'batch_id' => 0,
                                'status_id' => 6,
                                'plan_number' => $v['number'],
                                'shop_price' => $productCostInfo[$productID] ? $productCostInfo[$productID] : 0);
        }
        
        //如果只有虚拟商品，状态设为待收货
        foreach($product as $productID => $v) {
            if ($v['type'] == 7) {
                $hasVitual = true;
                if (!isset($onlyVitual)) {
                    $onlyVitual = true;
                }
            }
            else {
                $onlyVitual = false;
            }
        }
        if ($onlyVitual) {
            $row['bill_status'] = 6;
        }
        $inStock = new Admin_Models_API_InStock();
		$inStock -> insertApi($row, $details, $order['lid'], true);
		//丢件处理
		if ($type == 13) {
		    $inStock -> receiveByBillNo($billNo);
		}
		return $row['bill_no'];
    }
    
    /**
     * 更新物流信息
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function _updateOrderBatchLogistic($batchSN)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $logistic = $this -> _getLogistic($order);

        if ($order['pay_type'] == 'cod') {//代收款
            $tmp = $logistic['list'][$logistic['other']['default_cod']];
        } else {
            $tmp = $logistic['list'][$logistic['other']['default']];
        }
        $data = array(
                      'logistic_list' => Zend_Json::encode($logistic),
					  );
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
    }
	/**
     * 匹配物流公司
     *
     * @param    array    $order
     * @return   string
     */

    public function _getLogistic($order)
    {
        if (!$order['addr_area_id']) {
            return false;
        }
        $productStatus = $this -> _orderBatchProductStatus($order['batch_sn']);
        $data = $this -> _db -> getLogistic(array('area_id' => $order['addr_area_id'],'pay_type'=> $order['pay_type']));
        $pay = $order['price_pay'] - ($order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'] + $order['price_from_return']);
        
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v['cod']) {
                    if (!$tmp['cod'] || $v['is_send'] == 'OK' ) {
                        $tmp['cod'] = array('logistic_code' => $v['logistic_code'],
                                            'price' => $v['price'],
                                            'cod_price' => $v['cod_price']);
                    }
                }
                if (!$tmp['all'] || $v['is_send'] == 'OK' ) {
                    $tmp['all'] = array('logistic_code' => $v['logistic_code'], 'price' => $v['price']);
                }
                $logistic['list'][$v['logistic_code']] = $v;
            }
            $areaID = $where['area_id'];
        }
        
        if ($logistic['list']) {
            $logistic['other'] = array('name' => $name,
                                       'cod' => intval($cod),
                                       'zip' => $order['addr_zip'],
                                       'province' => $order['addr_province'],
                                       'city' => $order['addr_city'],
                                       'area' => $order['addr_area'],
                                       'area_id' => $order['addr_area_id'],
                                       'address' => $order['addr_address'],
                                       'amount' => $pay,
                                       'volume' => $productStatus['product_volume'],
                                       'number' => $productStatus['product_number'],
                                       'weight' => empty($productStatus['product_weight']) ? 1 : $productStatus['product_weight'],
                                       'default' => $logistic['list'][$tmp['all']['logistic_code']]['logistic_code'],
                                       'default_cod' => $logistic['list'][$tmp['cod']['logistic_code']]['logistic_code']);
        }
        return $logistic;
    }
    /**
     * 订单恢复 冻结在财务中0 和 1 状态的记录
     *
     * @param   string      $batchSN
     * @return  array
     */
    function undoFinance($batchSN)
    {
        $tmp = $this -> _finance -> getLastFinanceByItemNO(1, $batchSN);
        if ($tmp['status'] <= 1) {//0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效
            $this -> _finance -> updateFinance($tmp['finance_id'], array('status' => 4));
        }
    }
	/**
     * 更新当前订单金额信息
     *
     * @param   string      $batchSN
     * @param   array      $bank
     * @return  array
     */
    function addFinance($batchSN, $bank, $pay, $logistic, $point, $account, $gift, $status, $shop_id, $type = 1, $way = 1, $delivery = 1)
    {
        $tmp = $this -> _finance -> getLastFinanceByItemNO(1, $batchSN);
        if ($tmp['status'] <= 1) {//0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效
            $this -> _finance -> updateFinance($tmp['finance_id'], array('status' => 4));
        }
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        unset($order['logistic_list'],$order['addr_province_option'],$order['addr_city_option'],$order['addr_area_option']);
        $product = $this -> orderDetail($batchSN);
        $orderData = array('order' => $order, 'product' => $product['product_all']);
        if ($bank['type'] == 3) {
            $account += $pay;
            $pay = 0;
        }
        $data = array('shop_id' => $shop_id ? $shop_id : 0,
                      'type' => $type ? $type : 1,
                      'way' => $way ? $way : 1,
                      'item' => 1,
                      'item_no' => $batchSN,
                      'pay' => -$pay,
                      'logistic' => -$logistic,
                      'point' => -$point,
                      'account' => -$account,
                      'gift' => -$gift,
                      'status' => $status,
                      'bank_type' => $bank['type'],
                      'bank_data' => serialize($bank),
                      'order_data' => serialize($orderData),
                      'note' => $bank['note'],
                      'delivery' => $delivery,
                      'callback' => 'updateOrderBatchPayed',
                      'add_time' => time());
        $financeID = $this -> _finance -> addFrinance($data);//api 申请财务退款
        
        $this -> _db -> updateFinanceReturnProduct("arg = '{$batchSN}'", array('finance_id' => $financeID));

        if ($pay) {
            $title .= '退款金额：￥' . $pay . ';';
        }
        if ($logistic) {
            $title .= '退运费：￥' . $logistic . ';';
        }
        if ($point) {
            $title .= '退积分：￥' . $point . ';';
        }
        if ($account) {
            $title .= '退账户余额：￥' . $account . ';';
        }
        if ($gift) {
            $title .= '退礼品卡：￥' . $gift . ';';
        }

        //添加日志 todo 财务待测
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => $title,
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }

	/**
     * 库存处理接口
     *
     * @param    array    $data
     * @return   void
     */
    public function toStock($orderBatchSN, $callback, $arg=null)
    {
        $batchSNArray = explode(',', $orderBatchSN);
        foreach ($batchSNArray as $index => $batchSN) {
            if ($callback != 'change' || $index == 0) {
                $this -> $callback($batchSN, $arg);
            }
        }
    }

	/**
     * 备货返回 或者 取消
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function back($batchSN, $arg=null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));

        if ($order['status_back'] == 1) {//取消
            if ($arg['is_check'] == 1) {//同意
                $data = array('status' => 1,
                              'status_logistic' => 0,
                              'status_back' => 0,
                              'lock_name' => '');
                //api 抵用券接口(整退)
                $this -> unUseCardCoupon($batchSN);
                //api 财务接口
                $finance = $this -> _finance -> getLastFinanceByItemNO(1, $batchSN);
                if ($finance['status'] == 0) {//0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效
                    $this -> _finance -> updateFinance($finance['finance_id'], array('status' => 1));
                }
                if (!$arg['prepared']) {
                    $this -> releaseSaleOutStock($batchSN);
                }
            } else if ($arg['is_check'] == 2) {//拒绝
                $data = array('status_back' => 0,
                              'lock_name' => '');
                $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '申请取消被拒绝',
                     'note' => ''.$arg['remark'],
                     'admin_name' => $this -> _auth['admin_name']);
                $this -> _db -> addOrderBatchLog($log);
                //api 财务接口
                $finance = $this -> _finance -> getLastFinanceByItemNO(1, $batchSN);
                if ($finance['status'] == 0) {//0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效
                    $this -> _finance -> updateFinance($finance['finance_id'], array('status' => 4));
                }
            }
        } else if ($order['status_back'] == 2) {//返回
            if ($arg['is_check'] == 1) {
                $data = array('status_logistic' => 0, 'status_back' => 0, 'lock_name' => '');
            } else if ($arg['is_check'] == 2) {
                $data = array('status_back' => 0, 'lock_name' => '');
                $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '申请返回被拒绝',
                     'note' => ''.$arg['remark'],
                     'admin_name' => $this -> _auth['admin_name']);
                $this -> _db -> addOrderBatchLog($log);
            }
        }

        if ($data) {    //拆单订单可能已经更新过状态
            $where = array('batch_sn' => $batchSN);
            $this -> _db -> updateOrderBatch($where, $data);
        }

    }
	/**
     * 已发货
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function shipped($batchSN, $arg=null)
    {
		$order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
		
		$data = array('status_logistic' => 3,
                      'is_send' => 1,
                      'logistic_no' => $arg['logistic_no'],
                      'logistic_time' => $arg['logistic_time'] ? $arg['logistic_time'] : time(),
                      'invoice_no' => $arg['invoice_no'],
                      'lock_name' => '');
        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($batchSN);
		if ($isSplit) { //拆单
		    $batchSN = $isSplit['batch_sn'];
		    $order = array_shift($this -> _db -> getOrderBatchInfo(array('batch_sn' => $batchSN)));
		    $data['logistic_no'] = $order['logistic_no'];
		    if ($data['logistic_no']) {
		        $data['logistic_no'] .= ',';
		    }
		    $data['logistic_no'] .= $arg['logistic_no'];

		    if ($isSplit['hasSign']) {
		        $data['status_logistic'] = 6;
		    }
		    else if (!$isSplit['allSent']) {
		        $data['status_logistic'] = 2;
		    }
		}

        $where = array('batch_sn' => $batchSN);
        $this -> _db -> updateOrderBatch($where, $data);

        $data = array('is_send' => 1);
        $this -> _db -> updateOrderBatchGoods($where, $data);
        
        //礼品卡真实抵扣
        if ($order['gift_card_payed'] > 0) {
            $giftCardAPI = new Admin_Models_API_GiftCard();
            $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN, 'use_type' => 1));
            if ($giftCardLog['content']) {
                $giftCardLog = $giftCardAPI -> setCanReturnCard($giftCardLog['content']);
                foreach ($giftCardLog as $log) {
                    if ($log['can_return']) {
                        $card = array('card_type' => $log['card_type'],
                                      'card_price' => $log['price'],
                                      'card_sn' => $log['card_sn'],
                                      'card_pwd' => $log['card_pwd'],
                                      'add_time' => time(),
                                      'admin_id' => $this -> _auth['admin_id'] ? $this -> _auth['admin_id'] : 0,
                                      'admin_name' => $this -> _auth['admin_name'] ? $this -> _auth['admin_name'] : 'system',
                                      'batch_sn' => $batchSN,
                                      'use_type' => 2,
                                     );
                        $cardAPI = new Shop_Models_DB_Card();
                        $cardAPI -> useGift($card);
                    }
                }
            }
        }
        
        //添加应收款记录
        $financeAPI = new Admin_Models_API_Finance();
        $isCredit = $isDistribution = false;
        if ($order['pay_type'] == 'cod' || $isCredit || $isDistribution) {
            $receiveData = array('batch_sn' => $batchSN,
                                 'amount' => $order['price_pay'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'],
                                );
            if ($isCredit) {
                $receiveData['pay_type'] = 'credit';
                $receiveData['type'] = 2;
            }
            else if ($isDistribution) {
                $receiveData['pay_type'] = 'distribution';
                $receiveData['type'] = 2;
            }
            else {
                $receiveData['pay_type'] = $order['logistic_code'];
                if ($order['logistic_code'] == 'externalself') {
                    $receiveData['type'] = 3;
                }
                else {
                    $receiveData['type'] = 4;
                }
            }
            $financeAPI -> addFinanceReceivable($receiveData);
        }
        if (!$order['parent_batch_sn'] && ($order['account_payed'] > 0 || $order['point_payed'] > 0 || $order['gift_card_payed'] > 0)) {
            if ($order['account_payed'] > 0) {
                $receiveData = array('batch_sn' => $batchSN,
                                     'type' => 5,
                                     'pay_type' => 'account',
                                     'amount' => $order['account_payed'],
                                    );
                $financeAPI -> addFinanceReceivable($receiveData);
            }
            if ($order['point_payed'] > 0) {
                $receiveData = array('batch_sn' => $batchSN,
                                     'type' => 5,
                                     'pay_type' => 'point',
                                     'amount' => $order['point_payed'],
                                    );
                $financeAPI -> addFinanceReceivable($receiveData);
            }
            if ($order['gift_card_payed'] > 0) {
                $receiveData = array('batch_sn' => $batchSN,
                                     'type' => 5,
                                     'pay_type' => 'gift',
                                     'amount' => $order['gift_card_payed'],
                                    );
                $financeAPI -> addFinanceReceivable($receiveData);
            }
        }
        $this -> addFinanceReceivableForPreGiftCard($this -> orderDetail($batchSN));
        
        //直供单添加结算记录
        if ($order['type'] == 16 && $order['price_order'] > 0) {
            $data = array('batch_sn' => $batchSN,
                          'amount' => $order['price_order'],
                         );
            $financeAPI -> addDistributionSettlement($data);
        }
        
        $_SERVER['SERVER_NAME'] = strtolower($_SERVER['SERVER_NAME']);
        if ($_SERVER['SERVER_NAME']== "jkerp.1jiankang.com") {
			if($order['addr_mobile'] && Custom_Model_Check::isMobile($order['addr_mobile']) && $_SERVER['SERVER_NAME']== "jkerp.1jiankang.com"){
			  $sms = new  Custom_Model_Sms();
				$response = $sms -> send($order['addr_mobile'],"{$order['addr_consignee']}，非常感谢您的订购！您的订单 ".$batchSN." 已从我仓发出，物流公司为".$order['logistic_name']." 运单号".$arg['logistic_no']."  包裹将在近期内送达，请留意。 www.1jiankang.com");
			}
        }

    }
    
	/**
     * 代收货款变更
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function change($batchSN, $arg=null)
    {
        //添加调整金额 $arg['change_amount']
        $time = time();
        $adjust = array('order_sn' => array_shift(explode('_', $batchSN)),
                        'batch_sn' => $batchSN,
                        'type' => 1,
                        'money' => $arg['change_amount'],
                        'note' => '代收款变更金额',
                        'add_time' => $time);
        $this -> _db -> addOrderBatchAdjust($adjust);
        //更新支付状态
        $orderDetail = $this -> orderDetail($batchSN);
        
        //添加系统虚拟退款
        $financeAPI = new Admin_Models_API_Finance();
	    $data = array('shop_id' => $orderDetail['order']['shop_id'],
					  'type' => 0,//系统
					  'way' => 4,
					  'item' => 1,
					  'item_no' => $batchSN,
					  'pay' => $arg['change_amount'],
					  'logistic' => 0,
					  'point' => 0,
					  'account' => 0,
					  'gift' => 0,
					  'status' => 2,
					  'bank_type' => 4,
					  'bank_data' => '',
					  'order_data' => '',
					  'note' => '代收货款变更虚拟退款',
					  'callback' => '',
					  'add_time' => time(),
					  'check_time' => time());
		$financeID = $financeAPI -> addFrinance($data);
		
		//添加财务产品退款
        $productList = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0));
        foreach ($productList as $product) {
            $returnInfo[$product['product_id']]['number'] = $product['number'];
        }
        $finance = array('item_no' => $batchSN,
                         'pay' => abs($arg['change_amount']),
                         'finance_id' => $financeID,
                         );
        $this -> createFinanceReturnProduct($finance, $returnInfo);
        
        //修改应收款
        $financeAPI -> updateFinanceReceivable(array('amount' => $orderDetail['other']['price_must_pay']), "batch_sn = '{$batchSN}' and type = 4");
        
        //添加订单日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => $time,
                     'title' => '代收款变更金额[￥' . $arg['change_amount'] . ']',
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
	/**
     * 已签收
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function signed($batchSN, $arg=null)
    {
        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($batchSN);
        if ($isSplit) {
            $batchSN = $isSplit['batch_sn'];
        }

        $where = array('batch_sn' => $batchSN);
        $order = array_shift($this -> _db -> getOrderBatch($where));
        if ($order['pay_type'] == 'cod') {//代收款
            $data = array('status_logistic' => 4,
                          'status_pay' => 2,
                          'price_payed' => $order['price_pay'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'] - $order['price_from_return'],
                          'price_before_return' => $order['price_pay'] - $order['price_from_return'],
                          'logistic_signed_time' => time(),
                          'pay_time' => time(),
                          'lock_name' => '');
            //添加支付log记录
            $pay = $order['price_pay'] - $order['price_from_return'] - $order['price_payed'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'];
            $this -> addOrderPayLog(array('batch_sn' => $batchSN, 'pay_type' =>'cod', 'pay' => $pay));

        } else {
            $data = array('status_logistic' => 4,
                          'price_before_return' => $order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'],
                          'lock_name' => '');
        }

        if ($isSplit) {
            if (!$isSplit['allSign']) {
                $data['status_logistic'] = 6;
            }
        }
        
        $this -> _db -> updateOrderBatch($where, $data);
        $orderDetail = $this -> orderDetail($batchSN);
        //礼品卡处理
        $this -> splitOrderForgiftCard($orderDetail);
    }

	/**
     * 已拒收
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function reject($batchSN, $arg=null)
    {
        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($batchSN);
        if ($isSplit) {
            $batchSN = $isSplit['batch_sn'];
        }
        $where = array('batch_sn' => $batchSN);
        $order = array_shift($this -> _db -> getOrderBatch($where));
        if ($order['pay_type'] == 'cod') {//代收款
            $this -> delOrderPayLog($batchSN);
            $data = array('status_logistic' => 5,
                          'status_pay' => 0,
                          'price_payed' => 0,
                          'price_before_return' => 0,
                          'pay_time' => 0,
                          'lock_name' => '');
        }else{
          $data = array('status_logistic' => 5, 'lock_name' => '');
        }
        $this -> _db -> updateOrderBatch($where, $data);
        
    }
    /**
     * 客户拒收商品 退货已签收
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function returnSigned($batchSN, $arg=null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $newOrder = array_shift($this -> _db -> getOrderBatch(array('parent_batch_sn' => $batchSN, 'status' => 0)));
        $newBatchSN = $newOrder['batch_sn'];
        $oldBatchSN = $batchSN;
        if ($arg['is_check'] == 1) {//同意
            if ($newBatchSN) {
                $this -> _db -> updateOrderBatch(array('batch_sn' => $newBatchSN),
                                                 array('is_freeze' => 0,'status_logistic' => 0, 'lock_name' => ''));
            }
            
            $financeData = $this -> _finance -> getLastFinanceByItemNO(1, $oldBatchSN, true);
            if ($financeData) {
                $instockAPI = new Admin_Models_API_InStock();
                foreach ($financeData as $finance) {
                    //生成财务退款产品金额
                    $details = $instockAPI -> getDetail("b.bill_no = '{$arg['bill_no']}'");
                    foreach ($details as $detail) {
                        $returnInfo[$detail['product_id']]['number'] = $detail['number'];
                    }
                    $this -> createFinanceReturnProduct($finance, $returnInfo);
                    
                    if ($finance['status'] == 0) {
                        $this -> _finance -> updateFinance($finance['finance_id'], array('status' => 1));
                    }
                    else if ($finance['status'] == 2 && in_array($finance['way'], array(3,5))) {
                        $this -> _finance -> updateFinance($finance['finance_id'], array('check_time' => time()));
                        
                        if ($finance['way'] == 3) {
                            //添加应收款记录
                            $financeAPI = new Admin_Models_API_Finance();
                            $receiveData = array('batch_sn' => $newBatchSN,
                                                 'type' => 6,
                                                 'pay_type' => 'exchange',
                                                 'amount' => abs($finance['pay'] + $finance['point'] + $finance['account'] + $finance['gift']),
                                                 'settle_amount' => abs($finance['pay'] + $finance['point'] + $finance['account'] + $finance['gift']),
                                                 'settle_time' => time(),
                                                );
                            $financeAPI -> addFinanceReceivable($receiveData);
                        }
                    }
                }
            }
            else {
                //丢件虚拟入库的已支付订单，需要生成财务退款产品金额
                if ($order['status_logistic'] == 5 && ($order['price_payed'] + $order['account_payed'] + $order['gift_card_payed']) > 0) {
                    $instockAPI = new Admin_Models_API_InStock();
                    $instock = array_shift($instockAPI -> getMain("bill_no = '{$arg['bill_no']}' and bill_type = 13"));
                    if ($instock) {
                        $details = $instockAPI -> getDetail("b.bill_no = '{$arg['bill_no']}'");
                        foreach ($details as $detail) {
                            $returnInfo[$detail['product_id']]['number'] = $detail['number'];
                        }
                        $finance = array('finance_id' => 0,
                                         'pay' => $order['price_payed'],
                                         'account' => $order['account_payed'],
                                         'gift' => $order['gift_card_payed'],
                                         'item_no' => $batchSN,
                                         'arg' => $batchSN,
                                         );
                        $this -> createFinanceReturnProduct($finance, $returnInfo);
                    }
                }
            }
            
            //如果是货到付款/直供单/赊销的拒收单，更新退货拒收日志
            if ($order['status_logistic'] == 5 && ($order['pay_type'] == 'cod' || $order['type'] == 16 || $order['user_name'] == 'credit_channel')) {
                $this -> _db -> updateOrderReturnDate($oldBatchSN);
            }
            
            //设置商品正在退货数
            $batchGoods = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $oldBatchSN));
            if ($batchGoods) {
                foreach ($batchGoods as $k => $v) {
                    $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                    $data = array('returning_number' => 0);
                    $this -> _db -> updateOrderBatchGoods($where, $data);
                }
            }
            
            $log = array('order_sn' => $order['order_sn'],
                         'batch_sn' => $batchSN,
                         'add_time' => time(),
                         'title' => '退货已入库',
                         'admin_name' => $this -> _auth['admin_name']);
            $this -> _db -> addOrderBatchLog($log);
        } else {//拒绝
            /*
             * 新单 垃圾订单 invalid($batchSN);
             * 财务 冻结订单 -> 系统设置无效[财务不可见]
             * 老单 returning_number
             */
             $time = $order['returning_time'];
             if ($newBatchSN = $newOrder['batch_sn']) {
                $tmpOrder = $this -> orderDetail($newBatchSN);
                $priceAdjustForReturnLogistic = 0;//该变量是 退顾客运费，被物流拒绝后，需要把流转金额反给老单，但不能包含该部分金额
                $priceAdjustForReturnLogistic += abs($tmpOrder['adjust']['price_adjust_change_logistic_to']);
                $priceAdjustForReturnLogistic += abs($tmpOrder['adjust']['price_adjust_change_logistic_back']);

                 $time = $newOrder['add_time'];
                //设置新单无效
                $this -> invalid($newBatchSN);
                //删除新单微调金额
                $this -> _db -> delOrderBatchAdjust(array('batch_sn' => $newBatchSN));
             } else {
                //删除老单微调金额
                $this -> _db -> delOrderBatchAdjust(array('batch_sn' => $oldBatchSN, 'add_time' => $time));
             }
            //api 财务接口
            $finance = $this -> _finance -> getLastFinanceByItemNO(1, $oldBatchSN);
            if ($finance['status'] == 0) {
                //0:未通过其他部门审核[对财务不可见] 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效 5:系统设置无效[对财务不可见]
                $this -> _finance -> updateFinance($finance['finance_id'], array('status' => 5));
            }
            //如果有积分 或者 礼品卡 要返回到老单
            $newBatchGoods = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $newBatchSN));
            if ($newBatchGoods) {
                foreach ($newBatchGoods as $k => $v) {
                    if ($v['card_sn'] && $v['card_type'] == 'gift') {//礼券
                        $priceGift = abs($v['sale_price']);
                    } else if ($v['type'] == 4) {//积分
                        $pricePoint = abs($v['sale_price']);
                    }
                }
            }
            //恢复退货数量 积分 礼品卡
            $batchGoods = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $oldBatchSN));
            if ($batchGoods) {
                foreach ($batchGoods as $k => $v) {
                    $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                    if ($v['card_sn'] && $v['card_type'] == 'gift' && $priceGift) {//退还礼券
                        $data = array('sale_price' => $v['sale_price'] + (-$priceGift));
                    } else if ($v['type'] == 4 && $pricePoint) {//退还积分
                        $data = array('sale_price' => $v['sale_price'] + (-$pricePoint));
                    } else {
                        $data = array('returning_number' => 0, 'return_number' => $v['return_number']-$v['returning_number']);
                    }
                    $this -> _db -> updateOrderBatchGoods($where, $data);
                }
            }

            //恢复老单status_return,price_payed
            if ($newBatchSN) { //换货
                $oldOrder = $this -> orderDetail($oldBatchSN);
                $newOrder = $this -> orderDetail($newBatchSN);
                $this -> _db -> updateOrderBatch(array('batch_sn' => $oldBatchSN), array('status_return' => 0,'price_payed' => $oldOrder['order']['price_payed'] + ($newOrder['order']['price_from_return'] - $priceAdjustForReturnLogistic)));
            } else { //退货
                $this -> _db -> updateOrderBatch(array('batch_sn' => $oldBatchSN), array('status_return' => 0));
            }
            //删除退换货理由
            $reason = $this -> _db -> getOrderBatchGoodsReturn(array('batch_sn' =>$oldBatchSN, 'add_time' => $time));
            if ($reason) {
                foreach ($reason as $k => $v) {
                    $reasonArr[] = $v['id'];
                }
            }
            $this -> _db -> delOrderBatchGoodsReturn(array('batch_sn' => $oldBatchSN, 'add_time' => $time));
            $this -> _db -> delOrderBatchGoodsReturnReasonByIDArr($reasonArr);
            //添加日志
            $log = array('order_sn' => array_shift(explode('_', $oldBatchSN)),
                         'batch_sn' => $oldBatchSN,
                         'add_time' => time(),
                         'title' => '取消退换货',
                         'admin_name' => $this -> _auth['admin_name']);
            $this -> _db -> addOrderBatchLog($log);
            //更新订单
            $this -> orderDetail($oldBatchSN);
        }
    }
    /**
     * 派单
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function assign($batchSN, $arg=null)
    {
        $data = array('logistic_name' => $arg['logistic_name'],
                      'logistic_code' => $arg['logistic_code'],
                      'logistic_price' => $arg['price'],
                      'logistic_price_cod' => $arg['cod_price'],
                      'logistic_fee_service' => $arg['fee_service'],
                      'logistic_no' => $arg['logistic_no']);

        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($batchSN, null, false);

        if ($isSplit) { //拆单
            $batchSN = $isSplit['batch_sn'];
            if ($isSplit['hasAssign']) {
                $order = array_shift($this -> _db -> getOrderBatchInfo(array('batch_sn' => $batchSN)));

                $logisticNameArray = explode(',', $order['logistic_name']);
                if (!in_array($arg['logistic_name'], $logisticNameArray)) {
                    $data['logistic_name'] = $order['logistic_name'].','.$arg['logistic_name'];
                }
                $logisticCodeArray = explode(',', $order['logistic_code']);
                if (!in_array($arg['logistic_code'], $logisticCodeArray)) {
                    $data['logistic_code'] = $order['logistic_code'].','.$arg['logistic_code'];
                }

                $data['logistic_price'] = $order['logistic_price'] + $arg['price'];
                $data['logistic_price_cod'] = $order['logistic_price_cod'] + $arg['cod_price'];
                $data['logistic_fee_service'] = $order['logistic_fee_service'] + $arg['fee_service'];
            }

            unset($data['logistic_no']);
        }

        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
    }

	/**
     * 财务处理接口
     *
     * @param    array    $data
     * @return   void
     */
    public function toFinance($orderBatchSN, $callback, $arg=null)
    {
        $this -> $callback($orderBatchSN, $arg);
    }
    
    public function updateOrderBatchPayed($batchSN, $arg)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order)    return false;
        
        if ($arg['pay'] < 0) {
            $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
                                             array('price_payed' => $order['price_payed'] + $arg['pay']));
            $title .= "金额修改{$arg['pay']}&nbsp;";
        }
        if ($arg['gift'] < 0) {
            $this -> unUseCardGift($batchSN, abs($arg['gift']));
            $title .= "礼品卡修改{$arg['gift']}&nbsp;";
        }
        if ($arg['point'] < 0) {
            $this -> unPointPrice($batchSN, abs($arg['point']));
            $title .= "积分修改{$arg['point']}&nbsp;";
        }
        if ($arg['account'] < 0) {
            $this -> unAccountPrice($batchSN, abs($arg['account']));
            $title .= "账户余额修改{$arg['account']}&nbsp;";
        }
        
        $log = array('order_sn' => $order['order_sn'],
                     'batch_sn' => $order['batch_sn'],
                     'add_time' => time(),
                     'title' => '财务退款 '.$title,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        
        $this -> orderDetail($batchSN);
    }

	/**
     * 退礼券 coupon(整退)
     *
     * @param    string    $batchSN
     * @return   void
     */
    public function unUseCardCoupon($batchSN)
    {
        $product = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN,
                                                            'card_sn_is_not_null' => true,
                                                            'number>' => 0));
        if ($product) {
            $cardObj = new Shop_Models_API_Card();
            foreach ($product as $k => $v) {
                if ($v['card_type'] == 'coupon' || $v['card_type'] == 'goods') {
                    $cardObj -> unUseCard(array('card_sn' => $v['card_sn'], 'card_price' => abs($v['sale_price']), 'add_time' => $v['add_time']));
                    $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                    $data = array('number' => 0);
                    $this -> _db -> updateOrderBatchGoods($where, $data);
                }
            }
        }
        
        $this -> unUseCardGift($batchSN);
    }
    
	/**
     * 退礼品卡
     *
     * @param    string     $batchSN
     * @param    float      $money
     * @return   boolean
     */
    public function unUseCardGift($batchSN, $money = 0)
    {
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order || $order['gift_card_payed'] <= 0)  return false;
        
        $giftCardAPI = new Admin_Models_API_GiftCard();
        $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN, 'use_type' => 1));
        $giftCardLog = $giftCardAPI -> setCanReturnCard($giftCardLog['content']);
        if ($giftCardLog) {
            $giftCardAPI = new Shop_Models_API_Card();
            if ($money > 0) {
                $total = $money;
            }
            $result = false;
            $sum = 0;
            foreach ($giftCardLog as $giftCard) {
                if ($giftCard['can_return']) {
                    if ($money > 0) {
                        if ($total == 0)    break;
                        if ($total >= $giftCard['price']) {
                            $price = $giftCard['price'];
                            $total -= $giftCard['price'];
                        }
                        else {
                            $price = $total;;
                            $total = 0;
                        }
                    }
                    else {
                        $price = $giftCard['price'];
                    }
                    $card = array('card_sn' => $giftCard['card_sn'],
                                  'card_pwd' => $giftCard['card_pwd'],
                                  'card_type' => $giftCard['card_type'],
                                  'card_price' => $price,
                                  'add_time' => time(),
                                  'admin_id' => $this -> _auth['admin_id'],
                                  'admin_name' => $this -> _auth['admin_name'],
                                  'batch_sn' => $batchSN,
                                  'use_type' => $order['status_logistic'] < 3 ? 1 : 2,
                                 );
                    $giftCardAPI -> unUseCard($card);
                    $result[] = $card;
                    $sum += $price;
                }
            }
            
            $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('gift_card_payed' => $order['gift_card_payed'] - $sum));
            
            return $result;
        }
        else {
            return false;
        }
    }
	/**
     * 退积分
     *
     * @param    string    $batchSN
     * @param    float     $pricePoint
     * @return   int
     */
    public function unPointPrice($batchSN, $pricePoint = 0)
    {
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order || $order['point_payed'] <= 0)  return false;
        if ($pricePoint < 0)    return false;
        
        if ($pricePoint) {
            if ($pricePoint > $order['point_payed'])    return false;
        }
        else {
            $pricePoint = $order['point_payed'];
        }
        
        $pointAPI = new Admin_Models_DB_MemberPoint();
        $pointList = $pointAPI -> getPoint(" and A.batch_sn = '{$batchSN}'");
        if (!$pointList)    return false;
        
        $totalPoint = 0;
        foreach ($pointList as $point) {
            $totalPoint += $point['point'];
        }

        if ($totalPoint > 0)    return false;
        $totalPoint = abs($totalPoint);
        $point = floor($pricePoint * ($totalPoint / $order['point_payed']));
        
        $memberAPI = new Admin_Models_API_Member();
        $member = $memberAPI -> getMemberByUserName($order['user_name']);
        if (!$member)   return false;
        
        $tmp = array('member_id' => $member['member_id'],
                     'user_name' => $order['user_name'],
                     'accountType' => 1,
                     'accountValue' => $point,
                     'accountTotalValue' => $member['point'],
                     'batch_sn' => $batchSN,
                     'note' => '退积分'
                    );
        $memberAPI -> editAccount($member['member_id'], 'point', $tmp);
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('point_payed' => $order['point_payed'] - $pricePoint));
        
        return $point;
    }
    
    /**
     * 退账户余额
     *
     * @param    string    $batchSN
     * @param    float     $accountPoint
     * @return   boolean
     */
    public function unAccountPrice($batchSN, $priceAccount = 0)
    {
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order)    return false;
        if ($priceAccount < 0)  return false;
        
        if ($priceAccount > $order['account_payed']) {
            $accountPayed = $order['account_payed'];
            $pricePayed = $priceAccount - $order['account_payed'];
        }
        else {
            $accountPayed = $priceAccount;
        }
        
        $moneyAPI = new Admin_Models_DB_MemberMoney();
        $moneyList = $moneyAPI -> getMoney(" and A.batch_sn = '{$batchSN}'");
        if (!$moneyList)    return false;
        
        $memberAPI = new Admin_Models_API_Member();
        $member = $memberAPI -> getMemberByUserName($order['user_name']);
        if (!$member)   return false;
        
        $tmp = array('member_id' => $member['member_id'],
                     'user_name' => $order['user_name'],
                     'accountType' => 1,
                     'accountValue' => $priceAccount,
                     'accountTotalValue' => $member['money'],
                     'batch_sn' => $batchSN,
                     'note' => '退账户余额'
                    );
        $memberAPI -> editAccount($member['member_id'], 'money', $tmp);
        
        $set = array('account_payed' => $order['account_payed'] - $accountPayed);
        if ($pricePayed) {
            $set['price_payed'] = $order['price_payed'] - $pricePayed;
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
        
        return $priceAccount;
    }


	/**
	*  取消订单发送邮件
	*
	*  @param  array  $email
	*  @param  int    $batchSN
	*  @return string
	*/
	public function orderCancelEmail($email,$batchSN)
	{
		$templateValue['user_name']=$email;
		$templateValue['batchSN']=$batchSN;
		$template = new Admin_Models_API_EmailTemplate();
		$template = $template -> getEmailTemplateByName('order_cancel',$templateValue);
		$mail = new Custom_Model_Mail();
		if ($mail -> send($email, $template['title'], $template['value'])) {
			return 'error';
		} else {
			return 'setOrderCancelSucess';
		}
	}

	/**
	*  发货邮件通知
	*
	*  @param  array  $product
	*  @param  array  $order
	*  @param  string
	*/
	public function shippedOrderEmail($product, $order)
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
        $templateValue['logistic_time']=date('Y-m-d H:i:s',$order['logistic_time']);
        $templateValue['logistic_fee_service']=$order['price_logistic'];

        $tmp='';
        
        $siteurl = 'http://www.1jiankang.com';

        foreach ($product as $v)
        {
        	if($v['type']==0){
	        	$tmp.='<tr>
	    <td><a href="'.$siteurl.'/goods-'.$v['goods_id'].'.html">'.$v['goods_name'].'</a></td>
	    <td>'.$v['product_sn'].'</td>
	    <td>￥'.$v['price'].'</td>
	    <td>'.$v['number'].'</td>
	    <td>￥'.$v['price']*$v['number'].'</td>
	  </tr>';
        	}
        }
        unset($product);
        $templateValue['product']=$tmp;

	    $template = new Admin_Models_API_EmailTemplate();
	    $template = $template -> getEmailTemplateByName('deliver_notice', $templateValue);
	    $mail = new Custom_Model_Mail();
	    if ($mail -> send($order['user_name'], $template['title'], $template['value'])) {
		    return 'sendError';
	    } else {
	    	return 'sendPasswordSucess';
	    }
	}

    /**
     * 处理满意无需退货（超过40天）
     *
     * 状态，积分
     */
    public function dealCompleteOrder() {
    	$list = $this -> _db -> getCompleteOrder();
    	if(is_array($list) && count($list)){
    		$shopConfig = Zend_Registry::get('shopConfig');
            //会员api
            $member_api = new Admin_Models_API_Member();
    		foreach ($list as $order){
                //更新shop_order_batch
                $where = array('batch_sn' => $order['batch_sn']);
                $set = array('is_fav' => 1,'logistic_list' => '');
                $result = $this -> _db -> updateOrderBatch($where, $set);
                if (!isset($shopConfig['fav_point'])) {
                    $shopConfig['fav_point'] = 1;
                }
                $priceFromReturn = 0;
                if ($order['is_fav'] != -1) {
                    $priceFromReturn = $order['price_from_return'];
                }
                $point = intval(($order['price_payed'] + $priceFromReturn - $order['price_logistic']) * $shopConfig['fav_point']);
                if ($point < 0) {
                    $point = 0;
                }
                $member = $member_api -> getMemberByUserId($order['user_id']);
                $tmp = array('member_id' => $order['member_id'],
                         'user_name' => $order['user_name'],
                         'order_id' => $order['order_id'],
                         'accountType' => 1,
                         'accountValue' => $point,
                         'accountTotalValue' => $member['point'],
                         'note' => '满意不退货赠送积分'.$order['batch_sn']);
                 $member_api -> editAccount($order['member_id'], 'point', $tmp);
    		}
            return 'ok';
    	}else{
    		return 'ok';
    	}
    }

    /**
     * 取得指定条件的订单列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function fetchOrderBatch($where = null, $fields = '*',  $page = null, $pageSize = null)
    {
       if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']) + 86400;
            if ($fromDate <= $toDate) {
                $condition[] = "(b.add_time between {$fromDate} and {$toDate})";
            }
        }
        if ($where['batch_sn']) {
            $condition[] = "b.batch_sn like '{$where['batch_sn']}%'";
        }
        if ($where['pay_type']) {
            $condition[] = "b.pay_type like '{$where['pay_type']}%'";
        }
        if ($where['sub_pay_type']) {
            if ($where['sub_pay_type'] == 'call') {
                $condition[] = "a.shop_id = 2 and a.user_name like '%_call'";
            }
            else {
                $condition[] = "a.shop_id = 1";
            }
        }
        if (!is_null($where['status']) && $where['status'] !== '') {
            $condition[] = "status={$where['status']}";
        }
        if (!is_null($where['status_logistic']) && $where['status_logistic'] !== '') {
            $condition[] = "status_logistic={$where['status_logistic']}";
        }
        if (!is_null($where['status_logistic>'])) {
            $condition[] = "status_logistic>{$where['status_logistic>']}";
        }

        if (!is_null($where['status_return']) && $where['status_return'] !== '') {
            $condition[] = "status_return={$where['status_return']}";
        }
        if ($where['logistic_no']) {
            $condition[] = "logistic_no='{$where['logistic_no']}'";
        }
        if ($where['batch_sns']) {
            $condition[] = "b.batch_sn in (".implode(',', $where['batch_sns']).")";
        }
        if ($where['not_pay_type']) {
            $condition[] = "b.pay_type not in ({$where['not_pay_type']})";
        }
        if ($where['clear_pay'] !== null && $where['clear_pay'] !== '') {
            $condition[] = "clear_pay = '{$where['clear_pay']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' AND ', $condition);
        }
        $data =  $this -> _db ->fetchOrderBatch($condition,$fields, $page,$pageSize);
          if(is_array($data['list']) && count($data['list'])){
                    foreach ($data['list'] as $k => $v) {
                        $data['list'][$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                        $data['list'][$k]['status'] = $this -> status('status', $v['status']);
                        $data['list'][$k]['status_return'] = $this -> status('status_return', $v['status_return']);
                        $data['list'][$k]['status_logistic'] = $this -> status('status_logistic', $v['status_logistic']);
                        $data['list'][$k]['status_pay'] = $this -> status('status_pay', $v['status_pay']);
                        $data['list'][$k]['blance'] = round($v['price_pay'] - ($v['price_payed']+$v['price_from_return']), 2);
                        $data['list'][$k]['oinfo'] = Zend_Json::encode($data['list'][$k]);
                    }

                }
        return $data;
    }

    /**
     * 重新更新订单金额和订单商品金额，只对渠道订单的财务退款
     *
     * @param   array   $order
     * @param   float   $pay
     * @param   float   $point
     * @param   float   $account
     * @param   float   $gift
     */
    public function updateOrderAmountByFinanceReturn($order, $pay, $point, $account, $gift)
    {
        /*
        if ($order['price_order'] == $returnAmount && in_array($order['status_logistic'], array(0,1,2))) {
            $this -> _db -> updateOrderBatch(array('batch_sn' => $order['batch_sn']), array('status' => 1));

            $log = array('order_sn' => $order['batch_sn'],
                         'batch_sn' => $order['batch_sn'],
                         'add_time' => time(),
                         'title' => '财务全额退款 订单取消',
                         'data' => Zend_Json::encode($data),
                         'admin_name' => $this -> _auth['admin_name']);
            $this -> _db -> addOrderBatchLog($log);

            return true;
        }*/
        
        if ($pay > 0) {
            $type = 'pay';
            $returnAmount = $pay;
        }
        else if ($point > 0) {
            $type = 'point';
            $returnAmount = $point;
        }
        else if ($account > 0) {
            $type = 'account';
            $returnAmount = $account;
        }
        else if ($gift > 0) {
            $type = 'gift';
            $returnAmount = $gift;
        }
        
        $goodsData = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $order['batch_sn']));
        $goodsAmount = 0;
        foreach ($goodsData as $key => $goods) {
            $goodsData[$key]['amount'] = $goods['sale_price'] * ($goods['number'] - $goods['return_number']);
            $goodsAmount += $goodsData[$key]['amount'];
        }
        $totalAmount = 0;
        $realAmount = 0;
        for ($i = 0; $i < count($goodsData); $i++) {
            if ($i == count($goodsData) - 1) {
                $tempAmount = $returnAmount - $totalAmount;
            }
            else {
                $tempAmount = round($goodsData[$i]['amount'] / $goodsAmount * $returnAmount, 2);
                $totalAmount += $tempAmount;
            }
            $salePrice = $goodsData[$i]['sale_price'] - round($tempAmount / ($goodsData[$i]['number'] - $goodsData[$i]['return_number']), 2);

            $set = array('sale_price' => $salePrice);
            $this -> _db -> updateOrderBatchGoods(array('order_batch_goods_id' => $goodsData[$i]['order_batch_goods_id']), $set);

            $realAmount += $salePrice * ($goodsData[$i]['number'] - $goodsData[$i]['return_number']);
        }

        $priceOrder = $order['price_order'] - $returnAmount;
        $priceGoods = $order['price_goods'] - $returnAmount;
        $set = array('price_order' => $priceOrder,
                     'price_goods' => $priceGoods,
                     'price_pay' => $priceOrder,
                    );
        if ($type == 'pay') {
            if ($order['price_payed'] > 0) {
                $set['price_payed'] = $order['price_payed'] - $returnAmount;
            }
        }
        else if ($type == 'point') {
            if ($order['point_payed'] > 0) {
                $this -> unPointPrice($order['batch_sn'], $returnAmount);
            }
        }
        else if ($type == 'account') {
            if ($order['account_payed'] > 0) {
                $this -> unAccountPrice($order['batch_sn'], $returnAmount);
            }
        }
        else if ($type == 'gift') {
            if ($order['gift_card_payed'] > 0) {
                $this -> unUseCardGift($order['batch_sn'], $returnAmount);
            }
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $order['batch_sn']), $set);
        
        if (abs($realAmount - $priceGoods) > 0 ) {
            $shopAPI = new Admin_Models_API_Shop();
            $shopAPI -> updateOrderBatch2($order['batch_sn'], $priceGoods - $realAmount, '优惠补偿退款');
        }
        
        $data['price_order'] = $order['price_order'];
        $log = array('order_sn' => $order['batch_sn'],
                     'batch_sn' => $order['batch_sn'],
                     'add_time' => time(),
                     'title' => '财务退款 金额修改-'.$returnAmount,
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }

    /**
     * 获得订单是否包含提货券
     *
     * @param    string/array $data
     *
     * @return   void
     */
    public function getOrderGoodsCard($data)
    {
        if (is_array($data)) {
            $goodsData = $data;
        }
        else {
            $goodsData = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $data));
        }
        if ($goodsData) {
            foreach ($goodsData as $goods) {
                if ($goods['card_type'] == 'goods')  return $goods['card_sn'];
            }
        }
        return false;
    }

	/**
	 * 修改赠送人
	 *
	 */
    public function giftbywho($order_sn, $val) {
    	$order_sn = trim($order_sn); if($order_sn == ''){return '参数错误';}
    	$val = trim($val); if($val == ''){return '赠送人必须';}
    	$this -> _db -> updateOrder(array('order_sn'=>$order_sn), array('giftbywho' => $val));
    	return 'ok';
    }

    /**
     * 申请渠道销售出库单出库
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function virtualOut($batchSN)
    {
        $time = time();
        $fields = "a.batch_sn,a.type,a.note_print,a.note_logistic,a.logistic_name,a.logistic_price,a.pay_type,a.price_order,a.status,b.shop_id,b.user_name,b.distribution_type,b.lid";
	    $details = array();
		$r = array_shift($this -> _db -> getOrderBatchInfo(array('batch_sn' => $batchSN), $fields));
        
		$bill_no = $batchSN;
		$remark = $r['note_logistic'] ? $r['note_logistic'].',' : '';
		$where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0, '(number-return_number)>' => 0);
		$product = $this -> _db -> getOrderBatchGoods($where);
		if ($product) {
			foreach($product as $k => $v) {
				if ($details[$v['product_id']]) {
					$details[$v['product_id']]['number'] += $v['number'] - $v['return_number'];
					$details[$v['product_id']]['shop_price'] = $v['price'];
				} else {
					$details[$v['product_id']] = array ('product_id' => $v['product_id'],
														'number' => $v['number'] - $v['return_number'],
														'shop_price' => $v['price'],
														'status_id' => 2);
				}
				
				$this -> _db -> updateOrderBatchGoods(array('order_batch_goods_id' => $v['order_batch_goods_id']), array('finance_price' => $v['eq_price']));
			}
		}
		if ($r['status'] == 4) {
		    $logicArea = Custom_Model_Stock_Base::getDistributionArea($r['user_name']);
		}
		else if ($r['lid'] == 2) {
		    $logicArea = 2;
		}
		if (!$logicArea)    die('error');
		
		if ($r['status'] == 4) {
    		if ($r['distribution_type']) {
    		    $bill_type = 19;
    		    $stockConfig = new Custom_Model_Stock_Config();
    		    $remark = $stockConfig -> _logicArea[Custom_Model_Stock_Base::getDistributionArea($r['user_name'])];
    		}
    		else {
    		    $bill_type = 15;
    		}
    	}
    	else {
    	    $bill_type = 1;
    	}
        $rowOutStock = array ('lid' => $logicArea,
                              'bill_no' => $bill_no,
                              'bill_type' => $bill_type,
                              'bill_status' => 5,
                              'remark' => $remark,
                              'add_time' => $time,
                              'finish_time' => $time,
                              'admin_name' => $this -> _auth['admin_name']
                             );
        $outStock = new Admin_Models_API_OutStock();
        $outstockID = $outStock -> insertApi($rowOutStock, $details, $logicArea, true);
        if ($outstockID) {
           //生成出库单关系记录
           $transportAPI  = new Admin_Models_DB_Transport();
           $row = array('bill_no' => $bill_no,
                        'transport_id' => 0,
                        'outstock_id' => $outstockID,
                       );
           $transportAPI -> insertRelationOrder($row);
            
            if ($r['status'] == 4) {
    			//插入代销结款数据
    			$consign_result_params = array();
    			if (count($product) > 0) {
    				$consign_result_db = new Admin_Models_API_ConsignResult();
    				foreach ($product as $info) {
    					$consign_result_params = array(
    						'warehouse_id'      => $logicArea,
    						'product_id'        => $info['product_id'],
    						'number'            => $info['number'],
    						'created_month'     => date('Ym'),
    						'warehouse_product' => $logicArea.'_'.$info['product_id'],
    					);
    					$result = $consign_result_db->add($consign_result_params);
    				}
    			}
    			
    			if ($r['distribution_type']) {  //生成分销刷单入库单
    			    $details = array();
        		    $outstockDetail = $outStock -> getDetail("a.outstock_id = '{$outstockID}'");
        		    $row = array ('lid' => 1,
        			              'item_no' => $bill_no,
        			              'bill_no' => Custom_Model_CreateSn::createSn(),
        	                      'bill_type' => 19,
        	                      'bill_status' => 3,
        				          'remark' => $remark,
        	                      'add_time' => time(),
        	                      'admin_name' => $this -> _auth['admin_name'],
        	                     );
        	        foreach ($outstockDetail as $v) {
        				$details[] = array('product_id' => $v['product_id'],
        				                   'batch_id' => $v['batch_id'],
        		                           'plan_number' => $v['number'],
        		                           'shop_price' => $v['cost'],
        		                           'status_id' => 6,
        		                          );
        	        }
        	        $inStock = new Admin_Models_API_InStock();
        	    	$inStock -> insertApi($row, $details, 1, true);
    			}
			}
			
			//设置财务金额
    	    $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('balance_amount' => $r['price_order']));

            return true;
        }
    }

    /**
     * 订单出库前再次检验库存
     *
     * @param   string      $batchSN
     * @return  boolean
     */
    public function checkOut($batchSN, $onlyCheck = 1)
    {
        $where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0, '(number-return_number)>' => 0);
		$product = $this -> _db -> getOrderBatchGoods($where);
		if (!$product)  return false;
		
		$order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
		
		$stockAPI = new Admin_Models_API_Stock();
		$stockAPI -> setLogicArea($order['lid']);
		
		if (!$this -> _replenishmentAPI) {
		    $this -> _replenishmentAPI = new Admin_Models_API_Replenishment();
		}
		
		$is_error = 0;
		foreach($product as $k => $v) {
		    if ($onlyCheck == 1) {
		        //if (!$stockAPI -> checkPrepareProductStock($v['product_id'], $v['number'] - $v['return_number'], false)) {
		        //    return false;
		        //}
		        $productStock = $stockAPI -> getProductStock(array('product_id' => $v['product_id']));
		        if ($productStock['able_number'] - $v['number'] + $v['return_number'] < 0) {
		            return false;
		        }
		    } else {
		        if (!$stockAPI -> checkPrepareProductStock($v['product_id'], $v['number'] - $v['return_number'])) {
		            $this -> _replenishmentAPI -> applyReplenish($v['order_batch_id'], $v['product_id'], $v['number'], 2);
		            $productStock = $stockAPI -> getProductStock(array('product_id' => $v['product_id']));
		            if ($productStock['able_number'] - $v['number'] + $v['return_number'] < 0) {
		                $is_error = 1;
		            }
		        } else {
		            $this -> _replenishmentAPI -> cancelProductReplenish($v['order_batch_id'], $v['product_id'], 2);
		        }
		    }
		}

		if ($is_error) {
			return false;
		}
		return true;
    }

    /**
     * 释放销售产品占用库存
     *
     * @param    string     $batchSN
     * @return   boolean
     */
    function releaseSaleOutStock($batchSN)
    {
        $datas = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'number>' => 0));
        if (!$datas) return false;
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $stockAPI = new Admin_Models_API_Stock();
        if ($order['status'] == 4) {
            $logicArea = Custom_Model_Stock_Base::getDistributionArea($order['user_name']);
        }
        else {
            $logicArea = $order['lid'];
        }
        $stockAPI -> setLogicArea($logicArea);
        foreach ($datas as $data) {
            if (!$data['product_id'])  continue;

            $stockAPI -> releaseSaleProductStock($data['product_id'], $data['number']);
        }
        return true;
    }

	/**
	*
	*添加退货理由
	*/
	public function addreturnres($res){
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$data = Custom_Model_Filter::filterArray(array('res'=>$res), $filterChain);
		$reasons=$this->_db->addreason($data);
			if($reasons==='isExists'){
				return $reasons;
			}else{
				return $this->_db->getReason(array('reason_id'=>$reasons));
			}
	}
    /**
     * 退换货理由列表
     *
     * @param   array   $where
     * @return  array
     */
    public function getReason($where = null)
    {
        return $this -> _db -> getReason($where);
    }
	 /**
     * 取得退货订单列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
	 public function getreturnorder($where,$page=1){
		$data=$this->_db->getreturnorder($where,$page);
		$fileds='t1.order_batch_goods_id as batch_id,t1.product_sn,t1.batch_sn,t1.goods_name,t1.goods_style';
		if(is_array($data)&&count($data)>0){
			foreach($data['details'] as $k=>$v){
				$inBatchSN[]=$v['batch_sn'];
				$data['details'][$k]['return_time']=date("Y-m-d H:i",$v['return_time']);
			}
			$data['product'] = $this -> _db -> getOrderBatchGoodsInBatchSN($inBatchSN,$fileds);
		}
			return $data;
	 }

	 /**
     * 判断是否已配货
     *
     * @param    string      $bill_no
     * @return   void
     */
	public function checkPrepared($bill_no)
	{
	    $transportAPI = new Admin_Models_API_Transport();
	    $isSplit = $transportAPI -> isSplitOrder($bill_no);
	    if ($isSplit) {
	        $where = "bill_no like '{$bill_no}-%'";
	    }
	    else {
	        $isMerge = $transportAPI -> isMergeOrder($bill_no);
	        if ($isMerge) {
	            $where = "bill_no like '%{$bill_no}%'";
	        }
	        else {
	            $where = "bill_no = '{$bill_no}'";
	        }
	    }

	    return $transportAPI -> get($where);
	}

	public function getOrderBatchInfoAndGoodsInfosByBatchId($order_batch_id)
	{
		if (false === ($order_info = $this->_db->getOrderBatchInfoByOrderBatchId($order_batch_id))) {
			$this->_error = $this->_db->get_error();
			return false;
		}


		$order_info['goods'] = $this->_db->getOrderBatchGoodsByOrderBatchId($order_batch_id);

		return $order_info;

	}
	
	/**
     * 更改订单已支付金额
     *
     * @param   string      $batchSN
     * @param   float       $amount
     * @param   string      $type
     * @return  void
     */
    public function updateOrderPayed($batchSN, $amount, $type)
    {
        $this -> _db -> updateOrderPayed($batchSN, $amount, $type);
    }
    
    /**
     * 退回礼品卡
     *
     * @param   string      $batchSN
     * @param   int         $logID
     * @return  void
     */
    public function returnGiftCard($batchSN, $logID)
    {
        $giftCardAPI = new Admin_Models_API_GiftCard();
        $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN, 'use_type' => 1));
        $giftCardLog = $giftCardAPI -> setCanReturnCard($giftCardLog['content']);
        if (!$giftCardLog) {
           return false;
        }
        
        foreach ($giftCardLog as $temp) {
            if ($temp['log_id'] == $logID && $temp['can_return']) {
                $giftCard = $temp;
                break;
            }
        }
        if (!$giftCard) {
            return false;
        }
        
        $cardAPI = new Shop_Models_DB_Card();
        $card = array('card_type' => $giftCard['card_type'],
                      'card_price' => $giftCard['price'],
                      'card_sn' => $giftCard['card_sn'],
                      'batch_sn' => $giftCard['batch_sn'],
                      'admin_id' => $this -> _auth['admin_id'],
                      'admin_name' => $this -> _auth['admin_name'],
                      'add_time' => time(),
                      'use_type' => 1,
                     );
        if (!$cardAPI -> unUseGift($card)) {
            return false;
        }
        
        $this -> _db -> updateOrderPayed($batchSN, $giftCard['price'] * -1, 'gift_card');
        
        return true;
    }
    
    /**
     * 获取订单礼品卡
     *
     * @param   array   $productData
     * @return  void
     */
    public function setOrderGiftCard(&$productData)
    {
        if (!$productData)  return false;
        
        $giftCardAPI = new Admin_Models_API_GiftCard();
        
        foreach ($productData as $data) {
            $data['order_batch_goods_id'] > 0 && $idArray[] = $data['order_batch_goods_id'];
        }
        
        $this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
        $datas = $giftCardAPI -> getCardlist(array('order_batch_goods_id' => $idArray, 'status' => array(0,1,2)));
        $datas = $datas['content'];
        if (!$datas)    return false;
        
        foreach ($datas as $data) {
            $cardInfo[$data['order_batch_goods_id']][] = $data;
        }
        
        foreach ($productData as $index => $data) {
            $cardInfo[$data['order_batch_goods_id']] && $productData[$index]['gift_card'] = $cardInfo[$data['order_batch_goods_id']];
        }
    }
    
 
    
    /**
     * 发货后包含礼品卡的订单增加抵扣应收款
     *
     * @param   array   $orderDetail
     * @return  boolean
     */
    public function addFinanceReceivableForPreGiftCard($orderDetail)
    {
        $hasGiftCard = false;
        $onlyGiftCard = true;
        $productList = $orderDetail['product_all'];
        foreach ($productList as $product) {
            if (!$product['product_id'])    continue;
            if ($product['is_gift_card']) {
                $hasGiftCard = true;
            }
            else {
                $onlyGiftCard = false;
            }
        }
        
        if (!$hasGiftCard || $onlyGiftCard) return false;
        
        $order = $orderDetail['order'];
        
        $giftCardPrice = 0;
        foreach ($productList as $product) {
            if ($product['is_gift_card']) {
                $giftCardPrice += $product['sale_price'] * ($product['number'] - $product['return_number']);
            }
        }
        $pricePayed = $order['price_pay'] - $giftCardPrice;
        $giftCardPayed = $order['price_goods'] + $order['price_logistic'] + $order['price_adjust'] + $order['price_from_return'] - $pricePayed;
        
        $financeAPI = new Admin_Models_API_Finance();
        $receiveData = array('batch_sn' => $order['batch_sn'],
                             'type' => 5,
                             'pay_type' => 'gift',
                             'amount' => $giftCardPayed,
                            );
        $financeAPI -> addFinanceReceivable($receiveData);
        
        return true;
    }
    
    /**
     * 签收后包含礼品卡的订单拆分
     *
     * @param   array   $orderDetail
     * @return  boolean
     */
    public function splitOrderForgiftCard($orderDetail)
    {
        $hasGiftCard = false;
        $onlyGiftCard = true;
        $productList = $orderDetail['product_all'];
        foreach ($productList as $product) {
            if (!$product['product_id'] && $product['type'] != 5)   continue;
            if ($product['is_gift_card']) {
                $hasGiftCard = true;
            }
            else {
                $onlyGiftCard = false;
            }
        }

        if (!$hasGiftCard || $onlyGiftCard) return false;
        
        $order = $orderDetail['order'];
        
        $giftCardPrice = 0;
        foreach ($productList as $product) {
            if ($product['is_gift_card']) {
                $giftCardPrice += $product['sale_price'] * ($product['number'] - $product['return_number']);
                $orderBatchGoodsIDArray[] = $product['order_batch_goods_id'];
            }
        }
        
        //新增 批次
        $temp = array_shift($this -> _db -> getOrderBatch(array('order_sn' => $order['order_sn']), 'add_time desc'));
        $temp = explode('_', $temp['batch_sn']);
        $newBatchSN = $order['order_sn'].'_'.(intval($temp['1']) + 1);
        $data = array('order_id' => $order['order_id'],
                      'order_sn' => $order['order_sn'],
                      'batch_sn' => $newBatchSN,
                      'parent_batch_sn' => $order['batch_sn'],
                      'type' => $order['type'],
                      'add_time' => strtotime($order['add_time']),
                      'is_visit' => $order['is_visit'],
                      'is_fav' => $order['is_fav'],
                      'is_send' => $order['is_send'],
                      'status' => 5,
                      'status_logistic' => 4,
                      'status_pay' => 2,
                      'price_order' => $giftCardPrice,
                      'price_goods' => $giftCardPrice,
                      'price_pay' => $giftCardPrice,
                      'price_payed' => $giftCardPrice,
                      'balance_amount' => $giftCardPrice,
                      'pay_type' => $order['pay_type'],
                      'pay_name' => $order['pay_name'],
                      'pay_time' => $order['pay_time'],
                      'logistic_name' => $order['logistic_name'],
                      'logistic_code' => $order['logistic_code'],
                      'logistic_no' => $order['logistic_no'],
                      'logistic_time' => $order['logistic_time'],
                      'logistic_signed_time' => $order['logistic_signed_time'],
                      'addr_consignee' => $order['addr_consignee'],
                      'addr_province_id' => $order['addr_province_id'],
                      'addr_city_id' => $order['addr_city_id'],
                      'addr_area_id' => $order['addr_area_id'],
                      'addr_province' => $order['addr_province'],
                      'addr_city' => $order['addr_city'],
                      'addr_area' => $order['addr_area'],
                      'addr_address' => $order['addr_address'],
                      'addr_zip' => $order['addr_zip'],
                      'addr_tel' => $order['addr_tel'],
                      'addr_mobile' => $order['addr_mobile'],
                      'addr_email' => $order['addr_email'],
                      'addr_fax' => $order['addr_fax'],
                      'sms_no' => $order['sms_no'],
                     );
        $orderBatchID = $this -> _db -> addOrderBatch($data);
        
        //设置 最新批次号
        $this -> _db -> updateOrder(array('order_sn' => $order['order_sn']), array('batch_sn' => $newBatchSN));
        
        //新增礼品卡订单商品
        foreach ($productList as $product) {
            if (!$product['is_gift_card'])  continue;
            
            $data = array('order_id' => $order['order_id'],
                          'order_batch_id' => $orderBatchID,
                          'order_sn' => $order['order_sn'],
                          'batch_sn' => $newBatchSN,
                          'type' => $product['type'],
                          'is_send' => $product['is_send'],
                          'add_time' => strtotime($order['add_time']),
                          'product_id' => $product['product_id'],
                          'product_sn' => $product['product_sn'],
                          'goods_id' => $product['goods_id'],
                          'goods_name' => $product['goods_name'],
                          'cat_id' => $product['cat_id'],
                          'cat_name' => $product['cat_name'],
                          'weight' => $product['weight'],
                          'length' => $product['length'],
                          'width' => $product['width'],
                          'height' => $product['height'],
                          'goods_style' => $product['goods_style'],
                          'sale_price' => $product['sale_price'],
                          'eq_price' => $product['sale_price'],
                          'number' => $product['number'] - $product['return_number'],
                          'remark' => $product['remark'],
                         );
            $orderBatchGoodsIDInfo[] = array('oldOrderBatchGoodsID' => $product['order_batch_goods_id'],
                                             'newOrderBatchGoodsID' => $this -> _db -> addOrderBatchGoods($data),
                                             'is_vitual' => $product['is_vitual'],
                                            );
        }
        
        //运输单和出库单合单
        $transportAPI  = new Admin_Models_DB_Transport();
        $outstockAPI = new Admin_Models_DB_OutStock();
        $relationData = array_shift($transportAPI -> getRelationOrder("bill_no = '{$order['batch_sn']}'"));
        if ($relationData) {
            $transport = array_shift($transportAPI -> get("tid = '{$relationData['transport_id']}'", 'tid,bill_no'));
            $outstock = array_shift($outstockAPI -> get("b.outstock_id = '{$relationData['outstock_id']}'", 'b.outstock_id,bill_no'));
            if ($transport && $outstock) {
                $data = array('bill_no' => $newBatchSN,
                              'transport_id' => $relationData['transport_id'],
                              'outstock_id' => $relationData['outstock_id'],
                             );
                $transportAPI -> insertRelationOrder($data);
                $transportAPI -> update(array('bill_no' => $transport['bill_no'].",{$newBatchSN}"), "tid = '{$transport['tid']}'");
                $outstockAPI -> update(array('bill_no' => $outstock['bill_no'].",{$newBatchSN}"), "outstock_id = '{$outstock['outstock_id']}'");
            }
        }
        
        //修改原订单支付金额，礼品卡抵扣
        $pricePayed = $order['price_payed'] - $giftCardPrice;
        $giftCardPayed = $order['price_goods'] + $order['price_logistic'] + $order['price_adjust'] + $order['price_from_return'] - $pricePayed;
        
        $giftCardAPI = new Admin_Models_API_GiftCard();
        $cardAPI = new Shop_Models_DB_Card();
        $giftCardList = array_shift($giftCardAPI -> getCardlist(array('order_batch_goods_id' => $orderBatchGoodsIDArray, 'status' => 0)));
        if ($giftCardList) {
            $realGiftCardPayed = 0;
            foreach ($giftCardList as $card) {
                if ($giftCardPayed > 0) {
                    if ($card['card_real_price'] >= $giftCardPayed) {
                        $cardPrice = $giftCardPayed;
                        $giftCardPayed = 0;
                    }
                    else {
                        $cardPrice = $card['card_real_price'];
                        $giftCardPayed -= $cardPirce;
                    }
                }
                else {
                    break;
                }
                
                $card = array('card_type' => $card['card_type'],
                              'card_price' => $cardPrice,
                              'card_sn' => $card['card_sn'],
                              'card_pwd' => $card['card_pwd'],
                              'add_time' => time(),
                              'admin_id' => $this -> _auth['admin_id'] ? $this -> _auth['admin_id'] : 0,
                              'admin_name' => $this -> _auth['admin_name'] ? $this -> _auth['admin_name'] : 'system',
                              'batch_sn' => $order['batch_sn'],
                              'use_type' => 1,
                             );
                if ($cardAPI -> useGift($card)) {
                    $card['use_type'] = 2;
                    if ($cardAPI -> useGift($card)) {
                        $realGiftCardPayed += $cardPrice;
                    }
                }
            }
        }
        $set = array('price_payed' => $pricePayed, 'gift_card_payed' => $realGiftCardPayed, 'price_before_return' => $order['price_order']);
        if ($pricePayed <= 0) {
            $set['pay_type'] = 'no_pay';
            $set['pay_name'] = '无需支付';
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $order['batch_sn']), $set);
        //删除原订单的礼品卡订单商品
        $this -> _db -> delOrderBatchGoods("order_batch_goods_id in (".implode(',', $orderBatchGoodsIDArray).")");
        $this -> orderDetail($order['batch_sn']);
        return true;
    }
    
    /**
     * 订单正在退款记录与总金额
     *
     * @param   string  $batchSN
     * @return  float
     */
    private function _financeReturningDetail($batchSN)
    {
        $financeList = $this -> _finance -> getFinance(array('item_no' => $batchSN, 'status' => array(0,1), 'item' => 1));
        if (!$financeList)  return false;
        
        $amount = 0;
        if ($financeList) {
            foreach ($financeList as $finance) {
                $amount += abs($finance['pay'] + $finance['point'] + $finance['account'] + $finance['gift']);
            }
        }
        
        return array('data' => $financeList, 'amount' => $amount);
    }
    
    /**
     * 生成财务退货产品金额
     *
     * @param   array  $finance
     * @param   array  $productInfo
     * @return  boolean
     */
    public function createFinanceReturnProduct($finance, $productInfo) 
    {
        $batchSN = $finance['item_no'];
        $amount = abs($finance['pay'] + $finance['account'] + $finance['gift']);
        
        if ($amount == 0)   return false;
        
        $total = 0;
        foreach ($productInfo as $productID => $info) {
            $product = array_shift($this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'product_id' => $productID)));
            $productInfo[$productID]['price'] = $product['eq_price'];
            $total += $product['eq_price'] * $info['number'];
        }
        
        foreach ($productInfo as $productID => $info) {
            if ($total > 0) {
                $productInfo[$productID]['amount'] = round($info['price'] * $info['number'] / $total * $amount, 2);
            }
            else {
                $productInfo[$productID]['amount'] = round($amount / count($productInfo), 2);
            }
            
            if ($productInfo[$productID]['amount'] > 0) {
                $data = array('finance_id' => $finance['finance_id'],
                              'product_id' => $productID,
                              'amount' => $productInfo[$productID]['amount'],
                              'type' => $finance['type'] ? $finance['type'] : 1,
                              'add_time' => time(),
                             );
                $finance['arg'] && $data['arg'] = $finance['arg'];
                $this -> _db -> addFinanceReturnProduct($data);
            }
        }
        
        return true;
    }
    
	public function getError()
	{
		return $this->_error;
	}
	
	
	public function updateOrderDeclare($type , $sn)
	{
		if($type){
			$tmp = array('status_declare'=>$type);
			return $this -> _db -> updateOrderBatch(array('batch_sn'=>$sn),$tmp);
		}else{
			return false;
		}
	}
	
	
	public function getProductByBatch($batchSns)
	{
	    if(is_array($batchSns)){
	        $where['batch_sn_in'] = " '".implode("','", $batchSns)."' ";
	    }elseif($batchSns){
	        $where['batch_sn'] = $batchSns;
	    }
	    $tmp = $this -> _db -> getOrderBatchGoods($where);
	    $datas = array();
	    if($tmp){
    	    foreach($tmp as $k => $v){
    	        $datas[$v['batch_sn']][] = $v; 
    	    }
    	    return $datas;
	    }else{
	        return false;
	    }
	}
	
	function send_union_pay($batchSN)
	{
		$order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
	    $env = 'test';
	    if ($env == 'test') {
	    	// test
	    	$host = "111.203.189.205";
	    	$port = 6001;
	    	$MerID = '0P3';
	    	$MerNo = '002172785100010';
	    	$PayNo = '0P3000000000000';
	    } else {
	    	// product
	    	$host = "";
	    	$port = 0;
	    	$MerID = 'BZZ';
	    	$MerNo = '';
	    	$PayNo = 'BZZ000000000000';
	    }
	    $traceno = time()-strtotime(date('Y-m-d')).rand(1,9);
	    $data = array (
	    		'msgtype' => '0100', // 信息类型 定值 非空 定值：0100
	    		'procode' => '350000', // 处理码 定值 非空 定值：350000
	    		'MerID' => $MerID, // 商户ID String(3) 非空 （银联网络分配3位定值）（注意与MerNo不同）
	    		                   // 测试环境（定值）：0P3
	    		                   // 生产环境（定值）：BZZ
	    		'OrderNo' => $order['batch_sn'], // 客户系统流水号 String(20) 非空 客户系统生成
	    		'sysno' => '000000000000', // 银联支付单号 String(30) 非空 定值：000000000000（12位）
	    		'cardno' => '0000000000000000000', // 卡号 String(19) 非空 定值：0000000000000000000（19位）
	    		'traceno' => sprintf ( "%06d",$traceno), // 银联需求流水号 String(6) 非空 客户随机生成（6位长度，保证当天不唯一即可）
	    		'termid' => '00904044', // 终端号 String(8) 非空 银联分配给客户（定值）
	    		'CurrCode' => '142', // 海关货币代码 String(3) 非空 海关货币代码(一般RMB为：142)
	    		
	    		'CurrCodeCIQ' => '156', // 国检货币代码 String(3) 非空 国检货币代码（一般RMB为：156）
	    		'PayAmount' => sprintf ( "%012d", $order['price_order']*100 ), // 交易金额 String(12) 非空
	    		                                        // 格式：12位字符,最后2位为小数,形式为000000100000,代表1千元
	    		'MerNo' => $MerNo, // 商户号 String(15) 非空 银联网络分配给客户的商户号（15位定值）
	    		'RealName' => $order['addr_consignee'], // 持卡人真实姓名 String(16) 非空
	    		'CredentialsType' => sprintf ( "%02d",$order['credentials_type']), // 证件类型 String(2) 非空 01：身份证；
	    		                         // 02：军官证；
	    		                         // 03：护照；
	    		                         // 04: 回乡证；
	    		                         // 05: 台胞证；
	    		                         // 06: 警官证；
	    		                         // 07: 士兵证；
	    		'CredentialsNo' => $order['credentials_no'], // 证件号码 String (18) 非空
	    		'ShoppingDate' => date('Ymd',$order['pay_time']), // 订单交易（支付）日期 String(8) 非空 YYYYMMDD
	    		'InternetDomainName' => 'www.1jiankang.com', // 电商平台互联网域名 String(512) 非空 电商平台的互联网域名。
	    		                            // 以海关发布的对接电商平台域名列表为准。
	    		'ECommerceCode' => '3120560038', // 电商平台编号（海关） String(20) 非空
	    		                       // 在海关通关系统中备案的平台企业编号或代码
	    		'ECommerceName' => '上海众馥实业有限公司', // 电商平台名称(海关) String(48) 非空 在海关通关系统中备案的平台企业名称
	    		'MerCode' => '3110966871', // 交易商家编号（海关） String(20) 非空
	    		                 // 在海关通关系统中备案的平台企业下入驻商户的编号或代码。如果没有则填ECommerceCode电商平台编号
	    		'MerName' => '国药（上海）电子商务有限公司', // 交易商家名称（海关） String(48) 非空
	    		                 // 在海关通关系统中备案的平台企业下入驻商户的编号或代码。如果没有则填ECommerceName电商平台名称
	    		'CbepComCode' => '3120560038', // 跨境电商平台企业备案号（国检） String(20) 非空
	    		                     // 跨境电商平台企业在国检关口做企业备案，审核通过后的企业备案号
	    		'CbepComName' => '上海众馥实业有限公司', // 跨境电商平台企业备案名称（国检） String(48) 非空
	    		                     // 跨境电商平台企业在国检关口做企业备案，审核通过后的企业备案名称
	    		'CbepMerCode' => '3110966871', // 跨境电商交易商家备案号（国检） String(20) 非空
	    		                     // 入住跨境电商平台的商户在国检关口做企业备案，审核通过后的企业备案号，如果没有则填CbepComCode跨境电商平台企业备案号（国检）
	    		'CbepMerName' => '国药（上海）电子商务有限公司', // 跨境电商交易商家备案名称（国检） String(48) 非空
	    		                     // 入住跨境电商平台的商户在国检关口做企业备案，审核通过后的企业备案名称，如果没有则填CbepComNam跨境电商平台企业备案名称（国检）
	    		'GoodsAmount' => sprintf ( "%012d", $order['price_goods']*100 ), // 货款 String(12) 非空
	    		                     // 格式：12位字符,最后2位为小数,形式为000000100000,代表1千元
	    		'TaxAmount' => sprintf ( "%012d", $order['tax']*100 ), // 税款 String(12) 非空
	    		                   // 格式：12位字符,最后2位为小数,形式为000000100000,代表1千元
	    		'Freight' => sprintf ( "%012d", $order['price_logistic']*100 ), // 运费 String(12) 非空
	    		                 // 格式：12位字符,最后2位为小数,形式为000000100000,代表1千元
	    		'InsuredFee' => sprintf ( "%012d", $order['']*100 ), // 保费 String(12) 非空
	    		                    // 格式：12位字符,最后2位为小数,形式为000000100000,代表1千元
	    		'Mobile' => '', // 银行预留手机号码 String(11) 可空
	    		'Email' => '', // 持卡人常用邮箱 String(32) 可空
	    		'BizTypeCode' => '2', // 业务类型 String(2) 非空 直购进口：1
	    		                     // 网购保税进口：2
	    		'OriOrderNo' => $order['batch_sn'], // 商户自己系统原订单号 String(20) 非空 商户自己系统的原订单号
	    		'PayNo' => $PayNo, // 校验值 String(20) 非空 校验值（我司会做校验）：
	    		                   // 生产环境（定值）：
	    		                   // “BZZ000000000000”
	    		                   // 测试环境（定值）：
	    		                   // “0P3000000000000”
	    		'PaymentType' => 0, // 款项类型 String(1) 非空 0：全款
	    		                    // 1：货款
	    		                    // 2：运费
	    		'IEType' => 'E', // 进出口类型 String(1) 非空 I：进口
	    		                 // E：出口
	    		'OrganType' => '1', // 机构类型 String(1) 非空 0：海关、国检都推送
	    		                   // 1：只推送海关
	    		                   // 2：只推送国检
	    		'CustomsCode' => '100017', // 海关平台代码 String(6) 非空 详见CustomsCode参数说明
	    		'PortCode' => '2238', // 口岸代码 String(4) 非空 详见海关关口代码表
	    		'CIQOrgCode' => '311500', // 检验检疫机构代码 String(8) 非空 详见国检机构代码表
	    		'BusinessType' => 'B2C', // 业务类型 String(10) 非空 B2B2C或B2C
	    		'CreTime' => date('YmdHis',strtotime($order['add_time'])), // 订单创建时间 String(14) 非空 YYYYMMDDHHmmss
	    		'GetResultTime' => date('YmdHis',strtotime($order['pay_time'])),  // 交易成功时间 String(14) 可空 YYYYMMDDHHmmss
	    );
	    
	    $xml_model = new Custom_Model_Xml ();
	    //$xml_data = $this->arrayToXml ( $data);
	    $xml_data = $xml_model->array2xml($data,'BODY');
	    
	    $params = '<?xml version="1.0" encoding="gbk"?>
	    <PACKAGE>
	    ' . $xml_data . '
	    </PACKAGE>';
	    
	    $params = strlen($params).$params;
	    $base_len = 6;
	    
	    $socket = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP ) or die ( "Could not create    socket\n" ); // 创建一个Socket
	    
	    $connection = @socket_connect ( $socket, $host, $port ) or die ( "Could not connet server\n" ); // 连接
	    
	    socket_write ( $socket, $params ) or die ( "Write failed\n" ); // 数据传送
	    $ext_len = socket_read ( $socket, $base_len );
	    $output = socket_read ( $socket, $base_len + $ext_len );
	    socket_close ( $socket );
	    
	    $data = $xml_model->xml2array($output);
	    var_dump($data);echo $params;die();
	    //
	    $set = array();
	    $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
	} 
	function arrayToXml($arr){
	    $xml = "<BODY>";
	    foreach ($arr as $key=>$val){
	        if(is_array($val)){
	            $xml.="<".$key.">".arrayToXml($val)."</".$key.">";
	        }else{
	            $xml.="<".$key.">".$val."</".$key.">";
	        }
	    }
	    $xml.="</BODY>";
	    return $xml;
	}
	
}
