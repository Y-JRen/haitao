<?php
class AuthController extends Zend_Controller_Action
{
   /**
    * 
    * @var Shop_Models_API_Auth
    */
	protected $_auth = null;

	/**
     * 退出提示
     */
	const LOGOUT_SUCESS = '成功退出';
	/**
     * 未填写用户名
     */
	const NO_USERNAME = '请填写用户名!';

	/**
	 * 用户名格式不正确
	 */
	const ERR_USERNAME = '用户名格式不正确';
	/**
     * 密码不一致
     */
	const NO_SAME_PASSWORD = '输入的密码不一致!';

	/**
     * 密码为空
     */
	const NO_PASSWORD = '密码及确认密码不能为空!';

	/**
     * 注册会员成功
     */
	const REGISTER_SUCESS = '注册成功!';

	/**
     * 用户名已存在
     */
	const USERNAME_EXISTS = '该用户名已存在!';

	/**
     * 用户不存在
     */
	const USERNAME_NO_EXISTS = '该用户不存在!';

	/**
     * 昵称已存在
     */
	const NICKNAME_EXISTS = '该昵称已存在!';

	/**
     * 系统错误
     */
	const ERROR = '系统错误，请稍后再次尝试!';

	/**
     * 发送邮件失败
     */
	const SEND_ERROR = '发送邮件失败,请稍候再次尝试!';

	/**
     * 验证失败
     */
	const CODE_ERROR = '验证失败!';

