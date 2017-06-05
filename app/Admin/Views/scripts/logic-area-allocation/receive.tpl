<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<input type="hidden" name="from_logic_area" size="20" value="{{$data.from_lid}}" />
<input type="hidden" name="to_logic_area" size="20" value="{{$data.to_lid}}" />
<div class="title">调拨单收货</div>
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

<table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
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
	<td>{{$key+1}}<input type="hidden" name="ids[]" value="{{$key}}"></td>
	<td>{{$d.product_sn}}<input type="hidden" name="product_id[]" value="{{$d.product_id}}"></td>
	<td>{{$d.goods_name}}</td>
	<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}<input type="hidden" name="batch_id[]" value="{{if $d.batch_id}}{{$d.batch_id}}{{else}}0{{/if}}"></td>
	<td>{{$status[$d.status_id]}}<input type="hidden" name="status[]" value="{{$d.status_id}}"></td>
	<td>{{$d.number}}<input type="hidden" size="5" name="number[]" value="{{$d.number}}"></td>
	</tr>
	{{/foreach}}
</tbody>
</table>
<div style="text-align:right;padding:10px 20px"><strong>应收合计：</strong>{{$data.total_number}}
</div>

</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name}}
<input type="button" name="dosubmit1" id="dosubmit1" value="收货" onclick="dosubmit()"/>
{{/if}}
</div>
</form>
<script>
function dosubmit()
{
	if(confirm('确认收货吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed()
{
	$('dosubmit1').value = '收货';
	$('dosubmit1').disabled = false;
}

</script>