<form name="myForm" id="myForm" action="{{url param.action=tag}}" method="post" target="ifrmSubmit"/>
<div class="title">文章标签管理 -&gt; {{$data.title}}</div>
<div class="content">
    <div>
        <input type="button" onclick="openDiv('{{url param.action=sel}}','ajax','查询文章信息',750,450,true,'sel');" value="查询添加文章">
        排序：<input type="text" id="m_id" size="5">前置于<input type="text" id="to_id" size="5">
        <input type="button" onclick="sort();" value="排序">
    </div>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
	<tr>
	<td width="80">删除</td>
        <td>文章ID</td>
        <td>文章标题</td>
        <td>文章分类</td>
	</tr>
</thead>
<tbody id="list">
{{foreach from=$tags item=d}}
<tr id="sid{{$d.article_id}}">
	<td><input type="button" onclick="removeRow('{{$d.article_id}}')" value="删除"><input type="hidden" name="article_id[]" value="{{$d.article_id}}" ></td>
	<td>{{$d.article_id}}</td>
	<td>{{$d.title}}</td>
    <td>{{$d.cat_name}}</td>
</tr>
{{/foreach}}
</tbody>
</table>
</div>
<div class="submit">
该标签下共有{{$num}}篇文章
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
			var article_id = el[i].value;
			var str = $('ginfo' + article_id).value;
			var ginfo = JSON.decode(str);
			if ($('sid' + article_id))
			{
				continue;
			}
			else
			{
			    var tr = obj.insertRow(0);
			    tr.id = 'sid' + article_id;
			    for (var j = 0;j <= 3; j++)
				{
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ article_id +')"><input type="hidden" name="article_id[]" value="'+article_id+'" >';
				tr.cells[1].innerHTML = ginfo.article_id;
				tr.cells[2].innerHTML = ginfo.title;
				tr.cells[3].innerHTML = ginfo.cat_name;
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