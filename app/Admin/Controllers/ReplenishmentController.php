<?php
class Admin_ReplenishmentController extends Zend_Controller_Action
{
	private $_api;
	private $_lid;
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Replenishment();
		$this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
		$this -> _lid = $this -> _auth['lid'];
	}
	
	/**
     * 补货单列表
     *
     * @return   void
     */
	public function indexAction() 
	{
	    $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $search['lid'] = $this->_lid;
        $total = $this -> _api -> getProductRecordCount($search);
        if ($total > 0) {
            $datas = $this -> _api -> getProductRecord($search, null, $page, 25);
            foreach ($datas as $num => $data) {
                $ids[] = $data['replenishment_id'];
            }
            $billData = $this -> _api -> getBillRecord("t1.replenishment_id in (".implode(',', $ids).")");
            if ($billData) {
                foreach ($billData as $data) {
                    $billInfo[$data['replenishment_id']][] = $data['bill_no'];
                }
            }
        }

        $this -> view -> datas = $datas;
        $this -> view -> param = $search;
        $this -> view -> billInfo = $billInfo;
	    $pageNav = new Custom_Model_PageNav($total, 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	
	/**
     * 补货开单
     *
     * @return   void
     */
	public function applyAction() 
	{
        $search = $this -> _request -> getParams();
        $search['status'] = 0;
        $search['lid'] = $this->_lid;
        $total = $this -> _api -> getProductRecordCount($search);
        if ($total > 0) {
            $datas = $this -> _api -> getProductRecord($search);
        }
        
        $tempDatas = $this -> _api -> getProductRecord(array('status' => 0));
        if ($tempDatas) {
            $productIDArray = array();
            foreach ($tempDatas as $num => $data) {
                if (!in_array($data['product_id'], $productIDArray)) {
                    $productIDArray[] = $data['product_id'];
                }
            }
            $supplierAPI = new Admin_Models_API_Supplier();
            $this -> view -> supplierData = $supplierAPI -> getProductSupplier($productIDArray);
        }
        
        $this -> view -> datas = $datas;
        $this -> view -> param = $search;
	}
	
	/**
     * 添加补货入库单
     *
     * @return   void
     */
	public function newAction()
	{
	    if ($this -> _request -> isPost()) {
	        $instockAPI = new Admin_Models_API_InStock();
	        $tmp = $this -> _request -> getPost();
	        $tmp['logic_area'] = $this -> _lid;
	        $billNo = $instockAPI -> add($tmp);
        	if ($billNo) {
        	    $ids = explode(',', $this -> _request -> getParam('ids'));
        	    $number = $this -> _request -> getParam('number');
        	    $this -> _api -> addBill($billNo, $ids, $number);
        	    $this -> _api -> updateProductStatus($ids, 1);
        	    Custom_Model_Message::showMessage('申请成功', 'event', 1250, "Gurl('refresh')");
        	}
        	else{
        	    Custom_Model_Message::showMessage($instockAPI -> error(), 'event', 1250, "failed()");
        	}
	    }
	    
	    $supplier_id = $this -> _request -> getParam('supplier_id', 0);
	    $ids = $this -> _request -> getParam('ids', null);
	    
	    if (!$supplier_id || !$ids) exit;
	    
	    $supplierAPI = new Admin_Models_API_Supplier();
	    $this -> view -> supplier = array_shift(array_shift($supplierAPI -> get("supplier_id = '{$supplier_id}'")));
	    
	    $datas = $this -> _api -> getProductRecord(array('ids' => explode(',', $ids)));
	    if (!$datas)    exit;
	    
	    $ids = array();
	    foreach ($datas as $data) {
	        $ids[] = $data['replenishment_id'];
	    }
	    
	    $this -> view -> datas = $datas;
	    $this -> view -> replenishment_ids = implode(',', $ids);
	}
	
	/**
     * 补货单详情
     *
     * @return   void
     */
	public function viewAction() 
	{
	    $id = $this -> _request -> getParam('id', 0);
	    if (!$id)   exit;
	    
	    if ($this -> _request -> isPost()) {
	        $this -> _api -> updateProductStatus($id, 1);
	        Custom_Model_Message::showMessage('确认成功', 'event', 1250, "Gurl('refresh')");
	    }
	    
	    $data = array_shift($this -> _api -> getProductRecord(array('replenishment_id' => $id)));
	    
	    $this -> view -> details = $this -> _api -> getOrderRecord("t1.replenishment_id = '{$id}'");
	    $this -> view -> data = $data;
	}
	
	/**
     * 添加补货商品
     *
     * @return   void
     */
	public function addProductAction() 
	{
	    $ids = $this -> _request -> getParam('ids', null);
	    if (!$ids)  exit;
	    
	    if ($this -> _api -> addProduct(explode('|', $ids))) {
	        Custom_Model_Message::showMessage('添加成功', 'event', 1250, "Gurl('refresh')");
	    }
	    else {
	        Custom_Model_Message::showMessage('添加失败', 'event', 1250, "Gurl('refresh')");
	    }
	}
	
	/**
     * 调整补货商品数量
     *
     * @return   void
     */
	public function changeProductNumberAction() 
	{
	    $id = $this -> _request -> getParam('id', 0);
	    $number = $this -> _request -> getParam('number', 0);
	    if (!$id)   exit;
	    
	    if ($this -> _api -> changeProductNumber($id, $number)) { 
	        die('ok');
	    }
	    
	    exit;
	}

    /**
     * 删除无效的补货订单
     *
     * @return   void
     */
    public function deleteInvalidOrderAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        //删除官网补货订单数据
        $this->_api->deleteInvalidReplenishOrder(array('gt_status_logistic' => 1, 'status' => 0, 'type' => 2));
        //删除渠道补货订单数据
        $this->_api->deleteInvalidReplenishOrder(array('gt_status_business' => 0, 'status' => 0, 'type' => 0));

        exit('删除成功');
    }
	
}