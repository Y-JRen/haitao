<?php
class Admin_Models_DB_Product
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * page size
     * @var    int
     */
	private $_pageSize = null;
	
	/**
     * table name
     * @var    string
     */
	private $_table_product = 'shop_product';
	private $_table_goods = 'shop_goods';
	private $_table_goods_op = 'shop_goods_op';
	private $_table_goods_cat = 'shop_goods_cat';
	private $_table_stock_status = 'shop_stock_status';
	private $_table_logic_status = 'shop_logic_status';
	private $_table_order_batch_goods = 'shop_order_batch_goods';
	private $_table_order_batch_goods_return = 'shop_order_batch_goods_return';
	private $_table_goods_img = 'shop_goods_img';
	private $_table_product_batch = 'shop_product_batch';
    private $_table_supplier = 'shop_supplier';
    private $_table_product_characters = 'shop_product_characters';
	private $_table_gift_product = 'shop_gift_card_product';
	private $_table_product_goods = 'shop_product_goods';
	
	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}
	
	/**
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetch($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : 15;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY product_id DESC";
		}
		
		$table = "`$this->_table_product` p LEFT JOIN `$this->_table_goods_cat` gc ON p.cat_id=gc.cat_id";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
	}
	
	/**
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetchGoods($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : 10;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY g.product_id DESC";
		}
		
		$table = "`$this->_table_product` p LEFT JOIN `$this->_table_goods_cat` gc ON p.cat_id=gc.cat_id
		           INNER JOIN `$this->_table_goods` g on p.product_id = g.product_id";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
	}
	
	public function fetchRow($where)
	{
		return $this -> _db -> fetchRow("select * from {$this -> _table_product} where {$where}");
	}
	
	/**
     * 获取商品状态列表
     *
     * @return   array
     */
	public function getStockStatus($where, $fields = '*', $page = null, $pageSize = null, $orderBy = null, $fix = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		$whereSql = "where p.p_status = 0";
		if ($where != null) {
			$whereSql .= " and $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY a.product_id DESC,stock_id";
		}
        $table = "{$this->_table_stock_status}{$fix} a 
                  LEFT JOIN `$this->_table_logic_status` b ON a.status_id=b.id 
                  INNER JOIN `$this->_table_product` p ON p.product_id=a.product_id 
                  INNER JOIN `$this->_table_goods_cat` gc ON gc.cat_id=p.cat_id";
        $datas = $this -> _db -> fetchAll("SELECT a.*,p.suggest_price as price,b.name as status_name,p.*,p.product_name as goods_name,p.product_sn as goods_sn,gc.cat_path,gc.cat_name FROM $table $whereSql $orderBy $limit");
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		return $datas;
	}
	
	/**
     * 添加数据
     *
     * @param    array    $row
     * @return   boolean
     */
    public function add($row)
	{
	    $row['p_add_time'] = time();
	    $row['p_update_time'] = time();
	    
	    $this -> _db -> insert($this -> _table_product, $row);
	    return $this -> _db -> lastInsertId();
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
	    $set['p_update_time'] = time();
	    $this -> _db -> update($this -> _table_product, $set, $where);
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
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('product_id = ?', $id);
		if ($id > 0) {
			$fields = array('produce_cycle','warn_number','p_status','local_sn','ean_barcode','p_length','p_width','p_height','p_weight', 'adjust_num');
			if(in_array($field, $fields)){
		        $this -> _db -> update($this -> _table_product, $set, $where);
		    }
		    return true;
		}
	}
    /**
     * 获取商品图片
     *
     * @param    string   $where
     * @return   array
     */
	public function getImg($where)
	{
		$sql = "SELECT * FROM `$this->_table_goods_img` WHERE $where ORDER BY img_sort,img_id DESC";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加产品图片
     *
     * @param    int      $product_id
     * @param    string   $img_url
     * @param    int      $img_type    0标准图  1色块图  2细节图  3展示图
     * @param    int      $add_time
     * @param    string   $img_desc
     * @return   int      lastInsertId
     */
	public function addImg($product_id, $img_url, $img_type, $add_time, $thumbs, $img_desc = null, $img_sort = null)
	{
		$row = array (
                      'product_id' => $product_id,
                      'img_url' => $img_url,
                      'img_type' => $img_type,
			          'add_time' => $add_time,
                      'thumbs' => $thumbs,
                      'img_desc' => $img_desc,
                      'img_sort' => $img_sort,
                      );
        $this -> _db -> insert($this -> _table_goods_img, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
     * 删除产品图片
     *
     * @param    int      $id
     * @return   void
     */
	public function deleteImg($id)
	{
		$where = $this -> _db -> quoteInto('img_id = ?', (int)$id);
		return $this -> _db -> delete($this -> _table_goods_img, $where);
	}

    /**
     * 更新产品图片
     *
     * @param    int      $id
     * @return   void
     */
	public function updateImg($id, $params)
	{
		$where = $this -> _db -> quoteInto('img_id = ?', (int)$id);
		return $this -> _db -> update($this -> _table_goods_img, $params, $where);
	}
	
	/**
     * 插入商品状态
     *
	 * @param    string   $stockStatus
     * @return   void
     */
	public function insertStockStatus($stockStatus)
	{
		$sql = "INSERT INTO `".$this -> _table_stock_status."` (`lid`, `product_id`, `status_id`) VALUES".$stockStatus;
		$this -> _db -> execute($sql, $this -> _table_stock_status);
	}
	
	/**
     * 插入商品状态
     *
	 * @param    string   $stockStatus
     * @return   void
     */
	public function insertStockStatusFix($stockStatus, $fix = null)
	{
		$this -> _db -> execute("INSERT INTO `".$this -> _table_stock_status."_12` (`product_id`, `status_id`) VALUES ".$stockStatus, $this -> _table_stock_status."_12");
		$this -> _db -> execute("INSERT INTO `".$this -> _table_stock_status.$fix."_123` (`product_id`, `status_id`) VALUES ".$stockStatus, $this -> _table_stock_status.$fix."_123");
		$this -> _db -> execute("INSERT INTO `".$this -> _table_stock_status.$fix."_1234` (`product_id`, `status_id`) VALUES ".$stockStatus, $this -> _table_stock_status.$fix."_1234");
	}
	
	/**
     * 同步更新商品图片
     *
	 * @param    int   $product_id
	 * @param    array $set
     * @return   void
     */
	public function updateGoodsImage($product_id, $set)
	{
	    $this -> _db -> update($this -> _table_goods, $set, "product_id = {$product_id}");
	}
	
	/**
     * 获取产品批次(按批次)
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetchBatch($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : 10;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY batch_id DESC";
		}
		
		$table = "{$this -> _table_product_batch} as t1 
		          inner join {$this -> _table_product} as t2 on t1.product_id = t2.product_id
		          inner join {$this->_table_goods_cat} as t3 on t2.cat_id = t3.cat_id
		          inner join {$this -> _table_supplier} as t4 on t4.supplier_id = t1.supplier_id";
		$result['total'] = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		$result['data'] = $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
		
		return $result;
	}
	
	/**
     * 获取产品批次(按产品)
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetchProductBatch($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : 10;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}

		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY batch_id DESC";
		}
		
		$table = "{$this -> _table_product} as t1 
		          left join {$this -> _table_product_batch} as t2 on t1.product_id = t2.product_id
		          inner join {$this->_table_goods_cat} as t3 on t1.cat_id = t3.cat_id";
		$result['total'] = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		$result['data'] = $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
		
		return $result;
	}
	
	/**
     * 添加产品批次
     *
	 * @param    array      $set
     * @return   void
     */
	public function insertBatch($set)
	{
	    $this -> _db -> insert($this -> _table_product_batch, $set);
	    return $this -> _db -> lastInsertId();
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
	    $this -> _db -> update($this -> _table_product_batch, $set, $where);
	}
	
	/**
     * 获得产品性状
     *
	 * @param    string     $where
     * @return   array
     */
	public function getCharacters($where = 1)
	{
	    return $this -> _db -> fetchAll("SELECT *  FROM {$this -> _table_product_characters} where {$where}");
    }

	/**
	* 根据条码获取产品信息
	*
	* @return   string
	*/
	public function getProductInfoByBarcode($barcode)
	{
		$barcode = trim($barcode);
		if (empty($barcode)) {
			$this->_error = '条形码不能为空';
			return false;
		}

		$sql = "SELECT `product_id`, `product_name`, `ean_barcode` FROM `{$this->_table_product}` WHERE `ean_barcode` = '{$barcode}' limit 1";
		return $this->_db->fetchRow($sql);
		
	}

	/**
     * 获取信息列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function getGiftcardList($params, $limit = null)
	 {	
		list($_condition, $_join) = $this->getGiftcardCondition($params);
        
        $limit && $limit = "limit {$limit}";
        
		$field = array(
			'p.product_id',
			'p.product_sn',
			'p.product_name',
			'amount',
			'admin_name',
			'g.add_time',
			'g.update_time',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_product}` p ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." ORDER BY p.product_id desc {$limit}";
		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取信息总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getGiftcardCount($params)
	 {	
		list($_condition) = $this->getGiftcardCondition($params);

		$sql = "SELECT count(*) as count FROM `{$this->_table_product}` p WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getGiftcardCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		$_condition[] = " is_gift_card = '1'";
		!empty($params['product_sn']) && $_condition[] = "p.product_sn = '{$params['product_sn']}'";
		!empty($params['start_ts'])   && $_condition[] = "add_time >= '".strtotime($params['start_ts']. ' 00:00:00')."'";
		!empty($params['end_ts'])     && $_condition[] = "add_time <= '".strtotime($params['end_ts']. ' 23:59:59')."'";
	    if (!empty($params['amount'])) {
	        if (is_array($params['amount'])) {
	            $_condition[] = "amount in (".implode(',', $params['amount']).")";
	        }
	        else {
	            $_condition[] = "amount = '{$params['amount']}'";
	        }
	    }

		$_join[] = "LEFT JOIN `{$this->_table_gift_product}` g on p.product_id = g.product_id";
		return array($_condition, $_join);
	 }

	/**
     * 根据产品ID获取产品信息
     *
     * @param    int  
     *
     * @return   array
     */
	public function getProductInfoByProductid($product_id)
	{
		$product_id = intval($product_id);
		if ($product_id < 1) {
			$this->_error = '产品ID不正确';
			return false;
		}

		$sql = "SELECT `product_id`, `product_sn`, `is_gift_card` FROM `{$this->_table_product}` WHERE product_id = '{$product_id}' limit 1";

		return $this->_db->fetchRow($sql);
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
		$product_id = intval($product_id);
		if ($product_id < 1) {
			$this->_error = '产品ID不正确';
			return false;
		}

		$sql = "SELECT t1.*,t2.is_vitual,t2.is_gift_card FROM {$this->_table_gift_product} as t1 inner join {$this -> _table_product} as t2 on t1.product_id = t2.product_id WHERE t1.product_id = '{$product_id}' limit 1";
        
		return $this->_db->fetchRow($sql);
	}

	/**
     * 插入礼品卡数据
     *
     * @param    params  
     *
     * @return   boolean
     */
	public function insertGiftinfo($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		return (bool) $this->_db->insert($this->_table_gift_product, $params);
	}

	/**
     * 根据产品Id更新礼品卡信息
     *
     * @param    params  
     *
     * @return   boolean
     */
	public function updateGiftInfoByProductid($product_id, $params)
	{
		$product_id = intval($product_id);
		if ($product_id < 1) {
			$this->_error = '产品ID不正确';
			return false;
		}

		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		return (bool) $this->_db->update($this ->_table_gift_product, $params, "product_id = '{$product_id}'");
	}

	/**
     * 根据产品编码获取产品信息
     *
     * @param    string  
     *
     * @return   array
     */
	public function getProductInfoByProductSn($product_sn)
	{
		$product_sn = trim($product_sn);
		if (empty($product_sn)) {
			$this->_error = '产品编码为空';
			return false;
		}

		$sql = "SELECT `product_id`, `product_sn`, `price_limit` FROM `{$this->_table_product}` WHERE `product_sn` = '{$product_sn}' limit 1";

		return $this->_db->fetchRow($sql);
	}

	/**
     * 根据产品IDS获取产品信息
     *
     * @param    array  
     *
     * @return   array
     */
	public function getProductInfosByProductIds($product_ids)
	{
		if (empty($product_ids)) {
			$this->_error = '产品IDS为空';
			return false;
		}

		$sql = "SELECT `product_id`, `product_sn`, `product_name` FROM `{$this->_table_product}` WHERE `product_id` IN('". implode("','", $product_ids) ."') ";

		$infos = $this->_db->fetchAll($sql);

		if (count($infos) < 1) {
			return array();
		}

		$product_infos = array();
		foreach ($infos as $info) {
			$product_infos[$info['product_id']] = $info;
		}

		return $product_infos;
	}

 
    /**
     * 根据条件获取组装单最后一个数据
     *
     * @param    array
     *
     * @return   array
     **/
    public function getLastAssembelInfoByCondition($params)
    {
        $_condition[] = "1=1";
        !empty($params['like_assemble_sn']) && $_condition[] = "`assemble_sn` LIKE '{$params['like_assemble_sn']}%'";
        $sql = "SELECT `assemble_id`, `assemble_sn` FROM `shop_assemble` WHERE ".implode(' AND ', $_condition)." ORDER BY assemble_id DESC limit 1";

        return $this->_db->fetchRow($sql);
    }

    /**
     * 添加组装单数据
     *
     * @param    array
     *
     * @return   int
     **/
    public function addAssembelInfo($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        $result = $this->_db->insert('shop_assemble', $params);

        return $this->_db->lastInsertId();
    }

    /**
     * 添加组装原料数据
     *
     * @param    array
     *
     * @return   boolean
     **/
    public function addAssembelDetail($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        return (bool) $this->_db->insert('shop_assemble_detail', $params);
    }

    /**
     * 添加组装成品数据
     *
     * @param    array
     *
     * @return   boolean
     **/
    public function addAssembelFinished($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        return (bool) $this->_db->insert('shop_finished_detail', $params);
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
		$_condition = $this->getBrowseAssembleCondition($params);

		$field = array(
			'assemble_id',
            'assemble_sn',
            'type',
            'status',
            'remark',
            'created_ts',
            'audit_ts',
            'locked_by',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_assemble` WHERE ". implode(' AND ', $_condition) ." ORDER BY assemble_id asc limit {$limit}";

		return $this->_db->fetchAll($sql);
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
		$_condition = $this->getBrowseAssembleCondition($params);

		$sql = "SELECT count(*) as count FROM `shop_assemble` WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getBrowseAssembleCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        $_condition[] = '1=1';
		!empty($params['start_ts'])       && $_condition[] = "created_ts >= '{$params['start_ts']} 00:00:00'";
		!empty($params['end_ts'])         && $_condition[] = "created_ts <= '{$params['end_ts']} 23:59:59'";
        !empty($params['audit_start_ts']) && $_condition[] = "audit_ts >= '{$params['start_ts']} 00:00:00'";
		!empty($params['audit_end_ts'])   && $_condition[] = "audit_ts <= '{$params['end_ts']} 23:59:59'";
		!empty($params['assemble_sn'])    && $_condition[] = "assemble_sn = '{$params['assemble_sn']}'";
        !empty($params['type'])           && $_condition[] = "type = '{$params['type']}'";
        isset($params['status'])          && $_condition[] = "status = '{$params['status']}'";
        if ($params['lock']) {
            $_condition[] = "locked_by = '".$this->_auth['admin_name']."'";
        } else if (isset($params['lock'])) {
            $_condition[] = "locked_by = ''";
        }

		return $_condition;
	 }

    /**
     * 根据条件更新组装单数据
     *
     * @param    array
     * @param    array  
     *
     * @return   boolean
     */
    public function updateAssembleByCondition($params, $where)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
                     
        $where = Custom_Model_Filter::filterArray($where, $filterChain);

        $where_sql = ' 1 = 1';

        foreach ($where as $key => $val) {
            if ($key == 'locked_by' && $val == 1) {
                continue;
            }
            $where_sql .= " AND $key = '{$val}'";
        }

        return (bool) $this->_db->update('shop_assemble', $params, $where_sql);
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
        $assemble_id = intval($assemble_id);

        if ($assemble_id < 1) {
            $this->_error = "组装单ID不正确";
            return false;
        }

        $sql = "SELECT `assemble_id`, `assemble_sn`, `status`, `locked_by`, `type`, `remark` FROM `shop_assemble` WHERE assemble_id = '{$assemble_id}' LIMIT 1";
        return $this->_db->fetchRow($sql);
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
        $assemble_id = intval($assemble_id);

        if ($assemble_id < 1) {
            $this->_error = "组装单ID不正确";
            return false;
        }

        $field = array(
            'd.product_id',
            'product_sn',
            'product_name',
            'd.cost',
            'd.number',
            'd.status',

        );

        $join[] = " LEFT JOIN `shop_product` p ON p.product_id = d.product_id";

        $sql = "SELECT ". implode(', ', $field) ." FROM `shop_assemble_detail` d ". implode(' ', $join) ." WHERE assemble_id = '{$assemble_id}'";

        return $this->_db->fetchAll($sql);
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
        $assemble_id = intval($assemble_id);

        if ($assemble_id < 1) {
            $this->_error = "组装单ID不正确";
            return false;
        }

        $field = array(
            'd.product_id',
            'product_sn',
            'product_name',
            'd.cost',
            'd.number',
            'd.status',

        );

        $join[] = " LEFT JOIN `shop_product` p ON p.product_id = d.product_id";

        $sql = "SELECT ". implode(', ', $field) ." FROM `shop_finished_detail` d ". implode(' ', $join) ." WHERE assemble_id = '{$assemble_id}'";

        return $this->_db->fetchAll($sql);
    }
    
    public function addGoods($data)
    {
        $this -> _db -> insert($this -> _table_goods, $data);
	    return $this -> _db -> lastInsertId();
    }
    
    public function addProduct($data)
    {
        $this -> _db -> insert($this -> _table_product, $data);
	    return $this -> _db -> lastInsertId();
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
        return $this -> _db -> fetchALL("select * from {$this -> _table_product_goods} where {$where}");
    }
    
    /**
     * 添加产品商品属性
     *
     * @param    data
     *
     * @return   void
     */
    public function addProductGoods($data)
    {
        $this -> _db -> insert($this -> _table_product_goods, $data);
    }
    
    /**
     * 更新产品商品属性
     *
     * @param    data
     *
     * @return   void
     */
    public function updateProductGoods($data, $where)
    {
        $this -> _db -> update($this -> _table_product_goods, $data, $where);
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
