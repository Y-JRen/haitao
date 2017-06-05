//搜索
function chkValue(form){
	var val=form.keywords.value.replace(/^\s+|\s+$/g,"");
	if(val=='' || val=='请输入关键词'){
		alert("请输入您要搜索的关键词！");
		form.keywords.value="";
		form.keywords.focus();
		return false;
	}
	else{
		return true;
	}
}

//设置为首页
function setHomepage(url)
{
	if (document.all)
	{
		document.body.style.behavior='url(#default#homepage)';
		document.body.setHomePage(url);
	}
	else if (window.sidebar)
	{
		if(window.netscape)
		{
			try
			{
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}
			catch(e)
			{
				alert("this action was aviod by your browser，if you want to enable，please enter about:config in your address line,and change the value of signed.applets.codebase_principal_support to true");
			}
		}
		var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
		prefs.setCharPref('browser.startup.homepage',url);
	}
}

var index_tuan = 0;
var adTimer_tuan=null;
var len_tuan=0;
var index = 0;
var adTimer=null;
var len=0;
var menu_timer=null;
var on_object=null
var on1_object=null
//页面初始化加载
$(document).ready(function(){
	
	//导航条收缩
	var intervalCatMenu = null;
	$(".mallCategory").mouseenter(function(){
		clearInterval(intervalCatMenu);
		$("#cat-menu").show();
	});
	
	$(".mallCategory").mouseleave(function(){
		intervalCatMenu = setInterval(function(){
			$("#cat-menu").hide();
		},50);		
	});

	//导航条类别
	 var intervalItem = null;
	 $(".sort .item").mouseenter(function(){
	 	clearInterval(intervalItem);
		$(".sortLayer").hide();
		$(this).addClass("itemCur");
		$(this).next().show();
	 }).mouseleave(function(){
		 clearInterval(intervalItem);
		 var $item = $(this);
		 $(this).removeClass('itemCur');
		 intervalItem = setInterval(function(){
			 $item.next().hide();
		 },50);
	 });


	$(".sortLayer").mouseleave(function(){
		clearInterval(intervalItem);
		$(this).hide();
		$(this).prev().removeClass('itemCur');
	}).mouseenter(function(){
		clearInterval(intervalItem);
		$(this).prev().addClass('itemCur');
	});
	 

	//图片轮换
	 len = $(".slideadv .adv").length;
	 $(".num ul li").mouseover(function(){
		index  =   $(".num ul li").index(this);
		showImg(index);
		clearInterval(adTimer);
		adTimer=null;
	 });
	 $(".num ul li").mouseout(function(){
		go();
	 })
	 // 图片轮播---滑入 停止动画，滑出开始动画.
	 $('.slideadv a').hover(function(){
			 clearInterval(adTimer);
			 adTimer=null;
		 },function(){
			 go();
	 });
	 go();

	/*购物车显示控制*/
	 $(".mycart").mouseenter(function(){
			$(".mycart").attr("class","mycart hover");
			$(".cartLayer").show();
	 });
	  $(".mycart").mouseleave(function(){
			$(".cartLayer").hide();
			$(".mycart").attr("class","mycart");
	 });
	   
	 //异步登陆
	$("#syncSub").click(function(){
			var flag = $("#valNO").val();
			if(flag == ""){
				syncLogin("buy");
			}else{
				window.location.href="settlement.html";
			}
	});
	$("#msg_close").click(function(){
		$("#xj_msg_info").hide();
	});
	$(".closeDiv").click(function(){
		$("#xj_login").hide();
	});
	
});
//调用首焦图自动轮播
function go(){
	if(adTimer==null){
		adTimer = setInterval(function(){
			  showImg(index);
			   index++;
			if(index==len){index=0;}
			  } , 2000);
	}
}




/*图片延时加载end*/


//轮播，来显示不同的幻灯片
function showImg(index_on){
		$(".slideadv .adv").each(function(ind){
			if(ind==index_on){
				var tmp_num_on = ".num ul li:eq("+index_on+")";
				$(this).show();
				$(tmp_num_on).show();
				$(tmp_num_on).attr("class","cur");
			}else{
				var tmp_banner = ".slideadv .adv:eq("+ind+")";
				var tmp_num = ".num ul li:eq("+ind+")";
				$(tmp_banner).hide();
				$(tmp_num).removeClass("cur");
			}
		});
}

//jquery倒计时插件
$.fn.YMCountDown = function(msg,opt){
	var $this = $(this);
	$this.time = $this.attr('time')*1;
	$this.interval = null;
	if(opt != null){
		$this.day = $this.find('#'+opt[0]);
		$this.hour = $this.find('#'+opt[1]);
		$this.minute = $this.find('#'+opt[2]);
		$this.second = $this.find('#'+opt[3]);
	}
	$this.countDow = function(){
		var untime = $this.time;
		if( untime >= 0 ){
			if(untime == 0){
				window.location.reload();
			}
			var units =	new Array(
				86400,0,   // seconds in a day    (24 hours)
		  		3600,0,  // seconds in an hour  (60 minutes)
		   		60,0,     // seconds in a minute (60 seconds)
		   		1,0       // 1 second
			);
			for(var i = 0; i< units.length; i+=2){
				units[i+1] = Math.floor( untime / units[i]);
				untime = untime - units[i+1] * units[i];
			}
			if(opt == null){
				$this.html('<p>还剩</p><b>'+units[1]+'</b><em>天</em><b>'+(units[3]<10?'0'+units[3]:units[3])+'</b><em>时</em><b >'+(units[5]<10?'0'+units[5]:units[5])+'</b><em>分</em><b>'+(units[7]<10?'0'+units[7]:units[7])+'</b><em>秒</em>');
			}else{
				$this.day.text(units[1]);
				$this.hour.text(units[3]<10?'0'+units[3]:units[3]);
				$this.minute.text(units[5]<10?'0'+units[5]:units[5]);
				$this.second.text(units[7]<10?'0'+units[7]:units[7]);
			}
		}
	}
	$this.countDow($this,$this.time--)				//第一次显示
	$this.autoChange = function(){								//自动运行方法
		 $this.interval = setInterval(function(){
			$this.countDow($this,$this.time--)
		},1000);
	}
	$this.autoChange();								//开启自动运行
};

