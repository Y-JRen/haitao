<?php
class Admin_GoodsController extends Zend_Controller_Action
{
    /**
     * 
     * @var Admin_Models_API_Goods
     */
    private $_api = null;
	const ADD_SUCCESS = '商品添加成功!';
	const EDIT_SUCCESS = '商品编辑成功!';
	const LINK_SUCCESS = '关联商品添加成功!';
	const TAG_SUCCESS = '标签编辑成功!';
	const ATTR_SUCCESS = '添加属性成功!';
	const IMPORT_SUCCESS = '商品资料导入成功!';
	const IMG_SUCCESS = '商品图片保存成功!';
    /**
     * 允许操作的管理员列表
     * @var array
     */
    private $_allowDoList = array ('1');   
     
    private $_lid;

	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _api = new Admin_Models_API_Goods();
        $this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
        $this -> _lid = $this -> _auth['lid'];
	}
	/**
     * 预处理
     *
     * @return   void
     */
	public function postDispatch()
    {
	    $search = $this -> _request -> getParams();	
	    $search['lid'] = $this -> _lid;
        if(!isset($search['is_del'])){
              $search['is_del']='0';
        }
    	$action = $this -> _request -> getActionName();
        if (in_array($action, array('index', 'img-list', 'price-list', 'link-list', 'goods-url-alias'))) {
	        $page = (int)$this -> _request -> getParam('page', 1);
	        $datas = $this -> _api -> get($search,'a.goods_id,goods_name,goods_sn,goods_sort,goods_img,market_price,price,shop_price,fare,tax,onsale,onoff_remark,a.view_cat_id,a.limit_number,is_del',$page,null,$search['orderby']);
	        $total = $this -> _api -> getCount();
	        $this -> view -> datas = $datas;
	        $this -> view -> catSelect = $this -> _cat ->buildSelect(array('name' => 'view_cat_id','selected'=>$search['view_cat_id']));     
	        $this -> view -> param = $this -> _request -> getParams();
	        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
	        $this -> view -> pageNav = $pageNav -> getNavigation();
	        $this -> view -> message = $message;
        }
    }
	/**
     * 商品资料管理
     *
     * @return   void
     */
    public function indexAction()
    {
       
    }
    
	/**
     * 商品图片管理
     *
     * @return   void
     */
    public function imgListAction()
    {

    }
    
	/**
     * 商品价格管理
     *
     * @return   void
     */
    public function priceListAction()
    {
        $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        if(in_array($auth['admin_id'],$this -> _allowDoList)){
           $this -> view -> viewcost = '1';
        }
    }
    
	/**
     * 商品关联管理
     *
     * @return   void
     */
    public function linkListAction()
    {

    }
    
    /**
     * 商品URL别名管理
     *
     * @return   void
     */
    public function goodsUrlAliasAction()
    {
        
    }
   
	/**
     * 商品状态管理
     *
     * @return   void
     */
    public function goodsStatusAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $params = $this -> _request -> getParams();
        $params['lid'] = $this -> _lid;
        $datas = $this -> _api -> get($params,'goods_id,goods_name,goods_sn,goods_sort,goods_img,market_price,price,staff_price,onsale,onsale2,onoff_remark,onoff_remark2,a.view_cat_id',$page);
        
        if ($datas) {
            foreach ($datas as $num => $data) {
            	$datas[$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $datas[$num]['goods_id'], $datas[$num]['onsale']);
            	$datas[$num]['status2'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status2'), $datas[$num]['goods_id'], $datas[$num]['onsale2']);
    	        $datas[$num]['first_char'] = substr($data['goods_sn'], 0, 1);
            	$goodsIDArray[] = $data['goods_id'];
            }
            
            $stockInfo = $this -> _api -> getGoodsStock($goodsIDArray);
            foreach ($datas as $num => $data) {
                $datas[$num]['able_number'] = $stockInfo[$data['goods_id']]['able_number'];
            }
        }
     
        $total = $this -> _api -> getCount();
        $this -> view -> datas = $datas;
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $params;
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
	/**
     * 商品分类
     *
     * @return   void
     */
    public function goodsCatAction()
    {
        if ($this -> _request -> isPost()) {
			$view_cat_id = (int)$this -> _request -> getParam('view_cat_id', null);
			$goods_sn = $this -> _request -> getParam('goods_sn', null);
			$goods_name = $this -> _request -> getParam('goods_name', null);
			if(!$view_cat_id){
				Custom_Model_Message::showMessage('请选择商品分类');
			}
			if(!$goods_name){
				Custom_Model_Message::showMessage('请输入商品名称');
			}
			if (!$this -> _api -> checkNewGoodsSN($goods_sn)) {
			    //Custom_Model_Message::showMessage($this -> _api -> error());
			}
			if (!$this -> _api -> checkNewGoodsName($goods_name)) {
			    Custom_Model_Message::showMessage($this -> _api -> error());
			}
			$goodsID = $this -> _api -> addGoods($view_cat_id, $goods_sn, $goods_name);
			Custom_Model_Message::showMessage('添加成功，请完善商品信息', "/admin/goods/edit/id/{$goodsID}", 1250);
        } else {
            $this -> view -> viewcatSelect = $this -> _cat -> buildProductSelect(array('name' => 'view_cat_id'), 'changeCat(this.value)');
		}
    }
    /**
     * 添加动作
     *
     * @return void
     */
    public function addAction()
    {
        $goods_sn = $this -> _request -> getParam('goods_sn', null);
		$view_cat_id = (int)$this -> _request -> getParam('view_cat_id', null);
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> add($this -> _request -> getPost());
        	if ($result) {
        	    
        	    //更新商品as_name
        	    $this->_api->updateAsnameById($result);
        	    
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
			$view_cat = array_shift($this -> _cat -> get("cat_id=$view_cat_id"));

        	$this -> view -> action = 'add';
			$this -> view -> view_cat = $view_cat;
			$this -> view -> goods_sn = $goods_sn;
        	//多重分类选择
        	$this -> view -> viewcatSelect = $this -> _cat -> buildSelect( array('name' => 'other_cat_id[]' ,'id' => 'other_cat_id'));
        }
    }
    
    /**
     * 编辑动作
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $post = $this -> _request -> getPost();
                $result = $this -> _api -> edit($post, $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $data = array_shift($this -> _api -> get("a.goods_id = '{$id}'", "a.*,d.cat_name as view_cat_name"));
                $this -> view -> action = 'edit';
                $view_cat_row = array_shift($this -> _cat -> get(" cat_id = '{$data[view_cat_id]}'"));
                $this -> view -> data = $data;
                $this -> view -> brand = $this -> _api -> getBrand();
                $categoryAPI = new Admin_Models_API_Category();
                $catAttrData = $categoryAPI -> getAttr("cat_id = '{$data['view_cat_id']}'");
                if ($catAttrData) {
                    $attributeAPI = new Admin_Models_API_Attribute();
                    foreach ($catAttrData as $attrData) {
                        $attr = array_shift($attributeAPI -> get("attr_id = '{$attrData['attr_id']}'"));
                        $tempInfo = array('attr_id' => $attr['attr_id'],
                                          'attr_title' => $attr['attr_title'],
                                         );
                        $tempData = $attributeAPI -> get("attr_id in ({$attrData['attrs']})");
                        foreach ($tempData as $data) {
                            $tempInfo['detail'][] = array('attr_id' => $data['attr_id'],
                                                          'attr_title' => $data['attr_title'],
                                                         );
                        }
                        $attrInfo[] = $tempInfo;
                    }
                    $this -> view -> attrInfo = $attrInfo;
                }
                
                $productData = $this -> _api -> getGoodsProductData(array('goods_id' => $id));
                if ($productData) {
                    $attrIDArray = array();
                    foreach ($productData as $index => $product) {
                        if ($product['attrs']) {
                            $productData[$index]['attrs']  = explode(',', substr($product['attrs'], 1, strlen($product['attrs']) - 2));
                            $attrIDArray = array_merge($attrIDArray, $productData[$index]['attrs']);
                        }
                    }
                    if (count($attrIDArray) > 0) {
                        $attrData = $attributeAPI -> get("attr_id in (".implode(',', $attrIDArray).")");
                        foreach ($attrData as $attr) {
                            $tempAttrInfo[$attr['attr_id']] = $attr['attr_title'];
                        }
                        foreach ($productData as $index1 => $product) {
                            if ($product['attrs']) {
                                foreach ($product['attrs'] as $index2 => $attrID) {
                                    $productData[$index1]['attrs'][$index2] = $tempAttrInfo[$attrID];
                                }
                            }
                        }
                    }
                }
                $this -> view -> productData = $productData;
            }
            $this -> view -> region = $this -> _api -> getRegion();
            $this -> view -> tab = $this -> _request -> getParam('tab', 0);
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    public function calcPriceAction()
    {
    	$params = $this -> _request -> getParams();
    	$tax = $this -> _api ->calc($params['shop_price'], $params['org_tax_price'], $params['unit'], $params['tax_rate']);
    	exit($tax);
    }
    
    
    /**
     * 编辑动作
     *
     * @return void
     */
    public function priceAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $postdata = $this -> _request -> getPost();
                $result = $this -> _api -> updatePrice($postdata, $id);
                if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
                if(in_array($auth['admin_id'],$this -> _allowDoList)){
                   $this -> view -> viewcost = '1';
                }
                $data = array_shift($this -> _api -> get("a.goods_id='$id'", "a.*"));
                $data['price_seg'] = unserialize($data['price_seg']);
                $old_value = array(
							    	'cost' => $data['cost'],
							    	'cost_tax' => $data['cost_tax'],
							    	'invoice_tax_rate' => $data['invoice_tax_rate'],
							    	'market_price' => $data['market_price'],
							    	'price' => $data['price'],
							    	'price_seg' => $data['price_seg'],
							    	);
                $this -> view -> data = $data;
                $this -> view -> old_value = Zend_Json::encode($old_value);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
	/**
	*商品标签管理
	*
	*/
	public function goodsTagAction(){
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas= $this -> _api -> getAllTag($search,$page, 20);
        $this -> view -> taglist =  $datas['list'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	/**
	*添加商品新标签
	*
	*/
	public function addTagAction(){
		if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $title = $this -> _request -> getParam('title', null);
            $tag = $this -> _request -> getParam('tag', null);
            if($title && $tag){
                $chinput = $this -> _api -> checkinput(" title='$title'");
                if($chinput)  {
                     Custom_Model_Message::showMessage('填写有重复！' , 'event', 1250);
                }
            } else{
               Custom_Model_Message::showMessage('信息填写不完整！', 'event', 1250);
            }
            if($this -> _api -> addTag($this -> _request -> getPost())){
                Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250, 'Gurl()');
            } else {
                Custom_Model_Message::showMessage($this -> _api -> error());
            }
        }else{
             $this -> view -> action = 'add-tag';  
             $this -> render('edit-tag');
        }
	}
    /**
     * 单项标签管理
     *
     * @return void
     */
    public function tagAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
		$type = $this -> _request -> getParam('type', 'goods');
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
				$result = $this -> _api -> updateTag($this -> _request -> getPost(),$id,$type);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250, 'Gurl()' );
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
				$tags = $this -> _api -> getTag("tag_id=$id",$type);
                $this -> view -> data = $tags['data'];
				$this->view->type = $type;
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
	/**
     * 商品导出动作
     *
     * @return   void
     */
    public function exportAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $this -> _api -> export($this -> _request -> getParams());
        exit;
    }
    
    /**
     * 删除动作
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $value = (int)$this -> _request -> getParam('value', 0);
        if ($id > 0) {
            $result = $this -> _api -> deleteGoods($id,$value);
            if(!$result) {
        	    exit($this -> _api -> error());
            }
            exit($result);
        } else {
            exit('error!');
        }
    }
    
    /**
     * 删除关联商品
     *
     * @return void
     */
    public function deletelinkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = $this -> _request -> getParam('id', null);
		$type = $this -> _request -> getParam('type', null);
        if ((int)$id > 0) {
            $result = $this -> _api -> deleteLink((int)$id,$type);
			exit($result);
            if(!$result) {
        	    exit($this -> _api -> error());
            }
        } else {
            exit('error!');
        }
    }
    /**
     * 更改状态动作
     *
     * @return void
     */
    public function statusAction()
    {
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	$remark = $this -> _request -> getParam('remark', '');
    	if ($id > 0) {
    		if ($status == 0) {
		        $this -> _helper -> viewRenderer -> setNoRender();
		        if(!$this -> _api -> checkOnsale($id)){
		            Custom_Model_Message::showAlert('商品价格或重量或跨境通ID不正确，无法上架！',false);
		            exit('refresh');
		        }else{
    		        $this -> _api -> changeStatus($id, $status, $remark);
    		        exit('refresh');
		        }
	        }else{
	            if ($this -> _request -> isPost()) {
	            	$this -> _helper -> viewRenderer -> setNoRender();
	            	$this -> _api -> changeStatus($id, $status, $remark);
	            	Custom_Model_Message::showMessage('操作成功', 'event', 1250, "Gurl('refresh')");
	            }
	        }
        }else{
            Custom_Model_Message::showMessage('error!');
        }
    }
    
    /**
     * 更改状态动作
     *
     * @return void
     */
    public function status2Action()
    {
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	$remark = $this -> _request -> getParam('remark', '');
    	if ($id > 0) {
    		if ($status == 0) {
		        $this -> _helper -> viewRenderer -> setNoRender();
		        $this -> _api -> changeStatus2($id, $status, $remark);
		        exit('refresh');
	        }else{
	            if ($this -> _request -> isPost()) {
	            	$this -> _helper -> viewRenderer -> setNoRender();
	            	$this -> _api -> changeStatus2($id, $status, $remark);
	            	Custom_Model_Message::showMessage('操作成功', 'event', 1250, "Gurl('refresh')");
	            }
	        }
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        
        $this -> render('status');
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
        $type = $this -> _request -> getParam('type', null);
        if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, $field, $val, $type);
        } else {
            exit('error!');
        }
    }
    
    /**
     * 选择商品
     *
     * @return void
     */
    public function selAction()
    {
        $job = $this -> _request -> getParam('job', null);
		$tp = $this -> _request -> getParam('type', null);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
		$t=null;

		if ($job&&$job=="search") {
			$search['filter'] = "";
			$datas = $this -> _api -> get($search,'a.goods_id,goods_name,goods_sn,goods_sort,market_price,price,onsale,a.sort_sale,onoff_remark',$page);
			foreach($datas as $var){
				$productIDArray[] = $var['product_id'];
				$goodsProductMap[$var['product_id']] = $var['goods_id'];
			}
			$this -> view -> datas = $datas;
		}else{
			$this -> view -> catSelect = $this -> _cat -> buildSelect(array('name' => 'view_cat_id'));
		}
		$total = $this -> _api -> getCount($t);
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total,null, 'ajax_search_goods');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }

    /**
     * 关联商品
     *
     * @return void
     */
    public function linkAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
		$type=(int)$this -> _request -> getParam('type', null);
        if ($this -> _request -> isPost()) {
            $result = $this -> _api -> addLink($this -> _request -> getPost(), $id,$type);
            if($result){
                Custom_Model_Message::showMessage(self::LINK_SUCCESS, 'event', 1250);
            }else{
            	Custom_Model_Message::showMessage($this -> _api -> error());
            }
        }else{
			$this->view->type=$type;
            $this -> view -> links = $this -> _api -> getLink($id,$type);
        }
    }
   
 
    /**
     * 商品状态修改记录
     * 
     */
    public function statusHistoryAction(){
		$goods_id = (int)$this -> _request -> getParam('goods_id', 0);
    	if (!$goods_id) exit;
    	$this -> view -> goods = $this -> _api -> getOne($goods_id);
    	$history = $this -> _api -> getOp("goods_id = {$goods_id} and (op_type ='onoff' or op_type ='onoff2')");
    	if ($history) {
    	    foreach ($history as $key => $item) {
    	        if ($item['op_type'] == 'onoff') {
    	            $history[$key]['status'] = '官网';
    	        }
    	        else {
    	            $history[$key]['status'] = '内购';
    	        }
    	        if ($item['old_value'] == '0') {
    	            $history[$key]['status'] .= '下架';
    	        }
    	        else {
    	            $history[$key]['status'] .= '上架';
    	        }
    	    }
    	}
    	$this -> view -> history = $history;
    }
    
    /**
     * 商品图片管理
     * 
     */
    public function imgAction(){
        $goods_id = (int)$this -> _request -> getParam('id', 0);
        if (!$goods_id) exit;
        $goods = $this -> _api -> getOne($goods_id);
        $productData = $this -> _api -> getGoodsProductData(array('goods_id' => $goods_id));
        foreach ($productData as $product) {
            $productIDArray[] = $product['product_id'];
        }
        
        if ($this -> _request -> isPost()) {
            $this -> _api -> upimg($this -> _request -> getPost(), $goods_id, $goods['goods_sn']);
            $this -> _api -> updateGoodsImage($goods_id, $this -> _request -> getPost('img_ids'));
            Custom_Model_Message::showMessage(self::IMG_SUCCESS, 'event', 1250, "Gurl('refresh')");
        }
        $this -> view -> data = $goods;
        $product_id = $goods['product_id'];
        
        if ($goods['goods_img_ids']) {
            $tempArr = explode(',', $goods['goods_img_ids']);
            foreach ($tempArr as $img_id) {
                $img_ids[$img_id] = 1;
            }
        }
        if($productIDArray){
        	$productAPI = new Admin_Models_API_Product();
        	$this -> view -> img_url = $productAPI -> getImg("product_id in (".implode(',', $productIDArray).") and img_type=2");
        	$this -> view -> img_ext_url = $productAPI -> getImg("product_id in (".implode(',', $productIDArray).") and img_type=3");
        } 
        
        $this -> view -> img_ids = $img_ids;
        $this -> view -> goods_sn = $goods['goods_sn'];
    }

    /**
     * 编辑器上传图片管理
     * 
     */

    public function uploadImageAction()
    {
		$this -> _helper -> viewRenderer -> setNoRender();
        $save_path = 'upload/kindeditor/';
        $save_url = '/upload/kindeditor/';
        $ext_arr = array(
        	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
        	'flash' => array('swf', 'flv'),
        	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
        	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        
        $max_size = 1000000;
        
        $save_path = realpath($save_path) . '/';
        
        if (!empty($_FILES['imgFile']['error'])) {
        	switch($_FILES['imgFile']['error']){
        		case '1':
        			$error = '超过php.ini允许的大小。';
        			break;
        		case '2':
        			$error = '超过表单允许的大小。';
        			break;
        		case '3':
        			$error = '图片只有部分被上传。';
        			break;
        		case '4':
        			$error = '请选择图片。';
        			break;
        		case '6':
        			$error = '找不到临时目录。';
        			break;
        		case '7':
        			$error = '写文件到硬盘出错。';
        			break;
        		case '8':
        			$error = 'File upload stopped by extension。';
        			break;
        		case '999':
        		default:
        			$error = '未知错误。';
        	}
        	
        	kindEditorAlert($error);
        }
        
        if (empty($_FILES) === false) {
        	$file_name = $_FILES['imgFile']['name'];
        	$tmp_name = $_FILES['imgFile']['tmp_name'];
        	$file_size = $_FILES['imgFile']['size'];
        	if (!$file_name) {
        		kindEditorAlert("请选择文件。");
        	}
        	if (@is_dir($save_path) === false) {
        		kindEditorAlert("上传目录不存在。");
        	}
        	if (@is_writable($save_path) === false) {
        		kindEditorAlert("上传目录没有写权限。");
        	}
        	if (@is_uploaded_file($tmp_name) === false) {
        		kindEditorAlert("上传失败。");
        	}
        	if ($file_size > $max_size) {
        		kindEditorAlert("上传文件大小超过限制。");
        	}
        	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
        	if (empty($ext_arr[$dir_name])) {
        		kindEditorAlert("目录名不正确。");
        	}
        	$temp_arr = explode(".", $file_name);
        	$file_ext = array_pop($temp_arr);
        	$file_ext = trim($file_ext);
        	$file_ext = strtolower($file_ext);
        	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
        		kindEditorAlert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
        	}
        	if ($dir_name !== '') {
        		$save_path .= $dir_name . "/";
        		$save_url .= $dir_name . "/";
        		if (!file_exists($save_path)) {
        			mkdir($save_path);
        		}
        	}
        	$ymd = date("Ymd");
        	$save_path .= $ymd . "/";
        	$save_url .= $ymd . "/";
        	if (!file_exists($save_path)) {
        		mkdir($save_path);
        	}
        	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
        	$file_path = $save_path . $new_file_name;
        	if (move_uploaded_file($tmp_name, $file_path) === false) {
        		kindEditorAlert("上传文件失败。");
        	}
        	@chmod($file_path, 0644);
        	$file_url = $save_url . $new_file_name;
            
        	header('Content-type: text/html; charset=UTF-8');
        	echo Zend_Json::encode(array('error' => 0, 'url' => $file_url));
        	exit;
        }
    }
    
    public function fileManagerAction()
    {
		$this -> _helper -> viewRenderer -> setNoRender();
        $root_path = 'upload/kindeditor/';
        $root_url = '/upload/kindeditor/';
        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
        $dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
        if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
        	echo "Invalid Directory name.";
        	exit;
        }
        if ($dir_name !== '') {
        	$root_path .= $dir_name . "/";
        	$root_url .= $dir_name . "/";
        	if (!file_exists($root_path)) {
        		mkdir($root_path);
        	}
        }
        if (empty($_GET['path'])) {
        	$current_path = realpath($root_path) . '/';
        	$current_url = $root_url;
        	$current_dir_path = '';
        	$moveup_dir_path = '';
        } 
        else {
        	$current_path = realpath($root_path) . '/' . $_GET['path'];
        	$current_url = $root_url . $_GET['path'];
        	$current_dir_path = $_GET['path'];
        	$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
        echo realpath($root_path);
        $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);
        
        if (preg_match('/\.\./', $current_path)) {
        	echo 'Access is not allowed.';
        	exit;
        }
        if (!preg_match('/\/$/', $current_path)) {
        	echo 'Parameter is not valid.';
        	exit;
        }
        if (!file_exists($current_path) || !is_dir($current_path)) {
        	echo 'Directory does not exist.';
        	exit;
        }
        
        $file_list = array();
        if ($handle = opendir($current_path)) {
        	$i = 0;
        	while (false !== ($filename = readdir($handle))) {
        		if ($filename{0} == '.') continue;
        		$file = $current_path . $filename;
        		if (is_dir($file)) {
        			$file_list[$i]['is_dir'] = true;
        			$file_list[$i]['has_file'] = (count(scandir($file)) > 2);
        			$file_list[$i]['filesize'] = 0;
        			$file_list[$i]['is_photo'] = false;
        			$file_list[$i]['filetype'] = '';
        		} else {
        			$file_list[$i]['is_dir'] = false;
        			$file_list[$i]['has_file'] = false;
        			$file_list[$i]['filesize'] = filesize($file);
        			$file_list[$i]['dir_path'] = '';
        			$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        			$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
        			$file_list[$i]['filetype'] = $file_ext;
        		}
        		$file_list[$i]['filename'] = $filename;
        		$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file));
        		$i++;
        	}
        	closedir($handle);
        }
        
        usort($file_list, 'kindEditorCmpFunc');
        
        $result = array();
        $result['moveup_dir_path'] = $moveup_dir_path;
        $result['current_dir_path'] = $current_dir_path;
        $result['current_url'] = $current_url;
        $result['total_count'] = count($file_list);
        $result['file_list'] = $file_list;
        
        header('Content-type: application/json; charset=UTF-8');
        echo Zend_Json::encode($result);
        exit;
    }
    
    /**
     * 获得产品编码前缀(ajax调用)
     *
     * @return void
     */
    function getGoodsPrefixSnAction()
    {
        $catID = (int)$this -> _request -> getParam('catID', 0);
        if (!$catID) {
            exit;
        }
        
        $goodsSN = $this -> _api -> getGoodsPrefixSn($catID);
        if (!$goodsSN) {
            die('error');
        }
        
        die($goodsSN);
    }
    
    /**
     * 编辑商品属性
     *
     * @return   void
     */
    public function attrEditAction()
    {
        $productID = $this -> _request -> getParam('product_id', 0);
        $product = array_shift($this -> _api -> getGoodsProductData(array('product_id' => $productID)));
        if (!$product) {
            die('找不到对应的产品');
        }
        
        if ($this -> _request -> isPost()) {
            $post = $this -> _request -> getPost();
            $productAPI = new Admin_Models_API_Product();
            $post['goods_id'] = $product['goods_id'];
            $result = $productAPI -> editProductAttr($post);
            if ($result == 'ok') {
                Custom_Model_Message::showMessage(self::EDIT_SUCCESS, "/admin/goods/edit/id/{$product['goods_id']}/tab/3", 1250);
            }
            else {
               Custom_Model_Message::showMessage('已存在相同的属性产品'); 
            }
        }
        $categoryAPI = new Admin_Models_API_Category();
        $catAttrData = $categoryAPI -> getAttr("cat_id = '{$product['view_cat_id']}'");
        if (!$catAttrData) {
            die('产品分类没有可选属性');
        }
        
        $attributeAPI = new Admin_Models_API_Attribute();
        foreach ($catAttrData as $attrData) {
            $attr = array_shift($attributeAPI -> get("attr_id = '{$attrData['attr_id']}'"));
            $tempInfo = array('attr_id' => $attr['attr_id'],
                              'attr_title' => $attr['attr_title'],
                             );
            $tempData = $attributeAPI -> get("attr_id in ({$attrData['attrs']})");
            foreach ($tempData as $data) {
                $tempInfo['detail'][] = array('attr_id' => $data['attr_id'],
                                              'attr_title' => $data['attr_title'],
                                             );
            }
            $attrInfo[] = $tempInfo;
        }
        $this -> view -> attrInfo = $attrInfo;

        $attrIDArray = array();
        if ($product['attrs']) {
            $attrIDArray = explode(',', substr($product['attrs'], 1, strlen($product['attrs']) - 2));
            foreach ($attrIDArray as $attrID) {
                $attrIDInfo[$attrID] = 1;
            }
            $tempData = $attributeAPI -> get("attr_id in (".implode(',', $attrIDArray).")");
            foreach ($tempData as $attr) {
                $parentAttrIDInfo[$attr['parent_id']] = 1;
            }
            $this -> view -> attrIDInfo = $attrIDInfo;
            $this -> view -> parentAttrIDInfo = $parentAttrIDInfo;
        }
        
        $this -> view -> product = $product;
    }
}

