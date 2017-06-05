<form name="myForm" id="myForm" action="{{url param.action=tag}}" method="post" target="ifrmSubmit"/>
<div class="title">商品标签管理 -&gt; {{$data.title}}</div>
<div class="content">
    <div>
		{{if $type eq brand}}
		 <input type="button" onclick="openDiv('/admin/brand/sel/','ajax','查询品牌',750,350,true,'sel');" value="查询添加品牌">
		{{elseif $type eq groupgoods}}
		 <input type="button" onclick="openDiv('/admin/goods/sel/type/2/shop_sale/1','ajax','查询组合商品',750,350,true,'sel');" value="查询添加组合商品">
		{{else}}
		  <input type="button" onclick="openDiv('/admin/goods/sel/','ajax','查询商品',750,350,true,'sel');" value="查询添加商品">
		{{/if }}
		排序：<input type="text" id="m_id" size="5">前置于<input type="text" id="to_id" size="5">
		<input type="button" onclick="sort();" value="排序">
    </div>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
	<tr>
	<td width="80">删除</td>
	<td>ID</td>
   	{{if $type eq brand}}
	<td>LOGO</td>
	<td>品牌名称</td>
	<td>品牌别名</td>
	{{else}} 
	<td>商品编码</td>
	<td>商品名称</td>
	<td>状态</td>
	{{/if}}
	</tr>
</thead>
<tbody id="list">
{{foreach from=$tags item=d}}
<tr id="sid{{$d.goods_id}}">
	<td><input type="button" onclick="removeRow('{{$d.goods_id}}')" value="删除"><input type="hidden" name="goods_id[]" value="{{$d.goods_id}}" ></td>
	<td>{{$d.goods_id}}</td>
		{{if $type eq brand}} <td> <img src="{{$imgBaseUrl}}/{{$d.goods_sn}}" width="50"></td> {{else}} 
	<td>{{$d.goods_sn}}</td>{{/if}}
	<td>{{$d.goods_name}}</td>
	<td>{{$d.goods_status}}</td>
</tr>
{{/foreach}}
</tbody>
</table>
</div>
<div class="submit">
该标签下共有商品{{$num}}件
<input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
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
			    for (var j = 0;j <= 5; j++)
				{
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ goods_id +')"><input type="hidden" name="goods_id[]" value="'+goods_id+'" >';
				tr.cells[1].innerHTML = ginfo.goods_id;
				tr.cells[2].innerHTML = ginfo.goods_sn;
				tr.cells[3].innerHTML = ginfo.goods_name; 
				tr.cells[4].innerHTML = ginfo.goods_status; 
				obj.appendChild(tr);
			}
		}
	}
}

function removeRow(id)
{
	$('sid' + id).destroy();
}

function sort()
{
	var m_id = $('m_id').value;
	var to_id = $('to_id').value;
	if (m_id == '' || to_id == '') return false;
	var html = $('sid' + m_id);
	$('sid' + m_id).injectBefore($('sid' + to_id));
}
</script>
