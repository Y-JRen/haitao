<?php

 
/**
 * @see Smarty
 */
require_once "Smarty/Smarty.class.php";
 

class Custom_View_SmartyAdvanced extends Smarty
{
	/**
     * 占位符
     * @var    array
     */
	private $_placeHolders = array();
	
	/**
     * Zend_View
     * @var    object
     */
	private $_zendView = null;

    /**
     * 对象初始化
     *
     * @param    void
     * @param    void
     */
	public function __construct()
	{
		parent::__construct();
		$this -> compiler_class = 'Custom_View_SmartyAdvancedCompiler';
	}

    /**
     * get Zend_View object.
     *
     * @param    Zend_View_Interface    $view
     * @return   void
     */
	public function setZendView(Zend_View_Interface $view)
	{
		$this -> _zendView = $view;
	}

    /**
     * call Zend_View_Helper.
     *
     * @param    string    $name
     * @param    array     $args
     * @return   mixed
     */
	public function callViewHelper($name,$args)
	{
		$helper = $this -> _zendView -> getHelper($name);
		return call_user_func_array(array($helper, $name), $args);
	}

    /**
     * 取得占位符
     *
     * @param    string    $name
     * @return   string
     */
	public function getPlaceHolder($name)
	{
		if(isset($this -> _placeHolders[$name]))
			return $this -> _placeHolders[$name];
		else
			return '';
	}

    /**
     * 创建占位符
     *
     * @param    string    $name
     * @param    string    $content
     * @return   void
     */
	public function createPlaceHolder($name,$content)
	{
		$this -> _placeHolders[$name] = $content;
	}

}
?>