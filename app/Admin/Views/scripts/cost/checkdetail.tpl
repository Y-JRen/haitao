<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_sn" size="20" value="{{$data.bill_sn}}" />
<input type="hidden" name="is_cancel" size="20" value="{{$data.is_cancel}}" />
<input type="hidden" name="is_check" id="is_check" size="20" value="{{$data.is_check}}" />
<div class="title">调整成本审核</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>成本编号</strong> * </td>
      <td>{{$data.bill_sn}}</td>

      <td><strong>制单日期</strong> * </td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong> * </td>
      <td>{{$data.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="5">&nbsp;{{$data.remark}}</td>
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
    <td>调整前成本</td>
    <td>调整后成本</td>
    </tr>
</thead>
<tbody>
{{foreach from=$details item=d key=key}}
<tr>
<td>{{$d.product_sn}}<input type="hidden" name="product_id[]" value="{{$d.product_id}}"></td>
<td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>)</td>
<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}<input type="hidden" name="batch_id[]" value="{{if $d.batch_id}}{{$d.batch_id}}{{else}}0{{/if}}"></td>
<td>{{if $d.p_status eq '0'}}上架{{else}}下架{{/if}}<input type="hidden" name="ostatus[]" value="{{$d.ostatus}}"></td>
<td>{{$d.cost}}<input type="hidden" name="cost[]" value="{{$d.cost}}"></td>
<td>{{$d.precost}}<input type="hidden" name="precost[]" value="{{$d.precost}}"></td>
</tr>
{{/foreach}}
</tbody>
</table>
</div>
<div class="submit">

{{if $data.lock_name eq $auth.admin_name && $data.stauts eq '0' && $flag neq '1'}}
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