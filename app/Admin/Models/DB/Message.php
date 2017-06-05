<?php
class Admin_Models_DB_Message
{
	private $_db = null;

	private $_table_message = 'shop_message';
	private $_table_message_member = 'shop_message_member';

	private $_error;

	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this->_db = Zend_Registry::get('db');
		//$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
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
		$_condition = $this->getBrowseCondition($params);

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
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_message}` WHERE ". implode(' AND ', $_condition) ." ORDER BY message_id desc limit {$limit}";

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
		$_condition = $this->getBrowseCondition($params);

		$sql = "SELECT count(*) as count FROM `{$this->_table_message}` WHERE ". implode(' AND ', $_condition);

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
		!empty($params['start_ts']) && $_condition[] = "created_ts >= '{$params['start_ts']} 00:00:00'";
		!empty($params['end_ts'])   && $_condition[] = "created_ts <= '{$params['end_ts']} 23:59:59'";
		!empty($params['type'])     && $_condition[] = "type = '{$params['type']}'";

		//$_join[] = "LEFT JOIN `{$this->_table_message_user}` u on shop_message.message_id = u.message_id";
		return $_condition;
	 }

	/**
     * 添加信息数据
     *
     * @param    array  
     *
     * @return   array
     */
	 public function add($param)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $param = Custom_Model_Filter::filterArray($param, $filterChain);

		$param = array(
			'type'       => $param['type'],
			'title'      => $param['title'],
			'content'    => $param['content'],
			'created_id' => $param['created_id'],
			'created_by' => $param['created_by'],
			'to_who'     => $param['to_who'],
		);

		if (false ===  $this->_db->insert($this->_table_message, $param)) {
			$this->_error = '插入站内信息失败';
			return false;
		}

		return $this->_db->lastInsertId();
	 }

	/**
     * 批量出入站内信息用户数据
     *
     * @param    array  
     *
     * @return   array
     */
	 public function addMessageMembersByMessageId($message_id, $params)
	 {
		$message_id = intval($message_id);
		if ($message_id < 1) {
			$this->_error = '信息ID不正确';
			return false;
		}

		if (count($params) < 1) {
			$this->_error = '没有要插入的数据';
			return false;
		}


		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		$member_params = array();
		$sql = "INSERT INTO `{$this->_table_message_member}` (`message_id`, `member_id`, `user_name`) VALUES";
		foreach ($params as $key => $param) {
			$member_params[] = "('{$message_id}', '{$param['member_id']}', '{$param['user_name']}')";
			if ($key > 0 && $key % 100 == 0) {
				
				$this->_db->execute($sql . implode(',', $member_params));

				$member_params = array();
			}
		}

		if (count($member_params) > 0) {
			$this->_db->execute($sql . implode(',', $member_params));
		}

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