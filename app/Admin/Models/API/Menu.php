<?php

class Admin_Models_API_Menu
{
	/**
     * DB对象
     */
	private $_db = null;
	
    /**
     * 错误信息
     */
	private $error;
	
	/**
     * 商品菜单及其子类
     */
	private $_cacheCats = null;
	
	/**
     * 商品菜单及其父类
     */
	private $_cacheParentCat = null;
	
	/**
     * 商品菜单信息
     */
	private $_cacheCat = null;
	
	/**
     * 商品菜单下拉列表选项
     */
	private $_menuTreeSelectOption = null;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Menu();
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null)
	{
		return $this -> _db -> fetch($where, $fields, $orderBy);
	}
	
	/**
     * 构造菜单树.
     *
     * @param    string   $where
     * @param    array    $deny
     * @param    array    $data
     * @param    int      $parentID
     * @return   array
     */
	public function menuTree($where = null, $deny = null, $data = null, $parentID = 0)
	{
        static $tree, $step;
        if(!$data){
            $data = $this -> _db -> fetch($where);
        }
        foreach($data as $v){
            if($v['parent_id'] == $parentID){
                $step++;
                $tree[$v['menu_id']] = array('menu_id'=>$v['menu_id'],
                                            'menu_title'=>$v['menu_title'],
                                            'parent_id'=>$v['parent_id'],
                                            'is_open'=>$v['is_open'],
                                            'url'=>$v['url'],
                                            'privilege'=>$v['privilege'],
                                            'menu_path'=>$v['menu_path'],
                                            'menu_sort'=>$v['menu_sort'],
                                            'menu_status'=>$v['menu_status'],
                                            'step'=>$step);
                if(is_array($deny)){
                    foreach($deny as $x){
                        if($x == $v['menu_id'] || strstr($v['menu_path'],','.$x.',')){
                            $tree[$v['menu_id']]['deny'] = 1;
                            break;
                        }
                    }
                }
                if($parentID){
                    $tree[$parentID]['leaf'] = 0;
                }
                $this -> menuTree(null,$deny,$data,$v['menu_id']);
                $step--;
            }
        }
        if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
            $tree[$parentID]['leaf'] = 1;
            if ($tree[$parentID]['privilege']){
                $tree[$parentID]['privilege'] = $this -> _db -> getPrivilege("act!='' and privilege_id in(".$tree[$parentID]['privilege'].")");
            }
        }
        return $tree;
	}
	
	/**
     * 获取路径导航
     *
     * @param    string    $where
     * @param    string    $orderBy
     * @return   array
     */
	public function getPath($where, $orderBy = null)
	{
		$datas = $this -> _db -> fetch('display=1 and '.$where, '*', $orderBy);
		foreach ($datas as $data) {
			$path[]= $data['menu_title'];
		}
		$path && $path = implode(' -&gt; ', $path);
		return $path;
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
	    
		if ($data['menu_title'] == '') {
			$this -> error = 'no_name';
			return false;
		}
		if ($id === null) {
		    $result = $this -> _db -> insert($data);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		} else {
			if($data['old_parent_id']!=$data['parent_id']){
				$data['menu_path'] .= $id . ',';
				$this -> _db -> changeMenu((int)$id, $data['old_menu_path'], $data['menu_path']);
			}
			$result = $this -> _db -> update($data, (int)$id);
		}
		return $result;
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
     * 获取状态信息
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $display
     * @return   string
     */
	public function ajaxDisplay($url, $id, $display)
	{
		switch($display){
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0, \'ajax_display\');" title="点击设为隐藏"><u>显示</u></a>';
		   break;
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1, \'ajax_display\');" title="点击设为显示"><u><font color=red>隐藏</font></u></a>';
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
     * 更改显示状态
     *
     * @param    int    $id
     * @param    int    $display
     * @return   void
     */
	public function changeDisplay($id, $display)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateDisplay((int)$id, $display) <= 0) {
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
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'exists'=>'该菜单已存在!',
			         'not_exists'=>'该菜单不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_name'=>'请填写菜单名称!',
			         'no_sn'=>'菜单编码错误!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
	/**
     * 取得商品菜单结构
     *
     * @return   void
     */
    public function cacheCats()
    {
	    $data = $this -> _db-> fetch(null,'menu_id,menu_title,parent_id,menu_path,menu_sort');
        foreach ($data as $key => $row)
        {
    	    $this -> _cacheCats[$row["parent_id"]][$row["menu_id"]] = $row;
		    $this -> _cacheParentCat[$row["menu_id"]][$row["parent_id"]] = $row;
		    $this -> _cacheCat[$row["menu_id"]] = $row;
	    }
    }
    
    /**
     * 取得商品菜单信息
     *
     * @param    string    $catId    菜单ID
     * @return   array
     */
    public function getCacheCat($catId)
    {
	    return $this -> _cacheCat[$catId];
    }
    
    /**
     * 取得商品菜单所有一级子类
     *
     * @param    string    $catId    菜单ID
     * @return   array
     */
    public function getCacheCats($catId)
    {
	    return $this -> _cacheCats[$catId];
    }
    
    /**
     * 取得商品菜单所有一级父类
     *
     * @param    string    $catId    菜单ID
     * @return   array
     */
    public function getCacheParentCat($catId)
    {
	    return $this -> _cacheParentCat[$catId];
    }
    
    /**
     * 取得给定商品菜单的所有子类(包括自身)
     * 
     * @param   string	$catId	菜单ID
     * @return  string	$catId	所有子类,以','分隔
     */
    function getSubCats($catId)
    {
	    $cacheCats = $this -> _cacheCats;
	    if (is_array($cacheCats[$catId])) {
		    foreach($cacheCats[$catId] as $subCatId => $cat)
		    {
			    $catId .= "," . $this -> getSubCats($subCatId);
		    }
	    }
	    return $catId;
    }
    
    /**
     * 取得给定菜单的所有父类(包括自身)
     * 
     * @param   string	$catId	菜单ID
     * @return  string	$catId	所有父类,以','分隔
     */
    function getParentCats($catId)
    {
	    $cacheParentCat = $this -> _cacheParentCat;
	    if (is_array($cacheParentCat[$catId])) {
    	    foreach ($cacheParentCat[$catId] AS $parentCatId => $cat)
    	    {
			    $catId .= "," . $this -> getParentCats($parentCatId);
		    }
	    }
	    return $catId;
    }
    
    /**
     * 创建菜单下拉列表
     *
     * @param    array    $data
     * @return   string
     */
	public function buildSelect($data)
	{
		$this -> cacheCats();
		$this -> menuTreeSelect();
		foreach ($this -> _menuTreeSelectOption as $key => $value)
		{
			$selected = ($data['selected'] == $key) ? "selected" : "";
		    $option[] = "<option value=\"" . $key . "\" " . $selected . ">" . $value . "</option>";	
		}
		$result = "<select name=\"" . $data['name'] . "\" id=\"" . $data['name'] . "\"><option value=\"\">请选择菜单</option>" . implode('', $option) . "</select>";
		return $result;
	}
	
	/**
     * 创建商品菜单复选列表
     *
     * @param    array   $data
     * @return   string
     */
	public function buildCheckbox($data)
	{
		$this -> cacheCats();
		$this -> menuTreeSelect();
		$bgcounter = 0;
		foreach ($this -> _menuTreeSelectOption as $key => $cat)
		{
			if ($bgcounter++%2 == 0) {
            	$bgColor = '#fff';
        	} else {
            	$bgColor = '#eee';
        	}
			is_array($data['value']) && array_key_exists($key, $data['value']) && $checked = "checked";
		    $option[] = "<ul><li style='text-align:left; background-color:" . $bgColor . "'>" . $cat . "</li><li style='text-align:right;background-color:" . $bgColor . "'><input type='checkbox' name='" . $data['name'] . "[" . $key . "]' value='1' " . $checked . " /></li></ul>";
		    unset($checked);
		}
		$result = "<div class='cat_select'>" . implode("\n", $option) . "</div>";
		return $result;
	}
	
	/**
     * 创建商品菜单输入框列表
     *
     * @param    array   $data
     * @return   string
     */
	public function buildText($data)
	{
		$this -> cacheCats();
		$this -> menuTreeSelect();
		$bgcounter = 0;
		foreach ($this -> _menuTreeSelectOption as $key => $cat)
		{
			if ($bgcounter++%2 == 0) {
            	$bgColor = '#fff';
        	} else {
            	$bgColor = '#eee';
        	}
        	is_array($data['value']) && array_key_exists($key, $data['value']) && $text = $data['value'][$key];
		    $option[] = "<ul><li style='text-align:left; background-color:" . $bgColor . "'>" . $cat . "</li><li style='text-align:right;background-color:" . $bgColor . "'><input type='text' size='10' name='" . $data['name'] . "[" . $key . "]' value='" . $text . "' /></li></ul>";
		    unset($text);
		}
		$result = "<div class='cat_select'>" . implode("\n", $option) . "</div>";
		return $result;
	}
	
	/**
     * 初始化商品菜单树
     *
     * @param    int    $startId
     * @return   void
     */
     public function menuTreeSelect($startId = null){
     	$startId = ($startId) ? $startId :'0';
     	$data = $this -> getCacheCats($startId);
	    $num = count($data);
	    $index = 1;
	    
	    foreach ($data as $key => $cat)
	    {
	    	if ($index == $num) {
			    $this -> catExpandBranch($cat["menu_id"], $cat["menu_title"], "0");
	    	} else {
			    $this -> catExpandBranch($cat["menu_id"], $cat["menu_title"], "1");
	    	}
		    $index++;
	    }
    }
    
    /**
     * 生成指定商品菜单的菜单树
     *
     * @param    int    $catId
     * @param    int    $classes
     * @param    int    $tab
     * @param    array  $discount
     * @return   void
     */
    private function catExpandBranch($catId, $catName, $tab)
    {
        $tabs = explode(",", $tab);
        $option_item = "";
	    $i=0;
	    
	    for ($i=0; $i < count($tabs); $i++)
	    {
		    if ($i == count($tabs)-1) {
			    if ($tabs[$i] == '0') {
				    $option_item .= "└";
			    } else {
				    $option_item .= "├";
			    }
		    } else {
			    if ($tabs[$i] == '0') {
				    $option_item .= "　";
			    } else {
				    $option_item .= "│";
			    }
		    }
	    }
	    
	    $option_item .= $catName;
	    $this -> _menuTreeSelectOption[$catId] = $option_item;
	    $data = $this -> getCacheCats($catId);
	    $num = count($data);
	    $index = 1;
	    
	    if ($num > 0) {
	        foreach ($data as $key => $cat)
		    {
			    if ($index == $num) {
				    $this -> catExpandBranch($cat["menu_id"], $cat["menu_title"], $tab.",0");
			    } else {
				    $this -> catExpandBranch($cat["menu_id"], $cat["menu_title"], $tab.",1");
			    }
			    $index++;
		}
	    }
    }
}