<?php
class Admin_BrandController extends Zend_Controller_Action {
    /**
     *
     * @var Admin_Models_API_Brand
     */
    private $_api = null;
    const ADD_SUCCESS = '添加品牌成功!';
    const EDIT_SUCCESS = '编辑品牌成功!';
    
    /**
     * 初始化对象
     *
     * @return void
     */
    public function init() {
        $this->_api = new Admin_Models_API_Brand ();
    }
    
    /**
     * 默认动作
     *
     * @return void
     */
    public function indexAction() {
        $page = ( int ) $this->_request->getParam ( 'page', 1 );
        $search = $this->_request->getParams ();
        $datas = $this->_api->get ( null, '1' );
        if ($datas) {
            $total = count ( $datas );
        } else
            $total = 0;
        $datas = $this->_api->get ( $search, 'brand_id,band_sort,brand_name,big_logo,small_logo,as_name,status', null, $page, 20 );
        foreach ( $datas as $num => $data ) {
            $datas [$num] ['add_time'] = ($datas [$num] ['add_time'] > 0) ? date ( 'Y-m-d H:i:s', $datas [$num] ['add_time'] ) : '';
            $datas [$num] ['status'] = $this->_api->ajaxStatus ( $this->getFrontController ()->getBaseUrl () . $this->_helper->url ( 'status' ), $datas [$num] ['brand_id'], $datas [$num] ['status'] );
            $datas [$num] ['brand_goods_num'] = $this->_api->getGoodsBrandNum ( $datas [$num] ['brand_id'] );
        }
        $this->view->datas = $datas;
        $this->view->param = $this->_request->getParams ();
        $pageNav = new Custom_Model_PageNav ( $total, 20, 'ajax_search' );
        $this->view->pageNav = $pageNav->getNavigation ();
        $this->view->opt_yn = array (
                'Y' => '是',
                'N' => '否' 
        );
    }
    
    /**
     * 选择品牌
     *
     * @return void
     */
    public function selAction() {
        $job = $this->_request->getParam ( 'job', null );
        $page = ( int ) $this->_request->getParam ( 'page', 1 );
        $search = $this->_request->getParams ();
        $search['isSel'] = '1';
        if ($job) {
            $search ['filter'] = "";
            $data = $this->_api->get ( $search, 'brand_id,band_sort,brand_name,small_logo,bluk,as_name,status', null, $page );
            $datas = $this->_api->get ($search, '1' );
            if ($datas) {
                $total = count ( $datas );
            } else
                $total = 0;
            
            $this->view->datas = $data;
        }
        $this->view->param = $this->_request->getParams ();
        $pageNav = new Custom_Model_PageNav ( $total, null, 'ajax_search_goods' );
        $this->view->pageNav = $pageNav->getNavigation ();
    }
    
    /**
     * 添加动作
     *
     * @return void
     */
    public function addAction() {
        if ($this->_request->isPost ()) {
            $postdata = $this->_request->getPost ();
            $result = $this->_api->edit ( $postdata );
            if ($result) {
                Custom_Model_Message::showMessage ( self::ADD_SUCCESS, $this->getFrontController ()->getBaseUrl () . '/admin/brand/index' );
            } else {
                Custom_Model_Message::showMessage ( $this->_api->error () );
            }
        } else {
            $this->view->action = 'add';
            $this -> view -> region = $this -> _api -> getRegion();
            $this->render ( 'edit' );
        }
    }
    
    /**
     * 编辑动作
     *
     * @return void
     */
    public function editAction() {
        $id = ( int ) $this->_request->getParam ( 'id', null );
        if ($id > 0) {
            if ($this->_request->isPost ()) {
                $postdata = $this->_request->getPost ();
                $result = $this->_api->edit ( $postdata, $id );
                if ($result) {
                    Custom_Model_Message::showMessage ( self::EDIT_SUCCESS, $this->getFrontController ()->getBaseUrl () . '/admin/brand/index' );
                } else {
                    Custom_Model_Message::showMessage ( $this->_api->error () );
                }
            } else {
                $this->view->action = 'edit';
                $data = array_shift ( $this->_api->get ( "brand_id=$id" ) );
                $data['introduction'] = stripcslashes( $data['introduction'] ); 
                $this->view->data = $data;
                $this -> view -> region = $this -> _api -> getRegion();
            }
        } else {
            Custom_Model_Message::showMessage ( 'error!', 'event', 1250, 'Gurl()' );
        }
    }
    
