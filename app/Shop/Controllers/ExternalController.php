<?php
class ExternalController extends Zend_Controller_Action
{

	/**
	 * 快境捅 订单申报 返回
	 */
	public function applyResultAction()
	{
		$json = urldecode($_REQUEST['EData']);
		$kjt_api = new Admin_Models_API_Kjt();
		$bool = $kjt_api -> order_response($json);
		if($bool == TRUE){
			echo 'SUCCESS';
		}else{
			echo 'FAILURE';
		}
		exit;
	}
	
	function log($content)
	{
		$db = Zend_Registry::get('db');
		$array = array(
				'content'=>$content,
				'add_time'=>time()
		);
		$db -> insert('log',$array);
	}
	
	
	public function kuaidiAction()
	{
	    $param = $this -> _request -> getParam('param');
	    $param = Zend_Json::decode(trim(str_replace('\"', '"', $param)));
	
	    $state = $param['lastResult']['state'];
	    $logistic_no = $param['lastResult']['nu'];
	
	    if (!$logistic_no) {
	        echo '{"result":"false","returnCode":"500","message":"没有运单号"}';
	        exit;
	    }
	
	    $logisticAPI = new Admin_Models_DB_Logistic();
	    $logisticAPI -> addKuaidi100Track(array('logistic_no' => $logistic_no, 'content' => serialize($param)));
	
	    $row = array('logistic_no' => $logistic_no,
	            'status' => $param['status'],
	            'state' => $state,
	            'message' => $param['message'],
	            'last_poll_time' => time(),
	    );
	    $logisticAPI -> addKuaidi100($row);
	
	    if ($state == 4) {
	        $state = 6;
	    }
	    //签收有退回标志的，设为拒收
	    if ($state == 3) {
	        if (strpos($param['lastResult']['data'][0]['context'], '退回') !== false) {
	            //$state = 6;
	        }
	    }
	    if (in_array($state, array(3,6))) {
	        $transportAPI = new Admin_Models_API_Transport();
	        $transport = array_shift($transportAPI -> get("logistic_no = '{$logistic_no}'"));
	        if ($transport && !in_array($transport['logistic_status'], array(2,3))) {
	            if ($state == 3) {
	                $transport['logistic_status'] = 2;
	            }
	            else {
	                $transport['logistic_status'] = 3;
	            }
	            $transport['auto_track'] = 1;
	            $transport['op_time'] = time();
	            $transport['admin_name'] = 'system';
	            $transport['remark'] = '快递100推送';
	            $transportAPI -> track($transport);
	        }
	    }
	
	    echo '{"result":"true","returnCode":"200","message":"成功"}';
	    exit;
	}
}