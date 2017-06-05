<div class="title">菜单管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加菜单</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="60">排序</td>
            <td width="60">ID</td>
            <td width="150">菜单名称</td>
            <td width="450">地址</td>
            <td width="80">是否展开</td>
            <td width="60">状态</td>
            <td width="250">操作</td>
	        <td>
	        </td>
       </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.menu_id}}">
        <td><input type="text" name="update" size="2" value="{{$data.menu_sort}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.menu_id}},'menu_sort',this.value)"></td>
        <td>{{$data.menu_id}}</td>
        <td style="padding-left:{{$data.step*20}}px">{{$data.depth}}<input type="text" name="update" size="18" value="{{$data.menu_title}}"  onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.menu_id}},'menu_title',this.value)"></td>
        <td>{{$data.url}}</td>
        <td>{{if $data.is_open}}关闭{{else}}展开{{/if}}</td>
        <td id="ajax_status{{$data.menu_id}}">{{$data.status}}</td>
        <td>
			{{if $data.parent_id==0}}
			<a href="javascript:fGo()" onclick="G('{{url param.pid=$data.menu_id}}')">管理子菜单</a> | 
			{{/if}}
			<a href="javascript:fGo()" onclick="G('{{url param.action=add param.pid=$data.menu_id}}')">添加子菜单</a> | 
			<a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.menu_id}}')">编辑</a>
             <a href="javascript:fGo()" onclick="delMenu({{$data.menu_id}})">删除</a>
        </td>
        <td>
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<script type="text/javascript">
function delMenu(id){
	if(confirm("确认要对该菜单做删除操作吗？")){
		id=parseInt(id);
		if(id<1){alert('参数错误！');return;}
		new Request({
			url:'/admin/menu/delete/id/'+id,
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