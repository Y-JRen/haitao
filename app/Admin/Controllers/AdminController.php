<?php
class Admin_AdminController extends Zend_Controller_Action 
{
	/**
     * 管理员 API
     * @var Admin_Models_API_Admin
     */
    private $_admin = null;
    
    /**
     * 管理组
     * @var array
     */
    private $_groupIds = null;
    
    /**
     * 未填写管理员名
     */
	const NO_USERNAME = '请填写管理员名!';
	
	/**
     * 密码不一致
     */
	const NO_SAME_PASSWORD = '输入的密码不一致!';
	
	/**
     * 密码为空
     */
	const NO_PASSWORD = '密码及重复密码不能为空!';

	/**
     * 旧密码错误
     */
	const oldPasswordError = '旧密码错误!';

	/**
     * 添加管理员成功
     */
	const ADD_ADMIN_SUCESS = '添加管理员成功!';
	
	/**
     * 编辑管理员成功
     */
	const EDIT_ADMIN_SUCESS = '编辑管理员成功!';
	
	/**
     * 管理员已存在
     */
	const ADMIN_EXISTS = '该管理员已存在!';
	
	/**
     * 管理员不存在
     */
	const ADMIN_NO_EXISTS = '该管理员不存在!';
	
	/**
     * 删除管理员失败
     */
	const DELETE_ADMIN_FORBIDDEN = '删除管理员失败!';
	
