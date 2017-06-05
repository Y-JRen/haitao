<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
{{if $order.parent_batch_sn}}
<div style="margin:0 auto; text-align:center; color:red;">
<span style="cursor:pointer;" onclick="G('{{url param.action=info param.batch_sn=$order.parent_batch_sn}}')">换货单 [父单号：{{$order.parent_batch_sn}}]</span>
</div>
{{/if}}
<form id="myform">
  <table width="100%" border="0">
    <tr>
      <td width="50%" valign="top"><table width="100%">
        <tr bgcolor="#F0F1F2">
          <td width="150"> 单据编号：</td>
          <td>{{$order.batch_sn}}</td>
        </tr>
        <tr>
          <td>下单日期：</td>
          <td>{{$order.add_time}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>用户名称：</td>
          <td>{{$order.user_name}} {{if $order.rank_id}}({{$order.rank_id}}){{/if}}</td>
        </tr>
        <tr>
          <td>下单类型：</td>
          <td>
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
            渠道下单 {{if $order.status eq 3 && $order.fake_type}}[无款刷单]{{/if}} (渠道订单号：{{$order.external_order_sn}})
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
          <td>是否接受回访：</td>
          <td>{{if $order.is_visit}}是{{else}}否{{/if}}</td>
        </tr>
        <tr>
          <td>是否满意不退货：</td>
          <td>{{if $order.is_fav eq 1}}是{{else}}否{{/if}}</td>
        </tr>
      </table></td>
      <td width="50%" valign="top">
      
      
<div style="width:200px; float:left;" id="adddiv_{{$order.batch_sn}}"><input type="button" value="查看收货信息" style="width:120px;height:40px;" onclick="chkAddressinfo('{{$order.batch_sn}}','{{$order.user_id}}');"/></div>	

<table width="96%" style="display:none; float:left;" id="addinfo_{{$order.batch_sn}}">
<tr bgcolor="#F0F1F2"><th width="80" height="16">收货人：</th>
<td width="170" height="16" id="addr_consignee">{{$order.addr_consignee}}</td>
<td width="213" height="16" >{{if $order.lock_name == $adminName}}
  <input type="button" value="编辑收货人信息" onclick="G('/admin/order/edit-address/batch_sn/{{$order.batch_sn}}')" >{{/if}}
  </td>
</tr>
<tr><th width="120" height="16">联系电话：</th>
<td height="16" colspan="2" id="addr_tel">{{$order.addr_tel}}</td></tr>
<tr bgcolor="#F0F1F2"><th width="80" height="16">手机：</th>
<td height="16" colspan="2" id="addr_mobile">{{$order.addr_mobile}}    邮箱：{{$order.addr_email}}</td></tr>
<tr bgcolor="#F0F1F2">
  <th width="80" height="16">地区：</th>
  <td height="16" colspan="2">{{$order.addr_province}}{{$order.addr_city}}{{$order.addr_area}}</td>
</tr>
<tr bgcolor="#F0F1F2"><th width="120" height="16">收货地址：</th>
<td height="16" colspan="2" id="addr_address">{{$order.addr_address}}</td></tr>
<tr bgcolor="#F0F1F2"><th width="80" height="30">英文地址：</th>
<td height="30" colspan="2" id="addr_address">{{$order.addr_eng_address}}</td></tr>
<tr><th width="120" height="16">邮政编码：</th>
<td height="16" colspan="2" id="addr_zip">{{$order.addr_zip}}</td></tr>
</table>
<div style="clear:both;"></div>
</div>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
<table>
<tr bgcolor="#F0F1F2"><th width="150">付款方式：</td>
<td>{{$order.pay_name}}</td>
</tr>
</table>
<br>
<table>
<tr bgcolor="#F0F1F2"><th width="150">配送方式：</td>
<td>{{$order.logistic_name}}{{if $order.logistic_no}}<span style="cursor:pointer;" onclick="openDiv('/admin/transport/public-view/bill_no/{{$order.batch_sn}}/','ajax','运单号查询',750,400);">[{{$order.logistic_no}}]</span>{{/if}}</td>
</tr>
</table>
<br>
{{if $auth.group_id eq 1 || $auth.group_id eq 11}}
<table>
<tr bgcolor="#F0F1F2"><th width="150">财务金额：</td>
<td>{{$order.balance_amount-$order.balance_point_amount}}</td>
</tr>
</table>
<br>
{{/if}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
  <tr>
    <td>商品名称</td>
    <td>商品规格</td>
    <td>商品编号</td>
    <td>商品销售均价</td>
    <th height="30" > 行邮税</th>
    <td>销售价</td>
    <td>数量</td>
    <td>已退数量</td>
    <td>总金额</td>
  </tr>
  </thead>
  <tbody>
  {{foreach from=$product item=item}}
	{{if $item.product_id > 0}}
	  <tr>
		<td>{{$item.goods_name}} {{if $item.remark}}<font color="#FF0000">{{$item.remark}}</font>{{/if}}</td>
		<td>{{$item.goods_style}}&nbsp; </td>
		<td>
		  {{if $item.card_type eq 'coupon'}}
		    <a href="/admin/coupon/history/card_sn/{{$item.card_sn}}">{{$item.card_sn}}</a>
		  {{else}}
		    {{if $item.gift_card}}
              {{foreach from=$item.gift_card item=card}}
                {{$card.card_sn}}<br>
              {{/foreach}}
            
            {{else}}
              {{$item.product_sn}}
            {{/if}}
		  {{/if}}
		</td>
		<td>{{$item.eq_price}}</td>
		<td height="30">{{$item.tax}}</td>
		<td>{{$item.sale_price}}</td>
		<td>{{$item.number}}</td>
		<td>{{$item.return_number}}</td>
		<td>{{$item.sale_price*$item.number-$item.sale_price*$item.return_number}} </td>
	  </tr>
	  {{else}}
		<tr>
		<td>{{$item.goods_name}} {{if $item.remark}}<font color="#FF0000">{{$item.remark}}</font>{{/if}}</td>
		<td>{{$item.goods_style}}&nbsp; </td>
		<td>{{if $item.card_type eq 'gift'}}{{$item.card_sn}}{{/if}}</td>
		<td>{{$item.eq_price}}</td>
		<td height="30">{{$item.tax}}</td>
		<td>{{$item.sale_price}}</td>
		<td>{{$item.number}}</td>
		<td>{{$item.return_number}}</td>
		<td> {{$item.amount}} </td>
	  </tr>
	  
	  {{/if}}
  
  {{if  $item.child}}
  {{foreach from=$item.child item=a}}
  <tr>
    <td style="padding-left:20px"><font  color="#FF0000">
        {{if $a.type eq 1}}
       		 (活动)
        {{elseif $a.type eq 2}}
       		 (礼券)
        {{elseif $a.type eq 3}}
       		 (帐户余额)
        {{elseif $a.type eq 4}}
       		 (积分)
        {{elseif $a.type eq 5}}
       		 (组合商品)
        {{elseif $a.type eq 6}}
        	(团购商品)
        {{/if}}
    </font>{{$a.goods_name}}</td>
     <td>{{$a.goods_style}} &nbsp;</td>
    <td>{{$a.product_sn}}</td>
    <td>{{$a.eq_price}}</td>
	<td>{{$a.tax}}</td>
    <td>{{$a.sale_price}}</td>
    <td>{{$a.number}}</td>
    <td>{{$a.return_number}}</td>
    <td>{{if $a.type neq 5}}{{$a.amount}}{{/if}}</td>
  </tr>
  {{/foreach}}
  {{/if}}
  {{/foreach}}
  </tbody>
</table>
<br>
<table >
  <tr>
    <th width="150">商品总金额：</td>
    <td>{{$order.price_goods}}</td>
  </tr>
  <tr>
    <td>总行邮税：</td>
    <td>{{$order.tax}}</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td>运输费：</td>
    <td>{{$order.price_logistic}}</td>
  </tr>
  <tr>
    <td>订单总金额：</td>
    <td>{{$order.price_order}}</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td>调整金额：</td>
    <td>{{$order.price_adjust}}</td>
  </tr>
  {{if $order.price_adjust_return}}
  <tr bgcolor="#F0F1F2">
    <td>退货调整金额：</td>
    <td>{{$order.price_adjust_return|string_format:"%.2f"}}</td>
  </tr>
  {{/if}}
{{if $detail.adjust.price_adjust_return_logistic_to}}
  <tr bgcolor="#F0F1F2">
    <td>退还运费：</td>
    <td>{{$detail.adjust.price_adjust_return_logistic_to}}</td>
</tr>
{{/if}}
{{if $detail.adjust.price_adjust_return_logistic_back}}
  <tr bgcolor="#F0F1F2">
    <td>退还顾客邮寄回来的运费：</td>
    <td>{{$detail.adjust.price_adjust_return_logistic_back}}</td>
</tr>
{{/if}}
  <tr bgcolor="#F0F1F2">
    <td>已支付金额：</td>
    <td>{{$order.price_payed+$order.price_from_return}}</td>
  </tr>
  </tr>
{{if $order.gift_card_payed > 0}}
	<tr>
	<td>礼品卡抵扣：</td>
	<td>{{$order.gift_card_payed}}</td>
	</tr>
{{/if}}
{{if $order.point_payed > 0}}
	<tr>
	<td>积分抵扣：</td>
	<td>{{$order.point_payed}}</td>
	</tr>
{{/if}}
{{if $order.account_payed > 0}}
	<tr>
	<td>账户余额抵扣：</td>
	<td>{{$order.account_payed}}</td>
	</tr>
{{/if}}
{{if $detail.other.price_must_pay}}
	<tr>
	<td>需支付金额：</td>
	<td>{{$detail.other.price_must_pay}}</td>
	</tr>
{{/if}}
<tr bgcolor="#F0F1F2">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
{{if $detail.finance.price_return_money}}
	<tr>
	<td>需退款现金金额：</td>
	<td>{{$detail.finance.price_return_money}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_point}}
	<tr>
	<td>需退积分金额：</td>
	<td>{{$detail.finance.price_return_point}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_account}}
	<tr>
	<td>需退账户余额金额：</td>
	<td>{{$detail.finance.price_return_account}}</td>
	</tr>
{{/if}}
{{if $detail.finance.price_return_gift}}
	<tr>
	<td>需退礼品卡金额：</td>
	<td>{{$detail.finance.price_return_gift}}</td>
	</tr>
{{/if}}
</table>
<br />
{{if  $payLog}}
<table width="100%">
<tr align="left">
<th align="left">支付接口订单单据号</th>
<th align="left">订单SN</th>
<th align="left">支付时间</th>
<th align="left">支付接口</th>
<th align="left">支付金额</th>
</tr>
{{foreach from=$payLog item=tmp}}
<tr>
<td>{{$tmp.pay_log_id}}</td>
<td>{{$tmp.batch_sn}}</td>
<td>{{$tmp.add_time}}</td>
<td>{{$tmp.pay_type}}</td>
<td>{{$tmp.pay}}</td>
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
<tr align="left">
<th align="left">时间</th>
<th align="left">财务退款状态</th>
<th align="left">金额</th>
<th align="left">积分</th>
<th align="left">账户余额</th>
<th align="left">礼品卡</th>
<th align="left">备注</th>
</tr>
{{foreach from=$finance item=tmp}}
<tr>
<td>{{$tmp.add_time_label}}</td>
<td>{{$tmp.status_label}}</td>
<td>{{$tmp.pay_label}}</td>
<td>{{$tmp.point_label}}</td>
<td>{{$tmp.account_label}}</td>
<td>{{$tmp.gift_label}}</td>
<td>{{$tmp.note}}</td>
</tr>
{{/foreach}}
</table>
<br />
{{/if}}
<table>
<tr>
<th width="150">物流打印备注：</td>
<td>{{$order.note_print}}</td>
</tr>
<tr>
<td>物流部门备注：</td>
<td>{{$order.note_logistic}}</td>
</tr>
<tr>
<td>开票信息：</td>
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
{{if $order.invoice_no}}
&nbsp;&nbsp;&nbsp;&nbsp;{{$order.invoice_no}}
{{/if}}
</td>
</tr>
</table>
<br>
<table>
<tr>
<th width="150">订单留言：</td>
<td>{{$order.note}}</td>
</tr>
</table>
<br />
{{if $childOrder}}
<table>
<tr>
<th width="150">产生的换货单号</td>
<td>产生时间</td>
</tr>
{{foreach from=$childOrder item=item}}
<tr>
<td><span style="cursor:pointer;" onclick="G('{{url param.action=info param.batch_sn=$item.batch_sn}}')">{{$item.batch_sn}}</span></td>
<td>{{$item.add_time}}</td>
</tr>
{{/foreach}}
</table>
<br>
{{/if}}

