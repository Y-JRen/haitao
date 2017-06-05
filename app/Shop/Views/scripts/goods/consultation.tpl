<!--新加产品咨询-->
<p class="sh">共有{{$csl.total}} 人进行了提问 |  您的咨询，营养师将在10分钟内回复您，请您稍等。<!-- <a href="#" class="blue">查看所有问题>></a>--> </p>

<form action="javascript:;" onsubmit="submitComment(this)" method="post" name="ommenttForm" id="ommenttForm">
<input type="hidden" name="goods_id" value="{{$id}}" />
<input type="hidden" name="title" value="产品咨询" />
<input type="hidden" name="type" value="2" />
<input type="hidden" name="goods_name" value="{{$goods.goods_name}}" />

{{if $login}}
	<div class="grayborder"><a href="/login.html" class="blue">登录</a>国药电商帐号进行提问</div>
{{else}}
	<div class="grayborder"><textarea id="contentt" name="content"></textarea></div>
	<div class="tj"><input type="submit" value="我要提问" name=""></div> 
{{/if}}

</form>

<h4>产品咨询 <span>共有<em class="red"> {{$csl.total}} </em>条咨询 已解决<em class="red"> {{$csl.ctotal}} </em>条</span></h4>

<ul>
{{if $datas}}
{{foreach from=$datas item=item}}	
    <li><div class="one"><strong class="orange">{{$item.user_name}}</strong> 说：{{$item.add_time|date_format:'%Y-%m-%d %H:%M:%S'}}
    <div>{{$item.content}}</div></div>
    <div class="two clear">
    <div class="fl">
        {{if $item.dietitian eq 1}}
            <img src="{{$imgBaseUrl}}/images/shop/dietitian_1.jpg" />彭老师
            {{elseif $item.dietitian eq 2}}
            <img src="{{$imgBaseUrl}}/images/shop/dietitian_2.jpg" />白老师
            {{elseif $item.dietitian eq 3}}
            <img src="{{$imgBaseUrl}}/images/shop/dietitian_3.jpg" />杜老师
            {{elseif $item.dietitian eq 4}}
            <img src="{{$imgBaseUrl}}/images/shop/dietitian_4.jpg" />肖老师
            {{elseif $item.dietitian eq 5}}
            <img src="{{$imgBaseUrl}}/images/shop/dietitian_5.jpg" />王老师
            {{elseif $item.dietitian eq 6}}
            <img src="{{$imgBaseUrl}}/images/shop/dietitian_6.jpg" />白老师
        {{/if}}
    </div>
    <div class="fr"><strong class="orange">营养师回复解答：</strong>
    <div>{{$item.reply}}</div></div><!--fr end-->
    </div></li>
{{/foreach}}
{{/if}}	

</ul>
<div class="fy"> </div>
<!--新加产品咨询 end-->	
<script type="text/javascript">
/**
 * 提交问题
 */
var cmt_empty_content = "提问不能小于2个字符";
var cmt_large_content = "您输入的内容超过了250个字符";
function submitComment(){
	var uname=$.trim($("#ommenttForm input[name='user_name']").val());
	var content = $("#contentt").val();
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
		data:$('#ommenttForm').serialize(),
		success:function(msg){
			if(msg!=''){alert(msg);}
			else{
				alert('您的问题已成功提交，请等待专家回复。');
                $("#contentt").val('');
			}
		},
		error:function(msg,err){
			alert(err);
		}
	})
	return false;
}
</script>