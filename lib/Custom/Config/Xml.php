<?php
 class Custom_Config_Xml
 {
 	/**
     * 系统配置文件
     * 
     * @var    string
     */
 	public $_configFile = '../config/config.xml';
 	
 	/**
     * 系统模块配置文件
     * 
     * @var    string
     */
 	public $_moduleFile = '../config/module.xml';
 	
 	/**
     * 请求对象
     * 
     * @var    object
     */
    private $_request = null;
    
    /**
     * 模块
     * 
     * @var    array
     */
    private $_modules = null;
    
    /**
     * 对象初始化
     *
     * @param    string    $module     模块
     * @return   void
     */
    public function __construct()
    {
        $_GET && $_GET = self::addslashedArray($_GET);
        $_POST && $_POST = self::addslashedArray($_POST);
    	$_REQUEST && $_REQUEST = self::addslashedArray($_REQUEST);
    	$_COOKIE && $_COOKIE = self::addslashedArray($_COOKIE);
    }
    
    public function getConfig($module = null)
    {
    	if (!$module) {
    		$modules = $this -> getModules();
            $orgModule = array_shift(explode('/', trim($_SERVER['REQUEST_URI'], '/')));
            $module = ($orgModule && array_key_exists($orgModule, $modules)) ? $orgModule : array_shift(array_keys($modules));
    	}
    	Zend_Controller_Front::getInstance() -> throwExceptions(($module == 'admin') ? true : true);
    	return new Zend_Config_Xml(realpath($this -> _configFile), $module);
    }
    
    /**
     * 取得所有模块名
     *
     * @param    void
     * @return   array
     */
    public function getModules()
    {
    	if (!$this -> _modules) {
    		$module = new Zend_Config_Xml(realpath($this -> _moduleFile));
    		$domain2 = array_shift(explode('.', $_SERVER['HTTP_HOST']));
    		$this -> _modules = ($module -> $domain2) ? $module -> $domain2 -> toArray() : $module -> shop -> toArray();
    	}
    	return $this -> _modules;
    }
    
    /**
     * 解析XML文件
     *
     * @param    string    $path
     * @param    string    $module
     * @return   Zend_Config_Xml
     */
    public static function loadXml($path, $module = null)
    {
    	$config = Zend_Registry::get('config') -> cache -> backend -> memcached -> toArray();
    	$cache = Zend_Cache::factory('File', 'Memcached', array('automatic_serialization' => true, 'master_file' => realpath($path)), $config);
    	if (!($data = $cache->load('Cache_File_' . str_replace(array('.', '/', '\\', ':'), array('', '_'), $path . '_' . $module)))) {
    	    $data = new Zend_Config_Xml(realpath($path), $module);
    	    $cache->save($data);
    	}
    	return $data;
    }
    
    /**
     * 读取文件
     *
     * @param    string    $path
     * @return   string
     */
    public static function loadFile($path, $bool = false)
    {
    	$config = Zend_Registry::get('config') -> cache -> backend -> memcached -> toArray();
    	$cache = Zend_Cache::factory('File', 'Memcached', array('automatic_serialization' => $bool, 'master_file' => realpath($path)), $config);
    	
    	if (!($data = $cache->load('Cache_File_' . str_replace(array('.', '/', '\\', ':'), array('', '_'), $path)))) {
    	    $data = file_get_contents(realpath($path));
    	    $cache->save($data);
    	}
    	return $data;
    }
    
    /**
     * 递归过滤用户参数
     *
     * @param    mixed    $value
     * @return   mixed    $data
     */
    public static function addslashedArray($value)
    {
    	if (empty($value)) {
            return $value;
        } else {
            if (strpos($_SERVER['REQUEST_URI'], '/admin') === false && strpos($_SERVER['REQUEST_URI'], '/newsadmin') === false) {
                if (get_magic_quotes_gpc()) {
                    return is_array($value) ? @array_map('Custom_Config_Xml::addslashedArray', $value) : strip_tags($value);
                }
                else {
                    return is_array($value) ? @array_map('Custom_Config_Xml::addslashedArray', $value) : addslashes(strip_tags($value));
                }
            }
            else {
                if (get_magic_quotes_gpc()) {
                    return $value;
                }
                else {
                    return is_array($value) ? @array_map('Custom_Config_Xml::addslashedArray', $value) : addslashes($value);
                }
            }
        }
    }
    
    /**
     * 取得参数设置
     *
     * @param    void
     * @return   array
     */
    public static function getShopConfig()
    {
    	if (!file_exists(Zend_Registry::get('systemRoot') . '/data/ShopConfig.php')) {
    		$configApi = new Admin_Models_API_Config();
    		$configApi -> makeConfigFile();
    	}
    	require_once Zend_Registry::get('systemRoot') . "/data/ShopConfig.php";
    	return ShopConfig::getShopConfig();
    }
    
 }