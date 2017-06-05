<?php

class Admin_Models_API_LogicStatus
{
	/**
     * DB对象
     */
	private $_db = null;
	
	/**
     * 缓存路径
     */
	private $_dataDir = null;
	
    /**
     * 错误信息
     */
	private $error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_LogicStatus();
		$this -> _config = Zend_Registry::get('config');
		$this -> _dataDir = Zend_Registry::get('systemRoot') . '/data/admin/stock/';
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @return   array
     */
	public function get($where = null)
	{
		return $this -> _db -> fetch($where);
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
        $filterChain -> addFilter(new Zend_Filter_StringTrim());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($data['name'] == '') {
			$this -> error = 'no_name';
			return false;
		}
		$data['add_time'] = time ();
		if ($id === null) {
		    $result = $this -> _db -> insert($data);
		    if(!$result){
				$this -> error = 'exists';
				return false;
		    }
		} else {
			$result = $this -> _db -> update($data, (int)$id);
		}
		$this -> cache();
		return $result;
	}
	
	/**
     * 缓成数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function cache($data, $id = null)
	{
		$file = $this -> _dataDir.'logic.status';
		$r = $this -> _db -> fetch();
		foreach ($r as $v) {
		    $array[$v['id']] = $v['name'];
		}
		file_put_contents($file, Zend_Json::encode($array));
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
			         'exists'=>'该状态已存在!',
			         'not_exists'=>'该状态不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_name'=>'请填写状态名称!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}

}