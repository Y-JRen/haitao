<?php
class Admin_AdminGroupController extends Zend_Controller_Action
{
	/**
     * 管理组 API
     * @var Admin_Models_API_AdminGroup
     */
    private $_group = null;
    
    /**
     * 未填写管理组名
     */
	const NO_GROUP_NAME = '请填写管理组名!';
	
	/**
     * 添加管理组成功
     */
	const ADD_GROUP_SUCESS = '添加管理员组成功!';
	
	/**
     * 编辑管理组成功
     */
	const EDIT_GROUP_SUCESS = '编辑管理员组成功!';
	
	/**
     * 管理组已存在
     */
	const GROUP_EXISTS = '该管理员组已存在!';
	
	/**
     * 管理组不存在
     */
	const GROUP_NO_EXISTS = '该管理员组不存在!';
	
	/**
     * 删除管理组失败
     */
	const DELETE_GROUP_FORBIDDEN = '删除管理员组失败!';
	
	/**
     * 没有权限操作
     */
	const FORBIDDEN = '禁止操作!';
	
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _group = new Admin_Models_API_AdminGroup();
		$this -> _menu = new Admin_Models_API_Menu();
	}
    
    /**
     * 管理组列表
     *
     * @return void
     */
    public function indexAction()
    {
        $groupMessages = $this -> _group -> getAllGroup('*');
        $this -> view -> groupList = $groupMessages;
    }
    
    /**
     * 添加管理组
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _group -> editGroup($this -> _request -> getPost());
        	switch ($result) {
        		case 'addGroupSucess':
        		    Custom_Model_Message::showMessage(self::ADD_GROUP_SUCESS, 'event', 1250, 'Gurl()');
        		    break;
        		case 'noGroupName':
        		    Custom_Model_Message::showMessage(self::NO_GROUP_NAME);
        		    break;
        		case 'groupExists':
        		    Custom_Model_Message::showMessage(self::GROUP_EXISTS);
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, "Gurl()");
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> view -> title = '添加管理员组';
        	$this -> view -> menus = $this -> _menu -> menuTree();
        	$this -> render('edit');
        }
    }
    /**
     * 编辑管理组
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
            	$this -> _helper -> viewRenderer -> setNoRender();
                $result = $this -> _group -> editGroup($this -> _request -> getPost(), $id);
                switch ($result) {
                	case 'editGroupSucess':
        		        Custom_Model_Message::showMessage(self::EDIT_GROUP_SUCESS, 'event', 1250, 'Gurl()');
        		        break;
        		    case 'noGroupName':
        		        Custom_Model_Message::showMessage(self::NO_GROUP_NAME);
        		        break;
        		    case 'groupNoExists':
        		        Custom_Model_Message::showMessage(self::GROUP_NO_EXISTS);
        		        break;
        		    case 'groupExists':
        		        Custom_Model_Message::showMessage(self::GROUP_EXISTS);
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        	    }
            } else {
            	$group = $this -> _group -> getGroupById($id);
                $this -> view -> action = 'edit';
                $this -> view -> title = '修改管理员组';
                $this -> view -> group = $group;
                $this -> view -> menus = $this -> _menu -> menuTree();
                
	        	$this -> view -> menu = array_fill_keys(explode(',', $group['group_menu']), 1);
	        	$this -> view -> privilege = unserialize($group['group_privilege']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 删除管理组
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _group -> deleteGroupById($id);
            switch ($result) {
            	case 'forbidden':
            	    exit(self::FORBIDDEN);
        		    break;
        		case 'error':
        		    exit('error!');
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 取得管理组权限并转化为JSON
     *
     * @return void
     */
    public function jsonPrivilegeAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$adminApi = new Admin_Models_API_Admin();
    	$admin = $adminApi -> getAdminById((int)$this -> _request -> getParam('uid', 0));
    	$group = $this -> _group -> getGroupById((int)$this -> _request -> getParam('gid', 0));
    	
    	if ($group) {
            $_privilege = new Admin_Models_API_Privilege();
    		echo $_privilege -> getJsonPrivilege('database', array('id' => $group['privilege'], 'selectId' => ($admin['privilege']) ? $admin['privilege'] : $admin['group_privilege']));
    	}
    }
}

