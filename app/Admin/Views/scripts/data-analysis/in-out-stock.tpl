{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url}}">
    <span style="float:left;line-height:18px;">
      <select name="logic_area">
      <option value="">请选择仓库</option>
      {{foreach from=$areas key=key item=item}}
      <option value="{{$key}}" {{if $param.logic_area eq $key}}selected{{/if}}>{{$item}}</option>
      {{/foreach}}
      </select>
      <select name="stock_type" id="stock_type" onchange="changeStockType()">
        <option value="instock" {{if $param.stock_type eq 'instock'}}selected{{/if}}>产品入库</option>
        <option value="outstock" {{if $param.stock_type eq 'outstock'}}selected{{/if}}>产品出库</option>
      </select>&nbsp;&nbsp;
      <select name="bill_type" id="bill_type">
      </select>&nbsp;&nbsp;
      <select name="bill_status" id="bill_status">
      </select>&nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:100px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"  /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()"  /></span>
    <br><br>
    制单人：<input name="admin_name" type="text"  size="20" value="{{$param.admin_name}}"/>
    产品名称：<input name="product_name" type="text"  size="18" value="{{$param.product_name}}"/>
    产品编号：<input name="product_sn" type="text"  size="8" value="{{$param.product_sn}}"/>
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">产品出入库列表 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]  </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>{{if $param.stock_type eq 'outstock'}}出{{else}}入{{/if}}库单据类型</td>
				<td>产品名称</td>
				<td>产品编码</td>
				<td>{{if $param.stock_type eq 'outstock'}}出库{{else}}入库{{/if}}数量</td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>{{$data.bill_type}}</td>
		  <td>{{$data.product_name}} <font color="red">({{$data.goods_style}})</font></td>
		  <td>{{$data.product_sn}}</td>
		  <td>{{$data.number}}</td>
		</tr>
		{{/foreach}}
		<thead>
		<tr>
		  <td>合计</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>{{$total.Number}}</td>
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
    $('bill_type').options.add(new Option('请选择单据类型...', ''));
    
    $('bill_status').options.length = 0;
    $('bill_status').options.add(new Option('请选择单据状态...', ''));
    
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