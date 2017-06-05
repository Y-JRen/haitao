<?php

class Admin_Models_API_Cost
{
	/**
     * DB对象
     */
	private $_db = null;
	
	public $error = "";
	
	private $_auth  = null;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Cost();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	
	/*
	 * 申请调整成本
	 * 
	 */
	public function add($param){
	    $ids = $param['ids'];
	    $index = count($ids);
	    $costons = $param['coston'];
	    $costs = $param['cost'];
	    $stockAPI = new Admin_Models_API_Stock();
	    for ($i = 0; $i < $index; $i++) {
	        $stockInfo = $stockAPI -> getProductStock(array('product_id' => $ids[$i]));
	        if ($stockInfo['real_number'] <= 0) {
	            $this -> error = 'err_stock_number';
	            return false;
	        }
	    }
	    
	    $cost_data['bill_sn'] = date("Ymds").rand(1000,9999);
	    $cost_data['add_time'] = time();
	    $cost_data['stauts'] = 0;
	    $cost_data['admin_name'] =  $this -> _auth['admin_name'];
	    $cost_data['remark'] =  $param['remark'];
	    $id = $this->_db->insert($cost_data);
        //插入详情
	    for ($i = 0; $i < $index; $i++) {
	        $cost_detail_data = array();
	        $cost_detail_data['bill_id'] = $id;
	        $cost_detail_data['product_id'] = $ids[$i];
	        $cost_detail_data['cost'] = $costs[$i];
	        $cost_detail_data['precost'] = $costons[$i];
	        $this->_db->insertDetail($cost_detail_data); 
	    }
	       return true;
	}


	/*获取库存调整list*/
	public function getCostList($_param,$fied,$page){    
	    return $this->_db->get($this->creatWhereConf($_param),$fied,$page);
	}

	/*获取未审核的cost*/
	public function getCostListByCheck($_param,$fied,$page){
	    $wherestr = $this->creatWhereConf($_param);
	    return $this->_db->get(" stauts=0 and ".$wherestr,$fied,$page);
	}
	
	/*组合where条件*/
	private function creatWhereConf($_param){
	    $where_str = ' 1=1 ';
	    if(!empty($_param['fromdate'])){
	       $where_str .=' and add_time > '. strtotime($_param['fromdate']);
	    }
	    if(!empty($_param['fromdate'])){
	        $where_str .=' and add_time < '. strtotime($_param['todate']);
	    }
	    if(!empty($_param['admin_name'])){
	        $where_str .=' and admin_name = "'. trim($_param['admin_name']).'" ';
	    }
	    if(!empty($_param['bill_no'])){
	        $where_str .=' and bill_sn = "'. trim($_param['bill_no']).'" ';
	    }
	    if(!empty($_param['bill_status'])){
	        $where_str .=' and stauts = '. $_param['bill_status'];
	    }
	    return $where_str;
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @param    string    $orderBy
     * @return   array
     */
	public function get($where = null, $fields = '', $page=null, $pageSize = null, $orderBy = null)
	{
		return $this -> _db -> get($where, $fields, $page, $pageSize, $orderBy);
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
			         'err_cost_format'=>'成本格式不正确!',
			         'err_stock_number'=>'实际库存为0，不能调整成本!',
			        );
		if(array_key_exists($this->error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
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
	                $set = array('lock_name' => $admin_name);
	                $where = "lock_name=''";
	            } else {
	                $set = array('lock_name' => '');
	                $where = ($this -> _auth['group_id'] > 1 ) ? "lock_name='$admin_name'" : '1';
	            }
	            $this -> _db -> update($set, "$where and bill_id=$v");
	           
	        }
	    }
	    return true;
	}

	//根据单据编号查询单据信息及详情
	public function checkdetail($id){
	   return  $this->_db->getInfoById($id);
	}

    //审核操作
    public function gocheck($param){
        //处理数据
        $flag = 1;
        $flag = $param['is_check'];
        $ck_remark = $param['remark'];
        if($flag == 1){ //审核
            $s_data['stauts'] =  1;
            //处理产品成本平均价
            $prods = $param['product_id'];
            $prod_prices = $param['precost'];
            $index = count($prods);
            for ($i = 0; $i < $index; $i++) {
                $this -> updateCost($prods[$i], $prod_prices[$i]);
            }
        }else{//拒绝
            $s_data['stauts'] =  2;
            $flag = 0;
        }
        $s_data['check_time'] =  time();
        $s_data['check_admin_name'] = $this -> _auth['admin_name'];
        $s_data['check_remark'] =  $ck_remark;     
        $where = " bill_id =".$param['id'];
        $this->_db->update($s_data,$where);
       
        return $flag;
    }
    
    public function updateCost($productID, $cost)
    {
        $stockAPI = new Admin_Models_API_Stock();
        $stock = $stockAPI -> getProductStock(array('product_id' => $productID));
        $stockNumber = $stock['real_number'];
        
        $productAPI = new Admin_Models_API_Product();
        $product = array_shift($productAPI -> get(array('product_id' => $productID)));
        $currentCost = $product['cost'];
        
        $price = ($stockNumber + 1) * $cost - $stockNumber * $currentCost;
        
        $instockAPI = new Admin_Models_API_InStock();
        $billNo = Custom_Model_CreateSn::createSn();
        $row = array('lid' => 1,
		             'bill_status' => 6,
			         'bill_no' => $billNo,
			         'bill_type' => 20,
			         'add_time' => time(),
			         'admin_name' => $this -> _auth['admin_name'],
			         'remark' => '',
			        );
        $details = '';
        $details[] = array('product_id' => $productID,
	                       'batch_id' => 0,
	                       'plan_number' => 1,
	                       'shop_price' => $price
	                      );
        $instockAPI -> insertApi($row, $details, 1, true);
        $instockAPI -> receiveByBillNo($billNo);
        
        $outstockAPI = new Admin_Models_API_OutStock();
        $row = array ('lid' => 1,
		              'bill_no' => $billNo,
                      'bill_type' => 20,
                      'bill_status' => 4,
                      'add_time' => time(),
                      'admin_name' => $this -> _auth['admin_name'],
                      );
        $details = '';
        $details[] = array('product_id' => $productID,
	                       'batch_id' => 0,
	                       'status_id' => 1,
	                       'number' => 1,
	                       'shop_price' => $cost,
	                      );
        $outstockAPI -> insertApi($row, $details, 1, true);
        $outstockAPI -> sendByBillNo($billNo);
    }
}