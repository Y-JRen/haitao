<?php

class Admin_Models_API_Message
{
	private $_db = null;
	private $_error;
	private $_search_option;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this->_db = new Admin_Models_DB_Message();
		$this->_auth = Admin_Models_API_Auth::getInstance()->getAuth();
		$this->_search_option = array(
			'message_type' => array(
				'1' => '系统公告',
				'2' => '促销通知',
			),
			'message_to_who'      => array(
				'1' => '所有会员',
				'2' => '最近一周登录会员',
			),
		);
	}

	/**
     * 返回搜索参数
	 *
     * @return   string
     */
	public function getSearchOption()
	{
		return $this->_search_option;
	}

	/**
     * 根据消息ID获取消息
     *
     * @param    int
     *
     * @return   array
     */
	public function get($message_id)
	{
		if (intval($message_id) < 1) {
			$this->_error = '消息ID不正确';
			return false;
		}

		if (false === ($info = $this->_db->get($message_id))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($info) < 1) {
			return array();
		}

		foreach ($info as $key => $val) {
			if (in_array($key, array('type', 'to_who'))) {
				$info['message_'. $key] = $this->_search_option['message_'. $key][$val];
			}
		}

		return $info;
		
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
		$infos = $this->_db->browse($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['message_type'] = $this->_search_option['message_type'][$info['type']];
			$info['to_whos'] = $this->_search_option['message_to_who'][$info['to_who']];
		}

		return $infos;
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
		$count = $this->_db->getCount($params);

		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
	}

	public function sendMessage($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$params = Custom_Model_Filter::filterArray($params, $filterChain);

		if ($params['title'] == '') {
			$this->_error = '信息标题为空';
			return false;
		}

		if ($params['content'] == '') {
			$this->_error = '信息内容为空';
			return false;
		}

		if (!in_array($params['type'], array_keys($this->_search_option['message_type']))) {
			$this->_error = '信息类型不正确';
			return false;
		}

		if (!in_array($params['type'], array_keys($this->_search_option['message_to_who']))) {
			$this->_error = '发送给谁不正确';
			return false;
		}

		$user_params = array();

		if ($params['to_who'] == '2') {
			$user_params['start_ts'] = strtotime(date('Y-m-d', strtotime('-7 days')));
		}

		$member_db = new Admin_Models_DB_Member();

		$member_infos = $member_db->getMemberInfoByCondition($user_params);
		
		if (count($member_infos) < 1) {
			$this->_error = '没有相关会员数据';
			return false;
		}

		$params['created_id'] = $this->_auth['admin_id'];
		$params['created_by'] = $this->_auth['admin_name'];

		if (false === ($message_id = $this->_db->add($params))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (false === $this->_db->addMessageMembersByMessageId($message_id, $member_infos)) {
			$this->_error = $this->_db->getError();
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