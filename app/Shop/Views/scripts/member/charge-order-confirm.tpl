<script src='/_static/js/js.js'></script>
<div class="member">
	{{include file="member/menu.tpl"}}
	<div class="memberright">
		<div class="member-box">
			<div class="mb-t">
				账户余额  &gt; 充值单确认
			</div>
			<div class="mb-c">
					<table width="100%">
						<tr>
							<td width="100">充值单号：</td>
							<td>
								<span style="color:green;font-weight: bold;font-size: 18px;">{{$order.order_sn}}</span>
							</td>
						</tr>
						<tr>
							<td width="100">充值金额：</td>
							<td>
								￥<span style="color: red;font-weight: bold;font-size: 18px;">{{$order.amount}}</span>
							</td>
						</tr>
						<tr>
							<td width="100">状态：</td>
							<td>
								{{$order.status_name}}
							</td>
						</tr>
						{{if $order.status == -1}}
						<tr>
							<td>支付方式：</td>
							<td><img style="border: 1px solid #ddd;" src="{{$order.payment.img_url}}"/></td>
						</tr>
						<tr>
							<td colspan="2" align="center">{{$paymentButton}}</td>
						</tr>
						{{/if}}
					</table>
			</div>
		</div>
	</div>
	<div style="clear: both;"></div>
</div>

<script>
	$(function() {
		ajaxinit();
	});
</script>
