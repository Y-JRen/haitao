{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">开始日期从：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="todate" id="todate" size="15" value="{{$param.todate}}" /></span>
    产品编码：<input type="text" name="product_sn" id="product_sn" size="10" value="{{$param.product_sn}}">
    <input type="button" name="dosearch" value="按条件搜索" onclick="ajax_search($('searchForm'),'{{url param.todo=search}}','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>
</div>
{{/if}}

<div id="ajax_search">

<div class="title">产品进销存 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]</div>
<div class="content">
        <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
        {{if $total.no_tax_to_amount > 0}}
        <b>&nbsp;&nbsp;&nbsp;期末总成本(未税)：{{$total.no_tax_to_amount}}</b>
        {{/if}}
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 日期 = 发货或收货日期<br>
	    　* 成本 = 当前出库或入库成本<br>
	    </font>
	    </div>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
	<thead>
	  <tr>
	    <td>&nbsp;</td>
		<td colspan="3" style="text-align:center">期初</td>
		<td colspan="4" style="text-align:center">进</td>
		<td colspan="4" style="text-align:center">销</td>
		<td colspan="3" style="text-align:center">期末</td>
      </tr>
      <tr>
        <td>产品编号</td>
        <td width="6%">成本</td>
        <td width="5%">数量</td>
        <td width="7%">总金额</td>
        <td width="10%">入库方式</td>
        <td width="6%">成本</td>
        <td width="5%">数量</td>
        <td width="7%">总金额</td>
        <td width="10%">出库方式</td>
        <td width="6%">成本</td>
        <td width="5%">数量</td>
        <td width="7%">总金额</td>
        <td width="6%">成本</td>
        <td width="5%">数量</td>
        <td width="7%">总金额</td>
      </tr>
	</thead>
	<tbody>
	  {{foreach from=$datas item=data}}
	    {{foreach from=$data.instock item=instock name=instock}}
	      <tr>
	        <td>{{if $smarty.foreach.instock.iteration eq 1}}<a title="{{$data.product_name}}">{{$data.product_sn}}</a>{{else}}&nbsp;{{/if}}</td>
	        <td>{{if $smarty.foreach.instock.iteration eq 1}}{{$data.from_cost}}{{else}}&nbsp;{{/if}}</td>
	        <td>{{if $smarty.foreach.instock.iteration eq 1}}{{$data.from_number}}{{else}}&nbsp;{{/if}}</td>
	        <td>{{if $smarty.foreach.instock.iteration eq 1}}{{$data.from_cost*$data.from_number}}{{else}}&nbsp;{{/if}}</td>
	        <td>{{$instockType[$instock.bill_type]}}</td>
		    <td>{{$instock.cost}}</td>
		    <td>{{$instock.number}}</td>
		    <td>{{$instock.amount}}</td>
		    {{if $data.outstock[$instock.index]}}
		      <td>
		      {{foreach from=$outstockType key=billType item=billTypeName}}
		        {{if $data.outstock[$instock.index].bill_type eq $billType}}{{$billTypeName}}{{/if}}
		      {{/foreach}}
		      </td>
		      <td>{{$data.outstock[$instock.index].cost}}</td>
		      <td>{{$data.outstock[$instock.index].number}}</td>
		      <td>{{$data.outstock[$instock.index].amount}}</td>
		    {{else}}
		      <td>&nbsp;</td>
		      <td>&nbsp;</td>
		      <td>&nbsp;</td>
		      <td>&nbsp;</td>
		    {{/if}}
	        <td>{{if $smarty.foreach.instock.iteration eq 1}}{{$data.to_cost}}{{else}}&nbsp;{{/if}}</td>
	        <td>{{if $smarty.foreach.instock.iteration eq 1}}{{$data.to_number}}{{else}}&nbsp;{{/if}}</td>
	        <td>{{if $smarty.foreach.instock.iteration eq 1}}{{$data.to_cost*$data.to_number}}{{else}}&nbsp;{{/if}}</td>
	      </tr>
	    {{/foreach}}
	  {{/foreach}}
	  {{if $total}}
	  <tr>
	    <td><b>合计</b></td>
	    <td><b>{{$total.from_cost|default:0}}</b></td>
	    <td><b>{{$total.from_number}}</b></td>
	    <td><b>{{$total.from_amount}}</b></td>
	    <td>&nbsp;</td>
	    <td><b>{{$total.instock_cost|default:0}}</b></td>
	    <td><b>{{$total.instock_number}}</b></td>
	    <td><b>{{$total.instock_amount}}</b></td>
	    <td>&nbsp;</td>
	    <td><b>{{$total.outstock_cost|default:0}}</b></td>
	    <td><b>{{$total.outstock_number}}</b></td>
	    <td><b>{{$total.outstock_amount}}</b></td>
	    <td><b>{{$total.to_cost|default:0}}</b></td>
	    <td><b>{{$total.to_number}}</b></td>
	    <td><b>{{$total.to_amount}}</b></td>
	  </tr>
	  <tr>
	    <td colspan="13">&nbsp;</td>
	    <td colspan="2"><font color="white">{{$diff}}</font></td>
	  </tr>
	  {{/if}}
	</tbody>
  </table>
</div>
<div style="padding:0 5px;"></div>
</div>