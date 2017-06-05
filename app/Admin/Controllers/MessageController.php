<?php
class Admin_MessageController extends Zend_Controller_Action
{

	/**
     * 对象初始化
     *
     * @return   void
     */
	
	private $_page_size = '10';

	public function init()
	{
		$this ->_message = new Admin_Models_API_Message();
	}

    /**
     * 信息列表
     *
     * @return void
     */
    public function indexAction()
    {
        $page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
        $count  = $this->_message->getCount($params);
		
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_message->browse($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
		$this->view->search_option = $this->_message->getSearchOption();

        
    }

	/**
     * 添加站内消息
     *
     * @return void
     */
	public function addAction()
	{
		if ($this->_request->isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$params = $this->_request->getPost();
			if (false === $this->_message->sendMessage($params)) {
				Custom_Model_Message::showMessage($this->_message->getError());
			}

			Custom_Model_Message::showMessage('发送成功', 'event', 1250, 'Gurl()' );
		}
		$this->view->search_option = $this->_message->getSearchOption();
	}

	/**
     * 查看站内消息
     *
     * @return void
     */
	public function viewAction()
	{
		$message_id = intval($this->_request->getParam('message_id', 0));
		if ($message_id < 1) {
			Custom_Model_Message::showMessage('消息ID不正确');
		}

		$info = $this->_message->get($message_id);

		$this->view->info = $info;
	}
}