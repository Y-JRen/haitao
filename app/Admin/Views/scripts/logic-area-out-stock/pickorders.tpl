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
<div style="position:relative;left:0px;top:0px;"><img src="/haitaoadmin/images/pick.jpg"> </div>
<img src="/haitaoadmin/images/print_title.jpg">
<h2>产品销售拣货单</h2>
<br><br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品规格</td>
    <!--<td>产品批次</td>-->
    <td>数量</td>
    <td>货位</td>
    <!--<td>相关单号</td>-->
    </tr>
</thead>
<tbody>
{{foreach from=$pickGoods item=pickGoods1}}
{{foreach from=$pickGoods1 item=d key=key}}
<tr>
<td>{{$d.product_sn}}</td>
<td>{{$d.goods_name}}</td>
<td>{{$d.goods_style}}</td>
<!--<td>{{if $key}}{{$key}}{{else}}无批次{{/if}}</td>-->
<td>{{$d.number}}</td>
<td>{{if $d.local_sn}}{{$d.local_sn}}{{else}}&nbsp;{{/if}}</td>
<!--<td>{{$d.order}}</td>-->
</tr>
{{/foreach}}
{{/foreach}}
<tr>
<td colspan="3" style="text-align:right;">
包裹总件数
</td>
<td>{{$total_number}}</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
<!--
<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
<td>订单编号</td>
<td>送货方式</td>
<td>物流公司</td>
<td>物流单号</td>
<td>发票抬头</td>
<td>发票内容</td>
<td>开票金额</td>
</tr>
</thead>
{{foreach from=$datas item=data}}
<tr>
  <td>{{$data.order.order_sn}}</td>
  <td>{{if $data.order.pay_type eq 'cod'}}货到付款{{else}}款到发货{{/if}}</td>
  <td>{{$data.order.logistic_name}}</td>
  <td>{{$data.bill.logistic_no|default:'&nbsp;'}}</td>
  <td>{{if $data.order.invoice_type}}{{$data.order.invoice|default:'个人'}}{{else}}&nbsp;{{/if}}</td>
  <td>{{if $data.order.invoice_type}}{{$data.order.invoice_content}}{{else}}&nbsp;{{/if}}</td>
  <td>{{if $data.order.invoice_type}}{{$data.order.price_order}}{{else}}&nbsp;{{/if}}</td>
</tr>
{{/foreach}}
</table>
-->
<div style="text-align:left;padding:30px 0;"><br>
拣货人：________________　　　　　　QC：________________　　　　　　打印时间：{{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}}
</div>

<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
<td>订单编号</td>
<td>送货方式</td>
<td>物流公司</td>
<td>物流单号</td>
<td>产品信息</td>
</tr>
</thead>
{{foreach from=$datas item=data}}
<tr>
  <td>{{$data.order.order_sn}}</td>
  <td>{{if $data.order.pay_type eq 'cod'}}货到付款{{else}}款到发货{{/if}}</td>
  <td>{{$data.order.logistic_name}}</td>
  <td>{{$data.bill.logistic_no|default:'&nbsp;'}}</td>
  <td>
  {{foreach from=$data.details item=v}}
    【{{$v.product_sn}}】{{$v.product_name}}({{$v.goods_style}}) 【{{$v.number}}】 <br>
  {{/foreach}}
  </td>
</tr>
{{/foreach}}
</table>

</div>
</body>
</html>