<?php

class Admin_Models_API_GiftCard
{
	/**
     * 礼品卡 DB
     * 
     * @var Shop_Models_DB_GiftCard
     */
	private $_db = null;
	public $_cardData = null;
	/**
     * 对象初始化
     * 
     * @return void
     */
	public function __construct()
    {
        $this -> _db = new Admin_Models_DB_GiftCard();
	}
	
    /**
     * 取礼品卡信息
     *
     * @param    int    $sn
     * @return   void
     */
    public function getGiftInfo($where = null)
    {
        $result = $this -> _db -> getGiftInfo($where);
        if ($result) {
            $result['able_price'] = $result['card_real_price'] - $result['occupy_price'];
            return $result;
        }
    }



    /**
     * 礼品卡使用
     *
     * @param    array    $data
     *                    $data['user_id']		 	下单用户ID
     *                    $data['user_name']		下单用户名
     * @return   void
     */
    public function useGiftCard($data)
    {
        return  $this -> _db -> useGift($data);
  
    }

	/**
     * 取得所有礼品卡生成记录
     *
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getAllLog($page = null, $pageSize = null)
	{
		$content = $this -> _db -> getLog(null, $page, $pageSize);
		$total = $this -> _db -> getLogCount();
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得指定ID的礼品卡生成记录
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
     * 取得礼品卡
     *
     * @param    array   $search
     * @param    int     $page
     * @param    int     $pageSize
     * @return   array
     */
	public function getCardlist($search, $page = null, $pageSize = null)
	{
		if ($search) {
			($search['add_time_from']) ? $where = " and add_time >= " . strtotime($search['add_time_from']) : "";
			($search['add_time_end']) ? $where .= " and add_time < " . (strtotime($search['add_time_end'])+86400) : "";
			($search['card_type']) ? $where .= " and card_type=" . $search['card_type'] : "";
			($search['card_price']) ? $where .= " and card_price='" . sprintf('%.2f', $search['card_price']) . "'" : "";
			($search['card_sn']) ? $where .= " and card_sn='" . $search['card_sn'] . "'" : "";
			($search['card_pwd']) ? $where .= " and card_pwd='" . $search['card_pwd'] . "'" : "";
			($search['user_name']) ? $where .= " and user_name LIKE '%" . $search['user_name'] . "%'" : "";
			($search['lid']) ? $where .= " and log_id = " . $search['lid'] : "";
			if ($search['order_batch_goods_id']) {
			    if (is_array($search['order_batch_goods_id'])) {
			        $where .= " and order_batch_goods_id in (".implode(',', $search['order_batch_goods_id']).")";
			    }
			    else {
			        $where .= " and order_batch_goods_id = '{$search['order_batch_goods_id']}'";
			    }
			}
			if ($search['status'] !== '' && $search['status'] !== null) {
			    if (is_array($search['status'])) {
			        $where .= " and status in (".implode(',', $search['status']).")";
			    }
			    else {
			        $where .= " and status = '{$search['status']}'";
			    }
			}
		}
		$content = $this -> _db -> getCard($where, $page, $pageSize);
		$total = $this -> _db -> getCardCount($where);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得礼品卡使用历史
     *
     * @param    array   $search
     * @param    int     $page
     * @param    int     $pageSize
     * @return   array
     */
	public function getUseLog($search, $page = null, $pageSize = null)
	{
		if ($search) {
			($search['add_time_from']) ? $where .= " and t1.add_time >= " . strtotime($search['add_time_from']) : "";
			($search['add_time_end']) ? $where .= " and t1.add_time < " . (strtotime($search['add_time_end'])+86400) : "";
			($search['send_time_from']) ? $where .= " and t3.logistic_time >= " . strtotime($search['send_time_from']) : "";
			($search['send_time_end']) ? $where .= " and t3.logistic_time < " . (strtotime($search['send_time_end'])+86400) : "";
			($search['card_sn']) ? $where .= " and t1.card_sn = '" . $search['card_sn'] . "'" : "";
			($search['card_price']) ? $where .= " and t2.card_price = " . $search['card_price'] . "" : "";
			($search['user_name']) ? $where .= " and t1.user_name LIKE '%" . $search['user_name'] . "%'" : "";
			($search['batch_sn']) ? $where .= " and t1.batch_sn='" . $search['batch_sn'] . "'" : "";
			($search['use_type']) ? $where .= " and t1.use_type='" . $search['use_type'] . "'" : "";
		}
		$content = $this -> _db -> getCardLog($where, $page, $pageSize);
		$total = $this -> _db -> getCardLogCount($where);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 添加礼品卡
     *
     * @param    void
     * @return   string
     */
	public function addLog($data)
	{
		$this -> _cardData = '';
		
		if (!$data['number'] || $data['number'] <= 0) {
			return 'noNumber';
		}
		
		if (!$data['card_price'] || $data['card_price'] <= 0) {
			return 'noPrice';
		}
		$adminCertification = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$data['admin_name'] = $adminCertification['admin_name']?$adminCertification['admin_name']:$data['admin_name'];
		$data['add_time'] = time();
		$position = $this -> _db -> getPosition();
		$newPosition = ($position) ? intval($position)+1 : 1;
		$data['range_from'] = $newPosition;
		$data['range_end'] = $newPosition + $data['number']-1;
		for ($i = $data['range_from']; $i < $data['range_from'] + $data['number']; $i++) {
			$string = $data['card_type'] . date('y') . sprintf('%07d', $i);
			$encode = Custom_Model_Encryption::getInstance() -> encrypt($string, 'giftCard');
			$encode['sn'] = 'g' . $encode['sn'];
			$data['card_sn'][] = $encode;
			$this -> _cardData[] = $encode;
			unset($string, $encode);
		}
		$insertLog = $this -> _db -> addLog($data);
		if ($insertLog) {
			return 'addGiftCardSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 取得礼品卡文件
     *
     * @param    int    $id
     * @return   void
     */
	public function getCardFile($id)
	{
		$cards = $this -> _db -> getUnuseCard(array('log_id' => $id));
		
		if ($cards) {
			$type = array(1 => '出售', 2 => '赠送');
			$cardMsg = array_shift($this -> _db -> getLog(array('log_id' => $id)));
			$cardMsg['parent_id'] && $cardMsg['parent'] = "为UID为" . $cardMsg['parent_id'] . "的用户";
			$card_type_title = $type[$cardMsg['card_type']];
			$content = date('Y-m-d H:i:s', $cardMsg['add_time']) . " 管理员" . $cardMsg['admin_name'] . $cardMsg['parent'] . "生成的面值为" . $cardMsg['card_price'] . "的用于" . $card_type_title . "的未使用的礼品卡".chr(13).chr(10);
			foreach ($cards as $card)
			{
				$content .= strtoupper($card['card_sn']) . '	' . $card['card_pwd'] . chr(13).chr(10);
			}
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=giftCard_" . $id . ".txt;");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".strlen($content));
			echo $content;
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
	
	/**
     * 获得已使用的汇总信息
     *
     * @param    array  $logIDArray
     * @return   array
     */
    public function getUsedSum($logIDArray)
    {
        return $this -> _db -> getUsedSum($logIDArray);
    }
    
    /**
     * 验证礼品卡
     *
     * @param    string     $cardSN
     * @param    string     $batchSN
     * @param    float      $amount
     * @return   string
     */
    public function checkCard($cardSN, $batchSN, $amount = 0)
    {
        if (!$cardSN || !$batchSN)    return 'error';
        
        $orderAPI = new Admin_Models_API_Order();
        $order = @array_shift($orderAPI -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order) {
            return 'errorOrder';
        }
        $detail = $orderAPI -> orderDetail($batchSN);
        if ($detail['other']['price_must_pay'] <= 0) {
            return 'errorNeedPay';
        }
        
        $hasGiftCard = false;
        foreach ($detail['product_all'] as $product) {
            if ($product['product_id'] > 0) {
                if ($product['is_gift_card']) {
                    $hasGiftCard = true;
                    break;
                }
            }
        }
        if ($hasGiftCard) {
            return 'errorHasGiftCard';
        }
        
        $card = $this -> getGiftInfo(" and card_sn = '{$cardSN}'");
        if (!$card) {
            return 'errorCard';
        }
        if ($card['status'] == 1) {
            return 'errorInvalid';
        }
        if ($card['status'] == 2) {
            return 'errorInactive';
        }
        if ($card['user_name'] && $order['user_id'] > 10 && $card['user_name'] != $order['user_name']) {
            return 'errorUserNameError';
        }
        if (strtotime($card['end_date'].' 23:59:59') < time()) {
            return 'errorExpired';
        }
        if ($card['able_price'] <= 0) {
            return 'errorPrice';
        }
        if ($amount) {
            if ($amount > $card['able_price']) {
                return 'errorCardAmount';
            }
            if ($amount > $detail['other']['price_must_pay']) {
                return 'errorPayAmount';
            }
        }
        
        return array('card' => $card, 'order' => $order, 'detail' => $detail);
    }
    
    /**
     * 设置礼品卡可退回标志
     *
     * @param    array   $datas
     * @return   array
     */
	public function setCanReturnCard($datas)
	{
	    if (!$datas)    return false;

	    foreach ($datas as $index => $data) {
	        if ($data['price'] > 0) {
	            if ($info[$data['card_sn']][abs($data['price'])]) {
	                $info[$data['card_sn']][abs($data['price'])]--;
	            }
	            else {
	                $datas[$index]['can_return'] = 1;
	            }
	        }
	        else {
	            $info[$data['card_sn']][abs($data['price'])]++;
	        }
	    }
	    
	    return $datas;
	}
	
	/**
     * 添加日志
     *
     * @param    array   $data
     * @return   void
     */
	public function addCardLog($data)
	{
	    $this -> _db -> addCardLog($data);
	}
	
	/**
     * 更改礼品卡
     *
     * @param    string     $where
     * @param    array      $data
     * @return   void
     */
	public function updateCard($where, $data)
	{
	    $this -> _db -> updateCard($where, $data);
	}
	
	/**
     * 获得礼品卡使用订单
     *
     * @param    string     $cardSN
     * @return   array
     */
    public function getCardOrderInfo($cardSN)
	{
	    $logList = array_shift($this ->  getUseLog(array('card_sn' => $cardSN)));
	    if (!$logList)  return false;
	    
	    foreach ($logList as $log) {
	        if (!$log['batch_sn'])  continue;
	        
	        $cardInfo[$log['batch_sn']] += $log['price'];
	    }
	    if (!$cardInfo)  return false;
	    
	    $orderAPI = new Admin_Models_DB_Order();
	    foreach ($cardInfo as $batchSN => $price) {
	        if ($price > 0) {
	            $order = array_shift($orderAPI -> getOrderBatchInfo(array('batch_sn' => $batchSN)));
	            $order['consume_amount'] = $price;
	            $result[] = $order;
	        }
	    }
	    
	    return $result;
    }
    
    /**
     * 删除礼品卡
     *
     * @param    string  $where
     * @return   void
     */
	public function deleteCard($where)
	{
	    $this -> _db -> deleteCard($where);
	}
	
	/**
     * 占用礼品卡
     *
     * @param    string  $cardSN
     * @param    float   $amount
     * @return   void
     */
	public function occupyCard($cardSN, $amount)
	{
	    
	    
	}
}