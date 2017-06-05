<?php

class Admin_Models_API_Product
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
     * 上传路径
     */
	private $upPath = 'upload';
	
    private $_logic_area = 1;
	/**
     * 缓存路径
     */
	private $_dataDir = null;
	
    private $_search_option = array();
    
    private $_lid;
    
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Product();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> _dataDir = Zend_Registry::get('systemRoot') . '/data/admin/stock/';
        $this -> _lid = $this -> _auth['lid'];

        $this->_search_option = array(
            'assemble_type' => array(
                '1' => '拆装',
                '2' => '组装',
            ),
            'assemble_status' => array(
                '0' => '未审核',
                '1' => '已审核',
                '2' => '已取消',
            ),
        );
	}

    /**
     * 返回搜索项
     *
     * @return    array
     **/
    public function getSearchOption()
    {
        return $this->_search_option;
    }
	
	/**
     * 获取商品实体数据
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
		    $where['product_id'] && $whereSql .= " and p.product_id='" . $where['product_id'] ."'";
		    $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    $where['product_sn'] && $whereSql .= " and product_sn LIKE '%" . $where['product_sn'] . "%'";
            $where['ean_barcode'] && $whereSql .= " and sys_sn LIKE '%" . $where['ean_barcode'] . "%'";
		    $where['local_sn'] && $whereSql .= " and local_sn LIKE '%" . $where['local_sn'] . "%'";
		    $where['product_name'] && $whereSql .= " and product_name LIKE '%" . $where['product_name'] . "%'";
            $where['product_img'] && $whereSql .= " and (product_img = '' or product_img is null)";
			$where['price_limit'] && $whereSql .= " and p.suggest_price < p.price_limit";
			$where['lid'] && $whereSql .= " and lid = '{$where['lid']}'";
		    if ($where['is_lock']) {
		    	$lock_name = $where['is_lock'] == 'yes' ? $this -> _auth['admin_name'] : '';
		    	$whereSql .= " and p_lock_name = '" . $lock_name . "'";
		    }
		    if ($where['p_status'] !== null && $where['p_status'] !== '') {
		        $whereSql .= " and p_status={$where['p_status']}";
		    }
		    if ($where['is_vitual'] !== null && $where['is_vitual'] !== '') {
		        $whereSql .= " and is_vitual={$where['is_vitual']}";
		    }
		    if ($where['is_gift_card'] !== null && $where['is_gift_card'] !== '') {
		        $whereSql .= " and is_gift_card={$where['is_gift_card']}";
		    }
		    if ($where['fromprice'] && $where['toprice']) {
			    $fromprice = intval($where['fromprice']);
			    $toprice = intval($where['toprice']);
			    if($fromprice <= $toprice) $whereSql .= " and (purchase_cost between $fromprice and $toprice)";
	        }
	      
		} else {
			$whereSql = $where;
		}
		//供应商id sid
		$mysid = $where['sid'] ;
		if(!empty($mysid)){
			$supplierAPI = new Admin_Models_API_Supplier();
			$supplier = array_shift($supplierAPI -> getSupplier("supplier_id={$mysid}"));
		    if(empty($supplier['product_ids'])) return false;
		    $sup_tmp_sql = " AND product_id in ({$supplier['product_ids']}) ";
		    $whereSql = $whereSql.$sup_tmp_sql;
		}
	    $datas = $this -> _db -> fetch($whereSql, $fields, $page, $pageSize, $orderBy);
	    if ($datas) {
    	    if ($where['returnStock']) {
    	        foreach ($datas as $data) {
    	            $productIDArray[] = $data['product_id'];
    	        }
    	        $stockAPI = new Admin_Models_API_Stock();
    	        $productBatch = $stockAPI -> getCreateProductBatch($productIDArray);
    	    }

            $config = Custom_Model_Stock_Base::getInstance(1);
		    $status = $config -> getConfigLogicStatus();
    	    
    		foreach ($datas as $num => $data) {
                $datas[$num]['status_id']   = $where['status_id'];
                $datas[$num]['status_name'] = $status[$where['status_id']];
                unset($datas[$num]['act_notes']);
                if ($where['returnStock']) {
                    $datas[$num]['productBatch'] = $productBatch[$data['product_id']] ? $productBatch[$data['product_id']] : 0;
                }
    	        $datas[$num]['pinfo'] = Zend_Json::encode($datas[$num]);
            }
	    }
	    
        return $datas;
	}
	
	/**
     * 获取商品状态列表
     *
     * @return   array
     */
	public function getStockStatus($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
        if (is_array($where)) {
			$whereSql = "1=1";
		    $where['filter'] && $whereSql .= $where['filter'];
		    if($where['logic_area']){
    			if(strstr($where['logic_area'], '_')) {
    				$fix = $where['logic_area'];
    			}else{
    				$whereSql .= " and a.lid = '" . $where['logic_area'] . "'";
    			}
    		}
            
		    $where['status_id'] && $whereSql .= " and status_id = '" . $where['status_id'] . "'";
		    $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    $where['product_sn'] && $whereSql .= " and product_sn LIKE '%" . $where['product_sn'] . "%'";
		    $where['local_sn'] && $whereSql .= " and local_sn LIKE '%" . $where['local_sn'] . "%'";
		    $where['goods_name'] && $whereSql .= " and product_name LIKE '%" . $where['goods_name'] . "%'";
			$where['ean_barcode'] && $whereSql .= " and ean_barcode LIKE '%" . $where['ean_barcode'] . "%'";

		    $where['product_ids'] && $whereSql .= " and p.product_id in ({$where['product_ids']})";
            if ($where['is_lock']) {
		    	$lock_name = $where['is_lock'] == 'yes' ? $this -> _auth['admin_name'] : '';
		    	$whereSql .= " and p_lock_name = '" . $lock_name . "'";
		    }
		    if ($where['fromprice'] && $where['toprice']) {
			    $fromprice = intval($where['fromprice']);
			    $toprice = intval($where['toprice']);
			    if($fromprice <= $toprice) $whereSql .= " and (price between $fromprice and $toprice)";
	        }
		} else {
			$whereSql = $where;
		}
		$datas = $this -> _db -> getStockStatus($whereSql, $fields, $page, $pageSize, $orderBy, $fix);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['plan_number'] = $datas[$num]['in_number'] - $datas[$num]['out_number'];
         	$datas[$num]['real_number'] = $datas[$num]['real_in_number'] - $datas[$num]['real_out_number'];
         	$datas[$num]['able_number'] = $datas[$num]['real_in_number'] - $datas[$num]['out_number'];
         	$datas[$num]['wait_number'] = $datas[$num]['in_number'] - $datas[$num]['real_in_number'];
         	$datas[$num]['left_number'] = $datas[$num]['out_number'] - $datas[$num]['real_out_number'];
	        $datas[$num]['pinfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
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
     * 获取状态信息
     *`
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1, \'ajax_status\');" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0, \'ajax_status\');" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	
	/**
     * 修改数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function edit($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        $channel = 0;
        if (trim($data['product_name']) == '') {
            $this -> error = 'empty_product_name';
            return false;
        }
        if (trim($data['goods_style']) == '') {
            $this -> error = 'empty_goods_style';
            return false;
        }
        
        if (!$id) {
            if (!$data['cat_id']) {
                $this -> error = 'empty_cat_id';
                return false;
            }
            if ($this -> _db -> fetchRow("product_name = '{$data['product_name']}'")) {
                $this -> error = 'exists_product_name';
                return false;
            }
            if ($this -> _db -> fetchRow("product_sn = '{$data['product_sn']}'")) {
                $this -> error = 'exists_product_sn';
                return false;
            }
            $row = array('product_sn' => $data['product_sn'].$this -> getProductLastSN($data['cat_id']),
                         'product_name' => $data['product_name'],
                         'cat_id' => $data['cat_id'],
                         'p_length' => $data['info']['p_length'] ? $data['info']['p_length'] : 0,
                         'p_width' => $data['info']['p_width'] ? $data['info']['p_width'] : 0,
                         'p_height' => $data['info']['p_height'] ? $data['info']['p_height'] : 0,
                         'p_weight' => $data['info']['p_weight'] ? $data['info']['p_weight'] : 0,
                         'local_sn' => $data['info']['local_sn'],
                         'renew_number' => $data['info']['renew_number'] ? $data['info']['renew_number'] : 0,
                         'p_status' => '1',
                         'goods_style' => $data['goods_style'],
                         'goods_units' => $data['goods_units'],
						 'characters' => $data['characters'],
                         'ean_barcode' => $data['info']['ean_barcode'],
                         'suggest_price' => $data['suggest_price'] ? $data['suggest_price'] : 0,
                         'purchase_cost' => $data['purchase_cost'] ? $data['purchase_cost'] : 0,
                         'init_cost' => $data['purchase_cost'] ? $data['purchase_cost'] : 0,
						 'cost' => $data['purchase_cost'] ? $data['purchase_cost'] : 0,
                         'cost_tax' => $data['cost_tax'] ? $data['cost_tax'] : 0,
                         'invoice_tax_rate' => $data['invoice_tax_rate'] ? $data['invoice_tax_rate'] : 0,
                         'is_vitual' => $data['is_vitual'] ? $data['is_vitual'] : 0,
                         'is_gift_card' => $data['is_gift_card'] ? $data['is_gift_card'] : 0,
                         'lid' => $this -> _lid
                        );
            $sys_sn = $this -> getRand(1);
    	    $row['sys_sn'] = $sys_sn[0];
            $lastId = $this -> _db -> add($row);
            if (!$lastId)   return false;
            
            $row = array('product_id' => $lastId,
                         'goods_sn' => $row['product_sn'],
                         'goods_name' => $data['product_name'],
                         'view_cat_id' => $data['cat_id'],
                         'onsale' => 1
                         );
            $this -> _db -> addGoods($row);
            
    		return true;
        }
        else {

			/*
            $ean_barcode=$data['info']['ean_barcode'];
            if (isset($ean_barcode) &&  $this -> _db -> fetchRow("ean_barcode = '{$ean_barcode}' and ean_barcode!='' and product_id != {$id}")) {
                $this -> error = 'exists_ean_barcode';
                return false;
            }
			*/

            if ($this -> _db -> fetchRow("product_sn = '{$data['product_sn']}' and product_id != {$id}")) {
                $this -> error = 'exists_product_sn';
                return false;
            }

            $row = array('product_name' => $data['product_name'],
                         'p_length' => $data['info']['p_length'] ? $data['info']['p_length'] : 0,
                         'p_width' => $data['info']['p_width'] ? $data['info']['p_width'] : 0,
                         'p_height' => $data['info']['p_height'] ? $data['info']['p_height'] : 0,
                         'p_weight' => $data['info']['p_weight'] ? $data['info']['p_weight'] : 0,
                         'local_sn' => $data['info']['local_sn'],
                         'renew_number' => $data['info']['renew_number'] ? $data['info']['renew_number'] : 0,
                         'goods_style' => $data['goods_style'],
                         'goods_units' => $data['goods_units'],
                         'ean_barcode' => $data['info']['ean_barcode'],
						 'characters' => $data['characters'],
                         'is_vitual' => $data['is_vitual'] ? $data['is_vitual'] : 0,
                         'is_gift_card' => $data['is_gift_card'] ? $data['is_gift_card'] : 0,
                         'p_lock_name' => '',
                        );
            if ($data['cat_id']) {
                $row['cat_id'] = $data['cat_id'];
            }
            return $this -> _db -> update($row, "product_id=$id and p_lock_name='{$this->_auth['admin_name']}'");
        }
	}
	
	/**
     * 更新数据
     *
     * @param    array    $set
     * @param    string   $where
     * @return   array
     */
    public function update($set, $where)
	{
	    $this -> _db -> update($set, $where);
	    return true;
	}
	
	/**
     * 锁定/解锁数据
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function lock($datas, $val)
	{
		if (is_array($datas['ids'])) {
			foreach($datas['ids'] as $v){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($val) {
			    	$set = array('p_lock_name' => $admin_name);
			    	$where = " (p_lock_name = '' )";
			    } else {
			    	$set = array('p_lock_name' => '');
			    	$where = ($this -> _auth['group_id'] > 0 ) ? "p_lock_name='$admin_name'" : '1';
			    }
	    		$this -> _db -> update($set, "$where and product_id=$v");
			}
		}
        return true;
	}
	
	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
            if (in_array($field, array('img_sort'))) {
                $this->_db->updateImg($id, array($field => $val));
            } else if ($this -> _db -> ajaxUpdate((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }else{
		        if ($field == 'adjust_num' && $val > 0) {
		            $product = $this -> _db -> fetchRow("product_id = '{$id}'");
		            $stockAPI = new Admin_Models_API_Stock();
		            $stockAPI -> initProductStockRecord($id, $product['lid']);
		        }
		    	return true;
		    }
		}
	}
	
	/**
     * 导入商品资料
     *
     * @param   array   $data
     * @return void
     */
    public function import($data)
    {
    	if ($data['type'] != 'application/vnd.ms-excel') {
           $this -> error = '上传文件格式错误';
           return false;
        }
        
        if (!$data['tmp_name']) {
            $this -> error = '上传文件失败';
            return false;
        }
        $path = Zend_Registry::get('systemRoot');
        
        $ext = strtolower(trim(substr(strrchr($data['name'], '.'), 1, 10)));
        $file = $path.'/tmp/'.substr(md5(microtime()), 20).'.'.$ext;
        
        move_uploaded_file($data['tmp_name'], $file);
        @chmod($file, 0644);
        
        require_once 'Excel/Reader.php';
        
        $data = new Spreadsheet_Excel_Reader();
        
        $data -> setOutputEncoding('UTF-8');
        
        $data -> read($file);
        
        error_reporting(E_ALL ^ E_NOTICE);
        
	    for ($i = 2; $i <= $data -> sheets[0]['numRows']; $i++) {
			for ($j = 1; $j <= $data -> sheets[0]['numCols']; $j++) {
				$d[] = $data -> sheets[0]['cells'][$i][$j];
				if ($j == 1) {
				    $id = $data -> sheets[0]['cells'][$i][$j];
				}
			}
			$datas[$id] = $d;
			unset($d);
		}
		foreach ($datas as $k => $v) {
			$where = "product_id='$k'";
			$set = array(
			             'p_length' => $v[6],
			             'p_width' => $v[7],
			             'p_height' => $v[8],
			             'p_weight' => $v[9],
			             'local_sn' => $v[10],
			             'renew_number' => (int)$v[11],
			             );
			$set && $this -> _db -> update($set, $where);
			unset($set);
		}
		
		@unlink($file);
        return true;
    }
	
	/**
     * 导出商品资料
     *
     * @return void
     */
    public function export($param)
    {
        $excel = new Custom_Model_Excel();
		$excel -> send_header('product.xls');
		$excel -> xls_BOF();
    	$title = array('商品ID', '商品编码', '商品名称', '规格', '供应商', '长', '宽', '高',
                       '重量', '国际码', '商品状态');
        
        $col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
        $param['lid']= $this -> _lid;
    	$datas = $this -> get($param, "product_id,product_sn,product_name,goods_style,produce_cycle,p_length,p_width,p_height,p_weight,local_sn,ean_barcode,p_status");
    	if ($datas) {
            foreach ($datas as $num => $data) {
                $productIDArray[] = $data['product_id'];
            }
            $supplierAPI = new Admin_Models_API_Supplier();
            $supplierData = $supplierAPI -> getProductSupplier($productIDArray);
            if ($supplierData) {
                foreach ($supplierData as $supplier) {
                    $ids = explode(',', $supplier['product_ids']);
                    foreach ($ids as $productID) {
                        $productSupplierInfo[$productID][] = $supplier['supplier_name'];
                    }
                }
                foreach ($datas as $num => $data) {
                    $productSupplierInfo[$data['product_id']] && $datas[$num]['supplier'] = implode(',', $productSupplierInfo[$data['product_id']]);
                }
            }
            
            foreach ($datas as $k => $v) {
    			$goodsStatus = $v['p_status'] ? '冻结' : '正常';
    			$row = array($v['product_id'], $v['product_sn'], $v['product_name'], $v['goods_style'], $v['supplier'], $v['p_length'], $v['p_width'], $v['p_height'], $v['p_weight'], $v['ean_barcode'],$goodsStatus);
    			for ($i = 0; $i < $col; $i++) {
    			    $excel -> xls_write_label($k+1, $i, $row[$i]);
    			}
    			flush();
    		    ob_flush();
    			unset($row);
            }
            unset($datas);
        }
		
		$excel -> xls_EOF(); 
    }
	
	/**
     * 导出商品报表资料
     *
     * @return void
     */
    public function saleExport($where)
    {
        $excel = new Custom_Model_Excel();
		$excel -> send_header('salereport.xls');
		$excel -> xls_BOF();
    	$title = array('商品ID', '商品编号','商品名称','商品规格','成本价', '现价', '销售量', '销售总金额', '平均售价', '商品状态');
        $col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
        $datas = $this -> getReport($where);
		foreach ($datas as $k => $v)
        {
            $v['average_price']=$v['total_money'] / $v['total'];
			$goodsStatus = $v['onsale'] ? '下架' : '上架';
			$row = array($v['goods_id'], $v['goods_sn'], $v['goods_name'],$v['goods_style'], $v['cost'],$v['price'], $v['total'],$v['total_money'],$v['average_price'], $goodsStatus );
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
     * 更新前台销售排行
     *
     * @return void
     */
    public function updateSale($params)
    {
    	$where['fromdate'] = date('Y-m-d', time() - 86400 * 60);//两个月内销量统计
    	$where['todate'] = date('Y-m-d', time());
    	
    	$datas = $this -> getReport($where);
    	if($datas){
    		$goods = new Admin_Models_DB_Goods();
    		$goods -> updateGoods(array('sort_sale' => '0'), "sort_sale>0");
			foreach ($datas as $k => $v)
	        {
	        	$goods -> updateGoods(array('sort_sale' => $k+1), "goods_id='{$v['goods_id']}'");
	        }
        }
        return true;
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
			         'exists_product_name'=>'产品名称已存在!',
			         'exists_product_sn'=>'产品编码已存在!',
			         'not_exists'=>'该产品不存在!',
			         'forbidden'=>'禁止操作!',
                     'exists_ean_barcode'=>'该商品国际码已经存在!',
                     'empty_product_sn'=>'产品编码不能为空!',
                     'empty_product_name'=>'产品名称不能为空!',
                     'empty_goods_style'=>'产品规格不能为空!',
                     'empty_cat_id'=>'系统分类不能为空!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
	/**
     * 获取图片信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getImg($where)
	{
		return $this -> _db -> getImg($where);
	}
	
	/**
     * 上传图片  产品图片类型  0标准图  1色块图  2细节图  3展示图  4规格图
     *
     * @return   void
     */
	public function upimg($data, $product_id, $product_sn)
	{
        $imageAPI = new Custom_Model_Image();
        $imageAPI -> watermark_enable = true;
        
        $_path = strtolower(substr(md5($product_sn),0,2));
        $_path = $_path ? $_path : '00';
		$this -> upPath .= '/'.$_path.'/'.$product_sn;
		$add_time = time();
		//添加修改产品标准图
		if(is_file($_FILES['product_img']['tmp_name'])) {
		    $thumbs = '380,380|180,180|60,60';
			$upload = new Custom_Model_Upload('product_img', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> addImg($product_id, $img_url, 0, $add_time, $thumbs);
			$this -> _db -> update(array('product_img' => $img_url), "product_id = {$product_id}");
			
			$filename = str_replace('.jpg', '_380_380.jpg', $img_url);
			$imageAPI -> watermark($filename, null, 3, 'images/admin/water.gif');
		}
		//添加修改规格图
		if(is_file($_FILES['product_arr_img']['tmp_name'])) {
		    $thumbs = '380,380|180,180';
			$upload = new Custom_Model_Upload('product_arr_img', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> update(array('product_arr_img' => $img_url), "product_id = {$product_id}");
		}
		//添加产品细节图
		if(is_file($_FILES['img_url']['tmp_name'][0])) {
			$thumbs = '380,380|180,180|60,60';
			$upload = new Custom_Model_Upload('img_url', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			for($i = 0; $i < count($_FILES['img_url']['name']); $i++){
				$img_url = $this -> upPath.'/'.$upload->uploadedfiles[$i]['filepath'];
			    if($img_url) {
			        $this -> _db -> addImg($product_id, $img_url, 2, $add_time, $thumbs, $data['img_desc'][$i], $data['img_sort'][$i]);
        			$filename = str_replace('.jpg', '_380_380.jpg', $img_url);
        			$imageAPI -> watermark($filename, null, 3, 'images/admin/water.gif');
			    }
			}
		}
		
		//添加产品展示图
		if(is_file($_FILES['img_ext_url']['tmp_name'][0])) {
			$thumbs = '380,380|60,60';
			$upload = new Custom_Model_Upload('img_ext_url', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			for($i = 0; $i < count($_FILES['img_ext_url']['name']); $i++){
				$img_url = $this -> upPath.'/'.$upload->uploadedfiles[$i]['filepath'];
			    if($img_url) $this -> _db -> addImg($product_id, $img_url, 3, $add_time, $thumbs, $data['img_ext_desc'][$i], $data['img_ext_sort'][$i]);
			}
		}
	}
	
	/**
     * 删除图片
     *
     * @param    int      $id
     * @return   void
     */
	public function deleteImg($id)
	{
	    $where = "img_id = '$id'";
	    $res = $this -> _db -> getImg($where);
		foreach ($res as $r) {
		    if(trim($r['thumbs']) != ''){
			    $thumbs = explode('|', $r['thumbs']);
			    foreach($thumbs as $v){
			        $t = explode(',', $v);
			        $img_url = str_replace('.', '_'.$t[0].'_'.$t[1].'.', $r['img_url']);
				}
			}
		}
		return $this -> _db -> deleteImg($id);
	}
	
	/**
     * 获取随机数
     *
     * @return   int
     */
	public function getRand($num)
	{
	    $file = $this -> _dataDir.'sys.sn';
	    $last = $file.'.last';
	    if (!is_file($file)) {
		    exit('error');
		    $list = 99999;
			for($s; $s < $list; $s++) {
			    $a[] = $s;
			}
			for($i; $i < $list; $i = $key) {
		        for($n = mt_rand(0,($list - 1)); $a[$n] == $n; $a[$n] = $list) {
		                $key++;
		                $n>10000 && $array[] = $n;
		        }
			}
			$r = $array[0];
	        file_put_contents($file, Zend_Json::encode($array));
	        file_put_contents($last, '0');
	        unset($array);
	        return $r;
	    } else {
	    	$array = Zend_Json::decode(file_get_contents($file));
	    	if ($array[0] != '45914' || $array[count($array) - 1] != '94250' || count($array) != 89998) exit('error');
		    $p = array_shift($this -> _db -> fetch(null,'product_id'));
		    $lastKey = $p['product_id'];
	    	for ($i = 1; $i <= $num; $i++) {
	    	    $r[] = $array[$lastKey + $i];
	    	}
	    	unset($array);
	    	return $r;
	    }
	}
	
	/**
     * 修改产品价格
     *
     * @return   int
     */
	public function editPrice($id, $data)
	{

	    $row = array('suggest_price' => $data['suggest_price'],
	                 'purchase_cost' => $data['purchase_cost'],
	                 'cost_tax' => $data['cost_tax'],
	                 'invoice_tax_rate' => $data['invoice_tax_rate'],
	                 'p_lock_name' => '',
					 'price_limit' => $data['price_limit'],
	                );
         $rs = $this -> _db -> fetchRow("product_id = {$id}");
         if ($rs['cost'] <= 0) {
			$row['cost'] = $data['purchase_cost'];
		 }
		 if ($rs['init_cost'] <= 0) {
			$row['init_cost'] = $data['purchase_cost'];
		 }
	     return $this -> _db -> update($row, "product_id={$id} and p_lock_name='{$this->_auth['admin_name']}'");
	}
	
	/**
     * 获得产品批次信息(按批次)
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getBatch($where = null, $fields = 't1.*,t2.product_name,t2.goods_style,t3.cat_name,t4.supplier_name', $page=null, $pageSize = null, $orderBy = null)
	{
		if (is_array($where)) {
			$whereSql = "1";
			$where['batch_id'] && $whereSql .= " and batch_id ='" . $where['batch_id'] ."'";
		    $where['batch_no'] && $whereSql .= " and batch_no like '%" . $where['batch_no'] ."%'";
		    $where['product_id'] && $whereSql .= " and t1.product_id ='" . $where['product_id'] ."'";
		    $where['product_ids'] && $whereSql .= " and t1.product_id in (".implode(',', $where['product_ids']).")";
		    $where['product_name'] && $whereSql .= " and t2.product_name like '%" . $where['product_name'] ."'%";
		    $where['product_sn'] && $whereSql .= " and t1.product_sn = '" . $where['product_sn'] . "'";
		    $where['cat_id'] && $whereSql .= " and t3.cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    $where['barcode'] && $whereSql .= " and barcode LIKE '%" . $where['barcode'] . "%'";
            $where['supplier_id'] && $whereSql .= " and t4.supplier_id = '" . $where['supplier_id'] . "'";
		    if ($where['status'] !== null && $where['status'] !== '') {
		        $whereSql .= " and t1.status={$where['status']}";
		    }
		} else {
			$whereSql = $where;
		}

		return $this -> _db -> fetchBatch($whereSql, $fields, $page, $pageSize, $orderBy);
	}
	
	/**
     * 获得产品批次信息(按产品)
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getProductBatch($where = null, $fields = 't1.*,t2.batch_id,t2.batch_no', $page=null, $pageSize = null, $orderBy = null)
	{
		if (is_array($where)) {
			$whereSql = "1";
			$where['batch_id'] && $whereSql .= " and batch_id ='" . $where['batch_id'] ."'";
		    $where['batch_no'] && $whereSql .= " and batch_no like '%" . $where['batch_no'] ."%'";
		    $where['product_id'] && $whereSql .= " and t1.product_id ='" . $where['product_id'] ."'";
		    $where['product_ids'] && $whereSql .= " and t1.product_id in (".implode(',', $where['product_ids']).")";
		    $where['product_name'] && $whereSql .= " and t1.product_name like '%" . $where['product_name'] ."%'";
		    $where['product_sn'] && $whereSql .= " and t1.product_sn = '" . $where['product_sn'] . "'";
		    $where['cat_id'] && $whereSql .= " and t3.cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    $where['barcode'] && $whereSql .= " and barcode LIKE '%" . $where['barcode'] . "%'";
            $where['supplier_id'] && $whereSql .= " and t4.supplier_id = '" . $where['supplier_id'] . "'";
            $where['logic_area'] && $whereSql .= " and lid ='" . $where['logic_area'] ."'";
            $where['lid'] && $whereSql .= " and lid ='" . $where['lid'] ."'";
		    if ($where['p_status'] !== null && $where['p_status'] !== '') {
		        $whereSql .= " and t1.p_status={$where['p_status']}";
		    }
		} else {
			$whereSql = $where;
		}

		return $this -> _db -> fetchProductBatch($whereSql, $fields, $page, $pageSize, $orderBy);
	}
	
	
	/**
     * 修改批次数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function editBatch($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        if (!$id) {
            $data['batch_no'] = $data['product_sn'].date('ymd');
            $datas = $this -> _db -> fetchBatch("batch_no like '{$data['batch_no']}%'", 'batch_no');
            if ($datas['data']) {
                $no = substr($datas['data'][0]['batch_no'], -2);
                $no++;
                if ($no >= 10) {
                    $data['batch_no'] .= $no;
                }
                else {
                    $data['batch_no'] .= '0'.$no;
                }
            }
            else {
                $data['batch_no'] .= '01';
            }
            
            $set = array('batch_no' => $data['batch_no'],
                         'product_id' => $data['product_id'],
                         'product_sn' => $data['product_sn'],
                         'barcode' => $data['batch_no'],
                         'cost' => $data['cost'],
                         'cost_tax' => $data['cost_tax'],
                         'invoice_tax_rate' => $data['invoice_tax_rate'],
                         'supplier_id' => $data['supplier_id'],
                         'product_date' => $data['product_date'] ? strtotime($data['product_date']) : 0,
                         'expire_date' => $data['expire_date'] ? strtotime($data['expire_date']) : 0,
                         'add_time' => time(),
                         'admin_name' => $this -> _auth['admin_name'],
                         'status' => $data['status'],
                        );
            $batchID = $this -> _db -> insertBatch($set);
            
            $stockAPI = new Admin_Models_API_Stock();
            $productBatch = $stockAPI -> getCreateProductBatch($data['product_id']);
            if ($productBatch[$data['product_id']] == 2) {
                $stockAPI -> initProductBatch($data['product_id'], $batchID);
            }
        }
        else {
            $set = array('cost' => $data['cost'],
                         'cost_tax' => $data['cost_tax'],
                         'invoice_tax_rate' => $data['invoice_tax_rate'],
                         'supplier_id' => $data['supplier_id'],
                         'product_date' => $data['product_date'] ? strtotime($data['product_date']) : 0,
                         'expire_date' => $data['expire_date'] ? strtotime($data['expire_date']) : 0,
                         'admin_name' => $this -> _auth['admin_name'],
                         'status' => $data['status'],
                        );
            $this -> _db -> updateBatch($set, "batch_id = {$id}");
        }
        
        return true;
    }
    
    /**
     * 更新产品批次
     *
	 * @param    array      $set
	 * @param    string     $where
     * @return   void
     */
	public function updateBatch($set, $where)
	{
	    $this -> _db -> updateBatch($set, $where);
	}
	
	/**
     * 获取商品实体数据(包含批次信息)
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getProductWithBatch($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
	    $datas = $this -> get($where, $fields, $page, $pageSize, $orderBy);
	    if (!$datas)    return false;
	    
	    foreach ($datas as $data) {
	        $productIDArray[] = $data['product_id'];
	    }
        
	    $batchData = $this -> getBatch(array('product_ids' => $productIDArray));
	    if (!$batchData['data'])    return $datas;
	    
	    foreach ($batchData['data'] as $data) {
	        $batchInfo[$data['product_id']][] = $data;
	    }
	    
	    foreach ($datas as $index => $data) {
	        $datas[$index]['batch'] = $batchInfo[$data['product_id']];
	        $datas[$index]['pinfo'] = Zend_Json::encode($datas[$index]);
	    }

	    return $datas;
	}
	
	/**
     * 获得产品性状
     *
	 * @param    string     $where
     * @return   array
     */
	public function getCharacters($where = 1)
	{
	    return $this -> _db -> getCharacters($where);
	}
	
	/**
     * 获得产品编码前缀
     *
	 * @param    int        $catID
     * @return   string
     */
	public function getProductPrefixSn($catID)
	{
	    $catAPI = new Admin_Models_API_Category();
	    
	    $category = array_shift($catAPI -> getProductCat("cat_id = '{$catID}'"));
	    if ($category['parent_id'] == 0) {
	        return false;
	    }
	    
	    $result = $category['cat_sn'];
	    $category = array_shift($catAPI -> getProductCat("cat_id = '{$category['parent_id']}'"));
	    if ($this -> _lid == 1) {
	        return 'H'.$category['cat_sn'].$result;
	    }
	    else if ($this -> _lid == 2) {
	        return 'J'.$category['cat_sn'].$result;
	    }
	}
	
	/**
     * 获得产品编码前缀
     *
	 * @param    int        $catID
     * @return   string
     */
	public function getProductLastSN($catID)
	{
	    $product = array_shift($this -> _db -> fetch("p.cat_id = '$catID'", 'p.product_sn', 1, 1, 'p.product_sn desc'));
	    if ($product) {
            $result = substr($product['product_sn'], 5, 3);
        }
        else {
            $result = 0;
        }
        $result++;
        return substr('00'.$result, -3);
	}
	/*根据产品id 获取当前移动价格cost*/
	public function getCostByProudId($proudId){
	    $sql = "SELECT cost from shop_product where product_id = ".$proudId;
	    $obj = $this->_db->fetchRow($sql);
	    return $obj['cost'];
	}


	/**
	 * 取得键的值
     *
	 * @param    array
	 * @param    string
	 *
	 * @return   array
	 */
	public function getSingleKey($array , $key)
	{
		$array2 = array();
		foreach ($array as $val) {
			isset($val[$key]) && $array2[$val[$key]] = $val[$key];
		}

		return $array2;
	}

	/**
	 * 按键组合数组
     *
	 * @param    array
	 * @param    string
	 *
	 * @return   array
	 */
	public function singleGroup($array, $key)
	{
		$array2 = array();
		foreach ($array as $val) {
			isset($val[$key]) && $array2[$val[$key]] = $val;
		}

		return $array2;
	}
	
	/**
     * 计算产品移动成本(已入库)
     * 
     * @param    int    $productID
     * @param    int    $number
     * @param    float  $price
     *
     * @return   boolean
     */
    public function calculateProductCostByIn($productID, $number, $price)
    {
        if (!$this -> _stockAPI) {
            $this -> _stockAPI = new Admin_Models_API_Stock();
        }
        $stockInfo = $this -> _stockAPI -> getProductStock(array('product_id' => $productID));
        //$initNumber = $stockInfo['real_number'] + $this -> _stockAPI -> getCalculateProductCostSpecialStockNumber($productID) - $number ;
        $initNumber = $stockInfo['real_number'] - $number;
        if ($initNumber < 0)    return false;
        
        if ($initNumber > 0) {
            $product = $this -> _db -> fetchRow("product_id = '{$productID}'");
            if (!$product)  return false;
            $cost = round(($product['cost'] * $initNumber + $price * $number) / ($initNumber + $number), 3);
        }
        else {
            $cost = $price;
        }
        
        $this -> _db -> update(array('cost' => $cost), "product_id = '{$productID}'");
        
        return true;
    }
    
    /**
     * 计算产品移动成本(已出库)
     * 
     * @param    int    $productID
     * @param    int    $number
     * @param    float  $price
     *
     * @return   boolean
     */
    public function calculateProductCostByOut($productID, $number, $price)
    {
        if (!$this -> _stockAPI) {
            $this -> _stockAPI = new Admin_Models_API_Stock();
        }
        
        $stockInfo = $this -> _stockAPI -> getProductStock(array('product_id' => $productID));
        //$initNumber = $stockInfo['real_number'] + $this -> _stockAPI -> getCalculateProductCostSpecialStockNumber($productID) + $number;
        $initNumber = $stockInfo['real_number'] + $number;
        if ($initNumber - $number < 0)  return false;
        
        if ($initNumber - $number == 0) {
            return true;
        }
        else {
            $product = $this -> _db -> fetchRow("product_id = '{$productID}'");
            if (!$product)  return false;
            $cost = round(($product['cost'] * $initNumber - $price * $number) / ($initNumber - $number), 3);
        }
        
        $this -> _db -> update(array('cost' => $cost), "product_id = '{$productID}'");
        
        return true;
    }
	
	/**
	 * 按初始成本重新计算产品移动成本
     *
	 * @param    int/array  productID
	 * @param    boolean    updateProductCost
	 * @param    boolean    updateStockBill
	 * @param    array      reportParam
	 *
	 * @return   void
	 */
	public function calculateProductCostByInitCost($productID = null, $updateProductCost = false, $updateStockBill = false, $reportParam = false)
	{
	    $stockAPI = new Admin_Models_API_Stock();
	    $inStockAPI = new Admin_Models_DB_InStock();
	    $outStockAPI = new Admin_Models_DB_OutStock();

	    if ($productID) {
	        if (!is_array($productID)) {
	            $productID = array($productID);
	        }
	    }
	    else {
	        $productID = $stockAPI -> getActiveProductID();
	    }
	    if (!$productID)    return false;
	    
	    $productList = $this -> _db -> fetch("p.product_id in (".implode(',', $productID).")", 'p.product_sn,p.product_id,p.product_name,p.init_cost,p.cost,p.invoice_tax_rate');
	    if (!$productList)  return false;
	    
	    foreach ($productList as $product) {
	        $stockDetail = $stockAPI -> getOutInStockDetail($product['product_id']);
	        if (!$stockDetail)  continue;
            
	        $cost = $currentCost = $stockNumber = 0;
	        $outstockDetail = $instockDetail = array();
	        foreach ($stockDetail as $index => $detail) {
	            if ($index == 0) {
	                if ($detail['type'] == 'outstock') {
	                     die($detail['bill_no'].' out stock firstly!');
	                }
	                if (in_array($detail['bill_type'], array(2,5,14,17,18))) {
	                    $currentCost = $detail['price'];
	                }
	                else {
	                    $currentCost = $product['init_cost'];
	                    $instockDetail[$detail['bill_no']]['instock_id'] = $detail['id'];
	                    $instockDetail[$detail['bill_no']]['shop_price'] = $currentCost;
	                    $instockDetail[$detail['bill_no']]['finish_time'] = $detail['finish_time'];
	                    $detail['price'] = $currentCost;
	                }
                    
	                if ($reportParam) {
    	                $result[$product['product_id']]['product_sn'] = $product['product_sn'];
    	                $result[$product['product_id']]['product_name'] = $product['product_name'];
    	                $result[$product['product_id']]['invoice_tax_rate'] = $product['invoice_tax_rate'];
    	                
    	                if ($detail['finish_time'] > $reportParam['todate']) {
    	                    
    	                }
    	                else {
    	                    if ($detail['finish_time'] >= $reportParam['fromdate']) {
                                $result[$product['product_id']]['from_cost'] = 0;
        	                    $result[$product['product_id']]['from_number'] = 0;
        	                    $result[$product['product_id']]['instock'][] = $detail;
        	                }
        	                else {
        	                    $result[$product['product_id']]['from_cost'] = $currentCost;
        	                    $result[$product['product_id']]['from_number'] = $detail['number'];
        	                }
        	                if ($detail['finish_time'] < $reportParam['todate']) {
        	                    $result[$product['product_id']]['to_cost'] = $currentCost;
                                $result[$product['product_id']]['to_number'] = $detail['number'];
        	                }
        	            }
    	            }
	                
	                $stockNumber += $detail['number'];
	            }
	            else {
        	        if ($detail['finish_time'] >= $reportParam['fromdate'] && $detail['finish_time'] <= $reportParam['todate']) {
        	            if (!isset($result[$product['product_id']]['from_cost'])) {
        	                $result[$product['product_id']]['from_cost'] = $currentCost;
        	                $result[$product['product_id']]['from_number'] = $stockNumber;
        	            }
	                }
	                if ($detail['type'] == 'instock') {
	                    if (in_array($detail['bill_type'], array(2,5,14,17,18,20))) {
	                        $cost = $detail['price'];
	                    }
	                    else if (in_array($detail['bill_type'], array(1,3,4,10,11,12,13,15,19))) {
	                        if (isset($outstockDetail[$detail['item_no']])) {
	                            $cost = $outstockDetail[$detail['item_no']]['cost'];
	                        }
	                        else if (isset($splitOutStockDetail[$detail['item_no']])) {
	                            $cost = $splitOutStockDetail[$detail['item_no']]['cost'];
	                        }
	                        else {
	                            die($detail['bill_no'].' can not find outstock');
	                        }
	                    }
	                    else if (in_array($detail['bill_type'], array(6))) {    //第2次的盘点调整入库单取当前移动成本
	                        $cost = $currentCost;
	                    }
	                    else {
	                        $cost = $currentCost;
	                    }
	                    
	                    if ($detail['price'] != $cost) {
	                        $instockDetail[$detail['bill_no']]['instock_id'] = $detail['id'];
	                        $instockDetail[$detail['bill_no']]['shop_price'] = $cost;
	                        $instockDetail[$detail['bill_no']]['finish_time'] = $detail['finish_time'];
	                    }
	                    
	                    if ($stockNumber + $detail['number'] > 0) {
	                        $currentCost = round(($currentCost * $stockNumber + $cost * $detail['number']) / ($stockNumber + $detail['number']), 3);
	                    }
	                    
    	                $stockNumber += $detail['number'];
	                }
	                else if ($detail['type'] == 'outstock') {
	                    if (in_array($detail['bill_type'], array(17))) {
	                        if ($stockNumber - $detail['number'] > 0) {
	                            $currentCost = round(($currentCost * $stockNumber - $detail['price'] * $detail['number']) / ($stockNumber - $detail['number']), 3);
	                        }
	                    }
	                    else {
	                        $outstockDetail[$detail['bill_no']]['outstock_id'] = $detail['id'];
	                        $outstockDetail[$detail['bill_no']]['cost'] = $currentCost;
	                        $outstockDetail[$detail['bill_no']]['finish_time'] = $detail['finish_time'];
	                        if (strpos($detail['bill_no'], ',') !== false) {
	                            $tempBillNoArray = explode(',', $detail['bill_no']);
	                            foreach ($tempBillNoArray as $billNo) {
	                                $splitOutStockDetail[$billNo]['outstock_id'] = $detail['id'];
	                                $splitOutStockDetail[$billNo]['cost'] = $currentCost;
	                            }
	                        }
	                    }
	                    
	                    $stockNumber -= $detail['number'];
	                }
	                
	                if ($reportParam) {
        	            if ($detail['finish_time'] >= $reportParam['fromdate'] && $detail['finish_time'] <= $reportParam['todate']) {
        	                if ($detail['type'] == 'instock') {
        	                    $result[$product['product_id']]['instock'][] = $detail;
        	                }
        	                else if ($detail['type'] == 'outstock') {
        	                    $result[$product['product_id']]['outstock'][] = $detail;
        	                }
        	                
                            $result[$product['product_id']]['to_cost'] = $currentCost;
                            $result[$product['product_id']]['to_number'] = $stockNumber;
        	            }
        	            else if ($detail['finish_time'] < $reportParam['fromdate']) {
        	                $result[$product['product_id']]['to_cost'] = $currentCost;
                            $result[$product['product_id']]['to_number'] = $stockNumber;
        	            }
        	            if ($detail['finish_time'] < $reportParam['fromdate']) {
        	                $result[$product['product_id']]['from_cost'] = $currentCost;
        	                $result[$product['product_id']]['from_number'] = $stockNumber;
        	            }
        	        }
	            }
	        }

	        if (!$reportParam) {
    	        if ($product['cost'] != $currentCost) {
    	            echo $product['product_sn'].' '.$product['product_name'].' '.$product['cost'].' -> '.$currentCost.'<br>';
    	            if ($updateProductCost) {
    	                $this -> _db -> update(array('cost' => $currentCost), "product_id = '{$product['product_id']}'");
    	            }
    	            if ($updateStockBill) {
    	                if ($instockDetail) {
    	                    foreach ($instockDetail as $detail) {
    	                        if ($detail['finish_time'] >= 1388505600) {
    	                            $inStockAPI -> updatedetail(array('shop_price' => $detail['shop_price']), "instock_id = '{$detail['instock_id']}' and product_id = '{$product['product_id']}'");
    	                            $inStockAPI -> updatePlan(array('shop_price' => $detail['shop_price']), "instock_id = '{$detail['instock_id']}' and product_id = '{$product['product_id']}'");
    	                        }
    	                    }
    	                }
    	                if ($outstockDetail) {
    	                    foreach ($outstockDetail as $detail) {
    	                        if ($detail['finish_time'] >= 1388505600) {
    	                            $outStockAPI -> updateDetail(array('cost' => $detail['cost']), "outstock_id = '{$detail['outstock_id']}' and product_id = '{$product['product_id']}'");
    	                        }
    	                    }
    	                }
    	            }
    	        }
    	    }
	    }
	    
	    if ($reportParam) {
	        return $result;
	    }
	    
	    return true;
	}

	/**
     * 获取礼品卡信息列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function getGiftcardList($params, $limit = null)
	 {	
		$infos = $this->_db->getGiftcardList($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['created_ts'] = empty($info['add_time']) ? '' : date('Y-m-d H:i:s', $info['add_time']);
			$info['update_ts']  = empty($info['update_time']) ? '' : date('Y-m-d H:i:s', $info['update_time']);
		}

		return $infos;
	 }

	 /**
     * 获取礼品卡总数
     *
     * @param    array  
     *
     * @return   int
     */
    public function getGiftcardCount($params)
	{	
		$count = $this->_db->getGiftcardCount($params);

		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
	}

	/**
     * 更新礼品卡金额
     *
     * @param    int
	 * @param    decimal
     *
     * @return   boolean
     */
	public function updateGiftcardAmountByProductid($product_id, $amount)
	{
		$product_info = $this->_db->getProductInfoByProductid($product_id);

		if (false === $product_info) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if ($product_info['is_gift_card'] == '0') {
			$this->_error = '不是礼品卡数据操作';
			return false;
		}

		$gift_info  = $this->_db->getGiftcardInfoByProductid($product_id);
        
		if (empty($gift_info)) {
			$gift_params = array(
				'product_id' => $product_info['product_id'],
				'product_sn' => $product_info['product_sn'],
				'amount'     => $amount,
				'admin_name' => $this->_auth['admin_name'],
				'add_time'   => time(),
			);
			if (false === $this->_db->insertGiftinfo($gift_params)) {
				$this->_error = '操作失败，请重试';
				return false;
			}
		} else {
			$gift_params = array(
				'amount'      => $amount,
				'admin_name'  => $this->_auth['admin_name'],
				'update_time' => time(),
			);
			if (false  === $this->_db->updateGiftInfoByProductid($product_id, $gift_params)) {
				$this->_error = '操作失败，请重试';
				return false;
			}
		}

		return true;
	}

	/**
     * 根据产品ID获取礼品卡信息
     *
     * @param    int
     *
     * @return   array
     */
	public function getGiftcardInfoByProductid($product_id)
	{
		$info = $this->_db->getGiftcardInfoByProductid($product_id);

		if (false === $info) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($info) < 0) {
			$this->_error = '没有相关礼品卡信息';
			return false;
		}

		foreach ($info as $key=> $inf) {
			if ($key == 'add_time') {
				$info['created_ts'] = empty($info['add_time']) ? '' : date('Y-m-d H:i:s', $info['add_time']);
			} else if ($key == 'update_time') {
				$info['update_ts']  = empty($info['update_time']) ? '' : date('Y-m-d H:i:s', $info['update_time']);
			}
		}

		return $info;
	}

    /**
     * 组装开单申请
     *
     * @param    array
     *
     * @return   boolean
     **/
    public function addAssembleApply($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());

        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        if (!isset($params['outids'])) {
            $this->_error = '请选择要出库的产品';
            return false;
        }

        if (!isset($params['inids'])) {
            $this->_error = '请选择要入库的产品';
            return false;
        }

        if (empty($params['remark'])) {
            $this->_error = '备注不能为空';
            return false;
        }

        $out_products =  array();
        $in_products  =  array();

        $stockAPI = new Admin_Models_API_Stock();
        foreach($params['outids'] as $key => $val) {
            if ($params['outnumber'][$key] <= 0) {
                $this->_error = '出库数量不正确';
                return false;
            }

            $out_products[$val]['outnumber']  = $params['outnumber'][$key];
		    $out_products[$val]['outprice']   = $params['outprice'][$key];
            $out_products[$val]['outstatus']  = $params['outstatus'][$key];
        }

        foreach ($out_products as $product_id => $val) {
            $where = array(
                'lid' => 1,
                'status_id'  => $val['outstatus'],
                'product_id' => $product_id,
                'batch_id'   => 0,
              );
            $stock_data = array_shift($stockAPI -> getProductOutStock($where));
            if ($stock_data['stock_number'] < $val['outnumber']) {
                $this ->_error = '可用库存不足';
                return false;
            }
        }


        foreach($params['inids'] as $key => $val) {
            if ($params['innumber'][$key] <= 0) {
                $this->_error = '入库数量不正确';
                return false;
            }

            $in_products[$val]['innumber']  = $params['innumber'][$key];
		    $in_products[$val]['inprice']   = $params['inprice'][$key];
            $in_products[$val]['instatus']  = $params['instatus'][$key];
        }

        $date = date('Ymd');
        $assemble_info = $this->_db->getLastAssembelInfoByCondition(array('like_assemble_sn' => $date));

        $assemble_sn = $date .'0001';
        if (!empty($assemble_info)) {
            $auto_sn = str_pad(intval(substr($assemble_info['assemble_sn'],-4) + 1), 4, '0', STR_PAD_LEFT);
            $assemble_sn = substr($assemble_info['assemble_sn'],0,-4). $auto_sn;
        }

        $number = $params['type'] == 1 ? $params['outnumber'][0] : $params['innumber'][0];
        $assemble_params = array(
            'assemble_sn' => $assemble_sn,
            'type'        => $params['type'],
            'number'      => $number,
            'remark'      => $params['remark'],
            'created_id'  => $this->_auth['admin_id'],
			'created_by'  => $this->_auth['admin_name'],
            'created_ts'  => date('Y-m-d H:i:s'),
        );

        $assemble_id = $this->_db->addAssembelInfo($assemble_params);

        if ($assemble_id < 1) {
            $this->_error = '插入组装单数据失败';
            return false;
        }

        $total_out_amount = 0;
        foreach ($out_products as $product_id => $val) {
            $assemble_detail = array(
                'assemble_id' => $assemble_id,
                'product_id'  => $product_id,
                'cost'        => $val['outprice'],
                'number'      => $val['outnumber'],
                'status'      => $val['outstatus'],
            );

            $total_out_amount += $val['outprice'] * $val['outnumber'];

            if (false === $this->_db->addAssembelDetail($assemble_detail)) {
                $this->_error = '插入组装原料明细数据失败';
                return false;
            }
        }

        $total_in_amount = 0;
        foreach ($in_products as $product_id => $val) {
            $total_in_amount += $val['inprice'] * $val['innumber'];
        }


        foreach ($in_products as $product_id => $val) {
            $cost = 0;
            if ($params['type'] == '1') {
                $cost = $total_out_amount * (($val['inprice'] * $val['innumber']) / $total_in_amount)/$val['innumber'];
            } else if ($params['type'] == '2'){
                $cost = $total_out_amount / $val['innumber'];
            }
            $finished_detail = array(
                'assemble_id' => $assemble_id,
                'product_id'  => $product_id,
                'cost'        => $cost,
                'number'      => $val['innumber'], 
                'status'      => $val['instatus'],
            );

            if (false === $this->_db->addAssembelFinished($finished_detail)) {
                $this->_error = '插入组装原料明细数据失败';
                return false;
            }
        } 
        return true;
    }

    /**
     * 获取组装单数据
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function browseAssemble($params, $limit)
	 {	
		$infos = $this->_db->browseAssemble($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

        foreach ($infos as &$info) {
            $info['type_name']   = $this->_search_option['assemble_type'][$info['type']];
            $info['status_name'] = $this->_search_option['assemble_status'][$info['status']];
        }

		return $infos;
	 }

    /**
     * 获取组装单总数
     *
     * @param    array  
     *
     * @return   int
     */
    public function getAssembleCount($params)
	{	
		$count = $this->_db->getAssembleCount($params);

		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
	}

    /**
     * 锁定组装单/解锁组装单
     *
     * @param    array
     * @param    int
     *
     * @return   boolean
     */
	public function lockAssemble($ids, $is_lock)
	{
        if (empty($ids)) {
            return ;
        }
		if (is_array($ids)) {
			foreach($ids as $id){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($is_lock) {
			    	$assemble_params = array('locked_by' => $admin_name);
                    $where = array(
                        'locked_by' => '',
                        'assemble_id' => $id,
                    );
			    } else {
			    	$assemble_params = array('locked_by' => '');
                    $where = array(
                        'locked_by' => $this->_auth['group_id'] > 1 ? $admin_name : '1',
                        'assemble_id' => $id,
                    );
			    }

                $this->_db->updateAssembleByCondition($assemble_params, $where);
			}
		}
        return true;
	}

    /**
     * 根据ID获取组装单信息
     *
     * @param    int
     *
     * @return   array
     */
    public function getAssembleInfoById($assemble_id)
    {
        if (intval($assemble_id) < 1) {
            $this->_error = "组装单ID不正确";
            return false;
        }

        $info = $this->_db->getAssembleInfoById($assemble_id);

        if (false === $info) {
            $this->_error = $this->_db->getError();
            return false;
        }
        if (empty($info)) {
            $this->_error = "没有相关数据";
            return false;
        }

        $info['type_name'] = $this->_search_option['assemble_type'][$info['type']];
        if ($info['locked_by'] == $this->_auth['admin_name'] && $info['status'] == 0) {
            $info['lock'] = 'yes';
        }

        return $info;
    }

    /**
     * 根据组装ID获取组装原料明细数据
     *
     * @param    int
     *
     * @return   array
     */
    public function getAssembleDetailsByAssembleId($assemble_id)
    {
        if (intval($assemble_id) < 1) {
            $this->_error = "组装单ID不正确";
            return false;
        }

        return $this->_db->getAssembleDetailsByAssembleId($assemble_id);
    }

    /**
     * 根据组装ID获取组装原料明细数据
     *
     * @param    int
     *
     * @return   array
     */
    public function getAssembleFinishedsByAssembleId($assemble_id)
    {
        if (intval($assemble_id) < 1) {
            $this->_error = "组装单ID不正确";
            return false;
        }

        return $this->_db->getAssembleFinishedsByAssembleId($assemble_id);
    }

    /**
     * 组装单审核
     *
     * @param    int
     *
     * @return   array
     */
    public function checkAssemble($assemble_id, $params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        if (intval($assemble_id) < 1) {
            $this->_error = "组装单ID不正确";
            return false;
        }

        if (empty($params['remark'])) {
            $this->_error = '备注不能为空';
            return false;
        }

        if ($params['is_check'] == '2') {
            $this->_db->updateAssembleByCondition(array('locked_by' => '', 'status' => '2', 'audit_ts' => date('Y-m-d H:i:s')), array('assemble_id' => $assemble_id));
            return true;
        }

        if (false === $info = $this->getAssembleInfoById($assemble_id)) {
            return false;
        }

        $assemble_details          = $this->getAssembleDetailsByAssembleId($assemble_id);

        if (empty($assemble_details)) {
            return false;
        }
        $stockAPI = new Admin_Models_API_Stock();

        // 判断是否有库存
        foreach ($assemble_details as $val) {
            $where = array(
                'lid' => $this->_logic_area,
                'status_id'  => $val['status'],
                'product_id' => $val['product_id'],
                'batch_id'   => 0,
              );

            $stock_data = array_shift($stockAPI -> getProductOutStock($where));
            if ($stock_data['stock_number'] < $val['number']) {
                $this ->_error = '可用库存不足';
                return false;
            }
        }
        $assemble_finished_details = $this->getAssembleFinishedsByAssembleId($assemble_id);

        if (empty($assemble_details)) {
            return array();
        }

        if (empty($assemble_finished_details)) {
            return array();
        }

        // 审核通过
        $this->_db->updateAssembleByCondition(array('locked_by' => '', 'status' => '1', 'audit_ts' => date('Y-m-d H:i:s')), array('assemble_id' => $assemble_id));

        // 组合出库单数据

        $bill_no = Custom_Model_CreateSn::createSn();
	    $outstock_params = array (
            'lid'         => $this->_logic_area,
            'item_no'     => $info['assemble_sn'],
		    'bill_no'     => $bill_no,
            'bill_type'   => 14, //组装出库单
			'remark'      => $params['remark'],
            'amount'      => 0,
            'bill_status' => 3, //未确认(已审核)
            'add_time'    => time(),
            'admin_name'  => $this->_auth['admin_name'],
        );

        $outstock_details = array();

        foreach ($assemble_details as $val) {
            $outstock_details[] = array(
                'product_id' => $val['product_id'],
                'batch_id'   => 0,
                'status_id'  => $val['status'],
                'number'     => $val['number'],
                'shop_price' => $val['cost'],
               );
        }

        // 占用库存
        foreach ($assemble_details as $val) {
            $stock_params = array(
                'lid'        => $this->_logic_area,
    	        'product_id' => $val['product_id'],
    	        'batch_id'   => 0,
    	        'status_id'  => $val['status'],
    	    );
            $stockAPI->addStockOutNumber($val['number'], $stock_params);
        }

        $outstockAPI = new Admin_Models_API_OutStock();
        $outstockAPI->insertApi($outstock_params, $outstock_details, $this->_logic_area, false);


        $instock_db = new Admin_Models_DB_InStock();
        $bill_no = Custom_Model_CreateSn::createSn();
        // 组合入库单数据
        $instock_params = array (
            'lid'         => $this->_logic_area,
            'item_no'     => $info['assemble_sn'],
		    'bill_no'     => $bill_no,
            'bill_type'   => 14, //组装入库单
			'remark'      => $params['remark'],
            'add_time'    => time(),
            'bill_status' => 3, //未确认(已审核)
            'admin_name'  => $this->_auth['admin_name'],
        );
        $last_id = $instock_db->insert($instock_params);

        $instock_plans = array();
        $instock_details = array();
        foreach ($assemble_finished_details as $val) {
            $instock_plans = array(
                'instock_id'  => $last_id,
                'product_id'  => $val['product_id'],
                'batch_id'    => 0,
                'plan_number' => $val['number'],
                'real_number' => $val['number'],
                'shop_price'  => $val['cost'],
                'status_id'   => $val['status'],
               );
            $instock_db->insertPlan($instock_plans);         
        }

        return true;
    }

    public function getProductList($where) {
        return $this -> _db -> fetch($where);
    }


    /**
     * 添加产品
     *
     * @param    data
     *
     * @return   string
     */
    public function addProduct($data)
    {
        $goodsAPI = new Admin_Models_DB_Goods();
        $goods = array_shift($goodsAPI -> fetch("a.goods_id = '{$data['goods_id']}'"));
        if (!$goods) {
            return 'goods not found';
        }
        
        /*
        if ($this -> _db -> fetch("p.product_name = '{$data['product_name']}'")) {
            return 'same product name';
        }
        */
        
        if ($this -> getProductGoods("goods_id = '{$data['goods_id']}' and attrs = '{$data['attrs']}'")) {
            return 'same product';
        }
        
        if ($data['attrs'] == '') {
            $productSN = $goods['goods_sn'].'00';
        }
        else {
            do {
                $number = substr('0'.rand(1,99), -2);
                $productSN = $goods['goods_sn'].$number;
            }
            while ($this -> _db -> fetch("p.product_sn = '{$productSN}'"));
        }
        
        $row = array('product_sn' => $productSN,
                     'product_name' => $data['product_name'],
                     'cat_id' => $goods['view_cat_id'],
                     'goods_style' => $data['goods_style'],
                     'product_sn' => $productSN,
                     'lid' => $this -> _lid,
                    );
        $productID = $this -> _db -> addProduct($row);
        if (!$productID) {
            return 'add product error';
        }
        
        $row = array('product_id' => $productID,
                     'goods_id' => $data['goods_id'],
                     'attrs' => $data['attrs'],
                     );
        $this -> _db -> addProductGoods($row);
        $this -> ajaxUpdate($productID, 'adjust_num', 10);
        
        if ($data['attrs']) {
            $attrIDArray = explode(',', substr($data['attrs'], 1, strlen($data['attrs']) - 2));
            $attributeAPI = new Admin_Models_API_Attribute();
            $attrData = $attributeAPI -> get("attr_id in (".implode(',', $attrIDArray).")");
            foreach ($attrData as $attr) {
                $attrName .= $attr['attr_title'].' ';
            }
        }
        
        $result[] = 'ok';
        $result[] = $productSN;
        $result[] = $attrName;
        $result[] = $productID;
        return implode(']::[', $result);
    }
    
    /**
     * 编辑产品属性
     *
     * @param    data
     *
     * @return   string
     */
    public function editProductAttr($data)
    {
        $attrs = ',';
        foreach ($data as $name => $value) {
            if (substr($name, 0, 7) == 'attrID1') {
                $attrs .= $value[0].',';
            }
        }
        if ($attrs == ',') {
            $attrs = '';
        }
        
        if ($this -> getProductGoods("goods_id = '{$data['goods_id']}' and product_id <> '{$data['product_id']}' and attrs = '{$attrs}'")) {
            return 'same product';
        }
        
        $this -> _db -> updateProductGoods(array('attrs' => $attrs), "product_id = {$data['product_id']}");
        
        return 'ok';
    }
    
    /**
     * 获得产品商品属性
     *
     * @param    where
     *
     * @return   array
     */
    public function getProductGoods($where)
    {
        return $this -> _db -> getProductGoods($where);
    }

	/**
	* 返回错误信息
	*
	* @return   string
	*/
	public function getError()
	{
		return $this->_error;
	}
	
}
