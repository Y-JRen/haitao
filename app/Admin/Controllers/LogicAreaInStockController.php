<?php

class Admin_LogicAreaInStockController extends Zend_Controller_Action
{
    private $_api = null;

    private $_logicArea = null;

	private $_auth = null;

	private $_stock = null;
	
	private $_lid;

	const ADD_SUCCESS = '申请成功!';
	const CANCEL_SUCCESS = '申请取消成功!';
	const BACK_SUCCESS = '申请返回成功!';
	const CHECK_SUCCESS = '审核成功!';
	const CONFIRM_SUCCESS = '确认成功!';
	const RECEIVE_SUCCESS = '收货成功!';

	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init()
	{
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _api = new Admin_Models_API_InStock();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		$this -> _lid = $this -> _auth['lid'];

		$this -> _stock = Custom_Model_Stock_Base::getInstance($this -> _lid);
		$this -> _logicArea = $this -> _stock -> getArea();
		$this -> view -> logic_area = $this -> _stock -> getArea();
		$this -> _actions = $this -> _stock -> getConfigInAction();
		$this -> view -> actions = $this -> _stock -> getConfigInAction();
		$this -> view -> operates = $this -> _stock -> getConfigOperate();
		$this -> view -> billType = $this -> _stock -> getConfigInType();
		$this -> view -> areas = $this -> _stock -> getConfigLogicArea();
		$this -> view -> status = $this -> _stock -> getConfigLogicStatus();
		$this -> view -> billStatus = $this -> _stock -> getConfigBillInStatus();
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
	        $data = $this -> _api -> search($search, $action, $page, $this -> _logicArea);
		    $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');

		    $this -> view -> datas = $data['datas'];
		    $this -> view -> sum = $data['sum'];
	        $this -> view -> action = $action;
	        $this -> view -> param = $this -> _request -> getParams();
	        $this -> view -> catSelect = $this -> _cat ->buildProductSelect(array('name' => 'cat_id'));

            $supplierData = $this -> _api -> getSupplier('status = 0', 'supplier_id,supplier_name');
        	$this -> view -> supplier = $supplierData;

	        $this -> view -> pageNav = $pageNav -> getNavigation();
	        $this -> render('list');
        }
    }

    /**
     * 入库单查询
     *
     * @return void
     */
    public function searchListAction()
    {
    }

    /**
     * 入库单审核
     *
     * @return void
     */
    public function checkListAction()
    {
    }

    /**
     * 入库单确认
     *
     * @return void
     */
    public function confirmListAction()
    {
    }

    /**
     * 入库单收货
     *
     * @return void
     */
    public function receiveListAction()
    {
    }

    /**
     * 入库单取消
     *
     * @return void
     */
    public function cancelListAction()
    {
    }

    /**
     * 入库单取消
     *
     * @return void
     */
    public function setoverListAction()
    {
    }

    /**
     * 入库单申请
     *
     * @return void
     */
    public function addAction()
    {
    	if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> add($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
        	}
        }else{
            $type = $this -> _request -> getParam('type', 0);
            $supplierData = $this -> _api -> getSupplier('status = 0');
        	$this -> view -> supplier = $supplierData;
        	if ($supplierData) {
        	    $supplier = $supplierData[0];
        	    $this -> view -> bank = $supplier;
        	}
        	$this -> view -> billType = $this -> _stock -> getConfigAddInType($this -> _request -> getParam('type', null));
        }
    }

    /**
     * 入库单取消
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
     * 入库单取消审核
     *
     * @return void
     */
    public function cancelCheckAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> cancelCheck($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CANCEL_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        		$check = $p['is_check'];
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed($check)");
	        	}
            } else {
                $this -> view -> action = 'cancel-check';
                $datas = $this -> _api -> getPlan("a.instock_id=$id");

                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['plan_number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
                $this -> render('check');
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

    /**
     * 入库单审核
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
	        	}else{
	        		$check = $p['is_check'];
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed($check)");
	        	}
            } else {
                $this -> view -> action = 'check';
                $datas = $this -> _api -> getPlan("a.instock_id=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v) {
					$data['total_number'] += $v['plan_number'];
		        }

		        $supplier = array_shift($this -> _api -> getSupplier("supplier_id={$data['supplier_id']}"));
                $data['supplier_name'] = $supplier['supplier_name'];
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

    /**
     * 入库单确认
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
                $datas = $this -> _api -> getPlan("a.instock_id={$id} and (b.bill_type <> 1 or p.is_vitual <> 1)");
                $data = $datas[0];
                foreach ($datas as $num => $v) {
					$data['total_number'] += $v['plan_number'];
		        }
		        $supplier = array_shift($this -> _api -> getSupplier("supplier_id={$data['supplier_id']}"));
                $data['supplier_name'] = $supplier['supplier_name'];
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

    /**
     * 入库单收货
     *
     * @return void
     */
    public function receiveAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {

                $result = $this -> _api -> receive($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::RECEIVE_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
	        	}
            } else {
                $this -> view -> action = 'receive';
                $datas = $this -> _api -> getPlan("a.instock_id=$id");
                $data = $datas[0];

                if ($data['bill_type'] == 1 || $data['bill_type'] == 10) {
    		        $productAPI = new Admin_Models_API_Product();
    		    }
    		    
    		    //退货入库单，如果有退款，必须先开退款单
    		    if ($data['bill_type'] == 1 || $data['bill_type'] == 13) {
    		        $orderAPI = new Admin_Models_API_Order();
    		        $orderDetail = $orderAPI -> orderDetail($data['item_no']);
    		        if ($orderDetail['order']['status_pay'] == 1 && $orderDetail['finance']['status_return']) {
    		            $financeAPI = new Admin_Models_API_Finance();
    		            $financeData = $financeAPI -> getLastFinanceByItemNO(1, $data['item_no'], true);
    		            if (!$financeData) {
    		                $this -> view -> financeLimit = true;
    		            }
    		        }
    		    }

                foreach ($datas as $num => $v)  {
					if (strstr($v['bill_no'], '_')) {
						$root = explode('_', $v['bill_no']);
					    $pinfo = $this -> _api -> getPlan("a.instock_id <> {$id} and bill_no like '".$root[0]."%' and a.product_id=".$v['product_id'], "bill_no,plan_number,real_number");

						foreach($pinfo as $val) {
						    if (strstr($val['bill_no'], '_') === false) {
						        $datas[$num]['p_plan_number'] += $val['plan_number'];
						    }
						    $datas[$num]['p_real_number'] += $val['real_number'];
						}
					}else{
					    $datas[$num]['p_plan_number'] = $datas[$num]['plan_number'];
					    $datas[$num]['p_real_number'] = 0;
					}
					$data['total_number'] += $v['plan_number'];

					//渠道进仓退货需要重新选择批次
    		        if ($data['bill_type'] == 1 || $data['bill_type'] == 10) {
    		            $batchData = $productAPI -> getBatch(array('product_id' => $v['product_id']));
    		            $batchInfo = '';
    		            if ($batchData['data']) {
    		                foreach ($batchData['data'] as $batch) {
    		                    $batchInfo[$batch['batch_id']] = $batch['batch_no'];
    		                }
    		            }
    		            $this -> view -> batchInfo = $batchInfo;
    		        }
    		        
    		        $productIDArray[] = $v['product_id'];
		        }
		        
		        if ($productIDArray) {
		            $productAPI = new Admin_Models_API_Product();
		            $imageData = $productAPI -> getImg("product_id in (".implode(',', $productIDArray).") and img_type = 2");
		            if ($imageData) {
		                foreach ($imageData as $index => $image) {
		                    if (!$imageInfo[$image['product_id']] || count($imageInfo[$image['product_id']]) < 4) {
		                        $imageInfo[$image['product_id']][] = $image['img_url'];
		                    }
		                }
		            }
		            
		            foreach ($datas as $num => $v)  {
		                $datas[$num]['images'] = $imageInfo[$v['product_id']];
		            }
		        }

		        $supplier = array_shift($this -> _api -> getSupplier("supplier_id={$data['supplier_id']}"));
                $data['supplier_name'] = $supplier['supplier_name'];
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
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
        $bill_no = $this -> _request -> getParam('bill_no', null);
        if ($id > 0 || $bill_no) {
                $this -> view -> action = 'view';
                if (!$id) {
                    $bill = array_shift($this -> _api -> getMain("bill_no = '{$bill_no}'"));
                    $id = $bill['instock_id'];
                }
                $datas = $this -> _api -> getPlan("a.instock_id = '{$id}'");
                $data = $datas[0];
                foreach ($datas as $num => $v) {
					$data['total_number'] += $v['plan_number'];
					$data['total_real_number'] += $v['real_number'];
					$datas[$num]['status'] = $this -> _api -> getDetail("a.instock_id=$id and a.product_id='{$v['product_id']}'");
		        }
		        $supplier = array_shift($this -> _api -> getSupplier("supplier_id='{$data['supplier_id']}'"));
                $data['supplier_name'] = $supplier['supplier_name'];
                if ($data['bill_type'] == 2) {
                    $financeAPI = new Admin_Models_API_Finance();
                    $payments = $financeAPI -> getPurchaseData(array('bill_no' => $data['bill_no'], 'type' => 1));
                    $this -> view -> payment = $payments['data'][0];
                }

                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
                $this -> view -> op_cancel_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel-check'"));
                $this -> view -> op_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='check'"));
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

    /**
     * 查看动作
     *
     * @return void
     */
    public function setoverAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
           if ($this -> _request -> isPost()) {
           	    $result = $this -> _api -> setover($this -> _request -> getPost(), $id);
           	    Custom_Model_Message::showMessage('强制结束成功', 'event', 1250, "Gurl('refresh')");
           }else{
                $this -> view -> action = 'view';
                $datas = $this -> _api -> getPlan("a.instock_id=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['plan_number'];
					$data['total_real_number'] += $v['real_number'];
					$datas[$num]['status'] = $this -> _api -> getDetail("a.instock_id=$id and a.product_id='{$v['product_id']}'");
		        }
		        $supplier = array_shift($this -> _api -> getSupplier("supplier_id={$data['supplier_id']}"));
                $data['supplier_name'] = $supplier['supplier_name'];
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
                $this -> view -> op_cancel_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel-check'"));
                $this -> view -> op_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='check'"));
           }
        }else{
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
        if ($id > 0) {
                $this -> view -> action = 'print';
                $datas = $this -> _api -> getPlan("a.instock_id=$id");
                $data = $datas[0];
                if ($data['bill_status'] == 7) {
                    $details = $this -> _api -> getDetail("a.instock_id=$id");
                    foreach ($details as $temp) {
                        $detail[$temp['product_id']][$temp['batch_id']]['status_id'] = $temp['status_id'];
                        $detail[$temp['product_id']][$temp['batch_id']]['number'] = $temp['number'];
                    }
                }

                $stockAPI = new Admin_Models_API_Stock();
                foreach ($datas as $num => $v) {
					$data['total_number'] += $v['plan_number'];
					$totalAmount += $v['plan_number'] * $v['shop_price'];
					if ($data['bill_status'] == 7) {
					    $datas[$num]['real_number'] = $detail[$v['product_id']][$v['batch_id']]['number'];
					    $datas[$num]['status_id'] = $detail[$v['product_id']][$v['batch_id']]['status_id'];
					}

					$positionData = $stockAPI -> getProductPosition(array('product_id' => $v['product_id'], 'batch_id' => $v['batch_id'], 'area' => $data['lid']));
                    if ($positionData) {
                        foreach ($positionData as $position) {
                            $datas[$num]['position_no'] .= $position['position_no'].'<br>';
                        }
                    }
		        }
		        $supplier = array_shift($this -> _api -> getSupplier("supplier_id={$data['supplier_id']}"));
                $data['supplier_name'] = $supplier['supplier_name'];
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> auth = $this -> _auth;
                $this -> view -> totalAmount = $totalAmount;
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
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
     * 获得供应商信息(ajax调用)
     *
     * @return void
     */
    public function getSupplierAction()
    {
        $supplier_id = (int)$this -> _request -> getParam('supplier_id', 0);
        if (!$supplier_id)  exit;

        $supplier = array_shift($this -> _api -> getSupplier("supplier_id = {$supplier_id}"));
        echo Zend_Json::encode($supplier);

        exit;
    }

}
