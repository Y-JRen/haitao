<div class="title">归属地管理</div>
<form name="searchForm" id="searchForm" action="/admin/brand/brand-region" method="get">
<div class="search">
归属地名称：<input type="text" name="region_name" size="20" maxLength="50" value="{{$param.region_name}}"/>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=region-edit}}')">添加归属地</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="40">ID</td>
            <td>归属地名称</td>
			<td>图片</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$region item=v}}
        <tr id="ajax_list{{$data.brand_id}}">
            <td>{{$v.region_id}}</td>
            <td>{{$v.region_name}}</td>
            <td><img src='{{$imgBaseUr}}/{{$v.region_imgurl}}' width=30  height=20 /></td>
	        <td>
				<a href="javascript:fGo()" onclick="G('{{url param.action=region-edit param.id=$v.region_id}}')">编辑</a>
	        </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</di>

