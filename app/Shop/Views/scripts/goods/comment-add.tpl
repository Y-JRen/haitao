<div class="main mar">
<div class="left">
<a href="/goods-{{$goods.goods_id}}.html"><img id="img_{{$goods.goods_id}}" src="{{$imgBaseUrl}}/{{$goods.goods_img|replace:'.':'_180_180.'}}" style="margin:0 auto" alt="{{$goods.goods_name}}"/></a>
<a href="/goods-{{$goods.goods_id}}.html">{{$goods.goods_name}}</a>
<span>¥ {{$goods.price}}</span>
</div>
<!--left结束-->
<div class="rig re_rig">
<h3>评论“{{$goods.goods_name}}”</h3>
<div class="infro">1，您要发表的评论是针对商品的，而不是针对订单送货类购物过程。<br />
2，我们欢迎真实有具体见解的商品评论，评论应集中详细叙述商品的特性或使用感受，不但要表明您的观点，还最好能说明原因，使评论内容能为其他用户提供有价值的参考和帮助。<br />
3，管理员有权删除违反上述要求的内容。</div>

{{if !$errorMsg}}

<form action="javascript:;" onsubmit="submitGoodsComment(this)" method="post" name="commentForm" id="commentForm">
<h2>商品评分</h2>
<div class="one">
<ul><li><input type="radio" name="cnt1" value="5"><em></em></li>
<li class="er"><input type="radio" name="cnt1" value="4"><em></em></li>
<li class="san"><input type="radio" name="cnt1" value="3"><em></em></li>
<li class="si"><input type="radio" name="cnt1" value="2"><em></em></li>
<li class="wu"><input type="radio" name="cnt1" value="1"><em></em></li></ul></div>

<div class="two table-patch">
				{{if $office}}
				<h4>用户名 请用Email格式</h4>
				<input  type="text" maxlength="30" name="user_name" class="text" value="{{$auth.user_name}}"/>
				{{/if}}
				<h4 class="bluesquare">标题</h4>
				<input  type="text" maxlength="30" name="title" class="text"/>
				<h4 class="bluesquare">内容</h4>
                <div class="clear"><textarea name="content" cols="35" rows="3" id="content" onkeyup="$('#count').html(this.value.length)"></textarea>
				<input name="" type="submit" value="" class="pic"/>
                <div>已输入字数:<strong id="count">0</strong>/250</div></div>
				<p id="tip"></p>
		</div>
		<input type="hidden" name="goods_id" value="{{$goods.goods_id}}" />
		<input type="hidden" name="goods_name" value="{{$goods.goods_name}}" />
</form>
{{else}}
{{if $errorMsg eq 'notLogin'}}
<div class="two"><h4>您未登录所以不能发表评论</h4><a href="/auth/login"> 按这里登录您的帐号</a></div>
{{elseif $errorMsg eq 'notBuy'}}
<div class="two"><h4>您未购买过此商品不能发表评论</h4><a href="/goods-{{$goods.goods_id}}.html" title="{{$goods.goods_name}}">现在购买</a></div>

{{elseif $errorMsg eq 'hasComment'}}
<div class="two"><h4>您已对此商品发表过评论了</h4><a href="/goods-{{$goods.goods_id}}.html" title="{{$goods.goods_name}}">现在购买</a></div>
{{/if}}
{{/if}}

</div><!--rig 结束-->
<div class="cleardiv"></div>
</div><!--main结束-->

<script>
/**
 * 提交评论信息
 */
var cmt_empty_content = "评论的内容不能小于2个字符";
var cmt_large_content = "您输入的评论内容超过了250个字符";
var cmt_empty_cnt1 = "您没有选择外观评价";
var captcha_not_null = "验证码不能为空!";
var cmt_invalid_comments = "无效的评论内容!";

function submitGoodsComment(){
	var uname=$.trim($("#commentForm input[name='user_name']").val());
	var content=$.trim($("#content").val());
	
	{{if $office}}
	if(uname==''){
		alert('请填写用户名');
		return false;
	}else{
		var patrn= /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
		if(!patrn.test(uname)){
			alert('用户名格式不正确，请填写Email格式!');
            return false;
		}
	}
	{{/if}}
	
	if(content.length<2){
		alert(cmt_empty_content);
		return false;
	}
	if(content.length>250){
		alert(cmt_large_content);
		return false;
	}
	
	$.ajax({
		url:'/goods/msg',
		type:'post',
		data:$('#commentForm').serialize(),
		success:function(msg){
			if(msg!=''){alert(msg);}
			else{
				alert('您的评论已成功提交，请等待管理员审核');
			}
			window.location.replace('/goods-{{$goods.goods_id}}.html');
		},
		error:function(msg,err){
			alert(err);
		}
	})
	
	return false;
}
</script>