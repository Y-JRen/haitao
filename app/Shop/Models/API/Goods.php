<?php
class Shop_Models_API_Goods 
{
    /**
     *
     * @var Shop_Models_DB_Goods
     */
	public $_db = null;

    /**
     * 错误信息
     */
	protected $error;

	public $_filter_price = array(
			array(
					'price_value'=>'0',
					'price_name'=>'全部'
			),array(
					'price_value' => '1',
					'code'        => '0_50',
					'price_name'  => '0-50元'
			),array(
					'price_value' => '2',
					'code'        => '50_100',
					'price_name'  => '50-100元'
			),array(
					'price_value' => '3',
					'code'        => '100_300',
					'price_name'  => '100-300元'
			),array(
					'price_value' => '4',
					'code'        => '300_500',
					'price_name'  => '300-500元'
			),array(
					'price_value' => '5',
					'code'        => '500_1000',
					'price_name'  => '500-1000元'
			),array(
					'price_value' => '6',
					'code'        => '1000_2000',
					'price_name'  => '1000-2000元'
			),array(
					'price_value' => '7',
					'code'        => '2000',
					'price_name'  => '2000元以上'
			)
	);//价格过滤器配置
	
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Shop_Models_DB_Goods();
	}

	/**
     * 获取商品数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		$whereSql = "onsale=0 and cat_status=0";
		if (is_array($where)) {
		    $where['filter'] && $whereSql .= $where['filter'];
		    $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    $where['keyword'] && $whereSql .= " and (goods_sn='{$where['keyword']}' or goods_name like '%{$where['keyword']}%'  or cat_name like '%{$where['keyword']}%')";
		    if ($where['sort']) {
	        	switch ($where['sort']){
	        	    case 1:
	        	        $orderBy = "goods_id desc";
	        	    	break;
	        	    case 2:
	        	        $orderBy = "sort_sale desc";
	        	    	break;
	        	    case 3:
	        	        $orderBy = "price asc";
	        	    	break;
	        	    case 4:
	        	        $orderBy = "price desc";
	        	    	break;
                    default:
                        break;
	        	}
	        }
		}else{
			$whereSql .= ' and '.$where;
		}
		$datas = $this -> _db -> get($whereSql, $fields, $page, $pageSize, $orderBy);
        return $datas;
	}




	/**
     * 获取商品基本信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */

	public function getGoodsInfo($where = null, $fields = '*'){
       return $this -> _db -> getGoodsInfo($where, $fields);
    }

	/**
     * 获取商品数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */

	public function getGoodsList($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null){
         return $this -> _db -> get($where, $fields, $page, $pageSize, $orderBy);
    }


	/**
     * 获取属性信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getattribute($where = null)
	{
        return $this -> _db -> getattribute($where);
	}


	/**
     * 获取库存信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getProduct($where = null, $fields = '*', $page = null, $pageSize=null)
	{
        $datas = $this -> _db -> getProduct($where, $fields, $page, $pageSize);
        return $datas;
	}
	/**
     * 获取商品可销售库存
     *
     * @param    string    $where
     * @return   array
     */
	public function getGoodsStock($where)
	{
		$datas = $this -> _db -> getSaleStock($where);
		foreach ($datas as $num => $data)
        {
         	$result[$data['goods_id']] += $data['able_number'];
        }
        return $result;
	}

	/**
     * 获取商品种类信息
     *
     * @param    string    $where
     * @return   array
     */
	public function getProductGoods($where = null, $page = null, $pageSize=null, $orderBy=null)
	{
        $datas = $this -> _db -> get($where, 'a.goods_id,a.goods_name,a.view_cat_id,a.market_price,a.price,a.staff_price,b.cat_path,a.goods_img', $page, $pageSize, $orderBy);
        $total = $this -> _db -> total;
        if ($datas) {
        	foreach ($datas as $key => $goods)
        	{
        		$goodsIds[]= $goods['goods_id'];
        	}
        	foreach ($datas as $key => $value)
        	{
        		$result[$value['goods_id']] = $value;
        	}
        	return array('data' => $result, 'total' => $total);
        }
	}

	/**
     * 获取商品种类信息
     *
     * @param    string    $where
     * @return   array
     */
	public function getCatProductGoods($where = null, $page = null, $pageSize=null, $orderBy=null)
	{
        $datas = $this -> _db -> getCatGoods($where, 'a.goods_id,a.goods_name,a.view_cat_id,a.market_price,a.price,b.cat_path,a.goods_img,a.goods_sn,a.brief,a.goods_alt,c.product_id', $page, $pageSize, $orderBy);
        $total = $this -> _db -> total;
        if ($datas) {
        	foreach ($datas as $key => $value)
        	{
        		$result[$value['product_id']] = $value;
        	}
        	return array('data' => $result, 'total' => $total);
        }
	}
	/**
     * 获取总数
     *
     * @return   int
     */
	public function getCount()
	{
		return $this -> _db -> total;
	}

	/**
     * 构造分类树.
     *
     * @param    array    $deny
     * @param    array    $data
     * @param    int      $parentID
     * @return   array
     */
	public function catTree($deny=null,$data=null,$parentID=1,$where=null)
	{
        static $tree, $step;
        if(!$data){
            $data = $this -> _db -> getCat($where);
        }

        foreach($data as $v){
            if($v['parent_id'] == $parentID){
                $step++;
                $tree[$v['cat_id']] = array('cat_id'=>$v['cat_id'],
                                            'cat_sn'=>$v['cat_sn'],
                                            'cat_name'=>$v['cat_name'],
                                            'parent_id'=>$v['parent_id'],
                                            'cat_path'=>$v['cat_path'],
                                            'cat_sort'=>$v['cat_sort'],
                                            'display'=>$v['display'],
                                            'step'=>$step);
                if(is_array($deny)){
                    foreach($deny as $x){
                        if($x == $v['cat_id'] || strstr($v['cat_path'],','.$x.',')){
                            $tree[$v['cat_id']]['deny'] = 1;
                            break;
                        }
                    }
                }
                if($parentID){
                    $tree[$parentID]['leaf'] = 0;
                }
                $this -> catTree($deny,$data,$v['cat_id']);
                $step--;
            }
        }
        if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
            $tree[$parentID]['leaf'] = 1;
        }
        return $tree;
	}

	/**
     * 获取商品分类
     *
     * @param    string    $where
     * @return   array
     */
	public function getCat($where, $orderby = null)
	{
		return $this -> _db -> getCat($where, $orderby);
	}



	/**
     * 获取商品分类品牌
     *
     * @param    string    $where
     * @return   array
     */
	public function catbrandlist()
	{
		$cat = $this -> _db -> getCat('parent_id = "0"  and  brand_link_ids IS NOT NULL' ,'cat_sort desc');
		$_brandapi = new Shop_Models_API_Brand();
	
        foreach( $cat as $key => $var ){        
		   $cat[$key]['list'] = $this -> _db -> getCat(" parent_id = '{$var['cat_id']}' " ," cat_sort desc ");
		   $arr = array_filter(array_unique(explode(',',$var['brand_link_ids'])));
			if(count($arr) > 1){
				$arr = implode(',', $arr);
				$cat[$key]['brand'] = $_brandapi ->get(" AND brand_id in ({$arr})",' brand_name,brand_id,small_logo,region,as_name');
			}else{
				$brand_id = (int) $var['brand_link_ids'];
				$cat[$key]['brand'] = $_brandapi ->get(" AND brand_id = $brand_id",' brand_name,brand_id,small_logo,region,as_name');
			}
		}
		return $cat;
	}




    /**
     * 获取导航
     *
     * @param    string    $where
     * @param    string    $orderBy
     * @return   array
     */
    public function getNav($cat_id, $top_attr_id)
    {
        global $tree;
        if ( count($tree) > 1 ) {
            foreach ($tree as $id => $value) {
                for ($i = 0; $i < count($value); $i++) {
                    if ($value[$i][0] == $cat_id) {
                        if ( $value[$i][5] ) {
                            $url = $value[$i][5];
                        }
                        else {
                            $url = 'gallery-'.$cat_id.'.html';
                        }
                        return $this -> getNav($id, $top_attr_id).$nav;
                    }
                }
            }
        }
    }


	/**
     * 获取标签信息
     *
     * @param    string    $where
     * @return   array
     */

	public function getTagInfo($where)
	{
      return  $this -> _db -> getTag($where);

	}

	/**
     * 获取多个推荐标签
     *
     * @param    string    $where
     * @return   array
     */

	public function getGoodsTag($where)
	{
		$goodsData = $this -> _db -> getTag($where);
        if(count($goodsData)>0){
             foreach($goodsData as $key=>$var){
                  if ($var['config']){
						if($var['type']=='goods'){
							if($goods_ids){
								 $goods_ids.= ','.$var['config'];
							}else{
								$goods_ids.= $var['config'];
							}
						}elseif($var['type']=='brand'){
							if($brand_ids){
								 $brand_ids.= ','.$var['config'];
							}else{
								 $brand_ids.= $var['config'];
							}
						}
                  }
             }

			if($goods_ids){
				 $goods_ids = implode(',', array_unique( explode(',', $goods_ids))) ;				
				 $goodsdetails = $this ->get("a.goods_id in($goods_ids)", 'a.goods_id,a.view_cat_id,b.cat_path,a.goods_name,a.market_price,a.staff_price,a.goods_sn,a.price,a.goods_img,a.goods_alt,c.brand_name', $page, $pageSize,'find_in_set(a.goods_id,"'.$goods_ids.'")');
			}
			if($brand_ids){
	            $apiBrand=new Shop_Models_API_Brand();
				$branddetails = $apiBrand -> get(" AND brand_id in($brand_ids)", 'brand_id,brand_name,small_logo,as_name');
			}

		   foreach($goodsData as $key=>$var){
			    $result[$var[tag_id]]['tag'] = $var;
				if ($var['config'] && $var['type']=='brand'){
					 foreach($branddetails as $k=>$v){
						 if( in_array($v['brand_id'],explode(',',$var['config'])) ){
							$result[$var[tag_id]]['details'][] = $v;
						 }
					 }
				 }
				 if ($var['config'] && $var['type']=='goods'){
					 foreach($goodsdetails as $k=>$v){
						 if( in_array($v['goods_id'],explode(',',$var['config'])) ){
							$result[$var[tag_id]]['details'][] = $v;
						 }
					 }
				 }
				 $result[$var[tag_id]]['totle'] = count($result[$var[tag_id]]['details']);
			 }


        }
		return $result;
	}

	/**
     * 获取组合商品列表
     *
     * @param    string    $id
     * @return   array
     */
	public function getgroup($where,$fileds='*',$pageSize=null){
	    return $this->_db->fetchgroup(1,$where,$fileds,$pageSize);
	}

	/**
     * 获取单个标签列表
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where, $page=1, $pageSize=null)
	{
		$data = array_shift($this -> _db -> getTag($where));
		if ($data['config']){
			$ids = $data['config'];
			if($data['type'] == 'brand'){
	            $apiBrand=new Shop_Models_API_Brand();
				$result['details'] = $apiBrand -> get("brand_id in($ids)", 'brand_id,brand_name,small_logo,as_name');

			}else{
				$result['details'] = $this ->get("a.goods_id in($ids)", 'a.goods_id,a.view_cat_id,b.cat_path,a.goods_name,a.short_name,a.market_price,a.staff_price,a.goods_sn,a.price,a.goods_img,a.goods_alt', $page, $pageSize,'find_in_set(a.goods_id,"'.$ids.'")');
			}

		}
		$result['totle'] = count($result['details']);
		$result['data'] = $data;
		return $result;
	}

	/**
	 * 取得标签 不包括商品
	 *
	 * @param array $where
	 */
	public function onlyGetTag($where=null) {
		return $this -> _db -> getTag($where);
	}

	/**
     * 获取关联商品
     *
     * @param    int       $goods_id
     * @return   array
     */
	public function getLink($goods_id)
	{
		return $this -> _db -> getLink($goods_id);
	}

	/**
     * 获取图片信息(HT)
     *
     * @param    string   $where
     * @return   array
     */
	public function getImg($where)
	{
		return $this -> _db -> getImg($where);
	}

	/**
     * 获取商品分类信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getGoodsCatList($where)
	{
		return $this -> _db -> getGoodsCatList($where);
	}

	/**
     * 商品浏览(HT)
     *
     * @param    string   $where
     * @return   array
     */
	public function view($id)
	{
	    $datas = $this -> _db -> fetch("a.is_del=0 and a.goods_id='$id' GROUP BY d.goods_id", 'a.*,b.parent_id,b.cat_name,b.cat_path,c.brand_name,c.introduction,e.*,count(d.goods_id) as count');
	    if($datas){
	    	return $datas[0];
	    }else{
	    	header('Location:/');exit;
	    }
	    /*
        if ($datas) {
            $data = $datas[0];
        }else{
            header("Location: /");exit;
        }
        $path = substr($data['cat_path'], 1, -1);
        $place = explode(',', $path);
        $last_goods && $result['last_goods'] = $last_goods[0];
        $next_goods && $result['next_goods'] = $next_goods[0];
        $result['data'] = $data;
        $result['path'] = $path;
        $result['place'] = $place[1];
        return $result;*/
	}

	/**
     * 设置商品浏览历史
     *
     * @param    int       $id
     * @return   array
     */
	public function setHistory($id)
	{

        if ($_COOKIE['history']) {
        	$history = $_COOKIE['history'];

        	$r = explode(',', $history);
        	array_unshift($r, $id);
        	$r = array_slice(array_unique($r),0,5);
        	$str = implode(',', $r);
        }else{
            $str = $id;
        }
        setcookie('history', $str, time () + 86400 * 365, '/');
	}

	/**
     * 获取商品浏览历史
     *
     * @param    int       $id
     * @return   array
     */
	public function getHistory($goodIds=array())
	{
        $datas = array();
		if ($_COOKIE['history']) {
            $ids = array_map('intval',explode(',',$_COOKIE['history'],5));
            if($goodIds){
             $ids  =  array_diff($ids, $goodIds);
            }          
            if ($ids) {  
            	$ids = implode(',', $ids);
                $datas = $this -> _db -> get("a.onsale=0 and a.goods_id in ($ids)  ", "a.goods_id,goods_name,goods_sn,market_price,price,staff_price,goods_img",null,null,'find_in_set(a.goods_id,"'.$ids.'")');
                return $datas;
            } else {
               return array();
            }
		}
        return $datas;
	}

	/**
     * 清空商品浏览历史
     *
     * @param    int       $id
     * @return   array
     */
	public function emptyHistory()
	{
	    if ($_COOKIE['history'])    $_COOKIE['history'] = '';
	}

    /**
     * 取得商品分类信息
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheCat($catId)
    {
	    return $this -> _cacheCat[$catId];
    }

    /**
     * 取得商品分类所有一级子类
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheCats($catId)
    {
	    return $this -> _cacheCats[$catId];
    }

    /**
     * 取得商品分类所有一级父类
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheParentCat($catId)
    {
	    return $this -> _cacheParentCat[$catId];
    }

    /**
     * 取得给定商品分类的所有子类(包括自身)
     *
     * @param   string	$catId	分类ID
     * @return  string	$catId	所有子类,以','分隔
     */
    function getSubCats($catId)
    {
	    $cacheCats = $this -> _cacheCats;
	    if (is_array($cacheCats[$catId])) {
		    foreach($cacheCats[$catId] as $subCatId => $cat)
		    {
			    $catId .= "," . $this -> getSubCats($subCatId);
		    }
	    }
	    return $catId;
    }

	function getSubId($cat_id){
		$list = $this->getAll('shop_goods_cat',array('parent_id'=>$cat_id));
		$arr_id = array();
		foreach ($list as $k => $v) {
			$arr_id[] = $v['cat_id'];
		}
		
		return array_merge(array($cat_id),$arr_id);
	}

    /**
     * 取得给定分类的所有父类(包括自身)
     *
     * @param   string	$catId	分类ID
     * @return  string	$catId	所有父类,以','分隔
     */
    function getParentCats($catId)
    {
	    $cacheParentCat = $this -> _cacheParentCat;
	    if (is_array($cacheParentCat[$catId])) {
    	    foreach ($cacheParentCat[$catId] AS $parentCatId => $cat)
    	    {
			    $catId .= "," . $this -> getParentCats($parentCatId);
		    }
	    }
	    return $catId;
    }

    /**
     * 检查商品有效性
     *
     * @return   array()
     */
    public function checkGoods($goodsId = 0)
    {
        return $this -> _db -> checkGoods($goodsId);
    }
    /**
     * 把商品放入暂存架
     *
     * @param    int    $goodsId
     * @return void
     */
    public function addFavorite($goodsId)
    {
        $user = Shop_Models_API_Auth :: getInstance() -> getAuth();
        if (!$this -> _db -> checkFavorite($goodsId, $user['member_id'])) {
            $data = array('member_id' => $user['member_id'],
                          'user_id' => $user['user_id'],
                          'goods_id' => $goodsId,
                          'add_time' =>time());
             $res =  $this -> _db -> addFavorite($data);
             

             
             return $res;
          
        } else {
            return false;
        }
    }
    /**
     * 删除暂存架的商品
     *
     * @param  int    $favoriteId
     * @return void
     */
    public function delFavorite($favoriteId)
    {
        $user = Shop_Models_API_Auth :: getInstance() -> getAuth();
      
        //$goods_id =  $this->_db->getFavoriteInfo($favoriteId);
        $res =  $this -> _db -> delFavorite($favoriteId, $user['user_id']);
        
        return  $res;
    }

    /**
     * 获得商品基本价格
     *
     * @price_seg   array
     * @org_price   double
     * @number      int
     * @return      array
     */
	public function getPrice($price_seg, $org_price, $number) {
	    if ( $price_seg ) {
		    for ($i = 0; $i < count($price_seg); $i++) {
				if ($price_seg[$i][2]) {
				    if ( ($number >= $price_seg[$i][1]) && ($number <= $price_seg[$i][2]) ) {
				        return sprintf("%1\$.2f", $price_seg[$i][0]);
				    }
			    }
			    else {
				    if ( $number >= $price_seg[$i][1] ) {
				        return sprintf("%1\$.2f", $price_seg[$i][0]);
    			    }
    			}
	        }
	    }
	    return $org_price;
	}
	

	/*Start::搜索*/
	/**
	 * 得到搜索的ids
	 *
	 * @param array $search
	 * @param string $fields
	 * @param int $page
	 * @param int $pageSize
	 * @param string $orderBy
	 *
	 * @return array
	 */
	public function getGoodsIds($where = null, $page=null, $pageSize = null, $orderBy = null){
		if(isset($where['keyword']) && $where['keyword']!=''){
			$words = trim($where['keyword']);
			if(!$words)return null;
			$sourceWords = $words;
			if(isset($_SESSION['searchkeywords']) && isset($_SESSION['splitsearchkeywords']) && ($words == $_SESSION['searchkeywords'])){
				$words = $_SESSION['splitsearchkeywords'];
			}else{
				//存储原始搜索词
				$_SESSION['searchkeywords'] = $words;
				//过滤
				$search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】", "*");
		    	$words = trim(str_replace($search,' ',$words));
		    	if($words==''){return null;}
		    	//关键词长度>2个才执行分词程序
		    	if(mb_strlen($words,'utf8')>2){
			    	/*Start::分词*/
			    	$words = iconv('utf-8', 'gbk', $words);//字符串转换成gbk
			    	include 'Custom/Model/SplitWord.php';
			    	$sp = new SplitWord();
			    	$words = $sp->SplitRMM($words);
			    	$words = iconv('gbk', 'utf-8', $words);//结果转成utf-8
		    	}
		    	//分词存入session，方便程序第二次调用
		    	$_SESSION['splitsearchkeywords'] = $words;
		    	//分词存入cookie，方便js调用
		    	setcookie('searchkeywords', $words, time () + 86400 * 1, '/');
	    		/*End::分词*/
			}
	    	//分割
	    	$words = explode(' ', $words);
			//把原始的词加入
	    	$words[] = $sourceWords;
	    	//冒泡排序
	    	$wl = count($words);
	    	for($i=0;$i<$wl-1;$i++){
	    		for ($j=$i+1;$j<$wl;$j++){
	    			if(mb_strlen($words[$i]) < mb_strlen($words[$j])){
	    				$tmp = $words[$i];
	    				$words[$i] = $words[$j];
	    				$words[$j] = $tmp;
	    			}
	    		}
	    	}
	    	$ids = null;
	    	if(is_array($rs) && count($rs)){
	    		foreach ($rs as $k=>$v){
	    			if(is_array($v) && count($v)){
	    				foreach ($v as $index=>$val){
	    					$ids[] = $val['goods_id'];
	    				}
	    			}
	    		}
	    	}else{
	    		return null;
	    	}
	    	if($ids)$ids = array_unique($ids);
	    	if($ids)foreach ($ids as $k=>$v){$iids[] = $v;}
	    	return array('tot'=>count($iids),'goods'=>$iids,'gtot'=>count($gids));
		}else{
			return null;
		}
	}

	/**
	 * 得到搜索结果
	 *
	 * @param array $ids
	 *
	 * @return array
	 */
	public function getGoods($ids) {
		if(is_array($ids) && count($ids)){
			return $this -> _db -> getGoods($ids);
		}else{
			return false;
		}
	}
	/**
	 * ajax 得到搜索数量
	 *
	 * @param string $where
	 *
	 * @return int
	 */
	public function doAjaxSearch($where = null){
		$words = trim($where);
		if(!$words)return null;
		$sourceWords = $words;
		//过滤
		$search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】", "*");
		$words = trim(str_replace($search,' ',$words));
    	if($words==''){return null;}
    	if(isset($_SESSION['searchkeywords']) && isset($_SESSION['splitsearchkeywords']) && ($words == $_SESSION['searchkeywords'])){
			$words = $_SESSION['splitsearchkeywords'];
		}else{
	    	/*Start::分词*/
	    	$words = iconv('utf-8', 'gbk', $words);//字符串转换成gbk
	    	include 'Custom/Model/SplitWord.php';
	    	$sp = new SplitWord();
	    	$words = $sp->SplitRMM($words);
	    	$words = iconv('gbk', 'utf-8', $words);//结果转成utf-8
    		/*End::分词*/
		}
    	//分割
    	$words = explode(' ', $words);
		//把原始的词加入
    	$words[] = $sourceWords;
    	//冒泡排序
    	$wl = count($words);
    	for($i=0;$i<$wl-1;$i++){
    		for ($j=$i+1;$j<$wl;$j++){
    			if(mb_strlen($words[$i]) < mb_strlen($words[$j])){
    				$tmp = $words[$i];
    				$words[$i] = $words[$j];
    				$words[$j] = $tmp;
    			}
    		}
    	}
    	foreach ($words as $vv){
    		if(strlen($vv)>0){
	    		$whereSql = " a.keywords like '%{$vv}%' ";
	    		$whereS   = " goods_name like '%{$vv}%' ";
	    		$whereCat = " cat_name = '{$vv}' ";
	    		$whereCatLike = " cat_name like '%{$vv}%' ";
    			$counts[$vv] = $this -> _db -> doAjaxSearch($whereSql, $whereS, $whereCat, $whereCatLike);
    		}
    	}

    	return $counts;
	}

	/*End::搜索*/

    private function createTree(&$tree, $cat_arr) {
        if ($cat_arr) {
            $cat_id = $cat_arr[0];
            if ( !is_array($tree[$cat_id]) ) {
                $tree[$cat_id] = array();
            }
            array_shift($cat_arr);
            $this->createTree($tree[$cat_id], $cat_arr);
        }
        else    $tree = 1;
    }
    private function createPriceSeg($min_price, $max_price, $count) {
        $segCount = 6;
        if ( $count > $segCount)    $count = $segCount;

        if ( ($count == 1) || (($max_price - $min_price) < 10) ) {
            $result[] = '0-'.ceil( $max_price / 10) * 10;
        }
        else {
            $seg = ceil( round(($max_price - $min_price) / $count) / 10) * 10;
            $price = 0;
            for ( $i = 1; $i <= $count; $i++ ) {
                $priceSeg = $price;
                if ( $i != $count ) {
                    if ( ($price + $seg) > $max_price ) {
                        $result[] = $priceSeg;
                        break;
                    }
                    $priceSeg .= '-';
                    $price += $seg;
                    if ( $price < $min_price ) {
                        $price = ceil( $min_price / 10) * 10 + $seg;
                    }
                }
                else    $price = '';

                $result[] = $priceSeg.$price;
            }
        }

        for ( $i = 0; $i < count($result); $i++ ) {
            $str .= "'".$result[$i]."',";
        }

        return substr( $str, 0, -1 );
    }

  
    private function getSubCatByAttr($top_cat_id, $attr_id)
    {
        global $tree;

        $result = array();

        if ( !$tree[$top_cat_id] )  return $result;

        $cat_id_arr = array();
        for ( $i = 0; $i < count($tree[$top_cat_id]); $i++ ) {
            $cat_id_arr[] = $tree[$top_cat_id][$i][0];
        }

        $allCatAttrList = $this -> _db ->getAllCatAttr("cat_id in (".implode(',', $cat_id_arr).")");
        if ( !allCatAttrList )  return $result;

        foreach ( $allCatAttrList as $catAttr ) {
            $temparr = explode(',', $catAttr['attrs_sub']);
            if ( in_array($attr_id, $temparr) ) {
                $result[] = $catAttr['cat_id'];
            }
        }

        return $result;
    }
    /**
     * 获得cat的attr
     *
     * @param   integer $attr_id
     * @return  string
     */
     public function getBrandById($brand_id){
          return  $this -> _db ->getBrand(" brand_id='$brand_id'");
     }

     /**
     * 判断是否是保健品或食品
     *
     * @param   integer $productID
     * @param   integer $type   1:保健品 2:食品
     * @return  boolean
     */
    public function isRootCat($productID, $type = 1)
    {
        $where = "t1.product_id = {$productID} and t2.cat_path like '%,{$type},%'";
        if ($this -> _db -> getProductCat($where))  return true;
        return false;
    }

    public function getCatNavTree(){
    	
    	$list = $this->_db->getCatNavTree();
    	//组装两级分类
    	$tree = array();
    	foreach ($list as $k=>$v){
    		if($v['parent_id'] == 0){
    			$tree[$v['cat_id']] = $v;
    		}
    	}
    	
    	foreach($list as $k=>$v){
    		if($v['parent_id'] != 0 && array_key_exists($v['parent_id'], $tree)){
    			$tree[$v['parent_id']]['sub'][]=$v;
    		}
    	}
    	
        return $tree;
    }

    /**
     * 取得类别下的兄弟类别列表
     * @param unknown_type $cat_id
     *
     */
    public function getCatSiblings($cat_id){
        return $this->_db->getCatSiblings($cat_id);
    }

    /**
     * 根据类别ID取得品牌
     */
    public function getBrandByCatId($cat_id){
        return $this->_db->getBrandByCatId($cat_id);
    }

    /**
     * 根据商品取得品牌ID
     */
    public function getBrandByGoods($arr_goods_id){
        if(is_array($arr_goods_id)){
        	$str = "";
        	foreach($arr_goods_id as $v){
        		$str .= $v.',';
        	}
        	$str = substr($str, 0,strlen($str)-1);
        	return $this -> _db -> getBrandByGoods($str);
        }else{
        	return false;
        }
    	
    	
    	
    	
        $tbl = 'shop_goods as g|g.goods_id';
        $links = array(
            'shop_product as p'=>'p.product_id=g.product_id|p.brand_id',
            'shop_brand as b'=>'b.brand_id=p.brand_id|b.brand_name'
        );
        $where = array('g.goods_id|in'=>$arr_goods_id);
        
        $list_goods = $this->getAllWithLink($tbl,$links,$where);
        
        //品牌ID
        $list_brand = array();
        $arr_brand_id = array();
        foreach ($list_goods as $k=>$v){
            if(in_array($v['brand_id'], $arr_brand_id)) continue;
            $arr_brand_id[] = $v['brand_id'];
            $t = array();
            $t['brand_id'] = $v['brand_id'];
            $t['brand_name'] = $v['brand_name'];
            $list_brand[] = $v;
        }
        
        return $list_brand;
        
    }
    
    /**
     * 根据类别ID取得类别名称
     */
    public function getCatNameById($cat_id){
        return $this->_db->getCatNameById($cat_id);
    }
    /**
     * 分页取商品数据
     *
     */
    public function getGoodsByPage($where=array(),$pagesize=32){

        return $this->_db->getGoodsByPage($where,$pagesize);
    }


    /**
     * 取得关联
     * @param unknown_type $id
     * @param unknown_type $limit_type
     */
    public function getRelation($id,$limit_type='single',$type='view',$num=5){
        return $this->_db->getRelation($id,$limit_type,$type,$num);
    }

    public function getGoodsByIds($arr_id){
        if(empty($arr_id)) return array();
        return $this->_db->getGoodsById($arr_id);
    }
    
    /**
     * 猜你喜欢     
     * @param unknown $id
     * @param number $len
     * @return Ambigous <multitype:, unknown>
     */
    function guessGoods($id,$goodIds=array(),$len=5)
    {
    	$datas = $this->getHistory($goodIds);
    	$offset = $len-count($datas);
    	if($offset>0)
    	{
    		$relations  = $this->getRelation($id,'single','view',$offset);
    		if($offset == $len)
    		{
    			$datas = $relations;
    		}else{
    			$datas = array_merge($datas,$relations);
    		}
    	}    	
      return $datas;
    }
    
    public function search(&$page,$arr_goods_id,$params=array(),$fn=0,$ps=20){
        
        $tbl = 'shop_goods as g|g.goods_id,g.goods_name,g.goods_img,g.price,g.market_price';
        $links = array();
        $links['shop_product as p'] = 'p.product_id=g.product_id';
        $links['shop_brand as b'] = 'b.brand_id=p.brand_id|b.as_name';
        $ord = 'FIND_IN_SET(g.goods_id,"'.implode(',',$arr_goods_id).'")';
        $where = array('g.goods_id|in'=>$arr_goods_id,'is_gift_card'=>'0');
        if(!empty($params['bid'])){
            $where['p.brand_id'] = $params['bid']; 
        }
        
        if(!empty($params['price'])){
            $arr_price = explode('_', $params['price']);
            if(count($arr_price)<=1){
                $where['g.price|egt'] = $arr_price[0];
            }else{
                $where['g.price|egt'] = $arr_price[0];
                $where['g.price|elt'] = $arr_price[1];
            }
        }
        return $this->getListByPage($page, $tbl,$links,$where,$ord);
    } 
	

	public function getGoodsAll(){
		$list = array();
		
		$tbl = 'shop_goods as g|g.goods_id,g.goods_sn,g.goods_name';
		
		$map = array();
		$map['g.onsale'] = 0;
		$map['g.is_gift'] = 0;
		$map['g.is_del'] = 0;
		$map['p.p_status'] = 0;

		$links = array();
		$links['shop_product as p'] = 'p.product_id=g.product_id|p.p_status';
		$links['shop_brand as b'] = 'b.brand_id=p.brand_id|b.as_name';
		
		$list = $this->getAllWithLink($tbl,$links,$map);
		
		return $list;
	}


	
	/**
	 * 面包屑
	 * 
	 * @param string  $cat_path 
	 */
	public function getGoodsNav($cat_path)
	{
		$tmp = explode(',', $cat_path);
		$tmp = array_filter($tmp);
		$tmp = implode(",", $tmp);
		$array = $this -> _db -> getNav($tmp);
		return $array;
	}
	
	/**
	 * 获得商品的所有产品
	 * 
	 * @param int  $goods_id
	 */
	public function getGoodsProduct($goods_id)
	{
		return $this -> _db -> getGoodsProduct($goods_id); 
	}
	
	/**
	 * 获得人气商品
	 * 
	 */
	function getRenQi()
	{
		//要显示的数量
		$num = 5;
		//标签id
		$tag_id = 1;
		//获取标签内的商品id
		$config = $this->_db -> getTagConfig($tag_id);
		return $this -> _db -> getRenQi($config,$num);
	}
	
	/**
	 * 分类是大类还是小类
	 * @param unknown_type $cat_id
	 */
	public function isCat($cat_id)
	{
		if($cat_id == 0 ){ //全部分类
			return 1;
		}else{
			$tmp = $this -> _db -> getChildCat($cat_id);
			if(count($tmp) == 0){//小类
				return 3;
			}else{//大类
				return $tmp;
			}
		}
	}
	/**
	 * 获取品牌条件
	 * @param int $cat_id
	 */
	public function getBrandList($cat_id)
	{
		if($cat_id == 0){
			$where = "parent_id <> 0";
		}else{
			$where = "parent_id = {$cat_id}";
		}
		return $this -> _db -> getBrandList($where);
	}
	
	/**
	 * 获取列表商品
	 * @param array $params
	 * @param int $type (1:获得商品数据；2：获得商品数量)
	 * @param int $page
	 * @param int $pageSize
	 * @return multitype:|boolean
	 */
	public function getGoodsData($params,$type=1,$page=null,$pageSize=null)
	{
		if(is_array($params)){
			$tmp = $this -> _filter_price;
			$where = "a.onsale=0 and a.is_del=0 ";
			$order = "";
			if((int)$params['brand']) $where.= " and a.brand_id = {$params['brand']} ";
			if($params['price'] != 0 && $params['price'] != count($tmp)-1){
				$arr = explode('_',$tmp[$params['price']]['code']);
				$where .= " and a.price between {$arr[0]} and {$arr[1]}";
			}else if($params['price'] == count($tmp)-1){
				$where .= " and a.price > {$tmp[$params['price']]['code']} ";
			}
			if((int)$params['cat_id']) $where.= " and (a.view_cat_id = {$params['cat_id']} or b.parent_id ={$params['cat_id']})";
			$where .= " GROUP BY a.goods_id ";
			switch($params['order']){
				case 1:$order .= "a.price desc , a.goods_add_time desc"; break;
				case 2:$order .= "a.price asc , a.goods_add_time desc"; break;
				case 3:$order .= "a.tax/a.shop_price desc , a.goods_add_time desc"; break;
				case 4:$order .= "a.tax/a.shop_price asc , a.goods_add_time desc"; break;
				case 5:$order .= "a.goods_add_time desc"; break;
				case 6:$order .= "a.goods_add_time asc";break;
				default :$order .= "a.30day_sale desc , a.goods_add_time desc"; break;
			}
			if($type != 1){
				//返回符合条件的商品数量
				return count($this-> _db -> fetch($where,"a.*,b.cat_name,b.cat_path,c.brand_name,e.*",null,null,$order));
			}else{
				//返回符合条件的商品信息
				return $this-> _db -> fetch($where,"a.*,b.cat_name,b.cat_path,c.brand_name,e.*",$page,$pageSize,$order);
			}
		}else{
			return false;
		}
	}
	/**
	 * 查询数据
	 * @param array $all_goods_ids
	 * @param array $params
	 * @param int $type (1:获得商品数据；2：获得商品数量)
	 * @param int $page
	 * @param int $pageSize
	 */
	public function getGoodsDataBySearch($all_goods_ids,$params,$type=1,$page=null,$pageSize=null)
	{
		if(is_array($all_goods_ids) && is_array($params)){
			$goods_ids = implode(',', $all_goods_ids);
			
			$tmp = $this -> _filter_price;
			$where = "a.onsale=0 and a.is_del=0 ";
			$order = "";
			if((int)$params['cat_id']) $where.= " and a.view_cat_id = {$params['cat_id']} ";
			if((int)$params['brand']) $where.= " and a.brand_id = {$params['brand']} ";
			if($params['price'] != 0 && $params['price'] != count($tmp)-1){
				$arr = explode('_',$tmp[$params['price']]['code']);
				$where .= " and a.price between {$arr[0]} and {$arr[1]}";
			}else if($params['price'] == count($tmp)-1){
				$where .= " and a.price > {$tmp[$params['price']]['code']} ";
			}
			$where .= " and a.goods_id in ({$goods_ids}) GROUP BY a.goods_id ";
			switch($params['order']){
				case 1:$order .= "a.price desc , a.goods_add_time desc"; break;
				case 2:$order .= "a.price asc , a.goods_add_time desc"; break;
				case 3:$order .= "a.tax/a.shop_price desc , a.goods_add_time desc"; break;
				case 4:$order .= "a.tax/a.shop_price asc , a.goods_add_time desc"; break;
				case 5:$order .= "a.goods_add_time desc"; break;
				case 6:$order .= "a.goods_add_time asc";break;
				default :$order .= "a.30day_sale desc , a.goods_add_time desc"; break;
			}
			if($type != 1){
				//返回符合条件的商品数量
				return count($this-> _db -> fetch($where,"a.*,b.cat_name,b.cat_path,c.brand_name,e.*",null,null,$order));
			}else{
				//返回符合条件的商品信息
				return $this-> _db -> fetch($where,"a.*,b.cat_name,b.cat_path,c.brand_name,e.*",$page,$pageSize,$order);
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 已选中的条件
	 * @param array $search
	 * @return string
	 */
	public function setNav($search)
	{
		$search['brand'] = $search['brand'] ? (int)$search['brand']  : 0;
		$search['price'] = $search['price'] ? (int)$search['price'] : 0 ;
		$search['sort'] = $search['sort'] ? (int)$search['sort'] : 0 ;
		$nav = "<em>已选择: </em> ";
		if($search['cat_id']){
			$urltmp ="/gallery-0-{$search['brand']}-{$search['price']}-{$search['sort']}-1.html";
			$tmp = $this -> getCatNameById($search['cat_id']);
			$nav .= "<span><em>{$tmp}</em><b onclick='javascript:location.href = \"{$urltmp}\"'></b></span>";
		}
		if($search['brand']){
			$urltmp = "/gallery-{$search['cat_id']}-0-{$search['price']}-{$search['sort']}-1.html";
			$tmp = $this -> getBrandById($search['brand']);
			$nav .= "<span><em>{$tmp}</em><b onclick='javascript:location.href = \"{$urltmp}\"'></b></span>";
		}
		if($search['price']){
			$urltmp = "/gallery-{$search['cat_id']}-{$search['brand']}-0-{$search['sort']}-1.html";
			$tmp = $this -> _filter_price;
			$nav .= "<span><em>{$tmp[$search['price']]["price_name"]}</em><b onclick='javascript:location.href = \"{$urltmp}\"'></b></span>";
		}
		$nav .= "<em><a href='/gallery-0-0-0-0-1.html'>重置筛选条件</a></em>";
		return $nav;
	}
	/**
	 * 获得价格列表
	 */
	public function getPriceList()
	{
		return $this -> _filter_price;
	}
	/**
	 * 通过小类id 获得大类和小类的id name
	 * @param int $cat_id
	 */
	public function getCatParent($cat_id)
	{
		$cat = $this -> _db -> getCatParent($cat_id);
		$arr = array();
		foreach($cat as $k=>$v){
			if($v['parent_id']==0){
				$arr[0]=array($v['cat_id'],$v['cat_name']);
			}else{
				$arr[1]=array($v['cat_id'],$v['cat_name']);
			}
		}
		return $arr;
	}
	
	/**
	 * 通过商品获得类别
	 * @param array $all_goods_ids
	 */
	public function getCatByGoods($all_goods_ids)
	{
		if(is_array($all_goods_ids)){
			$str = implode(',', $all_goods_ids);
			return $this -> _db -> getCatByGoods($str);
		}else{
			return false;
		}	
	}
	
	/**
	 * 获得地区列表
	 */
	public function getRegionList()
	{
		return $this -> _db -> getRegion();
	}
	
	/**
	 * 通过产地找商品
	 * @param array $params
	 * @param int $type (1:获得商品数据；2：获得商品数量)
	 * @param int $page
	 * @param int $pageSize
	 */
	public function getGoodsByBrand($params,$type=1,$page=null,$pageSize=null)
	{
		if(is_array($params)){
			$tmp = $this -> _filter_price;
			$where = "a.onsale=0 and a.is_del=0 ";
			$order = "";
			if((int)$params['id']) $where.= " and a.brand_id = {$params['id']} ";
			if($params['price'] != 0 && $params['price'] != count($tmp)-1){
				$arr = explode('_',$tmp[$params['price']]['code']);
				$where .= " and a.price between {$arr[0]} and {$arr[1]}";
			}else if($params['price'] == count($tmp)-1){
				$where .= " and a.price > {$tmp[$params['price']]['code']} ";
			}
			$where .= " GROUP BY a.goods_id ";
			switch($params['order']){
				case 1:$order .= "a.price desc , a.goods_add_time desc"; break;
				case 2:$order .= "a.price asc , a.goods_add_time desc"; break;
				case 3:$order .= "a.tax/a.shop_price desc , a.goods_add_time desc"; break;
				case 4:$order .= "a.tax/a.shop_price asc , a.goods_add_time desc"; break;
				case 5:$order .= "a.goods_add_time desc"; break;
				case 6:$order .= "a.goods_add_time asc";break;
				default :$order .= "a.30day_sale desc , a.goods_add_time desc"; break;
			}
			if($type != 1){
				//返回符合条件的商品数量
				return count($this-> _db -> fetch($where,"a.*,b.cat_name,b.cat_path,c.brand_name,e.*",null,null,$order));
			}else{
				//返回符合条件的商品信息
				return $this-> _db -> fetch($where,"a.*,b.cat_name,b.cat_path,c.brand_name,e.*",$page,$pageSize,$order);
			}
		}else{
			return false;
		}
	}
	//search.html,array,cid,cat_id,array
	public function _searchFilter($pre_url,&$filter,$fname,$fkey,$params1){
		
		$params = $params1;
		unset($params['cat_id']);
		unset($params['controller']);
		unset($params['action']);
		unset($params['page']);
		unset($params['module']);
		
		foreach ($filter as $k=>$v){
			$is_c = false;//当前标识
			if(empty($v[$fkey])){
				unset($params[$fname]);
			}else{
				$params[$fname] = $v[$fkey];
			}
			$str_params = '';
			
			foreach ($params as $kk=>$vv){
				$str_params .= $kk.'='.$vv.'&';
			}
			$str_params = trim($str_params,'&');
			$url_param = empty($str_params) ? '' : '?'.$str_params;
			$filter[$k]['url'] = $pre_url.$url_param;
			//当前状态标识
			if($params1[$fname]){
				
				if($v[$fkey] == $params1[$fname]) $is_c = true;
			}else{
				if($k == 0) $is_c = true;
			}
			$filter[$k]['is_c'] = $is_c;
			
		}
		
	}
	
	public function _modifySort($url,&$sort,$params1){
		$params = $params1;
		unset($params['cat_id']);
		unset($params['controller']);
		unset($params['action']);
		unset($params['page']);
		unset($params['sort']);
		unset($params['module']);
		$url=$url.'?'.http_build_query($params);
		$param_sort = $params1['sort']?$params1['sort']:0;
		foreach ($sort as $k=>$v){
			//排序类标识
			if(isset($param_sort)){
				if(in_array($param_sort, $v['sorttype'])){
					$sortclass = 'png_bg';
					if($param_sort == $v['sorttype'][0]){
						$sort[$k]['url'] = $url."&sort={$v['sorttype'][1]}";
						$sortclass = 'png_bg2';
					}else{
						$sort[$k]['url'] = $url."&sort={$v['sorttype'][0]}";
						$sortclass = 'png_bg';
					}
				}else{
					$sort[$k]['url'] = $url."&sort={$v['sorttype'][0]}";
					$sortclass = 'png_bg';
				}
			}
			$sort[$k]['sortclass'] = $sortclass;
		}
	}
	
	/**
	 * 通过归属地id获得归属地
	 * @param int $region_id
	 */
	public function getRegionById($region_id)
	{
		return  $this -> _db -> getRegionById($region_id);
	}
	
	/**
	 * 获得最新商品
	 */
	public function getNewGoods($num=100)
	{
		return $this -> _db -> getNewGoods($num);
	}
	
	/**
	 * 获取随机数目的上架商品
	 * @param int $num
	 */
	public function getRandGoods($num=8)
	{
		return $this-> _db -> getRandGoods($num);
	}
	
	/**
	 * 获得低税商品
	 */
	public function getTaxGoods()
	{
		return $this -> _db -> getTaxGoods();
	}
	
	public function sendGoodsNotcie($data)
	{
	    $notice =  $this->_db->getNoticeByAccount($data);
	    if(!$notice)
	    {
	        $res =  $this->_db->addGoodsNotice($data);
	        return array('isok'=>$res,'msg'=>$res?'到货通知订阅知成功!':'到货通知订阅失败!');
	    }else if($notice['status'] == 0){ //处理中
	        return array('isok'=>false,'msg'=>'您已经订阅到货通知成功，无需重复提交!');
	    }elseif ($notice['status'] == 1) //重新订阅
	    {
	        $data['num'] = $notice['num']+1;
	        $data['status'] = 0;
	        unset($data['ctreated']);
	        $res = 	$this->_db->updateGoodsNotice($data,$notice['notice_id']);
	        return array('isok'=>$res,'msg'=>$res?'到货通知订阅成功!':'到货通知订阅失败!');
	    }
	
	}
}