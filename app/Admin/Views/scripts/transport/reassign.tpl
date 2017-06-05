<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_type" size="20" value="{{$data.bill_type}}" />
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<input type="hidden" name="logistic_status" size="20" value="{{$data.logistic_status}}" />
<div class="title">物流派单</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>{{$data.bill_no}}</td>
      <td width="12%"></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>制单日期</strong></td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong></td>
      <td>{{$data.admin_name}}</td>
      <td></td>
      <td></td>
    </tr>
      <td><strong>省份</strong></td>
      <td>{{$data.province}}</td>
      <td><strong>城市</strong></td>
      <td>{{$data.city}}</td>
      <td><strong>地区</strong></td>
      <td>{{$data.area}}</td>
    </tr>
    <tr>
      <td><strong>详细地址</strong></td>
      <td colspan="5">{{$data.address}}</td>
    </tr>
    <tr>
      <td><strong>配送方式</strong></td>
      <td>{{if $data.logistic_code neq 'ems'}}快递{{else}}EMS{{/if}}</td>
      <td><strong>订单金额</strong></td>
      <td>{{$data.amount}}</td>
      <td><strong>商品数量</strong></td>
      <td>{{$data.goods_number}}</td>
    </tr>
    <tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="5">&nbsp;{{$data.remark}}</td>
    </tr>
    <tr>
      <td><strong>重量</strong></td>
      <td>{{$data.weight}}</td>
      <td><strong>体积</strong></td>
      <td>{{$data.volume}}</td>
      <td><strong>件数</strong></td>
      <td>{{$data.number}}</td>
    </tr>
    <tr>
      <td><strong>原物流公司</strong></td>
      <td>{{$data.logistic_name}}</td>
      <td><strong>原运单号</strong></td>
      <td>{{$data.logistic_no}}</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>付款方式</strong></td>
      <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
      <td><strong>承运商</strong></td>
      <td colspan="3">
      <select name="logistic" id="logistic">
		<option value="">请选择</option>
		{{$logisticList}}
	  </select>
	  </td>
    </tr>
    {{if $data.logistic_no neq ''}}
    <tr>
      <td><strong>运输单号</strong></td>
      <td colspan="5">
      <input type="text" name="new_logistic_no" id="new_logistic_no" size="40" maxlength="50"/>
	  </td>
    </tr>
    {{/if}}
</tbody>
</table>

</div>

<div class="submit">
<input type="button" name="dosubmit1" id="dosubmit1" value="确认派单" onclick="dosubmit()"/>
</div>
</form>
<script>
function dosubmit()
{
	{{if $data.logistic_no neq ''}}
	if($('new_logistic_no').value==''){alert('请填写运单号');return false;}
	{{/if}}
	if($('logistic').value==''){alert('请选择承运商');return false;};
	if(confirm('确认重新派单吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed()
{
	$('dosubmit1').value = '确认派单';
	$('dosubmit1').disabled = false;
}

</script>