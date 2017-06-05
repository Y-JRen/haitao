<?php
/**
 * XML操作类
 * 
 * @param    void
 * @return   void
 */
class Custom_Model_Xml{
	/**
	* 发送XML数据
	*
	* @return void
	*/
	public function postXml($url,$xml_data){
		$ch = curl_init();
		$header[] = "Content-type: text/xml";//定义content-type为xml
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
		$response = curl_exec($ch);
		if(curl_errno($ch))
		{
			print curl_error($ch);
		}
		curl_close($ch); 
		return $response;
	}
	/**
	* 获取XML数据
	*
	* @return void
	*/
	public function getXml($url){
		$ch = curl_init();
		$header[] = "Content-type: text/xml";//定义content-type为xml
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$response = curl_exec($ch);
		if(curl_errno($ch))
		{
			print curl_error($ch);
		}
		curl_close($ch); 
		return $response;
	}

	/**
	 * 把XML转化为数组和Values的格式
	 */
    function xml2arrayValues($contents, $get_attributes=1) {
        if(!$contents) return array();
        if(!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }
        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create();
        xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
        xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 0 );
        xml_parse_into_struct( $parser, $contents, $xml_values );
        xml_parser_free( $parser );
        if(!$xml_values) return;//Hmm...
        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $current = &$xml_array;
        //Go through the tags.
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble
            extract($data);//We could use the array by itself, but this cooler.
            $result = '';
            if($get_attributes) {//The second argument of the function decides this.
                $result = array();
                if(isset($value)) $result['value'] = trim($value);

                //Set the attributes too.
                if(isset($attributes)) {
                    foreach($attributes as $attr => $val) {
                        if($get_attributes == 1) $result['attr'][$attr] = trim($val); //Set all the attributes in a array called 'attr'
                        /**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */
                    }
                }
            } elseif(isset($value)) {
                $result = trim($value);
            }
            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;
                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    $current = &$current[$tag];

                } else { //There was another element with the same tag name
                    if(isset($current[$tag][0])) {
                        array_push($current[$tag], $result);
                    } else {
                        $current[$tag] = array($current[$tag],$result);
                    }
                    $last = count($current[$tag]) - 1;
                    $current = &$current[$tag][$last];
                }
            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;

                } else { //If taken, put all things inside a list(array)
                    if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array...
                        or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
                            array_push($current[$tag],$result); // ...push the new element into that array.
                        } else { //If it is not an array...
                            $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        }
                }

            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }
        return($xml_array);
    }
	/**
	 * XML转数组
	 */
    function xml2array($contents, $output_tag=null) {
        if(!$contents) return array();

        if(!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }
        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create();
        xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
        xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 0 );
        xml_parse_into_struct( $parser, $contents, $xml_values );
        xml_parser_free( $parser );
        if(!$xml_values) return;//Hmm...
        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $current = &$xml_array;
        $number=0;
        //Go through the tags.
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble
            extract($data);//We could use the array by itself, but this cooler.
            $result = '';
            if($tag=='item') {//The second argument of the function decides this.
                if(!is_null($value)) $result = trim($value);
                    $tag=$number;
                    $number++;
            } elseif(!is_null($value)) {
                $result = trim($value);
            }
            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;

                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    $current = &$current[$tag];

                } else { //There was another element with the same tag name
                    if(isset($current[$tag][0])) {
                        array_push($current[$tag], $result);
                    } else {
                        $current[$tag] = array($current[$tag],$result);
                    }
                    $last = count($current[$tag]) - 1;
                    $current = &$current[$tag][$last];
                }
            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;

                } else { //If taken, put all things inside a list(array)
                    if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array...
                        or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
                            array_push($current[$tag],$result); // ...push the new element into that array.
                        } else { //If it is not an array...
                            $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        }
                }
            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }
        if($tag=='item'){
            $number=0;
        }
        if($output_tag){
            return($xml_array[$output_tag]);
        }else{
            return($xml_array);
        }
    }
	/**
	 *获取XML标签值
	 * 
	 */
    function getPath($xml,$tagName,$attr=null){
        $parser = xml_parser_create();
        xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
        xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 0 );
        xml_parse_into_struct( $parser, $xml, $xml_values );
        xml_parser_free( $parser );
        $node = null;
        foreach($xml_values as $k=>$v){
            if($tagName==$v['attributes']['type']){
                if($attr){
                    if(count(array_diff_assoc($attr,$v['attributes'])) == 0){
                        $node = &$xml_values[$k];
                        break;
                    }
                }else{
                    $node = &$xml_values[$k];
                    break;
                }
            }
        }
        $path=array();
        if($node){
            for($level = $node['level'];$k>-1;$k--){
                if($xml_values[$k]['level'] == $level){
                    array_unshift($path,$xml_values[$k]);
                    $level--;
                }
            }
//      unset($xml_values);
            return $path;
        }else{
//      unset($xml_values);
            return false;
        }

    }
	/**
	 *把数组转化为xml文档
	 * 
	 */
    function array2xml($data,$root='root'){
        $xml='<'.$root.'>';
        $this->_array2xml($data,$xml);
        $xml.='</'.$root.'>';
        return $xml;
    }

    function _array2xml(&$data,&$xml){
        if(is_array($data)){
            foreach($data as $k=>$v){
                if(is_numeric($k)){
                    $xml.='<item>';
                    $xml.=$this->_array2xml($v,$xml);
                    $xml.='</item>';
                }else{
                    $xml.='<'.$k.'>';
                    $xml.=$this->_array2xml($v,$xml);
                    $xml.='</'.$k.'>';
                }
            }
        }elseif(is_numeric($data)){
            $xml.=$data;
        }elseif(is_string($data)){
            $xml.='<![CDATA['.$data.']]>';
        }
    }
}