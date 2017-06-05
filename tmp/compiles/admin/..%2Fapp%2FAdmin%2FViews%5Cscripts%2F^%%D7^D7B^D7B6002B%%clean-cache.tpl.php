<?php /* Smarty version 2.6.19, created on 2014-07-25 14:59:18
         compiled from index/clean-cache.tpl */ ?>
<div class="content">
    <div style="width:100%;text-align:center;height:20px;padding-top:5px;color:red;font-weight:700;">注意：清缓存请不要频繁操作</div>
      <table cellpadding="0" cellspacing="0" border="0" class="table_form">
        <tbody>
            <tr><td><b>商城快速清理指定的缓存</b></td></tr>
            <tr><td>
                <input type="button"  id="index"  onclick="cleanCache('index');" value="首页">
                <input type="button"  id="goods"  onclick="cleanCache('goods');" value="所有商品页">
                <input type="button"  id="helppage"  onclick="cleanCache('helppage');" value="帮助文章页面">
                <input type="button"  id="special"  onclick="cleanCache('special');" value="全部专题页">
				 <input type="button"  id="brand"  onclick="cleanCache('brand');" value="品牌缓存">

				
            </td></tr>
			 <tr><td><b><input type="button" id="all" onclick="cleanCache('all');" value="全部缓存"><font color="red">该操作请谨慎处理</font></b></td></tr>
           </tbody>
       </table>
     
       
</div>
<script language="javascript">
function cleanCache(type){
    if (type == 'all' && !confirm('确认清除 “全部缓存” 么？')) {
        return false;
    }    
	var data = 'type=' + type;
    $(type).disabled = true;
	new Request({
	    url: '/admin/index/clean-cache/',
	    method: 'post',
	    data: data,
	    onSuccess: function(data)
	    {
	    	alert(data);
	    }
	}).send();
}
</script>