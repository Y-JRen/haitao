<input type="button" onclick="openDiv('{{url param.action=sel param.close_type=sel}}','ajax','查询商品',750,450,true,'sel');" value="查询{{if $type}}组合{{/if}}添加商品">
<form name="myForm" id="myForm" action="{{url param.action=link}}" method="post" target="ifrmSubmit"/>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
	<tr>
	<td>删除</td>
	<td>商品编码</td>
	<td>商品名称</td>
	<td>状态</td>
	</tr>
</thead>
<tbody id="list">
{{foreach from=$links item=d}}
<tr id="ajax_list_link{{$d.link_id}}">
	<td id="sid{{$d.goods_link_id}}"><input type="button" onclick="reallydelete('{{url param.action=deletelink}}','{{$d.link_id}}','','ajax_list_link')" value="删除"></td>
	<td id="selected_goods{{$d.goods_link_id}}">{{$d.goods_sn}}</td>
	<td>{{$d.goods_name}}</td>
	<td>{{$d.goods_status}}</td>
</tr>
{{/foreach}}
</tbody>
</table>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++)
	{
		if (el[i].checked)
		{
			var goods_id = el[i].value;
			var str = $('ginfo' + goods_id).value;
			var ginfo = JSON.decode(str);
			if ($('sid' + goods_id))
			{
				continue;
			}
			else
			{
			    var tr = obj.insertRow(0);
			    tr.id = 'sid' + goods_id;
			    for (var j = 0;j <= 3; j++)
				{
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ goods_id +')"><input type="hidden" name="goods_id[]" value="'+goods_id+'" >';
				tr.cells[1].innerHTML = ginfo.goods_sn;
				tr.cells[2].innerHTML = ginfo.goods_name; 
				tr.cells[3].innerHTML = ginfo.goods_status; 
				obj.appendChild(tr);
			}
		}
	}
}

function removeRow(id)
{
	$('sid' + id).destroy();
}
</script>
