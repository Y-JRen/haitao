<?php
function smarty_function_html($params, &$smarty) {
    
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
    $type = $params['type'];
    unset($params['type']);
    if($type == 'html'){
        $rs = smarty_function_html_html_html($params,$smarty);
    }elseif($type == 'slt'){
        $rs = smarty_function_html_html_select($params,$smarty);
    }elseif($type == 'time'){
        $rs = smarty_function_html_html_time($params,$smarty);
    }elseif($type == 'radio'){
        $rs = smarty_function_html_html_radio($params,$smarty);
    }elseif($type == 'mslt'){
        $rs = smarty_function_html_html_mselect($params,$smarty);
    }elseif($type == 'tmslt'){
        $rs = smarty_function_html_html_tmslt($params,$smarty);
    }elseif($type == 'txt'){
        $rs = smarty_function_html_html_txt($params,$smarty);
    }elseif($type == 'pic'){
        $rs = smarty_function_html_html_pic($params,$smarty);
    }elseif($type == 'wdt'){
        $rs = smarty_function_html_html_wdt($params,$smarty);
    }elseif($type == 'img'){
        $rs = smarty_function_html_html_img($params,$smarty);
    }else{
        $rs = smarty_function_html_html_input($params,$smarty);
    }
    
    return $rs;
    
}


function smarty_function_html_html_time($params,&$smarty){
	
    $rs = '';
    $id = '';
    $extra = '';
    $name = '';
    $value = '';

    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'id':
                $$_key = (string)$_val;
                break;
            case 'name':
                $$_key = (string)$_val;
                break;
            case 'value':
                $$_key = (string)$_val;
                break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }
    
	if(empty($id)){
	    if(!empty($_SESSION['cmpt_id'])){
	        $_SESSION['cmpt_id']++;
	    }else{
	        $_SESSION['cmpt_id'] = 1;
	    }
	    $cmpt_id = 'time-'.$_SESSION['cmpt_id'];
	}else{
		$cmpt_id = $id;
	}	
	
    $rs = '<input id="html-time-'.$cmpt_id.'" type="text" name="'.$name.'"></input>';
    
    ?>

<script>
$(function(){
    $('#html-time-<?php echo $cmpt_id;?>').datetimebox({
    	showSeconds:false,
    	okText:'确定',
    	currentText:'今天',
    	closeText:'关闭',
    	formatter:function(date){
    		
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate();
			var h = date.getHours();
			var i = date.getMinutes();
			
			return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d)+' '+(h<10?('0'+h):h)+':'+(i<10?('0'+i):i);
			
    	},
    	
    	parser:function(s){
    		
			if (!s) return new Date();
    		var spt_s = s.split(' ');
    		var s_date_part = spt_s[0];
    		var s_time_part = spt_s[spt_s.length-1];
    		
			var ss = (s_date_part.split('-'));
			var y = parseInt(ss[0],10);
			var m = parseInt(ss[1],10);
			var d = parseInt(ss[2],10);
			
			ss = (s_time_part.split(':'));
			var h = parseInt(ss[0],10);
			var i = parseInt(ss[1],10);
			
			if (!isNaN(y) && !isNaN(m) && !isNaN(d) && !isNaN(h) && !isNaN(i)){
				return new Date(y,m-1,d,h,i);
			} else {
				return new Date();
			}    		
			
    	}
    });
});
</script>

<?php     
    return $rs;
}

function smarty_function_html_html_input($params,&$smarty){
    	
    $id = '';
    $value = '';
    $extra = '';
    $required = '';
    $vtype = '';
    $extra = '';
	$width = 100;
	
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'id':
                $$_key = (string)$_val;
            break;
            case 'required':
                $$_key = (string)$_val;
            break;
            case 'vtype':
                $$_key = (string)$_val;
            break;
            case 'value':
                $$_key = (string)$_val;
            break;
            case 'width':
                $$_key = intval($_val);
            break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
            break;
        }
    }

	if(empty($id)){
	    if(!empty($_SESSION['cmpt_id'])){
	        $_SESSION['cmpt_id']++;
	    }else{
	        $_SESSION['cmpt_id'] = 1;
	    }
	    $cmpt_id = 'text-'.$_SESSION['cmpt_id'];
	}else{
		$cmpt_id = $id;
	}	

	$width = $width == 0 ? 200 : $width;	
    $rs = '<input id="'.$cmpt_id.'" type="input" '.$extra.' value="'.$value.'" style="width:'.$width.'px;"/>';
	
	$o = array();
	if(!empty($required) && $required == 'Y') $o['required'] = true;
	if(!empty($vtype)) $o['validType'] = $vtype;
	
