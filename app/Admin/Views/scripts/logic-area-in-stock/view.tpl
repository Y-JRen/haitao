<div class="title">入库单信息</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}{{if $data.bill_type eq 2}}({{if $data.purchase_type eq 1}}经销{{else}}代销{{/if}}){{/if}}</td>
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
        <td><strong>备注</strong></td>
        <td >&nbsp;{{$data.remark}}</td>
        <td><strong>供应商</strong></td>
        <td>{{$data.supplier_name}}</td>
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
        <td>应收数量</td>
        <td>实收数量</td>
        <td>价格</td>
        </tr>
  </thead>
  <tbody>
  {{foreach from=$details item=d key=key}}
  <tr>
  <td>{{$key+1}}</td>
  <td>{{$d.product_sn}}</td>
  <td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>)</td>
  <td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}</td>
  <td>{{$d.plan_number}}</td>
  <td>
  {{foreach from=$d.status item=item}}
  <p>{{$status[$item.status_id]}}：{{$item.number}}</p>
  {{/foreach}}
  <p>小计：{{$d.real_number}}</p>
  </td>
  <td>{{$d.shop_price}}</td>
  </tr>
  {{/foreach}}
</tbody>
</table>

<div style="text-align:right;padding:10px 20px"><strong>本次应收合计：</strong>{{$data.total_number}}
&nbsp;&nbsp;&nbsp;&nbsp;<strong>本次实数合计：</strong>{{$data.total_real_number}}
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

{{if $payment}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>应付款金额</strong></td>
      <td>{{$payment.amount}}</td>
    </tr>
    <tr>
      <td width="12%"><strong>已付款金额</strong></td>
      <td>{{$payment.real_amount}}</td>
    </tr>
    <tr>
      <td width="12%"><strong>入库单号</strong></td>
      <td>{{$payment.paper_no}}</td>
    </tr>
</tbody>
</table>
{{/if}}

</div>

<div class="submit">
<input type="button" onclick="window.open('{{url param.action=print param.id=$data.instock_id}}')" value="打印">
{{if $data.lock_name eq $auth.admin_name}}
{{if $data.bill_type ne 15 && $data.is_cancel eq 0 && ($data.bill_status eq 3 || $data.bill_status eq 6) && !$data.parent_id}}
<input type="button" onclick="openDiv('{{url param.action=cancel param.id=$data.instock_id}}','ajax','申请取消',400,200)" value="申请取消">
{{/if}}
{{/if}}
</div>

