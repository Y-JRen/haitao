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
<div style="position:relative;left:0px;top:0px;"><img src="/haitaoadmin/images/in.jpg"> </div>
<img src="/haitaoadmin/images/print_title.jpg">
<h2>{{$billType[$data.bill_type]}}</h2>
<br><br>
<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">
<table cellpadding="10" cellspacing="10" border="0" width="100%">
  <tr>
    <td width="50%">
      <h4>单据编号：{{$data.bill_no}}</h4>
    </td>
    <td>
      收货日期：{{if $data.delivery_date}}{{$data.delivery_date|date_format:"%Y-%m-%d"}}{{/if}}
    </td>
  </tr>
  <tr>
    <td>
      供应商：{{$data.supplier_name}}
    </td>
    <td>
      打印时间：{{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}}
    </td>
  </tr>
  <tr>
    <td>
      收货区域：{{$area_name}}{{if $logic_area eq 1}}&nbsp;&nbsp;宝山区铁力路388号宝湾物流6号库南{{/if}}
    </td>
    <td>
      入库单号：
    </td>
  </tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table_print" align="center">
<thead>
<tr>
    <td>产品编码</td>
    <td>产品名称</td>
    <!--<td>产品批次</td>-->
    <!--<td>状态</td>>-->
    {{if $auth.group_id eq 3}}
    <td>成本</td>
    {{/if}}
	<td>应收数量</td>
    <td>实收数量</td>
    <td>货架位</td>
	<td>小计</td>
    </tr>
</thead>
<tbody>
{{foreach from=$details item=d key=key}}
<tr>
<td>{{$d.product_sn}}</td>
<td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>)</td>
<!--<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}</td>-->
<!--<td>{{$status[$d.status_id]}}</td>-->
{{if $auth.group_id eq 3}}
<td>{{$d.shop_price}}</td>
{{/if}}
<td>{{$d.plan_number}}</td>
<td>{{$d.real_number}}</td>
<td>{{$d.position_no|default:'&nbsp;'}}</td>
<td>{{$d.shop_price*$d.real_number}} </td>
</tr>
{{/foreach}}
<tr>
<td colspan="8" align="center">
<strong>商品数量合计：</strong>{{$data.total_number}}
{{if $auth.group_id eq 3}}
<strong>商品金额合计：</strong>{{$totalAmount}}
{{/if}}
</td>
</tr>
</tbody>
</table>
<br>
<div>
备注：{{$data.remark}}
</div>
<br><br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
  <td width="50%">收货人：{{$data.admin_name}}</td>
  <td>仓库经理：</td>
</tr>
</table>
</div>

</body>
</html>