?>
<script>$(function(){
    $('#<?php echo $cmpt_id;?>').validatebox(<?php echo json_encode($o);?>);	
});</script>
<?php    
    return $rs;
}

function smarty_function_html_html_txt($params,&$smarty){
    $value = '';
    $extra = '';
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'value':
                $$_key = (string)$_val;
                break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }
    
    
    $rs = '<textarea '.$extra.'>'.$value.'</textarea>';
    
    return $rs;
}

function smarty_function_html_html_html($params,&$smarty){
    
    $id = null;
    $value = '';
    $width = 700;
    $height = 400;

    $extra = '';
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'id':
                $$_key = (string)$_val;
                break;
            case 'value':
                $$_key = (string)$_val;
                break;
            case 'width':
                $$_key = intval($_val);
                break;
            case 'height':
                $$_key = intval($_val);
                break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }
    
	if(empty($id)){
	    if(!empty($_SESSION['cmpt_id'])){
	        $_SESSION['cmpt_id']++;
	    }else{
	        $_SESSION['cmpt_id'] = 1;
	    }
	    $cmpt_id = 'text-'.$_SESSION['cmpt_id'];
	}else{
		$cmpt_id = $id;
	}	
	
    $rs = '<textarea id="'.$cmpt_id.'" '.$extra.'>'.$value.'</textarea>';
	
	$o = array();
	$o['width'] = $width;
	$o['height'] = $height;
	$o['upImgUrl'] = "/newsadmin/xhupload";
	$o['upImgExt'] = "jpg,jpeg,gif,png";
	
?>
<script type="text/javascript"> $(function(){
	$('#<?php echo $cmpt_id;?>').xheditor(<?php echo json_encode($o);?>);
}); </script>

<?php     
    return $rs;
}

function smarty_function_html_html_select($params,&$smarty){
    
    $opt = array();
    $label = '';

    $extra = '';
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'opt':
                $$_key = (array)$_val;
                break;
            case 'label':
                $$_key = (string)$_val;
                break;
            case 'value':
                $$_key = $_val;
                break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }
    
    $rs = '<select  '.$extra.'>';
    
    if(!empty($label)){
        $rs .= '<option value="">'.$label.'</option>';
    }
    
    foreach($opt as $k=>$v){
        $selected = '';
        if(isset($value) && $k == $value){
            $selected = ' selected="selected" ';
        }
        $rs .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
    }
    
    $rs .= '</select>';
    return $rs;    
    
}

function smarty_function_html_html_radio($params,&$smarty){
    
    $opt = array();
    $label = '';
    $name = '';
    $value = '';
    $extra = '';
    
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'opt':
                $$_key = (array)$_val;
                break;
            case 'label':
                $$_key = (string)$_val;
                break;
            case 'name':
                $$_key = (string)$_val;
                break;
            case 'value':
                $$_key = $_val;
                break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
            break;
        }
    }
    
    $rs = '<span class="html_checkbox">';
    
    foreach($opt as $k=>$v){
        $selected = '';
        if(isset($value) && $k == $value){
            $selected = ' checked="checked" ';
        }
        $rs .= '<label><input name="'.$name.'" type="radio" '.$extra.' value="'.$k.'" '.$selected.'/><span style="position:relative;top:-2px;">'.$v.'</span></label>';
    }
    
    $rs .= '</span>';
    return $rs;    
    
}

