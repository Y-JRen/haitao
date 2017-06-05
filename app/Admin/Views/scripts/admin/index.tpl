{{if !$param.do}}
<form name="searchForm" id="searchForm">
<div class="search">
<select id="group_id" class="required" msg="请选择管理员组" name="group_id">
<option value="">选择管理员组</option>
{{foreach from =$groupIds key=key item =group }} 
<option value="{{$key}}" label="{{$group}}" {{if $param.group_id eq  $key}} selected="selected"  {{/if}} >{{$group}}</option>
{{/foreach}}
</select> 
名称：<input type="text" name="admin_name" size="12" maxLength="50" value="{{$param.admin_name}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
</div>
</div>
</form>
{{/if}}
<div id="ajax_search">
<div class="title">管理员管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加管理员</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>管理员名称</td>
            <td>真实姓名</td>
            <td>创建者</td>
            <td>管理员组</td>
            <td>最后登录时间</td>
            <td>最后登录IP</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$adminList item=admin}}
        <tr id="ajax_list{{$admin.admin_id}}">
            <td>{{$admin.admin_id}}</td>
            <td>{{$admin.admin_name}}</td>
            <td>{{$admin.real_name}}</td>
            <td>{{$admin.add_admin}}</td>
            <td>{{$admin.group_name}}</td>
            <td>{{$admin.last_login}}</td>
            <td>{{$admin.last_login_ip}}</td>
            <td id="ajax_status{{$admin.admin_id}}">{{$admin.status}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$admin.admin_id}}')">编辑</a> | 
                <a href="javascript:fGo()" onclick="delAdmin('{{$admin.admin_id}}')">删除</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</div>

<script type="text/javascript">
function delAdmin(id){
	if(confirm("确认要对该管理员做删除操作吗？")){
		id=parseInt(id);
		if(id<1){alert('参数错误！');return;}
		new Request({
			url:'/admin/admin/delete/id/'+id,
			method:'get',
			onSuccess:function(data){
				if(data=='ok'){
					alert("操作成功");
					$('ajax_list'+id).destroy();
				}else{
					alert(data+"操作失败，请稍后重试");
				}
			},
			onFailure:function(){
				alert("网路繁忙，请稍后重试");
			}
		}).send();
	}
}
</script>