<?php
/**
 * 广告管理
 * @author gaozongbao
 *
 */
class Admin_AdController  extends  Zend_Controller_Action
{
	private $_adTpl;
	private $_adType;
	private $_api;
	public function init()
	{
		$this->_adType = array('image'=>'图片','code'=>'代码', 'flash'=>'动画','text'=>'文字');
		$this->_adTpl = array(
				'index-focus' => array('name'=>'首页焦点图[轮播]','allow_type'=>'image'),
				'slide' => array('name'=>'多图轮播','allow_type'=>'image'),
				'focus'  => array('name'=>'产品焦点图[轮播]','allow_type'=>'image'),
				'curtain'  => array('name'=>'拉幕','allow_type'=>'image'), 
				'banner' => array('name'=>'图片[仅展示]','allow_type'=>'image'),
				'text'   => array('name'=>'文字','allow_type'=>'text'),
				'flash'  => array('name'=>'动画','allow_type'=>'flash')				
				); 
		$this->_api = new Admin_Models_API_Ad();
	}
	/** 
	 * 广告管理
	 */ 
	public  function indexAction()
	{
		$search = $this->_request->getParams();
		$tmp = '';
		if(isset($search['keyword']) && $search['keyword']!='null'){
			$tmp .= " AND name LIKE '%{$search['keyword']}%' " ;
		}
		if($search['start_time_min'] < $search['start_time_max'])
		{  
			$s_time = strtotime($search['start_time_min']);
			$e_time = strtotime($search['start_time_max']);
			$tmp .= " AND start_time between  $s_time AND  $e_time" ;
		}
		if($search['end_time_min'] < $search['end_time_max'])
		{
			$s_time = strtotime($search['end_time_min']);
			$e_time = strtotime($search['end_time_max']);
			$tmp .= " AND end_time between  $s_time AND  $e_time" ;
		}
		if($search['status'])
		{
			$timestamp = time();
			switch ($search['status'])
			{
				case  1 :
					$tmp .= " AND status=1 " ;
					break;
				case  2 :
					$tmp .= " AND status=0 " ;
					break;
				case  3 :					
					$tmp .= " AND status=1 AND start_time<$timestamp AND  end_time>$timestamp" ;
					break;
				case  4 :
					$tmp .= " AND end_time<$timestamp" ;
					break;
				case  5 :
					$tmp .= " AND start_time>$timestamp " ;
				    break;
			}
		}	
		if($search['board_id'])
		{
			$tmp .= " AND board_id = ".intval($search['board_id']);
		} 
		
		if($search['style'])
		{
			$tmp .= " AND type ='{$search['style']}' ";
		}		
		$total = $this->_api->getAdCount($tmp);
		$page = (int)$this->_request->getParam('page', 1);
		$orderby = "board_id ASC, ordid ASC, end_time DESC";
		$data = $this->_api->getAdList($tmp,'*',$orderby,$page);
		if ($data) {
			foreach ($data as $num => $dval)
			{
				$data[$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('ajaxstatus'), $dval['id'], $dval['status']);
			}
		}
		$this->view->data = $data;
		$pageNav = new Custom_Model_PageNav($total);
		$this->view->pageNav=$pageNav->getNavigation();
		
		$adBoard = $this->_api->getAdboardList();
		$adBoardData = array();
		foreach ($adBoard as $val)
		{
			$adBoardData[$val['id']] = $val['name'];
		}
		unset($search['module'],$search['controller'],$search['action']);
		$back_url = '/admin/ad/index?'.http_build_query($search);
		
		$back_url = urlencode($back_url);
		$this->view ->back_url =  $back_url;
		$statusData = array('1'=>'开启','2'=>'关闭','3'=>'进行中','4'=>'已到期','5'=>'未开始');
		$this->view -> statusData  = $statusData ;
		$this->view -> params = $search;
		$this->view->adBoard = $adBoardData;
		$this->view->adtpl = $this->_adTpl;
		$this->view->adType = $this->_adType;
	}	
	/**
	 * 修改字段
	 */
	public  function ajaxChangeAdAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$id = (int)$this->_request->getParam('id', 0);
		$field = $this->_request->getParam('field', null);
		$val = $this->_request->getParam('val', null);
		if ($id > 0) {
			$result = $this->_api->updateField($id, $field, $val);
			switch ($result) {
				case 'forbidden':
					Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
					break;
				case 'error':
					Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
			}
		} else {
			Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
		}
	}

	public function ajaxstatusAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 0);
		$status = (int)$this -> _request -> getParam('status', 0);
		 
		if ($id > 0) {
			$this -> _api -> changeStatus($id, $status);
		}else{
			Custom_Model_Message::showMessage('error!');
		}
		echo $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('ajaxstatus'), $id, $status);
	}
	/**
	 * 添加广告
	 */
	public function addAdAction()
	{
		
		$post = $this->_request->getPost();
		if ($post['submitted']) {
			$data  =  array();
			$data['name'] =  trim($post['name']);
			$data['board_id']  =  trim($post['board_id']);
			$data['type'] =  trim($post['type']);
			$data['url'] =  trim($post['url']);
			$data['extconfig'] = serialize($post['extConfig']);
			$type = $post['type'];
			$data['content'] =  trim($post[$type]);
			$data['extimg'] =  trim($post['extimg']);
			$data['extval'] =  trim($post['extval']);
			$data['desc'] =  trim($post['desc']);
			$data['start_time'] =  strtotime($post['start_time']);
			$data['end_time'] =  strtotime($post['end_time']);
			$data['add_time'] =  time();
			$data['ordid'] = 225;
			$data['status'] =  intval($post['status']);
			$res = $this->_api->AddAd($data);
			if($res['isok']){
				Custom_Model_Message::showMessage($res['msg'],'/admin/ad/index');
			}else{
				Custom_Model_Message::showMessage($res['msg']);
			}
		}
	  $adBoard = $this->_api->getAdboardList();
	  $this->view->adBoard = $adBoard;
	  $this->view->adtpl = $this->_adTpl;
	  $this->view->adType = $this->_adType;
	}
	/**
	 * 编辑广告
	 */
	public function editAdAction()
	{
		$post = $this->_request->getPost();
		if ($post['submitted']) {
			$data  =  array();
			$data['id']  = intval($_POST['id']);
			$data['name'] =  trim($post['name']);
			$data['board_id']  =  trim($post['board_id']);
			$data['type'] =  trim($post['type']);
			$data['url'] =  trim($post['url']);
			$type = $post['type'];
			$data['extconfig'] = serialize($post['extConfig']);
			$data['content'] =  trim($post[$type]);
			$data['extimg'] =  trim($post['extimg']);
			$data['extval'] =  trim($post['extval']);
			$data['desc'] =  trim($post['desc']);
			$data['start_time'] =  strtotime($post['start_time']);
			$data['end_time'] =  strtotime($post['end_time']);
			$data['add_time'] =  time();
			//$data['ordid'] = (int)$post['ordid'];
			$data['status'] =  intval($post['status']);
			$res = $this->_api->editAd($data);
			if($res['isok']){			
				Custom_Model_Message::showMessage($res['msg'],urldecode($post['back_url'])); 
			}else{
				Custom_Model_Message::showMessage($res['msg']);
			}
		}		
		$id = $this->_request->getParam('id', null);
		if(!$id)
		{
			Custom_Model_Message::showMessage('参数错误！','/admin/ad/adboard');
		}
		$this->view->back_url = $this->_request->getParam('back_url', null);
		$adInfo = $this->_api->getAdById($id);
		$adBoard = $this->_api->getAdboardList();
		$this->view->adInfo = $adInfo;
		$this->view->config = unserialize($adInfo['extconfig']);
		$this->view->adBoard = $adBoard;
		$this->view->adtpl = $this->_adTpl;
		$this->view->adType = $this->_adType;
	}
	/**
	 * 删除广告
	 */
	public function delAdAction()
	{
		$id = (int) $this->_request->getParam('id', null);
		$res = $this->_api->delAd($id);
		if($res)
		{
			exit('删除成功');
		}else{
			exit('删除失败');
		}
	}
	/**
	 * 广告位管理
	 */
	public  function  adboardAction()
	{
		$search = $this->_request->getParams();
		$this -> view -> param = $search;
		$tmp = '';
		if(isset($search['name']) && $search['name']!='null'){
			$tmp = "AND name LIKE '%{$search['name']}%'" ;
		}
		$total = $this->_api->getAdboardCount($tmp); 
		$page = (int)$this->_request->getParam('page', 1);
		$data = $this->_api->getAdboardList($tmp,'*',null,$page);
		$this->view->data = $data;
		$pageNav = new Custom_Model_PageNav($total);
		$this->view->pageNav=$pageNav->getNavigation();
	}
	/**
	 * 添加广告位
	 */
    public  function  addAdboardAction()
    {
    	$post = $this->_request->getPost();
    	if ($post['submitted']) {
    		$data  =  array();
    		$data['name'] =  trim($post['name']);
    		$data['tpl']  =  trim($post['tpl']);
    		$data['width'] =  intval($post['width']);
    		$data['height'] =  trim($post['height']);
    		$data['description'] =  trim($post['description']);
    		$data['status'] =  intval($post['status']);
    		$res = $this->_api->addAdboard($data);
    		if($res['isok']){
    			Custom_Model_Message::showMessage($res['msg'],'/admin/ad/adboard');
    		}else{
    			Custom_Model_Message::showMessage($res['msg']);
    		}
    	} 
    	$this->view->tplOptions = $this->_adTpl;
    }
    /**
     * 编辑广告位
     */
    public  function editAdboardAction()
    {
    	$post = $this->_request->getPost();
    	if ($post['submitted']) {
    		$data  =  array();
    		$data['id'] =  intval($post['id']);
    		$data['name'] =  trim($post['name']);
    		$data['tpl']  =  trim($post['tpl']);
    		$data['width'] =  intval($post['width']);
    		$data['height'] =  trim($post['height']);
    		$data['description'] =  trim($post['description']);
    		$data['status'] =  intval($post['status']);
    		$res = $this->_api->editAdboard($data);
    		if($res['isok']){
    			Custom_Model_Message::showMessage($res['msg'],'/admin/ad/adboard');
    		}else{
    			Custom_Model_Message::showMessage($res['msg']);
    		}
    	}
    	$id = $this->_request->getParam('id', null);
    	if(!$id)
    	{
    		Custom_Model_Message::showMessage('参数错误！','/admin/ad/adboard');
    	}
    	$adboardInfo  = $this->_api->getAdboardById($id);
    	$this->view->adboardInfo = $adboardInfo;
    	$this->view->tplOptions = $this->_adTpl;
    }
    /**
     * 移除广告位
     */
    public function delAdboardAction()
    {
    	$id = (int) $this->_request->getParam('id', null);
    	$res = $this->_api->delAdboard($id);
    	if($res)
    	{
    		exit('删除成功');
    	}else{
    		exit('删除失败');
    	}
    }
    /**
     *上传图片
     */
    public function ajaxUploadImgAction() {
    	$type = trim($this->_request->getParam('type'));
    	if (!empty($_FILES[$type]['name'])) {
    		$dir = 'upload/advert/'.date('ym/d/');
    		$result = $this->_upload($type,  $dir );
    		if ($result) {
    			$savename = '/'.$dir. $result[0]['filepath'];
    			$result = array('status' => 1,'msg' => '上传成功','data' => $savename);
    			exit(json_encode($result));    			
    		} else {
    			$result = array('status' => 0,'msg' => '上传失败');
    			exit(json_encode($result));
    		}
    	} else {
    	   $result = array('status' => 0,'msg' => '上传失败');
    	   exit(json_encode($result));
    	}
    }
    /**
     * 上传文件
     */
    protected function _upload($type, $dir = '') {
    	if ($dir) {
    		$upload_path = SYSROOT.'/www/'. $dir ; 
    		$upload->savePath = $upload_path;
    	}    	
    	$upload = new Custom_Model_Upload($type,$upload_path);
	    return  $upload -> up();
    }   
}