<?php
class GoodsController extends Zend_Controller_Action
{
    /**
     *
     * @var Shop_Models_API_Goods
     */
    protected $_api = null;
    protected $_auth = null;
    
   
    private $_filter_sort = array(
    		array(
    				'value'=>0,
    				'sorttype'=>array('0'),
    				'sortname'=>"默认"
    		),
    		array(
    				'value'=> 1,
    				'sorttype'=>array('1', '2'),
    				'sortname'=>'价格'
    		),
    		array(
    				'value'=> 2,
    				'sorttype'=>array('3', '4'),
    				'sortname'=>'税率'
    		),
    		array(
    				'value'=>3,
    				'sorttype'=>array('5', '6'),
    				'sortname'=>'上架时间'
    					
    		),
    );//排序
    
  
    /**
     * 初始化对象
     *
     * @return   void
     */
	public function init()
	{
		$this -> _api = new Shop_Models_API_Goods();
        $this -> _auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
        $this -> view -> auth = $this -> _auth;
		$this->view->css_more=',product.css';
	}

	/**
     * 商品列表页面
     *
     * @return   void
     */
    public function galleryAction()
    {
    	$search1 = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
    	$search1 = Custom_Model_DeepTreat::filterArray($search1, 'htmlspecialchars');
    	
    	$pageSize = 12;
    	$search = $search1;
    	$this -> view -> cat_id = $search['cat_id'] = $search['cat_id'] ? (int)$search['cat_id'] : 0;
    	$this -> view -> brand_id = $search['brand'] = $search['brand'] ? (int)$search['brand'] : 0;
    	$this -> view -> price = $search['price'] = $search['price'] ? (int)$search['price'] : 0;
    	$this -> view -> sort = $search['order'] = $search['sort'] ? (int)$search['sort'] : 0;
    	$search['page'] = $search['page'] <= 0 ? 1 :(int)$search['page'];
    	$goodstotal = $this -> _api -> getGoodsData($search,2);
    	$search['page'] = ceil($goodstotal/$pageSize) < $search['page'] ? ceil($goodstotal/$pageSize) : $search['page'];
    	 
    	//类别树
    	$this -> view -> cat_list = $this -> _api -> getCatNavTree();
    	//已选中的条件
    	$this -> view -> catname = $this -> _api ->getCatNameById($search['cat_id']);
    	if($search['cat_id'] || $search['brand'] || $search['price']){
    		$this -> view -> nav = $this ->_api -> setNav($search);
    	}
    	//类别品牌
    	$temp = $this -> _api -> isCat($search['cat_id']);
    	if($temp == 1){
    		//全部分类
    		$this -> view -> crumbs = "<li><a href='/'><strong>首页</strong></a>></li><li><a href='/gallery-0-0-0-1.html'><strong>全部分类</strong></a></li>";
    		$this -> view -> cat = $this -> _api -> getCat("parent_id <> 0");
    		!$search['brand'] && $this -> view -> brand = $this -> _api -> getBrandList(0);
    		!$search['price'] && $this -> view -> priceList = $this -> _api -> getPriceList();
    	}else if($temp == 3){
    		//小类
    		$catname =  $this -> _api -> getCatParent($search['cat_id']);
    		$this -> view -> crumbs = "<li><a href='/'><strong>首页</strong></a>></li><li><a href='/gallery-{$catname[0][0]}-0-0-0-1.html'><strong>{$catname[0][1]}</strong></a>></li><li><a href='/gallery-{$search['cat_id']}-0-0-0-1.html'>{$catname[1][1]}</a></li>";
    		!$search['brand'] && $this -> view -> brand = $this -> _api -> getBrandByCatId($search['cat_id']);
    		!$search['price'] && $this -> view -> priceList = $this -> _api -> getPriceList();
    		$this -> view -> showCat = $this -> _api -> $catname[0][0];
    	}else{
    		//大类
    		$catname = $this -> _api -> getCatNameById($search['cat_id']);
    		$this -> view -> crumbs = "<li><a href='/'><strong>首页</strong></a>></li><li><a href='/gallery-{$search['cat_id']}-0-0-0-1.html'><strong>{$catname}</strong></a>></li>";
    		$this -> view -> cat = $temp;
    		!$search['brand'] && $this -> view -> brand = $this -> _api -> getBrandList($search['cat_id']);
    		!$search['price'] && $this -> view -> priceList = $this -> _api -> getPriceList();
    		$this -> view -> showCat = $search['cat_id'];
    	}
    	$this -> view -> sortList = $this -> _filter_sort;
    	
    	//商品列表
    	$this-> view -> goodsData = $this -> _api -> getGoodsData($search,1,$search['page'],$pageSize);
    	//人气商品
    	$this -> view -> renqi = $this -> _api -> getRenQi();
    	//分页
    	$pageNav = new Custom_Model_PageNav($goodstotal, $pageSize);
    	$this -> view -> pageNav = $pageNav -> getListPageNavigation();
    	$this -> view -> pageNav1 = $pageNav -> getListNavigationSimple();
    	
    	$cat = is_array($catname) ? $catname[0][1] : $catname;
    	$this -> view -> page_title = "{$cat}-国人海淘网_中国医药集团旗下唯一的海外网购平台,100%正品保证,低税,免税商品 尽在国人海淘网!" ;
    	$this -> view -> page_keyword = $cat ;
    	$this -> view -> page_description = "国人海淘网提供【{$cat}】相关商品，正品保障，请放心选购！" ;
    }
	/**
     * 搜索动作
     *
     * @return   void
     */
    public function searchAction()
    {
    	$ps = 12;
    	$search1 = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
    	$search1 = Custom_Model_DeepTreat::filterArray($search1, 'htmlspecialchars');
    	$keywords = iconv(mb_detect_encoding($search1['keyword'],"UTF-8,GB2312,GBK"),"UTF-8//IGNORE",$search1['keyword']);
    	
    	$search=$search1;
    	$sphinx = new Custom_Model_Sphinx();
    	$rs = $sphinx->getProductResultFromSphinx($keywords, 0, $ps,'haitaogoods');
    	$total = $rs['total'];
    	$arr_word = array();
    	if(!empty($rs['words'])){
    		foreach ($rs['words'] as $k=>$v){
    			$arr_word[] = $k;
    		}
    	}
    	//所有与关键词有关的商品
    	$arr_all_goods_id = array();
    	$arr_all_goods_id[] = 0;
    	$rs = $sphinx->getProductResultFromSphinx($keywords, 0, intval($total),'haitaogoods');
    	if(!empty($rs['matches'])){
    		foreach ($rs['matches'] as $k=>$v){
    			$arr_all_goods_id[] = $v['id'];
    		}
    	}

    	$search['order'] = $search['sort'] ? (int)$search['sort'] : 0;
    	$search['brand'] = $search['bid'] ? (int)$search['bid'] : 0;
    	$search['cat_id'] = $search['cid'] ? (int)$search['cid'] : 0;
    	$search['page'] = empty($search['page']) ? 1 : (int)$search['page'];
    	$goodstotal = $this -> _api -> getGoodsDataBySearch($arr_all_goods_id,$search,2);
    	$search['page'] = ceil($goodstotal/$ps) < $search['page'] ? ceil($goodstotal/$ps) : $search['page'];
    	
    	$filter_cat = $this -> _api -> getCatByGoods($arr_all_goods_id);
    	$filter_brand = $this -> _api -> getBrandByGoods($arr_all_goods_id);
    	array_unshift($filter_cat, array('cat_id'=>0,'cat_name'=>'全部'));
    	array_unshift($filter_brand, array('brand_id'=>0,'brand_name'=>'全部'));
    	//类别过滤器
    	$this -> _api -> _searchFilter('search.html',$filter_cat,'cid','cat_id',$search1);
    	$this -> view -> filter_cat = $filter_cat;
    	//品牌过滤器
    	$this -> _api -> _searchFilter('search.html',$filter_brand,'bid','brand_id',$search1);
    	$this -> view -> filter_brand = $filter_brand;
    	//价格过虑器
    	$this -> _api -> _searchFilter('search.html',$this->_api->_filter_price,'price','price_value',$search1);
    	$this -> view -> filter_price = $this->_api->_filter_price;
		//排序
    	$this -> _api -> _modifySort('search.html',$this->_filter_sort,$search1);
    	$this -> view -> sortList = $this -> _filter_sort;
    	
    	//商品列表
    	$this-> view -> goodsData = $this -> _api -> getGoodsDataBySearch($arr_all_goods_id,$search,1,$search['page'],$ps);
    	
    	$this->view->keywords = $keywords;
    	//人气商品
    	$this -> view -> renqi = $this -> _api -> getRenQi();
    	//分页
    	$pageNav = new Custom_Model_PageNav($goodstotal, $ps,null,"search.html");
    	$this -> view -> pageNav = $pageNav -> getListPageNavigation();
    	$this -> view -> pageNav1 = $pageNav -> getListNavigationSimple();
    	
    	//左则分类菜单
    	$this -> view -> cat_list = $this -> _api -> getCatNavTree();
    	
    	$this -> view -> page_title = "{$keywords}-搜索-国人海淘网" ;
    	$this -> view -> page_keyword = $keywords ;
    	$this -> view -> page_description = "国人海淘网提供【{$keywords}】相关商品，正品保障！货到付款，购物放心！" ;
    }

