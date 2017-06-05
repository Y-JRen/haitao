<?php
class Shop_Models_DB_Article
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;

    private $_pageSize = 20;
	
	/**
     * 文章表名
     * 
     * @var    string
     */
	private $_table = 'shop_article';
	
	/**
     * 文章分类表名
     * 
     * @var    string
     */
	private $_tableCat = 'shop_article_cat';
	

	private $_table_msg='shop_msg';
	/**
     * 文章标签表名
     * 
     * @var    string
     */
	private $_tableTag = 'shop_article_tag';
	public $total=0;
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	/**
     * 取得文章信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getArticle($where = null)
	{
		if ($where != null && is_array($where)) {
			$whereSql = " WHERE 1=1";
			
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		$sql = 'SELECT * FROM `' . $this -> _tableCat . '` AS A INNER JOIN `' . $this -> _table . '` as B ON A.cat_id=B.cat_id ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 取某分类ID的分类信息
     *
     * @param    int $catID
     * @return   array
     */
    public function getCatByID($catID){
        return $this->_db->fetchRow('select * from ' .$this -> _tableCat.' where cat_id='.$catID);
    }


    public function getCat($where=null,$limit=10){
			$whereSql=" where 1=1 ";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
					if ($value) {
					   $whereSql .= " AND $key='$value'";
					}
			    }
			}
		$sql="select * from ".$this->_tableCat.$whereSql." limit ".$limit;
		return $this->_db->fetchAll($sql);
			
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
			$whereSql = " WHERE 1=1 and is_view= 1 ";
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
		$sql = 'SELECT '.$fields.' FROM `'.$this -> _table.'` a INNER JOIN `'.$this -> _tableCat.'` b ON a.cat_id=b.cat_id '.$whereSql.' '.$orderBy.' '.$limit;
		return $this->_db->fetchAll($sql);
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
		$countsql = 'SELECT count(*) as count FROM `'.$this -> _table.'` a INNER JOIN `'.$this -> _tableCat.'` b ON a.cat_id=b.cat_id '.$whereSql;
		return $this->_db->fetchOne($countsql);
    }

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where)
	{
		if ($where != null) $where = "WHERE $where";
		$sql = "SELECT * FROM `$this->_tableTag` $where";
		return $this -> _db -> fetchAll($sql);
	}



	public function addarticlemsg($datas){
		$insertRows=array('content'=>$datas['message'],
							'add_time'=>time(),
							'user_id'=>$datas['user_id'],
							'ip'=>Custom_Model_Ip::ip(),
							'type'=>$datas['type'],
							'status'=>0,
							'user_name'=>$datas['user_name'],
							'is_open'=>$datas['is_open'],
							'article_id'=>$datas['aid']);
		return $this->_db->insert($this->_table_msg,$insertRows);
	}

	/**
     * 获取文章评论
     *
     * @param    string    $where
     * @return   array
     */

	public function getMsg($where = null, $filed=' ms.*,m.photo ', $page=1,$pagesize=10){
		if ($where != null) {
			$whereSql = " WHERE 1=1 ";
			if (is_string($where)) {
				$whereSql .= " $where";
			}elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
                    $whereSql .= " AND $key='$value'";
			    }
			}
        }

		$limit=" limit ".($page-1)*$pagesize." , ".$pagesize;
		$sql="select $filed from ".$this->_table_msg."  as ms left join shop_member as m  on  m.user_id=ms.user_id  $whereSql  order by msg_id desc ".$limit;
		$this->total=$this->_db->fetchOne("select count(*) from ".$this->_table_msg."  as ms $whereSql ");
		return $this->_db->fetchAll($sql);
	}

}