/*jquery操作cookie插件*/
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') {
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	   } else {
	       var cookieValue = null;
	       if (document.cookie && document.cookie != '') {
	           var cookies = document.cookie.split(';');
	           for (var i = 0; i < cookies.length; i++) {
	               var cookie = jQuery.trim(cookies[i]);
	               if (cookie.substring(0, name.length + 1) == (name + '=')) {
	                   cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
	                   break;
	               }
	           }
	       }
	       return cookieValue;
	   }
	};
//异步登陆函数
function syncLogin(type){
		var logname =$.trim($("#loginId").val());
		var password =$.trim($("#password").val());
		var num =$.trim($("#num").val());
		if(logname == ""){
			$("#error").html("<span style='color:red;'>登陆账号信息不能为空！</span>");
			$("#error").show();
			return false;
		}
		if(!jkvalidate("password","#password")){
			$("#error").html("<span style='color:red;'>密码格式或长度不正确，请重新填写！</span>");
			$("#error").show();
			return false;
		}
		if(!jkvalidate("valnum","#num")){
				$("#error").html("<span style='color:red;'>验证码为四位字母数字组合！</span>");
				$("#error").show();
				return false;
		}
		var domain=$("#Web_Name_Url").val();
		if(domain!="" && domain!=null && domain!="undefined" ){
			domain=domain+"/";
		}else{
			domain="";
		}
		$.ajax({type:"POST",url:domain+"sync_login.html",data:"num="+num+"&loginId="+logname+"&password="+password,
				success: function(data){
					if("success" == data){
						 $("#valNO").val(100);
						 $("#xj_login").hide();
						 $("#m_content").html("恭喜您，您已登陆成功，请继续操作！");
						 $("#xj_msg_info").fadeTo(1500,1,function(){
						 $("#xj_msg_info").fadeOut("slow");
						});
					}else{
						$("#error").html(data);
						$("#error").show();
						}
					return fasle;
  				 },
  				 error:function(msg){
					$("#error").html("数据交互异常！");
					$("#error").show();
					return fasle;
  				 }
			});
}

//自定义验证函数 type:邮箱、手机、密码、验证码  id:验证字段的id 带#号
function jkvalidate(type,id){
	var value = $(id).val();
	if(value == "") return false;
	if("email" == type){
		var res = value.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/);
		if(res == null){
			return false;
		}
	}else if("mobilePhone" == type){
		 if($(id).val().match(/^1[3|4|5|8][0-9]\d{8,8}$/) == null){
		 	return false;
		 }
	}else if("phone" == type){
		 if($(id).val().match(/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/) == null){
		 	return false;
		 }
	}else if("zip" == type){
		 if($(id).val().match(/^[1-9]\d{5}$/) == null){
		 	return false;
		 }
	}else if("password" == type){
		if(value.length < 6 || value.length > 20){
			return false;
		}
	 	var regex = /^[^_][A-Za-z]*[a-z0-9_]*$/ ;
	 	 if(!regex.test(value)){
	 	 	return false;
	 	 }
	}else if("valnum" == type){
		if(value.length != 4){
		 	return false;
		 }
	}
	return true;
}

//提示信息
function zt_message(msg){
	$("#m_content").html(msg);
	$("#xj_msg_info").fadeTo(1000,1,function(){
	$("#xj_msg_info").fadeOut("slow");
	});
}
function delShopCart(id,url){
	var priceQuantity=$("#cartPrice_"+id).html().replace("￥","").replace("元","");
	var priceQuantityArr=priceQuantity.split("×");
	var price=parseFloat(priceQuantityArr[0]);
	$.ajax({
		type: "GET",
		cache:false,
		url: url,
		success: function(){
			var totalQuantity=parseInt($("#cartQuantity").html())-1;
			if(totalQuantity<=0){
				$("#mycart-listbox").html('<div style="text-align: center;height:20px;line-height:20px;margin-bottom:10px;" class="priceColor">购物车中没有购买的产品！</div>');
			}
			else{
				var totalPrice=parseFloat($("#cartPrice").html())-price;
				$("#cartGoods_"+id).remove();
				$("#cartQuantity").html(totalQuantity);
				$("#cartPrice").html(totalPrice);

			}
			$("#showTotalQuantity").html(" "+totalQuantity+" ");

		}
	});
}
function addGoodsToCart(url){
	window.location.href=url+"-num"+$("#buyQuantity").val()+".html";
}