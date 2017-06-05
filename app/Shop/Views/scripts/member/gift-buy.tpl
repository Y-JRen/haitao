<div class="member">
{{include file="member/menu.tpl"}}
  <div class="memberright">
  
  
  <div class="mycard">
    	<div class="title"><i></i><h2>我的健康卡</h2></div>
        <div class="menu">
        	<ul>            	
     	<li> <a href="/member/gift-card">我的健康卡</a> </li>       
      <li>  <a href="/member/gift-card-log" >健康卡消费明细</a> </li>    
        <li  class="current"> <a href="/member/gift-buy" > 我买的健康卡</a>  </li>    
        <li> <a href="/member/active-card" >绑定健康卡</a> </li>    
            </ul>
        </div>
        <div class="mycard_list">
        	<p>
            	<span>我买的健康卡：我通过1健康网购买的健康卡。</span>
                <select name="type" onchange="location.href='/member/gift-buy/type/'+this.value">
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
                <th>绑定</th>
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
					  <td>{{if $data.user_id}}{{$data.user_name}}{{else}}未绑定{{/if}}</td>
                    </tr>
                    {{/foreach}}
            </tbody></table>
              <div class="pagesize">{{$pageNav}}</div>
	 </div>
     
  
  
  
 </div></div>