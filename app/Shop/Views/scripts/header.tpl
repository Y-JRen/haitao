<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{if $page_title}}{{$page_title}}{{else}}国人海淘网{{/if}}</title>
<link type="image/x-icon" href="/public/images/cnsc32.ico" rel="Shortcut Icon">
<meta name="Keywords" content="{{if $page_keyword}}{{$page_keyword}}{{else}}国人海淘网{{/if}}" />
<meta name="Description" content="{{if $page_description}}{{$page_description}}{{else}}国人海淘网{{/if}}" />		
<link type="text/css" href="{{$_static_}}/styles/css.php?t=css&f=base.css{{$css_more}}&v={{$sys_version}}.css" rel="stylesheet" />
<script>var static_url='{{$_static_}}',img_url='{{$imgBaseUrl}}',cur_time= '{{$smarty.now}}';</script>
<script src="{{$_static_}}/scripts/js.php?t=js&f=jquery.min.js,header.js,common.js,check.js{{$js_more}}&v={{$sys_version}}.js" type="text/jscript"></script>
<!--[if lte IE 6]>
<script type="text/javascript" src="/public/scripts/Noname2.js"></script>
<script>
  DD_belatedPNG.fix('.png_bg,.png_bg a:hover');
</script>
<![endif]-->
</head>
<body>
<div class="navbar" role="navigation">
	<div class="container">
			<ul class="navbar-nav">
				<span id="user_login_span" class="l welcome"><img src="/public/images/loading.gif" alt="loading图片" /> 加载中... </span>
			</ul>
			<ul class="navbar-nav navbar-right">
				<li><a href="/member/order">我的订单</a></li>
				<li><a href="/member">会员中心</a></li>
				<li><a href="/help">服务中心</a></li>
				
				<li><a href="http://www.kuajingtong.com/" target="_blank">进入跨境通</a></li>
			</ul>
	</div>
</div>
<!--navigation -->
<div class="nav-header">
  <div class="container">
  	<div class="logo" onclick="location.href='http://www.cnsc.com.cn'">
  		<a href="/">阳光海淘 正品直邮</a>
  	</div>
    
	<div class="search">
		<div class="input-group">
		  <form action='/search.html' method="get" id="form_hd">
		  <input type="text" class="form-control png_bg" value="搜索海淘网 关键字" name='keyword' id='keyword_hd' />
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="button" onclick='search_sbmit()'>搜索</button>
	      </span>
	      </form>
	    </div>
	    <div class="input-reci">
	    	<span>热门搜索：</span>
	    	
	    	<a href="http://www.cnsc.com.cn/gallery-49-0-0-0-1.html">保温杯</a>
	    	<a href="http://www.cnsc.com.cn/gallery-71-0-0-0-1.html">空气净化器</a>
	    	<a href="http://www.cnsc.com.cn/gallery-122-0-0-0-1.html">丝袜</a>
	    	<a class="last-resi" href="http://www.cnsc.com.cn/gallery-39-0-0-0-1.html">太阳能电波表</a>
	    </div>
   </div>
   <div id="cart1">
   	
   </div>
  </div>
</div><!--nav-header-->
<div class="clearfix"></div>
<div class="allSort container">
   <div class="allSort-list">
	    <h1 class="allSort-h1 png_bg">全部商品分类</h1>
	    <div class="show hidden"  {{if  $is_index_page}} style="display:block" {{/if}}>
	    {{include file="_library/catnav.tpl"}}
	    </div>
   </div>
    <div class="allSort-nav">
	    <ul class="subnav">
	      <li class="lihome"><a href="/">首页</a></li>
	      <!-- <li><a href="/page/hot">今日最热</a><span class="label"></span></li> -->
	      <li><a href="/new-goods.html">新品上架</a><span class="label label-new"></span></li>
	      <li><a href="/tax.html">低税商品</a></li>
	      <li><a href="/page/shop">免税店</a></li>
	      <li class="hotline"><a href="">海淘热线 400-649-3883</a></li>
	    </ul>
    </div>
</div><!--allSort-->
