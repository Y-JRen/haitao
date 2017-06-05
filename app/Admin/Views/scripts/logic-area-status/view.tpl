<div class="title">查看详情</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>单据编号</strong> * </td>
      <td>{{$data.bill_no}}</td>
      <td width="10%"><strong>调整区域</strong> * </td>
      <td>{{$areas.$logic_area}}</td>
    </tr>
    <tr>
      <td><strong>制单日期</strong> * </td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong> * </td>
      <td>{{$data.admin_name}}</td>
    </tr>
    <tr>
      <td width="10%"><strong>备注</strong></td>
      <td colspan="3">&nbsp;{{$data.remark}}</td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
<tr>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>当前状态</td>
    <td>调整状态</td>
    <td>调整数量</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$details item=d name=basic}}
	<tr>
	<td>{{$d.product_sn}}</td>
	<td>{{$d.goods_name}}(<font color="#FF3333">{{$d.goods_style}}</font>)</td>
	<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}</td>
	<td>{{$status[$d.ostatus]}}</td>
	<td>{{$status[$d.nstatus]}}</td>
	<td>{{$d.number}}</td>
	</tr>
	{{/foreach}}
</tbody>
</table>

{{if $op_cancel}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%" style="color:red"><strong>申请取消</strong></td>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td width="12%"><strong>申请日期</strong></td>
      <td width="20%">{{$op_cancel.op_time|date_format:"%Y-%m-%d"}}</td>
      <td width="12%"><strong>申请人</strong></td>
      <td>{{$op_cancel.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="3">{{$op_cancel.remark}}</td>
    </tr>
</tbody>
</table>
{{/if}}

{{if $op_cancel_check}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%" style="color:red"><strong>审核意见</strong></td>
      <td colspan="3">{{$op_cancel_check.item_value}}</td>
    </tr>
    <tr>
      <td width="12%"><strong>审核日期</strong></td>
      <td width="20%">{{$op_cancel_check.op_time|date_format:"%Y-%m-%d"}}</td>
      <td width="12%"><strong>审核人</strong></td>
      <td>{{$op_cancel_check.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="3">{{$op_cancel_check.remark}}</td>
    </tr>
</tbody>
</table>
{{/if}}

{{if $op_check}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%" style="color:red"><strong>审核意见</strong></td>
      <td colspan="3">{{$op_check.item_value}}</td>
    </tr>
    <tr>
      <td width="12%"><strong>审核日期</strong></td>
      <td width="20%">{{$op_check.op_time|date_format:"%Y-%m-%d"}}</td>
      <td width="12%"><strong>审核人</strong></td>
      <td>{{$op_check.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="3">{{$op_check.remark}}</td>
    </tr>
</tbody>
</table>
{{/if}}

</div>

<div class="submit">
<input type="button" onclick="window.open('{{url param.action=print param.id=$data.sid}}')" value="打印">
{{if $data.lock_name eq $auth.admin_name}}
{{if $data.is_cancel==0 and $data.bill_status==0}}
<!--
<input type="button" onclick="openDiv('{{url param.action=cancel param.id=$data.sid}}','ajax','申请取消',400,200)" value="申请取消">
-->
{{/if}}
{{/if}}
</div>
