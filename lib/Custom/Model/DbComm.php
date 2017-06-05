<?php
/**
 * 
 * 对DB操作的公用文件
 * @author chenjiamin
 * @version v1.1
 * @copyright 1jiankang.com
 *
 */
class Custom_Model_DbComm
{
	private  $_db = null;
	
    public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
    public function getAll($tbl_name,$where=array(),$col='*',$num=0,$ord='')
    {
	    $col = explode(',', $col);
	    $select = $this->_db->select();
	    $select->from($tbl_name, $col);
	    $this->where($select,$where);
	    if($num>0)
	        $select->limit($num,0);
	    if(!empty($ord))
	        $select->order($ord);
	    $list = $this->_db->fetchAll($select);
	    return $list;
	}
	
    public function getAllWithLink($tbl,$links = array(),$where=array(),$num=0,$ord='')
    {
	    $exp_tbl = explode('|',$tbl);
	    $tbl_name = $exp_tbl[0];
	    $tbl_field = empty($exp_tbl[1]) ? '' : $exp_tbl[1];
	    //数据列表
	    $db_select = $this->_db->select();
	    $db_select->from($tbl_name,explode(',',$tbl_field));
	    //处理连接
	    if(!empty($links)){
	        foreach ($links as $k=>$v){
	            $exp_k = explode('|', $k);
	            $exp_v = explode('|', $v);
	            $join_where = $exp_v[0];
	            $join_field = empty($exp_v[1]) ? '' : $exp_v[1];
	            $exp_join_field = explode(',', $join_field);
	            $count_exp_k = count($exp_k);
	            if($count_exp_k == 1){
	                $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
	            }elseif($count_exp_k == 2 && $exp_k[1] == 'l'){
	                $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
	            }else{
	                $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
	            }
	        }
	    }
	    $this->where($db_select,$where);
	    if(!empty($ord))
	        $db_select->order($ord);	
	    if($num>0)
	        $db_select->limit($num,0);
	    return  $this->_db->fetchAll($db_select);
	}
	
    public function getRow($tbl_name,$where=array(),$col='*',$num=0,$ord='')
    {
	    $select = $this->_db->select();
	    $select->from($tbl_name, $col);
	    $this->where($select,$where);
	    if($num>0)
	        $select->limit($num,0);
	    if(!empty($ord))
	        $select->order($ord);
	    return $this->_db->fetchRow($select);	   
	}
	
    public function max($tbl_name,$col,$where=array())
    {
	    $select = $this->_db->select();
	    $select->from($tbl_name, 'max('.$col.') as max');
	    $this->where($select,$where);
	    $r = $this->_db->fetchRow($select);
	    return $r['max'];
	}
	
