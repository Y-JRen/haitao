<form name="searchForm" id="searchForm" action="/admin/goods/link-list/">
<div class="search">
{{$catSelect}}
上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" {{if $param.onsale eq 'on'}}selected{{/if}}>上架</option><option value="off" {{if $param.onsale eq 'off'}}selected{{/if}}>下架</option></select>
名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/> 编码：<input type="text" name="goods_sn" size="20" maxLength="50" value="{{$param.goods_sn}}"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="title">商品管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>商品编码</td>
            <td>商品名称</td>
            <td>市场价</td>
            <td>本店价</td>
            <td>状态</td>
            <td>操 作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.goods_id}}">
        <td>{{$data.goods_id}}</td>
        <td>{{$data.goods_sn}}</td>
        <td>{{$data.goods_name}}</td>
        <td>{{$data.market_price}}</td>
        <td>{{$data.price}}</td>
        <td>{{$data.goods_status}}</td>
        <td>
			<a href="javascript:fGo()" onclick="openDiv('{{url param.action=link param.id=$data.goods_id }}','ajax','查看{{$data.goods_name}}关联商品',750,400)">编辑关联商品</a>
        </td>
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
