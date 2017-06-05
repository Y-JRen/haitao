<?php /* Smarty version 2.6.19, created on 2014-12-12 16:56:10
         compiled from auth/register.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'auth/register.tpl', 37, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>用户登录-国药集团1健康商城 </title>
<meta charset="utf-8"/>
<link type="text/css" href="<?php echo $this->_tpl_vars['_static_']; ?>
/styles/css.php?t=css&f=base.css,auth.css&v=<?php echo $this->_tpl_vars['sys_version']; ?>
.css" rel="stylesheet" />
<script src="<?php echo $this->_tpl_vars['_static_']; ?>
/scripts/js.php?t=js&f=jquery.min.js,common.js,login.js&v=<?php echo $this->_tpl_vars['sys_version']; ?>
.js" type="text/jscript"></script>	
</head>
<body>
<div class="register">
	<div class="suc_left"><img src="/public/images/success_icon.png"/></div>
	
	  <?php if ($this->_tpl_vars['is_success'] == 'YES'): ?>
	<div class="suc_right">
		<div class="suc_cue"><?php echo $this->_tpl_vars['username']; ?>
 恭喜您，您已注册成为我们的会员！现在可以开启您的海淘旅程啦！</div>
		<ul class="suc_link">
			<li class="to_home"><a href="/">去网站首页看看</a></li>
			<li class="to_center"><a href="/member">去会员中心完善资料</a></li>
			<br class="clearfix"/>
		</ul>
	</div>
	 <?php else: ?>
	 <div class="suc_right">
			<p><img src="/public/images/error_icon.gif" style="margin-left:40px;"/></p>
			<p>•错误信息：<span style="color:blue;"> <?php echo $this->_tpl_vars['message']; ?>
</span></p>
	</div>
	<?php endif; ?>
	
	 <div class="suc_right">
		<p>• 页面将在 <span id="totalSecond" style="color:red;font-size:16px;">5</span> 秒钟后跳转至上一次操作页面。</p>
	 </div>
	 <br class="clearfix"/>
</div>

<script language="JavaScript" type="text/javascript">
<?php if ($this->_tpl_vars['is_success'] == 'YES'): ?>
delayURL("<?php echo ((is_array($_tmp=@$this->_tpl_vars['goto'])) ? $this->_run_mod_handler('default', true, $_tmp, '/') : smarty_modifier_default($_tmp, '/')); ?>
");
<?php else: ?>
delayURL("<?php echo ((is_array($_tmp=@$this->_tpl_vars['refer'])) ? $this->_run_mod_handler('default', true, $_tmp, '/') : smarty_modifier_default($_tmp, '/')); ?>
");
<?php endif; ?>
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