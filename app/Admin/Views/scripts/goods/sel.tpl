{{if !$param.job}}
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
{{if !$param.type}}
排序：<select name="sort">
  <option value=""  >选择排序</option>
  <option value="2"  {{if $param.sort eq '2'}}selected{{/if}} >畅销</option>
  <option value="1" {{if $param.sort eq '1'}}selected{{/if}}>新品</option>
  <option value="3" {{if $param.sort eq '3'}}selected{{/if}}>价格低到高</option>
  <option value="4" {{if $param.sort eq '4'}}selected{{/if}} >价格高到低</option>
</select>
<br>
{{/if}}
商品编码：<input type="text" name="goods_sn" size="10" maxLength="10" value="{{$param.goods_sn}}"/>
商品名称：<input type="text" name="goods_name" size="15" maxLength="50" value="{{$param.goods_name}}"/>
{{if !$param.type}}{{$catSelect}}价格区间：<input type="text" name="fromprice" size="5" maxLength="10" value="{{$param.fromprice}}"/> - <input type="text" name="toprice" size="5" maxLength="10" value="{{$param.toprice}}"/>{{/if}}
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{if $param.type eq '2'}}{{url param.job=group param.type=2}}{{else}}
{{url param.job=search}}{{/if}}','ajax_search_goods')"/>
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
         <td>商品ID</td>
         <td>商品编码</td>
        <td>商品名称</td>
        <td>现价</td>
        <td>状态</td>
		{{if !$param.type}}<td>销量</td>{{/if}}
		<td>库存</td>
    </tr>
</thead>
<tbody>
{{foreach from=$datas item=data}}
<tr id="ajax_list{{$data.goods_id}}">
    <td><input type="checkbox" name="ids[]" value="{{$data.goods_id}}"/>
    <input type="hidden" id="ginfo{{$data.goods_id}}" value='{{$data.ginfo}}'>
    </td>
    <td>{{$data.goods_id}}</td>
    <td>{{$data.goods_sn}}</td>
    <td>{{$data.goods_name}}(<font color="#FF3333">{{$data.goods_style}}</font>)</td>
    <td>{{$data.price}}</td>
    <td>{{$data.goods_status}}</td>
	{{if !$param.type}}<td>{{$data.sort_sale}}</td>{{/if}}
	<td>{{$data.store}}</td>
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