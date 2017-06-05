<?php
class Shop_Models_DB_Payment
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 订单表名
     * 
     * @var    string
     */
	private $_table_order = 'shop_order';
	private $_table_order_batch = 'shop_order_batch';
	private $_table_order_batch_goods = 'shop_order_batch_goods';
	private $_table_order_pay_log = 'shop_order_pay_log';
	private $_table_payment = 'shop_payment';
	private $_table_order_pay_series = 'shop_order_pay_series';
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	/**
     * 取得指定条件订单批次信息
     *
     * @param   array     $where
     * @return  array
     */
    public function getOrderBatch($where = null) 
    {
        $condition = null;
        if (is_array($where)) {
            if ($where['batch_sn']) {
                $condition[] = "b.batch_sn = '{$where['batch_sn']}'";
            }
            if ($where['user_id']) {
                $condition[] = "a.user_id = '{$where['user_id']}'";
            }
            if (is_array($condition) && count($condition)) {
                $condition = 'AND ' . implode(' and ', $condition);
            }
        } else if (is_string($where)) {
            $condition = $where;
        }
        if ($condition) {
            $sql  = 'SELECT a.order_id,a.user_id,a.user_name,a.invoice,a.invoice_content,a.parent_id,a.parent_param,a.proportion,';
            $sql .= "b.* FROM {$this->_table_order} a left join {$this->_table_order_batch} b on a.order_sn = b.order_sn ";
            $sql .= "where 1=1 {$condition} order by order_batch_id desc";
            return $this->_db->fetchAll($sql);
        } else {
            return false;
        }
    }
    
    /**
     * 取得指定条件订单商品信息
     *
     * @param   array     $where
     * @return  array
     */
    public function getOrderBatchGoods($where = null) 
    {
        $condition = null;
        if (is_array($where)) {
            if ($where['batch_sn']) {
                $condition[] = "t2.batch_sn = '{$where['batch_sn']}'";
            }
            if ($where['user_id']) {
                $condition[] = "t1.user_id = '{$where['user_id']}'";
            }
            if (is_array($condition) && count($condition)) {
                $condition = 'AND '.implode(' and ', $condition);
            }
        }
        else if (is_string($where)) {
            $condition = $where;
        }
        if ($condition) {
            $sql  = "select t1.batch_sn,t3.order_batch_goods_id,t3.type,t3.product_id,t3.number from {$this -> _table_order} as t1 
                     inner join {$this -> _table_order_batch} as t2 on t1.batch_sn = t2.batch_sn
                     inner join {$this -> _table_order_batch_goods} as t3 on t1.batch_sn = t3.batch_sn
                     where 1 {$condition}";
            return $this -> _db -> fetchAll($sql);
        }
        else {
            return false;
        }
    }
    
	/**
     * 取得指定条件的支付方式
     *
     * @param   array     $where
     * @return  array
     */
    public function getPayment($where = null)
    {
        if (is_array($where)) {
            if ($where['pay_type']) {
                $condition[] = "pay_type='{$where['pay_type']}'";  
            }
            if (is_array($condition) && count($condition)) {
                $condition = 'AND ' . implode(' AND ', $condition);
            }
        } else if (is_string($where)) {
            $condition = $where;
        }
        return $this -> _db -> fetchAll("select * from `{$this->_table_payment}` where pay_type !='external'  {$condition}");
    }
	/**
     * 取得指定条件的支付日志
     *
     * @param   array     $where
     * @return  array
     */
    public function getOrderPayLog($where)
    {
        if (is_array($where)) {
            if ($where['batch_sn']) {
                $condition[] = "batch_sn='{$where['batch_sn']}'";  
            }
            if ($where['pay_log_id']) {
                $condition[] = "pay_log_id='{$where['pay_log_id']}'";  
            }
            if (is_array($condition) && count($condition)) {
                $condition = 'AND ' . implode(' AND ', $condition);
            }
        } else if (is_string($where)) {
            $condition = $where;
        }
        return $this -> _db -> fetchAll("select * from `{$this->_table_order_pay_log}` where 1=1 {$condition}");
    }
	/**
     * 添加一条支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderPayLog($data)
    {
        $this->_db->insert($this->_table_order_pay_log, $data);
		return $this->_db->lastInsertId();
    }
	/**
     * 更新指定ID的订单支付记录信息
     *
     * @param   array   $where
     * @param   array   $data
     * @return  bool
     */
    public function updateOrderPayLog($where, $data)
    {
        if ($where['pay_log_id']) {
            $condition[] = "pay_log_id = '{$where['pay_log_id']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
        return $this -> _db -> update($this -> _table_order_pay_log, $data, $condition);
    }
	/**
     * 更新指定ID的订单批次信息
     *
     * @param   array   $where
     * @param   array   $data
     * @return  bool
     */
    public function updateOrderBatch($where, $data)
    {
        $condition = null;
        if ($where['batch_sn']) {
            $condition[] = "batch_sn = '{$where['batch_sn']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
        if ($condition) {
            return $this-> _db -> update($this->_table_order_batch, $data, $condition);
        } else {
            return false;
        }
    }
    
    /**
     * 生成订单下一个付款序列号
     *
     * @param   array   $data
     * @return  int
     */
    public function createPaySeries($payment)
    {
        $data = $this -> _db -> fetchRow("select series_no from `{$this->_table_order_pay_series}` where batch_sn='{$payment['batch_sn']}' order by id DESC");
        if ( $data ) {
            $payment['series_no'] = $data['series_no'] + 1;
        }
        else    $payment['series_no'] = 1;
        
        $this -> _db -> insert($this -> _table_order_pay_series, $payment);
		
		return substr('0'.$payment['series_no'], -2);
    }
    
    /**
     * 获得订单付款序列
     *
     * @param   string  $whereSql
     * @return  array
     */
    public function getPaySeries($whereSql)
    {
        if ($whereSql) {
            $sql = 'where 1 '.$whereSql;
        }
        return $this -> _db -> fetchRow("select * from `{$this->_table_order_pay_series}` {$sql}");
    }
    
    /**
     * 获得订单最后一个付款序列号
     *
     * @param   string  $batchSN
     * @return  int
     */
    public function getLastPaySeries($batchSN)
    {
        $data = $this -> _db -> fetchRow("select series_no from `{$this->_table_order_pay_series}` where batch_sn='{$batchSN}' order by id DESC");
        if ( $data ) {
            return substr('0'.$data['series_no'], -2);
        }
        
        return false;
    }

}