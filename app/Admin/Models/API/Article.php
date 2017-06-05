<?php
class Admin_Models_API_Article
{
	private $upPath = 'upload/article/';

	public function __construct()
	{
		$this->_db = new Admin_Models_DB_Article();
	}
	/**
     * 构造分类树.
     *
     * @param    array    $deny
     * @param    array    $data
     * @param    int      $parentID
     * @return   array
     */
	public function catTree($deny=null,$data=null,$parentID=0)
	{
        static $tree,$step;
        if(!$data){
            $data = $this->_db->getAllCat();
        }
        foreach($data as $v){
            if($v['parent_id'] == $parentID){
                $step++;
                $tree[$v['cat_id']] = array('cat_id'=>$v['cat_id'],
                                            'cat_name'=>$v['cat_name'],
                                            'parent_id'=>$v['parent_id'],
                                            'cat_path'=>$v['cat_path'],
                                            'sort'=>$v['sort'],
                                            'step'=>$step);
                if(is_array($deny)){
                    foreach($deny as $x){
                        if($x == $v['cat_id'] || strstr($v['cat_path'],','.$x.',')){
                            $tree[$v['cat_id']]['deny'] = 1;
                            break;
                        }
                    }
                }
                if($parentID){
                    $tree[$parentID]['leaf'] = 0;
                }
                $this->catTree($deny,$data,$v['cat_id']);
                $step--;
            }
        }
        if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
            $tree[$parentID]['leaf'] = 1;
            $tree[$parentID]['num'] = $this->_db->countArticleByCatID($parentID);
        }
        return $tree;
	}
	/**
     * 取某ID的文章分类信息
     *
     * @param    int    $catID
     * @return   array
     */
    public function getCatByID($catID){
        return $this->_db->getCatByID($catID);
    }
	/**
     * 添加文章分类信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function addCat($data, &$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['cat_name'])){
            $error = 'noName';
            return false;
        }

        if($this->_db->addCat($data)){
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }
	/**
     * 编辑文章分类信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function editCat($data,&$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['cat_name'])){
            $error = 'noName';
            return false;
        }
        $data['parent_id'] = intval($data['parent_id']);
        $cat = $this->_db->getCatByID($data['cat_id']);//当前目录
        if($cat['parent_id'] != $data['parent_id']){
            $newParentCat = $this->_db->getCatByID($data['parent_id']);//新父目录
            // 1.设置当前目录的 cat_path
            // 2.设置当前目录的 子目录的 cat_path
            if($newParentCat['cat_id']){
                $newParentCat['cat_path'] && $temp = explode(',', substr($newParentCat['cat_path'],1,-1));
                $temp[] = $newParentCat['cat_id'];
                $data['cat_path'] = ','.implode(',',$temp).',';
            }else{
                $data['cat_path'] = '';
            }
            $temp[] = $cat['cat_id'];
            $path_1 = ','.implode(',',$temp).',';

            $cat['cat_path'] && $tmp = explode(',', substr($cat['cat_path'],1,-1));
            $tmp[] = $cat['cat_id'];
            $path_2 = ','.implode(',',$tmp).',';
            $childCats = $this->_db->getChildCats($path_2);//当前目录的 子目录
            if(is_array($childCats)){
                $replaceFrom = $path_2;
                $replaceTo = $path_1;
                foreach($childCats as $v){
                    $v['cat_path']=str_replace($replaceFrom,$replaceTo,$v['cat_path']);
                    $this->_db->editCat($v);
                }
            }

        }
        if($ct=$this->_db->editCat($data) || $ct==0){
            return true;
        }else{
            $error = 'editFail';
            return false;
        }
    }
	/**
     * 删除文章分类信息
     *
     * @param    int       $catID
     * @param    string    $error
     * @return   bool
     */
    public function delCat($catID,&$error){
        $catID = intval($catID);
        $cat = $this->_db->getCatByID($catID);
        $cat['cat_path'] && $tmp = explode(',', substr($cat['cat_path'],1,-1));
        $tmp[] = $catID;
        $path = ','.implode(',',$tmp).',';
        $childCats = $this->_db->getChildCats($path);
        if(is_array($childCats)){
             foreach($childCats as $v){
                $v['cat_path']=str_replace(','.$catID.',',',',$v['cat_path']);
                $v['cat_path'] = $v['cat_path']==','?'':$v['cat_path'];
                if($v['parent_id'] == $cat['cat_id']){
                    $v['parent_id'] = $cat['parent_id'];
                }
                $this->_db->editCat($v);
             }
        }
        if($this->_db->delCat($catID)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }

	/**
     * 编辑分类列表可以编辑项目
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   string
     */
	public function ajaxupdatecatAction($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());

		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
		    $result = $this->_db->ajaxupdatecatAction((int)$id, $field, $val);
		    if (is_numeric($result) && $result > 0) {
		        return 'ajaxUpdateSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	/**
     * 添加文章
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function add($data, &$error){

	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);

        if(is_null($data['title'])){
            $error = 'noName';
            return false;
        }elseif(is_null($data['content'])){
            $error = 'noContent';
            return false;
        }elseif(is_null($data['cat_id'])){
            $error = 'noCatID';
            return false;
        }
        $data['content'] = stripslashes($data['content']);
        if($insertID = $this->_db->add($data)){
        	//上传图片
        	if(is_file($_FILES['img_url']['tmp_name'])) {
				$upload = new Custom_Model_Upload('img_url', $this -> upPath);
				$upload -> up(false);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upPath.$upload->uploadedfiles[0]['filepath'];
				$this -> _db -> edit(array('img_url'=>$img_url, 'article_id'=>$insertID));
	    	}
	    	//
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }
	/**
     * 取文章数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getCount($where=null){
        return $this->_db->getCount($where);
    }
	/**
     * 取文章列表
     *
     * @param    string $where
     * @param    string $fields
     * @param    string $orderBy
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		$datas= $this->_db->get($where, $fields, $orderBy, $page, $pageSize);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
	}
	/**
     * 编辑文章列表可以编辑项目
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   string
     */
	public function ajaxupdatearticle($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());

		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
		    $result = $this->_db->ajaxupdatearticle((int)$id, $field, $val);
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
     * @param    int       $articleID
     * @param    string    $error
     * @return   bool
     */
    public function del($articleID,&$error){
        if($this->_db->del($articleID)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }
	/**
     * 取某ID的文章信息
     *
     * @param    int    $articleID
     * @return   array
     */
    public function getArticleByID($articleID){
        $article =  $this->_db->getArticleByID($articleID);
	    $article['content']	= stripslashes($article['content']);
		return	$article;
    }
	/**
     * 编辑文章信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function edit($data,&$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['title'])){
            $error = 'noName';
            return false;
        }elseif(is_null($data['content'])){
            $error = 'noContent';
            return false;
        }elseif(is_null($data['cat_id'])){
            $error = 'noCatID';
            return false;
        }
        $data['content'] = stripslashes($data['content']);
        if($ct=$this->_db->edit($data) || $ct==0){
        	//上传图片
        	if(is_file($_FILES['img_url']['tmp_name'])) {
				$upload = new Custom_Model_Upload('img_url', $this -> upPath);
				$upload -> up(false);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upPath.$upload->uploadedfiles[0]['filepath'];
				$this -> _db -> edit(array('img_url'=>$img_url, 'article_id'=>$data['article_id']));
	    	}
            return true;
        }else{
            $error = 'editFail';
            return false;
        }
    }

	/**
     * 获取标签列表
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllTag($where = null,$page=null, $pageSize = null)
	{
		if ($where['tag']) {
		    $wheresql .= " and  tag like '%{$where['tag']}%'";
		}
		if ($where['title']) {
		    $wheresql .= " and title like '%{$where['title']}%'";
		}
		return $this -> _db -> getAllTag($wheresql,$page, $pageSize);
	}

	/**
     * 添加新标签
     *
     * @param    string    $where
     * @return   array
     */
	public function addTag($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());

        $data = Custom_Model_Filter::filterArray($data, $filterChain);
		return $this -> _db -> addTag($data);
	}

	/**
     * 添加标签关联文章
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function updateTag($data, $tag_id)
	{

		if($tag_id > 0){
			if (is_array($data['article_id']) && count($data['article_id']) > 0){
				$val = implode(',', $data['article_id']);
			    $where = "article_id in(".implode(',', $data['article_id']).")";
			}else{
				$val = '';
				$where = "article_id>0";
			}
			$this -> _db -> updateTag($tag_id, $val);
			return true;
		}
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where)
	{
		$data = array_shift($this -> _db -> getTag($where));
		if ($data['config']){
			$ids = $data['config'];
			$result['details'] = $this -> get(" and a.article_id in($ids)", 'article_id,cat_name,title,author,source,is_view,add_time,a.sort', null, null, "find_in_set(a.article_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	/**
     * 检查输入
     *
     * @param    string    $where
     * @return   array
     */
	public function checkinput($where)
	{
		$data = array_shift($this -> _db -> getTag($where));
		return $data;
	}

	/**
     * 编辑分类列表可以编辑项目
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   string
     */
	public function ajaxupdatetag($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());

		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
		    $result = $this->_db->ajaxupdatetag((int)$id, $field, $val);
		    if (is_numeric($result) && $result > 0) {
		        return 'ajaxUpdateSucess';
		    } else {
			    return 'error';
		    }
		}
	}

	/**
     * 获取文章关联商品
     *
     * @param    string    $where
     * @return   array
     */
	public function getArticleGoods($articleID)
	{
		$data = $this->_db->getArticleByID($articleID);
		//echo("<pre>");
		//print_r($data);
		//echo("</pre>");
		if ($data['goods_ids']){
			$ids = $data['goods_ids'];
            $goods_api = new Admin_Models_API_Goods();
			$result['details'] = $goods_api -> get("a.goods_id in($ids)", 'a.goods_id,a.onsale,a.cat_id,b.cat_path,goods_name,goods_img,goods_sn,market_price,price', null, null, "find_in_set(a.goods_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	/**
     * 获取分类关联商品
     *
     * @param    string    $where
     * @return   array
     */
	public function getLinkCatGoods($cat_id)
	{
		$data = $this->_db->getCatByID($cat_id);
		if ($data['goods_config']){
			$ids = $data['goods_config'];
            $goods_api = new Admin_Models_API_Goods();
			$result['details'] = $goods_api -> get("a.goods_id in($ids)", 'a.goods_id,a.onsale,a.cat_id,b.cat_path,goods_name,goods_img,goods_sn,market_price,price', null, null, "find_in_set(a.goods_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	/**
     * 获取分类关联文章
     *
     * @param    string    $where
     * @return   array
     */
	public function getLinkCatArticle($cat_id)
	{
		$data = $this->_db->getCatByID($cat_id);
		if ($data['article_config']){
			$ids = $data['article_config'];
            $result['details'] = $this -> get(" and a.article_id in($ids)", 'article_id,cat_name,title,author,source,is_view,add_time,a.sort', null, null, "find_in_set(a.article_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	/**
     * 编辑关联商品
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function editlinkGoods($data, $article_id)
	{
		if($article_id > 0){
			if (is_array($data['goods_id']) && count($data['goods_id']) > 0){
				$val = implode(',', $data['goods_id']);
			    $where = "goods_id in(".implode(',', $data['goods_id']).")";
			}else{
				$val = '';
				$where = "goods_id>0";
			}
			$this -> _db -> updatelinkGoods($article_id, $val);
			return true;
		}
	}

	/**
     * 编辑分类关联商品
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function editlinkCatGoods($data, $cat_id)
	{
		if($cat_id > 0){
			if (is_array($data['goods_id']) && count($data['goods_id']) > 0){
				$val = implode(',', $data['goods_id']);
			    $where = "goods_id in(".implode(',', $data['goods_id']).")";
			}else{
				$val = '';
				$where = "goods_id>0";
			}
			$this -> _db -> updateLinkCat($cat_id, $val,'goods_config');
			return true;
		}
	}

	/**
     * 编辑分类关联文章
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function editlinkCatArticle($data, $cat_id)
	{
		if($cat_id > 0){
			if (is_array($data['article_id']) && count($data['article_id']) > 0){
				$val = implode(',', $data['article_id']);
			    $where = "article_id in(".implode(',', $data['article_id']).")";
			}else{
				$val = '';
				$where = "article_id>0";
			}
			$this -> _db -> updateLinkCat($cat_id, $val,'article_config');
			return true;
		}
	}

	/**
	 *
	 *
	 */
	public function editArticle($data) {
		if($this -> _db -> edit($data)){
			return 'ok';
		}else{
			return 'err';
		}
	}
}
