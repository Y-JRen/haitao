<?php
class Custom_Model_Sms
{
	public function __construct() 
	{
	    set_time_limit(0);
	}
	
	/**
	 * 发送
	 * @param String(Array) $tos
	 * @param String $msg
	 */
	public function send($tos,$msg,$type = 1)
	{
		header("Content-type:text/html;charset=utf-8");
		$msg = $msg.'【国人海淘网】';
        if ($type == 1) {
        	$client=new SoapClient("http://121.199.48.186:1210/Services/MsgSend.asmx?WSDL");
        	$param = array(
        			"userCode" => "gydzsw",
        			"userPass" => "gydzsw2014",
        			"DesNo"    => $tos,
        			"Msg"      => $msg,
        			"Channel"  => "0");
        	$p = $client->__soapCall('SendMsg',array('parameters' => $param));
        }
        else {	
        	
    	    $client=new SoapClient("http://yes.itissm.com/api/MsgSend.asmx?WSDL");
    	    $param = array(
    	    		"userCode" => "shgydz",
    	    		"userPass" => "Shgydz6789",
    	    		"DesNo"	   => $tos,
    	    		"Msg"      => $msg,
    	    		"Channel"  => "37");
    	    $p = $client->__soapCall('sendMes',array('parameters' => $param));    
    	}
    	if($p->sendMesResult < 0) {
    		return false;
    	}
	    return true;
	}
}