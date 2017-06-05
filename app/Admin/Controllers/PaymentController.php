<?php

class Admin_PaymentController extends Zend_Controller_Action 
{
	const NO_NAME = '请填写名称!';
	const ADD_SUCCESS = '添加成功!';
    const ADD_FAIL = '添加失败！';
    const EDIT_SUCCESS = '修改成功！';
    const EDIT_FAIL = '修改失败！';
    const DEL_SUCCESS = '删除成功！';
    const DEL_FAIL = '删除失败！';
    
    private $_lid;
    
    public function init() 
	{
        $this->_api = new Admin_Models_API_Payment();
        
        $auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
        $this -> _lid = $auth['lid'];
	}

    /**
     * 添加支付方式表单
     *
     * @return void
     */
    public function addformAction(){
        $dir = './../lib/Custom/Model/Payment';
        $dh = opendir(realpath($dir));
        while (($file = readdir($dh)) !== false) {
            if(strstr($file,'.php')){
                require_once 'Custom/Model/Payment/'.$file;
            }
        }
        $this->view->payments = $payments;
        $this->view->action='add';
    }
    /**
     * 添加支付方式
     *
     * @return void
     */
    public function addAction(){
        $post = $this->_request->getPost();
        $post['payment']['lid'] = $this -> _lid;
        if($this->_api->add($post['payment'],$error)){
            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/payment/list/');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_NAME);
                    break;
                case 'add_fail':
                    Custom_Model_Message::showMessage(self::ADD_FAIL);
                    break;
            }
        }
    }
    public function getpluginAction(){
        if($this->_request->getParam('payment')){
            $class = 'Custom_Model_Payment_' . ucfirst($this->_request->getParam('payment'));
            $payment = new $class();
            print $payment->getFields();
        }else{
            print 'NULL';
        }
        exit;
    }
    /**
     * 支付方式列表
     *
     * @return void
     */
    public function listAction(){
	    $params = $this -> _request -> getParams();
	    $params['lid'] = $this -> _lid;
        $total = $this->_api->getCount($params);
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->get($params,'*',null,$page,15);
        $this->view->data = $data;
        $this->view->param = $params;
        $pageNav = new Custom_Model_PageNav($total, 15);
        $this->view->pageNav=$pageNav->getNavigation();
    }
    /**
     * 更新 列表 中可修改的字段
     *
     * @return void
     */
    public function ajaxupdateAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $id = (int)$this->_request->getParam('id', 0);
        $field = $this->_request->getParam('field', null);
        $val = $this->_request->getParam('val', null);
        if ($id > 0) {
            $result = $this->_api->ajaxupdate($id, $field, $val);
            switch ($result) {
            	case 'forbidden':
            	    Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    /**
     * 删除
     *
     * @return void
     */
    public function delAction(){
        $id=$this->_request->getParam('id', null);
        if($this->_api->del($id,$error)){
            //exit(self::DEL_SUCCESS);
            exit;
        }else{
            switch ($error) {
                case 'delFail':
                    exit(self::DEL_FAIL);
                break;
            }
        }
    }
	
    /**
     * 修改表单
     *
     * @return void
     */
    public function editformAction(){
        $id=$this->_request->getParam('id', null);
        $data = $this->_api->getPaymentByID($id);
        $this->view->payment=$data;
        $this->view->action='edit';
        $class = 'Custom_Model_Payment_' . ucfirst($data['pay_type']);
        $payment = new $class();
        $this->view->config=$payment->getFields(unserialize($data['config']));

    }

    /**
     * 修改
     *
     * @return void
     */
    public function editAction(){
        $post = $this->_request->getPost();
        if($this->_api->edit($post['payment'],$error)){
            Custom_Model_Message::showMessage(self::EDIT_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/payment/list');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_TITLE);
                    break;
            }

        }
    }
    
}

