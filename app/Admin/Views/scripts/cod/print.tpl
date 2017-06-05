<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body {
    margin: 0;
    color: #000;
}
table, td, div {
    font: normal 12px  Verdana, "Times New Roman", Times, serif;
}
div {
    margin: 0 auto;
    width: 700px;
}
.table_print {
    clear: both;
    border-right: 1px solid #333;
    border-bottom: 1px solid #333;
    text-align: left;
    width: 700px;
}
.table_print td {
    padding: 2px;
    color: #333;
    background: #fff;
    border-top: 1px solid #333;
    border-left: 1px solid #333;
    line-height: 150%;
}
.item {
    text-align:right;
    font-weight:bold;
}
</style>
</head>
<body>
<div style="text-align:center;padding:5px;">
<img src="/haitaoadmin/images/print_title.jpg">
<br><br>
<h2>代收货款结算清单</h2>
<br><br>
<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">
结算单号{{$bill.clear_no}}&nbsp;&nbsp;&nbsp;&nbsp;
结算日期：{{$bill.clear_time|date_format:"%Y-%m-%d"}}&nbsp;&nbsp;&nbsp;&nbsp;
物流公司：{{$logisticList[$bill.logistic_code]}}<br>
备注：{{$data.remark}}
</div>
			<table cellpadding="0" cellspacing="0" border="0" class="table" width="100%">
			<thead>
			    <tr>
			        <td>单据编号</td>
			        <td>单据类型</td>
			        <td>运单号</td>
			        <td>订单金额</td>
			        <td>服务费</td>
			        <td>手续费</td>
			        <td>返款</td>
			    </tr>
			</thead>
			<tbody id="list">
			    {{foreach from=$datas item=data}}
			    <tr>
			        <td>{{$data.bill_no}}</td>
			        <td>{{$billType[$data.bill_type]}}</td>
			        <td>{{$data.logistic_no}}</td>
			        <td>{{$data.amount}}</td>
			        <td>{{$data.logistic_fee_service}}</td>
			        <td>{{$data.logistic_price_cod}}</td>
			        <td>{{$data.back_amount}}</td>
			    </tr>
			    {{/foreach}}
			</tbody>
			</table>

			<div style="clear:both;float:right;width:100%;text-align:right;padding:10px 20px"><strong>服务费合计：</strong>{{$bill.fee_service}}元
			&nbsp;&nbsp;&nbsp;&nbsp;<strong>手续费合计：</strong>{{$bill.cod_price}}元
			&nbsp;&nbsp;&nbsp;&nbsp;<strong>返款合计：</strong>{{$bill.back_amount}}元
			</div>

<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">
<br>
调整金额：{{$bill.adjust_amount}}元<br>
调整说明：{{$bill.adjust_remark}}<br>
本期实际返款金额：{{$bill.real_amount}}元<br>
</div>
</div>

<div style="text-align:center;padding:5px;"><strong>操作人：</strong>{{$auth.admin_name}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>打印时间：</strong>{{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}}</div>

</body>
</html>
