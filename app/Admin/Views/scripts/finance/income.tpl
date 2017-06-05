{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$fromdate}}"   class="Wdate" onClick="WdatePicker()" /></span>
<span style="float:left;line-height:18px;">结束日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="todate" id="todate" size="15" value="{{$todate}}" class="Wdate" onClick="WdatePicker()" /></span>

<div style="clear:both; padding-top:5px">
    订单编号：<input type="text" name="batch_sn" size="20" maxLength="50">
    收货人名字：<input type="text" name="addr_consignee" size="20" maxLength="50">
    支付方式:
	<select name='pay_type' id="pay_type">
	<option value="">请选择...</option>
	{{foreach from=$payment item=item}}
	<option value={{$item.pay_type}} {{if $pay_type==$item.pay_type}}selected="selected"{{/if}}>{{$item.name}}</option>
	{{/foreach}}
	</select>
	非货到付款：
	<select name='pay_type!='>
	<option value="">请选择...</option>
	<option value="cod">非货到付款</option>
	</select>
	</div>	
<div style="clear:both; padding-top:5px">	
     订单状态:
    <select name="status">
    <option value="">请选择...</option>
    <option value="0">有效单</option>
    <option value="1">取消单</option>
    <option value="2">垃圾单</option>
    </select>
     支付状态:
    <select name="status_pay">
    <option value="">请选择...</option>
    <option value="0">未收款</option>
    <option value="1">未退款</option>
    <option value="2">已结清</option>
    </select>
    配送状态:
    <select name="status_logistic">
    <option value="">请选择...</option>
    <option value="0">未确认</option>
    <option value="1">已确认[待收款]</option>
    <option value="2">待发货</option>
    <option value="3">已发货未签收</option>
    <option value="4">客户已签收</option>
    </select>
    
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
    </div>	
    </form>
</div>
<div id="ajax_search">
{{/if}}
<form name="myForm" id="myForm">
<div class="title">应收款列表</div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>订单号</td>
				<td>下单时间</td>
				<td>收货人</td>
				<td>应收金额</td>
				<td>已收金额</td>
				<td>支付方式</td>
				<td>订单状态</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.order_batch_id}}">
			<td><span style="cursor:pointer;" onclick="openDiv('{{url param.action=order param.batch_sn=$item.batch_sn}}','ajax','查看订单',750,400)">{{$item.batch_sn}}</span></td>
			<td>{{$item.add_time}}</td>
			<td>{{$item.addr_consignee}}</td>
			<td>{{if $item.blance>0}}{{$item.blance}}{{/if}}</td>
			<td>{{$item.price_payed+$item.price_from_return}}</td>
			<td>{{$item.pay_name}}</td>
			<td>{{$item.status}}  {{$item.status_pay}}</td>
		  </tr>
		{{/foreach}}
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>	
</form>