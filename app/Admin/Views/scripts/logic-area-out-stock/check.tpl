<form name="myForm1" id="myForm1">
<input type="hidden" name="logic_area" size="20" value="{{$logic_area}}" />
<input type="hidden" name="bill_status" size="20" value="{{$data.bill_status}}" />
<input type="hidden" name="bill_type" size="20" value="{{$data.bill_type}}" />
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<input type="hidden" name="item_no" size="20" value="{{$data.item_no}}" />
<input type="hidden" name="is_cancel" size="20" value="{{$data.is_cancel}}" />
<input type="hidden" name="is_check" id="is_check" size="20" value="{{$data.is_check}}" />
<input type="hidden" name="is_back" size="20" value="{{$data.is_back}}" />
<div class="title">出库单审核</div>
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
    <td>应发数量</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$details item=d key=key}}
	<tr>
	<td>{{$key+1}}</td>
	<td>{{$d.product_sn}}<input type="hidden" name="product_id[]" value="{{$d.product_id}}"></td>
	<td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>)</td>
	<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}<input type="hidden" name="batch_id[]" value="{{if $d.batch_id}}{{$d.batch_id}}{{else}}0{{/if}}"></td>
	<td>{{$status[$d.status_id]}}<input type="hidden" name="status[]" value="{{$d.status_id}}"></td>
	<td>{{$d.number}}<input type="hidden" name="number[]" value="{{$d.number}}"></td>
	</tr>
	{{/foreach}}
</tbody>
</table>
<div style="text-align:right;padding:10px 20px">
  {{if $data.bill_type eq '2'}}
  <strong>退款金额：</strong>{{$data.amount}}
  <input type="hidden" name="amount" value="{{$data.amount}}">
  <input type="hidden" name="supplier_id" value="{{$data.supplier_id}}">
  {{/if}}
  <strong>应发合计：</strong>{{$data.total_number}}
</div>

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

</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name && !$hideButton}}
说明：<input type="text" name="remark" id="remark" size="80" value="" /><br>
<input type="button" name="dosubmit1" id="dosubmit1" value="同意" onclick="dosubmit(1)"/>
<input type="button" name="dosubmit2" id="dosubmit2" value="拒绝" onclick="dosubmit(2)"/>
{{/if}}
</div>

</form>
<script language="JavaScript">
function dosubmit(check)
{
	$('is_check').value=check;
	if (check==1){
		var info = '同意此申请吗?'
	}else{
		if($('remark').value.trim()==''){alert('请填写说明');return false;}
		var info = '拒绝此申请吗?'
	}
	if(confirm(info)){
		$('dosubmit'+check).value = '处理中';
		$('dosubmit'+check).disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed(check)
{
	if (check==1){
	    var info = '同意'
	}else{
	    var info = '拒绝'
	}
	$('dosubmit'+check).value = info;
	$('dosubmit'+check).disabled = false;
}

</script>