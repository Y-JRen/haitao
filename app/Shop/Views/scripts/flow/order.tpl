{{include file="flow_header.tpl"}}
<form action="/flow/add-order" method="Post">
<div class="allSort container">
 <div class="mycart-title">
 	<h2>填写并核对订单信息</h2>
 </div>
 <div class="getinfo">
  <h3 class="infoh3">收货人信息</h3>
  <div class="checktop">
    <p><b>收货人信息</b><a href="/flow/fillin">[修改]</a></p>
    <p><b>{{$address.consignee}}</b> {{if ($address.mobile)}}{{$address.mobile}}{{else}}{{$address.phone}}{{/if}}</a></p>
    <p><span>{{$address.province_msg.area_name}}&nbsp;{{$address.city_msg.area_name}}&nbsp;{{$address.area_msg.area_name}}&nbsp;{{$address.address}}</span></p>
  </div>
 </div>
  <div class="payinfo">
	   <h3 class="infoh3">支付方式</h3>
	   <p><b>在线支付</b></p>
	   <img src="{{$imgBaseUr}}/public/images/payactive.png"><br>
  </div>
  <div class="transfer">
       <h3 class="infoh3">配送与发票</h3>
       <div class="ways checkway">
         <b>配送方式</b><br>
           <p><span>顺丰速递 </span>
           </p>
       </div>
       <!-- 
       <div class="ways checkway">
         <b>发票信息</b><br>
         <p>单位: 江苏省南京市XXXX科技有限公司</p>
         <p>单内容: 内容1</p>
       </div>
        -->
  </div>
 <div class="mycart-cont border2">
  <h3 class="infoh4">商品清单<span style='width:900px;display:inline-block;'>{{if $jnum && $hnum}}由于您选购的商品分别属于香港仓库和日本仓库，我们将分两个快递包裹为您送达，同时将向您收取两个包裹的快递费用，请知悉！{{/if}}</span><a href="/flow">返回修改购物车</a></h3>
    <table>
   	  <tr class="mycarttop mycarttop2">
   	   <td><span>商品</span></td>
   	   <td>商品单价</td>
   	   <td>行邮税</td>
   	   <td>购买数量</td>
   	   <td>小计</td>
   	   <td>操作</td>
   	  </tr>
   	  {{if $hnum}}
   	  <tr class="warehouse">
   	   <td> <p><img src="{{$imgBaseUr}}/public/images/flagbg.png">香港仓库</p></td>
   	   <td></td>
   	   <td></td>
   	   <td></td>
   	   <td></td>
   	   <td></td>
   	  </tr>
   	  {{foreach from=$data.hongkong item=goods}}
   	  {{if $goods.onsale neq 1}}
   	  <tr class="cartname">
   	   <td class="mycart_fistrtd">
   	       <p><img src="{{$imgBaseUrl}}/{{$goods.product_img|replace:'.':'_60_60.'}}"><a href="/goods-{{$goods.goods_id}}.html">{{$goods.goods_name|cut_str:30}}</a><br><span>规格:{{$goods.goods_style}}</span></p>
   	   </td>
   	   <td>{{$goods.shop_price}}</td>
   	   <td>{{$goods.tax}}</td>
   	   <td>{{$goods.number}}</td>
   	   <td>{{math equation="(x + y) * z" x=$goods.shop_price y=$goods.tax z = $goods.number format="%0.2f"}}</td>
   	   <td> <font onclick="favGoods(this,'{{$goods.goods_id}}')">收藏</font></td>
   	  </tr>
   	  {{/if}}
   	  {{/foreach}}
   	  {{/if}}
   	  {{if $jnum}}
   	  <tr class="warehouse">
   	   <td><p><img src="{{$imgBaseUr}}/public/images/flag_jp.png">日本仓库</p></td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	  </tr>
   	  {{foreach from=$data.japanese item=goods}}
   	  {{if $goods.onsale neq 1}}
   	  <tr class="cartname">
   	   <td class="mycart_fistrtd">
           <p><a href="/goods-{{$goods.goods_id}}.html"><img src="{{$imgBaseUrl}}/{{$goods.product_img|replace:'.':'_60_60.'}}"></a></p>
           <p><a href="/goods-{{$goods.goods_id}}.html">{{$goods.goods_name|cut_str:30}}</a><br><span>规格:{{$goods.goods_style}}</span></p>
   	   </td>
   	   <td>{{$goods.shop_price}}</td>
   	   <td>{{$goods.tax}}</td>
   	   <td>{{$goods.number}}</td>
   	   <td>{{math equation="(x + y) * z" x=$goods.shop_price y=$goods.tax z = $goods.number format="%0.2f"}}</td>
   	   <td><font onclick="favGoods(this,'{{$goods.goods_id}}')">收藏</font></td>
   	  </tr>
   	  {{/if}}
   	  {{/foreach}}
   	  {{/if}}
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
   	<p>合计总额：<span><strong>￥{{$amount|number_format:2}}</strong></span>元</p>
   	<p><input type="submit" id="overbuy" class='shopping buyBtn' value="提交订单" /></p>
   </div>
   <div class="remark">
   	添加备注<br>
   	<textarea cols="40" rows="6" name="order_note" id="order_note">{{$order_note}}</textarea>
   </div>
</div><!--container-->
</form>
{{include file="footer.tpl"}}
<script>
$(function(){
	$('#order_note').focus(function (){if(this.value == '输入订单备注内容，限500字'){$(this).val('');}});
	$('#order_note').blur(function (){if(this.value == ''){$(this).val('输入订单备注内容，限500字');}});
})
</script>