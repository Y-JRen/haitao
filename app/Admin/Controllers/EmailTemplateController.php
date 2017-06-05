<?php
class Admin_EmailTemplateController extends Zend_Controller_Action 
{
	/**
     * 邮件模板 API
     * 
     * @var Admin_Models_API_EmailTemplate
     */
    private $_template = null;
    
    /**
     * 邮件类型
     * 
     * @var array
     */
    private $_templateType = array('0' => 'HTML邮件', '1' => '纯文本邮件');
    
    /**
     * 添加邮件模板成功
     */
	const ADD_TEMPLATE_SUCESS = '添加邮件模板成功!';
	
	/**
     * 邮件模板名称未填写
     */
	const NO_TEMPLATE_NAME = '请填写邮件模板名称!';
	
	/**
     * 邮件主题未填写
     */
	const NO_TEMPLATE_TITLE = '请填写邮件主题!';
	
	/**
     * 邮件模板名称已存在
     */
	const TEMPLATE_EXISTS = '该邮件模板名称已存在!';
	
	/**
     * 编辑邮件模板成功
     */
	const EDIT_TEMPLATE_SUCESS = '编辑邮件模板成功!';
	
	/**
     * 邮件模板不存在
     */
	const TEMPLATE_NO_EXISTS = '该邮件模板不存在!';
    
    /**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _template = new Admin_Models_API_EmailTemplate();
	}
	/**
     * 邮件模板列表
     *
     * @return void
     */
    public function indexAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
        $templateMessages = $this -> _template -> getAllTemplate($page);
        
        if ($templateMessages) {
        	foreach ($templateMessages as $key => $templateMessage)
        	{
        		$templateMessages[$key]['type'] = $this -> _templateType[$templateMessage['type']];
        	}
        }
        
        $total = $this -> _template -> getAllTemplateCount();
        
        $this -> view -> templateList = $templateMessages;
        
        $pageNav = new Custom_Model_PageNav($total);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    /**
     * 添加邮件模板
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _template -> editTemplate($this -> _request -> getPost());
        	switch ($result) {
        		case 'addTemplateSucess':
        		    Custom_Model_Message::showMessage(self::ADD_TEMPLATE_SUCESS, 'event', 1250, 'Gurl()');
        		    break;
        		case 'noTemplateName':
        		    Custom_Model_Message::showMessage(self::NO_TEMPLATE_NAME);
        		    break;
        		case 'noTemplateTitle':
        		    Custom_Model_Message::showMessage(self::NO_TEMPLATE_TITLE);
        		    break;
        		case 'templateExists':
        		    Custom_Model_Message::showMessage(self::TEMPLATE_EXISTS);
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, "Gurl()");
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> view -> title = '添加邮件模板';
        	$this -> view -> typeOptions = $this -> _templateType;
        	$this -> render('edit');
        }
    }
    
    /**
     * 编辑邮件模板
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
            	$this -> _helper -> viewRenderer -> setNoRender();
                $result = $this -> _template -> editTemplate($this -> _request -> getPost(), $id);
                switch ($result) {
                	case 'editTemplateSucess':
        		        Custom_Model_Message::showMessage(self::EDIT_TEMPLATE_SUCESS, 'event', 1250, 'Gurl()');
        		        break;
        		    case 'templateExists':
        		        Custom_Model_Message::showMessage(self::TEMPLATE_EXISTS);
        		        break;
        		    case 'templateNoExists':
        		        Custom_Model_Message::showMessage(self::TEMPLATE_NO_EXISTS);
        		        break;
        		    case 'templateExists':
        		        Custom_Model_Message::showMessage(self::TEMPLATE_EXISTS);
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        	    }
            } else {
                $this -> view -> action = 'edit';
                $this -> view -> title = '修改邮件模板';
                $template = $this -> _template -> getTemplateById($id);
                $this -> view -> typeOptions = $this -> _templateType;
                $this -> view -> template = $template;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    /**
     * 检查邮件模板是否已经存在
     *
     * @return void
     */
    public function checkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $name = $this -> _request -> getParam('val', null);
        
        if(!empty($name)) {
	        $result = $this -> _template -> getTemplateByName($name);
	        
	        if (!empty($result)) {
	        	exit(self::TEMPLATE_EXISTS);
	        }
        }
    }
    
    /**
     * 删除邮件模板
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _template -> deleteTemplateById($id);
            switch ($result) {
            	case 'deleteSucess':
        		    break;
        		case 'error':
        		    exit('error!');
            }
        } else {
            exit('error!');
        }
    }
}