{{if !$param.job}}
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
品牌名称：<input type="text" name="brand_name" size="15" maxLength="50" value="{{$param._name}}"/>
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search_goods')"/>
<input type="reset" name="reset" value="清除">
</form>
<br>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('{{$param.close_type}}');" type="button" value="添加并关闭"></p>
{{/if}}
<div id="ajax_search_goods">
{{if !empty($datas)}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td width="30"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('source_select'),'ids',this)"/></td>
         <td>品牌ID</td>
        <td>品牌LOGO</td>
        <td>品牌名称</td>
		<td>品牌别名</td>
    </tr>
</thead>
<tbody>
{{foreach from=$datas item=data}}
<tr id="ajax_list{{$data.goods_id}}">
    <td><input type="checkbox" name="ids[]" value="{{$data.goods_id}}"/>
    <input type="hidden" id="ginfo{{$data.goods_id}}" value='{{$data.ginfo}}'>
    </td>
    <td>{{$data.goods_id}}</td>
    <td> <img src="/{{$data.goods_sn}}" width="60px"/></td>
    <td>{{$data.goods_name}}</td>
    <td>{{$data.goods_status}}</td>
</tr>
{{/foreach}}
</tbody>
</table>
<div class="page_nav">{{$pageNav}}</div>
{{/if}}
{{if !$param.job}}
</div>
<br>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('{{$param.close_type}}');" type="button" value="添加并关闭"></p>
{{/if}}
</div>