function smarty_function_html_html_mselect($params,&$smarty){

    $rs = '';
        
    $id = '';
    $name = '';
    $label = '';
    $mdl = '';
    $callback = '';
    $value = '';
    $tplid = '';
    $remain = '';

    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'value':
                if(is_array($_val)){
                    $_val = implode(',', $_val);
                    $$_key = $_val;
                }else{
                    $$_key = trim($_val,',');
                }        
            break;
            default:
                $$_key = (string)$_val;
                $$_key = $_val;
            break;
        }
    }

    if(empty($id)) return '';
    if(empty($mdl)) return '';

    if(!empty($remain) && $remain == 'false'){
        $remain = false;
    }else{
        $remain = true;
    }
    
    $rs .= '<div id="wrp-mslt-'.$id.'" class="mslt">';    
    $rs .= '<input id="mslt-callback-'.$id.'" type="hidden" value="'.$callback.'"/>';
    $rs .= '<input id="mslt-tplid-'.$id.'" type="hidden" value="'.$tplid.'"/>';
    $rs .= $label.' <a href="javascript:void(0);" onclick="selector_open(\''.$id.'\',\''.$remain.'\');">[选择]</a>';
    $rs .= '<input id="hdn-mslt-ids-'.$id.'" name="'.$name.'" class="hdn-ids" type="hidden" value="'.$value.'"/>';
    $rs .= '<div id="mslt-tpl-'.$id.'" class="wrp-mslt-tpl"></div>';
    $rs .= '</div>';    
    
?>
<script>


function selector_open(mslt_id,remain){

   	var grid_ids = remain ? $('#hdn-mslt-ids-'+mslt_id).val() : '';

	$.cookie("grid_ids",grid_ids,{path:"/"});
	$.cookie("mslt_id",mslt_id,{path:"/"});
	
    winopen('/admin/component/brandslt/mdl/<?php echo $mdl;?>');	
    
}

function msltback(mslt_id,grid_ids,msg){

	grid_ids = grid_ids.trim(',');
	$('#hdn-mslt-ids-'+mslt_id).val(grid_ids);
    var callback = $('#mslt-callback-'+mslt_id).val();
    var tplid = $('#mslt-tplid-'+mslt_id).val();

    if(tplid == ''){
        tplid = 'mslt-tpl-'+mslt_id;
    }
    
	if(callback != ''){
		if(!window[callback](msg,'hdn-mslt-ids-'+mslt_id,grid_ids,tplid,mslt_id)) return false;
	}
	
	var arr_tpl = new Array();

	arr_tpl.push('<table width="100%" cellspacing="0" class="mslt-tpl">');
	$.each(msg.data, function(i, n){
	    
	    var hdn_id = '<input class="hdn_id" type="hidden" value="'+n[msg.pk]+'"/>';
		
    	arr_tpl.push('<tr>');
    	arr_tpl.push('<td>'+hdn_id+n[msg.title]+'</td>');

        var btn_a = '<a href="javascript:void(0);" onclick="mslt_del_item(this,\''+mslt_id+'\');"> [ - ] </a>';
    	
    	arr_tpl.push('<td width="30" align="center">'+btn_a+'</td>');
    	arr_tpl.push('</tr>');
    	
    });
	arr_tpl.push('</table>');
	
	var tpl = arr_tpl.join('');
    
	$('#'+tplid).empty().append(tpl);
	
	return true;
}

function mslt_del_item(a,mslt_id){
	
	$(a).parent().parent().remove();

	var arr_g_id = new Array();

    $('#<?php echo $tplid;?>').find('.hdn_id').each(function(i,n){
    	arr_g_id.push($(n).val());
    });
    
    var grid_ids = arr_g_id.join(',');
    
    $('#hdn-mslt-ids-'+mslt_id).val(grid_ids);
    	
}

<?php if(!empty($value)):?>
$.ajax({
	   type: "GET",
	   url: "/admin/component/get-slt-data/mdl/<?php echo $mdl;?>",
	   data: "ids=<?php echo $value;?>",
	   dataType:'json',
	   success: function(msg){
	   		msltback('<?php echo $id;?>','<?php echo $value;?>',msg);
	   }
});
<?php endif;?>

