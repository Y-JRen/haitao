<?php

class Admin_LogicAreaStatusController extends Zend_Controller_Action
{
	private $_api = null;
    
    private $_logicArea = null;
    
	private $_auth = null;
	
	private $_stock = null;
	
	private $_lid;
    
	const ADD_SUCCESS = '申请成功!';
	const CANCEL_SUCCESS = '申请取消成功!';
	const CHECK_SUCCESS = '审核成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _api = new Admin_Models_API_Status();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		$this -> _lid = $this -> _auth['lid'];
		
		$this -> _stock = Custom_Model_Stock_Base::getInstance($this -> _lid);
		$this -> _logicArea = $this -> _stock -> getArea();
		$this -> view -> logic_area = $this -> _stock -> getArea();
		$this -> status = $this -> _stock -> getConfigLogicStatus();
		$this -> _actions = $this -> _stock -> getConfigStatusAction();
		$this -> view -> actions = $this -> _stock -> getConfigStatusAction();
		$this -> view -> operates = $this -> _stock -> getConfigOperate();
		$this -> view -> status = $this -> _stock -> getConfigLogicStatus();
		$this -> view -> billStatus = $this -> _stock -> getConfigStatusStatus();
		$this -> view -> areas = $this -> _stock -> getConfigLogicArea();
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
	        $this -> view -> action = $action;
	        $this -> view -> param = $this -> _request -> getParams();
	        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
	        $this -> view -> pageNav = $pageNav -> getNavigation();
	        $this -> render('list');
        }
    }
    
    /**
     * 商品状态查询
     *
     * @return void
     */
    public function searchListAction()
    {
    }
    
    /**
     * 商品状态审核
     *
     * @return void
     */
    public function checkListAction()
    {
    }
    
    /**
     * 商品状态取消
     *
     * @return void
     */
    public function cancelListAction()
    {
    }
    
    /**
     * 商品状态申请
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
        	foreach ($this -> status as $k => $v) {
			    $status .= "<option value='$k'>$v</option>";
			}
			$this -> view -> status = $status;
			$this -> render('add');
        }
    }
    
    /**
     * 商品状态申请取消
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
                $datas = $this -> _api -> getDetail("a.sid=$id");
                $data = $datas[0];
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 商品状态取消审核
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
                $datas = $this -> _api -> getDetail("a.sid=$id");
                $data = $datas[0];
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
     * 商品状态审核
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
                $datas = $this -> _api -> getDetail("a.sid=$id");
                $data = $datas[0];
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
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
                $datas = $this -> _api -> getDetail("a.sid=$id");
                $data = $datas[0];
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
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
                $this -> view -> action = 'print';
                $datas = $this -> _api -> getDetail("a.sid=$id");
                $data = $datas[0];
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
                $this -> view -> op_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='check'"));
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
    
    
}
