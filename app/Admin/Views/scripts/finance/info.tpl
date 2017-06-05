<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>

<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table_form">
    <tbody>
        <tr bgcolor="#F0F1F2">
          <th width="150">单据编号：</th>
          <td>{{$order.batch_sn}}</td>
        </tr>
        <tr><th>下单日期：</th>
        <td>{{$order.add_time}}</td>
        </tr>
        <tr bgcolor="#F0F1F2"><th>用户名称：</th>
        <td>{{$order.user_name}}</td></tr>
    </tbody>
</table>
<br>
<table class="mytable">
    <tbody>
        <tr  bgcolor="#F0F1F2"><th width="150">收货人：</th>
        <td>{{$order.addr_consignee}}</td>
        </tr>
        <tr><th>联系电话：</th>
        <td colspan="2">{{$order.addr_tel}}</td></tr>
        <tr bgcolor="#F0F1F2"><th>手机：</th>
        <td colspan="2">{{$order.addr_mobile}}</td></tr>
        <tr bgcolor="#F0F1F2"><th>收货地址：</th>
        <td colspan="2">{{$order.addr_address}}</td></tr>
        <tr><th>邮政编码：</th>
        <td colspan="2">{{$order.addr_zip}}</td></tr>
    </tbody>
</table>
<br>
<table>
    <tbody>
        <tr bgcolor="#F0F1F2"><th width="150">付款方式：</th>
        <td>{{$order.pay_name}}</td>
        </tr>
    </tbody>
</table>
        <br>
        <table>
        <tr bgcolor="#F0F1F2"><th width="150">配送方式：</th>
        <td>{{$order.logistic_name}}</td>
        </tr>
    </tbody>
</table>
<br>
<table class="mytable">
  <tbody>
  <tr>
    <th width="150">商品名称</th>
    <th>商品编号</th>
    <th>销售价</th>
    <th>数量</th>
    <th>已退数量</th>
    <th>总金额</th>
  </tr>
  {{foreach from=$product item=item}}
  <tr>
    <td>{{$item.goods_name}}</td>
    <td>{{$item.product_sn}}</td>
    <td>￥{{$item.sale_price}}</td>
    <td>{{$item.number}}</td>
    <td>{{$item.return_number}}</td>
    <td>￥{{$item.amount}}</td>
  </tr>
  {{if  $item.child}}
  {{foreach from=$item.child item=a}}
  <tr>
    <td style="padding-left:40px">{{$a.goods_name}}</td>
    <td>{{$a.product_sn}}</td>
    <td>￥{{$a.sale_price}}</td>
    <td>{{$a.number}}</td>
    <td>{{$a.return_number}}</td>
    <td>￥{{$a.amount}}</td>
  </tr>
  {{/foreach}}
  {{/if}}
  {{/foreach}}
  </tbody>
</table>
<br>
<table >
  <tbody>
	<tr>
		<th width="150">商品总金额：</th>
		<td>￥{{$order.price_goods}}</td>
	</tr>
	<tr bgcolor="#F0F1F2">
		<th>运输费：</th>
		<td>￥{{$order.price_logistic}}</td>
	</tr>
	<tr>
		<th>订单总金额：</th>
		<td>￥{{$order.price_order}}</td>
	</tr>
	<tr bgcolor="#F0F1F2">
		<th>调整金额：</th>
		<td>￥{{$order.price_adjust}}</td>
	<tr bgcolor="#F0F1F2">
		<th>已支付金额：</th>
		<td>￥{{$order.price_payed+$order.price_from_return}}</td>
	</tr>
    {{if $blance<0}}
        <tr>
            <th>需退款金额：</th>
            <td>￥{{$blance|replace:"-":""}}</td>
        </tr>
    {{elseif $blance>0}}
        <tr>
            <th>需支付金额：</th>
            <td>￥{{$blance}}</td>
        </tr>
    {{/if}}
    <br>
    </tbody>
</table>

<table>
  <tbody>
	<tr><th width="150">开票单位名称：</th><td>{{$order.invoice}}</td></tr>
  </tbody>
</table>
<br>
<table>
    <tr>
    <th width="150">订单留言：</th>
    <td>{{$order.note}}</td>
    </tr>
</table>
<br />
</tbody>
<table>

{{if $bank.type==1}}
<tr><th width="150">开户行名称：</th><td>{{$bank.bank}}</td></tr>
<tr><th>帐号：</th><td>{{$bank.account}}</td></tr>
<tr><th>开户名：</th><td>{{$bank.user}}</td></tr>
{{elseif $bank.type==2}}
<tr><th>汇款地址：</th><td>{{$bank.address}}</td></tr>
<tr><th>邮编：</th><td>{{$bank.zip}}</td></tr>
<tr><th>姓名：</th><td>{{$bank.name}}</td></tr>
{{elseif $bank.type==3}}</td></tr>
<tr><th>帐户余额支付</th><td></td></tr>
{{/if}}

</table>
<br>
<table>
  <tbody>
<tr>
<td>
<input type="button" onclick="Gurl();" value="返回列表">
</td>
</tr>
</tbody>
</table>
</table>