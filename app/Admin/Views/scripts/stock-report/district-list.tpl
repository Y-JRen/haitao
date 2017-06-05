<div class="search">
  <form id="searchForm" method="get">
  所属仓库：
  <select name="area">
    {{foreach from=$areas item=item key=key}}
    {{if $param.area eq $key}}
    <option value="{{$key}}" {{if $param.area eq $key}}selected{{/if}}>{{$item}}</option>
    {{/if}}
    {{/foreach}}
  </select>
  库区状态：
	<select name="status">
		<option value="">请选择...</option>
		<option value="0" {{if $param.status eq '0'}}selected{{/if}}>正常</option>
		<option value="1" {{if $param.status eq '1'}}selected{{/if}}>冻结</option>
	</select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">库区列表 [<a href="/admin/stock-report/add-district">添加库区</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>库区名称</td>
				<td>库区编号</td>
				<td>所属仓库</td>
    			<td>状态</td>
				<td>添加时间</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$data.district_name}}</td>
		    <td valign="top">{{$data.district_no}}</td>
			<td valign="top">{{$areas[$data.area]}}</td>
			<td>
			  {{if $data.status eq 0}}启用
			  {{else}}冻结
			  {{/if}}
			</td>
			<td valign="top">{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="G('{{url param.action=edit-district param.id=$data.district_id}}')">编辑</a>
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>