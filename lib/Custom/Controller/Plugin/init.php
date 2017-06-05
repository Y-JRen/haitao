<?php
class Custom_Controller_Plugin_init extends Zend_Controller_Plugin_Abstract
{
	/**
     * 缓存配置文件
     * 
     * @var    string
     */
 	private $_cacheConfigFile = '../config/cache.xml';

    /**
     * 预处理
     *
     * @param    Zend_Controller_Request_Abstract    $request
     * @return   void
     */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$config = Zend_Registry::get('config');
		$real_ip = Custom_Model_Ip::_real_ip();
      
        if ($config -> auth && $config -> auth -> must_login) {
            $controllerName = $request -> getControllerName();
        	$configAuthUrlArray = explode('/', trim($config -> auth -> url, '/'));
        	if ($controllerName == $configAuthUrlArray[1]) {
        		return;
        	}
        	$authClass = $config -> auth -> class_name;
        	$certification = new $authClass();
        	if (!$certification instanceof Custom_Model_AuthInterface) {
                throw new Zend_Exception("Certification class '" . $authClass . "' does not implements Custom_Model_AuthInterface");
            }
            // 身份验证
        	if (!$certification -> getAuth()) {
        		if ($config -> auth -> url && !$request -> isXmlHttpRequest()) {
                    header('Location:'.$config -> auth -> url);
                    exit;
        		} else {
        			$this -> getResponse() -> setHeader('Content-Type', 'text/html; charset=utf-8');
        		    exit('<a href="javascript:fGo()" onclick="window.location.reload();">请先登录!</a>');
        		}
        	} else if ($request->getModuleName() =='admin') {
        		// 权限认证
        	 	$privilege = $certification -> getPrivilege($request -> getModuleName(), $request -> getControllerName(), $request -> getActionName());        		
        	    if ($privilege != true && $request -> getControllerName() != 'index') {
        			if (!$request -> isXmlHttpRequest()) {
        				exit('您没有该操作权限!');
        			}else{
	        			echo "<script>alert('您没有该操作权限!')</script>";
	        			exit;
        			}
        	    }
        	}
        }
    }
    /**
     * 预处理
     *
     * @param    Zend_Controller_Request_Abstract    $request
     * @return   void
     */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$cacheconfig = Custom_Config_Xml::loadXml($this -> _cacheConfigFile);
    	$module = $request -> getModuleName();
    	$controller = $request -> getControllerName();
    	$action = $request -> getActionName();
    	is_object($cacheconfig -> cache -> $module) && $cache = $cacheconfig -> cache -> $module -> toArray();
    	is_object($cacheconfig -> clean -> $module) && $clean = $cacheconfig -> clean -> $module -> toArray();
		$this -> _params = $request -> getParams();
        if ($cache[$controller][$action] !== null || $cache[$controller]['_all'] !== null) {
            $gmtLastTime = gmdate('D, d M Y H:i:s', time()) . ' GMT';
            header('Expires: '.gmdate('D, d M Y H:i:s', time() + 10800).' GMT');   
            header('Cache-Control: max-age=10800');
            header('Pragma: ');
            header('Last-Modified: ' .$gmtLastTime);
        }
    }
	/**
     * 缓存处理 
     *
     * @param    Zend_Controller_Request_Abstract    $request
     * @return   void
     */
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
    	$config = Custom_Config_Xml::loadXml($this -> _cacheConfigFile);
    	$module = $request -> getModuleName();
    	$controller = $request -> getControllerName();
    	$action = $request -> getActionName();
    	is_object($config -> cache -> $module) && $cache = $config -> cache -> $module -> toArray();
    	is_object($config -> clean -> $module) && $clean = $config -> clean -> $module -> toArray();
		$this -> _params = $request -> getParams();
    	if ($module == 'admin'){
	    	if ($clean[$controller][$action] !== null || $clean[$controller]['_all'] !== null) {
	    		$rules = ($clean[$controller][$action] !== null) ? $clean[$controller][$action] : $clean[$controller]['_all'];
	    		$tags = strstr($rules, '@');
	    		$method = str_replace($tags, '', $rules);
	    		if ($method == 'post' && $request -> isPost() || $method != 'post') {
	    			$tags = explode('|', $this -> parseStr(substr($tags, 1)));
					$this ->memcachedapi = new Custom_Model_Memcached($tags);
					$this ->memcachedapi -> clean($tags);
	    		}
	    	}
    	}else{
	      	if ($cache[$controller][$action] !== null || $cache[$controller]['_all'] !== null) {
	    		$this -> _id = $this -> parseStr(($cache[$controller][$action] !== null) ? $cache[$controller][$action] : $cache[$controller]['_all']);
				$configxml = Zend_Registry::get('config');
				if ($configxml -> cache -> frontend -> caching ) {
					$this ->memcachedapi = new Custom_Model_Memcached($controller);
					$data = $this ->memcachedapi-> get($this -> _id);
					$data && exit($data);
				}
	    	}
    	}
    }
  
   /**
     * 缓存处理
     *
     * @return   void
     */
	public function dispatchLoopShutdown()
    {
    	$html = $this -> getResponse() -> getBody();
    	if ($this -> _id !== null) {
    		preg_match_all('/<LINK[\s]+href=[^>]+>/is', $html, $links);
    		$links[0] && $css = implode("\n", $links[0]) . "\n";
    		preg_match_all('/<STYLE[^>]*>\s*((<!--|)?[^<]+)<\/STYLE>/is', $html, $styles);
    		$styles[1] && $css .= "<style>\n" . implode("\n", $styles[1]) . "</style>";
    		$html = preg_replace(array('/<STYLE[^>]*>\s*((<!--|)?[^<]+)<\/STYLE>/is', '/<link href=[^>]+>/is'), '', $html);
    		$html = preg_replace("/<\/head>/i", "$css\n</head>", $html);
            $config = Zend_Registry::get('config');
			if ($config -> cache -> frontend -> caching ) {
				$this ->memcachedapi-> set($this -> _id,$html,'3600');
			}
    	}
    }
    /**
     * 处理配置
     *
     * @param    string    $str
     * @return   string
     */
    public function parseStr($str)
    {
    	$PARAM = $this -> _params;
    	$COOKIE = $_COOKIE;
    	eval("\$str = \"$str\";");
    	return $str;
    }
}