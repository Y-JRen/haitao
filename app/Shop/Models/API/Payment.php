<?php
class Shop_Models_API_Payment 
{
    protected $_db = null;

	public function __construct()
    {
		$this -> _db = new Shop_Models_DB_Payment();
        $this -> user = Shop_Models_API_Auth :: getInstance() -> getAuth();
	}

    /**
     * 更新支付状态 返回需支付或需退款金额
     *
     * @parma string  $logID  订单批次号+时间戳
     * @parma float   $paymentAmount 本次支付金额
     * @parma string   $payType 支付手段
     * @param string   $serial_number 返回的流水号
     * @return array
     */
    public function update($logID, $paymentAmount, $payType,$serial_number=null)
    {
        if (!$logID) return false;
        $returnRes = array('result' => false, 'remainder' => null, 'msg' => '');
        $batchSN = array_shift(explode('-', $logID));
        $batchSN = substr($logID, 0,15);
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (is_array($order) && !empty($order)) {
            if ($this -> _db -> getOrderPayLog(array('pay_log_id' => $logID))) {//已校验过
                //$returnRes['msg'] = "[{$logID}]该记录已经校验过!";
                $returnRes['result'] = true;
                return $returnRes;
            } else {//如果没有校验过
                //1 添加订单支付记录
                $data = array('pay_log_id' => $logID, 
                              'batch_sn' => $batchSN,
                              'add_time' => time(),
                              'pay_type' => $payType,//替换原先的$order['pay_type'],为了解决支付接口延迟，客服又修改支付方式，财务找不到支付数据
                              'pay' => $paymentAmount,
                			  'serial_number'=>$serial_number);
                $this -> _db -> addOrderPayLog($data);
                unset($data);
                
                //添加应收款记录
                $financeAPI = new Admin_Models_API_Finance();
                $data = array('batch_sn' => $batchSN,
                              'type' => 1,
                              'pay_type' => substr($payType, 0 ,6) == 'alipay' ? 'alipay' : $payType,
                              'amount' => $paymentAmount,
                             );
                $financeAPI -> addFinanceReceivable($data);
            }
            
            //2 更新订单已支付金额 / 支付状态
            $data = array();
            $data['price_payed'] = $order['price_payed'] + $paymentAmount;
            $orderHasPaid = $data['price_payed'] + $order['price_from_return'];
            $data['pay_time'] = time();
            if ($orderHasPaid < $order['price_pay']) { //已支付 < 需支付 = 未支付 
                $data['status_pay'] = 0;
            } else if ($orderHasPaid == $order['price_pay']) { //已结清
                $data['status_pay'] = 2;
                
            } else if ($orderHasPaid > $order['price_pay']) { //应退款
                $data['status_pay'] = 1;
            }
            $returnRes['result'] = $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
            $returnRes['remainder'] = $orderHasPaid - $order['price_pay'];   // >0表需退款，<0表仍需支付，=0表已结清
        }
        else {
            $returnRes['msg'] = '参数错误，未找到对应订单信息!';
        }

        return $returnRes;
    }

    /**
     * 支付接口返回
     *
     * @return void
     */
    public function respond($payType,$business = '')
    {
        $payType = ucfirst($payType);
        $class =  'Custom_Model_Payment_' . $payType;
        $pay = new $class();
        return is_object($pay) ?  $pay -> respond($business) : false;
    }
	
    /**
     * 异步校验
     *
     * @return void
     */
    public function sync($payType,$business='')
    {
        $payType = ucfirst($payType); 
        $class = 'Custom_Model_Payment_' . $payType;
        $pay = new $class();
        return is_object($pay) ?  $pay -> sync($business) : false;
    }
    public function auth($payType)
    {
        $payType = ucfirst($payType);
        $class = 'Custom_Model_Payment_' . $payType;
        $pay = new $class();
        return is_object($pay) ?  $pay -> auth() : false;
       
    }
    public function check($order_sn,$amount,$pay_id,$business='')
    {
    	$payment = $this->getRow('shop_payment',array('id'=>$pay_id));
        $payType = ucfirst($payment['pay_type']);
		return 'asdfasdfasf';
        $class = 'Custom_Model_Payment_' . $payType;
        $pay = new $class($order_sn,4,$amount,$business);
        return is_object($pay) ?  $pay -> check($order_sn,$amount,unserialize($payment['config'])) : false;
    }
	/**
     * 取得指定条件订单批次信息
     *
     * @param   array     $where
     * @return  array
     */
    public function getOrderBatch($where) 
    {
        $where['user_id'] = $this -> user['user_id'];
        return $this -> _db -> getOrderBatch($where);
    }
    
    /**
     * 取得指定条件订单商品信息
     *
     * @param   array     $where
     * @return  array
     */
    public function getOrderBatchGoods($where) 
    {
        $where['user_id'] = $this -> user['user_id'];
        return $this -> _db -> getOrderBatchGoods($where);
    }
    
	/**
     * 取得指定条件的支付方式
     *
     * @param   array     $where
     * @return  array
     */
    public function getPayment($where)
    {
        return $this -> _db -> getPayment($where);
    }
	/**
     * 取得指定条件的支付日志
     *
     * @param   array     $where
     * @return  array
     */
    public function getOrderPayLog($data)
    {
        return $this -> _db -> getOrderPayLog($data);
    }
	/**
     * 添加一条支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderPayLog($data)
    {
        return $this -> _db -> addOrderPayLog($data);
    }
    
    /**
     * 查询单笔订单
     *
     * @param   array     $data
     * @return  int
     */
    public function query($payType, $batchSN)
    {
        $payType = ucfirst($payType); 
        $class = 'Custom_Model_Payment_' . $payType;
        $pay = new $class($batchSN);
        return is_object($pay) ?  $pay -> query() : false;
    }
    
    /**
     * 生成订单下一个付款序列号
     *
     * @param   string  $payType
     * @param   string  $batchSN
     * @param   float   $amount
     * @return  int
     */
    public function createPaySeries($payType, $batchSN, $amount)
    {
        if ( !$payType || !$batchSN || !$amount )   return 'error';
        $data['batch_sn'] = $batchSN;
        $data['user_id'] = $this -> user['user_id'] ? $this -> user['user_id'] : 0;
        $data['pay-type'] = $payType;
        $data['amount'] = $amount;
        $data['add_time'] = time();
        return $this -> _db -> createPaySeries($data);
    }
    
    /**
     * 获得订单付款序列
     *
     * @param   array  $where
     * @return  int
     */
    public function getPaySeries($where)
    {
        if ($where['seriesNo']) {
            $whereSql .= " and series_no = '{$where['seriesNo']}'";
        }
        if ($where['batchSn']) {
            $whereSql .= " and batch_sn = '{$where['batchSn']}'";
        }
        if ($where['id']) {
            $whereSql .= " and id = {$where['id']}";
        }
        return $this -> _db -> getPaySeries($whereSql);
    }
    
    /**
     * 获得订单最后一个付款序列号
     *
     * @param   string  $batchSN
     * @return  int
     */
    public function getLastPaySeries($batchSN)
    {
        if ( !$batchSN )    return false;
        return $this -> _db -> getLastPaySeries($batchSN);
    }
	
	/**
	 * 得到所有的支付方式
	 * 
	 */
	public function getPays(){
		return $this->getAll('shop_payment',array('status'=>0));
	}
	
}
