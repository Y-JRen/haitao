<div class="memberCenter">
		<div class="topNav"><a href="/">首页</a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href="/member">会员中心</a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href="javascript:void(0);">订单详情</a></div>
		<div class="mcContent">
			<div class="detailTitle">
				<ul class="dd_msg">
					<li class="dd_msg01">订单编号：{{$order.order_sn}}</li>
					<li class="dd_msg02">订单状态：{{$order.deal_status}}</li>
					<li class="dd_msg03">付款状态：{{$order.status_pay_label}}</li>
					<br class="clearfix"/>
				</ul>
				
			</div>
			<div class="detailTitle">
				<ul class="dd_msg">
					<li class="dd_msg01">订单信息</li>
					<br class="clearfix"/>
				</ul>
				<ul class="detailMsg">
					<li>
						<p class="first">收货人信息</p>
						<p>收货人：{{$order.addr_province}}{{$order.addr_city}}{{$order.addr_area}}{{$order.addr_consignee}}</p>
						<p>地　址：{{$order.addr_address}}</p>
						<p>英文地址：{{$order.addr_eng_address}}</p>
						<p>邮　编：{{$order.addr_zip}}</p>
						<p>手　机：{{$order.addr_mobile}}</p>
						<p>电　话：{{$order.addr_tel}}</p>
					</li>
					<li class="sendMsg">
						<p class="first">配送</p>
						<p>配送方式：顺丰速递</p>
						<p>运&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;费：{{$order.price_logistic}} 元</p>
						{{if $order.logistic_no neq null}}
						<p>快递单号：{{$order.logistic_no}}</p>
						{{/if}}
					</li>
					<li class="last">
						<p class="first">支付方式</p>
						<p>支付方式：在线支付</p>
						<p>支付平台：东方支付</p>
					</li>
					<br class="clearfix"/>
				</ul>
			</div>
			<div class="detailTitle last">
				<ul class="dd_msg">
					<li class="dd_msg01">商品清单</li>
					<br class="clearfix"/>
				</ul>
				<div class="table">
					<table>
						<tr class="first">
							<td class="first" width="835" height="36">商品</td>
							<td width="112">单价</td>
							<td width="112">数量</td>
							<td class="last" width="128">小计</td>
						</tr>
						{{foreach from=$product item=item}}
						<tr>
							<td class="first" height="76">
								
								<p><a href='/goods-{{$item.goods_id}}.html'>{{$item.goods_name}}</a></p>
								<br class="clearfix"/>
							</td>
							<td>{{$item.sale_price}}</td>
							<td>{{$item.number}}</td>
							<td class="last">￥{{$item.amount}}</td>
						</tr>
						{{/foreach}}
						  
					</table>
				</div>
			</div>
			<div class="totalMoney">
				<div>
					<p class="total_p1">商品总额：</p>
					<p class="total_p2">￥{{$order.price_goods|string_format:"%.2f"}} <span>元</span></p>
					<br class="clearfix"/>
				</div>
				<div class="borderBottom">
					<p class="total_p1">运费：</p>
					<p class="total_p2">￥{{$order.price_logistic|string_format:"%.2f"}} <span>元</span></p>
					<br class="clearfix"/>
				</div>
				<div class="borderBottom">
					<p class="total_p1">行邮税：</p>
					<p class="total_p2">￥{{$order.tax|string_format:"%.2f"}} <span>元</span></p>
					<br class="clearfix"/>
				</div>
				<div>
					<p class="total_p1">订单总额：</p>
					<p class="total_p2">￥{{$order.price_pay|string_format:"%.2f"}} <span>元</span></p>
					<br class="clearfix"/>
				</div>
				{{if ($order.price_pay - $order.price_payed) > 0 && $order.status eq 0}}
				<div class="wantPayBtn">
					{{$paymentButton}}	
				</div>
				{{/if}}
			</div>
			<br class="clearfix"/>
		</div>
	</div>
