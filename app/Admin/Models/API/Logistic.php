<?php
class Admin_Models_API_Logistic
{
    private $tmp = NULL;

	public function __construct()
    {
		$this -> _db = new Admin_Models_DB_Logistic();
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	/**
     * 锁定
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function lock($datas, $val)
	{
		if (is_array($datas['ids'])) {
			foreach($datas['ids'] as $logisticAreaID){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($val) {
			    	$data = array('lock_name' => $admin_name);
			    } else {
			    	$data = array('lock_name' => '');
			    }
	    		$this -> _db -> lock($this -> _auth['admin_name'], $logisticAreaID, $data);
			}
		}
        return true;
	}
	/**
     * 取得指定条件的地区列表
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getAreaListWithPage($where=NULL, $page=1)
    {
        return $this -> _db -> getAreaWithPage($where, $page);
    }
    /**
     * 导入邮编 和 区号
     *
     * @param   array   $data
     * @return void
     */
    public function importLogisticArea($data)
    {
        if (!strstr($data['type'], 'excel')) {
          $return = array('return' => true, 'tip' => '上传文件格式错误');
        } else if (!$data['tmp_name']) {
            $return = array('return' => true, 'tip' => '上传文件失败');
        } else {
            $logistic = file($data['tmp_name']);
            if (!is_array($logistic) || !count($logistic)) {
                $return = array('return' => true, 'tip' => '上传文件内容不能为空');
            } else {
                foreach ($logistic as $k => $v) {
                    $tmp = explode("\t", $v);
                    if ($k > 0) {
                        $areaID = $tmp[3];
                        $data = array('code' => '0'.$tmp[8], 'zip' => $tmp[9]);
                        $this -> _db -> editArea($areaID, $data);
                        $this -> _db -> editLogisticArea(array('area_id' => $areaID), $data);
                    }
                }
            }
            $return = array('return' => true, 'tip' => '导入成功');
        }
        return $return;
    }
    /**
     * 指定ID的地区列表JSON数据
     *
     * @param   int     $areaID
     * @return string
     */
    public function getAreaListJsonData($areaID)
    {
        return Zend_Json::encode($this -> _db -> getAreaListByID($areaID));
    }
	/**
     * 取得指定ID的地区省市区全名
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getWholeAreaName($areaID)
    {
        $data = $this -> _db -> getAreaByID($areaID);
        $this -> tmp[] = array('area_id' => $data['area_id'],
                               'area_name' => $data['area_name'],
                               'parent_id' => $data['parent_id']);
        if ($data['parent_id']) {
            $this -> getWholeAreaName($data['parent_id']);
        }
        return  array_reverse($this -> tmp);
    }
	/**
     * 取得指定ID的地区的子地区列表
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaListByID($areaID)
    {
        return $this -> _db -> getAreaListByID($areaID);
    }
    /**
     * 添加地区
     *
     * @param   array     $data
     * @return  array
     */
    public function addArea($data)
    {
        if ($insertID = $this -> _db -> addArea($data)) {
            $wholeArae = $this -> getWholeAreaName($data['parent_id']);
            $countryID = $wholeArae[0]['area_id'];
            $country = $wholeArae[0]['area_name'];
            $provinceID = $wholeArae[1]['area_id'];
            $province = $wholeArae[1]['area_name'];
            $cityID = $wholeArae[2]['area_id'];
            $city = $wholeArae[2]['area_name'];
            $areaID = $insertID;
            $area = $data['area_name'];
            if ($logistic = $this -> getLogisticList()) {
                foreach ($logistic as $k => $v) {

                    $logisticArea = array('logistic_code' => $v['logistic_code'],
                                          'country_id' => $countryID,
                                          'province_id' => $provinceID,
                                          'city_id' => $cityID,
                                          'area_id' => $areaID,
                                          'country' => $country,
                                          'province' => $province,
                                          'city' => $city,
                                          'area' => $area,
                                          'open' => 1,
                                          'delivery' => 1,
                                          'pickup' => 1,
                                          'cod' => 0,
                                          'delivery_keyword' => '',
                                          'non_delivery_keyword' => '');
                    $this -> _db -> addLogisticArea($logisticArea);
                    if ($between = $this -> _db -> getLogisticAreaPriceBetween()) {
                        foreach ($between as $kk => $vv) {
                            $logisticAreaPrice = array('logistic_code' => $v['logistic_code'],
                                                       'area_id' => $areaID,
                                                       'min' => $vv['min'],
                                                       'max' => $vv['max'],
                                                       'price' => 0);
                            $this -> _db -> addLogisticAreaPrice($logisticAreaPrice);
                        }
                    }

                }
            }

            $return = array('return' => true, 'tip' => '添加地区成功', 'insert_id' => $insertID);
        } else {
            $return = array('return' => false, 'tip' => '添加地区失败');
        }
        return $return;
    }
	/**
     * 取得指定ID的地区
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaByID($areaID)
    {
        return $this -> _db -> getAreaByID($areaID);
    }
	/**
     * 删除地区
     *
     * @param   int     $areaID
     * @return  array
     */
    public function delArea($areaID)
    {
        $areaID = intval($areaID);
        if ($areaID < 1) {
            $return = array('return' => false, 'tip' => '删除地区失败，丢失地区ID');
        } else {
            $childArea = $this -> _db -> getAreaListByID($areaID);
            if (is_array($childArea) && count($childArea)) {
                $return = array('return' => false, 'tip' => '删除地区失败，请先清空该地区的子区域');
            } else if ($this -> _db -> delArea($areaID)) {
                $return = array('return' => true, 'tip' => '删除地区成功');
            } else {
                $return = array('return' => false, 'tip' => '删除地区失败');
            }
        }
        return $return;
    }
	/**
     * 编辑地区
     *
     * @param   int     $areaID
     * @param   array   $data
     * @return  array
     */
    public function editArea($areaID, $data)
    {
        $areaID = intval($areaID);
        if ($areaID < 1) {
            $return = array('return' => false, 'tip' => '编辑地区失败，丢失地区ID');
        } else if (!is_array($data) || !count($data)) {
            $return = array('return' => false, 'tip' => '编辑地区失败，丢失要编辑的地区字段');
        } else if ($this -> _db -> editArea($areaID, $data)) {
            $return = array('return' => true, 'tip' => '编辑地区成功');
        } else {
            $return = array('return' => false, 'tip' => '编辑地区失败');
        }
        return $return;
    }
	/**
     * 物流公司列表
     *
     * @return  array
     */
    public function getLogisticList()
    {
        return $this -> _db -> getLogisticList();
    }
    /**
     * 导出指定ID物流公司的操作区域/配送价格表
     *
     * @param   string     $logisticCode
     * @return void
     */
    public function exportLogisticByID($logisticCode)
    {
        $areaPrice = $areaPriceTitle = array();
        $logisticArea = $this -> _db -> getLogisticAreaList(array('logistic_code' => $logisticCode));
        $logisticAreaPrice = $this -> getLogisticAreaPriceList(array('logistic_code' => $logisticCode));
        if (is_array($logisticAreaPrice) && count($logisticAreaPrice)) {
            foreach ($logisticAreaPrice as $v) {
                $areaPrice[$v['area_id']][] = array('min' => $v['min'], 'max' => $v['max'], 'value' => $v['value']);
                sort($areaPrice[$v['area_id']]);
            }
        }
        $current = current($areaPrice);
        $priceCount = count($current);
        if (is_array($current) && count($current)) {
            foreach ($current as $v) {
                $areaPriceTitle[] = $v['min'] . '<X<=' . $v['max'];
            }
        }
        $logisticTitle = array('国家ID', '省份ID', '城市ID', '区县ID', '国家', '省份', '城市', '区县', '是否开通',
                               '能否上门派送','能否上门取件', '能否代收货款', '可操作区域关键字', '不可操作区域关键字');
        $title = array_merge($logisticTitle, $areaPriceTitle);
        $output = implode("\t", $title) . "\n";
        if (is_array($logisticArea) && count($logisticArea)) {
            foreach ($logisticArea as $k => $v) {
                $output .= $v['country_id'] . "\t" .
                           $v['province_id'] . "\t" .
                           $v['city_id'] . "\t" .
                           $v['area_id'] . "\t" .
                           $v['country'] . "\t" .
                           $v['province'] . "\t" .
                           $v['city'] . "\t" .
                           $v['area'] . "\t" .
                           $v['open'] . "\t" .
                           $v['delivery'] . "\t" .
                           $v['pickup'] . "\t" .
                           $v['cod'] . "\t" .
                           $v['delivery_keyword'] . "\t" .
                           $v['non_delivery_keyword'];
                if ($areaPrice[$v['area_id']]) {
                    foreach ($areaPrice[$v['area_id']] as $price) {
                        $output .= "\t" . $price['value'];
                    }
                } else {
                    for ($i = 0; $i < $priceCount; $i++) {
                        $output .= " \t";
                    }
                }
                $output .= "\n";
            }
        }
        echo mb_convert_encoding($output, 'GBK', 'UTF-8');
    }
    /**
     * 导入指定ID物流公司的操作区域/配送价格表
     *
     * @param   string     $logisticCode
     * @param   array   $data
     * @return void
     */
    public function importLogistic($logisticCode, $data)
    {
        if (!strstr($data['type'], 'excel')) {
          $return = array('return' => true, 'tip' => '上传文件格式错误');
        } else if (!$data['tmp_name']) {
            $return = array('return' => true, 'tip' => '上传文件失败');
        } else {
            $logistic = file($data['tmp_name']);
            if (!is_array($logistic) || !count($logistic)) {
                $return = array('return' => true, 'tip' => '上传文件内容不能为空');
            } else {
                $this -> delLogisticAreaList($logisticCode);
                $this -> delLogisticAreaPriceList($logisticCode);
                foreach ($logistic as $k => $v) {
                    $tmp = explode("\t", $v);
                    if ($k == 0) {
                        $fieldLength = count($tmp);
                        if (is_array($tmp) && count($tmp)) {
                            foreach ($tmp as $x => $y) {
                                if ($x > 13) {
                                    $temp = explode('<X<=', $y);
                                    $title[$x]['min'] = $temp[0];
                                    $title[$x]['max'] = $temp[1];
                                }
                            }
                        }
                    }
                    for ($i = 0; $i < $fieldLength; $i++) {
                        $tmp[$i] = mb_convert_encoding(trim($tmp[$i]), 'UTF-8', 'GBK');
                        if ($i > 13 && $title[$i] && $k > 0) {//存在该总量等级（防止excel自动产生），而却除去第一行表里栏

                            $logisticAreaPrice = array('logistic_code' => $logisticCode,
                                                       'area_id' => $tmp[3],
                                                       'min' => trim($title[$i]['min']),
                                                       'max' => trim($title[$i]['max']),
                                                       'price' => $tmp[$i]);
                            $this -> _db -> addLogisticAreaPrice($logisticAreaPrice);
                        }
                    }
                    if ($k > 0) {
                        $logisticArea = array('logistic_code' => $logisticCode,
                                              'country_id' => $tmp[0],
                                              'province_id' => $tmp[1],
                                              'city_id' => $tmp[2],
                                              'area_id' => $tmp[3],
                                              'country' => $tmp[4],
                                              'province' => $tmp[5],
                                              'city' => $tmp[6],
                                              'area' => $tmp[7],
                                              'open' => $tmp[8],
                                              'delivery' => $tmp[9],
                                              'pickup' => $tmp[10],
                                              'cod' => $tmp[11],
                                              'delivery_keyword' => $tmp[12],
                                              'non_delivery_keyword' => $tmp[13]);
                        $this -> _db -> addLogisticArea($logisticArea);
                    }
                }
            }
            $return = array('return' => true, 'tip' => '导入成功');
        }
        return $return;
    }
	/**
     * 物流公司插件列表
     *
     * @return  array
     */
    public function getLogisticPluginList()
    {
        $dir = './../lib/Custom/Model/Logistic';
        $dh = opendir(realpath($dir));
        while (($file = readdir($dh)) !== false) {
            if(strstr($file,'.php')){
                require_once 'Custom/Model/Logistic/'.$file;
            }
        }
        return $logisticPlugin;
    }
    /**
     * 添加物流公司
     *
     * @param   array     $data
     * @return  array
     */
    public function addLogistic($data)
    {
        if ($this -> _db -> getLogisticByCode($data['logistic_code'])) {
            $return = array('return' => false, 'tip' => '物流公司已经存在');
        } else if ($insertID = $this -> _db -> addLogistic($data)) {
            $this -> addLogisticAreaList($data['logistic_code']);
            $return = array('return' => true, 'tip' => '添加物流公司成功', 'insert_id' => $insertID);
        } else {
            $return = array('return' => false, 'tip' => '添加物流公司失败');
        }
        return $return;
    }
    /**
     * 删除物流公司
     *
     * @param   int     $logisticCode
     * @return  array
     */
    public function delLogistic($logisticCode)
    {
        if (!$logisticCode) {
            $return = array('return' => false, 'tip' => '删除物流公司失败，丢失物流公司ID');
        } else if ($this -> _db -> delLogistic($logisticCode)) {
            $this -> delLogisticAreaList($logisticCode);
            $this -> delLogisticAreaPriceList($logisticCode);
            $return = array('return' => true, 'tip' => '删除物流公司成功');
        } else {
            $return = array('return' => false, 'tip' => '删除物流公司失败');
        }
        return $return;
    }
	/**
     * 取得指定ID的物流公司
     *
     * @param   int     $logisticCode
     * @return  array
     */
    public function getLogisticByID($logisticCode)
    {
        return $this -> _db -> getLogisticByID($logisticCode);
    }
	/**
     * 编辑物流公司
     *
     * @param   string     $logisticCode
     * @param   array   $data
     * @return  array
     */
    public function editLogistic($logisticCode, $data)
    {
        if (!$logisticCode) {
            $return = array('return' => false, 'tip' => '编辑物流公司失败，丢失物流公司ID');
        } else if (!is_array($data) || !count($data)) {
            $return = array('return' => false, 'tip' => '编辑物流公司失败，丢失要编辑的物流公司字段');
        } else if ($this -> _db -> editLogistic($logisticCode, $data)) {
            $return = array('return' => true, 'tip' => '编辑物流公司成功');
        } else {
            $return = array('return' => false, 'tip' => '编辑物流公司失败');
        }
        return $return;
    }
	/**
     * 批量导入地区基础表信息到指定ID的物流公司地区
     *
     * @param   string     $logisticCode
     * @return  bool
     */
    public function addLogisticAreaList($logisticCode)
    {
        return $this -> _db -> addLogisticAreaList($logisticCode);
    }
	/**
     * 批量删除指定ID的物流公司地区
     *
     * @param   string     $logisticCode
     * @return  bool
     */
    public function delLogisticAreaList($logisticCode)
    {
        return $this -> _db -> delLogisticAreaList($logisticCode);
    }
	/**
     * 批量删除指定ID的物流公司地区价格
     *
     * @param   string     $logisticCode
     * @return  bool
     */
    public function delLogisticAreaPriceList($logisticCode)
    {
        return $this -> _db -> delLogisticAreaPriceList($logisticCode);
    }
	/**
     * 取得物流公司的配送地区及其配送价格
     *
     * @param   array     $where
     * @param   int     int
     * @return  array
     */
    public function getLogisticAreaPriceListWithPage($where=NULL, $page=1)
    {
        $data = $this -> _db -> getLogisticAreaListWithPage($where, $page);
        $logisticArea = $data['data'];
        if (is_array($logisticArea) && count($logisticArea)) {
            foreach ($logisticArea as $k => $v) {
                $where = array('logistic_code' => $v['logistic_code'], 'area_id' => $v['area_id']);
                $logisticAreaPrice = $this -> getLogisticAreaPriceList($where);
                if (is_array($logisticAreaPrice) && count($logisticAreaPrice)) {
                    foreach ($logisticAreaPrice as $x => $y) {
                        if (!$makeTitleEnd) {
                            $title[] = $y['min'] . '&lt;X&lt;=' . $y['max'];
                        }
                        $logisticArea[$k]['price'][] = $y['price'];
                    }
                    $makeTitleEnd = true;
                }
            }
        }
        return array('title' => $title, 'logistic_area' => $logisticArea, 'total' =>$data['total']);
    }
	/**
     * 获取物流公司地区列表对应的配送价格
     *
     * @param   array     $where
     * @return  array
     */
    public function getLogisticAreaPriceList($where = NULL)
    {
        return $this -> _db -> getLogisticAreaPriceList($where);
    }
	/**
     * 获取指定ID的物流公司地区
     *
     * @param   int     $logisticAreaID
     * @return  array
     */
    public function getLogisticAreaByID($logisticAreaID)
    {
        return $this -> _db -> getLogisticAreaByID($logisticAreaID);
    }
	/**
     * 编辑指定ID的物流公司配送地区价格
     *
     * @param   int     $areaID
     * @param   array   $data
     * @return  array
     */
    public function editLogisticAreaPrice($logisticAreaPriceID, $data)
    {
        $logisticAreaPriceID = intval($logisticAreaPriceID);
        if ($logisticAreaPriceID < 1) {
            $return = array('return' => false, 'tip' => '编辑价格失败，丢失ID');
        } else if (!is_array($data) || !count($data)) {
            $return = array('return' => false, 'tip' => '编辑价格失败，丢失要编辑的价格字段');
        } else if ($this -> _db -> editLogisticAreaPrice($logisticAreaPriceID, $data) >= 0) {
            $return = array('return' => true, 'tip' => '编辑价格成功');
        } else {
            $return = array('return' => false, 'tip' => '编辑价格失败');
        }
        return $return;
    }
	/**
     * 取得配送策略
     *
     * @param   array     $where
     * @param   int     int
     * @return  array
     */
    public function getAreaStrategyListWithPage($where=NULL, $page=1)
    {
        return $data = $this -> _db -> getAreaStrategyListWithPage($where, $page);
    }
	/**
     * 设置对应区域的策略
     *
     * @param   int     $areaID
     * @param   array    $strategy
     * @return  array
     */
    public function setAreaStrategy($areaID, $strategy){
        $place = $this -> getWholeAreaName($areaID);
        if (count($place) == 4) { //地区
            $data = $this -> _db -> getAreaList(array('area_id' => $place[3]['area_id']));
        } else if (count($place) == 3) { //市区
            $data = $this -> _db -> getAreaList(array('city_id' => $place[2]['area_id']));
        } else if (count($place) == 2) { //省份
            $data = $this -> _db -> getAreaList(array('province_id' => $place[1]['area_id']));
        } else if (count($place) == 1) { //中国
            $data = $this -> _db -> getAreaList(array('country_id' => $place[0]['area_id']));
        }
        if (is_array($data) && count($data)) {
            foreach ($data as $k => $v) {
                $this -> _db -> editArea($v['area_id'], array('strategy' => serialize($strategy)));
            }
        }
        return $return = array('return' => true, 'tip' => '设置区域策略成功');;
    }
    /**
     * ajax修改配送策略列表 的 优先级  	指定  	暂停
     *
     * @param   int     $areaStrategyID
     * @param   string    $logisticCode
     * @param   string    $name
     * @param   string    $value
     * @return  bool
     */
    public function updateAreaStrategyByAjax($areaID, $code, $name, $value)
    {
        $data = $this -> _db -> getAreaByID($areaID);
        $strategy = unserialize($data['strategy']);
        if ($strategy[$code]) {
            if ($name == 'use') {
                foreach ($strategy as $k => $v) {

                    $strategy[$k]['use'] = 0;
                }
            }
            $strategy[$code][$name] = $value;
        }
        $this -> _db -> editArea($areaID, array('strategy' => serialize($strategy)));

    }
    /**
     * 操作区域列表
     *
     * @param   array     $where
     * @param   int     int
     * @return  array
     */
    public function getLogisticAreaListWithPage($where, $page)
    {
        return $data = $this -> _db -> getLogisticAreaListWithPage($where, $page);
    }
    /**
     * 编辑操作区域列表(锁定/解锁)
     *
     * @param   array     $logisticAreaIDList
     * @param   int     int
     * @return  bool
     */
    public function editLogisticAreaLockName($logisticAreaIDList, $lock)
    {
        if ($lock) {
            $admin = $this->_auth['admin_name'];
        } else {
            $admin = '';
        }
        return $this -> _db -> editLogisticAreaLockName($logisticAreaIDList, array('lock_name' => $admin));
    }
	/**
     * 编辑物流公司区域
     *
     * @param   int     $logisticAreaID
     * @param   array   $data
     * @return  array
     */
    public function editLogisticArea($logisticAreaID, $data)
    {
        $admin = $this->_auth['admin_name'];
        if (!$logisticAreaID) {
            $return = array('return' => false, 'tip' => '编辑物流操作区域失败，丢失物流操作区域ID');
        } else if (!is_array($data) || !count($data)) {
            $return = array('return' => false, 'tip' => '编辑物流操作区域失败，丢失要编辑的操作区域字段');
        } else if ($this -> _db -> editLogisticArea(array('logistic_area_id' => $logisticAreaID, 'lock_name' => $admin), $data)) {
            $return = array('return' => true, 'tip' => '编辑物流操作区域成功');
            $tmp = array('logistic_area_id' => $logisticAreaID, 'lock_name' => $admin);
            $this -> _db -> editLogisticArea($tmp, array('lock_name' => ''));
        } else {
            $return = array('return' => false, 'tip' => '编辑物流操作区域失败');
        }
        return $return;
    }
	/**
     * 编辑物流公司关键字(接口)
     *
     * @param   array     $logisticAreaID
     * @param   array     $data
     * @return  array
     */
    public function editLogisticAreaKeyword($where, $data)
    {
        $this -> _db -> editLogisticArea($where, $data);
    }
    
    /**
     * 获得模板
     *
     * @param   string  $logistic_code
     * @param   int     $type
     * @return  array
     */
    public function getLogisticTemplate($logistic_code, $type = 1) 
    {
        return $this -> _db -> getLogisticTemplate($logistic_code, $type);
    }
      
    /**
     * 设置模板
     *
     * @param   array     $data
     * @return  void
     */
    public function setLogisticTemplate($data)
    {
        return $this -> _db -> setLogisticTemplate($data);
    }
    
    /**
     * 解析模板参数
     *
     * @param   array     $template
     * @param   array     $data
     * @return  array
     */
    public function parseTemplate($template, $data)
    {
        if ( !$template )   return false;
        
        if ( !$template['config'] ) return false;
        
        if ( !empty($template['image']) ) {
            $_size = @getimagesize($template['image']);

            if ($_size != false) {
                $template['image_size'] = array('width' => $_size[0], 'height' => $_size[1]);
            }
        }
        if ( empty($template['image_size']) ) {
            $template['image_size'] = array('width' => '1024', 'height' => '600');
        }
        $lable_box = array();
        $lable_box['t_brand'] = $data['brand'] ? $data['brand'] : '国药电商';
        $lable_box['t_company'] = $data['company'] ? $data['company'] : '国药（上海）电子商务有限公司';
        $lable_box['t_sender'] = '1健康';
        $lable_box['t_sender_addr'] = '上海宝山区铁力路388号宝湾国际物流6号库南  1健康';
        $lable_box['t_sender_city'] = '上海';
        $lable_box['t_sender_tel'] = $data['sender_tel'] ? $data['sender_tel'] : '400-603-3883';
        $lable_box['t_consignee'] = $data['consignee'];
        $tel = $data['tel'];
        if ( $data['mobile'] ) {
            if ( $tel ) $tel .= '<br>';
            $tel .= $data['mobile'];
        }
        $lable_box['t_consignee_tel'] = $data['tel'];
        $lable_box['t_consignee_mobile'] = $data['mobile'];
        $lable_box['t_consignee_tel_mobile'] = $tel;
        if ($data['validate_sn'] && $data['logistic_code'] != 'ems') {
            $lable_box['t_consignee_validate_sn'] = '400-670-8188 转（用户码）'.$data['validate_sn'];
        }
        else {
            $lable_box['t_consignee_validate_sn'] = $lable_box['t_consignee_tel_mobile'];
        }
        $lable_box['t_country'] = '中国';
        $lable_box['t_povince'] = $data['province'];
        $lable_box['t_city'] = $data['city'];
        $lable_box['t_area'] = $data['area'];
        
        $data['address'] = str_replace(',', '，', $data['address']);
        $data['address'] = str_replace("'", '', $data['address']);
        $data['address'] = str_replace("", '', $data['address']);
        $data['address'] = str_replace("\n", '', $data['address']);
        
        $data['addr'] = $data['province'].'&nbsp;&nbsp;'.$data['city'].'&nbsp;&nbsp;'.$data['area'].'&nbsp;&nbsp;'.$data['address'];        
        $lable_box['t_addr'] = $data['addr'];
        $lable_box['t_address'] = $data['address'];
        $lable_box['t_contact'] = '';
        $lable_box['t_contact_tel'] = '';
        $lable_box['t_bill_no'] = '订单号：'.str_replace(',', '<br>', $data['bill_no']);
        $lable_box['t_print_remark'] = $data['print_remark'];
        $lable_box['t_print_remark'] = str_replace("&mdash;", '-', $lable_box['t_print_remark']);
        $lable_box['t_print_remark'] = str_replace(chr(13), ' ', $lable_box['t_print_remark']);
        $lable_box['t_print_remark'] = str_replace(chr(10), ' ', $lable_box['t_print_remark']);
        $lable_box['t_print_remark'] = str_replace(',', '，', $lable_box['t_print_remark']);
        $lable_box['t_print_remark'] = str_replace("'", '', $lable_box['t_print_remark']);
        $lable_box['t_time'] = date('Y年m月d日', time());
        $lable_box['t_goods_name'] = '保健食品';
        $lable_box['t_goods_number'] = $data['goods_number'];
        $lable_box['t_amount'] = $data['amount']+$data['change_amount'].'元';
        $lable_box['t_amount_cn'] = $data['amount_cn'].'元';
        $lable_box['t_card_sn'] = '提货卡：'.$data['card_sn'];
        $lable_box['t_sign_received'] = '为了您的权益，请先开箱验货，后签收';
		$lable_box['t_sign_code'] = '拨打此电话加5位短号码可直接转到用户手机';
		$lable_box['pay_sign'] = '请快递员带好POS机';
        $lable_box['t_gou1'] = '√';
        $lable_box['t_gou2'] = '√';
        $lable_box['t_payment'] = '0210066986';
        
        $temp_config = array();
        $temp_config = explode('||,||', $template['config']);
        foreach ( $temp_config as $key => $lable )
        {
            $temp_info = explode(',', $lable);
            if (is_array($temp_info))
            {
                $temp_info[1] = $lable_box[$temp_info[0]];
            }
            $temp_config[$key] = implode(',', $temp_info);
        }
        $template['config'] = implode('||,||',  $temp_config);
        return $template;
    }
    
    /**
     * 判断是否在危险品省范围内
     *
     * @param   int     $province_id
     * @return  void
     */
    public function isInDangerousArea($province_id)
    {
        return false;
        //北京/天津/河北/西藏
        return in_array($province_id, array(2,3,4,27));
    }
    
    /**
     * 返回危险品可用的物流公司代码
     *
     * @return  void
     */
    public function getDangerousLogisticCode()
    {
        return array('ems', 'zjs');
    }
    
    /**
     * 获得物流公司策略
     *
     * @param   int     $provinceID
     * @return  array
     */
    public function getLogisticPolicy($isCod = false, $provinceID = null) {
        if ($isCod) {
            if (!$this -> _policyCod) {
                $this -> _policyCod = array(2 => array('code' => array('sf'), 'province'=>'北京'),
                                            3 => array('code' => array('sf'), 'province'=>'天津'),
                                        	4 => array('code' => array('sf'), 'province'=>'河北'),
                                        	5 => array('code' => array('sf'), 'province'=>'山西'),
                                        	6 => array('code' => array('ems'), 'province'=>'内蒙古'),
                                        	7 => array('code' => array('sf'), 'province'=>'辽宁'),
                                        	8 => array('code' => array('sf'), 'province'=>'吉林'),
                                        	9 => array('code' => array('sf'), 'province'=>'黑龙江'),
                                        	10 => array('code' => array('sf'), 'province'=>'上海'),
                                        	11 => array('code' => array('sf'), 'province'=>'江苏'),
                                        	12 => array('code' => array('sf'), 'province'=>'浙江'),
                                        	13 => array('code' => array('sf'), 'province'=>'安徽'),
                                        	14 => array('code' => array('sf'), 'province'=>'福建'),
                                        	15 => array('code' => array('sf'), 'province'=>'江西'),
                                        	16 => array('code' => array('sf'), 'province'=>'山东'),
                                        	17 => array('code' => array('sf'), 'province'=>'河南'),
                                        	18 => array('code' => array('sf'), 'province'=>'湖北'),
                                        	19 => array('code' => array('sf'), 'province'=>'湖南'),
                                        	20 => array('code' => array('sf'), 'province'=>'广东'),
                                        	21 => array('code' => array('sf'), 'province'=>'广西'),
                                        	22 => array('code' => array('sf'), 'province'=>'海南'),
                                        	23 => array('code' => array('sf'), 'province'=>'重庆'),
                                        	24 => array('code' => array('sf'), 'province'=>'四川'),
                                        	25 => array('code' => array('sf'), 'province'=>'贵州'),
                                        	26 => array('code' => array('ems'), 'province'=>'云南'),
                                        	27 => array('code' => array('ems'), 'province'=>'西藏'),
                                        	28 => array('code' => array('sf'), 'province'=>'陕西'),
                                        	29 => array('code' => array('ems'), 'province'=>'甘肃'),
                                        	30 => array('code' => array('ems'), 'province'=>'青海'),
                                        	31 => array('code' => array('ems'), 'province'=>'宁夏'),
                                        	32 => array('code' => array('ems'), 'province'=>'新疆'),
                                        	3880 => array('code' => array('sf'), 'province'=>'香港'),
                                        	3982 => array('code' => array('sf'), 'province'=>'未知省')
                                           );
            }
        }
        else {
            if (!$this -> _policy) {
                $this -> _policy = array(2 => array('code'=> array('yt'), 'province'=>'北京'),
                                    	 3 => array('code' => array('yt'), 'province'=>'天津'),
                                    	 4 => array('code' => array('yt'), 'province'=>'河北'),
                                    	 5 => array('code' => array('yt'), 'province'=>'山西'),
                                    	 6 => array('code' => array('ems'), 'province'=>'内蒙古'),
                                    	 7 => array('code' => array('yt'), 'province'=>'辽宁'),
                                    	 8 => array('code' => array('yt'), 'province'=>'吉林'),
                                    	 9 => array('code' => array('yt'), 'province'=>'黑龙江'),
                                    	 10 => array('code' => array('yt'), 'province'=>'上海'),
                                    	 11 => array('code' => array('yt'), 'province'=>'江苏'),
                                    	 12 => array('code' => array('yt'), 'province'=>'浙江'),
                                    	 13 => array('code' => array('yt'), 'province'=>'安徽'),
                                    	 14 => array('code' => array('yt'), 'province'=>'福建'),
                                    	 15 => array('code' => array('yt'), 'province'=>'江西'),
                                    	 16 => array('code' => array('yt'), 'province'=>'山东'),
                                    	 17 => array('code' => array('yt'), 'province'=>'河南'),
                                    	 18 => array('code' => array('yt'), 'province'=>'湖北'),
                                    	 19 => array('code' => array('yt'), 'province'=>'湖南'),
                                    	 20 => array('code' => array('yt'), 'province'=>'广东'),
                                    	 21 => array('code' => array('yt'), 'province'=>'广西'),
                                    	 22 => array('code' => array('yt'), 'province'=>'海南'),
                                    	 23 => array('code' => array('yt'), 'province'=>'重庆'),
                                    	 24 => array('code' => array('yt'), 'province'=>'四川'),
                                    	 25 => array('code' => array('yt'), 'province'=>'贵州'),
                                    	 26 => array('code' => array('yt'), 'province'=>'云南'),
                                    	 27 => array('code' => array('ems'), 'province'=>'西藏'),
                                    	 28 => array('code' => array('yt'), 'province'=>'陕西'),
                                    	 29 => array('code' => array('ems'), 'province'=>'甘肃'),
                                    	 30 => array('code' => array('ems'), 'province'=>'青海'),
                                    	 31 => array('code' => array('ems'), 'province'=>'宁夏'),
                                    	 32 => array('code' => array('ems'), 'province'=>'新疆'),
                                    	 3880 => array('code' => array('ems'), 'province'=>'香港'),
                                    	 3982 => array('code' => array('ems'), 'province'=>'未知省')
                                        );
            }
        }
        
        if ($provinceID) {
            return $isCod ? $this -> _policyCod[$provinceID] : $this -> _policy[$provinceID];
        }
        
        return $isCod ? $this -> _policyCod : $this -> _policy;
    }
    
    /**
     * 获得快递100物流公司
     *
     * @param   array   $code
     * @return  void
     */
    public function getKuaidi100Company($code)
    {
        $result['yt'] = 'yuantong';
        $result['sf'] = 'shunfeng';
        $result['ems'] = 'ems';
        
        return $result[$code];
    }
    
    /**
     * 向快递100推送信息
     *
     * @param   array   $code
     * @return  boolean
     */
    public function pushKuaidi100($data)
    {
        if (!$data['logistic_code'] || !$data['logistic_no']) {
            return false;
        }
        $company = $this -> getKuaidi100Company($data['logistic_code']);
        if (!$company) {
            return false;
        }
        
        if (!isset($this -> _config)) {
            $xml = new Custom_Config_Xml();
            $this -> _config = $xml->getConfig();
        }
        $domain = $this -> _config -> masterdomain;
        if ($domain == 'http://www.cnsc.com.cn') {
            $post_data["schema"] = 'json';
            $post_data["param"] = '{"company":"'.$company.'", "number":"'.$data['logistic_no'].'","from":"日本", "to":"'.$data['province'].$data['city'].'", "key":"guoyao201212122421", "parameters":{"callbackurl":"http://www.cnsc.com.cn/external/kuaidi"}}';
            foreach ($post_data as $k => $v) {
                $o .= "$k=".urlencode($v)."&";
            }
            $post_data = substr($o, 0, -1);
            $ch = curl_init();
    	    curl_setopt($ch, CURLOPT_POST, 1);
        	curl_setopt($ch, CURLOPT_HEADER, 0);
        	curl_setopt($ch, CURLOPT_URL, 'http://www.kuaidi100.com/poll');
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_exec($ch);
        }
        
        $row = array('logistic_code' => $data['logistic_code'],
                     'logistic_no' => $data['logistic_no'],
                     'require_time' => time(),
                    );
        $this -> _db -> addKuaidi100($row);
        
        return true;
    }
    
}
