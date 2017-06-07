<?php
class Admin_OrderController extends Zend_Controller_Action
{
    private $_api=null;
    private $_lid;

	public function init(){
		$this -> _api = new Admin_Models_API_Order();
        $this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
        $this -> _finance = new Admin_Models_API_Finance();
        $this -> _lid = $this -> _auth['lid'];
        $this -> view -> lid = $this -> _lid;
	}
    /**
     * 取指定条件的地区json数据
     *
     * @return void
     */
    public function areaAction()
    {
        $parentID = $this ->_request -> getParam('parent_id', null);
        $area = $this -> _api -> getArea(array('parent_id' => $parentID));
     	if ($area) {
     	    exit(Zend_Json :: encode($area));
     	}
    }

    /**
     * 锁定订单
     *
     * @return void
     */
    public function lockAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('lock', 0);
    	$this -> _api -> lock($this -> _request -> getPost(), $val);
    }
    /**
     * 锁定订单超级权限
     *
     * @return void
     */
    public function superLockAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('lock', 0);
    	$this -> _api -> superLock($this -> _request -> getPost(), $val);
    }
    /**
     * 未确认订单列表
     *
     * @return void
     */
    public function notConfirmListAction()
    {
        $search = $this->_request->getParams();
        $where = $search;
        $where['status'] = '0,4';
        $where['status_logistic'] = 0;
        $where['lid'] = $this -> _lid;
        $this -> view -> payment = $this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1));
        $data = $this -> _api -> getOrderBathWithPage($where, intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $this -> view -> product = $data['product'];
        $this -> view -> totalPriceOrder = $data['total_price_order'];
        $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> auth = $this -> _auth['admin_name'];
        $this -> view -> param = $search;
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    /**
     * 未确认订单信息
     *
     * @return void
     */
    public function notConfirmInfoAction()
    {
        $batchSN = $this ->_request -> getParam('batch_sn', null);
        if($batchSN){
            $where = array('batch_sn' => $batchSN, 'status' => '0,4', 'status_logistic' => 0);
            $order = array_shift($this -> _api -> getOrderBatch($where));
            if (is_array($order) && count($order)) {
                $where = "item=1 and item_no='{$batchSN}' and status>0 and status< 4 and (pay<0 || gift<0 || point<0)";
                $this -> view -> finance = $this -> _finance -> getFinance($where);
                $detail = $this -> _api -> orderDetail($batchSN);
                
                $this -> _api -> setOrderGiftCard($detail['product_all']);

                $this -> view -> detail = $detail;
                $this -> view -> pay_type = $detail['order']['pay_type'];
                $payment = $this -> _api -> getPayment(array('status' => 0));
                if ($detail['other']['price_blance'] <= 0) {
                    unset($payment['cod']);
                }
                if ($detail['pay_log']) {
                    //如果已经通过支付接口生成记录，则不允许修改支付方式
                    foreach ($detail['pay_log'] as $payLog) {
                        if (!in_array($payLog['pay_type'], array('service', 'bank', 'cash', 'cod'))) {
                            $this -> view -> notChangePayType = 1;
                            break;
                        }
                    }
                }
                if ($detail['other']['price_must_pay']) {
                    $memberAPI = new Admin_Models_API_Member();
                    $memberInfo = $memberAPI -> getMemberByUserId($order['user_id']);
                    if ($memberInfo['money'] > 0) {
                        $this -> view -> canAccountPayment = 1;
                    }
                }
                
                
                $this -> view -> payment = $payment;
                $this -> view -> blance = $detail['other']['price_blance'];
                $this -> view -> batchSN = $detail['order']['batch_sn'];
                $this -> view -> order = $detail['order'];
                $this -> view -> noteStaff = $detail['order']['note_staff'];
                $this -> view -> product = $detail['product_all'];
                $this -> view -> priceAccount = $detail['price_account'];
                $this -> view -> logs = $detail['batch_log'];
                $this -> view -> payLog = $detail['pay_log'];
                $this -> view -> auth = $this -> _auth;
                $stockConfig = Custom_Model_Stock_Base::getInstance();
                $this -> view -> areas = $stockConfig -> getConfigLogicArea();
                $this -> view -> distributionArea = $stockConfig -> getDistributionArea();
                $giftCardAPI = new Admin_Models_API_GiftCard();
                $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN, 'use_type' => 1));
                $giftCardLog = $giftCardLog['content'];
                $this -> view -> giftCardLog = $giftCardAPI -> setCanReturnCard($giftCardLog);        
                $canConfirm = true;
                $this -> view -> canConfirm = $canConfirm;
            }

        }else{
            exit('error');
        }
    }
    /**
     * 添加客服备注
     *
     * @return void
     */
    public function addNoteStaffAction()
    {
        $mod = $this ->_request -> getParam('mod', null);
        $batchSN = $this ->_request -> getParam('batch_sn', null);
        $noteStaff = $this ->_request -> getParam('note_staff', '');
        $invoice = $this -> _request -> getParam('invoice', '');
        $noteLogistic = $this -> _request -> getParam('note_logistic', '');
        $notePrint = $this -> _request -> getParam('note_print', '');
        $payType = $this -> _request -> getParam('pay_type', '');
        if ($invoice || $noteLogistic || $notePrint || $payType) {
            $data = array('invoice' => $invoice,
                          'note_logistic' => $noteLogistic,
                          'note_print' => $notePrint,
                          'pay_type' => $payType);
            $this -> _api -> saveNotConfirmInfo($batchSN, $data);
        }
        $this -> _api -> addNoteStaff($batchSN, $noteStaff);
        $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/{$mod}/batch_sn/{$batchSN}";
        Custom_Model_Message :: showMessage('添加成功', $url);
    }

    /**
     * 客服微调价格
     *
     * @return void
     */
    public function addPriceAdjustAction()
    {
        $batchSN = $this ->_request -> getParam('batch_sn', '');
        $invoice = $this -> _request -> getParam('invoice', '');
        $noteLogistic = $this -> _request -> getParam('note_logistic', '');
        $notePrint = $this -> _request -> getParam('note_print', '');
        $payType = $this -> _request -> getParam('pay_type', '');
        $price_payed = $this -> _request -> getParam('price_payed', 0);
        $price_logistic = $this -> _request -> getParam('price_logistic', 0);
        if ($invoice || $noteLogistic || $notePrint || $payType) {
            $data = array('invoice' => $invoice,
                          'note_logistic' => $noteLogistic,
                          'note_print' => $notePrint,
                          'pay_type' => $payType,
                          'price_payed' => $price_payed,
                          'price_logistic' => $price_logistic);
            $this -> _api -> saveNotConfirmInfo($batchSN, $data);
        }
        $data = array('money' => $this ->_request -> getParam('price_adjust', 0),
                      'note' => $this ->_request -> getParam('note_adjust', ''),
                      'type' => 1);
        $this -> _api -> addPriceAdjust($batchSN, $data);
        $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/not-confirm-info/batch_sn/{$batchSN}";
        Custom_Model_Message :: showMessage('调整金额成功', $url);
    }
    /**
     * 修改支付方式
     *
     * @return void
     */
    public function editPaymentAction()
    {
        $batchSN = $this ->_request -> getParam('batch_sn', null);
        $payType = $this->_request->getParam('pay_type', null);
        if (!$payType) {
            $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $batchSN)));
            $this -> view -> pay_type = $order['pay_type'];
            $payment = $this -> _api -> getPayment(array('status' => 0));
            $logisticList = $order['logistic_list'];
            if (!$order['logistic_list']['other']['cod']) {
                unset($payment['cod']);
            }
            $this -> view -> payment = $payment;
        } else {
           $payment = array_shift($this -> _api -> getPayment(array('status' => 0, 'pay_type' => $payType)));
           $this -> _api -> editPayment($batchSN, array('pay_type' => $payment['pay_type'], 'pay_name' => $payment['name']));
           $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/not-confirm-info/batch_sn/{$batchSN}";
           Custom_Model_Message :: showMessage('编辑成功', $url);
       }
    }
    /**
     * 修改配送地址
     *
     * @return void
     */
    public function editAddressAction()
    {
        $batchSN = $this ->_request -> getParam('batch_sn', null);
        $order_step    = $this ->_request -> getParam('order_step', '');
        if(!$this ->_request -> getParam('type', null)){
            $data = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $batchSN)));
            $data['addr_province_option'] = $this -> _api -> getArea(array('parent_id' => 1));
            $data['addr_city_option'] = $this -> _api -> getArea(array('parent_id' => $data['addr_province_id']));
            $data['addr_area_option'] = $this -> _api -> getArea(array('parent_id' => $data['addr_city_id']), true);
            $data['order_step'] = $order_step;
            $this -> view -> data = $data;
        }else{
            $consignee = $this ->_request -> getParam('addr_consignee', '');
            $provinceID = $this ->_request -> getParam('addr_province_id', '');
            $cityID = $this ->_request -> getParam('addr_city_id', '');
            $areaID = $this ->_request -> getParam('addr_area_id', '');
            $address = $this ->_request -> getParam('addr_address', '');
            $eng_address = $this ->_request -> getParam('addr_eng_address', '');
            $tel = $this ->_request -> getParam('addr_tel', '');
            $mobile = $this ->_request -> getParam('addr_mobile', '');
            $email = $this ->_request -> getParam('addr_email', '');
            $smsNo = $this ->_request -> getParam('sms_no', '');

            if ($provinceID) {
                $addr_province = $this -> _api -> getAreaName($provinceID);
            }
            if ($cityID) {
                $addr_city = $this -> _api -> getAreaName($cityID);
            }
            if ($areaID) {
                $addr_area = $areaID == -1 ? '其它区' : $this -> _api -> getAreaName($areaID);
            }
            $data = array('addr_consignee' => $consignee,
                          'addr_province' => $addr_province,
                          'addr_city' => $addr_city,
                          'addr_area' => $addr_area,
                          'addr_province_id' => $provinceID,
                          'addr_city_id' => $cityID,
                          'addr_area_id' => $areaID,
                          'addr_address' => $address,
            			  'addr_eng_address' => $eng_address,
                          'addr_email' => $email,
                          'addr_tel' => $tel,
                          'addr_mobile' => $mobile);
            $smsNo && $data['sms_no'] = $smsNo;
            $this -> _api -> editAddress($batchSN, $data);
            $url = '';
            if ($order_step == 'confirm') {
                $url = "/admin/order/confirm-info/batch_sn/{$batchSN}";
            } else {
                $url = "/admin/order/not-confirm-info/batch_sn/{$batchSN}";
            }
            $url = $this -> getFrontController() -> getBaseUrl() . $url;
            Custom_Model_Message :: showMessage('编辑成功', $url);
       }
    }
    /**
     * 修改订单商品
     *
     * @return void
     */
    public function editOrderBatchGoodsAction(){
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        $type = $this -> _request -> getParam('type', null);
        if (!$type) {
            $data = $this -> _api -> orderDetail($batchSN);
			$this->view->order_info  = $data['order'];
            $this -> view -> product = $data['product_all'];
        } else {
            $this -> _helper -> viewRenderer -> setNoRender();
            $this -> _api -> editOrderBatchGoods($batchSN, $this -> _request -> getParam('data', null), $this -> _request -> getParam('note', null), $error);
            if ($error) {
                Custom_Model_Message :: showMessage($error);
            }
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/not-confirm-info/batch_sn/{$batchSN}";
            Custom_Model_Message :: showMessage('编辑成功', $url);
       }
    }
    /**
     * 订单挂起
     *
     * @return void
     */
    public function hangAction(){
        $batchSN = $this->_request->getParam('batch_sn', null);
        $invoice = $this -> _request -> getParam('invoice', '');
        $noteLogistic = $this -> _request -> getParam('note_logistic', '');
        $notePrint = $this -> _request -> getParam('note_print', '');
        $payType = $this -> _request -> getParam('pay_type', '');
        if ($invoice || $noteLogistic || $notePrint || $payType) {
            $data = array('invoice' => $invoice,
                          'note_logistic' => $noteLogistic,
                          'note_print' => $notePrint,
                          'pay_type' => $payType);
            $this -> _api -> saveNotConfirmInfo($batchSN, $data);
        }
        $this -> _api -> hang($batchSN);
        $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/not-confirm-list/";
        Custom_Model_Message :: showMessage('订单挂起成功', $url);
    }
    /**
     * 订单无效
     *
     * @return void
     */
    public function invalidAction()
    {
        $batchSN = $this->_request->getParam('batch_sn', null);
        $invoice = $this -> _request -> getParam('invoice', '');
        $noteLogistic = $this -> _request -> getParam('note_logistic', '');
        $notePrint = $this -> _request -> getParam('note_print', '');
        $payType = $this -> _request -> getParam('pay_type', '');
        if ($invoice || $noteLogistic || $notePrint || $payType) {
            $data = array('invoice' => $invoice,
                          'note_logistic' => $noteLogistic,
                          'note_print' => $notePrint,
                          'pay_type' => $payType);
            $this -> _api -> saveNotConfirmInfo($batchSN, $data);
        }
        $orderDetail = $this -> _api -> orderDetail($batchSN);
        if ($orderDetail['finance']['status_return_all']) {
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/finance/batch_sn/{$batchSN}/jump/invalid/";
            $msg = '该订单需要退款';
        } else {
            $this -> _api -> invalid($batchSN);
            $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/not-confirm-list/';
            $msg = '订单设置无效成功';
        }
        Custom_Model_Message :: showMessage($msg, $url);
    }

    public function saveAction()
    {
        $batchSN = $this->_request->getParam('batch_sn', null);
        $data = $this->_request->getParams();
        $data['unlock'] = 1;
        if (false === $this -> _api -> saveNotConfirmInfo($batchSN, $data)) {
			Custom_Model_Message::showMessage($this->_api->getError(), 'reload', -1);
		}
        
        $orderDetail = $this -> _api -> orderDetail($batchSN);
        if ($orderDetail['finance']['price_return_money'] > 0) {
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/finance/batch_sn/{$batchSN}/jump/save/";
            $msg = '该订单需要退款';
        } else {
            $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/not-confirm-list/';
            $msg = '订单保存成功';
        }
        Custom_Model_Message :: showMessage($msg, $url);
    }
    
    /**
     * 订单确认
     *
     * @return void
     */
    public function confirmAction()
    {
        $batchSN = $this->_request->getParam('batch_sn', null);
        $data = array('invoice_type' => (int)$this -> _request -> getParam('invoice_type', 0),
                      'invoice' => $this -> _request -> getParam('invoice', ''),
                      'invoice_content' => $this -> _request -> getParam('invoice_content', ''),
                      'note_logistic' => $this -> _request -> getParam('note_logistic', ''),
                      'note_print' => $this -> _request -> getParam('note_print', ''),
                      'pay_type' => $this -> _request -> getPost('pay_type', ''),
                      'price_logistic' => $this -> _request -> getPost('price_logistic', 0),
                     );
        
		$order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $batchSN)));
        if(false === $this -> _api -> saveNotConfirmInfo($batchSN, $data)) {
			Custom_Model_Message::showMessage($this->_api->getError(), 'reload', -1);
		}

        if (!$this -> _api -> checkOut($batchSN, 2)) {
            Custom_Model_Message :: showMessage("订单{$batchSN}库存不足!", 'reload', -1);
        }
        
        $orderDetail = $this -> _api -> orderDetail($batchSN);
        
        if ($orderDetail['finance']['status_return'] && $orderDetail['finance']['price_return_money'] > 0) {
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/finance/batch_sn/{$batchSN}/jump/confirm/";
            $msg = '该订单需要退款';
        }
        else {
            //api 积分接口
            if ($orderDetail['finance']['price_return_point'] > 0) {
                $this -> _api -> unPointPrice($batchSN, $orderDetail['finance']['price_return_money']);
            }
            //api 账户余额接口
            if ($orderDetail['finance']['price_return_account'] > 0) {
                $this -> _api -> unAccountPrice($batchSN, $orderDetail['finance']['price_return_account']);
            }
            //api 礼品卡接口
            if ($orderDetail['finance']['price_return_gift'] > 0) {
                $this -> _api -> unUseCardGift($batchSN, $orderDetail['finance']['price_return_gift']);
            }
            
            $result = $this -> _api -> confirm($batchSN);
            if ($result == 'repeat') {//重复提交
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/not-confirm-list/';
                $msg = "订单重复确认";
            } else if ($result == 'outFail') {
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/not-confirm-list/';
                $msg = "订单确认失败，请联系管理员[订单号：{$batchSN}]";
            } else if ($result == 'giftCardError') {
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/not-confirm-info/batch_sn/'.$batchSN;
                $msg = "订单中包启礼品卡和普通商品，不能使用在线支付";
            } else if ($result == 'exchangeOrdereError') {
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/not-confirm-info/batch_sn/'.$batchSN;
                $msg = "换货单不能增加应付金额";
            } else {
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/not-confirm-list/';
                $msg = '订单确认成功';
            }
        }
        Custom_Model_Message :: showMessage($msg, $url);
    }
    /**
     * 已确认订单列表
     *
     * @return void
     */
    public function confirmListAction()
    {
        $search = $this->_request->getParams();
        $where = $search;
        $where['status'] = 0;
        $where['status_pay'] = 0;
        $where['pay_type!='] ='cod';
        $where['status_logistic>'] = 0;
        $where['status_return'] = '0';
        $where['pay_type!='] ='cod';
        $where['user_name!='] = "'credit_channel','batch_channel','distribution_channel'";
        $where['lid'] = $this -> _lid;
        $this -> view -> payment = $this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1));
        $data = $this -> _api -> getOrderBathWithPage($where, intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $this -> view -> product = $data['product'];
        $this -> view -> totalPriceOrder = $data['total_price_order'];
        $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> auth = $this -> _auth['admin_name'];
        $this -> view -> param = $search;
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    /**
     * 已确认订单信息
     *
     * @return void
     */
    public function confirmInfoAction()
    {
        $batchSN = $this ->_request -> getParam('batch_sn', null);
        $where = array('batch_sn' => $batchSN, 'status' => 0);
        $order = array_shift($this ->_api -> getOrderBatch($where));
        if (is_array($order) && count($order)) {
            $where = "item=1 and item_no='{$batchSN}' and status>0 and status< 4 and (pay<0 || gift<0 || point<0)";
            $this -> view -> finance = $this -> _finance -> getFinance($where);
            $detail = $this -> _api -> orderDetail($batchSN);
            
            $this -> _api -> setOrderGiftCard($detail['product_all']);
            
            $this -> view -> auth = $this -> _auth;
            $this -> view -> detail = $detail;
            $this ->  view -> blance = $detail['other']['price_blance'];
            $this -> view -> batchSN = $detail['order']['batch_sn'];
            $this -> view -> order = $detail['order'];
            $this -> view -> noteStaff = $detail['order']['note_staff'];
            $this -> view -> product = $detail['product_all'];
            $this -> view -> priceAccount = $detail['price_account'];
            $this -> view -> logs = $detail['batch_log'];
            $this -> view -> payLog = $detail['pay_log'];
            $this -> view -> adminName = $this -> _auth['admin_name'];
            $this -> view -> groupID = $this -> _auth['group_id'];
            $stockConfig = Custom_Model_Stock_Base::getInstance();
            $this -> view -> areas = $stockConfig -> getConfigLogicArea();
            $this -> view -> distributionArea = $stockConfig -> getDistributionArea();
            $giftCardAPI = new Admin_Models_API_GiftCard();
            $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN, 'use_type' => 1));
            $this -> view -> giftCardLog = $giftCardLog['content'];
        }
    }
    
    /**
     * 待退款订单列表
     *
     * @return void
     */
    public function refundListAction()
    {
        $search = $this->_request->getParams();
        $where = $search;
        $where['status'] = 0;
        $where['status_pay'] = 1;
        $where['lid'] = $this -> _lid;
        $this -> view -> payment = $this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1));
        $data = $this -> _api -> getOrderBathWithPage($where, intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $this -> view -> product = $data['product'];
        $this -> view -> totalPriceOrder = $data['total_price_order'];
        $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> auth = $this -> _auth['admin_name'];
        $this -> view -> param = $search;
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 确认收款
     *
     * @return void
     */
    public function hasPayAction()
    {
        $pay_money = $this->_request->getParam('pay_money', '0.00');
        $batchSN = $this->_request->getParam('batch_sn', null);
        //$this -> _api -> send_union_pay($batchSN);die();
        $orderDetail = $this -> _api -> orderDetail($batchSN);
        if ($orderDetail['finance']['status_return']) {
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/finance/batch_sn/{$batchSN}/jump/has-pay/";
            $msg = '该订单需要退款';
        } else {
            $result = $this -> _api -> hasPay($batchSN,$pay_money);
            if ($result == 'repeat') {//重复提交
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/confirm-list/';
                $msg = "订单重复确认";
            }
            else if ($result == 'no_pay_money') {
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/confirm-list/';
                $msg = "没有填写确认收款金额 [订单号：{$batchSN}]";
            }
            else if ($result == 'can_not_pay_manually') {
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/confirm-list/';
                $msg = "网关支付的订单不允许手动收款 [订单号：{$batchSN}]";
            }
            else if ($result == 'outFail') {
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/confirm-list/';
                $msg = "订单确认失败，请联系管理员 [订单号：{$batchSN}]";
            } else {
                $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/confirm-list/';
                $this -> _api -> send_union_pay($batchSN);
                $msg = '订单收款成功';
            }
        }
        Custom_Model_Message :: showMessage($msg, $url);
    }
    /**
     * 确认收款订单返回
     *
     * @return void
     */
    public function confirmBackAction()
    {
        $batchSN = $this->_request->getParam('batch_sn', null);
        $data = array('status_logistic' => 0, 'lock_name' => '');
        $this -> _api -> confirmBack($batchSN, $data);
        $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/confirm-list/';
        Custom_Model_Message :: showMessage('确认收款订单返回成功', $url);
    }
    /**
     * 订单取消
     *
     * @return void
     */
    public function confirmCancelAction()
    {
        $batchSN = $this->_request->getParam('batch_sn', null);
        $mod = $this->_request->getParam('mod', 'not-confirm-list');
        $noteStaff = $this->_request->getParam('note_staff_cancel', null);
        $invoice = $this -> _request -> getParam('invoice', '');
        $noteLogistic = $this -> _request -> getParam('note_logistic', '');
        $notePrint = $this -> _request -> getParam('note_print', '');
        $payType = $this -> _request -> getParam('pay_type', '');
        if ($invoice || $noteLogistic || $notePrint || $payType || $noteStaff) {
            $data = array('invoice' => $invoice,
                          'note_logistic' => $noteLogistic,
                          'note_print' => $notePrint,
                          'note_staff' => $noteStaff,
                          'pay_type' => $payType);
            $this -> _api -> saveNotConfirmInfo($batchSN, $data);
        }
        
        $orderDetail = $this -> _api -> orderDetail($batchSN);
        if ($orderDetail['finance']['status_return_all']) {
			$this -> _api -> confirmCancel($batchSN);
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/finance/batch_sn/{$batchSN}/jump/confirm-cancel/mod/{$mod}";
            $msg = '该订单需要退款';
        } else {
            $this -> _api -> confirmCancel($batchSN);
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/{$mod}/";
            $msg = '订单取消成功';
        }
        Custom_Model_Message :: showMessage($msg, $url);

    }
    /**
     * 未确认订单批量取消
     *
     * @return void
     */
    public function notConfirmBatchCancelAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $data = $this -> _request -> getPost();
    	$this -> _api -> batchCancel($data['ids']);
    }
    /**
     * 确认收款订单批量取消
     *
     * @return void
     */
    public function confirmBatchCancelAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $data = $this -> _request -> getPost();
    	$this -> _api -> batchCancel($data['ids']);
    }
    /**
     * 待发货订单申请返回
     *
     * @return void
     */
    public function toBeShippingBackAction()
    {
        $batchSN = $this->_request->getParam('batch_sn', null);
        $this -> _api -> toBeShippingBack($batchSN, $this->_request->getParam('note_staff_cancel', null));
        $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/list/';
        Custom_Model_Message :: showMessage('待发货订单申请返回成功', $url);
    }
    /**
     * 待发货订单申请取消
     *
     * @return void
     */
    public function toBeShippingCancelAction()
    {
        $batchSN = $this->_request->getParam('batch_sn', null);
        $orderDetail = $this -> _api -> orderDetail($batchSN);
        if ($this -> _api -> checkPrepared($batchSN)) {
            Custom_Model_Message :: showMessage('该订单已配货，请先申请返回再取消订单');
        }
        
        if ($orderDetail['finance']['status_return_all']) {
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/finance/batch_sn/{$batchSN}/jump/to-be-shipping-cancel/";
            $msg = '该订单需要退款';
        }
        else {
            $this -> _api -> toBeShippingCancel($batchSN, $this->_request->getParam('note_staff_cancel', null));
            $url = $this -> getFrontController() -> getBaseUrl() . '/admin/order/list/';
            $msg = '待发货订单申请取消成功';
        }
        
        Custom_Model_Message :: showMessage($msg, $url);
    }
    /**
     * 订单查询
     *
     * @return void
     */
    public function listAction()
    {
        $search = $this->_request->getParams();
        $search['lid'] = $this -> _lid;
        if (!$search['dosearch']) {
            $search['fromdate'] = $search['todate'] = date('Y-m-d', time());
        }
        $payment = $this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1));
        $this -> view -> payment = $payment;
        
        $logisticAPI = new Admin_Models_API_Logistic();
        foreach ($logisticAPI -> getLogisticList() as $key => $value) {
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	}
    	$this -> view -> logisticList = $logisticList;
        
        if ($search['todo'] == 'export') {
            @ini_set('memory_limit', '1024M');
            $data = $this -> _api -> getOrderBathWithPage($search, 0);
    	    $content[] = array('订单号', '店铺', '下单类型', '下单时间', '订单状态', '订单物流状态', '订单金额', '财务金额', '支付方式', '产品编码', '商品名称', '数量', '单价', '商品分类', '运费', '配送方式', '发货时间', '物流公司', '运单号', '收货人', '收货地区', '电话', '手机', '用户','结算状态','发票类型','发票内容' ,'发票抬头');
    	    if ($data['data']) {
    	        foreach ($data['data'] as $order) {
    	            $products = array();
    	            foreach ($data['product'] as $product) {
    	                if ($product['batch_sn'] == $order['batch_sn']) {
    	                    $products[] = array('product_sn' => $product['product_sn'], 'goods_name' => $product['goods_name'], 'number' => $product['number'], 'sale_price' => $product['sale_price'], 'cat_name' => $product['cat_name']);
    	                }
    	            }
    	            if ($order['type'] == 0) {
    	                $type = '官网下单';
    	            }
    	            else if ($order['type'] == 5) {
    	                $type = '赠送下单';
    	            }
    	            else if ($order['type'] == 7) {
    	                $type = '内购下单';
    	            }
    	            else if ($order['type'] == 10) {
    	                $type = '呼入下单';
    	            }
    	            else if ($order['type'] == 11) {
    	                $type = '呼出下单';
    	            }
    	            else if ($order['type'] == 12) {
    	                $type = '咨询下单';
    	            }
    	            else if ($order['type'] == 13) {
    	                $type = '渠道下单';
    	            }
    	            else if ($order['type'] == 14 && $order['user_name'] == 'batch_channel') {
    	                $type = '购销下单';
    	            }
    	            else if ($order['type'] == 14 && $order['user_name'] == 'credit_channel') {
    	                $type = '赊销下单';
    	            }
    	            else if ($order['type'] == 14) {
    	                $type = '渠道补单';
    	            }
    	            else if ($order['type'] == 15) {
    	                $type = '其它下单';
    	            }
    	            else if ($order['type'] == 16) {
    	                $type = '直供下单';
    	            }
    	            else if ($order['type'] == 17) {
    	                $type = '试用下单';
    	            }
    	            else if ($order['type'] == 18) {
    	                $type = '分销下单';
    	            }

    	            if ($order['invoice_type'] == 0) {
    	                $invoice_type = '没有填写';
    	            }
    	            else if ($order['invoice_type'] == 1) {
    	                $invoice_type = '个人';
    	            }
    	            else if ($order['invoice_type'] == 2) {
    	                $invoice_type = '企业';
    	            }



    	            foreach ($products as $index => $product) {
    	                if ($index == 0) {
    	                    $content[] = array("'".$order['batch_sn'],
            	                               $order['shop_name'],
            	                               $type,
            	                               $order['add_time'],
            	                               $order['status'],
            	                               $order['status_logistic'],
            	                               $order['price_order'],
            	                               $order['balance_amount'],
            	                               $order['pay_name'],
            	                               $product['product_sn'],
            	                               $product['goods_name'],
            	                               $product['number'],
            	                               $product['sale_price'],
            	                               $product['cat_name'],
            	                               $order['price_logistic'],
            	                               $order['pay_type'] == 'cod' ? '货到付款' : '款到发货',
            	                               $order['logistic_time'] ? date('Y-m-d H:i:s', $order['logistic_time']) : '',
            	                               $logisticList[$order['logistic_code']],
            	                               $order['logistic_no'],
            	                               $order['addr_consignee'],
            	                               $order['addr_province'].$order['addr_city'].$order['addr_area '].$order['addr_address'],
            	                               $order['addr_tel'],
            	                               $order['addr_mobile'],
            	                               $order['user_name'],
            	                               $order['clear_pay'] ? '已结算' : '未结算',
											   $invoice_type,
											   $order['invoice_content'],
								               $order['invoice'],
            	                              );
    	                }
    	                else {
    	                    $content[] = array('',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               $product['product_sn'],
            	                               $product['goods_name'],
            	                               $product['number'],
            	                               $product['sale_price'],
            	                               $product['cat_name'],
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                               '',
            	                              );
    	                }
    	            }
    	            
    	        }
    	    }
    	    
            $xls = new Custom_Model_GenExcel();
            $xls -> addArray($content);
            $xls -> generateXML('order');

            exit();
    	}
        
        $data = $this -> _api -> getOrderBathWithPage($search, intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $this -> view -> product = $data['product'];
        $this -> view -> totalPriceOrder = $data['total_price_order'];
        $this -> view -> totalBalanceAmount = $data['total_balance_amount'];
        $this -> view -> totalVitualAmount = $data['total_vitual_amount'];
        $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> auth = $this -> _auth;
        $this -> view -> param = $search;
    	$stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    /**
     * 订单信息
     *
     * @return void
     */
    public function infoAction()
    {
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        $print = $this -> _request -> getParam('print', null);
        $order = array_shift($this ->_api -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (is_array($order) && count($order)) {
            $where = "item=1 and item_no='{$batchSN}' and status< 4 and (pay<0 || gift<0 || point<0 || account<0)";
            $this -> view -> finance = $this -> _finance -> getFinance($where);
            
            $transport = new Admin_Models_DB_Transport();
            $complain = array_shift($transport -> get("t1.logistic_code='{$order['logistic_code']}' and t1.logistic_no='{$order['logistic_no']}'","is_complain"));
            $this -> view -> complain = $complain['is_complain'];

            $detail = $this -> _api -> orderDetail($batchSN);
            
            $this -> _api -> setOrderGiftCard($detail['product_all']);
          
            
            $this -> view -> detail = $detail;
            
            if ($order['shop_id'] <= 1) {
                $transportAPI = new Admin_Models_API_Transport();
                $splitData = $transportAPI -> isSplitOrder($batchSN);
                if ($splitData) {
                    $detail['order']['split_status'] = $transportAPI -> getSplitOrder($batchSN, $splitData);
                }
            }
            $this -> view -> auth = $this -> _auth;
            $this -> view -> blance = $detail['other']['price_blance'];
            $this -> view -> batchSN = $detail['order']['batch_sn'];
            $this -> view -> order = $detail['order'];
            $this -> view -> noteStaff = $detail['order']['note_staff'];
            $this -> view -> product = $detail['product_all'];
            $this -> view -> priceAccount = $detail['price_account'];
            $this -> view -> logs = $detail['batch_log'];
            $this -> view -> payLog = $detail['pay_log'];
            $this -> view -> adminName = $this -> _auth['admin_name'];
            $this -> view -> childOrder = $this ->_api -> getOrderBatch(array('parent_batch_sn' => $batchSN));
            $transportAPI = new Admin_Models_API_Transport();
			$this -> _stock = Custom_Model_Stock_Base::getInstance(1);
			$this -> _logisticStatus = $this -> _stock -> getConfigLogisticStatus();
			$this -> view -> logisticStatus = $this -> _stock -> getConfigLogisticStatus();
            $transportDatas =array_shift($transportAPI -> get("bill_no='{$batchSN}' "));
            $this -> view -> op = $transportAPI -> getOp("item_id='{$transportDatas['tid']}' ");
            $this -> view -> tracks = $transportAPI -> getTrack("item_no='{$batchSN}'");
            if($print=='1'){
                $this -> render('print');
            }
            $stockConfig = Custom_Model_Stock_Base::getInstance();
            $this -> view -> areas = $stockConfig -> getConfigLogicArea();
            $this -> view -> distributionArea = $stockConfig -> getDistributionArea();
            $giftCardAPI = new Admin_Models_API_GiftCard();
            $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN, 'use_type' => 1));
            $this -> view -> giftCardLog = $giftCardLog['content'];
            $memberAPI = new Admin_Models_API_Member();
            $pointLog = $memberAPI -> getPointFrequency(array('batch_sn' => $batchSN));
            $this -> view -> pointLog = array_shift($pointLog['point']);
            $moneyAPI = new Admin_Models_DB_MemberMoney();
            $this -> view -> moneyLog = array_shift($moneyAPI -> getMoney(" and A.batch_sn = '{$batchSN}'"));
            
        }
    }
    /**
     * 恢复订单
     *
     * @return void
     */
    public function undoAction()
    {
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        $type = $this -> _request -> getParam('type', null);
        if (!$type) {
            $data = $this -> _api -> orderDetail($batchSN);
            $this -> view -> product = $data['product_all'];

        } else {
            $this -> _helper -> viewRenderer -> setNoRender();
            $this -> _api -> undo($batchSN, $this -> _request -> getParam('data', null), $this -> _request -> getParam('note', null), $error);
            if ($error) {
                Custom_Model_Message :: showMessage($error);
            }
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/list/";
            Custom_Model_Message :: showMessage('恢复成功', $url);
       }
    }
    /**
     * 可退换货订单列表
     *
     * @return void
     */
    public function returnListAction()
    {
        $search = $this->_request->getParams();
        if (!$search['dosearch']) {
            $search['fromdate'] =date("Y-m-d",strtotime("-100 day"));
            $search['todate'] = date('Y-m-d', time());
        }
        $where = $search;
        $where['lid'] = $this -> _lid;
        if($search['rejection']=='1'){
            $where['status_logistic'] = 5;
            $where['status'] = 0;
            $where['status_return'] = 0;
        }else{
            $where['status_logistic>'] = 3;
            $where['status'] = 0;
        }
        $data = $this -> _api -> getOrderBathWithPage($where, intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $this -> view -> product = $data['product'];
        $this -> view -> totalPriceOrder = $data['total_price_order'];
        $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> auth = $this -> _auth['admin_name'];
        $this -> view -> param = $search;
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];

    }

    /**
     * 退换货开单
     *
     * @return void
     */
    public function returnProductAction()
    {
        $type = $this -> _request -> getParam('type', null);
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        if (!$type) {
            $detail = $this -> _api -> orderDetail($batchSN);
            $this -> view -> detail = $detail;
            $this -> view -> order = $detail['order'];
            
            $this -> view -> reason = $this -> _api -> getReason();
            $data = $this -> _api -> orderDetail($batchSN);

			$this->view->order_info  = $data['order'];
            $this -> view -> product = $data['product_all'];

            $this -> render('return-select-product');
        } else if ($type == 1) {
            $newBatchSN = $this -> _api -> runReturn($batchSN, $this -> _request -> getPost(), $error);
            if ($error) {
                Custom_Model_Message :: showMessage($error);
            } else {
                if ($newBatchSN) {
                    $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/not-confirm-info/batch_sn/{$newBatchSN}/";
                    $msg = '退换货开单成功';
                } else {
                    $orderDetail = $this -> _api -> orderDetail($batchSN);
                    if ($orderDetail['finance']['status_return'] || $this -> _api -> jumpFinanceReturn) {
                        $url = $this -> getFrontController() -> getBaseUrl() .
                               "/admin/order/finance/batch_sn/{$batchSN}/jump/return-list/";
                        $msg = '该订单需要退款';
                    } else {
                        $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/return-list/";
                        $msg = '退换货开单成功';
                    }
                }
                Custom_Model_Message :: showMessage($msg, $url);
            }
        }
    }
    /**
     * 投诉
     *
     * @return void
     */
    public function complainAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        $remark = $this -> _request -> getParam('remark', null);
        $this -> _api -> complain($batchSN, $remark);
        $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/list/";
        Custom_Model_Message :: showMessage('投诉成功', $url);
    }
    /**
     * 设置退款金额
     *
     * @return void
     */
    public function financeAction()
    {
        $bank = $this -> _request -> getParam('bank', null);
        $submit = $this -> _request -> getParam('submit', null);
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        $jump = $this -> _request -> getParam('jump', null);
        $mod = $this -> _request -> getParam('mod', null);
        $type = $this -> _request -> getParam('type', 1);
        $way = $this -> _request -> getParam('way', 1);
        
        $orderDetail = $this -> _api -> orderDetail($batchSN);
        if ($submit) {
            if ($jump == 'confirm-cancel' || $jump == 'invalid') {
                $pay = $orderDetail['finance']['price_return_all_money'];
                $point = $orderDetail['finance']['price_return_all_point'];
                $account = $orderDetail['finance']['price_return_all_account'];
                $gift = $orderDetail['finance']['price_return_all_gift'];
                $status = 1;
            } else if ($jump == 'to-be-shipping-cancel') {
                $pay = $orderDetail['finance']['price_return_all_money'];
                $point = $orderDetail['finance']['price_return_all_point'];
                $account = $orderDetail['finance']['price_return_all_account'];
                $gift = $orderDetail['finance']['price_return_all_gift'];
                $status = 1;
                
                $this -> _api -> confirmCancel($batchSN);
                
            } else if ($jump == 'confirm' || $jump == 'has-pay') {
                $pay = $orderDetail['finance']['price_return_money'];
                $point = $orderDetail['finance']['price_return_point'];
                $account = $orderDetail['finance']['price_return_account'];
                $gift = $orderDetail['finance']['price_return_gift'];
                $status = 1;
            } else if ($jump == 'save') {
                $pay = $orderDetail['finance']['price_return_money'];
                $point = $orderDetail['finance']['price_return_point'];
                $account = $orderDetail['finance']['price_return_account'];
                $gift = $orderDetail['finance']['price_return_gift'];
                $status = 0;
            } else if ($jump == 'return-list') {
                if ($orderDetail['order']['type'] == 16) {
                    $pay = $this -> _request -> getParam('returnMoney', 0);
                    if ($pay <= 0) {
                        Custom_Model_Message :: showMessage('请输入退款金额!');
                    }
                }
                else {
                    $pay = $orderDetail['finance']['price_return_money'];
                    $logistic = $orderDetail['finance']['price_return_logistic'];
                    $point = $orderDetail['finance']['price_return_point'];
                    $account = $orderDetail['finance']['price_return_account'];
                    $gift = $orderDetail['finance']['price_return_gift'];
                }
                
                //只包含虚拟商品的入库单已经收货，或者丢件虚拟入库，付款状态为可支付
                $inStockAPI = new Admin_Models_API_InStock();
                $instock = array_shift($inStockAPI -> get("b.bill_type in (1,13) and b.item_no = '{$batchSN}'", 'b.bill_status', 1, 1, 'b.instock_id desc'));
                if ($instock['bill_status'] == 7) {
                    $status = 1;
                }
                else {
                    $status = 0;
                }
            }
            $logistic = round($logistic, 2);
            $this -> _api -> addFinance($batchSN, $bank, $pay, $logistic, $point, $account, $gift, $status, $orderDetail['order']['shop_id'], $type, $way, $jump == 'return-list' ? 1 : 0);
            if ($mod) {
                $url = "/admin/order/{$jump}/batch_sn/{$batchSN}/mod/{$mod}";
            }
            else {
                $url = "/admin/order/info/batch_sn/{$batchSN}";
            }
            Custom_Model_Message :: showMessage('申请退款成功', $url);
        } else {
            $this -> view -> batchSN = $batchSN;
            $this -> view -> jump = $jump;
            $this -> view -> mod = $mod;
            $this -> view -> finance = $orderDetail['finance'];
            $this -> view -> order = $orderDetail['order'];
        }

    }
    /**
     * 提供其他模块查看订单信息
     *
     * @return void
     */
    public function publicViewAction()
    {
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        $order = array_shift($this ->_api -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (is_array($order) && count($order)) {
            $this -> view -> batchSN = $batchSN;
            $this -> view -> order = $order;
            $this -> view -> blance = floatval($order['price_pay']) - (floatval($order['price_payed']) + floatval($order['price_from_return']));
            $detail = $this -> _api -> orderDetail($batchSN);
            $this -> view -> product = $detail['product_all'];
            $this -> view -> noteStaff = $order['note_staff'];
            $this -> view -> logs = $detail['batch_log'];
        }
    }
    /**
     * 后台下单
     *
     * @return void
     */
    public function addAction()
    {
        $submit = $this -> _request -> getParam('submit', null);
        if ($submit) {
            $shop_id = $this -> _request -> getParam('shop_id');
			$creditshop_id = $this -> _request -> getParam('creditshop_id');
			$shop_id = $shop_id ? $shop_id : $creditshop_id;

            $type = $this -> _request -> getParam('type', null);
            $add = $this -> _request -> getParam('add', null);
            $addg = $this -> _request -> getParam('addg', null);
            $provinceID = $this -> _request -> getParam('province_id', null);
            $cityID = $this -> _request -> getParam('city_id', null);
            $areaID = $this -> _request -> getParam('area_id', null);
            $giftbywho = $this -> _request -> getParam('giftbywho', null);
            $part_pay = $this -> _request -> getParam('part_pay', '0');

            $addr_consignee = $this -> _request -> getParam('addr_consignee', null);
            $addr_address = $this -> _request -> getParam('addr_address', null);
            $addr_tel = $this -> _request -> getParam('addr_tel', null);
            $addr_mobile = $this -> _request -> getParam('addr_mobile', null);
            $order_payment = $this -> _request -> getParam('order_payment', null);
            $logistics_type = $this -> _request -> getParam('logistics_type', null);
            $add_time = $this -> _request -> getParam('add_time', date('Y-m-d H:i:s'));
            $external_order_sn = $this -> _request -> getParam('external_order_sn', null);
            $note_print = $this -> _request -> getParam('note_print', null);
            $note_logistic = $this -> _request -> getParam('note_logistic', null);
            $distribution_type = $this -> _request -> getParam('distribution_type', null);
            $user_name = $this -> _request -> getParam('user_name', null);
            $distribution_shop_id = $this -> _request -> getParam('distribution_shop_id', null);
            $lid = $this -> _request -> getParam('lid', null);
            $addr_zip = $this -> _request -> getParam('addr_zip',null);
            $addr_eng_address = $this -> _request -> getParam('addr_eng_address',null);
            $priceLogistic = 0;
            $credentials_type = $this -> _request -> getParam('credentials_type',null);
            $credentials_no = $this -> _request -> getParam('credentials_no',null);
            
            $batchSN = $this -> _api -> add($type,$add,$addg,$provinceID,$cityID,$areaID,$error,$giftbywho,$addr_consignee,$addr_address,$addr_tel,$addr_mobile,$order_payment,$priceLogistic,$shop_id,$external_order_sn,$add_time,null,$logistics_type,$part_pay,0,0,$user_name,$note_print,$note_logistic,$distribution_type,$distribution_shop_id,$lid,$addr_zip,$addr_eng_address,$credentials_type,$credentials_no);
            if ($error) {
                Custom_Model_Message :: showMessage($error);
            } else {
                if ($batchSN) {
                    if (in_array($type, array('other2', 'other3'))) {
                        $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/not-confirm-info/batch_sn/{$batchSN}/";
                    }
                    else    $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/not-confirm-info/batch_sn/{$batchSN}/";
                    Custom_Model_Message :: showMessage('恭喜你，订单提交成功！', $url);
                } else {
                    $url = $this -> getFrontController() -> getBaseUrl() . "/admin/order/add/{$type}";
                    Custom_Model_Message :: showMessage('操作失败，请重新下单', $url);
                }
                
            }
        }
        $this -> view -> province = $this -> _api -> getArea(array('parent_id' => 1));
        $this -> view -> payment_list = $this -> _api -> getPayment(array('status' => 0,'is_bank!=' =>1));
        $this -> view -> add_time = date('Y-m-d H:i:s');
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionAreaUsername = $stockConfig -> getDistributionArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
        $this -> view -> lid = $this -> _lid;
    }
    
    /**
     * 订单列表查询
     */
     public function selOrderAction() {
        $job = $this -> _request -> getParam('job', null);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        if (substr($search['pay_type'], 0, 6) != 'alipay') {
            unset($search['sub_pay_type']);
        }
        $this -> view -> param = $search;
        $this -> view -> payment_list = $this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1));
        if($job) {
            $financeAPI = new Admin_Models_API_Finance();
            $datas = $financeAPI -> fetchOrder($search, $page);
            $pageNav = new Custom_Model_PageNav($datas['total'], null, 'ajax_search_order');
            $this -> view -> pageNav = $pageNav -> getNavigation();
            $this -> view -> datas = $datas['list'];
        }
     }
     
    /**
     * 订单列表批量查询
     */
    public function selOrderBatchAction() {
        $doimport = $this -> _request -> getParam('doimport', null);
        $search = $this -> _request -> getParams();

        if ($doimport) {
            if(is_file($_FILES['import_file']['tmp_name'])) {
                $xls = new Custom_Model_PHPExcel();
			    $xls -> open($_FILES['import_file']['tmp_name']);
			    $lines = $xls -> toArray();
                if (is_array($lines)) {
                    $order_id_array = array();
                    for ($i = 0; $i < count($lines); $i++) {
                        $lines[$i][0] = trim($lines[$i][0]);
                        $lines[$i][1] = trim($lines[$i][1]);
                        $lines[$i][2] = trim($lines[$i][2]);
                        $order_id_array[] = $lines[$i][0];
                    }
                    $financeAPI = new Admin_Models_API_Finance();
                    $datas = $financeAPI -> fetchOrder(array('batch_sns' => $order_id_array, 'pay_type' => $search['pay_type']));
                    if ($datas['total']) {
                        foreach ($datas['list'] as $data) {
                            $orderInfo[trim($data['batch_sn'])] = $data;
                        }
                    }
                    echo '<script language="JavaScript" type="text/javascript">';
                    echo 'parent.delAllRow();';
                    $index = 0;
                    for ($i = 0; $i < count($lines); $i++) {
                        $batch_sn = $lines[$i][0];
                        $amount = round($lines[$i][1] + $lines[$i][2], 2);
                        if ($orderInfo[$batch_sn] && $orderInfo[$batch_sn]['price_payed'] == $amount) {
                            $amount = '';
                        }
                        else    continue;
                        echo "parent.insertRow2({$index}, '{$batch_sn}', '{$orderInfo[$batch_sn]['oinfo']}', '{$amount}', '{$lines[$i][2]}');";
                        $index++;
                    }
                    for ($i = 0; $i < count($lines); $i++ ) {
                        $batch_sn = $lines[$i][0];
                        $amount = round($lines[$i][1] + $lines[$i][2], 2);
                        if ($orderInfo[$batch_sn] && $orderInfo[$batch_sn]['price_payed'] == $amount) {
                            continue;
                        }
                        echo "parent.insertRow2({$index}, '{$batch_sn}', '{$orderInfo[$batch_sn]['oinfo']}', '{$amount}', '{$lines[$i][2]}');";
                        $index++;
                    }
                    echo "</script>";
                }
            }
            
            exit;
        }
        $this -> view -> param = $search;
    }

	/**
	 * ajax修改赠送人
	 */
    public function giftbywhoAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$order_sn = $this -> _request -> getParam('order_sn', ''); $order_sn = trim($order_sn);
    	if($order_sn == ''){exit('参数错误');}
    	$val = $this -> _request -> getParam('val', ''); $val = trim($val);
    	if($val == ''){exit('赠送人必须');}
    	$rs = $this -> _api -> giftbywho($order_sn, $val);
    	echo $rs;
    	exit();
    }
    /**
     * 礼品卡支付
     *
     * @return void
     */
    public function addGiftCardPaymentAction()
    {
        $param = $this -> _request -> getParams();
        
        $cardAPI = new Admin_Models_API_GiftCard();
        $result = $cardAPI -> checkCard($param['gift_card_sn'], $param['batch_sn'], $param['gift_card_pay_amount']);
        if (!is_array($result)) {
            Custom_Model_Message :: showMessage($result);
        }
        
        $cardAPI = new Shop_Models_DB_Card();
        $card = array('card_type' => $result['card']['card_type'],
                      'card_price' => $param['gift_card_pay_amount'],
                      'card_sn' => $result['card']['card_sn'],
                      'card_pwd' => $result['card']['card_pwd'],
                      'add_time' => time(),
                      'admin_id' => $this -> _auth['admin_id'],
                      'admin_name' => $this -> _auth['admin_name'],
                      'batch_sn' => $param['batch_sn'],
                      'use_type' => 1,
                     );
        if ($cardAPI -> useGift($card)) {
            $this -> _api -> updateOrderPayed($param['batch_sn'], $param['gift_card_pay_amount'], 'gift_card');
            
            if (!$result['card']['user_id']) {
                $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $param['batch_sn'])));
                if ($order['user_id'] > 10) {
                    $cardAPI -> updateGiftCard(array('user_id' => $order['user_id'], 'user_name' => $order['user_name']), "card_sn = '{$param['gift_card_sn']}'");
                }
            }
        }
        else {
            Custom_Model_Message :: showMessage('抵扣错误!');
        }
        
        $url = $this -> getFrontController() -> getBaseUrl()."/admin/order/not-confirm-info/batch_sn/{$param['batch_sn']}";
        Custom_Model_Message :: showMessage('抵扣金额成功', $url);
        
        exit;
    }
    
    /**
     * 余额支付
     *
     * @return void
     */
    public function addAccountPaymentAction()
    {
        $param = $this -> _request -> getParams();
        
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $param['batch_sn'])));
        
        $memberAPI = new Admin_Models_API_Member();
        $memberInfo = $memberAPI -> getMemberByUserId($order['user_id']);
        if (!$memberInfo) {
            Custom_Model_Message :: showMessage('找不到会员账户!');
        }
        if ($memberInfo) {
            if ($memberInfo['money'] < $param['account_pay_amount']) {
                Custom_Model_Message :: showMessage('抵扣金额不能大于账户余额!');
            }
        }
        
        $memberAPI = new Shop_Models_API_Member();
        $tmp = array('member_id' => $memberInfo['member_id'],
                     'user_name' => $order['user_name'],
                     'order_id' => $order['order_batch_id'],
                     'accountValue' => $param['account_pay_amount'],
                     'accountTotalValue' => $member['money'],
                     'batch_sn' => $order['batch_sn'],
                     'note' => '客户支付订单抵扣');
        $memberAPI -> editAccount($memberInfo['member_id'], 'money', $tmp);
        
        $this -> _api -> updateOrderPayed($order['batch_sn'], $param['account_pay_amount'], 'account');
        
        $url = $this -> getFrontController() -> getBaseUrl()."/admin/order/not-confirm-info/batch_sn/{$param['batch_sn']}";
        Custom_Model_Message :: showMessage('抵扣金额成功', $url);
        
        exit;
    }
    
    /**
     * 退回礼品卡
     *
     * @return void
     */
    public function returnGiftCardAction()
    {
        $logID = $this -> _request -> getParam('log_id', 0);
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        if (!$logID || !$batchSN) {
            Custom_Model_Message :: showMessage('参数错误!');
        }
        
        if ($this -> _api -> returnGiftCard($batchSN, $logID)) {
            $url = $this -> getFrontController() -> getBaseUrl()."/admin/order/not-confirm-info/batch_sn/{$batchSN}";
            Custom_Model_Message :: showMessage('取消抵扣成功', $url);
        }
        else {
             Custom_Model_Message :: showMessage('找不到礼品卡!');
        }
        
        exit;
    }
    
    /**
     * 内部下单检验用户名(ajax)
     *
     * @return void
     */
    public function checkUsernameAction()
    {
        $userName = $this -> _request -> getParam('user_name', null);
        if (!$userName) exit;
        
        $memberAPI = new Admin_Models_API_Member();
        if ($member = $memberAPI -> getMemberByUserName($userName)) {
            if ($member['status']) {
                echo 'ok';
            }
            else {
                echo 'invalid';
            }
        }
        else {
            echo 'empty';
        }
        
        exit;
    }
 /*************跨境通测试Action   start***********************************************************************/   
    public function orderDeclareAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	include_once dirname($_SERVER['DOCUMENT_ROOT']).'/cron/kjt.php';
    }
    public function orderSearchAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	include_once dirname($_SERVER['DOCUMENT_ROOT']).'/cron/kjt_search.php';
    }
    
    public function refundOrderAction()
    {
    	$sn = $this -> _request -> getParam('sn');
    	if($sn){
    		$db = Zend_Registry::get('db');
    		$sql = "select a.*,b.pay_log_id,b.pay from shop_order_batch a inner join shop_order_pay_log b on a.batch_sn = b.batch_sn inner join shop_order_batch_goods c on a.order_batch_id = c.order_batch_id where a.batch_sn='{$sn}'";
    		$order = $db -> fetchRow($sql);
    		$easipay = new Custom_Model_Payment_Easipay();
    		$easipay -> orderRefund($order);
    	}
    	exit;
    	
    }
    
    public function orderDeclare1Action()
    {
    	$db = Zend_Registry::get('db');
    	$sn = $this -> _request -> getParam('sn');
    	$sql = "select distinct a.*,b.pay_log_id,b.serial_number,b.add_time as order_time,b.pay from shop_order_batch a inner join shop_order_pay_log b on a.batch_sn = b.batch_sn inner join shop_order_batch_goods c on a.order_batch_id = c.order_batch_id inner join shop_goods d on d.goods_id = c.goods_id where a.batch_sn='{$sn}'";
    	$datas = $db ->fetchAll($sql);
        
        $order_batch_ids = array_reduce($datas, create_function('$v,$w', '$v[]=$w["order_batch_id"];return $v;'));
        if(!$order_batch_ids){
            exit("All declared");
        }
        $order_batch_ids = implode(',', $order_batch_ids);
        $sql = "select order_batch_id,b.goods_name,b.goods_sn,a.number,b.shop_price,a.sale_price,a.tax,c.cat_name from shop_order_batch_goods a inner join shop_goods b on a.goods_id = b.goods_id left join shop_goods_cat c on c.cat_id = b.view_cat_id where a.order_batch_id in ({$order_batch_ids}) ";
        $goods = $db ->fetchAll($sql);
        $order_batch_goods = array();
        foreach ($goods as $k => $v){
            $order_batch_goods[$v['order_batch_id']][]=$v;
        }
        $kjt_api = new Admin_Models_API_Kjt();
        foreach ($datas as $k => $v){
            $kjt_api -> order_request($v,$order_batch_goods[$v['order_batch_id']]);
        }
        exit;
    }
    
    public function searchOrderAction()
    {
    	$sn = $this -> _request -> getParam('sn');
    	$type = $this -> _request -> getParam('type','00');
    	$no = $this -> _request -> getParam('no',null);
    	$easipay = new Custom_Model_Payment_Easipay();
    	$easipay -> searchOrder($type,$sn,$no);;
    	exit;
    }
    /*************跨境通测试Action   end***********************************************************************/
}

