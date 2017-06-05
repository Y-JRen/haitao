<div class="title">商品基础状态管理</div>
<div class="content">
    <div class="sub_title">
        <!--[ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加状态</a> ]-->
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="40">ID</td>
            <td width="250">状态名称</td>
            <td>备注</td>
            <td width="180">添加时间</td>
            <td width="60">状态</td>
            <td width="60">操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr>
            <td>{{$data.id}}</td>
            <td>{{$data.name}}</td>
            <td>&nbsp;{{$data.remark}}</td>
            <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
            <td>{{if $data.disabled==1}}禁用{{else}}启用{{/if}}</td>
	        <td>
				<a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.id}}')">编辑</a>
	        </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>