{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">入库日期从：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="todate" id="todate" size="15" value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()" /></span>
    &nbsp;&nbsp;
    供应商：
    <select name="supplier_id">
	  <option value="">请选择...</option>
      {{foreach from=$supplier item=s}}
        {{if $s.status==0}}
          <option value="{{$s.supplier_id}}" {{if $param.supplier_id eq $s.supplier_id}}selected{{/if}}>{{$s.supplier_name}}</option>
        {{/if}}
      {{/foreach}}
	</select>
	采购类型：
    <select name="purchase_type">
	  <option value="">请选择...</option>
      <option value="1" {{if $param.purchase_type eq '1'}}selected{{/if}}>经销</option>
      <option value="2" {{if $param.purchase_type eq '2'}}selected{{/if}}>代销</option>
	</select>
	付款状态：
	<select name="status">
	  <option value="">请选择...</option>
      <option value="0" {{if $param.status eq '0'}}selected{{/if}}>未付款</option>
      <option value="1" {{if $param.status eq '1'}}selected{{/if}}>部分付款</option>
      <option value="2" {{if $param.status eq '2'}}selected{{/if}}>已付款</option>
	</select>
    <br><br>
    产品编码：<input name="product_sn" id="product_sn" type="text"  size="10" value="{{$param.product_sn}}" />
    产品名称：<input name="product_name" id="product_name" type="text"  size="18" value="{{$param.product_name}}" />
   <input type="button" name="dosearch" value="按条件搜索" onclick="ajax_search($('searchForm'),'{{url param.todo=search}}','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">供应商付款统计 <!--[<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]--> </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			  {{if $param.supplier_id}}
			  <td>供应商</td>
			  <td>单据编号</td>
			  <td>采购类型</td>
			  <td>应付金额(含税)</td>
			  <td>应付金额(不含税）</td>
			  <td>付款状态</td>
			  <td>入库日期</td>
			  {{else}}
			  <td>供应商</td>
			  <td>应付金额(含税)</td>
			  <td>应付金额(不含税）</td>
			  {{/if}}
		    </tr>
		</thead>
		<tbody>
		  {{if $datas}}
		  {{foreach from=$datas item=data}}
		  <tr>
		    {{if $param.supplier_id}}
		    <td>{{$data.supplier_name}}</td>
		    <td>{{$data.bill_no}}</td>
		    <td>{{if $data.purchase_type eq 1}}经销{{else}}代销{{/if}}</td>
		    <td>{{$data.amount1}}</td>
		    <td>{{$data.amount2}}</td>
		    <td>
		      {{if $data.status eq 1}}
		      部分付款
		      {{elseif $data.status eq 2}}
		      已付款
		      {{else}}
		      未付款
		      {{/if}}
		    </td>
		    <td>{{$data.finish_time|date_format:"%Y-%m-%d"}}</td>
		    {{else}}
		    <td>{{$data.supplier_name}}</td>
		    <td>{{$data.amount1}}</td>
		    <td>{{$data.amount2}}</td>
		    {{/if}}
		  </tr>
		  {{/foreach}}
		  <thead>
		  <tr>
		    <td>合计</td>
		  {{if $param.supplier_id}}
		    <td>*</td>
		    <td>*</td>
		    <td>{{$totalData.amount1}}</td>
		    <td>{{$totalData.amount2}}</td>
		    <td>*</td>
		    <td>*</td>
		  {{else}}
		    <td>{{$totalData.amount1}}</td>
		    <td>{{$totalData.amount2}}</td>
		  {{/if}}
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