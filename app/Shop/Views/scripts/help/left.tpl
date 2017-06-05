
<div class="help_menu">
{{foreach from=$menu item=item}}
		<dl>
			<dt><i></i>{{$item.cat_name}}</dt>
			{{foreach from=$item.list item=vo}}
			<dd><a title="{{$vo.title}}" href="/help/detail-{{$vo.article_id}}.html">{{$vo.title}}</a></dd>
			{{/foreach}}
		
		</dl>

 {{/foreach}}
</div>