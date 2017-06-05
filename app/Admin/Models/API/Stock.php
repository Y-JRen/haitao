<?php

class Admin_Models_API_Stock
{
	private $_db = null;
	private $_auth = null;
	private $_count;
	private $_logicArea;
	public $error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> _logicArea = 1;
	}
	
	/**
     * 设置仓库
     *
     * @logicArea   int
     * @return      void
     */
	public function setLogicArea($logicArea)
	{
		$this -> _logicArea = $logicArea;
	}
	
	/**
     * 获得 产品/批次 实际/在途/占用/可用 库存
     *
     * @where       array
     * @return      array
     */
	public function getProductStock($where, $groupBy = 'product_id')
	{
	    if ($groupBy != 'product_id' && $groupBy != 'batch_id' && $groupBy != 'product_id,batch_id' && $groupBy != 'product_id,batch_id' && $groupBy != 'product_id,batch_id,status_id') {
	        return false;
	    }
	    
	    $stockData = $this -> getSumStock($where, $groupBy);
	    if (!$stockData)    return false;
	    foreach ($stockData as $data) {
	        if ($groupBy == 'product_id' || $groupBy == 'batch_id') {
	            $result[$data[$groupBy]]['real_number'] = $data['real_in_number'] - $data['real_out_number'];
	            $result[$data[$groupBy]]['wait_number'] = $data['in_number'] - $data['real_in_number'];
	            $result[$data[$groupBy]]['hold_number'] = $data['out_number'] - $data['real_out_number'];
	            $result[$data[$groupBy]]['able_number'] = $result[$data[$groupBy]]['real_number'] - $result[$data[$groupBy]]['hold_number'];
	        }
	        else {
	            $tempData = array();
	            $tempData['real_number'] = $data['real_in_number'] - $data['real_out_number'];
	            $tempData['wait_number'] = $data['in_number'] - $data['real_in_number'];
	            $tempData['hold_number'] = $data['out_number'] - $data['real_out_number'];
	            $tempData['able_number'] = $tempData['real_number'] - $tempData['hold_number'];
	            if ($groupBy == 'product_id') {
	                $result[$data['product_id']] = $tempData;
	            }
	            else if ($groupBy == 'batch_id') {
	                $result[$data['batch_id']] = $tempData;
	            }
	            else if ($groupBy == 'product_id,batch_id') {
	                $result[$data['product_id']][$data['batch_id']] = $tempData;
	            }
	            else if ($groupBy == 'product_id,batch_id,status_id') {
	                $result[$data['product_id']][$data['batch_id']][$data['status_id']] = $tempData;
	            }
	        }
	    }
        
	    if ($where['product_id'] && !is_array($where['product_id']) && $groupBy == 'product_id') {
	        return $result[$where['product_id']];
	    }
	    if ($where['batch_id'] && !is_array($where['batch_id']) && $groupBy == 'batch_id') {
	        return $result[$where['batch_id']];
	    }
	    
	    return $result;
	}
	
	/**
     * 获得产品出库库存
     *
     * @productID   int/array
     * @return      array
     */
	public function getProductOutStock($where = 1, $page = null, $pageSize = null)
	{
	  
	    if ($page) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
        
	    if (is_array($where)) {
	        $whereSQL = 1;
	        is_array($where['product_id']) && $whereSQL .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        !is_array($where['product_id']) && $where['product_id'] && $whereSQL .= " and t1.product_id = {$where['product_id']}";
	        is_array($where['batch_id']) && $whereSQL .= " and t1.batch_id in (".implode(',', $where['batch_id']).")";
	        !is_array($where['batch_id']) && $where['batch_id'] && $whereSQL .= " and t1.batch_id = {$where['batch_id']}";
	        is_array($where['lid']) && $whereSQL .= " and t1.lid in (".implode(',', $where['lid']).")";
	        !is_array($where['lid']) && $where['lid'] && $whereSQL .= " and t1.lid = {$where['lid']}";
	        is_array($where['logic_area']) && $whereSQL .= " and t1.lid in (".implode(',', $where['logic_area']).")";
	        !is_array($where['logic_area']) && $where['logic_area'] && $whereSQL .= " and t1.lid = {$where['logic_area']}";
	        is_array($where['status_id']) && $whereSQL .= " and t1.status_id in (".implode(',', $where['status_id']).")";
	        !is_array($where['status_id']) && $where['status_id'] && $whereSQL .= " and t1.status_id = {$where['status_id']}";
	        $where['product_sn'] && $whereSQL .= " and t2.product_sn = '{$where['product_sn']}'";
	        $where['product_name'] && $whereSQL .= " and t2.product_name like '%{$where['product_name']}%'";
	        $where['cat_id'] && $whereSQL .= " and t3.cat_path like '%,{$where['cat_id']},%'";
	        if ($where['p_status'] !== null && $where['p_status'] !== '') {
	            $whereSQL .= " and t2.p_status = {$where['p_status']}";
	        }
	        if ($where['fromprice'] && $where['toprice']) {
	            $whereSQL .= " and (t2.suggest_price between {$where['fromprice']} and {$where['toprice']})";
	        }
	    }
	    else    $whereSQL = $where;
        
	    $sql = "select %%field%% from shop_stock_status as t1 
	            inner join shop_product as t2 on t1.product_id = t2.product_id
	            inner join shop_goods_cat as t3 on t2.cat_id = t3.cat_id
	            inner join shop_logic_status as t4 on t4.id = t1.status_id 
	            inner join shop_product_goods as t5 on t5.product_id = t2.product_id
	            inner join shop_goods as t6 on t6.goods_id = t5.goods_id
	            {$joinGoodsTable}
	            where {$whereSQL}
	            group by t1.product_id";
	            //having stock_number > 0";
	            //供应商id sid
	            $mysid = $where['sid'] ;
        	    if(!empty($mysid)){
        	        $supp_sql = 'SELECT product_ids from shop_supplier WHERE supplier_id = ' .$mysid;
        	        $supp_obj = $this->_db->fetchone($supp_sql);
        	        if(empty($supp_obj)) return false;
        	        $sup_tmp_sql = " AND t2.product_id in ($supp_obj)  group by ";
        	        $sql = str_replace('group by', $sup_tmp_sql, $sql);
        	       
        	    }
	    $field = 'count(*) as count,sum(t1.real_in_number - t1.out_number) as stock_number';     

        $this -> _count = $this -> _db -> fetchOne("select count(*) as num from (".str_replace('%%field%%', $field, $sql).") a");
        if ($this -> _count == 0)   return false;
        
        $field = 't1.product_id,sum(t1.real_in_number - t1.out_number) as stock_number,t2.product_sn,t2.product_name,t2.goods_style,t2.cost,t2.purchase_cost,t2.adjust_num,t3.cat_name,t4.name as status_name';
        $field .=',t2.suggest_price as price,t2.price_limit,t6.tax,t6.shop_price';
     
        !is_array($where['status_id']) && $where['status_id'] && $field .= ',t1.status_id';
        !is_array($where['logic_area']) && $where['logic_area'] && $field .= ',t1.lid';
        !is_array($where['lid']) && $where['lid'] && $field .= ',t1.lid';
        $sql .= " {$limit}";
	    $datas = $this -> _db -> fetchAll(str_replace('%%field%%', $field, $sql));
	    
	    foreach ($datas as $data) {
	        $productIDArray[] = $data['product_id'];
	    }
	    
	    $where1 = array();
    	$where['status_id'] && $where1['status_id'] = $where['status_id'];
    	$where['logic_area'] && $where1['lid'] = $where['logic_area'];
    	$where['lid'] && $where1['lid'] = $where['lid'];
    	$where['batch_id'] && $where1['batch_id'] = $where['batch_id'];
    	$where1['product_id'] = $productIDArray;
    	$stockData = $this -> getSumStock($where1, 'product_id,batch_id');
	    foreach ($stockData as $data) {
	        $tempData = array('batch_id' => $data['batch_id'],
	                          'real_number' => $data['real_in_number'] - $data['real_out_number'],
	                          'hold_number' => $data['out_number'] - $data['real_out_number'],
	                         );
	        $tempData['able_number'] = $tempData['real_number'] - $tempData['hold_number'];
	        !is_array($where['status_id']) && $where['status_id'] && $tempData['status_id'] = $data['status_id'];
	        !is_array($where['logic_area']) && $where['logic_area'] && $tempData['lid'] = $data['lid'];
	        !is_array($where['lid']) && $where['lid'] && $tempData['lid'] = $data['lid'];
	        
	        $stockInfo[$data['product_id']][] = $tempData;
	    }
	    
	    $productAPI = new Admin_Models_API_Product();
	    $batchData = $productAPI -> getBatch(array('product_ids' => $productIDArray));
	    if ($batchData['data']) {
    	    foreach ($batchData['data'] as $data) {
    	        for ($i = 0; $i < count($stockInfo[$data['product_id']]); $i++) {
    	            if ($stockInfo[$data['product_id']][$i]['batch_id'] == $data['batch_id']) {
    	                $stockInfo[$data['product_id']][$i]['batch_no'] = $data['batch_no'];
    	                $stockInfo[$data['product_id']][$i]['cost'] = $data['cost'];
    	                break;
    	            }
    	        }
    	    }
	    }
        
	    foreach ($datas as $index => $data) {
	        if ($stockInfo[$data['product_id']]) {
	            foreach ($stockInfo[$data['product_id']] as $stock) {
	                $datas[$index]['real_number'] += $stock['real_number'];
	                $datas[$index]['hold_number'] += $stock['hold_number'];
	                $datas[$index]['able_number'] += $stock['able_number'];
	            }
	        }
	        $datas[$index]['batch'] = $stockInfo[$data['product_id']];
	        $datas[$index]['pinfo'] = Zend_Json::encode($datas[$index]);
	    }
	    
	    return $datas;
	}
	
	/**
     * 获得销售产品出库库存
     *
     * @productID   int/array
     * @return      array
     */
	public function getSaleProductOutStock($where = 1, $page = null, $pageSize = null, $includeAdjustNumber = false)
	{
	    $where['logic_area'] && $where['lid'] = $where['logic_area'];
	    $condition = $this -> getSaleOutStockCondition();
	    $where['lid'] = $condition['lid'];
	    !$where['status_id'] && $where['status_id'] = $condition['status_id'];
	    $datas = $this -> getProductOutStock($where, $page, $pageSize);
	    if (!$datas)    return false;

        if ($where['status_id'] == 2 && in_array($where['lid'], $this -> getEntityAreaID())) {
            foreach ($datas as $data) {
        	    $productIDArray[] = $data['product_id'];
            }
        	$productData = $this -> getProductHoldStock("t1.product_id in (".implode(',', $productIDArray).")", 'inner');
        	if ($productData) {
        	    foreach ($productData as $data) {
        	        $holdStockInfo[$data['product_id']] = $data['number'] ? $data['number'] : 0;
        	    }
            }
        }
	    foreach ($datas as $index => $data) {
			if (!empty($where['shop_id'])) {
				$product_params = array(
						'shop_id'    => $where['shop_id'],
						'type'       => '1',
						'start_ts'   => empty($where['add_time']) ? date('Y-m-d H:i:s') : $where['add_time'],
						'product_sn' => $data['product_sn'],
					);
			}
	        $datas[$index]['hold_number'] += $holdStockInfo[$data['product_id']];
	        $datas[$index]['able_number'] = $datas[$index]['real_number'] - $datas[$index]['hold_number'];
	        if ($includeAdjustNumber) {
	           $datas[$index]['able_number'] = $datas[$index]['able_number'] + $datas[$index]['adjust_num'];
	        }
	        $datas[$index]['pinfo'] = Zend_Json::encode($datas[$index]);
	    }
	    return $datas;
	}
	
	/**
     * 获得上次查询的记录数
     *
     * @return      int
     */
	public function getCount()
	{
	    return $this -> _count;
	}
	
	/**
     * 获得销售产品 实际/可用/占用 库存
     *
     * @productID   int/array
     * @includeAdjustNumber   bool
     * @return      array
     */
	public function getSaleProductStock($productID = null, $includeAdjustNumber = false)
	{
	    if (is_array($productID)) {
	        $where = "t1.product_id in (".implode(',', $productID).")";
	    }
	    else if ($productID){
	        $where = "t1.product_id = {$productID}";
	    }
	    else    $where = 1;
	    
	    $condition = $this -> getSaleOutStockCondition();
	    $stockData = $this -> getSumStock(array('product_id' => $productID, 'lid' => $condition['lid'], 'status_id' => $condition['status_id']));
	    if ($stockData) {
	        foreach ($stockData as $data) {
    	        $stockInfo[$data['product_id']] = $data;
    	    }
	    }
	    $productData = $this -> getProductHoldStock($where);
	    foreach ($productData as $data) {
	        $result[$data['product_id']]['real_number'] = $stockInfo[$data['product_id']]['real_in_number'] - $stockInfo[$data['product_id']]['real_out_number'];
	        $result[$data['product_id']]['hold_number'] = $data['number'] + $stockInfo[$data['product_id']]['out_number'] - $stockInfo[$data['product_id']]['real_out_number'];
	        $result[$data['product_id']]['able_number'] = $result[$data['product_id']]['real_number'] - $result[$data['product_id']]['hold_number'];
	        if ($includeAdjustNumber) {
	            $result[$data['product_id']]['able_number'] += $data['adjust_num'];
	        }
	    }
	    if (!is_array($productID) && $productID) {
	        return $result[$productID];
	    }

	    return $result;
    }
    
    /**
     * 获得所有库存明细
     *
     * @where       array
     * @groupBy     array
     * @page        int
     * @pageSize    int
     * @return      array
     */
	public function getAllProductStock($where = 1, $groupBy = null, $page = null, $pageSize = null)
	{
	    if ($page) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		$where['logic_area'] && $where['lid'] = $where['logic_area'];
	    $whereSQL1 = "(t1.in_number <> 0 or t1.out_number <> 0 or t1.real_in_number <> 0 or t1.real_out_number <> 0)";
	    is_array($where['lid']) && $whereSQL1 .= " and t1.lid in (".implode(',', $where['lid']).")";
	    !is_array($where['lid']) && $where['lid'] && $whereSQL1 .= " and t1.lid = {$where['lid']}";
	    is_array($where['batch_id']) && $whereSQL1 .= " and t1.batch_id in (".implode(',', $where['batch_id']).")";
	    !is_array($where['batch_id']) && $where['batch_id'] && $whereSQL1 .= " and t1.batch_id = {$where['batch_id']}";
	    is_array($where['product_id']) && $whereSQL1 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	    !is_array($where['product_id']) && $where['product_id'] && $whereSQL1 .= " and t1.product_id = {$where['product_id']}";
	    is_array($where['status_id']) && $whereSQL1 .= " and t1.status_id in (".implode(',', $where['status_id']).")";
	    !is_array($where['status_id']) && $where['status_id'] && $whereSQL1 .= " and t1.status_id = {$where['status_id']}";
	    $where['batch_no'] && $whereSQL1 .= " and t4.batch_no = '{$where['batch_no']}'";
	    $where['product_sn'] && $whereSQL1 .= " and t2.product_sn = '{$where['product_sn']}'";
	    $where['product_name'] && $whereSQL1 .= " and t2.product_name like '%{$where['product_name']}%'";
	    $where['cat_id'] && $whereSQL1 .= " and t3.cat_path like '%,{$where['cat_id']},%'";
	    $where['p_status'] !== null && $where['p_status'] !== '' && $whereSQL1 .= " and t2.p_status = '{$where['p_status']}'";
        
		if ($groupBy) {
		    $keyField1 = '';$keyField3 = '';
		    foreach ($groupBy as $data) {
		        $keyField1 .= "t1.{$data},";
		        $keyField3 .= "{$data},";
		    }
		    
		    if (in_array('batch_id', $groupBy)) {
		        $joinBatchTable = 'left join shop_product_batch as t4 on t1.batch_id = t4.batch_id';
		        $fieldBatch = ',t4.batch_no';
    	    }
    	    else {
        	    if (!$where['lid'] || $this -> includeEntityArea($where['lid'])) {
            	    $whereSQL2 = "t7.number <> 0 and t7.number is not null";
            		$where['product_sn'] && $whereSQL2 .= " and t5.product_sn = '{$where['product_sn']}'";
                	$where['product_name'] && $whereSQL2 .= " and t5.product_name like '%{$where['product_name']}%'";
                	is_array($where['product_id']) && $whereSQL2 .= " and t5.product_id in (".implode(',', $where['product_id']).")";
	                !is_array($where['product_id']) && $where['product_id'] && $whereSQL2 .= " and t5.product_id = {$where['product_id']}";
                	$where['cat_id'] && $whereSQL2 .= " and t6.cat_path like '%,{$where['cat_id']},%'";
                	$where['p_status'] !== null && $where['p_status'] !== '' && $whereSQL2 .= " and t5.p_status = '{$where['p_status']}'";
                    $condition = $this -> getSaleOutStockCondition();
                	$valueMap = array('status_id' => $condition['status_id'],
            		                  'batch_id' => 0,
            		                  'product_id' => 't5.product_id'
            		                 );
            		$keyField2 = '';
            		foreach ($groupBy as $data) {
            		    $keyField2 .= "{$valueMap[$data]} as {$data},";
            		}
            		$field2 = "{$keyField2}0 as in_number,t7.number as out_number,0 as real_in_number,0 as real_out_number,t5.product_sn,t5.product_name,t5.goods_style,t5.goods_units,t5.ean_barcode,t5.local_sn,t5.p_status,t5.warn_number,t6.cat_name";
            		$whereSQL = 1;
            		if ($where['lid']) {
            		    if (is_array($where['lid'])) {
            		        $whereSQL7 .= "area_id in (".implode(',', $where['lid']).")";
            		    }
            		    else {
            		        $whereSQL7 .= "area_id = {$where['lid']}";
            		    }
            		}
                }
    	    }
    	    
    	    if ($field2) {
    	        $field1 = $keyField1.'t1.in_number,t1.out_number,t1.real_in_number,t1.real_out_number,t2.product_sn,t2.ean_barcode,t2.product_name,t2.goods_style,t2.goods_units,t2.local_sn,t2.p_status,t2.warn_number,t3.cat_name'.$fieldBatch;
    	    }
    	    else {
    	        $field1 = $keyField1.'sum(t1.in_number) as in_number,sum(t1.out_number) as out_number,sum(t1.real_in_number) as real_in_number,sum(t1.real_out_number) as real_out_number,t2.product_sn,t2.product_name,t2.goods_style,t2.ean_barcode,t2.goods_units,t2.local_sn,t2.p_status,t2.warn_number,t3.cat_name'.$fieldBatch;
    	    }
            
            if ($where['stock_number'] !== null && $where['stock_number'] !== '') {
                $whereSQL3 = '1';
                $logicOperation = $where['stock_number_logic'] == 'more' ? '>=' : '<';
                if ($where['stock_number_type'] == 'real_number') {
                    $whereSQL3 .= " and real_in_number - real_out_number {$logicOperation} {$where['stock_number']}";
                }
                else if ($where['stock_number_type'] == 'able_number') {
                    $whereSQL3 .= " and real_in_number - out_number {$logicOperation} {$where['stock_number']}";
                }
                else if ($where['stock_number_type'] == 'hold_number') {
                    $whereSQL3 .= " and out_number - real_out_number {$logicOperation} {$where['stock_number']}";
                }
                else if ($where['stock_number_type'] == 'wait_number') {
                    $whereSQL3 .= " and in_number - real_in_number {$logicOperation} {$where['stock_number']}";
                }
                else if ($where['stock_number_type'] == 'plan_number') {
                    $whereSQL3 .= " and in_number - out_number {$logicOperation} {$where['stock_number']}";
                }
    	    }
		    $tempBy = $groupBy;
		    $groupBy = 'group by ';
		    foreach ($tempBy as $data) {
		        $groupBy .= "{$data},";
		    }
		    $groupBy = substr($groupBy, 0, -1);
		}
		else {
		    if ($where['stock_number'] !== null && $where['stock_number'] !== '') {
                $whereSQL3 = '1';
                $logicOperation = $where['stock_number_logic'] == 'more' ? '>=' : '<';
                if ($where['stock_number_type'] == 'real_number') {
                    $whereSQL3 .= " and t1.real_in_number - t1.real_out_number {$logicOperation} {$where['stock_number']}";
                }
                else if ($where['stock_number_type'] == 'able_number') {
                    $whereSQL3 .= " and t1.real_in_number - t1.out_number {$logicOperation} {$where['stock_number']}";
                }
                else if ($where['stock_number_type'] == 'hold_number') {
                    $whereSQL3 .= " and t1.out_number - t1.real_out_number {$logicOperation} {$where['stock_number']}";
                }
                else if ($where['stock_number_type'] == 'wait_number') {
                    $whereSQL3 .= " and t1.in_number - t1.real_in_number {$logicOperation} {$where['stock_number']}";
                }
                else if ($where['stock_number_type'] == 'plan_number') {
                    $whereSQL3 .= " and t1.in_number - t1.out_number {$logicOperation} {$where['stock_number']}";
                }
    	    }
		    
		    $field1 = 't1.*,t2.product_sn,t2.product_name,t2.goods_style,t2.goods_units,t2.ean_barcode,t2.local_sn,t2.p_status,t2.warn_number,t3.cat_name,t4.batch_no';
		    $joinBatchTable = 'left join shop_product_batch as t4 on t1.batch_id = t4.batch_id';
		}
        
	    $sql1 = "select %%field1%% from shop_stock_status as t1
	            inner join shop_product as t2 on t1.product_id = t2.product_id
	            inner join shop_goods_cat as t3 on t2.cat_id = t3.cat_id
	            {$joinBatchTable}
	            where {$whereSQL1}";

        if ($field2) {
            $sql2 = "select %%field2%% from shop_product as t5
                    inner join shop_goods_cat as t6 on t5.cat_id = t6.cat_id
                    inner join (select product_id,sum(number) as number from shop_hold_stock where {$whereSQL7} group by product_id) as t7 on t5.product_id = t7.product_id
                    where {$whereSQL2}";
            if ($whereSQL3) {
                $having = "having {$whereSQL3}";
            }
            $sql1 = str_replace('%%field1%%', $field1, $sql1);
            $sql2 = str_replace('%%field2%%', $field2, $sql2);
            
            $whereSQL4 = '';
            $condition = $this -> getSaleOutStockCondition();
            is_array($where['lid']) && !$this -> includeEntityArea($where['lid']) && $whereSQL4 .= " and 0";
	        !is_array($where['lid']) && $where['lid'] && !in_array($where['lid'], $this -> getEntityAreaID()) && $whereSQL4 .= " and 0";
            is_array($where['status_id']) && !in_array($condition['status_id'], $where['status_id']) && $whereSQL4 .= " and 0";
	        !is_array($where['status_id']) && $where['status_id'] && $where['status_id'] != $condition['status_id'] && $whereSQL4 .= " and 0";
	        if ($whereSQL4) {
	            $sql2 = "select * from ({$sql2}) c where 1 {$whereSQL4}";
	        }
            $sql = "select %%field%% from ({$sql1} union all {$sql2}) a {$groupBy} {$having}";
            $this -> _count = $this -> _db -> fetchOne("select count(*) as count from ("."select 1 from (".str_replace('%%field%%', '*', $sql).") t1) as b");
        }
        else {
            $sql = $sql1;
            if ($groupBy) {
                $sql .= " {$groupBy}";
                if ($whereSQL3) {
                    $sql .= " having {$whereSQL3}";
                }
                $this -> _count = $this -> _db -> fetchOne("select count(*) as count from (".str_replace('%%field1%%', $field1, $sql).") as b");
            }
            else {
                $this -> _count = $this -> _db -> fetchOne(str_replace('%%field1%%', 'count(*) as count', $sql));
            }
        }
        
        if ($this -> _count == 0)   return false;
        
        $sql .= " {$limit}";
        if ($field2) {
            $field = "{$keyField3}sum(in_number) as in_number,sum(out_number) as out_number,sum(real_in_number) as real_in_number,sum(real_out_number) as real_out_number,product_sn,product_name,goods_style,goods_units,ean_barcode,local_sn,p_status,warn_number,cat_name";
            $datas = $this -> _db -> fetchAll(str_replace('%%field%%', $field, $sql));
        }
        else {
            $datas = $this -> _db -> fetchAll(str_replace('%%field1%%', $field1, $sql));
        }
	    foreach ($datas as $index => $data) {
	        $datas[$index]['plan_number'] = $data['in_number'] - $data['out_number'];
	        $datas[$index]['real_number'] = $data['real_in_number'] - $data['real_out_number'];
	        $datas[$index]['able_number'] = $data['real_in_number'] - $data['out_number'];
	        $datas[$index]['wait_number'] = $data['in_number'] - $data['real_in_number'];
	        $datas[$index]['hold_number'] = $data['out_number'] - $data['real_out_number'];
	    }
	    
	    return $datas;
	}
	
	/**
     * 获得 产品/批次 库存　实际入库/实际出库/计划出库/计划入库 
     *
     * @where       string
     * @groupBy     string
     * @return      array
     */
	public function getSumStock($where, $groupBy = 'product_id')
	{
	    if ($groupBy != 'product_id' && $groupBy != 'batch_id' && $groupBy != 'product_id,batch_id' && $groupBy != 'product_id,batch_id,status_id') {
	        return false;
	    }
	    
	    $field = "{$groupBy},sum(in_number) as in_number,sum(real_in_number) as real_in_number,sum(out_number) as out_number,sum(real_out_number) as real_out_number";
        !is_array($where['lid']) && $where['lid'] && $field .= ',lid';
        !is_array($where['status_id']) && $where['status_id'] && $field .= ',status_id';
        
        $where['logic_area'] && $where['lid'] = $where['logic_area'];
	    is_array($where['lid']) && $condition[] = "lid in (".implode(',', $where['lid']).")";
	    !is_array($where['lid']) && $where['lid'] && $condition[] = "lid = {$where['lid']}";
	    is_array($where['status_id']) && $condition[] = "status_id in (".implode(',', $where['status_id']).")";
	    !is_array($where['status_id']) && $where['status_id'] && $condition[] = "status_id = {$where['status_id']}";
	    is_array($where['batch_id']) && $condition[] = "batch_id in (".implode(',', $where['batch_id']).")";
	    !is_array($where['batch_id']) && $where['batch_id'] && $condition[] = "batch_id = {$where['batch_id']}";
	    is_array($where['product_id']) && $condition[] = "product_id in (".implode(',', $where['product_id']).")";
	    !is_array($where['product_id']) && $where['product_id'] && $condition[] = "product_id = {$where['product_id']}";
	    if ($condition) {
	        $where = implode(' and ', $condition);
	    }
	    else    $where = 1;

	    $sql = "select {$field}
	            from shop_stock_status 
	            where {$where}
	            group by {$groupBy}";
	    return $this -> _db -> fetchAll($sql);
	}
    
    /**
     * 占用产品销售库存数量
     *
     * @productID   int
     * @number      int
     * @return      void
     */
	public function holdSaleProductStock($productID, $number)
	{
	    /*
	    $filename = "../cron/stock.log";
	    if (file_exists($filename)) {
	        $log = file_get_contents($filename);
	    }
	    
	    $holdStockNumber = $this -> _db -> fetchOne("select number from shop_hold_stock where area_id = '{$this -> _logicArea}' product_id = {$productID}");
	    
	    $log .= date('Y-m-d H:i:s').' '.$this -> _auth['admin_name'].' hold '.$productID.' '.$holdStockNumber.' '.$number.chr(13).chr(10);
	    file_put_contents($filename, $log);
	    */
	    if ($this -> _db -> fetchRow("select 1 from shop_hold_stock where area_id = '{$this -> _logicArea}' and product_id = '{$productID}'")) {
	        $this -> _db -> execute("update shop_hold_stock set number = number + {$number} where area_id = '{$this -> _logicArea}' and product_id = {$productID}");
	    }
	    else {
	        $row = array('area_id' => $this -> _logicArea,
	                     'product_id' => $productID,
	                     'number' => $number,
	                    );
	        $this -> _db -> insert('shop_hold_stock', $row);
	    }
	    
	    
	}
	
	/**
     * 释放产品销售库存数量
     *
     * @productID   int
     * @number      int
     * @return      void
     */
	public function releaseSaleProductStock($productID, $number)
	{
	    /*
	    $filename = "../cron/stock.log";
	    if (file_exists($filename)) {
	        $log = file_get_contents($filename);
	    }
	    
	    $holdStockNumber = $this -> _db -> fetchOne("select number from shop_hold_stock where area_id = '{$this -> _logicArea}' and product_id = {$productID}");
	    
	    $log .= date('Y-m-d H:i:s').' '.$this -> _auth['admin_name'].' release '.$productID.' '.$holdStockNumber.' '.$number.chr(13).chr(10);
	    file_put_contents($filename, $log);
	    
	    if ($holdStockNumber < $number) {
	        $filename = "../cron/stock_error.log";
	        $log = '';
    	    if (file_exists($filename)) {
    	        $log = file_get_contents($filename);
    	    }
    	    $log .= date('Y-m-d H:i:s').' '.$this -> _auth['admin_name'].' '.$productID.' '.$holdStockNumber.' '.$number.chr(13).chr(10);
    	    file_put_contents($filename, $log);
	    }
	    */
	    
	    $this -> _db -> execute("update shop_hold_stock set number = number - {$number} where area_id = '{$this -> _logicArea}' and product_id = {$productID}");
	}
	
	/**
     * 检测销售产品当前库存是否可用(销售前)
     *
     * @productID   int
     * @number      int
     * @includeAdjustNumber int
     * @return      void
     */
	public function checkPreSaleProductStock($productID, $number, $includeAdjustNumber = false)
	{
	    $stockData = $this -> getSaleProductStock($productID, $includeAdjustNumber);
	    if ($stockData['able_number'] < $number) {
	        return false;
	    }
	    
	    return true;
	    
	    //return $stockData;    ???
	}
	
	/**
     * 检测销售产品当前库存是否可用(配货时)
     *
     * @productID   int
     * @number      int
     * @includeAdjustNumber int
     * @return      boolean
     */
	public function checkPrepareProductStock($productID, $number, $includeAdjustNumber = false)
	{
	    $stockData = $this -> getSaleProductStock($productID, $includeAdjustNumber);
	    if ($stockData['able_number'] + $number < $number) {
	        return false;
	    }
	    
	    return true;
	}
	
	/**
     * 检测虚拟仓的当前库存是否可用
     *
     * @productID   int
     * @number      int
     * @return      boolean
     */
	public function checkVirtualProductStock($logicArea, $productID, $number)
	{
	    $where = array('lid' => $logicArea,
	                   'status_id' => 2,
	                   'product_id' => $productID,
	                   );
	    $datas = $this -> getSumStock($where);
	    if (!$datas)    return false;
	    
	    $data = array_shift($datas);
	    
	    if ($data['real_in_number'] - $data['out_number'] < $number)    return false;
	    
	    return true;
	}
	
	/**
     * 生成销售出库批次数据
     *
     * @productID   int
     * @number      int
     * @return      void
     */
	public function createSaleOutStock($productID, $number, $updateStock = true)
	{
	    //if (!$this -> checkPrepareProductStock($productID, $number))    return false;
	    
	    $sql = "select t1.* from shop_stock_status as t1 
	            left join shop_product_batch as t2 on t1.batch_id = t2.batch_id
	            where t1.product_id = {$productID}";
        $condition = $this -> getSaleOutStockCondition();
        $condition['lid'] && $sql .= " and t1.lid = {$condition['lid']}";
        $condition['status_id'] && $sql .= " and t1.status_id = {$condition['status_id']}";
        $sql .=" order by t1.stock_id desc";

	    $stockData = $this -> getBatchPrior($this -> _db -> fetchAll($sql));
	    $tempNumber = $number;
	    
	    foreach ($stockData as $data) {
	        $stockNumber = $data['real_in_number'] - $data['out_number'];
	        if ($stockNumber <= 0)  continue;
	        
	        $outStock = array('stock_id' => $data['stock_id'],
	                          'lid' => $data['lid'],
	                          'batch_id' => $data['batch_id'],
	                          'status_id' => $data['status_id'],
	                         );
	        
	        if ($stockNumber >= $tempNumber) {
	            $outStock['number'] = $tempNumber;
	            $stock['number'] = $tempNumber;
	            $tempNumber = 0;
	        }
	        else {
	            $outStock['number'] = $stockNumber;
	            $stock['number'] = $stockNumber;
	            $tempNumber -= $stockNumber;
	        }
	        
	        $result[] = $outStock;
	        
	        if ($tempNumber == 0)   break;
	    }
	    
	    if ($tempNumber > 0) {
	        return false;
	    }
        
        if ($updateStock) {
            if (in_array($condition['lid'], $this -> getEntityAreaID())) {
    	        $this -> releaseSaleProductStock($productID, $number);
            }
            
    	    foreach ($result as $outStock) {
    	        $this -> addStockOutNumber($outStock['number'], array('stock_id' => $outStock['stock_id']));
    	    }
	    }
	    
	    return $result;
	}
	
	/**
     * 生成虚拟仓出库批次数据
     *
     * @productID   int
     * @number      int
     * @return      void
     */
	public function createVirtualOutStock($logicArea, $productID, $number, $billNo)
	{
	    $sql = "select t1.* from shop_stock_status as t1 
	            left join shop_product_batch as t2 on t1.batch_id = t2.batch_id
	            where t1.product_id = {$productID} and t1.lid = '{$logicArea}' and t1.status_id = 2 
	            order by t1.stock_id desc";
        
	    $stockData = $this -> getBatchPrior($this -> _db -> fetchAll($sql));
	    $tempNumber = $number;
	    foreach ($stockData as $data) {
	        $stockNumber = $data['real_in_number'] - $data['out_number'];
	        if ($stockNumber <= 0)  continue;
	        
	        $outStock = array('stock_id' => $data['stock_id'],
	                          'lid' => $data['lid'],
	                          'batch_id' => $data['batch_id'],
	                          'status_id' => $data['status_id'],
	                      );
	        
	        if ($stockNumber >= $tempNumber) {
	            $outStock['number'] = $tempNumber;
	            $stock['number'] = $tempNumber;
	            $tempNumber = 0;
	        }
	        else {
	            $outStock['number'] = $stockNumber;
	            $stock['number'] = $stockNumber;
	            $tempNumber -= $stockNumber;
	        }
	        
	        $result[] = $outStock;
	        
	        if ($tempNumber == 0)   break;
	    }
	    
	    if ($tempNumber > 0) {
	        return false;
	    }
        
	    foreach ($result as $outStock) {
	        $this -> addStockOutNumber($outStock['number'], array('stock_id' => $outStock['stock_id']));
	        $this -> addStockRealOutNumber($outStock['number'], array('stock_id' => $outStock['stock_id']), $billNo);
	        
	        //特殊仓库的处理
	        if (in_array($logicArea, $this -> getEntityAreaID())) {
	            $this -> releaseSaleProductStock($productID, $number);
	        }
	    }

	    return $result;
	}
	
	/**
     * 还原销售出库数据
     *
     * @productID   int
     * @number      int
     * @return      void
     */
	public function restoreSaleOutStock($productID, $batchID, $number, $holdStock = true)
	{
	    $condition = $this -> getSaleOutStockCondition();
	    $where = array('lid' => $condition['lid'],
	                   'status_id' => $condition['status_id'],
	                   'product_id' => $productID,
	                   'batch_id' => $batchID,
	                  );

	    $this -> reduceStockOutNumber($number, $where);
	    
	    if ($holdStock) {
	        $this -> holdSaleProductStock($productID, $number);
	    }
	}
	
	/**
     * 增加实际出库数量
     *
     * @number      int
     * @where       array
     * @return      boolean
     */
	public function addStockRealOutNumber($number, $where, $billNo = null)
	{
	    if ($where['stock_id']) {
	        $whereSQL = "stock_id = {$where['stock_id']}";
	    }
	    else {
	        $whereSQL = "lid = {$where['lid']} and product_id = {$where['product_id']} and batch_id = {$where['batch_id']} and status_id = {$where['status_id']}";
	    }
	    
	    $stock = $this -> _db -> fetchRow("select lid,product_id,batch_id,status_id,(real_in_number - real_out_number) as stock_number from shop_stock_status where {$whereSQL}");
	    $stock['type'] = 'outstock';
	    $stock['stock_number'] = $stock['stock_number'] ? $stock['stock_number'] : 0;
	    $stock['bill_no'] = $billNo;
	    $stock['number'] = $number;
	    $this -> addLog($stock);
	    
	    return $this -> updateStock(array('real_out_number' => array('op' => '+', 'number' => $number)), $where);
	}
	
	/**
     * 增加实际入库数量
     *
     * @number      int
     * @where       array
     * @return      boolean
     */
	public function addStockRealInNumber($number, $where, $billNo = null)
	{
	    if ($where['stock_id']) {
	        $whereSQL = "stock_id = {$where['stock_id']}";
	    }
	    else {
	        $whereSQL = "lid = {$where['lid']} and product_id = {$where['product_id']} and batch_id = {$where['batch_id']} and status_id = {$where['status_id']}";
	    }
	    $stockNumber = $this -> _db -> fetchOne("select (real_in_number - real_out_number) as stock_number from shop_stock_status where {$whereSQL}");
	    $row = $where;
	    $row['type'] = 'instock';
	    $row['stock_number'] = $stockNumber ? $stockNumber : 0;
	    $row['bill_no'] = $billNo;
	    $row['number'] = $number;
	    $this -> addLog($row);
	    
	    return $this -> updateStock(array('real_in_number' => array('op' => '+', 'number' => $number)), $where);
	}
	
	/**
     * 增加计划出库数量
     *
     * @number      int
     * @where       array
     * @return      boolean
     */
	public function addStockOutNumber($number, $where)
	{
	    return $this -> updateStock(array('out_number' => array('op' => '+', 'number' => $number)), $where);
	}
	
	/**
     * 增加计划入库数量
     *
     * @number      int
     * @where       array
     * @return      boolean
     */
	public function addStockInNumber($number, $where)
	{
	    return $this -> updateStock(array('in_number' => array('op' => '+', 'number' => $number)), $where);
	}
	
	/**
     * 减少实际出库数量
     *
     * @number      int
     * @where       array
     * @return      boolean
     */
	public function reduceStockRealOutNumber($number, $where)
	{
	    return $this -> updateStock(array('real_out_number' => array('op' => '-', 'number' => $number)), $where);
	}
	
	/**
     * 减少实际入库数量
     *
     * @number      int
     * @where       array
     * @return      boolean
     */
	public function reduceStockRealInNumber($number, $where)
	{
	    return $this -> updateStock(array('real_in_number' => array('op' => '-', 'number' => $number)), $where);
	}
	
	/**
     * 减少计划出库数量
     *
     * @number      int
     * @where       array
     * @return      boolean
     */
	public function reduceStockOutNumber($number, $where)
	{
	    return $this -> updateStock(array('out_number' => array('op' => '-', 'number' => $number)), $where);
	}
	
	/**
     * 减少计划入库数量
     *
     * @number      int
     * @where       array
     * @return      boolean
     */
	public function reduceStockInNumber($number, $where)
	{
	    return $this -> updateStock(array('in_number' => array('op' => '-', 'number' => $number)), $where);
	}
	
	/**
     * 重新生成所有占用库存(盘点用)
     *
     * @where       array
     * @update      boolean
     * @return      array
     */
	public function createAllHoldStock($where = null, $update = true)
	{
	    !is_array($where['lid']) && $where['lid'] && $where['lid'] = array($where['lid']);
	    !is_array($where['product_id']) && $where['product_id'] && $where['product_id'] = array($where['product_id']);
	    !is_array($where['batch_id']) && $where['batch_id'] && $where['batch_id'] = array($where['batch_id']);
	    !is_array($where['status_id']) && $where['status_id'] && $where['status_id'] = array($where['status_id']);
	    
	    if (is_array($where['lid'])) {
	        $whereSQL1 .= " and t2.lid in (".implode(',', $where['lid']).")";
	        $whereSQL2 .= " and t2.lid in (".implode(',', $where['lid']).")";
	        $whereSQL3 .= " and t2.from_lid in (".implode(',', $where['lid']).")";
	        $whereSQL4 .= " and t3.lid in (".implode(',', $where['lid']).")";
	        $whereSQL6 .= " and area_id in (".implode(',', $where['lid']).")";
	        $whereSQL7 .= " and lid in (".implode(',', $where['lid']).")";
	        $whereSQL8 .= " and t2.lid in (".implode(',', $where['lid']).")";
	        $whereSQL9 .= " and t2.to_lid in (".implode(',', $where['lid']).")";
	    }
	    if (is_array($where['product_id'])) {
	        $whereSQL1 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL2 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL3 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL4 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL5 .= " and t3.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL6 .= " and product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL7 .= " and product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL8 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL9 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	    }
	    if (is_array($where['batch_id'])) {
	        $whereSQL1 .= " and t1.batch_id in (".implode(',', $where['batch_id']).")";
	        $whereSQL2 .= " and t1.batch_id in (".implode(',', $where['batch_id']).")";
	        $whereSQL3 .= " and t1.batch_id in (".implode(',', $where['batch_id']).")";
	        $whereSQL7 .= " and batch_id in (".implode(',', $where['batch_id']).")";
	        $whereSQL8 .= " and batch_id in (".implode(',', $where['batch_id']).")";
	        $whereSQL9 .= " and t1.batch_id in (".implode(',', $where['batch_id']).")";
	    }
	    if (is_array($where['status_id'])) {
	        $whereSQL1 .= " and t1.status_id in (".implode(',', $where['status_id']).")";
	        $whereSQL2 .= " and t1.ostatus in (".implode(',', $where['status_id']).")";
	        $whereSQL3 .= " and t1.status_id in (".implode(',', $where['status_id']).")";
	        $whereSQL7 .= " and status_id in (".implode(',', $where['status_id']).")";
	        $whereSQL8 .= " and t1.nstatus in (".implode(',', $where['status_id']).")";
	        $whereSQL9 .= " and t1.status_id in (".implode(',', $where['status_id']).")";
	    }
        
	    //正常出库单
	    $sql = "select t1.*,t2.lid,t2.bill_no,t2.bill_type from shop_outstock_detail as t1 
	            inner join shop_outstock as t2 on t1.outstock_id = t2.outstock_id
	            where t2.bill_type <> 1 and t2.bill_status in (3,4) {$whereSQL1}";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        foreach ($datas as $data) {
	            if ($update) {
	                $outStockData1[$data['lid']][$data['product_id']][$data['batch_id']][$data['status_id']] += $data['number'];
	            }
	            else {
	                $outStockDetail[$data['lid']][$data['product_id']][$data['batch_id']][$data['status_id']][] = array('bill_no' => $data['bill_no'],
	                                                                                                                    'bill_type' => $data['bill_type'],
	                                                                                                                    'number' => $data['number'],
	                                                                                                                   );
	            }
	        }
	    }
        
	    //正常入库单
	    $sql = "select t1.*,t2.lid,t2.bill_no,t2.bill_type from shop_instock_plan as t1 
	            inner join shop_instock as t2 on t1.instock_id = t2.instock_id
	            where t2.bill_status in (3,6) {$whereSQL1}";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        foreach ($datas as $data) {
	            if ($update) {
	                $inStockData[$data['lid']][$data['product_id']][$data['batch_id']][$data['status_id']] += $data['plan_number'];
	            }
	            else {
	                $inStockDetail[$data['lid']][$data['product_id']][$data['batch_id']][$data['status_id']][] = array('bill_no' => $data['bill_no'],
	                                                                                                                   'bill_type' => $data['bill_type'],
	                                                                                                                   'number' => $data['plan_number'],
	                                                                                                                  );
	            }
	        }
	    }
        
	    //商品状态更改
	    $sql = "select t1.*,t2.lid,t2.bill_no from shop_status_detail as t1 
	            inner join shop_status as t2 on t1.sid = t2.sid
	            where t2.bill_status = 0 {$whereSQL2}";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        foreach ($datas as $data) {
	            if ($update) {
	                $outStockData1[$data['lid']][$data['product_id']][$data['batch_id']][$data['ostatus']] += $data['number'];
	            }
	            else {
	                $outStockDetail[$data['lid']][$data['product_id']][$data['batch_id']][$data['ostatus']][] = array('bill_no' => $data['bill_no'],
	                                                                                                                    'bill_type' => 'outStatus',
	                                                                                                                    'number' => $data['number'],
	                                                                                                                   );
	            }
	        }
	    }
	    $sql = "select t1.*,t2.lid,t2.bill_no from shop_status_detail as t1 
	            inner join shop_status as t2 on t1.sid = t2.sid
	            where t2.bill_status = 0 {$whereSQL8}";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        foreach ($datas as $data) {
	            if ($update) {
	                $inStockData[$data['lid']][$data['product_id']][$data['batch_id']][$data['nstatus']] += $data['number'];
	            }
	            else {
	                $inStockDetail[$data['lid']][$data['product_id']][$data['batch_id']][$data['nstatus']][] = array('bill_no' => $data['bill_no'],
	                                                                                                                 'bill_type' => 'inStatus',
	                                                                                                                 'number' => $data['number'],
	                                                                                                                );
	            }
	        }
	    }
        
	    //调拨单
	    $sql = "select t1.*,t2.from_lid,t2.bill_no from shop_allocation_detail as t1 
	            inner join shop_allocation as t2 on t1.aid = t2.aid
	            where t2.bill_status in (3,4) {$whereSQL3}";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        foreach ($datas as $data) {
	            if ($update) {
	                $outStockData1[$data['from_lid']][$data['product_id']][$data['batch_id']][$data['status_id']] += $data['number'];
	            }
	            else {
	                $outStockDetail[$data['from_lid']][$data['product_id']][$data['batch_id']][$data['status_id']][] = array('bill_no' => $data['bill_no'],
	                                                                                                                         'bill_type' => 'outAllocation',
	                                                                                                                         'number' => $data['number'],
	                                                                                                                        );
	            }
	        }
	    }
	    $sql = "select t1.*,t2.to_lid,t2.bill_no from shop_allocation_detail as t1 
	            inner join shop_allocation as t2 on t1.aid = t2.aid
	            where t2.bill_status in (3,6) {$whereSQL9}";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        foreach ($datas as $data) {
	            if ($update) {
	                $inStockData[$data['to_lid']][$data['product_id']][$data['batch_id']][$data['status_id']] += $data['number'];
	            }
	            else {
	                $inStockDetail[$data['to_lid']][$data['product_id']][$data['batch_id']][$data['status_id']][] = array('bill_no' => $data['bill_no'],
	                                                                                                                      'bill_type' => 'inAllocation',
	                                                                                                                      'number' => $data['number'],
	                                                                                                                     );
	            }
	        }
	    }
	    
	    if (!$where['status_id'] || in_array(2, $where['status_id'])) {
	        $condition = $this -> getSaleOutStockCondition();
	        
	        //官网销售出库单
    	    $sql = "select t1.product_id,t1.number,t2.batch_sn,t2.status_logistic,t3.lid from shop_order_batch_goods as t1
    	            inner join shop_order_batch as t2 on t1.order_batch_id = t2.order_batch_id
    	            inner join shop_order as t3 on t2.order_id = t3.order_id
    	            where t1.product_id > 0 and t1.number > 0 and t2.status = 0 and t2.status_logistic in (0,1,2) {$whereSQL4}";
    	    $datas = $this -> _db -> fetchAll($sql);
    	    if ($datas) {
    	        foreach ($datas as $data) {
    	            if ($data['status_logistic'] == 0 || $data['status_logistic'] == 1) {
    	                if ($update) {
    	                    $outStockData2[$data['lid']][$data['product_id']] += $data['number'];
    	                }
    	                else {
    	                    $outStockDetail[$data['lid']][$data['product_id']][0][$condition['status_id']][] = array('bill_no' => $data['batch_sn'],
	                                                                                                                 'bill_type' => 1,
	                                                                                                                 'number' => $data['number'],
	                                                                                                                );
    	                }
    	            }
    	            else {
    	                $orderSNMap[$data['batch_sn']] = 1;
    	            }
    	        }
    	    }
    	    $transportAPI = new Admin_Models_DB_Transport();
    	    $prepareDatas = $transportAPI -> getPrepareOrderList("t1.status = 0 and t1.status_logistic = 2");
    	    if ($prepareDatas['data']) {
    	        foreach ($prepareDatas['data'] as $prepareData) {
    	            foreach ($datas as $data) {
    	                if ($data['batch_sn'] == $prepareData['batch_sn']) {
    	                    if ($update) {
    	                        $outStockData2[$data['lid']][$data['product_id']] += $data['number'];
    	                    }
    	                    else {
    	                        $outStockDetail[$data['lid']][$data['product_id']][0][$condition['status_id']][] = array('bill_no' => $data['batch_sn'],
	                                                                                                                     'bill_type' => 1,
	                                                                                                                     'number' => $data['number'],
	                                                                                                                    );
    	                    }
    	                    unset($orderSNMap[$data['batch_sn']]);
    	                    continue;
    	                }
    	            }
    	        }
    	    }

    	    if ($orderSNMap) {
    	        $orderSNArray = array();
    	        foreach ($orderSNMap as $orderSN => $value) {
    	            $orderSNArray[] = "'{$orderSN}'";
    	        }
    	        $sql = "select t1.*,t2.lid,t2.bill_no,t2.bill_type from shop_outstock_detail as t1 
        	            inner join shop_outstock as t2 on t1.outstock_id = t2.outstock_id
        	            where t2.is_cancel <> 2 and bill_no in (".implode(',', $orderSNArray).") {$whereSQL1}";
        	    $datas = $this -> _db -> fetchAll($sql);
    	        if ($datas) {
        	        foreach ($datas as $data) {
        	            if ($update) {
        	                $outStockData1[$data['lid']][$data['product_id']][$data['batch_id']][$data['status_id']] += $data['number'];
        	            }
        	            else {
        	                $outStockDetail[$data['lid']][$data['product_id']][$data['batch_id']][$data['status_id']][] = array('bill_no' => $data['bill_no'],
	                                                                                                                            'bill_type' => $data['bill_type'],
	                                                                                                                            'number' => $data['number'],
	                                                                                                                           );
        	            }
        	        }
        	    }
    	    }
	    }

	    if (!$update)   return array('outStockDetail' => $outStockDetail, 'inStockDetail' => $inStockDetail);
	    
	    //更新销售占用库存
	    if (!$where['status_id'] || in_array(2, $where['status_id'])) {
	        $this -> _db -> delete('shop_hold_stock', "1 {$whereSQL6}");
	        
	        if ($outStockData2) {
    	        foreach ($outStockData2 as $lid => $tempData) {
    	            foreach ($tempData as $productID => $number) {
    	                $row = array('area_id' => $lid,
    	                             'product_id' => $productID,
    	                             'number' => $number,
    	                            );
    	                $this -> _db -> insert('shop_hold_stock', $row);
    	            }
    	        }
	        }
	    }
	    
	    //更新占用库存
	    $this -> _db -> execute("update shop_stock_status set out_number = real_out_number where 1 {$whereSQL7}");
	    if ($outStockData1) {
	        foreach ($outStockData1 as $lid => $data1) {
	            foreach ($data1 as $productID => $data2) {
	                foreach ($data2 as $batchID => $data3) {
	                    foreach ($data3 as $statusID => $number) {
	                        $where = array('lid' => $lid,
	                                       'product_id' => $productID,
	                                       'batch_id' => $batchID,
	                                       'status_id' => $statusID,
	                                      );
	                        $this -> addStockOutNumber($number, $where);
	                    }
	                }
	            }
	        }
	    }
	    
	    //更新计划库存
	    $this -> _db -> execute("update shop_stock_status set in_number = real_in_number where 1 {$whereSQL7}");
	    if ($inStockData) {
	        foreach ($inStockData as $lid => $data1) {
	            foreach ($data1 as $productID => $data2) {
	                foreach ($data2 as $batchID => $data3) {
	                    foreach ($data3 as $statusID => $number) {
	                        $where = array('lid' => $lid,
	                                       'product_id' => $productID,
	                                       'batch_id' => $batchID,
	                                       'status_id' => $statusID,
	                                      );
	                        $this -> addStockInNumber($number, $where);
	                    }
	                }
	            }
	        }
	    }
	}
	
	/**
     * 合并拣配区和存储区(升级用)
     *
     * @return      array
     */
	public function mergeArea()
	{
        $this -> _db -> delete('shop_stock_status', 'in_number = 0 and real_in_number = 0 and out_number = 0 and real_out_number = 0');
        
        $datas = $this -> _db -> fetchAll('select product_id,status_id,sum(in_number) as in_number,sum(out_number) as out_number,sum(real_in_number) as real_in_number,sum(real_out_number) as real_out_number from shop_stock_status where lid in (1,2,3) group by product_id,status_id');
        $this -> _db -> delete('shop_stock_status', 'lid in (1,2,3)');
	    foreach ($datas as $data) {
	        $row = array('lid' => 1,
	                     'product_id' => $data['product_id'],
	                     'status_id' => $data['status_id'],
	                     'in_number' => $data['in_number'],
	                     'real_in_number' => $data['real_in_number'],
	                     'out_number' => $data['out_number'],
	                     'real_out_number' => $data['real_out_number'],
	                     );
	        
	        $this -> _db -> insert('shop_stock_status', $row);
	    }
	    
	    $this -> _db -> update('shop_instock', array('lid' => 1), 'lid in (2,3)');
	    $this -> _db -> update('shop_outstock', array('lid' => 1), 'lid in (2,3)');
    }
    
    /**
     * 初始化库存(盘点用)
     *
     * @data        array
     * @return      void
     */
	public function initStock($data)
	{
        $whereSQL = "lid = {$data['lid']} and product_id = {$data['product_id']} and batch_id = {$data['batch_id']} and status_id = {$data['status_id']}";
	    $stockNumber = $this -> _db -> fetchOne("select (real_in_number - real_out_number) as stock_number from shop_stock_status where {$whereSQL}");
	    $stockNumber = $stockNumber ? $stockNumber : 0;
	    if ($stockNumber == $number)    return false;
	    
	    $row = $data;
	    $row['type'] = $stockNumber > $number ? 'outstock' : 'instock';
	    $row['stock_number'] = abs($stockNumber - $number);
	    $this -> addLog($row);
        
        $number = $data['number'];
        unset($data['number']);
	    $this -> updateStock(array('in_number' => array('op' => '=', 'number' => $number)), $data);
	    $this -> updateStock(array('real_in_number' => array('op' => '=', 'number' => $number)), $data);
	    $this -> updateStock(array('out_number' => array('op' => '=', 'number' => 0)), $data);
	    $this -> updateStock(array('real_out_number' => array('op' => '=', 'number' => 0)), $data);
	}
	
	/**
     * 调整库存(盘点用)
     *
     * @data        array
     * @return      void
     */
	public function adjustStock($data)
	{
        $number = $data['number'];
        unset($data['number']);
        
        $sql = "select (real_in_number - real_out_number) as number from shop_stock_status 
                where lid = {$data['lid']} and product_id = {$data['product_id']} and 
                      batch_id = {$data['batch_id']} and status_id = {$data['status_id']}";
        $stock = $this -> _db -> fetchRow($sql);
        if ($number == $stock['number'])    return true;
        
        $adjustNumber = $number - $stock['number'];
        if ($adjustNumber > 0) {
            $result['type'] = 'instock';
        }
        else {
            $result['type'] = 'outstock';
        }
        $result['number'] = abs($adjustNumber);
        
        return $result;
	}
    
    /**
     * 生成库存(盘点用)
     *
     * @data        array
     * @return      void
     */
	public function createStock($data, $updateHoldStock = true)
	{
	    if (!$data['lid'] || !$data['product_id'] || !$data['status_id']) {
	        return false;
	    }
	    if (!$data['batch_id']) $data['batch_id'] = 0;
        
        $this -> initStock($data);
	    
	    if ($updateHoldStock) {
	        $this -> createAllHoldStock($data);
	    }
	    
	    return true;
	}
	
	/**
     * 历史库存
     *
     * @where       array
     * @return      array
     */
	public function historyStock($where = null)
	{
	    $productWhere = 1;
	    if ($where['p_status'] !== null && $where['p_status'] !== '') {
	        $productWhere .= " and p_status = '{$where['p_status']}'";
	    }
	    $where['product_sn'] && $productWhere .= " and product_sn = '{$where['product_sn']}'";
	    $where['product_name'] && $productWhere .= " and product_name like '%{$where['product_name']}%'";
	    $result = $this -> _db -> fetchAll("select product_id,product_sn,product_name,goods_style,cost,round(cost / (1 + invoice_tax_rate / 100), 3) as cost_tax,suggest_price from shop_product where {$productWhere} order by product_sn");
	    if (!$result)   return false;
	        
	    foreach ($result as $product) {
	        $productIDArray[] = $product['product_id'];
	    }
	    
	    $where['product_id'] = $productIDArray;
	    $currentStockData = $this -> getSumStock($where);
        if ($currentStockData) {
            foreach ($currentStockData as $data) {
                $stockData[$data['product_id']] = $data['real_in_number'] - $data['real_out_number'];
            }
        }
        
	    $where['logic_area'] && $where['lid'] = $where['logic_area'];
	    !is_array($where['lid']) && $where['lid'] && $where['lid'] = array($where['lid']);
	    !is_array($where['product_id']) && $where['product_id'] && $where['product_id'] = array($where['product_id']);
	    !is_array($where['status_id']) && $where['status_id'] && $where['status_id'] = array($where['status_id']);
	    
	    if ($where['lid']) {
	        $whereSQL1 .= " and t2.lid in (".implode(',', $where['lid']).")";
	        $whereSQL2 .= " and t2.lid in (".implode(',', $where['lid']).")";
	        $whereSQL3 .= " and t2.lid in (".implode(',', $where['lid']).")";
	        $whereSQL4 .= " and t2.from_lid in (".implode(',', $where['lid']).")";
	        $whereSQL5 .= " and t2.to_lid in (".implode(',', $where['lid']).")";
	    }
	    if ($where['status_id']) {
	        $whereSQL1 .= " and t1.status_id in (".implode(',', $where['status_id']).")";
	        $whereSQL2 .= " and t1.ostatus in (".implode(',', $where['status_id']).")";
	        $whereSQL3 .= " and t1.nstatus in (".implode(',', $where['status_id']).")";
	        $whereSQL4 .= " and t1.status_id in (".implode(',', $where['status_id']).")";
	        $whereSQL5 .= " and t1.status_id in (".implode(',', $where['status_id']).")";
	    }
	    if ($where['product_id']) {
	        $whereSQL1 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL2 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL3 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL4 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	        $whereSQL5 .= " and t1.product_id in (".implode(',', $where['product_id']).")";
	    }
	    
	    $fromdate = $where['fromdate'] ? strtotime($where['fromdate'].' 00:00:00') : 0;
	    $fromdate > time() && $fromdate = time();
	    $dateSQL[1]['sql1'] = " and t2.finish_time >= {$fromdate}";
	    $dateSQL[1]['sql2'] = " and t2.finish_time >= {$fromdate}";
	    $dateSQL[1]['sql3'] = " and t2.finish_time >= {$fromdate}";
	    $dateSQL[1]['sql4'] = " and t2.send_finish_time >= {$fromdate}";
	    $dateSQL[1]['sql5'] = " and t2.receive_finish_time >= {$fromdate}";
	    
	    $todate = $where['todate'] ? strtotime($where['todate'].' 23:59:59') : time();
	    $todate > time() && $todate = time();
	    $dateSQL[2]['sql1'] = $dateSQL[1]['sql2']." and t2.finish_time <= {$todate}";
	    $dateSQL[2]['sql2'] = $dateSQL[1]['sql2']." and t2.finish_time <= {$todate}";
	    $dateSQL[2]['sql3'] = $dateSQL[1]['sql3']." and t2.finish_time <= {$todate}";
	    $dateSQL[2]['sql4'] = $dateSQL[1]['sql4']." and t2.send_finish_time <= {$todate}";
	    $dateSQL[2]['sql5'] = $dateSQL[1]['sql5']." and t2.receive_finish_time <= {$todate}";
        
	    for ($i = 1; $i <= 2; $i++) {
    	    //出库单
    	    $sql = "select t1.product_id,sum(t1.number) as number from shop_outstock_detail as t1 
    	            inner join shop_outstock as t2 on t1.outstock_id = t2.outstock_id
    	            where t2.bill_status = 5 {$whereSQL1}{$dateSQL[$i]['sql1']}
    	            group by t1.product_id";
    	    $datas = $this -> _db -> fetchAll($sql);
    	    if ($datas) {
    	        $tempVar = 'outStockData'.$i;
    	        foreach ($datas as $data) {
    	            ${$tempVar}[$data['product_id']] = $data['number'];
    	        }
    	    }
            
    	    //入库单
    	    $sql = "select t1.product_id,sum(t1.number) as number from shop_instock_detail as t1 
    	            inner join shop_instock as t2 on t1.instock_id = t2.instock_id
    	            where t2.bill_status = 7 {$whereSQL1}{$dateSQL[$i]['sql1']}
    	            group by t1.product_id";
    	    $datas = $this -> _db -> fetchAll($sql);
    	    if ($datas) {
    	        $tempVar = 'inStockData'.$i;
    	        foreach ($datas as $data) {
    	            ${$tempVar}[$data['product_id']] = $data['number'];
    	        }
    	    }
    	    
    	    //商品状态更改
    	    $sql = "select t1.product_id,sum(t1.number) as number from shop_status_detail as t1 
    	            inner join shop_status as t2 on t1.sid = t2.sid
    	            where t2.bill_status = 1 {$whereSQL2}{$dateSQL[$i]['sql2']}
    	            group by t1.product_id";
    	    $datas = $this -> _db -> fetchAll($sql);
    	    if ($datas) {
    	        $tempVar = 'outStatusData'.$i;
    	        foreach ($datas as $data) {
    	            ${$tempVar}[$data['product_id']] = $data['number'];
    	        }
    	    }
    	    $sql = "select t1.product_id,sum(t1.number) as number from shop_status_detail as t1 
    	            inner join shop_status as t2 on t1.sid = t2.sid
    	            where t2.bill_status = 1 {$whereSQL3}{$dateSQL[$i]['sql3']}
    	            group by t1.product_id";
    	    $datas = $this -> _db -> fetchAll($sql);
    	    if ($datas) {
    	        $tempVar = 'inStatusData'.$i;
    	        foreach ($datas as $data) {
    	            ${$tempVar}[$data['product_id']] = $data['number'];
    	        }
    	    }
            
    	    //调拨单
    	    $sql = "select t1.product_id,sum(t1.number) as number from shop_allocation_detail as t1 
    	            inner join shop_allocation as t2 on t1.aid = t2.aid
    	            where t2.bill_status in (5,6,7) {$whereSQL4}{$dateSQL[$i]['sql4']}
    	            group by t1.product_id";
    	    $datas = $this -> _db -> fetchAll($sql);
    	    if ($datas) {
    	        $tempVar = 'outAllocationData'.$i;
    	        foreach ($datas as $data) {
    	            ${$tempVar}[$data['product_id']] = $data['number'];
    	        }
    	    }
    	    $sql = "select t1.product_id,sum(t1.number) as number from shop_allocation_detail as t1 
    	            inner join shop_allocation as t2 on t1.aid = t2.aid
    	            where t2.bill_status = 7 {$whereSQL5}{$dateSQL[$i]['sql5']}
    	            group by t1.product_id";
    	    $datas = $this -> _db -> fetchAll($sql);
    	    if ($datas) {
    	        $tempVar = 'inAllocationData'.$i;
    	        foreach ($datas as $data) {
    	            ${$tempVar}[$data['product_id']] = $data['number'];
    	        }
    	    }
    	    
	    }
        
	    foreach ($result as $index => $product) {
	        $productID = $product['product_id'];
	        
	        $result[$index]['start_stock_number'] = $stockData[$productID] + $outStockData1[$productID] + $outStatusData1[$productID] + $outAllocationData1[$productID] - $inStockData1[$productID] - $inStatusData1[$productID] - $inAllocationData1[$productID];
	        $result[$index]['end_stock_number'] = $result[$index]['start_stock_number'] + $inStockData2[$productID] + $inStatusData2[$productID] + $inAllocationData2[$productID] - $outStockData2[$productID] - $outStatusData2[$productID] - $outAllocationData2[$productID];
	        $result[$index]['out_stock_number'] = $outStockData2[$productID];
	        $result[$index]['out_status_number'] = $outStatusData2[$productID];
	        $result[$index]['out_allocation_number'] = $outAllocationData2[$productID];
	        $result[$index]['in_stock_number'] = $inStockData2[$productID];
	        $result[$index]['in_status_number'] = $inStatusData2[$productID];
	        $result[$index]['in_allocation_number'] = $inAllocationData2[$productID];
	    }
        
	    return $result;
	}
	
	/**
     * 明细历史库存
     *
     * @where   array
     * @return  array
     */
	public function detailStock($where)
	{
	    $where['logic_area'] && $where['lid'] = $where['logic_area'];
	    
	    if (!$where['batch_no'] && !$where['product_sn']) {
	        return false;
	    }
	    
	    if ($where['batch_no']) {
	        $batch = $this -> getProductBatch(array('batch_no' => $where['batch_no']));
	        if (!$batch)  return false;
	        
	        $where['batch_id'] = $batch['batch_id'];
	        $where['product_id'] = $batch['product_id'];
	    }
	    if ($where['product_sn'] || $where['product_id']) {
	        $whereSQL = 1;
	        if ($where['product_sn']) {
	            $whereSQL .= " and t1.product_sn = '{$where['product_sn']}'";
	        }
	        if ($where['product_id']) {
	            $whereSQL .= " and t1.product_id = '{$where['product_id']}'";
	        }
	        $product = array_shift($this -> getProductHoldStock($whereSQL));
	        if (!$product)  return false;
	        
	        $where['product_id'] = $product['product_id'];
	    }
	    
	    $stock = array_shift($this -> getSumStock($where, 'product_id'));
	    $currentStockNumber = $stock['real_in_number'] - $stock['real_out_number'];
        
	    if ($where['lid']) {
	        $whereSQL1 .= " and t2.lid = {$where['lid']}";
	        $whereSQL2 .= " and t2.lid = {$where['lid']}";
	        $whereSQL3 .= " and t2.lid = {$where['lid']}";
	        $whereSQL4 .= " and t2.from_lid = {$where['lid']}";
	        $whereSQL5 .= " and t2.to_lid = {$where['lid']}";
	    }
	    if ($where['product_id']) {
	        $whereSQL1 .= " and t1.product_id = {$where['product_id']}";
	        $whereSQL2 .= " and t1.product_id = {$where['product_id']}";
	        $whereSQL3 .= " and t1.product_id = {$where['product_id']}";
	        $whereSQL4 .= " and t1.product_id = {$where['product_id']}";
	        $whereSQL5 .= " and t1.product_id = {$where['product_id']}";
	    }
	    if ($where['batch_id']) {
	        $whereSQL1 .= " and t1.batch_id = {$where['batch_id']}";
	        $whereSQL2 .= " and t1.batch_id = {$where['batch_id']}";
	        $whereSQL3 .= " and t1.batch_id = {$where['batch_id']}";
	        $whereSQL4 .= " and t1.batch_id = {$where['batch_id']}";
	        $whereSQL5 .= " and t1.batch_id = {$where['batch_id']}";
	    }
	    if ($where['status_id']) {
	        $whereSQL1 .= " and t1.status_id = {$where['status_id']}";
	        $whereSQL2 .= " and t1.ostatus = {$where['status_id']}";
	        $whereSQL3 .= " and t1.nstatus = {$where['status_id']}";
	        $whereSQL4 .= " and t1.status_id = {$where['status_id']}";
	        $whereSQL5 .= " and t1.status_id = {$where['status_id']}";
	    }
	    
	    $fromdate = $where['fromdate'] ? strtotime($where['fromdate'].' 00:00:00') : 0;
	    $fromdate > time() && $fromdate = time();
	    $whereSQL1 .= " and t2.finish_time >= {$fromdate}";
	    $whereSQL2 .= " and t2.finish_time >= {$fromdate}";
	    $whereSQL3 .= " and t2.finish_time >= {$fromdate}";
	    $whereSQL4 .= " and t2.send_finish_time >= {$fromdate}";
	    $whereSQL5 .= " and t2.receive_finish_time >= {$fromdate}";
	    
	    //出库单
    	$sql1 = "select 'outstock' as type,t1.product_id,t1.status_id,t1.number,t1.cost as price,t2.bill_no,t2.bill_type,t2.finish_time,t2.admin_name,t3.batch_no from shop_outstock_detail as t1 
    	        inner join shop_outstock as t2 on t1.outstock_id = t2.outstock_id
    	        left join shop_product_batch as t3 on t1.batch_id = t3.batch_id
    	        where t2.bill_status = 5 {$whereSQL1}";
        
	    //入库单
    	$sql2 = "select 'instock' as type,t1.product_id,t1.status_id,t1.number,t1.shop_price as price,t2.bill_no,t2.bill_type,t2.finish_time,t2.admin_name,t3.batch_no from shop_instock_detail as t1 
    	        inner join shop_instock as t2 on t1.instock_id = t2.instock_id
    	        left join shop_product_batch as t3 on t1.batch_id = t3.batch_id
    	        where t2.bill_status = 7 {$whereSQL1}";
        
        //商品状态更改
        $sql3 = "select 'outstatus' as type,t1.product_id,t1.ostatus as status_id,t1.number,'N/A' as price,t2.bill_no,0 as bill_type,t2.finish_time,t2.admin_name,t3.batch_no from shop_status_detail as t1 
    	        inner join shop_status as t2 on t1.sid = t2.sid
    	        left join shop_product_batch as t3 on t1.batch_id = t3.batch_id
    	        where t2.bill_status = 1 {$whereSQL2}";

    	$sql4 = "select 'instatus' as type,t1.product_id,t1.nstatus as status_id,t1.number,'N/A' as price,t2.bill_no,0 as bill_type,t2.finish_time,t2.admin_name,t3.batch_no from shop_status_detail as t1 
    	        inner join shop_status as t2 on t1.sid = t2.sid
    	        left join shop_product_batch as t3 on t1.batch_id = t3.batch_id
    	        where t2.bill_status = 1 {$whereSQL3}";
    	
    	//调拨单
        $sql5 = "select 'outallocation' as type,t1.product_id,t1.status_id,t1.number,'N/A' as price,t2.bill_no,0 as bill_type,t2.send_finish_time as finish_time,t2.admin_name,t3.batch_no from shop_allocation_detail as t1 
    	        inner join shop_allocation as t2 on t1.aid = t2.aid
    	        left join shop_product_batch as t3 on t1.batch_id = t3.batch_id
    	        where t2.bill_status in (5,6,7) {$whereSQL4}";
    	        
    	$sql6 = "select 'inallocation' as type,t1.product_id,t1.status_id,t1.number,'N/A' as price,t2.bill_no,0 as bill_type,t2.receive_finish_time as finish_time,t2.admin_name,t3.batch_no from shop_allocation_detail as t1 
    	        inner join shop_allocation as t2 on t1.aid = t2.aid
    	        left join shop_product_batch as t3 on t1.batch_id = t3.batch_id
    	        where t2.bill_status in (7) {$whereSQL5}";
        
        $sql = "select * from ($sql1 union all $sql2 union all $sql3 union all $sql4 union all $sql5 union all $sql6) a order by finish_time desc";
        $datas = $this -> _db -> fetchAll($sql);
        if (!$datas)    return false;
        
        foreach ($datas as $index => $data) {
            if (substr($data['type'], 0, 2) == 'in') {
                $currentStockNumber -= $data['number'];
            }
            else {
                $currentStockNumber += $data['number'];
            }
            $datas[$index]['stock'] = $currentStockNumber;
            
            $datas[$index]['product_sn'] = $product['product_sn'];
            $datas[$index]['product_name'] = $product['product_name'];
            $datas[$index]['goods_style'] = $product['goods_style'];
        }
	    
	    return array_reverse($datas);
	}
	
	/**
     * 获得产品出入库明细(用于计算移动成本)
     *
     * @productID   int
     * @return      array
     */
	public function getOutInStockDetail($productID)
	{
    	$sql1 = "select t2.outstock_id as id,'outstock' as type,t2.bill_no,'' as item_no,t1.product_id,t1.number,t1.cost as price,t2.bill_type,t2.finish_time from shop_outstock_detail as t1 
    	        inner join shop_outstock as t2 on t1.outstock_id = t2.outstock_id
    	        where t2.bill_status = 5 and t1.product_id = '{$productID}' and t1.number > 0";
        $sql2 = "select t2.instock_id as id,'instock' as type,t2.bill_no,t2.item_no,t1.product_id,sum(t1.number) as number,t1.shop_price as price,t2.bill_type,t2.finish_time from shop_instock_detail as t1 
    	        inner join shop_instock as t2 on t1.instock_id = t2.instock_id
    	        where t2.bill_status = 7 and t1.product_id = '{$productID}' and t1.number > 0 group by t2.instock_id";
        $sql = "select * from ($sql1 union all $sql2) a order by finish_time,type";

        return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 获得日志
     *
     * @where       array
     * @return      array
     */
	public function getStockLog($where)
	{
	    if (!$where['status_id'])   return false;
	    
	    $where['logic_area'] && $where['lid'] = $where['logic_area'];
	    
	    if (!$where['batch_no'] && !$where['product_sn']) {
	        return false;
	    }
	    
	    $whereSQL = 1;
	    if ($where['batch_no']) {
	        $batch = $this -> getProductBatch(array('batch_no' => $where['batch_no']));
	        if (!$batch)  return false;
	        
	        $whereSQL .= " and batch_id = {$batch['batch_id']}";
	    }
	    if ($where['product_sn']) {
	        $product = array_shift($this -> getProductHoldStock("t1.product_sn = '{$where['product_sn']}'"));
	        if (!$product)  return false;
	        
	        $whereSQL .= " and product_id = {$product['product_id']}";
	    }
	    $where['lid'] && $whereSQL .= " and lid = {$where['lid']}";
	    $whereSQL .= " and status_id = {$where['status_id']}";
	    $where['fromdate'] = $where['fromdate'] ? strtotime($where['fromdate'].' 00:00:00') : 0;
	    $where['todate'] = $where['todate'] ? strtotime($where['todate'].' 23:59:59') : time();
	    $whereSQL .= " and add_time >= {$where['fromdate']} and add_time < {$where['todate']}";
	    
	    $sql = "select * from shop_stock_log where {$whereSQL} order by id desc";
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 获得发生过出入库的产品
     *
     * @where       array
     * @return      array
     */
	public function getActiveProductID($where = null)
	{
	    $where['logic_area'] && $where['lid'] = $where['logic_area'];
	    
	    $whereSQL = 1;
	    $where['lid'] && $whereSQL .= " and lid = '{$where['lid']}'";
	    if ($where['p_status'] !== null && $where['p_status'] !== '') {
	        $whereSQL .= " and p_status = '{$where['p_status']}'";
	    }
	    
	    $datas = $this -> _db -> fetchAll("select t1.product_id from shop_stock_status as t1 left join shop_product as t2 on t1.product_id = t2.product_id where {$whereSQL} group by t1.product_id");
	   
	    if (!$datas)    return false;
	    
	    foreach ($datas as $data) {
	        $result[] = $data['product_id'];
	    }
	    
	    return $result;
	}
	
	/**
     * 获得库区
     *
     * @where       array
     * @return      array
     */
	public function getDistrict($where)
	{
	    if (is_array($where)) {
    	    $whereSQL = 1;
    	    $where['district_id'] && $whereSQL .= " and district_id = '{$where['district_id']}'";
    	    $where['area'] && $whereSQL .= " and area = '{$where['area']}'";
    	    if ($where['status'] !== null && $where['status'] !== '') {
    	        $whereSQL .= " and status = '{$where['status']}'";
    	    }
	    }
	    else {
	        $whereSQL = $where;
	    }
	    return $this -> _db -> fetchAll("select * from shop_stock_district where {$whereSQL}");
	}
	
	/**
     * 添加或修改库区
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function editDistrict($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
		if ($id) {
		    $temp = $this -> getDistrict("district_id <> {$id} and district_name = '{$data['district_name']}' and area = '{$data['lid']}'");
		}
		else {
		    $temp = $this -> getDistrict("district_name = '{$data['district_name']}' and area = '{$data['lid']}'");
		}
		if ($temp) {
		    $this -> error = '库区名称重复';
			return false;
		}
		
		if ($id) {
		    $temp = $this -> getDistrict("district_id <> {$id} and district_no = '{$data['district_no']}' and area = '{$data['lid']}'");
		}
		else {
		    $temp = $this -> getDistrict("district_no = '{$data['district_no']}' and area = '{$data['lid']}'");
		}
		if ($temp) {
		    $this -> error = '库区编号重复';
			return false;
		}
		
		$row = array('district_name' => $data['district_name'],
		             'district_no' => $data['district_no'],
		             'area' => $data['area'],
		             'memo' => $data['memo'],
		             'status' => $data['status'],
		            );
		
		if ($id === null) {
		    $row['add_time'] = time();
		    $this -> _db -> insert('shop_stock_district', $row);
		} else {
		    $this -> _db -> update('shop_stock_district', $row, "district_id = {$id}");
		}
		
		return true;
	}
	
	/**
     * 获得库位
     *
     * @where       array
     * @return      array
     */
	public function getPosition($where, $page = null, $pageSize = null)
	{
	    if ($page) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
	    if (is_array($where)) {
    	    $whereSQL = 1;
    	    $where['position_id'] && $whereSQL .= " and t1.position_id = '{$where['position_id']}'";
    	    $where['district_id'] && $whereSQL .= " and t1.district_id = '{$where['district_id']}'";
    	    $where['area'] && $whereSQL .= " and t2.area = '{$where['area']}'";
    	    if ($where['status'] !== null && $where['status'] !== '') {
    	        $whereSQL .= " and t1.status = '{$where['status']}'";
    	    }
    	    $where['position_no'] && $whereSQL .= " and t1.position_no like '%{$where['position_no']}%'";
    	    if ($where['product_sn'] || $where['product_name'] || $where['product_ids']) {
    	        $sql = 1;
    	        $where['product_sn'] && $sql .= " and t2.product_sn = '{$where['product_sn']}'";
    	        $where['product_name'] && $sql .= " and t2.product_name like '%{$where['product_name']}%'";
    	        $datas = $this -> _db -> fetchAll("select t1.position_id from shop_stock_product_position as t1 inner join shop_product as t2 on t1.product_id = t2.product_id where {$sql} group by t1.position_id");
    	        if ($datas) {
    	            foreach ($datas as $data) {
    	                $positionIDArray[] = $data['position_id'];
    	            }
    	            $whereSQL .= " and t1.position_id in (".implode(',', $positionIDArray).")";
    	        }
    	        else {
    	            return;
    	        }
    	    }
	    }
	    else {
	        $whereSQL = $where;
	    }
	    $this -> total = $this -> _db -> fetchOne("select count(*) from shop_stock_position as t1 left join shop_stock_district as t2 on t1.district_id = t2.district_id where {$whereSQL}");
	    return $this -> _db -> fetchAll("select t1.*,t2.area from shop_stock_position as t1 left join shop_stock_district as t2 on t1.district_id = t2.district_id where {$whereSQL} {$limit}");
	}
	
	/**
     * 获得库位(按产品)
     *
     * @where       array
     * @return      array
     */
	public function getPositionByProduct($where, $page = null, $pageSize = null)
	{
	    if ($page) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
	    if (is_array($where)) {
    	    $whereSQL = "t1.p_status = 0";
    	    $where['position_id'] && $whereSQL .= " and t2.position_id = '{$where['position_id']}'";
    	    $where['district_id'] && $whereSQL .= " and t3.district_id = '{$where['district_id']}'";
    	    $where['area'] && $whereSQL .= " and t4.area = '{$where['area']}'";
    	    if ($where['status'] !== null && $where['status'] !== '') {
    	        $whereSQL .= " and t2.status = '{$where['status']}'";
    	    }
    	    $where['position_no'] && $whereSQL .= " and t3.position_no like '%{$where['position_no']}%'";
    	    $where['product_sn'] && $whereSQL .= " and t1.product_sn = '{$where['product_sn']}'";
    	    $where['product_name'] && $whereSQL .= " and t1.product_name like '%{$where['product_name']}%'";
    	    $where['product_ids'] && $whereSQL .= " and t1.product_id in (".implode(',', $where['product_ids']).")";
	    }
	    else {
	        $whereSQL = $where;
	    }
	    
	    $sql = "select count(distinct(t1.product_id)) from shop_product as t1
	            left join shop_stock_product_position as t2 on t1.product_id = t2.product_id
	            left join shop_stock_position as t3 on t2.position_id = t3.position_id
	            left join shop_stock_district as t4 on t3.district_id = t4.district_id
	            left join shop_product_batch as t5 on t2.batch_id = t5.batch_id
	            where {$whereSQL}";
	    $this -> total = $this -> _db -> fetchOne($sql);
	    
	    $sql = "select t1.product_id,t1.product_sn,GROUP_CONCAT(t3.position_no) as position_no, t1.product_name,t4.district_name from shop_product as t1
	            left join shop_stock_product_position as t2 on t1.product_id = t2.product_id
	            left join shop_stock_position as t3 on t2.position_id = t3.position_id
	            left join shop_stock_district as t4 on t3.district_id = t4.district_id
	            left join shop_product_batch as t5 on t2.batch_id = t5.batch_id
	            where {$whereSQL} GROUP BY t1.product_id {$limit}";

        return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加或修改库位
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function editPosition($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);

		if ($id) {
		    $temp = $this -> getPosition("t1.district_id = {$data['district_id']} and t1.position_id <> {$id} and t1.position_no = '{$data['position_no']}'");
		}
		else {
		    if (!$data['district_id']) {
		        $this -> error = '没有选择库区';
			    return false;
		    }
		    $temp = $this -> getPosition("t1.district_id = {$data['district_id']} and t1.position_no = '{$data['position_no']}'");
		}
		if ($temp) {
		    $this -> error = '库位名称重复';
			return false;
		}

		$row = array('position_no' => $data['position_no'],
		             'status' => $data['status'],
		            );
		if ($id === null) {
		    $row['add_time'] = time();
		    $row['district_id'] = $data['district_id'];
		    $this -> _db -> insert('shop_stock_position', $row);
		} else {
		    $this -> _db -> update('shop_stock_position', $row, "position_id = {$id}");
		}
		
		return true;
	}
	
	/**
     * 删除库位
     *
     * @param    int      $id
     * @return   void
     */
	public function deletePosition($id)
	{
	    $this -> _db -> delete('shop_stock_position', "position_id = {$id}");
	    $this -> _db -> delete('shop_stock_product_position', "position_id = {$id}");
	}
	
	/**
     * 获得库位产品
     *
     * @where       array
     * @return      array
     */
	public function getProductPosition($where)
	{
	    if (is_array($where)) {
    	    $whereSQL = 1;
    	    $where['product_id'] && $whereSQL .= " and t1.product_id = '{$where['product_id']}'";
    	    isset($where['batch_id']) && $whereSQL .= " and t1.batch_id = '{$where['batch_id']}'";
    	    $where['position_id'] && $whereSQL .= " and t1.position_id = '{$where['position_id']}'";
    	    if ($where['area']) {
    	        if (is_array($where['area'])) {
    	            $whereSQL .= " and t3.area in (".implode(',', $where['area']).")";
    	        }
    	        else {
    	            $whereSQL .= " and t3.area = '{$where['area']}'";
    	        }
    	    }
	    }
	    else {
	        $whereSQL = $where;
	    }
	    $sql = "select t1.*,t2.position_no,t4.product_sn,t4.product_name,t5.batch_no from shop_stock_product_position as t1 
	            inner join shop_stock_position as t2 on t1.position_id = t2.position_id 
	            inner join shop_stock_district as t3 on t2.district_id = t3.district_id 
	            inner join shop_product as t4 on t1.product_id = t4.product_id 
	            left join shop_product_batch as t5 on t1.batch_id = t5.batch_id 
	            where {$whereSQL}";
	    return $this -> _db -> fetchAll($sql);
	}

   /**
	* 根据产品ID获取库位信息
	*
	* @param    int
	* @param    array
	*
	* @return   array
	**/
	public function getPositionInfosByProductId($product_id)
	{
		$product_id = intval($product_id);
		if ($product_id < 1) {
			$this->_error = '产品ID不正确';
			return false;
		}

		$_condition[] = "product_id = '{$product_id}'";
		$_join[]      = "LEFT JOIN `shop_stock_position` sp ON pp.position_id = sp.position_id";
		$_join[]      = "LEFT JOIN `shop_stock_district` d  ON sp.district_id = d.district_id";

		$sql = "SELECT pp.`id`, `product_id`, `batch_id`, pp.`position_id`, `position_no`, d.district_name  FROM `shop_stock_product_position` pp " . implode(' ', $_join). 
			   " WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchAll($sql);
	}

   /**
	* 根据产品SN获取库位信息
	*
	* @param    int
	* @param    array
	*
	* @return   array
	**/
	public function getPositionInfosByProductSn($sn)
	{
		$r_product = $this->getRow('shop_product',array('product_sn'=>$sn),'product_id');
		$product_id = $r_product['product_id'];
		
		$_condition[] = "product_id = '{$product_id}'";
		$_join[]      = "LEFT JOIN `shop_stock_position` sp ON pp.position_id = sp.position_id";
		$_join[]      = "LEFT JOIN `shop_stock_district` d  ON sp.district_id = d.district_id";

		$sql = "SELECT pp.`id`, `product_id`, `batch_id`, pp.`position_id`, `position_no`, d.district_name  FROM `shop_stock_product_position` pp " . implode(' ', $_join). 
			   " WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchAll($sql);
	}

	/**
     * 获取库位列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function getPositionList($params, $limit)
	 {	
		list($_condition, $_join) = $this->getPositionListCondition($params);

		$field = array(
			'position_id',
			'position_no',
			'district_no',
			'district_name',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_stock_position` sp ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." ORDER BY position_id desc limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取库位总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getPositionCount($params)
	 {	
		list($_condition, $_join) = $this->getPositionListCondition($params);

		$sql = "SELECT count(*) as count FROM `shop_stock_position` sp WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getPositionListCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		$_condition[] = "1=1";
		!empty($params['position_no']) && $_condition[] = "sp.position_no= '{$params['position_no']}'";

		$_join[] = "LEFT JOIN `shop_stock_district` sd on sp.district_id = sd.district_id";
		

		return array($_condition, $_join);
	 }
	
	/**
     * 添加库位产品
     *
     * @where       array
     * @return      array
     */
	public function addProductPosition($data)
	{
	    if (!$data['position_id'] || !$data['product_id'])  return false;
	    
	    $datas = $this -> getProductPosition(array('position_id' => $data['position_id']));
	    if ($datas) {
	        foreach ($datas as $temp) {
	            $positionInfo[$temp['product_id']][$temp['batch_id']] = 1;
	        }
	    }
	    
	    foreach ($data['product_id'] as $index => $product_id) {
	        if (!$positionInfo[$product_id][$data['batch_id'][$index]]) {
	            $row = array('product_id' => $product_id,
	                         'batch_id' => $data['batch_id'][$index],
	                         'position_id' => $data['position_id'],
	                        );
	            $this -> _db -> insert('shop_stock_product_position', $row);
	        }
	    }
	}

	public function addProductPositionsByProductid($product_id, $params)
	{
		$product_id = intval($product_id);
		if ($product_id < 1) {
			$this->_error = '产品Id不正确';
			return false;
		}

		if (count($params['position_ids']) < 1) {
			$this->_error = '没有需要提交的数据';
			return false;
		}
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		foreach ($params['position_ids'] as $position_id) {
			if (empty($position_id)) {
				continue;
			}
			$position_params = array(
				'product_id'  => $product_id,
				'position_id' => $position_id,
				'batch_id'    => $params['batch_id'],
			);
			$this->_db->insert('shop_stock_product_position', $position_params);
		}
	}
	
	/**
     * 删除库位产品
     *
     * @param    int      $id
     * @return   void
     */
	public function deleteProjectPosition($id)
	{
	    $this -> _db -> delete('shop_stock_product_position', "id = {$id}");
	}
	
	/**
     * 获得产品是否存在批次
     *
     * @param    int/array  $productID
     * @return   array
     */
	public function getCreateProductBatch($productID)
	{
	    if (!is_array($productID)) {
	        $productID = array($productID);
	    }
        
	    $datas = $this -> _db -> fetchAll("select product_id from shop_stock_status where product_id in (".implode(',', $productID).") and batch_id > 0 group by product_id");
	    if ($datas) {
	        foreach ($datas as $data) {
	            $result[$data['product_id']] = 1;   //存在批次
	        }
	    }

        foreach ($productID as $ID) {
            if ($result[$ID])   continue;
            $tempIDArray[] = $ID;
        }
        
        if ($tempIDArray) {
            $datas = $this -> _db -> fetchAll("select product_id from shop_stock_status where product_id in (".implode(',', $tempIDArray).") group by product_id");
            if ($datas) {
                foreach ($datas as $data) {
                    $result[$data['product_id']] = 2;   //无批次有出入库
                }
           }
	    }
	    
	    // 0: 无批次无出入库
	    
	    return $result;
	}
	
	/**
     * 初始化批次
     *
     * @param    int      $productID
     * @param    int      $batchID
     * @return   boolean
     */
	public function initProductBatch($productID, $batchID)
	{
	    $datas = $this -> _db -> fetchAll("select batch_id from shop_product_batch where product_id = '{$productID}'");
	    if (!$datas)    return false;
	    if (count($datas) > 1)  return false;
	    if ($datas[0]['batch_id'] != $batchID)  return false;
	   
	    $set = array('batch_id' => $batchID);
	    $where = "product_id = '{$productID}'";
	    $this -> _db -> update('shop_instock_plan', $set, $where);
	    $this -> _db -> update('shop_instock_detail', $set, $where);
	    $this -> _db -> update('shop_outstock_detail', $set, $where);
	    $this -> _db -> update('shop_allocation_detail', $set, $where);
	    $this -> _db -> update('shop_status_detail', $set, $where);
	    $this -> _db -> update('shop_stock_product_position', $set, $where);
	    $this -> _db -> update('shop_stock_status', $set, $where);
	    
	    return true;
	}
	
	//**************************************************************************************************************
	/**
     * 更新产品批次库存
     *
     * @data        array
     * @where       array
     * @return      boolean
     */
	private function updateStock($data, $where)
	{
	    if ($where['stock_id']) {
	        $whereSQL = "stock_id = {$where['stock_id']}";
	    }
	    else if (count($where) == 1 && $where['lid']) {
	        $whereSQL = "lid = {$where['lid']}";
	    }
	    else if (count($where) == 2 && $where['lid'] && $where['status_id']) {
	        $whereSQL = "lid = {$where['lid']} and status_id = {$where['status_id']}";
	    }
	    else if (count($where) == 4 && $where['lid'] && isset($where['batch_id']) && $where['product_id'] && $where['status_id']) {
	        $whereSQL = "lid = {$where['lid']} and batch_id = {$where['batch_id']} and product_id = {$where['product_id']} and status_id = {$where['status_id']}";
	    }
	    else    return false;
	    
	    $fields = array('in_number', 'real_in_number', 'out_number', 'real_out_number');
	    foreach ($fields as $field) {
	        if (!$data[$field])    continue;
	        
	        if ($data[$field]['op'] == '=') {
	            $set .= "{$field} = {$data[$field]['number']},";
	        }
	        else if ($data[$field]['op'] == '+') {
	            $set .= "{$field} = {$field} + {$data[$field]['number']},";
	        }
	        else if ($data[$field]['op'] == '-') {
	            $set .= "{$field} = {$field} - {$data[$field]['number']},";
	        }
	        else    return false;
	    }
	    if (!$set)  return false;
        
        $set = substr($set, 0, -1);
	    if ($this -> _db -> fetchRow("select 1 from shop_stock_status where {$whereSQL}")) {
	        $this -> _db -> execute("update shop_stock_status set {$set} where {$whereSQL}");
	    }
	    else {
	        if ($where['stock_id']) {
	            return false;
	        }
	        
	        $this -> _db -> insert('shop_stock_status', $where);
	        $this -> _db -> execute("update shop_stock_status set {$set} where {$whereSQL}");
	    }
    
	    return true;
	}
	
	/**
     * 获得批次优先级
     *
     * @data        array
     * @return      array
     */
	private function getBatchPrior($data)
	{
	    //todo
	    
	    return $data;
	}
	
	/**
     * 获得销售单出库库存条件
     *
     * @return      array
     */
	private function getSaleOutStockCondition()
	{
	    return array('lid' => $this -> _logicArea,
	                 'status_id' => 2,
	                );
	}
	
	/**
     * 获得产品占用库存
     *
     * @where       string
     * @joinType    string
     * @return      string
     */
	public function getProductHoldStock($where, $joinType = 'left')
	{
	    $sql = "select t1.product_id,t1.product_sn,t1.product_name,t1.goods_style,t1.local_sn,t1.adjust_num,t2.number,t2.area_id from shop_product as t1
	            {$joinType} join shop_hold_stock as t2 on t1.product_id = t2.product_id
	            where {$where}";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        foreach ($datas as $data) {
	            if ($data['area_id'] == $this -> _logicArea) {
	                $result[$data['product_id']] = $data;
	            }
	            else {
	                $data['area_id'] = $this -> _logicArea;
	                $data['number'] = 0;
	                $result[$data['product_id']] = $data;
	            }
	        }
	    }
	    
	    return $result;
	}
	
	/**
     * 按批次获得产品
     *
     * @where       array
     * @return      string
     */
	private function getProductBatch($where)
	{
	    $whereSQL = 1;
	    $fetchAll = true;
	    if (is_array($where['batch_id'])) {
	        $whereSQL .= " and batch_id in (".explode(',', $where['batch_id']).")";
	    }
	    else if ($where['batch_id']) {
	        $whereSQL .= " and batch_id = {$where['batch_id']}";
	        $fetchAll = false;
	    }
	    if ($where['batch_no']) {
	        $whereSQL .= " and batch_no = '{$where['batch_no']}'";
	        $fetchAll = false;
	    }
	    
	    if ($fetchAll) {
	        return $this -> _db -> fetchAll("select * from shop_product_batch where {$whereSQL}");
	    }
	    else {
	        return $this -> _db -> fetchRow("select * from shop_product_batch where {$whereSQL}");
	    }
	}
	
	/**
     * 添加日志
     *
     * @row         array
     * @return      void
     */
	private function addLog($row)
	{
	    $row['add_time'] = time();
	    $row['admin_name'] = $this -> _auth['admin_name'] ? $this -> _auth['admin_name'] : 'system';
	    
	    $this -> _db -> insert('shop_stock_log', $row);
	}
	
	/**
     * 获得计算移动成本分母的特殊库存
     *
     * @productID   int
     * @sum         boolean
     * @return      int
     */
	public function getCalculateProductCostSpecialStockNumber($productID, $sum = true)
	{
	    if ($sum) {
            $sql = "select sum(plan_number) as number from shop_instock_plan as t1
    	            inner join shop_instock as t2 on t1.instock_id = t2.instock_id
    	            where (lid > 20 and t2.bill_type = 15 and t2.bill_status in (3,6) and is_cancel = 0 and t1.product_id = '{$productID}') or 
    	                  (lid = 1 and t2.bill_type in (10,11,19) and t2.bill_status in (3,6) and is_cancel = 0 and t1.product_id = '{$productID}')";
    	    $number =  $this -> _db -> fetchOne($sql);
    	   
    	    return $number;
	    }
	    else {
	        $sql = "select t2.bill_no,t2.add_time,t1.plan_number as number from shop_instock_plan as t1
    	            inner join shop_instock as t2 on t1.instock_id = t2.instock_id
    	            where (lid > 20 and t2.bill_type = 15 and t2.bill_status in (3,6) and is_cancel = 0 and t1.product_id = '{$productID}') or 
    	                  (lid = 1 and t2.bill_type in (10,11,19) and t2.bill_status in (3,6) and is_cancel = 0 and t1.product_id = '{$productID}')
    	            order by t2.add_time";
    	    return $this -> _db -> fetchAll($sql);
	    }
	    
	    //有问题
	    $sql = "select sum(number) as number from shop_outstock_detail as t1
	            inner join shop_outstock as t2 on t1.outstock_id = t2.outstock_id
	            where (lid = 1 and t2.bill_type = 17 and t2.bill_status in (0,3,4) and is_cancel = 0 and t1.product_id = '{$productID}')";
	    $number -=  $this -> _db -> fetchOne($sql);
	   
	    return $number;
	}
	
	/*根据库存表获得有出库记录的产品*/
	public function getProdByOutStock($search){
	    $iswarn = $search['warn'];
	    if(empty($iswarn)) $iswarn = 1;
	    
	    if(!empty($search['product_sn'])){
	         $product_sn = trim($search['product_sn']);
	         $sql = "select t1.product_id,t1.real_in_number,t1.real_out_number,t1.out_number,t2.product_sn,t2.product_name from shop_stock_status as t1 left join shop_product as t2 on t1.product_id = t2.product_id where 1 and t1.lid = '{$search['lid']}' AND t1.status_id=2 and p_status = '0' AND t1.real_out_number>0 AND t2.product_sn='".$product_sn."' GROUP BY t1.product_id ORDER BY t1.real_out_number DESC";
	    }else if(!empty($search['product_name'])){
	        $product_name = trim($search['product_name']);
	        $sql = "select t1.product_id,t1.real_in_number,t1.real_out_number,t1.out_number,t2.product_sn,t2.product_name from shop_stock_status as t1 left join shop_product as t2 on t1.product_id = t2.product_id where 1 and t1.lid = '{$search['lid']}' AND t1.status_id=2  and p_status = '0' AND t1.real_out_number>0 AND t2.product_name LIKE '%".$product_name."%' GROUP BY t1.product_id ORDER BY t1.real_out_number DESC";
	    }else{
	        $sql = "select t1.product_id,t1.real_in_number,t1.real_out_number,t1.out_number,t2.product_sn,t2.product_name from shop_stock_status as t1 left join shop_product as t2 on t1.product_id = t2.product_id where 1 and t1.lid = '{$search['lid']}' AND t1.status_id=2  and p_status = '0' AND t1.real_out_number>0 GROUP BY t1.product_id ORDER BY t1.real_out_number DESC";
	    }
	 
	    $list =  $this->_db->fetchAll($sql);
	    return $this->checkStock($list,$iswarn);
	}
	
	/*遍历数据检测库存并组合数组返回*/
	private function checkStock($list,$iswarn){
	    $len = count($list);
	    $data_arr = array();
	    for ($i = 0; $i < $len; $i++) {
	        $arr_tmp = array();
	        //7天前的时间节点
	        $preWeek = time() - (7 * 24 * 60 * 60);
	        //30天前的时间节点
	        $preMon = time() - (30 * 24 * 60 * 60);
	        //计算时间需分为 大于30天  大于7天小于30天   小于7天来计算逻辑
	        $arr_tmp['product_id'] = $list[$i]['product_id'];
	        $arr_tmp['product_sn'] = $list[$i]['product_sn'];
	        $arr_tmp['product_name'] = $list[$i]['product_name'];
	        $arr_tmp['real_number'] = $list[$i]['real_in_number'] - $list[$i]['real_out_number']; //总入库-总出库
	        $arr_tmp['able_number'] =  $arr_tmp['real_number'] - ( $list[$i]['out_number']- $list[$i]['real_out_number']) ; //可用库存=总入库-总出库    
	        //根据产品id查询出库数据
	        $sql7 = 'SELECT otk.finish_time,od.product_id,od.number,SUM(number) as numall FROM shop_outstock as otk , shop_outstock_detail as od WHERE otk.outstock_id = od.outstock_id AND od.product_id = '. $arr_tmp['product_id'].' AND  otk.bill_type in (1,10) AND otk.finish_time > '.$preWeek;
	        $sql30 = 'SELECT otk.finish_time,od.product_id,od.number,SUM(number) as numall FROM shop_outstock as otk , shop_outstock_detail as od WHERE otk.outstock_id = od.outstock_id AND od.product_id = '. $arr_tmp['product_id'].' AND  otk.bill_type in (1,10) AND otk.finish_time > '.$preMon;
	        $obj30 = $this -> _db -> fetchRow($sql30);
	        if(empty($obj30['product_id'])) continue;
	        //判断是否需要预警 30天数据
	        $avg30 = floor($obj30['numall']/30);
	        $arr_tmp['count30'] = $avg30;
	        //可销售数量30,如果符合预警要求的产品库存不足4天进入预警   条件：可销售天数1”和“可销售天数2”中有任何一个数字小于或等于4个工作日
	        $obj7 = $this -> _db -> fetchRow($sql7);
	        $avg7 = floor(empty($obj7['numall'])?0:$obj7['numall']/7);
	        $p30 = $avg30*4;
	        $p7 = $avg7*4;
	        $arr_tmp['count7'] = $avg7;
	        if($arr_tmp['able_number'] < $p30 || $arr_tmp['able_number'] <  $p7) {
	            $arr_tmp['warn'] = 1;
	        }else{
	            $arr_tmp['warn'] = 0;
	        }
	        //30天平均可销售天数， 7天平均可销天数
	        $avg7 = empty($avg7)?1:$avg7;
	        $avg30 = empty($avg30)?1:$avg30;
	        $arr_tmp['count7avg'] = floor($arr_tmp['able_number']/$avg7);
	        $arr_tmp['count30avg'] = floor($arr_tmp['able_number']/$avg30);
	        if($iswarn == 1){
	            if( $arr_tmp['warn'] == 1){
	                array_push($data_arr, $arr_tmp);
	            }
	        }else{
	            if( $arr_tmp['warn'] == 0){
	                array_push($data_arr, $arr_tmp);
	            } 
	        }
	        
	    }
	    return $data_arr;
	}

	/**
	 * 根据产品IDS获取库存信息
     *
	 * @param    array
	 *
	 * @return   array
	 */
	public function getStockInfosByProductIds($product_ids, $params = array())
	{
		if (count($product_ids) < 1) {
			$this->_error = '产品Id为空';
			return false;
		}

        $_condition[] = "product_id in ('".implode("','", $product_ids)."')";
        !empty($params['lid'])       && $_condition[] = "lid = '{$params['lid']}'";
        !empty($params['status_id']) && $_condition[] = "status_id = '{$params['status_id']}'";

		$fields = array(
			'stock_id',
			'lid',
			'batch_id',
			'product_id',
			'status_id',
			'SUM(in_number) AS in_number',
			'SUM(real_in_number) as real_in_number',
			'SUM(out_number) as out_number',
			'SUM(real_out_number) as real_out_number',
		);
		$sql = "SELECT ".implode(',', $fields)." FROM shop_stock_status WHERE ". implode(' AND ', $_condition) ." GROUP BY product_id ";

		return $this->_db->fetchAll($sql);
	}

	/**
	 * 批量上传库位excel
     *
	 * @param    int
	 * @param    string
	 *
	 * @return   boolean
	 */
	public function importPositionXls($district_id, $file)
	{
		$xls = new Custom_Model_ExcelReader();
		$xls -> setOutputEncoding('utf-8');
		$xls -> read($file);
		$infos = $xls->sheets[0];
		if ($infos['numRows'] < 2) {
			Custom_Model_Message::showAlert('上传的文件为空', true, -1);
		}

		unset($infos[1]);
		$time = time();
		foreach ($infos['cells'] as $info) {
			if (empty($info[1]) || preg_match("/[\x7f-\xff]/", $info[1])) {
				continue;
			}

			$position_params[] = array(
				'district_id' => $district_id,
				'position_no' => $info[1],
				'status'      => '0',
				'add_time'    => $time,
			);
		}

		if (false === $this->insertBatchPosition($position_params)) {
			return false;
		}

		return true;
	}

	/**
	 * 清除上传库位excel
     *
	 * @param    int
	 * @param    string
	 *
	 * @return   boolean
	 */
	public function importClearPositionXls($district_id, $file)
	{
		$xls = new Custom_Model_ExcelReader();
		$xls -> setOutputEncoding('utf-8');
		$xls -> read($file);
		$infos = $xls->sheets[0];
		if ($infos['numRows'] < 2) {
			Custom_Model_Message::showAlert('上传的文件为空', true, -1);
		}

		unset($infos[1]);
		$time = time();
		foreach ($infos['cells'] as $info) {
			if (empty($info[1]) || preg_match("/[\x7f-\xff]/", $info[1])) {
				continue;
			}

			$position_params[] = array(
				'district_id' => $district_id,
				'position_no' => $info[1],
				'status'      => '0',
				'add_time'    => $time,
			);
		}

		$this->truncatePosition();

		if (false === $this->insertBatchPosition($position_params)) {
			return false;
		}

		$product_db = new Admin_Models_DB_Product();

		// 插入库位产品
		foreach ($infos['cells'] as $info) {
			if (empty($info[1]) || preg_match("/[\x7f-\xff]/", $info[2])) {
				continue;
			}

			$product_info = $product_db->getProductInfoByProductSn($info[2]);

			if (empty($product_info)) {
				continue;
			}

			$position_info = $this->getPositionInfoByPositionno($info[1]);

			if (empty($position_info)) {
				continue;
			}

			$position_product_params[] = array(
				'product_id'  => $product_info['product_id'],
				'position_id' => $position_info['position_id'],
				'batch_id'    => '0',
			);
		}

		if (false === $this->insertBatchPositionProduct($position_product_params)) {
			return false;
		}


		return true;
	}

	/**
	 * 清除库位和库位关系表数据
     *
	 *
	 * @return   boolean
	 */
	private function truncatePosition()
	{
		$sql = "TRUNCATE table `shop_stock_position`";

		$this->_db->execute($sql);

		$sql = "TRUNCATE table `shop_stock_product_position`";

		$this->_db->execute($sql);

		return true;
	}

	/**
	 * 批量插入库位数据
     *
	 * @param    array
	 *
	 * @return   boolean
	 */
	public function insertBatchPosition($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (count($params) < 1) {
			$this->_error = '没有要插入的数据';
			return false;
		}
		$sql = "INSERT IGNORE INTO `shop_stock_position`(`position_no`, `district_id`, `status`, `add_time`) VALUES ";

		$values = array();
		foreach ($params as $param) {
			$values[] = "('{$param['position_no']}', '{$param['district_id']}', '{$param['status']}', '{$param['add_time']}')";
		}

		return (bool) $this->_db->execute($sql.implode(',', $values));

	}


	/**
	 * 批量上传库位产品excel
     *
	 * @param    string
	 *
	 * @return   boolean
	 */
	public function importPositionProductXls($file)
	{
		$xls = new Custom_Model_ExcelReader();
		$xls -> setOutputEncoding('utf-8');
		$xls -> read($file);
		$infos = $xls->sheets[0];
		if ($infos['numRows'] < 2) {
			Custom_Model_Message::showAlert('上传的文件为空', true, -1);
		}

		unset($infos[1]);
		$time = time();

		$product_db = new Admin_Models_DB_Product();

		foreach ($infos['cells'] as $info) {
			if (empty($info[1]) || preg_match("/[\x7f-\xff]/", $info[2])) {
				continue;
			}

			$product_info = $product_db->getProductInfoByProductSn($info[1]);

			if (empty($product_info)) {
				continue;
			}

			$position_info = $this->getPositionInfoByPositionno($info[2]);

			if (empty($position_info)) {
				continue;
			}

			$position_product_params[] = array(
				'product_id'  => $product_info['product_id'],
				'position_id' => $position_info['position_id'],
				'batch_id'    => '0',
			);
		}

		if (false === $this->insertBatchPositionProduct($position_product_params)) {
			return false;
		}

		return true;
	}

	/**
	 * 根据库位号获取库位信息
     *
	 * @param    string
	 *
	 * @return   boolean
	 */
	public function getPositionInfoByPositionno($position_no)
	{
		$position_no = trim($position_no);
		if (empty($position_no)) {
			$this->_error = '库位号为空';
			return false;
		}

		$sql = "SELECT `position_id`, `position_no`, `district_id` FROM `shop_stock_position` WHERE `position_no` = '{$position_no}' and area = '{$this -> _logicArea}' limit 1";

		return $this->_db->fetchRow($sql);
	}

	/**
	 * 批量插入库位产品数据
     *
	 * @param    array
	 *
	 * @return   boolean
	 */
	public function insertBatchPositionProduct($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (count($params) < 1) {
			$this->_error = '没有要插入的数据';
			return false;
		}
		$sql = "INSERT IGNORE INTO `shop_stock_product_position`(`product_id`, `batch_id`, `position_id`) VALUES ";

		$values = array();
		foreach ($params as $key => $param) {
			$values[] = "('{$param['product_id']}', '{$param['batch_id']}', '{$param['position_id']}')";
			if ($key %1000 == 0) {
				$this->_db->execute($sql.implode(',', $values));
				$values = array();
			}
		}

		if (count($values) < 1) {
			return true;
		}
		
		return (bool) $this->_db->execute($sql.implode(',', $values));
	}

    /**
     * 添加库存提醒数据
     *
     * @param    array
     *
     * @return   boolean
     **/
    public function addStockRemindLog($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (count($params) < 1) {
			$this->_error = '没有要插入的数据';
			return false;
		}

        return $this->_db->insert('shop_stock_remind_log', $params);


    }

    /**
     * 获取库存预警日志数据
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function browseStockRemindLog($params, $limit)
	 {	
		list($_condition, $_join) = $this->getBrowseStockRemindLogCondition($params);

		$field = array(
			'log_id',
			'batch_sn',
			'l.product_id',
			'need_number',
			'able_number',
			'created_ts',
            'product_sn',
			'p.product_name',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_stock_remind_log` l ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." ORDER BY log_id desc limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取库存预警日志总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getStockRemindLogCount($params)
	 {	
		list($_condition, $_join) = $this->getBrowseStockRemindLogCondition($params);

		$sql = "SELECT count(*) as count FROM `shop_stock_remind_log` l ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getBrowseStockRemindLogCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		$_condition[] = "1 = 1";
		!empty($params['start_ts']) && $_condition[] = "l.created_ts >= '{$params['start_ts']} 00:00:00'";
		!empty($params['end_ts'])   && $_condition[] = "l.created_ts <= '{$params['end_ts']} 23:59:59'";
		!empty($params['product_sn'])     && $_condition[] = "p.product_sn = '{$params['product_sn']}'";
        !empty($params['batch_sn'])     && $_condition[] = "l.batch_sn = '{$params['batch_sn']}'";
        !empty($params['lid']) && $_condition[] = "p.lid = '{$params['lid']}'";

		$_join[] = "LEFT JOIN `shop_product` p on l.product_id = p.product_id";
		return array($_condition, $_join);
    }

	/**
     * 获得实体仓ID
     *
     * @return   array
     */
    public function getEntityAreaID()
	{
	    return array(1, 2);
	}
	
	/**
     * 初始一条库存记录
     *
     * @return   void
     */
    public function initProductStockRecord($productID, $lid)
	{
	    if (!$this -> _db -> fetchRow("select 1 from shop_stock_status where lid = '{$lid}' and status_id = '2' and product_id = '{$productID}'")) {
	        $row = array('lid' => $lid,
	                     'batch_id' => 0,
	                     'status_id' => 2,
	                     'product_id' => $productID,
	                    );
	        $this -> _db -> insert('shop_stock_status', $row);
	    }
	}
	
	/**
     * 是否包含实体仓
     *
     * @return   array
     */
	private function includeEntityArea($area)
	{
	    $entityAreaIDArray = $this -> getEntityAreaID();
	    if (is_array($area)) {
	        foreach ($area as $areaID) {
	            if (in_array($areaID, $entityAreaIDArray)) {
	                return true;
	            }
	        }
	        return false;
	    }
	    else {
	        return in_array($area, $entityAreaIDArray);
	    }
	}
	
	/**
	 * 获取异常数据
	 *
	 * @return   string
	 */
	 public function getError()
	 {
		return $this->_error;
	 }
    
}