    public function min($tbl_name,$col,$where=array())
    {
	    $select = $this->_db->select();
	    $select->from($tbl_name, 'min('.$col.') as min');
	    $this->where($select,$where);
	    $r = $this->_db->fetchRow($select);
	    return $r['min'];
	}
    public function insert($tbl,$data){
	    $sql = "show columns from ".$tbl;
	    $rs = $this->_db->execute($sql);
	    $list_col = $rs->fetchAll();
	    $arr_col = array();
	    foreach ($list_col as $k=>$v){
	        $arr_col[] = $v['Field'];
	    }
	    foreach ($data as $k=>$v){
	        if(!in_array($k, $arr_col)) unset($data[$k]);	
	    }   
	    return $this->_db->insert($tbl, $data);
	}
    public function update($tbl,$data,$where)
    {
	    $sql = "show columns from ".$tbl;
	    $rs = $this->_db->execute($sql);
	    $list_col = $rs->fetchAll();
	    $arr_col = array();
	    foreach ($list_col as $k=>$v){
	        $arr_col[] = $v['Field'];
	    }
	    foreach ($data as $k=>$v){
	        if(!in_array($k, $arr_col)) unset($data[$k]);
	    }
	    $select = $this->_db->select();
	    $select->from('some_table');
	    $this->where($select,$where);
	    $sql_where = $select->__toString();
	    $exp_sql_where = explode('WHERE', $sql_where);
	    return $this->_db->update($tbl, $data,$exp_sql_where[1]);
	}
    public function isIn($tbl,$where)
    {
    	$r = self::getRow($tbl,$where);
	    return empty($r) ? false : true;
	}
    public function count($tbl,$where)
    {
	    $select = $this->_db->select();
	    $select->from($tbl, 'count(*) as count');
	    $this->where($select,$where);
	    $r = $this->_db->fetchRow($select);
	    return $r['count'];
	}
    public function getListByPage(&$page,$tbl,$links = array(),$where=array(),$ord='')
    {
	    $ps = empty($page['ps']) ? 24 : $page['ps'];
	    $exp_tbl = explode('|',$tbl);
	    $tbl_name = $exp_tbl[0];
	    $tbl_field = empty($exp_tbl[1]) ? '' : $exp_tbl[1];
	    $db_select = $this->_db->select();
        $db_select->from($tbl_name,'count(*) as count');
	    //处理连接
	    if(!empty($links)){
	        foreach ($links as $k=>$v){

	            $exp_k = explode('|', $k);
	            $exp_v = explode('|', $v);

	            $count_exp_k = count($exp_k);
	            if($count_exp_k == 1){
	                $db_select->joinLeft($exp_k[0],$exp_v[0]);
	            }elseif($count_exp_k == 2 && $exp_k[1] == 'l'){
	                $db_select->joinLeft($exp_k[0],$exp_v[0]);
	            }else{
	                $db_select->joinLeft($exp_k[0],$exp_v[0]);
	            }
	        }
	    }
	    $this->where($db_select,$where);
	    $r = $this->_db->fetchRow($db_select);
	    $total = $page['tcount'] = $r['count'];
	    $objPage = new Custom_Model_Page($total, $ps,3);
	    $page['pagenav'] = $objPage -> show();
	    //数据列表
	    $db_select = $this->_db->select();
	    $db_select->from($tbl_name,explode(',',$tbl_field));
	    //处理连接
	    if(!empty($links)){
	        foreach ($links as $k=>$v){
	            $exp_k = explode('|', $k);
	            $exp_v = explode('|', $v);
	            $join_where = $exp_v[0];
	            $join_field = empty($exp_v[1]) ? '' : $exp_v[1];
	            $exp_join_field = explode(',', $join_field);
	            $count_exp_k = count($exp_k);
	            if($count_exp_k == 1){
	                $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
	            }elseif($count_exp_k == 2 && $exp_k[1] == 'l'){
	                $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
	            }else{
	                $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
	            }
	        }
	    }
	    $this->where($db_select,$where);
	    $db_select->limit($ps,($page['pn']-1)*$ps);
	    if(!empty($ord)) $db_select->order($ord);
	    $list = $this->_db->fetchAll($db_select);
	    return $list;
	}
	
