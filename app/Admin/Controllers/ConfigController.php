<?php

class Admin_ConfigController extends Zend_Controller_Action 
{
	/**
     * 配置 API
     * 
     * @var Admin_Models_API_Config
     */
    private $_config = null;
    
    /**
     * 变量选项
     * 
     * @var array
     */
    private $_typeOptions = array('text' => '文本框', 'password' => '密码框', 'textarea' => '文本域', 'radio' => '单选框', 'checkbox' => '复选框', 'select' => '下拉列表', 'file' => '文件上传');
    
    /**
     * 未填写变量名称
     */
	const NO_CONFIG_NAME = '请填写变量名称!';
	
	/**
     * 未填写变量说明
     */
	const NO_CONFIG_TITLE = '请填写变量说明!';
	
	/**
     * 未填写变量选项
     */
	const NO_CONFIG_TYPE_OPTIONS = '请填写变量选项!';
	
	/**
     * 该变量已经存在
     */
	const CONFIG_EXISTS = '该变量已经存在!';
	
	/**
     * 该变量不存在
     */
	const CONFIG_NO_EXISTS = '该变量不存在!';
	
	/**
     * 添加设置项成功
     */
	const ADD_CONFIG_SUCESS = '添加成功!';
	
	/**
     * 编辑设置项成功
     */
	const EDIT_CONFIG_SUCESS = '编辑成功!';
	
	/**
     * 更新设置值成功
     */
	const UPDATE_CONFIG_SUCESS = '更新成功!';
    
    /**
     * 未填写变量名称
     */
	const NO_PRIVILEGE = '你不能修改商城参数设置，请联系超级管理员!';
	
    /**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
        $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        $this -> _config = new Admin_Models_API_Config();
      
	}
	
	/**
     * 商店设置列表
     *
     * @return void
     */
    public function indexAction()
    {
    	$this -> view -> action = "update";
    	$this -> view -> cate = $this -> _config -> getAllCate();
    	$this -> view -> optionFrom = $this -> _config -> getOptionFrom();
    	$this -> _config -> makeConfigFile();
    }
    
    /**
     * 添加商店设置
     *
     * @return void
     */
    public function addAction()
    {
    	if ($this -> _request -> isPost()) {
    		$this -> _helper -> viewRenderer -> setNoRender();
    		$result = $this -> _config -> editConfig($this -> _request -> getPost());
    		switch ($result) {
    			case 'noConfigName':
    			    Custom_Model_Message::showMessage(self::NO_CONFIG_NAME);
    			    break;
    			case 'noConfigTitle':
    			    Custom_Model_Message::showMessage(self::NO_CONFIG_TITLE);
    			    break;
    			case 'noConfigTypeOptions':
    			    Custom_Model_Message::showMessage(self::NO_CONFIG_TYPE_OPTIONS);
    			    break;
    			case 'configExists':
    			    Custom_Model_Message::showMessage(self::CONFIG_EXISTS);
    			    break;
    			case 'addConfigSucess':
    			    $this -> _config -> makeConfigFile();
    			    Custom_Model_Message::showMessage(self::ADD_CONFIG_SUCESS, 'event', 1250, "Gurl()");
    			    break;
    		}
    	} else {
    	    $this -> view -> title = "添加设置";
    	    $this -> view -> action = "add";
    	    $this -> view -> catOptions = $this -> _config -> getAllCate();
    	    $this -> view -> typeOptions = $this -> _typeOptions;
    	    $this -> render('edit');
    	}
    }
    
    /**
     * 编辑商店设置
     *
     * @return void
     */
    public function editAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
    	if ($id > 0) {
    	    if ($this -> _request -> isPost()) {
    	    	$this -> _helper -> viewRenderer -> setNoRender();
    		    $result = $this -> _config -> editConfig($this -> _request -> getPost(), $id);
    		    switch ($result) {
    			    case 'noConfigName':
    			        Custom_Model_Message::showMessage(self::NO_CONFIG_NAME);
    			        break;
    			    case 'noConfigTitle':
    			        Custom_Model_Message::showMessage(self::NO_CONFIG_TITLE);
    			        break;
    			    case 'noConfigTypeOptions':
    			        Custom_Model_Message::showMessage(self::NO_CONFIG_TYPE_OPTIONS);
    			        break;
    			    case 'configExists':
    			        Custom_Model_Message::showMessage(self::CONFIG_EXISTS);
    			        break;
    			    case 'configNoExists':
    			        Custom_Model_Message::showMessage(self::CONFIG_NO_EXISTS);
    			        break;
    			    case 'editConfigSucess':
    			        $this -> _config -> makeConfigFile();
    			        Custom_Model_Message::showMessage(self::EDIT_CONFIG_SUCESS, 'event', 1250, "Gurl()");
    			        break;
    		    }
    	    } else {
    	        $this -> view -> title = "编辑设置";
    	        $this -> view -> action = "edit";
    	        $config = $this -> _config -> getConfigById($id);
    	        $config['type_options'] = unserialize($config['type_options']);
    	        $this -> view -> config = $config;
    	        $this -> view -> catOptions = $this -> _config -> getAllCate();
    	        $this -> view -> typeOptions = $this -> _typeOptions;
    	    }
    	}
    }
    
    /**
     * 检查选项名是否已经存在
     *
     * @return void
     */
    public function checkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $name = $this -> _request -> getParam('val', null);
        
        if(!empty($name)){
	        $result = $this -> _config -> getConfigByName($name);
	        
	        if (!empty($result)) {
	        	exit(self::CONFIG_EXISTS);
	        }
        }
    }
    
    /**
     * 删除设置
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _config -> deleteConfigById($id);
            switch ($result) {
            	case 'deleteConfigSucess':
            	    $this -> _config -> makeConfigFile();
        		    break;
        		case 'error':
        		    exit('error!');
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 更新设置
     *
     * @return void
     */
    public function updateAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        if ($this -> _request -> isPost()) {
        	$result = $this -> _config -> updateConfig($this -> _request -> getPost());
        	if ($result) {
        		switch ($result) {
        		    case 'noValid':
        		        Custom_Model_Message::showMessage(self::UPLOAD_NOVALID);
        		        break;
        		    default:
        		        Custom_Model_Message::showMessage($result);
        	    }
        	} else {
        		Custom_Model_Message::showMessage(self::UPDATE_CONFIG_SUCESS, 'event', 1250, "Gurl('refresh')");
        	}
        } else {
            exit('error!');
        }
    }
	
    /**
     * 生产站点 地图
     */
    public function createSitemapAction(){
		$api_sitemap = new Admin_Models_API_Sitemap();
		$api_sitemap->createSitemap(SYSROOT);    	
    }
    


    
}