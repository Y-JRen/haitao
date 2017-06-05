{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form name="searchForm" id="searchForm">
     <span style="float:left;line-height:18px;">开始日期：<input  class="Wdate" onClick="WdatePicker()" type="text" name="fromdate" id="fromdate" size="11" value="{{$param.fromdate}}"/></span>
<span style="float:left;line-height:18px;">截止日期：<input  class="Wdate" onClick="WdatePicker()" type="text" name="todate" id="todate" size="11" value="{{$param.todate}}"/></span>	
        <span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="nick_name" value="{{$param.nick_name}}" size="15" />
        <span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=moneylist param.do=search}}','ajax_search')"/>
    </form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title">账户余额变动历史</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
			<td>用户昵称</td>
            <td>变动时间</td>
            <td>余额</td>
			<td>余额变动</td>
            <td>变动原因</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$moneylist item=money name=money}}
        <tr id="ajax_list{{$member.user_id}}">
            <td>{{$money.member_id}}</td>
			<td>{{$money.nick_name}}</td>
			<td>{{$money.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td>{{$money.money_total}}</td>
            <td>{{$money.money}}</td>
            <td>{{$money.note}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
</div>