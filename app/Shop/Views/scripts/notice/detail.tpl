
<div class="bread container">
	<ul>
		<li><a href="/"><strong>首页</strong></a>></li>
		<li><a href="{{if $info.cat_id eq 34}}/notice{{else}}/notice-{{$info.cat_id}}{{/if}}"><strong>{{if $info.cat_id eq 34}}公告{{else}}常见问题{{/if}}</strong></a>></li>
		<li><a href="javascript:void(0);">{{$info.title}}</a></li>
	</ul>
</div><!--breadcrumbs-->
<div class="public container">
	<div class="public_left textP border2">
	 <h1>{{$info.title}}</h1>
	 <em>{{$info.add_time|date_format:'%Y-%m-%d %H:%M:%S'}}</em>
	 <hr>
	 {{$info.content}}
	</div>
    <div class="public_right">
    	<div class="public_imgtop border2">{{widget class="AdvertWidget" id="33"}}</div>
    	<div class="public_imgbottom border2">{{widget class="AdvertWidget" id="34"}}</div>
    </div>
</div><!--public-->
