{{if $template.type}}
    {{assign var="type" value=$template.type}}
{{else}}
    {{assign var="type" value="0"}}
{{/if}}
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{$title}}</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=index}}')">邮件模板列表</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="30%">邮件模板名称 * </td>
<td><input type="text" name="name" id="name" size="20" maxlength="40" value="{{$template.name}}" msg="请填写邮件模板名称" class="required" onchange="ajax_check('{{url param.action=check}}','name')" /></td>
</tr>
<tr>
<td width="30%">邮件主题 * </td>
<td><input type="text" name="title" size="35" maxlength="80" value="{{$template.title}}" msg="请填写邮件主题" class="required" /></td>
</tr>
<tr>
<td width="30%">邮件类型 </td>
<td>{{html_radios name="type" options=$typeOptions checked=$type separator=""}}</td>
</tr>
<tr>
<td>邮件模板内容</td>
<td><textarea style="width: 500px;height: 160px" name="value">{{$template.value}}</textarea></td>
</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>