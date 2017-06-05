<?php

class Admin_ErrorController extends Zend_Controller_Action
{
	/**
     * 错误处理
     *
     * @return void
     */
    public function errorAction()
    {
        $errors = $this ->_getParam('error_handler');
        switch ($errors -> type) {
        	case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
        	    $this -> view -> type = '500';
                break;
            default:                
                $this -> view -> type = '404';
                break;
        }
        $this->getResponse()->clearBody();
    }
}