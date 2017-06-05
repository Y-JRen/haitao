<?php
class Shop_Models_DB_Msg 
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
	private $_pageSize = null;
	
	/**
     * table name
     * @var    string
     */
	private $_table_goods_msg = 'shop_goods_msg';
	private $_table_order_msg = 'shop_order_msg';
	private $_table_msg = 'shop_msg';
	private $_table_member_msg = 'shop_member_msg';
	private $_table_goods = 'shop_goods';
	private $_table_member_goods = 'shop_member_goods';

	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this  ->  _db = Zend_Registry::get('db');
	}
	
	/**
     * 用户站点留言
     *
     * @param    array      $data
     * @return   int
     */
	public function shopMsg($data)
	{
		$this -> _db -> insert($this -> _table_msg, $data);
		return $this -> _db -> lastInsertId();
	}
	
    /**
     * 商品评论
     *
     * @param    array      $data
     * @return   int
     */
	public function goodsMsg($data)
	{
		$this -> _db -> insert($this -> _table_goods_msg, $data);
		return $this -> _db -> lastInsertId();
	}
    //首页获得商品评论
	public function getCommByIndex($num){
            
        $sql="SELECT gm.goods_id,gm.goods_name,gm.goods_msg_id,gm.content,gd.goods_img 
	           FROM shop_goods_msg as gm
               LEFT JOIN shop_goods as gd on gd.goods_id=gm.goods_id
               LEFT JOIN shop_brand as bd on bd.brand_id=gd.brand_id 
	           where gm.is_hot=1 AND gm.`status`=1 AND gd.onsale=0 GROUP BY gm.goods_id ORDER BY gm.is_hot DESC, gm.add_time DESC LIMIT ".$num;		
        return $this->_db->fetchAll($sql); 
	}
        
	public function gettopmember(){
		$sql="select t.nick_name,t.photo ,t.cc from  (select m.nick_name,m.photo ,count(msg_id) as cc from shop_msg as ms  left join shop_member as m  on m.user_id=ms.user_id where ms.type=5 and nick_name<>'' and ms.add_time BETWEEN UNIX_TIMESTAMP(DATE_FORMAT(CURRENT_DATE(),'%Y-%m-01')) AND UNIX_TIMESTAMP()  group by user_name limit 5) as t  order by t.cc desc ";
		return $this->_db->fetchAll($sql);
	}
	
	/**
     * 获取用户留言
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getShopMsg($where = null, $fields = '*', $page = null, $pageSize = null,$photo='photo')
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = " WHERE ".$where;
		}
		
		$orderBy = " ORDER BY msg_id DESC";
		
		$table = "`$this->_table_msg`";
		
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table as ms $whereSql");
		if($photo!='photo'){
			return $this -> _db -> fetchAll("SELECT $fields FROM $table as ms $whereSql $orderBy $limit");
		}else{
			return $this -> _db -> fetchAll("SELECT $fields FROM $table as ms left join shop_member m  on  m.user_id=ms.user_id $whereSql $orderBy $limit");	
		}
	}
	
	/**
     * 获取热点商品评论
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getHotGoodsMsg($where = null, $fields = '*', $limit)
	{
		$table = "`$this->_table_goods_msg` as  msg left join `$this->_table_goods` as goods on msg.goods_id = goods.goods_id ";
		if ($where != null) {
			$whereSql = " WHERE ".$where;
		}
		$orderBy = " ORDER BY msg.goods_msg_id DESC";
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy limit $limit");
	}
	/*获取评论等级数量*/
	public function getCommByLevel($where= null ){
	    return $total = $this -> _db -> fetchOne("SELECT count(*) FROM shop_goods_msg WHERE  type= 1 AND `status` = 1 AND $where ");
	    
	}

	/**
     * 获取热点商品评论
     *
     * @param    string    $where
     * @return   array
     */
	public function getCslCount($goods_id = null)
	{   
        $table = "`$this->_table_goods_msg`";
        $total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table where status=0 and type= 2 and goods_id='$goods_id' ");
        $ctotal = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table where status=1 and type= 2 and goods_id='$goods_id' ");
		return array('total'=>$total,'ctotal'=>$ctotal);
	}

	/**
     * 获取商品留言信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getGoodsMsg($where = null, $fields = '*', $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = " WHERE ".$where;
		}
		$orderBy = " ORDER BY add_time DESC";
		$table = "`$this->_table_goods_msg`";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
	}
	
	/**
     * 获取商品统计数据
     *
     * @param    array       $data
     * @return   array
     */
	public function checkBuy($data)
	{
		$whereSql = "WHERE user_id='{$data['user_id']}' and goods_id='{$data['goods_id']}'";
		$sql = "SELECT sum(number) as number FROM `$this->_table_member_goods` $whereSql GROUP BY goods_id";
		return true;
	}
	
	/**
     * 获取商品统计数据
     *
     * @param    int       $goods_id
     * @return   array
     */
	public function getGoodsCnt($goods_id)
	{
		$where = "status=1 and goods_id=$goods_id and add_time>1238544000";
		$sql = "SELECT cnt1 as k,count(cnt1) as v FROM `$this->_table_goods_msg` WHERE $where group by cnt1";
		$cnt['cnt1'] = $this -> _db -> fetchAll($sql);
		
		$sql = "SELECT cnt2 as k,count(cnt2) as v FROM `$this->_table_goods_msg` WHERE $where group by cnt2";
		$cnt['cnt2'] = $this -> _db -> fetchAll($sql);
		return $cnt;
	}
	public function getUserByRand($ltime, $time)
	{
         $sql = "SELECT user_name,last_login FROM shop_user WHERE last_login>".$ltime." and last_login<".$time." and user_name like '%@%' ORDER BY RAND() LIMIT 300";
         return $this -> _db -> fetchAll($sql);
	}
}