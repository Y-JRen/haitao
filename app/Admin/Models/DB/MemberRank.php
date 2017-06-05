<?php

class Admin_Models_DB_MemberRank
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 会员等级表名
     * 
     * @var    string
     */
	private $_table = 'shop_member_rank';
	
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
     * 取得会员等级信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getRank($where = null)
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
     * 取得最低非特殊会员等级
     *
     * @param    string $where
     * @return   array
     */
	public function getMinRank()
	{
		$sql = 'SELECT * FROM `' . $this -> _table . '` WHERE is_special=0 ORDER BY min_point ASC LIMIT 1';
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
     * 添加会员等级
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addRank(array $data)
	{
		$row = array (
                      'rank_name' => $data['rank_name'],
                      'min_point' => $data['min_point'],
                      'max_point' => $data['max_point'],
                      'discount' => $data['discount'],
                      'is_special' => $data['is_special'],
                      'show_price' => $data['show_price']
                      );
        
        $this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新指定会员等级
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateRank(array $data, $id)
	{
		$set = array (
                      'rank_name' => $data['rank_name'],
                      'min_point' => $data['min_point'],
                      'max_point' => $data['max_point'],
                      'discount' => $data['discount'],
                      'is_special' => $data['is_special'],
                      'show_price' => $data['show_price']
                      );
        
        $where = $this -> _db -> quoteInto('rank_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
	
	/**
     * 删除会员等级
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteRank($id)
	{
		$where = $this -> _db -> quoteInto('rank_id = ?', $id);
		return $this -> _db -> delete($this -> _table, $where);
	}
	
	/**
     * 更改是否显示价格
     *
     * @param    int    $id
     * @param    int    $showPrice
     * @return   int    lastInsertId
     */
	public function showPrice($id, $showPrice)
	{
		$set = array ('show_price' => $showPrice);
		$where = $this -> _db -> quoteInto('rank_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> update($this -> _table, $set, $where);
		}
	}
	
	/**
     * 更改特殊商品折扣
     *
     * @param    int      $rankId
     * @param    int      $goodsId
     * @param    string   $value
     * @return   int      lastInsertId
     */
	public function updateGoodsDiscount($rankId, $goodsId, $value)
	{
		$rank = array_shift($this -> getRank(array('rank_id' => $rankId)));
		$discount = unserialize($rank['discount']);
		$discount['goodsDiscount'][$goodsId] = $value;
		$set = array ('discount' => $discount);
		$where = $this -> _db -> quoteInto('rank_id = ?', $rankId);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
}