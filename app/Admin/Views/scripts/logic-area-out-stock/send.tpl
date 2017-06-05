<form name="myForm1" id="myForm1">
<input type="hidden" name="outstock_id" size="20" value="{{$data.outstock_id}}" />
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<input type="hidden" name="logic_area" value="{{$logic_area}}">
<input type="hidden" name="bill_type" value="{{$data.bill_type}}">
<input type="hidden" name="item_no" value="{{$data.item_no}}">
<input type="hidden" name="bill_name" value="{{$billType[$data.bill_type]}}">
<input type="hidden" name="logistic_code" value="{{$data.transport.logistic_code}}" />
<input type="hidden" name="weight" value="{{$data.transport.weight}}" />
<input type="hidden" name="recipient" value="{{$data.recipient}}" />
<input type="hidden" name="barcode" id="barcode">
<input type="hidden" name="remark" id="remark"  value="{{$data.remark}}">
<input type="hidden" name="toback" id="toback" value="" />

<div class="title">出库单发货</div>
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
      <td><strong>制单日期</strong></td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong></td>
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
<div style="text-align:right;padding:10px 20px"><strong>应发合计：</strong>{{$data.total_number}}<input type="hidden" name="total_number" id="total_number" value="{{$data.total_number}}"></div>

{{if $data.transport.logistic_name and ($data.bill_type==1  or $data.bill_type==10)}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="table">
<tbody>
    <tr>
      <td width="10%"><strong>物流公司</strong></td>
      <td>{{$data.transport.logistic_name}}
      </td>
    </tr>
    <tr>
      <td width="10%"><strong>付款方式</strong></td>
      <td>{{if $data.transport.is_cod}}货到付款{{else}}非货到付款{{/if}}
      </td>
    </tr>
    <tr>
      <td width="10%"><strong>称重</strong></td>
      <td><input type="text" name="deliver_weigh" id="deliver_weigh" size="30" maxlength="30" value="{{$data.deliver_weigh}}" /> *KG</td>
    </tr>
    <tr>
      <td width="10%"><strong>运单号</strong></td>
      <td><input type="text" name="logistic_no" id="logistic_no" size="40" maxlength="50" value="{{$data.logistic_no}}" /></td>
    </tr>
</tbody>
</table>
{{/if}}

</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name}}
<input type="button" name="dosubmit1" id="dosubmit1" value="发货" onclick="dosubmit()"/>
{{if $data.bill_type eq 1}}
<input type="button" name="doback" id="doback" value="返回打印" onclick="back()"/>
{{/if}}
{{/if}}
</div>
</form>
<script language="JavaScript">
function dosubmit()
{
	{{if $data.bill_type eq 1}}
		var no = $('logistic_no').value.trim();
		var code = '{{$data.transport.logistic_code}}';
		var len = no.length;
		var cod = '{{$data.transport.is_cod}}';
		var result = false;
		if(no==''){alert('请填写运单号!');return false;}
		if (code=='zjs'){
			 if (len==10){
				 if ((cod==0 && no.substr(0, 1) != '6') || (cod==1 && no.substr(0, 1) == '6')) {result = true;}
			 }
		}else if (code=='sf'){
			 if (len==12){
				 if (no.substr(0, 3) == '513') {result = true;}
			 }
		}else if (code=='st'){
			 if (len==12){
				 if (no.substr(0, 3) != '513') {result = true;}
			 }
			 if (no == '自提' || no == 'zt') {result = true;}
		}else if (code=='ems'){
			 if (len==13){
				 if ((cod==0 && no.substr(0, 2) != 'EC' && no.substr(0, 4) != '316A') || (cod==1 && no.substr(0, 2) == 'EC')) {result = true;}
			 }
		}else if (code=='jldt'){
			 if (len==13){
				 if (no.substr(0, 4) == '316A') {result = true;}
			 }
		}
		/*
		if (result == false){
			alert('运单号码校验错误,请检查使用的快递面单是否正确!');
			$('logistic_no').value='';
			$('logistic_no').focus();
			return false;
		}
		*/
	{{/if}}
	
	if(confirm('确认发货吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function back()
{
    if(confirm('确认返回吗？')){
        $('toback').value = 1;
        ajax_submit($('myForm1'),'{{url}}');
    }
}

function failed()
{
	$('dosubmit1').value = '发货';
	$('dosubmit1').disabled = false;
}

</script>