	/**
     * 没有操作权限
     */
	const FORBIDDEN = '禁止操作!';
	
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _admin = new Admin_Models_API_Admin();
		$this -> _privilege = new Admin_Models_API_Privilege();
		$this -> _group = new Admin_Models_API_AdminGroup();
		$this -> _menu = new Admin_Models_API_Menu();
		$groupMessages = $this -> _group -> getAllGroup('group_id,group_name,remark');
    	foreach ($groupMessages as $key => $value)
    	{
    		$groupIds[$value['group_id']] = $value['group_name'];
    	}
    	$this -> _groupIds = $groupIds;
	}
    
    /**
     * 管理员列表
     *
     * @return void
     */
    public function indexAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $adminMessages = $this -> _admin -> getAllAdmin($search, $page);
        $total = $this -> _admin -> getAllAdminCount($search);
        $groupIds = $this -> _groupIds;
      	$this -> view -> groupIds = $groupIds;
        if (is_array($adminMessages)) {
        	foreach ($adminMessages as $num => $adminMessage)
            {
        	    $adminMessages[$num]['group_name'] = ($adminMessages[$num]['group_id'] > 0) ? $groupIds[$adminMessages[$num]['group_id']] : '';
        	    $adminMessages[$num]['last_login'] = ($adminMessages[$num]['last_login'] > 0) ? date('Y-m-d H:i:s', $adminMessages[$num]['last_login']) : '';
        	    $adminMessages[$num]['status'] = $this -> _admin -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $adminMessages[$num]['admin_id'], $adminMessages[$num]['status']);
            }
        }
        $this -> view -> adminList = $adminMessages;
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 添加管理员
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _admin -> editAdmin($this -> _request -> getPost());
        	switch ($result) {
        		case 'noUserName':
        		    Custom_Model_Message::showMessage(self::NO_USERNAME);
        		    break;
        		case 'noSamePassword':
        		    Custom_Model_Message::showMessage(self::NO_SAME_PASSWORD);
        		    break;
        		case 'noPassword':
        		    Custom_Model_Message::showMessage(self::NO_PASSWORD);
        		    break;
        		case 'addAdminSucess':
        		    Custom_Model_Message::showMessage(self::ADD_ADMIN_SUCESS, '', 1250, "Gurl()");
        		    break;
        		case 'adminExists':
        		    Custom_Model_Message::showMessage(self::ADMIN_EXISTS);
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, "Gurl()");
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> view -> title = '添加管理员';
        	$this -> view -> groupIds = $this -> _groupIds;
        	$this -> view -> admin = $admin;
        	$this -> view -> menus = $this -> _menu -> menuTree();
        	$stockAPI = Custom_Model_Stock_Base::getInstance();
        	$this -> view -> areaList = $stockAPI -> getConfigLogicArea();
        	$this -> render('edit');
        }
    }
    
    /**
     * 编辑管理员
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
            	$this -> _helper -> viewRenderer -> setNoRender();
                $result = $this -> _admin -> editAdmin($this -> _request -> getPost(), $id);
                switch ($result) {
        		    case 'noUserName':
        		        Custom_Model_Message::showMessage(self::NO_USERNAME);
        		        break;
        		    case 'noSamePassword':
        		        Custom_Model_Message::showMessage(self::NO_SAME_PASSWORD);
        		        break;
        		    case 'noPassword':
        		        Custom_Model_Message::showMessage(self::NO_PASSWORD);
        		        break;
        		    case 'editAdminSucess':
        		        Custom_Model_Message::showMessage(self::EDIT_ADMIN_SUCESS, 'event', 1250, 'Gurl()');
        		        break;
        		    case 'adminNoExists':
        		        Custom_Model_Message::showMessage(self::ADMIN_NO_EXISTS);
        		        break;
        		    case 'adminExists':
        		        Custom_Model_Message::showMessage(self::ADMIN_EXISTS);
        		        break;
        		    case 'forbidden':
        		        Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        	    }
            } else {
            	$admin = $this -> _admin -> getAdminById($id);
                $this -> view -> action = 'edit';
                $this -> view -> title = '修改管理员';
                $this -> view -> changePassword = '不改请留空';
                $this -> view -> admin = $admin;
                $this -> view -> groupIds = $this -> _groupIds;
                if ($admin['group_menu']){
                    $this -> view -> menus = $this -> _menu -> menuTree("menu_id in(".$admin['group_menu'].")");
                }
                $r = explode(',', $admin['menu']);
	        	foreach($r as $v){
	        		$menu[$v] = $v;
	        	}
	        	$this -> view -> menu = $menu;
	        	
                $r = explode(',', $admin['privilege']);
	        	foreach($r as $v){
	        		$privilege[$v] = $v;
	        	}
	        	$this -> view -> privilege = $privilege;
	        	$this -> view -> group_privilege = unserialize($admin['group_privilege']);
	        	$stockAPI = Custom_Model_Stock_Base::getInstance();
        	    $this -> view -> areaList = $stockAPI -> getConfigLogicArea();
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 修改个人密码
     *
     * @return void
     */

    public function changePasswordAction()
    {
        $_auth = Admin_Models_API_Auth :: getInstance()->getAuth(); 
        $id = $_auth['admin_id'];
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
            	$this -> _helper -> viewRenderer -> setNoRender();
                $result = $this -> _admin -> changePassword($this -> _request -> getPost(), $id);
                switch ($result) {
        		    case 'noSamePassword':
        		        Custom_Model_Message::showMessage(self::NO_SAME_PASSWORD);
        		        break;
        		    case 'noPassword':
        		        Custom_Model_Message::showMessage(self::NO_PASSWORD);
        		        break;
        		    case 'editAdminSucess':
        		        Custom_Model_Message::showMessage(self::EDIT_ADMIN_SUCESS, 'event', 1250, 'window.top.alertBox.closeDiv()');
        		        break;
        		    case 'adminNoExists':
        		        Custom_Model_Message::showMessage(self::ADMIN_NO_EXISTS);
        		        break;
        		    case 'oldPasswordError':
        		        Custom_Model_Message::showMessage(self::oldPasswordError);
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showMessage('error!', 'event', 1250, 'window.top.alertBox.closeDiv()');
        	    }
            } else {
            	$admin = $this -> _admin -> getAdminById($id);
                $this -> view -> action = 'change-password';
                $this -> view -> title = '修改管理员个人密码';
                $this -> view -> admin = $admin;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'window.top.alertBox.closeDiv()');
        }
    }

    /**
     * 删除管理员
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _admin -> deleteAdminById($id);
            switch ($result) {
            	case 'forbidden':
            	    exit(self::FORBIDDEN);
        		    break;
        		case 'error':
        		    exit('error!');
        		case 'deleteAdminSucess':
        		    exit('ok');
        		default:
        		    exit('ok');
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 更改状态
     *
     * @return void
     */
    public function statusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	if ($id > 0) {
	        $this -> _admin -> changeStatus($id, $status);
        } else {
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _admin -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
    }
    
    /**
     * 检查管理员是否存在
     * 
     * @return void
     */
    public function privilegeAction()
    {
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $gid = $this -> _request -> getParam('gid', null);
        if($gid){
            $group = $this -> _group -> getGroupById($gid);
            if ($group['group_menu']){
                 $this -> view -> menus = $this -> _menu -> menuTree("menu_id in(".$group['group_menu'].")");
            }
    	    $this -> view -> group_privilege = unserialize($group['group_privilege']);
    	}
    }
    /**
     * 检查管理员是否存在
     * 
     * @return void
     */
    public function checkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $name = $this -> _request -> getParam('val', null);
        if(!empty($name)){
	        $result = $this -> _admin -> getAdminByName($name);
	        if (!empty($result)) {
	        	exit(self::ADMIN_EXISTS);
	        }
	        exit;
        }
    }
    
    /**
     * 获得管理组数据(ajax)
     * 
     * @return void
     */
    public function getAction()
    {
        $groupID = $this -> _request -> getParam('gid', null);
        if ($groupID) {
            $groupAPI = new Admin_Models_API_AdminGroup();
            $group = $groupAPI -> getGroupById($groupID);
            echo json_encode($group);
        }
        
        exit;
    }
}