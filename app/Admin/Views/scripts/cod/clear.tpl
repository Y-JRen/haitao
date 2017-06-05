<script type="text/javascript">
loadCss('/images/calendar/calendar.css');
loadJs("/scripts/calendar.js",MyCalendar);
function MyCalendar(){
    new Calendar({fromdate: 'Y-m-d'});
    new Calendar({todate: 'Y-m-d'});
}
</script>
<form name="myForm1" id="myForm1">
<div class="title">代收货款结算</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="30%">
        物流公司：<select name="logistic_code" id="logistic_code">
          {{foreach from=$logisticList key=key item=data}}
            {{if $key eq 'sf' || $key eq 'ems'}}
            <option value="{{$key}}">{{$data}}</option>
            {{/if}}
          {{/foreach}}
		</select>
		<select name="sub_code" id="sub_code">
		  <option value="jiankang">1健康</option>
		  <option value="in_call">呼入</option>
		  <option value="out_call">呼出</option>
		  <option value="tq_call">咨询</option>
		  <option value="employee">国药内购</option>
		  <option value="dangdang">当当网</option>
		  <option value="baidu">百度微购</option>
		  <option value="other">其它</option>
		</select>
      </td>
      <td>
      <input type="button" onclick="openDiv('{{url param.controller=transport param.action=sel}}/type/clear-cod/logistic_code/'+$('logistic_code').value+'/sub_code/'+$('sub_code').value,'ajax','手工添加运输单',750,400);" value="手工添加运输单">
      <input type="button" onclick="openDiv('{{url param.controller=transport param.action=sel-batch}}/logistic_code/'+$('logistic_code').value+'/sub_code/'+$('sub_code').value,'ajax','批量添加运输单',780,400);" value="批量添加运输单">
      </td>
      <td width="20%"></td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>删除</td>
        <td>物流公司</td>
        <td>单据编号</td>
        <td>单据类型</td>
        <td>运单号</td>
        <td>配送状态</td>
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
	var el = $('source_select').getElements('input[type=checkbox]');
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			var id = el[i].value;
			var str = $('pinfo' + id).value;
			var pinfo = JSON.decode(str);
			var obj = $('sid' + id);
			if (obj) {
				continue;
			}
			else {
			    insertRow(pinfo);
			}
		}
	}
}

function insertRow(pinfo)
{
	var s1 = parseFloat($('order_back_amount').innerHTML) + parseFloat(pinfo.clear_amount);
	$('order_back_amount').innerHTML = s1.toFixed(2);
	
	var obj = $('list');
	var tr = obj.insertRow(0);
    tr.id = 'sid' + pinfo.tid;
    for (var j = 0;j <= 7; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.tid +')"><input type="hidden" name="ids[]" value="'+ pinfo.tid +'" >';
	tr.cells[1].innerHTML = pinfo.logistic_name;
	tr.cells[2].innerHTML = pinfo.bill_no; 
	tr.cells[3].innerHTML = pinfo.bill_type; 
	tr.cells[4].innerHTML = pinfo.logistic_no; 
	tr.cells[5].innerHTML = pinfo.logistic_status;
	if (pinfo.commission == null) {
	    pinfo.commission = 0;
	}
	tr.cells[6].innerHTML = '<span id="new_amount_' + pinfo.tid + '">' + pinfo.clear_amount + '</span><input type="hidden" id="amount_' + pinfo.tid + '" value="' + (parseFloat(pinfo.clear_amount) + parseFloat(pinfo.commission)) + '">';
	tr.cells[7].innerHTML = '<input type="text" name="commission[]" value="' + pinfo.commission + '" size="2" onblur="changeCommission(' + pinfo.tid + ', this.value)">';
	obj.appendChild(tr);
}

function removeRow(id)
{
	$('sid' + id).destroy();
}

function changeCommission(tid, commission)
{
    var old_value = parseFloat($('new_amount_' + tid).innerHTML);
    $('new_amount_' + tid).innerHTML = parseFloat($('amount_' + tid).value) - parseFloat(commission);
    var s1 = parseFloat($('order_back_amount').innerHTML) - old_value + parseFloat($('new_amount_' + tid).innerHTML);
	$('order_back_amount').innerHTML = s1.toFixed(2);
}

</script>