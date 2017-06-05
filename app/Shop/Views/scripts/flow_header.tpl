<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{if $page_title}}{{$page_title}}{{else}}国药电商 - 专业的健康品商城{{/if}}</title>
<meta name="Keywords" content="{{if $page_keyword}}{{$page_keyword}}{{else}}国药电商 ,网上保健品商城,网上买保健品，{{/if}}" />
<meta name="Description" content="{{if $page_description}}{{$page_description}}{{else}}国药电商 -专业的健康品商城，绝对正品保证，支持货到付款，30天退换货保障！{{/if}}" />		
<link type="text/css" href="{{$_static_}}/styles/css.php?t=css&f=base.css{{$css_more}}&v={{$sys_version}}.css" rel="stylesheet" />
<script>var static_url='{{$_static_}}',img_url='{{$imgBaseUrl}}',cur_time= '{{$smarty.now}}';</script>
<script src="{{$_static_}}/scripts/js.php?t=js&f=jquery.min.js,header.js,common.js{{$js_more}}&v={{$sys_version}}.js" type="text/jscript"></script>
<!--[if lte IE 6]>
<script type="text/javascript" src="/public/scripts/Noname2.js"></script>
<script>
  DD_belatedPNG.fix('.png_bg,.png_bg a:hover');
</script>
<![endif]-->
</head>
<body>
<div class="flow_header_blank"></div>
<div class="nav-header">
  <div class="container">
    <div class="logo" onclick="javascript:location.href='/';">
      <a href="/">阳光海淘 正品直邮</a>
   </div>
   {{if $flow_index eq 1}}
   <div class="cartflow"><img src="/public/images/flow.png"></div>
   {{elseif $flow_index eq 2}}
   <div class="cartflow"><img src="/public/images/flow2.png"></div>
   {{elseif $flow_index eq 3}}
   <div class="cartflow"><img src="/public/images/flow3.png"></div>
   {{elseif $flow_index eq 4}}
   <div class="cartflow"><img src="/public/images/flow4.png"></div>
   {{/if}}
  </div>
</div><!--nav-header-->
<div class="clearfix"></div>