	/**
     * 输入的验证码错误
     */
	const ERROR_VERIFY_CODE = '验证码错误或已过期!';
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _auth = Shop_Models_API_Auth :: getInstance();
        
	}
	/**
     * Js用户状态
     *
     * @return void
     */
	public function jsAuthUserIdAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
        $_auth = $this -> _auth -> getAuth();
		exit($_auth['user_id']);
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
		!$this -> _request -> getParam('nojump') && $this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
	}
	/**
     * Js登录状态显示
     *
     * @return void
     */
	public function jsAuthStateAction()
	{
		//---------- 自动登录 start ----------
		if (!$this -> _auth -> getAuth() && $_COOKIE['auto_login']) {
			$codstr = Custom_Model_Encryption::getInstance() ->decrypt($_COOKIE['auto_login'],'EncryptCode');
			$userInfo = unserialize($codstr);
			if($userInfo['user_name'] && $userInfo['password'])
			{
				$authed = $this -> _auth -> certification($userInfo['user_name'], $userInfo['password'], array('utype' => 'member'));
			}
		}
		//---------- 自动登录 end -----------
		
		$getAId = $this -> _request -> getParam($this -> _actName);
		if ($getAId) {
			$activity = new Shop_Models_API_Activity();
			$activity -> setActivityCookie();
		}
		
        $loginUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/login.html';
        $regUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/reg.html';
        $logoutUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/logout.html';
		$login = $this -> _auth -> jsAuthState($loginUrl, $regUrl, $logoutUrl);
		exit($login);
	}

	/**
	 * 购物车快速认证
	 *
	 * @return json
	 * */
	public function fastLoginAction()
	{
		if ($this -> _request -> isPost()){
		    $username = $this -> _request -> getPost('user_name');
            $password = $this -> _request -> getPost('password');
            $filterChain = new Zend_Filter();
            $filterChain -> addFilter(new Zend_Filter_StripTags());      
            $username = $filterChain -> filter($username);
            $password = $filterChain -> filter($password);
            $utype = 'member';   
            $authed = $this -> _auth -> certification($username, $password, array('utype' => $utype));
            if($authed === true){            	
	            echo json_encode(array('status'=>'yes','msg'=>'登录成功！','user_name'=>$username));
	            exit;
            }else{
            	echo json_encode(array('status'=>'no','msg'=>'用户名或密码错误！'));exit;
            }
            
		}else{
            echo json_encode(array('status'=>'no','msg'=>'非法操作！'));exit;
		}
	}

	/**
     * 用户登录
     *
     * @return void
     */
	public function loginAction(){		
		
		if ($this -> _request -> isPost()) {
            $username = $this -> _request -> getPost('user_name');
            $password = $this -> _request -> getPost('password');

            $filterChain = new Zend_Filter();
            $filterChain -> addFilter(new Zend_Filter_StripTags());
            $username = $filterChain -> filter($username);
            $password = $filterChain -> filter($password);

            $utype ='member';
            $verifyCode = $this -> _request -> getPost('verifyCode');
            $authImage = new Custom_Model_AuthImage('shopLogin');
            if (!$authImage -> checkCode($verifyCode)){
            	echo Zend_Json::encode(array('status'=>0,'msg'=>'验证码错误！'));
            	exit();
            }
            $authed = $this -> _auth -> certification($username, $password, array('utype' => $utype));
            if ($authed === true) {
            	$auto_login = $this -> _request -> getPost('auto_login');
            	if ($auto_login) {
            		$userinfo = array();
            		$userinfo['user_name'] = $username;
            		$userinfo['password'] = $password;
            		$strinfo = Custom_Model_Encryption::getInstance() -> encrypt(serialize($userinfo),'EncryptCode');
            		setcookie('auto_login',$strinfo,time() + 86400*7,'/');
            	}
                $goto = base64_decode($this -> _request -> getParam('goto'));
                $goto = strpos($goto, 'payment/respond') ? '' : $goto;
                $refer = base64_decode($this -> _request -> getPost('refer'));
                $refer = strpos($refer, 'payment/respond') ? '' : $refer;
                $refer = strpos($refer, 'login.html') ? '' : $refer;
                $refer = strpos($refer, 'reg.html') ? '' : $refer;
                $url = ($goto) ? $goto : (($refer) ? $refer : Zend_Controller_Front::getInstance() -> getBaseUrl());
                echo Zend_Json::encode(array('status'=>1,'url'=>$url,'msg'=>'登录成功,正在跳转……'));
                exit();
            } else {
                echo Zend_Json::encode(array('status'=>0,'msg'=>'用户名或密码错误！'));
                exit();
            }          
		} else {
			if ($this -> _auth -> getAuth()) {
				if (strpos($_SERVER['HTTP_REFERER'],'login.html')) {
					$_SERVER['HTTP_REFERER'] = Zend_Controller_Front::getInstance() -> getBaseUrl();
				}
				$this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
			}
            if ($this->_request->getParam('goto', '')) {
                $this->view->goto = $this->_request->getParam('goto');
            }            
            $this -> view -> refer = base64_encode(addslashes(strip_tags(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '')));
			$referer = ($_SERVER['HTTP_REFERER']) ? preg_replace('/^[a-z]+:\/\/[^\/]+/i', '', $_SERVER['HTTP_REFERER']) : '';
			$this -> getResponse() -> setHeader('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT')
			                       -> setHeader('Cache-Control', 'no-cache, must-revalidate')
			                       -> setHeader('Pragma', 'no-cache');
		}
		$this -> view -> css_more = ',auth.css';
		$this -> view -> js_more = ',login.js';
	}

	/**
     * 用户注册
     *
     * @return void
     */
	public function regAction()
	{
		//Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this -> view -> page_title = "会员注册-国人海淘网";
        $this -> view -> page_keyword = "会员注册,保健品";
        $this -> view -> page_description = '国药电商 -专业的健康品商城，绝对正品保证，支持货到付款，30天退换货保障! ';
        $this -> view -> js_more = ',validform.js';
        $this -> view -> css_more = ',auth.css';
        if ($this->_request->getParam('goto', '')) {
            $this->view->goto = $this->_request->getParam('goto');
        }else{
        	$this->view->goto = '/';
        }
        $refer = addslashes(strip_tags(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));       
        $this -> view -> refer = base64_encode($refer);
		if ($this -> _auth -> getAuth()) {
            if (strpos($_SERVER['HTTP_REFERER'],'/login.html') || strpos($_SERVER['HTTP_REFERER'],'/reg.html')) {
                $_SERVER['HTTP_REFERER'] = Zend_Controller_Front::getInstance() -> getBaseUrl();
            }
			$this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
		}
	}
	/**
     * 电话下单登录
     *
     * @return void
     */
	public function mixLoginAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$code = $this -> _request -> getParam('code');
        $operator_id =(int)$this -> _request -> getParam('operator_id');
		if ($code) {
            if($operator_id){
                setcookie('operator_id',$operator_id, time() + 86400, '/');
            }
            setcookie('u','', time() + 30*86400, '/');
			$this -> _auth -> mixLogin($code, 'phone');
		}
		$this -> _helper -> redirector('index', 'index');
	}
	/**
     * 用户注册
     *
     * @return void
     */
	public function registerAction()
	{
		if ($this -> _request -> isPost()) {
			$result = $this -> _auth -> register($this -> _request -> getPost());
            $message = '';  // 系统执行信息
            $status = false; // 用于前台显示样式
            $is_success = "NO";
			switch ($result) {
				case 'errUserName':
					$message = self::ERR_USERNAME;
					break;
        		case 'noUserName':
        		    $message = self::NO_USERNAME;
        		    break;
        		case 'noSamePassword':
        		    $message = self::NO_SAME_PASSWORD;
        		    break;
        		case 'noPassword':
        		    $message = self::NO_PASSWORD;
        		    break;
        		case 'userNameExists':
        		    $message = self::USERNAME_EXISTS;
        		    break;
        		case 'nickNameExists':
        		    $message = self::NICKNAME_EXISTS;
        		    break;
                case 'ERROR_VERIFY_CODE':
                    $message = self::ERROR_VERIFY_CODE;
                    break;
        		case 'addUserSucess':
        		    $this->view->is_success = "YES";
        		    $message = self::REGISTER_SUCESS;
                    $status = true;
                    $username = $this -> _request -> getPost('user_name');
        		    $password = $this -> _request -> getPost('password');

                    $filterChain = new Zend_Filter();
                    $filterChain -> addFilter(new Zend_Filter_StripTags());
                    $username = $filterChain -> filter($username);
                    $password = $filterChain -> filter($password);

        		    $utype = 'member';
        		    $this -> _auth -> certification($username, $password, array('utype' => $utype));

                    
                    $this -> view -> auth = $auth;
					$this->view->username = $username;
                    $goto = base64_decode($this -> _request -> getParam('goto'));
                    $refer = base64_decode($this -> _request -> getPost('refer'));
                    $this->view->goto =  $goto;
                    $this->view->refer =  $refer;

        		    break;
        		case 'error':
        		    $message = self::ERROR;
                    break;
                default:
                    break;
        	}
            $this -> view -> status = $status;
            $this -> view -> message = $message;
            $this -> view -> refer = '/reg.html';
		} else {
			$this -> _helper -> redirector('index', 'index');
		}
	}
	
	
	/**
     * 检测注册用户信息
     *
     * @return void
     */
	public function checkAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$data = $this -> _request -> getParams();
		$data['user_name'] = $data['param'];
		if($data['user_name'] == '邮箱/手机/用户名')
		{
	      echo  Zend_Json::encode(array('status'=>'n','info'=>'输入有效的邮箱/手机/用户名!'));
		  exit();	
		}
		$user = $this -> _auth -> check($data['user_name']);
		if (!$user) {
			echo  Zend_Json::encode(array('status'=>'y','info'=>'账号可以注册!'));
			exit();		 
		} else {
          echo  Zend_Json::encode(array('status'=>'n','info'=>'账号已被使用!'));
		  exit();	
        }
	}
	
	public function checkRegCodeAction()
	{
		$data = $this -> _request -> getParams();
		$data['verifyCode'] = $data['param'];			
		$authImage = new Custom_Model_AuthImage('shopRegister');
		if ($authImage -> checkCode($data['verifyCode'])) {		
			echo  Zend_Json::encode(array('status'=>'y','info'=>'验证通过!'));
			exit();
		} else {
			echo  Zend_Json::encode(array('status'=>'n','info'=>'验证码错误!'));
			exit();
		}
		
	}
	/**
     * 找回密码
     *
     * @return void
     */
	public function getPasswordAction()
	{
        $this -> view -> page_title = "找回密码 ";
        $this -> view -> page_keyword = "找回密码,密码找回,会员注册";
        $this -> view -> page_description = '';
        $this -> view -> css_more = ',auth.css';
        $this -> view -> type = 1;
		if ($this -> _request -> isPost()) {//接收到申请密码找回请求
			if($this -> _request -> getPost('type') == 1){//输入帐号
				$username = $this -> _request -> getPost('name');
				$verifyCode = $this -> _request -> getParam('verifyCode');
				$authImage = new Custom_Model_AuthImage('getPwd');
				if (!$authImage -> checkCode($verifyCode)) {
					Custom_Model_Message::showAlert(self::ERROR_VERIFY_CODE , false);
				}else{
					$result = $this -> _auth -> getUserByName($username);
					$result = $result[0];
					if($result){
					    $_SESSION['u_name'] = $username;
						$this -> view -> mobile = $result['mobile']? 1 : 0;
						$this -> view -> email = $result['email'] ? 1 : 0;
						$this -> view -> status = ($result['mobile'] || $result['email']) ? 1 : 0;
						$this -> view -> type = 2;
					}else{
					    Custom_Model_Message::showAlert(self::USERNAME_NO_EXISTS,true,'/auth/get-password');
						exit;
					}
				}
			}elseif($this -> _request -> getPost('type') == 2){//邮箱or手机
			    if($_SESSION['u_name']){
			        if($set_type = $this -> _request -> getParam('set_type')){
    			        switch ($set_type){
    			            case 'mobile':
    			                $this -> view -> status = 2;
    			                $this -> view -> type = 2;
    			                $result = $this -> _auth -> getUserByName($_SESSION['u_name']);
    			                $this -> _auth -> sendSMS($result[0]['mobile']);
    			                break;
    			            case 'email':
    			                $result = $this -> _auth -> sendPassword($_SESSION['u_name']);
    			                switch ($result) {
    			                    case 'sendPasswordSucess':
    			                        Custom_Model_Message::showAlert('重置密码的邮件已经发到您的邮箱',true,'/');
    			                        break;
    			                    case 'sendError':
    			                        Custom_Model_Message::showAlert(self::SEND_ERROR);
    			                        break;
    			                    case 'noUser':
    			                        Custom_Model_Message::showAlert(self::USERNAME_NO_EXISTS);
    			                        break;
    			                }
    			                break;
    			            default:
    			                Custom_Model_Message::showAlert('系统繁忙，请稍后再试！',true,'/auth/get-password');
    			                exit;
    			        }
			        }elseif($code = $this -> _request -> getParam('code')){
			            if($code == $_SESSION['mobile_code']['value'] && ((time()-$_SESSION['mobile_code']['time'])< 185)){
			                $userObject = new Shop_Models_API_Member();
			                $tmp = $userObject -> getMemberByUserName($_SESSION['u_name']);
			                $userInfo = $tmp[0];
			                $userInfo['utype'] = 'member';
			                //var_dump($this->_auth->getPasswordCode($userInfo));die();
			                header("Location:".$this->_auth->getPasswordCode($userInfo));
			            }else{
			                Custom_Model_Message::showAlert('手机验证码错误',true,'/auth/get-password');
			                exit;
			            }
			        }
			    }else{
			        Custom_Model_Message::showAlert('系统繁忙1，请稍后再试！',true,'/auth/get-password');
			        exit;
			    }
			}
			
			
			
			
			
			/*
			$this -> _helper -> viewRenderer -> setNoRender();
			$username = $this -> _request -> getPost('name');
			$verifyCode = $this -> _request -> getParam('verifyCode');
			
			$result = $this -> _auth -> sendPassword($username);
		    echo "<script>parent.document.getElementById('dosubmit').disabled=false;</script>";
			$authImage = new Custom_Model_AuthImage('getPwd');
			if (!$authImage -> checkCode($verifyCode)) {
				 Custom_Model_Message::showAlert(self::ERROR_VERIFY_CODE , false);
				 exit;
			}
		    switch ($result) {
        	    case 'sendPasswordSucess':
        	        echo "<script>parent.document.getElementById('send_password_div').style.display='block';parent.document.getElementById('send_password_msg').innerHTML='<b>重置密码的邮件已经发到您的邮箱:".$email."</b>';</script>";
        	        break;
        	    case 'sendError':
        	        Custom_Model_Message::showAlert(self::SEND_ERROR);
        	        break;
        	    case 'noUser':
        	        Custom_Model_Message::showAlert(self::USERNAME_NO_EXISTS);
        	        break;
		    }
		    exit;
		    */
		} elseif($this -> _request -> getParam('code')) {//接收到重置密码的code

			$code = $this -> _request -> getParam('code');
			if ($code) {
				if ($this -> _auth -> setPassword($code)) {
					$this -> _helper -> redirector('password', 'member');
				} else {
					Custom_Model_Message::showAlert(self::CODE_ERROR, false, '/index');
				}
			}

		} else {//登录状态下，直接跳转
			if ($this -> _auth -> getAuth()) {
				$this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
			}
		}	
	}

	/**
     * 创建认证图片
     *
     * @return void
     */
	public function authImageAction()
	{
		$this -> _helper -> viewRenderer ->setNoRender();
        $space = $this -> _request -> getParam('space');
        $authImage = new Custom_Model_AuthImage($space);
        $authImage -> createImage();
        exit();
	}
	
	
	public function checkMobileCode()
	{
	    $code = $this -> _request -> getParam('code');
	    if($code){
	        if($code == $_SESSION['mobile_code']['value'] && ((time()-$_SESSION['mobile_code']['time'])< 1800)){
	            
	        }
	    }
	}
	
	//跨境通联合登入
	public function unionLoginAction()
	{
	    $un = $this -> _request -> getParam('un',null);
	    $refer = $this -> _request -> getParam('refer',null);
	    $refer = $refer ? base64_decode($refer) : Zend_Controller_Front::getInstance() -> getBaseUrl();
	    switch($un){
	        case 'kjt':
	            $kjtApi = new Shop_Models_Api_Kjt();
	            $kjtApi -> unionLogin($refer);
	            break;
	        default:
	            break;
	    }
	    exit;
	}
	
	public function kjtAction()
	{
	    $data = $_REQUEST;
	    $kjtApi = new Shop_Models_Api_Kjt();
	    $kjtApi -> login($data);
	    exit;
	}
	
	public function ssoReturnAction()
	{
	    var_dump(Zend_Controller_Front::getInstance() -> getBaseUrl());die();
	    $data = $_REQUEST;
	    $kjtApi = new Shop_Models_Api_Kjt();
	    $kjtApi -> ssoReturn($data);
	    exit;
	}
	
}