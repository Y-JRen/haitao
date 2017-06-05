<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
function smarty_modifier_cn_truncate($string, $strlen = 20, $etc = '...', $keep_first_style = false) {
	return _cn_substr_utf8($string,$strlen,0,$etc);
}

/* vim: set expandtab: */

/**
 * PHP 截取中文字符
 * echo cn_substr_utf8($cc,65)
 */
function _cn_substr_utf8($str, $length, $start = 0,$etc = '...') {
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
		$str .= $etc;
	}
	return $str;
}
?>
