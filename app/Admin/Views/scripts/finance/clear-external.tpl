<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm1" id="myForm1">
<div class="title">外部支付订单货款结算</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="30%">
        当前店铺：
          <select name="shop_id" id="shop_id">
            {{foreach from=$shopDatas item=data}}
              {{if ($data.shop_type ne 'tuan' || $data.shop_id eq 11) && $data.shop_type ne 'jiankang' && $data.shop_type ne 'credit'}}
              <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
              {{/if}}
            {{/foreach}}
          </select>
      </td>
      <td>
      <input type="button" onclick="openDiv('{{url param.controller=order param.action=sel-order-external}}/shop_id/'+$('shop_id').value,'ajax','手工添加订单',780,400);" value="手工添加订单">
      <input type="button" onclick="openDiv('{{url param.controller=order param.action=sel-order-external-batch}}/shop_id/'+$('shop_id').value,'ajax','批量添加订单',880,400);" value="批量添加订单">
      </td>
      <td></td>
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
      <td colspan="3"><input type="text" name="real_amount" id="real_amount" size="10" value="0" onblur="adjust(this.value)"/></td>
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
	if ($('real_amount').value <= 0) {
	    var str = '实际返款金额为0，确认结算吗？';
	}
	else {
	    var str = '确认结算吗？';
	}
	if(confirm(str)){
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
	var s1 = parseFloat($('order_back_amount').innerHTML) + parseFloat(oinfo.amount);
	$('order_back_amount').innerHTML = s1.toFixed(2);
	
	var obj = $('list');
	var tr = obj.insertRow(0);
    tr.id = 'sid' + oinfo.order_id;
    for (var j = 0;j <= 4; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ oinfo.order_id +')"><input type="hidden" name="ids[]" value="'+ oinfo.order_id +'" >';
	tr.cells[1].innerHTML = oinfo.pay_name;
	tr.cells[2].innerHTML = oinfo.order_sn + '<input type="hidden" name="sns[]" value="' + oinfo.order_sn + '">'; 
	if (oinfo.commission == null) {
	    oinfo.commission = 0;
	}
	tr.cells[4].innerHTML = '<input type="text" name="commission[]" value="' + oinfo.commission + '" size="2" onblur="changeCommission(' + oinfo.order_id + ', this.value)">';
	tr.cells[3].innerHTML = '<span id="new_amount_' + oinfo.order_id + '">' + oinfo.amount + '</span><input type="hidden" id="amount_' + oinfo.order_id + '" value="' + (parseFloat(oinfo.amount) + parseFloat(oinfo.commission)) + '">';
	obj.appendChild(tr);
	
	$('real_amount').focus();
}
function removeRow(id)
{
	var s1 = parseFloat($('order_back_amount').innerHTML) - parseFloat($('new_amount_' + id).innerHTML);
	$('order_back_amount').innerHTML = s1.toFixed(2);
	$('sid' + id).destroy();
}

function changeCommission(order_id, commission)
{
    var old_value = parseFloat($('new_amount_' + order_id).innerHTML);
    $('new_amount_' + order_id).innerHTML = parseFloat($('amount_' + order_id).value) - parseFloat(commission);
    var s1 = parseFloat($('order_back_amount').innerHTML) - old_value + parseFloat($('new_amount_' + order_id).innerHTML);
	$('order_back_amount').innerHTML = s1.toFixed(2);
}
</script>