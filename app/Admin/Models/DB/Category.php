<?php
class Admin_Models_DB_Category 
{
	/**
     * page size
     * @var    int
     */
	private $_pageSize = 100;
	
	/**
     * table name
     * @var    string
     */
	private $_table = 'shop_goods_cat';
	private $_table_goods = 'shop_goods';
	private $_table_goods_relation = 'shop_goods_relation';
	private $_table_cat_attr = 'shop_cat_attr';
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
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @return   array
     */
	public function fetch($where = null, $fields = '*', $orderBy = null)
	{
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY parent_id, cat_sort DESC";
		}
		
		$sql = "SELECT $fields FROM `$this->_table` $whereSql $orderBy";
		return $this -> _db -> fetchAll($sql);
	}
	
	
	public function getProductCat($where = null, $fields = '*', $orderBy = null)
	{
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY parent_id, cat_sort";
		}
	
		$sql = "SELECT $fields FROM `$this->_table` $whereSql $orderBy";
		return $this -> _db -> fetchAll($sql);
	}
	
	public function getProductCatALL($fields = '*', $orderBy = null)
	{		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY parent_id, cat_sort";
		}
		$sql = "SELECT $fields FROM `$this->_table`  $orderBy";
		return $this -> _db -> fetchAll($sql);
	}
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertCat(array $data)
	{
		$row = array (
				'cat_name' => $data['cat_name'],
				'cat_sn' => $data['cat_sn'],
				'cat_path' => $data['cat_path'],			
				'parent_id' => $data['parent_id'],				
				'cat_status' => $data['cat_status']?$data['cat_status']:0 ,
				'meta_title' => $data['meta_title'],
				'meta_keywords' => $data['meta_keywords'],
				'meta_description' => $data['meta_description'],
				'display' => $data['display']?$data['display']:0,
				'brand_link_ids' => $data['brand_link_ids']?$data['brand_link_ids']:'',
		);
	
		$this -> _db -> insert($this -> _table, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		$where = $this -> _db -> quoteInto('cat_id = ?', $lastInsertId);
		$set = array ('cat_path' => $data['cat_path'].$lastInsertId.',');
		$this -> _db -> update($this -> _table, $set, $where);
		return $lastInsertId;
	}

	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function updateCat($data, $id)
	{
		$set = array (
				'cat_name' => $data['cat_name'],
				'cat_sn' => $data['cat_sn'],
				'cat_path' => $data['cat_path'],
			    'meta_title' => $data['meta_title'],
			    'meta_keywords' => $data['meta_keywords'],
			    'brand_link_ids' => $data['brand_link_ids'],
			    'meta_description' => $data['meta_description'],
				'parent_id' => $data['parent_id']				
		);
		$where = $this -> _db -> quoteInto('cat_id = ?', $id);
		if ($id > 0) {
			$this -> _db -> update($this -> _table, $set, $where);
			return true;
		}
	}
	
	/**
     * 编辑商品品牌属性
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function bandcat($data, $id)
	{
        $where = $this -> _db -> quoteInto('cat_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $data, $where);
		    return true;
		}
	}
    /**
     * 保存类别的品牌数据
     */
    public function saveRelationBrand($cat_id,$brand_ids){
		$set = array ('brand_link_ids' => $brand_ids);
		$where = $this -> _db -> quoteInto('cat_id = ?', $cat_id);
		return $this -> _db -> update($this -> _table, $set, $where);
    }

	/**
     * 删除数据
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('cat_id = ?', $id);
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
		$set = array ('cat_status' => $status);
		$where = $this -> _db -> quoteInto('cat_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	/**
     * 更新显示状态
     *
     * @param    int    $id
     * @param    int    $display
     * @return   void
     */
	public function updateDisplay($id, $display)
	{
		$set = array ('display' => $display);
		$where = $this -> _db -> quoteInto('cat_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
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
		$where = $this -> _db -> quoteInto('cat_id = ?', $id);
		if ($id > 0) {
			$fields = array('cat_name', 'cat_sort');
			if(in_array($field, $fields)){
			    
		        $this -> _db -> update($this -> _table, $set, $where);
		    }
		    return true;
		}
	}
	
	
	/**
     * 批量更改分类路径
     *
     * @param    int      $id
	 * @param    string   $old_cat_path
	 * @param    string   $cat_path
     * @return   void
     */
	public function changeCat($id, $old_cat_path, $cat_path)
	{
		if ((int)$id > 0) {
		    $datas = $this -> fetch("cat_path like '%,$id,%'");
		    if($datas){
				foreach ($datas as $data){
			        $val = str_replace($old_cat_path, $cat_path, $data['cat_path']);
			        $set = array ('cat_path' => $val);
					$where = $this -> _db -> quoteInto('cat_id = ?', $data['cat_id']);
					$this -> _db -> update($this -> _table, $set, $where);
				}
				return true;
		    }
		}
	}
	
	/**
	 * 保存类别的商品关联
	 * @param unknown_type $cat_id
	 * @param unknown_type $type
	 * @param unknown_type $arr_goods_id
	 */
	public function saveRelation($id,$limit_type,$type,$arr_goods_id){
	    
	    $data = array(
	        'id'=>$id,
	        'type'=>$type,
	        'limit_type'=>$limit_type,
	        'goods_ids'=>implode(',', $arr_goods_id)
	    );
	    $where = array('id'=>$id,'type'=>$type,'limit_type'=>$limit_type);
	    if($this->isIn($this->_table_goods_relation,$where)){
            $flag = parent::update($this->_table_goods_relation,$data,$where); 	        
	    }else{
	        $flag = $this->_db->insert($this->_table_goods_relation,$data);
	    }

	    return $flag;
	    
	}
	
	/**
	 * 取得分类关联数据
	 * @param unknown_type $cat_id
	 * @param unknown_type $type
	 */
	public function getRelation($id,$limit_type,$type){
	    $r = $this->getRow($this->_table_goods_relation,array('id'=>$id,'type'=>$type,'limit_type'=>$limit_type));
	    if(empty($r['goods_ids'])) return array();
	    $list_goods = $this->getAll($this->_table_goods,array('goods_id|in'=>$r['goods_ids']));
	    return $list_goods;
	}
	
    public function deleteCatAttr($where)
    {
        $this -> _db -> delete($this -> _table_cat_attr, $where);
    }
    
    public function insertCatAttr($data)
    {
        $this -> _db -> insert($this -> _table_cat_attr, $data);
    }
    
    public function getCatAttr($where = 1)
    {
        return $this -> _db -> fetchAll("select * from {$this -> _table_cat_attr} where {$where}");
    }
}