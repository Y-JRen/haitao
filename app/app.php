<?php
define('SYS_VERSION','20140625'); //系统版本
/**
 * 项目引导类
 *
 * @return void
 */
 class app{
	public $config = null;
    public $systemRoot = null;
	public $xml = null;
	public $frontController = null;
	public $router = null;
	public $modules = null;
	public $view = null;
	public $viewRenderer = null;
	public $routesconfig = null;
	
	/**
     * 对象初始化
     * 
     * @return void
     */
	public function __construct($systemRoot)
    {
		$this -> systemRoot = $systemRoot;
		$this -> xml = new Custom_Config_Xml();
		$this -> config = $this -> xml -> getConfig();
		$this -> frontController = Zend_Controller_Front::getInstance();
		$this -> router = new Zend_Controller_Router_Rewrite();
		$this -> modules = $this -> xml -> getModules();
		$this -> view = new Custom_View_Smarty($this->config -> view -> smarty -> toArray());
		Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_Memcached($this->config -> session -> memcached -> toArray()));
	}
	public function setRegistry(){
	    Zend_Registry::set('systemRoot', $this -> systemRoot);
		Zend_Registry::set('config', $this -> config);
		Zend_Registry::set('db', new Custom_Model_Db($this->config -> database));
		Zend_Registry::set('shopConfig', $this -> xml -> getShopConfig());
	}
	public function initController(){
		$this->routesconfig = new Zend_Config_Ini($this->systemRoot. '/config/routes.ini', 'production');
		$this->router->addConfig($this -> routesconfig, 'routes');
		$this->frontController -> setRouter($this->router);
		$this->frontController -> setControllerDirectory($this -> modules);
        $this->frontController -> setDefaultModule(array_shift(array_keys($this -> modules)));
		$this->view -> getEngine() -> setZendView($this->view);
		$this->viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($this -> view);
		$this->viewRenderer -> setViewSuffix($this -> config -> view -> suffix)
							-> setViewBasePathSpec(dirname($this->config -> view -> smarty -> template_dir));
		Zend_Controller_Action_HelperBroker::addHelper($this -> viewRenderer);
		$this->frontController -> registerPlugin(new Custom_Controller_Plugin_Layout());
		$this->frontController -> registerPlugin(new Custom_Controller_Plugin_init());
		//$this->frontController -> throwExceptions (false);
		$this->frontController -> dispatch();
	}
	public function run(){		
		$this -> setRegistry();
		$this -> initController();
	}
 }
 ?>
