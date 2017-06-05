<?php /* Smarty version 2.6.19, created on 2014-12-15 10:15:31
         compiled from member/profile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'member/profile.tpl', 24, false),array('function', 'html_select_date', 'member/profile.tpl', 32, false),)), $this); ?>
<div class="memberCenter">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <div class="mcContentRight">
        <form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post"  target="ifrmSubmit">
        <div class="dataLeft">
          <div class="data_tx"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/data_tx.jpg"/></div>
          
        </div>
        <ul class="dataRight">
          <li>
            <div class="dataMsg"><span>* </span>姓名：</div>
            <div class="dataText"><input type="text" name="real_name" value="<?php echo $this->_tpl_vars['member']['real_name']; ?>
"/></div>
            <br class="clearfix"/>
          </li>
          <li>
            <div class="dataMsg">昵称：</div>
            <div class="dataText disabledText"><input type="text" name="nick_name" value="<?php echo $this->_tpl_vars['member']['nick_name']; ?>
" readonly="readonly"/></div>
            <p class="dataTip">不可修改</p>
            <br class="clearfix"/>
          </li>
          <li>
            <div class="dataMsg">性别：</div>
            <div class="sexSelect">
              <?php echo smarty_function_html_radios(array('name' => 'sex','options' => $this->_tpl_vars['sexRadios'],'checked' => $this->_tpl_vars['member']['sex'],'separator' => ""), $this);?>

              <br class="clearfix"/>
            </div>
            <br class="clearfix"/>
          </li>
          <li>
            <div class="dataMsg">生日：</div>
            <div class="selectBox">
              <?php if ($this->_tpl_vars['birthdayAble']): ?>  <?php echo smarty_function_html_select_date(array('field_array' => 'birthday','time' => "0000-00-00",'month_format' => "%m",'field_order' => 'YMD','start_year' => -70,'reverse_years' => true,'year_empty' => "请选择",'month_empty' => "请选择",'day_empty' => "请选择"), $this);?>
  <?php else: ?> <?php echo $this->_tpl_vars['member']['birthday']; ?>
 <?php endif; ?>
            </div>
            <br class="clearfix"/>
          </li>
          <li>
            <div class="dataMsg">身份证号：</div>
            <div class="dataText"><input type="text" name="id_card" value="<?php echo $this->_tpl_vars['member']['id_card']; ?>
" maxlength="18"/></div>
            <br class="clearfix"/>
          </li>
           <li>
            <div class="dataMsg">护照号：</div>
            <div class="dataText"><input type="text" name="passport_number" value="<?php echo $this->_tpl_vars['member']['passport_number']; ?>
" maxlength="10"/></div>
            <br class="clearfix"/>
          </li>
          <li>
            <div class="dataMsg">办公电话：</div>
            <div class="dataText">
              <input type="text" name="office_phone" size="20" maxlength="40" value="<?php echo $this->_tpl_vars['member']['office_phone']; ?>
" />
            </div>
            <br class="clearfix"/>
          </li>
          <li>
            <div class="dataMsg">住宅电话：</div>
            <div class="dataText">
              <input type="text" name="home_phone" size="20" maxlength="40" value="<?php echo $this->_tpl_vars['member']['home_phone']; ?>
" />
            </div>
            <br class="clearfix"/>
          </li>
          <!--  
          <li>
            <div class="dataMsg"><span>* </span>手机：</div>
            <div class="dataText"><input type="text" value="<?php echo $this->_tpl_vars['member']['mobile']; ?>
" maxlength="11"/></div>
            
            <p class="dataTip">未验证</p>
            <div class="getCode"><a href="#">获取手机验证码</a></div>
            <br class="clearfix"/>
          </li>
         -->
           <li>
            <div class="dataMsg"><span>* </span>手机：</div>
            <div class="dataText"><input name="mobile" type="text" value="<?php echo $this->_tpl_vars['member']['mobile']; ?>
" maxlength="11"/></div>
            <br class="clearfix"/>
          </li>
        
          <li>
            <div class="dataMsg"><span>* </span>E-mail：</div>
            <div class="dataText"><input type="text" name="email" value="<?php echo $this->_tpl_vars['member']['email']; ?>
"/></div>
            <!--  
            <p class="dataTip">未验证</p>
            <div class="getCode"><a href="#">发送验证邮件</a></div>
            -->
            <br class="clearfix"/>
          </li>
          <li class="baocunBtn">
            <div class="dataMsg"></div>
            <div class="dataText"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/baocun_btn.png" onclick="return profileSubmit()"></div>
            <p class="dataTip" id="dataTip" style='display:none;'>资料保存成功！</p>
          </li>
          <br class="clearfix"/>
        </ul>
        </form>
      </div>
      <br class="clearfix"/>
     
    </div>
  </div>

<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['_static_']; ?>
/scripts/check.js"></script>
<script>
var tempNickName = '';

function profileSubmit(){
	var msg = '';
	if($.trim($("#myForm input[name='email']").val())!='' && !Check.isEmail($.trim($("#myForm input[name='email']").val())) ){
		msg += '请输入正确的Email地址!\n';
	}
	if($.trim($("#myForm input[name='mobile']").val())!='' && !Check.isMobile($.trim($("#myForm input[name='mobile']").val())) ){
		msg += '请输入正确的手机号码!\n';
	}
	if($.trim($("#myForm input[name='msn']").val())!='' && !Check.isEmail($.trim($("#myForm input[name='msn']").val())) ){
		msg += '请输入正确的MSN!\n';
	}
	if($.trim($("#myForm input[name='qq']").val())!='' && !Check.isQq($.trim($("#myForm input[name='qq']").val())) ){
		msg += '请输入正确的QQ号码!\n';
	}
	if($.trim($("#myForm input[name='office_phone']").val())!='' && !Check.isTel($.trim($("#myForm input[name='office_phone']").val())) ){
		msg += '请输入正确的办公室电话!\n';
	}
	if($.trim($("#myForm input[name='home_phone']").val())!='' && !Check.isTel($.trim($("#myForm input[name='home_phone']").val())) ){
		msg += '请输入正确的住宅电话!\n';
	}
	if (msg.length > 0) {
        alert(msg);
        return false;
    } else {
        $('#dataTip').attr('value','提交中..');
		$('#dataTip').attr('disabled',true);
		document.myForm.submit();
		return true;
    }
}

//检测用户昵称是否被占用
function checkNickName(nickname,bak_nickname){
	var nickname = $.trim(nickname);
    var bak_nickname = $.trim(bak_nickname);
	if (nickname != '' && nickname != bak_nickname && nickname != tempNickName){
		$.ajax({
			url:'/auth/check',
			data:{nick_name:nickname},
			success:function(msg){
				if(msg=='ok'){
					$('#nick_name_notice').html('&nbsp;<font color="green">可以使用!</font>');
					$('#dosubmit').attr('disabled',false);
				}else{
					$('#nick_name_notice').html('&nbsp;<font color="red">已经被使用!</font>');
					$('#dosubmit').attr('disabled',true);
				}
				tempNickName = nickname;
			}
		})
	}else if(nickname == bak_nickname){
		resetDom();
	}
}
function verify_email()
{
	var email =  $.trim($("#myForm input[name='email']").val());
	if(email!='' && !Check.isEmail(email) ){
		alert('请输入正确的Email地址!');
		return false;
	}
	
	$.post("/member/verifyemail/email/"+email,
			 function(data) {
			   alert(data.msg);
			 }, 
		    "json"
	);
}
//数据复位
function resetDom(){
	$('#nick_name_notice').html('');
	$('#dosubmit').attr('disabled',false);
}

function checkIdCard()
{
	
}
</script>