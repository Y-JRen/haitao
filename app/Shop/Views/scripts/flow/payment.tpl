{{include file="flow_header.tpl"}}
<div class="allSort container">
 <div class="mycart-title">
 	<h2>成功提交订单</h2>
 </div>
 <div class="getinfo successinfo">
   <div class="success-flag"><img src="{{$imgBaseUr}}/public/images/success.png"></div>
   <div class="success-main">
     <h3>订单已提交，请尽快付款，以便我们尽快处理订单！</h3>
     <p>{{if $hongkong && $japanese}}由于您购买的商品分两个包裹送达，为便于对订单信息跟踪和管理，我们把您的订单拆分成两个订单。{{/if}}</p>
     <table>
       <tr>
         <td><b>仓库</b></td>
         <td><b>订单号</b></td>
         <td><b>订单金额</b></td>
       </tr>
       {{if ($hongkong)}}
       <tr>
         <td>香港仓库</td>
         <td>{{$hongkong.order_sn}}</td>
         <td>￥{{$hongkong.account|number_format:2}}</td>
       </tr>
       {{/if}}
       {{if ($japanese)}}
       <tr>
         <td>日本仓库</td>
         <td>{{$japanese.order_sn}}</td>
         <td>￥{{$japanese.account|number_format:2}}</td>
       </tr>
       {{/if}}
     </table>
     <p><b>应付总额:</b><span>{{$account|number_format:2}}</span>元</p>
     <p><i>支付方式:</i><img src="{{$imgBaseUr}}/public/images/pay2.png"></p>
     <p class="clearfix" style="margin-bottom:10px;"></p>
     <span id="overbuy">{{$pay_info_J}}</span>
   </div>
 </div>
</div><!--container-->
{{include file="footer.tpl"}}