{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url param.action=order-distribution}}">
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">订单数量列表   </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>订单来源</td>
				<td><a href="{{url param.sortField=TotalCount param.sortType=$sortType}}">订单总数</a></td>
				<td><a href="{{url param.sortField=ValidCount param.sortType=$sortType}}">有效单数量</a></td>
				<td><a href="{{url param.sortField=TotalAmount param.sortType=$sortType}}">订单总金额</a></td>
				<td><a href="{{url param.sortField=PaidAmount param.sortType=$sortType}}">实收金额</a></td>
				<td><a href="{{url param.sortField=ReturnCount param.sortType=$sortType}}">退货单数</a></td>
				<td><a href="{{url param.sortField=ReturnAmout param.sortType=$sortType}}">退货金额</a></td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>
		    {{if $data.type==0}}
            前台下单
            {{elseif $data.type==1}}
            电话下单
			{{elseif $data.type==5}}
			赠送下单
			{{elseif $data.type==6}}
			非会员下单
			{{elseif $data.type==7}}
			团购下单
			{{elseif $data.type==13}}
			渠道下单
			{{elseif $data.type==14}}
			渠道补单
			{{elseif $data.type==15}}
			其它下单
			{{/if}}
		  </td>
		  <td>{{if $data.TotalCount}}{{$data.TotalCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.ValidCount}}{{$data.ValidCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.TotalAmount}}{{$data.TotalAmount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.PaidAmount}}{{$data.PaidAmount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.ReturnCount}}{{$data.ReturnCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.ReturnAmout}}{{$data.ReturnAmout}}{{else}}0{{/if}}</td>
		</tr>
		{{/foreach}}
		<thead>
		<tr>
		  <td>合计</td>
		  <td>{{$totalData.TotalCount}}</td>
		  <td>{{$totalData.ValidCount}}</td>
		  <td>{{$totalData.TotalAmount}}</td>
		  <td>{{$totalData.PaidAmount}}</td>
		  <td>{{$totalData.ReturnCount}}</td>
		  <td>{{$totalData.ReturnAmout}}</td>
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