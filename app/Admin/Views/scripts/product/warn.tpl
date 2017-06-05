<div class="search">
<form name="searchForm" id="searchForm" action="/admin/product/warn">
	<select name="cat_id"><option value="">请选择</option>
	{{foreach from=$catSelect item=data}}
	    <option value="{{$data.cat_id}}" {{if $data.cat_id==$param.cat_id}}selected{{/if}}>{{section name="i" loop=$data.step-1}}│{{/section}}├{{$data.cat_name}}</option>
	{{/foreach}}
	</select>
	商品上下架：<select name="onsale"><option value="">请选择</option><option value="0" {{if $param.onsale eq '0'}}selected{{/if}}>上架</option><option value="1" {{if $param.onsale eq '1'}}selected{{/if}}>下架</option></select>
	商品编码：<input type="text" name="product_sn" size="12" maxLength="50" value="{{$param.product_sn|default:''}}" class="show_tips" title="输入帮助：<br/> keyword 精确匹配，速度快；<br/>  keyword% 右模糊匹配，速度快； <br/>---------------<br/>%keyword : 左模糊匹配，速度很慢；<br/>%keyword%  最大模糊匹配，速度很慢。"/>
	商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name|default:''}}" class="show_tips" title="支持模糊搜索"/>
    
	预警
	<select name="warn">
	<option value="1" {{if $param.warn eq '1'}}selected{{/if}}>预警</option>
	<option value="2" {{if $param.warn eq '2'}}selected{{/if}}>未预警</option>
	</select>
    
	可用库存：
	<select name="real_number_bi">
	<option value="more" {{if $param.real_number_bi eq 'more'}}selected{{/if}}>大于</option>
	<option value="less" {{if $param.real_number_bi eq 'less'}}selected{{/if}}>小于</option>
	</select>
	<input type="text" name="real_number" size="10" value="{{$param.real_number}}"/>
	<input type="submit" name="dosearch" value="查询"/>
</form>
</div>
<form name="myForm" id="myForm">
<div class="title">物流管理 -&gt; 基础数据维护 -&gt; 库存预警管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品编码</th>
            <th class="nosort">商品名称</th>
            <th class="nosort">商品规格</th>
            <th class="nosort">上架状态</th>
            <th>真实库存</th>
            <th>库存预警数字</th>
            <th>库存预警状态</th>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.product_id}}">
        <td>{{$data.product_id}}</td>
        <td>{{$data.product_sn}}</td>
        <td>{{$data.goods_name}}</td>
         <td>{{$data.goods_style}}</td>
         <td>{{if $data.onsale eq 1}}<font color="red">下架</font> {{else}} 上架{{/if}}</td>
        <td>{{$data.real_number}}</td>
        <td><input type="text" name="update" size="5" value="{{$data.warn_number}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.product_id}},'warn_number',this.value)"></td>
        <td>{{$data.status}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>