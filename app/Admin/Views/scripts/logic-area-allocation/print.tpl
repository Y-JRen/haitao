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
<div style="position:relative;text-align:center;padding:5px;">
<div style="position:absolute;right:0px;top:70px;z-index:10;width:7cm;overflow:hidden">{{$data.barcode}}</div>
<img src="/haitaoadmin/images/print_title.jpg">
<h2>调拨单</h2>
<br><br>
<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">
单据编号：{{$data.bill_no}}&nbsp;&nbsp;&nbsp;&nbsp;
制单日期：{{$data.add_time|date_format:"%Y-%m-%d"}}<br>
发货区域：{{$areas[$data.from_lid]}}&nbsp;&nbsp;&nbsp;&nbsp;收货区域：{{$areas[$data.to_lid]}}<br>
备注：{{$data.remark}}
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table_print" align="center">
<thead>
<tr>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>状态</td>
	<td>货架位</td>
    <td>调拨数量</td>
    </tr>
</thead>
<tbody>
{{foreach from=$details item=d key=key}}
<tr>
<td>{{$d.product_sn}}</td>
<td>{{$d.goods_name}}</td>
<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}</td>
<td>{{$status[$d.status_id]}}</td>
<td>{{$d.local_sn}}</td>
<td>{{$d.number}}</td>
</tr>
{{/foreach}}
<tr>
<td colspan="8" align="center">
<strong>商品数量合计：</strong>{{$data.total_number}}
</td>
</tr>
</tbody>
</table>
</div>
<div style="text-align:center;padding:30px 0;"><strong>打印时间：</strong>{{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>操作人：</strong>{{$auth.admin_name}}</div>

</body>
</html>