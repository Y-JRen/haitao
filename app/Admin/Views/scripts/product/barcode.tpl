<form name="searchForm" id="searchForm">
<div class="search">
产品编码：<input type="text" name="product_sn" size="15" maxLength="50" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/>
国际码：<input type="text" name="ean_barcode" size="25" maxLength="50" value="{{$param.ean_barcode}}"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 产品列表       </div>
<div class="content">
<div class="sub_title">
  [ <a href="javascript:fGo()" onclick="G('/admin/product/barcode/type/1');">查看所有产品</a> ]  [ <a href="javascript:fGo()" onclick="G('/admin/product/barcode/type/2');">查看有库存产品</a> ] 
</div>
   <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
			<td>产品分类</td>
			<td>产品名称（规格）</td>
			<td>库存</td>
            <td>国际码</td>
            <td>长(cm)</td>
		    <td>宽(cm)</td>
			<td>高(cm)</td>
           <td>重量(kg)</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.product_id}}</td>
        <td>{{$data.product_sn}}</td>
		<td>{{$data.cat_name}}</td>
		<td>{{$data.product_name|stripslashes}} <font color="#FF0000">({{$data.goods_style}})</font></td>
		<td>{{$data.real_number|default:0}}</font></td>
        <td>
		<input type="text" name="update" size="16" value="{{$data.ean_barcode}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.product_id}},'ean_barcode',this.value)">
		</td>
        <td>
		<input type="text" name="update" size="4" value="{{$data.p_length}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.product_id}},'p_length',this.value)">
		</td>
		<td>
		<input type="text" name="update" size="4" value="{{$data.p_width}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.product_id}},'p_width',this.value)">

		</td>
		<td>
		<input type="text" name="update" size="4" value="{{$data.p_height}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.product_id}},'p_height',this.value)">
		
		</td>
        <td>
		<input type="text" name="update" size="4" value="{{$data.p_weight}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.product_id}},'p_weight',this.value)">
		</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
