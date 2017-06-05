<?php

class Custom_Model_Excel
{
	/**
	 * 写入XLS文件头
	 */
	function xls_BOF()
	{
	 echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	 return;
	}

	/**
	 * 写入XLS文件尾
	 */
	function xls_EOF()
	{
	 echo pack("ss", 0x0A, 0x00);
	 return;
	}

	/**
	 * 写入一个数字
	 */
	function xls_write_number($row, $col, $value)
	{
	 echo pack("sssss", 0x203, 14, $row, $col, 0x0);
	 echo pack("d", $value);
	 return;
	}

	/**
	 * 写入一个文字
	 */
	function xls_write_label($row, $col, $value, $tochar = 'GB2312', $fromchar = 'UTF-8')
	{
	 $value = mb_convert_encoding($value, $tochar, $fromchar);
	 $l = strlen($value);
	 echo pack("ssssss", 0x204, 8 + $l, $row, $col, 0x0, $l);
	 echo $value;
	 return;
	}

	/*
	 * 向浏览器发送XLS的文件头信息
	 */
	function send_header($file_name='')
	{
	 if ( !$file_name ) $file_name = 'client_info';
	 
	 header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	 header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	 header ("Pragma: public");
	 header ('Content-type: application/x-msexcel');
	 header ("Content-Disposition: attachment; filename=".$file_name );
	}

}