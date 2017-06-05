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
<h2>在线支付货款结算</h2>
<br><br>
<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">
结算单号{{$bill.clear_no}}&nbsp;&nbsp;&nbsp;&nbsp;
结算日期：{{$bill.clear_time|date_format:"%Y-%m-%d"}}&nbsp;&nbsp;&nbsp;&nbsp;
支付方式：{{if $payment_list[$bill.pay_type].name}}{{$payment_list[$bill.pay_type].name}}{{elseif $bill.pay_type eq 'bank'}}银行打款{{elseif $bill.pay_type eq 'cash'}}现金支付{{/if}}<br>
备注：{{$data.remark}}
</div>
	
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>单据编号</td>
        <td>订单号</td>
        <td>支付方式</td>
        <td>结算金额</td>
      
    </tr>
</thead>
<tbody id="list">
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$bill.clear_no}}</td>
        <td>{{$data.batch_sn}}</td>
        <td>{{$data.pay_name}}</td>
        <td>{{$data.price_payed}}</td>
        
    </tr>
    {{/foreach}}
</tbody>
</table>
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