    /**
     * 商品详情页面
     *
     * @return void
     */
    public function showAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
    	if($id > 0){
    		//商品的产品（规格）
    		$this -> view -> product = $this -> _api -> getGoodsProduct($id);
    		if(!$this -> view -> product)
    			$this -> _helper -> redirector -> gotoUrl(Zend_Controller_Front::getInstance() -> getBaseUrl() );
    		//商品数据
    		$result = $this -> _api -> view($id);
    		$stockAPI = new Admin_Models_API_Goods();
    		$stock = $stockAPI -> getGoodsStock(array($result['goods_id']));
    		$result['able_number'] = $stock[$result['goods_id']]['able_number'];
    		$this -> view -> data = $result;
    		//商品小图
    		if ($result['goods_img_ids'])
    			$this -> view -> imgurl = $this-> _api -> getImg("img_id in ({$result['goods_img_ids']})");
    		//面包屑
    		$this -> view -> nav = $this -> _api -> getGoodsNav($result['cat_path']);
    		//相关品牌
    		$this -> view -> brand = $this -> _api -> getBrandByCatId($result['view_cat_id']);
    		//人气商品
    		$this -> view -> renqi = $this -> _api -> getRenQi();
    		
    	}else{
    		$this -> _helper -> redirector -> gotoUrl(Zend_Controller_Front::getInstance() -> getBaseUrl() . '/error');
    	}
    	$this -> view -> css_more =',product.css';
    	$this -> view -> js_more = ',jquery.jqzoom.js,goods.js';
    	
