<?php

class Admin_Models_API_Admin
{
	/**
     * 管理员 DB
     * 
     * @var Admin_Models_DB_Admin
     */
	private $_db = null;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Admin();
	}
	
	/**
     * 取得所有管理员信息
     *
     * @param    string    $where
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getAllAdmin($where, $page = null, $pageSize = null)
	{
		if (is_array($where)) {
			$where['group_id'] && $whereSql .= " and A.group_id={$where['group_id']}";
	        $where['admin_name'] && $whereSql .= " and (admin_name LIKE '%" . $where['admin_name'] . "%' or real_name LIKE '%" . $where['admin_name'] . "%')";
		}else{
			$whereSql = $where;
		}
		return $this -> _db -> getAdmin($whereSql, $page, $pageSize);
	}
	
	/**
     * 取得指定ID的管理员信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getAdminById($id)
	{
		return  @array_shift($this -> _db -> getAdmin(array('A.admin_id' => $id)));
	}
	
	/**
     * 取得指定管理员组ID的所有管理员信息
     *
     * @param    int    $grouId
     * @return   array
     */
	public function getAdminByGroupId($grouId)
	{
		return $this -> _db -> getAdmin(array('A.group_id' => $grouId));
	}
	
	/**
     * 取得指定用户名的管理员信息
     *
     * @param    string    $name
     * @return   array
     */
	public function getAdminByName($name)
	{
		return  @array_shift($this -> _db -> getAdmin(array('A.admin_name' => $name)));
	}
	
	/**
     * 取得所有管理员人数
     *
     * @return   int
     */
	public function getAllAdminCount($where)
	{

		if (is_array($where)) {
			$where['group_id'] && $whereSql .= " and A.group_id={$where['group_id']}";
	        $where['admin_name'] && $whereSql .= " and (admin_name LIKE '%" . $where['admin_name'] . "%' or real_name LIKE '%" . $where['admin_name'] . "%')";
		}else{
			$whereSql = $where;
		}

		return $this -> _db -> getAdminCount($whereSql);
	}
	
	/**
     * 取得管理员状态显示代码
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
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	
	/**
     * 添加/编辑管理员
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function editAdmin($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
		if ($data['admin_name'] == '') {
			return 'noUserName';
		}
		
		if ($data['menu']) {
			foreach($data['menu'] as $k => $v){
				$r = explode(',', $v);
				foreach($r as $mid){
					$mid && $menu[$mid] = $mid;
				}
			}
			$data['menu'] = implode(',', $menu);
		}else{
			$data['menu'] = '';
		}
		
		if ($data['privilege']) {
			$data['privilege'] = implode(',', $data['privilege']);
		}else{
			$data['privilege'] = '';
		}
		
		if ($data['password'] != '' && $data['confirm_password'] != '') {
			
		    if ($data['password'] != $data['confirm_password']) {
		        return 'noSamePassword';
			}
		} elseif ($id === null || ($data['password'] != '' || $data['confirm_password'] != '')) {
			return 'noPassword';
		}
		
		if ($data['password'] !='') {
		    $data['password'] = Custom_Model_Encryption::getInstance() -> encrypt($data['password']);
		}
		
		$data['add_time'] = time();
		
		$adminCertification = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$data['add_admin'] = $adminCertification['admin_name'];
		
		$groupAPI = new Admin_Models_API_AdminGroup();
		$group = $groupAPI -> getGroupById($data['group_id']);
		if (!$group['type']) {
		    $data['lid'] = 0;
		}
		$admin = $this -> getAdminByName($data['admin_name']);
		
		if ($id == null) {
			
		    if ($admin) {
			    return 'adminExists';
		    }
		    
		    $result = $this -> _db -> addAdmin($data);
		} else {
			
			if ($admin && $admin['admin_id'] != $id) {
			    return 'adminExists';
		    }
		    
			$exists = $this -> getAdminById($id);
			
			if (!$exists) {
			    return 'adminNoExists';
		    }
			
			if ((int)$id == 1 && $adminCertification['admin_id'] != 1) {
		    	return 'forbidden';
		    }
		    
			$result = $this -> _db -> updateAdmin($data, (int)$id);
		}
		
		if (is_numeric($result) && $result >= 0) {
		    return ($id === null) ? 'addAdminSucess' : 'editAdminSucess';
		} else {
			return 'error';
		}
	}
	

	/**
     * 添加/编辑管理员
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function changePassword($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());        
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if($data['password'] != $data['confirm_password']){
            return 'noSamePassword';
        }
		/*
        if($data['old_password']){
            $adminRow = $this->getAdminById($id);
            if(md5($data['old_password'])!=$adminRow ['password']){
                  return 'oldPasswordError';
            }
        }*/
        $result = $this -> _db -> changePassword($data, (int)$id);

        return 'editAdminSucess';
    }



	/**
     * 删除管理员
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteAdminById($id)
	{
		if ((int)$id > 0) {
			
		    if ((int)$id == 1) {
		    	return 'forbidden';
		    }
		    
		    $result = $this -> _db -> deleteAdmin((int)$id);
		    if (is_numeric($result) && $result > 0) {
		        return 'deleteAdminSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	
	/**
     * 更改管理员状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		$id = (int)$id;
		if ($id ==1 ) {
		    exit('forbidden');
		}
		if ($id > 0) {
		    if($this -> _db -> updateStatus($id, $status) <= 0) {
			    exit('failure');
		    }
		}
	}
}