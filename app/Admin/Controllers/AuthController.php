<?php
class Admin_AuthController extends Zend_Controller_Action
{
	/**
     * 认证 API
     * 
     * @var Admin_Models_API_Auth
     */
	private $_auth = null;
	
	/**
     * 认证 SESSION
     * 
     * @var Zend_Session_Namespace
     */
	private $_codeSession = null;
	

    /**
     * 允许操作的管理员列表
     * @var array
     */	
    private $_allowDoList = array ('root');

	/**
     * IP限制
     * 
     * @var    array
     */
 	private $_ipArray = array('58.247.53.254' => 1);   

	/**
     * 认证图片设置
     * 
     * @var Custom_Model_AuthImage
     */
	private $_authImageConfig = null;
 
	/**
     * 输入的验证码错误
     */
	const ERROR_VERIFY_CODE = '验证码错误或已过期!';
	
	/**
     * 重新登录成功提示
     */
	const RELOGIN_SUCESS = '重新登录成功';
	
	/**
     * 退出提示
     */
	const LOGOUT_SUCESS = '成功退出';
	
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _authImage = new Custom_Model_AuthImage('adminLogin');
		$this -> _auth = Admin_Models_API_Auth :: getInstance();
	}
	
	/**
     * 默认转向
     *
     * @return void
     */
	public function indexAction()
	{
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$this -> _helper -> redirector('index', 'index');
	}
	
	/**
     * 用户登录
     *
     * @return void
     */
	public function loginAction() 
	{
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		if ($this -> _auth -> getAuth()) {
			$this -> _helper -> redirector('index', 'index');
		}
		$this -> view -> message = '';
		if ($this -> _request -> isPost()) {
            $username = $this -> _request -> getPost('user_name');
            $password = $this -> _request -> getPost('password');
            $verifyCode = $this -> _request -> getPost('verifyCode');
/*
            if(!in_array($username,$this -> _allowDoList)){
                $isLocalIp = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || substr($_SERVER['REMOTE_ADDR'], 0, 9) == '192.168.1';
                if(!isset($this -> _ipArray[$_SERVER['REMOTE_ADDR']]) && !$isLocalIp) {
                    header("Location:http://www.1jiankang.com/");
                    exit;
                }
            }

*/

            if (!$this -> _authImage -> checkCode($verifyCode)) {
				$this -> view -> message = self::ERROR_VERIFY_CODE;
            } else {
                $authed = $this -> _auth -> certification($username, $password, $extra = null);
			    if ($authed === true) {
			        $this -> _helper -> redirector('index', 'index');
			    } else {
			        $this -> view -> message = $authed;
			    }
            }
		} else {
			$this -> getResponse() -> setHeader('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT')
			                       -> setHeader('Cache-Control', 'no-cache, must-revalidate')
			                       -> setHeader('Pragma', 'no-cache');
		}
	}
	
	/**
     * 用户重新登录
     *
     * @return void
     */
	public function reloginAction() 
	{
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$this -> view -> message = '';
		if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $username = $this -> _request -> getPost('user_name');
            $password = $this -> _request -> getPost('password');
            $verifyCode = $this -> _request -> getPost('verifyCode');
            if (!$this -> _authImage -> checkCode($verifyCode)) {
				Custom_Model_Message::showAlert(self::ERROR_VERIFY_CODE);
            } else {
                $authed = $this -> _auth -> certification($username, $password, $extra = null);
			    if ($authed === true) {
			    	echo "<script>window.top.resetTimer();window.top.alertBox.closeDiv('relogin');</script>";
			    } else {
			        Custom_Model_Message::showAlert($authed);
			    }
            }
		} else {
			$this -> getResponse() -> setHeader('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT')
			                       -> setHeader('Cache-Control', 'no-cache, must-revalidate')
			                       -> setHeader('Pragma', 'no-cache');
		}
	}
	
	/**
     * 创建认证图片
     *
     * @return void
     */
	public function authImageAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
        $space = $this -> _request -> getParam('space');
        $authImage = new Custom_Model_AuthImage($space,90,30);
        $authImage -> createImage();
        exit;
	}
	
	/**
     * 用户登出
     *
     * @return void
     */
	public function logoutAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$this -> _auth -> unsetAuth();
		$this -> _helper -> redirector('index', 'index');
	}

	/**
     * 登录日志
     *
     * @return void
     */
	public function logAction()
	{
        $search = $this->_request->getParams();
        $this -> view -> param = $search;
		$page = (int)$this -> _request -> getParam('page', 1);
        $datas = $this -> _auth -> getLog($search,'*',null ,$page ,'25');
        foreach ($datas['list'] as $num => $data)
        {
        	$datas['list'][$num]['login_time'] = ($datas['list'][$num]['login_time'] > 0) ? date('Y-m-d H:i:s', $datas['list'][$num]['login_time']) : '';
        }
        $this -> view -> datas = $datas['list'];
	    $pageNav = new Custom_Model_PageNav($datas['total'], '25', 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}
}