<?php

class Admin_GiftCardController extends Zend_Controller_Action 
{
    /**
     * 礼品卡类型
     * @var array
     */
    private $_type = array(1 => 'B2C出售', 2 => '赠送', 3 => '呼叫中心出售');
    
    /**
     * 未填写礼品卡生成数量
     */
	const NO_NUMBER = '请填写生成数量!';
	
	/**
     * 未填写礼品卡价格
     */
	const NO_PRICE = '请填写礼品卡价格!';
	
	/**
     * 生成礼品卡成功
     */
	const ADD_GIFTCARD_SUCESS = '生成礼品卡成功!';
    
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _giftCard = new Admin_Models_API_GiftCard();
		$this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
	}
	
	/**
     * 礼品卡发放记录
     *
     * @return void
     */
    public function logAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
        $logMessages = $this -> _giftCard -> getAllLog($page);
        
        if ($logMessages['content']) {
        	foreach ($logMessages['content'] as $num => $logMessage) {
        	    $logMessages['content'][$num]['add_time'] = ($logMessage['add_time'] > 0) ? date('Y-m-d', $logMessage['add_time']) : '';
        	    $logMessages['content'][$num]['type'] = $this -> _type[$logMessage['card_type']];
        	    $logMessages['content'][$num]['status'] = $this -> _giftCard -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('log-status'), $logMessage['log_id'], $logMessage['status']);
        	    $logIDArray[] = $logMessage['log_id'];
            }
            $this -> view -> sum = $this -> _giftCard -> getUsedSum($logIDArray);
        }
        
        $this -> view -> logList = $logMessages['content'];
        $pageNav = new Custom_Model_PageNav($logMessages['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 添加礼品卡
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$data = $this -> _request -> getPost();
        	$data['card_type_title'] = $this -> _type[$data['card_type']];
            $result = $this -> _giftCard -> addLog($data);
            switch ($result) {
            	case 'noNumber':
        		    Custom_Model_Message::showMessage(self::NO_NUMBER);
        		    break;
        		case 'noPrice':
        		    Custom_Model_Message::showMessage(self::NO_PRICE);
        		    break;
        		case 'addGiftCardSucess':
        		    Custom_Model_Message::showMessage(self::ADD_GIFTCARD_SUCESS, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!');
        	}
        } else {
        	$this -> view -> cardType = $this -> _type;
        }
    }
    
    /**
     * 取得已生成礼品卡
     *
     * @return void
     */
    public function getFileAction()
    {
		Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
		$id = (int)$this -> _request -> getParam('id', 0);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$this -> _giftCard -> getCardFile($id);
    	exit;
    }
    
    /**
     * 礼品卡列表
     *
     * @return void
     */
    public function listAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
        $cards = $this -> _giftCard -> getCardlist($this -> _request -> getParams(), $page);
        
        foreach ($cards['content'] as $num => $card)
        {
        	$cards['content'][$num]['add_time'] = date('Y-m-d H:i:s', $card['add_time']);
        	$cards['content'][$num]['using_time'] && $cards['content'][$num]['using_time'] = date('Y-m-d H:i:s', $card['using_time']);
        	$cards['content'][$num]['card_type'] = $this -> _type[$card['card_type']];
        }
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> cardList = $cards['content'];
        $pageNav = new Custom_Model_PageNav($cards['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 礼品卡使用历史记录
     *
     * @return void
     */
    public function useLogAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$params = $this -> _request -> getParams();
    	$params['use_type'] = 2;
        $cards = $this -> _giftCard -> getUseLog($params, $page);
        
        foreach ($cards['content'] as $num => $card) {
        	$cards['content'][$num]['add_time'] = date('Y-m-d H:i:s', $card['add_time']);
        	$cards['content'][$num]['using_time'] && $cards['content'][$num]['using_time'] = date('Y-m-d H:i:s', $card['using_time']);
        	$cards['content'][$num]['logistic_time'] && $cards['content'][$num]['logistic_time'] = date('Y-m-d H:i:s', $card['logistic_time']);
        	$cards['content'][$num]['card_type'] = $this -> _type[$card['card_type']];
        }
        $this -> view -> param = $params;
        $this -> view -> cardList = $cards['content'];
        $this -> view -> total = $cards['total'];
        $pageNav = new Custom_Model_PageNav($cards['total']['count'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 更改礼品卡有效状态
     *
     * @return void
     */
    public function logStatusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _giftCard -> changeLogStatus($id, $status);
        } else {
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _giftCard -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('log-status'), $id, $status);
    }
    
    /**
     * 验证礼品卡(ajax调用)
     *
     * @return void
     */
    public function checkAction()
    {
        $card_sn = $this -> _request -> getParam('card_sn', null);
        $batch_sn = $this -> _request -> getParam('batch_sn', null);
        
        $result = $this -> _giftCard -> checkCard($card_sn, $batch_sn);
        if (is_array($result)) {
            echo $result['card']['able_price'];
        }
        else {
            echo $result;
        }
        
        exit;
    }
    
    public function initGiftAction()
    {
        $db = Zend_Registry::get('db');
        
        $datas = $db -> fetchAll("select * from shop_gift_card where user_name like 'YP-%'");
        foreach ($datas as $data) {
            $logs = $db -> fetchAll("select price from shop_gift_card_use_log where card_sn = '{$data['card_sn']}'");
            $data['init_price'] = $data['card_real_price'];
            if ($logs) {
                foreach ($logs as $log) {
                    $data['init_price'] += $log['price'];
                    $data['consume'] += $log['price'];
                }
            }
            
            $result[] = $data;
        }
        
        foreach ($result as $data) {
            if ($data['using_time']) {
                $usingTime = date('Y-m-d H:i:s', $data['using_time']);
            }
            else {
                $usingTime = '';
            }
            echo $data['user_name'].','.$data['card_sn'].','.$data['card_price'].','.$data['init_price'].','.$data['card_real_price'].','.$data['consume'].','.$usingTime.'<br>';
        }
        
        exit;
    }
}