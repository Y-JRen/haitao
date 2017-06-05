<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>用户登录-国药集团1健康商城 </title>
<meta charset="utf-8"/>
<link type="text/css" href="{{$_static_}}/styles/css.php?t=css&f=base.css,auth.css&v={{$sys_version}}.css" rel="stylesheet" />
<script src="{{$_static_}}/scripts/js.php?t=js&f=jquery.min.js,common.js,login.js&v={{$sys_version}}.js" type="text/jscript"></script>	
</head>
<body>
<div class="register">
	<div class="suc_left"><img src="/public/images/success_icon.png"/></div>
	
	  {{if $is_success eq "YES"}}
	<div class="suc_right">
		<div class="suc_cue">{{$username}} 恭喜您，您已注册成为我们的会员！现在可以开启您的海淘旅程啦！</div>
		<ul class="suc_link">
			<li class="to_home"><a href="/">去网站首页看看</a></li>
			<li class="to_center"><a href="/member">去会员中心完善资料</a></li>
			<br class="clearfix"/>
		</ul>
	</div>
	 {{else}}
	 <div class="suc_right">
			<p><img src="/public/images/error_icon.gif" style="margin-left:40px;"/></p>
			<p>•错误信息：<span style="color:blue;"> {{$message}}</span></p>
	</div>
	{{/if}}
	
	 <div class="suc_right">
		<p>• 页面将在 <span id="totalSecond" style="color:red;font-size:16px;">5</span> 秒钟后跳转至上一次操作页面。</p>
	 </div>
	 <br class="clearfix"/>
</div>

<script language="JavaScript" type="text/javascript">
{{if $is_success eq "YES"}}
delayURL("{{$goto|default:'/'}}");
{{else}}
delayURL("{{$refer|default:'/'}}");
{{/if}}
function delayURL(url) {
		var delay = document.getElementById("totalSecond").innerHTML;
		if(delay > 0) {
			delay--;
			document.getElementById("totalSecond").innerHTML = delay;
		} else {
			window.top.location.href = url;
		}
		setTimeout("delayURL('" + url + "')", 1000);
}
</script>
</body>
</html>