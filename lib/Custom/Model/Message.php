<?php

class Custom_Model_Message
{
    /**
     * 创建信息提示框
     *
     * @param    string    $msg         信息内容
     * @param    string    $urlForward  转向地址
     * @param    int       $ms          转向时间（毫秒）
     * @param    function  $event       按钮绑定事件
     * @param    boolean   $exit        是否退出
     * @return   $string
     */
    public static function showMessage($msg = '', $urlForward = '', $ms = 1000, $event = '', $exit = true)
    {
    	$msg = ($msg) ? "msg=\\\"" . $msg . "\\\"" : "";
    	$urlForward = ($urlForward) ? ",url=\\\"" . $urlForward . "\\\"" : "";
    	$ms = ($ms) ? ",MS=" . $ms : "";
    	$frame = 'window.top.main_iframe.';
    	if($event){
    	    if (!strstr($event, 'window.top')) {
    	        $event = $frame.$event;
    	    }else{
    	    	$frame = 'window.top.';
    	    }
    	}
    	$event = ($event) ? ",event=\\\"$event\\\"" : "";
        $box = '<script type="text/javaScript">'.$frame.'alertBox.init("' . $msg . $urlForward . $ms . $event . '");</script>';
        die($box);                 
        @header('Content-Type: text/html; charset=utf-8');
        echo $box;
        $exit && exit;
    }
    
    /**
     * 创建信息提示框类型2
     *
     * @param    string    $msg         信息内容
     * @param    boolean   $exit        是否退出
     * @return   $string
     */
    public static function showAlert($msg = '', $exit = true, $urlForward = null , $is_close='0', $is_top = 1)
    {
		if ($urlForward == '-1') {
			$urlForward && $urlForward = 'top.location.href = "' . $urlForward . '"';
				$box = '<script type="text/javaScript">alert("' . $msg . '");history.back(-1);</script>';
				@header('Content-Type: text/html; charset=utf-8');
				echo $box;
				$exit && exit;
		} else if ($is_close =='1') {
			$box = '<script type="text/javaScript">alert("' . $msg . '"); parent.window.close();</script>';
			@header('Content-Type: text/html; charset=utf-8');
			echo $box;
			$exit && exit;
		} else {
			if ($is_top) {
				$urlForward && $urlForward = 'top.location.href = "' . $urlForward . '"';
			} else {
				$urlForward && $urlForward = 'parent.location.href = "' . $urlForward . '"';
			}
			$box = '<script type="text/javaScript">alert("' . $msg . '");' . $urlForward . '</script>';
			@header('Content-Type: text/html; charset=utf-8');
			echo $box;
			$exit && exit;
		}
    }
    /**
     * 创建信息提示框类型2
     *
     * @param    string    $msg         信息内容
     * @param    boolean   $exit        是否退出
     * @return   $string
     */
    public static function showAlert1($msg = '', $action='',$exit = true, $urlForward = null , $is_close='0')
    {
        if ($is_close =='1') {
            $box = '<script type="text/javaScript" src="'.$action.'"></script>';
            $box .= '<script type="text/javaScript">alert("' . $msg . '"); parent.window.close();</script>';
            @header('Content-Type: text/html; charset=utf-8');
            echo $box;
            $exit && exit;
        } else {
            $urlForward && $urlForward = 'top.location.href = "' . $urlForward . '"';
            $box = '<script type="text/javaScript" src="'.$action.'"></script>';
            $box .= '<script type="text/javaScript">alert("' . $msg . '");' . $urlForward . '</script>';
            @header('Content-Type: text/html; charset=utf-8');
            echo $box;
            $exit && exit;
        }
    }
}