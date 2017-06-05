<form name="myForm1" id="myForm1">
<input type="hidden" name="logistic_code" size="20" value="{{$data.logistic_code}}" />
<input type="hidden" name="tid" size="20" value="{{$data.tid}}" />
<input type="hidden" name="is_check" id="is_check" size="20" />
<input type="hidden" name="amount" size="20" value="{{$data.amount}}" />
<input type="hidden" name="change_amount" size="20" value="{{$data.tmp_amount}}" />
<input type="hidden" name="bill_no" value="{{$data.bill_no}}" />
<div class="title">单据审核</div>
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
      <td><strong>配送状态</strong></td>
      <td>{{$logisticStatus[$data.logistic_status]}}</td>
    </tr>
    <tr>
      <td><strong>原订单金额</strong></td>
      <td>{{$data.amount}}</td>
      <td><strong>变更后金额</strong></td>
      <td>{{$data.amount+$data.tmp_amount}}</td>
    </tr>
    <tr>
      <td><strong>变更理由</strong></td>
      <td colspan="3">{{$data.change_remark}}</td>
    </tr>
</tbody>
</table>

</div>
<div class="submit">
{{if $data.lock_name eq $auth.admin_name}}
说明：<input type="text" name="remark" id="remark" size="80" value="" /><br>
{{if $data.logistic_status<=2}}
<input type="button" name="dosubmit1" id="dosubmit1" value="同意" onclick="dosubmit(1)"/>
{{else}}
此单已完成不允许变更
{{/if}}
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
		if (check==1){
		    ajax_submit($('myForm1'),'{{url param.is_check=1}}');
		}
		else {
		    ajax_submit($('myForm1'),'{{url param.is_check=2}}');
		}
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