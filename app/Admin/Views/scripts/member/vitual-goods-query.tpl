{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/member/gift-query">
<span style="margin-left:5px;">卡号: </span><input type="text" name="sn" value="{{$param.sn}}" size="15" />
<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=vitual-goods-query param.do=search}}','ajax_search')"/>
</form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title">虚拟商品查询 </div>
{{if $error}}
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td align="center">
<font color="red" size="3">
{{if $error eq 'no_card'}}卡号错误！
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
<td width="10%">商品类型</td>
<td width="40%">
{{if $data.type eq 1}}体检卡
{{/if}}
</td>
<td width="10%">商品名称</td>
<td width="40%">{{$data.product_name}}</td>
</tr>
<tr>
<td>生成时间</td>
<td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
<td>状态</td>
<td>
{{if $data.status eq 0}}未分配
{{elseif $data.status eq 1}}已交付
{{elseif $data.status eq 2}}已使用
{{elseif $data.status eq 9}}已作废
{{/if}}
</td>
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
<td>交付时间</td>
<td>{{$data.deliver_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
<td>使用时间</td>
<td>{{if $data.using_time}}{{$data.using_time|date_format:"%Y-%m-%d %H:%M:%S"}}{{/if}}</td>
</tr>
</table>
</div>
{{/if}}

{{if $orderInfo}}
<div class="title">订单信息 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">订单号</td>
<td width="40%"><a href="/admin/order/info/batch_sn/{{$orderInfo.batch_sn}}">{{$orderInfo.batch_sn}}</a></td>
<td width="10%">订单金额</td>
<td>{{$orderInfo.price_pay}}</td>
</tr>
<tr>
<td>订单状态</td>
<td>{{if $orderInfo.status eq 0}}正常单{{elseif $orderInfo.status eq 1}}取消单{{elseif $orderInfo.status eq 2}}无效单{{/if}}</td>
<td>短信接收号码</td>
<td>{{$orderInfo.sms_no}}</td>
</tr>
</table>
</div>
{{/if}}

{{if $data.send_content}}
<div class="title">短信发送记录 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
{{foreach from=$data.send_content item=content}}
<tr>
<td width="20%">发送用户 -> 发送时间</td>
<td>{{$content.sendUser}} -> {{$content.sendTime|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
</tr>
{{/foreach}}
</table>
</div>
{{/if}}

{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
</div>
{{/if}}