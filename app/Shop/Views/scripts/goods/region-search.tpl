
<div class="bread container">
	<ul>
		{{$crumbs}}
	</ul>
</div><!--breadcrumbs-->
<div class="catalog-Pro container">
	<div class="catalog-Pro-left">
		<dl class="catalog-Pro-nav border2">
		   <p class="catalog-Pro-title"><span class="catalog-Pro-titleSpan">全部分类:</span><span onclick="javascript:location.href='/gallery-0-0-0-0-1.html'">>></span></p>
		   {{foreach from=$cat_list item=v}}
		   <dt class="shensuo"><a href="/gallery-{{$v.cat_id}}-0-0-0-1.html">{{$v.cat_name}}</a></dt>
		   		<dd style='display:none;'>
		   		{{foreach from=$v.sub item=vv}}
		   		<a href="/gallery-{{$vv.cat_id}}-0-0-0-1.html">{{$vv.cat_name}}</a><br>
		   		{{/foreach}}
		   		</dd>
		   {{/foreach}}
			<script type="text/javascript">
			      $(document).ready(function(){
			           $.each($("dl>dt"), function(){
			                $(this).click(function(){
			                    $("dl>dd ").not($(this).next()).slideUp(0);
			                    $(this).next().slideToggle(0);
			                    $("dl>dt ").not($(this)).removeClass('active');
			                    $(this).toggleClass('active');
			                });
			           });
			      });
         </script>
		</dl>
		{{include file="_library/renqi.tpl"}}
	</div>
	<div class="catalog-Pro-right">
		<div class="screening  border2">
           <div class="screentop">
             <p class="pleft">商品筛选<strong>{{$keywords}}</strong></p>
             
           </div>
           
           
           <div class="attrs">
	           <div class="attrs_key">价格:</div>
	           <div class="attrs_values">
			        <ul class="jiagelist">
			        	{{foreach from=$filter_price item=v key=k}}
							{{if $k eq 0}}<p>{{else}}<li>{{/if}}<a href="/region-search-{{$id}}-{{$v.price_value}}-{{$sort}}-1.html" class="{{if $k eq 0}}allbg{{/if}}  {{if $v.price_value eq $price}}attrs_current{{/if}}">{{$v.price_name}}</a>{{if $k eq 0}}</p>{{else}}</li>{{/if}}
						{{/foreach}}
			        	
			      	</ul>
		      	</div>
           </div>
           
           <div class="clearfix"></div>
		</div><!--screening -->
		<div class="screening md border2">
		  <div class="sort">
		  	<strong>排序</strong>
		  	{{foreach from=$sortList item=v}}
		  	<a href="/region-search-{{$id}}-{{$price}}-{{if $v.value eq 0}}{{$v.sorttype.0}}{{elseif $sort|in_array:$v.sorttype && ($sort%2) eq 0}}{{$v.sorttype.0}}{{else}}{{$v.sorttype.1}}{{/if}}-1.html">
		  		<em>{{$v.sortname}}</em>
		  		{{if $v.value neq 0}}<i class="{{$v.sortclass}}"></i>{{/if}}
		  	</a>
		  	{{/foreach}}
		  </div>
		  {{$pageNav1}}
		</div><!--md-->
		<div class="cplist">
			{{if $goodsData eq null}}
			<span style='color:#900'>没有符合您条件的商品</span>
			{{else}}
			{{foreach from=$goodsData item=v}}
			<div class="cpone">
		       <div class="hot-pro mainP">
				<p class="img border1"><a href="/goods-{{$v.goods_id}}.html"><img src="{{$imgBaseUr}}/{{$v.goods_img|replace:'.':'_380_380.'}}"  height='280'></a><p>
				<p class="text"><a href="/goods-{{$v.goods_id}}.html">{{$v.goods_name}}</a></p>
				<p class="text"><span class="span1">品牌：{{$v.brand_name}}</span><span class="span3"><img src="{{$imgBaseUr}}/{{$v.region_imgurl}}" width=30 height=20></span><span class="span2">来自{{$v.region_name}}</span></p>
		       </div>
		       <div class="cpCart"><strong>￥{{$v.price}}</strong><button onclick="javascript:location.href='/goods-{{$v.goods_id}}.html'">立即去购买</button></div>
			</div>
			<div class="fenge"></div>
			{{/foreach}}
			{{/if}}
		</div>
		<div class="clearfix"></div>
		<div class="footer-page">
		{{$pageNav}}
		</div>
	</div>
</div><!--container-->