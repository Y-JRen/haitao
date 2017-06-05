<div class="title">属性管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加顶级属性</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>属性名称</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.attr_id}}">
	        <td><input type="text" name="update" size="2" value="{{$data.attr_sort}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.attr_id}},'attr_sort',this.value)"></td>
            <td>{{$data.attr_id}}</td>
            <td style="padding-left:{{$data.step*20}}px;{{if $data.step==1}}font-weight:bold{{/if}}">{{$data.attr_title}}</td>
            <td id="ajax_status{{$data.attr_id}}">
            {{$data.status}}
            </td>
            <td>
            {{if !$data.parent_id}}<a href="javascript:fGo()" onclick="G('{{url param.action=add param.id=$data.attr_id}}')">添加</a> | {{/if}}
            <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.attr_id}}')">编辑</a> | 
            <a href="javascript:fGo()" onclick="if (confirm('确定要删除吗？')) G('{{url param.action=delete param.id=$data.attr_id}}')">删除</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>