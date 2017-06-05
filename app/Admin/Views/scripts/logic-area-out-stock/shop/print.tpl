<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>店铺销售单打印</title>
<meta HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=utf-8'>
<style type="text/css">
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
{{foreach from=$datas item=data}}
<div style="position:relative;text-align:center;padding:5px;">
<h3>销售出货明细单</h3>
</div>
<div style="position:relative;text-align:center;padding:5px;">
<table cellpadding="0" cellspacing="0" border="0" align="left">
<tr height="30px">
<td>订单编号：{{$data.bill.bill_no}}</td>
<td width="200px">店铺名称：{{$shop.shop_name}}</td>
<td width="140px">开单日期：{{$data.bill.add_time|date_format:"%Y-%m-%d"}}</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<tr>
  <td width="80px">收货人</td>
  <td width="140px">{{$data.order.addr_consignee}}</td>
  <td width="80px">联系方式</td>
  <td width="140px">{{$data.order.addr_mobile}}</td>
  <td width="80px">邮编</td>
  <td>{{$data.bill.transport.zip}}</td>
</tr>
<tr>
  <td>省份</td>
  <td>{{$data.order.addr_province}}</td>
  <td>城市</td>
  <td>{{$data.order.addr_city}}</td>
  <td>区县</td>
  <td>{{$data.order.addr_area}}</td>
</tr>
<tr>
  <td>收货地址</td>
  <td colspan="5">{{$data.order.addr_address}}</td>
</tr>
</table>
<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
  <td>序号</td>
  <td >商品编码</td>
  <td >货品名称</td>
  <td >商品规格</td>
  <td >数量</td>
  <td >单价</td>
  <td >合计</td>
</tr>
</thead>
<tbody>
{{foreach from=$data.details item=d key=key name=key}}
<tr>
  <td>{{$smarty.foreach.key.iteration}}</td>
  <td>{{$d.product_sn}}</td>
  <td>{{$d.goods_name}}</td>
  <td>{{$d.goods_style}}</td>
  <td>{{$d.number}}</td>
  <td>{{$d.shop_price}}</td>
  <td>{{math equation="x * y" x=$d.shop_price y=$d.number}}</td>
</tr>
{{/foreach}}
</table>
<br>
<table cellpadding="0" cellspacing="0" border="0" align="left" width="100%">
<tr>
  <td width="90px" style="font-size:16px">商品数量：</td>
  <td width="90px" style="font-size:16px">{{$data.total_number}}</td>
  <td width="90px" style="font-size:16px">货款合计：</td>
  <td width="90px" style="font-size:16px">{{$data.order.price_goods}}</td>
  <td width="90px" style="font-size:16px">运送费用：</td>
  <td width="90px" style="font-size:16px">{{$data.order.price_logistic}}</td>
  <td width="90px" style="font-size:16px">总金额：</td>
  <td style="font-size:16px">{{$data.order.price_order}}</td>
</tr>
<tr height=2>
  <td colspan="8" style="color:#000000;background-color:#000000;border-left-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;"></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr height=30>
  <td colspan="8" style="font-size:14px" align="left">买家留言：{{$data.order.note}}</td>
</tr>
<tr height=30>
  <td colspan="8" style="font-size:14px" align="left">物流备注：{{$data.order.note_logistic}}</td>
</tr>
<tr height=30>
  <td colspan="8" style="font-size:14px" align="left">发票：{{$data.order.invoice_title}}</td>
</tr>
<tr height=30>
  <td colspan="8" style="font-size:14px" align="left">送货时间：{{$data.bill.add_time|date_format:"%Y-%m-%d"}}</td>
</tr>
<tr height=30>
  <td colspan="8" style="font-size:14px" align="left">服务热线：400-603-3883　　投诉电话：400-605-3883　　网址：www.1jiankang.com </td>
</tr>
</table>
</div>
<div style="PAGE-BREAK-AFTER:always"></div>
{{/foreach}}
</body>
</html>