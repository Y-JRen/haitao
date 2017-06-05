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
    text-700px: left;
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
{{foreach from=$datas item=data name=data}}
<div style="position:relative;text-align:center;padding:5px;">
<img src="/haitaoadmin/images/print_title.jpg">
<h2>
{{if $data.order.shop_id eq 62 or $data.order.shop_id eq 11 }}发　货　单{{else}}销　售　单{{/if}}
{{if $data.bill.bill_no_array}}
{{foreach from=$data.bill.bill_no_array item=item}}
<br><br><img src="/admin/transport/barcode/no/{{$item}}">
{{/foreach}}
{{else}}
<img src="/admin/transport/barcode/no/{{if $data.bill.bill_no|count_commas:',' eq 0}}{{$data.bill.bill_no}}{{else}}OID{{$data.bill.outstock_id}}{{/if}}">
{{/if}}
</h2>
<br><br>
<br><br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
  <tr>
    <td width="50%" style="text-align:left;">
      送货方式：{{if $data.order.pay_type eq 'cod'}}<b>货到付款</b>   {{if $data.order.shop_id  ne 11 }} 应付货款：<b>{{$data.bill.transport.amount+$data.bill.transport.change_amount}}</b>元 {{/if}}  {{else}}<b>款到发货</b> {{if $data.order.shop_id ne 62 and  $data.order.shop_id ne 11 }}已付货款：<b>{{$data.order.price_order}}</b>元{{/if}}{{/if}}
    </td>
    <td style="text-align:right;">
      订购渠道：{{if !$data.order.shop_id}}官网{{else}}{{$shopInfo[$data.order.shop_id]}}{{/if}}
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流公司：{{$data.order.logistic_name}}
    </td>
    <td style="text-align:right;">
      订单编号：<b>{{$data.bill.bill_no|truncate:"15":""}}</b>
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流单号：{{$data.bill.logistic_no}}
    </td>
    {{if $data.order.external_order_sn}}
    <td style="text-align:right;">
      渠道单号：{{$data.order.external_order_sn}}
    </td>
    {{/if}}
  </tr>
</table>
<br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
  <tr>
    <td style="text-align:left;">
      收货人姓名：{{$data.order.addr_consignee}}　　　　　　电话：{{$data.order.addr_tel}}　　　　　　手机：{{$data.order.addr_mobile}}
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      地址：{{$data.order.addr_province}} {{$data.order.addr_city}} {{$data.order.addr_area}} {{$data.order.addr_address}}
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      是否开票：{{if $data.order.invoice_type}}是　　　　　发票抬头：{{$data.order.invoice|default:'个人'}}　　　　　发票内容：{{$data.order.invoice_content}}　　　　　开票金额：{{$data.order.price_pay-$data.order.point_payed-$data.order.account_payed-$data.order.gift_card_payed-$data.order.price_logistic|string_format:'%.2f'}}{{else}}否{{/if}}
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流备注：{{$data.order.note_logistic}}
    </td>
  </tr>
</table>
<br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr><td style="text-align:left;">本次发货情况：</td></tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
    <td>商品编码</td>
    <td>商品名称</td>
    <td>商品规格</td>
    {{if $data.order.shop_id  ne 11 and  $data.order.shop_id ne 62}}
    <td>单价</td>
    {{/if}}
    <td>数量</td>
    {{if $data.order.shop_id ne 62 and $data.order.shop_id ne 65 and $data.order.shop_id ne 11}}<td >小计</td>{{/if}}
    </tr>
</thead>
<tbody>
{{foreach from=$data.order.product item=d key=key}}
{{if $d.product_id > 0}}
<tr>
<td>{{$d.product_sn}}</td>
<td>{{$d.goods_name}}</td>
<td>{{$d.goods_style}}&nbsp;</td>
 {{if $data.order.shop_id  ne 11 and  $data.order.shop_id ne 62}}
<td>{{$d.sale_price}}</td>
 {{/if}}

<td>{{$d.number}}</td>
{{if $data.order.shop_id ne 62 and $data.order.shop_id ne 65   and $data.order.shop_id ne 11}}
<td>
  {{if $d.group}}
    {{$d.sum_price-$d.discount|string_format:'%.2f'}}(套组)
  {{else}}
    {{$d.sale_price*$d.number|string_format:'%.2f'}}
  {{/if}}
</td>
{{/if}}
</tr>
{{/if}}
{{/foreach}}
{{if $data.order.shop_id ne 62 and $data.order.shop_id ne 65  and $data.order.shop_id ne 11}}
<tr>
<td colspan="5" style="text-align:right;">
商品金额总计：
</td>
<td>
{{$data.order.price_goods|string_format:'%.2f'}}
</td>
</tr>
{{if $data.order.price_adjust < 0 || $data.order.discount < 0}}
<tr>
<td colspan="5" style="text-align:right;">
折扣：
</td>
<td>
{{$data.order.price_adjust+$data.order.discount|string_format:'%.2f'}}
</td>
</tr>
{{/if}}
{{if $data.order.tax > 0}}
<tr>
<td colspan="5" style="text-align:right;">
商品行邮税总计：
</td>
<td>
{{$data.order.tax|string_format:'%.2f'}}
</td>
</tr>
{{/if}}
{{if $data.order.price_logistic > 0}}
<tr>
<td colspan="5" style="text-align:right;">
运费：
</td>
<td>
{{$data.order.price_logistic|string_format:'%.2f'}}
</td>
</tr>
{{/if}}
<tr>
<td colspan="5" style="text-align:right;">
订单金额总计：
</td>
<td>
{{$data.order.price_order|string_format:'%.2f'}}
</td>
</tr>
{{/if}}
{{if $data.order.gift_card_margin}}
<tr>
<td colspan="5" style="text-align:right;">
礼品卡预抵扣：
</td>
<td>
-{{$data.order.gift_card_margin|string_format:'%.2f'}}
</td>
</tr>
{{/if}}
{{if  $data.order.shop_id ne 11 and  $data.order.shop_id ne 62}}
<tr>
<td colspan="5" style="text-align:right;">
{{if $data.order.gift_card_payed > 0}}
礼品卡抵扣：-{{$data.order.gift_card_payed}}&nbsp;
{{/if}}
{{if $data.order.account_payed > 0}}
账户余额抵扣：-{{$data.order.account_payed}}&nbsp;
{{/if}}
{{if $data.order.point_payed > 0}}
积分抵扣：-{{$data.order.point_payed}}&nbsp;
{{/if}}
&nbsp;&nbsp;&nbsp;&nbsp;应付金额：
</td>
<td>
{{$data.order.price_pay-$data.order.gift_card_payed-$data.order.account_payed-$data.order.point_payed|string_format:'%.2f'}}
</td>
</tr>
{{/if}}

</tbody>
</table>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr><td style="font-style:italic">感谢您在国药集团1健康商城购物，如需退换货请填写背面的退换货单并拨打客服热线400-603-3883。我们期待您的再次光临</td></tr>
</table>
<p>&nbsp;</p>  <p>&nbsp;</p>


</div>
{{if $datas|@count ne $smarty.foreach.data.iteration}}
<div style="PAGE-BREAK-AFTER:always"></div>
{{/if}}
{{/foreach}}
</body>
</html>