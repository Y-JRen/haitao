<?php
class IndexController extends Zend_Controller_Action
{
	protected $_apiGoods;
	protected $_authUser;
	private $_adv_api;

	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
        $auth = Shop_Models_API_Auth :: getInstance();
		$this -> _authUser = $auth -> getAuth();
        $this -> _apiGoods = new Shop_Models_API_Goods();
	}
		
	/**
     * 首页
     *
     * @return void
     */
	public function indexAction()
	{
		//商城首页评论
		$msgAPI = new Shop_Models_API_Msg();
		$this -> view -> comlist = $msgAPI->getCommByIndex(10);

		$this -> goodsApi = new Shop_Models_API_Goods();
		$pageApi = new Shop_Models_API_Page();
		$indextag = $this -> goodsApi ->getGoodsTag('tag_id in ("1")');
		$this->view->index_tag1 = $indextag['1']['details']; 
		$this->view->index_tag2 = $this-> goodsApi -> getRandGoods(8);
		/*
		//地区搜索
		$this -> view -> region = $this -> goodsApi -> getRegionList();*/
		//网站公告
		$this->view->noticeInfo = $pageApi->getArtByCat(65,3);
		//常见问题
		$this->view->noticeInfo2 = $pageApi->getArtByCat(66,3);
		
		$this->view->is_index_page = true;		
        $this->view->css_more=',home.css';

        $this -> view -> page_title = "国人海淘网-中国医药集团旗下唯一的海外网购平台,100%正品保证,低税,免税商品 尽在国人海淘网!";
        $this -> view -> page_keyword = "国人海淘网,海淘网站,海外商品代购,免税商品,海外购物网站";
        $this -> view -> page_description = '国人海淘网是中国医药集团旗下唯一的海淘网站,专业提供海外一线品牌电器,服装配件,世界奢侈品牌手表,进口营养保健品,居家百货等系列产品,国企单位,100%正品保证,订购热线:400-603-3883 ';
	}

	/**
     * 浏览记录
     *
     * @return void
     */
	function historyGoodsAction()
	{
	    $data = $this -> _apiGoods -> getHistory();
	    if ( $data ) {
	        for ( $i = 0; $i < count($data); $i++ ) {
	            if ($i>2)   break;
	            $alldata[] = $data[$i];
	        }
	        $this -> view -> historydatas = $alldata;
	    }
	    else    $this -> view -> isEmpty = 1;
	    echo $this->view->render('_library/history-goods.tpl');
        exit;
	}

	/**
     * 清空浏览记录
     *
     * @return void
     */
	function emptyHistoryGoodsAction()
	{
	    $this -> _apiGoods -> emptyHistory();
	    $this -> historyGoodsAction();
	}


	/**
     * 我的收藏
     *
     * @return void
     */
    public function favoriteAction()
    {
		$this -> _apiMember = new Shop_Models_API_Member();
		$this -> view -> user = $this -> _authUser;
		if ( $this -> _authUser ) {
    		$favorite = $this -> _apiMember -> getFavorite(1, 3);
    		$this -> view -> datas = $favorite['info'];
		}
		echo $this->view->render('_library/favorite.tpl');
        exit;
    }
    /**
     * 我的购物车
     *
     * @return void
     */
    public function cartAction()
    {
    	$cart = $_COOKIE['cart'];
        $this -> _apiCart = new Shop_Models_API_Cart();
        $data = $this -> _apiCart -> getCartProduct();
        $this -> view -> data = $data['data'];
        $this -> view -> number = $data['number'];
        $this -> view -> shownum = $data['cookie_num'];
        $this -> view -> amount = $data['amount'];
        $type =  $this -> _request -> getParam('type', 'top');
        $html =  $this->view->render('_library/cart_tips.tpl');
      
        echo Zend_Json::encode(array('status'=>1,'html'=>$html,'number'=>$data['number']));
        exit;        
    }
    /**
     * 获得购物车商品数量
     *
     * @return void
     */
    public function getCartGoodsNumberAction()
    {
    	$this -> _apiCart = new Shop_Models_API_Cart();
    	$cart = $this -> _apiCart -> makeCartGoodsToArray();
	    if ( count($cart) > 0 ) {
            $_COOKIE['cart'] = $this -> _apiCart -> makeCartGoodsToString($cart);
            $data = $this -> _apiCart -> getCartProduct($cart);
	    }
	    echo $data['number'] ? $data['number'] : 0;
	    exit;
    }
    /**
     * 删除购物车商品
     *
     * @return void
     */
    public function delCartGoodsAction()
    {
        $this -> _apiCart = new Shop_Models_API_Cart();
        $cart = $this -> _apiCart -> makeCartGoodsToArray();
        $productID = intval($this -> _request -> getParam('product_id', null));
        $number = intval($this -> _request -> getParam('number', 0));
        $this -> _apiCart -> del($productID, $number);
        if ($_SESSION['Card']) {
           unset($_SESSION['Card']);
        }
        if ( $cart ) {
            foreach ( $cart as $product_id => $produce_number) {
                if ( ($product_id == $productID) ) {
                    $cart[$product_id] -= $number;
                    if ( $cart[$product_id] <= 0 ) {
                        unset($cart[$product_id]);
                    }
                }
            }
        }
        $data = $this -> _apiCart -> getCartProduct($cart);

        //start:删除gift中的赠品
        $tmp = $_COOKIE['gift'];
        if($tmp!==NULL){
	        $tmp = explode(',', $tmp);
	        foreach ($tmp as $k => $v){
	        	if(strpos($v, $productID."_")>-1){
	        		unset($tmp[$k]);
	        	}
	        }
	        setcookie('gift', implode(',', $tmp), time () + 86400 * 365, '/');
        }
        //end:删除gift中的赠品
        $this -> view -> data = $data['data'];
        $this -> view -> number = $data['number'] ? $data['number'] : 0;
        $this -> view -> amount = $data['amount'] ? $data['amount'] : 0;
        echo $this->view->render('_library/cart.tpl');
        echo ']::[';
        echo $data['number'] ? $data['number'] : 0;
        exit;
    }
    
    public function testAction()
    {
    	$str = 'Hello';
    	switch ($str){
    		case dddd:
    			echo 'dddd';
    			break;
    		case Helloe:
    			echo hellow;
    			break;
    	}
    	$arr = array(dd=>ddd,cc=>ccc);
    	
    	echo $arr[dd].'<br>';
    	$str = '2014-05-16 12:23:23';
    	echo strtotime($str).'<br>';
    	echo mktime();
    	//echo $x;
    	exit;
    }
}