<?php
class BrandController extends Zend_Controller_Action
{
	/**
     * 品牌 API
     *
     * @var Shop_Models_API_Brand
     */
	private $_api = null;
	/**
     * 产品 API
     *
     * @var Shop_Models_API_Brand
     */
	private $_goods_api = null;
     /**
      * 登陆状态
      */
    private $_auth=null;

	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _auth = Shop_Models_API_Auth :: getInstance();
        $this -> _api = new Shop_Models_API_Brand();
        $this -> _goods_api = new Shop_Models_API_Goods();
	}
	
	/**
	 * 品牌频道页
	 */
	public function indexAction(){
		$this -> view->css_more=',brand.css';
		
	}      

}