<div class="cart">
	<a href="/flow/index">我的购物车({{$number}})</a>
	<span class="caret"></span>
</div>
<ul class="cartul hidden">
	
	<div class="cart_pro">
		{{foreach from=$data item=goods}}
		{{if $goods.onsale eq 0}}
		<li>
			<img src="{{$imgBaseUrl}}/{{$goods.product_img|replace:'.':'_60_60.'}}">
			<span>
			<a href="/goods-{{$goods.goods_id}}.html">{{$goods.goods_name|cut_str:20}}</a><br>
			<i>规格: {{$goods.goods_style}}</i>
			</span>
			<p>
			￥{{$goods.price}} * {{$goods.number}}
			<br>
			<a href="" onclick="delCartGoods({{$goods.product_id}},{{$goods.number}},'top');return false;">删除</a>
			</p>
		</li>
		{{/if}}
		{{/foreach}}
	
	</div>
		<li class="cart-last">
			<p class="zongji"> <strong>共{{$number}}件商品</strong> <b>总计:</b>
			￥{{$amount|number_format:2}}
			</p>
			<p>
			<button onclick="goToPay()">进入购物车</button>
			</p>
		</li>
</ul>