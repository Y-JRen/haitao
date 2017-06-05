
<div class="bread container">
	<ul>
		<li><a href="/"><strong>首页</strong></a>></li>
		<li><a href="{{if $cat eq 34}}/notice{{else}}/notice-{{$cat}}{{/if}}"><strong>{{if $cat eq 65}}公告{{else}}常见问题{{/if}}</strong></a></li>
	</ul>
</div><!--breadcrumbs-->
<div class="public container">
	<div class="public_left border2">
	 <ul>
	 	{{foreach from=$main.data item=v}}
	 	<li><p>{{$v.add_time|date_format:'%Y-%m-%d'}}</p><a href="/notice/detail/id/{{$v.article_id}}">{{$v.title}}</a></li>
	 	{{/foreach}}
	 	
	 </ul>
	 <div class="footer-page">
	 	{{$main.pagenav}}
		
	</div>
	</div>
    <div class="public_right">
    	<div class="public_imgtop border2">{{widget class="AdvertWidget" id="33"}}</div>
    	<div class="public_imgbottom border2">{{widget class="AdvertWidget" id="34"}}</div>
    </div>
</div>
