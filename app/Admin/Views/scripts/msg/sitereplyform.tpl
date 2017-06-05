<form name="myForm" id="myForm" action="{{url param.action=sitereply}}" method="post">
<input type='hidden' name='id' value='{{$msg.msg_id}}'>
<div class="title">留言回复</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="10%"><strong>留言内容</strong> * </td>
      <td><textarea name="content" rows="3" cols="39" style="width:450px; height:60px;" msg="请填写留言内容" class="required">{{$msg.content}}</textarea></td>
    </tr>
    <tr>
      <td width="10%"><strong>回复内容</strong></td>
      <td><textarea name="reply" rows="3" cols="39" style="width:450px; height:60px;">{{$msg.reply}}</textarea></td>
    </tr>
    <tr> 
      <td><strong>审核意见</strong> * </td>
      <td>
	   <input type="radio" name="status" value="1" {{if $msg.status==1}}checked{{/if}}/> 已审核
	   <input type="radio" name="status" value="2" {{if $msg.status==2}}checked{{/if}}/> 已拒绝
	  </td>
    </tr>
    <tr> 
      <td><strong>审核意见</strong> * </td>
      <td>
	   <input type="radio" name="is_hot" value="1" {{if $msg.is_hot==1}}checked{{/if}}/> 热门
	   <input type="radio" name="is_hot" value="0" {{if $msg.is_hot==0}}checked{{/if}}/> 非热门
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>