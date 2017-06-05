<?php /* Smarty version 2.6.19, created on 2014-08-18 13:41:50
         compiled from ad/add-ad.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ad/add-ad.tpl', 62, false),)), $this); ?>
<script src="/scripts/my97/WdatePicker.js" type="text/javascript" language="javascript"></script>
<script src="/haitaoadmin/scripts/fileuploader.js"></script>
<style>
.btn_submit:hover {
    background: none repeat scroll 0 0 #3486C1;
}
.upload_btn {
    background: none repeat scroll 0 0 #F1F0F0;
    border: 1px solid #C4C4C4;
    border-radius: 2px 2px 2px 2px;
    color: #333333;
    cursor: pointer;
    display: inline-block;
    float: left;
    height: 22px;
    text-align: center;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
    width: 60px;
}
.upload_btn span {
	position:absolute;
    float: right;
    height: 22px;
    display:block;
	background:#F1F0F0;
	color:#000;
    line-height: 22px;
    right: 0;
    top: 0;
    width: 60px;
}
.mr10 {
    margin-right: 10px;
}
.fl {
    float: left;
}
</style>
<div class="title">添加广告</div>
<form  name="myForm" id="myForm"  action="/admin/ad/add-ad" method="post" onsubmit="return check_post()">
<div class="content">
<table width="100%" cellspacing="1" cellpadding="2" class="table_form">
<tbody>
<tr>
     <th width="80">广告名称 :</th>
     <td><input type="text" size="40" class="input-text" id="name" name="name"><div id="nameTip" class="onShow">请填写广告名称</div></td>
</tr>

    <tr>
    <th>广告链接 :</th>
     <td><input type="text" size="40" class="input-text" name="url"></td>
   </tr>
    
    <tr><th>广告位 :</th><td><select id="board_id" name="board_id">
    <?php $_from = $this->_tpl_vars['adBoard']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <option allowtype="<?php echo $this->_tpl_vars['adtpl'][$this->_tpl_vars['item']['tpl']]['allow_type']; ?>
" tpl="<?php echo $this->_tpl_vars['item']['tpl']; ?>
" value="<?php echo $this->_tpl_vars['item']['id']; ?>
"><?php echo $this->_tpl_vars['item']['name']; ?>
（<?php echo $this->_tpl_vars['item']['width']; ?>
*<?php echo $this->_tpl_vars['item']['height']; ?>
）</option>
    <?php endforeach; endif; unset($_from); ?>
    </select></td></tr>
    
   <tr><th>广告类型 :</th><td>
   <select id="type" name="type">
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['adType']), $this);?>

   </td>
   </tr>
  
  <tr class="bill_media" id="ad_image" style="display: table-row;">
  <th>广告图片 :</th>
  <td><input type="text" size="30" class="input-text fl mr10 tips" id="J_img" name="image">
  <div class="upload_btn" id="J_upload_img" style="position: relative; overflow: hidden; direction: ltr;"><span>上传</span>
  <input type="file" name="file" style=" font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;"></div></td></tr>
  <tr style="display:none;" class="bill_media" id="ad_code">
  <th>广告代码 :</th><td><textarea id="code" name="code" cols="50" rows="3"></textarea></td></tr>
 
 
  <tr style="display:none;" class="bill_media" id="ad_flash">
  <th>广告动画 :</th>
  <td><input type="text" size="30" class="input-text fl mr10" id="J_flash" name="flash">
  <div class="upload_btn" id="J_upload_flash" style="position: relative; overflow: hidden; direction: ltr;"><span>上传</span>
  <input type="file" name="file" style="font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;"></div></td></tr>
  
  
  <tr style="display:none;" class="bill_media" id="ad_text">
  <th>文字内容 :</th><td><textarea id="text" name="text" cols="50" rows="3"></textarea></td></tr>
 
  <tr class="bill_media" id="moreimg" style="display:none;">
    <th>对应小图</th>
    <td>
    
    <p>
    <div><span class="fl">1. 链接：</span> <input type="text" size="30" class="input-text fl mr10" name="extConfig[0][link]"></div>
    <div><span class="fl"> 图片：</span><input type="text" size="30" class="input-text fl mr10 tips" id="J_more_1" name="extConfig[0][pic]"></div> 
   <div class="upload_btn" id="J_upload_more_1" style="position: relative; overflow: hidden; direction: ltr;"><span>上传</span>
   <input type="file" name="file" style=" font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;"></div>
   </p>
   
     <p style="clear:both;padding-top:10px;">
    <div><span class="fl">2. 链接：</span> <input type="text" size="30" class="input-text fl mr10"  name="extConfig[1][link]"></div>
    <div><span class="fl"> 图片：</span><input type="text" size="30" class="input-text fl mr10 tips" id="J_more_2" name="extConfig[1][pic]"></div> 
   <div class="upload_btn" id="J_upload_more_2" style="position: relative; overflow: hidden; direction: ltr;"><span>上传</span>
   <input type="file" name="file" style=" font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;"></div>
   </p>
      
    <p style="clear:both;padding-top:10px;">
    <div><span class="fl">3. 链接：</span> <input type="text" size="30" class="input-text fl mr10"  name="extConfig[2][link]"></div>
    <div><span class="fl"> 图片：</span><input type="text" size="30" class="input-text fl mr10 tips" id="J_more_3" name="extConfig[2][pic]"></div> 
   <div class="upload_btn" id="J_upload_more_3" style="position: relative; overflow: hidden; direction: ltr;"><span>上传</span>
   <input type="file" name="file" style=" font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;"></div>
   </p>
    </td>
 </tr>
  
 
  <tr><th>扩展图片 :</th><td><input type="text" size="30" class="input-text fl mr10 tips" id="J_extimg" name="extimg">
  <div class="upload_btn" id="J_upload_extimg" style="position: relative; overflow: hidden; direction: ltr;"><span>上传</span>
  <input type="file" name="file" style=" font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;"></div></td></tr>
  <tr><th>扩展字段值 :</th><td><input type="text" class="input-text fl mr10" name="extval"></td>
  </tr>
  
  <tr><th>广告描述 :</th><td><input type="text" size="30" class="input-text fl mr10" name="desc"></td></tr>
  <tr><th>广告时间 :</th><td><input type="text" size="20" class="Wdate"   onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  id="start_time" name="start_time"> - 
  <input type="text" size="20" class="Wdate"   onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="end_time" name="end_time"></td></tr>
  <tr>
  
  <th>是否启用 :</th>
   <td><label><input type="radio" checked="" value="1" name="status">是</label>&nbsp;&nbsp;
   <label><input type="radio" value="0" name="status">否</label></td></tr>
   </tbody>
 </table>
</div>

<div class="submit">
<input type="hidden" value="submitted" name="submitted" />
<input type="submit" value="确定" id="dosubmit" name="dosubmit"> 
<input type="reset" value="重置" name="reset"></div>
</form>
<script>

var type = $('board_id').getSelected().get('allowtype');
if($('board_id').getSelected().get('tpl') == 'index-focus')
{
	$("moreimg").setStyle('display', '');
}else{
	$("moreimg").setStyle('display', 'none');
}
$('type').value = type;
switchAd(type);

$('board_id').addEvent('change',function(){
	var type = $('board_id').getSelected().get('allowtype');
	if($('board_id').getSelected().get('tpl') == 'index-focus')
	{
		$("moreimg").setStyle('display', '');
	}else{
		$("moreimg").setStyle('display', 'none');
	}
	$('type').value = type;
	switchAd(type);
});

$('type').addEvent('change',function(){	
	switchAd(this.value);
});

function check_post()
{
	 if($("name").value	== '')
	 {
	    alert("请输入广告位名称！");
	    $("name").focus();
	    return false;
	 }
	 return true;
}

function switchAd(type)
{   
	$$("tr[id^=ad_]").setStyle('display', 'none');
	$("ad_"+type).setStyle('display', '');
}




function setOptions(index)
{
	var options = {
	    allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
	    button: document.getElementById('J_upload_more_'+index),
	    multiple: false,
	    action: "/admin/ad/ajax-upload-img/type/moreimg_"+index,
	    inputName: 'moreimg_'+index,
	    forceMultipart: true, //用$_FILES
	    messages: {
	        typeError: "文件类型错误",
	        sizeError: "文件太大",
	        minSizeError: "文件尺寸太小",
	        emptyError: "文件为空",
	        noFilesError: "上传文件失败",
	        onLeave: "上传超时"
	    },
	    showMessage: function(message){
	        alert(message);
	    },
	    onSubmit: function(id, fileName){   
	        $('J_upload_more_'+index).addClass('btn_disabled').getElements('span').set("text","上传中……");
	    },
	    onComplete: function(id, fileName, result){
	        $('J_upload_more_'+index).removeClass('btn_disabled').getElements('span').set("text","上传成功");
	        if(result.status == '1'){
	            $('J_more_'+index).set('value',result.data);
	        } else {
	        	alert(result.msg); 
	        }
	    }
	}
	return  options;
}

var img_uploader_1 =  new qq.FileUploaderBasic(setOptions(1));
var img_uploader_2 =  new qq.FileUploaderBasic(setOptions(2));
var img_uploader_3 =  new qq.FileUploaderBasic(setOptions(3));

//上传图片
var img_uploader = new qq.FileUploaderBasic({
    allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
    button: document.getElementById('J_upload_img'),
    multiple: false,
    action: "/admin/ad/ajax-upload-img/type/img",
    inputName: 'img',
    forceMultipart: true, //用$_FILES
    messages: {
        typeError: "文件类型错误",
        sizeError: "文件太大",
        minSizeError: "文件尺寸太小",
        emptyError: "文件为空",
        noFilesError: "上传文件失败",
        onLeave: "上传超时"
    },
    showMessage: function(message){
        alert(message);
    },
    onSubmit: function(id, fileName){   
        $('J_upload_img').addClass('btn_disabled').getElements('span').set("text","上传中……");
    },
    onComplete: function(id, fileName, result){
        $('J_upload_img').removeClass('btn_disabled').getElements('span').set("text","上传成功");
        if(result.status == '1'){
            $('J_img').set('value',result.data);
        } else {
        	alert(result.msg); 
        }
    }
});

var extimg_uploader = new qq.FileUploaderBasic({
    allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
    button: document.getElementById('J_upload_extimg'),
    multiple: false,
    action: "/admin/ad/ajax-upload-img/type/extimg",
    inputName: 'extimg',
    forceMultipart: true, //用$_FILES
    messages: {
    	 typeError: "文件类型错误",
         sizeError: "文件太大",
         minSizeError: "文件尺寸太小",
         emptyError: "文件为空",
         noFilesError: "上传文件失败",
         onLeave: "上传超时"
    },
    showMessage: function(message){
    	 alert(message);
    },
    onSubmit: function(id, fileName){
        $('J_upload_extimg').addClass('btn_disabled').getElements('span').set("text","上传中……");
    },
    onComplete: function(id, fileName, result){
        $('J_upload_extimg').removeClass('btn_disabled').getElements('span').set("text","上传成功");
        if(result.status == '1'){
            $('J_extimg').set('value',result.data);
        } else {
        	alert(result.msg); 
        }
    }
});

var flash_uploader = new qq.FileUploaderBasic({
    allowedExtensions: ['swf'],
    button: document.getElementById('J_upload_flash'),
    multiple: false,
    action: "/admin/ad/ajax-upload-img/type/flash",
    inputName: 'flash',
    forceMultipart: true, //用$_FILES
    messages: {
    	 typeError: "文件类型错误",
         sizeError: "文件太大",
         minSizeError: "文件尺寸太小",
         emptyError: "文件为空",
         noFilesError: "上传文件失败",
         onLeave: "上传超时"
    },
    showMessage: function(message){
    	 alert(message);
    },
    onSubmit: function(id, fileName){
        $('J_upload_flash').addClass('btn_disabled').getElements('span').set("text","上传中……");
    },
    onComplete: function(id, fileName, result){
        $('J_upload_flash').removeClass('btn_disabled').getElements('span').set("text","上传成功");
        if(result.status == '1'){
            $('J_flash').set('value',result.data);
        } else {
        	alert(result.msg); 
        }
    }
});


tip('.tips');
function tip(els){
	var el1=new Element('div',{'class':'bubble'});
	el1.setStyle('opacity',0);
	var el2=new Element('div',{'class':'bubbleLeft'});
	var el3=new Element('div',{'class':'bubbleRight'});
	var el4=new Element('span',{'id':'bubbleFont'});
	el4.inject(el3);
	el3.inject(el2);
	el2.inject(el1);
	el1.inject($$('body')[0]);
            //以上步骤生成气泡所需html代码
	$$(els).each(function(e){//为所有class属性包含els的元素初始化
		e.addEvent('mouseenter',function(event){//鼠标进入时触发
			mx=event.page.x+20;//获取浏览器x坐标并且偏移20px
			my=event.page.y-40;//同理
			note=this.get('value');//提示的信息由元素的title属性提供
			if(!note) return false;
			$('bubbleFont').set('html','<img src="'+note+'" />');
			el1.setStyles({
				'left':mx,
				'top':my
			});
			el1.tween('opacity',1);//变换透明度到1
		});
		e.addEvent('mousemove',function(event){//鼠标移动时触发
			mx=event.page.x+20;
			my=event.page.y-40;
			el1.setStyles({
				'left':mx,
				'top':my
			});
		});
		e.addEvent('mouseleave',function(event){//鼠标离开时触发
			el1.tween('opacity',0);//变换透明度到0
		});
	});
}
</script>