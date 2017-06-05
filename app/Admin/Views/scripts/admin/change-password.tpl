<form name="myForm" id="myForm"  method="post"  action="{{url param.action=$action}}"  target="ifrmSubmit" >
<table cellpadding="0" cellspacing="0" border="0" width="100%"  class="table_form">
<tbody>
<tr>
	<td >管理员名称*</td>
	<td >{{$admin.admin_name}} </td>
	<td >真实姓名*</td>
	<td>{{$admin.real_name}}</td>
</tr>
<tr>
	<td>旧密码</td>
	<td colspan="3"><input type="password" name="old_password" size="15" maxlength="25" value=""  msg="请填写管理员密码" class="required" /></td>
</tr>
<tr>
	<td>管理员密码</td>
	<td><input type="password" name="password" size="15" maxlength="25" msg="请填写管理员密码" class="required" /></td>
	<td>重复密码</td>
	<td><input type="password" name="confirm_password" size="15" maxlength="25" msg="请填写重复密码" class="required equal"  /></td>
</tr>
</tbody>
</table>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
