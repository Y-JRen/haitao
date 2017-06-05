<?php
class Admin_LoadscriptController extends Zend_Controller_Action
{
	/**
     * 读取脚本文件并输出内容（可合并多个）
     *
     * @return void
     */
	public function indexAction()
	{
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$this -> _helper -> viewRenderer -> setNoRender();
		$files = $this -> _request -> getParam('file', null);
		
		if ($files) {
			$fileArray = explode(',', $files);
		}
		foreach($fileArray as $value) 
		{
		    $content .= file_get_contents($_SERVER['DOCUMENT_ROOT'] . trim($value)) . "\n";
		}
		
		header("content-type: application/x-javascript");
		echo $content;
	}
}