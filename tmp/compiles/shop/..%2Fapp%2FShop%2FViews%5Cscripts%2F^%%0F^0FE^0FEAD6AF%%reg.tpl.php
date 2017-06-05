<?php /* Smarty version 2.6.19, created on 2014-12-11 17:15:11
         compiled from auth/reg.tpl */ ?>

	<div class="register">
		<div class="register_title">会员注册</div>
		
		<form action="/auth/register" method="post" name="formRegister" id="formRegister" >
		
		<?php if ($this->_tpl_vars['error']): ?>
		<div class="red">
			<?php echo $this->_tpl_vars['error']; ?>

		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['goto']): ?>
		<input type="hidden" name="goto" value="<?php echo $this->_tpl_vars['goto']; ?>
" />
		<?php else: ?>
		<input type="hidden" name="refer" value="<?php echo $this->_tpl_vars['refer']; ?>
" />
		<?php endif; ?>
		
		<ul class="register_infor">
			<li>
				<div class="register_name">账号</div>
				<div class="register_text"><input id="user_name" name="user_name" value='邮箱/手机/用户名' nullmsg="请输入邮箱/手机/用户名"  datatype=" /^[A-Za-z0-9_\-\\.\\@\\u4e00-\\u9fa5]{3,30}$/"  ajaxurl="/auth/check/" sucmsg="验证通过！" errormsg="请输入正确的邮箱/手机/用户名" tabindex="1"/></div>
				<div class="register_tip"></div>
			</li>
			<li>
				<div class="register_name">密码</div>
				<div class="register_text"><input type="password" name="password" datatype="*6-20" nullmsg="输入6-20位字母、数字、符号！" sucmsg="验证通过" errormsg="输入6-20位字母、数字、符号！" plugin="passwordStrength" tabindex="2"/></div>
				<div class="register_tip"></div>
			</li>
			<li>
				<div class="register_name">确认密码</div>
				<div class="register_text"><input type="password"  name="confirm_password"  id="cpassword" errormsg="您两次输入的账号密码不一致！" sucmsg="验证通过" nullmsg="请再输入一次密码！" recheck="password"  datatype="*6-20" tabindex="3"/></div>
				<div class="register_tip"></div>
			</li>
			<!-- <li>
				<div class="register_name">昵称</div>
				<div class="register_text"><input type="text" name="nick_name" tabindex="4" datatype="s2-10" nullmsg="昵称为2-10个字母或者汉字构成！" errormsg="昵称为2-10个字母或者汉字构成！" sucmsg="验证通过"/></div>
				<div class="register_tip"></div>
			</li> -->
			<li>
				<div class="register_name">验证码</div>
				<div class="register_code">
					<input type="text"  ajaxurl="/auth/check-reg-code/" nullmsg="请输入验证码！" maxlength="5" errormsg="输入右侧图片中的字符！" datatype="s1-5" name="verifyCode" tabindex="5" onkeyup="parseUpperCase(this)"/>
					<div class="code_img"><img src="/auth/auth-image/space/shopRegister/code/<?php echo time(); ?>
" onclick="change_verify('verify_img','shopRegister');" id="verify_img"   width="64" height="36" align="absmiddle" style="margin-top:3px;margin-left:5px; "/></div>
					<div class="code_reset"><a href="javascript:;" onclick="change_verify('verify_img','shopRegister');" style="margin-left:5px;color:#317ee7">换一个</a></div>
					<br class="clearfix"/>
          		</div>
				<div class="register_tip" style="width:400px;margin:0 0 0 28px;"></div>
			</li>
		</ul>
		<ul class="register_agreement" id="register_agreement">
			<li class="checkbox"><input type="checkbox" name="agreement"  nullmsg="请勾选用户隐私条款！"  datatype="clause"   id="checkbox" class="checkbox" checked="checked"/></li>
			<li class="agreement_word">阅读并同意<a href="http://www.cnsc.com.cn/help/detail-336.html">《国人海淘网会员协议》</a></li>
		</ul>
		<div class="submit_box">
			<div class="register_btn"> <input type="image" src="/public/images/register_btn.png" alt="注册" /></div>
			<div class="own_number">已有账号，<a href="/login.html">我要登录</a></div>
		</div>
		
    </form>
		
	</div>
<script type="text/javascript">
$(function(){	
    $("#formRegister").Validform({
  	    tiptype:function(msg,o,cssctl){
			o.obj.parent().parent().find('.register_tip').html(msg);
			
  	    },
  		datatype:{ //传入自定义datatype类型【方式二】;
		 "clause":function(gets,obj,curform,regxp){
				var need=1,
				numselected=curform.find("input[name='"+obj.attr("name")+"']:checked").length;
				return  numselected >= need ? true : "请勾选用户隐私条款";
		}},			
  		usePlugin:{passwordstrength:{minLen:6,maxLen:16}}
  	});  
    $('#user_name').focus(function(){
		if($(this).val() == '邮箱/手机/用户名'){
			$(this).val('');
			$(this).css("color","#333");
		}
		ajax_username = $(this).val();
	});
	
	$('#user_name').blur(function(){
		if($(this).val() == '')
		{
			$(this).val('邮箱/手机/用户名');
			$(this).css("color","#999");
		}
	});

	$('.code_img img').css({margin:0,width:'68px',height:'22px'});
});
</script>