{{if $order.lock_name == $adminName}}
    {{if $order.status_logistic == 2 && $order.status_back == 0}}
    <table>
    <tr>
      <td>订单取消/返回备注：</td>
      <td><textarea name="note_staff_cancel" id="note_staff_cancel" cols="39" rows="3" style="width:330px; height:45px;"></textarea></td>
    </tr>
    </table>
    {{/if}}
    <table>
    <tr>
    <th width="150">订单类型：</td>
    <td>
    {{if  $order.status == 2}}
    无效订单
    {{elseif $order.status == 1}}
    取消订单
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
    {{/if}}
    </td></tr>
    <tr>
    <td></td>
    <td>
    {{if  $order.status == 1}}<!--取消单-->
        <input type="button" value="恢复订单" onclick="G('{{url param.action=undo}}')" >
    {{elseif $order.status_logistic == 1}}<!--待收款-->
			{{if  $is_pay}} 	
			  <input type="button" value="确认收款" onclick="confirmed('确认收款', $('myform'), '{{url param.action=has-pay}}')" />
			{{/if}}
             <input type="button" value="订单取消" onclick="confirmed('订单取消', $('myform'), '{{url param.action=confirm-cancel param.mod=confirm-list}}')" />
        <input type="button" value="订单返回" onclick="confirmed('订单返回', $('myform'), '{{url param.action=confirm-back}}')" />
    {{elseif $order.status_logistic == 2}}<!--待发货-->
        {{if $order.status_back == 2}}
            待发货订单 申请返回 处理中
        {{elseif  $order.status_back == 1}}
            待发货订单 申请取消 处理中
        {{else}}
            {{if !$order.split_status || !$order.split_status.hasConfirm}}
            <input type="button" value="申请返回" onclick="if ($('note_staff_cancel').value==''){alert('请填写订单备注');return false;}confirmed('申请返回', $('myform'), '{{url param.action=to-be-shipping-back}}')" />
            <input type="button" value="申请取消" onclick="if ($('note_staff_cancel').value==''){alert('请填写订单备注');return false;}confirmed('申请取消', $('myform'), '{{url param.action=to-be-shipping-cancel}}')" />
            {{/if}}
        {{/if}}
    {{elseif $order.status_logistic == 3 ||  $order.status_logistic == 4 || $order.status_logistic == 5 || $order.status_logistic == 6}}<!--已发货、客户已签收，客户已拒收，部分签收-->
        {{if $order.status_logistic == 3}}
            {{if $complain}}
            配送投诉中
            {{else}}
            <div><textarea name="remark"></textarea> <input type="button" value="配送投诉" onclick="confirmed('配送投诉', $('myform'), '{{url param.action=complain}}')" /></div>
            {{/if}}
        {{/if}}
        {{if ($order.status eq '0' || $order.status eq '4') && $order.status_logistic > 3}}
            <input type="button" value="退换货开单" onclick="G('{{url param.action=return-product}}')" >
        {{/if}}
        {{if ($order.status_pay eq '1' && $detail.finance.status_return) || ($order.type eq 16 && $order.price_payed > 0)}}
            <input type="button" value="退款开单" onclick="G('{{url param.action=finance param.jump=return-list}}')" >
        {{/if}}
    {{/if}}
    <input type="button" onclick="Gurl();" value="返回订单列表">
    </td>
    </tr>
    </table>
{{/if}}


