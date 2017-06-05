<?php

class Admin_Models_DB_Article
{
	private $_db = null;

	private $_table = array(
                           'cat'=>'shop_article_cat',
                           'article'=>'shop_article',
                           'tag'=>'shop_article_tag'
    );
	public function __construct()
	{
		$this->_db = Zend_Registry::get('db');
        $this->_pageSize = Zend_Registry::get('config')->view->page_size;
    }

	/**
     * 取所有文章分类
     *
     * @return   array
     */
    public function getAllCat()
	{
        return $this->_db->fetchAll('select * from ' .$this->_table['cat'].' order by parent_id,sort');
	}

	/**
     * 添加文章分类
     *
     * @param    array $data
     * @return   int
     */
    public function addCat($data)
    {
        if($data['parent_id']){
            $parentCat = $this->getCatByID($data['parent_id']);
            $parentArea['cat_path'] && $tmp = explode(',',substr($parentCat['cat_path'],1,-1));
            $tmp[] = $parentCat['cat_id'];
            $data['cat_path'] = ','.implode(',', $tmp).',';
        }

        $this->_db->insert($this->_table['cat'], $data);
		return $this->_db->lastInsertId();
    }
	/**
     * 编辑文章分类
     *
     * @param    string $where
     * @return   int
     */
    public function editCat($data){
        $catID = $data['cat_id'];
        unset($data['cat_id']);
        $where = $this->_db->quoteInto('cat_id = ?', $catID);
		if ($catID > 0) {
		    return $this->_db->update($this->_table['cat'], $data, $where);
		}
    }
	/**
     * 取某ID的文章分类
     *
     * @param    int $catID
     * @return   array
     */
    public function getCatByID($catID){
        return $this->_db->fetchRow('select * from ' .$this->_table['cat'].' where cat_id='.$catID);
    }
	/**
     * 取父节点下的所有文章分类
     *
     * @param    string $catPath
     * @return   array
     */
    public function getChildCats($catPath){
        return $this->_db->fetchAll('select * from '.$this->_table['cat'].' where cat_path like"%'.$catPath.'%"');
    }
	/**
     * 统计某ID文章分类下的文章数量
     *
     * @param    int $catID
     * @return   int
     */
    public function countArticleByCatID($catID){
        return $this->_db->fetchOne('select count(*) from '.$this->_table['article'].' where cat_id='.$catID);
    }
	/**
     * 删除文章分类
     *
     * @param    int $catID
     * @return   int
     */

    public function delCat($catID){
		$where = $this->_db->quoteInto('cat_id = ?', $catID);
		if ($catID > 0) {
		    return $this->_db->delete($this->_table['cat'], $where);
		}
    }
	/**
     * 编辑文章分类列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxupdatecatAction($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('cat_id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table['cat'], $set, $where);
		}
	}
	/**
     * 添加文章
     *
     * @param    array $data
     * @return   int
     */
     public function add($data){
        $this->_db->insert($this->_table['article'], $data);
		return $this->_db->lastInsertId();
    }
	/**
     * 取文章数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getCount($where=null){
		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'title') {
                            $whereSql .= " AND $key like '%$value%'";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		$sql = 'SELECT count(*) as count FROM `'.$this->_table['article'].'` a INNER JOIN `'.$this->_table['cat'].'` b ON a.cat_id=b.cat_id '.$whereSql;
		return $this->_db->fetchOne($sql);
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
	public function get($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = ' LIMIT '.$pageSize.' OFFSET '.$offset;
		}

		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'title') {
                            $whereSql .= " AND $key like '%$value%'";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY article_id DESC";
		}
		$sql = 'SELECT '.$fields.' FROM `'.$this->_table['article'].'` a INNER JOIN `'.$this->_table['cat'].'` b ON a.cat_id=b.cat_id '.$whereSql.' '.$orderBy.' '.$limit;
		
		return $this->_db->fetchAll($sql);
	}
	/**
     * 编辑文章列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxupdatearticle($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('article_id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table['article'], $set, $where);
		}
	}
	/**
     * 删除文章
     *
     * @param    int $articleID
     * @return   int
     */

    public function del($articleID){
		$where = $this->_db->quoteInto('article_id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->delete($this->_table['article'], $where);
		}
    }
	/**
     * 取某ID的文章
     *
     * @param    int $articleID
     * @return   array
     */
    public function getArticleByID($articleID){
        return $this->_db->fetchRow('select * from ' .$this->_table['article'].' where article_id='.$articleID);
    }
	/**
     * 编辑文章
     *
     * @param    string $where
     * @return   int
     */
    public function edit($data){
        $articleID = $data['article_id'];
        unset($data['article_id']);
        $where = $this->_db->quoteInto('article_id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->update($this->_table['article'], $data, $where);
		}
    }

	/**
     * 添加新标签
     *
     * @param    string    $where
     * @return   array
     */
	public function addTag($data)
	{
			$row = array (
						  'tag' => $data['tag'],
						  'title' => $data['title']
						  );
			$this -> _db -> insert($this->_table['tag'], $row);
			$lastInsertId = $this -> _db -> lastInsertId();
			return $lastInsertId;
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllTag($where = null,$page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) $where = " WHERE 1 $where";
        return array(
            'list'=>$this -> _db -> fetchAll('SELECT * FROM '.$this->_table['tag']. "$where order by tag_id $limit" ),
            'total'=> $this -> _db -> fetchOne('SELECT count(*) as count FROM '.$this->_table['tag'].$where )
            );
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where = null)
	{
		if ($where != null) $where = " WHERE $where";
		$sql = "SELECT * FROM ".$this->_table['tag'] . $where." order by tag_id ";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 更新标签
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updateTag($tag_id, $val)
	{
		if ($tag_id > 0) {
			$set = array ('config' => $val);
			$where = $this -> _db -> quoteInto('tag_id = ?', $tag_id);
		    $this -> _db -> update($this->_table['tag'], $set, $where);
		    return true;
		}
	}

	/**
     * 编辑文章分类列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxupdatetag($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('tag_id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table['tag'], $set, $where);
		}
	}

	/**
     * 更新关联商品
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updatelinkGoods($article_id, $val)
	{
		if ($article_id > 0) {
			$set = array ('goods_ids' => $val);
			$where = $this -> _db -> quoteInto('article_id = ?', $article_id);
		    $this -> _db -> update($this->_table['article'], $set, $where);
		    return true;
		}
	}




	/**
     * 更新关联商品
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updateLinkCat($cat_id, $val,$field ='goods_config')
	{
		if ($cat_id > 0) {
			$set = array ($field => $val);
			$where = $this -> _db -> quoteInto('cat_id = ?', $cat_id);
		    $this -> _db -> update($this->_table['cat'], $set, $where);
		    return true;
		}
	}








}