    	$this -> view -> page_title = $result['meta_title'] ;
    	$this -> view -> page_keyword = $result['meta_keywords'] ;
    	$this -> view -> page_description = $result['meta_description'] ;
    	
    	
    }
    /**
     * 设置商品历史记录
     */
    public function setHistoryGoodsAction()
    {
      $this->view->history = $t = $this->_api->getHistory();
      $html =  $this->view->render('goods/history.tpl');
          	
      $goods_id =  $this->_request->getParam('goods_id');
      if($goods_id)
      {
      	$this->_api->setHistory($goods_id);	      
      }
      echo Zend_Json::encode(array('status'=>1,'msg'=>"请求成功",'html'=>$html));
      exit();
    }    
  
	/**
     * 商品标签单页
     *
     * @return void
     */
	public function labelAction()
	{
	    $pageSize =10;
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$tag_id = (int)$this -> _request -> getParam('id', 1);
		$this -> view -> tag_id = $tag_id ;
		$datas = $this -> _api -> getTag("tag_id=".$tag_id);
		$this -> view -> page_title = $datas['data']['title'];
		$this -> view -> datas = (is_array($datas['details']) && count($datas['details'])) ? array_slice($datas['details'],($page-1)*$pageSize,$pageSize) : $datas['details'];
        $pageNav = new Custom_Model_PageNav($datas['totle'], $pageSize);
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        $this -> view -> cur_place = 'new';
	}

	/**
     * post商品评论
     *
     * @return void
     */
	public function msgAction()
	{
		$this -> _msgAPI = new Shop_Models_API_Msg();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$this -> _helper -> viewRenderer -> setNoRender();
		if ($this -> _request -> isPost()) {
        	$post = $this -> _request -> getPost();
        	$result = $this -> _msgAPI  -> goodsMsg($post);
        	if ($result) {
        	    exit($result);
        	}
        } else {
        	exit('error');
        }
	}

	/**
     * 获得商品原始价格(商品详情页面Ajax调用)
     *
     * @return   array
     */
	public function getPriceAction()
	{
	    $id = (int)$this -> _request -> getParam('id', null);
	    $number = (int)$this -> _request -> getParam('number', null);
	    if ($id && $number) {
	        $goods = $this -> _api -> getGoodsInfo(" and goods_id = {$id}");
	        if ($goods) {
	            echo $this -> _api -> getPrice(unserialize($goods['price_seg']), $goods['price'], $number);
	        }
	    }
	    exit;
	}
    /**
     * 商品评论
     *
     * @return void
     */
	public function commentAction()
	{
	    $conf = $this -> _request -> getParam('conf',0);
	    $this -> _msgAPI = new Shop_Models_API_Msg();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        $id = (int)$this -> _request -> getParam('id', null);
        if($id){
            if(empty($conf)){
                $where = "status=1 and type= 1 and goods_id=$id ";
            }else if($conf == 1){
                $where = "status=1 and type= 1 and  goods_id=$id and cnt1 between 4 AND 5 ";
            }elseif($conf == 2){
                $where = "status=1 and type= 1 and goods_id=$id  and  cnt1 between 2 AND 3 ";
            }elseif($conf == 3){
                $where = "status=1 and type= 1 and goods_id=$id  and cnt1 between 0 AND 1 ";
            }
			$datas = $this -> _msgAPI -> getGoodsMsg($where, '*', $page, 5);
			$cnt = $this -> _msgAPI -> getGoodsCnt($id);
			$total = $this -> _msgAPI -> getCount();
			$this -> view -> cnt = $cnt;
			$this -> view -> id = $id;
			$this -> view -> datas = $datas;
			$this -> view -> total = $total;
			if ($this -> _msgAPI -> checkIp()) {
				$this -> view -> office = true;
			}
			$goods = new Shop_Models_DB_Goods();
			$this -> view -> goods = array_shift($goods -> fetch("goods_id=$id"));
			$pageNav = new Custom_Model_PageNavJS($total, 5);
            $this -> view -> pageNav = $pageNav -> getPageNavigation('getCommentListnew('.$conf.',%%page%%)');
        }
        //取评论信息  getCommByLevel
        $level_data['levelall']= $this->_msgAPI->getCommByLevel("goods_id = $id  ");
        $level_data['levelall']= empty($level_data['levelall'])?1: $level_data['levelall'];
        $level_data['level1']= $this->_msgAPI->getCommByLevel("goods_id = $id AND cnt1 between 4 AND 5 "); //好评
        $level_data['level2']= $this->_msgAPI->getCommByLevel("goods_id = $id AND cnt1 between 2 AND 3 ");//中评
        $level_data['level3']= $this->_msgAPI->getCommByLevel("goods_id = $id AND cnt1 between 0 AND 1 ");//差评

        $level_data_bf['level1']= round( $level_data['level1']/ $level_data['levelall']*100); //好评
        $level_data_bf['level2']= round( $level_data['level2']/ $level_data['levelall']*100);//中评
        $level_data_bf['level3']= round( $level_data['level3']/ $level_data['levelall']*100);//差评
        
        $this->view->level_data=$level_data;
        $this->view->level_data_bf=$level_data_bf;
        $this->view->type = $conf;
	}

    /**
     * 商品咨询
     *
     * @return void
     */
	public function consultationAction()
	{
	    $this -> _msgAPI = new Shop_Models_API_Msg();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        $id = (int)$this -> _request -> getParam('id', null);
        if($id){
			$where = "status=1 and type= 2 and goods_id=$id ";
			$datas = $this -> _msgAPI -> getGoodsMsg($where,'*',$page,5);
			$this -> view -> id = $id;
			$this -> view -> datas = $datas;

			if ($this -> _msgAPI -> checkIp()) {
				$this -> view -> office = true;
			}
            $id = (int)$this -> _request -> getParam('id', null);
            $this -> view -> csl = $this -> _msgAPI -> getCslCount($id);

			$goods = new Shop_Models_DB_Goods();
			$this -> view -> goods = array_shift($goods -> fetch("goods_id=$id"));
        }
        if (!$this -> _auth) {
             $this -> view -> login = 1;
        }
	}

     /**
     * 检查库存和限购数量
     *
     * @return void
     */
    public function checkAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$number = (int)$this -> _request -> getParam('number', 0);
    	$id = (int)$this -> _request -> getParam('id', null);
    	$product_sn = $this -> _request -> getParam('product_sn', null);
    	if ($number && ($product_sn || $id)) {
    	    $stockAPI = new Admin_Models_API_Stock(); 
    	    if ($id) {
    	        $product = array_shift($this -> _api -> getProduct("a.product_id = '{$id}'"));
    	    }
    	    else {
    	        $product = array_shift($this -> _api -> getProduct("a.product_sn = '{$product_sn}'"));
    	    }

    	    if (!$product) {
    	        exit("产品错误");
    	    }
    	    
    	    $id = $product['product_id'];
    	    $goodsID = $product['goods_id'];
    	    $productSN = $product['product_sn'];
    	    
    	    $cartApi = new Shop_Models_API_Cart();
        	$cart = $cartApi -> getCartProduct();
    	    if(!$this -> _request -> getParam('check_cart')){    
        	    if($cart['data']){
            	    foreach($cart['data'] as $v){
            	        if($v['product_id'] == $id || $v['product_sn'] == $product_sn){
            	            $number += $v['number'];
            	        }
            	    }
        	    }
    	    }
    	    
    	    $stockAPI = new Admin_Models_API_Stock();
    	    $stockAPI->setLogicArea($product['lid']);
    	    $stock = $stockAPI -> getSaleProductStock($id,true);
    	    if ($stock['able_number']<1 ) { 
    	    	$stockAPI2 = new Admin_Models_API_Goods();
    	    	$stock2 = $stockAPI2 -> getGoodsStock(array($goodsID));
    	    	if($stock2[$goodsID]['able_number'] < 1){
	    	        /* $goodsDB = new Shop_Models_DB_Goods();
	    	        $goodsDB -> updateStatus(1, $productSN, $goodsID);
	                $log_params = array(
	                    'product_id'  => $product['product_id'],
	                    'need_number' => $number,
	                    'able_number' => $stock['able_number'],
	                    'created_by'  => 'guest',
	                    'created_ts'  => date('Y-m-d H:i:s'),
	                );
	                $stockAPI->addStockRemindLog($log_params); */ 
	                exit("对不起，库存不足!");
    	    	}
    	        exit('对不起，你要的商品库存不足！');
    	    }elseif ($stock['able_number'] < $number) {
    	        $cartApi -> change($id,$stock['able_number']);
    	    	exit("您最多只能买{$stock['able_number']}件商品!");
    	    }else{
    	    	exit('ok');
    	    }
    	    
	    }else {
        	exit('Error!');
        }
        exit;
    }

    /**
     * 获取商品
     *
     * @return void
     */
    public function getProductAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', null);
    	$product_sn = $this -> _request -> getParam('product_sn', null);
    	if ($product_sn || $id) {
	        $where = $id ? "product_id='$id'" : "product_sn='$product_sn'";
	        $r = array_shift($this -> _api -> getProduct($where,"product_id,product_sn,goods_name,market_price,goods_img"));
	        if ($r){
	        	exit(Zend_Json::encode($r));
	        }else{
	        	exit;
	        }
	    }else{
        	exit;
        }
    }
    /**
     * 放商品入暂存架
     *
     * @return void
     */
    public function favoriteAction()
    {
        $goodsId = intval($this -> _request -> getParam('goodsid',0));
        if (!$this -> _auth) {
            $goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/goods/show/id/'.$goodsId);
            $url = "{$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto;
            echo Zend_Json::encode(array('status'=>0,'url'=>$url,'msg'=>'请先登录!'));
            exit();
        } else {
            if ($goodsId) {
                $data = $this -> _api -> checkGoods($goodsId);
                if ($data['goods_id']) {
                    if($this -> _api -> addFavorite($goodsId)){
                    	echo Zend_Json::encode(array('status'=>1,'msg'=>'收藏成功!'));
                    }else{
                    	echo Zend_Json::encode(array('status'=>0,'msg'=>'已收藏，无需重复收藏!'));
                    }
                    exit();
                }else{
                	echo Zend_Json::encode(array('status'=>0,'msg'=>'商品错误'));
                	exit();
                }
            }else{
            	echo Zend_Json::encode(array('status'=>0,'msg'=>'参数错误!'));
            	exit();
            } 
        }
        exit;
    }
    /**
     * 删除暂存架中的商品
     *
     * @return void
     */
    public function delFavoriteAction()
    {
        $favoriteId = intval($this -> _request -> getParam('favorite_id',0));
        if ($favoriteId) {
            $this -> _api -> delFavorite($favoriteId);
        }
        header("Location: {$this -> getFrontController() -> getBaseUrl()}/member/favorite/");
        exit;
    }

	/**
     * ajax 搜索
     *
     */
	public function doAjaxSearchAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$keywords = $this -> _request -> getParam('keywords', null);
    	if($keywords!=''){
    		$list = $this -> _api -> doAjaxSearch($keywords);
			if(is_array($list) && count($list)){
	        	$outStr='<div class="ajaxsearchlist" id="ajaxsearchlist"><ul>';
	        	$str='';
	        	$x=1;
    			foreach ($list as $k=>$v){
    				//下拉>13列表太长了
    				if($x>13){
    					break;
    				}
    				//如果此词没搜索到则不显示
    				if($v[0]>0){
			            $str.='<li onmouseover="this.style.backgroundColor=\'#ffffcc\'" onmouseout="this.style.backgroundColor=\'#ffffff\'" onclick="goSearch('.$x.')" class="clear"><span class="ajaxsearchlistright">'.$v[0].'&nbsp;&nbsp;</span>&nbsp;<span class="ajaxsearchlistleft" id="key'.$x.'">'.$k.'</span></li>';
			            $x++;
			            //goods
	    				if(is_array($v[1]) && count($v[1])){
			            	foreach ($v[1] as $vv){
			            		$str.='<li onmouseover="this.style.backgroundColor=\'#ffffcc\'" onmouseout="this.style.backgroundColor=\'#ffffff\'" onclick="goSearch('.$x.')" class="clear"><span class="ajaxsearchlistright"></span>&nbsp;&nbsp;&nbsp;<span class="ajaxsearchlistleft" style="color:#666666;" id="key'.$x.'">'.$vv.'</span></li>';
			            		$x++;
			            	}
			            }
			            //cat
			            if(is_array($v[2]) && count($v[2])){
			            	foreach ($v[2] as $vvv){
			            		$str.='<li onmouseover="this.style.backgroundColor=\'#ffffcc\'" onmouseout="this.style.backgroundColor=\'#ffffff\'" onclick="goGallery('.$vvv['cat_id'].')" class="clear"><span class="ajaxsearchlistright">'.$vvv['ct'].'&nbsp;&nbsp;</span>&nbsp;<span class="ajaxsearchlistleft" id="key'.$x.'">'.$vvv['cat_name'].'</span></li>';
			            		$x++;
			            	}
			            }
    				}
			    }
			    //如果所有词都没搜索到
			    if($str==''){
			    	$str='<li onmouseover="this.style.backgroundColor=\'#ffffcc\'" onmouseout="this.style.backgroundColor=\'#ffffff\'" onclick="goSearch(1)" class="clear"><span class="ajaxsearchlistright">0&nbsp;&nbsp;</span>&nbsp;<span class="ajaxsearchlistleft" id="key1">'.$keywords.'</span></li>';
			    }
			    $str=$outStr.$str.'</ul></div>';
			    echo $str;
		    }else{
		    	echo '';
		    }
    	}else{
    		echo '';
    	}
    	exit;
    }
    
    /**
     * 新版判断是否为utf-8
     *
     */
    public function isutf8($word){
    	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true){
    		return true;
    	}else{
    		return false;
    	}
    }
    
   
    
    public function regionSearchAction()
    {
    	$params = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
    	$params = Custom_Model_DeepTreat::filterArray($params, 'htmlspecialchars');
    	$params['id'] = $params['id'] ? (int)$params['id'] : null;
    	$params['page'] = $params['page'] ? (int)$params['page'] : 1;
    	$params['price'] = $params['price'] ? (int)$params['price'] : 0;
    	$params['order'] = $params['sort'] ? (int)$params['sort'] : 0;
    	$this -> view -> id = $params['id'];
    	$this -> view -> sort = $params['order'];
    	$this -> view -> price = $params['price'];
    	$this -> view -> keywords = $this -> _api -> getBrandById($params['id']);
    	$pageSize = 12;
    	
    	$total = $this -> _api -> getGoodsByBrand($params,2,null,$pageSize);
    	$params['page'] = ceil($total/$pageSize) < $params['page'] ? ceil($total/$pageSize) : $params['page'];
    	$this -> view -> goodsData = $data = $this -> _api -> getGoodsByBrand($params,1,$params['page'],$pageSize);
    	//价格筛选
    	$this -> view -> filter_price = $this -> _api -> _filter_price;
    	$this -> view -> sortList = $this -> _filter_sort;
    	//左则分类菜单
    	$this -> view -> cat_list = $this -> _api -> getCatNavTree();
    	//人气商品
    	$this -> view -> renqi = $this -> _api -> getRenQi();
    	//分页
    	$pageNav = new Custom_Model_PageNav($total, $pageSize);
    	$this -> view -> pageNav = $pageNav -> getListPageNavigation();
    	$this -> view -> pageNav1 = $pageNav -> getListNavigationSimple();
    }
    
    public function sendNoticeAction()
    {
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $post = $this->_request->getPost();
        if(!$post['goods_id'])
        {
            echo Zend_Json::encode(array('status'=>0,'msg'=>'参数错误！'));
            exit();
        }
    
        if($post['mobile']!='' &&   !Custom_Model_Check::isMobile($post['mobile']) )
        {
            echo Zend_Json::encode(array('status'=>0,'msg'=>'请输入正确格式的手机格式！'));
            exit();
        }
    
        if($post['email']!='' && !Custom_Model_Check::isEmail($post['email']))
        {
            echo Zend_Json::encode(array('status'=>0,'msg'=>'请输入正确格式的邮箱格式！'));
            exit();
        }
    
        $data = array();
        $data['goods_id'] = $post['goods_id'];
    
        $post['mobile'] &&  $data['mobile'] =  $post['mobile'];
        $post['email']  &&  $data['email'] =   $post['email'];
    
        $data['ctreated'] =  time();
        $data['modified'] =  time();
        $res = $this->_api->sendGoodsNotcie($data);
        echo Zend_Json::encode(array('status'=>$res['isok'],'msg'=>$res['msg']));
        exit();
    }
}
