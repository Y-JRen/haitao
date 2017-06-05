<?php
class Admin_Models_DB_Msg
{


    /**
     * Zend_Db
     * @var    Zend_Db
     */
    private $_db = null;

    /**
     * page size
     * @var    int
     */
    private $_pageSize = 50;

    private $_table_msg = 'shop_msg';
    private $_table_goods_msg = 'shop_goods_msg';
    private $_table_order_msg = 'shop_order_msg';
    private $_table_complaint = 'shop_complaint';

    /**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
    public function __construct(){
        $this->_db = Zend_Registry::get('db');
        $this->_pageSize = Zend_Registry::get('config')->view->page_size;
    }
    /**
     * 获取站点留言条数
     *
     * @param    string    $where
     * @return   void
     */

    public function getCountSite($where=null){
        $where && $where = " where 1=1 and ".$where;
        return $this->_db->fetchone('select count(*) as count from `'.$this->_table_msg.'` '.$where);
    }

    /**
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */

    public function getSite($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null){
        $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;
        if($page){
            $offset = ($page-1)*$pageSize;
            $limit = ' limit '.$pageSize.' offset '.$offset;
        }
        $where && $where = ' where 1=1 and '.$where;
        if($orderBy) $orderBy = ' order by '.$orderBy;
        else $orderBy = ' order by add_time desc';
        $sql = 'select '.$fields.' from `'.$this->_table_msg.'` '.$where.' '.$orderBy.' '.$limit;
        return $this->_db->fetchAll($sql);
    }
    /**
     * 获取商品留言条数
     *
     * @param    string    $where
     * @return   void
     */
    public function getCountGoods($where=null){
        $where && $where = " where 1=1 ".$where;
        return $this->_db->fetchone('select count(*) as count from `'.$this->_table_goods_msg.'` '.$where);
    }

    /**
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */

    public function getGoods($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null){
        $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;
        if($page){
            $offset = ($page-1)*$pageSize;
            $limit = ' limit '.$pageSize.' offset '.$offset;
        }
        $where && $where = ' where 1=1  '.$where;
        if($orderBy) $orderBy = ' order by '.$orderBy;
        else $orderBy = ' order by add_time desc';
        $sql = 'select '.$fields.' from `'.$this->_table_goods_msg.'` '.$where.' '.$orderBy.' '.$limit;

        return $this->_db->fetchAll($sql);
    }
    /**
     * 删除站点留言数据
     *
     * @param    int      $id
     * @return   void
     */
    public function delsite($id){
        $where = $this->_db->quoteInto('msg_id = ?', $id);
        if($id > 0) return $this->_db->delete($this->_table_msg, $where);
    }
    /**
     * 删除商品留言数据
     *
     * @param    int      $id
     * @return   void
     */
    public function delgoods($id){
        $where = $this->_db->quoteInto('goods_msg_id = ?', $id);
        if($id > 0) return $this->_db->delete($this->_table_goods_msg, $where);
    }
    /**
     * 取站点留言
     *
     * @param    int      $id
     * @return   void
     */
    public function getSiteMsgByID($id){
        return $this->_db->fetchRow('select * from ' .$this->_table_msg.' where msg_id='.$id);
    }

    /**
     * 取商品留言
     *
     * @param    int      $id
     * @return   void
     */

    public function getGoodsMsgByID($id){
        return $this->_db->fetchRow('select * from ' .$this->_table_goods_msg.' where goods_msg_id='.$id);
    }
    /**
     * 回复站点留言
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function siteReply($id, $data){
        $where = $this->_db->quoteInto('msg_id = ?', $id);
        if($id > 0) return $this->_db->update($this->_table_msg, $data, $where);
    }

    /**
     * 回复商品留言
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function goodsReply($id, $data){
        $where = $this->_db->quoteInto('goods_msg_id = ?', $id);
        if($id > 0) return $this->_db->update($this->_table_goods_msg, $data, $where);
    }
    /**
     * 添加商品留言
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function goodsCommentAdd($data){
        $this->_db->insert($this->_table_goods_msg, $data);
        $lastInsertId = $this -> _db -> lastInsertId();
        return $lastInsertId;
    }

    /**
     * 编辑商品留言
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function editGoodsMsg($id, $data){
        $where = $this->_db->quoteInto('goods_msg_id = ?', $id);
        if($id > 0) return $this->_db->update($this->_table_goods_msg, $data, $where);
    }

    /**
     * 获取投诉数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
    public function getComplaint($search = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null){
        $limit = null;
        $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
        if ($page != null) {
            $offset = ($page-1)*$pageSize;
            $limit = " LIMIT  $pageSize  OFFSET $offset";
        }
        //
        $where = ' where 1';
        if($search){
            $search['id'] && $where.=" and id='".$search['id']."'";
            isset($search['is_read']) && $search['is_read']!='' && $where.=" and is_read='".$search['is_read']."'";
            isset($search['is_solved']) && $search['is_solved']!='' && $where.=" and is_solved='".$search['is_solved']."'";
        }
        //
        $orderby = " order by id desc ";
        //
        $sqlct = "select count(id) from {$this->_table_complaint} a $where";
        $sql   = "select $fields from {$this->_table_complaint} $where $orderby $limit";
        $rs['tot'] = $this -> _db -> fetchOne($sqlct);
        $rs['datas'] = $this -> _db -> fetchAll($sql);
        //
        return $rs;
    }

    /**
     * 更新投诉
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function updateComplaint($dat, $id){
        $where = $this->_db->quoteInto('id = ?', $id);
        if($id > 0){return $this->_db->update($this->_table_complaint, $dat, $where);}
        else{return false;}
    }
}