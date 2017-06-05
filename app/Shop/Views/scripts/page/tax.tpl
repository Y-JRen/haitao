
<div class="theme_container lowtaxgoods">
 <div class="theme_title png_bg">行邮税税率低于20%的海外商品，购买这类商品最划算！</div>
	<div class="theme_catalog border2">
       <div class="cat_attr">
           <div class="attrs">
	           <div class="attrs_key">分类:</div>
	           <div class="attrs_values v-fold">
			        <ul class="f-first">
			            {{foreach from=$filter_cat item=v key=k}}
						{{if $k eq 0}}<p>{{else}}<li>{{/if}}<a href="{{$v.url}}" class="{{if $k eq 0}}allbg{{/if}} {{if $v.is_c}}attrs_current{{/if}}" >{{$v.cat_name}}</a>{{if $k eq 0}}</p>{{else}}</li>{{/if}}
					    {{/foreach}}
			      	</ul>
			      	{{if count($filter_cat) gt 14}}
			      	<div option="more" onclick="moreExpandValue(this)" class="attrs_option"><span class="moreV png_bg">更多</span></div>
			      	<div option="less" onclick="lessExpandValue(this)" class="attrs_option hidden"><span class="moreV png_bg">收起</span></div>
			      	{{/if}}
		      	</div>
           </div>
       </div>
       <div class="divide clearfix"></div>
       <div class="brand_attr">
           <div class="attrs">
	           <div class="attrs_key">品牌:</div>
	           <div class="attrs_values v-fold">
			        <ul class="f-first">
			            {{foreach from=$filter_brand item=v key=k}}
			            {{if $k eq 0}}<p>{{else}}<li>{{/if}}<a href="{{$v.url}}" class="{{if $k eq 0}}allbg{{/if}} {{if $v.is_c}}attrs_current{{/if}}" >{{$v.brand_name}}</a>{{if $k eq 0}}</p>{{else}}</li>{{/if}}
					    {{/foreach}}
			      	</ul>
					{{if count($filter_brand) gt 14}}
			      	<div option="more" onclick="moreExpandValue(this)" class="attrs_option"><span class="moreV moreV2 png_bg">更多</span></div>
			      	<div option="less" onclick="lessExpandValue(this)" class="attrs_option hidden"><span class="moreV moreV2 png_bg">收起</span></div>
			      	{{/if}}
		      	</div>
           </div>
       </div> 
       <div class="clearfix"></div>	
	</div>
	<div class="md border2">
		<div class="sort"><strong>排序</strong>
		{{foreach from=$sortList item=v}}
		  	<a href="{{$v.url}}">
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
			{{foreach from=$goodsData item=v key=k}}
			<div class="cpone">
		       <div class="hot-pro mainP">
				<p class="img border1"><a href="/goods-{{$v.goods_id}}.html"><img src="{{$imgBaseUr}}/{{$v.goods_img|replace:'.':'_380_380.'}}"  height='280'></a><p>
				<p class="text"><a href="/goods-{{$v.goods_id}}.html">{{$v.goods_name}}</a></p>
				<p class="text"><span class="span1">品牌：{{$v.brand_name}}</span><span class="span3"><img src="{{$imgBaseUr}}/{{$v.region_imgurl}}" width=30 height=20></span><span class="span2">来自{{$v.region_name}}</span></p>
		       </div>
		       <div class="cpCart"><strong>￥{{$v.price}}</strong><button onclick="javascript:location.href='/goods-{{$v.goods_id}}.html'">立即去购买</button></div>
			</div>
			{{if ($k+1)%4 neq 0}}
			<div class="fenge"></div>
			{{/if}}
			{{/foreach}}
			{{/if}}
			
	</div><!--cplist-->
	<div class="clearfix"></div>
	<div class="footer-page">
		{{$pageNav}}
	</div>
</div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
	function moreExpandValue(t) {
	    $(t).hide().next().show();
	    $(t).parent().addClass('v-unfold');
	}
	function lessExpandValue(t) {
	    $(t).parent().removeClass('v-unfold');
	    $(t).hide().prev().show();
	}
</script>
</body>
</html>


