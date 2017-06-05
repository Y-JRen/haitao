<?php
class Admin_IndexController extends Zend_Controller_Action
{
	/**
     * 后台首页 API
     * @var Admin_Models_API_Index
     */
    private $_index = null;
    
	/**
     * 所有有效的模块
     *
     * @var    array
     */
	private $_modules = array();
	
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _index = new Admin_Models_API_Index();
		$this -> _admin = $this -> _index -> getAuth();
		$this -> view -> admin = $this -> _admin;
		$this -> _menu = new Admin_Models_API_Menu();
		if ($this -> _admin && !in_array($this -> _admin['admin_id'], array(1))){
			$menus = $this -> _admin['menu'];
			if ($menus){
			    $this -> _menuWhere = " and menu_id in($menus)";
			}else{
				$this -> _helper -> redirector('auth', 'login');
			}
		}
	}
	/**
     * 后台首页
     *
     * @return void
     */
	public function indexAction()
	{
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$systemMessage = $this -> _index -> getSystemMessage();
		$menus = $this -> _menu -> get("menu_status=0 and parent_id=0".$this -> _menuWhere);
		$this -> view -> menus = $menus;
		$this -> view -> init = $menus[0]['menu_id'];
		$this -> view -> lifeTime = @ini_get('session.gc_maxlifetime');
		if ($this -> _admin &&     !in_array($this -> _admin['group_id'], array(0,1))) {
			$this -> view -> clearCache = true;
		}
		$stockAPI = Custom_Model_Stock_Base::getInstance($this -> _admin['lid']);
		$this -> view -> currentAreaName = $stockAPI -> getAreaName();
		$areaInfo = $stockAPI -> getConfigLogicArea();
		if (!$this -> _admin['group_type']) {
    		if ($this -> _admin['lid'] == 1) {
    		    $this -> view -> switchAreaID = 2;
    		}
    		else if ($this -> _admin['lid'] == 2) {
    		    $this -> view -> switchAreaID = 1;
    		}
    		$this -> view -> switchAreaName = $areaInfo[$this -> view -> switchAreaID];
        }
		$this -> view -> areaInfo = $areaInfo;
	}
	
	public function switchAction()
	{
	    $lid = $this -> _request -> getParam('lid');
	    if ($lid && !$this -> _admin['group_type']) {
	        $config = Custom_Model_Stock_Base::getInstance($lid);
	        if ($config -> getAreaName($lid)) {
        	    $authDB = new Admin_Models_DB_Auth();
        	    $authDB -> updateLid($this -> _admin['admin_name'], $lid);
        	    $authAPI = new Admin_Models_API_Auth();
        	    $this -> _admin['lid'] = $lid;
        	    $authAPI -> setCertifiction($this -> _admin);
        	    
        	    echo 'ok';
    	    }
        }
        exit;
	}
	
    /**
     * 后台菜单
     *
     * @return void
     */
	public function menuAction()
	{
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$pid = $this -> _request -> getParam('pid');
		$menu = $this -> _menu -> menuTree("menu_status=0 and menu_path like '%,$pid,%'".$this -> _menuWhere);
		$this -> view -> pid = $pid;
		$this -> view -> menu = $menu;
	}
	/**
     * 系统信息
     *
     * @return void
     */
	public function infoAction()
	{
		$this -> view -> systemMessage = $this -> _index -> getSystemMessage();
	}
    /**
     * 发送手机短信
     *
     * @return void
     */
	public function sendmsgAction()
	{
		if($this -> _request -> isPost()){
			$this -> _helper -> viewRenderer -> setNoRender();
			//发送手机短信
		
			$sms= new  Custom_Model_Sms();
			$response =   $sms->send(trim($this -> _request -> getParam('mobile')),trim($this -> _request -> getParam('msg')),2);
			if(!$response){
				Custom_Model_Message::showMessage('短信发送失败','event',1250,'window.top.alertBox.closeDiv()');
			}
			$sendmsglog = new Admin_Models_API_Operation();
			$sendmsglog->addCustomerMsg($this -> _request -> getPost());
			Custom_Model_Message::showMessage('短信发送成功','event',1250,'window.top.alertBox.closeDiv()');
		}
	}
    /**
     * 发送系统邮件给用户
     *
     * @return void
     */
	public function sendEmailAction()
	{
		 if ($this -> _request -> isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$sendmail = new Custom_Model_Mail();
			$sendmail -> send(trim($this -> _request -> getParam('email')), trim($this -> _request -> getParam('title')) , trim($this -> _request -> getParam('emailmsg')) ,'service@1jiankang.com') ;
			Custom_Model_Message::showMessage('邮件发送成功','event',1250,'window.top.alertBox.closeDiv()');
		 }
	}

	/**
     * 清除缓存
     *
     * @return void
     */
	public function cleanCacheAction()
	{
        $logFile = Zend_Registry::get('systemRoot').'/tmp/sytemCache/cleancache'.date('Ymd').'.txt';
        if($this -> _request -> isPost()){
	        $params = $this -> getRequest() -> getParams();
	        $type = trim($params['type']);
            error_log('清理时间:' . date('Y-m-d H:i:s') . ' TAG: ' 
                    . ($type == 'custom' ? 'CTM:' . $params['mod'] . ':'. $params['ctl'] . ':' . $params['act'] : $type) . ' -- 清理人:' 
                    . ($this->_admin['real_name'] ? $this->_admin['real_name'] : $this->_admin['admin_name']) 
                    . "\n", 3, $logFile);
            switch ($type){
	            case 'index':
					$this ->memcachedapi = new Custom_Model_Memcached('index');
	            	if ($this ->memcachedapi -> clean('index')){
				        $status = 'ok';
				    }
	            break;
	            case 'goods':
					$this ->memcachedapi = new Custom_Model_Memcached('goods');
	            	if ($this ->memcachedapi -> clean('goods')){
				        $status = 'ok';
				    }
	            break;
                case 'brand':
					$this ->memcachedapi = new Custom_Model_Memcached('brand');
	            	if ($this ->memcachedapi -> clean('brand')){
				        $status = 'ok';
				    }
                case 'help':
					$this ->memcachedapi = new Custom_Model_Memcached('help');
	            	if ($this ->memcachedapi -> clean('help')){
				        $status = 'ok';
				    }
	            break;
	            case 'all':
					$this ->memcachedapi = new Custom_Model_Memcached();
	            	if ($this ->memcachedapi -> cleanAll()){
				        $status = 'ok';
				    }
				break;
	            default:
	            	exit('缓存清除失败');
                break;
		    }
		    if ($status == 'ok'){
		    	exit('缓存清理成功!');
		    } else {
		    	exit('缓存清除失败');
		    }
		} 
	}

    private function _evalStr($str,$data)
    {
        $PARAM = $data;
    	eval("\$str = \"$str\";");
    	return $str;
    }
   
    /**
     * 清除CDN缓存
     *
     * @return void
     */
	public function cleanCacheCdnAction()
	{
	    if( $this -> _request -> isPost() ) {
	        $params = $this -> getRequest() -> getParams();
	        if ($params['type'] == 'img_folder' || $params['type'] == 'img_file') {
	            if (!$params['img']) {
	                exit('必须输入图片路径!');
	            }
	        }
	        $CdnAPI = new Custom_Model_Cdn();
            $result = $CdnAPI -> cleanAll($params['domain'], $params['type'], $params['img'], $params['url']);
		    if ($result == '0') {
		    	exit('缓存清理成功!');
		    }
		    else {
		    	exit('缓存清除失败('.$result.')!');
		    }
	    }
	}
}