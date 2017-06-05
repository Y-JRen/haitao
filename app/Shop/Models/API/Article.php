<?php

 class Shop_Models_API_Article
 {
 	/**
     * 文章 DB
     * 
     * @var Shop_Models_DB_Article
     */
	protected $_db = null;
	
 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
        $this -> _db = new Shop_Models_DB_Article();
		$this -> _auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
    }
    
    /**
     * 根据文章ID获取文章信息
     * 
     * @return void
     */
    public function getArticleById($id)
    {
    	if ($id > 0) {
    		$article = $this -> _db -> getArticle(array('article_id' => (int)($id)));
    		if ($article) {
    			return array_shift($article);
    		}
    	}
    }

	/**
     * 取某分类ID的分类信息
     *
     * @param    int    $catID
     * @return   array
     */
    public function getCatByID($catID,$whereflag=null,$multi=false){
         $datas=$this->_db->getCatByID($catID,$whereflag,$multi);
		 return $datas;
    }





	public function getCat($where){
		return $this->_db->getCat($where);
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
        return $this->_db->get($where,$fields, $orderBy, $page, $pageSize);
	}
	
	public function getMsgcounts(){
		return $this->_db->total;
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
     * 获取多个推荐标签
     *
     * @param    string    $where
     * @return   array
     */

	public function getArticleTag($where)
	{
		$articleData = $this -> _db -> getTag($where);
        $limit = " LIMIT  12 ";
        foreach($articleData as $key=>$var){
            if ($var['config']){
                $ids = $var['config'];
                $result[$var[tag_id]]['details'] = $this -> get(" and a.article_id in($ids)", 'a.article_id,a.title,a.is_view,a.add_time,a.abstract,a.sort,a.img_url,b.cat_id,b.cat_name', null, null, "find_in_set(a.article_id, '$ids') $limit ");
                $result[$var[tag_id]]['totle'] = count($articleData[$key]['details']);
            }
        }
		return $result;
	}

	/**
     * 获取单个标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where)
	{
		$data = array_shift($this -> _db -> getTag($where));
		if ($data['config']){
			$ids = $data['config'];
			$result['details'] = $this -> get(" and a.article_id in($ids)", 'a.article_id,a.title,a.is_view,a.add_time,a.abstract,a.sort,a.img_url,b.cat_id,b.cat_name', null, null, "find_in_set(a.article_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	public function addarticlemsg($datas){
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
        $datas = Custom_Model_Filter::filterArray($datas, $filterChain);

        $verifyCode = $datas['verifyCode'];
        $authImage = new Custom_Model_AuthImage('info');
        if(!$authImage -> checkCode($datas['verifyCode'])){
            return '1';
        }
		if(empty($datas['message'])){
			 return '2';
		}

		$datas['user_name']=isset($this->_auth['user_name'])&&!empty($this->_auth['user_name'])?$this->_auth['user_name']:'anonymous';
		$datas['user_id']=isset($this->_auth['user_id'])&&!empty($this->_auth['user_id'])?intval($this->_auth['user_id']):0;
        if($this->_db->addarticlemsg($datas)){
            return '3';
        }else{
            return '4';
        }
	}

	/**
     * 获取文章评论
     *
     * @param    string    $where
     * @return   array
     */
	public function getMsg($where = null,$field='*',$page,$pagesize){
		return $this->_db->getMsg($where , $field,$page,$pagesize);
	}
	
 }