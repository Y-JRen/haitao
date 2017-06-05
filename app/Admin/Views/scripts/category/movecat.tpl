<div class="title">分类管理 ---> 展示分类移动</div>
<div class="search">
	<form name="myForm" id="myForm" method="post" action="/admin/category/movecat">
	把{{$fromcatSelect}}的商品移动到  {{$tocatSelect}} <font color="#FF0000"> (必须是末级分类)</font>
	<input type="submit" name="dosubmit" id="dosubmit" value="确定移动"/> 
	</form>
</div>