</script>
<?php     
    
    return $rs;
}

function smarty_function_html_html_complent_id(&$comid=1){
    return $comid++;
}


function smarty_function_html_html_pic($params,&$smarty){
    $rs = '';

    $id = '';
    $uploader = '/admin/upload/uploadify';
    $limit = 200;
	$extra = '';
	$value = '';

    foreach($params as $_key => $_val) {
        switch($_key) {
        	case 'id':
                $$_key = (string)$_val;
            break;
        	case 'limit':
                $$_key = intval($_val);
            break;
        	case 'uploader':
                $$_key = (string)$_val;
            break;
        	case 'value':
                $$_key = (string)$_val;
            break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
            break;
        }
    }
	
	if(empty($id)){
	    if(!empty($_SESSION['cmpt_id'])){
	        $_SESSION['cmpt_id']++;
	    }else{
	        $_SESSION['cmpt_id'] = 1;
	    }
	    $cmpt_id = 'tmslt-'.$_SESSION['cmpt_id'];
	}else{
		$cmpt_id = $id;
	}	
	
	$id = $cmpt_id;
	
    $rs .= '<div class="wrp-pic" style="display:inline;position:relative;">';
    $rs .= '<input type="file" id="pic-'.$id.'"/>';
    
    $default_img = !empty($value) ? '<img width="98" height="98" src="/'.$value.'"/>' : '';
    
    $rs .= '<div id="pic-thumb-'.$id.'" class="pic-thumb">'.$default_img.'</div>';
    $rs .= '<input type="hidden" id="pic-hidden-'.$id.'" '.$extra.' value="'.$value.'"/>';
    $rs .= '</div>';
    ?>
<script>$(function(){
    $('#pic-<?php echo $id;?>').uploadify({
        swf      : '/Public/js/uploadify/uploadify.swf',
        uploader : '<?php echo $uploader;?>',
        multi: false,
        width:100,
        height:20,
        fileTypeExts:'*.gif; *.jpg; *.png',
        formData:{ssid:'<?php echo Zend_Session::getId();?>'},
        transparent: false,
        fileSizeLimit: <?php echo $limit;?>,
        queueID  : 'some_file_queue-<?php echo $id;?>',
        onUploadSuccess : function(file, data, response){
        	data = eval('('+data+')');
        	$('#pic-hidden-<?php echo $id;?>').val(data.path);
        	$('#pic-thumb-<?php echo $id;?>').empty().append('<img width="98" height="98" src="/'+data.path+'"/>');
        },
		buttonText:'UP'
    });
});</script>
<style>
.uploadify-button {
    border: 2px solid #808080;
    border-radius: 0px 0px 0px 0px;
}
.pic-thumb{
	width:100px;
	height:100px;
	padding-top:2px;
	padding-left:2px;
	border:1px solid #eee;
}
</style>
<?php     
    return $rs;

}



function smarty_function_html_html_wdt($params,&$smarty){

    $rs = '';
    $id = '';
    foreach($params as $_key => $_val) {
        switch($_key) {
            default:
                $$_key = (string)$_val;
                $$_key = $_val;
            break;
        }
    }
    
    $file_wdt = SHOP_TPL_ROOT.'widget/'.$id.'.tpl';

    if(file_exists($file_wdt)){
        require SHOP_TPL_ROOT.'widget/'.$id.'.tpl';
        return;
    }
    return '<div style="color:red;">ID为'.$id.'的挂件不存在！</div>';

}

