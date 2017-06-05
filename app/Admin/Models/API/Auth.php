<?php
 class Admin_Models_API_Auth implements Custom_Model_AuthInterface
 {
 	/**
     * 认证 DB
     * 
     * @var Admin_Models_DB_Auth
     */
	private $_db = null;
	
	/**
     * 认证名字空间名称
     * 
     * @var string
     */
	private $_certificationName = 'Admin';
	
	/**
     * 认证名字空间
     * 
     * @var Zend_Session_Namespace
     */
	private $_certificationNamespace = null;
	
	/**
     * 对象实例
     *
     * @var Admin_Models_API_Auth
     */
    protected static $_instance = null;
	
	/**
     * Cache文件
     * 
     * @var string
     */
    private $_cacheFile = 'cache.privilege';
	
	/**
     * 未填写用户名
     */
	const NO_USERNAME = '请填写用户名!';
	
	/**
     * 用户名或密码错误
     */
	const ERROR_CERTIFICATION = '用户名或密码错误!';
	
	/**
     * 该时段不允许登录
     */
	const NO_ALLOW_TIME = '该时段不允许登录!';
	
 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
        $this -> _certificationNamespace = new Zend_Session_Namespace($this -> _certificationName);
        $this -> _db = new Admin_Models_DB_Auth();
        $this -> _cacheFile = realpath(Zend_Registry::get('config') -> sytem_cache -> dir) . '/' . $this -> _cacheFile;
    }
    /**
     * 取得对象实例
     *
     * @return Admin_Models_API_Auth
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * 取得用户认证信息
     *
     * @param    void
     * @return   array
     */
    public function getAuth()
    {
     	if (!$this -> _certificationNamespace -> adminCertification) {
			return false;
		}
		return $this -> _certificationNamespace -> adminCertification;
    }
    /**
     * 取得用户操作是否有权限
     *
     * @param    string    $mod
     * @param    string    $ctl
     * @param    string    $act
     * @return   bool
     */
    public function getPrivilege($mod, $ctl, $act)
    {
    	$ctl = $this -> parsePrivilegeString($ctl);
    	$act = $this -> parsePrivilegeString($act, 'action');
		$adminCertification = $this -> _certificationNamespace -> adminCertification;
		if (in_array($adminCertification['admin_id'], array(1,101))){
			return true;
		}
		$adminPrivilege = $adminCertification['privilege'];
		$privilegeArray = $adminCertification['privilegeArray'];
		
		$k = $mod.'-'.$ctl.'-'.$act;
		$pid = $privilegeArray[$k];
		unset($privilegeArray);
		if (strstr(','.$adminPrivilege.',', ','.$pid.',') || !$pid) {
	        return true;
        }else{
        	return false;
        }
    }
    
    /**
     * 转换模块名.
     *
     * @param    string    $string
     * @param    string    $type
     * @return   string
     */
    public function parsePrivilegeString($string, $type = 'controller')
    {
    	$string = explode('-', $string);
    	foreach($string as $key => $subString)
    	{
    		$result[$key] = (($type == 'action' && $key == 0) || $type == 'module') ? $subString : ucfirst($subString);
    	}
    	return implode('', $result);
    }
     
    /**
     * 清除用户认证信息
     *
     * @param    void
     * @return   void
     */
    public function unsetAuth()
    {
    	$this -> _certificationNamespace -> unsetAll();
        session_unset();
        session_destroy();
    }
    
    /**
     * 重新载入用户认证信息
     *
     * @param    void
     * @return   bool
     */
    public function reload()
    {
    	$auth = $this -> getAuth();
    	if ($auth && $this -> certification() == true) {
    		return true;
    	} else {
    		return false;
    	}
    }
     
    /**
     * 用户认证
     *
     * @param    string    $username
     * @param    string    $password
     * @param    string    $extra
     * @return   mixed
     */
    public function certification($username, $password, $extra)
    {
     	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        
        $username = $filterChain -> filter($username);
        $password = $filterChain -> filter($password);
        
        if (empty($username)) {
        	return self::NO_USERNAME;
        }
		/*
        $time = date('Hi');
        if (($time >= 2300 && $time <= 2359)  or ($time >= 0000 && $time <= 0630) ) {
        	return self::NO_ALLOW_TIME;
        }*/
		$result = $this -> _db -> certification($username, Custom_Model_Encryption::getInstance() -> encrypt($password));
		if ($result != null) {
			if($result['group_id'] > 0){
				$IpLocation = new Custom_Model_IpLocation();
				$address = $IpLocation -> getlocation();
				$ip_address = mb_convert_encoding($address['country'].$address['area'], "UTF-8", "gbk");
				$this -> _db -> loginLog(array('admin_id' => $result['admin_id'], 'admin_name' => $result['admin_name'], 'login_time' => $result['last_login'], 'login_ip' => $result['last_login_ip'] ,'ip_address' => $ip_address));
			}
			$privilege = new Admin_Models_API_Privilege();
			!file_exists($this -> _cacheFile.'.db') && $privilege -> cachePrivilegeDb();
			$result['privilegeArray'] = Zend_Json::decode(file_get_contents($this -> _cacheFile.'.db'));
			if (!$result['lid']) {
			    $result['lid'] = 1;
			    $this -> _db -> updateLid($result['admin_name'], $result['lid']);
			}
			$this -> _certificationNamespace -> adminCertification = $result;
			return true;
		} else {
			return self::ERROR_CERTIFICATION;
		}
    }

    /**
     * 计划任务用户认证
     */
    public function cronCertification()
    {
        $this -> _certificationNamespace -> adminCertification = array('admin_id'=>'0','admin_name'=>'system');
    }

	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getLog($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
        $whereSql = "1=1";
        $where['admin_name'] && $whereSql .= " and admin_name LIKE '%" . trim($where['admin_name']) . "%'";
        $where['login_ip'] && $whereSql .= " and login_ip LIKE '%" . trim($where['login_ip']) . "%'";

		return $this -> _db -> getLog($whereSql, $fields, $orderBy, $page, $pageSize);
	}
	
	public function setCertifiction($certification)
	{
	    $this -> _certificationNamespace -> adminCertification = $certification;
	}
 }