<div id="account-list">
<div style="width:850px; float:left; background-color:#FFFFFF; border:0px; overflow:auto; padding-left:2px">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table" style="background-color: #fff">
        <thead>
        <tr>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">ID</td>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">变动时间</td>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">订单ID</td>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">变动原因</td>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">{{$accountName}}</td>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">{{$accountName}}变动</td>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">操作管理员</td>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">是否有效</td>
            <td style="background: url(/haitaoadmin/images/table_thead.gif) repeat-x">无效的原因</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$accounts item=account name=account}}
        <tr id="ajax_list{{$goods.goods_id}}">
            <td>{{$account.id}}</td>
            <td>{{$account.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td>{{$account.batch_sn}}</td>
            <td>{{$account.note}}</td>
            <td>{{$account.totalValue}}</td>
            <td>{{$account.value}}</td>
            <td>{{$account.admin_name}}</td>
            <td>{{if $account.disable eq 0}}有效{{else}}<font color=red>无效</font>{{/if}}</td>
            <td>{{$account.disable_note}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div style="float:left"><input type="button" onclick="closeWin()" value=" 关闭 " /></div><div class="page_nav" style="padding:5px 2px 0px 0px">{{$pageNav}}</div>
</div>
</div>