<?php

class Admin_LogicStatusController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
    
	const ADD_SUCCESS = '添加成功!';
	const EDIT_SUCCESS = '编辑成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_LogicStatus();
	}
    
	/**
     * 默认动作
     *
     * @return   void
     */
    public function indexAction()
    {
        $datas = $this -> _api -> get();
        $this -> view -> datas = $datas;
    }
    
    /**
     * 添加动作
     *
     * @return void
     */
    public function addAction()
    {exit;
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> edit($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> render('edit');
        }
    }
    
    /**
     * 编辑动作
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> edit($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'edit';
                $data = array_shift($this -> _api -> get("id=$id"));
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
}
