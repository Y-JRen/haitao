<form name="myForm1" id="myForm1">
<div class="title">在线支付订单货款结算</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="20%">支付方式：<select name="pay_type" id="pay_type" onchange="changePayType()">
            {{foreach from=$payment_list item=payment }}
              {{if $payment.pay_type ne 'external' && $payment.pay_type ne 'cod'}}
              <option value="{{$payment.pay_type}}" {{if $param.pay_type eq $payment.pay_type}}selected{{/if}}>  {{$payment.name}} </option>
              {{/if}}
            {{/foreach}}
            <option value="bank" {{if $param.pay_type eq 'bank'}}selected{{/if}}>银行打款</option>
            <option value="cash" {{if $param.pay_type eq 'cash'}}selected{{/if}}>现金支付</option>
		</select>
		<select name="sub_pay_type" id="sub_pay_type" style="display:none">
		  <option value="jiankang">1健康</option>
		  <option value="call">呼叫中心</option>
		  <option value="employee">国药内购</option>
		</select>
      </td>
      <td width="32%">
      <input type="button" onclick="openDiv('{{url param.controller=order param.action=sel-order}}/pay_type/'+$('pay_type').value+'/sub_pay_type/'+$('sub_pay_type').value,'ajax','手工添加订单',780,400);" value="手工添加订单">
      <input type="button" onclick="openDiv('{{url param.controller=order param.action=sel-order-batch}}/pay_type/'+$('pay_type').value+'/sub_pay_type/'+$('sub_pay_type').value,'ajax','批量添加订单',780,400);" value="批量添加订单">
      </td>
      <td width="20%"></td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>删除</td>
        <td>支付方式</td>
        <td>单据编号</td>
        <td>结算金额</td>
        <td>佣金</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table>
<div style="text-align:right;padding:10px 20px"><strong>应返款合计：</strong><span id="order_back_amount">0</span>元</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>实际返款金额</strong></td>
      <td colspan="3"><input type="text" name="real_amount" id="real_amount" size="10" value="0" onchange="adjust(this.value)"/></td>
    </tr>
    <tr>
      <td><strong>调整金额</strong></td>
      <td colspan="3"><input type="text" name="adjust_amount" id="adjust_amount" size="10" value="0" readonly/></td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="3"><textarea name="adjust_remark" style="width:500px" rows="6"></textarea></td>
    </tr>
</tbody>
</table>
</div>
<input type="button" name="dosubmit1" id="dosubmit1" value="确认结算" onclick="dosubmit()"/>
</div>
</form>
<script>
function dosubmit()
{
	if(confirm('确认结算吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed()
{
	$('dosubmit1').value = '确认结算';
	$('dosubmit1').disabled = false;
}

function adjust(num)
{
     var r = parseFloat($('order_back_amount').innerHTML)- parseFloat(num);
     $('adjust_amount').value = r.toFixed(2);
}

function addRow()
{
	var el = $('ajax_search_order').getElements('input[type=checkbox]');
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			var id = el[i].value;
			var str = $('oinfo' + id).value;
			var oinfo = JSON.decode(str);
			var obj = $('sid' + id);
			if (obj) {
				continue;
			}
			else {
			    insertRow(oinfo);
			}
		}
	}
}

function insertRow(oinfo)
{
	var s1 = parseFloat($('order_back_amount').innerHTML) + parseFloat(oinfo.price_payed);
	$('order_back_amount').innerHTML = s1.toFixed(2);
	
	var obj = $('list');
	var tr = obj.insertRow(0);
    tr.id = 'sid' + oinfo.order_batch_id;
    for (var j = 0; j <= 4; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ oinfo.order_batch_id +')"><input type="hidden" name="ids[]" value="'+ oinfo.order_batch_id +'" ><input type="hidden" name="order_ids[]" value="'+ oinfo.order_id +'" >';
	tr.cells[1].innerHTML = oinfo.pay_name;
	tr.cells[2].innerHTML = oinfo.batch_sn;
	if (oinfo.commission == null) {
	    oinfo.commission = 0;
	}
	tr.cells[3].innerHTML = '<span id="new_amount_' + oinfo.order_batch_id + '">' + oinfo.price_payed + '</span><input type="hidden" id="amount_' + oinfo.order_batch_id + '" value="' + (parseFloat(oinfo.price_payed) + parseFloat(oinfo.commission)) + '">';
	tr.cells[4].innerHTML = '<input type="text" name="commission[]" value="' + oinfo.commission + '" size="2" onblur="changeCommission(' + oinfo.order_batch_id + ', this.value)">';
	obj.appendChild(tr);
}

function removeRow(id)
{
	$('sid' + id).destroy();
}

function changeCommission(order_batch_id, commission)
{
    if (commission == null || commission == '') {
        commission = 0;
    }
    var old_value = parseFloat($('new_amount_' + order_batch_id).innerHTML);
    $('new_amount_' + order_batch_id).innerHTML = parseFloat($('amount_' + order_batch_id).value) - parseFloat(commission);
    var s1 = parseFloat($('order_back_amount').innerHTML) - old_value + parseFloat($('new_amount_' + order_batch_id).innerHTML);
	$('order_back_amount').innerHTML = s1.toFixed(2);
}

function changePayType()
{
    if ($('pay_type').value == 'alipay') {
        $('sub_pay_type').style.display = '';
    }
    else {
        $('sub_pay_type').style.display = 'none';
    }
}

changePayType();
</script>