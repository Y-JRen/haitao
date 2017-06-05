<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
  <form id="searchForm" method="get">
  <span style="float:left;line-height:18px;">开票日期从：</span>
    <span style="float:left;line-height:18px;width:100px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;width:100px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="todate" id="todate" size="12" value="{{$param.todate}}" /></span>
    发票状态：<select name="status">
        <option value="">请选择...</option>
        <option value="1" {{if $param.status eq 1}}selected{{/if}}>有效</option>
        <option value="2" {{if $param.status eq 2}}selected{{/if}}>作废</option>
    </select>
    发票号：<input type="text" size="12" name="invoice_no" value="{{$param.invoice_no}}">
    订单号：<input type="text" size="20" name="batch_sn" value="{{$param.batch_sn}}">
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">发票列表</div>
	<div style="float:right;top:10px"><br><b>开票金额：{{$total.amount}}</b>&nbsp;&nbsp;&nbsp;</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>发票号</td>
				<td>开票日期</td>
				<td>开票金额</td>
				<td>订单号</td>
				<td>状态</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$data.invoice_no}}</td>
		    <td valign="top">{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
		    <td valign="top">{{$data.amount}}</td>
			<td valign="top">{{$data.batch_sn}}</td>
			<td valign="top" id="ajax_status{{$data.invoice_no}}">
			  {{if $data.status eq 1}}<a href="javascript:fGo()" onclick="ajax_status('/admin/finance/invoice-status', '{{$data.invoice_no}}', 2)">有效</a>
			  {{elseif $data.status eq 2}}<a href="javascript:fGo()" onclick="ajax_status('/admin/finance/invoice-status', '{{$data.invoice_no}}', 1)"><font color="red">作废</font></a>
			  {{/if}}
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>