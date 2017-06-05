<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_type" size="20" value="{{$data.bill_type}}" />
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<input type="hidden" name="logistic_code" size="20" value="{{$data.logistic_code}}" />
<input type="hidden" name="logistic_no" size="20" value="{{$data.logistic_no}}" />
<div class="title">运输单确认</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>{{$data.bill_no_str}}</td>
      <td width="12%"><strong>制单日期</strong></td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
    </tr>
    <tr>
      <td><strong>配送方式</strong></td>
      <td>{{if $data.logistic_code neq 'ems'}}快递{{else}}EMS{{/if}}</td>
      <td><strong>{{if $data.is_cod}}应收金额{{else}}订单金额{{/if}}</strong></td>
      <td>{{$data.amount}}</td>
      <td><strong>付款方式</strong></td>
      <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
    </tr>
    <tr>
      <td><strong>重量</strong></td>
      <td>{{$data.weight}}</td>
      <td><strong>体积</strong></td>
      <td>{{$data.volume}}</td>
      <td><strong>承运商</strong></td>
      <td>{{$data.logistic_name}}</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="5">&nbsp;{{$data.remark}}</td>
    </tr>
</tbody>
</table>

</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name}}
<input type="button" onclick="window.open('{{url param.action=print2 param.id=$data.tid param.is_cod=$data.is_cod param.logistic_code=$data.logistic_code}}')" value="打印运输单">
<input type="button" name="dosubmit1" id="dosubmit1" value="确认" onclick="dosubmit()"/>
{{/if}}
</div>
</form>
<script>
function dosubmit()
{
	if(confirm('确认吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed()
{
	$('dosubmit1').value = '确认';
	$('dosubmit1').disabled = false;
}

</script>