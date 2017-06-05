<?php
class Custom_View_Helper_Preload
{
	/**
     * 预载
     *
     * @param    array    $params
     * @return   array
     */
	public function preload($params)
	{
	    foreach ($params as $k => $str) {
	    	switch ($k) {
	    		case 'css':
	    			$r = explode(',', $str);
				    foreach ($r as $css) {
				    	$result['assign']['page_css'] .= "<link href=\"/styles/$css.css\" type=\"text/css\" rel=\"stylesheet\"/>\n";
				    }
	    			break;
	    			
	    		case 'js':
	    			$r = explode(',', $str);
				    foreach ($r as $js) {
				    	$result['assign']['page_js'] .= "<script language=\"javascript\" src=\"/scripts/$js.js\"></script>\n";
				    }
	    			break;
	    	}
	    }
		return $result;
	}
	
}