<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm1" id="myForm1">
<input type='hidden' name='goods_id' value='{{$param.goods_id}}'>
<input type='hidden' name='goods_name' value='{{$param.goods_name}}'>
<div class="title">添加评论</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="15%"><strong>评论标题</strong> * </td>
      <td><input type="text" name="title" size="50" id="title"></td>
    </tr>
    <tr>
      <td><strong>评论内容</strong> * </td>
      <td><textarea name="content" rows="3" cols="39" style="width:450px; height:60px;" msg="请填写留言内容" class="required" id="content">{{$msg.content}}</textarea></td>
    </tr>
    <tr>
      <td><strong>评论用户</strong> * </td>
      <td><input type="text" name="user_name" size="20" id="user_name"></td>
    </tr>
    <tr>
      <td><strong>外观</strong> * </td>
      <td><input type="text" name="cnt1" size="4" id="cnt1">颗星</td>
    </tr>
    <tr>
      <td><strong>口感</strong> * </td>
      <td><input type="text" name="cnt2" size="4" id="cnt2">颗星</td>
    </tr>
	
    <tr>
      <td width="12%"><strong>评论时间</strong> * </td>
      <td width="88%"><input type="text" name="add_time"  size="26"  value="{{$msg.add_time}}" class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" ></td>
    </tr>

    <tr>
      <td><strong>是否为经典评论</strong> * </td>
      <td>
	   <input type="radio" name="is_hot" value="1" checked/> 是
	   <input type="radio" name="is_hot" value="0"/> 否
	  </td>
    </tr>
		
	
</tbody>
</table>
</div>
<div class="submit"><input type="button" name="dosubmit1" id="dosubmit1" value="添加" onclick="dosubmit()"/>
<input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function dosubmit()
{
    if($('title').value.trim() == ''){alert('请填写评论标题');return false;}
    if($('content').value.trim() == ''){alert('请填写评论内容');return false;}
    if($('user_name').value.trim() == ''){alert('请填写评论用户');return false;}
    if($('cnt1').value.trim() == ''){alert('请填写外观');return false;}
    if($('cnt2').value.trim() == ''){alert('请填写舒适度');return false;}
    if(confirm('确认发布此评论吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}
</script>