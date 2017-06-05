<?php
class Admin_ArticleController extends Zend_Controller_Action
{
	const NO_NAME = '请填写名称!';
	const ADD_SUCCESS = '添加成功!';
    const ADD_FAIL = '添加失败！';
    const EDIT_SUCCESS = '修改成功！';
    const EDIT_FAIL = '修改失败！';
    const DEL_SUCCESS = '删除成功！';
    const DEL_FAIL = '删除失败！';
    const NO_TITLE = '请填写文章标题';
    const NO_CONTENT = '请填写文章内容';
    const NO_CAT = '请选择分类';
    const TAG_SUCCESS = '标签编辑成功!';
    const IMG_FAIL = '上传图片失败功!';

    public function init()
	{
        $this->_api = new Admin_Models_API_Article();
	}
	
    /**
     * 添加分类表单
     *
     * @return void
     */
    public function addcatformAction(){
        $this->view->catTree=$this->_api->catTree();
        $this->view->catID=$this->_request->getParam('id', null);
        $this->view->action='addcat';
    }
    
    
    /**
     * 添加分类表单
     *      
     * @return   void
     */
    public function addcatAction(){

        $post = $this->_request->getPost();
        if($this->_api->addCat($post['cat'],$error)){
            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/article/listcat/');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_NAME);
                    break;
                case 'add_fail':
                    Custom_Model_Message::showMessage(self::ADD_FAIL);
                    break;
            }
        }
    }
    
    /**
     * 分类列表
     *
     * @return void
     */
    public function listcatAction(){
        $this->view->catTree=$this->_api->catTree();
    }
    /**
     * 更新 分类列表 中可修改的字段
     *
     * @return void
     */
    public function ajaxupdatecatAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $id = (int)$this->_request->getParam('id', 0);
        $field = $this->_request->getParam('field', null);
        $val = $this->_request->getParam('val', null);
        if ($id > 0) {
            $result = $this->_api->ajaxupdatecatAction($id, $field, $val);
            switch ($result) {
            	case 'forbidden':
            	    Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    /**
     * 修改分类表单
     *
     * @return void
     */
    public function editcatformAction(){
        $id=$this->_request->getParam('id', null);
        $this->view->catTree=$this->_api->catTree(array($id));
        $this->view->cat=$this->_api->getCatByID($id);
        $this->view->id=$id;
        $this->view->action='editcat';
    }
    /**
     * 修改分类
     *
     * @return void
     */
    public function editcatAction(){
        $post = $this->_request->getPost();
        if($this->_api->editCat($post['cat'],$error)){
            Custom_Model_Message::showMessage(self::EDIT_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/article/listcat');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_NAME);
                    break;
                case 'editFail':
                    Custom_Model_Message::showMessage(self::EDIT_FAIL);
                    break;
            }

        }
    }
    /**
     * 删除分类
     *
     * @return void
     */
    public function delcatAction(){
        $catID=$this->_request->getParam('id', null);
        if($this->_api->delCat($catID,$error)){
            exit;
        }else{
            switch ($error) {
                case 'delFail':
                    exit(self::DEL_FAIL);
                    break;
            }

        }
    }
    /**
     * 添加文章表单
     *
     * @return void
     */
    public function addformAction(){
        $this->view->catTree=$this->_api->catTree();
        $this->view->action='add';
    }
    /**
     * 添加文章
     *
     * @return void
     */
    public function addAction(){
        $post = $this->_request->getPost();
        $post['article']['add_time'] = time();
        $post['article']['content'] = $this->_request->getParam('content', null);
        if($this->_api->add($post['article'],$error)){
            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/article/list/');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_TITLE);
                    break;
                case 'noContent':
                    Custom_Model_Message::showMessage(self::NO_CONTENT);
                    break;
                case 'noCatID':
                    Custom_Model_Message::showMessage(self::NO_CAT);
                    break;
                case 'addFail':
                    Custom_Model_Message::showMessage(self::ADD_FAIL);
                    break;
                case 'imgErr':
                    Custom_Model_Message::showMessage(self::IMG_FAIL);
                    break;
            }

        }
    }
    /**
     * 文章列表
     *
     * @return void
     */
     public function listAction(){
        $search = $this->_request->getParams();
        $this -> view -> param = $search;

        if(isset($search['is_view']) && $search['is_view']!='null'){
         $tmp['is_view'] = $search['is_view'];
        }
        if(isset($search['is_hot']) && $search['is_hot']!='null'){
         $tmp['is_hot'] = $search['is_hot'];
        }
        if(isset($search['title']) && $search['title']!='null'){
         $tmp['title'] = $search['title'];
        }
        if(isset($search['cat_id']) && $search['cat_id']!='null'){
         $tmp['b.cat_id'] = $search['cat_id'];
        }

        $total = $this->_api->getCount($tmp);
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->get($tmp,'article_id,cat_name,title,author,source,is_view,add_time,is_hot,a.sort',null,$page);
        $this->view->catTree=$this->_api->catTree();
        $this->view->data = $data;
        $pageNav = new Custom_Model_PageNav($total);
        $this->view->pageNav=$pageNav->getNavigation();
     }
    public function footerAction()
    {
   		
   		$catTree=$this->_api->catTree();
   		$articleTree = array();
   		$ids = array();
   		foreach ($catTree as $val){
   			if($val['parent_id'] == 1){
   				$articleTree[$val['cat_id']]['name'] = $val['cat_name'];
   				$ids[$val['cat_id']] = $val['cat_id'];
   			}
   		}
   		$ids = join(',', $ids);
   		$data = $this->_api->get('and is_view = 1 and a.cat_id IN('.$ids.')', 'article_id,title,cat_name,a.cat_id');
   		foreach ($data as $key=>$v)
   		{	
   			if(38 != $v['cat_id'])
   				$articleTree[$v['cat_id']][$v['article_id']] = $v;
   		}
   		$html = '';
   		foreach ($articleTree as $key=>$val){
   			$html .= '<dl><dt>'.$val['name'].'</dt>';
	   		foreach ($val as $v){
	   			if(is_array($v))
	   				$html .= '<dd><a href="/help/detail-'.$v['article_id'].'.html">'.$v['title'].'</a></dd>';
	   		}
	   		$html .= '</dl>';
   		}
        $tpl_path =  dirname($_SERVER['DOCUMENT_ROOT']).'/app/shop/views/scripts/_library/footer_nav.tpl';
   	    $objFile = new Custom_Model_File();
        $objFile->writefile($tpl_path, $html);
        Custom_Model_Message::showAlert("更新缓存成功！",true,-1);
    } 
    
    /**
     * 更新 文章列表 中可修改的字段
     *
     * @return void
     */
    public function ajaxupdatearticleAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $id = (int)$this->_request->getParam('id', 0);
        $field = $this->_request->getParam('field', null);
        $val = $this->_request->getParam('val', null);
        if ($id > 0) {
            $result = $this->_api->ajaxupdatearticle($id, $field, $val);
            switch ($result) {
            	case 'forbidden':
            	    Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    /**
     * 删除分类
     *
     * @return void
     */
    public function delAction(){
        $articleID=$this->_request->getParam('id', null);
        if($this->_api->del($articleID,$error)){
            //exit(self::DEL_SUCCESS);
            exit;
        }else{
            switch ($error) {
                case 'delFail':
                    exit(self::DEL_FAIL);
                    break;
            }

        }
    }
    /**
     * 修改文章表单
     *
     * @return void
     */
    public function editformAction(){
        $id=$this->_request->getParam('id', null);
        $this->view->catTree=$this->_api->catTree();
        $this->view->article=$this->_api->getArticleByID($id);
        $this->view->action='edit';
    }



    /**
     * 文章标签列表
     *
     * @return void
     */
    public function taglistAction(){
      $page = (int)$this -> _request -> getParam('page', 1);
      $search = $this -> _request -> getParams();
      $datas= $this -> _api -> getAllTag($search,$page, 15);
      $this -> view -> taglist =  $datas['list'];
	  $this -> view -> param = $this -> _request -> getParams();
      $pageNav = new Custom_Model_PageNav($datas['total'], 15, 'ajax_search');
      $this -> view -> pageNav = $pageNav -> getNavigation();

    }

	/**
	*添加新标签
	*
	*/
	public function addTagAction(){
		if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $title = $this -> _request -> getParam('title', null);
            $tag = $this -> _request -> getParam('tag', null);
            if($title && $tag){
                $chinput = $this -> _api -> checkinput(" title='$title' or tag = '$tag'");
                if($chinput)  {
                        Custom_Model_Message::showMessage('填写有重复！' , 'event', 1250);
                }
            } else{
               Custom_Model_Message::showMessage('信息填写不完整！', 'event', 1250);
            }
            if($this -> _api -> addTag($this -> _request -> getPost())){
                Custom_Model_Message::showMessage('标签添加成功！', 'event', 1250, 'Gurl()');
            } else {
                Custom_Model_Message::showMessage($this -> _api -> error());
            }
        }else{
             $this -> view -> tagtype = $this->_tagType;
             $this -> view -> action = 'add-tag';
             $this -> render('edit-tag');
        }
	}

    /**
     * 单项标签管理
     *
     * @return void
     */
    public function tagAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> updateTag($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250, 'Gurl()' );
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getTag("tag_id=$id");
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

    /**
     * 更新 分类列表 中可修改的字段
     *
     * @return void
     */
    public function ajaxupdatetagAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $id = (int)$this->_request->getParam('id', 0);
        $field = $this->_request->getParam('field', null);
        $val = $this->_request->getParam('val', null);
        if ($id > 0) {
            $result = $this->_api->ajaxupdatetag($id, $field, $val);
            switch ($result) {
            	case 'forbidden':
            	    Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

   /**
     * 选择商品
     *
     * @return void
     */
    public function selAction()
    {
        $job = $this -> _request -> getParam('job', null);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        if($job) {
	        $search['filter'] = "";
	        $data = $this->_api->get(array('b.cat_id' => $search['cat_id'], 'title' => $search['title']),'article_id,cat_name,title,author,source,is_view,add_time,a.sort',null,$page);
			foreach($data as $var){
				$articleId[]=$var['article_id'];
			}
            $num=count($articleId);
	        $total = $this->_api->getCount(array('b.cat_id' => $search['cat_id'], 'title' => $search['title']));
	        $this -> view -> datas = $data;

        }else{
	        $this->view->catTree=$this->_api->catTree();
        }
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search_goods');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }


    /**
     * 修改文章
     *
     * @return void
     */
    public function editAction(){
        $post = $this->_request->getPost();
        $post['article']['content'] = $this->_request->getParam('content', null);
        if($this->_api->edit($post['article'],$error)){
            Custom_Model_Message::showMessage(self::EDIT_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/article/list');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_TITLE);
                    break;
                case 'noContent':
                    Custom_Model_Message::showMessage(self::NO_CONTENT);
                    break;
                case 'noCatID':
                    Custom_Model_Message::showMessage(self::NO_CAT);
                    break;
                case 'editFail':
                    Custom_Model_Message::showMessage(self::EDIT_FAIL);
                    break;
            }

        }
    }

    /**
     * 关联商品
     *
     * @return void
     */
    public function linkgoodsAction(){
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {

            if ($this -> _request -> isPost()) {
            	Custom_Model_Message::showMessage('error!', 'event', 1250);
                $result = $this -> _api -> editlinkGoods($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getArticleGoods($id);
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250);
        }
    }

    /**
     * 分类关联商品
     *
     * @return void
     */
    public function linkCatGoodsAction(){
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editlinkCatGoods($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getLinkCatGoods($id);
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250);
        }
    }

    /**
     * 分类关联文章
     *
     * @return void
     */
    public function linkCatArticleAction(){
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editlinkCatArticle($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getLinkCatArticle($id);
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250);
        }
    }

    /**
     * ajax 文章是否推荐
     *
     */
    public function isHotAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$article_id = (int)$this -> _request -> getParam('article_id', 0);
    	$st = (int)$this -> _request -> getParam('st', 0);
    	if($article_id < 0){ exit('参数错误'); }
    	if($st!=0 && $st!=1){ $st = 0; }
    	$rs = $this -> _api -> editArticle(array('article_id'=>$article_id, 'is_hot'=>$st));
    	echo $rs;
    	exit;
    }
}