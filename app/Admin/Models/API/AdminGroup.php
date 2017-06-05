<?php

class Admin_Models_API_AdminGroup
{
	/**
     * 管理员组 DB
     * 
     * @var Admin_Models_DB_Group
     */
	private $_db = null;
	
	/**
     * 权限 API
     * 
     * @var $_privilege
     */
	private $_privilege = null;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_AdminGroup();
	}
	
	/**
     * 取得所有管理组
     *
     * @return   array
     */
	public function getAllGroup($fields = '*')
	{
		return $this -> _db -> getGroup('null',$fields);
	}
	
	/**
     * 取得指定ID的管理组
     *
     * @param    int    $id
     * @return   array
     */
	public function getGroupById($id)
	{
		$group = $this -> _db -> getGroup(array('group_id' => $id));
		
		if (is_array($group)) {
			return array_shift($group);
		}
	}
	
	/**
     * 取得指定管理组名的管理组
     *
     * @param    string    $name
     * @return   array
     */
	public function getGroupByName($name)
	{
		$group = $this -> _db -> getGroup(array('group_name' => $name));
		
		if (is_array($group)) {
			return array_shift($group);
		}
	}
	
	/**
     * 添加/编辑管理组
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function editGroup($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($data['group_name'] == '') {
			return 'noGroupName';
		}
		
		if ($data['group_menu']) {
			foreach($data['group_menu'] as $k => $v){
				$r = explode(',', $v);
				foreach($r as $mid){
					$mid && $menu[$mid] = $mid;
				}
			}
			$data['group_menu'] = implode(',', $menu);
		}else{
			$data['group_menu'] = '';
		}
		
		if ($data['group_privilege']) {
			$data['group_privilege'] = serialize($data['group_privilege']);
		}else{
			$data['group_privilege'] = '';
		}
		
		$data['add_time'] = time ();
		
		$group = $this -> getGroupByName($data['group_name']);
		
		if ($id === null) {
			
			if ($group) {
			    return 'groupExists';
		    }
		    
		    $result = $this -> _db -> addGroup($data);
		} else {
			
			if ($group && $group['group_id'] != $id) {
			    return 'groupExists';
		    }
		    
			$exists = $this -> getGroupById($id);
			
			if (!$exists) {
			    return 'groupNoExists';
		    }
		    
		    
			$id > 0 && $result = $this -> _db -> updateGroup($data, (int)$id);
		}
		
		if (is_numeric($result) && $result >= 0) {
		    return ($id === null) ? 'addGroupSucess' : 'editGroupSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 删除管理组
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteGroupById($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deleteGroup((int)$id);
		    if (is_numeric($result) && $result > 0) {
		        return 'deleteGroupSucess';
		    } else {
			    return 'error';
		    }
		}
	}
}