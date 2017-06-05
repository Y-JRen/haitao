<?php
class Admin_Models_API_Msg
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
     * 构造函数
     *
     * @param  void
     * @return void
     */
    public function __construct(){
        $this->_db = new Admin_Models_DB_Msg();

    }
    /**
     * 获取站点留言条数
     *
     * @param    string    $where
     * @return   void
     */
    public function getCountSite($where=null){
        return $this->_db->getCountSite($where);
    }

    /**
     * 获取站点留言数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
    public function getSite($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null){
        return $this->_db->getSite($where, $fields, $orderBy, $page, $pageSize);
    }
    /**
     * 获取商品留言条数
     *
     * @param    string    $where
     * @return   void
     */
    public function getCountGoods($search=null){

        if ($search != null ) {
            if ($search['is_hot'] !== '' && $search['is_hot'] !== null) {
                $where .= " and is_hot ='$search[is_hot]'";
            }
            if ($search['status'] !== '' && $search['status'] !== null) {
                $where .= " and status ='$search[status]'";
            }
            if ($search['fromdate']) {
                $where .= " and add_time >= ".strtotime($search['fromdate']);
            }
            if ($search['todate']) {
                $where .= " and add_time <= ".strtotime($search['todate'].' 23:59:59');
            }
            ($search['type']) ? $where .="  and type ='$search[type]'": " and type =1 ";
            ($search['goods_id']) ? $where .="  and goods_id ='$search[goods_id]'": "";
            ($search['goods_name']) ? $where .="  and goods_name like '%$search[goods_name]%'": "";
        }

        return $this->_db->getCountGoods($where);
    }
    /**
     * 获取商品留言数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
    public function getGoods($search = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null){
        if ($search != null ) {
            if ($search['is_hot'] !== '' && $search['is_hot'] !== null) {
                $where .= " and is_hot ='$search[is_hot]'";
            }
            if ($search['status'] !== '' && $search['status'] !== null) {
                $where .= " and status ='$search[status]'";
            }
            if ($search['fromdate']) {
                $where .= " and add_time >= ".strtotime($search['fromdate']);
            }
            if ($search['todate']) {
                $where .= " and add_time <= ".strtotime($search['todate'].' 23:59:59');
            }
            ($search['type']) ? $where .="  and type ='$search[type]'": " and type =1 ";
            ($search['goods_id']) ? $where .="  and goods_id ='$search[goods_id]'": "";
            ($search['goods_name']) ? $where .="  and goods_name like '%$search[goods_name]%'": "";
        }
        return $this->_db->getGoods($where, $fields, $orderBy, $page, $pageSize);
    }

    /**
     * 删除站点留言数据
     *
     * @param    int      $id
     * @return   void
     */
    public function delsite($id){
        return $this->_db->delsite($id);
    }
    /**
     * 删除商品留言数据
     *
     * @param    int      $id
     * @return   void
     */
    public function delgoods($id){
        return $this->_db->delgoods($id);
    }
    /**
     * 取站点留言
     *
     * @param    int      $id
     * @return   void
     */

    public function getSiteMsgByID($id){
        return $this->_db->getSiteMsgByID($id);
    }

    /**
     * 取商品留言
     *
     * @param    int      $id
     * @return   void
     */
    public function getGoodsMsgByID($id){
        return $this->_db->getGoodsMsgByID($id);
    }

    /**
     * 回复站点留言
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function siteReply($id, $data){
        return $this->_db->siteReply($id, $data);
    }
    /**
     * 回复商品留言
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function goodsReply($id, $data){
        return $this->_db->goodsReply($id, $data);
    }

    /**
     * 添加商品留言
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function goodsCommentAdd($data){
        return $this->_db->goodsCommentAdd($data);
    }
    /**
     * 编辑商品留言
     *
     * @param    int      $id
     * @param    array      $data
     * @return   void
     */
    public function editGoodsMsg($id, $data){
        $this->_db->editGoodsMsg($id, $data);
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
    public function getComplaint($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null){
        return $this -> _db -> getComplaint($where, $fields, $orderBy, $page, $pageSize);
    }

    /**
     * 更新投诉
     *
     * @param array $dat
     * @param int   $id
     * @return array
     */
    public function updateComplaint($dat=array(), $id=null){
        if(!count($dat)){ return array('status'=>'err', 'err_msg'=>'没有数据'); }
        if(!$id){ return array('status'=>'err', 'err_msg'=>'参数错误'); }
        $rs = $this -> _db -> updateComplaint($dat, $id);
        if($rs){ return array('status'=>'ok', 'ok_msg'=>'操作成功'); }
        else{ return array('status'=>'err', 'err_msg'=>'操作失败'); }
    }
}
