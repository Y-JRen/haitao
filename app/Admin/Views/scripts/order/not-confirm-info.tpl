<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
#myform td,#myform th{padding-left:10px;}
.goods_table{border:1px solid #ddd;border-left:0;border-top:0;border-collapse:collapse;border-spacing:0;margin-top:15px;}
.goods_table td{border-left:1px solid #ddd;border-top:1px solid #ddd;}
.goods_table th{border-left:1px solid #ddd;border-top:1px solid #ddd;background:#eee;}
</style>

{{if $order.parent_batch_sn}}
<div style="margin:0 auto; text-align:center; color:red;">
<span style="cursor:pointer;" onclick="G('{{url param.action=info param.batch_sn=$order.parent_batch_sn}}')">换货单 [父单号：{{$order.parent_batch_sn}}]</span>
</div>
{{/if}}

<form id="myform">
<div style="border-bottom:1px solid #CCC;">
<table width="50%" style="float:left;border-right:1px solid #CCC;">
<tr bgcolor="#F0F1F2">
  <th width="21%" height="30"  >单据编号：</th>
  <td width="79%" height="30"  >{{$order.batch_sn}}</td>
</tr>
<tr><th height="30">下单日期：</th>
<td height="30">{{$order.add_time}}</td>
</tr>
<tr bgcolor="#F0F1F2"><th height="30">用户名称：</th>
<td height="30">{{$order.user_name}} {{if $order.rank_id}}({{$order.rank_id}}){{/if}}</td></tr>
<tr>
  <th height="30">下单类型：</th>
  <td height="30">
    {{if $order.shop_id==1}}
      官网下单 ({{if $order.source eq 0}}后台下单{{elseif $order.source eq 1}}会员下单{{elseif $order.source eq 2}}电话下单{{elseif $order.source eq 3}}匿名下单{{elseif $order.source eq 4}}试用下单{{/if}})
    {{elseif $order.shop_id==3}}
      国药内购平台下单 ({{if $order.source eq 0}}后台下单{{elseif $order.source eq 1}}会员下单{{elseif $order.source eq 2}}电话下单{{elseif $order.source eq 3}}匿名下单{{elseif $order.source eq 4}}试用下单{{/if}})
    {{elseif $order.type==5}}
    赠送下单
    {{elseif $order.type==7}}
    内购下单
    {{elseif $order.type==10}}
    呼入下单 ({{if $order.source eq 0}}后台下单{{elseif $order.source eq 1}}会员下单{{elseif $order.source eq 2}}电话下单{{elseif $order.source eq 3}}匿名下单{{/if}})
    {{elseif $order.type==11}}
    呼出下单 ({{if $order.source eq 0}}后台下单{{elseif $order.source eq 1}}会员下单{{elseif $order.source eq 2}}电话下单{{elseif $order.source eq 3}}匿名下单{{/if}})
    {{elseif $order.type==12}}
    咨询下单 ({{if $order.source eq 0}}后台下单{{elseif $order.source eq 1}}会员下单{{elseif $order.source eq 2}}电话下单{{elseif $order.source eq 3}}匿名下单{{/if}})
    {{elseif $order.type==13}}
    渠道下单 (渠道订单号：{{$order.external_order_sn}})	
    {{elseif $order.type==14 && $order.user_name == 'batch_channel'}}
    购销下单
	{{elseif $order.type==14 && $order.user_name == 'credit-channel'}}
	赊销下单
    {{elseif $order.type==14}}
    渠道补单 {{if $order.external_order_sn}}(渠道订单号：{{$order.external_order_sn}}){{/if}}
    {{elseif $order.type==15}}
    其它下单
    {{elseif $order.type==16}}
    直供下单
    {{elseif $order.type==17}}
    试用下单
    {{elseif $order.type==18}}
    渠道分销 [{{if $order.distribution_type}}刷单{{else}}销售单{{/if}}]
    {{foreach from=$areas item=item key=key}}
      {{if $key eq $distributionArea[$order.user_name]}}
        ({{$item}})
      {{/if}}
    {{/foreach}}
    {{/if}}
    {{if $order.type ne 18}}
      ({{$areas[$order.lid]}}发货)
    {{/if}}
  </td>
</tr>

<tr bgcolor="#F0F1F2">
  <th height="30" >是否接受回访：</th>
  <td height="30">{{if $order.is_visit}}是{{else}}否{{/if}}</td>
</tr>
<tr>
  <th height="30">是否满意不退货：</th>
  <td height="30">{{if $order.is_fav eq 1}}是{{else}}否{{/if}}</td>
</tr>
</table>

<div style="width:200px; float:left;" id="adddiv_{{$order.batch_sn}}"><input type="button" value="查看收货信息" style="width:120px;height:40px;" onclick="chkAddressinfo('{{$order.batch_sn}}','{{$order.user_id}}');"/></div>	

<table width="48%" style="display:none; float:right;" id="addinfo_{{$order.batch_sn}}">
<tr bgcolor="#F0F1F2"><th width="80" height="30">收货人：</th>
<td width="170" height="30" id="addr_consignee">{{$order.addr_consignee}}</td>
<td width="213" height="30" >{{if $order.lock_name == $auth['admin_name']}}
  <input type="button" value="编辑收货人信息" onclick="G('/admin/order/edit-address/batch_sn/{{$order.batch_sn}}')" >{{/if}}
  </td>
</tr>
<tr><th width="80" height="30">联系电话：</th>
<td height="30" colspan="2" id="addr_tel">{{$order.addr_tel}}</td></tr>
<tr bgcolor="#F0F1F2"><th width="80" height="30">手机：</th>
<td height="30" colspan="2" id="addr_mobile">{{$order.addr_mobile}}    邮箱：{{$order.addr_email}}</td></tr>
<tr bgcolor="#F0F1F2">
  <th width="80" height="30">地区：</th>
  <td height="30" colspan="2">{{$order.addr_province}}{{$order.addr_city}}{{$order.addr_area}}</td>
</tr>
<tr bgcolor="#F0F1F2"><th width="80" height="30">收货地址：</th>
<td height="30" colspan="2" id="addr_address">{{$order.addr_address}}</td></tr>
<tr bgcolor="#F0F1F2"><th width="80" height="30">英文地址：</th>
<td height="30" colspan="2" id="addr_address">{{$order.addr_eng_address}}</td></tr>
<tr><th width="80" height="30">邮政编码：</th>
<td height="30" colspan="2" id="addr_zip">{{$order.addr_zip}}</td></tr>
</table>
<div style="clear:both;"></div>
</div>
<table style="border-bottom:1px solid #CCC;" width="100%">
<tr bgcolor="#F0F1F2"><td width="117" height="30" align="left">付款方式：</td>
<td height="30" align="left">
{{if $order.lock_name==$auth['admin_name']}}
	<select name='pay_type' id="pay_type">
	    {{if $order.pay_type eq 'no_pay' || $order.parent_batch_sn}}
	      <option value="no_pay" {{if $pay_type=='no_pay'}}selected="selected"{{/if}}>无需支付</option>
	    {{else}}
		  {{if $order.user_id lt 100 && $order.user_id neq 5}}
		    <!--<option value="cod" {{if $pay_type=='cod'}}selected="selected"{{/if}}>货到付款</option>-->
		    <option value="bank" {{if $pay_type=='bank'}}selected="selected"{{/if}}>银行打款</option>
		    <option value="cash" {{if $pay_type=='cash'}}selected="selected"{{/if}}>现金支付</option>
		    <option value="easipay" {{if $pay_type=='easipay'}}selected="selected"{{/if}}>东方支付</option>
		    <option value="external" {{if $pay_type=='external'}}selected="selected"{{/if}}>渠道支付</option>
		    <!--<option value="externalself" {{if $pay_type=='externalself'}}selected="selected"{{/if}}>渠道代发货自提</option>-->
		  {{else}}
		    {{if $notChangePayType}}
		      <option value="{{$order.pay_type}}">{{$order.pay_name}}</option>
		    {{else}}
		      {{foreach from=$payment item=item}}
		        <option value={{$item.pay_type}} {{if $pay_type==$item.pay_type}}selected="selected"{{/if}}>{{$item.name}}</option>
		      {{/foreach}}
		    {{/if}}
		  {{/if}}
		{{/if}}
	</select>
{{else}}
	{{$order.pay_name}}
{{/if}}
</td>
</tr>
</table>

<table width="100%" class="goods_table" style="border-bottom:0;">
<tr>
<th height="30" align="left">商品名称</th>
<th height="30" align="left">商品规格</th>
<th height="30" align="left">商品编号</th>
<th height="30" align="left">商品销售均价</th>
<th height="30" align="left">销售价</th>
<th height="30">行邮税</th>
<th height="30" align="left">数量</th>
<th height="30" align="left">总金额</th>
</tr>
{{foreach from=$product item=item}}
<tr>
<td height="30">{{$item.goods_name}} {{if $item.remark}}<font color="#FF0000">{{$item.remark}}</font>{{/if}}</td>
<td height="30">{{$item.goods_style}}&nbsp;</td>
<td height="30">
  {{if $item.gift_card}}
    {{foreach from=$item.gift_card item=card}}
      {{$card.card_sn}}<br>
    {{/foreach}}
  {{elseif $item.vitual_goods}}
    {{foreach from=$item.vitual_goods item=vitual}}
      {{$vitual.sn}}<br>
    {{/foreach}}
  {{else}}
    {{$item.product_sn}}
  {{/if}}
</td>
<td height="30">{{$item.eq_price}}</td>
<td height="30">{{$item.sale_price}}</td>
<td height="30">{{$item.tax}}</td>
<td height="30">{{$item.number}}</td>
<td height="30">{{$item.amount}}</td>
</tr>
	{{if $item.child}}
		{{foreach from=$item.child item=a}}
		<tr>
		<td height="30" style="padding-left:20px">{{$a.goods_name}}</td>
        <td height="30">{{$a.goods_style}}&nbsp;</td>
		<td height="30">{{$a.product_sn}}</td>
		<td height="30">{{$a.eq_price}}</td>
	    <td height="30">{{$a.avg_price}}</td>
		<td height="30">{{$a.sale_price}}</td>
		<td height="30">{{$a.number}}</td>
		<td height="30">{{if $a.type neq 5}}{{$a.amount}}{{/if}}</td>
		</tr>
		{{/foreach}}
	{{/if}}
{{/foreach}}
<tr>
  <td colspan="8">{{if $order.lock_name == $auth['admin_name']}}<input type="button" value="编辑/添加商品" onclick="G(' /admin/order/edit-order-batch-goods/batch_sn/{{$order.batch_sn}}')" >{{/if}}</td>
  </tr>
</table>
<table width="100%" class="goods_table">
<tr><td width="117" height="30"><strong>商品总金额：</strong></td>
<td height="30">{{$order.price_goods}}</td>
</tr>
<tr><td width="117" height="30"><strong>商品总行邮税：</strong></td>
<td height="30">{{$order.tax}}</td>
</tr>
<tr bgcolor="#F0F1F2"><td width="117" height="30"><strong>运输费：</strong></td>
<td height="30"><input type="text" name="price_logistic" id="price_logistic" value="{{$order.price_logistic|default:0}}" size="8"></td>
</tr>
<tr><td width="117" height="30"><strong>订单总金额：</strong></td>
<td height="30">{{$order.price_order}}</td>
</tr>
<tr bgcolor="#F0F1F2">
  <td width="117" height="30"><strong>调整金额：</strong></td>
  <td height="30">{{$order.price_adjust}}</td>
</tr>
<tr bgcolor="#F0F1F2">
  <td width="117" height="30" bgcolor="#ffffff"><strong>已支付金额：</strong></td>
  <td height="30" bgcolor="#ffffff">
    <!--<input type="text" name="price_payed" value="{{$order.price_payed+$order.price_from_return}}" size="3">-->
    {{$order.price_payed+$order.price_from_return}}
  </td>
</tr>
{{if $order.gift_card_payed > 0}}
	<tr>
	<td width="117" height="30" bgcolor="#F0F1F2"><strong>礼品卡抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$order.gift_card_payed}}</td>
	</tr>
{{/if}}
{{if $order.point_payed > 0}}
	<tr>
	<td width="117" height="30" bgcolor="#F0F1F2"><strong>积分抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$order.point_payed}}</td>
	</tr>
{{/if}}
{{if $order.account_payed > 0}}
	<tr>
	<td width="117" height="30" bgcolor="#F0F1F2"><strong>账户余额抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$order.account_payed}}</td>
	</tr>
{{/if}}
{{if $detail.other.price_must_pay}}
	<tr>
	<td width="117" height="30" bgcolor="#F0F1F2"><strong>需支付金额：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$detail.other.price_must_pay}}</td>
	</tr>
{{/if}}
<tr bgcolor="#F0F1F2">
  <td width="117" height="30" bgcolor="#ffffff">&nbsp;</td>
  <td height="30" bgcolor="#ffffff">&nbsp;</td>
