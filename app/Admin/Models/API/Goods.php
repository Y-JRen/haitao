<?php
class Admin_Models_API_Goods 
{
    /**
     * DB对象
     */
	private $_db = null;
	
    /**
     * 错误信息
     */
	private $error;
	
	/**
	 * 词库
	 */
	public $dicfile;
	public $dicfilebak;
	
	/**
     * 上传路径
     */
	private $upPath = 'upload';
	
	private $_lid;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _config = Zend_Registry::get('config');
		$this -> _db = new Admin_Models_DB_Goods();
		$this -> _product = new Admin_Models_DB_Product();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> _lid = $this -> _auth['lid'];
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
	public function get($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		if (is_array($where)) {
			$whereSql = "1=1";
		    $where['filter'] && $whereSql .= $where['filter'];
		    $where['onsale'] == 'on' && $whereSql .= " and onsale=0";
		    $where['onsale'] == 'off' && $whereSql .= " and onsale=1";
		    $where['cat_id'] && $whereSql .= " and d.cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    if ($where['view_cat_id']) {
		        $whereSql .= " and d.cat_path LIKE '%," . $where['view_cat_id'] . ",%'";
		    }
            if (!is_null($where['is_del']) && $where['is_del'] !== '') {
                $whereSql .= " and (a.is_del='" . $where['is_del']. "')";
            }
		    $where['goods_sn'] && $whereSql .= " and (goods_sn LIKE '" . trim($where['goods_sn']). "%')";
		    $where['goods_name'] && $whereSql .= " and goods_name LIKE '%" . trim($where['goods_name']) . "%'";
		    $where['cat_name'] && $whereSql .= " and (b.cat_name LIKE '%" . trim($where['cat_name']) . "%' or d.cat_name LIKE '%" . trim($where['cat_name']) . "%')";
		    $where['goods_img'] && $whereSql .= " and (goods_img = '' or goods_img is null)";
			$where['goods_arr_img'] && $whereSql .= " and (goods_arr_img = '' or goods_arr_img is null)";
			($where['fromdate']) ? $whereSql .= " and goods_add_time >=" . strtotime($where['fromdate']) : "";
			($where['todate']) ? $whereSql .= " and goods_add_time <" . (strtotime($where['todate'])+86400) : "";
            $where['sid'] && $whereSql .= " and (sid='" . $where['sid']. "')";
            $where['lid'] && $whereSql .= " and (lid='" . $where['lid']. "')";
            $where['brand_id'] && $whereSql .= " and (a.brand_id='" . $where['brand_id']. "')";

		    if ($where['fromprice'] && $where['toprice']) {
			    $fromprice = intval($where['fromprice']);
			    $toprice = intval($where['toprice']);
			    if($fromprice <= $toprice) $whereSql .= " and (price between $fromprice and $toprice)";
	        }
	        if ($where['fromprice_market'] && $where['toprice_market']) {
			    $fromprice = intval($where['fromprice_market']);
			    $toprice = intval($where['toprice_market']);
			    if($fromprice <= $toprice) $whereSql .= " and (market_price between $fromprice and $toprice)";
	        }
	        if ($where['fromprice_staff'] && $where['toprice_staff']) {
			    $fromprice = intval($where['fromprice_staff']);
			    $toprice = intval($where['toprice_staff']);
			    if($fromprice <= $toprice) $whereSql .= " and (staff_price between $fromprice and $toprice)";
	        }
	        if ($where['brand_name']) {
	            $brand_data = $this->getBrand($where['brand_name']);
	            if ($brand_data) {
	                $whereSql .= " and a.brand_id={$brand_data[0]['brand_id']}";
	            }
	            else    $whereSql .= " and a.brand_id=0";
	        }

			$where['price_limit'] && $whereSql .= " AND a.price < c.price_limit ";
		}else{
			$whereSql = $where;
		}

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
		$datas = $this -> _db -> fetch($whereSql, $fields, $page, $pageSize, $orderBy);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['goods_status'] = ($datas[$num]['onsale']) ? '<font color="red">下架</font>' : '上架';
	        $datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
	}
	
	/**
     * 获取总数
     *
     * @return   int
     */
	public function getCount($type=null)
	{

		return $this -> _db -> total;

	}
	
	/**
     * 获取品牌列表
     *
     * @return   array
     */
	public function getBrand($brand_name = null)
	{
        if ($brand_name) {
            $where = "where brand_name like '%{$brand_name}%'";
        }
        return $this -> _db -> getBrand($where);
	}
	
	
	/**
     * 获取标签列表
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllTag($where = null,$page=null, $pageSize = null)
	{
		if ($where['tag']) {
		    $wheresql .= " and  tag like '%{$where['tag']}%'";
		}
		if ($where['title']) {
		    $wheresql .= " and title like '%{$where['title']}%'";
		}
		return $this -> _db -> getAllTag($wheresql,$page, $pageSize);
	}


	/**
     * 添加商品新标签
     *
     * @param    string    $where
     * @return   array
     */
	public function addTag($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        $data['admin_name'] = $this -> _auth['admin_name'];
		return $this -> _db -> addTag($data);
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where,$type='goods')
	{  
		$data = array_shift($this -> _db -> getTag($where));
		if ($data['config']){
			$ids = $data['config'];
			if($type=='goods') {
			    $result['details'] = $this -> get("a.goods_id in($ids)", 'a.goods_id,a.onsale,goods_name,goods_img,goods_sn,market_price,price', null, null, "find_in_set(a.goods_id, '$ids')");
			}elseif($type=='brand'){

	            $apiBrand=new Admin_Models_API_Brand();
				$res= $apiBrand -> get("brand_id in($ids)", 'brand_id,brand_name,small_logo,as_name');
				foreach($res as $k=>$v){
					$result['details'][$k]['goods_id']=$v['brand_id'];
					$result['details'][$k]['goods_sn']=$v['small_logo'];
					$result['details'][$k]['goods_name']=$v['brand_name'];
					$result['details'][$k]['goods_status']=$v['as_name'];
				}
			}

		}
		$result['data'] = $data;
		return $result;
	}


	/**
     * 检查输入
     *
     * @param    string    $where
     * @return   array
     */
	public function checkinput($where)
	{
		$data = array_shift($this -> _db -> getTag($where));
		return $data;
	}

	/**
     * 添加关联商品
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function updateTag($data, $tag_id,$type=null)
	{
		if($tag_id > 0){
			if (is_array($data['goods_id']) && count($data['goods_id']) > 0){
				$val = implode(',', $data['goods_id']);
			    $where = "goods_id in(".implode(',', $data['goods_id']).")";
			}else{
				$val = '';
				$where = "goods_id > 0";
			}
			$this -> _db -> updateTag($tag_id, $val,$type);
			return true;
		}
	}
	
	/**
     * 添加商品
     *
     * @param    int        $catID
     * @param    string     $goodsSN
     * @param    string     $goodsName
     * @return   void
     */
	public function addGoods($catID, $goodsSN, $goodsName)
	{
	    $data = array('view_cat_id' => $catID,
	                  'goods_sn' => $goodsSN,
	                  'goods_name' => $goodsName,
	                  'lid' => $this -> _lid
	                 );
	    $goodsID = $this -> _db -> insert($data);
	    
	    $categorAPI = new Admin_Models_API_Category();
	    if (!$categorAPI -> getAttr("cat_id = '{$catID}'")) {
	        $data = array('product_sn' => $goodsSN.'00',
                          'product_name' => $goodsName,
                          'cat_id' => $catID,
                          'lid' => $this -> _lid,
                         );
            $productID = $this -> _product -> addProduct($data);
            
            if ($productID) {
                $data = array('product_id' => $productID,
                              'goods_id' => $goodsID,
                             );
                $this -> _product -> addProductGoods($data);
                $productApi = new Admin_Models_API_Product();
                $productApi -> ajaxUpdate($productID, 'adjust_num', 10);
            }
	    }
	    
	    return $goodsID;
	}
	
	public function updateAsnameById($id){
	    $r_goods = $this->getRow('shop_gooods',array('goods_id'=>$id),'product_id');
	    $r_product = $this->getRow('shop_product',array('product_id'=>$r_goods['product_id']),'brand_id');
	    $r_brand = $this->getRow('shop_brand',array('brand_id'=>$r_product['brand_id']),'as_name');
	    $as_name = $r_brand['as_name'];
	    return $this->update('shop_goods', array('as_name'=>$as_name),array('goods_id'=>$id));
	}
	
	/**
     * 添加或修改数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function edit($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());        
        $data = Custom_Model_Filter::filterArray($data, $filterChain);	
        $data['brief'] = stripslashes($data['brief']);
		$data['act_notes'] = stripslashes($data['act_notes']);
		$data['description'] = stripslashes($data['description']);
		$this -> _db -> update($data, (int)$id);
		
		//日志记录开始
	    $row = array ('goods_id' => $id,
                      'admin_name' => $this -> _auth['admin_name'],
                      'op_type' => 'goods',
                      'remark' => '商品资料修改',
                      'op_time' => time(),
                      );
	    $this -> _db -> insertOp($row);
	    
		return true;
	}
	
	/**
     * 获取关联商品
     *
     * @param    int       $goods_id
     * @return   array
     */
	public function getLink($goods_id,$type)
	{
		$datas = $this -> _db -> getLink($goods_id,$type);
        foreach ($datas as $num => $data)
        {
	       if($type==null){ 
			   $datas[$num]['goods_status'] = $datas[$num]['onsale']==0 ? '上架' : '下架';
		   }else{
			   $datas[$num]['goods_status'] = $datas[$num]['onsale']==0 ? '下架' : '上架';	
		   }
        }
        return $datas;
	}
	
	/**
     * 添加关联商品
     *
     * @param    int    $data
     * @param    int    $goods_id
     * @return   void
     */
	public function addLink($data, $goods_id,$type=null)
	{
		if($goods_id> 0){
			if(is_array($data['goods_id'])){
				foreach($data['goods_id'] as $key => $val){
				    $this -> _db -> addLink($goods_id, $val,$type);
				}
				return true;
			}else{
				$this -> error = 'error';
				return false;
			}
		}
	}
	
	/**
     * 删除关联商品数据
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteLink($id,$type=null)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deleteLink((int)$id,$type);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    return $result;
		}
	}
	
	/**
     * 删除数据
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteGoods($id,$value)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deleteGoods((int)$id,$value);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    return $result;
		}
	}
	
	/**
     * 获取状态信息
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="openDiv(\''.$url.'/id/'.$id.'/status/1\', \'ajax\', \'商品下架\',400,200);" title="点击设为下架"><u>上架</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为上架"><u><font color=red>下架</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	/**
     * 更改状态
     *
     * @param    int       $id
     * @param    int       $status
     * @param    string    $remark
     * @return   void
     */
	public function changeStatus($id, $status, $remark = '')
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateStatus((int)$id, $status, $remark)) {
				//日志记录开始
			    $row = array (
		                      'goods_id' => $id,
		                      'old_value' => $status ? 0 : 1,
		                      'new_value' => $status,
		                      'admin_name' => $this -> _auth['admin_name'],
		                      'op_type' => 'onoff',
		                      'remark' => $remark,
		                      'op_time' => time(),
		                      );
			    $this -> _db -> insertOp($row);
			}
		}
	}
	
	/**
     * 更改状态
     *
     * @param    int       $id
     * @param    int       $status
     * @param    string    $remark
     * @return   void
     */
	public function changeStatus2($id, $status, $remark = '')
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateStatus((int)$id, $status, $remark, 2)) {
				//日志记录开始
			    $row = array (
		                      'goods_id' => $id,
		                      'old_value' => $status ? 0 : 1,
		                      'new_value' => $status,
		                      'admin_name' => $this -> _auth['admin_name'],
		                      'op_type' => 'onoff2',
		                      'remark' => $remark,
		                      'op_time' => time(),
		                      );
			    $this -> _db -> insertOp($row);
			}
		}
	}
	
	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
	 * @param    string   $type
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val, $type)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		$type = $filterChain->filter($type);
		
		if ((int)$id > 0) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val, $type) <= 0) {
		        exit('failure');
		    }
		}
	}
	
	/**
     * 更新商品价格
     *
     * @param    array    $set
     * @param    string   $where
     * @param    string   $type
     * @return   array
     */
    public function updatePrice($data, $id)
	{
		$data['tax'] = $this -> calc($data['shop_price'], $data['org_tax_price'], $data['measurement_unit'], $data['tax_rate']);
		$data['price'] = $data['shop_price'] + $data['fare'] + ($data['tax'] >=50 ? $data['tax'] : 0);
	    $set = array(
	    	'market_price' => $data['market_price'],
	    	'price' => $data['price'],
	    	'tax' => $data['tax'],
	    	'fare' => 0,
	    	'shop_price' => $data['shop_price'],
	    	'measurement_unit' => $data['measurement_unit'],
	    	'org_tax_price' => $data['org_tax_price'],
	    	'tax_rate' => $data['tax_rate']
	    		
	    	);
	    
	    $price_seg = array();
	    $max = 0;
	    $min = 10000;
	    for ( $i = 1; $i <= 5; $i++ ) {
	        $pricevar = 'price'.$i;
	        $quantityvar_from = "quantity{$i}_from";
	        $quantityvar_to = "quantity{$i}_to";
	        if ($data[$pricevar] && $data[$quantityvar_from]) {
	            if ($data[$quantityvar_from] == 1) {
	                $this -> error = 'price_seg_1';
	                return false;
	            }
	            if ($data[$quantityvar_to] == '')   $temparr[$data[$quantityvar_from]] = 1;
	            else {
	                for ( $j = $data[$quantityvar_from]; $j <= $data[$quantityvar_to]; $j++ ) {
	                    $temparr[$j] = 1;
	                } 
	            }
	            if ( $data[$quantityvar_from] > $max )  $max = $data[$quantityvar_from];
	            if ( $data[$quantityvar_to] > $max )    $max = $data[$quantityvar_to];
	            if ( $data[$quantityvar_from] < $min)   $min = $data[$quantityvar_from];
	            $price_seg[] = array($data[$pricevar], $data[$quantityvar_from], $data[$quantityvar_to] ? $data[$quantityvar_to] : '');
	        }
	    }
	    if ( $max > 0 ) {
	        for ( $i = $min; $i <= $max; $i++ ) {
	            if (!$temparr[$i]) {
	                $this -> error = 'price_seg';
	                return false;
	            }
	        }
	    }
	    $set['price_seg'] = serialize($price_seg);
	    $this -> _db -> updateGoods($set, "goods_id=$id");
	    //日志记录开始
	    $row = array (
                      'goods_id' => $id,
                      'old_value' => $data['old_value'],
                      'new_value' => Zend_Json::encode($data),
                      'admin_name' => $this -> _auth['admin_name'],
                      'op_type' => 'price',
                      'remark' => '商品价格修改',
                      'op_time' => time(),
                      );
	    $this -> _db -> insertOp($row);
	    return true;
	}
	
	/**
     * 导出商品资料
     *
     * @return void
     */
    public function export($where)
    {
        $excel = new Custom_Model_Excel();
		$excel -> send_header('goods.xls');
		$excel -> xls_BOF();
    	$title = array('商品ID', '商品编码', '商品名称', '前台链接', '商品分类', '行邮税', '本店价', '状态', '商品分类路径','主图URL','品牌');
    	$col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
		if (is_array($where)) {
			$whereSql = "1=1";
		    $where['filter'] && $whereSql .= $where['filter'];
		    $where['onsale'] == 'on' && $whereSql .= " and onsale=0";
		    $where['onsale'] == 'off' && $whereSql .= " and onsale=1";
		    $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
            $where['goods_sn'] && $whereSql .= " and (goods_sn LIKE '" . trim($where['goods_sn']). "%')";
		    $where['goods_name'] && $whereSql .= " and goods_name LIKE '%" . $where['goods_name'] . "%'";
		    $where['goods_img'] && $whereSql .= " and (goods_img = '' or goods_img is null)";
			$where['goods_arr_img'] && $whereSql .= " and (goods_arr_img = '' or goods_arr_img is null)";
			($where['fromdate']) ? $whereSql .= " and goods_add_time >=" . strtotime($where['fromdate']) : "";
			($where['todate']) ? $whereSql .= " and goods_add_time <" . (strtotime($where['todate'])+86400) : "";

            if ($where['is_del'] !== null && $where['is_del'] !== '') {
                $whereSql .=  " and is_del={$where['is_del']}";  
            }    

		    if ($where['fromprice'] && $where['toprice']) {
			    $fromprice = intval($where['fromprice']);
			    $toprice = intval($where['toprice']);
			    if($fromprice <= $toprice) $whereSql .= " and (price between $fromprice and $toprice)";
	        }
	        if ($where['fromprice_market'] && $where['toprice_market']) {
			    $fromprice = intval($where['fromprice_market']);
			    $toprice = intval($where['toprice_market']);
			    if($fromprice <= $toprice) $whereSql .= " and (market_price between $fromprice and $toprice)";
	        }
	        if ($where['fromprice_staff'] && $where['toprice_staff']) {
			    $fromprice = intval($where['fromprice_staff']);
			    $toprice = intval($where['toprice_staff']);
			    if($fromprice <= $toprice) $whereSql .= " and (staff_price between $fromprice and $toprice)";
	        }
	        if ($where['brand_name']) {
	            $brand_data = $this->getBrand($where['brand_name']);
	            if ($brand_data) {
	                $whereSql .= " and brand_id={$brand_data[0]['brand_id']}";
	            }
	            else    $whereSql .= " and brand_id=0";
	        }
		}else{
			$whereSql = $where;
		}
		$datas = $this -> _db -> fetchGoods($whereSql, "a.goods_id,goods_sn,goods_name,a.view_cat_id,market_price,price,onsale,a.brief,a.description,b.cat_path,a.goods_img,a.goods_img,p.brand_name,a.shop_price,a.tax ");
		foreach ($datas as $k => $v) {
            $nav = '';
            $path = substr($v['cat_path'], 1, -1);
            if ($path) {
                $tempDatas = $this -> _db -> getCat(" cat_id in ($path) ", " find_in_set(cat_id, '$path')");
                foreach ($tempDatas as $data) {
                    $nav .= $data['cat_name'].'/';
                }
            } 
            else {
                $nav = '';
            }
            
			if ($v['goods_img']) {
				$goods_img='http://www.1jiankang.com/'.$v['goods_img'];
			}
			else {
				$goods_img='没有图片';
			}
            $v['goods_link']="/goods-".$v['goods_id'].".html";
            $v['brief']=preg_replace("/<(.*?)>/","",$v['brief']);
            $v['description']=preg_replace("/<(.*?)>/","",$v['description']);
			$status = $v['onsale'] ? '下架' : '上架';
			$row = array($v['goods_id'], $v['goods_sn'], $v['goods_name'],$v['goods_link'], $v['cat_name'], $v['tax'], $v['shop_price'], $status, $nav ,$goods_img, $v['brand_name']);
		    for ($i = 0; $i < $col; $i++) {
			    $excel -> xls_write_label($k+1, $i, $row[$i]);
			}
			flush();
		    ob_flush();
			unset($row);
        }
        unset($datas);
        $excel -> xls_EOF(); 
	}

	/**
     * 错误集合
     *
     * @param   array   $data
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'exists'=>'此款商品已存在!',
			         'not_exists'=>'该商品不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_cat'=>'请选择分类!',
			         'no_child'=>'请选择子分类!',
			         'no_name'=>'请填写商品名称!',
			         'no_sn'=>'没有编码!',
			         'price_seg'=>'数量区间不连续!',
			         'price_seg_1'=>'起始数量必须大于等于2!',
			         'exists_goods_sn' => '商品编码已存在!',
			         'exists_goods_name' => '商品名称已存在!',
			         'not_exists_goods_sn' => '商品编码不存在!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
    /**
     * 得到某个商品所属扩展分类
     * 
     * @param int $goods_id
     * 
     * @return array
     */
    public function getGoodsInCat($goods_id) {
    	if($goods_id > 0){
    		return $this -> _db -> getGoodsInCat($goods_id);
    	}
    }
    
    /**
     * 取出某商品的关键字
     * 
     * @param int $goods_id
     * 
     * @return array
     */
    public function getKeywords($goods_id) {
    	if($goods_id){
    		return $this -> _db-> getKeywords($goods_id);
    	}else{
    		return array();
    	}
    }
    
    /**
     * 添加关键字
     * 
     * @param int $arr
     */
    public function addkeywords($data) {
    	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
    	return $this -> _db -> addkeywords($data);
    }
    
    /**
     * 修改关键字
     * 
     * @param array
     */
    public function editkeywords($data) {
    	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
    	return $this -> _db -> editkeywords($data);
    }
    
    /**
     * 由goods_id得到一个商品
     * 
     * @param int $goods_id
     * @return array
     */
    public function getOne($goods_id, $fields='*') {
    	if(intval($goods_id)){
    		return $this->_db->getOne($goods_id, $fields);
    	}
    }
    
    /**
     * 第一次使用一下，以后不再用
     * 
     */
    public function genkeywords() {
    	$this -> _db -> genkeywords();
    }
    
    /**词库词库词库词库
     * 读取词库 
     * 
     */
    public function readDict() {
    	//读取词库 到->$dic[]
    	$dicfile = $this -> dicfile;
	  	$fp = @fopen($dicfile,'r');
	  	if(!$fp)return false;
	  	while($line = fgets($fp,256)){
	  		  $ws = explode(' ',$line);
	  		  $val = iconv('gbk', 'utf-8', $ws[0]);
	  		  $dic[] = $val;
	  	}
	  	fclose($fp);
	  	return array_unique($dic);
    }
    
    /**
     * 生成词库
     * 
     * @param array $dict 词库数组
     * 
     * @return bool;
     */
    private function genDict($dict) {
    	if(!is_array($dict) && !count($dict))return false;
		if(is_file($this -> dicfilebak)){@unlink($this -> dicfilebak);}//如果有备份词库，删除
		if(copy($this -> dicfile,$this -> dicfilebak)){//备份
			@unlink($this -> dicfile);//删除原来词库
			file_put_contents($this -> dicfile, $dict);
			return true;
		}else{
			return false;//不能备份写入词库
		}
    }
    
	/**
     * 添加更新词库dictjiankang.dat
     * 
     * @param string $str
     */
    public function updateDict($str) {
    	$str = trim($str);
    	if($str == '') return false;
	  	$dic = $this -> readDict();
    	$tmp = explode('|', $str);
    	foreach($tmp as $v){
    		if(!in_array($v, $dic) && strlen($v)>3){
    			$dic[] = $v;
    		}
    	}
    	//保存文件
    	foreach ($dic as $v){
    		if($v){
    			$v=iconv('utf-8', 'gbk', $v);
				$dict.=$v.' '.chr(10);
    		}
		}
		return $this -> genDict($dict);
    }
    
    /**
     * 修改词库中的关键词
     * 
     * @param string $v 新值
     * @param string $ov 原值
     * 
     * @return bool
     */
    public function editKeywordDict($v,$ov) {
    	$v = trim($v); $ov = trim($ov);
    	if($v=='' || $ov==''){return false;}
    	$dict = $this -> readDict();
    	foreach ($dict as $val){
    		if($val == $ov){$val = $v;}
    		$val=iconv('utf-8', 'gbk', $val);
    		$tmp .= $val.' '.chr(10);
    	}
    	return $this -> genDict($tmp);
    }
    
    /**
     * 删除词库中的关键词
     * 
     * @param string $ov 原值
     * 
     * @return bool
     */
    public function delKeywordDict($ov) {
    	$ov = trim($ov);
    	if($ov==''){return false;}
    	$dict = $this -> readDict();
    	foreach ($dict as $val){
    		if($val == $ov){continue;}
    		$val = iconv('utf-8', 'gbk', $val);
    		$tmp .= $val.' '.chr(10);
    	}
    	return $this -> genDict($tmp);
    }
    
	/**
     * 获取关联商品文章
     *
     * @param    string    $where
     * @return   array
     */
	public function getLinkArticle($goods_id)
	{
		$data = $this->_db->getOne($goods_id,'goods_name,goods_sn,article_ids');
		if ($data['article_ids']){
			$ids = $data['article_ids'];
            $articleApi = new Admin_Models_API_Article();
			$result['details'] = $articleApi -> get(" and a.article_id in($ids)", 'article_id,cat_name,title,author,source,is_view,add_time,a.sort', null, null, "find_in_set(a.article_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}


	/**
	 * 用户搜索统计列表
	 * 
	 * @param array $search
	 * @param int 4page
	 * 
	 * @return array
	 */
	public function getCustomerSearch($search=null, $page=null, $pageSize=null) {
		$orderBy = '';
		if(is_array($search) && count($search)){
			$wheresql = ' 1=1 ';
			$search['orderby'] && $orderBy = $search['orderby'];
			
			if(isset($search['searchcount'])){
				$ct = (int)$search['searchcount'];
				if($ct!=0){ $wheresql .= " and searchcount > ".abs($ct); }
			}
			$search['searchword'] && $wheresql .= " and searchword like '%".$search['searchword']."%' ";
			if($search['ctime'] && $search['ltime']){
				$t1 = strtotime($search['ctime']);
				$t2 = strtotime($search['ltime']);
				if($t1 > $t2){
					$tmp = $t1;$t1=$t2;$t2=$tmp;
				}
				$wheresql .= " and ctime > ".$t1;
				$wheresql .= " and ltime < ".$t2;
			}else{
				$search['ctime'] && $wheresql .= " and ctime > ".strtotime($search['ctime']);
				$search['ltime'] && $wheresql .= " and ltime < ".strtotime($search['ltime']);
			}
			if($search['status']!=''){
				if($search['status']==1 || $search['status']==2){
					$wheresql .= " and status = ".$search['status'];
				}
			}
		}else{
			$wheresql = ' 1=1 ';
		}
		return $this -> _db -> getCustomerSearch($wheresql,$page,$pageSize,$orderBy);
	}
	
	/**
	 * 删除用户搜索统计列表一条记录
	 * 
	 * @param int $id
	 **/
	public function delOneCustomerSearchword($id) {
		$id = (int)$id;
		if($id>0){
			$this -> _db -> delOneCustomerSearchword($id);
		}
	}
	/**
	 * 更新用户搜索
	 * 
	 * @param array $arr
	 * @param string $where
	 */
	public function updateCustomerSearchword($arr,$where) {
		$this -> _db -> updateCustomerSearchword($arr,$where);
	}

	/**
     * 得到百度数据以便生成xml文件
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getGoodsForBaidu($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		if (is_array($where)) {
			$whereSql = "1=1";
		    $where['onsale'] == 'on' && $whereSql .= " and a.onsale=0";
		    $where['onsale'] == 'off' && $whereSql .= " and a.onsale=1";

			if ($where['sort']) {
				switch ($where['sort']){
					case 1:
						$orderBy = "a.goods_id desc";
						break;
					case 2:
						$orderBy = "a.sort_sale desc";
						break;
					case 3:
						$orderBy = "a.price asc";
						break;
					case 4:
						$orderBy = "a.price desc";
						break;
					default:
						break;
				}
			}
			return $this -> _db -> getGoodsForBaidu($whereSql, $fields, $page, $pageSize, $orderBy);
		}
	}
	
	/**
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
		return $this -> _db -> getOp($where);
	}
	
	/**
     * 新增商品时检查商品编码是否合法
     *
     * @param    string   $goodsSN
     * @return   boolean
     */
	public function checkNewGoodsSN($goodsSN)
	{
	    if ($this -> _db -> fetchGoods("a.goods_sn = '{$goodsSN}' ")) {
	        $this -> error = 'exists_goods_sn';
	        return false;
	    }
	    return true;
	}
	
	/**
     * 新增商品时检查商品名称是否合法
     *
     * @param    string   $goodsName
     * @return   boolean
     */
	public function checkNewGoodsName($goodsName)
	{
	    if ($this -> _db -> fetchGoods("a.goods_name = '{$goodsName}' and a.lid = '{$this -> _lid}'")) {
	        $this -> error = 'exists_goods_name';
	        return false;
	    }
	    return true;
	}
	
	/**
     * 新增商品时检查商品编码是否合法
     *
     * @param    string   $goodsSN
     * @return   boolean
     */
	public function updateGoodsImage($goods_id, $goods_img_ids)
	{
        if (is_array($goods_img_ids)) {
            $goods_img_ids = implode(',', $goods_img_ids);
        }
        else    $goods_img_ids = '';
        $this -> _db -> updateGoods(array('goods_img_ids' => $goods_img_ids), "goods_id = {$goods_id}");
	}
	
	/**
     * 上传图片  产品图片类型  0标准图  1色块图  2细节图  3展示图  4规格图
     *
     * @return   void
     */
	public function upimg($data, $goods_id, $goods_sn)
	{
        $_path = strtolower(substr(md5($goods_sn),0,2));
        $_path = $_path ? $_path : '00';
		$this -> upPath .= '/'.$_path.'/'.$goods_sn;
		$add_time = time();
		//添加修改产品标准图
		if(is_file($_FILES['goods_img']['tmp_name'])) {
		    $thumbs = '380,380|180,180|60,60';
			$upload = new Custom_Model_Upload('goods_img', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> updateGoods(array('goods_img' => $img_url), "goods_id = {$goods_id}");
		}
		//添加修改规格图
		if(is_file($_FILES['goods_arr_img']['tmp_name'])) {
		    $thumbs = '380,380|180,180|60,60';
			$upload = new Custom_Model_Upload('goods_arr_img', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> updateGoods(array('goods_arr_img' => $img_url), "goods_id = {$goods_id}");
		}
	}
	

	/**
     * 获得商品编码前缀
     *
	 * @param    int        $catID
     * @return   string
     */
	public function getGoodsPrefixSn($catID)
	{
	    $catAPI = new Admin_Models_API_Category();
	    
	    $category = array_shift($catAPI -> getProductCat("cat_id = '{$catID}'"));
	    if ($category['parent_id'] == 0) {
	        return false;
	    }
	    $result = $category['cat_sn'];
	    $category = array_shift($catAPI -> getProductCat("cat_id = '{$category['parent_id']}'"));
	    if ($this -> _lid == 1) {
	        return 'H'.$category['cat_sn'].$result.$this -> getGoodsLastSN($catID);
	    }
	    else if ($this -> _lid == 2) {
	        return 'J'.$category['cat_sn'].$result.$this -> getGoodsLastSN($catID);
	    }
	}
	
	/**
     * 获得商品编码前缀
     *
	 * @param    int        $catID
     * @return   string
     */
	public function getGoodsLastSN($catID)
	{
	    $goods = array_shift($this -> _db -> fetchGoods("a.view_cat_id = '{$catID}'", 'a.goods_sn', 1, 1, 'a.goods_sn desc'));
	    if ($goods) {
            $result = substr($goods['goods_sn'], 5, 3);
        }
        else {
            $result = 0;
        }
        $result++;
        return substr('00'.$result, -3);
	}
	
	/**
     * 获得商品产品信息
     *
	 * @param    array    $where
     * @return   array
     */
	public function getGoodsProductData($where)
	{
	    $whereSQL = 1;
	    $where['goods_id'] && $whereSQL .= " and t1.goods_id = '{$where['goods_id']}'";
	    $where['goods_sn'] && $whereSQL .= " and t1.goods_sn = '{$where['goods_sn']}'";
	    $where['goods_name'] && $whereSQL .= " and t1.goods_name = '{$where['goods_name']}'";
	    $where['view_cat_id'] && $whereSQL .= " and t1.view_cat_id = '{$where['view_cat_id']}'";
	    $where['cat_id'] && $whereSQL .= " and t3.view_cat_id = '{$where['cat_id']}'";
	    $where['product_id'] && $whereSQL .= " and t3.product_id = '{$where['product_id']}'";
	    $where['product_sn'] && $whereSQL .= " and t3.product_sn = '{$where['product_sn']}'";
	    $where['product_name'] && $whereSQL .= " and t3.product_name = '{$where['product_name']}'";
	    
	    return $this -> _db -> getGoodsProductData($whereSQL);
	}
	
	/**
     * 获得商品库存
     *
	 * @param    array    $goodsIDArray
     * @return   array
     */
	public function getGoodsStock($goodsIDArray)
	{
	    $stockAPI = new Admin_Models_API_Stock();
        $stockAPI -> setLogicArea($this -> _lid);
        $productAPI = new Admin_Models_API_Product();
        
        $productList = $productAPI -> getProductGoods("goods_id in (".implode(',', $goodsIDArray).")");
        if (!$productList)  return false;
        
        foreach ($productList as $product) {
            $productIDArray[] = $product['product_id'];
            $goodsProductInfo[$product['product_id']] = $product['goods_id'];
        }
        
        $productStock = $stockAPI -> getSaleProductOutStock(array('product_id' => $productIDArray),null,null,true);
        if ($productStock) {
            foreach ($productStock as $stock) {
                $stockData[$stock['product_id']] = $stock;
            }
        }

        foreach ($productList as $product) {
            $result[$goodsProductInfo[$product['product_id']]]['able_number'] += $stockData[$product['product_id']]['able_number'];
            $result[$goodsProductInfo[$product['product_id']]]['real_number'] += $stockData[$product['product_id']]['real_number'];
            $result[$goodsProductInfo[$product['product_id']]]['hold_number'] += $stockData[$product['product_id']]['hold_number'];
        }

        return $result;
	}
	
	/**
	 * 获得归属地
	 */
	public function getRegion()
	{
		return $this->_db->getRegion();
	}
	
	/**
	 * 计算行邮税
	 * @param float $shop_price 商品单价
	 * @param float $org_tax_price 完税价格
	 * @param float $unit 计量单位
	 * @param float $tax_rate 税率 10%=>10
	 */
	public function calc($shop_price,$org_tax_price,$unit,$tax_rate)
	{
	    return $shop_price * $tax_rate * 0.01;
	    /*
		$rel_tax_price = 0;
		if($org_tax_price == 0){
			$rel_tax_price = $shop_price * $unit;
		}else{
			$rel_tax_price = $org_tax_price * $unit;
		}
		
		if($shop_price > ($rel_tax_price/2) && $shop_price < ($rel_tax_price*2)){
			$tax = $rel_tax_price * $tax_rate * 0.01;
		}else{
			$tax = $shop_price * $tax_rate * 0.01;
		}
		return $tax;*/
	}
	
	public function checkGoodsPrice($goods_id)
	{
	    $data = $this -> _db -> getGoodsProductData(" t1.goods_id = {$goods_id} ");
	    if($data){
	        foreach($data as $k => $v){
	            if($v['shop_price'] && $v['fare'] && $v['tax'] && floatval($v['p_weight'])!=0.000 && ($v['price']==$v['shop_price'] + $v['fare'] + ($v['tax']>=50?$v['tax']:0))){
	                continue;
	            }else{
	                return false;
	            }
	        }
	        return true;
	    }
	    return false;
	}
	
	public function checkOnsale($goods_id)
	{
	    $data = $this -> _db -> getGoodsProductData(" t1.goods_id = {$goods_id} ");
	    foreach($data as $k => $v){
	        if(!$v['kjt_sn']){
	            return false;
	        }
	    }
	    return $this -> checkGoodsPrice($goods_id);
	}
}