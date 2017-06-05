<?php
class AdvertWidget extends SmartyWidget{
	
	public function init()
	{
		$this->_api = new Admin_Models_API_Ad();
		
	}
	public function run()
	{  
		$ad_id = (int)$this->id; //传递的参数
		$adboard =  $this->_api->getAdboardById($ad_id);
		if($adboard && $adboard['status'])
		{			
			$timestamp = time();
			$sqlwhere = "  AND board_id={$ad_id} AND status=1 AND  start_time<$timestamp AND  end_time>$timestamp";
			$adlist = $this->_api->getAdList($sqlwhere ,'*'," ordid asc");		
			$data = array();
			$data['board'] = $adboard;
			$data['ad_id'] = $ad_id;
			$data['stop'] = $this->stop; //定在动画效果
			
			$tpl = 	$adboard['tpl'].'.html'	;
			$imgUrl = Zend_Registry::get('config') -> view -> imgBaseUrl;
			if($adlist){				
			foreach($adlist as $key=> $val){
				if( $val['type'] == 'image')
				{ 
					if(!strstr($val['content'],'http://') )
				   {
					$adlist[$key]['content'] = $imgUrl.$adlist[$key]['content'];
				   }
				   if(!strstr($val['extimg'],'http://') )
				   {
				    	$adlist[$key]['extimg'] = $imgUrl.$adlist[$key]['extimg'];
				   }
				   $extconfig = unserialize($val['extconfig']);
				   foreach ($extconfig as $ck=>$confVal)
				   {
				   	if($confVal['pic'] && !strstr($confVal['pic'],'http://') )
				   	{
				     	$extconfig[$ck]['pic'] = $imgUrl.$confVal['pic'];
				   	}
				   }
				   $adlist[$key]['extconfig'] = $extconfig;
				   
				}else{
					unset($adlist[$key]['extconfig']);
				}
				
			}
			
			$data['adlist'] = $adlist;
			$this->render($tpl,$data);
			}else{
			  echo "<!-- 该广告位下没有可用的广告 -->";
			}
		}else{
			echo "<!-- 广告位不存在或关闭 -->";
		}
	}  
	
}