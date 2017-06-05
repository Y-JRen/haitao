<?php
class NoticeController extends Zend_Controller_Action {
	
  public function init()
  {
  	$this->_pageApi = new Shop_Models_API_Page();
  	//$this->guessYouLove();
  }
  	  
  public function  indexAction()
  {
  	$page = $this->_request->getParam('page',1);
  	$this -> view -> cat = $cat_id = $this -> _request -> getParam('cat',65);
  	$this->view -> main = $this->_pageApi -> showArtList($page,$cat_id);
  	$this->view->css_more=",page.css";
  }	
  
 public function detailAction()
 {
 	$id = $this->_request->getParam('id'); 
 	if($id>0){
 		$data = $this->_pageApi->getInfo("article_id ={$id}");
 		$article =  array_shift($data);
 		$this->view->info =$article;
  		$this->view->css_more=",page.css";
  		
 	}else{
 	   header("Location:/notice"); exit();
 	}
 
 } 
 
 private  function guessYouLove()
 {

 	$cartApi = new Shop_Models_API_Cart();
 	$cat_products= $cartApi -> getCartProduct();
 	$goodIds = array();
 	if($cat_products['data']){
 		foreach ($cat_products['data'] as $val)
 		{
 			if (intval($val['goods_id'])>0) $goodIds[] = $val['goods_id'];
 		}
 	}
 	$apiGoods = new Shop_Models_API_Goods();
 	$tree_nav_cat = $apiGoods -> getCatNavTree();
 	$this -> view -> tree_nav_cat = $tree_nav_cat;
 	
 	//猜你喜欢
 	if($goodIds && count($goodIds)>0)
 	{
 		$goodsApi  =  new Shop_Models_API_Goods();
 		$gkey =rand(0, count($goodIds)-1);
 		$links = $goodsApi->guessGoods($goodIds[$gkey],$goodIds);
 	}
 	
 	$this -> view -> links = $links;
 }
 
}
