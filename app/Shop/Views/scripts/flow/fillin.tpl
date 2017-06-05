{{include file="flow_header.tpl"}}
<div class="allSort container">
 <div class="mycart-title">
 	<h2>填写并核对订单信息</h2>
 </div>
 <form action="/flow/order" method="Post">
 <div class="getinfo">
  <h3 class="infoh3">收货人信息</h3>
  <ul>
    {{if ($memberAddress)}}
    {{foreach from=$memberAddress item=address name=address}}
  	<li>
  		<input type="radio" name="selected" class="danxuan" value="{{$address.address_id}}" {{if $address.is_default eq 1}}checked="checked"{{/if}}>
  		<strong>{{$address.consignee}}</strong>
  		<p>{{$address.province_msg.area_name}}&nbsp;{{$address.city_msg.area_name}}&nbsp;{{$address.area_msg.area_name}}&nbsp;{{$address.address}}</p>
  		<a href="javascript:void(0);" onclick="addNewAddress({{$address.address_id}});">修改</a>
  		{{if $count gt 1}}
  		<a href="javascript:void(0);" onclick="delNewAddress({{$address.address_id}});">删除</a>
  		{{/if}}
  	</li>
   {{/foreach}}
   <div class="addnew_adress"><input type='submit' class='shopping buyBtn' value="确认收货地址" />
   {{if $count lt 5}}
   &nbsp;&nbsp;<a href="javascript:void(0);" onclick="addNewAddress();">添加新地址</a>
   {{/if}}
   </div>
   
   {{else}}
   <li>
        <div class="infogroup"><input type="radio" class="danxuan" checked="checked"><strong>创建新的地址</strong></div>
      	<div class="infogroup"><span><em>*</em> 收货人姓名:</span><input type="text" class="infoname" name="consignee" id="consignee"></div>
      	<div class="infogroup"><span><em>*</em> 配送区域:</span>
      	  <select class="infonarea" name="province_id" id="province_id" onchange="getCity(this)">
      	      <option selected="selected" value="0">请选择省份</option>
      	      {{foreach from=$province key=province_id item=province}}
      	      <option value="{{$province_id}}">{{$province.area_name}}</option>
      	      {{/foreach}}
      	  </select>
      	  <select class="infonarea" name="city_id" id="city_id" onchange="getCity(this)">
      	      <option selected="selected">请选择城市</option>
      	      	
      	  </select>
      	  <select class="infonarea" name="area_id" id="area_id"  onchange="getAreaCode()">
      	      <option selected="selected">请选择区域</option>
      	  </select>
      	</div>
      	<div class="infogroup"><span><em>*</em> 详细地址:</span><input type="text" class="infonadr" name="address" id="address"></div>
      	<div class="infogroup"><span><em></em> 英文地址:</span><input type="text" class="infonadr" name="eng_address" id="eng_address">&nbsp;&nbsp;为了更方便快捷的送货，请填写英文地址</div>
      	<div class="infogroup"><span>邮编:</span><input type="text" class="celnum" name="zip" id="postalcode"></div>
      	<div class="infogroup"><span><em>*</em> 联系电话:</span><b>手机或固话任填一项</b></div>
      	<div class="infogroup"><span>手机:</span><input type="text" class="celnum" name="mobile" id="mobile"></div>
      	<div class="infogroup"><span>固话:</span>
      	<input type="text" class="phone1" name="area_code" id="area_code"  maxlength="4">
      	<input type="text" class="phone2" name="tel" id="tel">
      	<input type="text" class="phone3" name="tel_branch" id="tel_branch"><b>区号+电话分号+分机号，如021-33555777-8888</b></div>      
     
  	</li>
    <button class="buyBtn shopping" type="button" onclick="return editAddress();">保存收货人信息</button>
   {{/if}}
  </ul>
 </div>
 </form>
  <div class="payinfo">
	   <h3 class="infoh3">支付方式</h3>
	   <p><b>在线支付</b> （目前只支持东方支付，即时到帐，付款成功后将立即安排发货）</p>
	   <img src="{{$imgBaseUr}}/public/images/payactive.png"><br>
	   <!--  
	   <button id="overbuy">保存支付方式</button>
	   -->
  </div>
  <div class="transfer">
       <h3 class="infoh3">配送与发票</h3>
       <div class="ways">
         <b>配送方式</b>
         <div class="waysO">
           <input type="radio" class="danxuan" checked>
           <p>顺丰速递 </p>
           <!--  
           <p><input type="checkbox" class="baojia">商品需要保价 <span>保价费10.00</span></p>
           -->
           <p class="ways0_p">我们会以最快的速度为您发货，但因商品从境外发货，可能会因为入关、交通、气候等原因订单到达时间可能会有误差请谅解！</p>
         </div>
       </div>
       <div class="clearfix"></div>
       <!--  
       <div class="ways">
         <b>发票信息</b>
         <p>选择开票，发票、纳税证明将会与商品一同寄出。</p>
         <div class="waysO waysb"><input type="radio" name="invoice" class="danxuan" checked="checked" value="">不开发票</div>
         <div class="waysO waysb"><input type="radio" name="invoice" class="danxuan" >个人<input type="text" name="invoice_name" id="invoice_name" value="姓名"></div>
         <div class="waysO waysb"><input type="radio" name="invoice" class="danxuan" >单位<input type="text" name="invoice_company" id="invoice_company" value="单位全称" class="danwei">  (请务必输入完整单位名称)</div>
        
         <div class="waysO waysb"><p>发票内容</p> <p><input type="radio" class="danxuan" checked>内容1</p> <p><input type="radio" class="danxuan" checked>内容2</p></div>
         <button id="overbuy">保存配送与发票信息</button>
         
       </div>
       -->
  </div>
 <div class="mycart-cont border2">
  <h3 class="infoh4">商品清单<span style='width:900px;display:inline-block;'>{{if $jnum && $hnum}}由于您选购的商品分别属于香港仓库和日本仓库，我们将分两个快递包裹为您送达，同时将向您收取两个包裹的快递费用，请知悉！{{/if}}</span><a href="/flow">返回修改购物车</a></h3>
    <table>
   	  <tr class="mycarttop mycarttop2">
   	   <td class="mycart_fistrtd"><span>商品</span></td>
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
   	<p><button id="overbuy" class='unshopping buyBtn' onclick="javascript:alert('请先确认收货地址');return false;">提交订单</button></p>
   </div>
  <!--  <div class="remark">
   	添加备注<br>
   	<textarea cols="40" rows="6" name="order_note" id="order_note">{{if ($order_note)}}{{$order_note}}{{else}}输入订单备注内容，限500字{{/if}}</textarea>
   </div> -->
