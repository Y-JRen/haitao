<?php

class Shop_Models_DB_Message
{
	protected $_db = null;
	
	private $_table_message = 'shop_message';
	private $_table_message_member = 'shop_message_member';
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry :: get('config') -> view -> page_size;
	}

	/**
     * 根据信息ID获取信息
     *
     * @param    int
     *
     * @return   array
     */
	public function get($message_id)
	{
		$message_id = intval($message_id);
		if ($message_id < 1) {
			$this->_error = '消息ID不正确';
			return false;
		}
		$field = array(
			'message_id',
			'type',
			'to_who',
			'title',
			'content',
			'created_id',
			'created_by',
			'created_ts',
			'is_deleted',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_message}` WHERE message_id = '{$message_id}' limit 1";

		return $this->_db->fetchRow($sql);
	}
	
	/**
     * 获取信息列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function browse($params, $limit)
	 {	
		list($_condition, $_join) = $this->getBrowseCondition($params);

		$field = array(
			'message.message_id',
			'type',
			'to_who',
			'title',
			'content',
			'created_id',
			'created_by',
			'created_ts',
			'is_deleted',
			'is_read',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_message}` message ". implode(' ', $_join) .
			   " WHERE ". implode(' AND ', $_condition) ." ORDER BY message_id desc limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取信息总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getCount($params)
	 {	
		list($_condition, $_join) = $this->getBrowseCondition($params);

		$sql = "SELECT count(*) as count FROM `{$this->_table_message}` message ". implode(' ', $_join) .
			   " WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getBrowseCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		$_condition[] = "is_deleted = '0'";
		!empty($params['member_id'])  && $_condition[] = "member_id = '{$params['member_id']}'";
		isset($params['read'])        && $_condition[] = "is_read   = '{$params['read']}'";

		$_join[] = "LEFT JOIN `{$this->_table_message_member}` m on message.message_id = m.message_id";
		return array($_condition, $_join);
	 }

	 /**
     * 更改站内信会员记录
     *
     * @param    int
	 * @param    array
     *
     * @return   boolean
     */
	 public function updateMessageMemberInfo($params, $set)
     {
	     $params['message_id'] = intval($params['message_id']);
		 if ($params['message_id'] < 1) {
			$this->_error = '信息ID不正确';
		 }

		 $_condition[] = "message_id = '{$params['message_id']}'";
		 !empty($params['member_id']) && $_condition[] = "member_id = '{$params['member_id']}'";

		 $data = array();

		 !empty($set['is_read']) && $data[] = "is_read= '{$set['is_read']}'";
		 
		 $sql = "UPDATE {$this->_table_message_member} SET ". implode(',', $data) ." WHERE ". implode(' AND ', $_condition);

		 $this->_db->execute($sql);
	 }
}