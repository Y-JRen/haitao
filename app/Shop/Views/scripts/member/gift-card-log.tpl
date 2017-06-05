<div class="member">
    {{include file="member/menu.tpl"}}
  <div class="memberright">
  
  <div class="mycard">
    	<div class="title"><i></i><h2>我的健康卡</h2></div>
        <div class="menu">
        	<ul>            	
     	<li> <a href="/member/gift-card">我的健康卡</a> </li>       
      <li class="current">  <a href="/member/gift-card-log" >健康卡消费明细</a> </li>    
        <li>   <a href="/member/gift-buy" > 我买的健康卡</a>  </li>    
        <li> <a href="/member/active-card" >绑定健康卡</a> </li>    
            </ul>
        </div>
        <div class="mycard_list">
  <p>
            	<span>使用明细：使用健康卡的消费明细。</span>
                <span class="query"><input type="text"  id="card_sn" class="txt_card" value="{{$card_sn|default:'输入我的健康卡号'}}" name="card_sn">
                <a href="javascript:;" onclick="search_post()"><img width="62" height="23" src="{{$_static_}}/images/btn_query.jpg"></a></span>
            </p>
<table width="100%">
                <thead>
                    <tr align="center">
                      <th width="165px">时间</th>
						<th>卡号</th>
                        <th>订单编号 </th>                        
                        <th width="120px">消费金额</th>
                    </tr>
                </thead>
                <tbody>
                    {{foreach from=$logInfo item=data}}
                    <tr>
                     <td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
						<td>{{$data.card_sn}}</td>
                        <td>{{$data.batch_sn}}</td>
                        <td>{{$data.price}}</td>
                    </tr>
                    {{/foreach}}
                </tbody>
            </table>
    <div class="page_nav">{{$pageNav}}</div>
  </div>
  
</div></div>

<script>
{{if !$card_sn}}
$(function(){
	$('#card_sn').iClear({enter: $(':submit')}); 
});
{{/if}}
function search_post()
{
	var card_sn = $.trim($('#card_sn').val());
	if(card_sn!='' && card_sn!="输入我的健康卡号")
	{
		location.href='/member/gift-card-log/card_sn/'+card_sn;
	}
}
</script>
