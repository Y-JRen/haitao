<?php
 class Shop_Models_API_Auth implements Custom_Model_AuthInterface
 {
    /**
     * 新浪 联合登录 参数
     */
     /**
      * @ignore
      */
     public $client_id="1862192494";
     /**
      * @ignore
      */
     public $client_secret="79d5f028bca9a42e6cf4055c69681c84";
     /**
      * @ignore
      */
     public $access_token;
     /**
      * @ignore
      */
     public $refresh_token;
     /**
      * Contains the last HTTP status code returned.
      *
      * @ignore
      */
     public $http_code;
     /**
      * Contains the last API call.
      *
      * @ignore
      */
     public $url;
     /**
      * Set up the API root URL.
      *
      * @ignore
      */
     public $host = "https://api.weibo.com/2/";
     /**
      * Set timeout default.
      *
      * @ignore
      */
     public $timeout = 30;
     /**
      * Set connect timeout.
      *
      * @ignore
      */
     public $connecttimeout = 30;
     /**
      * Verify SSL Cert.
      *
      * @ignore
      */
     public $ssl_verifypeer = FALSE;
     /**
      * Respons format.
      *
      * @ignore
      */
     public $format = 'json';
     /**
      * Decode returned json data.
      *
      * @ignore
      */
     public $decode_json = TRUE;
     /**
      * Contains the last HTTP headers returned.
      *
      * @ignore
      */
     public $http_info;
     /**
      * Set the useragnet.
      *
      * @ignore
      */
     public $useragent = 'Sae T OAuth2 v0.1';
     
     /**
      * print the debug info
      *
      * @ignore
      */
     public $debug = FALSE;
     
     /**
      * boundary of multipart
      * @ignore
      */
     public static $boundary = '';
     
     /**
      * Set API URLS
      */
     /**
      * @ignore
      */
     function accessTokenURL()  { return 'https://api.weibo.com/oauth2/access_token'; }
     /**
      * @ignore
      */
     function authorizeURL()    { return 'https://api.weibo.com/oauth2/authorize'; }
    /**
     * QQ登录 参数
     * @var unknown_type
     */ 
    public $qq_params=array(
            "appid"=>"100481130",
            "callback"=>"http://www.1jiankang.com/auth/qqcallback",
            "scope"=>"get_user_info",
            "appkey"=>"d459dbdf57c9901c0ba77c97b9bdcb70"
    );
    /**
     * QQ 对接所需参数
     * @var unknown_type
     */
    const VERSION = "2.0";
    const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
    const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
    const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";
    const GET_USER_INFO = "https://graph.qq.com/user/get_user_info";
    /**
     * QQ登录错误码
     */
    private $errorMsg= array(
            "20001" => "<h2>配置文件损坏或无法读取，请重新执行intall</h2>",
            "30001" => "<h2>The state does not match. You may be a victim of CSRF.</h2>",
            "50001" => "<h2>可能是服务器无法请求https协议</h2>可能未开启curl支持,请尝试开启curl支持，重启web服务器，如果问题仍未解决，请联系我们"
            );
 	/**
     * 认证 DB
     *
     * @var Shop_Models_DB_Auth
     */
	protected $_db = null;

	/**
     * 用户认证名字空间名称
     *
     * @var string
     */
	protected $_userCertificationName = '';

	/**
     * 用户认证名字空间
     *
     * @var Zend_Session_Namespace
     */
	protected $_certificationNamespace = null;

	/**
     * 对象实例
     *
     * @var Shop_Models_API_Auth
     */
    protected static $_instance = null;

    /**
     * 登录/注册
     */
	const REG_LGOIN = '登录/注册';

	/**
     * 未填写用户名
     */
	const NO_USERNAME = '请填写用户名!';

	/**
     * 用户名或密码错误
     */
	const ERROR_CERTIFICATION = '用户名或密码错误!';


 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
        $this -> _userCertificationName ='User';
        $this -> _certificationNamespace = new Zend_Session_Namespace($this -> _userCertificationName);
        $this -> _db = new Shop_Models_DB_Auth();
    }

    /**
     * 取得对象实例
     *
     * @return Shop_Models_API_Auth
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * QQ登录函数
     * @param unknown_type $name
     * @param unknown_type $value
     */
    public function write($name,$value){
        $_SESSION[$name] = $value;
    }
    
    public function read($name){
        if(empty($_SESSION[$name])){
            return null;
        }else{
            return $_SESSION[$name];
        }
    }
    
    public function delete($name){
        unset($_SESSION[$name]);
    }
    /**
     * combineURL
     * 拼接url
     * @param string $baseURL   基于的url
     * @param array  $keysArr   参数列表数组
     * @return string           返回拼接的url
     */
    public function combineURL($baseURL,$keysArr){
        $combined = $baseURL."?";
        $valueArr = array();
    
        foreach($keysArr as $key => $val){
            $valueArr[] = "$key=$val";
        }
    
        $keyStr = implode("&",$valueArr);
        $combined .= ($keyStr);
    
        return $combined;
    }
    /**
     * get_contents
     * 服务器通过get请求获得内容
     * @param string $url       请求的url,拼接后的
     * @return string           请求返回的内容
     */
    public function get_contents($url){
        if (ini_get("allow_url_fopen") == "1") {
            $response = file_get_contents($url);
        }else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response =  curl_exec($ch);
            curl_close($ch);
        }
    
        //-------请求为空
        if(empty($response)){
            $this->showError("50001");
        }
    
        return $response;
    }
    
    /**
     * showError
     * 显示错误信息
     * @param int $code    错误代码
     * @param string $description 描述信息（可选）
     */
    public function showError($code, $description = '$'){
        echo "<meta charset=\"UTF-8\">";
        if($description == "$"){
            die($this->errorMsg[$code]);
        }else{
            echo "<h3>error:</h3>$code";
            echo "<h3>msg  :</h3>$description";
            exit();
        }
    }
    /**
     * QQ登录
     */
    public function get_openid(){
    
        //-------请求参数列表
        $keysArr = array(
                "access_token" => $this->read("access_token")
        );
    
        $graph_url = $this->combineURL(self::GET_OPENID_URL, $keysArr);
        $response = $this->get_contents($graph_url);
    
        //--------检测错误是否发生
        if(strpos($response, "callback") !== false){
    
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }
    
        $user = json_decode($response);
        if(isset($user->error)){
            $this->showError($user->error, $user->error_description);
        }
    
        //------记录openid
        $this->write("openid", $user->openid);
        return $user->openid;
    }
    
    /**
	 * access_token接口
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
	 *
	 * @param string $type 请求的类型,可以为:code, password, token
	 * @param array $keys 其他参数：
	 *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
	 *  - 当$type为password时： array('username'=>..., 'password'=>...)
	 *  - 当$type为token时： array('refresh_token'=>...)
	 * @return array
	 */
	function getAccessToken( $type = 'code', $keys ) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		if ( $type === 'token' ) {
			$params['grant_type'] = 'refresh_token';
			$params['refresh_token'] = $keys['refresh_token'];
		} elseif ( $type === 'code' ) {
			$params['grant_type'] = 'authorization_code';
			$params['code'] = $keys['code'];
			$params['redirect_uri'] = $keys['redirect_uri'];
		} elseif ( $type === 'password' ) {
			$params['grant_type'] = 'password';
			$params['username'] = $keys['username'];
			$params['password'] = $keys['password'];
		} else {
			throw new Exception("wrong auth type");
		}

		$response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
		$token = json_decode($response, true);
		if ( is_array($token) && !isset($token['error']) ) {
			$this->access_token = $token['access_token'];
			//$this->refresh_token = $token['refresh_token'];
		} else {
			throw new Exception("get access token failed." . $token['error']);
		}
		return $token;
	}
	
	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 */
	function oAuthRequest($url, $method, $parameters, $multi = false) {
	
	    if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
	        $url = "{$this->host}{$url}.{$this->format}";
	    }
	
	    switch ($method) {
	        case 'GET':
	            $url = $url . '?' . http_build_query($parameters);
	            return $this->http($url, 'GET');
	        default:
	            $headers = array();
	            if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
	                $body = http_build_query($parameters);
	            } else {
	                $body = self::build_http_query_multi($parameters);
	                $headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
	            }
	            return $this->http($url, $method, $body, $headers);
	    }
	}
	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
	 */
	function getHeader($ch, $header) {
	    $i = strpos($header, ':');
	    if (!empty($i)) {
	        $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
	        $value = trim(substr($header, $i + 2));
	        $this->http_header[$key] = $value;
	    }
	    return strlen($header);
	}
	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	function http($url, $method, $postfields = NULL, $headers = array()) {
	    $this->http_info = array();
	    $ci = curl_init();
	    /* Curl settings */
	    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	    curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
	    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
	    curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
	    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ci, CURLOPT_ENCODING, "");
	    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
	    curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
	    curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
	    curl_setopt($ci, CURLOPT_HEADER, FALSE);
	
	    switch ($method) {
	        case 'POST':
	            curl_setopt($ci, CURLOPT_POST, TRUE);
	            if (!empty($postfields)) {
	                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
	                $this->postdata = $postfields;
	            }
	            break;
	        case 'DELETE':
	            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
	            if (!empty($postfields)) {
	                $url = "{$url}?{$postfields}";
	            }
	    }
	
	    if ( isset($this->access_token) && $this->access_token )
	        $headers[] = "Authorization: OAuth2 ".$this->access_token;
	
	    if ( !empty($this->remote_ip) ) {
	        if ( defined('SAE_ACCESSKEY') ) {
	            $headers[] = "SaeRemoteIP: " . $this->remote_ip;
	        } else {
	            $headers[] = "API-RemoteIP: " . $this->remote_ip;
	        }
	    } else {
	        if ( !defined('SAE_ACCESSKEY') ) {
	            $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
	        }
	    }
	    curl_setopt($ci, CURLOPT_URL, $url );
	    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
	    curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
	
	    $response = curl_exec($ci);
	    $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
	    $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
	    $this->url = $url;
	
	    if ($this->debug) {
	        echo "=====post data======\r\n";
	        var_dump($postfields);
	
	        echo "=====headers======\r\n";
	        print_r($headers);
	
	        echo '=====request info====='."\r\n";
	        print_r( curl_getinfo($ci) );
	
	        echo '=====response====='."\r\n";
	        print_r( $response );
	    }
	    curl_close ($ci);
	    return $response;
	}
	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	public function get($url, $parameters = array()) {
	    $response = $this->oAuthRequest($url, 'GET', $parameters);
	    if ($this->format === 'json' && $this->decode_json) {
	        return json_decode($response, true);
	    }
	    return $response;
	}
	/**
	 * @ignore
	 */
	public function id_format(&$id) {
	    if ( is_float($id) ) {
	        $id = number_format($id, 0, '', '');
	    } elseif ( is_string($id) ) {
	        $id = trim($id);
	    }
	}
    /**
     * 解析会员类
     *
     * @param    int       $type
     * @return   string
     */
    private function getUserClass($utype)
    {
    	$class = 'Shop_Models_API_' . implode('', array_map('ucfirst', explode('-', $utype)));
    	return new $class;
    }

    /**
     * 取得用户认证信息
     *
     * @param    void
     * @return   array
     */
    public function getAuth()
    {
     	if (!$this -> _certificationNamespace -> userCertification) {
			return false;
		}
		return $this -> _certificationNamespace -> userCertification;
    }

    /**
     * 更新用户认证信息
     *
     * @param    void
     * @return   array
     */
    public function updateAuth()
    {
     	if (!$this -> _certificationNamespace -> userCertification) {
			return false;
		}
		$this -> _certificationNamespace -> userCertification = $this -> getUser();
    }

    /**
     * 取得用户当前信息
     *
     * @param    void
     * @return   array
     */
    public function getUser()
    {
    	$auth = $this -> getAuth();
     	$userObject = $this -> getUserClass($auth['utype']);
        $userInfo = $userObject -> getUser();
        if(isset($auth['noencrypt']) && isset($auth['ltype']) && $auth['ltype']=='phone'){
        	$userInfo['noencrypt']=1;
        	$userInfo['ltype']='phone';
        }
        $userInfo['utype'] = $auth['utype'];
        return $userInfo;
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
        setcookie('auto_login',$strinfo,time() + 86400*7,'/');
        setcookie('userInfo','', 0, '/');
    }

    /**
     * 用户认证
     *
     * @param    string    $username
     * @param    string    $password
     * @param    int       $type
     * @return   mixed
     */
    public function certification($username, $password, $extra)
    {
        $username = trim($username);
        $password = trim($password);
     	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StripTags());
        $username = $filterChain -> filter($username);
        $password = $filterChain -> filter($password);
        if (empty($username)) {
        	return self::NO_USERNAME;
        }
        $userObject = $this -> getUserClass($extra['utype']);

        if (method_exists($userObject, 'certification')) {
        	!$extra['noencrypt'] && $password = Custom_Model_Encryption::getInstance() -> encrypt($password);
        	$result = $userObject -> certification($username, $password, false);
            if ($result != null) {
            	if ($extra) {
            		foreach ($extra as $key => $value)
            	    {
            		    $result[$key] || $result[$key] = $value;
            	    }
            	}
            	
			    $this -> _certificationNamespace -> userCertification = $result;
			    return true;
		    } else {
			    return self::ERROR_CERTIFICATION;
		    }
        }
    }
    /**
     * 会员注册
     *
     * @param    array    $data
     * @return   array
     */
    public function register($data,$isOut=null)
    {
    	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());

        $data = Custom_Model_Filter :: filterArray($data, $filterChain);
		if ($data['user_name'] == '') {//用户名不能为空
			return 'noUserName';
		}
		if ($data['password'] != '' || $data['confirm_password'] != '') {
		    if ($data['password'] != $data['confirm_password']) {//2次密码输入要一致
		        return 'noSamePassword';
			}else{
				$data['password'] = Custom_Model_Encryption::getInstance() -> encrypt($data['password']);
			}
		} else {
			return 'noPassword';
		}
		if ($this -> check($data['user_name'])) {
			return 'userNameExists';
		}else{
			if(Custom_Model_Check::isEmail($data['user_name'])){
				$data['email'] = $data['user_name'];
			}else if(Custom_Model_Check::isMobile($data['user_name'])){
				$data['mobile'] = $data['user_name'];
			}
		}
        if (isset($data['nick_name'])) {
            $data['nick_name'] = trim($data['nick_name']);
            $data['nick_name'] = $data['nick_name'] != '' ? $data['nick_name'] : $data['user_name'];
        } else {
            $data['nick_name'] = $data['user_name'];
        }
		if($isOut){ //当是共享登录的时候,数据库里加一个标识
			$data['is_share'] = 1;
		} else {
            $authImage = new Custom_Model_AuthImage('shopRegister');
            if (!$authImage -> checkCode($data['verifyCode'])) {
                return 'ERROR_VERIFY_CODE';
            }
            unset($_SESSION['shopRegister']['code']);
		}
		$data['utype'] = ($data['utype']) ? $data['utype'] : 'member';
		$userObject = $this -> getUserClass($data['utype']);
        return $userObject -> register($data);
    }
    /**
     * Js登录显示
     *
     * @param    string    $loginUrl
     * @param    string    $logoutUrl
     * @return   string
     */
    public function jsAuthState($loginUrl = null, $regUrl =null, $logoutUrl = null )
    {
    	$user = $this -> getAuth();
    	if ( !$hello ) {
        	$hour = 0 + date('H', time());

        	if ( $hour >= 6 && $hour < 12 )         $hello = '<li class="nav-hy">上午好,欢迎来到国人海淘网 ! </li>';
        	else if ( $hour >= 12 && $hour < 18 )   $hello = '<li class="nav-hy">下午好,欢迎来到国人海淘网 ! </li>';
        	else                                    $hello = '<li class="nav-hy">晚上好,欢迎来到国人海淘网 !</li>';

        	if ( $hour >= 6 && $hour < 12 )         $hello = '<li class="nav-hy">上午好,欢迎来到国人海淘网 ! </li>';
        	else if ( $hour >= 12 && $hour < 18 )   $hello = '<li class="nav-hy">下午好,欢迎来到国人海淘网 ! </li>';
        	else                                    $hello = '<li class="nav-hy">晚上好,欢迎来到国人海淘网 ! </li>';
        }
    	if ($user) {
    		if ($logoutUrl) {
    			$username = ($user['nick_name']) ? $user['nick_name'] : $user['user_name'];
				$result .= "<li>{$hello}</li><li class='navbar-nav-user'><a href='/member'>{$username}</li>";
				$result .= "<li class='navbar-nav-user'><a href='/member'>我的帐户</a></li>";
				$result .= "<li class='navbar-nav-user'><a href='/logout.html'>退出</a></li>";
    		} else {
    			$result .= "<li><a href='/member/order'>我的订单</a></li>";
    		}
		} else {
			if ($loginUrl) {
                $result .= "{$hello}<li><a href='" . $loginUrl ."'  class='blue' style='padding:0 4px;' id='glob-user' >[请登录]</a></li><li><a href='" . $regUrl ."' class='orange' style='padding:0 4px;'>[免费注册]</a></li>";
			}
		}
		return $result;
    }

    /**
     * 头部是否登陆 登录显示
     *
     * @param    string    $loginUrl
     * @param    string    $logoutUrl
     * @return   string
     */
    public function checkAuthState($loginUrl = null, $regUrl =null, $logoutUrl = null )
    {
    	$user = $this -> getAuth();
    	$hello='欢迎来到国药集团1健康商城！';
    	if ($user) {
    		$username = ($user['nick_name']) ? $user['nick_name'] : $user['user_name'];
    		$result .= $username." 欢迎来到国药集团1健康商城购物! ";
		} else {
			if ($loginUrl) {
                $result .= "{$hello} <a href='" . $loginUrl ."' >[请登录]</a><a href='" . $regUrl ."' class='a-register'>[免费注册]</a>";
			}
		}
		return $result;
    }

    /**
     * 检测用户信息
     *
     * @param    array    $user_name
     * @param	  bool		$isCheckUsername 是否检测用户名
     * @return   array
     */
    public function check($user_name,$isCheckUsername = false)
    {
    	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter :: filterArray($user_name, $filterChain);
        //$user_name = $data['user_name'];
        $user = null;
        if ($user_name) {
        	$function = $isCheckUsername ? 'checkUser2' : 'checkUser';
        	$user = $this -> $function($user_name);
        } 
        if ($user) {
        	return true;
        } else {
        	return false;
        }
    }
    
    private function checkUser($user)
    {
    	return $this -> _db -> checkUser($user);
    }
    private function checkUser2($user)
    {
    	$auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
    	return $this -> _db -> checkUser2($user,$auth['user_id']);
    }
    /**
     * 根据用户名获取用户基表信息
     *
     * @param    string    $username
     * @return   array
     */
    public function getUserByName($username)
    {
    	return $this -> _db -> getUser(array('A.user_name' => $username));
    }

    /**
     * 根据用户ID获取用户基表信息
     *
     * @param    string    $username
     * @return   array
     */
    public function getUserById($userId)
    {
    	return array_shift($this -> _db -> getUser(array('A.user_id' => $userId)));
    }

   /**
     * 外部注册会员
     *
     * @param    string    $userInfo
     * @param    string    $utype
     * @return void
     */
    public function outerRegister($userInfo)
    {
        if ($userInfo) {
            $data['user_name'] = $userInfo['user_name'];
            $data['rank_id'] = 1;
            $data['password'] = md5($userInfo['password']);
            $userInfo['real_name'] &&  $data['real_name'] = $userInfo['real_name'];
            $userInfo['email'] &&  $data['email'] = $userInfo['email'];
            $userInfo['msn'] &&  $data['msn'] = $userInfo['msn'];
            $userInfo['qq'] &&  $data['qq'] = $userInfo['qq'];
            $data['utype'] = 'member';
            $memberAPI = $this -> getUserClass('member');
            $exists = @array_shift($this -> getUserByName($data['user_name']));

            if ($userInfo['authType'] == '139') {
                if ($exists) {
                    $this -> certification($userInfo['user_name'], $exists['password'], array('utype' => 'member' , 'noencrypt' => true));
                    $result = 'certificationSucess';
                } else {
                    $data['nick_name'] = $userInfo['nick_name'];
                    $data['is_share'] = 1;
                    $result = $memberAPI -> register($data);
                }
            }else if ($userInfo['authType'] == 'alipay') {
                 $parentId = 9769;
                if ($exists) {
                    if ($exists['parent_id'] == $parentId) {
                        $this -> certification($userInfo['user_name'], $exists['password'], array('utype' => 'member', 'noencrypt' => true));
                        $result = 'certificationSucess';
                    } else {
                        return false;
                    }
                } else {
                    $data['email'] = $userInfo['email'];
                    $data['nick_name'] = $userInfo['nick_name'];
                    $data['real_name'] = $userInfo['real_name'];
                    $data['sex'] = $userInfo['sex'];
                    $data['home_phone'] = $userInfo['home_phone'];
                    $data['parent_id'] = $parentId;
                    $data['is_share'] = 1;
                    $result = $memberAPI -> register($data);
                }
            }

            if ($result == 'addUserSucess') {
                $this -> certification($userInfo['user_name'], $userInfo['password'], array('utype' => 'member'));
                return 'addUserSucess';
            } elseif ($result == 'certificationSucess') {
                return 'certificationSucess';
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * 后台快捷登录
     *
     * @param    string    $code
     * @param    string    $ltype    登录类型
     * @return void
     */
    public function mixLogin($code, $ltype)
    {
    	if ($code) {
    		$userInfo = Custom_Model_Encryption::getInstance() -> decrypt($code, 'UserAuth');
            if ($userInfo && $userInfo['user_name'] . $userInfo['password'] && $this -> certification($userInfo['user_name'], $userInfo['password'], array('utype' => 'member', 'noencrypt' => true, 'ltype' => $ltype))) {
            	return true;
            } else {
            	return false;
            }
		}
    }
    /**
     * 注册成功发邮件
     *
     * @return void
     */
	public function sendRegisterEmail($data){
        	$templateValue['user_name'] = $data['user_name'];
        	$templateValue['shop_name'] = Zend_Registry::get('config') -> name;
        	$templateValue['send_date'] = date('Y-m-d');
		    $template = new Shop_Models_API_EmailTemplate();
		    $template = $template -> getTemplateByName('register_email',$templateValue);
		    $mail = new Custom_Model_Mail();
		    if ($mail -> send($data['user_name'], $template['title'], $template['value'])) {
			    return 'sendError';
		    } else {
		    	return 'sendPasswordSucess';
		    }
	}
	
    /**
     * 找回密码
     *
     * @return void
     */
	public function sendPassword($email)
	{
		$userObject = $this -> getUserClass('member');
        $userInfo = $userObject -> getMemberByEmail($email);

        if ($userInfo) {
        	$userInfo['utype'] = 'member';
        	$templateValue['user_name'] = $userInfo['user_name'];
        	$templateValue['reset_email'] = $this->getPasswordCode($userInfo);//'http://' . array_shift(explode(',', Zend_Registry::get('config') -> domain)) . '/auth/get-password/code/' . Custom_Model_Encryption::getInstance() -> encrypt($userInfo, 'UserAuth');
        	$templateValue['shop_name'] = Zend_Registry::get('config') -> name;
        	$templateValue['send_date'] = date('Y-m-d H:i');
		    $template = new Shop_Models_API_EmailTemplate();
		    $templates = $template -> getTemplateByName('send_password', $templateValue);
		    $mail = new Custom_Model_Mail();
		    
		    if ($mail -> send($email, $templates['title'], $templates['value'])) {
			    return 'sendError';
		    } else {
		    	return 'sendPasswordSucess';
		    }
        } else {
        	return 'noUser';
        }
	}
	
	public function getPasswordCode($userInfo)
	{
	    return 'http://' . array_shift(explode(',', Zend_Registry::get('config') -> domain)) . '/auth/get-password/code/' . Custom_Model_Encryption::getInstance() -> encrypt($userInfo, 'UserAuth');
	}
	
	/**
     * 找回密码认证并修改密码
     *
     * @return void
     */
	public function setPassword($code)
	{
		$userInfo = Custom_Model_Encryption::getInstance() -> decrypt($code, 'UserAuth');
        if ($userInfo && $this -> certification($userInfo['user_name'], $userInfo['password'], array('utype' => 'member', 'noencrypt' => true, 'setPwd' => true))) {
            return true;
        } else {
        	return false;
        }
	}
	
	public function sendSMS($mobile)
	{
	    $code = $this -> createSmsCode(6);
	    $_SESSION['mobile_code']['value'] = $code;
	    $_SESSION['mobile_code']['time'] = time();
	    $smsApi = new Custom_Model_Sms();
	    $msg = '您正在找回密码，验证码为'.$code.'，请直接在页面中输入完成验证，切勿告知他人。如有问题致电客服 400-603-3883';
	    //$msg = '国药集团1健康商城手机验证码:'.$code.',请尽快输入验证,否则失效,请勿将验证码告知他人,谢谢!';
	    $tmp = $smsApi -> send($mobile, $msg);
	}
	
	private function createSmsCode($len)
	{
	    $string = "1234567890";
	    $str = '';
	    for($i=0; $i <$len; $i++){
	        $str = $str .$s = substr($string, rand(0,strlen($string)-1), 1);
	    }
	    return $str;
	}
	
	public function updateMember($where,$set)
	{
	    return $this -> _db -> updateMember($where,$set);
	}
 }