<?php

class Shop_Models_DB_MemberAddress
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 会员收货地址表名
     * 
     * @var    string
     */
	private $_table = 'shop_member_address';
	
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
     * 取得会员收货地址信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getAddress($where = null)
	{
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加会员收货地址
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addAddress(array $data)
	{
        $this -> _db -> insert($this -> _table, $data);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新会员收货地址
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateAddress(array $data, $id)
	{
		$set = $data;
        if ($data['zip']){
			$set['zip'] = $data['zip'];
		}
        $where = $this -> _db -> quoteInto('address_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
	
	/**
	 * 
	 * 收货默认地址
	 * @param $aid
	 */
	public function updateDefaultAddress($aid,$member_id)
	{
		$where = $this->_db->quoteInto('member_id = ?', $member_id);
		$set = array('is_default'=>0);
		$this->_db->update($this->_table, $set, $where);
		$where = $this -> _db -> quoteInto('address_id = ?', $aid);
		$set = array('is_default'=>1);
		return $this->_db->update($this->_table, $set, $where);
	}
    /**
     * 编辑会员送货地址信息
     *
     * @param    int    $time
     * @param    int      $id
     * @return   bool
     */
    public function updateAddressUseTime($addressID, $time, $memberID)
    {
		$where = $this -> _db -> quoteInto('address_id = ?', $addressID);
		$where .= $this -> _db -> quoteInto(' AND member_id = ?', $memberID);
        return $this -> _db -> update($this -> _table, array('use_time' => $time), $where);
    }
	
	/**
     * 删除会员收货地址
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteAddress($id,$member_id)
	{
		$where = $this -> _db -> quoteInto('address_id = ?', $id);
		$where .= $this -> _db -> quoteInto(' AND member_id = ?', $member_id);
		return $this -> _db -> delete($this -> _table, $where);
	}
	
	
	public function getAddressById($id)
	{
		$where = " where address_id = $id";
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $where;
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
     * 根据会员ID删除会员收货地址
     *
     * @param    int      $memberId
     * @return   int      lastInsertId
     */
	public function deleteAddressByMemberId($memberId)
	{
		$where = $this -> _db -> quoteInto('member_id = ?', $memberId);
		return $this -> _db -> delete($this -> _table, $where);
	}
	/**
	 * 
	 * 获取默认收货地址
	 * @param int $member_id
	 */
	public function getDefaultAddress($member_id)
	{
		$where = " where is_default = 1 and member_id = $member_id ";
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $where;
		return $this -> _db -> fetchRow($sql);
	}
	
	public function getDefaultAddressId($member_id)
	{
		$sql = "select address_id from `{$this->_table}` where member_id ={$member_id} and is_default = 1";
		return $this->_db->fetchOne($sql);
	}
	
}