<?php /* Smarty version 2.6.19, created on 2014-12-17 14:06:30
         compiled from flow_header.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php if ($this->_tpl_vars['page_title']): ?><?php echo $this->_tpl_vars['page_title']; ?>
<?php else: ?>国药电商 - 专业的健康品商城<?php endif; ?></title>
<meta name="Keywords" content="<?php if ($this->_tpl_vars['page_keyword']): ?><?php echo $this->_tpl_vars['page_keyword']; ?>
<?php else: ?>国药电商 ,网上保健品商城,网上买保健品，<?php endif; ?>" />
<meta name="Description" content="<?php if ($this->_tpl_vars['page_description']): ?><?php echo $this->_tpl_vars['page_description']; ?>
<?php else: ?>国药电商 -专业的健康品商城，绝对正品保证，支持货到付款，30天退换货保障！<?php endif; ?>" />		
<link type="text/css" href="<?php echo $this->_tpl_vars['_static_']; ?>
/styles/css.php?t=css&f=base.css<?php echo $this->_tpl_vars['css_more']; ?>
&v=<?php echo $this->_tpl_vars['sys_version']; ?>
.css" rel="stylesheet" />
<script>var static_url='<?php echo $this->_tpl_vars['_static_']; ?>
',img_url='<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
',cur_time= '<?php echo time(); ?>
';</script>
<script src="<?php echo $this->_tpl_vars['_static_']; ?>
/scripts/js.php?t=js&f=jquery.min.js,header.js,common.js<?php echo $this->_tpl_vars['js_more']; ?>
&v=<?php echo $this->_tpl_vars['sys_version']; ?>
.js" type="text/jscript"></script>
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
   <?php if ($this->_tpl_vars['flow_index'] == 1): ?>
   <div class="cartflow"><img src="/public/images/flow.png"></div>
   <?php elseif ($this->_tpl_vars['flow_index'] == 2): ?>
   <div class="cartflow"><img src="/public/images/flow2.png"></div>
   <?php elseif ($this->_tpl_vars['flow_index'] == 3): ?>
   <div class="cartflow"><img src="/public/images/flow3.png"></div>
   <?php elseif ($this->_tpl_vars['flow_index'] == 4): ?>
   <div class="cartflow"><img src="/public/images/flow4.png"></div>
   <?php endif; ?>
  </div>
</div><!--nav-header-->
<div class="clearfix"></div>