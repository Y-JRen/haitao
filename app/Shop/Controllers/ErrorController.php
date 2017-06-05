<?php

class ErrorController extends Zend_Controller_Action
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
             case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:    
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');  
                $content ='您要找的页面不存在';  
                $this->getResponse()->clearBody(); //清除掉别的信息  
                $this->view->content = $content;  //404页面模板的信息 
                break;  

        	case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
        	    $this -> view -> type = '500';
                break;
        	case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
					$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');  
					$content ='您要找的页面不存在';  
					$this->getResponse()->clearBody(); //清除掉别的信息  
					$this->view->content = $content;  //404页面模板的信息 
                	//$this -> _helper -> redirector -> gotoUrl(Zend_Controller_Front::getInstance() -> getBaseUrl());
					//$this->_forward('');
                break;
            default:
                $REQUEST_URI = addslashes(strip_tags($this -> _request -> getServer('REQUEST_URI')));
                substr($REQUEST_URI, 0, 4)!='http' && $REQUEST_URI = 'http://' . array_shift(explode(',', Zend_Registry::get('config') -> domain)) . $REQUEST_URI;
                $url = parse_url($REQUEST_URI);
                $url['query'] && parse_str($url['query']);
                if (strpos($url['path'], '.php') > 0 && $u > 0) {
                	$this -> _helper -> redirector -> gotoUrl(Zend_Controller_Front::getInstance() -> getBaseUrl() . '/?u=' . $u);
                }
                $this -> view -> type = '404';
                break;
        }
        $this->getResponse()->clearBody();
    }

}