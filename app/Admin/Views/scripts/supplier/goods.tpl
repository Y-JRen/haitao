<div class="title">供货商产品管理 -&gt; {{$data.supplier_name}}</div>
<form name="searchForm" id="searchForm" action="{{url}}" method="get" />
<div>
    <input type="button" onclick="openDiv('/admin/product/sel','ajax','查询产品',750,450,true,'sel');" value="查询添加产品">
    <!--
    产品名称：<input type="text" name="product_name" id="product_name" size="20" value="{{$params.product_name}}">
    产品编码：<input type="text" name="product_sn" id="product_sn" size="5" value="{{$params.product_sn}}">
    <input type="submit" name="search" value="查询">
    -->
</div>
</form>
<form name="myForm" id="myForm" action="{{url}}" method="post"/>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
	<tr>
	<td width="80">操作</td>
	<td>ID</td>
	<td>产品编码</td>
	<td>产品名称</td>
    <td>产品规格</td>
	<td>单位</td>
	</tr>
</thead>
<tbody id="list">
{{foreach from=$datas item=d}}
<tr id="sid{{$d.product_id}}">
	<td><input type="button" onclick="removeRow('{{$d.product_id}}')" value="删除"><input type="hidden" name="product_id[]" value="{{$d.product_id}}" ></td>
	<td>{{$d.product_id}}</td>
	<td>{{$d.product_sn}}</td>
	<td>{{$d.product_name}}</td>
    <td>{{$d.goods_style}}</td>
	<td>{{$d.goods_units}}</td>
</tr>
{{/foreach}}
</tbody>
</table>
</div>
<div class="submit">
{{if $params.product_name eq '' && $params.product_sn eq ''}}
<input type="submit" name="dosubmit" id="dosubmit" value="保存" />
{{else}}
<input type="button" name="reset" value="重置" onclick="$('product_name').value = '';$('product_sn').value = '';$('searchForm').submit()"/>
{{/if}}
</div>
</form>
<script language="JavaScript">
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++)
	{
		if (el[i].checked) {
			var product_id = el[i].value;
			var str = $('pinfo' + product_id).value;
			var pinfo = JSON.decode(str);
			if ($('sid' + product_id)) {
				continue;
			}
			else {
			    var tr = obj.insertRow(0);
			    tr.id = 'sid' + product_id;
			    for (var j = 0;j <= 6; j++) {
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ product_id +')"><input type="hidden" name="product_id[]" value="'+product_id+'" >';
				tr.cells[1].innerHTML = pinfo.product_id;
				tr.cells[2].innerHTML = pinfo.product_sn;
				tr.cells[3].innerHTML = pinfo.product_name; 
				tr.cells[4].innerHTML = pinfo.goods_style; 
				tr.cells[5].innerHTML = pinfo.goods_units; 
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
