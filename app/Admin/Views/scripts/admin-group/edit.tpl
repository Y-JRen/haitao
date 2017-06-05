<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{$title}}</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=index}}')">管理员组列表</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">管理员组名称 * </td>
<td><input type="text" name="group_name" size="20" maxlength="20" value="{{$group.group_name}}" msg="请填写管理员组名称" class="required" /></td>
</tr>
<tr>
<td>分仓</td>
<td>
<input type="radio" name="type" value="1" {{if $group.type}}checked{{/if}}>是
<input type="radio" name="type" value="0" {{if $action eq 'add' || !$group.type}}checked{{/if}}>否
</td>
</tr>
<tr>
<td>说明</td>
<td><textarea style="width: 400px;height: 50px" name="remark">{{$group.remark}}</textarea></td>
</tr>
<tr>
<td>权限</td>
<td colspan="3"><div class="tree_div" id="treeboxbox_tree" style="padding: 5px; width:98%; height: 400px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;">
    <table cellpadding="0" cellspacing="0" border="0" id="table">
    <tbody>
    {{foreach from=$menus item=data}}
    <tr id="menu_{{$data.menu_id}}">
        <td style="padding-left:{{$data.step*20}}px;{{if $data.step==1}}padding-top:20px;color:red{{/if}}">
        {{if $data.leaf}}<input type="checkbox" name="group_menu[{{$data.menu_id}}]" value="{{$data.menu_path}}" {{if $menu[$data.menu_id]}}checked{{/if}} onclick="selectChildAll(this,{{$data.menu_id}})">{{/if}}
        <b>{{$data.menu_title}}<b>
    {{if $data.leaf && $data.privilege}}
    <table style="margin-left:20px;border:1px solid #ccc;width:95%">
    <tr id="privilege_{{$data.menu_id}}">
        <td style="background:#f2f2f2;padding:3px">
        {{foreach from=$data.privilege item=p key=key}}
        <input type="checkbox" name="group_privilege[{{$data.menu_id}}][{{$p.privilege_id}}]" value="{{$p.privilege_id}}" {{if $privilege[$data.menu_id][$p.privilege_id]}}checked{{/if}}> {{$p.title}}&nbsp;&nbsp;&nbsp;&nbsp;
        {{/foreach}}
        </td>
    </tr>
    </table>
    {{/if}}
    </td></tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<br>
<input type="checkbox" value="" onclick="selectAll(this)">
全选/不选
</td>
</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function selectChildAll(obj, id)
{
	var div = 'privilege_'+id;
	var val = obj.checked;
	var checkbox = $(div).getElements('input[type=checkbox]');
	for(var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if(e.name != obj.name) {
			e.checked = obj.checked;
		}
	}
}
function selectAll(obj)
{
    var div = 'table';
	var val = obj.checked;
	var checkbox = $(div).getElements('input[type=checkbox]');
	for(var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if(e.name != obj.name) {
			e.checked = obj.checked;
		}
	}
}
</script>