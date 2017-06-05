<div class="member">

    {{include file="member/menu.tpl"}}
  <div class="memberright">
    <div class="memberddbg">
	 <p>
	   {{if $type eq 1}}
	     {{if $total > 0}}您有 <span class="highlight">{{$total}}</span> 张抵用券可以使用{{else}}您没有抵用券{{/if}}
	   {{else}}
	     {{if $total > 0}}您有 <span class="highlight">{{$total}}</span> 张无效抵用券{{else}}您没有无效抵用券{{/if}}
	   {{/if}}
	 </p>
	</div>
<img src="{{$imgBaseUrl}}/images/shop/member_coupon.png">

	<div class="coupontype ">
	<div class="coupontypea">
    <a href="/member/coupon/type/1" {{if $type eq 1}} class="sel" {{/if}}>可使用</a>
    <a href="/member/coupon/type/2" {{if $type eq 2}} class="sel" {{/if}}>已无效</a>
    <a href="/member/active-coupon" >激活优惠券</a>
    </div>
    </div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
                <thead>
                    <tr>
                        <th>券号</th>
                        <th>密码</th>
                        <th>类型</th>
                        <th>金额</th>
			<th>使用条件</th>
                        <th>使用状态</th>
			<th>开始时间</th>
                        <th>有效期至</th>
                    </tr>
                </thead>
                <tbody class="text-mid">
                    {{foreach from=$coupons item=coupon}}
                    <tr>
                        <td>{{$coupon.card_sn}}</td>
                        <td>{{$coupon.card_pwd}}</td>
                        <td>
                          {{if $coupon.card_type eq 0}}
                            订单金额抵扣
                          {{elseif $coupon.card_type eq 1}}
                            订单金额抵扣
                          {{elseif $coupon.card_type eq 2}}
                            {{if $coupon.goods_name}}
                              <a title="{{$coupon.goods_name}}">特定商品抵扣</a>
                            {{else}}
                              特定商品抵扣
                            {{/if}}
                          {{elseif $coupon.card_type eq 3 || $coupon.card_type eq 5}}
                            {{if $coupon.coupon_price > 0}}
                              {{if $coupon.goods_name}}
                                <a title="{{$coupon.goods_name}}">特定商品抵扣</a>
                              {{else}}
                                特定商品抵扣
                              {{/if}}
                            {{else}}
                              {{if $coupon.goods_name}}
                                <a title="{{$coupon.goods_name}}">特定商品全额抵扣</a>
                              {{else}}
                                特定商品全额抵扣
                              {{/if}}
                            {{/if}}
                          {{elseif $coupon.card_type eq 4}}
                            订单金额折扣
                          {{/if}}
                        </td>
                        <td>
                        {{if $coupon.card_type eq 3 || $coupon.card_type eq 5}}
                          {{if $coupon.coupon_price > 0}}
                            ￥{{$coupon.coupon_price}}
                          {{else}}
                            *
                          {{/if}}
                        {{elseif $coupon.card_type eq 4}}
                          {{$coupon.coupon_price}}折
                        {{else}}
                          ￥{{$coupon.coupon_price}}
                        {{/if}}
                        </td>
						<td>
						  {{if $coupon.min_amount > 0}}
						  满{{$coupon.min_amount}}可使用 
						  {{else}}
						  订单金额无限制
						  {{/if}}
						  {{if $coupon.card_type eq 0 || $coupon.card_type eq 1 || $coupon.card_type eq 4}}
						    {{if $coupon.goods_info.allGoods || $coupon.goods_info.allGroupGoods}}
						    <br>购买指定商品
						    {{/if}}
						  {{/if}}
						</td>
                        <td>{{if $curtime > $coupon.end_date}} <font color="#FF6600"> 已经过期 </font>{{else}} 
						  {{if $coupon.status eq 0}}<span class="highlight">可使用</span>{{else}}已使用/无效{{/if}} {{/if}}   
						 </td>
			<td>{{$coupon.start_date}}</td>
                        <td>{{$coupon.end_date}}</td>
                    </tr>
                    {{/foreach}}
                </tbody>
            </table>
    <div class="page_nav">{{$pageNav}}</div>
  </div>
</div>