<div class="search">
  <form id="searchForm" method="get">
  补货单状态：
  <select name="status">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status eq '0'}}selected{{/if}}>未确认</option>
    <option value="1" {{if $param.status eq '1'}}selected{{/if}}>已申请</option>
    <option value="2" {{if $param.status eq '2'}}selected{{/if}}>已审核</option>
    <option value="3" {{if $param.status eq '3'}}selected{{/if}}>已收货</option>
    <option value="4" {{if $param.status eq '4'}}selected{{/if}}>已完成</option>
    <option value="9" {{if $param.status eq '9'}}selected{{/if}}>已取消</option>
  </select>
  产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="{{$param.product_name}}">
  产品编码：<input type="text" name="product_sn" size="10" maxLength="10" value="{{$param.product_sn}}">
  采购入库单号：<input type="text" name="bill_no" size="20" maxLength="50" value="{{$param.bill_no}}">
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">补货单列表</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>产品编码</td>
				<td>产品名称</td>
				<td>需求数量</td>
				<td>收货数量</td>
				<td>采购入库单号</td>
				<td>添加时间</td>
				<td>状态</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$data.product_sn}}</td>
		    <td valign="top">{{$data.product_name}}</td>
			<td valign="top">{{$data.require_number}}</td>
			<td valign="top">{{$data.receive_number}}</td>
			<td valign="top">
			{{if $billInfo[$data.replenishment_id]}}
			  {{foreach from=$billInfo[$data.replenishment_id] item=bill_no}}
			  <a href="javascript:void(0);" onclick="openDiv('/admin/logic-area-in-stock/view/logic_area/1/bill_no/{{$bill_no}}','ajax','查看单据',800,400)">{{$bill_no}}</a><br>
			  {{/foreach}}
			{{/if}}
			</td>
			<td valign="top">{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
			<td valign="top">
			  {{if $data.status eq 0}}未确认
			  {{elseif $data.status eq 1}}已申请
			  {{elseif $data.status eq 2}}已审核
			  {{elseif $data.status eq 3}}已收货
			  {{elseif $data.status eq 4}}已完成
			  {{elseif $data.status eq 9}}已取消
			  {{/if}}
			</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="openDiv('/admin/replenishment/view/id/{{$data.replenishment_id}}','ajax','补货单详情',750,400);">详请</a>
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>