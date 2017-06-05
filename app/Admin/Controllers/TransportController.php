<?php
require_once 'Custom/Model/Code39.php';
/**
 * Admin_TransportController
 *
 * @author    Rice
 */
class Admin_TransportController extends Zend_Controller_Action
{
    /**
     * 
     * @var Admin_Models_API_Transport
     */
    private $_api = null;
    
    private $_auth = null;
    
    private $_logicArea = null;
    
    private $_lid;
	/**
	 * 
	 * @var Custom_Model_Stock_Base
	 */
	private $_stock = null;
    
	const ADD_SUCCESS = '添加成功!';
	const EDIT_SUCCESS = '关键字维护成功!';
	const CANCEL_SUCCESS = '申请取消成功!';
	const CHECK_SUCCESS = '审核成功!';
	const CONFIRM_SUCCESS = '确认成功!';
	const ASSIGN_SUCCESS = '派单成功!';
	const TRACK_SUCCESS = '维护状态成功!';
	const IMPORT_SUCCESS = '导入成功!';
	const FILL_SUCCESS = '填充成功!';
	const BACK_SUCCESS = '返回成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _logistic = new Admin_Models_DB_Logistic();
		$this -> _api = new Admin_Models_API_Transport();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		$this -> _lid = $this -> _auth['lid'];
		
		$this -> _logicArea = $this -> _lid;
		$this -> _stock = Custom_Model_Stock_Base::getInstance($this -> _lid);
		
		$this -> view -> logic_area = $this -> _logicArea;
		$this -> _actions = $this -> _stock -> getConfigTransportAction();
		$this -> view -> _actions = $this -> _stock -> getConfigTransportAction();
		$this -> _bill_type = $this -> _stock -> getConfigTransportType();
		$this -> view -> billType = $this -> _stock -> getConfigTransportType();
		$this -> _logisticStatus = $this -> _stock -> getConfigLogisticStatus();
		$this -> view -> logisticStatus = $this -> _stock -> getConfigLogisticStatus();
		$this -> view -> operates = $this -> _stock -> getConfigOperate();
		
