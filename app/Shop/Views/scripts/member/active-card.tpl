<div class="member">
{{include file="member/menu.tpl"}}
  <div class="memberright">	
	  <div class="mycard">
    	<div class="title"><i></i><h2>我的健康卡</h2></div>
        <div class="menu">
        	<ul>            	
     	<li > <a href="/member/gift-card">我的健康卡</a> </li>       
      <li>  <a href="/member/gift-card-log" >健康卡消费明细</a> </li>    
        <li> <a href="/member/gift-buy" > 我买的健康卡</a>  </li>    
        <li class="current"> <a href="/member/active-card" >绑定健康卡</a> </li>    
            </ul>
        </div>
	
	
	<div class="state_band">
        	说明：<br>
1、健康卡可以绑定到自己的账号上，也可以绑定到他人的账号上，一旦绑定不能解绑，只能由绑定者使用，请小心操作。<br>
2、也可以在购物结算时使用健康卡余额支付，健康卡将默认与首次使用卡余额支付的用户进行绑定。<br>
3、一旦绑定，在结算时，将无需再次输入卡号和密码可直接用卡余额支付，直至卡内余额为0。
        </div>
	
	
	
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="card_band">
          <tbody><tr>
            <th>健康卡自己使用</th>
            <th>健康卡送给他人</th>
          </tr>
          <tr>
            <td>输入卡号密码，健康卡绑定到自己的账号上。</td>
            <td>健康卡如要送给他人，输入接收人的账号、卡号、密码绑定即可。</td>
          </tr>
          <tr>
            <td><input type="text"  class="txt_card" value="输入卡号" id="card_sn" name="card_sn"></td>
            <td><input type="text"  class="txt_account" value="接收人1健康账号" id="give_account" name="give_account"></td>
          </tr>
          <tr>
            <td><input type="text" class="txt_pwd" value="输入密码" id="card_pwd" name="card_pwd"></td>
            <td><input type="text"  class="txt_card" value="输入卡号" id="give_card_sn" name="give_card_sn"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="text" class="txt_pwd" value="输入密码" id="give_card_pwd" name="give_card_pwd"></td>
          </tr>
          <tr>
            <td><a href="javascript:;" onclick="bind_card('my')"><img width="62" height="23" src="{{$_static_}}/images/btn_band.jpg"></a> </td>            
            <td><a  href="javascript:;"  onclick="bind_card('give')"><img width="62" height="23" src="{{$_static_}}/images/btn_band.jpg"></a> </td>
          </tr>
        </tbody></table>            
  </div>
  
  
</div></div>
<script>
$('#card_sn').iClear({enter: $(':submit')}); 
$('#give_card_sn').iClear({enter: $(':submit')}); 
$('#card_pwd').iClear({enter: $(':submit')}); 
$('#give_card_pwd').iClear({enter: $(':submit')}); 
$('#give_account').iClear({enter: $(':submit')}); 

function bind_card(type)
{
	 var  params = {};
	if(type == 'my')
	{
		if ($('#card_sn').val() == '' || $.trim($('#card_sn').val()) == '输入卡号' ) {
	        alert('请输入卡号');
	        $('#card_sn').focus();
	        return false;
	    }
	    if ($('#card_pwd').val() == '' || $.trim($('#card_pwd').val()) == '输入密码' ) {
	        alert('请输入输入密码');
	        $('#card_pwd').focus();
	        return false;
	    }	    
	    params = {card_sn : $('#card_sn').val(),card_pwd : $('#card_pwd').val()};	   
	}else{
		if ($('#give_account').val() == '' || $.trim($('#give_account').val()) == '接收人1健康账号' ) {
	        alert('请输入接收人1健康账号');
	        $('#give_account').focus();
	        return false;
	    }
		if ($('#give_card_sn').val() == '' || $.trim($('#give_card_sn').val()) == '输入卡号' ) {
	        alert('请输入卡号');
	        $('#give_card_sn').focus();
	        return false;
	    }
	    if ($('#give_card_pwd').val() == '' || $.trim($('#give_card_pwd').val()) == '输入密码' ) {
	        alert('请输入输入密码');
	        $('#give_card_pwd').focus();
	        return false;
	    }	    
	    params = {card_sn : $('#give_card_sn').val(),card_pwd : $('#give_card_pwd').val(),'give_account':$('#give_account').val()};	
	}
    
    
    $.ajax({
        url : '/member/active-card',
        data : params,
		type : 'post',
		success : function(msg) {
            if (msg == 'ok') {
                alert('绑定成功!');
                if(type == 'my')
            	{
                	location.href='/member/gift-card/';
            	}else{
            		location.href='/member/gift-buy/';	
            	}
            }else if (msg == 'fail') {
                alert('绑定失败!');
            }
            else if (msg == 'card not exists') {
                alert('券号或密码错误!');
            }
            else if (msg == 'card binded') {
                alert('券已被绑定!');
            }
            else if (msg == 'card expired') {
                alert('该券已过期!');
            }else if(msg == 'user not exists')
            {
            	 alert('指定用户不存在!');
            }
		}
	});
}
</script>