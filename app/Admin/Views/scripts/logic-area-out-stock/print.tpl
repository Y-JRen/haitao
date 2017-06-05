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
<div style="position:relative;left:0px;top:0px;"><img src="/haitaoadmin/images/out.jpg"> </div>
<img src="/haitaoadmin/images/print_title.jpg">
<h2>{{$billType[$datas.0.bill.bill_type]}}　　<img src="/admin/transport/barcode/no/{{$datas.0.bill.bill_no}}"></h2>
<br><br>
<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">

<table cellpadding="10" cellspacing="10" border="0" width="100%">
  <tr>
    <td width="50%">
      <h4>单据编号：{{$datas.0.bill.bill_no}}</h4>
    </td>
    <td>
      收货日期：{{if $datas.0.bill.finish_time}}{{$datas.0.bill.finish_time|date_format:"%Y-%m-%d"}}{{/if}}
    </td>
  </tr>
  <tr>
    <td>
      供应商：{{$datas.0.bill.supplier_name}}
    </td>
    <td>
      打印时间：{{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}}
    </td>
  </tr>
  <tr>
    <td>
      发货区域：{{$area_name}}
    </td>
    <td>
      物流运单号：
    </td>
  </tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table_print" align="center">
<thead>
<tr>
    <td>商品编码</td>
    <td>商品名称</td>
    <td>状态</td>
	<td>货架位</td>
    <td>发货数量</td>
    </tr>
</thead>
<tbody>
{{foreach from=$datas.0.details item=d key=key}}
<tr>
<td>{{$d.product_sn}}</td>
<td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>) </td>
<td>{{$status[$d.status_id]}}</td>
<td>{{$d.position_no|default:'&nbsp;'}}</td>
<td>{{$d.number}}</td>
</tr>
{{/foreach}}
<tr>
<td colspan="8" align="center">
<strong>商品数量合计：</strong>{{$datas.0.total_number}}
</td>
</tr>
</tbody>
</table>
<br>
<div>
备注：{{$datas.0.bill.remark}}
</div>
<br><br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
  <td width="50%">收货人：</td>
  <td>仓库经理：</td>
</tr>
</table>
</div>

</body>
</html>