    /**
     * 删除动作
     *
     * @return void
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        if ($id > 0) {
            $result = $this->_api->delete ( $id );
            if (! $result) {
                exit ( $this->_api->error () );
            }
        } else {
            exit ( 'error!' );
        }
    }
    
    /**
     * 更改状态动作
     *
     * @return void
     */
    public function statusAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $status = ( int ) $this->_request->getParam ( 'status', 0 );
        
        if ($id > 0) {
            $this->_api->changeStatus ( $id, $status );
        } else {
            Custom_Model_Message::showMessage ( 'error!' );
        }
        echo $this->_api->ajaxStatus ( $this->getFrontController ()->getBaseUrl () . $this->_helper->url ( 'status' ), $id, $status );
    }
    
 
    
    /**
     * 切换品牌城状态
     *
     * @return void
     */
    public function toggleIspinpaichengAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $status = ( int ) $this->_request->getParam ( 'status', 0 );
        
        if ($id <= 0) die('failure'); 
        
        $cs = $this->_api->toggleIspinpaicheng($id);
        echo $cs == 0 ? '否' : '是';
        exit;
    }
    
    /**
     * ajax更新数据
     *
     * @return void
     */
    public function ajaxupdateAction() {
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $field = $this->_request->getParam ( 'field', null );
        $val = $this->_request->getParam ( 'val', null );
        if ($id > 0) {
            $this->_api->ajaxUpdate ( $id, $field, $val );
        } else {
            exit ( 'error!' );
        }
        if ($field == 'brand_name') {
            $data = array_shift ( $this->_api->get ( "brand_id = {$id}", 'attr_id' ) );
        }
    }
    /**
     * 品牌产品标签
     */
    public function tagAction() {
        $id = ( int ) $this->_request->getParam ( 'id', null );
        $this->view->data = $this->_api->getGoodsByBrandTag ( $id );
        ;
    }
    /**
     * 修改品牌推荐商品
     */
    public function brandTagAction() {
        $id = ( int ) $this->_request->getParam ( 'id', null );
        $result = $this->_api->updateBrandTag ( $this->_request->getPost (), $id );
        if ($result) {
            Custom_Model_Message::showMessage ( self::EDIT_SUCCESS, $this->getFrontController ()->getBaseUrl () . '/admin/brand/index' );
        } else {
            Custom_Model_Message::showMessage ( $this->_api->error () );
        }
    }
    /**
     * 归属地管理
     */
    public function brandRegionAction()
    {
    	$pageSize = 20;
    	
    	$param = $this -> _request -> getParams();
    	$this-> view ->param = $param;
    	
    	$this->view->region = $this->_api->getRegion($param['region_name'],$param['page']?$param['page']:1,$pageSize);
    	$total = $this -> _api -> _regionTotal;
    	$pageNav = new Custom_Model_PageNav ( $total, $pageSize, 'ajax_search' );
    	$this->view->pageNav = $pageNav->getNavigation ();
    }
    
    /**
     * 归属地编辑
     */
    public function regionEditAction()
    {
    	if($this -> _request -> isPost()){
    		$id = $id = (int)$this -> _request -> getParam('id',null);
    		$post = $this -> _request -> getPost();
    		$result = $this -> _api -> editRegion ( $post, $id );
    		if($result){
    			Custom_Model_Message::showMessage ( "成功", $this->getFrontController ()->getBaseUrl () . '/admin/brand/brand-region' );
    		}else{
    			Custom_Model_Message::showMessage ( "失败" );
    		}
    	}else{
    		$id = (int)$this -> _request -> getParam('id',null);
    		if($id){
    			$this -> view -> action = edit;
    			$data =$this->_api->getRegionOne($id);
    			$this -> view -> data = $data[0]; 
    		}else{
    			$this -> view -> action = add;
    		}
    	}
    }
   
}
