{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form name="searchForm" id="searchForm">
    <div>
        <span style="float:left">使用日期从：</span><span style="float:left;width:150px;"><input type="text" name="add_time_from" id="add_time_from" size="11" value="{{$param.add_time_from}}" class="Wdate"   onClick="WdatePicker()" /></span>
        <span style="float:left; margin-left:10px">截止到：</span><span style="float:left;width:150px;"><input type="text" name="add_time_end" id="add_time_end" size="11" value="{{$param.add_time_end}}" class="Wdate"   onClick="WdatePicker()" /></span>
        <span style="margin-left:5px; vertical-align:top">礼券价格: <input type="text" name="card_price" value="{{$param.card_price}}" size="4" /></span>
        <span style="margin-left:5px; vertical-align:top">礼券卡号: <input type="text" name="card_sn" value="{{$param.card_sn}}" size="8" /></span>
        <span style="margin-left:5px; vertical-align:top">用户名: <input type="text" name="user_name" value="{{$param.user_name}}" size="6" /></span>
        <span style="margin-left:5px; vertical-align:top"><input type="button" name="dosearch" id="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/></span>
    </div>
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">礼券使用记录</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>礼券类型</td>
            <td>重复使用</td>
            <td>礼券价格</td>
            <td>卡号</td>
            <td>使用用户</td>
            <td>使用时间</td>
            <td>绑定用户ID</td>
            <td>绑定参数</td>
            <td>状态</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$historyList item=history}}
        <tr id="ajax_list{{$history.card_id}}">
            <td>{{$history.card_id}}</td>
            <td>{{$history.card_type}}</td>
            <td>{{$history.is_repeat}}</td>
            <td>{{$history.card_price}}</td>
            <td><a href="/admin/coupon/view-log/id/{{$history.log_id}}">{{$history.card_sn}}</a></td>
            <td>{{$history.user_name}}</td>
            <td>{{$history.add_time}}</td>
            <td>{{$history.parent_id}}</td>
            <td>{{$history.parent_param}}</td>
            <td>{{$history.status}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</div>