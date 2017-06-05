<div class="memberCenter">
	{{include file="member/menu.tpl"}}
		<div class="mcContentRight">
			<div class="rightTitle">
				<div class="leftNav">
				
					<p class="leftNav01">事务提醒：</p>
					<p class="leftNav02"><a href="/member/order">全部（<span>{{$allorder}}</span>）</a></p>
					<p class="leftNav02"><a href="/member/order/ordertype/4">待支付订单（<span>{{$nopay}}</span>）</a></p>
					<p class="leftNav02"><a href="/member/order/ordertype/6">等待收货（<span>{{$send}}</span>）</a></p>
					<p class="leftNav02"><a href="/member/order/ordertype/7">交易完成（<span>{{$okorder}}</span>）</a></p>
					<br class="clearfix"/>
					
				</div>
				<div class="rightNav">
				    <form action="/member/order" method="get" onsubmit="return FormSubmit();">
					
					<div class="rightNav02"><input name="sn" id="ordersn" type="text" value="订单编号"/></div>
					<div class="rightNav03"><input type="submit" style="background:url('/public/images/cx_btn.png');width:49px;height:24px;" value=" "/></div>
					<br class="clearfix"/>
					</form>
				</div>
				<br class="clearfix"/>
			</div>
			<div class="orderList">
				<table>
					<tr class="first">
						<!-- <td class="allBtn" width="73">全选</td> -->
						<td width="152">订单编号</td>
						<td width="233">商品</td>
						<td width="108">金额</td>
						<td width="108">收货人</td>
						<td width="109">时间</td>
						<td width="108">状态</td>
						<td width="123">操作</td>
					</tr>
					{{foreach from=$orderInfo item=order}}
					<tr>
						<!-- <td class="checkBox"><input type="checkbox"/></td> -->
						<td>{{$order.batch_sn}}</td>
						<td class="picImg">
						    {{foreach from=$order.goods  item=goods}}
							<div><a href='/goods-{{$goods.goods_id}}.html' title='{{$goods.product_name}}'><img src="{{$imgBaseUr}}/{{$goods.product_img|replace:'.':'_60_60.'}}" width=46; height=46;/></a></div>
							{{/foreach}}
							
							<br class="clearfix"/>
						</td>
						<td class="price">￥{{$order.price_order}}</td>
						<td>{{$order.addr_consignee}}</td>
						<td class="time">
							<p>{{$order.add_time}}</p>
						</td>
						<td>{{$order.deal_status}}</td>
						<td class="caozuo">
							<div><a href="/member/order-detail?batch_sn={{$order.batch_sn}}">查看</a>{{if $order.price_payed eq 0 && $order.status eq 0 && $order.status_logistic eq 0}}&nbsp;&nbsp;<a href='/member/cancel-order/batch_sn/{{$order.batch_sn}}' onclick='return canelOrder();'>取消订单</a>{{/if}}</div>
							<!--  
							<div class="payBtn"><a href="#"><img src="{{$imgBaseUr}}/public/images/payBtn.png"/></a></div>
							-->
						</td>
					</tr>
					{{/foreach}}
				</table>
			</div>
			<div class="orderBottom">
				
				<div class="page">
					{{$pageNav}}
				</div>
				<br class="clearfix"/>
			</div>
		</div>
		<br class="clearfix"/>
	</div>
</div>
<script>
$(function(){	
	 $('#ordersn').focus(function(){
			if($(this).val() == '订单编号')
				$(this).val('');
		});
		
		$('#ordersn').blur(function(){
			if($(this).val() == '')
				$(this).val('订单编号');
		});
})

function FormSubmit(){
	if($("#ordersn").val() == '订单编号'){
		alert('订单号不能为空！');
		return false;
	}else{
		return true;
	}
}

function canelOrder(){
	if(confirm('确定要取消订单吗~？')){
		return true;
	}else{
		return false;
	}
}
</script>
