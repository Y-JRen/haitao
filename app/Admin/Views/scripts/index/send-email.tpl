<form id="myform" name="myform" action="{{url param.action=send-email}}"  method="post"  onsubmit="return checkinput()" target="ifrmSubmit">
<table width="100%" height="143" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="25%" height="35">　　邮件地址：</td>
    <td width="75%">
      <label>
        <input name="email"  id="email" type="text" size="20" maxlength="35" />
        </label>
      </td>
  </tr>
  <tr>
    <td width="25%" height="35">　　邮件标题：</td>
    <td width="75%">
      <label>
        <input name="title"  id="title" type="text" size="45" maxlength="50" />
        </label>
      </td>
  </tr>
  <tr>
    <td height="61">　　邮件内容：</td>
    <td><label>
      <textarea name="emailmsg"  id="emailmsg" cols="45" rows="6" onclick="emp(this)">请输入邮件内容</textarea>
    </label></td>
  </tr>
  <tr >
    <td height="30" colspan="2" ><br /><label> 　　　　　　　　　　　  
        <input type="submit" name="Submit" value="发送邮件" />
 </label></td>
  </tr>
</table>
</form>
  

<script   language=javascript>   
function   emp(oInput){   
  if(oInput.eflag!=true){   
	  oInput.value="";   
	  oInput.eflag=true;   
  }   
}  
function checkinput(){
	   if(document.getElementById('email').value==""||document.getElementById('email').value==null)
		{
			  alert("邮件地址没有填写！");
			  return false;
		}
	   if(document.getElementById('emailmsg').value==""||document.getElementById('emailmsg').value==null)
		{
			  alert("要发送的邮件内容没有填写！");
			  return false;
		}	
		
	   var patrn= /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
	    var email=document.getElementById('email').value;
       if (!patrn.test(email)) {
	   		alert("Email邮件格式不正确 ！");
			return false;
       }	
			
	  var  msg=document.getElementById('emailmsg').value
	  if(msg.length<15){
			alert("发送短信字数不能小于15！");
			return false;
	  }
 	  return true;
}

</script>