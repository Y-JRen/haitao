<script type="text/javascript">
loadCss('/images/calendar/calendar.css');
loadJs("/scripts/calendar.js",MyCalendar);
function MyCalendar(){
    new Calendar({fromdate: 'Y-m-d'});
    new Calendar({todate: 'Y-m-d'});
}
</script>
<form name="myForm1" id="myForm1">
<div class="title">运费单结算</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="20%">物流公司：<select name="logistic_code" id="logistic_code">
			{{html_options options=$logisticList}}
		</select>
</td>
      <td>
      <input type="button" onclick="openDiv('{{url param.controller=transport param.action=sel}}/type/clear/logistic_code/'+$('logistic_code').value,'ajax','手工添加运输单',750,400);" value="手工添加运输单">
      <input type="button" onclick="openDiv('{{url param.controller=transport param.action=sel-clear-batch}}/logistic_code/'+$('logistic_code').value,'ajax','批量添加运输单',780,400);" value="批量添加运输单">
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
    </tr>
</thead>
<tbody id="list"></tbody>
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
	var obj = $('list');
	var tr = obj.insertRow(0);
    tr.id = 'sid' + pinfo.tid;
    for (var j = 0;j <= 6; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.tid +')"><input type="hidden" name="ids[]" value="'+ pinfo.tid +'" >';
	tr.cells[1].innerHTML = pinfo.logistic_name;
	tr.cells[2].innerHTML = pinfo.bill_no;
	tr.cells[3].innerHTML = pinfo.bill_type; 
	tr.cells[4].innerHTML = pinfo.logistic_no; 
	tr.cells[5].innerHTML = pinfo.logistic_status;
	if (pinfo.logistic_price == null) {
	    pinfo.logistic_price = 0;
	}
	tr.cells[6].innerHTML = '<input type="text" size="3" name="logistic_price[' + pinfo.tid + ']" value="' + pinfo.logistic_price + '">';
	obj.appendChild(tr);
}

function removeRow(id)
{
	$('sid' + id).destroy();
}

</script>