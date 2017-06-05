<?php

class Admin_Models_DB_Brand
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
	private $_pageSize = 20;

	/**
     * table name
     * @var    string
     */
	private $_table = 'shop_brand';

	/**
     * table name
     * @var    string
     */
	private $_goods_table = 'shop_goods';

	private $_product_table = 'shop_product';
	
	private $_goods_cat_table = 'shop_goods_cat';

	private $_table_brand_region = 'shop_brand_region';
	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	/**
     * 取得品牌商品数
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getGoodsBrandNum($brand_id = null)
	{
        if($brand_id>=1){
          return  $this -> _db -> fetchOne("SELECT count(*) as count FROM $this->_goods_table where  brand_id='$brand_id'");
        }
	}

	/**
     * 获取记录集
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
			$whereSql = "WHERE $where";
		}

		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY brand_id";
		}

		$sql = "SELECT $fields FROM `$this->_table` $whereSql $orderBy $limit";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 插入记录
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		if ( $this -> fetch("brand_name = '{$data['brand_name']}'") ) {
		    return false;
		}

		$row = array ('brand_name' => $data['brand_name'],
                      'as_name' => $data['as_name'],
                      'char' => $data['char'],
			          'region' => $data['region'],
                      'title' => $data['title'],
                      'keywords' => $data['keywords'],
                      'description' => $data['description'],
                      'topcateids' => $data['topcateids'],
                      'brand_desc' => $data['brand_desc'],
			          'introduction' => $data['introduction'],
                      'status' => $data['status'],
                      'add_time' => $data['add_time'],
                      );
		if($data['big_logo']){
			$row['big_logo']=$data['big_logo'];
		}
		if($data['small_logo']){
			$row['small_logo']=$data['small_logo'];
		}
        $this -> _db -> insert($this -> _table, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

	/**
     * 更新记录
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function update($data, $id)
	{
		if ( $this -> fetch("brand_name = '{$data['brand_name']}' and brand_id <> {$id}") ) {
		    return false;
		}
		$set = array ('brand_name' => $data['brand_name'],
                      'as_name' => $data['as_name'],
                      'char' => $data['char'],
			          'region' => $data['region'],
                      'title' => $data['title'],
                      'keywords' => $data['keywords'],
                      'description' => $data['description'], 
                      'topcateids' => $data['topcateids'],
                      'brand_desc' => $data['brand_desc'],
			          'introduction' => $data['introduction'],
                      'status' => $data['status'],
                      );

		if($data['big_logo']){
			$set['big_logo'] = $data['big_logo'];
		}
		if($data['small_logo']){
			$set['small_logo'] = $data['small_logo'];
		}
        $where = $this -> _db -> quoteInto('brand_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}

	/**
     * 删除
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('brand_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table, $where);
		}
	}

	/**
     * 更新状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function updateStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('brand_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}

	/**
     * ajax操作
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('brand_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	
	 private function _getAllChildCate($pid){
		$result = $this -> _db -> fetchAll("SELECT cat_id as id,cat_name as name,parent_id as pid FROM ".$this->_goods_cat_table." where  parent_id=".$pid);
		if($result){
			$str .= "<ul>";
			for($i=0; $i<count($result);$i++){
				$id = $result[$i]['id'];
				$name = $result[$i]['name'];
				$str .="<li><label>$name</label><input type='checkbox' value='$id' name='$name'>";
				$str.= $this->_getAllChildCate($id);
				$str.="</li>";
			}
			$str .= "</ul>";
		}
		return $str;
	 }
	 /**
	  * 根据品牌ID获取推荐产品
	  */
	 public function getGoodsByBrandTag($brandId){
	 	$sql="SELECT config FROM $this->_table where  brand_id='$brandId'";
	 	$brandConfig=$this -> _db -> fetchOne($sql);
	 	if($brandConfig){
	 		$fields="a.goods_id,a.goods_sn,a.goods_name,a.onsale ";
		 	$where=" a.goods_id in($brandConfig)";
		 	$orderBy="find_in_set(a.goods_id, '$brandConfig')";
		 	$sql="select $fields from $this->_goods_table a where $where order by $orderBy";
		 	$result = $this -> _db -> fetchAll($sql);
		 	return $result;
	 	}else{
	 		return null;
	 	}

	 }
	 /**
     * 修改品牌推荐商品
     */
	 public function updateBrandTag($val,$brandId){
	 	$set = array ('config' => $val);
		$where = $this -> _db -> quoteInto('brand_id = ?', $brandId);
		if ($brandId > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	 }
	 /**
	  * 获取所属地
	  */
	 public function getRegion($where=null,$limit=null)
	 {
	 	$sql = "select * from `$this->_table_brand_region` {$where} {$limit}";
	 	return $this -> _db -> fetchAll($sql);
	 }
	 /**
	  * 修改归属地
	  * 
	  * @param unknown_type $data
	  * @param unknown_type $id
	  * @return boolean
	  */
	 public function updateRegion($data,$id)
	 {
	 	if ( $this -> _db -> fetchRow("select * from `$this->_table_brand_region` where region_name = '{$data['region_name']}' and region_id <> {$id}") ) {
	 		return false;
	 	}
	 	$set = array("region_name"=>$data['region_name']);
	 	if($data['region_imgurl']){
	 		$set["region_imgurl"]=$data["region_imgurl"];
	 	}
	 	
	 	$where = $this -> _db -> quoteInto('region_id = ?', $id);
	 	$this -> _db -> update($this->_table_brand_region,$set,$where);
	 	return true;
	 }
	 
	 /**
	  * 添加归属地
	  * @param unknown_type $data
	  * @return boolean|unknown
	  */
	 public function insertRegion($data)
	 {
	 	if ( $this -> _db -> fetchRow("select * from `$this->_table_brand_region` where region_name = '{$data['region_name']}'") ) {
	 		return false;
	 	}
	 	
	 	$set = array("region_name"=>$data['region_name']);
	 	if($data['region_imgurl']){
	 		$set["region_imgurl"]=$data["region_imgurl"];
	 	}
	 	
	 	$this -> _db -> insert($this -> _table_brand_region, $set);
	 	$lastInsertId = $this -> _db -> lastInsertId();
	 	return $lastInsertId;
	 }
	 
}