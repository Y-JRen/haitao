<div class="memberCenter">
    {{include file="member/menu.tpl"}}
      <div class="mcContentRight">
      <form action="" method="Post" id="myForm" name="myForm">
        <ul class="revisePassword">
         {{if !$member.setPwd}}
         <li>
            <div class="first"><span>*</span>原登录密码：</div>
            <div class="second"><input type="password" name="old_password"/></div>
            <div class="third"></div>
            <br class="clearfix"/>
          </li>
		{{/if}}
          <li>
            <div class="first"><span>*</span>新密码：</div>
            <div class="second"><input type="password" name="password"/></div>
            <div class="third">6-20位字母、数字或字符组合，不建议使用纯数字或纯字母的简单密码</div>
            <br class="clearfix"/>
          </li>
          <li>
            <div class="first"><span>*</span>再输入一次新密码：</div>
            <div class="second"><input type="password" name="confirm_password" /></div>
            <div class="third"></div>
            <br class="clearfix"/>
          </li>
          <li>
            <div class="first"></div>
            <div class="seconds"><a href="#"><img src="{{$imgBaseUr}}/public/images/nextBtn.png" onclick="return passwordSubmit()"></a></div>
          </li>
        </ul>
        </form>
      </div>
      <br class="clearfix"/>
    </div>
  </div>

<script>
function passwordSubmit(){
	{{if !$member.setPwd}}
	if($.trim($("#myForm input[name='old_password']").val())==''){
		alert('请输入原密码!');return false;
	} 
	{{/if}}
	if($.trim($("#myForm input[name='password']").val())=='' || !/^[a-zA-Z0-9]{6,20}$/.test($.trim($("#myForm input[name='password']").val())) ){
		alert('密码必须为6-20位的字母和数字的组合!');return false;
	}
	if($.trim($("#myForm input[name='password']").val())!=$.trim($("#myForm input[name='confirm_password']").val())){
		alert('新密码和确认密码不一致!');return false;
	}
	document.myForm.submit();
	//$('#dosubmit').attr('value','提交中..');
	//$('#dosubmit').attr('disabled',true);
	return true;
}
</script>