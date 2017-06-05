<?php
class Shop_Models_DB_Brand 
{
	/**
     * page size
     * @var    int
     */
	private $_pageSize = null;

	/**
     * table name
     * @var    string
     */
   private $_table = array(
                           'brand'=>'shop_brand',
                           'goods'=>'shop_goods',
                           'cat'=>'shop_goods_cat',
                           'extend'=>'shop_goods_extend_cat',
                           'product'=>'shop_product'
    );

	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{   
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}


	/**
     * 取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetch($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}

		if ($where != null) {
			$whereSql = "WHERE status =0 AND  small_logo IS NOT NULL $where";
		}

		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy ";
		}else{
			$orderBy = "ORDER BY brand_id ";
		}
		$sql = "SELECT $fields FROM ".$this->_table['brand']." $whereSql $orderBy $limit" ;	
	    
		return $this -> _db -> fetchAll($sql);
	}



    /**
     * 根据asName 查询品牌
     */
	public function getBrandByAsName($asName){
		$t = $this->getRow($this->_table['brand'],array('as_name'=>$asName,'status'=>0));
		return $t;
	}
	/**
	 * 根据分类ID查询分类
	 */
	public function getCateById($cid){
		$t = $this->getRow($this->_table['cat'],array('cat_id'=>$cid));
		return $t;
	}
	 /**
     * 取得品牌下面的商品
     * @param int $user_id
     * @param array $page
     * @param array $where
     */
    public function getGoodsListByBrandIdPage($brand_id,&$page,$where = array(),$order=""){

        $tbl = $this->_table['goods'].' as g|g.goods_id,g.goods_name,g.goods_sn,g.market_price,g.price,g.staff_price,g.goods_img';

        $map = array();
        $map['p.brand_id'] = $brand_id;
        $map['g.onsale'] = 0;
        $map['g.is_del'] = 0;
        $map['g.is_gift'] = 0; 
        if($where['cat_id'])
        {
        	$map['g.view_cat_id'] = $where['cat_id'];
        }
        $links = array();
        $links[$this->_table['product'].' as p'] = 'p.product_id=g.product_id';

        return $this->getListByPage($page, $tbl,$links,$map,$order);

    }
    
     /**
     * 取得品牌分类下面的商品
     * @param int $user_id
     * @param array $page
     * @param array $where
     */
    public function getGoodsListByBrandIdCateIdPage($brand_id,&$page,$where = array(),$order="",$cid){
        $tbl = $this->_table['goods'].' as g|g.goods_id,g.goods_name,g.market_price,g.price,g.goods_img';

        $map = array();
        $map['p.brand_id'] = $brand_id;
        $map['g.onsale'] = 0;
        $map['g.is_del'] = 0;
        $map['g.is_gift'] = 0;

        $goodsIdList=$this->getCateInGoodsIds($cid);
        if($goodsIdList){
            $map['_sql'] = 'g.view_cat_id='.$cid.' or g.goods_id in ('.implode(',',$goodsIdList).')';
        }else{
            $map['g.view_cat_id'] = $cid;
        }

        $links = array();
        $links[$this->_table['product'].' as p'] = 'p.product_id=g.product_id';

        return $this->getListByPage($page, $tbl,$links,$map,$order);
    }
    /**
     * 获取分类By品牌Id
     */
     public function getCateByBrandId($brand_id){
     	$tbl = $this->_table['goods'].' as g|g.goods_id,g.view_cat_id';
     	$map = array();
        $map['p.brand_id'] = $brand_id;
        $map['g.onsale'] = 0;
        $map['g.is_del'] = 0;
        $map['g.is_gift'] = 0;
        $links[$this->_table['product'].' as p'] = 'p.product_id=g.product_id';
        return $this->getAllWithLink($tbl,$links,$map);
     }

     /**
     * 根据Goods_id集合获取goods
     */
     public function getGoodsByGoodsIds($goods_ids){
     	$tbl = $this->_table['goods'].' as g|g.goods_id,g.goods_name,g.market_price,g.price,g.goods_img';
     	$map = array();
        $map['g.goods_id|in'] = $goods_ids;
        $links[$this->_table['product'].' as p'] = 'p.product_id=g.product_id';
        $links[$this->_table['brand'].' as b'] = 'p.brand_id=b.brand_id|b.as_name';
        return $this->getAllWithLink($tbl,$links,$map);
     }
     
     /**
      * 根据 根据分类Id 获取goodsId列表
      */
      public function getCateInGoodsIds($cateId){
      	$where['cat_id']=$cateId;
      	$list_goods = $this -> getAll($this->_table['extend'],$where,'goods_id');
      	$arr_goods_id = array();
      	foreach($list_goods as $k=>$v){
      		$arr_goods_id[] = $v['goods_id'];
      	}

      	return $arr_goods_id;
      }
     /**
      * 根据 产品id集合 查询 扩展分类
      */
      public function getGoodsListByCateId($goodsIds){
      	$where['goods_id|in']=$goodsIds;
      	return $this -> getAll($this->_table['extend'],$where,'cat_id');
      }
      /**
	 * 根据分类ids 获取分类List
	 */
	 public function getCateListByIds($ids,$num){
	 	$where['cat_id|in']=explode(',',$ids);
      	return $this -> getAll($this->_table['cat'],$where,'cat_id,cat_name',$num);
	 }
	 /**
	  * 根据Config获取推荐商品
	  */
	 public function getRecommandByConfig($config,$num){
	 	$where['goods_id|in']=explode(',',$config);
      	return $this -> getAll($this->_table['goods'],$where,'goods_id,goods_name,market_price,price,goods_img',$num);
	 }
      /**
       * 根据 分类Id集合 查询 分类
       */
       public function getCateInCateInIds($cateIds){
       		$where['cat_id|in']=$cateIds;
       		$where['cat_status']=0;
       		$where['display']=1;
      		return $this -> getAll($this->_table['cat'],$where,'cat_id,cat_name');
       }

       /**
        * 取得品牌城数据
        */
       public function getBrandCityList(){
           $list_brand = $this->getAll('shop_brand',array('ispinpaicheng'=>1,'status'=>0),'*',10);
           foreach ($list_brand as $k=>$v){
               if(!empty($v['config'])){
                   $tbl = 'shop_goods as g|g.goods_id,g.goods_name,g.price,g.market_price,g.goods_img';
                   $links = array();
                   $links['shop_product as p'] = 'p.product_id=g.product_id|p.brand_id';
                   $links['shop_brand as b'] = 'b.brand_id=p.brand_id|b.as_name';
                   $list_goods = $this->getAllWithLink($tbl,$links,array('goods_id|in'=>trim($v['config'],',')),4);
                   foreach ($list_goods as $kk=>$vv){
                       $saleoff = ($vv['market_price'] <= 0) ? 0 : ($vv['price']/$vv['market_price'])*10;
                       $list_goods[$kk]['saleoff'] = round($saleoff,1);
                   }
                   $list_brand[$k]['goods'] = $list_goods;
               }
           }

           return $list_brand;
       }
}