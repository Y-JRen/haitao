{{if !$param.job}}
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
		分类:
		<select name="cat_id">
			<option value="">请选择...</option>
			{{foreach from=$catTree key=cat_id item=item}}
			{{if $item.leaf}}
			<option value={{$cat_id}} style="padding-left:{{$item.step*20-20}}px" {{if $catID==$cat_id}}selected="selected"{{/if}}>
			{{$item.cat_name}}（{{$item.num}}）
			</option>
			{{else}}
			<optgroup label='{{$item.cat_name}}' style="padding-left:{{$item.step*20-20}}px"></optgroup>
			{{/if}}
			{{/foreach}}	
			文章标题：<input type="text" name="title" size="28" maxLength="50" value="{{$param.title}}"/>
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search_goods')"/>
<input type="reset" name="reset" value="清除">
</form>
<br>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('sel');" type="button" value="添加并关闭"></p>
{{/if}}
<div id="ajax_search_goods">
{{if !empty($datas)}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td width="30"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('source_select'),'ids',this)"/></td>
        <td>文章ID</td>
        <td>分类名称</td>
        <td>文章标题</td>
        <td>是否显示</td>
		<td>添加时间</td>
    </tr>
</thead>
<tbody>
{{foreach from=$datas item=data}}
<tr id="ajax_list{{$data.article_id}}">
    <td><input type="checkbox" name="ids[]" value="{{$data.article_id}}"/>
    <input type="hidden" id="ginfo{{$data.article_id}}" value='{{$data.ginfo}}'>
    </td>
    <td>{{$data.article_id}}</td>
    <td>{{$data.cat_name}}</td>
    <td>{{$data.title}}</td>
    <td>{{if $data.is_view eq 1}} 显示{{else}} 不显示 {{/if}}</td>
	<td>{{$data.add_time|date_format:'%Y-%m-%d'}}</td>
</tr>
{{/foreach}}
</tbody>
</table>
<div class="page_nav">{{$pageNav}}</div>
{{/if}}
{{if !$param.job}}
</div>
<br>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('sel');" type="button" value="添加并关闭"></p>
{{/if}}
</div>