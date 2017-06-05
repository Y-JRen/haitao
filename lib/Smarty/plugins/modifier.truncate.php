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
function smarty_modifier_truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
	
	 /*
	 if ($length == 0)
	 return '';

	 if (_strLen($string) > $length) {
	 //$length -= min($length, _strLen($etc));
	 if (!$break_words && !$middle) {
	 $string = preg_replace('/\s+?(\S+)?$/', '', _subStr($string, 0, $length+1));
	 }
	 if(!$middle) {
	 return _subStr($string, 0, $length) . $etc;
	 } else {
	 return _subStr($string, 0, $length/2) . $etc . _subStr($string, -$length/2);
	 }
	 } else {
	 return $string;
	 }
	 * */

	if ($length == 0)
		return '';

	if (strlen($string) > $length) {
		$length -= min($length, strlen($etc));
		for ($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
		}
		return $strcut . $etc;

	} else {
		return $string;
	}
}

/**
 * 截取字符串
 *
 * @param    string    $string    被截取的字符串
 * @param    integer   $start     截取开始位置
 * @param    integer   $length    截取长度
 *
 * @return  string
 */
function _subStr($string, $start = 0, $length) {
	$subLength = $start + $length;
	if (_strlen($string) <= $subLength) {
		return $string;
	}

	if (function_exists('mb_substr')) {
		$result = mb_substr($string, $start, $length, 'UTF-8');
	} else {
		$i = $j = 0;
		while ($j < $subLength) {
			if (ord(substr($string, $i, 1)) > 127) {
				$result .= substr($string, $i, 3);
				$i += 3;
			} else {
				$result .= substr($string, $i, 1);
				$i++;
			}
			$j++;
		}
	}
	return $result;
}

/**
 * 计算字符串长度
 *
 * @param    string    $string    字符串
 * @return  string
 */
function _strLen($string) {
	if (function_exists('mb_strlen')) {
		$length = mb_strlen($string, 'UTF-8');
	} else {
		$i = $length = 0;
		while ($i < strlen($string)) {
			if (ord(substr($string, $i, 1)) > 127) {
				$i += 3;
			} else {
				$i++;
			}
			$length++;
		}
	}
	return $length;
}

/* vim: set expandtab: */
?>
