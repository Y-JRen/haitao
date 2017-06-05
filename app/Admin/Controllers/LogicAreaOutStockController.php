<?php

class Admin_LogicAreaOutStockController extends Zend_Controller_Action
{
	private $_api = null;
    
    private $_logicArea = null;
    
	private $_auth = null;
	
	private $_stock = null;
	
	private $_lid;
    
	const ADD_SUCCESS = '申请成功!';
	const CANCEL_SUCCESS = '申请取消成功!';
	const BACK_SUCCESS = '申请返回成功!';
	const CONFIRM_SUCCESS = '确认成功!';
	const CHECK_SUCCESS = '审核成功!';
	const SEND_SUCCESS = '发货成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _api = new Admin_Models_API_OutStock();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		$this -> _lid = $this -> _auth['lid'];
		
		$this -> _stock = Custom_Model_Stock_Base::getInstance($this -> _lid);
		$this -> _logicArea = $this -> _stock -> getArea();
		$this -> view -> logic_area = $this -> _stock -> getArea();
		$this -> _actions = $this -> _stock -> getConfigOutAction();
		$this -> view -> actions = $this -> _stock -> getConfigOutAction();
		$this -> view -> operates = $this -> _stock -> getConfigOperate();
		$this -> view -> billType = $this -> _stock -> getConfigOutType();
		$this -> view -> areas = $this -> _stock -> getConfigLogicArea();
		$this -> view -> status = $this -> _stock -> getConfigLogicStatus();
		$this -> view -> billStatus = $this -> _stock -> getConfigBillOutStatus();
		$this -> view -> area_name = $this -> _stock -> getAreaName();
	}
	
	/**
     * 预处理
     *
     * @return   void
     */
	public function postDispatch()
    {
    	$action = $this -> _request -> getActionName();
        if (array_key_exists($action, $this -> _actions)) {
			$search = $this -> _request -> getParams();
			if ($search['logic_area'] == 99) {
			    $search['logic_area'] = $this -> _logicArea;
			}
		    $page = (int)$this -> _request -> getParam('page', 1);
		    $search['hideExternal'] = 1;
	        $data = $this -> _api -> search($search, $action, $page, $this -> _logicArea);
		    $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
		    
		    if ($data['datas']) {
		        foreach ($data['datas'] as $temp) {
		            $outstockIDArray[] = $temp['outstock_id'];
		        }
		        $details = $this -> _api -> getDetail("a.outstock_id in (".implode(',', $outstockIDArray).")");
		        if ($details) {
		            foreach ($details as $detail) {
		                $amountInfo[$detail['outstock_id']]['cost'] += $detail['number'] * $detail['cost'];
		                $amountInfo[$detail['outstock_id']]['no_tax_cost'] += round($detail['cost'] / (1 + $detail['invoice_tax_rate'] / 100) * $detail['number'], 2);
		            }
		            foreach ($data['datas'] as $index => $temp) {
		                $data['datas'][$index]['amount'] = $amountInfo[$temp['outstock_id']]['cost'];
		                $data['datas'][$index]['no_tax_amount'] = $amountInfo[$temp['outstock_id']]['no_tax_cost'];
		                $data['datas'][$index]['bill_no'] = str_replace(',', '<br>', $temp['bill_no']);
		            }
		        }
		    }
		    
		    $this -> view -> datas = $data['datas'];
		    $this -> view -> sum = $data['sum'];
	        $this -> view -> action = $action;
	        $this -> view -> param = $this -> _request -> getParams();
	        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
	        $this -> view -> pageNav = $pageNav -> getNavigation();
	        $this -> view -> auth = $this -> _auth;
	        if ($action == 'send-list') {
	            $this -> render('send-list');
	        }
	        else if ($action == 'batch-send-list') {
                $this -> render('batch-send-list');
            }
            else {
                $this -> render('list');
	        }
        }
    }
    
    /**
     * 出库单查询
     *
     * @return void
     */
    public function searchListAction()
    {
    }
    
    /**
     * 出库单审核
     *
     * @return void
     */
    public function checkListAction()
    {
    }
    
    /**
     * 出库单确认
     *
     * @return void
     */
    public function confirmListAction()
    {
    }
    
    /**
     * 出库单发货
     *
     * @return void
     */
    public function sendListAction()
    {
    }
    
    /**
     * 出库单取消
     *
     * @return void
     */
    public function cancelListAction()
    {
    }
    
    /**
     * 批量出库单发货
     *
     * @return void
     */
    public function batchSendListAction()
    {
    }
    
    /**
     * 出库单申请
     *
     * @return void
     */
    public function addAction()
    {
    	if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> add($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}
        	else {
        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
        	}
        }
        else {
            $type = $this -> _request -> getParam('type', 0);
        
            $this -> view -> supplier = $this -> _api -> getSupplier();
            $this -> view -> type = $type;
        	$member = new Admin_Models_API_Member();
        	$this -> view -> province = $member -> getChildAreaById(1);
        	
        	$this -> view -> billType = $this -> _stock -> getConfigAddOutType($type);
        }
    }
    
    /**
     * 出库单取消
     *
     * @return void
     */
    public function cancelAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> cancel($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CANCEL_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'cancel';
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 出库单取消审核
     *
     * @return void
     */
    public function cancelCheckAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> cancelCheckAdv($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CHECK_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}
	        	else {
	        		$check = $p['is_check'];
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed($check)");
	        	}
            } 
            else {
                $this -> view -> action = 'cancel-check';
                $datas = $this -> _api -> getDetail("a.outstock_id=$id");
                $data = $datas[0];
                $data['bill_no_array'] = $this -> _api -> getMergeSplitOrderSNArray($data['bill_no']);
                foreach ($datas as $num => $v) {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
                $this -> render('check');
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 出库单审核
     *
     * @return void
     */
    public function checkAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> check($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CHECK_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}
	        	else {
	        		$p = $this -> _request -> getPost();
	        		$check = $p['is_check'];
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed($check)");
	        	}
            } else {
                $this -> view -> action = 'check';
                $datas = $this -> _api -> getDetail("a.outstock_id=$id");
                $data = $datas[0];
                
                $data['bill_no_array'] = $this -> _api -> getMergeSplitOrderSNArray($data['bill_no']);
                foreach ($datas as $num => $v) {
					$data['total_number'] += $v['number'];
					$data['total_amount'] += $v['staff_price'] * $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 出库单确认
     *
     * @return void
     */
    public function confirmAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> confirm($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CONFIRM_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'confirm';
                $datas = $this -> _api -> getDetail("a.outstock_id=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 批量确认
     *
     * @return   void
     */
    public function confirmsAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) > 0) {
    		foreach($post['ids'] as $k => $v) {
    			$this -> _api -> confirm($post['info'][$v], $v);
    		}
    	} 
    	else {
    		exit;
    	}
    }
    
    /**
     * 出库单批量发货
     *
     * @return void
     */
    public function batchSendAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $post = $this -> _request -> getPost();
        if (count($post['ids']) > 0) {
            foreach($post['ids'] as $id ){
                $where = "a.outstock_id=$id and bill_status=4";
                $datas = $this -> _api -> getDetail($where);
                $this -> _api -> batchSend($datas[0], $id);
            }
        }
    }
    
    /**
     * 出库单发货
     *
     * @return void
     */
    public function sendAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $bill_no = $this -> _request -> getParam('bill_no', null);
        if ($id > 0 || $bill_no) {
            if ($this -> _request -> isPost()) {
                $post = $this -> _request -> getPost();
                if ($post['toback']) {
                    $this -> _api -> backToPrint($bill_no);
                    Custom_Model_Message::showMessage(self::BACK_SUCCESS, 'event', 1250, "Gurl('refresh')");
                }
                else {
                    $result = $this -> _api -> send($this -> _request -> getPost(), $id);
    	        	if ($result) {
    	        	    Custom_Model_Message::showMessage(self::SEND_SUCCESS, 'event', 1250, "Gurl('refresh')");
    	        	}
    	        	else {
    	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
    	        	}
    	        }
            } 
            else {
                $this -> view -> action = 'send';
                $bill_no = str_replace('*', '_', $bill_no);
                $where = $id ? "a.outstock_id = {$id} and bill_status = 4" : "bill_no = '{$bill_no}' and is_cancel = 0 and bill_status = 4";
                $datas = $this -> _api -> getDetail($where);
                !$datas && exit('无对应需要发货信息');
                $data = $datas[0];
                $data['bill_no_array'] = $this -> _api -> getMergeSplitOrderSNArray($data['bill_no']);
                foreach ($datas as $num => $v) {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 查看动作
     *
     * @return void
     */
    public function viewAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            $this -> view -> action = 'view';
            $datas = $this -> _api -> getDetail("a.outstock_id=$id","a.cost as p_cost,a.*,b.*,p.*,pb.batch_no");
            $data = $datas[0];
            foreach ($datas as $num => $v) {
                $data['total_number'] += $v['number'];
		    }
		    $supplier = array_shift($this -> _api -> getSupplier("supplier_id={$data['supplier_id']}"));
            $data['supplier_name'] = $supplier['supplier_name'];
                
            if ($data['bill_type'] == 2) {
                $financeAPI = new Admin_Models_API_Finance();
                $payments = $financeAPI -> getPurchaseData(array('bill_no' => $data['bill_no'], 'type' => 2));
                $this -> view -> receive = $payments['data'][0];
            }
            
            $data['bill_no_array'] = $this -> _api -> getMergeSplitOrderSNArray($data['bill_no']);
            
            $this -> view -> data = $data;
            $this -> view -> details = $datas;
            $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
            $this -> view -> op_cancel_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel-check'"));
            $this -> view -> op_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='check'"));
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 打印动作
     *
     * @return void
     */
    public function printAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $bill_no = $this -> _request -> getParam('bill_no', null);
        if ($id > 0 || $bill_no) {
                $orderAPI = new Admin_Models_API_Order();
		        $transportAPI = new Admin_Models_API_Transport();
                $stockAPI = new Admin_Models_API_Stock();
                
                $this -> view -> action = 'print';
                $where = $id ? "a.outstock_id=$id" : "bill_no='$bill_no' and is_cancel=0";
                $r = $this -> _api -> getDetail($where);
                
                $total_number = 0;
                foreach ($r as $index => $v) {
                    $total_number += $v['number'];
                    
                    $positionData = $stockAPI -> getProductPosition(array('product_id' => $v['product_id'], 'batch_id' => $v['batch_id'], 'area' => $v['lid']));
                    if ($positionData) {
                        foreach ($positionData as $position) {
                            $r[$index]['position_no'] .= $position['position_no'].'<br>';
                        }
                    }
                }
                
		        $datas[0]['details'] = $r;
		        $num = 0;
                $batchSNArray = $transportAPI -> getMergeSplitOrderSNArray($r[0]['bill_no']);
                foreach ($batchSNArray as $batchSN) {
                    $data = $orderAPI -> getOrderBathWithPage(array('batch_sn' => $batchSN));
                    $order = $data['data'][0];
                    if ($datas[$num]['order']) {
                        $datas[$num]['order']['price_order'] += $order['price_order'];
                        $datas[$num]['order']['price_adjust'] += $order['price_adjust'];
                        $datas[$num]['order']['price_pay'] += $order['price_pay'];
                        $datas[$num]['order']['price_from_return'] += $order['price_from_return'];
                        $datas[$num]['order']['price_payed'] += $order['price_payed'];
                        $datas[$num]['order']['product'] = array_merge($datas[$num]['order']['product'], $data['product']);
                    }
                    else {
                        $order['product'] = $data['product'];
                        $datas[$num]['order'] = $order;
                    }
                }
		        
		        $total_amount += $r[0]['transport']['amount'];
                if ($r[0]['transport']['is_cod']) {
                    $cod_amount += $r[0]['transport']['amount'];
                }
		        $datas[0]['bill'] = $r[0];
                $datas[0]['total_number'] = $total_number;
                $datas[0]['total_amount'] = $total_amount;
                $supplier = array_shift($this -> _api -> getSupplier("supplier_id='{$datas[0]['bill']['supplier_id']}'"));
                $datas[0]['bill']['supplier_name'] = $supplier['supplier_name'];
		        $this -> view -> datas = $datas;
                
                if ($r[0]['bill_type'] == 1) {
                    $this -> render('print_orders');
                }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 批量打印
     *
     * @return   void
     */
    public function printsAction()
    {
    	$post = $this -> _request -> getPost();
    	
    	$search = $this -> _request -> getParams();
    	if (count($post['ids']) > 0 && count($post['bill_no']) > 0 && $search['bill_type']!='' && $search['is_cod']!='') {
	        $orderAPI = new Admin_Models_API_Order();
	        $transportAPI = new Admin_Models_API_Transport();
	        $productAPI = new Admin_Models_DB_Product();
	    	foreach ($post['ids'] as $num => $v) {
		    	$bill_no = $post['bill_no'][$num];
		    	$r = $this -> _api -> getDetail("b.bill_no='$bill_no' and is_cancel=0");
		        if ($r){
		            if (!$r[0]['logistic_no'] && $r[0]['transport']['logistic_code'] != 'self') {
		                die("请先填充对应的运单号再打印");
		            }
		            
                    $datas[$num]['details'] = $r;
                    $batchSNArray = $transportAPI -> getMergeSplitOrderSNArray($r[0]['bill_no']);
                    foreach ($batchSNArray as $batchSN) {
                        $data = $orderAPI -> getOrderBathWithPage(array('batch_sn' => $batchSN, 'includeOffer' => true, 'includeCoupon' => true));
                        $order = $data['data'][0];
                        $order['discount'] = 0;
                        $hasGiftCard = false;
                        $onlyGiftCard = true;
                        $priceGoods = $giftCardAmount = $giftCardPrice = 0;
                        foreach ($data['product'] as $index => $goods) {
                            if ($goods['product_id']) {
                                if (!isset($productNameInfo[$goods['product_id']])) {
                                    $product = array_shift($productAPI -> fetch("p.product_id = '{$goods['product_id']}'"));
                                    $productNameInfo[$goods['product_id']] = $product['product_name'];
                                }
                                $data['product'][$index]['goods_name'] = $productNameInfo[$goods['product_id']];
                                
                                if ($goods['is_gift_card']) {
                                    $hasGiftCard = true;
                                    $giftCardPrice += $goods['sale_price'] * $goods['number'];
                                }
                                else {
                                    $onlyGiftCard = false;
                                }
                                $priceGoods += $goods['sale_price'] * ($goods['number'] - $goods['return_number']);
                                $giftCard = $productAPI -> getGiftcardInfoByProductid($goods['product_id']);
                                $giftCardAmount += $giftCard['amount'];
                            }
                            else if ($goods['type'] != 5 && !$goods['parent_id']){
                                $order['discount'] += $goods['sale_price'] * ($goods['number'] - $goods['return_number']);
                            }
                        }
                        
                        if ($hasGiftCard && !$onlyGiftCard) {
                            $order['price_goods'] = $priceGoods;
                            $order['price_order'] = $priceGoods + $order['discount'] + $order['price_adjust'];
                            if ($priceGoods + $order['discount'] + $order['price_adjust'] - $giftCardPrice >= $giftCardPrice) {
                                $order['gift_card_margin'] = $giftCardAmount;
                            }
                            else {
                                $order['gift_card_margin'] = $priceGoods + $order['discount'] + $order['price_adjust'] - $giftCardPrice;
                            }
                        }
                        
                        if ($datas[$num]['order']) {
                            $datas[$num]['order']['price_order'] += $order['price_order'];
                            $datas[$num]['order']['price_goods'] += $order['price_goods'];
                            $datas[$num]['order']['price_adjust'] += $order['price_adjust'];
                            $datas[$num]['order']['price_pay'] += $order['price_pay'];
                            $datas[$num]['order']['price_from_return'] += $order['price_from_return'];
                            $datas[$num]['order']['price_payed'] += $order['price_payed'];
                            $datas[$num]['order']['account_payed'] += $order['account_payed'];
                            $datas[$num]['order']['point_payed'] += $order['point_payed'];
                            $datas[$num]['order']['gift_card_payed'] += $order['gift_card_payed'];
                            $datas[$num]['order']['discount'] += $order['discount'];
                            $datas[$num]['order']['product'] = array_merge($datas[$num]['order']['product'], $data['product']);
                        }
                        else {
                            $order['product'] = $data['product'];
                            $datas[$num]['order'] = $order;
                        }
                    }
                    
                    $total_amount += $r[0]['transport']['amount'];
                    if ($r[0]['transport']['is_cod']) {
                        $cod_amount += $r[0]['transport']['amount'];
                    }
                    $total_number = 0;
                    foreach ($r as $v) {
                        $total_number += $v['number'];
                        
                        $pickGoods[$v['product_sn']][$v['batch_no']]['product_sn'] = $v['product_sn'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['goods_name'] = $v['goods_name'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['goods_style'] = $v['goods_style'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['number'] += $v['number'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['order'][$bill_no] += $v['number'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['local_sn'] = $v['local_sn'];
                        
                        $pickGoods['total_number'] += $v['number'];
                    }
                    
                    $datas[$num]['bill'] = $r[0];
                    $datas[$num]['total_number'] = $total_number;
                    $datas[$num]['total_amount'] = $total_amount;
                    unset($r);
		        }
		    }
            
            $this -> view -> total_number = $pickGoods['total_number'];
            unset($pickGoods['total_number']);
            
            if ($pickGoods) {
                foreach ($pickGoods as $productSN => $pickGoods1) {
                    foreach ($pickGoods1 as $batchNo => $goods) {
                        $orderNumber = '';
                        foreach ($goods['order'] as $billNo => $number) {
                            $orderNumber .= $billNo.'('.$number.'件)<br>';
                        }
                        $pickGoods[$productSN][$batchNo]['order'] = $orderNumber;
                    }
                }
            }
	    	$this -> view -> datas = $datas;
	    	$this -> view -> pickGoods = $pickGoods;
	    	$this -> view -> shopInfo = $shopInfo;
	    	
            $this -> render('print_orders');
            
    	} else {
    		exit('no content');
    	}
    }
    
    /**
     * 批量打印拣货单
     *
     * @return   void
     */
    public function printsPickordersAction()
    {
    	$post = $this -> _request -> getPost();
    	
    	$search = $this -> _request -> getParams();
    	if (count($post['ids']) > 0 && count($post['bill_no']) > 0) {
	        $orderAPI = new Admin_Models_API_Order();
	        $transportAPI = new Admin_Models_API_Transport();
	        $stockAPI = new Admin_Models_API_Stock();
	    	foreach ($post['ids'] as $num => $v) {
		    	$bill_no = $post['bill_no'][$num];
		    	$r = $this -> _api -> getDetail("b.bill_no='{$bill_no}' and is_cancel=0 and p.is_vitual = 0");
		        if ($r){
                    $datas[$num]['details'] = $r;
                    $batchSNArray = $transportAPI -> getMergeSplitOrderSNArray($r[0]['bill_no']);
                    foreach ($batchSNArray as $batchSN) {
                        $order = array_shift($orderAPI -> getOrderBatch(array('batch_sn' => $batchSN)));
                        if ($datas[$num]['order']) {
                            $datas[$num]['order']['price_order'] += $order['price_order'];
                            $datas[$num]['order']['price_adjust'] += $order['price_adjust'];
                            $datas[$num]['order']['price_pay'] += $order['price_pay'];
                            $datas[$num]['order']['price_from_return'] += $order['price_from_return'];
                            $datas[$num]['order']['price_payed'] += $order['price_payed'];
                        }
                        else {
                            $datas[$num]['order'] = $order;
                        }
                    }
                    
                    $total_amount += $r[0]['transport']['amount'];
                    if ($r[0]['transport']['is_cod']) {
                        $cod_amount += $r[0]['transport']['amount'];
                    }
                    $total_number = 0;
                    
                    foreach ($r as $v) {
                        $positionData = $stockAPI -> getProductPosition(array('area' => $v['lid'], 'product_id' => $v['product_id'], 'batch_id' => $v['batch_id']));
                        if ($positionData) {
                            foreach ($positionData as $position) {
                                if ($positionInfo[$v['product_id']][$v['batch_id']]) {
                                    if (!in_array($position['position_no'], $positionInfo[$v['product_id']][$v['batch_id']])) {
                                        $positionInfo[$v['product_id']][$v['batch_id']][] = $position['position_no'];
                                    }
                                }
                                else {
                                    $positionInfo[$v['product_id']][$v['batch_id']][] = $position['position_no'];
                                }
                            }
                        }
                    }
                    
                    foreach ($r as $v) {
                        $total_number += $v['number'];
                        
                        $pickGoods[$v['product_sn']][$v['batch_no']]['product_sn'] = $v['product_sn'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['goods_name'] = $v['goods_name'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['goods_style'] = $v['goods_style'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['number'] += $v['number'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['order'][$bill_no] += $v['number'];
                        $pickGoods[$v['product_sn']][$v['batch_no']]['local_sn'] = $positionInfo[$v['product_id']][$v['batch_id']] ? implode('<br>', $positionInfo[$v['product_id']][$v['batch_id']]) : '';
                        
                        $pickGoods['total_number'] += $v['number'];
                    }
                    
                    $datas[$num]['bill'] = $r[0];
                    $datas[$num]['total_number'] = $total_number;
                    $datas[$num]['total_amount'] = $total_amount;
                    unset($r);
		        }
		    }
            
            
            $this -> view -> total_number = $pickGoods['total_number'];
            unset($pickGoods['total_number']);
            
            if ($pickGoods) {
                uasort($pickGoods, 'compareProductLocalSN');
                
                foreach ($pickGoods as $productSN => $pickGoods1) {
                    foreach ($pickGoods1 as $batchNo => $goods) {
                        $orderNumber = '';
                        foreach ($goods['order'] as $billNo => $number) {
                            $orderNumber .= $billNo.'('.$number.'件)<br>';
                        }
                        $pickGoods[$productSN][$batchNo]['order'] = $orderNumber;
                    }
                }
            }
            
            $this -> view -> datas = $datas;
            $this -> view -> pickGoods = $pickGoods;
            $this -> render('pickorders');

    	} else {
    		exit('no content');
    	}
    }
    
	/**
     * 锁定/解锁动作
     *
     * @return   void
     */
    public function lockAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('val', 0);
    	$this -> _api -> lock($this -> _request -> getPost(), $val);
    }
    
    /**
     * 条码动作
     *
     * @return   void
     */
    public function barcodeAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $code = $this -> _request -> getParam('code');
        $barcode = new Custom_Model_Barcode();
        $barcode -> makecode($code);
    }
    
}

function compareProductLocalSN($a, $b)
{
    $a = array_shift($a);
    $b = array_shift($b);
    return $a['local_sn'] > $b['local_sn'];
}