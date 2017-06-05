

	<div class="register">
		<div class="register_title">找回密码</div>
		<ul class="findStep">
			<li>
				<div>{{if $type eq 1}}<img src="/public/images/onStep01.png"/>{{else}}<img src="/public/images/step01.png"/>{{/if}}</div>
				<p>输入账号</p>
			</li>
			<li>
				<div>{{if $type eq 2}}<img src="/public/images/onStep02.png"/>{{else}}<img src="/public/images/step02.png"/>{{/if}}</div>
				<p>验证身份</p>
			</li>
			<li>
				<div>{{if $type eq 3}}<img src="/public/images/onStep03.png"/>{{else}}<img src="/public/images/step03.png"/>{{/if}}</div>
				<p>设置新密码</p>
			</li>
			<li>
				<div>{{if $type eq 4}}<img src="/public/images/onStep04.png"/>{{else}}<img src="/public/images/step04.png"/>{{/if}}</div>
				<p>完成</p>
			</li>
			<br class="clearfix"/>
		</ul>
		
		{{if $type eq 1}}
		<form action="" method="post" name="getPassword" id="getPassword" onsubmit="return submitPwdInfo();" >
			<input type='hidden' name='type' value='1' />
			<ul class="lostEnter">
				<li class="enterAccount">
					<p>输入账号</p>
					<div class=""><input class="text" name="name" id="name" type="text" /></div>
					<br class="clearfix"/>
				</li>
				<li class="enterCode">
					<p>验证码</p>
					<div class="enterCodeText"><input type="text" id="verifyCode"  name="verifyCode" maxlength="5" onkeyup="parseUpperCase(this)"/></div>
					<div class="enterCodeImg"><img src="/auth/auth-image/space/getPwd/code/{{$smarty.now}}" onclick="change_verify('verify_img','getPwd');" id="verify_img" /></div>
					<div class="enterCodeReset"><a href="#" onclick="change_verify('verify_img','getPwd');">换一个</a></div>
					<br class="clearfix"/>
				</li>
			</ul>
			<div class="nextBtn"><input type='submit' style='background:#990000;width:127px;height:31px;color:#fff' value='下一步' /></div>
		</form>
		<iframe src="about:blank" style="width:0px;height:0px;" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
		<script>
			/**
			 * 处理验证码输入框的按键事件，将所有输入的内容转换为大写
			 */
			function pressVerifyCode(obj) {
				obj.value = obj.value.toUpperCase();
			}
			function submitPwdInfo() {
				var email = $.trim($('#name').val());
				var verifyCode = $.trim($('#verifyCode').val());
				var msg = '';
				if (email == '') 
					msg += '请输入您的账号!\n';
				if (verifyCode == '') 
					msg += '请输入验证码!\n';
				if (msg.length > 0) {
					alert(msg);
					return false;
				} else {
					$('#dosubmit').attr('disabled', true);
					return true;
				}
			}
		</script>
		{{elseif $type eq 2}}
			{{if $status eq 2}}
			<form action='' method='post' onsubmit="return submitPwdInfo();">
			<ul class="lostEnter">
				<li>验证码已发送至您的手机，请在3分钟内输入验证码</li>
				<li>验证码：<input type='text' name='code' /><input type='hidden' name='type' value=2 /></li>
				<li><input type='submit' value='确认' /></li>
			</ul>
			</form>
			<script>
			function submitPwdInfo()
			{
				var value = $("input[name='code']").val();
				if(value){
					return true;
				}else{
					alert("请填写手机验证码！");
					return false;
				}
				return false;
			}
			</script>
			{{elseif $status eq 0}}
			<ul class="lostEnter">
				<li>对不起，无法找回你的密码</li>
				<li>您的帐号未绑定手机或邮箱，如需找回密码，请与客服联系</li>
			</ul>
			{{else}}
			<form action='' method='post' onsubmit="return submitPwdInfo();">
				<input type='hidden' value='2' name='type'>
				<ul class="lostEnter">
					{{if $email}}
					<li><input type='radio' name='set_type' value='email'/>通过邮箱找回密码</li>
					{{/if}}
					{{if $mobile}}
					<li><input type='radio' name='set_type' value='mobile' />通过手机找回密码</li>
					{{/if}}
					<li><input type='submit' value='确定' /></li>
				</ul>
			</form>
			<script>
			function submitPwdInfo()
			{
				value = $(":radio[name='set_type']:checked").val();
				if(value){
					return true;	
				}
				alert('选择找回密码方式')
				return false;
			}
			</script>
			{{/if}}
		{{/if}}
	</div>
	

