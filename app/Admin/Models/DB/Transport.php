<?php

class Admin_Models_DB_Transport
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;

	/**
     * page size
     * @var    int
     */
	private $_pageSize = null;

	/**
     * table name
     * @var    string
     */
	private $_table_transport = 'shop_transport';
	private $_table_transport_track = 'shop_transport_track';
	private $_table_transport_clear = 'shop_transport_clear';
	private $_table_area = 'shop_area';
    private $_table_logistic = 'shop_logistic';
    private $_table_logistic_area = 'shop_logistic_area';
    private $_table_logistic_area_price = 'shop_logistic_area_price';
    private $_table_order = 'shop_order';
    private $_table_order_batch = 'shop_order_batch';
    private $_table_transport_source = 'shop_transport_source';
    private $_table_finance_invoice = 'shop_finance_invoice';
    private $_table_shop = 'shop_shop';

	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}

	/**
     * ��ȡ��ݼ�
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = 't1.*', $page = null, $pageSize = null, $orderBy = null, $includeOrder = false)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}

		if ($where != null) {
			$whereSql = "WHERE $where";
		}

		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}
		else {
			$orderBy = "ORDER BY t1.tid desc";
		}

		$table = "{$this->_table_transport} as t1 ";
		if ($includeOrder) {
		    $table .= "left join {$this -> _table_transport_source} as t2 on t1.tid = t2.transport_id
		               left join {$this -> _table_order_batch} as t3 on t2.bill_no = t3.batch_sn";
		}
        $data = $this -> _db -> fetchRow("select count(*) as count,sum(amount) as amount from (SELECT t1.tid,t1.amount FROM $table $whereSql group by t1.tid) a");
		$this -> total = $data['count'];
		$this -> amount = $data['amount'];
		return $this -> _db -> fetchAll("SELECT * from (select {$fields},(t1.amount+t1.change_amount-t1.logistic_fee_service-t1.logistic_price_cod) as back_amount FROM $table $whereSql group by t1.tid $orderBy) a $limit");
	}

	/**
     * ��ȡ��ݼ�
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getTrack($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}

		if ($where != null) {
			$whereSql = "WHERE $where";
		}

		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY id desc";
		}

		$table = "`$this->_table_transport_track`";

		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");

		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
	}

	/**
     * ������
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert($data)
	{
        $this -> _db -> insert($this -> _table_transport, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

	/**
     * 插入运输单关联数据
     *
     * @param    array    $data
     * @return   int
     */
	public function insertRelationOrder($data)
	{
        $this -> _db -> insert($this -> _table_transport_source, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

    /**
     * 根据订单SN删除关联数据
     *
     * @param    array
     *
     * @return   boolean
     */
	public function deleteRelationOrderByOrderSn($params)
	{
        !empty($params['like_order']) && $_condition[] = "bill_no like '{$params['like_order']}%'";
        !empty($params['bill_no'])    && $_condition[] = "bill_no = '{$params['bill_no']}'";
        return (bool) $this -> _db -> delete($this -> _table_transport_source, implode(' AND ', $_condition));
	}

	/**
	 * 根据运输单ID删除运输单关联数据
	 *
	 * @param    int
	 *
	 * @return   boolean
	 **/
	public function deleteTransportSourceByTransportId($transport_id)
	{
		$transport_id = intval($transport_id);

		if ($transport_id < 1) {
			$this->_error = '运输单ID不正确';
			return false;
		}

		if (false === $this->_db->delete($this->_table_transport_source, "transport_id='{$transport_id}'")) {
			$this->_error = '插入关联数据失败';
			return false;
		}

		return true;
	}
	
	/**
     * 获得运输单关联数据
     *
     * @param    string    $where
     * @return   array
     */
	public function getRelationOrder($where = 1)
	{
        return $this -> _db -> fetchAll("select * from {$this -> _table_transport_source} where {$where}");
	}

	/**
     * �������
     *
     * @param    array    $data
     * @param    string   $where
     * @return   void
     */
	public function update($data, $where)
	{
        $this -> _db -> update($this -> _table_transport, $data, $where);
	    return true;
	}

	/**
     * ɾ�����
     *
     * @param    array    $data
     * @return   void
     */
	public function delete($where)
	{
        $this -> _db -> delete($this -> _table_transport, $where);
	}

	/**
     * ��ӽ������
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertClear($data)
	{
        $this -> _db -> insert($this -> _table_transport_clear, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

	/**
     * ajax�������
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('tid = ?', $id);
		if ($id > 0) {
			$fields = array('logistic_no');
			if(in_array($field, $fields)){
		        $this -> _db -> update($this -> _table, $set, $where);
		    }
		    return true;
		}
	}

	/**
     * ȡ���͵����б�
     *
     * @param   int     $province
     * @param   int     $city
     * @param   int     $area
     * @return  void
     */
    public function getLogistic($where)
    {
        if ($where['area_id']) {
            $condition[] = "b.area_id = '{$where['area_id']}'";
        }
        if ($where['weight']) {
            $condition[] = "min < {$where['weight']} and max >= {$where['weight']}";
        }
        if ($where['is_cod']) {
            $condition[] = "b.cod = 1";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        if ($condition) {
            $sql = "select
                        b.logistic_code,b.zip,b.code,b.country_id,b.province_id,b.city_id,b.area_id,
                        b.country,b.open,b.delivery, b.province,b.city,b.area,b.cod,b.delivery_keyword,b.non_delivery_keyword,
                        c.price as price,a.name as logistic_name,a.cod_rate,a.cod_min,a.fee_service,a.logistic_code
                    from {$this->_table_logistic} a
                        left join {$this->_table_logistic_area} b  on a.logistic_code = b.logistic_code
                        left join {$this->_table_logistic_area_price} c on b.logistic_code=c.logistic_code and b.area_id=c.area_id

                    where 1=1 {$condition}";

            return $this->_db->fetchAll($sql);
        } else {
            return false;
        }
    }

	/**
     * ��Ӹ������
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertTrack($data)
	{
        $this -> _db -> insert($this -> _table_transport_track, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

	/**
     * ������Ż���ⲿ������
     *
     * @param    string    $logisticCode
     * @param    string    $logisticNo
     * @return   string
     */
	public function getExternalOrderSNByLogisticNo($logisticCode, $logisticNo)
	{
	    $row = $this -> _db -> fetchRow("select t1.bill_no,external_order_sn from {$this -> _table_transport} as t1 left join {$this -> _table_order} as t2 on t1.bill_no = t2.order_sn where logistic_code = '{$logisticCode}' and logistic_no = '{$logisticNo}'");
	    if ($row) {
	        if ($row['external_order_sn'])  return $row['external_order_sn'];
	        return $row['batch_sn'];
	    }
	}

	/**
     * ��ô�����
     *
     * @param    string $sql
     * @return   array
     */
	public function getPrepareOrderList($whereSql)
	{
	    $sql = "select t1.*,t2.shop_id,t2.lid,t3.shop_name from {$this -> _table_order_batch} as t1
	            inner join {$this -> _table_order} as t2 on t1.order_id = t2.order_id
	            left join {$this -> _table_shop} as t3 on t2.shop_id = t3.shop_id
	            where {$whereSql}
	            order by t1.add_time desc";
	    $datas = $this -> _db -> fetchAll($sql);
	    if (!$datas)    return false;

	    $whereSql = '0';
	    $batchSNArray = array();
	    foreach ($datas as $data) {
	        $whereSql .= " or bill_no = '{$data['batch_sn']}'";
	        $batchSNArray[] = $data['batch_sn'];
	        $orders[$data['batch_sn']] = $data;
	    }

	    $sql = "select bill_no from {$this -> _table_transport} where ({$whereSql}) and is_cancel < 2";
	    $datas = $this -> _db -> fetchAll($sql);
	    if ($datas) {
	        $batchSNArray1 = array();
	        foreach ($datas as $data) {
	            $batchSNArray1[] = $data['bill_no'];
	        }
	        $batchSNArray = array_diff($batchSNArray, $batchSNArray1);
	    }

	    if ($batchSNArray && count($batchSNArray) > 0) {
	        $batchSNArray1[] = array();
    	    $whereSql = '0';
    	    foreach ($batchSNArray as $$batchSN) {
    	        $whereSql .= " or bill_no like '%{$batchSN}%'";
    	    }
    	    $sql = "select bill_no from {$this -> _table_transport} where ({$whereSql}) and is_cancel < 2";
    	    $datas = $this -> _db -> fetchAll($sql);
    	    if ($datas) {
    	        foreach ($datas as $data) {
    	            $tempArr = explode(',', $data['bill_no']);
    	            foreach ($tempArr as $bill_no) {
    	                $bill_no = explode('-', $bill_no);
    	                $batchSNArray1[] = $bill_no[0];
    	            }
    	        }
    	        $batchSNArray = array_diff($batchSNArray, $batchSNArray1);
    	    }
        }

	    if ($batchSNArray && count($batchSNArray) > 0) {
	        foreach ($batchSNArray as $batchSN) {
	            $result[] = $orders[$batchSN];
	        }

	        $orderDB = new Admin_Models_DB_Order();
	        $product = $orderDB -> getOrderBatchGoodsInBatchSN($batchSNArray);
	    }

	    return array('data' => $result, 'product' => $product);
	}
	
	/**
	 * 添加发票号
	 */
	public function addInvoiceNo($invoiceNo, $amount)
	{
        $data = $this -> _db -> fetchRow("select * from {$this -> _table_finance_invoice} where invoice_no = '{$invoiceNo}'");
        if ($data) {
            if ($data['status'] != 1 || $data['amount'] != $amount) {
                $this -> _db -> update($this -> _table_finance_invoice, array('status' => 1, 'amount' => $amount), "invoice_no = '{$invoiceNo}'");
            }
        }
        else {
            $data = array('invoice_no' => $invoiceNo,   
                          'amount' => $amount,
                          'status' => 1,
                          'add_time' => time(),
                         );
            $this -> _db -> insert($this -> _table_finance_invoice, $data);
        }
	}
	
	/**
	  * 根据validate_sn 获取 Mobile and tel
	  */
	  public function getMobileByValidateSn($validate_sn){
	  	$sql="select tel,mobile from ".$this->_table_transport." where logistic_status=1 and send_time < ". (time()-12*60*60) ." and validate_sn=".$validate_sn;
	  	$datas = $this -> _db -> fetchAll($sql);
	  	return $datas;
	  }

	/**
	* 返回错误信息
	*
	* @return   string
	*/
	public function getError()
	{
		return $this->_error;	
	}

}