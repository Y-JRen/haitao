{{if $isEmpty ne 1}}
<ul class="top_cart">
{{foreach from=$historydatas item=history}}
<li class="clear"><a href="/goods-{{$history.goods_id}}.html" target=_blank><img src="{{$imgBaseUrl}}/{{$history.goods_img|replace:'.':'_60_60.'}}" /></a><span><a href="/goods-{{$history.goods_id}}.html" target=_blank>{{$history.goods_name}}</a></span></li>
{{/foreach}}
</ul>
<div class="more"><a href="javascript:;" onclick="emptyGoodsHistory();return false;">[清除记录]</a></div>
{{else}}
您最近没有进行过搜索。
{{/if}}