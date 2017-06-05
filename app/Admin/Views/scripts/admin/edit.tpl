<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{$title}}</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=index}}')">管理员列表</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">管理员名称 * </td>
<td width="40%"><input type="text" name="admin_name" id="admin_name" msg="请填写管理员名称" class="required limitlen" min="4" max="15" size="15" maxlength="20" value="{{$admin.admin_name}}" {{if $action eq 'edit'}}readonly{{else}}onchange="ajax_check('{{url param.action=check}}','admin_name')"{{/if}} /><span id="tip_admin_name" class="errorMessage">请输入4-15个字符</span></td>
<td width="10%">真实姓名 * </td>
<td width="40%"><input type="text" name="real_name" msg="请填写真实姓名" class="required" size="15" maxlength="20" value="{{$admin.real_name}}" /></td>
</tr>
<tr>
<td>管理员密码{{if $action eq 'add'}} * {{/if}}</td>
<td><input type="password" name="password" size="15" maxlength="20" {{if $action eq 'add'}}msg="请填写管理员密码" class="required" {{/if}}/></td>
<td>重复密码{{if $action eq 'add'}} * {{/if}}</td>
<td><input type="password" name="confirm_password" size="15" maxlength="40" {{if $action eq 'add'}}msg="请填写重复密码" class="required equal" to="password" {{/if}}/>{{if $action eq 'edit'}} {{$changePassword}}{{/if}}</td>
</tr>
<tr>
<td>管理员组</td>
<td>
<select name="group_id" id="group_id" msg="请选择管理员组" class="required" onchange="getPrivilege(this.value)">
<option value="">请选择</option>
{{html_options options=$groupIds selected=$admin.group_id}}
</select>
</td>
<td width="10%"><span id="lid_area1">所属仓库</span></td>
<td width="40%">
<span id="lid_area2">
{{foreach from=$areaList item=areaName key=areaID name=data}}
<input type="radio" name="lid" value="{{$areaID}}" {{if $admin.lid eq $areaID || ($action eq 'add' && $smarty.foreach.data.iteration eq 1)}}checked{{/if}}>{{$areaName}}
{{/foreach}}
</span>
</td>
</tr>
<tr>
<td>Email</td>
<td colspan="3"><input type="text" name="email" size="25" maxlength="25" value="{{$admin.email}}"/></td>
</tr>
<tr>
<td>权限</td>
<td colspan="3" id="privilege">
{{if $action eq 'edit'}}
<div class="tree_div" id="treeboxbox_tree" style="padding: 5px; width:98%; height: 400px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;">
    <table cellpadding="0" cellspacing="0" border="0" id="table">
    <tbody>
    {{foreach from=$menus item=data}}
    <tr id="menu_{{$data.menu_id}}">
        <td style="padding-left:{{$data.step*20}}px;{{if $data.step==1}}padding-top:20px;color:red{{/if}}">
        {{if $data.leaf}}<input type="checkbox" name="menu[{{$data.menu_id}}]" value="{{$data.menu_path}}" {{if $menu[$data.menu_id]}}checked{{/if}} onclick="selectChildAll(this,{{$data.menu_id}})">{{/if}}
        <b>{{$data.menu_title}}<b>
    {{if $data.leaf && $data.privilege}}
    <table style="margin-left:20px;border:1px solid #ccc;width:95%">
    <tr id="privilege_{{$data.menu_id}}">
        <td style="background:#f2f2f2;padding:3px">
        {{foreach from=$data.privilege item=p key=key}}
        {{if $group_privilege[$data.menu_id][$p.privilege_id]}}
        <input type="checkbox" name="privilege[{{$p.privilege_id}}]" value="{{$p.privilege_id}}" {{if $privilege[$p.privilege_id]}}checked{{/if}}> {{$p.title}}&nbsp;&nbsp;&nbsp;&nbsp;
        {{/if}}
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
{{else}}
请选择管理员组
{{/if}}
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
function getPrivilege(id){

    setLidArea(id);
    
    url = '{{url param.action=privilege}}';
    url = filterUrl(url, 'gid');
    new Request({
        url: url + '/gid/' + id,
        onRequest: loading,
        onSuccess:function(data){
        $('privilege').innerHTML = data;
        loadSucess();
        }
    }).send();
}
function setLidArea(id)
{
    url = '{{url param.action=get}}';
    url = filterUrl(url, 'gid');
    new Request({
        url: url + '/gid/' + id,
        onRequest: loading,
        onSuccess:function(data){
            if (data) {
                var info = JSON.decode(data);
                if (info.type == 1) {
                    $('lid_area1').style.display = '';
                    $('lid_area2').style.display = '';
                }
                else {
                    $('lid_area1').style.display = 'none';
                    $('lid_area2').style.display = 'none';
                }
            }
            else {
                $('lid_area1').style.display = 'none';
                $('lid_area2').style.display = 'none';
            }
            loadSucess();
        }
    }).send();
}
setLidArea({{$admin.group_id|default:0}});
</script>