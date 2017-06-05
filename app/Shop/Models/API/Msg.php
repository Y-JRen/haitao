<?php

class Shop_Models_API_Msg
{
    /**
     * 
     * @var Shop_Models_DB_Msg
     */
	protected $_db = null;
	
    /**
     * 错误信息
     */
	protected $error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this  -> _db = new Shop_Models_DB_Msg();
		$this -> _auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
	}
	
	/**
     * 用户站点留言
     *
     * @param    array     $data
     * @return   bool
     */
    public function shopMsg($data){
        
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        if ($data['type'] == '5') {
        	$data['url'] = $_SERVER['HTTP_REFERER'];
        }
        if(is_null($data['content'])){
            return '请填写内容';
        }else{
        	$msg['content'] = $data['content'];
        }
        if ($data['url']){
	        $msg['content'] = $data['url']."\n".$msg['content'];
	        unset($data['url']);
        }
        if ($data['goods_name']){
	        $msg['content'] = $data['msg']."\n".$msg['content'];
	        unset($data['goods_name']);
        }
		if($this -> _auth){
		    $msg['user_id'] = $this -> _auth['user_id'];
		    $msg['user_name'] = $this -> _auth['user_name'];
		    $msg['real_name'] = $this -> _auth['real_name'];
		}else{
			$msg['user_name'] = '匿名用户';
		}
        $msg['type'] = $data['type'];
        $msg['contact'] = $data['contact'];
	    $msg['email'] = $data['email'] ? $data['email'] : $this -> _auth['email'];
        $msg['add_time'] = time();
        $msg['ip'] = $_SERVER['REMOTE_ADDR'];
        if(!$this -> _db -> shopMsg($msg)){
            return '添加失败';
        }
       return '提交成功！';
    }
	

	/**
     * 获取商品咨询信息
     *
     * @param    string    $where
     * @return   array
     */
	public function getCslCount($goods_id)
	{
		return  $this -> _db -> getCslCount($goods_id);
	}

    /**
     * 商品评论
     *
     * @param    array     $data
     * @return   bool
     */
    public function goodsMsg($data){
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        if(!$data['content']){
            return '请填写内容';
        }else{
        	$msg['content'] = $data['content'];
        }
        if(!$data['title']) {
        	$msg['title'] = cut_str($data['content'],30,0,'');
        }else{
        	$msg['title'] = $data['title'];
        }
    	$r = $this -> checkHistory($data);
    	
		if($r) {
			switch ($r){
				case 'notLogin':
				    return '请先登录';
				case 'notBuy':
				    return '您未购买过此商品不能发表评论';
				case 'hasComment':
				    return '您已对此商品发表过评论了';
			}
		}
        
        if ($data['score']) {
            $msg['cnt1'] = $data['score'];
        }
		else {
			$msg['cnt1'] = (int)$data['cnt1'];
		}
		
		$msg['cnt2'] = (int)$data['cnt2'];
		
		$msg['goods_id'] = $data['goods_id'];
		$msg['goods_name'] = $data['goods_name'];
        $msg['type'] = $data['type']?$data['type']:1 ;

		if ($data['user_name']) {
			$msg['user_id'] = 0;
		    $msg['user_name'] = $data['user_name'];
		}else{
			if($this -> _auth){
			    $msg['user_id'] = $this -> _auth['user_id'];
			    $msg['user_name'] = $this -> _auth['user_name'];
			    $msg['real_name'] = $this -> _auth['real_name'];
			    $msg['email'] = $this -> _auth['email'];
			    
			    
			}else{
				$msg['user_name'] = '匿名用户';
			}
		}
        $msg['add_time'] = time();
        $msg['ip'] = $_SERVER['REMOTE_ADDR'];

        if(!$this -> _db -> goodsMsg($msg)){
            return '添加失败';
        }
    }
	
	/**
     * 获取用户站点留言
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getShopMsg($where = null, $fields = '*', $page=1, $pageSize = 10,$photo=null)
	{
		return $this -> _db -> getShopMsg($where, $fields, $page, $pageSize,$photo);
	}
	
	/**
     * 检查环境
     *
     * @return   void
     */
    public function checkIp()
	{
		$ipArray = array( '127.0.0.1');
        if(in_array($_SERVER['REMOTE_ADDR'], $ipArray) || substr($_SERVER['REMOTE_ADDR'], 0, 7) != '192.168'){
                return true;//内部直接发表
        }
	}
        
        
        
	public function gettopmember(){
		return $this->_db->gettopmember();
	}
	
	/**
     * 获取商品统计数据
     *
     * @param    array       $data
     * @return   array
     */
	public function checkHistory($data)
	{
		if ($this -> checkIp()) return false;
		$data['user_id'] = (int)$this -> _auth['user_id'];
		if(!$data['user_id']) return 'notLogin';
		
		$number = $this -> _db -> checkBuy($data);
		if (!$number){
			return 'notBuy';
		}else{
			$where = "user_id='{$data['user_id']}' and goods_id='{$data['goods_id']}'";
			if($datas = $this -> _db -> getGoodsMsg($where)){
			    $commentNum = count($datas);
			    if($commentNum >= $number){
			    	return 'hasComment';
			    }
			}
		}
	}
	
	public function formatName($name)
	{
		 $name = str_replace('1jiankang.', '163.', $name);
		 if(strstr($name, '@')){
		 	 $user_name = explode('@', $name);
		 	 $name = substr($user_name[0], 0, 3).str_repeat("*", strlen($user_name[0]-2)).'@'.$user_name[1];
		 }
		 else if(strlen($name) == 11 && preg_match("/^(((1[3|5|8]{1}[0-9]{1}))[0-9]{8})$/", $name, $m)){
		 	 $name = substr($name, 0, 3).'****'.substr($name, -4);
		 }
		 else {
		 	 if (strlen($name) < 3 || !strstr($name, '*')) $name = substr($name, 0, 3)."******";
		 }
		 return $name;
	}
	


	/**
     * 获取热点商品评论
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getHotGoodsMsg($where = null, $fields = '*', $limit)
	{
		$datas = $this -> _db -> getHotGoodsMsg($where, $fields,$limit);
		return $datas;
	}
	/*
	 * 首页获取最新评论
	 */
	public function getCommByIndex($num){
	    $list = $this -> _db -> getCommByIndex($num);
	    return $list;
	}

	/**
     * 获取商品评论
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getGoodsMsg($where = null, $fields = '*', $page=null, $pageSize = null)
	{
		$memberApi = new Shop_Models_API_Member();
		$datas = $this -> _db -> getGoodsMsg($where, $fields, $page, $pageSize);
		foreach ($datas as $num => $data){
			$datas[$num]['user_name'] = $this -> formatName($datas[$num]['user_name']);
			$datas[$num]['add_time'] = date('Y-m-d H:i:s', $datas[$num]['add_time']);	
			if($data[$num]['user_id'])
			{
				$info  = $memberApi->getMemberByuid($data[$num]['user_id']);
				$data[$num]['photo'] = $info['photo']?$info['photo']:'';
			}	
				
		}
		return $datas;
	}
	
	/**
     * 获取商品统计
     *
     * @return   int    $goods_id
     * @return   array
     */
	public function getGoodsCnt($goods_id)
	{
		$r = $this -> _db -> getGoodsCnt($goods_id);
		for ($i = 1; $i <= 2; $i++){
			$cntKey = 'cnt'.$i;
				foreach($r[$cntKey] as $cnt){
					$point += $cnt['k'] * $cnt['v'];
					$total += $cnt['v'];
				}
                if(count($r[$cntKey])>1){
                    foreach($r[$cntKey] as $cnt){
                        $result[$cntKey]['detail'][$cnt['k']] = array('num' => $cnt['v'], 'scale' => round($cnt['v']*100/$total, 1));
                    }
                }

				$result[$cntKey]['total'] = (int)$total;
				!$total && $total = 1;
				$result[$cntKey]['point'] = round($point/$total, 1);
				if(strstr($result[$cntKey]['point'], '.')){
					$s = explode('.', $result[$cntKey]['point']);
					$result[$cntKey]['point'] = $s[0].'.'.($s[1] < 5 ? 0 : '5');
				}
				$star = 0;
				$starWidth = 80;
				$result[$cntKey]['pointshow'] = "<div class=\"showstarbg\" style=\"width:".$starWidth."px;\"><div class=\"showstar\" style=\"width:".round($starWidth * $result[$cntKey]['point']/5, 1)."px;\"><span>".$cntKey."</span></div></div>";
				for($j = 1; $j <= 5; $j++){
				    $result[$cntKey]['pointdetail'] .= "<li>";
				    $result[$cntKey]['pointdetail'] .= "<div class=\"showstarbg\" style=\"width:".$starWidth."px;\"><div class=\"showstar\" style=\"width:".round(16 * (6 - $j), 1)."px;\"><span>".$cntKey."</span></div></div>";
				    $detail = $result[$cntKey]['detail'][6 - $j];
				    $barWidth = 100;//定义宽度
				    $result[$cntKey]['pointdetail'] .= "<div class=\"showbarbg\" style=\"width:".$barWidth."px;\"><div class=\"showbar\" style=\"width:".round($barWidth * $detail['scale']/100, 1)."px;\"><span>".$cntKey."</span></div></div><div class=\"showstarnum\">".(int)$detail['num']."</div>";
				    $result[$cntKey]['pointdetail'] .= "</li>";
				}
			unset($point, $total);
		}
		return $result;
	}
	
	/**
     * 获取总数
     *
     * @return   int
     */
	public function getCount()
	{
		return $this -> _db -> total;
	}
	
	/**
     * 获取购买记录
     *
     * @return   int
     */
	public function getBuyLog($goods_id, $cnum = 0, $return = false)
	{
	    $log = $this -> _db -> getBuyLog($goods_id);
        if($log['buy_log'] != ''){
        	$logs = explode("|", $log['buy_log']);
            foreach($logs as $k => $v){
            	$r = explode("^", $v);
                $data[$k]['user_name'] = $this -> formatName($r[0]);
                $data[$k]['rank_name'] = $r[1];
                $data[$k]['add_time'] = $r[2];
            }
	        $log['buy_log'] = $data;
	        if (!$return && $g['onsale'] != 1) $this -> updateBuyLog($goods_id, $log, $cnum);
        } else {
        	if ($g['onsale'] == 1) return $log;
        	$cnum = count($this -> getGoodsMsg("goods_id='$goods_id'"));
	        $this -> updateBuyLog($goods_id, $log, intval($cnum));
	        if (!$return) return $this -> getBuyLog($goods_id, intval($cnum), true);
        }
        
        return $log;
	}
	
	/**
     * 更新购买记录
     *
     * @return   void
     */
	public function updateBuyLog($goods_id, $log, $cnum = 0)
	{
        //参照：评论数量，时间>最后更新时间，已有购买记录，更新条数
        
        $logNum = count($log['buy_log']);
        if ($logNum > 1){
        	$upnum = rand(0, 2);
        	$time = time();
        } else {
            $upnum = 10;
	        //初始化购买数量
	        $log['buy_total'] = $cnum * 9 + rand(52, 71);
	        //初始化时间
	        $time = time();
        }
        
        $rank = array('普通会员','银卡会员','普通会员','金卡会员','普通会员','钻石会员');
        
        if ($logNum > 1) $update_time = strtotime($log['buy_log'][0]['add_time']);
        $stime = rand(300, 600);
        if ($time - intval($update_time) < $stime) return false;
        $ltime = (int)$update_time > 0 ? $update_time : ($time - 86400*5);
        
        
        if ($update_time > 0 && $time - $update_time > 86400) {
        	$upnum = 10;
        	$ltime = $time - 86400*2;
        }
        
        if ($upnum < 1) return false;
        $users = $this -> _db -> getUserByRand($ltime, $time);
        if (count($users) < 30) return false;
        for ($i=0;$i<30;$i++) {
        	$user = $users[rand(rand(0, 3), count($users) - rand(0, 3))];
        	$tmp['user_name'] = $user['user_name'];
        	$tmp['user_name'] = $this -> formatName($tmp['user_name']);
        	$tmp['rank_name'] = $rank[rand(0, 5)];
        	$tmp['add_time'] = date('Y-m-d H:i:s', $user['last_login']);
        	if (!$newlog[$user['last_login']] && $tmp['user_name'] && $tmp['add_time'] && intval(date('H', $user['last_login'])) > 8 && intval(date('H', $user['last_login'])) < 21) {
	        	$newlog[$user['last_login']] = implode("^", $tmp);
	        	$t ++;
        	}
        	if ($t >= $upnum ) break;
        }
        $buy_total = (int)$log['buy_total'] + count($newlog);
        
        if (count($newlog) > 0) {
	        if ($upnum < 10){
	        	foreach ($log['buy_log'] as $k => $v){
	        		$newlog[strtotime($v['add_time'])] = implode("^", $v);
	        	}
	        }
	        krsort($newlog);
	        
	        $update_time = max(array_keys($newlog));
	        $newlog = implode("|", array_slice($newlog, 0, 10));
	        $this -> _db -> updateBuyLog($goods_id, $newlog, $buy_total, intval($update_time));
        }
	}
	
	/**
     * 插入购买记录
     *
     * @return   int
     */
	public function insertBuyLog($goods_id, $user_name, $rank_name, $add_time)
	{
	    $time = time();
	    $tmp = array();
	    $tmp['user_name'] = $user_name;
    	$tmp['user_name'] = $this -> formatName($tmp['user_name']);
    	$tmp['rank_name'] = $rank_name;
    	$tmp['add_time'] = date('Y-m-d H:i:s', $add_time);
    	$newlog[$add_time] = implode("^", $tmp);
    	
	    $log = $this -> _db -> getBuyLog($goods_id);
        if($log['buy_log'] != ''){
        	$logs = explode("|", $log['buy_log']);
        	foreach($logs as $k => $v){
        		$r = explode("^", $v);
        		$tk = strtotime($r[2]);
            	$newlog[$tk] = $v;
            }
            krsort($newlog);
        }
        
        $buy_total = (int)$log['buy_total'] + 1;
        
        if (count($newlog) > 0) $update_time = max(array_keys($newlog));
        $newlog = implode("|", array_slice($newlog, 0, 10));
	    $this -> _db -> updateBuyLog($goods_id, $newlog, $buy_total, intval($update_time));
	}
	/*获取评论等级数量*/
	public function getCommByLevel($where= null ){
	    return $total = $this -> _db -> getCommByLevel($where);
	     
	}
	
}