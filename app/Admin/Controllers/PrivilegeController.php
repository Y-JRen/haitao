<?php

class Admin_PrivilegeController extends Zend_Controller_Action 
{
	/**
     * 权限设置 API
     * 
     * @var Admin_Models_API_Privilege
     */
    private $_privilege = null;
    
    /**
     * 没有选择权限项
     */
	const NO_PRIVILEGE_SELECT = '请选择权限!';
	
	/**
     * 没有填写权限标识
     */
	const NO_TITLE = '请填写权限标识!';
	
	/**
     * 编辑权限成功
     */
	const EDIT_PRIVILEGE_SUCESS = '编辑权限成功!';
    
    /**
     * 控制器初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _privilege = new Admin_Models_API_Privilege();
	}
	
	/**
     * 可用权限列表
     *
     * @return void
     */
    public function indexAction()
    {
    	$this -> view -> jsonMixedPrivilege = $this -> _privilege -> getJsonMixedPrivilege();
    }
    
    /**
     * 编辑权限显示名称
     *
     * @return void
     */
    public function editAction()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', null);
    	
    	if ($id > 0) {
            $result = $this -> _privilege -> editPrivilege($this -> _request -> getParams());
            switch ($result) {
        		case 'noPrivilegeSelect':
        		    exit(self::NO_PRIVILEGE_SELECT);
        		    break;
        		case 'noTitle':
        		    exit(self::NO_TITLE);
        		    break;
        		case 'editPrivilegeSucess':
        		    //exit(self::EDIT_PRIVILEGE_SUCESS);
        		    break;
        		case 'error':
        		    exit('error');
        	}
    	}
    }
    
    /**
     * 删除已启用权限
     *
     * @return void
     */
    public function deleteAction()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$string = $this -> _request -> getParam('string', null);
    	
    	if ($string) {
            $result = $this -> _privilege -> deletePrivilege($string);
            switch ($result) {
        		case 'deletePrivilegeSucess':
        		    //exit(self::EDIT_PRIVILEGE_SUCESS);
        		    break;
        		case 'error':
        		    exit('error');
        	}
    	}
    }
    
    /**
     * 生成权限缓存文件
     *
     * @return void
     */
    public function cacheAction()
    {
    	//$this -> _helper -> viewRenderer -> setNoRender();
    	//$this -> _privilege -> cachePrivilegeFile();
    }
}