		foreach ($this -> _logistic -> getLogisticList() as $key => $value) {
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	    $this -> _logistics[$value['logistic_code']] = $value;
    	}
    	$this -> view -> prints = $this -> _prints;
    	$this -> view -> logisticList = $logisticList;
	}
	
    /**
     * 运输单查询
     *
     * @return void
     */
    public function searchListAction()
    {
    	$action = $this -> _request -> getActionName();
		$search = $this -> _request -> getParams();
		$search['lid'] = $this -> _lid;
	    $page = (int)$this -> _request -> getParam('page', 1);
		if ($search['export'] == '1') {
			$this->_api->exportXlsTransportInfos($search, $action);
			exit();
		} else {
			$data = $this -> _api -> search($search, $action, $page, 50);
		}
	    $pageNav = new Custom_Model_PageNav($data['total'], 50, 'ajax_search');
	    
	    $this -> view -> datas = $data['datas'];
        $this -> view -> action = $action;
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> render('list');
    }
    /**
     * 运输单确认
     *
     * @return void
     */
    public function confirmListAction()
    {
    	$action = $this -> _request -> getActionName();
		$search = $this -> _request -> getParams();
		$search['lid'] = $this -> _lid;
		$search['bill_type'] = 1;
		$search['logistic_code'] = 'sf';
		$search['is_cod'] = '0';
	    $page = (int)$this -> _request -> getParam('page', 1);
        $data = $this -> _api -> search($search, $action, $page,50);
        
        //获得店铺名称
        if ($data['datas']) {
            for ($i = 0; $i < count($data['datas']); $i++) {
                if ( $data['datas'][$i]['shop_id'] > 0 ) {
                    $shopIDArray[] = $data['datas'][$i]['shop_id'];
                }
            }
            if ($shopIDArray) {
                $shopAPI = new Admin_Models_API_Shop();
                $shopData = $shopAPI -> get("shop_id in (".implode(',', array_unique($shopIDArray)).")");
                if ($shopData['list']) {
                    foreach ( $shopData['list'] as $shop ) {
                        $shopInfo[$shop['shop_id']] = $shop['shop_name'];
                    }
                }
                for ($i = 0; $i < count($data['datas']); $i++) {
                    $data['datas'][$i]['shop_name'] = $shopInfo[$data['datas'][$i]['shop_id']];
                }
            }
        }
        
	    $pageNav = new Custom_Model_PageNav($data['total'], 50, 'ajax_search');
	    $this -> view -> datas = $data['datas'];
        $this -> view -> action = $action;
        $this -> view -> param = $search;
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 物流派单
     *
     * @return void
     */
    public function assignListAction()
    {
    	$search = $this -> _request -> getParams();
    	$search['lid'] = $this -> _lid;
	    $page = (int)$this -> _request -> getParam('page', 1);
        $result = $this -> _api -> search($search, 'assign-list', $page);
	    $pageNav = new Custom_Model_PageNav($result['total'], null, 'ajax_search');
	    $orderAPI = new Admin_Models_API_Order();
	    foreach($result['datas'] as $data){
	    	$logisticList = '';
	    	$hasGoodsCard = $orderAPI -> getOrderGoodsCard($data['bill_no']);
	    	$r = Zend_Json::decode($data['logistic_list']);
            $sel = $data['is_cod'] ? $r['other']['default_cod'] : $r['other']['default'];
            if($r['list']){
                foreach ($r['list'] as $k => $v) {
                	$selected = $v['logistic_code'] == $sel ? "selected='true'" : '';
                	//$t = $v['logistic_name'].' - '.($v['cod'] ? 'OK' : 'NO').' - '.$v['search_mod'].' - '.$v['price'].' - '.$v['cod_price'];
                    $t = $v['logistic_name'];
                	if (!$hasGoodsCard || ($hasGoodsCard && in_array($v['logistic_code'], array('zjs', 'ems')))) {
                	    $logisticList .= "<option value='".Zend_Json::encode($v)."' $selected>$t</option>\n";
                	}
                }
            }
            $data['logisticList'] = $logisticList;
            $datas[] = $data;
            
            if ( $data['shop_id'] > 0 ) {
                $shopIDArray[] = $data['shop_id'];
            }
	    }
	    
	    //获得店铺名称
	    if ($shopIDArray) {
            $shopAPI = new Admin_Models_API_Shop();
            $shopData = $shopAPI -> get("shop_id in (".implode(',', array_unique($shopIDArray)).")");
            if ($shopData['list']) {
                foreach ( $shopData['list'] as $shop ) {
                    $shopInfo[$shop['shop_id']] = $shop['shop_name'];
                }
            }
            for ($i = 0; $i < count($datas); $i++) {
                $datas[$i]['shop_name'] = $shopInfo[$datas[$i]['shop_id']];
            }
        }
	    
	    $this -> view -> datas = $datas;
	    $member = new Admin_Models_API_Member();
        $this -> view -> province = $member -> getChildAreaById(1);
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 运输单跟踪
     *
     * @return void
     */
    public function trackListAction()
    {
    	$search = $this -> _request -> getParams();
    	$search['lid'] = $this -> _lid;
	    $page = (int)$this -> _request -> getParam('page', 1);
        $data = $this -> _api -> search($search, 'track-list', $page, 120);
	    $pageNav = new Custom_Model_PageNav($data['total'], 120, 'ajax_search');
	    $this -> view -> datas = $data['datas'];
	    $member = new Admin_Models_API_Member();
        $this -> view -> province = $member -> getChildAreaById(1);
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }



    /**
     * 运输单跟踪维护
     *
     * @return void
     */
    public function changeTrackListAction()
    {
    	$search = $this -> _request -> getParams();
    	$search['lid'] = $this -> _lid;
	    $page = (int)$this -> _request -> getParam('page', 1);
        $data = $this -> _api -> search($search, 'change-track-list', $page, 120);
	    $pageNav = new Custom_Model_PageNav($data['total'], 120, 'ajax_search');
	    
	    $this -> view -> datas = $data['datas'];
	    $member = new Admin_Models_API_Member();
        $this -> view -> province = $member -> getChildAreaById(1);
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }

    /**
     * 关键字维护
     *
     * @return void
     */
    public function keywordsAction()
    {
    	$search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
        $data = $this -> _api -> search($search, 'keywords', $page);
	    $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
	    
	    $this -> view -> datas = $data['datas'];
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 运输单申请
     *
     * @return void
     */
    public function addAction()
    {
    	if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> add($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
        	}
        }else{
        	$member = new Admin_Models_API_Member();
        	$this -> view -> province = $member -> getChildAreaById(1);
			$this -> view -> status = $status;
			$this -> render('add');
        }
    }
    
    /**
     * 匹配物流公司
     *
     * @return void
     */
    public function getLogisticAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $is_cod = (int)$this -> _request -> getParam('is_cod', null);
        if ($this -> _request -> isPost()) {
        	$post = $this -> _request -> getPost();
        	$r = $this -> _api -> getLogistic(array('area_id' => $post['area_id'],
                                       'address' => $post['address'],
                                       'amount' => $post['amount'], 
                                       'weight' => $post['weight'],
                                       'is_cod' => $is_cod));
            if ($r) {
            $sel = $is_cod ? $r['other']['default_cod'] : $r['other']['default'];
            $logisticList = '<input type="hidden" name="transport[logistic_list]" value=\''.Zend_Json::encode($r).'\'><select name="logistic" id="logistic">';
            foreach ($r['list'] as $k => $v) {
            	$selected = $v['logistic_code'] == $sel ? "selected='true'" : '';
            	//$t = $v['logistic_name'].' - '.$v['is_send'].' - '.$v['search_mod'].' - '.$v['price'].' - '.($is_cod ? $v['cod_price']+$v['fee_service'] : 0);
                $t = $v['logistic_name'];
            	$logisticList .= "<option value='".Zend_Json::encode($v)."' $selected>$t</option>\n";
            }
            $logisticList .= "</select>";
            }
        }
        echo $logisticList;
    }
    
    /**
     * 物流派单
     *
     * @return void
     */
    public function assignAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> assign($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::ASSIGN_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
	        	}
            } else {
                $this -> view -> action = 'assign';
                $datas = $this -> _api -> get("tid=$id");
                $data = $datas[0];
                $data['bill_no_array'] = $this -> _api -> getMergeSplitOrderSNArray($data['bill_no']);
                $r = Zend_Json::decode($data['logistic_list']);
                if ($r){
                $sel = $data['is_cod'] ? $r['other']['default_cod'] : $r['other']['default'];
                foreach ($r['list'] as $k => $v) {
                	$selected = $v['logistic_code'] == $sel ? "selected='true'" : '';
                	//$t = $v['logistic_name'].' - '.($v['cod'] ? 'OK' : 'NO').' - '.$v['search_mod'].' - '.$v['price'].' - '.($data['is_cod'] ? $v['cod_price']+$v['fee_service'] : 0);
                    $t = $v['logistic_name'];
                	$logisticList .= "<option value='".Zend_Json::encode($v)."' $selected>$t</option>\n";
                }
                }
                $this -> view -> logisticList = $logisticList;
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 物流重新派单
     *
     * @return void
     */
    public function reassignAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> reassign($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::ASSIGN_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
	        	}
            } else {
                $this -> view -> action = 'assign';
                $datas = $this -> _api -> get("tid=$id");
                $data = $datas[0];
                $r = Zend_Json::decode($data['logistic_list']);
                $sel = $data['is_cod'] ? $r['other']['default_cod'] : $r['other']['default'];
                foreach ($r['list'] as $k => $v) {
                	$t = $v['logistic_name'].' - '.($v['cod'] ? 'OK' : 'NO').' - '.$v['search_mod'].' - '.$v['price'].' - '.($data['is_cod'] ? $v['cod_price']+$v['fee_service'] : 0);
                	$logisticList .= "<option value='".Zend_Json::encode($v)."' $selected>$t</option>\n";
                }
                $this -> view -> logisticList = $logisticList;
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
	/**
     * 批量派单
     *
     * @return   void
     */
    public function assignsAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) > 0) {
    		foreach($post['ids'] as $k => $v){
    			$this -> _api -> assign($post['info'][$v], $v);
    		}
    	} else {
    		exit;
    	}
    }
    
    /**
     * 返回配货
     *
     * @return   void
     */
    public function prepareReturnAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) > 0) {
    		foreach($post['ids'] as $k => $v){
    		    $data = array_shift($this -> _api -> get("tid=$v"));
    		    if (!$data) continue;
    		    
    		    if ($data['lock_name'] != $this -> _auth['admin_name']) {
    		        Custom_Model_Message::showMessage('请先锁定订单!', 'event', 1250, 'Gurl()');
    		    }
    		    if ($data['is_assign'] != 0 || $data['is_cancel'] >= 2) {
    		        Custom_Model_Message::showMessage('订单状态不正确，不能返回配货!', 'event', 1250, 'Gurl()');
    		    }
    		    if (!$this -> _api -> prepareReturn($post['info'][$v]['bill_no'])) {
    		        Custom_Model_Message::showMessage('返回配货失败!', 'event', 1250, 'Gurl()');
    		    }
    		}
    	} else {
    		exit;
    	}
    }
    
    /**
     * 运输单确认
     *
     * @return void
     */
    public function confirmAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> confirm($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CONFIRM_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
	        	}
            } else {
                $this -> view -> action = 'confirm';
                $datas = $this -> _api -> get("tid=$id");
                $data = $datas[0];
                $data['bill_no_str'] = str_replace(',', '<br>', $data['bill_no']);
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
	/**
     * 批量确认
     *
     * @return   void
     */
    public function confirmsAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $bill_type = (int)$this -> _request -> getParam('bill_type', null);
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) > 0) {
    		foreach($post['ids'] as $k => $v){
    			$this -> _api -> confirm($post['info'][$v], $v);
    		}
			Custom_Model_Message::showMessage(self::CONFIRM_SUCCESS, 'event', 1250, "Gurl('refresh')");
    	} else {
    		exit;
    	}
    }
    
    /**
     * 批量填充运单号
     *
     * @return   void
     */
    public function fillNoAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) > 0 && $post['logistic_no']) {
    		foreach($post['ids'] as $k => $v){
    		    $this -> _api -> fill($post['info'][$v], $v, $post['logistic_no'][$v]);
    		}
			Custom_Model_Message::showMessage(self::FILL_SUCCESS, 'event', 1250, "Gurl('refresh')");
    	} else {
    		exit;
    	}
    }
    
     /**
     * 批量填充发票号
     *
     * @return   void
     */
    public function fillInvoiceNoAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) > 0 && $post['invoice_no']) {
            $invoice_no = $post['invoice_no'];
            $length = strlen($invoice_no);
    		foreach($post['ids'] as $k => $v){
    		    if (strlen($invoice_no) < $length) {
    		        for ($i = 0; $i < $length - strlen($invoice_no); $i++) {
    		            $invoice_no = '0'.$invoice_no;
    		        }
    		    }
    			$this -> _api -> fillInvoice($post['info'][$v], $v, $invoice_no);
                $invoice_no += 1;
    		}
			Custom_Model_Message::showMessage(self::FILL_SUCCESS, 'event', 1250, "Gurl('refresh')");
    	} else {
    		exit;
    	}
    }
    
    /**
     * 运输单跟踪
     *
     * @return void
     */
    public function trackAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
            	$post = $this -> _request -> getPost();
            	$post['logistic_status'] = (int)$this -> _request -> getParam('logistic_status', null);
                $result = $this -> _api -> track($post);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TRACK_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'track';
                $datas = $this -> _api -> get("tid=$id");
                $data = $datas[0];
                $data['bill_no_array'] = $this -> _api -> getMergeSplitOrderSNArray($data['bill_no']);
                $this -> view -> data = $data;
                $this -> view -> tracks = $this -> _api -> getTrack("item_no='{$data['bill_no']}'");
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 运输单跟踪批量维护
     *
     * @return void
     */
    public function trackBatchAction()
    {
    	if ($this -> _request -> isPost()) {
    		$post = $this -> _request -> getPost();
    		$post['logistic_status'] = (int)$this -> _request -> getParam('logistic_status', null);
        	$result = $this -> _api -> trackBatch($post);
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::TRACK_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage('error');
        	}
        }
    }
    
    /**
     * 关键字维护
     *
     * @return void
     */
    public function protectAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $logistic = new Admin_Models_API_Logistic();
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
            	$post = $this -> _request -> getPost();
                $result = $logistic -> editLogisticAreaKeyword(array('logistic_code' => $post['logistic_code'], 'area_id' => $post['area_id']), array('delivery_keyword' => $post['delivery_keyword'], 'non_delivery_keyword' => $post['non_delivery_keyword']));
	        	if ($result) {
	        	    $this -> _api -> protect($id);
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'protect';
                $datas = $this -> _api -> get("tid=$id");
                $data = $datas[0];
                $r = array_shift($logistic -> getLogisticAreaListWithPage(array('logistic_code' => $data['logistic_code'], 'area_id' => $data['area_id']),1));
                $this -> view -> logistic = array_shift($r);
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 查看动作
     *
     * @return void
     */
    public function viewAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
                $this -> view -> action = 'view';
                $where = $id ? "tid=$id" : "bill_no=$bill_no and is_cancel=0";
                $datas = $this -> _api -> get($where);
                $data = $datas[0];
                $data['bill_no_array'] = $this -> _api -> getMergeSplitOrderSNArray($data['bill_no']);
                $this -> view -> data = $data;
                $this -> view -> op = $this -> _api -> getOp("item_id=$id");
                $this -> view -> tracks = $this -> _api -> getTrack("item_no='{$data['bill_no']}'");
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 查看动作
     *
     * @return void
     */
    public function publicViewAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $bill_no = $this -> _request -> getParam('bill_no', null);
        if ($id > 0 || $bill_no) {
                $this -> view -> action = 'view';
                $where = $id ? "tid=$id" : "bill_no='$bill_no' and is_cancel=0";
                $datas = $this -> _api -> get($where);
                $data = $datas[0];
                if (!$data) exit('无此运单信息');
                $this -> view -> data = $data;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
                $this -> view -> op_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='check'"));
                $this -> view -> tracks = $this -> _api -> getTrack("item_no='{$data['bill_no']}'");
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 老打印动作 现在已不用
     *
     * @return void
     */
    public function printAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $is_cod = (int)$this -> _request -> getParam('is_cod', null);
        $logistic_code = $this -> _request -> getParam('logistic_code', null);
        if ($id > 0) {
                $this -> view -> action = 'print';
                $datas = $this -> _api -> get("tid=$id");
                $datas[0]['addr'] = $datas[0]['province'].'&nbsp;&nbsp;'.$datas[0]['city'].'&nbsp;&nbsp;'.$datas[0]['area'].'&nbsp;&nbsp;'.$datas[0]['address'];
		        $datas[0]['contact'] = $datas[0]['tel'].'<br>'.$datas[0]['mobile'];
		        $datas[0]['rmb'] = $this -> _api -> changeToRMB($datas[0]['amount']);
		        $datas[0]['no'] = $datas[0]['bill_no'].'<br>商品数量：'.$datas[0]['goods_number'];
		        $datas[0]['time'] = date('Y年m月d日', time());
		        $datas[0]['bill_no'] = str_replace(',', ' ', $datas[0]['bill_no']);
                $this -> view -> datas = $datas;
                $this -> render('print/'.$logistic_code.($is_cod ? 'cod' : ''));
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 打印动作(新版)
     *
     * @return void
     */
    public function print2Action()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $is_cod = (int)$this -> _request -> getParam('is_cod', null);
        if ( $is_cod )  $type = 1;  //货到付款
        else    $type = 2;          //款到发货
        $logistic_code = $this -> _request -> getParam('logistic_code', null);
        
        if ( !$id ) {
            die('no id');
        }
        
        $data = array_shift( $this -> _api -> get("tid=$id") );
        if ( !$data ) {
            die('no data');
        }
        
        $logisticAPI = new Admin_Models_API_Logistic();
        $template = $logisticAPI -> getLogisticTemplate($logistic_code, $type);
        if ( !$template ) {
            die('找不到对应模板!');
        }
        
        $data['amount_cn'] = $this -> _api -> changeToRMB($data['amount']);
        $template = $logisticAPI -> parseTemplate($template, $data);
        $this -> view -> templates = array($template);
        echo $this -> view -> render('transport/print2.tpl');
        exit;
    }
    
    /**
     * 打印动作(测试)
     *
     * @return void
     */
    public function print3Action()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $is_cod = (int)$this -> _request -> getParam('is_cod', null);
        if ( $is_cod )  $type = 1;  //货到付款
        else    $type = 2;          //款到发货
        $logistic_code = $this -> _request -> getParam('logistic_code', null);
        
        if ( !$id ) {
            die('no id');
        }
        
        $data = array_shift( $this -> _api -> get("tid=$id") );
        if ( !$data ) {
            die('no data');
        }
        
        $logisticAPI = new Admin_Models_API_Logistic();
        $template = $logisticAPI -> getLogisticTemplate($logistic_code, $type);
        if ( !$template ) {
            die('找不到对应模板!');
        }
        
        $data['amount_cn'] = $this -> _api -> changeToRMB($data['amount']);
        $template = $logisticAPI -> parseTemplate($template, $data);
        
        $this -> view -> templates = array($template);
        
        echo $this -> view -> render('transport/print3.tpl');
        exit;
    }
    
	/**
     * 批量打印
     *
     * @return   void
     */
    public function printsAction()
    {
    	$post = $this -> _request -> getPost();
    	$search = $this -> _request -> getParams();
    	if (count($post['ids']) > 0 && $search['bill_type']!='' && $search['logistic_code'] && $search['is_cod']!='') {
    	    
    	    $logisticAPI = new Admin_Models_API_Logistic();
	    	if ( $search['is_cod'] )    $type = 1;
	    	else    $type = 2;
	    	$template = $logisticAPI -> getLogisticTemplate($search['logistic_code'], $type);
            if ( !$template ) {
                die('找不到对应模板!');
            }
            
            $convert = new code39();
    		$orderAPI = new Admin_Models_API_Order();
	    	$datas = $this -> _api -> get("tid in (".implode(',', $post['ids']).") and lock_name='{$this->_auth['admin_name']}'");
	    	foreach ($datas as $num => $data) {
		        $datas[$num]['bill_no'] && $datas[$num]['barcode'] = $convert -> decode($datas[$num]['bill_no']);
		        $datas[$num]['amount_cn'] = $this -> _api -> changeToRMB($datas[$num]['amount']);
		        $datas[$num]['card_sn'] = $orderAPI -> getOrderGoodsCard($datas[$num]['bill_no']);
		        if ( $datas[$num]['shop_id'] > 0 ) {
		            $datas[$num]['brand'] = $shopInfo[$datas[$num]['shop_id']];
		        }
		        $templates[] = $logisticAPI -> parseTemplate($template, $datas[$num]);
	        }
	    	$this -> view -> templates = $templates;
            echo $this -> view -> render('transport/print2.tpl');
            exit;
	    	
    	} else {
    		exit('no content');
    	}
    }
    
    /**
     * 批量打印
     *
     * @return   void
     */
    public function printsInvoiceAction()
    {
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) == 0) {
    	    exit('no content');
    	}
    	
    	$orderAPI = new Admin_Models_API_Order();
    	$productAPI = new Admin_Models_DB_Product();
    	
    	foreach ($post['ids'] as $num => $v) {
		    $bill_no = $post['bill_no'][$num];
		    $transport = array_shift($this -> _api -> get("t1.bill_no='{$bill_no}' and is_cancel=0"));
            if (!$transport) {
                die("单据{$bill_no}找不到");
            }
	        if (!$transport['invoice_no']) {
	            die("请先填充对应的发票号再打印");
	        }
            
            if (!$transport['invoice_type']) {
                $transport['invoice_type'] = 1;
                $transport['invoice'] = '个人';
                $transport['invoice_content'] = '保健品';
            }
            
            $datas[$num]['transport'] = $transport;
            $datas[$num]['now'] = date('Y-m-d');
            
            $batchSNArray = $this -> _api -> getMergeSplitOrderSNArray($bill_no);
            foreach ($batchSNArray as $batchSN) {
                $data = $orderAPI -> getOrderBathWithPage(array('batch_sn' => $batchSN, 'includeOffer' => true, 'includeCoupon' => true));
                $order = $data['data'][0];
                $order['discount'] = 0;
                $hasGiftCard = false;
                $onlyGiftCard = true;
                $priceGoods = $giftCardAmount = $giftCardPrice = 0;

                foreach ($data['product'] as $index => $goods) {
                    if ($goods['product_id']) {
                        if (!isset($productNameInfo[$goods['product_id']])) {
                            $product = array_shift($productAPI -> fetch("p.product_id = '{$goods['product_id']}'"));
                            $productNameInfo[$goods['product_id']] = $product['product_name'];
                        }
                        $data['product'][$index]['goods_name'] = $productNameInfo[$goods['product_id']];
                                
                        if ($goods['is_gift_card']) {
                            $hasGiftCard = true;
                            $giftCardPrice += $goods['sale_price'] * $goods['number'];
                        }
                        else {
                            $onlyGiftCard = false;
                        }
                        $priceGoods += $goods['sale_price'] * ($goods['number'] - $goods['return_number']);
                        $giftCard = $productAPI -> getGiftcardInfoByProductid($goods['product_id']);
                        $giftCardAmount += $giftCard['amount'];
                    }
                    else if ($goods['type'] != 5 && !$goods['parent_id']){
                        $order['discount'] += $goods['sale_price'] * ($goods['number'] - $goods['return_number']);
                    }
                }
                
                if ($hasGiftCard && !$onlyGiftCard) {
                    $order['price_goods'] = $priceGoods;
                    $order['price_order'] = $priceGoods + $order['discount'] + $order['price_adjust'];
                    if ($priceGoods + $order['discount'] + $order['price_adjust'] - $giftCardPrice >= $giftCardPrice) {
                        $order['gift_card_margin'] = $giftCardAmount;
                    }
                    else {
                        $order['gift_card_margin'] = $priceGoods + $order['discount'] + $order['price_adjust'] - $giftCardPrice;
                    }
                }
                
                $order['price_eq_goods'] = 0;
                foreach ($data['product'] as $product) {
                    $order['price_eq_goods'] += $product['eq_price'] * $product['number'];
                }
                
                if ($datas[$num]['order']) {
                    $datas[$num]['order']['price_order'] += $order['price_order'];
                    $datas[$num]['order']['price_goods'] += $order['price_goods'];
                    $datas[$num]['order']['price_adjust'] += $order['price_adjust'];
                    $datas[$num]['order']['price_pay'] += $order['price_pay'];
                    $datas[$num]['order']['price_from_return'] += $order['price_from_return'];
                    $datas[$num]['order']['price_payed'] += $order['price_payed'];
                    $datas[$num]['order']['account_payed'] += $order['account_payed'];
                    $datas[$num]['order']['point_payed'] += $order['point_payed'];
                    $datas[$num]['order']['gift_card_payed'] += $order['gift_card_payed'];
                    $datas[$num]['order']['discount'] += $order['discount'];
                    $datas[$num]['order']['product'] = array_merge($datas[$num]['order']['product'], $data['product']);
                    $datas[$num]['order']['price_eq_goods'] += $order['price_eq_goods'];
                }
                else {
                    $order['product'] = $data['product'];
                    $datas[$num]['order'] = $order;
                }
            }

            $datas[$num]['invoice_amount'] = $datas[$num]['order']['price_pay'] - $datas[$num]['order']['point_payed'] - $datas[$num]['order']['account_payed'] - $datas[$num]['order']['gift_card_payed'] - $datas[$num]['order']['price_logistic'];
            $datas[$num]['invoice_big_amount'] = $this -> _api -> changeToRMB($datas[$num]['invoice_amount']);
            
            if (bcadd(bcsub($datas[$num]['order']['price_pay'], $datas[$num]['order']['price_eq_goods'], 2), $datas[$num]['order']['gift_card_margin'], 2) != 0) {
                $datas[$num]['balance_amount'] = bcsub($datas[$num]['order']['price_pay'], $datas[$num]['order']['price_eq_goods'], 2);
            }
    	}
    	
    	$this -> view -> datas = $datas;
    }
    
    /**
     * 选择运单
     *
     * @return void
     */
    public function selAction()
    {
    	$job = $this -> _request -> getParam('job', null);
    	$where = $this -> _request -> getParams();
    	$page = (int)$this -> _request -> getParam('page', 1);
        if($job) {
	        switch ($where['type']){
	            case 'track':
	            $where['filter'] = " and t1.is_confirm=1 and t1.is_cancel=0 and t1.logistic_status=1 and t1.logistic_code <> 'self' ";
	            break;
	            case 'clear':
	            $where['filter'] = " and t1.is_cancel=0 and t1.logistic_no <> ''";
	            break;
	            case 'clear-cod':
	            $where['filter'] = " and t1.is_cancel=0 and t1.logistic_no <> ''";
	            break;
	            default:
	            $where['filter'] = " and t1.is_cod=1 and t1.cod_status=0 and t1.is_cancel=0 and t1.logistic_status >0 and t1.amount > 0";
	        }
	        $data = $this -> _api -> search($where, 'search-list', $page);
	        $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
	        $datas = $data['datas'];
	        foreach ($datas as $num => $data) {
	        	$datas[$num]['bill_type'] = $this -> _bill_type[$data['bill_type']];
	        	$datas[$num]['logistic_status'] = $this -> _logisticStatus[$data['logistic_status']];
	        	$datas[$num]['fee_service'] = (float)$datas[$num]['logistic_fee_service'];
	        	$datas[$num]['cod_price'] = (float)$datas[$num]['logistic_price_cod'];
	        	$datas[$num]['clear_amount'] = $datas[$num]['amount'] + $datas[$num]['change_amount'];
	        	$datas[$num]['back_amount'] = $datas[$num]['amount'] + $datas[$num]['change_amount'] - (float)$datas[$num]['logistic_fee_service'] - $datas[$num]['logistic_price_cod'];
	        	unset($datas[$num]['address']);
	        	unset($datas[$num]['logistic_list']);
	        	$datas[$num]['info'] = Zend_Json::encode($datas[$num]);
	        }
	        
	        $this -> view -> logistic = Zend_Json::encode($logistic);
	        $this -> view -> datas = $datas;
	        $this -> view -> param = $this -> _request -> getParams();
            $this -> view -> pageNav = $pageNav -> getNavigation();
        }
        
        $this -> view -> param = $this -> _request -> getParams();
    }
    
    /**
     * ajax更新数据
     *
     * @return void
     */
    public function ajaxupdateAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, $field, $val);
        } else {
            exit('error!');
        }
    }
    
	/**
     * 锁定/解锁动作
     *
     * @return   void
     */
    public function lockAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('val', 0);
    	$this -> _api -> lock($this -> _request -> getPost(), $val);
    }
    
	/**
     * 导出运输单数据
     *
     * @return   void
     */
    public function exportAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $tmp = $this -> _request -> getParams();
        $param['lid'] = $this -> _lid;
        $param['ids'] = $tmp['ids'];
        $this -> _api -> export($param, $this -> _request -> getParam('act', null));
        exit;
    }
	/**
     * 导入运输单结算数据
     *
     * @return   void
     */
    public function importClearAction()
    {
	    if ($this -> _request -> isPost()) {
	        $this -> _helper -> viewRenderer -> setNoRender();
	        
	        if ($this -> _api -> importClear($_FILES['uploadfile']))
	        {
	        	Custom_Model_Message::showMessage(self::IMPORT_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        } else {
	            Custom_Model_Message::showMessage($this -> _api -> error());
	        }
	        exit;
	    }else{
	    	
	    }
    }
	/**
     * 运输单跟踪查询
     *
     * @return   void
     */
    public function billTrackAction()
    {
        $AppKey='467f8081db36770e';
        $logistic_code = $this -> _request -> getParam('logistic_code');
        $logistic_no = $this -> _request -> getParam('logistic_no');
        if($logistic_code=='ems'){
            $logistic_com='ems';
        }elseif($logistic_code=='zjs'){
            $logistic_com='zhaijisong';
        }elseif($logistic_code=='yt'){
            $logistic_com='yuantong';
        }elseif($logistic_code=='sf'){
            $logistic_com='shunfeng';
        }
        if ($logistic_code == 'yt' || $logistic_code == 'zjs' || $logistic_code == 'ems' || $logistic_code == 'sf') {
            $url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$logistic_com.'&nu='.$logistic_no.'&show=2&muti=1&order=asc';
            //优先使用curl模式发送数据
            if (function_exists('curl_init') == 1){
              $curl = curl_init();
              curl_setopt ($curl, CURLOPT_URL, $url);
              curl_setopt ($curl, CURLOPT_HEADER,0);
              curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
              curl_setopt ($curl, CURLOPT_TIMEOUT,5);
              $get_content = curl_exec($curl);
              curl_close ($curl);
            }else{
              $snoopy = new Custom_Model_Snoopy();
              $snoopy->referer = 'http://www.google.com/';//伪装来源
              $snoopy->fetch($url);
              $get_content = $snoopy->results;
            }
            print_r($get_content . '<br/>');
            exit;
        }
    }
    /**
     * 运输单列表批量查询
     */
    public function selBatchAction() {
        $doimport = $this -> _request -> getParam('doimport', null);
        $search = $this -> _request -> getParams();
        if ($doimport) {
            if(is_file($_FILES['import_file']['tmp_name'])) {
                $xls = new Custom_Model_PHPExcel();
			    $xls -> open($_FILES['import_file']['tmp_name']);
			    $lines = $xls -> toArray();
                if (is_array($lines)) {
                    $bill_no_array = array();
                    for ($i = 0; $i < count($lines); $i++) {
                        $lines[$i][0] = trim($lines[$i][0]);
                        $lines[$i][1] = trim($lines[$i][1]);
                        $lines[$i][2] = trim($lines[$i][2]);
                        $bill_no_array[] = "'{$lines[$i][0]}'";
                    }
                    $where['filter'] = " and t1.is_cod=1 and t1.is_cancel=0 and t1.logistic_status>0 and t1.logistic_code = '{$search['logistic_code']}' and t1.logistic_no in (".implode(',', $bill_no_array).")";
                    $temp_data = $this -> _api -> search($where, null, null, null, null, true);
                    $total = $temp_data['total'];
        	        $datas = $temp_data['datas'];
        	        if ($total) {
            	        foreach ($datas as $num => $data) {
            	        	$datas[$num]['bill_type'] = $this -> _bill_type[$data['bill_type']];
            	        	$datas[$num]['logistic_status'] = $this -> _logisticStatus[$data['logistic_status']];
            	        	$datas[$num]['logistic_price_cod'] = $datas[$num]['logistic_price_cod'];
            	        	$datas[$num]['clear_amount'] = $datas[$num]['amount'] + $datas[$num]['change_amount'];
            	        	unset($datas[$num]['logistic_list']);
            	        	$datas[$num]['binfo'] = Zend_Json::encode(array('tid' => $datas[$num]['tid'],
            	        	                                                'logistic_name' => $datas[$num]['logistic_name'],
            	        	                                                'bill_no' => $datas[$num]['bill_no'],
            	        	                                                'bill_type' => $datas[$num]['bill_type'],
            	        	                                                'logistic_no' => $datas[$num]['logistic_no'],
            	        	                                                'logistic_status' => $datas[$num]['logistic_status'],
            	        	                                                'clear_amount' => $datas[$num]['clear_amount'],
            	        	                                                'logistic_price_cod' => $datas[$num]['logistic_price_cod'],
            	        	                                                'cod_status' => $datas[$num]['cod_status'],
            	        	                                                'shop_id' => $datas[$num]['shop_id'],
            	        	                                                'type' => $datas[$num]['type'],
            	        	                                                ));
            	        	$billInfo[trim($data['logistic_no'])] = $datas[$num];
            	        }
                    }
                    echo '<script language="JavaScript" type="text/javascript">';
                    echo 'parent.delAllRow();';
                    $index = 0;
                    for ($i = 0; $i < count($lines); $i++) {
                        $bill_no = $lines[$i][0];
                        $amount = round($lines[$i][1] + $lines[$i][2], 2);
                        if ($billInfo[$bill_no] && $billInfo[$bill_no]['clear_amount'] == $amount) {
                            $amount = '';
                        }
                        else    continue;
                        echo "parent.insertRow2({$index}, '{$bill_no}', '{$billInfo[$bill_no]['binfo']}', '{$amount}', '{$lines[$i][2]}');";
                        $index++;
                    }
                    for ($i = 0; $i < count($lines); $i++) {
                        $bill_no = $lines[$i][0];
                        $amount = round($lines[$i][1] + $lines[$i][2], 2);
                        if ($billInfo[$bill_no] && $billInfo[$bill_no]['clear_amount'] == $amount) {
                            continue;
                        }
                        echo "parent.insertRow2({$index}, '{$bill_no}', '{$billInfo[$bill_no]['binfo']}', '{$amount}', '{$lines[$i][2]}');";
                        $index++;
                    }
                    echo "</script>";
                }
            }
            
            exit;
        }
        $this -> view -> param = $search;
    }
    
    /**
     * 运输单跟踪批量查询
     */
    public function selTrackBatchAction() {
        $doimport = $this -> _request -> getParam('doimport', null);
        $search = $this -> _request -> getParams();
        
        if ($doimport) {
            if( is_file($_FILES['import_file']['tmp_name']) ) {
                $xls = new Custom_Model_ExcelReader();
                $xls -> setOutputEncoding('utf-8');
			    $xls -> read($_FILES['import_file']['tmp_name']);
			    $datas = $xls -> sheets[0];
			    $lines = $datas['cells'];
                if ( is_array($lines) ) {
                    $bill_no_array = array();
                    for ( $i = 2; $i <= count($lines); $i++ ) {
                        $bill_no_array[] = $lines[$i][1];
                        $lines[$i][1] = trim($lines[$i][1]);
                    }
                    
                    $where['filter'] = " and is_confirm=1 and is_cancel=0 and logistic_status=1 and logistic_code = '{$search['logistic_code']}' and logistic_no in (".implode(',', $bill_no_array).")";
                    $temp_data = $this -> _api -> search($where);
                    $total = $temp_data['total'];
        	        $datas = $temp_data['datas'];
        	        if ( $total ) {
            	        foreach ($datas as $num => $data) {
            	        	$datas[$num]['bill_type'] = $this -> _bill_type[$data['bill_type']];
            	        	$datas[$num]['logistic_status'] = $this -> _logisticStatus[$data['logistic_status']];
            	        	$datas[$num]['fee_service'] = (float)$datas[$num]['logistic_fee_service'];
            	        	$datas[$num]['cod_price'] = (float)$datas[$num]['logistic_price_cod'];
            	        	$datas[$num]['clear_amount'] = $datas[$num]['amount'] + $datas[$num]['change_amount'];
            	        	$datas[$num]['back_amount'] = $datas[$num]['amount'] + $datas[$num]['change_amount'] - (float)$datas[$num]['logistic_fee_service'] - $datas[$num]['logistic_price_cod'];
            	        	unset($datas[$num]['logistic_list']);

            	        	$datas[$num]['binfo'] = Zend_Json::encode(array('tid' => $datas[$num]['tid'],
            	        	                                                'logistic_name' => $datas[$num]['logistic_name'],
            	        	                                                'bill_no' => $datas[$num]['bill_no'],
            	        	                                                'bill_type' => $datas[$num]['bill_type'],
            	        	                                                'logistic_no' => $datas[$num]['logistic_no'],
            	        	                                                'logistic_status' => $datas[$num]['logistic_status'],
            	        	                                                'clear_amount' => $datas[$num]['clear_amount'],
            	        	                                                'fee_service' => $datas[$num]['fee_service'],
            	        	                                                'cod_price' => $datas[$num]['cod_price'],
            	        	                                                'back_amount' => $datas[$num]['back_amount'],
            	        	                                                ));
            	        	
            	        	$billInfo[$data['logistic_no']] = $datas[$num];
            	        }
                    }
                    echo '<script language="JavaScript" type="text/javascript">';
                    echo 'parent.delAllRow();';
                    $index = 0;
                    for ( $i = 2; $i <= count($lines); $i++ ) {
                        $bill_no = $lines[$i][1];
                        $amount = $lines[$i][2];
                        if ( $billInfo[$bill_no] && $billInfo[$bill_no]['clear_amount'] == $amount ) {
                            $amount = '';
                        }
                        else    continue;
                        echo "parent.insertRow2({$index}, '{$bill_no}', '{$billInfo[$bill_no]['binfo']}', '{$amount}');";
                        $index++;
                    }
                    for ( $i = 2; $i <= count($lines); $i++ ) {
                        $bill_no = $lines[$i][1];
                        $amount = $lines[$i][2];
                        if ( $billInfo[$bill_no] && $billInfo[$bill_no]['clear_amount'] == $amount ) {
                            continue;
                        }
                        echo "parent.insertRow2({$index}, '{$bill_no}', '{$billInfo[$bill_no]['binfo']}', '{$amount}');";
                        $index++;
                    }
                    echo "</script>";
                }
            }
            exit;
        }
        $this -> view -> param = $search;
    }
    
    /**
     * 运输单列表批量查询(运费结算)
     */
    public function selClearBatchAction() {
        $doimport = $this -> _request -> getParam('doimport', null);
        $search = $this -> _request -> getParams();
        if ($doimport) {
            if(is_file($_FILES['import_file']['tmp_name'])) {
                $xls = new Custom_Model_PHPExcel();
			    $xls -> open($_FILES['import_file']['tmp_name']);
			    $lines = $xls -> toArray();
                if (is_array($lines)) {
                    $bill_no_array = array();
                    for ($i = 0; $i < count($lines); $i++) {
                        $lines[$i][0] = trim($lines[$i][0]);
                        $lines[$i][1] = trim($lines[$i][1]);
                        $bill_no_array[] = "'{$lines[$i][0]}'";
                        $priceInfo[$lines[$i][0]] = $lines[$i][1];
                    }
                    $where['filter'] = " and is_cancel=0 and logistic_code = '{$search['logistic_code']}' and logistic_status > 0 and logistic_no in (".implode(',', $bill_no_array).")";
                    $temp_data = $this -> _api -> search($where);
                    $total = $temp_data['total'];
        	        $datas = $temp_data['datas'];
        	        if ($total) {
            	        foreach ($datas as $num => $data) {
            	        	$datas[$num]['bill_type'] = $this -> _bill_type[$data['bill_type']];
            	        	$datas[$num]['logistic_status'] = $this -> _logisticStatus[$data['logistic_status']];
            	        	unset($datas[$num]['logistic_list']);

            	        	$datas[$num]['binfo'] = Zend_Json::encode(array('tid' => $datas[$num]['tid'],
            	        	                                                'logistic_name' => $datas[$num]['logistic_name'],
            	        	                                                'bill_no' => $datas[$num]['bill_no'],
            	        	                                                'bill_type' => $datas[$num]['bill_type'],
            	        	                                                'logistic_no' => $datas[$num]['logistic_no'],
            	        	                                                'logistic_status' => $datas[$num]['logistic_status'],
            	        	                                                'logistic_price' => $priceInfo[$datas[$num]['logistic_no']],
            	        	                                                ));
            	        	
            	        	$billInfo[$data['logistic_no']] = $datas[$num];
            	        }
                    }
                    echo '<script language="JavaScript" type="text/javascript">';
                    echo 'parent.delAllRow();';
                    $index = 0;
                    for ($i = 0; $i < count($lines); $i++) {
                        $bill_no = $lines[$i][0];
                        $amount = $lines[$i][1];
                        if ($billInfo[$bill_no] && $billInfo[$bill_no]['clear_amount'] == $amount) {
                            $amount = '';
                        }
                        else    continue;
                        echo "parent.insertRow2({$index}, '{$bill_no}', '{$billInfo[$bill_no]['binfo']}', '{$amount}');";
                        $index++;
                    }
                    for ($i = 0; $i < count($lines); $i++) {
                        $bill_no = $lines[$i][0];
                        $amount = $lines[$i][1];
                        if ($billInfo[$bill_no] && $billInfo[$bill_no]['clear_amount'] == $amount) {
                            continue;
                        }
                        echo "parent.insertRow2({$index}, '{$bill_no}', '{$billInfo[$bill_no]['binfo']}', '{$amount}');";
                        $index++;
                    }
                    echo "</script>";
                }
            }
            
            exit;
        }
        
        $this -> view -> param = $search;
    }
    
    /**
     * 仓库配货订单列表
     *
     * @return void
     */
    public function prepareListAction()
    {
        $search = $this->_request->getParams();
        $search['lid'] = $this -> _lid;
        $data = $this -> _api -> getPrepareOrderList($search);
        $this -> view -> data = $data['data'] ? $data['data'] : null;
        $this -> view -> product = $data['product'];
        $this -> view -> auth = $this -> _auth['admin_name'];
        $this -> view -> param = $search;
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 锁定/解锁动作
     *
     * @return   void
     */
    public function lockOrderAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('val', 0);

    	$orderAPI = new Admin_Models_API_Order();
    	$orderAPI -> lock($this -> _request -> getPost(), $val);
    }
    
    /**
     * 仓库配货动作
     *
     * @return   void
     */
    public function prepareAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        
        $orderAPI = new Admin_Models_API_Order();
        $params = $this -> _request -> getParams();
        $params['lid'] = $this -> _lid;
        if ($params['ids']) {
            $datas = $this -> _api -> getPrepareOrderList($params);
            /*
            foreach ($datas['data'] as $data) {
                if ($data['can_merge']) {
                    Custom_Model_Message :: showMessage("请先合并订单再配货!", 'reload', -1);
                }
            }
            */
            foreach ($params['ids'] as $batchSN) {
                $data = $this -> _api -> getPrepareOrderList(array('batch_sn' => $batchSN));
                if (!$data['data']) {
                    Custom_Model_Message :: showMessage("订单{$batchSN}不存在或状态不正确，无法配货!", 'reload', -1);
                }
                if ($data['data'][0]['lock_name'] != $this -> _auth['admin_name']) {
                    Custom_Model_Message :: showMessage("订单{$batchSN}未锁定或锁定用户不正确，无法配货!", 'reload', -1);
                }
                
                if (!$orderAPI -> checkOut($batchSN)) {
                    Custom_Model_Message :: showMessage("订单{$batchSN}库存不足!", 'reload', -1);
                }
                
                if (!$orderAPI -> out($batchSN, null, $this -> _logicArea)) {
                    Custom_Model_Message :: showMessage("订单{$batchSN}出库失败!", 'reload', -1);
                }
                $this -> _api -> autoAssign($batchSN);
                $orderAPI -> lock(array('ids' => array($batchSN)), 0);
            }
        }
        else {
            Custom_Model_Message :: showMessage("请先选择订单!", 'reload', -1);
        }
        
        Custom_Model_Message :: showMessage("配货完成!");
    }
    
    /**
     * 合并订单
     *
     * @return   void
     */
    public function mergeOrderAction()
    {
        $ids = $this -> _request -> getParam('ids');
        if (!$ids)  exit;
        
        $batchSNArray = explode(',', $ids);
        foreach ($batchSNArray  as $index => $batchSN) {
            if (!$batchSN) {
                unset($batchSNArray[$index]);
                continue;
            }
            
            $search['ids'][] = "{$batchSN}";
        }
        if (!$search['ids'])    exit;

        $datas = $this -> _api -> getPrepareOrderList(array('ids' => $search['ids']));
        
        if (!$datas['data'] || (count($datas['data']) != count($search['ids']))) {
            die('找不到对应的订单!');
        }
        
        foreach ($datas['data'] as $order) {
            if ($order['lock_name'] != $this -> _auth['admin_name']) {
                die('请先锁定订单!');
            }
            
            if (!$key) {
                $key = $this -> _api -> getMergeKey($order);
            }
            else {
                if ($key != $this -> _api -> getMergeKey($order)) {
                    die('订单有不同的收货地址或收货人，不能合并!');
                }
            }
            
            if (!$payType) {
                $payType = $order['pay_type'] == 'cod' ? 'cod' : 'notcod';
            }
            else {
                $tempPayType = $order['pay_type'] == 'cod' ? 'cod' : 'notcod';
                if ($payType != $tempPayType ) {
                    die('订单有不同的付款方式，不能合并!');
                }
            }
        }
        
        if ($this -> _request -> isPost()) {
            $orderAPI = new Admin_Models_API_Order();
            
            if (!$orderAPI -> out($batchSNArray, null, $this -> _logicArea)) {
                Custom_Model_Message :: showMessage("订单出库失败!");
            }
            $orderAPI -> lock(array('ids' => $batchSNArray), 0);
            
            Custom_Model_Message::showMessage('配货完成!', 'event', 1250, "Gurl('refresh')");
        }
        
        $this -> view -> datas = $datas['data'];
        $this -> view -> data = $datas['data'][0];
        $this -> view -> product = $datas['product'];
        $this -> view -> ids = $ids;
    }
    
    /**
     * 拆分订单
     *
     * @return   void
     */
    public function splitOrderAction()
    {
        $batch_sn = $this -> _request -> getParam('batch_sn');
        
        $datas = $this -> _api -> getPrepareOrderList(array('batch_sn' => $batch_sn));
        $order = $datas['data'][0];
        
        if (!$order) {
            die('找不到对应的订单!');
        }
        
        if ($order['lock_name'] != $this -> _auth['admin_name']) {
            die('请先锁定订单!');
        }
        
        if ($this -> _request -> isPost()) {
            $post = $this -> _request -> getPost();
            $productNumber = '';
            $productInfo = '';
            foreach ($datas['product'] as $product) {
                $productInfo[$product['product_sn']] = $product['product_id'];
            }
            
            foreach ($post['number'] as $index => $number) {
                $tempArr = explode('_', $post['product'][$index]);
                if ($number <= 0)   continue;
                
                $splitData[$tempArr[0]][] = array('product_sn' => $tempArr[1], 'product_id' => $productInfo[$tempArr[1]], 'number' => $number);
                $productNumber[$tempArr[1]] += $number;
            }
        
            foreach ($datas['product'] as $product) {
                if ($product['number'] != $productNumber[$product['product_sn']]) {
                    Custom_Model_Message :: showMessage("产品数量不正确!");
                }
            }
            
            $orderAPI = new Admin_Models_API_Order();
            if (!$orderAPI -> out($order['batch_sn'], $splitData, $this -> _logicArea)) {
                Custom_Model_Message :: showMessage("订单出库失败!");
            }
            
            $orderAPI -> lock(array('ids' => array($order['batch_sn'])), 0);
            
            Custom_Model_Message::showMessage('配货完成!', 'event', 1250, "Gurl('refresh')");
        }
        
        $this -> view -> order = $order;
        $this -> view -> product = $datas['product'];
    }
    
    /**
     * 订单配货详情
     *
     * @return   void
     */
    public function prepareOrderAction()
    {
        $batch_sn = $this -> _request -> getParam('batch_sn');
        
        $datas = $this -> _api -> getPrepareOrderList(array('batch_sn' => $batch_sn));

        $order = $datas['data'][0];
        
        if (!$order) {
            die('找不到对应的订单!');
        }
        
        if ($order['lock_name'] != $this -> _auth['admin_name']) {
            die('请先锁定订单!');
        }
        
        if ($this -> _request -> isPost()) {
            $orderAPI = new Admin_Models_API_Order();
            if (!$orderAPI -> checkOut($batch_sn)) {
                Custom_Model_Message :: showMessage("订单{$batch_sn}库存不足!", 'reload', -1);
            }
            if (!$orderAPI -> out(array($batch_sn), null, $this -> _logicArea)) {
                Custom_Model_Message :: showMessage("订单出库失败!");
            }
            $orderAPI -> lock(array('ids' => array($batch_sn)), 0);
            
            $this -> _api ->autoAssign($batch_sn);
            Custom_Model_Message::showMessage('配货完成!', 'event', 1250, "Gurl('refresh')");
        }
        
        $this -> view -> order = $order;
        $this -> view -> product = $datas['product'];
    }
    /**
     * 运输单变更
     *
     * @return   void
     */
    public function changeAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
            	$post = $this -> _request -> getPost();
            	$tempArr = explode('|', $post['new_logistic_code']);
            	$logistic_code = $tempArr[0];
            	$logistic_name = $tempArr[1];
            	$result = $this -> _api -> update(array('logistic_code' => $logistic_code, 'logistic_name' => $logistic_name, 'logistic_no' => $post['new_logistic_no']), "tid = {$id}");
            	if ($post['bill_type'] == 1) {
            	    $orderAPI = new Admin_Models_DB_Order();
            	    $order = array_shift($orderAPI -> getOrderBatch(array('batch_sn' => $post['bill_no'])));
            	    if ($order['pay_type'] == 'cod' && $order['logistic_code'] != $logistic_code) {
            	        $financeAPI = new Admin_Models_API_Finance();
            	        $receivableData = $financeAPI -> getFinanceReceivable(array('batch_sn' => $post['bill_no'],'pay_type' => $order['logistic_code']));
            	        if ($receivableData) {
            	            foreach ($receivableData as $temp) {
            	                if (!$temp['settle_time']) {
            	                    $financeAPI -> updateFinanceReceivable(array('settle_amount' => $temp['amount'], 'settle_time' => time()), "id = '{$temp['id']}'");
            	                }
            	            }
            	        }
            	        
            	        $time = time();
            	        $receiveData = array('batch_sn' => $post['bill_no'],
                                             'type' => 4,
                                             'pay_type' => $logistic_code,
                                             'amount' => $order['price_pay'] - $order['price_payed'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'],
                                             'time_flag' => 1,
                                             'add_time' => $time,
                                            );
                        $financeAPI -> addFinanceReceivable($receiveData);
                        
                        //添加系统虚拟退款
                	    $data = array('shop_id' => $order['shop_id'],
                					  'type' => 0,//系统
                					  'way' => 6,
                					  'item' => 1,
                					  'item_no' => $post['bill_no'],
                					  'pay' => ($order['price_pay'] - $order['price_payed'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed']) * -1,
                					  'logistic' => 0,
                					  'point' => 0,
                					  'account' => 0,
                					  'gift' => 0,
                					  'status' => 2,
                					  'bank_type' => 4,
                					  'bank_data' => '',
                					  'order_data' => '',
                					  'note' => '货到付款物流公司变更虚拟退款',
                					  'callback' => '',
                					  'add_time' => $time,
                					  'check_time' => $time);
                		$financeAPI -> addFrinance($data);
            	    }
            	    
            	    $orderAPI -> updateOrderBatch(array('batch_sn' => $post['bill_no']), array('logistic_code' => $logistic_code, 'logistic_name' => $logistic_name, 'logistic_no' => $post['new_logistic_no']));
            	}
	        	if ($result) {
	        	    Custom_Model_Message::showMessage('变更成功!', 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $datas = $this -> _api -> get("tid=$id");
                $data = $datas[0];
                $data['bill_no_array'] = $this -> _api -> getMergeSplitOrderSNArray($data['bill_no']);
                $this -> view -> data = $data;
                
                $r = Zend_Json::decode($data['logistic_list']);
                if ($r['list']) {
                    $this -> view -> logisticList = $r['list'];
                }
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 快递单跟踪查询
     * 
     */
    public function expressTrackAction() {
    	if($this -> _request -> isPost()){
    		$logistic_code = $this -> _request -> getParam('logistic_code', ''); $logistic_code = trim($logistic_code);
    		$logistic_no = $this -> _request -> getParam('logistic_no', ''); $logistic_no = trim($logistic_no);
    		//
    		switch ($logistic_code) {
    			case 'yt':
	    			$ytAPI = new Custom_Model_Logistic_Yt();
		        	$ytAPI -> billAddNo($logistic_no);
		        	$tracks = $ytAPI -> billQuery(true);
		        	if(is_array($tracks) && count($tracks)){
		        		$this -> view -> tracks = $tracks[$logistic_no];
		        	}else{
		        		$this -> view -> tracks = false;
		        	}
	    			break;
    			
    			default:
    				;
    			break;
    		}
    	}
    	$this -> view -> params = $this -> _request -> getParams();
    }
    
    /**
     * 导入单号
     */
    public function importNoAction() {
        $doimport = $this -> _request -> getParam('doimport', null);
        $search = $this -> _request -> getParams();
        
        if ($doimport) {
            if( is_file($_FILES['import_file']['tmp_name']) ) {
                $xls = new Custom_Model_ExcelReader();
			    $xls -> setOutputEncoding('utf-8');
			    $xls -> read($_FILES['import_file']['tmp_name']);
			    $datas = $xls -> sheets[0];
			    $lines = $datas['cells'];
                if ( is_array($lines) ) {
                    $bill_no_array = array();
                    for ( $i = 2; $i <= count($lines); $i++ ) {
                        $lines[$i][1] = trim($lines[$i][1]);
                        $lines[$i][2] = trim($lines[$i][2]);
                        $bill_no_array[] = "'{$lines[$i][1]}'";
                    }
                    
                    $datas = $this -> _api -> search("bill_no in (".implode(',', $bill_no_array).")", 'confirm-list');
                    if ( $datas['total'] ) {
                        foreach ( $datas['datas'] as $index => $data ) {
                            $data['oinfo'] = Zend_Json::encode(array('tid' => $data['tid'], 'logistic_name' => $data['logistic_name']));
                            $billInfo[$data['bill_no']] = $data;
                        }
                    }
                    
                    echo '<script language="JavaScript" type="text/javascript">';
                    echo 'parent.delAllRow();';
                    $index = 0;
                    for ( $i = 2; $i <= count($lines); $i++ ) {
                        $bill_no = $lines[$i][1];
                        $logistic_no = trim($lines[$i][2]);
                        echo "parent.insertRow({$index}, '{$bill_no}', '{$billInfo[$bill_no]['oinfo']}', '{$logistic_no}');";
                        $index++;
                    }
                    echo "</script>";
                }
            }
            
            exit;
        }
        $this -> view -> param = $search;
    }
    
    /**
     * 填充批量导入的单号
     *
     * @return   void
     */
    public function fillImportNoAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) > 0 && count($post['no']) > 0) {
    		foreach($post['ids'] as $k => $v){
    		    $this -> _api -> fill(array('bill_no' => ''), $v, $post['no'][$k]);
    		}
			Custom_Model_Message::showMessage(self::FILL_SUCCESS, 'event', 1250, "Gurl('refresh')");
    	} else {
    		exit;
    	}
    }
    
    /**
     * 返回派单
     *
     * @return   void
     */
    public function backAssignAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$post = $this -> _request -> getPost();
    	if (count($post['ids']) > 0) {
    		foreach($post['ids'] as $k => $v){
    		    $this -> _api -> backAssign($v);
    		}
			Custom_Model_Message::showMessage(self::BACK_SUCCESS, 'event', 1250, "Gurl('refresh')");
    	} else {
    		exit;
    	}
    }
    
    /**
     * 当天物流取件
     *
     * @return   void
     */
    public function pickUpAction()
    {
        $params = $this -> _request -> getParams();
        $params['lid'] = $this -> _lid;
        !$params['date'] && $params['date'] = date('Y-m-d');
        $params['fromdate'] = $params['date'];
        $params['todate'] = $params['date'];
        $result = $this -> _api -> search($params, 'pick-up-list');
        $datas = $result['datas'];
        $total = $result['total'];
        foreach($datas as $data){
            if ($data['shop_id'] > 0) {
                $shopIDArray[] = $data['shop_id'];
            }
	    }
	    $this -> view -> datas = $datas;
	    $this -> view -> total = $total;
        $this -> view -> param = $params;
    }
    
    /**
     * 出库产品扫描
     *
     * @return   void
     */
    public function barcodeScanAction()
    {
        
    }
    
    /**
     * 打包发货扫描
     *
     * @return   void
     */
    public function packageScanAction()
    {
        
    }
    
    /**
     * 获得扫描的单据
     *
     * @return   void
     */
    public function getScanBillAction()
    {
        $outstockAPI = new Admin_Models_API_OutStock();
        
        $billNo = $this -> _request -> getParam('bill_no');
        $logisticNo = $this -> _request -> getParam('logistic_no');
        if ($billNo) {
            $billNo = 'b'.$billNo;
            $billNo = str_replace(array('ZZZZ'), array('_'), $billNo);
            $scan = 0;
        
            if (substr($billNo, 0, 3) == 'OID') {
                $outstockID = substr($billNo, 3, strlen($billNo));
                $datas = $outstockAPI -> search(array('outstock_id' => $outstockID), 'send-list', 1, $this -> _lid);
                if ($datas['total'] > 0) {
                    $tempData = $datas['datas'][0];
                    $billNo = $tempData['bill_no'];
                }
                else {
                    die('error bill no');
                }
            }
        }
        else if ($logisticNo) {
            $logisticNo = str_replace(array('ZZZZ'), array('_'), $logisticNo);
            $scan = 1;
            
            if (substr($logisticNo, 0, 3) == 'OID') {
                $outstockID = substr($logisticNo, 3, strlen($logisticNo));
                $datas = $outstockAPI -> search(array('outstock_id' => $outstockID), 'send-list', 1, $this -> _lid);
                if ($datas['total'] > 0) {
                    $tempData = $datas['datas'][0];
                    $logisticNo = $tempData['bill_no'];
                }
                else {
                    die('error bill no');
                }
            }
        }
        else {
            exit;
        }
        
        if ($scan == 1) {
            $datas = $outstockAPI -> search(array('logistic_no' => $logisticNo), 'send-list', 1, $this -> _lid);
            if ($datas['total'] > 0) {
                $tempData = $datas['datas'][0];
                $billNo = $tempData['bill_no'];
            }

            if (!$billNo) {
                $billNo = $logisticNo;
                $billNo = 'b'.$billNo;
            }
        }
        
        $datas = $outstockAPI -> search(array('no' => $billNo), 'send-list', 1, $this -> _lid);
        if ($datas['total'] > 0) {
            $data = $datas['datas'][0];
            $data['shop_name'] = '';
            if ($data['bill_type'] == 1) {
                if (!$data['logistic_no']) {
                    die('error empty logistic no');
                }
            }
            if ($data['scan'] != $scan) {
                $scan == 0 ? die('error scan status1') : die('error scan status2');
            }
            $data['logistic_no'] = $data['logistic_no'] ? $data['logistic_no'] : '';
        }
        
        if (!$data) {
            die('error bill no');
        }
        
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $billTypeList = $stockConfig -> getConfigOutType();
        $data['bill_type'] = $billTypeList[$data['bill_type']];
        $data['add_time'] = date('Y-m-d');
        $result['data'] = $data;
        if (is_array($billNo)) {
            $result['detail'] = array();
            foreach ($billNo as $no) {
                $result['detail'] = array_merge($result['detail'], $outstockAPI -> getDetail("b.bill_no = '{$no}'"));
            }
        }
        else {
            $result['detail'] = $outstockAPI -> getDetail("b.bill_no = '{$billNo}'");
        }
        
        echo Zend_Json::encode($result);
        
        exit;
    }
    
     /**
     * 出库单扫描状态
     *
     * @return   void
     */
    public function scanBillStatusAction()
    {
        $billNo = $this -> _request -> getParam('bill_no');
        if (!$billNo)   exit;
        
        $outstockAPI = new Admin_Models_API_OutStock();
        
        $billNo = str_replace(array('ZZZZ'), array('_'), $billNo);
        if (substr($billNo, 0, 3) == 'OID') {
            $outstockID = substr($billNo, 3, strlen($billNo));
            $datas = $outstockAPI -> search(array('outstock_id' => $outstockID), 'send-list', 1, $this -> _lid);
            if ($datas['total'] > 0) {
                $tempData = $datas['datas'][0];
                $billNo = $tempData['bill_no'];
            }
            else {
                 die('error bill no');
            }
        }
        else {
            $billNo = 'b'.$billNo;
        }
        
        $datas = $outstockAPI -> search(array('no' => $billNo), 'send-list', 1, $this -> _lid);
        if ($datas['total'] > 0) {
            $data = $datas['datas'][0];
        }
        
        if (!$data) {
            die('error bill no');
        }
        
        $outstockAPI -> update(array('scan' => 1), "outstock_id = '{$data['outstock_id']}'");
        
        exit;
    }
    
    /**
     * 发货扫描的单据号
     *
     * @return   void
     */
    public function sendScanBillAction()
    {
        $billNo = $this -> _request -> getParam('bill_no');
        $weight = $this -> _request -> getParam('weight', 0);
        if (!$billNo)   exit;
        
        $outstockAPI = new Admin_Models_API_OutStock();
        
        if (substr($billNo, 0, 3) == 'OID') {
            $outstockID = substr($billNo, 3, strlen($billNo));
            $datas = $outstockAPI -> search(array('outstock_id' => $outstockID), 'send-list', 1, $this -> _lid);
            if ($datas['total'] > 0) {
                $tempData = $datas['datas'][0];
                $billNo = $tempData['bill_no'];
            }
            else {
                die('error bill no');
            }
        }
        
        $datas = $outstockAPI -> search(array('no' => $billNo), 'send-list', 1, $this -> _lid);
        if ($datas['total'] > 0) {
            if (!$outstockAPI -> sendByBillNo($billNo, $weight)) {
                die('error send');
            }
            
            $data = $datas['datas'][0];
        }
        
        if ($data) {
            if ($data[0]) {
                foreach ($data as $temp) {
                    $outstockAPI -> update(array('weight' => $weight), "outstock_id = '{$temp['outstock_id']}'");
                }
            }
            else {
                $outstockAPI -> update(array('weight' => $weight), "outstock_id = '{$data['outstock_id']}'");
            }
        }
        else {
            die('error bill no');
        }
        
        exit;
    }
    
    /**
     * 生成条码
     *
     * @return   void
     */
    public function barcodeAction()
    {
        $no = $this -> _request -> getParam('no');
        $c = substr($no, 0, 1);
        if (ctype_alpha(substr($no, 0, 1))) {
            $no = substr($no, 1, strlen($no));
        }
        $api = new Custom_Model_Barcode(15, 35);
        $api -> makecode($no);
    }
    
    /**
     * 重新生成transport_source记录(目前只处理合单)
     *
     * @return   void
     */
    public function reCreateRelationRecordAction()
    {
        $db = Zend_Registry::get('db');
        
        $db -> delete('shop_transport_source', 1);
        $datas = $db -> fetchAll("select t1.tid,t1.bill_no,t2.outstock_id from shop_transport as t1 inner join shop_outstock as t2 on t1.bill_no = t2.bill_no where t1.bill_type = 1");
        foreach ($datas as $data) {
            $billNoArray = explode(',', $data['bill_no']);
            foreach ($billNoArray as $billNo) {
                $result[$billNo]['transport_id'] = $data['tid'];
                $result[$billNo]['outstock_id'] = $data['outstock_id'];
            }
        }
        
        foreach ($result as $billNo => $data) {
            $row = array('bill_no' => $billNo,
                         'transport_id' => $data['transport_id'],
                         'outstock_id' => $data['outstock_id'],
                        );
            $db -> insert('shop_transport_source', $row);
        }
        
        exit;
    }
    
    public function splitSelfCallOrderAction()
    {
        $db = Zend_Registry::get('db');
        
        $sql = "select t1.bill_no,t1.transport_id,t3.type from shop_transport_source as t1
                inner join 
                (select t1.transport_id,count(*) as number from shop_transport_source as t1
                inner join shop_transport as t2 on t1.transport_id = t2.tid
                group by t1.transport_id
                having number > 1) as t2
                on t1.transport_id = t2.transport_id
                inner join shop_order_batch as t3 on t1.bill_no = t3.batch_sn";
        $datas = $db -> fetchAll($sql);
        foreach ($datas as $data) {
            $order[$data['transport_id']][] = $data;
        }
        
        foreach ($order as $tid => $datas) {
            $type = '';
            $diff = false;
            foreach ($datas as $data) {
                if ($type === '') {
                    $type = $data['type'];
                }
                else {
                    if ($type != $data['type']) {
                        $diff = true;
                        break;
                    }
                }
            }
            if ($diff) {
                foreach ($datas as $data) {
                    echo $data['bill_no'].' ';
                }
                echo '<br>';
            }
        }
        
        exit;
    }

}