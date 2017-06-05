<?php
/**
 * 地区、物流公司、物流公司配送区域的管理界面 DB

*/
class Admin_Models_DB_Logistic
{
	private $_db = null;
	private $_table_area = 'shop_area';
    private $_table_logistic = 'shop_logistic';
    private $_table_logistic_area = 'shop_logistic_area';
    private $_table_logistic_area_price = 'shop_logistic_area_price';
    private $_table_logistic_template = 'shop_logistic_template';
    private $_table_logistic_kuaidi100 = 'shop_logistic_kuaidi100';

	public function __construct()
    {
		$this -> _db = Zend_Registry :: get('db');
        $this -> _pageSize = Zend_Registry :: get('config') -> view -> page_size;
    }
	/**
     * 锁定
     *
     * @param    string    $admin
     * @param    int      $logisticAreaID
     * @param    array    $data
     * @return   array
     */
    public function lock($admin, $logisticAreaID, $data) 
    {
        return $this->_db->update($this->_table_logistic_area, $data, "logistic_area_id='{$logisticAreaID}' and (lock_name='' || lock_name='{$admin}')");
    }
	/**
     * 取得地区分页列表
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getAreaWithPage($where, $page)
    {
        $country = 1;
        if ($where['province_id']) {
            $condition[] = "b.area_id = {$where['province_id']}";
        } else {
            $condition[] = "b.parent_id = {$country}";
        }
        if ($where['city_id']) {
            $condition[] = "c.area_id = {$where['city_id']}";
        }
        if ($where['area_id']) {
            $condition[] = "d.area_id = {$where['area_id']}";
        }
        if ($where['code']) {
            $condition[] = "d.code = {$where['code']}";
        }
        if ($where['zip']) {
            $condition[] = "d.zip = {$where['zip']}";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        $sql = "FROM {$this -> _table_area} a ";
        $sql .= "LEFT JOIN {$this -> _table_area} b ON a.area_id = b.parent_id ";
        $sql .= "LEFT JOIN {$this -> _table_area} c ON b.area_id = c.parent_id ";
        $sql .= "LEFT JOIN {$this -> _table_area} d ON c.area_id = d.parent_id ";
        $sql .= "WHERE 1=1 {$condition} ";
		$sqlOfList = "SELECT a.area_name as country, b.area_name AS province, c.area_name AS city, ";
		$sqlOfList .= "d.area_name AS area, d.area_id AS area_id, d.code AS code, d.zip AS zip {$sql} ";
        $sqlOfList .= "LIMIT {$this -> _pageSize} OFFSET " . (($page - 1) * $this -> _pageSize);
        $sqlOfTotal = "SELECT count(*) {$sql}";
        $data['data'] = $this -> _db -> fetchAll($sqlOfList);
        $data['total'] = $this -> _db -> fetchOne($sqlOfTotal);
        return $data;
    }	/**
     * 取得地区列表
     *
     * @param   array   $where
     * @return  array
     */
    public function getAreaList($where)
    {
        $country = 1;
        if ($where['province_id']) {
            $condition[] = "b.area_id = {$where['province_id']}";
        } else {
            $condition[] = "b.parent_id = {$country}";
        }
        if ($where['city_id']) {
            $condition[] = "c.area_id = {$where['city_id']}";
        }
        if ($where['area_id']) {
            $condition[] = "d.area_id = {$where['area_id']}";
        }
        if ($where['code']) {
            $condition[] = "d.code = {$where['code']}";
        }
        if ($where['zip']) {
            $condition[] = "d.zip = {$where['zip']}";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
		$sql = "SELECT a.area_name as country, b.area_name AS province, c.area_name AS city, ";
		$sql .= "d.area_name AS area, d.area_id AS area_id, d.code AS code, d.zip AS zip ";
        $sql .= "FROM {$this -> _table_area} a ";
        $sql .= "LEFT JOIN {$this -> _table_area} b ON a.area_id = b.parent_id ";
        $sql .= "LEFT JOIN {$this -> _table_area} c ON b.area_id = c.parent_id ";
        $sql .= "LEFT JOIN {$this -> _table_area} d ON c.area_id = d.parent_id ";
        $sql .= "WHERE 1=1 {$condition} ";
        return $data = $this -> _db -> fetchAll($sql);
    }
	/**
     * 取得指定ID的地区
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaByID($areaID)
    {
        return $this -> _db -> fetchRow("SELECT * FROM {$this -> _table_area} WHERE area_id = {$areaID}");
    }
	/**
     * 取得指定ID地区的子地区列表
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaListByID($areaID)
    {
        return $this -> _db -> fetchAll("SELECT * FROM {$this -> _table_area} WHERE parent_id = {$areaID}");
    }
    /**
     * 添加地区
     *
     * @param   array     $data
     * @return  int
     */
    public function addArea($data)
    {
        $this -> _db -> insert($this -> _table_area, $data);
        return $this -> _db -> lastInsertId();
    }
	/**
     * 删除地区
     *
     * @param   int     $areaID
     * @return  bool
     */
    public function delArea($areaID)
    {
        return $this -> _db -> delete($this -> _table_area, "area_id = {$areaID}");
    }
	/**
     * 编辑地区
     *
     * @param   int     $areaID
     * @param   array   $data
     * @return  bool
     */
    public function editArea($areaID, $data)
    {
		return $this -> _db -> update($this -> _table_area, $data, "area_id = {$areaID}");
    }
	/**
     * 物流公司列表
     *
     * @return  array
     */
    public function getLogisticList()
    {
        return $this -> _db -> fetchAll("SELECT * FROM {$this -> _table_logistic}");
    }
    /**
     * 添加物流公司
     *
     * @param   array     $data
     * @return  int
     */
    public function addLogistic($data)
    {
        $this -> _db -> insert($this -> _table_logistic, $data);
        return $this -> _db -> lastInsertId();
    }
	/**
     * 删除物流公司
     *
     * @param   string     $logisticCode
     * @return  bool
     */
    public function delLogistic($logisticCode)
    {
        return $this -> _db -> delete($this -> _table_logistic, "logistic_code = '{$logisticCode}'");
    }
	/**
     * 取得指定ID的物流公司
     *
     * @param   string     $logisticCode
     * @return  array
     */
    public function getLogisticByID($logisticCode)
    {
        return $this -> _db -> fetchRow("SELECT * FROM {$this -> _table_logistic} WHERE logistic_code = '{$logisticCode}'");
    }
	/**
     * 取得指定物流插件(code)的物流公司
     *
     * @param   string     $logisticCode
     * @return  array
     */
    public function getLogisticByCode($logisticCode)
    {
        return $this -> _db -> fetchRow("SELECT * FROM {$this -> _table_logistic} WHERE logistic_code = '{$logisticCode}'");
    }
	/**
     * 编辑物流公司
     *
     * @param   string     $logisticCode
     * @param   array   $data
     * @return  bool
     */
    public function editLogistic($logisticCode, $data)
    {
		return $this -> _db -> update($this -> _table_logistic, $data, "logistic_code = '{$logisticCode}'");
    }
	/**
     * 批量导入地区基础表信息到指定ID的物流公司地区
     *
     * @param   string     $logisticCode
     * @return  bool
     */
    public function addLogisticAreaList($logisticCode)
    {
        $sql = "insert into {$this -> _table_logistic_area}";
        $sql .= '(logistic_code, country_id, province_id, city_id, area_id, country, province, city, area) ';
        $sql .= "SELECT '{$logisticCode}', a.area_id, b.area_id, c.area_id, d.area_id, ";
        $sql .= 'a.area_name, b.area_name, c.area_name, d.area_name ';
        $sql .= "FROM {$this -> _table_area} a ";
        $sql .= 'LEFT JOIN shop_area b ON a.area_id = b.parent_id ';
        $sql .= 'LEFT JOIN shop_area c ON b.area_id = c.parent_id ';
        $sql .= 'LEFT JOIN shop_area d ON c.area_id = d.parent_id ';
        $sql .= 'WHERE 1 = 1 AND a.parent_id = 0 ';
        $sql .= 'ORDER BY a.area_id desc';

        return $this -> _db -> execute($sql, $this -> _table_logistic_area);
    }
	/**
     * 批量删除指定ID的物流公司地区
     *
     * @param   string     $logisticCode
     * @return  bool
     */
    public function delLogisticAreaList($logisticCode)
    {
        return $this -> _db -> delete($this -> _table_logistic_area, "logistic_code = '{$logisticCode}'");
    }
	/**
     * 批量删除指定ID的物流公司地区价格
     *
     * @param   string     $logisticCode
     * @return  bool
     */
    public function delLogisticAreaPriceList($logisticCode)
    {
        return $this -> _db -> delete($this -> _table_logistic_area_price, "logistic_code = '{$logisticCode}'");
    }
	/**
     * 获取指定ID的物流公司地区列表
     *
     * @param   array     $where
     * @return  array
     */
    public function getLogisticAreaList($where = NULL)
    {
        if ($where['logistic_code']) {
            $condition[] = "logistic_code = '{$where['logistic_code']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        return $this -> _db -> fetchAll("SELECT * FROM {$this -> _table_logistic_area}  where 1=1 {$condition}");
    }
	/**
     * 获取物流公司地区列表对应的配送价格
     *
     * @param   array     $where
     * @return  array
     */
    public function getLogisticAreaPriceList($where = NULL)
    {
        if ($where['logistic_code']) {
            $condition[] = "logistic_code = '{$where['logistic_code']}'";
        }
        if ($where['area_id']) {
            $condition[] = "area_id = {$where['area_id']}";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        return $this -> _db -> fetchAll("SELECT * FROM {$this -> _table_logistic_area_price} where 1=1 {$condition}");
    }
    /**
     * 添加物流公司配送地区
     *
     * @param   array     $data
     * @return  int
     */
    public function addLogisticArea($data)
    {
        //file_put_contents('d:/1.php', print_r($data,1));
        //exit;
        $this -> _db -> insert($this -> _table_logistic_area, $data);
        return $this -> _db -> lastInsertId();
    }
    /**
     * 添加物流公司配送地区对应的价格
     *
     * @param   array     $data
     * @return  int
     */
    public function addLogisticAreaPrice($data)
    {
        $this -> _db -> insert($this -> _table_logistic_area_price, $data);
        return $this -> _db -> lastInsertId();
    }
	/**
     * 取得物流公司地区分页列表
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getLogisticAreaListWithPage($where, $page)
    {
        $country = 1;
        if ($where['logistic_code']) {
            $condition[] = "logistic_code = '{$where['logistic_code']}'";
        }
        if ($where['province_id']) {
            $condition[] = "province_id = {$where['province_id']}";
        }
        if ($where['city_id']) {
            $condition[] = "city_id = {$where['city_id']}";
        }
        if ($where['area_id']) {
            $condition[] = "area_id = {$where['area_id']}";
        }
        if ($where['delivery']) {
            $condition[] = "delivery = {$where['delivery']}";
        }
        if ($where['pickup']) {
            $condition[] = "pickup = {$where['pickup']}";
        }
        if ($where['cod']) {
            $condition[] = "cod = {$where['cod']}";
        }
        if ($where['open']) {
            $condition[] = "open = {$where['open']}";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        $sql = "FROM {$this -> _table_logistic_area} WHERE 1=1 {$condition} ";
		$sqlOfList = "SELECT * {$sql}";
		$sqlOfList .= "ORDER BY province_id, city_id, area_id ";
        $sqlOfList .= "LIMIT {$this -> _pageSize} OFFSET " . (($page - 1) * $this -> _pageSize);
        $sqlOfTotal = "SELECT count(*) {$sql}";

        $data['data'] = $this -> _db -> fetchAll($sqlOfList);
        $data['total'] = $this -> _db -> fetchOne($sqlOfTotal);
        return $data;
    }
	/**
     * 获取指定ID的物流公司地区
     *
     * @param   int     $logisticAreaID
     * @return  array
     */
    public function getLogisticAreaByID($logisticAreaID)
    {
        return $this -> _db -> fetchRow("SELECT * FROM {$this -> _table_logistic_area} WHERE logistic_area_id = '{$logisticAreaID}'");
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
		return $this -> _db -> update($this -> _table_logistic_area_price, $data, "logistic_area_price_id = {$logisticAreaPriceID}");
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
        $country = 1;
        if ($where['province_id']) {
            $condition[] = "b.area_id = {$where['province_id']}";
        } else {
            $condition[] = "b.parent_id = {$country}";
        }
        if ($where['city_id']) {
            $condition[] = "c.area_id = {$where['city_id']}";
        }
        if ($where['area_id']) {
            $condition[] = "d.area_id = {$where['area_id']}";
        }
        if ($where['code']) {
            $condition[] = "d.code = {$where['code']}";
        }
        if ($where['zip']) {
            $condition[] = "d.zip = {$where['zip']}";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }
        $sql = "FROM {$this -> _table_area} a ";
        $sql .= "LEFT JOIN {$this -> _table_area} b ON a.area_id = b.parent_id ";
        $sql .= "LEFT JOIN {$this -> _table_area} c ON b.area_id = c.parent_id ";
        $sql .= "LEFT JOIN {$this -> _table_area} d ON c.area_id = d.parent_id ";
        $sql .= "WHERE 1=1 {$condition} ";
		$sqlOfList = "SELECT a.area_name as country, b.area_name AS province, c.area_name AS city, ";
		$sqlOfList .= "d.area_name AS area, d.area_id AS area_id, d.code AS code, d.zip AS zip, d.strategy as strategy {$sql} ";
        $sqlOfList .= "LIMIT {$this -> _pageSize} OFFSET " . (($page - 1) * $this -> _pageSize);
        $sqlOfTotal = "SELECT count(*) {$sql}";
        $data['data'] = $this -> _db -> fetchAll($sqlOfList);
        if (is_array($data['data']) && count($data['data'])) {
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['strategy'] = unserialize($v['strategy']);
            }
        }
        $data['total'] = $this -> _db -> fetchOne($sqlOfTotal);
        return $data;
    

    }
    /**
     * 编辑操作区域列表(锁定/解锁)
     *
     * @param   array     $logisticAreaIDList
     * @param   array     $data
     * @return  int
     */
    public function editLogisticAreaLockName($logisticAreaIDList, $data)
    {
        if (is_array($logisticAreaIDList) && count($logisticAreaIDList)) {
            $condition = 'logistic_area_id in(' . implode(', ', $logisticAreaIDList) . ')';
        }
		return $this -> _db -> update($this -> _table_logistic_area, $data, $condition);
    }
	/**
     * 编辑物流公司操作区域
     *
     * @param   where     $where
     * @param   array   $data
     * @return  bool
     */
    public function editLogisticArea($where, $data)
    {
        if ($where['logistic_area_id']) {
            $condition[] = "logistic_area_id = {$where['logistic_area_id']}";
        }
        if ($where['lock_name']) {
            $condition[] = "lock_name = '{$where['lock_name']}'";
        }
        if ($where['area_id']) {
            $condition[] = "area_id = {$where['area_id']}";
        }
        if ($where['logistic_code'] !== NULL) {
            $condition[] = "logistic_code = '{$where['logistic_code']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = implode(' and ', $condition);
        }
        if ($condition) {
    		return $this -> _db -> update($this -> _table_logistic_area, $data, $condition);
        } else {
            return false;
        }
    }
	/**
     * 取得物流重量价格区间
     *
     * @param   void
     * @return  array
     */
    public function getLogisticAreaPriceBetween () {
        $sql = "SELECT min, max
                FROM `shop_logistic_area_price`
                WHERE logistic_code = (
                SELECT logistic_code
                FROM shop_logistic_area_price
                LIMIT 1 )  and area_id=(SELECT area_id
                FROM shop_logistic_area_price
                LIMIT 1)";
        return $this -> _db -> fetchAll($sql);
    }
    
    /**
     * 设置模板
     *
     * @param   array     $data
     * @return  void
     */
    public function setLogisticTemplate($data)
    {
        $logistic = $this -> getLogisticTemplate($data['logistic_code'], $data['type']);
        
        if ( $logistic ) {
            $this -> _db -> update($this -> _table_logistic_template, $data, "id={$logistic['id']}");
        }
        else {
            $this -> _db -> insert($this -> _table_logistic_template, $data);
        }
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
        return $this -> _db -> fetchRow("SELECT * FROM {$this->_table_logistic_template} where logistic_code = '{$logistic_code}' and type = {$type}");
    }
    
    /**
     * 添加快递100推送信息
     *
     * @param   array   $data
     * @return  void
     */
    public function addKuaidi100($data)
    {
        $temp = $this -> _db -> fetchRow("select id from {$this -> _table_logistic_kuaidi100} where logistic_no = '{$data['logistic_no']}'");
        if ($temp) {
            $this -> _db -> update($this -> _table_logistic_kuaidi100, $data, "id = {$temp['id']}");
        }
        else {
            $this -> _db -> insert($this -> _table_logistic_kuaidi100, $data);
        }
    }
    
    /**
     * 添加快递100推送日志
     *
     * @param   array   $data
     * @return  void
     */
    public function addKuaidi100Track($data)
    {
        $data['add_time'] = time();
        $this -> _db -> insert($this -> _table_logistic_kuaidi100_track, $data);
    }
    

}
