<?php

class Admin_AttributeController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
    
	const EXISTS = '该属性已存在!';
	const ADD_SUCCESS = '添加属性成功!';
	const EDIT_SUCCESS = '编辑属性成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Attribute();
	}
    
	/**
     * 默认动作
     *
     * @return   void
     */
    public function indexAction()
    {
        $datas = $this -> _api -> attrTree(null, null, 0, "parent_id >=0 ");
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $datas[$num]['attr_id'], $datas[$num]['attr_status']);
        }
        $this -> view -> datas = $datas;
    }
    
    /**
     * 添加动作
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> edit($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$id = (int)$this -> _request -> getParam('id', 0);
        	$cat_id = (int)$this -> _request -> getParam('cat_id', null);
        	if($id){
        	    $r = array_shift($this -> _api -> get("attr_id=$id"));
        	    $data['parent_name'] = $r['attr_title'];
	        	$data['attr_key'] = $r['attr_key'];
	        	$data['attr_path'] = $r['attr_path'];
	        	$where = "parent_id=$id";
        	}else{
        		$data['attr_path'] = ',';
        		$where = "parent_id=0";
        	}
        	$datas = $this -> _api -> get($where);
        	$data['parent_id'] = $id;
        	$data['cat_id'] = $cat_id;
        	$this -> view -> action = 'add';
        	$this -> view -> datas = $datas;
        	$this -> view -> data = $data;
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
                $data = array_shift($this -> _api -> get("attr_id=$id"));
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 删除动作
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _api -> delete($id);
            if(!$result) {
        	    exit($this -> _api -> error());
            }
        } else {
            exit('error!');
        }
        $this->_redirect('/admin/attribute/index/');
    }
    
    /**
     * 更改状态动作
     *
     * @return void
     */
    public function statusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _api -> changeStatus($id, $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
    }
    
    /**
     * ajax更新数据
     *
     * @return void
     */
    public function ajaxupdateAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        
        if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, $field, $val);
        } else {
            exit('error!');
        }
    }
    
    /**
     * 检查重复值
     *
     * @return void
     */
    public function checkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        $pid = (int)$this -> _request -> getParam('pid', 0);
        
        if(!empty($val) && (int)$pid > 0){
	        $result = $this -> _api -> get("parent_id =$pid AND $field='$val'",$field);
	        if (!empty($result)){
	        	exit(self::EXISTS);
	        }
        }
        exit;
    }
}
