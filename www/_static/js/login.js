/**用户登录*/
function  userLogin() {
	var username = $('#user_name').val();
	var password = $('#pwd').val();
	if(username == '')
	{
		$('#user_name').focus(); return false;
	}
	if(password == '')
	{
		$('#pwd').focus(); return false;
	}
	if($("#vcode").val()=='')
	{
	  $('#vcode').focus(); return false;
	}
	//异步提交表单
	var params = $("#loginForm").serializeArray();
	$.post($("#loginForm").attr('action'),params,
			function(data)
			{
		       if(data.status==1)
		    	  {
		    	    $("#msg_box").show().html(data.msg);
		    	    location.href=data.url;
		    	  }else{
		    	    change_verify('verify_img','shopLogin');
		    	    $("#msg_box").show().html(data.msg); 
		    	  }
	   },'json');	
	return false;
}


/**新加邮箱注册加提示*/
function gogo(a) {
	close_suggest();
	$("#user_name").val(a).focus();		
	return false;

}

function close_suggest() {
	$("#result").css({
		display : "none",
		border : "0px "
	}).empty();
}

$("#layer").click(function() {
	close_suggest();
});

var i = -1, f = false;
/**用户注册提示*/
function userNameSuggest(ob,e)
{
	var res = ['@qq.com', '@126.com', '@163.com', '@sina.com', '@sohu.com', '@hotmail.com', '@yahoo.com', '@live.com'];
    var evt = e || window.e;

	$(ob).val($(ob).val().toLowerCase());
	
	$("#result").show().addClass("down");
	
	var ss = "", tx = "<li title='" + $(ob).val() + "' onclick=\"gogo('" + $(ob).val() + "')\">" + $(ob).val() + "</li>";
	if (!/@/.test($(ob).val()) || /@&/.test($(ob).val())) {
		f = true;
	} else {
		f = false;
	}
	if (f) {
		for (var t in res) {
			ss += "<li title='" + $(ob).val() + res[t] + "' onclick=\"gogo('" + $(ob).val() + res[t] + "')\">" + $(ob).val() + res[t] + "</li>";
		}
	} else {
		var place = $(ob).val().indexOf('@');
		var sear = $(ob).val().substring(place);
		for (var t in res) {
			var txt = $(ob).val();
			if (!res[t].search(sear)) {
				ss += "<li title='" + txt.replace(sear, "") + res[t] + "' onclick=\"gogo('" +  txt.replace(sear, "") + res[t] + "')\">" + txt.replace(sear, "") + res[t] + "</li>";
			}
		}
	}
	$("#result").html("<p class='tt'><span>请选择邮箱的类型...</span><a href='javascript:void(0);' onclick='close_div()' title='关闭'>X</a></p>" + tx + ss);
	var len = $("#result li").length;

	if (len >= 1) {
		//alert(evt.keyCode);
		switch(evt.keyCode) {
			//向上是38，向下是40，回车是13
			case 38:
				$("#result li").eq(i).css({
					"background-color" : "#ffffff"
				});
				i--;
				if (i < 0) {
					i = len - 1;
				}
				$("#result li").eq(i).css({
					"background-color" : "#eee"
				});
				break;
			case 40:
				$("#result li").eq(i).css({
					"background-color" : "#ffffff"
				});
				i++;
				if (i > len - 1) {
					i = 0;
				}
				$("#result li").eq(i).css({
					"background-color" : "#eee"
				});
				break;
			case 13:
				$("#user_name").val($("#result li").eq(i).text());
				close_suggest();
				break;
			default:
				break;
		}
	}
}