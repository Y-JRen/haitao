<div class="title">管理员组管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加管理员组</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>管理员组名称</td>
            <td>分仓</td>
            <td>说明</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$groupList item=group}}
        <tr id="ajax_list{{$group.group_id}}">
            <td>{{$group.group_id}}</td>
            <td>{{$group.group_name}}</td>
            <td>{{if $group.type}}是{{else}}否{{/if}}</td>
            <td>{{$group.remark}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$group.group_id}}')">编辑</a>
                <a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delete}}','{{$group.group_id}}')">删除</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>