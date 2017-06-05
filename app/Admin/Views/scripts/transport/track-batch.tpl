<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm1" id="myForm1">
<div class="title">运输单跟踪批量维护</div>
<div class="content">
      <input type="button" onclick="openDiv('{{url param.controller=transport param.action=sel param.type=track}}','ajax','手工添加运输单',750,400);" value="手工添加运输单">
      <input type="button" onclick="openDiv('{{url param.controller=transport param.action=sel-track-batch}}','ajax','批量添加运输单',750,400);" value="批量添加运输单">
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>删除</td>
        <td>物流公司</td>
        <td>单据编号</td>
        <td>单据类型</td>
        <td>运单号</td>
        <td>配送状态</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table>
</div>
<div class="submit">
{{foreach from=$logisticStatus item=item key=key}}
{{if $key>1 && $key<4}}
<input type="button" name="dosubmit{{$key}}" value="{{$item}}" onclick="if(confirm('确认维护成[{{$item}}]吗？')){ajax_submit($('myForm1'),'{{url param.logistic_status=$key}}');}"/>
 {{/if}}
{{/foreach}}
</div>
</div>
</form>
<script>

function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	
	for (i = 1; i < el.length; i++)
	{
		if (el[i].checked)
		{
			var id = el[i].value;
			var str = $('pinfo' + id).value;
			var pinfo = JSON.decode(str);
			var obj = $('sid' + id);
			if (obj)
			{
				continue;
			}
			else
			{
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
    for (var j = 0;j <= 5; j++)
	{
	  	 tr.insertCell(j);
	}
	tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.tid +')"><input type="hidden" name="ids[]" value="'+ pinfo.tid +'" >';
	tr.cells[1].innerHTML = pinfo.logistic_name;
	tr.cells[2].innerHTML = pinfo.bill_no; 
	tr.cells[3].innerHTML = pinfo.bill_type; 
	tr.cells[4].innerHTML = pinfo.logistic_no; 
	tr.cells[5].innerHTML = pinfo.logistic_status;
	obj.appendChild(tr);
}

function removeRow(id)
{
	$('sid' + id).destroy();
}

</script>