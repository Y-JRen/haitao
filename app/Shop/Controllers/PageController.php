<?php
class PageController extends Zend_Controller_Action
{
    /*api*/
    private $pageApi = null;
    /*auth*/
    private $_auth = null;
    
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
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> pageApi = new Shop_Models_API_Page();
		$this -> _auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
		$this->view->css_more=',theme.css';
	}
	/**
     * 热卖
     *
     * @return void
     */
 	public function hotAction(){ 
 		
		



 	}
	/**
     * 新品
     *
     * @return void
     */
 	public function newAction(){ 
 		
		$this -> _api = new Shop_Models_API_Goods();

		$ps = 12;
		$search1 = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
		$search1 = Custom_Model_DeepTreat::filterArray($search1, 'htmlspecialchars');
		$search = $search1;
		$tmp = $this->_api->getNewGoods(100);
		foreach($tmp as $v){
			$arr_all_goods_id[]=$v['goods_id'];
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
		$this -> _api -> _searchFilter('new-goods.html',$filter_cat,'cid','cat_id',$search1);
		$this -> view -> filter_cat = $filter_cat;
		//品牌过滤器
		$this -> _api -> _searchFilter('new-goods.html',$filter_brand,'bid','brand_id',$search1);
		$this -> view -> filter_brand = $filter_brand;
		
		//排序
		$this -> _api -> _modifySort('new-goods.html',$this->_filter_sort,$search1);
		$this -> view -> sortList = $this -> _filter_sort;
		 
		//商品列表
		$this-> view -> goodsData = $this -> _api -> getGoodsDataBySearch($arr_all_goods_id,$search,1,$search['page'],$ps);
		 
		$this->view->keywords = $keywords;
		//人气商品
		$this -> view -> renqi = $this -> _api -> getRenQi();
		//分页
		$pageNav = new Custom_Model_PageNav($goodstotal, $ps,null,"region-search.html");
		$this -> view -> pageNav = $pageNav -> getListPageNavigation();
		$this -> view -> pageNav1 = $pageNav -> getListNavigationSimple();
		 
		//左则分类菜单
		$this -> view -> cat_list = $this -> _api -> getCatNavTree();
		
		$this->view->css_more=',theme.css';
        
        $this -> view -> page_title = '更多海外新品正品不定时更新,正品承诺,海外直邮-国人海淘网';		
		$this -> view -> page_keyword = '名品代购 海外代购 海淘商城 国人海淘网';
		$this -> view -> page_description = '海淘海外一线品牌电器,服装配件,世界奢侈品牌手表,进口营养保健品,居家百货 等系列商品上国人海淘网,100%正品保证,100%海外直邮.更多更新低税,免税商品尽在国人海淘网-国人海淘网';
 	}

	/**
     * 关税
     *
     * @return void
     */
 	public function taxAction(){ 
 		
		
 		$this -> _api = new Shop_Models_API_Goods();
 		
 		$ps = 12;
 		$search1 = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
 		$search1 = Custom_Model_DeepTreat::filterArray($search1, 'htmlspecialchars');
 		$search = $search1;
 		$tmp = $this->_api->getTaxGoods();
 		foreach($tmp as $v){
 			$arr_all_goods_id[]=$v['goods_id'];
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
 		$this -> _api -> _searchFilter('tax.html',$filter_cat,'cid','cat_id',$search1);
 		$this -> view -> filter_cat = $filter_cat;
 		//品牌过滤器
 		$this -> _api -> _searchFilter('tax.html',$filter_brand,'bid','brand_id',$search1);
 		$this -> view -> filter_brand = $filter_brand;
 		
 		//排序
 		$this -> _api -> _modifySort('tax.html',$this->_filter_sort,$search1);
 		$this -> view -> sortList = $this -> _filter_sort;
 			
 		//商品列表
 		$this-> view -> goodsData = $this -> _api -> getGoodsDataBySearch($arr_all_goods_id,$search,1,$search['page'],$ps);
 			
 		$this->view->keywords = $keywords;
 		//人气商品
 		$this -> view -> renqi = $this -> _api -> getRenQi();
 		//分页
 		$pageNav = new Custom_Model_PageNav($goodstotal, $ps,null,"region-search.html");
 		$this -> view -> pageNav = $pageNav -> getListPageNavigation();
 		$this -> view -> pageNav1 = $pageNav -> getListNavigationSimple();
 			
 		//左则分类菜单
 		$this -> view -> cat_list = $this -> _api -> getCatNavTree();
 		
 		$this->view->css_more=',theme.css';

        $this -> view -> page_title = '最划算的低税商品,更多超值海外代购直邮商品-国人海淘网';
		$this -> view -> page_keyword = '低税商品 海外直邮  海外代购 海淘商城 国人海淘网';
		$this -> view -> page_description = '低税,海外直邮,最划算的低税商品,更多超值海外代购直邮商品尽在国人海淘网.低税商品为行邮税税率低于20%的海外商品,上国人海淘网淘低税商品最划算,100%正品直邮-国人海淘网';
 	}

	/**
     * 专题页面
     *
     * @return void
     */
 	public function shopAction(){ 
 		$this -> view -> page_title = 'CNSC免税店开启海外购物之旅,遍布国内众多城市-国人海淘网';
		$this -> view -> page_keyword = '免税店 低税商品 海外代购 海淘商城 国人海淘网';
		$this -> view -> page_description = 'CNSC免税店为国人开启海外购物之旅,更便利的购物空间,更多时尚大牌让国人享受到更高品质的免税购物.CNSC免税实体店遍布国内各大城市,上海免税店,北京免税店,杭州免税店,南京免税店,郑州免税店等-国人海淘网';
 	}

}