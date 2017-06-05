<?php

class Admin_Models_API_Attribute
{
	/**
     * DB对象
     */
	private $_db = null;
	
    /**
     * 错误信息
     */
	public $error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Attribute();
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		return $this -> _db -> fetch($where, $fields, $orderBy, $page, $pageSize);
	}
	
	/**
     * 添加或修改数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function edit($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($data['attr_title'] == '') {
			$this -> error = 'no_name';
			return false;
		}
		if ($id === null) {
		    $result = $this -> _db -> insert($data);
		    if(!$result){
				$this -> error = 'exists';
				return false;
		    }
		} else {
			$result = $this -> _db -> update($data, (int)$id);
		}
		
		return $result;
	}
	
	/**
     * 更改属性名称
     *
     * @param    int    $id
     * @return   void
     */
	public function setAttrTitle($attr_id, $attr_title)
	{
	    $this -> _db -> setAttrTitle($attr_id, $attr_title);
	}
	
	/**
     * 删除数据
     *
     * @param    int    $id
     * @return   void
     */
	public function delete($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> delete((int)$id);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    return $result;
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
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	
	/**
     * 更改状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateStatus((int)$id, $status) <= 0) {
				exit('failure');
			}
		}
	}
	
	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		
		if ((int)$id > 0) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }
		}
	}
	
	/**
     * 构造分类树.
     *
     * @param    array    $deny
     * @param    array    $data
     * @param    int      $parentID
     * @return   array
     */
	public function attrTree($deny=null, $data=null, $parentID=0, $where=null)
	{
        static $tree, $step;
        if(!$data){
            $data = $this -> _db-> fetch($where);
        }
        foreach($data as $v){
            if($v['parent_id'] == $parentID){
                $step++;
                $tree[$v['attr_id']] = array(
                	                        'attr_id'=>$v['attr_id'],
                                            'attr_title'=>$v['attr_title'],
                                            'parent_id'=>$v['parent_id'],
                                            'attr_status'=>$v['attr_status'],
                                            'attr_sort'=>$v['attr_sort'],
                                            'step'=>$step
                                            );
                if(is_array($deny)){
                    foreach($deny as $x){
                        if($x == $v['attr_id'] || strstr($v['attr_path'],','.$x.',')){
                            $tree[$v['attr_id']]['deny'] = 1;
                            break;
                        }
                    }
                }
                if($parentID){
                    $tree[$parentID]['leaf'] = 0;
                }
                $this -> attrTree($deny,$data,$v['attr_id']);
                $step--;
            }
        }
        if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
            $tree[$parentID]['leaf'] = 1;
        }
        return $tree;
	}
	
	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'exists'=>'该属性已存在!',
			         'not_exists'=>'该属性不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_name'=>'请填写属性名称!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}

}