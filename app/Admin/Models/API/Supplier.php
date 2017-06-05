<?php

class Admin_Models_API_Supplier
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
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Supplier();
	}
	
	/**
     * 根据供应商Id取商品
     *
     * @param    string    $product_ids
     * @return   array
     */
	public function getProductSupplierData($product_ids)
	{
		if (!$product_ids)  return 0;
		return $this -> _db -> getProductSupplierData($product_ids);
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
		if (is_array($where)) {
		    $whereSQL = 1;
		    $where['supplier_name'] && $whereSQL .= " and supplier_name like '%{$where['supplier_name']}%'";
		}
		else {
		    $whereSQL = $where;
		}
		return $this -> _db -> fetch($whereSQL, $fields, $orderBy, $page, $pageSize);
	}
	

	/**
     * 取供应商信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getSupplier($where = null, $fields = '*')
	{
		return $this -> _db -> getSupplier($where, $fields);
	}
	
	/**
     * 添加或修改数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function edit($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($id === null && $data['supplier_name'] == '') {
			$this -> error = 'no_name';
			return false;
		}
		if ($id) {
		    if ($this -> _db -> getSupplier("supplier_name = '{$data['supplier_name']}' and supplier_id <> '{$id}'")) {
                $this -> error = 'exists';
                return false;
            }
		}
		else {
		    if ($this -> _db -> getSupplier("supplier_name = '{$data['supplier_name']}'")) {
                $this -> error = 'exists';
                return false;
            }
		}
		
		
		$data['add_time'] = time ();
		if ($id === null) {
		    $result = $this -> _db -> insert($data);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		} else {
			$result = $this -> _db -> update($data, (int)$id);
		}
		return $result;
	}
	
	/**
     * 删除数据
     *
     * @param    int    $id
     * @return   void
     */
	public function delete($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> delete((int)$id);
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
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	
	/**
     * 更改状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateStatus((int)$id, $status) <= 0) {
				exit('failure');
			}
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
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		
		if ((int)$id > 0) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }
		}
	}
	
	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'exists'=>'该供货商已存在!',
			         'not_exists'=>'该供货商不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_name'=>'请填写供货商名称!',
			         'login_name'=>'请填写管理员名称!',
			         'loginNameExist'=>'管理员名称已存在，请更换新管理员名!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
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
	public function getProduct($where, $fields = '*')
	{
        $sql = 1;
        if ($where['product_name']) {
            $sql .= " and product_name like '%{$where['product_name']}%'";
        }
        if ($where['product_sn']) {
            $sql .= " and product_sn = '{$where['product_sn']}'";
        }
        if ($where['product_ids']) {
            $sql .= " and product_id in ({$where['product_ids']})";
        }
        
        return  $this -> _db -> getProduct($sql, $fields, $page, $pageSize, $orderBy);
    }

	/**
     * 编辑供应商产品
     *
     * @return   
     */
	public function editSupplierProduct($data = null,$supplier_id)
	{
        if ($data['product_id']) {
            $product_ids = implode(',', $data['product_id']);
        }
        else    $product_ids = '';
        
        $result = $this -> _db -> editSupplierProduct($supplier_id, $product_ids);

		if ($result) {
			$this->_db->deleteSupplierProductBySupplierId($supplier_id);

			foreach ($data['product_id'] as $product_id) {
				$product_param = array(
					'supplier_id' => $supplier_id,
					'product_id'  => $product_id,
				);
				$this->_db->insertSupplierProductInfo($product_param);
			}
			$this->_db->reallyDeleteSupplierProductBySupplierId($supplier_id);
		}

		return true;
    }

    /**
     * 获得某个产品的所有供应商
     *
     * @return   
     */
	public function getProductSupplier($product_id)
	{
	    $datas = $this -> _db -> getSupplier('status = 0', 'supplier_id,supplier_name,product_ids');
	    if (!$datas)    return false;
	    
	    if (!is_array($product_id)) {
	        $product_id = array($product_id);
	    }

	    foreach ($datas as $data) {
	        if (!$data['product_ids'])  continue;
	        
	        $tempArr = explode(',', $data['product_ids']);
	        foreach ($product_id as $id) {
	            if (in_array($id, $tempArr)) {
	                $result[$data['supplier_id']] = $data;
	            }
	        }
	    }
	    
	    return $result;
	}

}