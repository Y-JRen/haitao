
<div id="ajax_search">
	<div class="title">广告管理</div>
	<div class="content">
		<div class="sub_title">[ <a onclick="G('{{url param.action=add-adboard}}')" href="javascript:fGo()">添加广告位</a> ]</div>
		<table cellspacing="0" cellpadding="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
                <td>广告位名称</td> 						
				<td>广告类型</td>
				<td>广告位尺寸	</td>
				<td>广告位描述</td>
				<td>状态</td>
				<td>管理操作</td>
			</tr>
		</thead>
		<tbody>
		 {{foreach from=$data item=item}}
		   <tr id="ajax_list26">
			<td>{{$item.id}}</td>
             <td>{{$item.name}}</td>
            
			<td>{{$item.tpl}}</td>
			<td>{{$item.width}}X{{$item.height}}</td>
			<td>{{$item.description}}</td>
			<td>{{if $item.status eq 1}}开启{{else}}关闭{{/if}}</td>
			<td>
			<a onclick="G('{{url param.action=edit-adboard param.id=$item.id}}')" href="javascript:fGo()">编辑</a> |
          	<a onclick="reallydelete('{{url param.action=del-adboard}}','{{$item.id}}','{{url param.action=adboard}}')" href="javascript:fGo()">删除</a>
		</td>
		</tr>	
		{{/foreach}}		
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>	
</div>