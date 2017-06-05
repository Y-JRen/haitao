<?php

class Shop_Models_API_Message
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
		$this->_db = new Shop_Models_DB_Message();
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

	/**
     * 更改站内信会员记录
     *
     * @param    int
	 * @param    array
     *
     * @return   boolean
     */
	public function updateMessageMemberInfo($message_id, $params)
	{
		return $this->_db->updateMessageMemberInfo($message_id, $params);
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