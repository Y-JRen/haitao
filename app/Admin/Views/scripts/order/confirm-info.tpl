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
  <th width="150" height="30">单据编号：</th>
  <td height="30">{{$order.batch_sn}}</td>
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
  <th height="30">是否接受回访：</th>
  <td height="30">{{if $order.is_visit}}是{{else}}否{{/if}}</td>
</tr>
<tr>
  <th height="30">是否满意不退货：</th>
  <td height="30">{{if $order.is_fav eq 1}}是{{else}}否{{/if}}</td>
</tr>
</table>


<div style="width:200px; float:left;" id="adddiv_{{$order.batch_sn}}"><input type="button" value="查看收货信息" style="width:120px;height:40px;" onclick="chkAddressinfo('{{$order.batch_sn}}','{{$order.user_id}}');"/></div>	

<table width="49%" style="display:none; float:right" id="addinfo_{{$order.batch_sn}}">
<tr bgcolor="#F0F1F2"><th width="80" height="30">收货人：</th>
<td width="170" height="30" id="addr_consignee">{{$order.addr_consignee}}</td>
<td width="213" height="30" >{{if $order.lock_name == $adminName}}
  <input type="button" value="编辑收货人信息" onclick="G('/admin/order/edit-address/batch_sn/{{$order.batch_sn}}/order_step/confirm')" >{{/if}}
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
<tr bgcolor="#F0F1F2"><th width="117" height="30">付款方式：</th>
<td height="30">{{$order.pay_name}}</td>
</tr>
</table>


<table width="100%" class="goods_table">
  <tr>
    <th height="30" >商品名称</th>
    <th height="30" >商品规格</th>
    <th height="30" >商品编号</th>
    <th height="30" >商品销售均价</th>
	<th height="30" > 行邮税</th>
    <th height="30" >销售价</th>
    <th height="30" >数量</th>
    <th height="30" >总金额</th>
  </tr>
  {{foreach from=$product item=item}}
  <tr >
    <td height="30">{{$item.goods_name}} {{if $item.remark}}<font color="#FF0000">{{$item.remark}}</font>{{/if}}</td>
     <td height="30">{{$item.goods_style}}&nbsp; </td>   
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
	<td height="30">{{$item.tax}}</td>
    <td height="30">{{$item.sale_price}}</td>
    <td height="30">{{$item.number}}</td>
    <td height="30">{{$item.amount}}</td>
  </tr>
  {{if  $item.child}}
  {{foreach from=$item.child item=a}}
  <tr >
    <td height="30" style="padding-left:20px">{{$a.goods_name}}</td>
    <td height="30">{{$a.goods_style}} &nbsp; </td>
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
</table>
<table width="100%" class="goods_table">
  <tr>
    <td width="150" height="30"><strong>商品总金额：</strong></td>
    <td height="30">{{$order.price_goods}}</td>
  </tr>
  <tr>
  	<td width="117" height="30"><strong>商品总行邮税：</strong></td>
	<td height="30">{{$order.tax}}</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td height="30"><strong>运输费：</strong></td>
    <td height="30">{{$order.price_logistic}}</td>
  </tr>
  <tr>
    <td height="30"><strong>订单总金额：</strong></td>
    <td height="30">{{$order.price_order}}</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td height="30" bgcolor="#F0F1F2"><strong>调整金额：</strong></td>
    <td height="30" bgcolor="#F0F1F2">{{$order.price_adjust}}</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td height="30" bgcolor="#ffffff"><strong>已支付金额：</strong></td>
    <td height="30" bgcolor="#ffffff">{{$order.price_payed+$order.price_from_return}}</td>
  </tr>
{{if $order.gift_card_payed > 0}}
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>礼品卡抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$order.gift_card_payed}}</td>
	</tr>
{{/if}}
{{if $order.point_payed > 0}}
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>积分抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$order.point_payed}}</td>
	</tr>
{{/if}}
{{if $order.account_payed > 0}}
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>账户余额抵扣：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$order.account_payed}}</td>
	</tr>
{{/if}}
{{if $detail.other.price_must_pay}}
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>需支付金额：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$detail.other.price_must_pay}}</td>
	</tr>
{{/if}}
<tr bgcolor="#F0F1F2">
  <td height="30" bgcolor="#ffffff">&nbsp;</td>
  <td height="30" bgcolor="#ffffff">&nbsp;</td>
