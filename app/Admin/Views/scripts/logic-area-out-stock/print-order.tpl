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
<div style="position:absolute;right:0px;top:70px;z-index:10;width:8cm;overflow:hidden">
<!--<img  src="/admin/logic-area2-out-stock/barcode/code/{{$data.bill_no}}" alt="barcode" border="0"  />-->
</div>
<img src="/haitaoadmin/images/print_title.jpg">
<h2>销售单  </h2> 
<br><br>
<br><br>
<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">
订单编号：{{$data.bill_no}}&nbsp;&nbsp;&nbsp;&nbsp;制单日期：{{$data.add_time|date_format:"%Y-%m-%d"}}<br>
收货人：{{$order.addr_consignee}}&nbsp;&nbsp;&nbsp;&nbsp;固定电话：{{$order.addr_tel}}&nbsp;&nbsp;&nbsp;&nbsp;手机：{{$order.addr_mobile}}&nbsp;&nbsp;&nbsp;&nbsp;<br>
省份：{{$order.addr_province}}&nbsp;&nbsp;&nbsp;&nbsp;城市：{{$order.addr_city}}&nbsp;&nbsp;&nbsp;&nbsp;区县：{{$order.addr_area}}&nbsp;&nbsp;&nbsp;&nbsp;<br>
详细地址：{{$order.addr_address}}<br>

{{if $order.type==5}}
赠送下单:赠送人	 {{$order.giftbywho}}
{{/if}}

</div>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
    <td >商品编码</td>
    <td >商品名称</td>
    <td >商品规格</td>
    <td >货位</td>
    <td >数量小计</td>
</tr>
</thead>
<tbody>
{{foreach from=$details item=d key=key}}
<tr>
<td>{{$d.product_sn}}</td>
<td>{{$d.goods_name}}</td>
<td>{{$d.goods_style}}</td>
<td>{{$d.local_sn}}</td>
<td>{{$d.number}}</td>
</tr>
{{/foreach}}
<tr>
<td colspan="5" align="center">
<strong>商品数量合计：</strong>{{$data.total_number}}  &nbsp;&nbsp;&nbsp; <strong>订单金额合计：</strong> {{$order.price_order}}  &nbsp;&nbsp;&nbsp;<strong>运费合计：</strong> {{$order.price_logistic}}  </td>
</tr>
</tbody>
</table>

<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">
<br>
订单总金额：{{$order.price_order}}元&nbsp;&nbsp;&nbsp;&nbsp;
调整金额：{{$order.price_adjust}}元&nbsp;&nbsp;&nbsp;&nbsp;
需支付金额：{{$order.price_pay}}元&nbsp;&nbsp;&nbsp;&nbsp;
已支付金额：{{$order.price_payed+$order.price_from_return}}元&nbsp;&nbsp;&nbsp;&nbsp;
未支付金额：{{$order.price_pay-$order.price_payed-$order.price_from_return}}元&nbsp;&nbsp;&nbsp;&nbsp;<br>
付款方式：{{$order.pay_name}}<br>
开票单位名称：{{$order.invoice}}<br>
物流部门备注：{{$order.note_logistic}}<br>
物流打印备注：{{$order.note_print}}<br>
物流公司：{{$order.logistic_name}}<br>
下单用户：{{$order.user_name}}<br>
</div>

<div style="text-align:left; line-height:150%">
<p><br>
    <b>如需退货，请按商品编码填写退货数量和原因，谢谢！</b></p>
	<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
    <td width="119">商品编码</td>
    <td width="84">退货数量</td>
    <td width="496">退货原因</td>
</tr>
</thead>
<tbody>
{{foreach from=$details item=d key=key}}
{{if $key<5}}
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="checkbox">商品不符 <input type="checkbox">规格错误 <input type="checkbox">包装问题 <input type="checkbox">含量不符  
  <input type="checkbox">质量问题 <input type="checkbox">其他 _____________</td>
</tr>
{{/if}}
{{/foreach}}
</tbody>
</table>
<p><br>
    <b>你希望我们怎么处理您的退款？</b></p>
<p>
<input type="checkbox"> 1、银行转帐：银行名称_______________，开户支行______________，开户名_____________，帐号____________________。 <br>
  <input type="checkbox"> 
  2、支付宝：________________________。<br>
<p><br>
  在我们处理您的退换货过程中，可能需要和您联系，请填写您的联系电话：<br>
  联系人姓名____________________手机___________________座机_______________________<br>
  您方便接听电话的时段___________________________
  <p>温馨提示：
无论通过支付宝或银行转账方式，退款到达您账户可能需要一定时间，银行账户会根据不同银行间跨行转账的到账期而不同，敬请谅解。

</div>
<p><b>国药退换货服务中心：上海宝山区淞兴西路258号4158国药集团1健康 收货人：钱宇浩 邮编：200940<br>
如有疑问请拨打我们的客服热线400-603-3883，我们将竭诚为您服务。</b></p>
</p>
</div>
<div style="text-align:center;padding:30px 0;"><br>
<strong>打印时间：</strong>{{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>操作人：</strong>{{$auth.admin_name}}<br><br>
<strong>拣货人：</strong> _________&nbsp;&nbsp;&nbsp;&nbsp;<strong>拣货时间：</strong> _________&nbsp;&nbsp;&nbsp;&nbsp;<strong>打包人：</strong> _________&nbsp;&nbsp;&nbsp;&nbsp;<strong>打包时间：</strong> _________
</div>
</body>
</html>