<?php
 class Shop_Models_API_Brand
 {
 	/**
     * 试用 DB
     *
     * @var Shop_Models_DB_Brand
     */
	private $_db = null;

 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
        $this -> _db = new Shop_Models_DB_Brand();
    }


	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    if ($where['brand_name']) {
		        $wheresql = " AND brand_name like '%{$where['brand_name']}%'";
		    }		   
			if ($where['region']) {
		        $wheresql = " AND region like '%{$where['region']}%'";
		    }
		}
		else {
		    $wheresql = $where;
		}

        return  $this -> _db -> fetch($wheresql, $fields, $orderBy, $page, $pageSize);

	}

	/**
     * 获取进口品牌数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getImported($type)
	{
		if($type == '1'){
			//进口
			 $where = " AND region !='中国' AND region !='台湾' AND region !='香港' ";
		}else{
			//国产
			$where = " AND (region ='中国' OR region ='台湾' OR region ='香港' ) ";
		}

        $list =  $this -> _db -> fetch('AND region IS NOT NULL '.$where. ' GROUP BY region ', ' region,count(brand_id) as number ', ' number DESC');
        foreach($list as $key =>$var){
			if($var['number'] > 1 ){
				$common[]="'".$var['region']."'";
				$commonlist[]=$var['region'];
			}else{
				$other[]="'".$var['region']."'";
			}
		}
		
		if($other){
          $result['other'] = $this -> _db -> fetch(' AND status =0 AND region IS NOT NULL  AND region IN ('.implode(',',$other).') ',' brand_name,brand_id,small_logo,region,as_name,status');
		}
		
		if($common)
		{
          $brand['common'] = $this -> _db -> fetch(' AND status =0 AND region IS NOT NULL  AND region IN ('.implode(',',$common).') ',' brand_name,brand_id,small_logo,region,as_name,status');
		}
		
		
		if($commonlist)
		{
			foreach($commonlist as $k =>$v){
				foreach($brand['common'] as $kk =>$vv){
					if($v==$vv['region'] ){
						$result['main'][$v][] = $vv;
					}
				}
			}
		}
		return $result;
	}


    /**
     * 根据asName 查询品牌
     */
	public function getBrandByAsName($asName){
		return $this->_db->getBrandByAsName($asName);
	}
	/**
	 * 根据分类Id获取分类
	 */
	public function getCateById($cid){
		return $this->_db->getCateById($cid);
	}
	/**
	 * 根据分类ids 获取分类List
	 */
	 public function getCateListByIds($ids,$num){
	 	return $this->_db->getCateListByIds($ids,$num);
	 }
	 /**
	  * 根据Config获取推荐商品
	  */
	 public function getRecommandByConfig($config,$num){
	 	return $this->_db->getRecommandByConfig($config,$num);
	 }
	 /**
     * 取得品牌下面的商品
     * @param int $user_id
     * @param array $page
     * @param array $where
     */
    public function getGoodsListByBrandIdPage($brand_id,$where='',&$page,$asc,$orderby){
    	$orderby="g.".$orderby." ".$asc;
    	return $this->_db->getGoodsListByBrandIdPage($brand_id,$page,$where,$orderby);
    }
    /**
     * 取得品牌分类下面的商品
     * @param int $user_id
     * @param array $page
     * @param array $where
     */
    public function getGoodsListByBrandIdCateIdPage($brand_id,&$page,$asc,$orderby,$cid){
    	$orderby="g.".$orderby." ".$asc;
    	return $this->_db->getGoodsListByBrandIdCateIdPage($brand_id,$page,null,$orderby,$cid);
    }
    /**
     * 根据Goods_id集合获取goods
     */
     public function getGoodsByGoodsIds($goods_ids){
     	return $this->_db->getGoodsByGoodsIds($goods_ids);
     }
    /**
     *根据品牌ID获取所有类别和扩展类别
     */
     public function getCateListByBrandId($brand_id){
		$cateList=$this->_db->getCateByBrandId($brand_id);
		$cates = array();
		$goods = array();
		foreach($cateList as $Vo){
			$cates[] = $Vo['view_cat_id'];
			$goods[] = $Vo['goods_id'];
		}
		if($goods){
			$catesIdList=$this->_db->getGoodsListByCateId($goods);
			foreach($catesIdList as $vo){
				$cates[]= $vo['cat_id'];
			}
		}
		$cateAllList=null;
		if($cates){
			$cateAllList=$this->_db->getCateInCateInIds($cates);
		}
	 	return $cateAllList;
     }

     /**
      * 取得品牌城数据
      */
     public function getBrandCityList(){
         $list_brand = $this->_db->getBrandCityList();
         $objNew = new Shop_Models_API_News();

         foreach ($list_brand as $k=>$v){
             $list_adv = $objNew->getAdvyPosition('BPPC'.$v['brand_id'],1);
             $list_brand[$k]['adv'] = $list_adv[0];
         }

         return $list_brand;
     }

 }