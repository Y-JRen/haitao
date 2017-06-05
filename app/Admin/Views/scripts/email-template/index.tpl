<div class="title">邮件模板</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加邮件模板</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>邮件模板名称</td>
            <td>邮件主题</td>
            <td>邮件类型</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$templateList item=template}}
        <tr id="ajax_list{{$template.template_id}}">
            <td>{{$template.template_id}}</td>
            <td>{{$template.name}}</td>
            <td>{{$template.title}}</td>
            <td>{{$template.type}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$template.template_id}}')">编辑</a>
                <a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delete}}','{{$template.template_id}}')">删除</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>