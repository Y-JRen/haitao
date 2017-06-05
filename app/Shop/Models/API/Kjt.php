<?php
class Shop_Models_Api_Kjt
{
    protected $_appId = "";
    protected $_appSecret = "";
    protected $_version = "1.0";
    protected $_apiUrl = "http://www.kuajingtong.com/api.php";//获得跨境通用户信息接口
    
    
    
    public function __construct()
    {
        $this -> _authApi = new Shop_Models_API_Auth();
        $this -> _baseUrl = 'http://www.cnsc.com.cn/';
    }
    /**
     * 单点登入接收
     * @param array $data 请求的所有参数
     */
    public function login($data)
    {
        if($this->checkSign($data)){
            $status = $data['login_status'];
            if($status == 1){//已登入
                $this -> _regAndLogin($data['kjt_user_id']);
            }
        }
        $url = $data['target_url'] ? $data['target_url'] : $this -> _baseUrl;
        header("Location:".$url);
    }
    /**
     * 单点登入回调
     * @param array $data 请求的所有参数
     */
    public function ssoReturn($data)
    {
        if($this -> checkSign($data)){
            //登入+跳转
            if($data['login_status'] == 1){
                $this -> _regAndLogin($data['kjt_user_id']);
            }
            $client_state = $data['client_state'] ? unserialize($data['client_state']) : array();
            $url = urldecode($client_state['back_act']);
        }
        $url = $url ? $url : $this -> _baseUrl;
        header("Location:{$url}");
    }
    /**
     * 跨境通联合登入
     * @param string $url 跳转地址
     */
    public function unionLogin($url)
    {
        $array = array(
                    'method'=>'sso.checkLogin',
                    'back_act'=>urlencode($url),
                );
        $tmp = serialize($array);
        $this -> ssoCheckLogin($tmp);
        exit;
    }
    /**
     * 单点登入检查接口
     * @param var $data  回传的参数 
     */
    protected function ssoCheckLogin($data)
    {
        $tmp = array(
                'method'=>'sso.checkLogin',
                'back_url'=> 'http://www.cnsc.com.cn/auth/sso-return/',
                'client_state'=>$data,
                'need_login'=>1,
        );
        $params = $this -> makeParams($tmp);
        header("Laction:{$this->_apiUrl}?".http_build_query($params));
    }
    
    /**
     * 单点登入
     * @param var $data  回传的参数  
     */
    protected  function ssoLogin($data)
    {
        $tmp = array(
                    'method'=>'sso.login',
                    'back_url'=> 'http://www.cnsc.com.cn/auth/sso-return/',
                    'client_state'=>$data,
                );
        $params = $this -> makeParams($tmp);
        header("Laction:{$this->_apiUrl}?".http_build_query($params));
    }
    
    
    /**
     * 获得跨境通用户信息
     * @param int $user_id 跨境通用户id
     */
    protected function getMemberInfo($user_id)
    {
        $tmp = array(
                    'method'=>'user.getInfo',
                    'kjt_user_id'=>$user_id,
                );
        $params = $this -> makeParams($tmp);
        $result = $this -> _authApi -> http($this->_apiUrl, 'GET',$params);
        $result = json_decode($output,JSON_FORCE_OBJECT);
        if($result['id']){
            return $result;
        }else{
            return false;
        }
    }
    /**
     * 注册+登入
     * @param int $user_id 跨境通用户id
     */
    protected function _regAndLogin($user_id)
    {
        $flag = true;
        //海淘的帐号 密码
        $user_name = "kjt*{$user_id}";
        $password = $user_name.'hehe2B';
        
        //获得跨境通用户信息
        $memberInfo = $this -> getMemberInfo($user_id);
        if($memberInfo && $user = $this -> _authApi -> getUserByName($user_name)){
            //更新+登入
            $where = " member_id = {$user['member_id']} ";
            $this -> _authApi -> updateMember($where, array('parent_param'=> serialize($memberInfo)));
        }elseif($memberInfo){
            //注册+登入
            $user = array(
                    'user_name'			=> $user_name,
                    'password'			=> $password,
                    'confirm_password'	=> $password,
                    'nick_name'			=> $memberInfo['username'],
                    'parent_id'			=> '',
                    'parent_user_name'	=> 'kjt',
                    'parent_param'		=> serialize($memberInfo),
                    'share_id'			=> '',
                    'email'             => $memberInfo['email'],
            );
            $tp = $this -> _authApi -> register($user,true);
            if($tp != 'addUserSucess'){
                $flag = false;
            }
        }
        if($flag){
            $tmp = $this -> _authApi -> certification($user_name, $password, array('utype' => 'member'));
            if($tmp === true){
                return true;
            }
        }
        return false;
    }
    
    
    
    /**
     * 生成参数
     * @param array $data
     * @return array
     */
    protected function makeParams($data)
    {
        $array = array(
                    'version'      => $this -> _version,
                    'appId'        => $this -> _appId,
                    'appSecret'    => $this -> _appSecret,
                    'timestamp'    => date('YmdHis'),
                    'nonce'        => rand(1, 999),
                );
        $tmp = array_merge($array,$data);
        $tmp['sign'] = $this -> getSign($tmp);
        $tmp = array_map('urlencode', $tmp);
        return $tmp;
    }
    
    
    /**
     * 加密
     * @param array $data    需要加密的数组
     * @param string $key  由接口提供方分配给接口调用方的身份验证信息
     * @return string    加密字符串
     */
    protected function getSign($data,$key=null)
    {
        $key = $key ? $key : $this -> _appSecret;
        $str = $this -> buildQuery($data);
        $str .= "&{$key}";
        $sign = md5($str);
        return $sign;
    }
    /**
     * 拼接参数字符串
     * @param array $data
     */
    protected function buildQuery($data)
    {
        if(is_array($data)){
            ksort($data);
            $str = http_build_query($data);
            return $str;
        }
        return false;
    }
    /**
     * 验签
     * @param array $data
     */
    protected function checkSign($data)
    {
        $sign = $data['sign'];
        unset($data['sign']);
        
        $sign_new = strtoupper($this -> getSign($data));
        if(strtoupper($sign) == $sign_new){
            return true;
        }else{
            return false;
        }
    }
}