<?php

class Custom_Model_Tools {

	public static function vd($var, $is_append = false, $stop = false) {
		$filename = SYSROOT . '/www/log.txt';
		if (!file_exists($filename)) {
			$fp = fopen($filename, "w+");
			fclose($fp);
		}

		if (!$is_append) {
			file_put_contents($filename, '');
		}

		error_log(print_r($var, 1), 3, $filename);
		if ($stop)
			exit ;
	}

	public static function getListFiled($field, $list) {
		$arr = array();

		foreach ($list as $k => $v) {
			$arr[] = $v[$field];
		}

		return (array)$arr;
	}

	public static function info($var, &$i = 0) {
		++$i;
		if (is_array($var)) {
			$str_null = '';
			for ($j = 0; $j <= $i; $j++) {
				$str_null .= '     ';
			}
			foreach ($var as $k => $v) {
				if (is_array($v)) {
					echo '<script>console.info("' . $str_null . '[' . $k . ']=>Array(");</script>';
					self::info($v, $i);
				} else {
					$str = $str_null . '[' . $k . ']=>' . $v;
					echo '<script>console.info("' . $str . '");</script>';
				}
			}
			echo '<script>console.info("' . $str_null . ')");</script>';
		} else {
			echo '<script>console.info("' . $var . '");</script>';
		}
	}

	public static function validate($vtype, $var) {

	}

	public static function ejd($status, $msg = '', $href = '', $data = array()) {

		if ($status == 'debug') {
			$msg = '<pre>' . print_r($msg, 1);
		}

		echo json_encode(array('status' => $status, 'msg' => $msg, 'href' => $href, 'data' => $data));

		exit ;
	}

	/**
	 * PHP 截取中文字符
	 * echo cn_substr_utf8($cc,65)
	 */
	public static function cn_substr_utf8($str, $length, $start = 0) {
		$lgocl_str = $str;
		//echo strlen($lgocl_str)."||".$length;
		if (strlen($str) < $start + 1) {
			return '';
		}
		preg_match_all("/./su", $str, $ar);
		$str = '';
		$tstr = '';

		//为了兼容mysql4.1以下版本,与数据库varchar一致,这里使用按字节截取
		for ($i = 0; isset($ar[0][$i]); $i++) {
			if (strlen($tstr) < $start) {
				$tstr .= $ar[0][$i];
			} else {
				if (strlen($str) < $length + strlen($ar[0][$i])) {
					$str .= $ar[0][$i];
				} else {
					break;
				}
			}
		}
		if (strlen($lgocl_str) <= $length) {
		} else {
			$str .= "...";
		}
		return $str;
	}
	
	public static function list_fkey($list,$field_name){
		$t_list = array();
		foreach ($list as $k => $v) {
			$t_list[$v[$field_name]] = $v;
		}
		return $t_list;
	}
		
	public static function findChild(&$arr, $id,$pid_name) {
	    $childs = array ();
	    foreach ( $arr as $k => $v ) {
	        if ($v [$pid_name] == $id) {
	            $childs [] = $v;
	        }
	    }
	
	    return $childs;
	}
	public static function build_tree($list,$id_name,$pid_name,$sub_name,$root_id=0) {
	
	    $childs = self::findChild ( $list, $root_id ,$pid_name);
	    if (empty ( $childs )) {
	        return null;
	    }
	    foreach ( $childs as $k => $v ) {
	        $rescurTree = self::build_tree ($list,$id_name,$pid_name,$sub_name, $v [$id_name] );
	        if (null != $rescurTree) {
	            $childs [$k] [$sub_name] = $rescurTree;
	        }
	    }
	    return $childs;
	}
	
	public static function getCatTree($list, $pid = 0, &$t = array()){
		
		foreach ($list as $k=>$v){
			
			if($v['parent_id'] == $pid){
				
				$v['step'] = self::getCatDepth($list, $v['cat_id']);
	
				$v['has_sub'] = self::hasCatSub($list, $v['cat_id']);
				
				$t[] = $v;
				self::getCatTree($list,$v['cat_id'],$t);
				
			}
		}
		
		return $t;
		
	}
	
	public static function getCatDepth($list,$id,&$i=0){
		
		foreach ($list as $v){
			if($v['cat_id'] == $id && $v['parent_id'] != 0){
				$i++;
				self::getCatDepth($list,$v['parent_id'],$i);
			}
		}
		
		return $i;
		
	}
	
	public static function hasCatSub($list,$id){
		
		foreach ($list as $v){
			if($v['parent_id'] == $id){
				return true;
			}
		}
	
		return false;
	}
	
	
	public static function getCatFname($list,$id,$str_div = '&gt;'){

		$arr = self::getCatFnameArr($list, $id);
		
		foreach ($list as $k=>$v){
			if($v['cat_id'] == $id) $arr[] = $v['cat_name'];
		}
		
		$fname = implode($arr, $str_div);
		if(!empty($fname)) return $fname;
		return '其它';
	}
	
	public static function getCatFnameArr($list,$id,&$t = array()){
	
		foreach ($list as $k=>$v){
			if($v['cat_id'] == $id){
				
				$pid = $v['parent_id'];
				
				foreach ($list as $z=>$j){
					if($j['cat_id'] == $pid){
						$t[] = $j['cat_name'];
						self::getCatFnameArr($list, $j['cat_id'],$t);
					}
				}
			
			}
			
		}
		
		return array_reverse($t);
		
	}
	
	
	public static function getCatSubId($list,$id,&$arr_sub_id= array()){
		
		foreach ($list as $k=>$v){
			
			if($v['parent_id'] == $id){
				
				$arr_sub_id[] = $v['cat_id'];
				self::getCatSubId($list, $v['cat_id'],$arr_sub_id);
				
			}
			
		}
	
		return array_merge(array($id),$arr_sub_id);
		
	}

}
