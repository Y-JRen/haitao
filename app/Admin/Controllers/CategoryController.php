<?php
class Admin_CategoryController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
	const EXISTS = '该分类已存在';
	const ADD_SUCCESS = '添加分类成功!';
	const EDIT_SUCCESS = '编辑分类成功!';
	
	/**
     * 初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Category();
	}
	/**
     * 分类列表
     *
     * @return   void
     */
    public function indexAction()
    {
    	$datas = $this -> _api -> CatTree();    	
    	if ($datas) {
    		foreach ($datas as $num => $data)
    		{
            	$datas[$num]['display'] = $this -> _api -> ajaxDisplay($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('display'), $datas[$num]['cat_id'], $datas[$num]['display']);
    			$datas[$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $datas[$num]['cat_id'], $datas[$num]['cat_status']);
    		}
    	}
    	$this -> view -> datas = $datas;
    }

    /**
     * 添加产品分类
     *      
     * @return void
     */
    public function   addcatAction()
    {
    	if ($this -> _request -> isPost()) {
    		$result = $this -> _api -> editCat($this -> _request -> getPost());
    		if ($result) {
    			Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
    		}else{
    			Custom_Model_Message::showMessage($this -> _api -> error());
    		}
    	} else {
    		$pid = (int)$this -> _request -> getParam('pid', null);
    		$this -> view -> action = 'addcat';
    		if (!$pid) {
    			$pid = 0;
    			$data['cat_path'] = ',';
                $cat_info   = array_shift($this->_api->getProductCat("parent_id = 0 and cat_sn < 99", "cat_sn", 'cat_sn desc'));
                $data['cat_sn'] = str_pad($cat_info['cat_sn'] + 1, 2, '0', STR_PAD_LEFT);
    		}else{
    			$pcat = array_shift($this -> _api -> get("cat_id=$pid","cat_name,cat_path"));
    			$data['parent_name'] = $pcat['cat_name'];
    			$data['cat_path'] =  $pcat['cat_path'];
                $cat_info   = array_shift($this->_api->getProductCat("parent_id = '{$pid}' and cat_sn < 99", "cat_sn", 'cat_sn desc'));
                $data['cat_sn'] = str_pad($cat_info['cat_sn'] + 1, 2, '0', STR_PAD_LEFT);
    		}
    		$data['parent_id'] = $pid;
    		$this -> view -> data = $data;
    		$this -> render('editcat');
    	}
    }
    
    /**
     * 编辑产品分类
     *
     * @return void
     */
    public function editcatAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
    	if ($id > 0) {
    		if ($this -> _request -> isPost()) {
    			$result = $this -> _api -> editCat($this -> _request -> getPost(), $id);
    			if ($result) {
    				Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl()");
    			}else{
    				Custom_Model_Message::showMessage($this -> _api -> error());
    			}
    		} else {    			 
    			$data = array_shift($this -> _api -> get("cat_id=$id"));
    			if($data['parent_id'] == 0){
    				$data['cat_path'] =  ',';
    			}else{
    				$where = "cat_id=".$data['parent_id'];
    				$pcat = array_shift($this -> _api -> get($where,"cat_name,cat_path"));
    				$data['parent_name'] = $pcat['cat_name'];
    			}
    			$this -> view -> action = 'editcat';
    			$this -> view -> data = $data;
    		}
    	}else{
    		Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
    	}
    }

    /**
     * 生成分类缓存文件
     *
     * @return void
     */     
    public function reflashCacheAction()
    {
        $datas = $this -> _api -> CatTree();  
        $cat_tree = array();
        $freeze = array();
        foreach($datas as $val)
        {
        	$pid = $val['parent_id'];
        	$cid = $val['cat_id'];
        	if($val['display'] == 1){
	        	if($pid == 0){
	        		$cat_tree[$cid]['parent'] = $val;
	        	}else{
	        		$cat_tree[$pid][$cid] = $val;
	        	}
        	}
        	if($val['display'] != 1 && $pid == 0)
        	{
        		$freeze[] = $cid;
        	}
        }
        foreach ($freeze as $val){unset($cat_tree[$val]);}
        foreach ($cat_tree as $val){
        	$html .= '<div class="catalog" >';
        	$html .= '<h2><a href="/gallery-'.$val['parent']['cat_id'].'.html">'.$val['parent']['cat_name'].'</a></h2>';
        	$tmp = array();
        	foreach ($val as $k=>$v){
        		if(is_integer($k)){
        			$tmp[] = $v;
        		}
        	}
        	$n = 1 ;
        	$v1 = $v2 = $v3 = null;
        	while($n < count($tmp)){
        		
        		$v1 = $tmp[$n-1];
        		$v2 = $tmp[$n];
        		$v3 = $tmp[$n+1];
        		if( (mb_strlen($v1['cat_name'],'utf-8') + mb_strlen($v2['cat_name'],'utf-8') + mb_strlen($v3['cat_name'],'utf-8') )< 12){
        			$html .= '<ul class="ul_list_one">';
        			$html .= '<li><a href="/gallery-'.$v1['cat_id'].'.html">'.$v1['cat_name'].'</a></li>';
        			$html .= '<li><a href="/gallery-'.$v2['cat_id'].'.html">'.$v2['cat_name'].'</a></li>';
        			$html .= '<li><a href="/gallery-'.$v3['cat_id'].'.html">'.$v3['cat_name'].'</a></li>';
        			$html .= '</ul>';
        			$n += 3;
        		}else{
        			$html .= '<ul><li><a href="/gallery-'.$v1['cat_id'].'.html">'.$v1['cat_name'].'</a></li></ul>';
        			$html .= '<ul><li><a href="/gallery-'.$v2['cat_id'].'.html">'.$v2['cat_name'].'</a></li></ul>';
        			$n += 2;
        		}
        	}
        	/* foreach ($val as $k=>$v){

        		if(is_integer($k)){
        			$html .= '<li><a href="/gallery-'.$v['cat_id'].'.html">'.$v['cat_name'].'</a></li>';
        		}

        	} */
	        $html .= '</div>';
        }
        $tpl_path = dirname($_SERVER['DOCUMENT_ROOT']).'/app/Shop/Views/scripts/_library/catnav.tpl';
        $objFile = new Custom_Model_File();
        $objFile->writefile($tpl_path, $html);
        Custom_Model_Message::showAlert("更新缓存成功！",true,-1);
    }

    /**
     * 分类品牌
     *
     * @return void
     */
    public function brandAction()
    {
	  if ($this -> _request -> isPost()) {
		$post=$this -> _request -> getPost();

		if(count($post['brand_ids'])>0){
			$cat_band=implode(',',$post['brand_ids']);
			$data=array(
				'cat_band'=>$cat_band,
			);
			$this -> _api -> bandcat($data,$post['cat_id']);
		    Custom_Model_Message::showMessage('编辑成功', 'event', 1250, "Gurl()");
		}
	  }else{
			$cat_id = (int)$this -> _request -> getParam('cat_id', 0);
			if($cat_id > 0){
				$catInfo = array_shift($this -> _api -> get("cat_id=$cat_id"));
				$oldBand=explode(',',$catInfo['cat_band']);
				$this->view->cat = $catInfo;
				$this -> _brand = new Admin_Models_API_Brand();
				$datas = $this -> _brand -> get(null,'brand_id,brand_type,brand_name,status');
				foreach($datas as $key=>$var){
					if(in_array($var['brand_id'],$oldBand)){
						$datas[$key]['is_check']=1;
					}
				}
				$this -> view -> brandDatas = $datas;
				$this -> view -> action = 'brand';
			}else{
				exit('未错误');
			}	  
	  }
    }
    /**
     * 删除动作
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _api -> delete($id);
            if(!$result) {
        	    exit($this -> _api -> error());
            }
        } else {
            exit('error!');
        }
    }

     /**
     * 更改状态动作
     *
     * @return void
     */   
    public function statusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	 
    	if ($id > 0) {
    		$this -> _api -> changeStatus($id, $status);
    	}else{
    		Custom_Model_Message::showMessage('error!');
    	}
    	echo $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
    }

    /**
     * 更改显示状态
     *
     * @return void
     */
    public function displayAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _api -> changeDisplay($id, $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _api -> ajaxDisplay($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('display'), $id, $status);
    }
    /**
     * ajax更新数据
     *
     * @return void
     */
    public function ajaxupdateAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, $field, $val);
        } else {
            exit('error!');
        }
    }
    /**
     * 检查重复值
     *
     * @return void
     */
    public function checkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        
        if(!empty($val)){
	        $result = $this -> _api -> get("$field='$val'",$field);
	        if (!empty($result)){
	        	exit(self::EXISTS);
	        }
        }
    }
    /**
     * 获取下级分类列表
     *
     * @return void
     */
    public function getcatAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $pid = (int)$this -> _request -> getParam('pid', null);
        $this -> view -> cat_id = $id;
        $this -> view -> pid = $pid;
        $this -> view -> cats = $this -> _api -> get("parent_id=$pid");
    }
    
    /**
     * 选择产品属性
     *
     * @return void
     */  
    public function selAttrAction()
    {
        $catID = $this->_request->getParam('cat_id');
        if ($this -> _request -> isPost()) {
            $post = $this -> _request -> getPost();
            if ($this -> _api -> updateAttr($post)) {
                Custom_Model_Message::showMessage('更新成功');
            }
            else {
                Custom_Model_Message::showMessage('更新失败');
            }
        }
        $category = array_shift($this -> _api -> get("cat_id = '{$catID}'"));
        $attributeAPI = new Admin_Models_API_Attribute();
        $datas = $attributeAPI -> get("parent_id = 0");
        foreach ($datas as $data) {
            $tempData = $attributeAPI -> get("parent_id = {$data['attr_id']}");
            if (!$tempData) continue;
            
            $attrData[$data['attr_id']]['name'] = $data['attr_title'];
            foreach ($tempData as $temp) {
                $attrData[$data['attr_id']]['detail'][] = $temp;
            }
        }
        
        $catAttrData = $this -> _api -> getAttr("cat_id = '{$catID}'");
        if ($catAttrData) {
            foreach ($catAttrData as $data) {
                $catAttrInfo[$data['attr_id']] = 1;
                
                $subData = explode(',', $data['attrs']);
                foreach ($subData as $subAttrID) {
                    $subAttrData[$data['attr_id']][$subAttrID] = 1;
                }
            }
        }
        $this -> view -> category = $category;
        $this -> view -> attrData = $attrData;
        $this -> view -> subAttrData = $subAttrData;
        $this -> view -> catAttrInfo = $catAttrInfo;
    }

    /**
     * ajax获取自增品类编码
     *
     **/
    public function getAjaxCatsnAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$parent_id = $this->_request->getParam('parent_id', 0);

        $cat_info   = array_shift($this->_api->getProductCat("parent_id = '{$parent_id}' and cat_sn < 99", "cat_sn", 'cat_sn desc'));
        $data['cat_sn'] = str_pad($cat_info['cat_sn'] + 1, 2, '0', STR_PAD_LEFT);
		exit(json_encode(array('success' => 'true', 'data' => $data)));
	}
}