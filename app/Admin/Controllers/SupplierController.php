<?php
class Admin_SupplierController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
    
	const ADD_SUCCESS = '添加供货商成功!';
	const EDIT_SUCCESS = '编辑供货商成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Supplier();
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
		
		if ($search['todo'] == 'export') {
		    $datas = $this -> _api -> get($search, '*');
            $content[] = array('供应商名称', '商品编码', '商品名称', '状态');
            foreach ($datas['list'] as $data) {
                $products = $this -> _api -> getProductSupplierData($data['product_ids']);
                if ($products) {
                    foreach ($products as $product) {
                        $content[] = array($data['supplier_name'], $product['product_sn'], $product['product_name'], $product['p_status'] ? '冻结' : '正常');
                    }
                }
                else {
                    $content[] = array($data['supplier_name'], '', '', '');
                }
            }
            
            $xls = new Custom_Model_GenExcel();
            $xls -> addArray($content);
            $xls -> generateXML('supplier');

            exit();
        }
        
        $datas = $this -> _api -> get($search,'*',null,$page, 15);
        foreach ($datas['list'] as $num => $data) {
        	$datas['list'][$num]['add_time'] = ($datas['list'][$num]['add_time'] > 0) ? date('Y-m-d H:i:s', $datas['list'][$num]['add_time']) : '';
        	$datas['list'][$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $datas['list'][$num]['supplier_id'], $datas['list'][$num]['status']);
            $datas['list'][$num]['products'] = $this -> _api -> getProductSupplierData($data['product_ids']);
            $datas['list'][$num]['goods_num'] = $datas['list'][$num]['products'] ? count($datas['list'][$num]['products']) : 0;
        }
        
        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
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
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
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
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'edit';
                $data = array_shift($this -> _api -> getSupplier("supplier_id=$id"));
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 删除动作
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _api -> delete($id);
            if(!$result) {
        	    exit($this -> _api -> error());
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 更改状态动作
     *
     * @return void
     */
    public function statusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _api -> changeStatus($id, $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
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
     * 供应商商品管理
     *
     * @return   void
     */
    public function goodsAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($this -> _request -> isPost()) {
            $result = $this -> _api -> editSupplierProduct($this -> _request -> getPost(), $id);
            if ($result) {
                Custom_Model_Message::showMessage('编辑供应商商品成功', "/admin/supplier/index", 1250);
            }else{
               Custom_Model_Message::showMessage('编辑失败',  "Gurl()" ,1250);
            }
        } else {
            $params = $this -> _request -> getParams();
            $data = array_shift($this -> _api -> getSupplier("supplier_id=$id"));
            $this -> view -> data = $data;
            if ($data['product_ids'])  {
                $params['product_ids'] = $data['product_ids'];
            }
            else {
                $params['product_ids'] = -1;
            }
            $datas = $this -> _api -> getProduct($params, 'product_id,product_sn,product_name,goods_style,goods_units');
            $this -> view -> datas = $datas['list'];
            $this -> view -> params = $params;
        }
    }
}