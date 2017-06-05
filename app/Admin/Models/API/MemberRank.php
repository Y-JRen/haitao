<?php

class Admin_Models_API_MemberRank
{
	/**
     * 会员等级 DB
     * 
     * @var Admin_Models_DB_MemberRank
     */
	private $_db = null;
	
	/**
     * 商品分类 API
     * 
     * @var Admin_Models_API_Category
     */
	private $_cat = null;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_MemberRank();
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _cat -> cacheCats();
	}
	
	/**
     * 取得所有会员等级
     *
     * @return   array
     */
	public function getAllRank()
	{
		return $this -> _db -> getRank();
	}
	
	/**
     * 取得指定ID的会员等级列表
     *
     * @param    int    $id
     * @return   array
     */
	public function getRankById($id)
	{
		$rank = $this -> _db -> getRank(array('rank_id' => $id));
		
		if (is_array($rank)) {
			return array_shift($rank);
		}
	}
	
	/**
     * 取得指定会员等级名称的会员等级列表
     *
     * @param    string    $name
     * @return   array
     */
	public function getRankByName($name)
	{
		$rank = $this -> _db -> getRank(array('rank_name' => $name));
		
		if (is_array($rank)) {
			return array_shift($rank);
		}
	}
	
	/**
     * 取得最低非特殊会员等级
     *
     * @return   array
     */
	public function getMinRank()
	{
		return $this -> _db -> getMinRank();
	}
	
	/**
     * 添加/编辑会员等级
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function editRank($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($data['rank_name'] == '') {
			return 'noRankName';
		}
		
		$data['discount'] =  trim($data['discount']);
		
		if ($data['catDiscount']) {
			foreach ($data['catDiscount'] as $catId => $discount)
		    {
			    if (trim($discount) > 0) {
			    	$catDiscount[$catId] = trim($discount);
			    }
		    }
		}
		$data['catDiscount'] = $catDiscount;
		
		if ($data['goodsDiscount']) {
			foreach ($data['goodsDiscount'] as $catId => $discount)
		    {
			    if (trim($discount) > 0) {
			    	$goodsDiscount[$catId] = trim($discount);
			    }
		    }
		}
		$data['goodsDiscount'] = $goodsDiscount;
		$data['discount'] = serialize(array('discount' => $data['discount'], 'catDiscount' => $data['catDiscount'], 'goodsDiscount' => $data['goodsDiscount']));
		
		$rank = $this -> getRankByName($data['rank_name']);
		
		if ($id === null) {
			
		    if ($rank) {
			    return 'rankExists';
		    }
		    
		    $result = $this -> _db -> addRank($data);
		} else {
			
			if ($rank && $rank['rank_id'] != $id) {
			    return 'rankExists';
		    }
		    
			$exists = $this -> getRankById($id);
			
			if (!$exists) {
			    return 'rankNoExists';
		    }
		    
			$result = $this -> _db -> updateRank($data, (int)$id);
		}
		
		if (is_numeric($result) && $result >= 0) {
		    return ($id === null) ? 'addRankSucess' : 'editRankSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 删除会员等级
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteRankById($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deleteRank((int)$id);
		    if (is_numeric($result) && $result > 0) {
		        return 'deleteRankSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	
	/**
     * 更改是否显示价格
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function showPrice($id, $showPrice)
	{
		$id = (int)$id;
		if($this -> _db -> showPrice($id, $showPrice) <= 0) {
			exit('failure');
		}
	}
	
	/**
     * 取得会员等级是否显示价格
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxShowPrice($url, $id, $status)
	{
		switch ($status) {
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为显示价格"><u><font color=red>否</font></u></a>';
		       break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为不显示价格"><u>是</u></a>';
		       break;
		}
	}
    
    /**
     * 取得商品列表
     *
     * @param    int    $page
     * @param    array  $where
     * @return   array
     */
	public function getGoods($page, $pageSize = null, $where = null)
	{
		$goods = new Admin_Models_API_Goods();
        $datas = $goods -> get('onsale=0', 'a.goods_id,a.goods_name,a.goods_sort,c.product_sn,a.market_price,a.price,c.cost,a.onsale', $page, $pageSize);
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['onsale'] = ($datas[$num]['onsale']) ? '下架' : '上架';
        }
        return array('content' => $datas, 'total' => $goods -> getCount());
	}
	
	/**
     * 取得特殊折扣商品信息
     *
     * @param    array    $discountGoods
     * @return   array
     */
	public function getDiscountGoods($discountGoods)
	{
		$goods = new Admin_Models_API_Goods();
		$discountGoodsIds = array_keys($discountGoods);
		$whereSql['goods_id'] = implode(',', $discountGoodsIds);
		$datas = $goods -> get($whereSql, 'a.goods_id,a.goods_name,a.goods_sn');
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['discount'] = $discountGoods[$datas[$num]['goods_id']];
        }
        return $datas;
	}
	
	/**
     * 取得商品分类下拉列表
     *
     * @param    array    $data
     * @return   array
     */
    public function buildSelect($data = null)
	{
		return $this -> _cat -> buildProductSelect($data);
	}
	
	/**
     * 更改特殊商品折扣
     *
     * @param    int     $rankId
     * @param    int     $goodsId
     * @param    string  $value
     * @return   void
     */
	public function updateGoodsDiscount($rankId, $goodsId, $value)
	{
		if ($rankId > 0 && $goodsId > 0 && $value) {
		    $result = $this -> _db -> updateGoodsDiscount($rankId, $goodsId, $value);
		    if (is_numeric($result) && $result >= 0) {
		        return 'deleteRankSucess';
		    } else {
			    return 'error';
		    }
		}
	}
}