function smarty_function_html_html_img($params,&$smarty){

	$xml = new Custom_Config_Xml();
    $config=$xml->getConfig();
	$img_base_url = $config -> view -> imgBaseUrl;
	
	$dir_tmp_img = SYSROOT.'/www/tmpimg'; 
	
    $rs = '';

    $src = '';
    $lazy = 'N';
    $w = '';
    $h = '';
	
    foreach($params as $_key => $_val) {
        switch($_key) {
        	case 'src':
                $$_key = (string)$_val;
                break;
        	case 'lazy':
                $$_key = (string)$_val;
                break;
            case 'w':
                $$_key = intval($_val);
                break;
            case 'h':
                $$_key = intval($_val);
                break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
            break;
        }
    }

	$is_lazy = false;
	if(!empty($lazy) && $lazy == 'Y'){
		$is_lazy = true;
	}
	
	$src = trim($src,'/');
	
	$exp_src = explode('.', $src);
	$ext_name = $exp_src[count($exp_src)-1];


	if(!in_array(strtoupper($ext_name), array('JPG','JPEG'))){
		$img_src = $is_lazy == true ? '_src="/'.$src.'"' : 'src="/'.$src.'"'; 
		$rs = '<img '.$extra.' '.$img_src.'/>';
		return $rs;
	}

	unset($exp_src[count($exp_src)-1]);
	$t = implode('', $exp_src);

	$exp_t = explode('/', $t);
	
	$tmp_img_src = $exp_t[count($exp_t)-1].'_'.$w.'_'.$h.'.'.$ext_name;
	if(!@file_exists($dir_tmp_img.'/'.$tmp_img_src)){
		if(!@file_exists(SYSROOT.'/www/'.$src)) return '<img src="" '.$extra.'/>';
		$thumb = Custom_Model_Thumb::create(SYSROOT.'/www/'.$src);
	    $thumb->adaptiveResize($w, $h);
		$thumb->save($dir_tmp_img.'/'.$tmp_img_src);
	}

	$img_src = $is_lazy == true ? '_src="/tmpimg/'.$tmp_img_src.'"' : 'src="/tmpimg/'.$tmp_img_src.'"'; 
	
	$rs = '<img '.$img_src.' '.$extra.'/>';
	
	return $rs; 
	
}

function smarty_function_html_html_tmslt($params,&$smarty){
    
    $rs = '';
    $label = '';
    $value = '';
    $mdl = '';
    $pk = '';
    $title = '';
    $pid = '';
    $disabled = false;
    $extra = '';
    $id = '';
    $required  = '';
    
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'id':
                $$_key = (string)$_val;
                break;
            case 'required':
                $$_key = (string)$_val;
                break;
            case 'label':
                $$_key = (string)$_val;
                break;
            case 'mdl':
                $$_key = (string)$_val;
                break;
            case 'pk':
                $$_key = (string)$_val;
                break;
            case 'pid':
                $$_key = (string)$_val;
                break;
            case 'title':
                $$_key = (string)$_val;
                break;
            case 'value':
                $$_key = $_val;
                break;
            case 'disabled':
                $disabled = $_val == 'Y' ? true : false;
                break;
            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_options: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

	if(empty($id)){
	    if(!empty($_SESSION['cmpt_id'])){
	        $_SESSION['cmpt_id']++;
	    }else{
	        $_SESSION['cmpt_id'] = 1;
	    }
	    $cmpt_id = 'tmslt-'.$_SESSION['cmpt_id'];
	}else{
		$cmpt_id = $id;
	}	
	
    if($value == '0') $value = '';
    
    $mdl_cm = new Custom_Model_Dbadv();
    $list = $mdl_cm->getAll('shop_seo_cat',array(),''.$pk.','.$pid.','.$title);
    
    $combo_list = array();
    foreach ($list as $k=>$v){
        $item = array();
        $item['id'] = $v[$pk];
        $item['text'] = $v[$title];
        $item['parent_id'] = $v[$pid];
        $combo_list[] = $item;
    }
    $combo_tree = Custom_Model_Tools::build_tree($combo_list, 'id', 'parent_id', 'children');
    
    $rs .= '<input id="'.$cmpt_id.'" value="'.$value.'" '.$extra.'>';
	$o = array();
	$o['data'] = $combo_tree;
	$o['disabled'] = $disabled;
	if(!empty($required) && $required == 'Y') $o['required'] = true;
	
?>
<script>$(function(){
    $('#<?php echo $cmpt_id;?>').combotree(<?php echo json_encode($o);?>);
});</script>
<?php     
    return $rs;    
}




