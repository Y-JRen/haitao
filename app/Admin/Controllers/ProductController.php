<?php
class Admin_ProductController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
    
    private $_units = array('件','瓶');
    
    const ADD_SUCCESS = '产品资料添加成功!';
    const EDIT_SUCCESS = '产品资料编辑成功!';
    const EDIT_FAIL = '产品资料编辑失败!';
    const IMG_SUCCESS = '商品图片添加成功!';

	private $_page_size = '15';
    
    /**
     * 允许操作的管理员列表
     * @var array
     */
     private $_allowDoList = array ('1');   
     
     private $_lid;
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _api = new Admin_Models_API_Product();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		$config = Custom_Model_Stock_Base::getInstance($this -> _request -> getParam('logic_area', null));
		$this -> view -> status = $config -> getConfigLogicStatus();
		$this -> _lid = $this -> _auth['lid'];
	}
	
	/**
     * 默认动作
     *
     * @return   void
     */
    public function indexAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $search['lid'] = $this -> _lid;
        $datas = $this -> _api -> get($search, '*', $page,20);
        if ($datas) {
            foreach ($datas as $num => $data) {
                $datas[$num]['status'] = $this -> _api -> ajaxStatus('/admin/product/status', $datas[$num]['product_id'], $datas[$num]['p_status']);
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
        }

		$product_ids = $this->_api->getSingleKey($datas, 'product_id');

		$stockAPI = new Admin_Models_API_Stock();
		$stock_infos = $stockAPI->getStockInfosByProductIds($product_ids, array('status_id' => 2, 'lid' => $this -> _lid));
        
		if  (false !== $stock_infos) {
			$stock_infos = $this->_api->singleGroup($stock_infos, 'product_id');
			$holdStockData = $stockAPI -> getProductHoldStock("t1.product_id in (".implode(',', $product_ids).")", 'inner');
			if ($holdStockData) {
			    foreach ($holdStockData as $data) {
			        $holdStockInfo['product_id'] = $data['number'];
			    }
			}
			foreach ($datas as &$data) {
				$data['stock_able_number'] = 
                    ($stock_infos[$data['product_id']]['real_in_number'] - $stock_infos[$data['product_id']]['real_out_number']) 
                    - $holdStockInfo[$data['product_id']] 
                    - ($stock_infos[$data['product_id']]['out_number'] - $stock_infos[$data['product_id']]['real_out_number']);
                
			}
		}

        $total = $this -> _api -> getCount();
        $this -> view -> datas = $datas;
		$this->view->stock_infos = $stock_infos;
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id','selected'=>$search['cat_id']));
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 产品成本列表
     *
     * @return   void
     */
    public function priceListAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $search['lid'] = $this -> _lid;
        $datas = $this -> _api -> get($search, '*', $page, 20);
        $total = $this -> _api -> getCount();
        if ($datas) {
            foreach ($datas as $num => $data) {
                $productIDArray[] = $data['product_id'];
            }
            
            $stockAPI = new Admin_Models_API_Stock();
            $productStock = $stockAPI -> getSaleProductOutStock(array('product_id' => $productIDArray));
            if ($productStock) {
                foreach ($productStock as $stock) {
                    $stockData[$stock['product_id']] = $stock;
                }
            }
            foreach ($datas as $num => $data) {
                $datas[$num]['real_number'] = $stockData[$data['product_id']]['real_number'];
            }
        }
        $this -> view -> datas = $datas;
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 导出产品成本
     *
     * @return   void
     */
    public function exportPriceAction()
    {
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> get($search, '*');
        
        $content[] = array('产品ID', '产品编码', '产品名称','建议销售价', '采购成本价', '移动成本价', '发票税率', '状态');
        
        if ($datas) {
            foreach ($datas as $data) {
                $content[] = array($data['product_id'], $data['product_sn'], $data['product_name'], $data['suggest_price'], $data['purchase_cost'], $data['cost'], $data['invoice_tax_rate'], $data['p_status'] ? '冻结' : '正常');
            }
        }
        
        $xls = new Custom_Model_GenExcel();
        $xls -> addArray($content);
        $xls -> generateXML('product-price');
                
        exit();
    }
    
    /**
     * 添加动作
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
		    $result = $this -> _api -> edit($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, '/admin/product/index', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
            $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'), 'changeCat(this.value)');
            $goodsAPI = new Admin_Models_API_Goods();
            $this -> view -> characters = $this -> _api -> getCharacters('status = 0');
            $this -> view -> brand = $goodsAPI -> getBrand();
            $this -> view -> units = $this -> _units;
        	$this -> view -> action = 'add';
        	$this -> render('edit');
        }
    }
    
    /**
     * 编辑动作
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                
                $result = $this -> _api -> edit($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	} else {
	        		Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } 
            else {
                $data = array_shift($this -> _api -> get(array('product_id' => $id)));
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
                $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
                $goodsAPI = new Admin_Models_API_Goods();
                $this -> view -> brand = $goodsAPI -> getBrand();
                $this -> view -> characters = $this -> _api -> getCharacters('status = 0');
                $this -> view -> character = $this -> _character;
                $this -> view -> units = $this -> _units;
                
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 更改状态动作
     *
     * @return void
     */
    public function statusAction()
    {
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
    		$this -> _api -> ajaxUpdate($id, 'p_status', $status);
        }
        
        echo $this -> _api -> ajaxStatus('/admin/product/status', $id, $status);
        
        exit;
    }
    
	/**
     * 导出动作
     *
     * @return   void
     */
    public function exportAction()
    {
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this -> _helper -> viewRenderer -> setNoRender();
        $this -> _api -> export($this -> _request -> getParams());
        exit;
    }
    
	/**
     * 导出动作
     *
     * @return   void
     */
    public function saleExportAction()
    {
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this -> _helper -> viewRenderer -> setNoRender();
        $this -> _api -> saleExport($this -> _request -> getParams());
    }
	/**
     * 更新前台销售排行
     *
     * @return   void
     */
    public function updateSaleAction()
    {
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this -> _api -> updateSale($this -> _request -> getParams());
        exit('ok');
    }
    
    /**
     * 选择商品
     *
     * @return void
     */
    public function selAction()
    {
        $job = $this -> _request -> getParam('job', null);
        $type = $this -> _request -> getParam('type', null);
        $justOne = $this -> _request -> getParam('justOne', null);
        $hidePrice = $this -> _request -> getParam('hidePrice', null);
        $logicArea = (int)$this -> _request -> getParam('logic_area', 1);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $search['lid'] = $this -> _lid;
        
        switch ($type) {
        	case 'sel':
        		$logicArea <= 10 && $search['filter'] = " and p_status = 0";
        	    $showStatus = 'true';
        	    $showNumber = 'false';
        	    break;
        	case 'sel_status':
        	    $logicArea <= 10 && $search['p_status'] = "0";
        	    break;
        	case 'sel_stock':
        		$logicArea <= 10 && $search['p_status'] = "0";
        	    break;
    	    case 'sel_cost':
    	        $logicArea <= 10 ;
    	        break;
            default:
                $showStatus = 'false';
                $logicArea <= 10 && $search['filter'] = " and p_status = 0 ";
        }
     
        if ($job) {
            if ($type == 'sel_status') {        //不包含销售产品占用库存
        	    $stockAPI = new Admin_Models_API_Stock();
        	    $datas = $stockAPI -> getProductOutStock($search, $page, 15);
        	    $total = $stockAPI -> getCount();
        	}
        	else if ($type == 'sel_stock') {    //包含销售产品占用库存
        	    $stockAPI = new Admin_Models_API_Stock();
        	    $stockAPI -> setLogicArea($search['lid']);
				$datas = $stockAPI -> getSaleProductOutStock($search, $page, 15,true);
        	    $total = $stockAPI -> getCount();
        	} 
        	else if ($type == 'sel') {          //无库存信息
        	    $datas = $this -> _api -> getProductWithBatch($search, '*', $page,15);
        	    $total = $this -> _api -> getCount();
        	}
        	else {  //无库存信息，无批次信息
        	    $datas = $this -> _api -> get($search, '*', $page,15);
        		$total = $this -> _api -> getCount();
        	}
       	    $this -> view -> datas = $datas;
        }
        else {
            $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        }
        
        $this -> view -> justOne = $justOne;
        $this -> view -> showStatus = $showStatus;
        $this -> view -> showNumber = $showNumber;
        $this -> view -> hidePrice = $hidePrice;
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
	/**
     * 锁定/解锁动作
     *
     * @return   void
     */
    public function lockAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('val', 0);
    	$this -> _api -> lock($this -> _request -> getPost(), $val);
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
            $this -> _api -> ajaxUpdate($id, $field, $val);
        } else {
            exit('error!');
        }
    }
    
    /**
     * 商品图片管理
     *
     * @return void
     */
    public function imageAction()
    {
        $product_id = (int)$this -> _request -> getParam('id', 0);
        $product_sn = $this -> _request -> getParam('product_sn', null);
        if ($product_id > 0) {
            if ($this -> _request -> isPost()) {
            	$result = $this -> _api -> upimg($this -> _request -> getPost(), $product_id, $product_sn);
            	Custom_Model_Message::showMessage(self::IMG_SUCCESS, 'event', 1250, "Gurl('refresh')");
            } else {
            	$this -> view -> data = array_shift($this -> _api -> get(array('product_id' => $product_id)));
                $this -> view -> img_url = $this -> _api -> getImg("product_id='$product_id' and img_type=2");
                $this -> view -> img_ext_url = $this -> _api -> getImg("product_id='$product_id' and img_type=3");
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 删除产品细节/展示图片
     *
     * @return void
     */
    public function deleteimgAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = $this -> _request -> getParam('id', null);
        if ((int)$id > 0) {
            $result = $this -> _api -> deleteImg((int)$id);
            exit;
            if(!$result) {
        	    exit($this -> _api -> error());
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 成本修改
     *
     * @return void
     */
    public function costEditAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id < 0)    exit;
        
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editPrice($id, $this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl('refresh')");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
            $data = array_shift($this -> _api -> get(array('product_id' => $id)));
            
            $this -> view -> data = $data;
            
        }
    }
    
    /**
     * 产品批次列表
     *
     * @return   void
     */
    public function batchListAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> getBatch($search, 't1.*,t2.product_name,t2.goods_style,t3.cat_name,t4.supplier_name', $page, 20);
        if ($datas['data']) {
            foreach ($datas['data'] as $num => $data) {
                
            }
        }
        
        $supplierAPI = new Admin_Models_API_Supplier();
        $this -> view -> supplierData = $supplierAPI -> getSupplier("status = 0", "supplier_id,supplier_name");
        $this -> view -> datas = $datas['data'];
        $this -> view -> catSelect = $this -> _cat ->buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 添加批次动作
     *
     * @return void
     */
    public function addBatchAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editBatch($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage('添加批次成功', '/admin/product/batch-list', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> render('edit-batch');
        }
    }
    
    /**
     * 编辑批次动作
     *
     * @return void
     */
    public function editBatchAction()
    {
        $batch_id = (int)$this -> _request -> getParam('batch_id', null);
        if ($batch_id> 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editBatch($this -> _request -> getPost(), $batch_id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage('编辑批次成功', '/admin/product/batch-list', 1250);
	        	} else {
	        		Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } 
            else {
                $datas = $this -> _api -> getBatch(array('batch_id' => $batch_id));
                $data = array_shift($datas['data']);
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 生成供应商下拉框(ajax调用)
     *
     * @return void
     */
    public function supplierBoxAction()
    {
        $product_id = (int)$this -> _request -> getParam('product_id', 0);
        if (!$product_id)   exit;
        
        $supplier_id = (int)$this -> _request -> getParam('supplier_id', 0);
        
        $supplierAPI = new Admin_Models_API_Supplier();
        $datas = $supplierAPI -> getProductSupplier($product_id);
        if ($datas) {
            foreach ($datas as $data) {
                if ($data['supplier_id'] == $supplier_id) {
                    $selected = 'selected';
                }
                else    $selected = '';
                $result .= "<option value=\"{$data['supplier_id']}\" {$selected}>{$data['supplier_name']}</option>";
            }
        }
        else {
            $result .= "<option value=\"\">无供应商</option>";
        }
        
        echo '<select name="supplier_id" id="supplier_id">'.$result.'</select>';
        
        exit;
    }
    
    /**
     * 修改产品批次排序(ajax调用)
     *
     * @return void
     */
    public function batchChangeSortAction()
    {
        $batch_id = (int)$this -> _request -> getParam('batch_id', 0);
        $sort = (int)$this -> _request -> getParam('sort', 0);
        
        if (!$batch_id || !$sort) {
            exit;
        }
        
        $this -> _api -> updateBatch(array('sort' => $sort), "batch_id = {$batch_id}");
        
        exit;
    }
    
    /**
     * 获得产品编码前缀(ajax调用)
     *
     * @return void
     */
    function getProductPrefixSnAction()
    {
        $catID = (int)$this -> _request -> getParam('catID', 0);
        if (!$catID) {
            exit;
        }
        
        $productSN = $this -> _api -> getProductPrefixSn($catID);
        if (!$productSN) {
            die('error');
        }
        
        die($productSN);
    }



    /**
     * 产品国际码列表
     *
     * @return void
     */
    function barcodeAction()
    {
        $type = (int)$this -> _request -> getParam('type', 1);
		if($type == '1'){
			$page = (int)$this -> _request -> getParam('page', 1);
			$search = $this -> _request -> getParams();
			$search['lid'] = $this -> _lid;
			$datas = $this -> _api -> get($search, '*', $page);
			$total = $this -> _api -> getCount();
			$this -> view -> datas = $datas;
			$this -> view -> param = $this -> _request -> getParams();
			$pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
			$this -> view -> pageNav = $pageNav -> getNavigation();
		}else{
			$page = (int)$this -> _request -> getParam('page', 1);
			$search = $this -> _request -> getParams();
			$search['p_status'] = 0;
			$search['status_id'] = 2;
			$search['logic_area'] = $this -> _lid;
			$datas = $this -> _api -> getStockStatus($search, '*', $page);
			$total = $this -> _api -> getCount();
			$this -> view -> datas = $datas;
			$this -> view -> param = $this -> _request -> getParams();
			$pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
			$this -> view -> pageNav = $pageNav -> getNavigation();
		}
    }

	/**
	 * 礼品卡金额列表
	 *
	 */
	public function giftcardPricelistAction()
	{
		$page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
        $count  = $this->_api->getGiftcardCount($params);
		
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->getGiftcardList($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
	}

	public function changeAjaxGiftproductAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$product_id = $this->_request->getParam('product_id', 0);
		$amount  = $this->_request->getParam('amount', 0);

		if (intval($product_id) == 0) {
			exit(json_encode(array('success' => 'false', 'message' => '产品ID不正确')));
		}

		if (ceil($amount) <= 0) {
			exit(json_encode(array('success' => 'false', 'message' => '金额不能小于等于0')));
		}

		if (false === $this->_api->updateGiftcardAmountByProductid($product_id,$amount)) {
			exit(json_encode(array('success' => 'false', 'message' => $this->_api->getError())));
		}

		$gift_info = $this->_api->getGiftcardInfoByProductid($product_id);
		exit(json_encode(array('success' => 'true', 'message' => '操作成功', 'data' => $gift_info)));
	}
    
    public function productCostAction()
    {
        $param = $this -> _request -> getParams();
        if ($param['detail']) {
            $reportParam['fromdate'] = strtotime('2013-05-27');
            $reportParam['todate'] = time();
        }
        $result = $this -> _api -> calculateProductCostByInitCost($param['product_id'], false, false, $reportParam);
        
        if ($param['detail']) {
            var_dump($result);
        }
        else {
            echo date('Y-m-d H:i:s');
        }
        
        exit;
    }

    /**
     * 组装开单申请
     *
     *@return    void
     **/
    public function assembleApplyAction()
    {
        if ($this->_request->isPost()) {
        	$result = $this->_api->addAssembleApply($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage('申请成功', 'event', 1250, "Gurl()");
        	}
        	else {
        	    Custom_Model_Message::showMessage($this->_api->getError(), 'event', 1250, "failed()");
        	}
        }
        
        $this -> view -> lid = $this -> _lid;
    }

    /**
     * 组装单审核
     *
     * @return void
     */
    public function assembleCheckListAction()
    {
        $page = (int)$this ->_request->getParam('page', 1);
		$params = $this->_request->getParams();
        $params['status'] = 0;
        $this->getAssembleList($page, $params);
    }


    /**
     * 锁定组装单/解锁组装单
     *
     * @return   void
     */
    public function lockAssembleAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$is_lock = (int)$this->_request->getParam('is_lock', 0);
        $ids     = $this->_request->getPost('ids');
    	$this->_api->lockAssemble($ids, $is_lock);
    }

    /**
     * 组装单审核页面
     *
     * @return   void
     */
    public function assembleCheckAction()
    {
        $assemble_id = intval($this->_request->getParam('assemble_id', 0));

        $query_type  = $this->_request->getParam('query_type', '');
        if ($this->_request->isPost()) {
            if (false === $this->_api->checkAssemble($assemble_id, $this->_request->getPost())) {
                Custom_Model_Message::showMessage($this->_api->getError());
            }
            Custom_Model_Message::showMessage('操作成功');
        }
		if ($assemble_id < 1) {
			Custom_Model_Message::showMessage('ID不正确');
		}

		$info = $this->_api->getAssembleInfoById($assemble_id);

        if (false === $info) {
            Custom_Model_Message::showMessage($this->_api->getError());
        }

        if (empty($info)) {
            Custom_Model_Message::showMessage('没有相关数据');
        }

        $assemble_details          = $this->_api->getAssembleDetailsByAssembleId($assemble_id);
        $assemble_finished_details = $this->_api->getAssembleFinishedsByAssembleId($assemble_id);

        $this->view->query_type                = $query_type;
		$this->view->info                      = $info;
        $this->view->assemble_details          = $assemble_details;
        $this->view->assemble_finished_details = $assemble_finished_details;
    }

    /**
     * 组装单查询页面
     *
     * @return   void
     */
    public function assembleListAction()
    {
        $page = (int)$this ->_request->getParam('page', 1);
		$params = $this->_request->getParams();
        $this->getAssembleList($page, $params);
    }
    
    /**
     * 添加产品(ajax调用)
     *
     * @return   void
     */
    public function addProductAction()
    {
        $params = $this ->_request->getParams();
        echo $this -> _api -> addProduct($params);
        
        exit;
    }

    /**
     * 获取列表
     *
     * @return   void
     */
    public function getAssembleList($page, $params)
    {
        if ($params['search'] == 'search') {
            unset($params['lock']);
        }
		$count  = $this->_api->getAssembleCount($params);
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->browseAssemble($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
		$this->view->search_option = $this->_api->getSearchOption(); 
    }
}