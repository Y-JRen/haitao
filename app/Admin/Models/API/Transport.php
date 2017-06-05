<?php
class Admin_Models_API_Transport
{
	/**
	 * DB对象
	 * @var Admin_Models_DB_Transport
	 */
	private $_db = null;

	/**
     * Admin certification
     * @var array
     */
	public $_auth = null;

    /**
     * 错误信息
     */
	private $error;

	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */

	private $_search_option = array(
		'logistic_status' => array(
			'0' => '未出仓',
			'1' => '在途',
			'2' => '签收',
			'3' => '拒收',
		),
		'cod_status' => array(
			'0' => '非代收',
			'1' => '代收',
		),
	);
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Transport();
		$this -> _op = new Admin_Models_DB_StockOp();
    	$this -> _order = new Admin_Models_API_Order();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}

	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @param    string    $orderBy
     * @return   array
     */
	public function get($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null, $includeOrder = false)
	{
		$datas = $this -> _db -> get($where, $fields, $page, $pageSize, $orderBy, $includeOrder);
        return $datas;
	}

	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    int       $page
     * @param    int       $pageSize
     * @param    string    $orderBy
     * @return   array
     */
	public function getTrack($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		$datas = $this -> _db -> getTrack($where, $fields, $page, $pageSize, $orderBy);
        return $datas;
	}

	/**
     * 获取总数
     *
     * @return   int
     */
	public function getCount()
	{
		return $this -> _db -> total;
	}
	
	/**
     * 获取总金额
     *
     * @return   float
     */
	public function getAmount()
	{
		return $this -> _db -> amount;
	}

	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    string   $where
     * @return   array
     */
	public function update($data, $where, $type = null)
	{
        return $this -> _db -> update($data, $where, $type);
	}

	/**
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
        $where && $where = " and $where";
        return $this -> _op -> getOp("item='transport' $where");
	}

	/**
     * 添加操作日志记录
     *
     * @return   int      lastInsertId
     */
	public function addOp($item_id, $admin_name, $op_type, $remark = null)
	{
		$this -> _op -> insertOp('transport', $item_id, $admin_name, $op_type, $remark);
	}

	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   string
     */
	public function add($data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());

        $data = Custom_Model_Filter::filterArray($data, $filterChain);

	    $transport = $data['transport'];

		if ($transport['tel'] == '' && $transport['mobile'] == '') {
			$this -> error = 'no_contact';
			return false;
		}
		if (!$data['logistic']) {
			$this -> error = 'no_logistic';
			return false;
		}

		if (!$transport['logistic_no']) {
			$this -> error = 'no_logistic_no';
			return false;
		}

		$sel = Zend_Json::decode(stripslashes($data['logistic']));

		$transport['province'] = $sel['province'];
		$transport['city'] = $sel['city'];
		$transport['area'] = $sel['area'];
		$transport['search_mod'] = $sel['search_mod'];
		$transport['logistic_name'] = $sel['logistic_name'];
		$transport['logistic_code'] = $sel['logistic_code'];
		$transport['logistic_fee_service'] = $sel['fee_service'];
		$transport['zip'] = $sel['zip'];
		$sel['price'] && $transport['logistic_price'] = $sel['price'];
		$sel['cod_price'] && $transport['logistic_price_cod'] = $sel['cod_price'];

		$transport['is_assign'] = 1;
		$transport['logistic_status'] = 1;
		$transport['add_time'] = time ();
		$transport['bill_no'] = Custom_Model_CreateSn::createSn();
		$transport['admin_name'] = $this -> _auth['admin_name'];
		$transport['logistic_list'] = stripslashes($transport['logistic_list']);

	    $this -> _db -> insert($transport);
		return true;
	}

	/**
     * 派单
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function assign($data, $id)
	{
        $r = Zend_Json::decode(stripslashes($data['logistic']));
        $set = array(
        	         'is_assign' => '1',
                     'lock_name' => '',
                     'number' => $data['number'],
                     'logistic_name' => $r['logistic_name'],
                     'logistic_code' => $r['logistic_code'],
                     'logistic_price' => $r['price'],
                     'logistic_fee_service' => $r['fee_service'],
                     'logistic_price_cod' => $r['cod_price'],
                     'search_mod' => $r['search_mod'],
                   );

	    if ($this -> _db -> update($set, "tid =$id and is_assign=0 and lock_name='{$this->_auth['admin_name']}'")) {
	        $this -> addOp($id, $this -> _auth['admin_name'], 'assign', $data['remark']);
	    }

        //订单接口处理
        if ($data['bill_type'] == 1) {
            $this -> _order -> toStock($data['bill_no'], 'assign', $r);
        }
	    return true;
	}

	public function autoAssign($batch_sn)
	{
	    $datas = $this -> get("t1.bill_no = '{$batch_sn}'");

	    $data = $datas[0];
	    $r = Zend_Json::decode($data['logistic_list']);
	   
	    if ($r){
	        $param= array(
	                'bill_type'=>1,
	                'bill_no'=>$batch_sn,
	                'number'=>1,
	        );
	        foreach ($r['list'] as $k => $v) {
	            if($k=='sf'){
	                foreach($v as &$vv){
	                    $vv = urlencode($vv);
	                }
	                $param['logistic'] = urldecode(Zend_Json::encode($v));
	            }
	        }
	        return $this -> assign($param,$data['tid']);
	    }
	    return false;
	}
	/**
     * 返回派单
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function backAssign($id)
	{
	    $set = array('is_assign' => '0',
                     'lock_name' => '',
                   );

	    $this -> _db -> update($set, "tid =$id and is_assign=1 and lock_name='{$this->_auth['admin_name']}'");
	}

	/**
     * 重新派单
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function reassign($data, $id)
	{
        $logistic = Zend_Json::decode(stripslashes($data['logistic']));

	    $r = array_shift($this -> get("tid =$id"));
        $row['is_assign'] = 1;
	    $row['add_time'] = time();
        $row['admin_name'] = $this -> _auth['admin_name'];
        $row['logistic_name'] = $logistic['logistic_name'];
        $row['logistic_code'] = $logistic['logistic_code'];
        $row['logistic_price'] = $logistic['logistic_price'];
        $row['logistic_fee_service'] = $logistic['logistic_fee_service'];
        $row['logistic_price_cod'] = $logistic['logistic_price_cod'];
        $row['search_mod'] = $logistic['search_mod'];

        $row['bill_no'] = $r['bill_no'];
        $row['bill_type'] = $r['bill_type'];
        $row['logistic_list'] = $r['logistic_list'];
        $row['consignee'] = $r['consignee'];
        $row['province'] = $r['province'];
        $row['city'] = $r['city'];
        $row['area'] = $r['area'];
        $row['province_id'] = $r['province_id'];
        $row['city_id'] = $r['city_id'];
        $row['area_id'] = $r['area_id'];
        $row['address'] = $r['address'];
        $row['zip'] = $r['zip'];
        $row['tel'] = $r['tel'];
        $row['mobile'] = $r['mobile'];
        $row['print_remark'] = $r['note_print'];
        $row['remark'] = $r['note_logistic'];
        $row['amount'] = $r['amount'];
        $row['weight'] = $r['weight'];
        $row['volume'] = $r['volume'];
        $row['goods_number'] = $r['goods_number'];
        $row['is_cod'] = $r['is_cod'];
        $row['logistic_status'] = 0;

        if ($data['new_logistic_no']) {
            $logistic['logistic_no'] = $data['new_logistic_no'];
            $row['logistic_status'] = 1;
            $row['logistic_no'] = $data['new_logistic_no'];
        }
	    $this -> _db -> insert($row);

        $set = array(
        	         'is_cancel' => '2',
                   );
	    if ($this -> _db -> update($set, "tid =$id")) {
	        $this -> addOp($id, $this -> _auth['admin_name'], 'reassign', $data['remark']);
	    }
        //订单接口处理
        if ($data['bill_type'] == 1) {
            $data['new_logistic_no'] && $logistic['logistic_no'] = $data['new_logistic_no'];
            $this -> _order -> toStock($data['bill_no'], 'assign', $logistic);
        }
	    return true;
	}

	/**
     * 订单跟踪
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function track($data)
	{
		$set = array ('lock_name' => '', 'logistic_status' => $data['logistic_status'], 'logistic_list' => '' );
		if (in_array($data['logistic_status'], array(2,3))) {
		    $set['is_complain'] = 0;
		    $set['validate_sn'] = 0;
		}
		if($data['auto_track']) {
		    if($data['op_time'] <= $data['track_time']){
		        return false;
		    }else{
		    	$set['track_time'] = $data['op_time'];
		    	$set['is_track'] = 1;
		    }
		}else{
			$data['logistic_status'] > 1 && $set['is_track'] = 1;
		}
        
        $transport = array_shift($this -> get("bill_no = '{$data['bill_no']}'"));
        if ($transport['is_cod'] && $data['logistic_status'] == 3) {
            $set['amount'] = 0;
            $set['change_amount'] = 0;
        }
		if($this -> _db -> update($set, "bill_no='{$data['bill_no']}' and is_cancel=0")) {
		    !$data['op_time'] && $data['op_time'] = time();
		    !$data['admin_name'] && $data['admin_name'] = $this -> _auth['admin_name'];
		    $row = array(
		    	        'item_no' => $data['bill_no'],
		    	        'logistic_no' => $data['logistic_no'],
					    'logistic_code' => $data['logistic_code'],
				        'logistic_status' => $data['logistic_status'],
				        'op_time' => $data['op_time'],
				        'admin_name' => $data['admin_name'],
				        'remark' => $data['remark'],
					    );
		    $this -> _db -> insertTrack($row);
		    //已签收接口处理
	        if ($data['bill_type'] == 1 && $data['logistic_status'] > 1) {
	        	switch ($data['logistic_status']){
	        		case '2':
	        			$return = 'signed';
	        		    break;
	        		case '3':
	        			$return = 'reject';
	        		    break;
	        	}
	            $this -> _order -> toStock($data['bill_no'], $return, $data);
	        }
		}
		return true;
	}

	/**
     * 运输单跟踪批量维护
     *
     * @param    array    $data
     * @return   void
     */
	public function trackBatch($data)
	{
		if($data['ids']) {
			$add_time = time ();
			$ids = implode(',', $data['ids']);

			$itemNos = $this -> get('tid in ('.$ids.')');
			foreach($itemNos as $v){
				$data = array(
				    'bill_no' => $v['bill_no'],
	    	        'bill_type' => $v['bill_type'],
	    	        'logistic_no' => $v['logistic_no'],
				    'logistic_code' => $v['logistic_code'],
			        'logistic_status' => $data['logistic_status'],
			        'op_time' => time(),
			        'admin_name' => $this -> _auth['admin_name'],
			        'remark' => $data['remark'],
				    );
				$this -> track($data);
			}
		    return true;
	    }
	}

	/**
     * 运输单结算导入
     *
     * @param    array    $data
     * @return   void
     */
	public function importClear($data)
	{
    	if ($data['type'] != 'application/vnd.ms-excel') {
           $this -> error = '上传文件格式错误';
           return false;
        }

        if (!$data['tmp_name']) {
            $this -> error = '上传文件失败';
            return false;
        }
        $path = Zend_Registry::get('systemRoot');

        $ext = strtolower(trim(substr(strrchr($data['name'], '.'), 1, 10)));
        $file = $path.'/tmp/'.substr(md5(microtime()), 20).'.'.$ext;

        move_uploaded_file($data['tmp_name'], $file);
        @chmod($file, 0644);

        require_once 'Excel/Reader.php';

        $data = new Spreadsheet_Excel_Reader();

        $data -> setOutputEncoding('UTF-8');

        $data -> read($file);

        error_reporting(E_ALL ^ E_NOTICE);

	    for ($i = 2; $i <= $data -> sheets[0]['numRows']; $i++) {
			for ($j = 1; $j <= $data -> sheets[0]['numCols']; $j++) {
				$d[] = $data -> sheets[0]['cells'][$i][$j];
				if ($j == 1) {
				    $id = $data -> sheets[0]['cells'][$i][$j];
				}
			}
			$datas[$id] = $d;
			unset($d);
		}

		foreach ($datas as $k => $v) {
			$row = array(
			             'logistic_code' => strtolower($v[0]),
			             'logistic_no' => $v[1],
			             'logistic_cost' => $v[2],
			             );
			$row && $this -> _db -> insertClear($row);

			unset($row);
		}
		@unlink($file);
		return true;
	}

	/**
     * 关键字维护
     *
     * @param    int      $id
     * @return   void
     */
	public function protect($id)
	{
		$set = array ('is_protect' => 1, 'lock_name' => '');
		if($this -> _db -> update($set, "tid =$id")) {
		    $this -> addOp($id, $this -> _auth['admin_name'], 'protect', $data['remark']);
		}
		return true;
	}

	/**
     * 确认
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function confirm($data, $id ,$logistic_no = '')
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());

        $data = Custom_Model_Filter::filterArray($data, $filterChain);

        if($logistic_no!=''){
             $set = array ('is_confirm' => 1, 'lock_name' => '','logistic_no'=>$logistic_no);
        }else{
             $set = array ('is_confirm' => 1, 'lock_name' => '');
        }

	    if($data['bill_type'] == 2){
	        $set['send_time'] = time();
	        //跟踪接口
	    }
	    if ($this -> _db -> update($set, "tid =$id and is_confirm=0 and lock_name='{$this->_auth['admin_name']}'")) {
	        $this -> addOp($id, $this -> _auth['admin_name'], 'confirm', $data['remark']);
			if($data['bill_type'] == 1 && $data['bill_no']){
				$outStock = new Admin_Models_DB_OutStock();
				$set = array('bill_status' => '4');
				if ($logistic_no) {
				    $set['logistic_no'] = $logistic_no;
				}
				$outStock -> update($set, "bill_no='{$data['bill_no']}' and bill_status=3 and is_cancel=0");
			}
	    }
	    return true;
	}

	/**
     * 填充运单号
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function fill($data, $id, $logistic_no)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);

        $set = array('logistic_no'=> $logistic_no);
        $this -> _db -> update($set, "tid = {$id} and is_confirm=0 and lock_name='{$this->_auth['admin_name']}'");

        if (!$data['bill_no']) {
            $transport = array_shift($this -> _db -> get("tid = {$id}"));
            if ($transport) {
                $data['bill_no'] = $transport['bill_no'];
            }
            else {
                return false;
            }
        }

        $outStock = new Admin_Models_DB_OutStock();
        $outStock -> update($set, "bill_no='{$data['bill_no']}' and bill_status=3 and is_cancel=0");
	}
	
	/**
     * 填充发票号
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function fillInvoice($data, $id, $invoice_no)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        $orderAPI = new Admin_Models_DB_Order();
        
        $batchSNArray = $this -> getMergeSplitOrderSNArray($data['bill_no']);
        $amount = 0;
        foreach ($batchSNArray as $batchSN) {
            $order = $orderAPI -> getOrderBatchInfoByOrderBatchSn($batchSN);
            $amount += $order['price_pay'] - $order['point_payed'] - $order['account_payed'] - $order['gift_card_payed'] - $order['price_logistic'];
        }
        
        $set = array('invoice_no'=> $invoice_no);
        $this -> _db -> update($set, "tid = {$id} and is_confirm=0 and lock_name='{$this->_auth['admin_name']}'");
        
        $this -> _db -> addInvoiceNo($invoice_no, $amount);
	}

	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());

		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }
		}
	}

	public function changeToRMB($Val)
	{
		 $str=array();
		 $str[0]="零";
		 $str[1]="壹";
		 $str[2]="贰";
		 $str[3]="叁";
		 $str[4]="肆";
		 $str[5]="伍";
		 $str[6]="陆";
		 $str[7]="柒";
		 $str[8]="捌";
		 $str[9]="玖";
		 $oldval=$Val;
		 $Val=str_replace(",","",$Val);
		 $Val=number_format($Val,2);
		 $Val=str_replace(".","",$Val);
		 $Val=str_replace(",","",$Val);
		 $f=$Val;
		 $Val=abs($Val);
		 if($f!=$Val) $m="负";
		 for ($i=1 ;$i<= strlen($Val);$i++)
		 {
		  $mynum=substr($Val,$i-1,1);
		  switch (strlen($Val)+1-$i)
		  {
		   case 1:
		   $k= $mynum."分"; break;
		   case 2:
		   $k= $mynum."角"; break;
		   case 3:
		   $k= $mynum."元"; break;
		   case 4:
		   $k= $mynum."拾"; break;
		   case 5:
		   $k= $mynum."佰"; break;
		   case 6:
		   $k= $mynum."仟"; break;
		   case 7:
		   $k= $mynum."万"; break;
		   case 8:
		   $k= $mynum."拾"; break;
		   case 9:
		   $k= $mynum."佰"; break;
		   case 10:
		   $k= $mynum."仟"; break;
		   case 11 :
		   $k= $mynum."亿"; break;
		   case 12 :
		   $k= $mynum."拾"; break;
		   case 13:
		   $k= $mynum."佰"; break;
		   case 14:
		   $k= $mynum."仟"; break;
		  }
		  $m .=$k;
		 }
		 foreach($str as $key=>$val)
		 {
		     $m = str_replace($key,$val,$m);
		 }

		 return $m.'整';
	}

	/**
     * 匹配物流公司
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
    public function getLogistic($where)
    {
        $amount = $where['amount'];
        $address = $where['address'];
        $weight = $where['weight'];
        $volume = $where['volume'];
        $number = $where['number'];
        $isCOD = $where['is_cod'];
        unset($where['amount'], $where['address'], $where['volume'], $where['number'], $where['is_cod']);
        !$where['area_id'] && exit;
        $data = $this -> _db -> getLogistic($where);


        if ($data) {
            foreach ($data as $k => $v) {
                $logisticCode = $v['logistic_code'];
                $zip = $v['zip'];
                $code = $v['code'];
                $countryID = $v['country_id'];
                $provinceID = $v['province_id'];
                $cityID = $v['city_id'];
                $areaID = $v['area_id'];
                $country = $v['country'];
                $open = $v['open'];//是否开通
                $delivery = $v['delivery'];//是否上门派送
                $province = $v['province'];
                $city = $v['city'];
                $area = $v['area'];
                $cod = $v['cod'];
                $deliveryKeyword = trim($v['delivery_keyword']);
                $nonDeliveryKeyword = trim($v['non_delivery_keyword']);
                $price = $v['price'];
                $logisticName = $v['logistic_name'];
                $codRate = $v['cod_rate'];
                $codMin = $v['cod_min'];
                $feeService = $v['fee_service'];

                //代收款费率
                $pay = $amount;
                $data[$k]['cod_price'] = $v['cod_rate'] * $pay;
                if ($data[$k]['cod_price'] < $v['cod_min']) {
                    $data[$k]['cod_price'] = $v['cod_min'];
                }
            }
        }
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v['cod']) {
                    if (!$tmp['cod'] || ($v['is_send'] == 'OK' && $v['price'] > 0 && (($v['price'] + $v['cod_price']) < ($tmp['cod']['price'] + $tmp['cod']['cod_price'])))) {
                        $tmp['cod'] = array('logistic_code' => $v['logistic_code'],
                                            'price' => $v['price'],
                                            'cod_price' => $v['cod_price']);
                    }
                    if ($v['logistic_code'] == 'ems') {
                         $emsCod = true;
                    } else {
                         $notEmsCod = true;
                    }
                }
                if (!$tmp['all'] || ($v['is_send'] == 'OK' && $v['price'] > 0 && $v['price'] < $tmp['all']['price'])) {
                    $tmp['all'] = array('logistic_code' => $v['logistic_code'], 'price' => $v['price']);
                }
                $logistic['list'][$v['logistic_code']] = $v;
                $province = $v['province'];
                $city = $v['city'];
                $area = $v['area'];
                $zip = $v['zip'];
            }
            $areaID = $where['area_id'];
        }
        if ($logistic['list']) {
            if (count($logistic['list']) > 1) {
                $name = '普通快递';
                $cod = $notEmsCod;
            } else if ($logistic['list']['ems']) {
                $name = 'EMS';
                $cod = $emsCod;
            }
            $logistic['other'] = array('name' => $name,
                                       'cod' => intval($cod),
                                       'zip' => $zip,
                                       'province' => $province,
                                       'city' => $city,
                                       'area' => $area,
                                       'area_id' => $areaID,
                                       'address' => $address,
                                       'amount' => $amount,
                                       'volume' => $volume,
                                       'number' => $number,
                                       'weight' => $weight,
                                       'default' => $logistic['list'][$tmp['all']['logistic_code']]['logistic_code'],
                                       'default_cod' => $logistic['list'][$tmp['cod']['logistic_code']]['logistic_code']);
        }

        return $logistic;
    }

	/**
     * 锁定/解锁数据
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function lock($datas, $val)
	{
		if (is_array($datas['ids'])) {
			foreach($datas['ids'] as $v){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($val) {
			    	$set = array('lock_name' => $admin_name);
			    	$where = "lock_name=''";
			    } else {
			    	$set = array('lock_name' => '');
			    	$where = ($this -> _auth['group_id'] > 1 ) ? "lock_name='$admin_name'" : '1';
			    }
	    		$this -> _db -> update($set, "$where and tid=$v");
			}
		}
        return true;
	}

	/**
     * 导出运输单数据
     *
     * @return void
     */
    public function export($where, $act = '')
    {
		$datas = $this -> search($where, $act,null,null,null,true);
		
        $excel = new Custom_Model_Excel();
		$excel -> send_header('transport.xls');
		$excel -> xls_BOF();

    	
        $title = array('单据编号', '订单金额', '收货人', '联系电话','手机','详细地址','邮编', '商品名称', '数量', '单价');
        $col = count($title);

        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
        if(count($datas['datas']) <= 0){
            $excel -> xls_EOF();
        }
        
        $rowNum = 1;
		foreach ($datas['datas'] as $k => $v) {
		    foreach($v['product'] as $kk => $vv){
		        if($kk == 0){
		            $row = array(
		                    $v['bill_no'],
		                    $v['price_order'],
		                    $v['consignee'],
		                    $v['tel'],
		                    $v['mobile'],
		                    $v['province'].$v['city'].$v['area'].$v['address'],
		                    $v['addr_zip'],
		                    $vv['goods_name'],
		                    $vv['number'],
		                    $vv['sale_price'],
		                    );
		        }else{
		            $row = array(
		                    '',
		                    '',
		                    '',
		                    '',
		                    '',
		                    '',
		                    '',
		                    $vv['goods_name'],
		                    $vv['number'],
		                    $vv['sale_price'],
		            );
		        }
		        for ($i = 0; $i < $col; $i++) {
		            $excel -> xls_write_label($rowNum, $i, $row[$i]);
		        }
		        $rowNum++;
		        flush();
		        ob_flush();
		        unset($row);
		    }
        }
        unset($datas);
		$excel -> xls_EOF();
    }

	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'forbidden'=>'禁止操作!',
			         'no_contact'=>'请填写至少一个联系方式!',
			         'no_amount'=>'请填写变更后金额!',
			         'no_logistic'=>'请选择物流公司!',
			         'no_logistic_no'=>'请填写运单号!',
			         'no_remark'=>'请填写理由!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}

	/**
     * 查询动作
     *
     * @param    array  $where
     * @param    int    $act
     * @param    int    $page
     *
     * @return   void
     */
    public function search($where, $act = null, $page = null, $pageSize = null, $order_by = null, $includeOrder = false)
    {
		$whereSql = "1=1";
		if (is_array($where)) {
	        $where['filter'] && $whereSql .= $where['filter'];
	        $where['bill_type'] && $whereSql .= " and t1.bill_type={$where['bill_type']}";
	        $where['admin_name'] && $whereSql .= " and t1.admin_name LIKE '%" . $where['admin_name'] . "%'";
		    if ($where['send_type'] != '') {
			    if ($where['send_type'] == 'ems') {
			    $whereSql .= " and t1.logistic_code='ems'";
			    }else{
			    $whereSql .= " and t1.logistic_code<>'ems'";
			    }
		    }
		    $where['cod_status'] != '' && $whereSql .= " and t1.cod_status=".$where['cod_status'];
		    $where['is_cod'] != '' && $whereSql .= " and t1.is_cod={$where['is_cod']}";
		    $where['is_protect'] != '' && $whereSql .= " and t1.is_protect={$where['is_protect']}";
		    $where['is_complain'] != '' && $whereSql .= " and t1.is_complain={$where['is_complain']}";
		    $where['bill_no'] && $whereSql .= " and (t1.bill_no LIKE '%" . trim($where['bill_no']) . "%')";
		    $where['logistic_no'] && $whereSql .= " and t1.logistic_no LIKE '%" . trim($where['logistic_no']) . "%'";
		    $where['consignee'] && $whereSql .= " and t1.consignee LIKE '%" . $where['consignee'] . "%'";
		    $where['search_mod'] && $whereSql .= " and t1.search_mod = '" . $where['search_mod'] . "'";
		    $where['province_id'] && $whereSql .= " and t1.province_id = '" . $where['province_id'] . "'";
		    $where['city_id'] && $whereSql .= " and t1.city_id = '" . $where['city_id'] . "'";
		    $where['area_id'] && $whereSql .= " and t1.area_id = '" . $where['area_id'] . "'";
            $where['logistic_status'] !='' && $whereSql .= " and t1.logistic_status = '" . $where['logistic_status'] . "'";
		    $where['logistic_code'] && $whereSql .= " and t1.logistic_code = '" . $where['logistic_code'] . "'";
		    $where['validate_sn'] && $whereSql .= " and t1.validate_sn = '" . $where['validate_sn'] . "'";
		    if ($where['invoice'] !== '' && $where['invoice'] !== null) {
		        if ($where['invoice']) {
		            $whereSql .= " and t1.invoice_type > 0";
		        }
		        else {
		            $whereSql .= " and t1.invoice_type = 0";
		        }
		    }
		    if ($where['sub_code']) {
		        if ($where['sub_code'] == 'jiankang') {
		            $where['shop_id'] = 1;
		        }
		        else if ($where['sub_code'] == 'in_call') {
		            $where['shop_id'] = 2;
		            $where['type'] = 10;
		        }
		        else if ($where['sub_code'] == 'out_call') {
		            $where['shop_id'] = 2;
		            $where['type'] = 11;
		        }
		        else if ($where['sub_code'] == 'tq_call') {
		            $where['shop_id'] = 2;
		            $where['type'] = 12;
		        }
		        else if ($where['sub_code'] == 'employee') {
		            $where['shop_id'] = 3;
		        }
		        else if ($where['sub_code'] == 'dangdang') {
		            $where['shop_id'] = 51;
		        }
		        else if ($where['sub_code'] == 'baidu') {
		            $where['shop_id'] = 98;
		        }
		        else if ($where['sub_code'] == 'other') {
		            $where['shop_id!'] = '1,2,3,51,98';
		        }
		    }
            if ($where['fromdate'] && $where['todate']) {
			    $fromdate = strtotime($where['fromdate']);
			    $todate = strtotime($where['todate']) + 86400;
			    if($fromdate <= $todate) {
			        if ($act == ''){
					    $whereSql .= " and (t1.send_time between $fromdate and $todate)";
				    }else{
				    	$whereSql .= " and (t1.send_time between $fromdate and $todate)";
				    }
				}
	        }
	        if ($where['is_lock']) {
		    	$lock_name = $where['is_lock'] == 'yes' ? $this -> _auth['admin_name'] : '';
		    	$whereSql .= " and t1.lock_name = '" . $lock_name . "'";
		    }
		    if ($where['shop_id'] !== '' && $where['shop_id'] !== null) {
		        $whereSql .= " and t1.shop_id='{$where['shop_id']}'";
		    }
		    if ($where['shop_id!']) {
		        $whereSql .= " and t1.shop_id not in ({$where['shop_id!']})";
		    }
		    if ($where['type']) {
		        $whereSql .= " and t3.type = '{$where['type']}'";
		        $includeOrder = true;
		    }
		    if ($where['lid']) {
		        $whereSql .= " and t1.lid = '{$where['lid']}'";
		    }
		    if ($where['ids']) {
		        $id_in = implode(',', $where['ids']);
		        $whereSql .= " and t1.tid in ($id_in) ";
		    }
		} else {
			$whereSql = $where;
		}
		
		if($where['bill_status']) {
			switch($where['bill_status']){
				case '1':
					$whereSql .= " and t1.is_assign=0";
					break;
				case '2':
					$whereSql .= " and t1.is_assign=1 and t1.is_confirm=0";
					break;
				case '3':
					$whereSql .= " and t1.is_confirm=1";
					break;
			}
		}
		
		switch($act){
		    case 'search-list':
				$whereSql .= " and t1.is_cancel<2 ";
				break;
			case 'assign-list':
				$whereSql .= " and t1.is_assign=0 and t1.is_cancel=0";
				break;
			case 'confirm-list':
				$whereSql .= " and t1.is_assign=1 and t1.is_confirm=0 and t1.is_cancel=0";
				break;
			case 'track-list':
				$whereSql .= " and t1.is_confirm=1 and t1.is_cancel=0 and t1.logistic_status = 1 ";
				$orderBy = ($order_by != null) ? $order_by : "complain_time desc,is_complain desc,tid desc";
				break;
			case 'change-track-list':
				$whereSql .= " and t1.is_confirm=1 and t1.is_cancel=0 and t1.logistic_status>=1";
			    $orderBy = "complain_time desc,is_complain desc,tid desc";
				break;
			case 'cod-list':
				$whereSql .= " and t1.is_cod=1 and t1.is_cancel=0 and t1.logistic_code <> 'self' and t1.logistic_code <> 'externalself'";
				break;
	        case 'cod-change-list':
				$whereSql .= " and t1.is_cod=1 and t1.is_cancel=0 and t1.logistic_code <> 'self' and t1.logistic_code <> 'externalself' and t1.logistic_status = 1";
				break;
			case 'keywords':
				$whereSql .= " and t1.is_confirm=1 and t1.logistic_status>1 and t1.is_cancel=0";
			    $orderBy = "is_protect,tid desc";
				break;
	        case 'pick-up-list':
				$whereSql .= " and t1.is_cancel = 0 and t1.is_assign = 1 and t1.is_confirm = 1 and t1.logistic_status = 1 ";
				$orderBy = "send_time";
				break;
			default:
				$whereSql .= " and t1.is_cancel<2";
		}
		
        $datas = $this -> get($whereSql, $includeOrder ? 't1.*,t3.type,t3.price_order,t3.addr_zip,t3.addr_eng_address' : 't1.*', $page, $pageSize, $orderBy, $includeOrder);
        if ($datas) {
            foreach ($datas as $index => $data) {
                $datas[$index]['bill_no_str'] = str_replace(',', '<br>', $data['bill_no']);
                $tmp[] = $data['bill_no'];
            }
            $orderApi = new Admin_Models_API_Order();
            $orderProducts = $orderApi -> getProductByBatch($tmp);
            if($orderProducts){
                $tmp = array();
                foreach ($datas as $k => $v){
                    if(array_key_exists($v['bill_no'], $orderProducts)){
                        $datas[$k]['product'] = $orderProducts[$v['bill_no']];
                    }
                }
            }
        }
        $result['datas'] = $datas;
        $result['total'] = $this -> getCount();
        $result['amount'] = $this -> getAmount();
        return $result;
    }

    /**
     * 按物流号获得外部订单号
     *
     * @param    string    $logisticCode
     * @param    string    $logisticNo
     * @return   string
     */
	public function getExternalOrderSNByLogisticNo($logisticCode, $logisticNo)
	{
	    return $this -> _db -> getExternalOrderSNByLogisticNo($logisticCode, $logisticNo);
	}

	/**
     * 获得待配货订单
     *
     * @param    array    $search
     * @return   array
     */
	public function getPrepareOrderList($where)
	{
	    $whereSQL = "t1.status = 0 and t1.status_logistic = 2";
	    if ($where['fromdate']) {
	        $fromDate = strtotime($where['fromdate']);
	        $whereSQL .= " and t1.add_time >= {$fromDate}";
	    }
	    if ($where['todate']) {
	        $todate = strtotime($where['todate']) + 86400;
	        $whereSQL .= " and t1.add_time < {$todate}";
	    }
	    if ($where['addr_consignee']) {
            $whereSQL .= " and t1.addr_consignee = '{$where['addr_consignee']}'";
        }
        if ($where['batch_sn']) {
            $whereSQL .= " and t1.batch_sn = '{$where['batch_sn']}'";
        }
        if ($where['lid']) {
            $whereSQL .= " and t2.lid = '{$where['lid']}'";
        }
        if ($where['ids']) {
            if (is_array($where['ids'])) {
                $whereSQL .= " and t1.batch_sn in ('".implode("','", $where['ids'])."')";
            }
            else {
                $whereSQL .= " and t1.batch_sn in ('".implode("','", $where['ids'])."')";
            }
        }
        if ($where['is_lock'] == 'yes') {
            $whereSQL .= " and t1.lock_name = '{$this->_auth['admin_name']}'";
        }
        else if ($where['is_lock'] == 'no') {
            $whereSQL .= " and (t1.lock_name = '' or t1.lock_name is null)";
        }

        $datas = $this -> _db -> getPrepareOrderList($whereSQL);
        //$datas['data'] = $this -> getCanMergeOrderList($datas['data']);

        return $datas;
	}

	/**
     * 获得可以合并的订单
     *
     * @param    array    $datas
     * @return   array
     */
	public function getCanMergeOrderList($datas)
	{
	    if (!$datas)    return false;

	    $orderColorArray = array('#000080', '#800080', '#800000', '#808000', '#008080', '#00FF00');

	    foreach ($datas as $order) {
	        $key = $this -> getMergeKey($order);
    	    $orderInfo[$key][]  = 1;
	    }

	    $colorIndex = 0;
	    $colorInfo = '';
	    foreach ($orderInfo as $key => $array) {
	        foreach ($datas as $index => $order) {
	            if ($key == $this -> getMergeKey($order)) {
	                if (count($orderInfo[$key]) > 1) {
	                    $order['can_merge'] = 1;
	                    if (!$colorInfo[$key]) {
	                        $colorInfo[$key] = $orderColorArray[$colorIndex];
	                        if (++$colorIndex > 5)  $colorIndex = 0;
	                    }
	                    $order['order_color'] = $colorInfo[$key];
	                }
	                $result[] = $order;
	            }
	        }
	    }

	    return $result;
	}

	public function getMergeKey($order)
	{
	    return $order['shop_id'].'-'.$order['type'].'-'.$order['addr_province_id'].'-'.$order['addr_city_id'].'-'.$order['addr_area_id'].'-'.$order['addr_address'].'-'.$order['addr_consignee'];
	}

	/**
     * 返回配货
     *
     * @param    string     $bill_no
     * @return   void
     */
	public function prepareReturn($bill_no)
	{
	    $order_db   = new Admin_Models_DB_Order();
	    $outStockAPI = new Admin_Models_API_OutStock();
        
	    $isSplit = $this -> getSplitOrder($bill_no);
	    if ($isSplit) {
	        $datas = $this -> _db -> get("bill_no like '{$isSplit['batch_sn']}-%'");
	        foreach ($datas as $data) {
	            if ($data['is_assign'] != 0 || $data['is_cancel'] >= 2) {
	                return false;
	            }
	        }

	        foreach ($datas as $data) {
	            $outStockAPI -> delete($data['bill_no'], true);
	            $order_db -> updateOrderBatch(array('batch_sn' => $data['bill_no']), array('balance_amount' => 0));
	        }
	        $this -> _db -> delete("bill_no like '{$isSplit['batch_sn']}-%'");

            $relation_params = array(
                'like_order' => "{$isSplit['batch_sn']}-",    
            );
            $this->_db->deleteRelationOrderByOrderSn($relation_params);
	    }
	    else {
	        $data = array_shift($this -> _db -> get("bill_no = '{$bill_no}'"));
            
	        $outStockAPI -> delete($bill_no, true);
	        $this -> _db -> delete("bill_no = '{$bill_no}'");
            $bill_nos = explode(',', $bill_no);
            foreach ($bill_nos as $no) {
                $relation_params = array(
                    'bill_no' => $no,    
                );
                $this->_db->deleteRelationOrderByOrderSn($relation_params);
	        }
	        $order_db -> updateOrderBatch(array('batch_sn' => $bill_no), array('balance_amount' => 0));
	    }

		// @zhouyong 如果补货单没有完成，作废补货单
		$order_info = $order_db->getOrderBatchInfoByOrderBatchSn($bill_no);
		if (empty($order_info)) {
			return true;
		}

	    return true;
	}

	/**
     * 获得合单或拆单的订单号数组
     *
     * @param    string     $bill_no
     * @return   array
     */
	public function getMergeSplitOrderSNArray($bill_no)
	{
	    $tempArray = explode(',', $bill_no);

	    for ($i = 0; $i < count($tempArray); $i++) {
	        $tempArr = explode('-', $tempArray[$i]);
	        $result[$tempArray[$i]] = $tempArr[0];
	    }

	    return $result;
	}

    /**
     * 通过拆单订单号获得物流状态
     *
     * @return   void
     */
	public function getSplitOrder($bill_no, $datas = null, $statusIncludeSelf = true)
	{
	    if (!$datas) {
	        $tempArr = explode('-', $bill_no);
	        if (!$tempArr[1]) {
	            return false;
	        }
	        $batch_sn = $tempArr[0];
	        $datas = $this -> _db -> get("bill_no like '{$batch_sn}-%' and is_cancel in (0,1)");
	        if (!$datas)    return false;
	    }

	    $allAssign = true;$hasAssign = false;
	    $allConfirm = true;$hasConfirm = false;
	    $allSent = true;$hasSent = false;
	    $allSign = true;$hasSign = false;
	    $allReject = true;$hasReject = false;
	    foreach ($datas as $data) {
	        $billNoArray[] = $data['bill_no'];

	        if ($data['is_assign'] == 0) {
	            $allAssign = false;
	        }
	        else {
	            if ($statusIncludeSelf || $data['bill_no'] != $bill_no) {
	                $hasAssign = true;
	            }
	        }

	        if ($data['is_confirm'] == 0) {
	            $allConfirm = false;
	        }
	        else {
	            if ($statusIncludeSelf || $data['bill_no'] != $bill_no) {
	                $hasConfirm = true;
	            }
	        }

	        if ($data['logistic_status'] == 0) {
	            $allSent = false;
	        }
	        else {
	            if ($statusIncludeSelf || $data['bill_no'] != $bill_no) {
	                $hasSent = true;
	            }
	        }

	        if ($data['logistic_status'] != 2) {
	            $allSign = false;
	        }
	        else {
	            if ($statusIncludeSelf || $data['bill_no'] != $bill_no) {
	                $hasSign = true;
	            }
	        }

	        if (!in_array($data['logistic_status'], array(3,4,6))) {
	            $allReject = false;
	        }
	        else {
	            if ($statusIncludeSelf || $data['bill_no'] != $bill_no) {
	                $hasReject = true;
	            }
	        }
	    }

	    return array('batch_sn' => $batch_sn,
	                 'hasAssign' => $hasAssign,
	                 'allAssign' => $allAssign,
	                 'hasConfirm' => $hasConfirm,
	                 'allConfirm' => $allConfirm,
	                 'hasSent' => $hasSent,
	                 'allSent' => $allSent,
	                 'allSign' => $allSign,
	                 'hasSign' => $hasSign,
	                 'allReject' => $allReject,
	                 'hasReject' => $hasReject,
	                 'bill_no_array' => $billNoArray,
	                );
	}

	/**
     * 判断是否为拆单
     *
     * @return   void
     */
	public function isSplitOrder($batch_sn, $is_cancel = 0)
	{
	    return $this -> _db -> get("bill_no like '{$batch_sn}-%' and is_cancel = {$is_cancel}");
	}

	/**
     * 判断是否为合单
     *
     * @return   void
     */
	public function isMergeOrder($batch_sn, $is_cancel = 0)
	{
	    $data = array_shift($this -> _db -> get("bill_no like '%{$batch_sn}%' and is_cancel = {$is_cancel}"));
	    if (!$data) return false;

	    $batchSNArray = explode(',', $data['bill_no']);
	    if (count($batchSNArray) == 1)  return false;

	    return $batchSNArray;
	}

	/**
     * 删除数据
     *
     * @param    array    $data
     * @return   void
     */
	public function delete($where)
	{
        $this -> _db -> delete($where);
	}
	  
	  /**
	   * 根据手机号码 判断是否是外地手机，外地手机返回加0手机号码
	   */
	  public function checkPhoneNo($phone){
	      if(empty($phone)){
	          return $phone;
	      }else{
			  $url='http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel='.$phone;
			  $html = file_get_contents($url);
			  $html  = mb_convert_encoding($html, 'UTF-8', 'GBK'); 
			  $html  = str_replace('__GetZoneResult_ =','',$html); 
    	      if(strripos($html,"上海") > 0){
    	          return $phone;
    	      } else {
    	          return "0".$phone;
    	      }
	      }
	  }
	  /**
	   * 根据座机号码验证号码是否去掉- 或者 021
	   */
	  public function checkTelNo($tel){
	      if(empty($tel)){
	          return $tel;
	      }else{
	          $arr=explode("-",$tel);
	          if(count($arr)==1){
	              return "9".$arr[0];
	          }else{
	              if($arr[0] == "021"){
	                  return "9".$arr[1];
	              }else{
	                  return "9".$arr[0].$arr[1];
	              }
	          }
	      }  
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
		if (intval($transport_id) < 1) {
			$this->_error = '运输单ID不正确';
			return false;
		}

		if (false === $this->_db->deleteTransportSourceByTransportId($transport_id)) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return true;
	}

	/**
	 * 根据条件导出相应的物流数据
	 *
	 * @param    array
	 *
	 * @return   boolean
	 **/
	public function exportXlsTransportInfos($params, $action)
	{
		$infos = $this->search($params, $action);
		$title[] = array('订单号', '物流公司', '运输单', '发货时间', '配送状态', '付款方式', '代收货款金额', '收货人', '城市', '收货人地址', '电话', '手机', '邮编', '重量');

		$transport_infos = array();
		if (!empty($infos)) {
			foreach ($infos['datas'] as $info) {
				$transport_infos[] = array(
					'batch_sn'        => "'" . $info['bill_no'],
					'logistic_name'   => $info['logistic_name'],
					'logistic_no'     => $info['logistic_no'],
					'send_time'       => date('Y-m-d H:i:s', $info['send_time']),
					'logistic_status' => $this->_search_option['logistic_status'][$info['logistic_status']],
					'cod_status'      => $this->_search_option['cod_status'][$info['is_cod']],
					'amount'          => !empty($info['is_cod']) ? $info['amount'] : '0',
					'consignee'       => $info['consignee'],
					'city'            => $info['city'],
					'address'         => $info['province'] . $info['city'] . $info['area'] . $info['address'],
					'tel'             => $info['tel'],
					'mobile'          => $info['mobile'],
					'zip'             => $info['zip'],
					'deliver_weigh'             => $info['deliver_weigh'],
				);
			}
			$transport_infos = array_merge($title, $transport_infos);
		} else {
			$transport_infos = $title;
		}
		$xls = new Custom_Model_GenExcel();
		$xls -> addArray($transport_infos);
		$xls -> generateXML('transport_infos-'.date('Y-m-d'));

		return true;
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