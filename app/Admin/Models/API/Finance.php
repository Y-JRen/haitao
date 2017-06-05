<?php
class Admin_Models_API_Finance 
{
	private $_db = null;

	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Finance();
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	/**
     * 取得指定条件的支付方式
     *
     * @param   array   $where
     * @return  array
     */
    public function getPayment($where = null)
    {
        $payment = $this -> _db -> getPayment($where);
        if (is_array($payment) && count($payment)) {
            foreach ($payment as $k => $v) {
                $v['config'] = unserialize($v['config']);
                $data[$v['pay_type']] = $v;
            }
        }
        return $data;
    }

	/**
     * 添加财务退款申请
     *
     * @param   array     $data
     * @return  int
     */
    public function applyFinance($data)
    {
        $order = $this -> _db -> getOrderByOrderSN($data['batch_sn'], $data['shop_id']);
        if (!$order || $order['lid'] != $data['lid']) {
            Custom_Model_Message::showMessage("订单{$data['batch_sn']}不存在!", $url, 1250);
        }
        if ($order['price_order'] < $data['pay']) {
            //Custom_Model_Message::showMessage("应退款不能大于订单金额!", $url, 1250);
        }
        
        $bank = $data['bank'];
        $data = array('shop_id' => $data['shop_id'] ? $data['shop_id'] : 0,
              'type' => $data['type'] ? $data['type'] : 1,
              'way' => $data['way'] ? $data['way'] : 1,
              'item' => 2,
              'item_no' => $data['batch_sn'],
              'pay' => -$data['pay'],
              'logistic' =>'',
              'point' => '',
              'gift' => '',
              'status' => 1,
              'bank_type' => $data['bank']['type'] ? $data['bank']['type'] : 1,
              'bank_data' => serialize($data['bank']),
              'order_data' => '',
              'note' => $bank['note'],
              'callback' => '',
              'add_time' => $data['add_time'] ? $data['add_time'] : time());
        
        $financeID = $this -> _db -> addFrinance($data);
        
        //添加财务产品退款
        $orderDB = new Admin_Models_DB_Order();
        $orderAPI = new Admin_Models_API_Order();
        $productList = $orderDB -> getOrderBatchGoods(array('batch_sn' => $order['batch_sn'], 'product_id>' => 0, 'number>' => 0));
        foreach ($productList as $product) {
            $returnInfo[$product['product_id']]['number'] = $product['number'];
        }
        $finance = array('item_no' => $batchSN,
                         'pay' => abs($data['pay']),
                         'finance_id' => $financeID,
                         );
        $orderAPI -> createFinanceReturnProduct($finance, $returnInfo);
        
        return $financeID;
    }

