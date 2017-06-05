<div class="member">
	{{include file="member/menu.tpl"}}
	<div class="memberright">
		<div class="member-box">
			<div class="mb-t"> 账户余额 </div>
			<div class="mb-c">
				您的账户余额为：<span style="font-size: 15px;">￥</span><font color="#D52319" size="4">{{$money}}</font>
				<div style="position: absolute;top:8px;right: 10px;">
					<!--
					<a class="btn-mem" href="/member/charge">充值</a>
						-->
					</div>
			</div>
		</div>
		<div style="height: 10px;overflow: hidden;"></div>
		{{include file="member/inc-amount-tab.tpl"}}		
		<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
			<thead>
				<tr>
					<th>充值单号</th>
					<th>充值金额</th>
					<th>支付方式</th>
					<th>状态</th>
					<th>充值时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				{{foreach from=$list_charge_order item=v}}
				<tr>
					<td>{{$v.order_sn}}</td>
					<td style="color: red;">{{$v.amount}}</td>
					<td>{{$v.pay_name}}</td>
					<td>{{$v.status_name}}</td>
					<td>{{$v.create_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
					<td align="left">{{if $v.status == -1}}<a href="/member/charge-order-confirm/id/{{$v.id}}">充值</a>{{/if}}</td>
				</tr>
				{{/foreach}}
			</tbody>
		</table>
		<div class="pagenav1">
			{{$pagenav}}
		</div>
	</div>
</div>

