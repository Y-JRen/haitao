{{if $history}}
<ul class="hislist">

	{{foreach from=$history item=v key=k}}
	<li class="clearfix">
		<div class="img">
			<div class="wh60 verticalPic">
				<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}"><img src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_380_380.'}}" alt="{{$v.goods_name}}" width="60" height="60"></a>
			</div>
		</div>
		<div class="txt">
			<p class="title">
				<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}">{{$v.goods_name}}</a>
			</p>
			<p class="Sprice">
				￥<span>{{$v.price}}</span>
			</p>
		</div>
	</li>
	{{/foreach}}							
</ul>
{{else}}
	<div style="padding: 10px;">暂无浏览记录！</div>
{{/if}}