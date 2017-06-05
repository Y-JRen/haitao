<?php
function smarty_modifier_cut_str($string, $sublen, $start = 0,$etc = '...', $code = 'UTF-8')
{
		if($code == 'UTF-8')
		{
			$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);
			$str_flag=0;
			$return_str = "";
			$total_strlen =0;
			foreach($t_string[0] as $charactor)
			{
				$t_strlen=strlen($charactor)>1?2:1;
				if($start <= $str_flag){
					$total_strlen += $t_strlen;
					if($total_strlen > $sublen){
						$return_str .= $etc;
						break;
					}
					$return_str .= $charactor;
				}
				$str_flag+=$t_strlen;
			}
			return $return_str;	
			
		}
		else
		{
			$start = $start*2;
			$sublen = $sublen*2;
			$strlen = strlen($string);
			$tmpstr = '';
	
			for($i=0; $i< $strlen; $i++)
			{
			if($i>=$start && $i< ($start+$sublen))
				{
				if(ord(substr($string, $i, 1))>129)
				{
					$tmpstr.= substr($string, $i, 2);
					}
					else
					{
					$tmpstr.= substr($string, $i, 1);
			}
			}
			if(ord(substr($string, $i, 1))>129) $i++;
		}
	if(strlen($tmpstr)< $strlen ) $tmpstr.= $etc;
	return $tmpstr;
	}
}