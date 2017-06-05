<form name="searchForm" id="searchForm" action="/admin/goods/img-list">
<div class="search">

{{$catSelect}}
上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" {{if $param.onsale eq 'on'}}selected{{/if}}>上架</option><option value="off" {{if $param.onsale eq 'off'}}selected{{/if}}>下架</option></select>
<input type="checkbox" name="goods_img" value="1" {{if $param.goods_img}}checked{{/if}}> 标图未上传
名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/>  编码：<input type="text" name="goods_sn" size="20" maxLength="50" value="{{$param.goods_sn}}"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="title">商品管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>商品主图</td>
            <td>ID</td>
            <td>商品编码</td>
			<td>商品分类</td>
			<td width="280px">商品名称</td>
            <td>市场价</td>
            <td>本店价</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.goods_id}}">
        <td>{{if $data.goods_img}}<img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_60_60.'}}" width="50">{{else}}<font color="red" size="3">未上传</font>{{/if}}</td>
        <td>{{$data.goods_id}}</td>
        <td>{{$data.goods_sn}}</td>
		<td>{{$data.cat_name}}</td>
        <td>{{$data.goods_name}}</td>
        <td>{{$data.market_price}}</td>
        <td >{{$data.price}}</td>
        <td>{{$data.goods_status}}</td>
        <td>
        <a href="javascript:fGo()" onclick="window.open('/goods-{{$data.goods_id}}.html')">查看 |  </a>
		
		<a href="javascript:fGo()" onclick="openDiv('{{url param.action=img}}/id/{{$data.goods_id}}/goods_sn/{{$data.goods_sn}}','ajax','查看{{$data.goods_name}}图片',750,400);">管理图片</a>
        </td>
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
