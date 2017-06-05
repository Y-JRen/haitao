<?php

class Admin_Models_API_Index
{
	/**
     * 管理后台首页 DB
     * 
     * @var Admin_Models_DB_Index
     */
	private $_db = null;
	
	/**
     * 认证对象
     *
     * @var Admin_Models_API_Auth
     */
	private $_auth = null;
	
	/**
     * 是
     *
     */
	const YES = '是';
	
	/**
     * 否
     *
     */
	const NO = '否';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Index();
		$this -> _auth = Admin_Models_API_Auth :: getInstance();
	}
	
	/**
     * 取得登录用户信息
     *
     * @param  void
     * @return array
     */
	public function getAuth()
	{
		return $this -> _auth -> getAuth();
	}
	
	/**
     * 转换URL
     *
     * @param    string    $string
     * @return   array
     */
    public function parseUrlString($url)
    {
    	$urlArray = explode('/', trim($url, '/'));
    	$modArray = array('module', 'controller', 'action');
    	foreach ($urlArray as $key => $value)
    	{
    		$urlArray[$key] = $this -> _auth -> parsePrivilegeString($urlArray[$key], $modArray[$key]);
    	}
    	return $urlArray;
    }
    
    /**
     * 取得菜单
     *
     * @param    array    $modules
     * @return   array
     */
    public function getMenu($modules)
    {
    	if (!$this -> getAuth()) {
    		return false;
    	}
    	$innerFlag = $outerFlag = false;
    	foreach ($modules['module'] as $key1 => $vaue1)
		{
			foreach ($vaue1['menu'] as $key2 => $value2)
			{
				if ($value2['item']['url']) {
					$urlArray = $this -> parseUrlString($value2['item']['url']);
					
					if (!$this -> _auth -> getPrivilege($urlArray[0], $urlArray[1], $urlArray[2])) {
						unset($modules['module'][$key1]['menu'][$key2]['item']['title'], $modules['module'][$key1]['menu'][$key2]['item']['url']);
					} else {
						$innerFlag = true;
					}
				} else {
					if ($value2['submenu']) {
						foreach ($value2['submenu'] as $subkey => $subvalue)
					    {
							foreach ($subvalue['item'] as $key3 => $value3)
						    {
								$urlArray = $this -> parseUrlString($value3['url']);
								
								if (!$this -> _auth -> getPrivilege($urlArray[0], $urlArray[1], $urlArray[2])) {
									unset($modules['module'][$key1]['menu'][$key2]['submenu'][$subkey]['item'][$key3]['title'], $modules['module'][$key1]['menu'][$key2]['submenu'][$subkey]['item'][$key3]['url']);
							    } else {
							    	$innerFlag = true;
							    }
						    }
					    }
				    } else {
				    	foreach ($value2['item'] as $key3 => $value3)
					    {
							$urlArray = $this -> parseUrlString($value3['url']);
							
							if (!$this -> _auth -> getPrivilege($urlArray[0], $urlArray[1], $urlArray[2])) {
								unset($modules['module'][$key1]['menu'][$key2]['item'][$key3]['title'], $modules['module'][$key1]['menu'][$key2]['item'][$key3]['url']);
						    } else {
						    	$innerFlag = true;
						    }
					    }
				    }
				}
				
				if (!$innerFlag) {
				    unset($modules['module'][$key1]['menu'][$key2]);
				    !$outerFlag && $outerFlag = false;
			    } else {
			    	$innerFlag = false;
			    	$outerFlag = true;
			    }
			}
			
			if (!$outerFlag) {
				unset($modules['module'][$key1]);
			} else {
				$outerFlag = false;
			}
		}
		$modules['module'] && $modules['module'] = array_values($modules['module']);
		return $modules;
    }
    
    /**
     * 取得系统信息
     *
     * @param    void
     * @return   array
     */
    public function getSystemMessage()
    {
    	$message[] = array('title' => '服务器操作系统', 'value' => $this -> getOS());
    	$message[] = array('title' => 'WEB服务器', 'value' => $this -> getServer());
    	$message[] = array('title' => 'PHP版本', 'value' => $this -> getPhpVersion());
    	$message[] = array('title' => 'MySQL版本', 'value' => $this -> getMysqlVersion());
    	$message[] = array('title' => '文件上传最大限制', 'value' => $this -> getUploadMaxSize());
    	
    	return $message;
    }
    
    /**
     * 取得服务器操作系统
     *
     * @param    void
     * @return   string
     */
    public function getOS()
    {
    	return PHP_OS;
    }
    
    /**
     * 取得WEB服务器
     *
     * @param    void
     * @return   string
     */
    public function getServer()
    {
    	$server = Zend_Controller_Front::getInstance() -> getRequest() -> getServer('SERVER_SOFTWARE');
    	if (strpos($server, ' ') > 0) {
    		$server = explode(' ', Zend_Controller_Front::getInstance() -> getRequest() -> getServer('SERVER_SOFTWARE'));
    		return $server[0];
    	} else {
    		return $server;
    	}
    }
    
    /**
     * 取得PHP版本
     *
     * @param    void
     * @return   string
     */
    public function getPhpVersion()
    {
    	return PHP_VERSION;
    }
    
    /**
     * 取得MySql版本
     *
     * @param    void
     * @return   string
     */
    public function getMysqlVersion()
    {
    	return $this -> _db -> getVersion();
    }
    
    /**
     * 取得文件上传最大限制
     *
     * @param    void
     * @return   string
     */
    public function getUploadMaxSize()
    {
    	return ini_get('upload_max_filesize');
    }
}