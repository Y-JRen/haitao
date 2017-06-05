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
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr>
<td>机打代码 131001323353</td>
</tr>
<tr>
<td>机打号码 {{$data.transport.invoice_no}}</td>
</tr>
<tr>
<td>发票号码 {{$data.transport.invoice_no}}</td>
</tr>
</table>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr>
<td>{{$data.now}}</td>
<td>商业</td>
</tr>
</table>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr>
<td>付款单位(个人)名称：{{$data.transport.invoice}}</td>
<td>纳税人识别码：</td>
</tr>
<tr>
<td>收款单位(个人)名称：国药（上海）电子商务有限公司</td>
<td>纳税人识别码：310106552956457</td>
</tr>
</table>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr>
<td>项目</td>
<td>单位</td>
<td>数量</td>
<td>单价</td>
<td>金额</td>
</tr>
{{if $data.transport.invoice_content eq '明细' || $data.transport.invoice_content eq '明细(产品代码)'}}
{{foreach from=$data.order.product item=product}}
{{if $product.product_id > 0}}
<tr>
{{if $data.transport.invoice_content eq '明细'}}
<td>{{$product.goods_name}}</td>
{{else}}
<td>{{$product.product_sn}}</td>
{{/if}}
<td>件</td>
<td>{{$product.number}}</td>
<td>{{$product.sale_price}}</td>
<td>
{{if $product.group}}
  {{$product.sum_price-$product.discount|string_format:'%.2f'}}(套组)
{{else}}
  {{$product.sale_price*$product.number|string_format:'%.2f'}}
{{/if}}
</td>
</tr>
{{/if}}
{{/foreach}}
{{if $data.balance_amount}}
<tr>
<td>小数点误差</td>
<td>件</td>
<td>1</td>
<td>{{$data.balance_amount|string_format:'%.2f'}}</td>
<td>{{$data.balance_amount|string_format:'%.2f'}}</td>
</tr>
{{/if}}
{{if $data.order.price_adjust < 0 || $data.order.discount < 0}}
<tr>
<td>折扣</td>
<td>件</td>
<td>1</td>
<td>{{$data.order.price_adjust+$data.order.discount|string_format:'%.2f'}}</td>
<td>{{$data.order.price_adjust+$data.order.discount|string_format:'%.2f'}}</td>
</tr>
{{/if}}
{{if $data.order.gift_card_margin}}
<tr>
<td>礼品卡预抵扣</td>
<td>件</td>
<td>1</td>
<td>-{{$data.order.gift_card_margin|string_format:'%.2f'}}</td>
<td>-{{$data.order.gift_card_margin|string_format:'%.2f'}}</td>
</tr>
{{/if}}
{{if $data.order.gift_card_payed > 0}}
<tr>
<td>礼品卡抵扣</td>
<td>件</td>
<td>1</td>
<td>-{{$data.order.gift_card_payed}}</td>
<td>-{{$data.order.gift_card_payed}}</td>
</tr>
{{/if}}
{{if $data.order.account_payed > 0}}
<tr>
<td>账户余额抵扣</td>
<td>件</td>
<td>1</td>
<td>-{{$data.order.account_payed}}</td>
<td>-{{$data.order.account_payed}}</td>
</tr>
{{/if}}
{{if $data.order.point_payed > 0}}
<tr>
<td>积分抵扣</td>
<td>件</td>
<td>1</td>
<td>-{{$data.order.point_payed}}</td>
<td>-{{$data.order.point_payed}}</td>
</tr>
{{/if}}
{{else}}
<tr>
<td>{{$data.transport.invoice_content}}</td>
<td>件</td>
<td>1</td>
<td>{{$data.invoice_amount|string_format:'%.2f'}}</td>
<td>{{$data.invoice_amount|string_format:'%.2f'}}</td>
</tr>
{{/if}}
</table>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr>
<td>合计人民币（大写） {{$data.invoice_big_amount}}</td>
<td>￥{{$data.invoice_amount|string_format:'%.2f'}}</td>
</tr>
</table>
<table>
<tr>
<td width="10px">备注</td>
<td width="70%">超壹万元无效</td>
<td width="10px">机打信息</td>
<td></td>
</tr>
</table>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr>
<td>收款单位(个人)名称(章)</td>
<td>开票人：钱宇浩</td>
<td>复核人：</td>
</tr>
</table>
{{if $datas|@count ne $smarty.foreach.data.iteration}}
<div style="PAGE-BREAK-AFTER:always"></div>
{{/if}}
{{/foreach}}
</body>
</html>