</div><!--container-->
<div id="newADRESS" style='background:#000;opacity:0.5;width:100%;height:100%;filter: alpha(opacity=50);position:fixed;top:0px;left:0px;display:none'></div>
<div id="newADRESS2"></div>
{{include file="footer.tpl"}}
<script>
$(function(){
	$('#order_note').focus(function (){if(this.value == '输入订单备注内容，限500字'){$(this).val('');}});
	$('#order_note').blur(function (){if(this.value == ''){$(this).val('输入订单备注内容，限500字');}});
})
function addNewAddress(id){
	if(id){
		$.ajax({
			url:"/flow/address",
			data:{address_id:id},
			success:function(data){
				$("#newADRESS").css("display","block");
				$("#newADRESS2").html(data);
			}
		});
	}else{
		$.ajax({
			url:"/flow/address",
			success:function(data){
				$("#newADRESS").css("display","block");
				$("#newADRESS2").html(data);
			}
		});
	}
}

function closeaddadress(){
	$("#newADRESS2").html("");
	$("#newADRESS").css("display","none");
	
}

function delNewAddress(id){
	if(id){
		if(confirm("确定要舍弃这个地址吗？")){
			$.ajax({
				type : "GET",
				url:'/flow/delete-address',
				data:{id:id},
				dataType:'text',
				success:function(msg){
					if('success' == msg){
						location.href='/flow/fillin';
					}else{
						alert("操作失败，请稍候再试！");
					}
				}
			});
		}
	}
}
</script>