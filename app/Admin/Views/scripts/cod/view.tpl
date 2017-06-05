<div class="title">查看详情</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="15%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="15%"><strong>单据编号</strong></td>
      <td>{{$data.bill_no}}</td>
    </tr>
    <tr>
      <td><strong>物流公司</strong></td>
      <td>{{$data.logistic_name}}</td>
      <td><strong>配送方式</strong></td>
      <td>{{if $data.logistic_code neq 'ems'}}快递{{else}}EMS{{/if}}</td>
    </tr>
    <tr>
      <td><strong>原订单金额</strong></td>
      <td>{{$data.amount}}</td>
      <td><strong>变更后金额</strong></td>
      <td>{{$data.amount+$data.change_amount}}</td>
    </tr>
    <tr>
      <td><strong>变更理由</strong></td>
      <td colspan="3">{{$data.change_remark}}</td>
    </tr>
</tbody>
</table>

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