	/**
     * 添加一条财务申请
     *
     * @param   array     $data
     * @return  int
     */
    public function addFrinance($data)
    {
        return $this -> _db -> addFrinance($data);
    }
	/**
     * 取得指定条件的财务列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getFinanceWithPage($where=NULL, $page=null)
    {
        $data = $this -> _db -> getFinanceWithPage($where, $page);
        if ($data['data']) {
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $data['data'][$k]['check_time'] = $data['data'][$k]['check_time'] ? date('Y-m-d H:i:s', $v['check_time']) : '';
                $orderSNArray[] = "'{$v['item_no']}'";
            }
            $externalOrderSNInfo = $this -> _db -> getExternalOrderSN($orderSNArray);
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['external_order_sn'] = $externalOrderSNInfo[$v['item_no']];
            }
            
        }
        
        return $data;
    }
	/**
     * 取得指定条件的财务信息
     *
     * @param   array   $where
     * @return  array
     */
    public function getFinance($where=NULL)
    {
        $data = $this -> _db -> getFinance($where);
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['add_time_label'] = date('Y-m-d H:i:s', $v['add_time']);
                //0:待收货 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效 5系统设置无效[财务不可见]
                if ($v['status'] == 0) {
                    $label = '待收货';
                } else if ($v['status'] == 1) {
                    $label = '财务未审核';
                } else if ($v['status'] == 2) {
                    $label = '财务已审核';
                } else if ($v['status'] == 3) {
                    $label = '财务设置无效';
                } else if ($v['status'] == 4) {
                    $label = '系统设置无效';
                }
                $data[$k]['status_label'] = $label;
                $data[$k]['pay_label'] = abs($v['pay']);
                $data[$k]['point_label'] = abs($v['point']);
                $data[$k]['account_label'] = abs($v['account']);
                $data[$k]['gift_label'] = abs($v['gift']);
            }
        }
        return $data;
    }
	/**
     * 更新财务状态
     *
     * @param   array   $where
     * @param   int     $data
     * @return  void
     */
    public function updateFinance($financeID, $data)
    {
        //0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效 5系统设置无效[财务不可见]
        $finance = @array_shift($this -> _db -> getFinance(array('finance_id' => $financeID)));
        if ($finance['status'] == 1 && ($data['status'] == 2 || $data['status'] == 3)) {//财务未审核 进行2,3操作
            $this -> _db -> updateFinance($financeID, $data);
            if ($data['status'] == 2) {//支付账款
                $orderDb = new Admin_Models_DB_Order();
                $order = array_shift($orderDb -> getOrderBatch(array('batch_sn' => $finance['item_no'])));
                $memberApi = new Admin_Models_API_Member();
                $member = $memberApi -> getMemberByUserName($order['user_name']);
                if ($finance['callback']) {
                    $orderApi = new Admin_Models_API_Order();
                    $orderApi -> toFinance($finance['item_no'], 
                                           $finance['callback'], 
                                           array('pay' => $finance['pay'] + $finance['logistic'],
                                                 'gift' => $finance['gift'],
                                                 'point' => $finance['point'],
                                                 'account' => $finance['account']));
                }
                else {
                    $orderAPI = new Admin_Models_API_Order();
                    $orderAPI -> updateOrderAmountByFinanceReturn($order, abs($finance['pay']), abs($finance['point']), abs($finance['account']), abs($finance['gift']));
                }
            }

        } else if ($finance['status'] == 1 && $data['status'] == 4) {//财务未审核 进行4 操作
            $this -> _db -> updateFinance($financeID, $data);
        } else if ($finance['status'] == 0 && ($data['status'] == 1 || $data['status'] == 4 || $data['status'] == 5)) {//未通过其他部门审核 进行1,4，5操作
            $this -> _db -> updateFinance($financeID, $data);
        } else if ($finance['status'] == 2 && in_array($finance['way'], array(3,5))) {//系统退款修改审核日期
            $this -> _db -> updateFinance($financeID, $data);
        }
    }

	/**
     * 取最后增加的财务记录
     *
     * @param   int         $item
     * @param   string      $batchSN
     * @param   int         $includeWay3
     * @return  void
     */
    public function getLastFinanceByItemNO($item, $batchSN, $includeWay35 = false)
    {
        return $this -> _db -> getLastFinanceByItemNO($item, $batchSN, $includeWay35);
    }
	/**
     * 删除
     *
     * @param   int     $financeID
     * @return  void
     */
    public function delFinance($financeID)
    {
        $finance = array_shift($this -> _db -> getFinance(array('finance_id' => $financeID)));
        if ($finance['status'] == 3) {
            return $this -> _db -> delFinance($financeID);
        } else {
            return false;
        }
    }
    /**
     * 返回订单各个状态标签
     *
     * @param   string   $type
     * @param   int     $id
     * @return  string
     */
    public function status($type, $id)
    {
        $status = array(0 => '有效单', 1 => '取消单', 2 => '无效单');
        $status_return = array(0 => '正常单', 1 => '退货单');
        $status_logistic = array(0 => '未确认', 1 => '待收款', 2 => '待发货', 3 => '已发货', 4 => '客户已签收', 5 => '拒收');
        $status_pay = array(0 => '未收款', 1 => '未退款', 2 => '已结清');
        $tmp = $$type;
        return $tmp[$id];
    }
  
	/**
     * 订单结款
     *
     * @param    array    $data
     * @return   void
     */
	public function clear($data)
	{
		if($data['ids']) {
			$add_time = time ();
			$ids = implode(',', $data['ids']);
			$fromdate = strtotime($data['fromdate']);
			$todate = strtotime($data['todate']);
			$row = array('clear_no' => Custom_Model_CreateSn::createSn(),
			             'pay_type' => $data['pay_type'],
			             'adjust_amount' => $data['adjust_amount'],
			             'real_amount' => $data['real_amount'],
			             'commission' => array_sum($data['commission']),
			             'tids' => $ids,
			             'adjust_remark' => $data['adjust_remark'],
			             'fromdate' => $fromdate,
			             'todate' => $todate,
			             'clear_time' => $add_time,
			             'admin_name' => $this -> _auth['admin_name'],
			             );
		    
            $orderDb = new Admin_Models_DB_Order();
		    
		    foreach ($data['order_ids'] as $index => $order_id) {
		        $orderDb -> updateOrder(array('order_id' => $order_id), array('commission' => $data['commission'][$index]));
		        $orderDb -> clearOrder(array('clear_pay' => 1), "order_id = '{$order_id}'");
		    }
		    
		    return $this -> _db -> insertClear($row);
	    }
	}
	
	/**
     * 获取结款数据
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getClear($where = null, $page=null, $pageSize = null)
	{
		$whereSql = "1=1";
		if (is_array($where)) {
		    $where['clear_no'] && $whereSql .= " and clear_no LIKE '%" . $where['clear_no'] . "%'";
		    $where['logistic_no'] && $whereSql .= " and logistic_no LIKE '%" . $where['logistic_no'] . "%'";
		    $where['consignee'] && $whereSql .= " and consignee LIKE '%" . $where['consignee'] . "%'";
		    $where['logistic_code'] && $whereSql .= " and a.logistic_code = '" . $where['logistic_code'] . "'";
		    $where['pay_type'] && $whereSql .= " and a.pay_type = '" . $where['pay_type'] . "'";
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (clear_time between $fromdate and $todate)";
	        }
	        if ($where['batch_sn']) {
	            $orderAPI = new Admin_Models_DB_Order();
	            $order = array_shift($orderAPI -> getOrderBatchInfo(array('batch_sn' => $where['batch_sn'])));
	            if ($order) {
	                $whereSql .= " and tids like '%{$order['order_batch_id']}%'";
	            }
	            else {
	                return false;
	            }
	        }
		}else {
			$whereSql = $where;
		}
		return $this -> _db -> getClear($whereSql, $page, $pageSize);
	}
	
	/**
     * 获取外部结款数据
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getExternalClear($where = null, $page=null, $pageSize = null)
	{
		$whereSql = "1=1";
		if (is_array($where)) {
		    $where['clear_no'] && $whereSql .= " and clear_no LIKE '%" . $where['clear_no'] . "%'";
		    $where['shop_id'] && $whereSql .= " and t1.shop_id = '{$where['shop_id']}'";
		    $where['admin_name'] && $whereSql .= " and t1.admin_name = '{$where['admin_name']}'";
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (clear_time between $fromdate and $todate)";
	        }
	        if ($where['order_sn']) {
	            $orderAPI = new Admin_Models_API_Order();
	            $orderData = $orderAPI -> fetchExternalOrderBatch(array('order_sn' => $where['order_sn']));
	            if ($orderData['list']) {
	                $whereSql .= " and tids like '%{$orderData['list'][0]['order_id']}%'";
	            }
	            else {
	                return false;
	            }
	        }
		}else {
			$whereSql = $where;
		}
		return $this -> _db -> getExternalClear($whereSql, $page, $pageSize);
	}


	/**
     * 结算查看
     *
     * @param    int       $id
     * @return   array
     */
	public function viewClear($id)
	{
        $datas = $this -> _db -> getClear("id=$id");
        $data= array_shift($datas['data']);
		if ($data['tids']){
			$ids = $data['tids'];
            $orderDb = new Admin_Models_DB_Order();
			$result['details'] = $orderDb -> fetchOrderBatch("  and order_batch_id in($ids)",'a.commission,b.order_batch_id,b.batch_sn,b.status_pay,b.price_order,b.price_goods,b.price_pay,b.price_payed,b.clear_pay,b.pay_type,b.pay_name,b.pay_time,b.logistic_price');
		}
		$result['data'] = $data;
		return $result;
	}
	
	/**
     * 外部结算查看
     *
     * @param    int       $id
     * @return   array
     */
	public function viewExternalClear($id)
	{
        $datas = $this -> _db -> getExternalClear("id=$id");
        $data= array_shift($datas['data']);
		if ($data['tids']) {
            $orderAPI = new Admin_Models_API_Order();
			$result['details'] = $orderAPI -> fetchExternalOrderBatch(array('ids' => $data['tids'], 'type' => $data['type'], 'is_settle' => 1));
		}
		$result['data'] = $data;
		return $result;
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function payLogList($where = null, $fields = 't1.*', $orderBy = null, $page=null, $pageSize = null)
	{

		$whereSql = "1=1";
		if (is_array($where)) {
		    $where['pay_type'] && $whereSql .= " and t1.pay_type LIKE '%" . $where['pay_type'] . "%'";
		    $where['batch_sn'] && $whereSql .= " and t1.batch_sn LIKE '%" . $where['batch_sn'] . "%'";
		    $where['lid'] && $whereSql .= " and t3.lid = '{$where['lid']}'";
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) $whereSql .= " and (add_time between $fromdate and $todate)";
	        }
		}else {
			$whereSql = $where;
		}
		
		return $this -> _db -> payLogList($whereSql, $fields, $orderBy, $page, $pageSize);
	}
	
	/**
     * 外部订单结款
     *
     * @param    array    $data
     * @return   void
     */
	public function clearExternal($data)
	{
		if($data['ids']) {
			$ids = implode(',', $data['ids']);
			$row = array('shop_id' => $data['shop_id'],
			             'clear_no' => Custom_Model_CreateSn::createSn(),
			             'tids' => $ids,
			             'adjust_amount' => $data['adjust_amount'],
			             'real_amount' => $data['real_amount'],
			             'commission' => array_sum($data['commission']),
			             'adjust_remark' => $data['adjust_remark'],
			             'admin_name' => $this -> _auth['admin_name'],
			             'clear_time' => time(),
			             );
		    
		    return $this -> _db -> insertExternalClear($row);
	    }
	}
	
	/**
     * 结款统计
     *
     * @param    array    $data
     * @return   array
     */
	public function settlementSum($search)
	{
	    if ( $search['fromdate'] ) {
            $orderTime = strtotime("{$search['fromdate']} 00:00:00");
            $whereSql .= " and t1.add_time >= {$orderTime}";
        }
        if ( $search['todate'] ) {
            $orderTime = strtotime("{$search['todate']} 23:59:59");
            $whereSql .= " and t1.add_time <= {$orderTime}";
        }
	   
	    if ($search['type'] == 1) {
	        $datas = $this -> _db -> getSettlementSumType1($whereSql);
	        if (!$datas)    return false;
	        
	        foreach ($datas as $data) {
	            if ($data['pay_type'] == '' || $data['pay_type'] == 'cod')  continue;
	            
	            if (substr($data['pay_type'], 0, 6) == 'alipay') {
	                $data['pay_type'] = 'alipay';
	                $data['pay_name'] = '支付宝';
	            }
	            else if (substr($data['pay_type'], 0, 6) == 'yeepay') {
	                $data['pay_type'] = 'yeepay';
	                $data['pay_name'] = '易宝';
	            }
	            
	            $result[$data['pay_type']]['name'] = $data['pay_name'];
	            $result[$data['pay_type']]['count'] += $data['count'];
	            if ($data['clear_pay']) {
	                $result[$data['pay_type']]['count1'] += $data['count'];
	                $result[$data['pay_type']]['done_amount'] += $data['amount'];
	            }
	            else {
	                $result[$data['pay_type']]['count2'] += $data['count'];
	            }
	            $result[$data['pay_type']]['amount'] += $data['amount'];
	        }
	    }
	    else if ($search['type'] == 2) {
	        $datas = $this -> _db -> getSettlementSumType2($whereSql);
	        if (!$datas)    return false;
	        
	        foreach ($datas as $data) {
	            if ($data['logistic_code'] == '')    continue;
	            
	            $result[$data['logistic_code']]['name'] = $data['logistic_name'];
	            $result[$data['logistic_code']]['count'] += $data['count'];
	            if ($data['cod_status']) {
	                $result[$data['logistic_code']]['count1'] += $data['count'];
	                $result[$data['logistic_code']]['done_amount'] += $data['amount'];
	            }
	            else {
	                $result[$data['logistic_code']]['count2'] += $data['count'];
	            }
	            $result[$data['logistic_code']]['amount'] += $data['amount'];
	        }
	    }
	    else if ($search['type'] == 3) {
	        $datas = $this -> _db -> getSettlementSumType3($whereSql);
	        if (!$datas)    return false;
	        
	        foreach ($datas as $data) {
	            $result[$data['shop_id']]['name'] = $data['shop_name'];
	            $result[$data['shop_id']]['count'] += $data['count'];
	            if ($data['clear_pay']) {
	                $result[$data['shop_id']]['count1'] += $data['count'];
	                $result[$data['shop_id']]['done_amount'] += $data['amount'];
	            }
	            else {
	                $result[$data['shop_id']]['count2'] += $data['count'];
	            }
	            $result[$data['shop_id']]['amount'] += $data['amount'];
	        }
	    }
	    
	    return $result;
	}

    /**
     * 获取采购付款收款数据
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getPurchaseData($where = null, $page=null, $pageSize = null)
	{
		$whereSql = "1";
		if (is_array($where)) {
		    if ($where['fromdate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $whereSql .= " and t1.add_time >= {$fromdate}";
	        }
	        if ($where['todate']) {
			    $todate = strtotime($where['todate']) + 86400;
			    $whereSql .= " and t1.add_time < {$todate}";
	        }
		    $where['supplier_id'] && $whereSql .= " and t1.supplier_id = '{$where['supplier_id']}'";
		    if ($where['status'] !== null && $where['status'] !== '') {
		        $whereSql .= " and t1.status = '{$where['status']}'";
		    }
		    if ($where['invoice'] !== null && $where['invoice'] !== '') {
		        $whereSql .= " and t1.invoice = '{$where['invoice']}'";
		    }
		    $where['bill_no'] && $whereSql .= " and t1.bill_no = '{$where['bill_no']}'";
		    $where['type'] && $whereSql .= " and t1.type = '{$where['type']}'";
		    $where['paper_no'] && $whereSql .= " and t1.paper_no like '%{$where['paper_no']}%'";
		    $where['lid'] && $whereSql .= " and t3.lid = '{$where['lid']}'";
		    $where['id'] && $whereSql .= " and t1.id = '{$where['id']}'";
		}
		else {
			$whereSql = $where;
		}
		if ($where['bySupplier']) {
		    $order = "CONVERT(t2.supplier_name USING gbk),t1.add_time desc";
		}
		else {
		    $order = "t1.add_time desc";
		}
		return $this -> _db -> getPurchasePayment($whereSql, $page, $pageSize, $order, $where['type']);
	}
	
	/**
     * 采购入库付款
     *
     * @param    array     $payment
     * @param    float     $amount
     * @return   void
     */
	public function purchaseAction($payment, $amount)
	{
	    if ($payment['mixamount']  <= $amount) {
            $status = 2;
        }
        else    $status = 1;
        
        if ($payment['memo']) {
            $memo = "　备注:{$payment['memo']}";
        }
        
        if ($payment['type'] == 1) {
            $history = "付款时间:".date('Y-m-d H:i:s')."　付款总金额:{$amount}　付款人:".$this -> _auth['admin_name'].$memo."[当次发票信息：".$payment['invinfo'] ."]";
        }
        else if ($payment['type'] == 2) {
            $history = "收款时间:".date('Y-m-d H:i:s')."　收款金额:{$amount}　收款人:".$this -> _auth['admin_name'].$memo;
        }
        
        $row = array('real_amount' => $amount,
                     'status' => $status,
                     'history' => $payment['history']."\n".$history,
                     'update_time' => time(),
                    );
	    $this -> _db -> purchasePayment($payment['id'], $row);
	}
	
	/**
     * 采购收付款修改
     *
     * @param    int    $id
     * @param    id     $row
     * @return   void
     */
	public function purchasePayment($id, $row)
	{
	    $this -> _db -> purchasePayment($id, $row);
	}
	
	/**
     * 获取分销刷单
     *
     * @param   array   $where
     * @param   int     $page
     * @param   int     $pageSize
     * @return  array
     */
    public function getDistributionOrder($where = null, $page = null, $pageSize = null)
    {
        $whereSQL = 1;
        $where['fromdate'] && $whereSQL .= " and t1.add_time >= ".strtotime($where['fromdate']);
        $where['todate'] && $whereSQL .= " and t1.add_time <= ".strtotime($where['todate'].' 23:59:59');
        $where['batch_sn'] && $whereSQL .= " and t1.batch_sn = '{$where['batch_sn']}'";
        $where['shop_id'] && $whereSQL .= " and t2.distribution_id = '{$where['shop_id']}'";
        if ($where['write_off'] !== '' && $where['write_off'] !== null) {
            if ($where['write_off'] == '0') {
                $whereSQL .= " and t1.settle_time = 0";
            }
            else {
                $whereSQL .= " and t1.settle_time > 0";
            }
        }
        
        return $this -> _db -> getDistributionOrder($whereSQL, $page, $pageSize);
    }
    
    /**
     * 获取分销刷单的差额合计
     *
     * @param   array   $where
     * @return  float
     */
    public function getDistributionOrderSumAmount($where)
    {
        $whereSQL = "t1.status = 4 and t1.status_logistic = 4 and t2.distribution_type = 1";
        $where['fromdate'] && $whereSQL .= " and t1.add_time >= ".strtotime($where['fromdate']);
        $where['todate'] && $whereSQL .= " and t1.add_time <= ".strtotime($where['todate'].' 23:59:59');
        $where['from_pay_time'] && $whereSQL .= " and t1.pay_time >= ".strtotime($where['from_pay_time']);
        $where['to_pay_time'] && $whereSQL .= " and t1.pay_time <= ".strtotime($where['to_pay_time'].' 23:59:59');
        $where['batch_sn'] && $whereSQL .= " and t1.batch_sn = '{$where['batch_sn']}'";
        $where['user_name'] && $whereSQL .= " and t2.user_name = '{$where['user_name']}'";
        if ($where['clear_pay'] !== '' && $where['clear_pay'] !== null) {
            $whereSQL .= " and t1.clear_pay = '{$where['clear_pay']}'";
        }
        if ($where['write_off'] !== '' && $where['write_off'] !== null) {
            if ($where['write_off'] == '0') {
                $whereSQL .= " and t3.id is null";
            }
            else {
                $whereSQL .= " and t3.id > 0";
            }
        }
        
        return $this -> _db -> getDistributionOrderSumAmount($whereSQL);
    }
    
    /**
     * 分销刷单销账
     *
     * @param   string   $batchSN
     * @param   float    $amount
     * @return  string
     */
    public function distributionWriteOff($batchSN, $amount)
    {
        return $this -> _db -> distributionWriteOff($batchSN, $amount);
    }
    
    /**
     * 获取结款订单数据集
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetchOrder($where = null, $page = null, $pageSize = null)
	{
	    if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']) + 86400;
            if ($fromDate <= $toDate) {
                $condition[] = "(a.add_time between {$fromDate} and {$toDate})";
            }
        }
        if ($where['batch_sn']) {
            $condition[] = "b.batch_sn like '{$where['batch_sn']}%'";
        }
        if ($where['pay_type']) {
            $condition[] = "b.pay_type like '{$where['pay_type']}%'";
        }
        if ($where['sub_pay_type']) {
            if ($where['sub_pay_type'] == 'call') {
                $condition[] = "a.shop_id = 2 and b.type in (10,11,12)";
            }
            else if ($where['sub_pay_type'] == 'jiankang') {
                $condition[] = "a.shop_id = 1";
            }
            else if ($where['sub_pay_type'] == 'employee') {
                $condition[] = "a.shop_id = 3";
            }
        }
        if (!is_null($where['status']) && $where['status'] !== '') {
            $condition[] = "status={$where['status']}";
        }
        if (!is_null($where['status_logistic']) && $where['status_logistic'] !== '') {
            $condition[] = "status_logistic={$where['status_logistic']}";
        }
        if (!is_null($where['status_logistic>'])) {
            $condition[] = "status_logistic>{$where['status_logistic>']}";
        }

        if (!is_null($where['status_return']) && $where['status_return'] !== '') {
            $condition[] = "status_return={$where['status_return']}";
        }
        if ($where['logistic_no']) {
            $condition[] = "logistic_no='{$where['logistic_no']}'";
        }
        if ($where['batch_sns']) {
            $condition[] = "b.batch_sn in (".implode(',', $where['batch_sns']).")";
        }
        if ($where['not_pay_type']) {
            $condition[] = "b.pay_type not in ({$where['not_pay_type']})";
        }
        if ($where['clear_pay'] !== null && $where['clear_pay'] !== '') {
            $condition[] = "clear_pay = '{$where['clear_pay']}'";
        }
        if ($where['shop_id']) {
            $condition[] = "a.shop_id = '{$where['shop_id']}'";
        }
        if ($where['user_name']) {
            $condition[] = "a.user_name like '%{$where['user_name']}%'";
        }
        if ($where['type'] !== '' && $where['type'] !== null) {
            if ($where['type'] == '0' && $where['user_name'] != 'yumi_jiankang' && $where['user_name'] != 'xinjing_jiankang') {
                $condition[] = "b.type='{$where['type']}' and a.user_name <> 'yumi_jiankang' && a.user_name <> 'xinjing_jiankang'";
            }
            else {
                $condition[] = "b.type='{$where['type']}'";
            }
        }
        if ($where['status'] !== '' && $where['status'] !== null) {
            $condition[] = "b.status = '{$where['status']}'";
        }
        if ($where['send_fromdate']) {
            $condition[] = "b.logistic_time >= ".strtotime($where['send_fromdate']);
        }
        if ($where['send_todate']) {
            $condition[] = "b.logistic_time <= ".strtotime($where['send_todate'].' 23:59:59');
        }
        
        if ($where['entry'] == 'call') {
            $condition[] = "b.type in (10,11,12)";
        }
        if ($where['entry'] == 'other') {
            $condition[] = "b.type in (5,7,15)";
        }
        if ($where['entry'] == 'b2c') {
            $condition[] = "b.type = 0";
        }
        
        if ($where['clear_fromdate'] || $where['clear_todate']) {
            $subCondition = 1;
            $where['clear_fromdate'] && $subCondition .= " and clear_time >= ".strtotime($where['clear_fromdate']);
            $where['clear_todate'] && $subCondition .= " and clear_time <= ".strtotime($where['clear_todate'].' 23:59:59');
            $clearData = $this -> _db -> getClear($subCondition);
            if ($clearData['data']) {
                $tids = '';
                foreach ($clearData['data'] as $data) {
                    $tids .= $data['tids'].',';
                }
                $tids = substr($tids, 0, -1);
                if ($tids) {
                    $condition[] = "b.order_batch_id in ({$tids})";
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' AND ', $condition);
        }
	    
	    $data = $this -> _db -> fetchOrder($condition, $page, $pageSize);
        if (is_array($data['list']) && count($data['list'])){
            foreach ($data['list'] as $k => $v) {
                $data['list'][$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $data['list'][$k]['oinfo'] = Zend_Json::encode($data['list'][$k]);
            }
        }
        
        return $data;
	}
	
	/**
     * 添加应收记录
     *
     * @param   array       $data
     * @return  void
     */
    public function addFinanceReceivable($data)
    {
        if (!$data['add_time']) {
            $data['add_time'] = time();
        }
        
        if (in_array($data['pay_type'], array('bank', 'cash'))) {
            if ($this -> getFinanceReceivable(array('batch_sn' => $data['batch_sn'], 'pay_type' => $data['pay_type']))) {
                return false;
            }
        }
        
        $this -> _db -> addFinanceReceivable($data);
    }
    
    /**
     * 获得应收记录
     *
     * @param   array       $search
     * @return  array
     */
    public function getFinanceReceivable($search)
    {
        $whereSQL = 1;
        $search['batch_sn'] && $whereSQL .= " and batch_sn = '{$search['batch_sn']}'";
        $search['type'] && $whereSQL .= " and type = '{$search['type']}'";
        $search['pay_type'] && $whereSQL .= " and pay_type = '{$search['pay_type']}'";
        
        return $this -> _db -> getFinanceReceivable($whereSQL);
    }
    
    /**
     * 更新应收记录
     *
     * @param   array       $data
     * @param   array       $where
     * @return  void
     */
    public function updateFinanceReceivable($data, $where)
    {
        $this -> _db -> updateFinanceReceivable($data, $where);
    }
    
    /**
     * 获得应收款支付方式
     *
     * @return  array
     */
    public function getAccountReceivablePayType()
	{
	    $logisticAPI = new Admin_Models_API_Logistic();
	    $logisticList = $logisticAPI -> getLogisticList();
	    foreach ($logisticList as $logistic) {
	        $result[$logistic['logistic_code']] = $logistic['name'];
	    }
	    
	    $paymentAPI = new Admin_Models_API_Payment();
	    $paymentList = $paymentAPI -> get(array('is_bank' => '0,2'));
	    if ($paymentList) {
	        foreach ($paymentList as $payment) {
	            $result[$payment['pay_type']] = $payment['name'];
	        }
	    }

	    $result['external'] = '渠道支付';
	    $result['externalself'] = '渠道代发货支付';
	    $result['cash'] = '现金支付';
	    $result['bank'] = '银行打款';
	    $result['credit'] = '赊销支付';
	    $result['distribution'] = '直供支付';
	    $result['point'] = '积分抵扣';
	    $result['account'] = '账户余额抵扣';
	    $result['gift'] = '礼品卡抵扣';
	    $result['exchange'] = '换货支付';
	    
	    return $result;
	}
	
	/**
     * 添加直供结算
     *
     * @param   array       $data
     * @return  void
     */
    public function addDistributionSettlement($data)
	{
	    $data['add_time'] = time();
	    $this -> _db -> addDistributionSettlement($data);
	}
	
	/**
     * 直供结算
     *
     * @param   string      $batchSN
     * @param   array       $post
     * @return  boolean
     */
    public function distributionSettlement($batchSN, $post)
	{
	    $totalNumber = $totalReturnNumber = $amount = $returnAmount = 0;
	    if ($post['return_amount_number']) {
	        foreach ($post['return_amount_number'] as $productID => $number) {
	            if ($number > 0) {
	                $totalReturnNumber += $number;
	                $returnAmount += $post['price'][$productID] * $number;
	                $returnAmountDetail[$productID] = $number;
	            }
	        }
	    }
	    if ($post['number']) {
            foreach ($post['number'] as $productID => $number) {
                if ($number > 0) {
                    $totalNumber += $number;
                    $amount += $post['price'][$productID] * $number;
                    $detail[$productID] = $number;
                }
            }
        }
        if ($totalNumber == 0 && $totalReturnNumber == 0) {
            $this -> error = '结款数量或退款数量不能同时为0!';
	        return false;
        }
	    
	    $data = array_shift($this -> getDistributionSettlement(array('batch_sn' => $batchSN)));
	    if (!$data) {
	        $this -> error = '找不到结款订单!';
	        return false;
	    }
	    
	    if ($totalNumber > 0) {
    	    if ($data['settle_amount'] + $data['promotion_amount'] + $data['point_amount'] >= $data['amount']) {
    	        $this -> error = '该订单已结款!';
    	        return false;
    	    }
    	    
    	    if (($data['amount'] - $data['settle_amount'] - $data['promotion_amount'] - $data['point_amount']) < $amount) {
    	        $this -> error = '结款金额大于未结款金额!';
    	        return false;
    	    }
	    }
	    
	    if ($totalReturnNumber > 0) {
	        $row = array('distribution_id' => $data['distribution_id'],
    	                 'type' => 1,
    	                 'amount' => $returnAmount * -1,
    	                 'promotion_amount' => 0,
    	                 'point_amount' => 0,
    	                 'detail' => serialize($returnAmountDetail),
    	                 'admin_name' => $this -> _auth['admin_name'],
    	                 'add_time' => time()
    	                );
    	    $this -> _db -> addDistributionSettlementDetail($row);
    	    
    	    $set = array('settle_amount' => $data['settle_amount'] - $returnAmount,
                         'settle_time' => time(),
                        );
            $this -> _db -> updateDistributionSettlement($batchSN, $set);
            $data['settle_amount'] -= $returnAmount;
	    }
	    
	    if ($totalNumber > 0) {
    	    $row = array('distribution_id' => $data['distribution_id'],
    	                 'type' => 1,
    	                 'amount' => $amount - $post['promotion_amount'] - $post['point_amount'],
    	                 'promotion_amount' => $post['promotion_amount'],
    	                 'point_amount' => $post['point_amount'],
    	                 'detail' => serialize($detail),
    	                 'admin_name' => $this -> _auth['admin_name'],
    	                 'add_time' => time()
    	                );
    	    $this -> _db -> addDistributionSettlementDetail($row);
            
            $set = array('settle_amount' => $data['settle_amount'] + $amount - $post['promotion_amount'] - $post['point_amount'],
                         'promotion_amount' => $data['promotion_amount'] + $post['promotion_amount'],
                         'point_amount' => $data['point_amount'] + $post['point_amount'],
                         'settle_time' => time(),
                        );
            $this -> _db -> updateDistributionSettlement($batchSN, $set);
        }
        
	    return $amount - $returnAmount;
	}
	
	/**
     * 修改直供结算
     *
     * @param   string      $batchSN
     * @param   array       $data
     * @return  void
     */
    public function updateDistributionSettlement($batchSN, $data)
	{
	    $this -> _db -> updateDistributionSettlement($batchSN, $data);
	}
	
	/**
     * 获得直供结算
     *
     * @param   array   $search
     * @param   int     $page
     * @param   int     $pageSize
     * @return  array
     */
    public function getDistributionSettlement($search, $page = null, $pageSize = null)
	{
	    $where = $this -> getDistributionSettlementSQL($search);
	    
	    return $this -> _db -> getDistributionSettlement($where, $page, $pageSize);
	}
	
	/**
     * 获得直供结算合计
     *
     * @param   array   $search
     * @return  array
     */
	public function getDistributionSettlementSum($search)
	{
	    $where = $this -> getDistributionSettlementSQL($search);
	    
	    return $this -> _db -> getDistributionSettlementSum($where);
	}
	
	/**
     * 获得直供结算查询条件
     *
     * @param   array   $search
     * @return  string
     */
	private function getDistributionSettlementSQL($search)
	{
	    $where = "t2.status = 0 and t2.type = 16 and t2.status_logistic in (3,4)";
	    $search['fromdate'] && $where .= " and t2.logistic_time >= ".strtotime($search['fromdate']);
	    $search['todate'] && $where .= " and t2.logistic_time <= ".strtotime($search['todate'].' 23:59:59');
	    $search['settle_fromdate'] && $where .= " and t1.settle_time >= ".strtotime($search['settle_fromdate']);
	    $search['settle_todate'] && $where .= " and t2.settle_time <= ".strtotime($search['settle_todate'].' 23:59:59');
	    $search['batch_sn'] && $where .= " and t1.batch_sn = '{$search['batch_sn']}'";
	    $search['shop_id'] && $where .= " and t3.shop_id = '{$search['shop_id']}'";
	    if ($search['status']) {
	        if ($search['status'] == 1) {
	            $where .= " and t1.settle_amount <= 0";
	        }
	        else if ($search['status'] == 2) {
	            $where .= " and t1.settle_amount > 0 and t1.amount > (t1.settle_amount + t1.promotion_amount + t1.point_amount)";
	        }
	        else if ($search['status'] == 3) {
	            $where .= " and t1.settle_amount = (t1.settle_amount + t1.promotion_amount + t1.point_amount)";
	        }
	    }
	    
	    return $where;
	}
	
	/**
     * 获得直供结算明细
     *
     * @param   string  $batchSN
     * @return  array
     */
    public function getDistributionSettlementDetail($batchSN)
	{
	    $where = "t2.batch_sn = '{$batchSN}'";
	    $datas =  $this -> _db -> getDistributionSettlementDetail($where);
	    if (!$datas)    return false;
	    
	    foreach ($datas as $index => $data) {
	        $datas[$index]['detail'] = unserialize($data['detail']);
	    }
	    return $datas;
	}
	
	/**
     * 添加直供结算明细
     *
     * @param   array       $data
     * @return  void
     */
    public function addDistributionSettlementDetail($data)
	{
	    $this -> _db -> addDistributionSettlementDetail($data);
	}
	
	/**
     * 获得应收款列表
     *
     * @param   array     $where
     * @param   int       $page
     * @return  array
     */
    public function getReceivableList($where, $page = null)
    {
        $whereSQL = "((t1.status = 0 and t1.status_logistic in (3,4,5)) or (t1.status = 3 and t1.fake_type = 0) or t1.status in (4,5)) and t1.logistic_time > 0 and t4.pay_type <> 'point'";
        
        if ($where['send_fromdate']) {
	        $fromdate = strtotime($where['send_fromdate']);
	        $whereSQL .= " and t1.logistic_time >= {$fromdate}";
	    }
	    if ($where['send_todate']) {
	        $todate = strtotime($where['send_todate'].' 23:59:59');
	        $whereSQL .= " and t1.logistic_time <= {$todate}";
	    }
	    if ($where['fromdate']) {
	        $fromdate = strtotime($where['fromdate']);
	        $whereSQL .= " and t4.add_time >= {$fromdate}";
	    }
	    if ($where['todate']) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $whereSQL .= " and t4.add_time <= {$todate}";
	    }
	    if ($where['clear_pay'] != null && $where['clear_pay'] != '') {
	        if ($where['clear_pay'] == '0') {
	            $whereSQL .= " and t4.settle_time = 0";
	        }
	        else {
	            $whereSQL .= " and t4.settle_time > 0";
	        }
	    }
	    if ($where['batch_sn']) {
	        $whereSQL .= " and (t4.batch_sn = '{$where['batch_sn']}' or t1.logistic_no = '{$where['batch_sn']}' or t2.external_order_sn = '{$where['batch_sn']}')";
	    }
	    if ($where['pay_type']) {
	        $whereSQL .= " and t4.pay_type = '{$where['pay_type']}'";
	    }
	    if ($where['id']) {
	        $whereSQL .= " and t4.id = '{$where['id']}'";
	    }
	    if ($where['is_fake']) {
	        if ($where['is_fake'] == 1) {
	            $whereSQL .= " and t1.status = 3";
	        }
	        else {
	            $whereSQL .= " and t1.status <> 3";
	        }
	    }
	    if ($where['lid']) {
	        $whereSQL .= " and t2.lid = '{$where['lid']}'";
	    }
	     
	    $jiankangSQLArray['haitao'] = "t2.shop_id = 1";
	    $jiankangSQLArray['employee'] = "t2.shop_id = 3";
	    $jiankangSQLArray['gift'] = "t2.shop_id = 0 and t2.user_name = 'gift'";
	    $jiankangSQLArray['internal'] = "t2.shop_id = 0 and t2.user_name = 'internal'";
	    $jiankangSQLArray['other'] = "t2.shop_id = 0 and t2.user_name = 'other'";
	    
	    $callSQLArray['call_in'] = "t2.shop_id = 2 and t1.type = 10";
	    $callSQLArray['call_out'] = "t2.shop_id = 2 and t1.type = 11";
	    $callSQLArray['call_tq'] = "t2.shop_id = 2 and t1.type = 12";
	    
	    if ($where['type']) {
	        if ($where['entry'] == 'self') {
	            $whereSQL .= ' and '.$jiankangSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'call') {
	            $whereSQL .= ' and '.$callSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'channel') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'distribution') {
	            $whereSQL .= " and t2.shop_id = 0 and t2.user_name like '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'new_distribution') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'tuan') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	    }
	    else {
	        $sqlArray['self'] = " and (";
	        foreach ($jiankangSQLArray as $sql) {
	            $sqlArray['self'] .= "({$sql}) or ";
	        }
	        $sqlArray['self'] .= "0)";
	        $sqlArray['call'] .= " and t2.shop_id = 2 and t1.type in (10,11,12)";
	        $sqlArray['channel'] = " and t2.shop_id > 0 and t3.shop_type <> 'jiankang' and t3.shop_type <> 'tuan' and t3.shop_type <> 'credit' and t3.shop_type <> 'distribution'";
	        $sqlArray['distribution'] = " and t2.shop_id = 0 and (t2.user_name like '%_distribution' or t2.user_name = 'batch_channel')";
	        $sqlArray['new_distribution'] = " and t3.shop_type = 'distribution'";
	        $sqlArray['tuan'] = " and (t3.shop_type = 'tuan' or t3.shop_type = 'credit')";
	        
	        $whereSQL .= $sqlArray[$where['entry']];
	    }
        
        return $this -> _db -> getReceivableList($whereSQL, $page);
    }
    
    /**
     * 获得发票
     *
     * @param   array   $where
     * @param   int    $page
     * @return  array
     */
    public function getInvoice($where = null, $page = null)
    {
        $whereSQL = 1;
        $where['invoice_no'] && $whereSQL .= " and t1.invoice_no = '{$where['invoice_no']}'";
        $where['status'] && $whereSQL .= " and t1.status = '{$where['status']}'";
        $where['fromdate'] && $whereSQL .= " and t1.addtime >= ".strtotime($where['fromdate']);
        $where['todate'] && $whereSQL .= " and t1.addtime <= ".strtotime($where['todate'].' 23:59:59');
        $where['batch_sn'] && $whereSQL .= " and t2.batch_sn = '{$where['batch_sn']}'";
        
        return $this -> _db -> getInvoice($whereSQL, $page);
    }
    
    /**
     * 更新发票
     *
     * @param   array   $data
     * @param   string  $where
     * @return  void
     */
    public function updateInvoice($data, $where)
    {
        $this -> _db -> updateInvoice($data, $where);
    }
    
    /**
     * 获得应收款
     *
     * @param   array   $where
     * @return  array
     */
    public function fetchReceivableList($where)
    {
        if (!$where['pay_type'])    return false;
        
        $whereSQL = "t1.pay_type = '{$where['pay_type']}'";
        if ($where['bill_nos']) {
            if (in_array($where['pay_type'], array('external', 'externalself'))) {
                $where['bill_nos'] && $whereSQL .= " and t3.external_order_sn in (".implode(',', $where['bill_nos']).")";
            }
            else if (in_array($where['pay_type'], array('sf', 'ems'))) {
                $where['bill_nos'] && $whereSQL .= " and t2.logistic_no in (".implode(',', $where['bill_nos']).")";
            }
            else {
                $where['bill_nos'] && $whereSQL .= " and t1.batch_sn in (".implode(',', $where['bill_nos']).")";
            }
        }
        $data = $this -> _db -> fetchReceivableList($whereSQL, $where['pay_type']);
        if ($data) {
            foreach ($data as $k => $v) {
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $data[$k]['oinfo'] = Zend_Json::encode($v);
            }
        }
        
        return $data;
    }
}
