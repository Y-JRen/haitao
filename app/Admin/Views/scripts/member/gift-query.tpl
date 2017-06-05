{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/member/gift-query">
<span style="margin-left:5px;">卡号: </span><input type="text" name="card_sn" value="{{$param.card_sn}}" size="15" />
<span style="margin-left:5px;">密码: </span><input type="text" name="card_password" value="{{$param.card_password}}" size="15" />
<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=gift-query param.do=search}}','ajax_search')"/>
</form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title">礼品卡信息查询 </div>
{{if $error}}
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td align="center">
<font color="red" size="3">
{{if $error eq 'no_card'}}卡号或密码错误！
{{elseif $error eq 'no_card_log'}}找不到开卡信息！
{{elseif $error eq 'no_card_sn'}}卡号必须输入！
{{/if}}
</font>
</td>
</tr>
</table>
</div>
{{/if}}
{{if $data}}
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">礼券类型</td>
<td width="40%">
{{if $data.card_type eq 1}}出售
{{elseif $data.card_type eq 2}}赠送
{{elseif $data.card_type eq 3}}兑换
{{/if}}
</td>
<td width="10%">截止日期</td>
<td width="40%">{{$data.end_date}}</td>
</tr>
<tr>
<td>礼券金额</td>
<td>{{$data.card_price}}</td>
<td>剩余金额</td>
<td>{{$data.card_real_price}}</td>
</tr>
</table>
</div>
{{/if}}

{{if $data.user_id}}
<div class="title">会员信息 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">会员名称</td>
<td width="40%">{{$data.user_name}}</td>
<td width="10%">会员ID</td>
<td>{{$data.user_id}}</td>
</tr>
<tr>
<td>使用时间</td>
<td>{{$data.using_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</table>
</div>
{{/if}}

{{if $orderInfo}}
<div class="title">订单信息 </div>
{{foreach from=$orderInfo item=data}}
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">订单号</td>
<td width="40%"><a href="/admin/order/info/batch_sn/{{$data.batch_sn}}">{{$data.batch_sn}}</a></td>
<td width="10%">订单状态</td>
<td>{{if $data.status eq 0}}正常单{{elseif $data.status eq 1}}取消单{{elseif $data.status eq 2}}无效单{{/if}}</td>
</tr>
<tr>
<td>订单金额</td>
<td>{{$data.price_pay}}</td>
<td>抵扣金额</td>
<td>{{$data.consume_amount}}</td>
</tr>
<tr>
<td>联系方式</td>
<td>{{$data.addr_consignee}} {{$data.addr_mobile}}</td>
<td></td>
<td></td>
</tr>
</table>
{{/foreach}}
</div>
{{/if}}

{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
</div>
{{/if}}