<?php

class Admin_CodController extends Zend_Controller_Action
{
	/**
     * api对象
     */
    private $_api = null;
    
	/**
     * Admin certification
     * @var array
     */
	private $_auth = null;
    
	const ADD_SUCCESS = '添加成功!';
	const CANCEL_SUCCESS = '申请取消成功!';
	const CHECK_SUCCESS = '审核成功!';
	const CHANGE_SUCCESS = '申请变更成功!';
	const CLEAR_SUCCESS = '结算成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _logistic = new Admin_Models_DB_Logistic();
		$this -> _api = new Admin_Models_API_Cod();
		$this -> _transport = new Admin_Models_API_Transport();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		$stock = new Custom_Model_Stock_Config;
		$this -> _actions = $stock -> _codActions;
		$this -> view -> actions = $stock -> _codActions;
		$this -> view -> operates = $stock -> _operates;
		$this -> view -> logisticStatus = $stock -> _logisticStatus;
		$this -> view -> billType = $stock -> _tranTypes;
		foreach ($this -> _logistic -> getLogisticList() as $key => $value)
    	{
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	    $this -> _logistics[$value['logistic_code']] = $value;
    	}
    	$this -> view -> logisticList = $logisticList;
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
		    $page = (int)$this -> _request -> getParam('page', 1);
	        $data = $this -> _api -> search($search, $action, $page);
		    $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
		    
		    $this -> view -> datas = $data['datas'];
	        $this -> view -> action = $action;
	        $this -> view -> param = $this -> _request -> getParams();
	        $this -> view -> pageNav = $pageNav -> getNavigation();
	        $this -> render('list');
        }
    }
    
    /**
     * 代收货款变更查询
     *
     * @return void
     */
    public function searchListAction()
    {
    }
    
    /**
     * 代收货款变更审核
     *
     * @return void
     */
    public function checkListAction()
    {
    }
    
    /**
     * 代收货款变更申请
     *
     * @return void
     */
    public function codChangeAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> codChange($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CHANGE_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
	        	}
            } else {
                $datas = $this -> _transport -> get("tid=$id");
                $data = $datas[0];
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 代收货款查询
     *
     * @return void
     */
    public function codListAction()
    {
    	$search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
        $data = $this -> _transport -> search($search, 'cod-list', $page);
	    $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
	    $this -> view -> datas = $data['datas'];
	    $this -> view -> sum = $data['amount'];
	    $member = new Admin_Models_API_Member();
        $this -> view -> province = $member -> getChildAreaById(1);
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 代收货款变更
     *
     * @return void
     */
    public function codChangeListAction()
    {
    	$search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
        $data = $this -> _transport -> search($search, 'cod-change-list', $page);
	    $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
	    $this -> view -> datas = $data['datas'];
	    $this -> view -> sum = $data['amount'];
	    $member = new Admin_Models_API_Member();
        $this -> view -> province = $member -> getChildAreaById(1);
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 查看动作
     *
     * @return void
     */
    public function viewCodAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
                $this -> view -> action = 'view';
                $datas = $this -> _transport -> get("tid=$id");
                $data = $datas[0];
                $this -> view -> data = $data;
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 结算查询
     *
     * @return void
     */
    public function viewClearAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
	        $datas = $this -> _api -> viewClear($id);
	        foreach ($datas['details'] as $num => $data) {
	        	$bill['fee_service'] += $data['logistic_fee_service'];
	        	$bill['cod_price'] += $data['logistic_price_cod'];
	        	$bill['back_amount'] += $data['back_amount'];
	        }
	    	$this -> view -> bill = array_merge($datas['data'], $bill);
	    	$this -> view -> datas = $datas['details'];
    	}
    }
    
    /**
     * 代收货款结算
     *
     * @return void
     */
    public function clearAction()
    {
    	if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> clear($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::CLEAR_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
        	}
        }
    }
    
    /**
     * 结算单查询
     *
     * @return void
     */
    public function clearListAction()
    {
    	$search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
        $datas = $this -> _api -> getClear($search, $page);
        $total = $this -> _api -> getCount();
	    $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
	    
	    $this -> view -> datas = $datas;
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 代收货款变更审核
     *
     * @return void
     */
    public function checkAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> check($this -> _request -> getParams(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CHECK_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        		$check = $p['is_check'];
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed($check)");
	        	}
            } else {
                $this -> view -> action = 'check';
                $datas = $this -> _api -> get("a.cid=$id");
                $data = $datas[0];
                $this -> view -> data = $data;
                $this -> view -> op = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
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
        if ($id > 0) {
            $this -> view -> action = 'view';
            $datas = $this -> _api -> get("a.cid=$id");
            $data = $datas[0];
            $this -> view -> data = $data;
            $this -> view -> op_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='check'"));
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
	        $datas = $this -> _api -> viewClear($id);
	        foreach ($datas['details'] as $num => $data) {
	        	$bill['fee_service'] += $data['logistic_fee_service'];
	        	$bill['cod_price'] += $data['logistic_price_cod'];
	        	$bill['back_amount'] += $data['back_amount'];
	        }
	    	$this -> view -> bill = array_merge($datas['data'], $bill);
	    	$this -> view -> datas = $datas['details'];
    	}else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
	/**
     * 锁定/解锁动作 (针对 shop_cod_change)
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
     * 锁定/解锁动作 (针对 shop_transport)
     *
     * @return   void
     */
    public function lockTransportAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('val', 0);
    	$this -> _api -> lockTransport($this -> _request -> getPost(), $val);
    }
    
}
