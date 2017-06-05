<?php /* Smarty version 2.6.19, created on 2014-07-23 11:17:56
         compiled from auth/relogin.tpl */ ?>
<div class="login_main">
    <ul class="login_box">
    <form name="loginForm" id="loginForm" method="post" action="<?php echo $this -> callViewHelper('url', array());?>" onsubmit="return validate()" target="ifrmSubmit">
    <li>用户名：<input name="user_name" type="text" size="25" value="" maxlength="32" /></li>
	<li>密　码：<input name="password" type="password" size="25" value="" maxlength="32" /></li>
    <li>验证码：<input name="verifyCode" type="text" size="12" maxlength="5" style="width:70px" onkeyup="this.value = this.value.toUpperCase();"/>
        <img class="auth-image" src="<?php echo $this -> callViewHelper('url', array(array('action'=>"auth-image",'space'=>'adminLogin',)));?>" alt="verifyCode" border="0" onclick= this.src="<?php echo $this -> callViewHelper('url', array(array('action'=>"auth-image",'space'=>'adminLogin','code'=>"",),""));?>"+Math.random() style="cursor: pointer;" title="点击更换验证码" />
	</li>
    <li>
	    <input type="submit" name="dosubmit" id="dosubmit" value="登录" />
	    <input type="reset" name="reset" value="清除" />
    </li>
    </form>
    </ul>
</div>
<script language="JavaScript">
/**
 * 检查表单输入的内容
 */
function validate() {
    var username = $('loginForm').user_name.value.trim();
    var password = $('loginForm').password.value.trim();
    var checkcode = $('loginForm').verifyCode.value.trim();
    if (username == '') {
	    alert('用户名不能为空');
	    $('loginForm').user_name.focus();
	    return false;
    }
    if(password==''){
	    alert('密码不能为空');
	    $('loginForm').password.focus();
	    return false;
    }
    if(checkcode==''){
	    alert('验证码不能为空');
	    $('loginForm').verifyCode.focus();
	    return false;
    }
    return true;
}
</script>