function kindEditorAlert($msg) 
{
	header('Content-type: text/html; charset=UTF-8');
	echo Zend_Json::encode(array('error' => 1, 'message' => $msg));
	
	exit;
}

function kindEditorCmpFunc($a, $b) 
{
    global $order;
    if ($a['is_dir'] && !$b['is_dir']) {
        return -1;
    } 
    else if (!$a['is_dir'] && $b['is_dir']) {
    	return 1;
    } 
    else {
        if ($order == 'size') {
            if ($a['filesize'] > $b['filesize']) {
        	    return 1;
            }
            else if ($a['filesize'] < $b['filesize']) {
        		return -1;
            } 
            else {
        	    return 0;
            }
        } 
        else if ($order == 'type') {
            return strcmp($a['filetype'], $b['filetype']);
        } 
        else {
            return strcmp($a['filename'], $b['filename']);
        }
    }
}

class XMLmap
{
    public $header = "<\x3Fxml version=\"1.0\" encoding=\"gb2312\"\x3F>\n\t<provider id=\"1\">";
    public $charset = "gb2312";
    public $footer = "\t</provider>\n";
    public $items = array();
    public function __construct() {
        
    }
    function addItem($newItem) {
        if(!is_a($newItem, "XMLmapItem")){
          trigger_error("Can't add a non-XMLmapItem object to the sitemap items array");
        }
        $this->items[] = $newItem;
    }
    function build( $fileName = null ) {
        $map = $this->header . "\n";
        foreach($this->items as $item) {
            $item->id = htmlentities($item->id, ENT_QUOTES);
            $map .= "\t\t<goods>\n\t\t\t<id><![CDATA[$item->id]]></id>\n";
            // cate
            if ( !empty( $item->cate ) ) {
                $map .= "\t\t\t<cate><![CDATA[$item->cate]]></cate>\n";
            }
            // brand
            if ( !empty( $item->brand ) ) {
                $map .= "\t\t\t<brand><![CDATA[$item->brand]]></brand>\n";
            }
            // name
            if ( !empty( $item->name ) ) {
                $map .= "\t\t\t<name><![CDATA[$item->name]]></name>\n";
            }
            // img
            if ( !empty( $item->img ) ) {
                $map .= "\t\t\t<images>\n\t\t\t\t<img><![CDATA[$item->img]]></img>\n\t\t\t</images>\n";
            }
            // marketprice
            if ( !empty( $item->marketprice ) ) {
                $map .= "\t\t\t<marketprice><![CDATA[$item->marketprice]]></marketprice>\n";
            }
            // price
            if ( !empty( $item->price ) ) {
                $map .= "\t\t\t<price><![CDATA[$item->price]]></price>\n";
            }
            // store
            if ( !empty( $item->store ) ) {
                $map .= "\t\t\t<store><![CDATA[$item->store]]></store>\n";
            }
            // url
            if ( !empty( $item->url ) ) {
                $map .= "\t\t\t<url><![CDATA[$item->url]]></url>\n";
            }

            $map .= "\t\t</goods>\n\n";
        }
        $map .= $this->footer . "\n";
		$map  = mb_convert_encoding($map, 'GBK', 'UTF-8'); 
        if (!is_null($fileName)) {
            return file_put_contents($fileName, $map);
        } else {
            return $map;
        }
    }
}
class XMLmapItem
{
    public $id = '';
    public $cate = '';
    public $brand = '';
	public $name = '';
    public $changefreg = '';
    public $marketprice = '';
	public $price = '';
	public $store = '';
	public $url = '';
    public function __construct( $id, $cate = '', $brand = '',$name = '', $img = '', $marketprice = '' ,$price = '' ,$store = '',$url = '') {
        $this->id = $id;
        $this->cate = $cate;
		$this->brand = $brand;
		$this->name = $name;
        $this->img = $img;
        $this->marketprice = $marketprice;
		$this->price = $price;
		$this->store = $store;
		$this->url = $url;
    }
}