</tr>
{{if $detail.finance.price_return_money}}
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>需退款现金金额：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$detail.finance.price_return_money}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_point}}
	<tr>
	<td height="30"><strong>需退积分金额：</strong></td>
	<td height="30">{{$detail.finance.price_return_point}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_account}}
	<tr>
	<td height="30"><strong>需退账户余额金额：</strong></td>
	<td height="30">{{$detail.finance.price_return_account}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_gift}}
	<tr>
	<td height="30" bgcolor="#F0F1F2"><strong>需退礼品卡金额：</strong></td>
	<td height="30" bgcolor="#F0F1F2">{{$detail.finance.price_return_gift}}</td>
	</tr>
{{/if}}
</table>

{{if  $payLog}}
<table width="100%">
<tr>
<th height="30">支付接口订单单据号</th>
<th height="30">订单SN</th>
<th height="30">支付时间</th>
<th height="30">支付接口</th>
<th height="30">支付金额</th>
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
<tr align="left">
<th align="left">礼品卡抵扣卡号</th>
<th align="left">抵扣时间</th>
<th align="left">抵扣金额</th>
<th align="left">操作用户</th>
</tr>
{{foreach from=$giftCardLog item=tmp}}
<tr>
<td>{{$tmp.card_sn}}</td>
<td>{{$tmp.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
<td>{{$tmp.price}}</td>
<td>{{$tmp.user_name}}</td>
</tr>
{{/foreach}}
</table>
<br />
{{/if}}
{{if  $finance}}
<table width="100%">
<tr>
<th height="30">时间</th>
<th height="30">财务退款状态</th>
<th height="30">金额</th>
<th height="30">积分</th>
<th height="30">礼品卡</th>
<th height="30">备注</th>
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

<table width="100%">
<tr>
<th width="117" height="30">物流打印备注：</th>
<td height="30">{{$order.note_print}}</td>
</tr>
<tr>
<th width="117" height="30">物流部门备注：</th>
<td height="30">{{$order.note_logistic}}</td>
</tr>
<tr>
<td><b>开票信息：</b></td>
<td>
{{if $order.invoice_type eq 1}}
个人
{{elseif $order.invoice_type eq 2}}
抬头：{{$order.invoice}}
{{/if}}
&nbsp;&nbsp;&nbsp;&nbsp;
{{if $order.invoice_type eq 1 || $order.invoice_type eq 2}}
开票内容：{{$order.invoice_content}}
{{/if}}
</td>
</tr>
</table>
<br>
<table width="100%">
<tr>
<th width="117" height="30">订单留言：</th>
<td height="30">{{$order.note}}</td>
</tr>
</table>
<br>
{{if $order.lock_name == $adminName}}
<table width="100%">
<tr>
  <th width="117">订单取消备注：</th>
  <td><textarea name="note_staff_cancel" id="note_staff_cancel" cols="39" rows="3" style="width:330px; height:45px;"></textarea></td>
</tr>
</table>
<table>
<tr>
<th></th>
<td>
{{if $order.part_pay eq '1' }}
	请填写本次收款金额：<input name="pay_money" size="8" type="text" value="{{$detail.other.price_must_pay}}" />
{{/if}}
{{if $order.type ne 16 || $adminName eq 'huangchunqing' || $adminName eq 'zhaoli' || $groupID eq 1}}
<input type="button" value="确认收款" onclick="confirmed('确认收款', $('myform'), '{{url param.action=has-pay}}')" />
{{/if}}
<input type="button" value="订单返回" onclick="confirmed('订单返回', $('myform'), '{{url param.action=confirm-back}}')" />
<input type="button" value="订单取消" onclick="if ($('note_staff_cancel').value==''){alert('请填写订单取消备注');return false;}confirmed('订单取消', $('myform'), '{{url param.action=confirm-cancel param.mod=confirm-list}}')" />
<input type="button" onclick="Gurl();" value="返回订单列表">
</td>
</tr>
</table>
{{/if}}
{{if $noteStaff}}
<br>
<table width=100%>
<tr>
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
<table>
<tr>
<th width="117" height="30">客服添加新备注：</th>
<td height="30">
<input type="text" name="note_staff" id="note_staff" size="80">
<input type="button" value="添加" onclick="if($('note_staff').value==''){alert('备注内容不能为空');return false;}ajax_submit($('myform'), '{{url param.action=add-note-staff  param.mod=confirm-info}}');" /></td>
</tr>
</table>
<br>
{{if $logs}}
<br>
<table width=100%>
<tr>
<th width="150" height="30">操作者</th>
<th width="200" height="30">操作时间</th>
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
	if (confirm('确认执行 "' + str + '" 操作？')) {
		ajax_submit(obj, url);
	}
}
//查询收货信息
function chkAddressinfo(orderno,userid){

	$("adddiv_"+orderno).setStyle('display', 'none'); 
	$("addinfo_"+orderno).setStyle('display', 'block'); 
}
</script>
