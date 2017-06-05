<div class="title">查看详情</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>{{$data.bill_no}}</td>
    </tr>
    <tr>
      <td><strong>制单日期</strong></td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong></td>
      <td>{{$data.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>称重</strong></td>
      <td>&nbsp;{{$data.weight}}</td>
      <td><strong>备注</strong></td>
      <td>&nbsp;{{$data.remark}}</td>
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
    <td>应发数量</td>
	<td>成本{{if $data.bill_type eq 2}}/退货金额{{/if}}</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$details item=d key=key}}
	<tr>
	<td>{{$key+1}}</td>
	<td>{{$d.product_sn}}</td>
	<td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>) </td>
	<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}</td>
    <td>{{$d.number}}</td>
	<td>{{$d.p_cost}}{{if $data.bill_type eq 2}}/{{$d.shop_price}}{{/if}}</td>
	</tr>
	{{/foreach}}
</tbody>
</table>
<div style="text-align:right;padding:10px 20px">
  {{if $data.bill_type eq '2'}}
  <strong>退款金额：</strong>{{$data.amount}}
  {{/if}}
  <strong>数量合计：</strong>{{$data.total_number}}
</div>

{{if $op_cancel}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%" style="color:red"><strong>申请取消</strong></td>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td width="12%"><strong>申请日期</strong></td>
      <td width="20%">{{$op_cancel.op_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
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
      <td width="20%">{{$op_cancel_check.op_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
      <td width="12%"><strong>审核人</strong></td>
      <td>{{$op_cancel_check.admin_name}}</td>
    </tr>
    {{if $receive}}
    <tr>
      <td><strong>应收款金额</strong></td>
      <td colspan="3">{{$receive.amount}}</td>
    </tr>
    {{/if}}
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
      <td width="20%">{{$op_check.op_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
      <td width="12%"><strong>审核人</strong></td>
      <td>{{$op_check.admin_name}}</td>
    </tr>
    {{if $receive}}
    <tr>
      <td><strong>应收款金额</strong></td>
      <td colspan="3">{{$receive.amount}}</td>
    </tr>
    {{/if}}
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="3">{{$op_check.remark}}</td>
    </tr>
</tbody>
</table>
{{/if}}

</div>

<div class="submit">
<input type="button" onclick="window.open('{{url param.action=print param.id=$data.outstock_id}}')" value="打印">
{{if $data.lock_name eq $auth.admin_name}}
{{if $data.is_cancel eq 0 and ($data.bill_status eq 3 or $data.bill_status eq 4)}}
<input type="button" onclick="openDiv('{{url param.action=cancel param.id=$data.outstock_id}}','ajax','申请取消',400,200)" value="申请取消">
{{/if}}
{{/if}}
</div>
