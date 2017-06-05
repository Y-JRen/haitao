<?php
class Shop_Models_API_Page
{
    /**
     * DB对象
     */
	public   $_db = null;

    /**
     * 错误信息
     */
	protected $error;

	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Shop_Models_DB_Page();
	}

	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getInfo($where = null, $fileds = '*')
	{
		return $this -> _db -> getInfo($where, $fileds);
	}

	/**
     * 获取帮助栏目数据
     *
     */
	public function getListCat($where = null)
	{
		$catList = $this -> _db -> getCat($where);
		foreach($catList as $key=>$cat){
            $catList[$key]['list'] = $this->_db->getArticleInfosByCatId($cat['cat_id']);
		}

		return $catList;
	}

	/**
     * 获取总数
     *
     * @return   int
     */
	public function getCount()
	{
		return $this -> _db -> total;
	}

	/**
     * 构造分类树.
     *
     * @param    array    $deny
     * @param    array    $data
     * @param    int      $parentID
     * @return   array
     */
	public function catTree($deny=null,$data=null,$parentID=0,$where=null)
	{
        static $tree, $step;
        if(!$data){
            $data = $this -> _db-> getCat($where);
        }
        foreach($data as $v){
            if($v['parent_id'] == $parentID){
                $step++;
                $tree[$v['cat_id']] = array('cat_id'=>$v['cat_id'],
                                            'cat_name'=>$v['cat_name'],
                                            'parent_id'=>$v['parent_id'],
                                            'cat_path'=>$v['cat_path'],
                                            'cat_sort'=>$v['cat_sort'],
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
                $this -> catTree($deny,$data,$v['cat_id']);
                $step--;
            }
        }
        if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
            $tree[$parentID]['leaf'] = 1;
        }
        return $tree;
	}

    
    /**
     * 按类别获取文章
     * @param unknown $catid
     * @param number $num
     */
     public function  getArtByCat($catid,$num=5)
     {
     	return $this->_db->getArtByCat($catid,$num);
     }

     /**
      * 获取文章列表
      * @param string $where
      * @param number $page
      */
     public  function getArtList($where=NULL, $page=1)
     {
     	return $this -> _db -> getArtList($where, $page);
     }
     /**
      * 文章列表展示
      *
      * @param int  $page
      * @return string
      */
     public function showArtList($page,$cat_id)
     {
     	$page_size = 20;
     	$data = $this -> getArtList(array('cat_id'=>$cat_id,'source'=> ''),$page);
     	$objPage = new Custom_Model_PageNav($data['total'],$page_size);
     	$pagenav = $objPage ->getListPageNavigation();
     	$data['pagenav']=$pagenav;
     	$list = $data;
     	
     	return $list;
     }
     
	/**
	 * 简单 curl
	 */
	public function simpleCurl($url) {
	    $ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $url);
		curl_setopt($ch2, CURLOPT_HEADER, false);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		$rs = curl_exec($ch2);
		return $rs;
	}

	/**
	 * 解析xml
	 */
	public function xmlToArray($file, $type=0){
		$str='';
		//将$file读到字符串中
		if($type == 0){
			$str = simplexml_load_string($file);
		}else{
			$str = simplexml_load_file($file);
		}
		//
		$str = serialize($str);
		$str = str_replace('O:16:"SimpleXMLElement"','a',$str);
		return unserialize($str);
	}

	/**
	 * 网易（IP & 手机）归属地查询
	 *
	 * @param string $type
	 * @param string $q
	 *
	 * @return array
	 */
	public function neteaseIpMobileIdLocationApi($type=null, $q=null) {
		if($type==null || $q==null){return null;}
		if($type!='ip' && $type!='mobile' && $type!='id'){return null;}
		//IP
		if($type=='ip'){
			if(!filter_var($q, FILTER_VALIDATE_IP)){
				return null;
			}
		}
		//手机
		if($type=='mobile'){
			if(!Custom_Model_Check::isMobile($q)){
				return null;
			}
		}
		//身份证
		if($type=='id'){
			return null;
		}
		$url = 'http://www.youdao.com/smartresult-xml/search.s?type='.$type.'&q='.$q;
		$xml = $this -> simpleCurl($url);
		$xml = $this -> xmlToArray($xml);
		return $xml;
	}

}