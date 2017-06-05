<?php
class Admin_CostController extends Zend_Controller_Action
{
	private $_api = null;
	private $_auth = null;

	const ADD_SUCCESS = '申请成功!';
	const CANCEL_SUCCESS = '申请取消成功!';
	const CHECK_SUCCESS = '审核成功!';
	const OPT_SUCCESS = '申请已被拒绝!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
        $this->_api = new Admin_Models_API_Cost();
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        $this -> view -> auth = $this -> _auth;
	}
	/**
     * 调整单查看
     *
     * @return   void
     */	
	public function listAction(){
	   $page = (int)$this -> _request -> getParam('page', 1);
	   $res= $this->_api->getCostList($this->_request->getParams(),'*',$page);
	   $this->view->datas=$res['datas'];
	   $this->view->param = $this->_request->getParams();
	   $pageNav = new Custom_Model_PageNav($res['totle'], 10, 'ajax_search');
	   $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	/**
     * 调整单审核列表
     *
     * @return   void
     */	
	public function checkAction(){	    
	    $page = (int)$this -> _request -> getParam('page', 1);
	    $res= $this->_api->getCostListByCheck($this->_request->getParams(),'*',$page);
	    $this->view->datas=$res['datas'];
	    $this->view->param = $this->_request->getParams();
	    $pageNav = new Custom_Model_PageNav($res['totle'], 10, 'ajax_search');
	    $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	/**
     * 调整单审核
     *
     * @return   void
     */	
	public function checkdetailAction(){
	    if($this->_request->isPost()){
	        $result = $this->_api->gocheck($this->_request->getParams());
	        if ($result) {
	            Custom_Model_Message::showMessage(self::CHECK_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        }else{
	            Custom_Model_Message::showMessage(self::OPT_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        }
	    }else{
	        $id =$this->_request->getParam("id");
	        $flag =$this->_request->getParam("flag");
	        $data_arr = $this->_api->checkdetail($id);
	        $this->view->details= $data_arr['details'];
	        $this->view->data = $data_arr['cost_obj'];
	        $this->view->flag = $flag;
	    }   
	}
    /**
     * 调整成本申请
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
			$this -> render('add');
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
