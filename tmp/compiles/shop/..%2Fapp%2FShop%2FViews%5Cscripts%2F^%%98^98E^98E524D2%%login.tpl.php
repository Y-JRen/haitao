<?php /* Smarty version 2.6.19, created on 2015-02-04 13:47:16
         compiled from auth/login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'widget', 'auth/login.tpl', 3, false),)), $this); ?>

	<div class="login">
		<div class="login_left"><?php echo smarty_function_widget(array('class' => 'AdvertWidget','id' => '17'), $this);?>
</div>
		<div class="login_right">
			<div class="login_title">
				<p class="login_p1">会员登录</p>
				<p class="login_p2" id="msg_box" style="color:red; width:150px;"></p>
			</div>
			
			<form action="/auth/login/" method="post"  onsubmit="return userLogin();" name="loginForm" id="loginForm">
			<?php if ($this->_tpl_vars['goto']): ?>
			<input type="hidden" name="goto" value="<?php echo $this->_tpl_vars['goto']; ?>
" />
			<?php endif; ?>
			<input type="hidden" name="refer" value="<?php echo $this->_tpl_vars['refer']; ?>
" />
			
			<ul class="login_content">
				<li class="login_userName"><input   id="user_name" name="user_name"  type="text"   <?php if ($this->_tpl_vars['isRemUserName'] && $this->_tpl_vars['cookieUserName']): ?>value="<?php echo $this->_tpl_vars['cookieUserName']; ?>
"<?php endif; ?>  /></li>
				<li class="login_password">
					<div class="passwordText"><input id="pwd"  name="password" type="password" /></div>
					<div class="lostPassword"><a href="/auth/get-password">忘记密码？</a></div>
					<br class="clearfix"/>
				</li>
				<li class="loginCode">
					<div class="loginCodeText"><input name="verifyCode"  maxlength="5" id="vcode" type="text" onkeyup="parseUpperCase(this)" /></div>
					<div class="loginCodeImg">
					 <img src="/auth/auth-image/space/shopLogin/code/<?php echo time(); ?>
" onclick="change_verify('verify_img','shopLogin');" id="verify_img"  width="64" height="24" />
					</div>
					<div class="loginCodeReset"><a href="javascript:;" onclick="change_verify('verify_img','shopLogin');">换一个</a></div>
					<br class="clearfix"/>
				</li>
				<li class="loginOwn">
					<div class="loginOwnCheck"><input name="auto_login" type="checkbox" value="1" /></div>
					<p>自动登录</p>
					<div class="login_kjt"><span>其他登录方式:</span><a href="/auth/union-login/un/kjt/refer/<?php echo $this->_tpl_vars['refer']; ?>
"><img src="/public/images/kjt.jpg"><em>跨境通</em></a></div>
					<br class="clearfix"/>
				</li>
				<li class="loginBtn">
					<div class="loginImg"><img src="/public/images/login_btn.png" onclick="return userLogin()"/></div>
					<div class="noAccount ">没账号？<a href="/reg.html">免费注册</a></div>
					<br class="clearfix"/>
				</li>
			</ul>
          </form>
		</div>
		<br class="clearfix"/>
	</div>

<script>
$(document).keydown(function(event){
	if(13 == event.keyCode){userLogin();}
})
</script>