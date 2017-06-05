<div class="title">会员等级管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加会员等级</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>会员等级名称</td>
            <td>积分下限</td>
            <td>积分上限</td>
            <td>普通折扣率</td>
            <td>特殊会员等级</td>
            <td>显示价格</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$rankList item=rank}}
        <tr id="ajax_list{{$rank.rank_id}}">
            <td>{{$rank.rank_id}}</td>
            <td>{{$rank.rank_name}}</td>
            <td>{{$rank.min_point}}</td>
            <td>{{$rank.max_point}}</td>
            <td>{{$rank.discount}}</td>
            <td>{{$rank.is_special}}</td>
            <td id="ajax_status{{$rank.rank_id}}">{{$rank.show_price}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$rank.rank_id}}')">编辑</a>
                <a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delete}}','{{$rank.rank_id}}')">删除</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>