<p class="title-hot border2">人气商品</p>
{{foreach from=$renqi item=v}}

   <div class="hot-pro">
	<p class="img border1"><a href="/goods-{{$v.goods_id}}.html"><img src="{{$imgBaseUr}}/{{$v.goods_img|replace:'.':'_180_180.'}}"></a><p>
	<p class="text"><a href="/goods-{{$v.goods_id}}.html">{{$v.goods_name}}</a></p>
	<p class="text"><span class="span1">品牌：{{$v.brand_name}}</span><span class="span3" style="margin-right:3px;"><img src="{{$imgBaseUr}}/{{$v.region_imgurl}}" width=30 height=20></span><span class="span2">来自{{$v.region_name}}</span></p>
</div>
{{/foreach}}