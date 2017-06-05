{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    商品编码：<input name="product_sn" id="product_sn" type="text"  size="10" value="{{$param.product_sn}}" />
    商品名称：<input name="product_name" id="product_name" type="text"  size="18" value="{{$param.product_name}}" />
    周转基数(天)：<input name="days" id="days" type="text"  size="2" value="{{$param.days}}" />
    <select name="type">
      <option value="">周转率</option>
      <option value=">=" {{if $param.type eq '>='}}selected{{/if}}>周转率大于</option>
      <option value="<" {{if $param.type eq '<'}}selected{{/if}}>周转率小于</option>
    </select>
    <input name="rate" id="rate" type="text"  size="2" value="{{$param.rate}}" />
    <input type="checkbox" name="onlyShowHaveStockNumber" value="1" {{if $param.onlyShowHaveStockNumber}}checked{{/if}}/>只显示当前库存大于0的产品
    <input type="button" name="dosearch" value="按条件搜索" onclick="if (document.getElementById('days').value == ''){alert('滞销天数必须输入!');return false;} ajax_search($('searchForm'),'{{url param.todo=search}}','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">产品库存周转率列表 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]</div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td >产品编码</td>
				<td >产品名称</td>
				<td >规格</td>
				<td >当前库存</td>
				<td >出库数量</td>
				<td >周转率</td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=item}}
		<tr>
			<td>{{$item.product_sn}} </td>
			<td>{{$item.product_name}} </td>
			<td>{{$item.goods_style}} </td>
			<td>{{$item.number}} </td>
			<td>{{$item.stock_number}} </td>
			<td>{{$item.rate}} </td>
		  </tr>
		{{/foreach}}
		</tbody>
		<thead>
		<tr>
		  <td>合计</td>
		  <td >*</td>
		  <td >*</td>
		  <td >{{$total.Number}}</td>
		  <td >{{$total.StockNumber}}</td>
		  <td >{{$total.Rate}}</td>
	    </tr>
		</thead>
		{{/if}}
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>	