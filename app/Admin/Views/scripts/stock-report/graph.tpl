<script language="javascript" type="text/javascript" src="/scripts/jquery.js"></script>
<script language="javascript" type="text/javascript" src="/scripts/flot/excanvas.min.js"></script>
<script language="javascript" type="text/javascript" src="/scripts/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="/scripts/flot/jquery.flot.stack.js"></script>
<link type="text/css" rel="stylesheet" href="/styles/99vk/body_test.css" />
<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<form id="myform" name="myform" mothod="get" action="{{url}}">
<br>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr bgcolor="#F0F1F2">
    <td width="100">　产品名称：</td>
    <td width="200">{{$product.product_name}}</td>
    <td width="100">　产品编码：</td>
    <td>{{$product.product_sn}}</td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#F0F1F2">
    <td width="100">　选择月份：</td>
    <td width="200">
      <select name="year" id="year" onchange="document.getElementById('myform').submit()">
		 <option value="2015" {{if $param.year eq '2015'}}selected{{/if}}>2015</option>
		 <option value="2014" {{if $param.year eq '2014'}}selected{{/if}}>2014</option>
		 <option value="2013" {{if $param.year eq '2013'}}selected{{/if}}>2013</option>
      </select>
      <select name="month" id="month" onchange="document.getElementById('myform').submit()">
        <option value="01" {{if $param.month eq '01'}}selected{{/if}}>01</option>
        <option value="02" {{if $param.month eq '02'}}selected{{/if}}>02</option>
        <option value="03" {{if $param.month eq '03'}}selected{{/if}}>03</option>
        <option value="04" {{if $param.month eq '04'}}selected{{/if}}>04</option>
        <option value="05" {{if $param.month eq '05'}}selected{{/if}}>05</option>
        <option value="06" {{if $param.month eq '06'}}selected{{/if}}>06</option>
        <option value="07" {{if $param.month eq '07'}}selected{{/if}}>07</option>
        <option value="08" {{if $param.month eq '08'}}selected{{/if}}>08</option>
        <option value="09" {{if $param.month eq '09'}}selected{{/if}}>09</option>
        <option value="10" {{if $param.month eq '10'}}selected{{/if}}>10</option>
        <option value="11" {{if $param.month eq '11'}}selected{{/if}}>11</option>
        <option value="12" {{if $param.month eq '12'}}selected{{/if}}>12</option>
      </select>
    </td>
    <td width="100">　库存类型：</td>
    <td>
      <select name="stock_type" id="stock_type" onchange="document.getElementById('myform').submit()">
        <option value="">全部</option>
        <option value="out_stock" {{if $param.stock_type eq 'out_stock'}}selected{{/if}}>出库</option>
        <option value="in_stock" {{if $param.stock_type eq 'in_stock'}}selected{{/if}}>入库</option>
      </select>
      {{if $param.stock_type eq 'out_stock'}}
        <select name="bill_type" onchange="document.getElementById('myform').submit()">
          <option value="">请选择单据类型</option>
        {{foreach from=$outTypes key=type item=type_name}}
          <option value="{{$type}}" {{if $param.bill_type eq $type}}selected{{/if}}>{{$type_name}}</option>
        {{/foreach}}
        </select>
      {{/if}}
      {{if $param.stock_type eq 'in_stock'}}
        <select name="bill_type" onchange="document.getElementById('myform').submit()">
          <option value="">请选择单据类型</option>
        {{foreach from=$inTypes key=type item=type_name}}
          <option value="{{$type}}" {{if $param.bill_type eq $type}}selected{{/if}}>{{$type_name}}</option>
        {{/foreach}}
        </select>
      {{/if}}
    </td>
  </tr>
</table>
</form>
<br>
<table width="100%" border="0">
<tr>
<td width="300">&nbsp;</td>
<td id="hint">&nbsp;</td>
</tr>
</table>
<br>
<div style="width:780px;height:400px;" id="placeholder"></div>

<script type="text/javascript">
$(function () {
    var d1 = [];
    var d2 = [];
    {{if $outStockData}}
    {{foreach from=$outStockData name=data item=data}}
        d1.push([{{$smarty.foreach.data.iteration}}, {{$data}}]);
    {{/foreach}}
    {{/if}}
    
    {{if $inStockData}}
    {{foreach from=$inStockData name=data item=data}}
        d2.push([{{$smarty.foreach.data.iteration}}, {{$data}}]);
    {{/foreach}}
    {{/if}}
    
    var ticks = [];
    var days = {{$days}};
    for (i = 1; i <= days; i++) {
        ticks.push([i,i]);
    }
    
    $.plot($("#placeholder"), [
        {
            label: {{if $outStockData}}"出库数量"{{else if $inStockData}}"入库数量"{{/if}},
            color: {{if $outStockData}}"#edc240"{{else if $inStockData}}"#afd8f8"{{/if}},
            data: {{if $outStockData}}d1{{else if $inStockData}}d2{{/if}},
            lines: { show: true },
            points: { show: true }
        }
        {{if $outStockData && $inStockData}}
        ,
        {
            color: "#afd8f8",
            label: "入库数量",
            data: d2,
            lines: { show: true },
            points: { show: true }
        }
        {{/if}}
        ],
        {
        xaxis: {
                ticks: ticks
               },
        grid: { hoverable: true, clickable: true }
        }
    );
    
    $("#placeholder").bind("plotclick", function (event, pos, item) {
        if (item) {
            document.getElementById('hint').innerHTML = '日期：{{$param.year}}-{{$param.month}}-' + item.datapoint[0] + '&nbsp;&nbsp;' + item.series.label + '：' + item.datapoint[1];
        }
    });
});
</script>