</tr>
{{if $detail.finance.price_return_money}}
	<tr>
	<td width="117" height="30" bgcolor="#F0F1F2"><strong>需退款现金金额：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$detail.finance.price_return_money}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_point}}
	<tr>
	<td width="117" height="30"><strong>需退积分金额：</strong></td>
	<td height="30">{{$detail.finance.price_return_point}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_account}}
	<tr>
	<td width="117" height="30"><strong>需退账户余额金额：</strong></td>
	<td height="30">{{$detail.finance.price_return_account}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_gift}}
	<tr>
	<td width="117" height="30" align="left" bgcolor="#F0F1F2"><strong>需退礼品卡金额：</strong></td>
	<td height="30" align="left" bgcolor="#F0F1F2">{{$detail.finance.price_return_gift}}</td>
	</tr>
{{/if}}

</table>

{{if $order.type==5}}
<table width="100%">
	<tr>
	<th width="117" height="30">赠送人：</th>
	<td height="30"><input type="text" value="{{$order.giftbywho}}" onchange="giftByWho({{$order.order_sn}}, this.value)" /></td>
	</tr>
</table>
<br />
{{/if}}

{{if $payLog}}
<table width="100%">
<tr>
<th height="30" align="left">支付接口订单单据号</th>
<th height="30" align="left">订单SN</th>
<th height="30" align="left">支付时间</th>
<th height="30" align="left">支付接口</th>
<th height="30" align="left">支付金额</th>
</tr>
{{foreach from=$payLog item=tmp}}
<tr>
<td height="30">{{$tmp.pay_log_id}}</td>
<td height="30">{{$tmp.batch_sn}}</td>
<td height="30">{{$tmp.add_time}}</td>
<td height="30">{{$tmp.pay_type}}</td>
<td height="30">{{$tmp.pay}}</td>
</tr>
{{/foreach}}
</table>
<br />
{{/if}}
{{if  $giftCardLog}}
<table width="100%">
<tr height="30" align="left">
<th align="left">礼品卡抵扣卡号</th>
<th align="left">抵扣时间</th>
<th align="left">抵扣金额</th>
<th align="left">操作用户</th>
<th align="left">操作</th>
</tr>
{{foreach from=$giftCardLog item=tmp}}
<tr>
<td>{{$tmp.card_sn}}</td>
<td>{{$tmp.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
<td>{{$tmp.price}}</td>
<td>{{$tmp.user_name}}</td>
<td>
  {{if $tmp.can_return}}
    <input type="button" value="取消" onclick="confirmed('确定要取消该礼品卡的抵扣吗?', $('myform'), '/admin/order/return-gift-card/batch_sn/{{$order.batch_sn}}/log_id/{{$tmp.log_id}}');">
  {{/if}}
</td>
</tr>
{{/foreach}}
</table>
<br />
{{/if}}

{{if  $finance}}
<table width="100%">
<tr>
<th height="30" align="left">时间</th>
<th height="30" align="left">财务退款状态</th>
<th height="30" align="left">金额</th>
<th height="30" align="left">积分</th>
<th height="30" align="left">礼品卡</th>
<th height="30"> align="left"备注</th>
</tr>
{{foreach from=$finance item=tmp}}
<tr>
<td height="30">{{$tmp.add_time_label}}</td>
<td height="30">{{$tmp.status_label}}</td>
<td height="30">{{$tmp.pay_label}}</td>
<td height="30">{{$tmp.point_label}}</td>
<td height="30">{{$tmp.gift_label}}</td>
<td height="30">{{$tmp.note}}</td>
</tr>
{{/foreach}}
</table>
<br />
{{/if}}


{{if $order.lock_name == $auth['admin_name']}}
<table width="100%">
<tr>
  <th width="117" height="30">&nbsp;</th>
  <td height="30">&nbsp;</td>
</tr>
<tr>
  <th width="117" height="30">调整金额：</th>
  <td height="30"><input name="price_adjust" type="text" id="price_adjust" size="6">[负数：优惠减少金额] [正数：订单需另增加的金额]</td>
</tr>
<tr>
<th width="117" height="30">调整金额备注：</th>
<td height="30"><input name="note_adjust" type="text" id="note_adjust" size="80"></td>
</tr>
<tr>
<th width="117" height="30"></th>
<td height="30"><input type="button" value="调整" onclick="{{if $order.type eq 16}}alert('直供单不能调整金额!');{{else}}if (checkAdjust()) confirmed('确定调整金额?', $('myform'), '{{url param.action=add-price-adjust}}');{{/if}}" /></td>
</tr>
</table>
{{if $detail.other.price_must_pay && $order.type ne 16}}
<table width="100%">
<tr>
  <th width="117" height="30">&nbsp;</th>
  <td height="30">&nbsp;</td>
</tr>
<tr>
  <th width="117" height="30">礼品卡抵扣卡号：</th>
  <td height="30">
    <input name="gift_card_sn" type="text" id="gift_card_sn" size="15" onblur="checkGiftCard(this.value)">
    &nbsp;&nbsp;&nbsp;&nbsp;
    <span id="gift_card_area" style="display:none">
      <span id="gift_card_amount_area"></span>
      <input type="hidden" id="gift_card_amount">
      &nbsp;&nbsp;&nbsp;&nbsp;
      礼品卡抵扣金额：&nbsp;&nbsp;&nbsp;<input name="gift_card_pay_amount" type="text" id="gift_card_pay_amount" size="6" onblur="checkGiftCardPayAmount(this.value)">
      <input type="button" value="抵扣" onclick="confirmed('确定要抵扣吗?', $('myform'), '{{url param.action=add-gift-card-payment}}');" />
    </span>
  </td>
</tr>
{{if $canAccountPayment}}
<tr>
  <th width="117" height="30">账户余额抵扣：</th>
  <td height="30">
      <input name="account_pay_amount" type="text" id="account_pay_amount" size="6" onblur="checkAccountPayAmount(this.value)">
      <input type="button" value="抵扣" onclick="confirmed('确定要抵扣吗?', $('myform'), '{{url param.action=add-account-payment}}');" />
  </td>
</tr>
{{/if}}
</table>
{{/if}}
{{/if}}
<br>
<table>
<tr>
<th width="150">订单留言：</th>
<td id="note">{{$order.note}}</td>
<td>&nbsp;</td>
</tr>
</table>
	<br>
{{if $order.lock_name == $auth['admin_name']}}
	
	<table>
	<tr>
	  <th width="117">物流打印备注：</th>
	  <td><textarea name="note_print" cols="39" rows="3" style="width:330px; height:45px;">{{$order.note_print}}</textarea></td>
	  </tr>
	<tr>
	  <th width="117">物流部门备注：</th>
	  <td><textarea name="note_logistic" cols="39" rows="3" style="width:330px; height:45px;">{{$order.note_logistic}}</textarea></td>
	  </tr>
	
		<tr>
		  <th width="117">订单取消备注：</th>
		  <td><textarea name="note_staff_cancel" id="note_staff_cancel" cols="39" rows="3" style="width:330px; height:45px;"></textarea></td>
	  </tr>
	  <tr>
  <td width="117" height="30">
    <strong>开票信息：</strong>
  </td>
  <td height="30">
    <input type="radio" name="invoice_type" value="0" {{if !$order.invoice_type}}checked{{/if}} onclick="changeInvoiceType(this.value)">不开票
    <input type="radio" name="invoice_type" value="1" {{if $order.invoice_type eq 1}}checked{{/if}} onclick="changeInvoiceType(this.value)">个人
    <input type="radio" name="invoice_type" value="2" {{if $order.invoice_type eq 2}}checked{{/if}} onclick="changeInvoiceType(this.value)">企业
    &nbsp;&nbsp;&nbsp;
    <span id="invoiceContent" {{if !$order.invoice_type}}style="display:none"{{/if}}>
    发票内容：
    <select name="invoice_content">
      <option value="保健品" {{if $order.invoice_content eq '保健品'}}selected{{/if}}>保健品</option>
      <option value="保健食品" {{if $order.invoice_content eq '保健食品'}}selected{{/if}}>保健食品</option>
      <option value="明细" {{if $order.invoice_content eq '明细'}}selected{{/if}}>明细</option>
      <option value="明细(产品代码)" {{if $order.invoice_content eq '明细(产品代码)'}}selected{{/if}}>明细(产品代码)</option>
    </select>
    </span>
    <span id="invoiceArea" {{if $order.invoice_type ne 2}}style="display:none"{{/if}}>
    企业抬头：<input type="text" name="invoice" value="{{$order.invoice}}">
    </span>
  </td>
  </tr>
		<tr>
		  <td width="117">&nbsp;</td>
		  <td>
{{if $order.is_freeze}}		  
	<input type="button" value="保存信息" onclick="confirmed('保存信息', $('myform'), '{{url param.action=save}}')" />
{{else}}
	{{if $finance.type != 1}}
	    <input type="button" value="订单保存" onclick="confirmed('订单保存', $('myform'), '{{url param.action=save}}')" />
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    {{if $canConfirm}}
		<input type="button" value="订单确认" onclick="confirmed('订单确认', $('myform'), '{{url param.action=confirm}}')" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		{{/if}}
	{{/if}}
	{{if $finance.type != 1 and $finance.type != 2}}
		<input type="button" value="订单取消" onclick="if ($('note_staff_cancel').value==''){alert('请填写订单取消备注');return false;}confirmed('订单取消', $('myform'), '{{url param.action=confirm-cancel param.mod=not-confirm-list}}')" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		{{if !$order.parent_batch_sn}}
		<input type="button" value="垃圾订单" onclick="confirmed('垃圾订单', $('myform'), '{{url param.action=invalid}}')" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		{{/if}}
	{{/if}}
	<!--<input type="button" value="挂起" onclick="confirmed('订单挂起', $('myform'), '{{url param.action=hang}}')" />-->
{{/if}}		  </td>
		</tr>
	</table>
<br />
{{/if}}

{{if $noteStaff}}
<br>
<table width=100%>
<tr align="left">
<th width="150" height="30">客服</th>
<th height="30">客服备注内容</th>
<th height="30">客服备注日期</th>
</tr>
{{foreach from=$noteStaff item=data}}
<tr>
<td height="30">{{$data.admin_name}}</td>
<td height="30">
{{$data.content}}
</td>
<td height="30">{{$data.date}}</td>
</tr>
{{/foreach}}
</table>
<br>
{{/if}}

<table width="100%">
<tr>
<th width="117" height="30">客服添加新备注：</th>
<td height="30">
<input type="text" name="note_staff" id="note_staff" size="80">
<input type="button" value="添加" onclick="if($('note_staff').value==''){alert('备注内容不能为空');return false;}ajax_submit($('myform'), '{{url param.action=add-note-staff param.mod=not-confirm-info}}');" /></td>
</tr>
</table>
<br>


{{if $logs}}
<br>
<table width=100%>
<tr align="left">
<th width="150" height="30" align="left">操作者</th>
<th width="200" height="30" align="left">操作时间</th>
<th height="30">操作信息</th>
</tr>
{{foreach from=$logs item=item}}
<tr>
<td height="30">{{$item.admin_name}}</td>
<td height="30">{{$item.add_time}}</td>
<td height="30">{{$item.title}} {{if $item.note}}[{{$item.note}}]{{/if}}</td>
</tr>
{{/foreach}}
</table>
{{/if}}
</form>
<script>
	
function confirmed(str, obj, url) {
	var blance = parseFloat('{{$blance}}') + parseFloat({{$detail.other.fareAll|default:0}});
	if (isNaN(blance)) {
		blance = 0;
	}
	var addr_consignee = $('addr_consignee').innerHTML;
	var addr_address = $('addr_address').innerHTML;
	//var addr_tel = $('addr_tel').innerHTML;
	//var addr_mobile = $('addr_mobile').innerHTML;
	if (!addr_consignee || !addr_address) {
		alert('收货人信息不能为空');
		return false;
	}
	if ($('pay_type')) {
		if (blance<=0 && $('pay_type').value=='cod') {//需退款 和 已经支付 不能选择 货到付款
			alert('应收金额为零的订单，不能选择货到付款方式');
			return false;
		}
		if (blance>0 && $('pay_type').value == '') {//需支付 支付方式不能为空
			alert('请选择支付方式');
			return false;
		}
	}
	if (confirm('确认执行 "' + str + '" 操作？')) {
		ajax_submit(obj, url);
	}
}
function editNoteStaff(obj,time)
{
	var url = filterUrl('{{url param.action=edit-note-staff}}/batch_sn/{{$batchSN}}/time/' + time + '/note_staff/' + obj.value, 'batch_sn');	
    new Request({
        url: url,
        onSuccess:function(data){
			alert('修改成功');
        }
    }).send();
}

function checkTransaction()
{
	if (!$('transaction_price').value) {
		alert('渠道金额不能为空');
		return false;
	}
	return true;
}

function checkAdjust()
{
	if (!$('price_adjust').value) {
		alert('调整金额不能为空');
		return false;
	}
	if (!$('note_adjust').value) {
		alert('调整金额备注不能为空');
		return false;
	}
	return true;
}
function checkNoteStaff()
{
	if (!$('note_staff').value) {
		alert('客户备注不能为空');
		return false;
	}
	return true;
}

function giftByWho(order_sn, val){
	if(order_sn==''){alert('参数错误');return false;}
	if(val==''){alert('赠送人必填');return false;}
	new Request({
		url:'/admin/order/giftbywho/order_sn/'+order_sn+'/val/'+val,
		onSuccess:function(msg){
			if(msg != 'ok'){
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

function changeInvoiceType(value)
{
    if (value == 2) {
        $('invoiceArea').style.display = '';
    }
    else {
        $('invoiceArea').style.display = 'none';
    }
    if (value == 0) {
        $('invoiceContent').style.display = 'none';
    }
    else {
        $('invoiceContent').style.display = '';
    }
}
//查询收货信息
function chkAddressinfo(orderno,userid){

	$("adddiv_"+orderno).setStyle('display', 'none'); 
	$("addinfo_"+orderno).setStyle('display', 'block'); 
}

function checkGiftCard(card_sn)
{
    $('gift_card_pay_amount').value = '';
    
    if (card_sn == '')  return;
    
    new Request({
		url:'/admin/gift-card/check/card_sn/' + card_sn + '/batch_sn/{{$order.batch_sn}}',
		onSuccess:function(msg){
		    if (msg.substring(0,5) == 'error') {
		        if (msg == 'error') {
		            alert('未知错误!');
		        }
		        else if (msg == 'errorCard') {
		            alert('找不到礼品卡!');
		        }
		        else if (msg == 'errorOrder') {
		            alert('找不到订单!');
		        }
		        else if (msg == 'errorInvalid') {
		            alert('礼品卡已无效!');
		        }
		        else if (msg == 'errorInactive') {
		            alert('礼品卡未激活!');
		        }
		        else if (msg == 'errorUserNameError') {
		            alert('礼品卡已绑定其他用户!');
		        }
		        else if (msg == 'errorExpired') {
		            alert('礼品卡已过期!');
		        }
		        else if (msg == 'errorPrice') {
		            alert('礼品卡余额为0!');
		        }
		        else if (msg == 'errorNeedPay') {
		            alert('订单不需要支付!');
		        }
		        else if (msg == 'errorHasGiftCard') {
		            alert('不能抵扣包含礼品卡的订单!');
		        }
		        $('gift_card_area').style.display = 'none';
		        $('gift_card_sn').value = '';
		        $('gift_card_amount').value = 0;
		        $('gift_card_sn').focus();
		    }
		    else {
		        $('gift_card_amount_area').innerHTML = '可用金额：' + msg;
		        $('gift_card_amount').value = msg;
		        $('gift_card_area').style.display = '';
		    }
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

function checkGiftCardPayAmount(amount)
{
    if (amount == '' || amount == 0)    return false;
    amount = parseFloat(amount);
    if (isNaN(amount) || amount <= 0) {
        alert('抵扣金额错误!');
    }
    else if (amount > $('gift_card_amount').value) {
        alert('抵扣金额不能大于礼品卡余额!');
    }
    else if (amount > {{$detail.other.price_must_pay|default:0}}) {
        alert('抵扣金额不能大于需支付金额!');
    }
    else {
        return true;
    }
    $('gift_card_pay_amount').value = '';
    $('gift_card_pay_amount').focus();
    return false;
}

function checkAccountPayAmount(amount)
{
    if (amount == '' || amount == 0)    return false;
    amount = parseFloat(amount);
    if (isNaN(amount) || amount <= 0) {
        alert('抵扣金额错误!');
    }
    else if (amount > {{$detail.other.price_must_pay|default:0}}) {
        alert('抵扣金额不能大于需支付金额!');
    }
    else {
        return true;
    }
    $('amount_pay_amount').value = '';
    $('amount_pay_amount').focus();
    return false;
}

</script>
