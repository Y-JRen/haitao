<div class="main mar">
<div class="left">
<a href="/goods-{{$goods.goods_id}}.html" ><img id="img_{{$goods.goods_id}}" src="{{$imgBaseUrl}}/{{$goods.goods_img|replace:'.':'_180_180.'}}" style="margin:0 auto" alt="{{$goods.goods_name}}"/></a>
<a href="/goods-{{$goods.goods_id}}.html">{{$goods.goods_name}}</a>
<span>¥ {{$goods.price}}</span>
</div>
<!--left结束-->
<div class="rig re_left re_rig">
<div class="goods_comment" style="margin:20px">
<ul class="clear title"><li class="plaction">全部评论</li></ul>
<p> 共有 {{$total}} 人参与了评论 | 所有评论均来自购买过本产品的用户  </p>
<a href="/commentadd-{{$id}}.html" ><img src="{{$imgBaseUrl}}/images/shop/singlepage_r26_c13.jpg"  alt="我要写评论"/></a>

<ol>
{{if $datas}}
{{foreach from=$datas item=item}}	
			
<li>
<img src="{{$imgBaseUrl}}/images/shop/singlepage_r28_c13.jpg"/>
<div class="time clear"><!--{{$item.add_time|date_format:'%Y-%m-%d %H:%M:%S'}}-->
{{$item.cnt1}}</div>
<p><strong>{{$item.title}}</strong>{{$item.content}}</p>
</li>
{{/foreach}}
{{/if}}	
</ol>
</div></div><!--rig 结束-->
<div class="clear"></div>
</div><!--main结束-->
