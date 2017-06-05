
<style type="text/css">

.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<table>
<tr bgcolor="#F0F1F2">
  <th width="150">单据编号：</th>
  <td>{{$order.batch_sn}}</td>
</tr>
<tr><th>下单日期：</th>
<td>{{$order.add_time}}</td>
</tr>
<tr><th>订单状态：</th>
<td>
{{if $order.status_logistic == 0}}
未确认订单
{{elseif $order.status_logistic == 1}}
待收款订单
{{elseif $order.status_logistic == 2}}
待发货订单
{{elseif $order.status_logistic == 3}}
已发货订单
{{elseif $order.status_logistic == 4}}
客户已签收订单
{{elseif $order.status_logistic == 5}}
拒收
{{/if}}</td>
</tr>
<tr bgcolor="#F0F1F2"><th>用户名称：</th>
<td>{{$order.user_name}}</td></tr>
<tr >
  <th>是否电话订单：</th>
  <td>{{if $order.is_tel}}是{{else}}否{{/if}}</td>
</tr>
<tr bgcolor="#F0F1F2">
  <th>是否接受回访：</th>
  <td>{{if $order.is_visit}}是{{else}}否{{/if}}</td>
</tr>
<tr>
  <th>是否满意不退货：</th>
  <td>{{if $order.is_fav eq 1}}是{{else}}否{{/if}}</td>
</tr>
</table>
<br>

<table class="mytable" >
<tr  bgcolor="#F0F1F2"><th width="150">收货人：</th>
<td>{{$order.addr_consignee}}</td>
</tr>
<tr><th>联系电话：</th>
<td colspan="2">{{$order.addr_tel}}</td></tr>
<tr bgcolor="#F0F1F2"><th>手机：</th>
<td colspan="2">{{$order.addr_mobile}}</td></tr>

<tr bgcolor="#F0F1F2">
  <th>地区：</th>
  <td colspan="2">{{$order.addr_province}} {{$order.addr_city}} {{$order.addr_area}}</td>
</tr>
<tr bgcolor="#F0F1F2"><th>收货地址：</th>
<td colspan="2">{{$order.addr_address}}</td></tr>
<tr><th>邮政编码：</th>
<td colspan="2">{{$order.addr_zip}}</td></tr>
</table>


<br>
<table>
<tr bgcolor="#F0F1F2"><th width="150">付款方式：</th>
<td>{{$order.pay_name}}</td>
</tr>
</table>
<br>
<table>
<tr bgcolor="#F0F1F2"><th width="150">配送方式：</th>
<td>{{$order.logistic_name}}</td>
</tr>
</table>
<br>
<table class="mytable">
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
</table>
<br>
<table >
	<tr>
		<th width="150">商品总金额：</th>
		<td>￥{{$order.price_goods}}</td>
	</tr>
	<tr>
		<th width="150">商品总行邮税：</th>
		<td>￥{{$order.tax}}</td>
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

</table>
{{if $noteStaff}}
<br>
<table width=100%>
<tr>
<th width="150">客服</th>
<th width="550">客服备注内容</th>
<th>客服备注日期</th>
</tr>
{{foreach from=$noteStaff item=data}}
<tr>
<td>{{$data.admin_name}}</td>
<td>
{{$data.content}}
</td>
<td>{{$data.date}}</td>
</tr>
{{/foreach}}
</table>
<br>
{{/if}}
<table>
<tr>
<th width="150">物流打印备注：</th>
<td>{{$order.note_print}}</td>
</tr>
<tr>
<th>物流部门备注：</th>
<td>{{$order.note_logistic}}</td>
</tr>
<tr><th>开票单位名称：</th><td>{{$order.invoice}}</td></tr>
</table>
<br>
<table>
<tr>
<th width="150">订单留言：</th>
<td>{{$order.note}}</td>
</tr>
</table>
<br />
{{if $logs}}
<br>
<table width=100%>
<tr>
<th width="150">操作者</th>
<th width="200">操作时间</th>
<th>操作信息</th>
</tr>
{{foreach from=$logs item=item}}
<tr>
<td>{{$item.admin_name}}</td>
<td>{{$item.add_time}}</td>
<td>{{$item.title}} {{if $item.note}}[{{$item.note}}]{{/if}}</td>
</tr>
{{/foreach}}
</table>
{{/if}}
