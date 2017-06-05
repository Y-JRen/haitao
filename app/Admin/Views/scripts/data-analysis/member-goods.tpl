{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url param.action=member-goods}}">
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:120px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:120px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <br><br>
    会员名：<input name="user_name" type="text"  size="18" value="{{$param.user_name}}"/>
    有效单数 >= <input name="order_count" type="text"  size="3" value="{{$param.order_count}}"/>
    消费金额 >= <input name="real_amount" type="text"  size="3" value="{{$param.real_amount}}"/>
  <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">会员订单商品列表   </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>会员名</td>
				<td ><a href="{{url param.sortField=GoodsCount param.sortType=$sortType}}">购买商品清单</a></td>
				<td ><a href="{{url param.sortField=TotalOrderCount param.sortType=$sortType}}">总单数</a></td>
				<td ><a href="{{url param.sortField=OrderCount param.sortType=$sortType}}">有效单数</a></td>
				<td ><a href="{{url param.sortField=TotalAmount param.sortType=$sortType}}">应收总金额</a></td>
				<td ><a href="{{url param.sortField=RealAmount param.sortType=$sortType}}">实际消费总金额</a></td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>{{$data.user_name}}</td>
		  <td>
		    {{if $data.GoodsCount}}
		      <a href="javascript:void(0)" onclick="this.style.display='none';document.getElementById('Goods_{{$data.user_id}}').style.display='';">{{$data.GoodsCount}}</a>
		      <div id="Goods_{{$data.user_id}}" style="display:none">
		        {{foreach from=$data.Goods item=goods}}
		          {{$goods.goods_name}} * 
		          {{$goods.number}}<br>
		        {{/foreach}}
		      </div>
		    {{else}}
		      0
		    {{/if}}
		  </td>
		  <td>{{if $data.TotalOrderCount}}{{$data.TotalOrderCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.OrderCount}}{{$data.OrderCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.TotalAmount}}{{$data.TotalAmount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.RealAmount}}{{$data.RealAmount}}{{else}}0{{/if}}</td>
		</tr>
		{{/foreach}}
		<thead>
	    <tr>
		  <td>合计</td>
		  <td >{{$totalData.GoodsCount}}</td>
		  <td >{{$totalData.TotalOrderCount}}</td>
		  <td >{{$totalData.OrderCount}}</td>
		  <td >{{$totalData.TotalAmount}}</td>
	      <td >{{$totalData.RealAmount}}</td>
	    </tr>
		</thead>
		{{/if}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>	