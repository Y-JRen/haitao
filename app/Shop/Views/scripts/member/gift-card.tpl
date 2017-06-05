<div class="member">
{{include file="member/menu.tpl"}}
  <div class="memberright">
  
  
  <div class="mycard">
    	<div class="title"><i></i><h2>我的健康卡</h2></div>
        <div class="menu">
        	<ul>            	
     	<li class="current"> <a href="/member/gift-card">我的健康卡</a> </li>       
      <li>  <a href="/member/gift-card-log" >健康卡消费明细</a> </li>    
        <li> <a href="/member/gift-buy" > 我买的健康卡</a>  </li>    
        <li> <a href="/member/active-card" >绑定健康卡</a> </li>    
            </ul>
        </div>
        <div class="mycard_list">
        	<p>
            	<span>我的健康卡：绑定我的账号且我能消费的健康卡。</span>
                <select name="type" onchange="location.href='/member/gift-card/type/'+this.value">
                	<option {{if $type eq 0}}selected{{/if}}value="0">全部</option>
                	<option {{if $type eq 1}}selected{{/if}} value="1">正常</option>
                	<option {{if $type eq 2}}selected{{/if}} value="2">作废</option>
                	<option {{if $type eq 3}}selected{{/if}} value="3">过期</option>
                </select>
            </p>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <th>卡号</th>
                <th>密码</th>
                <th>余额 </th>
                <th>到期时间 </th>
                <th>状态</th>
              </tr>
                {{foreach from=$info item=data}}
                    <tr>
					    <td>{{$data.card_sn}}</td>
						<td>{{$data.card_pwd}}</td>
						<td>{{$data.card_real_price}}</td>
                        <td>{{$data.end_date}}</td>                     
                        <td>
						{{if $data.card_real_price eq 0.00}} 作废  {{else}}
						{{if $curtime > $data.end_date}} <font color="#FF0000">已过期</font> {{else}}{{if $data.status eq 0}} <font color="#009900">正常</font>{{elseif $data.status eq 1}}作废{{/if}}{{/if}}{{/if}}
						</td>
                    </tr>
                    {{/foreach}}
            </tbody></table>
              <div class="pagesize">{{$pageNav}}</div>
	 </div>
      <div class="useflow">
       	<h3>健康卡使用流程</h3>
          <img width="676" height="50" src="{{$_static_}}/images/cart/card_flow.jpg"> 
          <p>说明：1.账户中已有绑定的健康卡，可在结算信息中，勾选已绑定的健康卡，卡余额将会抵扣订单金额；<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.账户中未有绑定的健康卡，可在结算信息中，输入健康卡卡号和密码，卡余额将会抵扣订单金额。</p>
        </div>
          <div class="userule">
          	<h3>健康卡使用规则</h3>
            <p>1、健康卡可与1健康商城会员账户进行绑定，使用时无须再输入卡号和密码，直接选择即可。已绑定的健康卡只能被当前账号使用<br>，不能跨账号使用，且不支持解除绑定功能；<br>
2、健康卡可用于购买1健康商城销售的所有商品，可与其他优惠一起使用；<br>
3、下单时，每笔订单可用多张健康卡支付，不足部分以现金补足或在线支付；<br>
4、健康卡有效期自销售之日起3年，有效期内可重复使用，延期将自动失效。健康卡暂不支持充值功能；<br>
5、健康卡不记名、不挂失、不兑换现金，请妥善保管卡号密码；<br>
6、健康卡销售时若开具过发票给顾客，之后购买商品时健康卡支付金额部分将不再开具发票；<br>
7、发生拒收或退货时，健康卡支付金额部分将自动退回卡内，有效期不变；<br>
8、顾客可登录国药集团1健康商城“我的1健康”-“我的健康卡”页面查询健康卡的卡号、余额、有效期等使用情况；<br>
9、国药集团1健康商城拥有健康卡的最终解释权。</p>
          </div>
    </div>
  
  
  
 </div></div>