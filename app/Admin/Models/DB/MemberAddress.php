<?php

class Admin_Models_DB_MemberAddress
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
     * 取得收货地址手机
     *
     * @param    array    $where
     * @return   array
     */
	public function getexportAddress($where = null)
	{
		$whereSql = ' WHERE 1=1 and mobile >0 ';
		$sql = 'SELECT mobile FROM `' . $this -> _table . '` ' . $whereSql;
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
		$row = array (
                      'member_id' => $data['member_id'],
                      'consignee' => $data['consignee'],
                      'email' => $data['email'],
                      'province_id' => $data['province'],
                      'city_id' => $data['city'],
                      'area_id' => $data['area'],
                      'address' => $data['address'],
                      'zip' => $data['zip'],
                      'phone' => $data['phone'],
                      'mobile' => $data['mobile'],
                      'fax' => $data['fax'],
                      'add_time' => $data['add_time']
                      );
        
        $this -> _db -> insert($this -> _table, $row);
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
		$set = array (
                      'consignee' => $data['consignee'],
                      'email' => $data['email'],
                      'province_id' => $data['province'],
                      'city_id' => $data['city'],
                      'area_id' => $data['area'],
                      'address' => $data['address'],
                      'zip' => $data['zip'],
                      'phone' => $data['phone'],
                      'mobile' => $data['mobile'],
                      'fax' => $data['fax'],
                      'add_time' => $data['add_time']
                      );
        
        $where = $this -> _db -> quoteInto('address_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
	
	/**
     * 删除会员收货地址
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteAddress($id)
	{
		$where = $this -> _db -> quoteInto('address_id = ?', $id);
		return $this -> _db -> delete($this -> _table, $where);
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
}