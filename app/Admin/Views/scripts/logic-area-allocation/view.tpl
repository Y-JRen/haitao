<div class="title">调拨单详情</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>单据编号</strong> * </td>
      <td>{{$data.bill_no}}</td>
      <td width="10%"></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>调出区域</strong> * </td>
      <td>{{$areas[$data.from_lid]}}</td>
      <td><strong>调入区域</strong> * </td>
      <td>{{$areas[$data.to_lid]}}</td>
    </tr>
    <tr>
      <td><strong>制单日期</strong> * </td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong> * </td>
      <td>{{$data.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="3">&nbsp;{{$data.remark}}</td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
<tr>
    <td>序号</td>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>状态</td>
    <td>调拨数量</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$details item=d key=key}}
	<tr>
	<td>{{$key+1}}</td>
	<td>{{$d.product_sn}}</td>
	<td>{{$d.goods_name}}</td>
	<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}</td>
	<td>{{$status[$d.status_id]}}</td>
	<td>{{$d.number}}</td>
	</tr>
	{{/foreach}}
</tbody>
</table>
<div style="text-align:right;padding:10px 20px"><strong>数量合计：</strong>{{$data.total_number}}</div>

{{if $op_cancel}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%" style="color:red"><strong>申请取消</strong></td>
      <td colspan="5"></td>
    </tr>
    <tr>
      <td width="12%"><strong>申请日期</strong></td>
      <td width="20%">{{$op_cancel.op_time|date_format:"%Y-%m-%d"}}</td>
      <td width="12%"><strong>申请人</strong></td>
      <td>{{$op_cancel.admin_name}}</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="5">{{$op_cancel.remark}}</td>
    </tr>
</tbody>
</table>
{{/if}}

{{if $op_check}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>      <td width="12%" style="color:red"><strong>审核意见</strong></td>
      <td colspan="5">{{if $data.is_check==1}}同意{{else}}拒绝{{/if}}</td>
</tr>
    <tr>
      <td width="12%"><strong>审核日期</strong></td>
      <td width="20%">{{$op_check.op_time|date_format:"%Y-%m-%d"}}</td>
      <td width="12%"><strong>审核人</strong></td>
      <td>{{$op_check.admin_name}}</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="5">{{$op_check.remark}}</td>
    </tr>
</tbody>
</table>
{{/if}}

</div>

<div class="submit">
<input type="button" onclick="window.open('{{url param.action=print param.id=$data.aid}}')" value="打印">
{{if $data.lock_name eq $auth.admin_name}}
{{if $data.is_cancel==0 and $data.bill_status!=2 and $data.bill_status<5}}
<input type="button" onclick="openDiv('{{url param.action=cancel param.id=$data.aid}}','ajax','申请取消',400,200)" value="申请取消">
{{/if}}
{{/if}}
</div>
