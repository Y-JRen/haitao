<?php

class Admin_Models_API_Payment
{
	public function __construct()
	{
		$this->_db = new Admin_Models_DB_Payment();

	}
	/**
     * 添加
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function add($data, &$error){
        //$filterChain = new Zend_Filter();
        //$filterChain->addFilter(new Zend_Filter_StringTrim())
                    //->addFilter(new Zend_Filter_StripTags());
        //$data = Custom_Model_Filter::filterArray($data, $filterChain);

        $data['config']['bank'] = stripslashes($data['config']['bank']);
        $data['config'] = serialize($data['config']);
        if(is_null($data['name'])){
            $error = 'noName';
            return false;
        }
        if($this->_db->add($data)){
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }

	/**
     * 取数量
     *
     * @return   int
     */
    public function getCount($search=null){
        if ($search['name'] ) {
           $whereSql .= " and name like '%{$search['name']}%'";
        }
        if ($search['pay_type']!== ''  &&  !is_null($search['pay_type'])) {
           $whereSql .= " and pay_type = '{$search['pay_type']}'";
        }
        if ($search['status']!== '' && !is_null($search['status'])) {
           $whereSql .= " and status = '{$search['status']}'";
        }
        if ($search['is_bank']!== '' && !is_null($search['is_bank'])) {
            $whereSql .= " and is_bank = '{$search['is_bank']}'";
        }
        if ($search['lid']) {
            $whereSql .= " and lid = '{$search['lid']}'";
        }
        return $this->_db->getCount($whereSql);
    }
	/**
     * 取列表
     *
     * @param    string $fields
     * @param    string $orderBy
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function get($search = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
        if ($search['name'] ) {
           $whereSql .= " and name like '%{$search['name']}%'";
        }
        if ($search['pay_type']!== ''  &&  !is_null($search['pay_type'])) {
           $whereSql .= " and pay_type = '{$search['pay_type']}'";
        }
        if ($search['status']!== '' && !is_null($search['status'])){
           $whereSql .= " and status = '{$search['status']}'";
        }
        if ($search['is_bank']!== '' && !is_null($search['is_bank'])){
            $whereSql .= " and is_bank in ({$search['is_bank']})";
        }
        if ($search['lid']) {
            $whereSql .= " and lid = '{$search['lid']}'";
        }
		return $this->_db->get($whereSql,$fields, $orderBy, $page, $pageSize);
	}
	/**
     * 编辑列表可以编辑项目
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   string
     */
	public function ajaxupdate($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());
		
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		
		if ((int)$id > 0) {
		    $result = $this->_db->ajaxupdate((int)$id, $field, $val);
		    if (is_numeric($result) && $result > 0) {
		        return 'ajaxUpdateSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	/**
     * 删除文章
     *
     * @param    int       $id
     * @param    string    $error
     * @return   bool
     */
    public function del($id,&$error){
        if($this->_db->del($id)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }
	/**
     * 取某ID的信息
     *
     * @param    int    $id
     * @return   array
     */
    public function getPaymentByID($id){
        return $this->_db->getPaymentByID($id);
    }
	/**
     * 编辑信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function edit($data,&$error){
	    //$filterChain = new Zend_Filter();
        //$filterChain->addFilter(new Zend_Filter_StringTrim())
        //          ->addFilter(new Zend_Filter_StripTags());
        //$data = Custom_Model_Filter::filterArray($data, $filterChain);
        $data['config']['bank'] = stripslashes($data['config']['bank']);
        $data['config'] = serialize($data['config']);
        if(is_null($data['name'])){
            $error = 'noName';
            return false;
        }
        if($ct=$this->_db->edit($data) || $ct==0){
            return true;
        }else{
            $error = 'editFail';
            return false;
        }
    }
}
