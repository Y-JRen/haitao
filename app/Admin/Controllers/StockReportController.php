<?php
class Admin_StockReportController extends Zend_Controller_Action
{
	/**
     * api对象
     */
    private $_api = null;
    
	private $_allowDoList = array ('1','3', '10');   //组
	
	private $_page_size = '25';
	
	private $_lid;
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _api = new Admin_Models_API_Stock();
		
		$config = new Custom_Model_Stock_Config();
		$this -> view -> outTypes = $config -> _outTypes;
		$this -> view -> inTypes = $config -> _inTypes;
		$this -> view -> status = $config -> _logicStatus;
		$this -> view -> billStatus = $config -> _billStatus;
		$this -> view -> areas = $config -> _logicArea;
		$this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
		$this -> _lid = $this -> _auth['lid'];
		$this -> view -> area_name = $config -> _logicArea[$this -> _lid];
	}
	
	/**
     * 初始库存盘点
     *
     * @return   void
     */
	public function inventoryAction()
    {
		$search = $this -> _request -> getParams();
		$search['p_status'] = 0;
		$search['logic_area'] = $this -> _lid;
		if (!$search['logic_area']) {
		    if ($search['only_distribution']) {
		        foreach ($this -> view -> areas as $key => $value) {
		            if ($key > 20) {
		                $search['logic_area'] = $key;
		                break;
		            }
		        }
		    }
		    else {
		        $search['logic_area'] = 1;
		    }
		}
		!$search['status_id'] && $search['status_id'] = 2;
        $page = (int)$this -> _request -> getParam('page', 1);
        $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        
    	$productAPI = new Admin_Models_API_Product();
    	$datas = $productAPI -> getProductBatch($search, 't1.*,t2.batch_id,t2.batch_no', $page, 20, 't1.product_id');
    	if ($datas['total'] > 0) {
    	    $total = $datas['total'];
    	    $datas = $datas['data'];
    	    foreach ($datas as $data) {
    	        $productIDArray[] = $data['product_id'];
    	    }
    	    $stockData = $this -> _api -> getProductStock(array('lid' => $search['logic_area'], 'product_id' => $productIDArray, 'status_id' => $search['status_id']), 'product_id,batch_id');
    	    foreach ($datas as $index => $data) {
    	        if (!$data['batch_id']){
    	            $data['batch_id'] = 0;
    	            $datas[$index]['batch_id'] = 0;
    	        }
    	        
                $datas[$index]['real_number'] = $stockData[$data['product_id']][$data['batch_id']]['real_number'];
                $datas[$index]['wait_number'] = $stockData[$data['product_id']][$data['batch_id']]['wait_number'];
                $datas[$index]['hold_number'] = $stockData[$data['product_id']][$data['batch_id']]['hold_number'];
                !$datas[$index]['real_number'] && $datas[$index]['real_number'] = 0;
                !$datas[$index]['wait_number'] && $datas[$index]['wait_number'] = 0;
                !$datas[$index]['hold_number'] && $datas[$index]['hold_number'] = 0;
                
                $positionData = $this -> _api -> getProductPosition(array('product_id' => $data['product_id'], 'batch_id' => $data['batch_id'], 'area' => $search['logic_area']));
                if ($positionData) {
                    foreach ($positionData as $position) {
                        $datas[$index]['position_no'] .= $position['position_no'].'<br>';
                    }
                }
  	        }
   	    }
   	    else    $datas = '';
        
        $pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
	    $this -> view -> datas = $datas;
        $this -> view -> param = $search;
        $this -> view -> groupID = $auth['group_id'];
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }

	/**
     * 处理初始库存
     *
     * @return   void
     */
	public function doInventoryAction()
    {
        $data['lid'] = (int)$this -> _request -> getParam('logic_area');
        $data['product_id'] = (int)$this -> _request -> getParam('product_id');
        $data['batch_id'] = (int)$this -> _request -> getParam('batch_id');
        $data['status_id'] = (int)$this -> _request -> getParam('status_id');
        $data['number'] = (int)$this -> _request -> getParam('number');
        $type = (int)$this -> _request -> getParam('type', 1);
        $remark = $this -> _request -> getParam('remark');
        
        if ($type == 1) {
            $result = $this -> _api -> createStock($data);
        }
        else if ($type == 2) {
            $result = $this -> _api -> adjustStock($data);
            if (is_array($result)) {
                $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
                if ($result['type'] == 'instock') {
                    $instockAPI = new Admin_Models_API_InStock();
                    
                    $billNo = Custom_Model_CreateSn::createSn();
                    $row = array('lid' => $data['lid'],
                                 'bill_no' => $billNo,
                                 'bill_type' => 6,
        		                 'bill_status' => 6,
        			             'remark' => '盘点调整入库单 '.chr(13).chr(10).$remark,
        			             'add_time' => time(),
        			             'admin_name' => $auth['admin_name'],
        			            );
                    $details[] = array('product_id' => $data['product_id'],
                                       'batch_id' => $data['batch_id'],
	       	                           'status_id' => $data['status_id'],
	       	                           'plan_number' => $result['number'],
	       	                           'shop_price' => 0,
	       	                          );
	       	        $id = $instockAPI -> insertApi($row, $details, $data['lid'], true);
	       	        
	       	        $instockAPI -> receiveByBillNo($billNo);
                }
                else if ($result['type'] == 'outstock') {
                    $outstockAPI = new Admin_Models_API_OutStock();
                    
                    $billNo = Custom_Model_CreateSn::createSn();
                    $row = array('lid' => $data['lid'],
            		             'bill_no' => $billNo,
                                 'bill_type' => 11,
                                 'bill_status' => 5,
            			         'remark' => '盘点调整出库单 '.chr(13).chr(10).$remark,
                                 'add_time' => time(),
                                 'finish_time' => time(),
                                 'admin_name' => $auth['admin_name'],
                                );
                    $details[] = array('product_id' => $data['product_id'],
	                                   'batch_id' => $data['batch_id'],
	                                   'status_id' => $data['status_id'],
	                                   'number' => $result['number'],
	                                   'shop_price' => 0,
	                                  );
	                
                    $outstockAPI -> insertApi($row, $details, $row['lid'], true);
                    $where = array('lid' => $data['lid'],
                                   'product_id' => $data['product_id'],
                                   'batch_id' => $data['batch_id'],
                                   'status_id' => $data['status_id'],
                                   );
                    $this -> _api -> addStockRealOutNumber($result['number'], $where, $billNo);
                }
            }
            
            $this -> _api -> createAllHoldStock($data);
        }
        
        if ($result) {
            echo 'ok';
        }
        else {
            echo 'error';
        }
        
        exit;
    }

    /**
     * 盘点表格导入
     */
    public function addInventoryXlsAction() 
    {
        if ($this -> _request -> isPost()) {
            $post = $this -> _request -> getPost();
            if ($post['export']) {
    		    $post['p_status'] = 0;
            	$productAPI = new Admin_Models_API_Product();
            	$datas = $productAPI -> getProductBatch($post, 't1.*,t2.batch_id,t2.batch_no', null, null, 't1.product_id');
            	
            	$content[] = array('仓库', '产品ID', '产品批次ID', '产品批次', '产品编码', '产品名称', '产品规格', '库位', '库存状态', '实际库存', '在途库存', '占用库存', '实际盘点数');
            	if ($datas['data']) {
            	    foreach ($datas['data'] as $data) {
            	        $productIDArray[] = $data['product_id'];
            	    }
            	    $stockData = $this -> _api -> getProductStock(array('lid' => $post['logic_area'], 'product_id' => $productIDArray, 'status_id' => $post['status_id']), 'product_id,batch_id,status_id');
            	    foreach ($datas['data'] as $data) {
            	        !$data['batch_id'] && $data['batch_id'] = 0;
            	        $tempData1 = array($this -> view -> areas[$post['logic_area']],
            	                           $data['product_id'],
            	                           $data['batch_id'] ? $data['batch_id'] : 0,
            	                           $data['batch_no'],
            	                           $data['product_sn'],
            	                           $data['product_name'],
            	                           $data['goods_style'],
            	                           $data['local_sn'],
            	                           );
            	        $tempData2 = array();
            	        if ($stockData[$data['product_id']][$data['batch_id']]) {
            	            foreach ($stockData[$data['product_id']][$data['batch_id']] as $statusID => $data1) {
            	                //if (!$data1['real_number'] && !$data1['wait_number'] && !$data1['hold_number']) continue;
            	                $tempData2 = array($this -> view -> status[$statusID],
            	                                   $data1['real_number'],
            	                                   $data1['wait_number'],
            	                                   $data1['hold_number'],
            	                                  );
            	                $content[] = array_merge($tempData1, $tempData2);
            	            }
            	        }
            	        else {
            	            if ($post['status_id']) {
            	                $tempData2 = array($this -> view -> status[$post['status_id']], 0, 0, 0);
            	                $content[] = array_merge($tempData1, $tempData2);
            	            }
            	        }
          	        }
           	    }
                
                $xls = new Custom_Model_GenExcel();
                $xls -> addArray($content);
                $xls -> generateXML('stock_xml');
                    
                exit();
            }
            else if ($post['run'] && is_file($_FILES['import_file']['tmp_name'])) {
                $xls = new Custom_Model_ExcelReader();
			    $xls -> setOutputEncoding('utf-8');
			    $xls -> read($_FILES['import_file']['tmp_name']);
			    $datas = $xls -> sheets[0];
			    $lines = $datas['cells'];
			    if (!$lines || count($lines) < 2)   die('no content');
			    
			    if ($post['type'] == 'full') {
			        $where = array('lid' => $post['logic_area']);
			        if ($post['status_id']) {
			            $where['status_id'] = $post['status_id'];
			        }
			        $datas = $this -> _api -> getSumStock($where, 'product_id,batch_id,status_id');
			        if ($datas) {
			            foreach ($datas as $data) {
			                $stockData[$post['logic_area']][$data['product_id']][$data['batch_id']][$data['status_id']] = 1;
			            }
			        }
			    }
                
			    $config = new Custom_Model_Stock_Config();
			    unset($lines[1]);
			    $datas = array();
			    foreach ($lines as $index => $line) {
			        $logicArea = $config -> getAreaID($line[1]);
			        if (!$logicArea)    die("#{$index} no logic area");
			        
			        $productID = $line[2];
			        if (!$productID)    die("#{$index} no product id");
			        
			        $batchID = (int)$line[3] ? (int)$line[3] : 0;
			        
			        $statusID = $config -> getStatusID($line[9]);
			        if (!$statusID) die("#{$index} no status");
			        
			        $number = (int)$line[13] ? (int)$line[13] : 0;
			        
			        $datas[] = array('lid' => $logicArea,
			                         'product_id' => $productID,
			                         'batch_id' => $batchID,
			                         'status_id' => $statusID,
			                         'number' => $number,
			                        );
			        
			        if ($post['type'] == 'full' && $stockData) {
			            $stockData[$logicArea][$productID][$batchID][$statusID] = 0;
			        }
			    }

			    if ($post['type'] == 'full' && $stockData) {
			        foreach ($stockData as $lid => $stockData1) {
			            foreach ($stockData1 as $productID => $stockData2) {
			                foreach ($stockData2 as $batchID => $stockData3) {
			                    foreach ($stockData3 as $statusID => $value) {
			                        if ($value) {
			                            $datas[] = array('lid' => $lid,
			                                             'product_id' => $productID,
			                                             'batch_id' => $batchID,
			                                             'status_id' => $statusID,
			                                             'number' => 0,
			                                            );
			                        }
			                    }
			                }
			            }
			        }
			    }
                
			    $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
			    $instockAPI = new Admin_Models_API_InStock();
			    $outstockAPI = new Admin_Models_API_OutStock();
			    
			    $inDetails = array();
			    $outDetails = array();
			    foreach ($datas as $data) {
			        $result = $this -> _api -> adjustStock($data);
			        if (is_array($result)) {
                        if ($result['type'] == 'instock') {
                            $inDetails[$logicArea][] = array('product_id' => $data['product_id'],
                                                             'batch_id' => $data['batch_id'],
        	       	                                         'status_id' => $data['status_id'],
        	       	                                         'plan_number' => $result['number'],
        	       	                                         'shop_price' => 0,
        	       	                                        );
                        }
                        else if ($result['type'] == 'outstock') {
                            $outDetails[$logicArea][] = array('product_id' => $data['product_id'],
        	                                                  'batch_id' => $data['batch_id'],
        	                                                  'status_id' => $data['status_id'],
        	                                                  'number' => $result['number'],
        	                                                  'shop_price' => 0,
        	                                                 );
                        }
                    }
			    }
			    
			    if ($inDetails) {
			        foreach ($inDetails as $lid => $details) {
			            $billNo = Custom_Model_CreateSn::createSn();
                        $row = array('lid' => $lid,
                                     'bill_no' => $billNo,
                                     'bill_type' => 6,
                    		         'bill_status' => 6,
                    			     'remark' => '盘点调整入库单',
                    			     'add_time' => time(),
                    			     'admin_name' => $auth['admin_name'],
                    			    );
                        $instockAPI -> insertApi($row, $details, $lid, true);
            	        
            	        $instockAPI -> receiveByBillNo($billNo);
			        }
			    }
			    
			    if ($outDetails) {
			        foreach ($outDetails as $lid => $details) {
			            $billNo = Custom_Model_CreateSn::createSn();
			            $row = array('lid' => $lid,
                    		         'bill_no' => $billNo,
                                     'bill_type' => 11,
                                     'bill_status' => 4,
                    			     'remark' => '盘点调整出库单',
                                     'add_time' => time(),
                                     'finish_time' => time(),
                                     'admin_name' => $auth['admin_name'],
                                    );
                        $outstockAPI -> insertApi($row, $details, $lid, true);
                        
                        $outstockAPI -> sendByBillNo($billNo);
			        }
			    }
			    
			    $this -> _api -> createAllHoldStock();
			    
			    echo "<script>alert('盘点完成!')</script>";
            }
        }
        
        $this -> view -> lid = $this -> _lid;
    }

	/**
     * 动态库存
     *
     * @return   void
     */
	public function indexAction()
    {
		$search = $this -> _request -> getParams();
		$search['logic_area'] = $this -> _lid;
	    $page = (int)$this -> _request -> getParam('page', 1);
	    !isset($search['p_status']) && $search['p_status'] = 0;
	    if ($search['logic_area']){
	        if ($search['showBatch']) {
	            $groupBy = array('product_id', 'batch_id', 'status_id');
	        }
	        else {
	            $groupBy = array('product_id', 'status_id');
	        }
	        if ($search['export']) {
	            $datas = $this -> _api -> getAllProductStock($search, $groupBy);
	            $tempArray = array('产品ID');
	            if ($search['showBatch']) {
                    $tempArray[] = '产品批次ID';
                    $tempArray[] = '产品批次';
                }
                $tempArray[] = '产品编码';
                $tempArray[] = '产品名称';
                $tempArray[] = '产品规格';
                $content[] = array_merge($tempArray, array('库存状态', '计划结存', '实际库存', '可用库存', '在途库存', '占有库存', '国际码', '库位'));
                if ($datas) {
                    foreach ($datas as $data) {
                        $tempArray = array($data['product_id']);
                        if ($search['showBatch']) {
                            $tempArray[] = $data['batch_id'];
                            $tempArray[] = $data['batch_no'];
                        }
                        $tempArray[] = $data['product_sn'];
                        $tempArray[] = $data['product_name'];
                        $tempArray[] = $data['goods_style'];
                        $status = $this -> view -> status[$data['status_id']];
                        
                        $position = '';
                        $where = array('area' => $search['logic_area'],
	                                   'product_id' => $data['product_id']
	                                  );
	                    if ($search['showBatch']) {
	                        $where['batch_id'] = $data['batch_id'];
	                    }
	                    $positionData = $this -> _api -> getProductPosition($where);
                        if ($positionData) {
                            foreach ($positionData as $temp) {
                                $position .= $temp['position_no'].',';
                            }
                        }
                        
                        $content[] = array_merge($tempArray, array($status, $data['plan_number'], $data['real_number'], $data['able_number'], $data['wait_number'], $data['hold_number'], "'".$data['ean_barcode'], $position));
                    }
                }

                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('stock');
                
            	exit();
	        }
	        else {
	            $datas = $this -> _api -> getAllProductStock($search, $groupBy, $page, 20);
	            $total = $this -> _api -> getCount();
	            
	            if ($datas) {
	                foreach ($datas as $index => $data) {
	                    $where = array('area' => $search['logic_area'],
	                                   'product_id' => $data['product_id']
	                                  );
	                    if ($search['showBatch']) {
	                        $where['batch_id'] = $data['batch_id'];
	                    }
	                    $positionData = $this -> _api -> getProductPosition($where);
                        if ($positionData) {
                            foreach ($positionData as $position) {
                                $datas[$index]['position_no'] .= $position['position_no'].'<br>';
                            }
                        }
	                }
	            }
	        }
        }
        
        if ($search['logic_area']) {
	        if (is_array($search['logic_area'])) {
    	        for ( $i = 0; $i < count($search['logic_area']); $i++ ) {
                    $logic_area[$search['logic_area'][$i]] = 1;
                }
            }
            else    $logic_area[$search['logic_area']] = 1;
        }
        else {
            $logic_area[1] = 1;
        }
        $search['logic_area']  = $logic_area;

	    $pageNav = new Custom_Model_PageNav($total);
	    $this -> view -> datas = $datas;
        $this -> view -> param = $search;
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 历史库存
     *
     * @return   void
     */
	public function historyAction()
    {
		$search = $this -> _request -> getParams();
	    !isset($search['p_status']) && $search['p_status'] = 0;
	    !$search['fromdate'] && $search['fromdate'] = '2014-05-01';
        $search['logic_area'] = $this -> _lid;
	    if ($search['logic_area']){
	        $datas = $this -> _api -> historyStock($search);
	        if ($datas) {
	            foreach ($datas as $index => $data) {
	                if (!$data['start_stock_number'] && !$data['end_stock_number'] && 
	                    !$data['in_stock_number'] && !$data['in_status_number'] && !$data['in_allocation_number'] &&
	                    !$data['out_stock_number'] && !$data['out_status_number'] && !$data['out_allocation_number']) {
	                    unset($datas[$index]);
	                }
	                else if ($search['onlyChangeRecord'] && 
	                         !$data['in_stock_number'] && !$data['in_status_number'] && !$data['in_allocation_number'] &&
	                         !$data['out_stock_number'] && !$data['out_status_number'] && !$data['out_allocation_number']) {
	                    unset($datas[$index]);
	                }
	                
	            }
	        }
	        if ($search['todo'] == 'export') {
	            $content[] = array('产品编码', '产品名称', '成本价', '未税价','建议零售价', '期初库存', '期末库存', '正常入库数量', '状态更改入库数量', '调拨入库数量', '正常出库数量', '状态更改出库数量', '调拨出库数量');
	            if ($datas) {
	                foreach ($datas as $index => $data) {
	                    $content[] = array($data['product_sn'], $data['product_name'], $data['cost'], $data['cost_tax'], $data['suggest_price'], $data['start_stock_number'], $data['end_stock_number'], $data['in_stock_number'], $data['in_status_number'], $data['in_allocation_number'], $data['out_stock_number'], $data['out_status_number'], $data['out_allocation_number']);
	                }
	            }
	            $xls = new Custom_Model_GenExcel();
                $xls -> addArray($content);
                $xls -> generateXML('stock');
                
                exit;
	        }
	    }
	    
	    if ($search['logic_area']) {
	        if (is_array($search['logic_area'])) {
    	        for ( $i = 0; $i < count($search['logic_area']); $i++ ) {
                    $logic_area[$search['logic_area'][$i]] = 1;
                }
            }
            else    $logic_area[$search['logic_area']] = 1;
        }
        else {
            $logic_area[1] = 1;
        }
        $search['logic_area'] = $logic_area;
	    
		if (in_array($this -> _auth['group_id'], $this -> _allowDoList)) {
            $this -> view -> viewcost = '1';
		}
		
	    $this -> view -> datas = $datas;
        $this -> view -> param = $search;
	}
	
	/**
     * 明细库存
     *
     * @return   void
     */
	public function detailAction()
    {
		$search = $this -> _request -> getParams();
	    $search['p_status'] = 0;
	    !$search['fromdate'] && $search['fromdate'] = '2014-05-01';
        $search['logic_area'] = $this -> _lid;
	    if ($search['logic_area']){
	        $tempDatas = $this -> _api -> getStockLog($search);
	        if ($tempDatas) {
	            foreach ($tempDatas as $data) {
	                if ($data['bill_no']) {
	                    $logData[$data['batch_id']][$data['status_id']][$data['bill_no']] = $data['stock_number'];
	                }
	                else {
	                    if (substr($data['type'], 0, 2) == 'in') {
	                        $data['number'] = '+'.$data['number'];
	                    }
	                    else {
	                        $data['number'] = '-'.$data['number'];
	                    }
	                    
	                    $initStockData[] = $data;
	                }
	            }
	        }
            
	        $tempDatas = $this -> _api -> detailStock($search, $initStockData);
	        $totalData['number'] = 0;
	        $totalData['stock'] = 0;
	        if ($tempDatas) {
	            if ($search['todate']) {
	                $todate = strtotime($search['todate'].' 23:59:59');
	            }
	            else $todate = time();
	            foreach ($tempDatas as $index => $data) {
	                if ($data['finish_time'] > $todate) break;
	                
	                $datas[] = $data;
	            }
	            
	            if ($datas) {
    	            foreach ($datas as $index => $data) {
    	                !$data['batch_id'] && $data['batch_id'] = 0;
    	                
    	                if (substr($data['type'], 0, 2) == 'in') {
    	                    $datas[$index]['number'] = '+'.$datas[$index]['number'];
    	                }
    	                else {
    	                    $datas[$index]['number'] = '-'.$datas[$index]['number'];
    	                }
    	                
    	                $totalData['number'] += $datas[$index]['number'];
    	                
    	                if (isset($logData[$data['batch_id']][$data['status_id']][$data['bill_no']]) &&
    	                    $logData[$data['batch_id']][$data['status_id']][$data['bill_no']] != $data['stock']) {
    	                    $datas[$index]['error'] = '('.$logData[$data['batch_id']][$data['status_id']][$data['bill_no']].')';
    	                }
    	            }
	            }
	            
	            if (substr($data['type'], 0, 2) == 'in') {
	                $totalData['stock'] = $data['stock'] + $data['number'];
	            }
	            else {
	                $totalData['stock'] = $data['stock'] - $data['number'];
	            }
	        }
	    }
        
        if ($datas) {
            foreach ($datas as $index => $data) {
                $datas[$index]['bill_no'] = str_replace(',', '<br>', $data['bill_no']);
            }
        }
        
	    $this -> view -> datas = $datas;
	    $this -> view -> totalData = $totalData;
	    $this -> view -> initStockData = $initStockData;
        $this -> view -> param = $search;
	}
	
	/**
     * 占用库存明细
     *
     * @return   void
     */
    public function holdStockDetailAction()
    {
        $search = $this -> _request -> getParams();
        if (!$search['product_id']) exit;
        $search['logic_area'] = $this -> _lid;
        !$search['status_id'] && $search['status_id'] = 2;
        isset($search['batch_id']) && !$search['batch_id'] && $search['batch_id'] = 0;
        $search['lid'] = $search['logic_area'];
        
        $productAPI = new Admin_Models_API_Product();
        $product = array_shift($productAPI -> get(array('product_id' => $search['product_id'])));
        if (!$product)  exit;
        
        $details = $this -> _api -> createAllHoldStock($search, false);
        $total = 0;
        if ($details['outStockDetail']) {
            foreach ($details['outStockDetail'] as $lid => $data1) {
	            foreach ($data1 as $productID => $data2) {
	                foreach ($data2 as $batchID => $data3) {
	                    foreach ($data3 as $statusID => $detail) {
	                        foreach ($detail as $data) {
	                            $datas[] = $data;
	                            $total += $data['number'];
	                        }
	                    }
	                }
	            }
	        }
        }
        
        $this -> view -> product = $product;
        $this -> view -> param = $search;
        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
    }
    
    /**
     * 在途库存明细
     *
     * @return   void
     */
    public function waitStockDetailAction()
    {
        $search = $this -> _request -> getParams();
        if (!$search['product_id']) exit;
        $search['logic_area'] = $this -> _lid;
        !$search['status_id'] && $search['status_id'] = 2;
        isset($search['batch_id']) && !$search['batch_id'] && $search['batch_id'] = 0;
        $search['lid'] = $search['logic_area'];
        
        $productAPI = new Admin_Models_API_Product();
        $product = array_shift($productAPI -> get(array('product_id' => $search['product_id'])));
        if (!$product)  exit;
        
        $details = $this -> _api -> createAllHoldStock($search, false);
        $total = 0;
        if ($details['inStockDetail']) {
            foreach ($details['inStockDetail'] as $lid => $data1) {
	            foreach ($data1 as $productID => $data2) {
	                foreach ($data2 as $batchID => $data3) {
	                    foreach ($data3 as $statusID => $detail) {
	                        foreach ($detail as $data) {
	                            $datas[] = $data;
	                            $total += $data['number'];
	                        }
	                    }
	                }
	            }
	        }
        }
        
        $this -> view -> product = $product;
        $this -> view -> param = $search;
        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
    }

    /**
     * 库存图表
     *
     * @return   void
     */
    public function graphAction()
    {
        $search = $this -> _request -> getParams();
        $product_id = (int)$this -> _request -> getParam('product_id', 0);
        if (!$product_id)   exit;
        
        $productAPI = new Admin_Models_API_Product();
        $product = array_shift($productAPI -> get(array('product_id' => $product_id)));
        if (!$product)  exit;
        
        if (!$search['year']) {
            $search['year'] = date('Y');
        }
        if (!$search['month']) {
            $search['month'] = date('m');
        }
        
        $startTime = strtotime($search['year'].'-'.$search['month'].'-01 00:00:00');
        if ($search['month'] == 12) {
            $endTime = strtotime(($search['year'] + 1).'-01-01 00:00:00');
        }
        else {
            $endTime = strtotime($search['year'].'-'.($search['month'] + 1).'-01 00:00:00');
        }
        
        if (!$search['stock_type'] || $search['stock_type'] == 'out_stock') {
            $outStockAPI = new Admin_Models_API_OutStock();
            $sql = "a.product_id = {$product_id} and bill_status in (4,5) and b.finish_time >= {$startTime} and b.finish_time < {$endTime}";
            if ($search['bill_type']) {
                $sql .= " and b.bill_type = {$search['bill_type']}";
            }
            $datas = $outStockAPI -> getDetail($sql);
            if ($datas) {
                foreach ($datas as $detail) {
                    $date = date('Y-m-d', $detail['finish_time']);
                    $outStockData[$date] += $detail['number'];
                }
            }
            $time = $startTime;
            while ($time < $endTime ) {
                $date = date('Y-m-d', $time);
                if (!$outStockData[$date]) {
                    $outStockData[$date] = 0;
                }
                $time += 3600 * 24;
            }
            ksort($outStockData);
        }
        
        if (!$search['stock_type'] || $search['stock_type'] == 'in_stock') {
            $inStockAPI = new Admin_Models_API_InStock();
            $sql = "a.product_id = {$product_id} and bill_status in (6,7) and b.finish_time >= {$startTime} and b.finish_time < {$endTime}";
            if ($search['bill_type']) {
                $sql .= " and b.bill_type = {$search['bill_type']}";
            }
            $datas = $inStockAPI -> getDetail($sql);
            if ($datas) {
                foreach ($datas as $detail) {
                    $date = date('Y-m-d', $detail['finish_time']);
                    $inStockData[$date] += $detail['number'];
                }
            }
            $time = $startTime;
            while ($time < $endTime ) {
                $date = date('Y-m-d', $time);
                if (!$inStockData[$date]) {
                    $inStockData[$date] = 0;
                }
                $time += 3600 * 24;
            }
            ksort($inStockData);
        }
        
        $this -> view -> days = ($endTime - $startTime) / 3600 / 24;
        
        
        $this -> view -> product = $product;
        $this -> view -> param = $search;
        $this -> view -> outStockData = $outStockData;
        $this -> view -> inStockData = $inStockData;
    }

    /*库存预警*/
    public function warnAction(){
        //1.根据库存表统计有出库记录的产品，并获得实际库存，按出库数量从大到小排序
        $search = $this -> _request -> getParams();
        $search['lid'] = $this -> _lid;
        $data =  $this->_api->getProdByOutStock($search);
        $this -> view -> param = $search;
        $this->view->datas = $data;
        //2.根据出库后符合要求的数据进行计算
    }
    
    /**
     * 库存预警old
     *
     * @return   void
     */
    public function warnBakAction()
    {
        $search = $this -> _request -> getParams();
		$search['p_status'] = 0;
		if (!$search['logic_area'])  $search['logic_area'] = 1;
		if (!$search['status_id'])  $search['status_id'] = 2;
        if (!$search['warn'])   $search['warn'] = 1;
        
        if ($search['product_sn'] || $search['product_name'] || $search['cat_id']) {
            $productAPI = new Admin_Models_API_Product();
            $datas = $productAPI -> get($search, 'product_id');
            if ($datas) {
                foreach ($datas as $data) {
                    $productIDArray[] = $data['product_id'];
                }
            }
        }
        else {
            $productIDArray = $this -> _api -> getActiveProductID($search);
        }
        
        if ($productIDArray) {
            $where = array('lid' => $search['logic_area'],
                           'status_id' => $search['status_id'],
                           'product_id' => $productIDArray,
                          );
            $datas = $this -> _api -> getAllProductStock($where, array('product_id'));
            if ($datas && $search['warn'] && !$search['product_sn'] && !$search['product_name']) {
                foreach ($datas as $index => $data) {
                    if ($search['warn'] == 1 && $data['real_number'] < $data['warn_number']) {
                        continue;
                    }
                    if ($search['warn'] == 2 && $data['real_number'] >= $data['warn_number']) {
                        continue;
                    }
                    unset($datas[$index]);
                }
            }
        }
        
        $this -> view -> datas = $datas;
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $search;
    }
    
    /**
     * ajax更新数据
     *
     * @return void
     */
    public function ajaxupdateAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        if ($id > 0) {
            $productAPI = new Admin_Models_API_Product();
            $productAPI -> ajaxUpdate($id, $field, $val);
        } else {
            exit('error!');
        }
    }
    
    /**
     * 更新产品货位
     *
     * @return   void
     */
	public function updateLocalSnAction()
    {
        $productID = (int)$this -> _request -> getParam('product_id', 0);
        if (!$productID)    exit;
        
        $productAPI = new Admin_Models_API_Product();
        $productAPI -> ajaxUpdate($productID, 'local_sn', $this -> _request -> getParam('local_sn'));
        
        exit;
    }
    
    /**
     * 重新计算占用库存
     *
     * @return   void
     */
    public function stockAction()
    {
        $this -> _api -> createAllHoldStock();
        exit;
    }
    
    /**
     * 库区列表
     *
     * @return   void
     */
    public function districtListAction()
    {
        $search = $this -> _request -> getParams();
        $search['area'] = $this -> _lid;
        $datas = $this -> _api -> getDistrict($search);
        
        $this -> view -> datas = $datas;
        $this -> view -> param = $search;
    }
    
    /**
     * 添加库区
     *
     * @return   void
     */
	public function addDistrictAction()
	{
	    if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editDistrict($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage('添加成功', '/admin/stock-report/district-list', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error);
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> view -> area = $this -> _lid;
        	$this -> render('edit-district');
        }
	}
	
	/**
     * 编辑库区
     *
     * @return void
     */
    public function editDistrictAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editDistrict($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage('编辑成功', '/admin/stock-report/district-list', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error);
	        	}
            } else {
                $data = array_shift($this -> _api -> getDistrict(array('district_id' => $id)));
                $this -> view -> area = $this -> _lid;
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 库位列表
     *
     * @return   void
     */
    public function positionListAction()
    {
        $search = $this -> _request -> getParams();
        $search['area'] = $this -> _lid;
        !$search['viewType'] && $search['viewType'] = 'position';
        
        $districtData = $this -> _api -> getDistrict(array('area' => $search['area']));
        if ($districtData) {
            foreach ($districtData as $district) {
                $districts[$district['district_id']] = $district['district_name'];
            }
        }
        
        if ($search['viewType'] == 'position') {
            $datas = $this -> _api -> getPosition($search, $search['page'] ? $search['page'] : 1, 25);
        }
        else {
            $positionData = $this -> _api -> getPositionByProduct($search, $search['page'] ? $search['page'] : 1, 25);
            if ($positionData) {
                foreach ($positionData as $data) {
                    if ($datas[$data['product_id']]) {
                        $datas[$data['product_id']]['position_no'] .= $data['position_no'].'<br>';
                    }
                    else {
                        $datas[$data['product_id']] = $data;
                    }
                }
            }
        }
        $this -> view -> districts = $districts;
        $this -> view -> datas = $datas;
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($this -> _api -> total, 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 添加库位
     *
     * @return   void
     */
	public function addPositionAction()
	{
	    if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editPosition($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage('添加成功', '/admin/stock-report/position-list', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error);
        	}
        } else {
            $districtData = $this -> _api -> getDistrict(array('area' => $this -> _lid));
            if ($districtData) {
                foreach ($districtData as $district) {
                    $districts[$district['district_id']] = $district['district_name'];
                }
                $this -> view -> data = array('position_no' => $districtData[0]['district_no']);
            }
            $this -> view -> districts = $districts;
            $this -> view -> area = $this -> _lid;
        	$this -> view -> action = 'add';
        	$this -> render('edit-position');
        }
	}
	
	/**
     * 编辑库位
     *
     * @return void
     */
    public function editPositionAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editPosition($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage('编辑成功', '/admin/stock-report/position-list', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error);
	        	}
            } else {
                $data = array_shift($this -> _api -> getPosition(array('position_id' => $id)));
                $districtData = $this -> _api -> getDistrict(array('area' => $data['area']));
                if ($districtData) {
                    foreach ($districtData as $district) {
                        $districts[$district['district_id']] = $district['district_name'];
                    }
                }
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
                $this -> view -> districts = $districts;
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 获得库区下拉框(ajax)
     *
     * @return void
     */
    public function getDistrictBoxAction()
    {
        $area = (int)$this -> _request -> getParam('area', 0);
        if ($area) {
            $districtData = $this -> _api -> getDistrict(array('area' => $area));
            if ($districtData) {
                $result = '<select name="district_id" onchange="changeDistrict()">';
                foreach ($districtData as $district) {
                    $result .= '<option value="'.$district['district_id'].'">'.$district['district_name'].'</option>';
                }
                $result .= '</select>';
                echo $result;
            }
        }
        
        exit;
    }
    
    /**
     * 获得库区编号(ajax)
     *
     * @return void
     */
    public function getDistrictNoAction()
    {
        $district_id = (int)$this -> _request -> getParam('district_id', 0);
        if ($district_id) {
            $districtData = array_shift($this -> _api -> getDistrict(array('district_id' => $district_id)));
            echo $districtData['district_no'];
        }
        
        exit;
    }
    
    /**
     * 删除库位
     *
     * @return   void
     */
	public function deletePositionAction()
	{
	    $id = (int)$this -> _request -> getParam('id', 0);
	    if ($id) {
	        $this -> _api -> deletePosition($id);
	        $this->_redirect("/admin/stock-report/position-list");
	    }
	    
	    exit;
	}
	
	/**
     * 选择库位对应产品
     *
     * @return void
     */
    public function selectAllProductAction()
    {
    	$id = $this -> _request -> getParam('id', 0);
    	if (!$id)   exit;
    	
    	if ($this -> _request -> isPost()) {
    	    $post = $this -> _request -> getPost();
    	    $this -> _api -> addProductPosition($post);
    	    $this->_redirect(base64_decode($post['url']));
    	}
    	
    	$datas = $this -> _api -> getProductPosition(array('position_id' => $id));
    	$this -> view -> datas = $datas;
    	$this -> view -> position_id = $id;
    	$this -> view -> url = $this -> _request -> getParam('url');
    }

	/**
     * 选择库位对应产品
     *
     * @return void
     */
    public function selectAllPositionAction()
    {
    	$id = $this -> _request -> getParam('id', 0);
		$batch_id = $this->_request->getParam('batch_id', 0);
    	if (empty($id)) {
			exit('产品ID为空');
		}
   
    	if ($this -> _request -> isPost()) {
    	    $params = $this->_request->getPost();
			if (false === $this->_api->addProductPositionsByProductid($params['product_id'], $params)) {
				exit($this->_api->getError());
			}
    	    $this->_redirect(base64_decode($params['url']));
    	}
    	
    	$infos = $this->_api->getPositionInfosByProductId($id);
		$this->view->infos      = $infos;
		$this->view->product_id = $id;
		$this->view->batch_id   = $batch_id;
    	$this->view->url        = $this->_request->getParam('url');
    }

	public function selPositionAction()
	{
		$page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
		$infos = array();
		if ($params['job'] == 'search') {
			$count  = $this->_api->getPositionCount($params);
			if ($count > 0) {
				$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
				$infos = $this->_api->getPositionList($params, $limit);
			}
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();

        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
	}

	/**
	 * 批量导入库位
	 *
	 */
	public function insertBatchPositionAction()
	{
		$params = $this->_request->getParams();
		$params['area'] = $this -> _lid;
		if ($this->_request->isPost()) {
			$tmp_file = $_FILES['import_file']['tmp_name'];
			if (!is_file($tmp_file)) {
				Custom_Model_Message::showAlert('请选择要上传的excel文件', true, -1);
			}

			$district_id = !empty($params['district_id']) ? $params['district_id'] : 0;

			if (false === $this->_api->importPositionXls($district_id, $tmp_file)) {
				Custom_Model_Message::showAlert($this->_api->getError(), true, -1);
			}

			Custom_Model_Message::showAlert('操作成功', true, '/admin/stock-report/position-list', 0, 0);

		}

		$districtData = $this->_api->getDistrict(array('area' => $this -> _lid));
		if ($districtData) {
			foreach ($districtData as $district) {
				$districts[$district['district_id']] = $district['district_name'];
			}
			$this -> view -> data = array('position_no' => $districtData[0]['district_no']);
		}
		$this -> view -> districts = $districts;
		$this -> view -> params = $params;
	}

	/**
	 * 批量导入库位产品
	 *
	 */
	public function insertBatchPositionProductAction()
	{
		if ($this->_request->isPost()) {
			$tmp_file = $_FILES['import_file']['tmp_name'];
			if (!is_file($tmp_file)) {
				Custom_Model_Message::showAlert('请选择要上传的excel文件', true, -1);
			}
            $this->_api->setLogicArea($this -> _lid);
			if (false === $this->_api->importPositionProductXls($tmp_file)) {
				Custom_Model_Message::showAlert($this->_api->getError(), true, -1);
			}

			Custom_Model_Message::showAlert('操作成功', true, '/admin/stock-report/position-list', 0, 0);

		}
	}

	/**
	 * 清空导入产品库位
	 *
	 */
	public function insertClearPositionAction()
	{
		$params = $this->_request->getParams();
		$params['area'] = $this -> _lid;
		if ($this->_request->isPost()) {
			$tmp_file = $_FILES['import_file']['tmp_name'];
			if (!is_file($tmp_file)) {
				Custom_Model_Message::showAlert('请选择要上传的excel文件', true, -1);
			}

			$district_id = !empty($params['district_id']) ? $params['district_id'] : 0;

			if (false === $this->_api->importClearPositionXls($district_id, $tmp_file)) {
				Custom_Model_Message::showAlert($this->_api->getError(), true, -1);
			}

			Custom_Model_Message::showAlert('操作成功', true, '/admin/stock-report/position-list', 0, 0);

		}

		$districtData = $this->_api->getDistrict(array('area' => 1));
		if ($districtData) {
			foreach ($districtData as $district) {
				$districts[$district['district_id']] = $district['district_name'];
			}
			$this -> view -> data = array('position_no' => $districtData[0]['district_no']);
		}
		$this -> view -> districts = $districts;
		$this -> view -> params = $params;
	}


    
    /**
     * 删除库位产品(ajax)
     *
     * @return void
     */
    public function deleteProductPositionAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id) {
            $this -> _api -> deleteProjectPosition($id);
        }
        
        exit;
    }


	/**
	 * 下载模板
	 *
	 *
	 **/
	public function downloadTemplateAction()
	{
		$this->_helper->viewRenderer->setNoRender();

		$type = $this->_request->getParam('type', '');

		switch($type) {
			case 'position':
				$title[] = array('库位号');
			break;
			case 'position-clear-product':
				$title[] = array('库位', '产品编码');
			break;
			case 'position-product':
			default:
				$title[] = array('产品编码', '库位号');
		}

		$xls = new Custom_Model_GenExcel();
		$xls -> addArray($title);
		$xls -> generateXML($type.date('Y-m-d'));
		exit();
	}
	
	public function distributionStockAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    $datas = $db -> fetchAll("select product_id,product_sn,product_name from shop_product");
	    foreach ($datas as $data) {
	        $productInfo[$data['product_id']] = $data;
	    }
	    
	    $lidArray = array('21' => '亚马逊',
                          '22' => '京东',
                          '23' => '一号店',
                          '24' => '顺丰优选',
                          '25' => '易迅',
                          '26' => '苏宁易购',
                          '27' => '国药小卖部',
                          '28' => '天呈科技',
                          '29' => '国药阳光',
                          '30' => '国大药房',
    	                 );
    	                 
    	$usernameArray = array('21' => 'amazon_distribution',
                               '22' => 'jingdong_distribution',
                               '23' => 'yihaodian_distribution',
                               '24' => 'shunfeng_distribution',
                               '25' => 'yixun_distribution',
                               '26' => 'suning_distribution',
                               '27' => 'store_distribution',
                               '28' => 'tiancheng_distribution',
                               '29' => 'yangguang_distribution',
                               '30' => 'guoda_distribution',
        	                  );
    	
        foreach ($lidArray as $lid => $name) {
            $stockData = $this -> _api -> getProductStock(array('lid' => $lid));
            if ($stockData) {
                foreach ($stockData as $productID => $data) {
                    if ($data['hold_number'] > 0 || $data['wait_number'] > 0) {
                        die($name.' '.$productInfo[$productID]['product_sn'].'占用或在途库存大于0!');
                    }
                    
                    $data['real_number'] > 0 && $result[$lid][$productID] += $data['real_number'];
                }
            }
            
            $sql = "select t1.product_id,t1.number from shop_order_batch_goods as t1
                    inner join shop_order as t2 on t1.order_id = t2.order_id
                    inner join shop_order_batch as t3 on t1.order_batch_id = t3.order_batch_id
                    where t2.user_name = '{$usernameArray[$lid]}' and t3.type = 18 and t3.status = 4 and t3.status_logistic = 4 and t1.number > 0";
            $orderData = $db -> fetchAll($sql);
            if ($orderData) {
                foreach ($orderData as $data) {
                    $result[$lid][$data['product_id']] += $data['number'];
                }
            }
        }
        
        foreach ($result as $lid => $datas) {
            foreach ($datas as $productID => $number) {
                echo $lidArray[$lid].','.$productInfo[$productID]['product_sn'].','.$productInfo[$productID]['product_name'].','.$number.chr(13).chr(10);
            }
        }
        
        exit;
	}

    /**
     * 库存预警日志
     *
     * @return void
     */
    public function stockRemindLogAction()
    {
        $page = (int)$this ->_request->getParam('page', 1);
		$params = $this->_request->getParams();
		$params['lid'] = $this -> _lid;
        $count  = $this->_api->getStockRemindLogCount($params);
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->browseStockRemindLog($params, $limit);
		}
		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
    }
}

