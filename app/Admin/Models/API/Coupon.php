<?php
class Admin_Models_API_Coupon
{
	/**
     * 卡 DB
     * 
     * @var Shop_Models_DB_Card
     */
	private $_db = null;
	
	/**
     * 生成礼券记录文件目录
     * 
     * @var string
     */
	private $_couponFile = 'data/admin/coupon';
	
	/**
     * 对象初始化
     * 
     * @return void
     */
	public function __construct()
    {
        $this -> _db = new Admin_Models_DB_Coupon();
	}
	
	/**
     * 取得所有礼券生成记录
     *
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getAllLog($page = null, $pageSize = null, $where = null)
	{
		if (($where['card_type'] !==null)&&($where['card_type'] !=='')) {
		    $arr['card_type'] = $where['card_type'];
		}
		if (($where['status'] !== null)&&($where['status'] !== '')) {
		    $arr['status'] = $where['status'];
		}
		$arr['is_system'] = 0;
		$content = $this -> _db -> getLog($arr, $page, $pageSize);
		$total = $this -> _db -> getLogCount($arr);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得指定ID的礼券生成记录
     *
     * @param    int    $id
     * @return   array
     */
	public function getLogById($id)
	{
		if ($id) {
			return @array_shift($this -> _db -> getLog(array('log_id' => intval($id))));
		}
	}
	
