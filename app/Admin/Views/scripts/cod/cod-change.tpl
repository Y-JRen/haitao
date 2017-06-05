<form name="myForm1" id="myForm1">
<input type="hidden" name="old_amount" size="20" value="{{$data.amount}}" />
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="20%"><strong>原代收货款金额</strong></td>
      <td colspan="3">{{$data.amount}}元</td>
    </tr>
    <tr>
      <td width="20%"><strong>变更后金额</strong> * </td>
      <td colspan="3"><input type="text" name="change_amount" size="6" maxlength="6" value="" id ="amount" /></td>
    </tr>
    <tr>
      <td width="20%"><strong>变更理由</strong> * </td>
      <td colspan="3"><textarea name="change_remark" style="width: 250px;height: 80px" id="remark"></textarea></td>
    </tr>
</tbody>
</table>

{{if $data.is_change==0}}
<div class="submit">
<input type="button" name="dosubmit" onclick="submitForm()" value="确认" />
</div>{{/if}}

</form>
<script>
function submitForm()
{
    if($('amount').value.trim()==''){
        alert('请填写变更后金额');
        return false;
    }
    if($('remark').value.trim()==''){
        alert('请填写变更理由');
        return false;
    }
    if ($('amount').value >= {{$data.amount}}) {
        alert('变更后金额不能大于原金额');
        return false;
    }
    if(confirm('确定要申请变更吗？')){
        ajax_submit($('myForm1'),'{{url}}');
    }
}
</script>