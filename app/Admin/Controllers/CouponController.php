<?php

class Admin_CouponController extends Zend_Controller_Action 
{
    /**
     * 礼券类型
     * @var array
     */
    private $_type = array(0=>'常规卡', 1=>'非常规卡', 3=>'商品抵扣卡', 4=>'订单金额折扣卡');
    
    /**
     * 是否可重复使用
     * @var array
     */
    private $_repeat = array(0=>'否', 1=>'是');
    
    /**
     * 状态
     * @var array
     */
    private $_status = array(0=>'可使用', 1=>'不可用');
    
    /**
     * 未填写礼券生成数量
     */
	const NO_NUMBER = '请填写生成数量!';
	
	/**
     * 未填写礼券价格
     */
	const NO_PRICE = '请填写礼券价格!';
	
	/**
     * 生成礼券成功
     */
	const ADD_COUPON_SUCESS = '生成礼券成功!';
    
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _coupon = new Admin_Models_API_Coupon();
		$this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
	}
	
	/**
     * 礼券发放记录
     *
     * @return void
     */
    public function logAction()
    {
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$search = $this -> _request -> getParams();
        $logMessages = $this -> _coupon -> getAllLog($page, null, $search );
        if ($logMessages['content']) {
            $cardid_arr = array();
        	foreach ($logMessages['content'] as $num => $logMessage)
            {
        	    if ($logMessages['content'][$num]['card_type'] == 3) {
        	        if ($logMessages['content'][$num]['card_price'] <= 0) {
        	            $logMessages['content'][$num]['card_price'] = '全额抵扣';
        	        }
        	    }
        	    else if ($logMessages['content'][$num]['card_type'] == 4) {
        	        $logMessages['content'][$num]['card_price'] = ($logMessages['content'][$num]['card_price'] / 10).'折';
        	    }
        	    $logMessages['content'][$num]['add_time'] = ($logMessage['add_time'] > 0) ? date('Y-m-d', $logMessage['add_time']) : '';
        	    $logMessages['content'][$num]['card_type'] = $this -> _type[$logMessage['card_type']];
        	    $logMessages['content'][$num]['is_repeat'] = $this -> _repeat[$logMessage['is_repeat']];
        	    $logMessages['content'][$num]['status'] = $this -> _coupon -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('log-status'), $logMessage['log_id'], $logMessage['status']);
        	    
        	    $cardid_arr[] = $logMessages['content'][$num]['log_id'];
            }
            
            $history_data = $this -> _coupon -> getHistory(" and t1.log_id in (".implode(',', $cardid_arr).")");
            if ($history_data['total'] > 0) {
                foreach ($history_data['content'] as $num => $history) {
                    $history_count[$history['log_id']]++;
                }
            }
        }
        $this -> view -> logList = $logMessages['content'];
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> history_count = $history_count;
        $pageNav = new Custom_Model_PageNav($logMessages['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 添加礼券
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
            
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$data = $this -> _request -> getPost();
        	$data['card_type_title'] = $this -> _type[$data['card_type']];
        	$data['card_repeat_title'] = $this -> _repeat[$data['is_repeat']];
            $result = $this -> _coupon -> addLog($data);
            switch ($result) {
            	case 'noNumber':
        		    Custom_Model_Message::showMessage(self::NO_NUMBER);
        		    break;
        		case 'noPrice':
        		    Custom_Model_Message::showMessage(self::NO_PRICE);
        		    break;
        		case 'addCouponSucess':
        		    Custom_Model_Message::showMessage(self::ADD_COUPON_SUCESS, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!');
                    break;
                case 'isAffNoPid':
                    Custom_Model_Message::showMessage('错误：你选择了按券绑定，但是绑定ID非法！');
                    break;
                default:
                    break;
        	}
        } else {
        	$this -> view -> cardType = $this -> _type;
        	$this -> view -> isRepeat = $this -> _repeat;
        }
    }
    
    /**
     * 取得已生成礼券
     *
     * @return void
     */
    public function getFileAction()
    {
		$id = (int)$this -> _request -> getParam('id', 0);
    	Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender(); 	
    	$this -> _coupon -> getCouponFile($id);
    	exit;
    }
    
    /**
     * 礼券使用记录
     *
     * @return void
     */
    public function historyAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
        $historys = $this -> _coupon -> getHistory($this -> _request -> getParams(), $page);
        
        foreach ($historys['content'] as $num => $history)
        {
        	$historys['content'][$num]['add_time'] = date('Y-m-d H:i:s', $history['add_time']);
        	$historys['content'][$num]['card_type'] = $this -> _type[$history['card_type']];
        	$historys['content'][$num]['is_repeat'] = $this -> _repeat[$history['is_repeat']];
        	$historys['content'][$num]['status'] = $this -> _status[$history['status']];
        }
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> historyList = $historys['content'];
        
        $pageNav = new Custom_Model_PageNav($historys['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 更改礼券使用记录状态
     *
     * @return void
     */
    public function logStatusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _coupon -> changeLogStatus($id, $status);
        } else {
            Custom_Model_Message::showMessage('error!');
        }
        
        echo $this -> _coupon -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('log-status'), $id, $status);
    }
     /**
     * 礼券开券详情
     *
     * @return void
     */   
    public function viewLogAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        
        $data = $this -> _coupon -> getLogById($id);
        if ( $data['card_type'] == 3 ) {
            $goods_info = unserialize($data['goods_info']);
            $goods_sn = array();
            $goods_api = new Admin_Models_API_Goods();
            foreach ($goods_info as $key => $value) {
                $goods_sn[] = "'".$key."'";
            }
            $goods_data = $goods_api -> get('a.goods_sn in ('.implode(',',$goods_sn).')', 'a.goods_sn,a.goods_name');
            
            if ( $goods_data ) {
                for ($i = 0; $i < count($goods_data); $i++) {
                    $goods_name[$goods_data[$i]['goods_sn']] = $goods_data[$i]['goods_name'];
                }
            }
            
            $this -> view -> goods_info = $goods_info;
            $this -> view -> goods_name = $goods_name;
        }
        else if ( ($data['card_type'] == 0) || ($data['card_type'] == 1) || ($data['card_type'] == 4) ) {
            $goods_info = unserialize($data['goods_info']);
            if ( $goods_info['allGoods'] ) {
                unset($goodsDiscount);
                parse_str($goods_info['allGoods']);
                foreach ($goodsDiscount as $key => $value) {
                    $goods_id[] = "'".$key."'";
                }
                $goods_api = new Admin_Models_API_Goods();
                $goods_data = $goods_api -> get('a.goods_id in ('.implode(',',$goods_id).')', 'a.goods_id,a.goods_name');
                
                if ( $goods_data ) {
                    for ($i = 0; $i < count($goods_data); $i++) {
                        $goods_name[$goods_data[$i]['goods_id']] = $goods_data[$i]['goods_name'];
                    }
                }
                
                $this -> view -> goods_info = $goodsDiscount;
                $this -> view -> goods_name = $goods_name;
            }
        }

        $this -> view -> data = $data;
    }

}