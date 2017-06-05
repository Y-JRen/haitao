<?php

class HelpController extends Zend_Controller_Action
{
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
        $this -> _pageAPI = new Shop_Models_API_Page();
		$this->view->cur_position = 'help';
	}

	/**
     * 帮助中心
     *
     * @return void
     */
	public function indexAction()
	{
		$this -> view -> menu = $this -> _pageAPI -> getListCat("parent_id =1");
		$id = (int)$this -> _request -> getParam('id', 19);
		$info = array_shift($this -> _pageAPI -> getInfo("article_id='$id'"));
		!$info && exit('error');
		$this->view->css_more = ',page.css';
        $this -> view -> page_title = '帮助中心-'.$info['title'].'-国人海淘网'; 
        $this -> view -> page_keyword = "关于征税计征的重要通知"; 
        $this -> view -> page_description = '关于征税计征的重要通知'; 
		$this -> view -> ur_here = '<li><a title="首页" href="/"><strong>首页</strong></a> &gt;&gt;</li><li><a title="帮助中心" href="/help/">帮助中心</a> &gt;&gt; </li><li>'. $info['title'].' </li>';
		$this -> view -> info = $info;
	}

	/**
     * 帮助中心列表
     *
     * @return void
     */
	public function listAction()
	{
		$this -> view -> menu = $this -> _pageAPI -> getListCat("parent_id =1");
		$id = (int)$this -> _request -> getParam('id', 19);
		$info = array_shift($this -> _pageAPI -> getInfo("article_id='$id'"));
		!$info && exit('error');
		$this->view->css_more = ',page.css';
        $this -> view -> page_title = '帮助中心-'.$info['title'].'-国人海淘网'; 
        $this -> view -> page_keyword = $info['title'].',国人海淘网'; 
        $this -> view -> page_description = '什么是海淘，海淘购物、支付、发货等相关流程及帮助说明'; 
		$this -> view -> ur_here = '<li><a title="首页" href="/"><strong>首页</strong></a> &gt;&gt;</li><li><a title="帮助中心" href="/help/">帮助中心</a> &gt;&gt; </li><li>'. $info['title'].' </li>';
		$this -> view -> info = $info;
	}
}