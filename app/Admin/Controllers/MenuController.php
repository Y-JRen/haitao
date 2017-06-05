<?php

class Admin_MenuController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
    
	const EXISTS = '该菜单已存在';
	const ADD_SUCCESS = '添加菜单成功!';
	const EDIT_SUCCESS = '编辑菜单成功!';
	
	/**
     * 初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Menu();
		$this -> _privilege = new Admin_Models_API_Privilege();
	}
    
	/**
     * 默认动作
     *
     * @return   void
     */
    public function indexAction()
    {
        $pid = (int)$this -> _request -> getParam('pid', null);
        $where = $pid ? "menu_path like '%,$pid,%'" : "parent_id=0";
        $datas = $this -> _api -> menuTree($where);
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $datas[$num]['menu_id'], $datas[$num]['menu_status']);
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
        	$pid = (int)$this -> _request -> getParam('pid', null);
        	$this -> view -> action = 'add';
            if (!$pid) {
	            $pid = 0;
	            $data['menu_path'] = ',';
            }else{
	            $pmenu = array_shift($this -> _api -> get("menu_id=$pid","menu_title,menu_path"));
	            $data['parent_title'] = $pmenu['menu_title'];
	            $data['menu_path'] =  $pmenu['menu_path'];
        	}
        	$data['parent_id'] = $pid;
        	$this -> view -> data = $data;
        	$this -> view -> jsonDatabasePrivilege = $this -> _privilege -> getJsonPrivilege('database');
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
                $data = array_shift($this -> _api -> get("menu_id=$id"));
                if($data['parent_id'] == 0){
	                $data['parent_title'] = '顶级菜单';
                }else{
	                $where = "menu_id=".$data['parent_id'];
	                $pmenu = array_shift($this -> _api -> get($where,"menu_title,menu_path"));
	                $data['parent_title'] = $pmenu['menu_title'];
                }
                $this -> view -> data = $data;
                $this -> view -> jsonDatabasePrivilege = $this -> _privilege -> getJsonPrivilege('database', array('selectId' => $data['privilege']));
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
            }else{
             exit('ok');
            }
        } else {
            exit('error!');
        }
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
     * 获取下级菜单列表
     *
     * @return void
     */
    public function getchildAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $pid = (int)$this -> _request -> getParam('pid', null);
        $this -> view -> menu_id = $id;
        $this -> view -> pid = $pid;
        $this -> view -> menus = $this -> _api -> get("parent_id=$pid");
    }
    
}
