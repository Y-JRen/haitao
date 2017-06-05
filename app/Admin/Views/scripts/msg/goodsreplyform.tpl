<form name="myForm" id="myForm" action="{{url param.action=goodsreply}}" method="post">
<input type='hidden' name='id' value='{{$msg.msg_id}}'>
<div class="title">留言回复</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>用户</strong> * </td>
      <td width="88%"><input type="text" name="user_name" style="width:150px;" msg="请填写用户" class="required" value="{{$msg.user_name}}"></td>
    </tr>
    <tr>
      <td width="12%"><strong>留言时间</strong> * </td>
      <td width="88%"><input type="text" name="add_time" style="width:150px;" msg="请填写留言时间" class="required" value="{{$msg.add_time}}"></td>
    </tr>
    <tr>
      <td width="12%"><strong>留言内容</strong> * </td>
      <td width="88%"><textarea name="content" rows="3" cols="39" style="width:450px; height:60px;" msg="请填写留言内容" class="required">{{$msg.content}}</textarea></td>
    </tr>
    <tr>
      <td width="12%"><strong>回复内容</strong></td>
      <td><textarea name="reply" rows="3" cols="39" style="width:450px; height:60px;">{{$msg.reply}}</textarea></td>
    </tr>
    
   {{if $type eq 1}}
    <tr>
      <td><strong>外观</strong> * </td>
      <td><input type="text" name="cnt1" size="4" id="cnt1" value="{{$msg.cnt1}}">颗星</td>
    </tr>
    <tr>
      <td><strong>口感</strong> * </td>
      <td><input type="text" name="cnt2" size="4" id="cnt2" value="{{$msg.cnt2}}">颗星</td>
    </tr>
    <tr>
      <td><strong>审核意见</strong> * </td>
      <td>
	   <input type="radio" name="status" value="1" {{if $msg.status==1}}checked{{/if}}/> 已审核
	   <input type="radio" name="status" value="2" {{if $msg.status==2}}checked{{/if}}/> 已拒绝
	  </td>
    </tr>
    <tr>
      <td><strong>是否为经典评论</strong> * </td>
      <td>
	   <input type="radio" name="is_hot" value="1" {{if $msg.is_hot==1}}checked{{/if}}/> 是
	   <input type="radio" name="is_hot" value="0" {{if $msg.is_hot==0}}checked{{/if}}/> 否
	  </td>
    </tr>
    {{else}}
    <tr>
      <td><strong>回复意见</strong> * </td>
      <td>
	   <input type="radio" name="status" value="1" {{if $msg.status==1}}checked{{/if}}/> 审核显示
	   <input type="radio" name="status" value="2" {{if $msg.status==2}}checked{{/if}}/> 拒绝显示
	  </td>
    </tr>
    <tr>
      <td><strong>回复专家</strong> * </td>
      <td>
      请选择专家：<select id="dietitian"   name="dietitian">
     <option value="0" selected>请选择</option>
    {{foreach from=$dietitian key=key item=dietitian}}
        <option value="{{$key}}" > {{$dietitian}} </option>
    {{/foreach}}
    </select>  
	  </td>
    </tr>
  {{/if}}
  
  
  
  
  
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>