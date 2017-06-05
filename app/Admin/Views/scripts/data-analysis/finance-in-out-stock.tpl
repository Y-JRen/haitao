{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url}}">
    <span style="float:left;line-height:18px;">
      <select name="stock_type" id="stock_type" onchange="changeStockType()">
        <option value="instock" {{if $param.stock_type eq 'instock'}}selected{{/if}}>产品入库</option>
        <option value="outstock" {{if $param.stock_type eq 'outstock'}}selected{{/if}}>产品出库</option>
      </select>&nbsp;&nbsp;
      <select name="bill_type" id="bill_type">
      </select>&nbsp;&nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">开始日期从：</span><span style="float:left;width:100px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"  /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:120px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()"  /></span>
    <input type="checkbox" name="show_bill" id="show_bill" value="1" {{if $param.show_bill}}checked{{/if}}>列出单据编号
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">出入库单据列表 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]  </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>供应商</td>
				<td>总数量</td>
				<td>总成本</td>
				<td>总成本(不含税)</td>
				{{if $param.show_bill}}<td>单据编号</td>{{/if}}
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>{{$data.supplier_name}}</td>
		  <td>{{$data.count}}</font></td>
		  <td>{{$data.cost}}</td>
		  <td>{{$data.no_tax_cost}}</td>
		  {{if $param.show_bill}}<td>{{$data.bill}}</td>{{/if}}
		</tr>
		{{/foreach}}
		<thead>
		<tr>
		  <td>合计</td>
		  <td>{{$total.count}}</td>
		  <td>{{$total.cost|string_format:"%.3f"}}</td>
		  <td>{{$total.no_tax_cost|string_format:"%.3f"}}</td>
		  {{if $param.show_bill}}<td>&nbsp;</td>{{/if}}
		</tr>
		</thead>
		{{/if}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
</div>	

<script>
function changeStockType()
{
    $('bill_type').options.length = 0;
    
    var stock_type = $('stock_type').value;
    if (stock_type == 'instock') {
        {{foreach from=$in_stock_type key=type item=type_name}}
        {{if $type eq $param.bill_type}}
        $('bill_type').options.add(new Option('{{$type_name}}', '{{$type}}', true, true));
        {{else}}
        $('bill_type').options.add(new Option('{{$type_name}}', '{{$type}}')); 
        {{/if}}
        {{/foreach}}
        
        {{foreach from=$in_stock_status key=status item=status_name}}
        {{if $status eq $param.bill_status && $param.bill_status ne ''}}
        $('bill_status').options.add(new Option('{{$status_name}}', '{{$status}}', true, true));
        {{else}}
        $('bill_status').options.add(new Option('{{$status_name}}', '{{$status}}')); 
        {{/if}}
        {{/foreach}}
    }
    else if (stock_type == 'outstock') {
        {{foreach from=$out_stock_type key=type item=type_name}}
        {{if $type eq $param.bill_type}}
        $('bill_type').options.add(new Option('{{$type_name}}', '{{$type}}', true, true));
        {{else}}
        $('bill_type').options.add(new Option('{{$type_name}}', '{{$type}}')); 
        {{/if}}
        {{/foreach}}
        
        {{foreach from=$out_stock_status key=status item=status_name}}
        {{if $status eq $param.bill_status && $param.bill_status ne ''}}
        $('bill_status').options.add(new Option('{{$status_name}}', '{{$status}}', true, true));
        {{else}}
        $('bill_status').options.add(new Option('{{$status_name}}', '{{$status}}')); 
        {{/if}}
        {{/foreach}}
    }
}
changeStockType();
</script>