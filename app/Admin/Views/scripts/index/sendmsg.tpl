<form id="myform" name="myform" action="{{url param.action=sendmsg}}"  method="post"  onsubmit="return checkinput()" target="ifrmSubmit">
<table width="100%" height="143" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="25%" height="35">　　手机号码：</td>
    <td width="75%">
      <label>
        <input name="mobile"  id="mobile" type="text" size="18" maxlength="15" />
        </label>
      </td>
  </tr>
  <tr>
    <td height="61">　　短信内容：</td>
    <td><label>
      <textarea name="msg"  id="msg" cols="35" rows="3" onclick="emp(this)">请输入60字以内的短信内容</textarea>
    </label></td>
  </tr>
  <tr >
    <td height="30" colspan="2" ><br /><label> 　　　　　　　　　　　  
        <input type="submit" name="Submit" value="发送短信" />
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
	   if(document.getElementById('mobile').value==""||document.getElementById('mobile').value==null)
		{
			  alert("手机号码没有填写！");
			  return false;
		}
	   if(document.getElementById('msg').value==""||document.getElementById('msg').value==null)
		{
			  alert("短信内容没有填写！");
			  return false;
		}		
		
	  var reg=/^[0-9]+$/;
	  var mobile=document.getElementById('mobile').value;
	  if(!reg.test(mobile))
	  {
			alert("手机号码只能填写数字！");
			return false;
	  }	
	  
	var  msg=document.getElementById('msg').value
	if(msg.length>280){
		alert("发送短信字数不能超过280！");
		return false;
	}
 	return true;
}

</script>