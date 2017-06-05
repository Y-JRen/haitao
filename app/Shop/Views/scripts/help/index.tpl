<div class="bread container">
	<ul>{{$ur_here}}</ul>
</div>
<!--breadcrumbs-->
<div class="support container">
 <div class="spleft">
 	{{include file="_library/footer_nav.tpl"}}
 </div>
 <div class="spright">
 	<div class="spright-title">
 		{{$info.title}}
 	</div>
 	<div class="spright-text">{{$info.content}}</div>
 </div>
</div><!--support-->
	
<script type="text/javascript">
	$(function(){
		$('.spleft dl:last').addClass('lastbotm');
	})
</script>
