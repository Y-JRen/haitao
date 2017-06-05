{{include file="flow_header.tpl"}}
<div class="allSort container">
 <div class="mycart-title">
 	<h2>我的购物车</h2>
 	{{if $auth.user_id eq ''}}
 	<p>现在 <button onclick="gotoLogin()">登录</button> 购物车中的商品将被永久保存</p>
 	{{/if}}
 </div>
 <div class="mycart-cont border2">
   <table>
   	  <tr class="mycarttop">
   	   <td class="mycart_fistrtd"><span>商品</span></td>
   	   <td>商品单价</td>
   	   <td>行邮税</td>
   	   <td>购买数量</td>
   	   <td>小计</td>
   	   <td>操作</td>
   	  </tr>
   	  {{if ($data.hongkong)}}
   	  <tr class="warehouse">
   	   <td> <p><img src="{{$imgBaseUr}}/public/images/flagbg.png">香港仓库</p></td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	  </tr>
   	  {{foreach from=$data.hongkong item=goods}}
   	  <tr class="cartname {{if $goods.onsale eq 1}}cart_offshelf{{/if}}">
   	   <td class="mycart_fistrtd">
            <p>{{if $goods.onsale eq 1}}<i></i>{{/if}}<a href="/goods-{{$goods.goods_id}}.html"><img src="{{$imgBaseUrl}}/{{$goods.product_img|replace:'.':'_60_60.'}}"></a></p> 
             <p><a href="/goods-{{$goods.goods_id}}.html" title='{{$goods.goods_name}}'>{{$goods.goods_name|cut_str:30}}</a><br><span>规格:{{$goods.goods_style}}</span></p>
         </td>
   	   <td>{{$goods.shop_price}}</td>
   	   <td>{{$goods.tax}}</td>
   	   <td>
   	   		<input type="button" value="-" class="cart1" {{if $goods.onsale eq 0}}onclick="selNumLess('{{$goods.product_id}}')"{{/if}}>
   	   		<input type="text" class="carttext" value="{{$goods.number}}" id="buy_number_{{$goods.product_id}}" readonly="readonly">
   	   		<input type="button" value="+" class="cart2" {{if $goods.onsale eq 0}}onclick="selNumAdd('{{$goods.product_id}}')"{{/if}}>
   	   	</td>
   	   <td>{{math equation="(x + y) * z" x=$goods.shop_price y=$goods.tax z = $goods.number format="%0.2f"}}</td>
   	   <td> <font onclick="favGoods(this,'{{$goods.goods_id}}')">收藏</font> | <a href="/flow/del?product_id={{$goods.product_id}}" onclick="return confirmMsg();">删除</a></td>
   	  </tr>
   	  {{/foreach}}
   	  {{/if}}
   	  {{if ($data.japanese)}}
   	  <tr class="warehouse">
   	   <td><p><img src="{{$imgBaseUr}}/public/images/flag_jp.png">日本仓库</p></td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	  </tr>
   	  {{foreach from=$data.japanese item=goods}}
   	  <tr class="cartname {{if $goods.onsale eq 1}}cart_offshelf{{/if}}">
   	   <td class="mycart_fistrtd">
          <p>{{if $goods.onsale eq 1}}<i></i>{{/if}}<a href="/goods-{{$goods.goods_id}}.html"><img src="{{$imgBaseUrl}}/{{$goods.product_img|replace:'.':'_60_60.'}}"></a></p>
             <p><a href="/goods-{{$goods.goods_id}}.html" title='{{$goods.goods_name}}'>{{$goods.goods_name|cut_str:30}}</a><br><span>规格:{{$goods.goods_style}}</span></p>
   	   </td>
   	   <td>{{$goods.shop_price}}</td>
   	   <td>{{$goods.tax}}</td>
   	   <td>
	   	   <input type="button" value="-" class="cart1"  {{if $goods.onsale eq 0}}onclick="selNumLess('{{$goods.product_id}}')"{{/if}}>
	   	   <input type="text" class="carttext" value="{{$goods.number}}"  onchange='changeNumber({{$goods.product_id}},this.value,{{$goods.number}})' id="buy_number_{{$goods.product_id}}">
	   	   <input type="button" value="+" class="cart2"  {{if $goods.onsale eq 0}}onclick="selNumAdd('{{$goods.product_id}}')"{{/if}}></td>
   	   <td>{{math equation="(x + y) * z" x=$goods.shop_price y=$goods.tax z = $goods.number format="%0.2f"}}</td>
   	   <td><font onclick="favGoods(this,'{{$goods.goods_id}}')">收藏</font> | <a href="/flow/del?product_id={{$goods.product_id}}" onclick="return confirmMsg();">删除</a></td>
   	  </tr>
   	  {{/foreach}}
   	  {{/if}}
       
   	  <tr class="mycartbottom">
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	  </tr>
   </table>
 </div>
    <div class="cartprice">
   	<p>已选<span>{{$number}}</span>件商品,商品总额：<span><strong>￥{{$shop_price|number_format:2}}</strong></span>元</p>
   	<p>行邮税：<span><strong>￥{{$oldTax|number_format:2}}</strong></span>元</p>
   	<p>快递费：<span><strong>￥{{$shipping_fee|number_format:2}}</strong></span>元</p>
   	{{if $disTax}}
   	<p>行邮税减免（行邮税低于50元）：<span><strong>-￥{{$disTax|number_format:2}}</strong></span>元</p>
   	{{/if}}
   	<hr>
   	<p>合计总额 ：<span><strong>￥{{$amount|number_format:2}}</strong></span>元</p>
   	<p><button id="overbuy" class="buyBtn {{if $err eq 1}}shopping{{else}}unshopping{{/if}}" onclick="{{if $err eq 1}}ftogoOrer(){{else}}javascript:alert('{{$alert}}');{{/if}}">去结算 ></button><button id="gobuy" type="button" class="buyBtn" onClick="fngobuy()">继续购物</button></p>
   </div>
</div><!--container-->
{{include file="footer.tpl"}}
<script>
function gotoLogin()
{
	location.href="/login.html";
}
//继续购物
function fngobuy()
{
	location.href="/"
}
//结账
function ftogoOrer()
{
	location.href="/flow/order";
}
</script>