    protected function where(&$select,$where,$debug = false)
    {
	    if(empty($where)) return;
        $where = (array)$where;
        $arr_modifer = array(
                'll'  => 'like ?',
                'rl'  => 'like ?',
                'l'   => 'like ?',
                'gt'  => '> ?',
                'lt'  => '< ?',
                'egt' => '>= ?',
                'elt' => '<= ?',
                'neq' => '!= ?',
                'eq'  => '= ?',
                'in'  => 'IN(?)'
        );
        foreach ($where as $k=>$v){
            $k = trim($k);
            if(empty($k)) continue;
            if(is_array($v) && empty($v)) continue;
            if(strpos($k, '|') !== false){
                $t = explode('|', $k);
                $colname = $t[0];
                $flag_or = strpos($t[1], 'o') !== false ? true : false;
                $modifyer = trim($t[1],'o');
                if(!array_key_exists($modifyer, $arr_modifer)) continue;
                if($modifyer == 'in'){
                    if(!is_array($v)){
                        $colvalue = explode(',', trim($v,','));
                    }else{
                        $colvalue = $v;
                    }
                }elseif($modifyer == 'll'){
                    $colvalue = $v.'%';
                }elseif($modifyer == 'rl'){
                    $colvalue = '%'.$v;
                }elseif($modifyer == 'l'){
                    $colvalue = '%'.$v.'%';
                }else{
                    $colvalue = $v;
                }
                if($flag_or){
                    $select->orWhere($colname.' '.$arr_modifer[$modifyer],$colvalue);
                }else{
                    $select->where($colname.' '.$arr_modifer[$modifyer],$colvalue);
                }
            }elseif($k == '_sql'){
                $select->where('('.$v.')');
            }else{
                $select->where($k.' = ?',$v);
            }
        }
	}
	
    public function getLastInsertId(){
        return $this -> _db -> lastInsertId();
    }
    
    public function getAjaxListByPage(&$page,$tbl,$links = array(),$where=array(),$ord=''){
    
        $ps = empty($page['ps']) ? 24 : $page['ps'];
        $page['pn'] = empty($page['pn']) ? 1 : $page['pn'];
        $exp_tbl = explode('|',$tbl);
        $tbl_name = $exp_tbl[0];
        $tbl_field = empty($exp_tbl[1]) ? '' : $exp_tbl[1];
    
        $db_select = $this->_db->select();
    
        $db_select->from($tbl_name,'count(*) as count');
    
        //处理连接
        if(!empty($links)){
            foreach ($links as $k=>$v){
    
                $exp_k = explode('|', $k);
                $exp_v = explode('|', $v);
    
                $count_exp_k = count($exp_k);
                if($count_exp_k == 1){
                    $db_select->joinLeft($exp_k[0],$exp_v[0]);
                }elseif($count_exp_k == 2 && $exp_k[1] == 'l'){
                    $db_select->joinLeft($exp_k[0],$exp_v[0]);
                }else{
                    $db_select->joinLeft($exp_k[0],$exp_v[0]);
                }
            }
        }
    
        $this->where($db_select,$where);
        $r = $this->_db->fetchRow($db_select);
        $total = $page['tcount'] = $r['count'];
    
        //数据列表
        $db_select = $this->_db->select();
    
        $db_select->from($tbl_name,explode(',',$tbl_field));
    
        //处理连接
        if(!empty($links)){
            foreach ($links as $k=>$v){
    
                $exp_k = explode('|', $k);
                $exp_v = explode('|', $v);
    
                $join_where = $exp_v[0];
                $join_field = empty($exp_v[1]) ? '' : $exp_v[1];
                $exp_join_field = explode(',', $join_field);
                $count_exp_k = count($exp_k);
    
                if($count_exp_k == 1){
                    $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
                }elseif($count_exp_k == 2 && $exp_k[1] == 'l'){
                    $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
                }else{
                    $db_select->joinLeft($exp_k[0],$join_where,$exp_join_field);
                }
            }
        }
    
        $this->where($db_select,$where);
    
        $db_select->limit($ps,($page['pn']-1)*$ps);
    
        if(!empty($ord)) $db_select->order($ord);
    
        $list = $this->_db->fetchAll($db_select);
    
        return $list;
    }
    
    
    public function delete($tbl,$where){
    
        $select = $this->_db->select();
        $select->from('some_table');
        $this->where($select,$where);
        $sql_where = $select->__toString();
        $exp_sql_where = explode('WHERE', $sql_where);
        return $this->_db->delete($tbl,$exp_sql_where[1]);
    
    }
	
	public function fetchBySql($stmt_sql,$bind=array()){
		return $result = $this->_db->fetchAll($stmt_sql, $bind);
	}
}