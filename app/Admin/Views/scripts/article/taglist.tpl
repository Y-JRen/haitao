<div class="title">文章标签管理   [ <a href="javascript:fGo()" onclick="G('{{url param.action=add-tag}}')">添加新标签</a> ]</div>
<form name="searchForm" id="searchForm" action="/admin/article/taglist">
<div class="search">
标题：<input type="text" name="title" size="10" maxLength="50" value="{{$param.title}}"/>
标签名：<input type="text" name="tag" size="10" maxLength="50" value="{{$param.tag}}"/>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>标签ID</td>
            <td>标题</td>
            <td>标签名</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$taglist item=data}}
    <tr id="ajax_list{{$data.goods_id}}">
        <td>{{$data.tag_id}}</td>
        <td>  
		<input type="text" name="update" size="30" value="{{$data.title}}"  onchange="ajax_update('{{url param.action=ajaxupdatetag}}',{{$data.tag_id}},'title',this.value)">
		 </td>
        <td>
		<input type="text" name="update" size="30" value="{{$data.tag}}"  onchange="ajax_update('{{url param.action=ajaxupdatetag}}',{{$data.tag_id}},'tag',this.value)">
		</td>
        <td>
		 <a href="javascript:fGo()" onclick="G('/admin/article/tag/id/{{$data.tag_id}}')">编辑添加文章</a> 
        </td>
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
