<?php
class Custom_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract
{
	/**
     * 布局文件
     * 
     * @var    array
     */
	protected $_layoutFileName = array('header', 'footer');
	
    /**
     * 布局处理
     *
     * @param    void
     * @return   void
     */
	public function dispatchLoopShutdown()
    {
    	if (!Zend_Registry::get('config') -> view -> layout) {
    		return;
    	}
    	$view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer') -> view;
    	$layoutPath = '';
    	$layoutHeader = $this -> _layoutFileName[0];
    	$layoutFooter = $this -> _layoutFileName[1];
        $this -> getResponse() -> prepend('header', $view -> render($layoutPath . $layoutHeader . '.' . Zend_Registry::get('config') -> view -> suffix));
        $this -> getResponse() -> append('footer', $view -> render($layoutPath . $layoutFooter . '.' . Zend_Registry::get('config') -> view -> suffix));
    }
}