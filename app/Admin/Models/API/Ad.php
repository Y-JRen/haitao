<?php
class Admin_Models_API_Ad
{
	
	public function __construct()
	{
		$this->_db = new Admin_Models_DB_Ad();
	}
	
	/**
	 * 添加广告位
	 * @param array $data
	 * @return array
	 */
	public function AddAd($data)
	{
	   if ($data['name'] == '') {
			return array('isok'=>false,'msg'=>'请输入广告名称');
		}		
		if($data['start_time'] >= $data['end_time'] ){
			return array('isok'=>false,'msg'=>'开始日期必须小于结束日期');
		}		
		$res =  $this->_db->AddAd($data);
		if($res)
		{
		    return array('isok'=>true,'msg'=>'添加广告成功');
		}else{
			return array('isok'=>false,'msg'=>'添加广告失败');
		}
	}
	/**
	 * 编辑广告位
	 * @param array $data
	 * @return array
	 */
	public function editAd($data)
	{
		if ($data['name'] == '') {
			return array('isok'=>false,'msg'=>'请输入广告名称');
		}
		if($data['start_time'] >= $data['end_time'] ){
			return array('isok'=>false,'msg'=>'开始日期必须小于结束日期');
		}
		$res =  $this->_db->editAd($data);
		if($res)
		{
		  return array('isok'=>true,'msg'=>'编辑广告成功');
		}else{
		  return array('isok'=>false,'msg'=>'编辑广告失败');
		}
	}
    /**
     * 广告列表
     * @param string $where
     * @param string $fields
     * @param string $orderBy
     * @param int $page
     * @param int $pageSize
     * @return array
     */
	public function getAdList($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		$datas= $this->_db->get($where, $fields, $orderBy, $page, $pageSize,'ad');
		return $datas;
	}
	/**
	 * 广告数统计
	 * @param string $where
	 */
	public function getAdCount($where=null){
		return $this->_db->getCount($where,'ad');
	}
	/**
	 * 广告位统计
	 * @param string $where
	 */
	public function getAdboardCount($where=null){
		return $this->_db->getCount($where,'board');
	}
	/**
	 * 广告位统计
	 * @param string $where
	 * @param string $fields
	 * @param string $orderBy
	 * @param string $page
	 * @param string $pageSize
	 * @return array
	 */
	public function getAdboardList($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		$datas= $this->_db->get($where, $fields, $orderBy, $page, $pageSize,'board');
		return $datas;
	}	
	
	/**
	 * 更新广告字段
	 * @param int $id
	 * @param string $field
	 * @param string $val
	 * @return string
	 */
	public function updateField($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
		$filterChain->addFilter(new Zend_Filter_StringTrim())->addFilter(new Zend_Filter_StripTags());
	
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
	
		if ((int)$id > 0) {
			$result = $this->_db->updateField((int)$id, $field, $val);
			if (is_numeric($result) && $result > 0) {
				return 'success';
			} else {
				return 'error';
			}
		}
	}
	
	
	/**
	 * 获取状态信息
	 *
	 * @param    string    $url
	 * @param    int       $id
	 * @param    int       $status
	 * @return   string
	 */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
			case 0:
				return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击开启"><u><font color="red">关闭</font></u></a>';
				break;
			case 1:
				return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击关闭"><u>开启</u></a>';
				break;
		}
	}
	
	public function changeStatus($id, $status)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateField((int)$id,'status' ,$status) <= 0) {
				exit('failure');
			}
		}
	}
	/**
	 * 添加广告位
	 * @param array $data
	 * @return array
	 */
	public function  addAdboard($data)
	{
		if ($data['name'] == '') {
			return array('isok'=>false,'msg'=>'请输入广告位名称');
		}
		
		$res =  $this->_db->addAdboard($data);
		if($res)
		{
		   return array('isok'=>true,'msg'=>'添加广告位成功');
		}else{
			return array('isok'=>false,'msg'=>'添加广告位失败');
		}
		
	}
	/**
	 * 根据广告id获取广告信息
	 * @param int $id
	 */
	public  function getAdById($id)
	{
		return $this->_db->getAdById($id);
	}
	/**
	 * 根据广告位id获取广告位信息
	 * @param int $id
	 */
	public function  getAdboardById($id)
	{
		return $this->_db->getAdboardById($id);
	}
	/**
	 * 删除广告位
	 * @param int $id
	 */
	public function delAdboard($id){
		return $this->_db->delAdboard($id);
	}
	
	/**
	 * 删除广告
	 * @param int $id
	 */
	public function delAd($id){
		return $this->_db->delAd($id);
	}
	/**
	 * 编辑广告位
	 * @param array $data
	 * @return array
	 */
	public function  editAdboard($data)
	{
		if ($data['name'] == '') {
			return array('isok'=>false,'msg'=>'请输入广告位名称');
		}
	
		$res =  $this->_db->editAdboard($data);
		if($res)
		{
			return array('isok'=>true,'msg'=>'编辑广告位成功');
		}else{
			return array('isok'=>false,'msg'=>'编辑广告位失败');
		}
	
	}
}