	/**
     * 取得已使用礼券
     *
     * @param    array   $search
     * @param    int     $page
     * @param    int     $pageSize
     * @return   array
     */
	public function getHistory($search, $page = null, $pageSize = null)
	{
		if (is_array($search)) {
			($search['add_time_from']) ? $where = " and t1.add_time >= " . strtotime($search['add_time_from']) : "";
			($search['add_time_end']) ? $where .= " and t1.add_time < " . (strtotime($search['add_time_end'])+86400) : "";
			($search['card_type']) ? $where .= " and card_type=" . $search['card_type'] : "";
			($search['card_price']) ? $where .= " and card_price='" . sprintf('%.2f', $search['card_price']) . "'" : "";
			($search['card_sn']) ? $where .= " and card_sn='" . $search['card_sn'] . "'" : "";
			($search['user_name']) ? $where .= " and user_name LIKE '%" . $search['user_name'] . "%'" : "";
			($search['lid']) ? $where = " and t1.log_id = " . $search['lid'] : "";
		}
		else {
		    $where = $search;
		}
		$content = $this -> _db -> getHistory($where, $page, $pageSize);
		$total = $this -> _db -> getHistoryCount($where);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 添加礼券
     *
     * @param    void
     * @return   string
     */
	public function addLog($data)
	{
		if (!$data['number'] || $data['number'] <= 0) {
			return 'noNumber';
		}
		if ( $data['card_type'] == 3 || $data['card_type'] == 5) {
		    $data['card_price'] = $data['card_price1'] ? $data['card_price1'] : 0;
		    $goods_arr = array();
		    for ( $i = 0; $i <= $data['GoodsNum']; $i++ ) {
		        $idvar = 'goods_id'.$i;
		        if ( $data[$idvar] ) {
		            $numvar = 'goods_num'.$i;
		            $goods_arr[$data[$idvar]] = $data[$numvar];
		        }
		    }
		    $data['goods_info'] = serialize($goods_arr);
		}
		else {
		    if (!$data['card_price'] || $data['card_price'] <= 0) {
			    return 'noPrice';
		    }
		    if ( $data['card_type'] == 0 || $data['card_type'] == 1 || $data['card_type'] == 4) {
		        $data['goods_info'] = serialize($data['goods_info']);
		    }
		}
        $data['parent_id'] = intval($data['parent_id']);
        if (isset($data['is_affiliate']) && !$data['parent_id']) {
            return 'isAffNoPid';
        }
		$adminCertification = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$data['admin_name'] = $adminCertification['admin_name'];
		$data['add_time'] = time();
		$position = $this -> _db -> getPosition();
		$newPosition = ($position) ? intval($position) +1 : 1;
		$data['range_from'] = $newPosition;
		$data['range_end'] = $newPosition + $data['number']-1;
		$insertId = $this -> _db -> addLog($data);
		
		if ($insertId) {
			$handle = fopen(Zend_Registry::get('systemRoot') . '/' . $this -> _couponFile . '/Coupon_' 
                            . $data['range_from'] . '_' . $data['range_end'] . '_' . $data['number'] . '.txt', 'w');
			$data['parent_id'] && $data['parent'] = "为UID为" . $data['parent_id'] . "的用户";
			$repeat = ($data['is_repeat'] == 1) ? '可重复使用' : '不可重复使用';
            $repeat .= isset($data['is_affiliate']) && $data['is_affiliate'] == 1 ? '按券分成的' : '';
			fwrite($handle, date('Y-m-d H:i:s', $data['add_time']) . " 管理员" . $data['admin_name'] . $data['parent'] 
                    . "生成范围从" . $data['range_from'] . "到" . $data['range_end'] . "共" . $data['number'] . "张面值为" 
                    . $data['card_price'] . "的" . $data['card_type_title'] . $repeat ."礼券".chr(13).chr(10));
            
            if ($data['card_type'] == 3) {
                $goods_info = unserialize($data['goods_info']);
                $goods_ids = array();
                $goods_api = new Admin_Models_API_Goods();
                foreach ($goods_info as $key => $value) {
                    $goods_ids[] = "'{$key}'";
                }
                $goods_data = $goods_api -> get('goods_sn in ('.implode(',',$goods_ids).')', 'goods_sn,goods_name');
                if ( $goods_data ) {
                    for ($i = 0; $i < count($goods_data); $i++) {
                        $goods_name[$goods_data[$i]['goods_sn']] = $goods_data[$i]['goods_name'];
                    }
                    reset($goods_info);
                    $goods_line = '';
                    foreach ($goods_info as $key => $value) {
                        $goods_line .= $goods_name[$key].'=>'.$value.' ';
                    }
                    if ( $goods_line ) {
                        fwrite($handle, $goods_line.chr(13).chr(10));
                    }
                }
                fwrite($handle, "订单最低价格为{$data['min_amount']}".chr(13).chr(10));
            }
			for ($i = $data['range_from']; $i < $data['range_from'] + $data['number']; $i++)
			{
				$encode = Custom_Model_Encryption::getInstance() -> encrypt($i, 'coupon');
				fwrite($handle, strtoupper($encode['sn']) . "	" . $encode['pwd'] . chr(13).chr(10));
				
			}
			fclose($handle);
			return 'addCouponSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 添加单一系统礼券
     *
     * @param    void
     * @return   string
     */
	public function addSysCoupon($data)
	{
		$data['card_type'] = 1;
		$data['is_repeat'] = 0;
		$data['card_price'] = 20;
		$data['min_amount'] = 150;
		$position = $this -> _db -> getPosition();
		$newPosition = ($position) ? intval($position) +1 : 1;
		$data['range_from'] = $data['range_end'] = $newPosition;
		$data['number'] = 1;
		$data['admin_name'] = 'STSTEM';
		$data['add_time'] = time();
	    $data['start_date'] = date('Y-m-d', mktime(0, 0, 0, date('m')+2, 0, date('Y')));
		$data['end_date'] = date('Y-m-d', mktime(0, 0, 0, date('m')+2, 0, date('Y')));
		$encode = Custom_Model_Encryption::getInstance() -> encrypt($newPosition, 'coupon');
		$data['note'] = '系统发放';
		$data['is_system'] = 1;
		$insertId = $this -> _db -> addLog($data);
		$data['log_id'] = $insertId;
		$data['card_sn'] = $encode['sn'];
		$data['card_pwd'] = $encode['pwd'];
		$data['user_id'] = $data['user_id'];
		$data['user_name'] = $data['user_name'];
		
		if ($this -> _db -> addSysCoupon($data)) {
		    return 'addCouponSucess';
		} else {
		    return 'error';
		}
	}
	
	/**
     * 取得礼券文件
     *
     * @param    int    $id
     * @return   void
     */
	public function getCouponFile($id)
	{
		$log = array_shift($this -> _db -> getLog(array('log_id' => $id)));
		$fileName = Zend_Registry::get('systemRoot') . '/' . $this -> _couponFile . '/' . '/Coupon_' . $log['range_from'] . '_' . $log['range_end'] . '_' . $log['number'] . '.txt';
		
		if (file_exists($fileName)) {
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=".basename($fileName).";");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($fileName));
			@readfile($fileName);
		} else {
			echo 'error';
		}
	}
	
	/**
     * 取得礼券状态显示代码
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为无效"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u><font color=red>无效</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
    /**
     * 更新礼券发放记录状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeLogStatus($id, $status)
	{
		$id = (int)$id;
		if ($id > 0) {
		    if($this -> _db -> updateLogStatus($id, $status) <= 0) {
			    exit('failure');
		    }
		}
	}
	/*绑定优惠券操作coupon*/
	public function addCoupon($data){
	   return $this->_db->addSysCoupon($data);
	}
}