{{if $noteStaff}}
<table width=100%>
<tr align="left">
<th width="150">客服</td>
<td>客服备注内容</td>
<td>客服备注日期</td>
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
<th width="150">客服添加新备注：</td>
<td>
<input type="text" name="note_staff" id="note_staff" size="80">
<input type="button" value="添加" onclick="if($('note_staff').value==''){alert('备注内容不能为空');return false;}ajax_submit($('myform'), '{{url param.action=add-note-staff param.mod=info}}');" /></td>
<td><input type="button" onclick="window.open('/admin/logic-area-out-stock/print/logic_area/1/bill_no/{{$order.batch_sn}}')" value="打印销售单"></td>
</tr>
</table>
<br>
{{if $logs}}
<table  cellpadding="0" cellspacing="0" border="0" width="100%" >
<tr align="left">
<th align="left" width="150">操作者</td>
<th align="left" width="200">操作时间</td>
<td>操作信息</td>
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

{{if $op}}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<thead>
<tr>
    <td  align="left" width="180">操作时间</td>
    <td  align="left" width="150">操作人</td>
    <td  align="left" width="200">操作内容</td>
    <td>备注</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$op item=d}}
	<tr>
	<td width="150">{{$d.op_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
	<td>{{$d.admin_name}}</td>
	<td>{{if $d.op_type=='assign'}}
	物流派单
	{{elseif $d.op_type=='confirm'}}
	运输单确认
	{{elseif $d.op_type=='prepare'}}
	仓库配库
	{{/if}}
	{{$d.item_value}}</td>
	<td>{{$d.remark}}</td>
	</tr>
	{{/foreach}}
</tbody>
</table>
{{/if}}

<table cellpadding="0" cellspacing="0" border="0" width="100%" >
    <tr>
      <td width="80"><strong>操作人</strong></td>
      <td width="150"><strong>维护时间</strong></td>
      <td width="80"><strong>维护状态</strong></td>
      <td><strong>维护说明</strong></td>
    </tr>
	{{foreach from=$tracks item=t}}
		<tr>
		  <td>{{$t.admin_name}}</td>
		  <td>{{$t.op_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
		  <td>{{$logisticStatus[$t.logistic_status]}}</td>
		  <td>{{$t.remark}}</td>
		</tr>
	{{/foreach}}
</table>


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