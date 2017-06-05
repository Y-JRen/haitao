<?php
class Admin_Models_DB_Goods
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
	private $_table_goods = 'shop_goods';
	private $_table_goods_tag = 'shop_goods_tag';
	private $_table_product = 'shop_product';
	private $_table_goods_cat = 'shop_goods_cat';
	private $_table_goods_img = 'shop_goods_img';
	private $_table_goods_link = 'shop_goods_link';
	private $_table_stock_status = 'shop_stock_status';
	private $_table_supplier = 'shop_supplier';
	private $_table_brand = 'shop_brand';
	private $_table_goods_op = 'shop_goods_op';
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
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = " WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY a.goods_id DESC";
		}
		$table = "`$this->_table_goods` a LEFT JOIN `$this->_table_goods_cat` d ON a.view_cat_id=d.cat_id  ";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields,d.cat_name as cat_name,d.cat_sn,d.cat_path FROM $table $whereSql $orderBy $limit");
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
	public function fetchGoods($where = null, $fields = '*')
	{
		if ($where != null) {
			$whereSql = " WHERE $where";
		}
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY a.goods_id DESC";
		}
		$table = "`$this->_table_goods` a INNER JOIN `$this->_table_goods_cat` b ON a.view_cat_id=b.cat_id LEFT JOIN `$this->_table_brand` p ON a.brand_id=p.brand_id  ";
		return $this -> _db -> fetchAll("SELECT $fields,cat_name,cat_path FROM $table $whereSql $orderBy");
	}

	/**
     * 添加关联商品
     *
     * @param    int      $goods_id
     * @param    int      $goods_link_id
     * @return   int      lastInsertId
     */
	public function addLink($goods_id, $goods_link_id,$type)
	{
		$row = array (
			  'goods_id' => $goods_id,
			  'goods_link_id' => $goods_link_id,
			  );
		$table = $this -> _table_goods_link ;
		$this -> _db -> insert($table, $row);
		return $this -> _db -> lastInsertId();
		
		
	}
	
	/**
     * 删除关联商品
     *
     * @param    int      $id
     * @return   void
     */
	public function deleteLink($id = null,$type)
	{
		if ($id >0 ) {
			$where = $this -> _db -> quoteInto('link_id = ?', (int)$id);
			$table=$this -> _table_goods_link;
			return $this -> _db -> delete($table, $where);
		}
	}

	/**
     * 添加商品实体
     *
     * @param    array    $row
     * @return   int      lastInsertId
     */
	public function addProduct($row)
	{
        $row['p_add_time'] = time();
        $row["goods_id"] = $row['goods_id'];
        $this -> _db -> insert($this -> _table_product, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 获得商品实体
     *
     * @param    array    $row
     * @return   int      lastInsertId
     */
	public function getProduct($where)
	{
        return $this -> _db -> fetchAll("select * from {$this -> _table_product} where {$where}");
	}
	
	/**
     * 修改商品实体goods_id
     *
     * @param    array    $row
     * @return   int      lastInsertId
     */
	public function updateProductGoodsID($productID, $field, $goodsID)
	{
		$this -> _db -> update($this->_table_product, array($field => $goodsID), "product_id = $productID");
	}
	
	/**
     * 获取关联商品
     *
     * @param    int      $goods_id
     * @return   array
     */
	public function getLink($goods_id,$type)
	{
		
	    $sql = "SELECT * FROM `$this->_table_goods_link` a INNER JOIN `$this->_table_goods` b ON b.goods_id=a.goods_link_id  WHERE a.goods_id=$goods_id";
		
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加商品新标签
     *
     * @param    string    $where
     * @return   array
     */
	public function addTag($data)
	{
			$row = array (
						  'tag' => $data['tag'],
						  'title' => $data['title'],
						  'type'  => $data['type'],
						  'admin_name' => $data['admin_name'],
						  'add_time' => time()
						  );
			$this -> _db -> insert($this->_table_goods_tag, $row);
			$lastInsertId = $this -> _db -> lastInsertId();
			return $lastInsertId;
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllTag($where = null,$page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) $where = "WHERE 1 $where";
        return array('list'=>$this -> _db -> fetchAll("SELECT * FROM `$this->_table_goods_tag` $where order by tag_id  DESC $limit "),'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM `$this->_table_goods_tag` $where "));
	}
	

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where = null)
	{
		if ($where != null) $where = "WHERE $where";
		$sql = "SELECT * FROM `$this->_table_goods_tag` $where order by tag_id  DESC ";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 获取供货商列表
     *
     * @return   array
     */
	public function getSupplier()
	{
		$sql = "SELECT supplier_id,supplier_name,status FROM `$this->_table_supplier` ORDER BY supplier_id";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 获取品牌列表
     *
     * @return   array
     */
	public function getBrand($where='')
	{
		$sql = "SELECT * FROM `$this->_table_brand` {$where} ORDER BY brand_id";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 获取分类列表
     *
     * @return   array
     */
	public function getCat($where = null, $orderBy = null)
	{
        $where && $whereSql = " AND $where";
        if ($orderBy != null){
            $orderBy = "ORDER BY $orderBy";
        }else{
            $orderBy = "ORDER BY parent_id, cat_sort";
        }
		$sql = "SELECT cat_id,cat_name,parent_id,cat_path,cat_sort FROM `$this->_table_goods_cat`  WHERE cat_status=0 $whereSql $orderBy ";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		$data['goods_add_time'] = time();
        $this -> _db -> insert($this->_table_goods, $data);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function update($data, $id)
	{
		$set = array ('goods_name' => $data['goods_name'],
			          'short_name' => $data['short_name'],
                      'limit_number' => $data['limit_number'],
                      'goods_alt' => $data['goods_alt'],
			          'brief' => $data['brief'],
                      'region' => $data['region'],
					  'act_notes' => $data['act_notes'],
			          'description' => $data['description'],
                      'is_gift' => $data['is_gift'] ? $data['is_gift'] : 0,
			          'meta_title' => $data['meta_title'],
			          'meta_keywords' => $data['meta_keywords'],
			          'meta_description' => $data['meta_description'],
			          'brand_id' => $data['brand_id'],
			          'goods_update_time' => time(),
		              'kjt_sn'=>$data['kjt_sn'],
                      );
		if ($id > 0) {
		    $this -> _db -> update($this->_table_goods, $set, "goods_id = '{$id}'");
		    return true;
		}
	}
	
	/**
     * 删除数据
     *
     * @param    int      $id
     * @return   void
     */
	public function deleteGoods($id,$value)
	{
		$where = $this -> _db -> quoteInto('goods_id = ?', $id);
        if($value=='1'){
            $set['is_del'] = '1';
        }else{
            $set['is_del'] = '0';
        }

		if ($id > 0 && $this -> _db -> update($this->_table_goods, $set, $where)) {
            return 'ok';
		}
	}

	/**
     * 更新标签
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updateTag($tag_id, $val,$type=null)
	{
		if ($tag_id > 0) {
			$set = array ('config' => $val);
			$where = $this -> _db -> quoteInto('tag_id = ?', $tag_id);
		    $this -> _db -> update($this->_table_goods_tag, $set, $where);
		    return true;
		}
	}
	
	/**
     * 更新状态
     *
     * @param    int       $id
     * @param    int       $status
     * @param    string    $remark
     * @param    int       $type
     * @return   void
     */
	public function updateStatus($id, $status, $remark = '', $type = 0)
	{
		if ($id > 0) {
		    if ($type == 2) {
			    $set = array ('onsale2' => $status, 'onoff_remark2' => $remark);
			}
			else {
			    $set = array ('onsale' => $status, 'onoff_remark' => $remark);
			}
			$where = $this -> _db -> quoteInto('goods_id = ?', $id);
		    return $this -> _db -> update($this->_table_goods, $set, $where);
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
		$set = array ($field => $val);
		if ($id > 0) {
		switch($type){
			case 'img':
			    $fields = array('img_sort', 'img_desc');
			    if(in_array($field, $fields)){
				    $where = $this -> _db -> quoteInto('img_id = ?', $id);
				    $this -> _db -> update($this -> _table_goods_img, $set, $where);
			    }
			    break;

			case 'tag':
			    $fields = array('title', 'tag');
			    if(in_array($field, $fields)){
				    $where = $this -> _db -> quoteInto('tag_id = ?', $id);
				    $this -> _db -> update($this -> _table_goods_tag, $set, $where);
			    }
			    break;
			default:
			    $fields = array('goods_name','goods_sort', 'cost_price', 'market_price', 'price', 'limit_number');
			    if(in_array($field, $fields)){
				    $where = $this -> _db -> quoteInto('goods_id = ?', $id);
				    $this -> _db -> update($this->_table_goods, $set, $where);
			    }
		}
		    return true;
		}
	}
	
	/**
     * 更新数据
     *
     * @param    array    $set
     * @param    string   $where
     * @return   array
     */
    public function updateGoods($set, $where)
	{
	    $set['goods_update_time'] = time();
	    $this -> _db -> update($this -> _table_goods, $set, $where);
	    return true;
	}
	
	/**
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
		if ($where != null) $where = "WHERE $where";
		$sql = "SELECT * FROM `$this->_table_goods_op` $where ORDER BY op_id DESC";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加操作日志记录
     *
     * @return   int      lastInsertId
     */
	public function insertOp($row)
	{
        $this -> _db -> insert($this -> _table_goods_op, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	
	/**
     * 由goods_id得到一个商品
     * 
     * @param int $goods_id
     * @return array
     */
    public function getOne($goods_id, $fields='*') {
    	if(intval($goods_id)){
    		return $this -> _db-> fetchRow("select {$fields} from {$this -> _table_goods} where goods_id = {$goods_id} limit 1");
    	}
    }
	/**
     * 根据商品编码获取商品数据
     * 
     *
     * @return   array
     */
	public function getGoodsInfoByGoodsSn($goods_sn)
	{
		$goods_sn = trim($goods_sn);
		if (empty($goods_sn)) {
			$this->_error = '产品编码为空';
			return false;
		}

		$sql = "SELECT `goods_id`, `goods_sn`, `market_price`, `price` FROM `shop_goods` WHERE `goods_sn` = '{$goods_sn}' limit 1";

		return $this->_db->fetchRow($sql);
	}
	
	/**
     * 获得商品产品信息
     *
	 * @param    string     $whereSQL
     * @return   array
     */
	public function getGoodsProductData($where)
	{
	    $sql = "select t1.*,t2.attrs,t3.product_id,t3.product_name,t3.product_sn,t3.goods_style,t3.p_weight from {$this -> _table_goods} as t1
	            inner join {$this -> _table_product_goods} as t2 on t1.goods_id = t2.goods_id
	            inner join {$this -> _table_product} as t3 on t2.product_id = t3.product_id
	            where {$where}";
	    return $this -> _db -> fetchAll($sql);
	    
	}
	
	/**
	 * 获得归属地
	 */
	public function getRegion()
	{
		$sql ="select * from shop_brand_region";
		return $this->_db->fetchAll($sql);
	}
	
}