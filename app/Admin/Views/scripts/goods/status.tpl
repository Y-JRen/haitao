<form name="myForm1" id="myForm1">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="20%"><strong>下架理由</strong> * </td>
      <td colspan="3"><textarea name="remark" style="width: 250px;height: 80px" id="remark"></textarea></td>
    </tr>
</tbody>
</table>

<div class="submit">
<input type="button" name="dosubmit" onclick="submitForm()" value="确认" />
</div>
</form>
<script>
function submitForm()
{
    if($('remark').value.trim()==''){
        alert('请填写下架理由');
        return false;
    }
    if(confirm('确定此操作吗？')){
        ajax_submit($('myForm1'),'{{url}}');
    }
}
</script>