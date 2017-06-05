<div class="groups mar">
<h2 class="clear"><strong>品牌热卖</strong></h2>
<ul class="clear">
{{foreach from=$datas item=data}}
		<li><a href="/{{if $data.g_type}}group{{/if}}goods-{{$data.goods_id}}.html" target="_blank"  >
		<img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_180_180.'}}"  alt="{{$data.goods_name}}"/></a>
			<p><a href="/{{if $data.g_type}}group{{/if}}goods-{{$data.goods_id}}.html" target="_blank">{{$data.goods_name}}</a></p>
			<span>市场价：<em>￥{{$data.market_price}}</em></span>
			{{if $data.org_price}}
				{{if $data.offers_type=='exclusive' || $data.offers_type=='group-exclusive'}}
			<span>专享价：<strong>￥{{$data.price}}</strong>
					 {{if $data.excluisv_name neq ''}}<strong style="color:#060">（{{$data.excluisv_name}}）</strong>{{/if}}</span>
				{{elseif $data.offers_type=='price-exclusive'}}	

			<span>特价：<strong>￥{{$data.price}}</strong>
					{{if $data.excluisv_name neq ''}}<strong style="color:#060">（{{$data.excluisv_name}}）</strong>{{/if}}</span>
				{{elseif $data.offers_type=='fixed'}}
			<span>特惠价：<strong>￥{{$data.price}}</strong></span>
				{{elseif $data.offers_type=='discount'}}
			<span>{{$data.discount_title}}折价：<strong>￥{{$data.price}}</strong></span>
				{{/if}}
			{{else}}
			<span>维康价：<strong>￥{{$data.price}}</strong></span>
			{{/if}}	
		</li>
{{/foreach}}
</ul>   
</div>

