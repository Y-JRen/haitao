//流程检查
function checkOrderStep(){
	$.ajax({
		url:'/flow/check-order-step',
		type:'post',
		dataType:'json',
		success:function(data){
		  if(data.address){
			  $("#payment_edit_action").html('<a href="javascript:;" onclick="editPayment();">修改</a>');
		      $("#invoice_edit_action").html('<a href="javascript:;" onclick="editDelivery();">修改</a>');
		  }else{
			  $("#payment_edit_action").html("<font>"+data.msg+"</font>");
		      $("#invoice_edit_action").html("<font>"+data.msg+"</font>");  
		  }
		  if(data.payment){
			  $("#consignee_edit_action").html('<a onclick="editAddressInfo(3410,\'show\');" href="javascript:;">修改</a>');
		      $("#invoice_edit_action").html('<a href="javascript:;" onclick="editDelivery();">修改</a>');   
		  }else{
			  $("#consignee_edit_action").html("<font>"+data.msg+"</font>");
		      $("#invoice_edit_action").html("<font>"+data.msg+"</font>");  
		  } 
		  
		  if(data.delivery){
			  $("#consignee_edit_action").html('<a onclick="editAddressInfo(3410,\'show\');" href="javascript:;">修改</a>');
			  $("#payment_edit_action").html('<a href="javascript:;" onclick="editPayment();">修改</a>');
		  }else{
			  $("#consignee_edit_action").html("<font>"+data.msg+"</font>");
			  $("#payment_edit_action").html("<font>"+data.msg+"</font>");  
		  } 		 
		}
	})
}


/**
 * 编辑添加 收货地址
 */
function editAddressInfo(aid,type,tpl)
 {
        var url = '/flow/addr';
        aid = aid?aid:0;
		url+='/addr_id/'+aid;
		
		type = type?type:'edit';
		url+='/type/'+type;	
		
	    tpl = tpl?tpl:'addr';
	    url+='/tpl/'+tpl;
	    
	    $.post(url,function(data){
		    if(data.status == 1)
		    {    	    	
		       $("#address_box").html(data.html);
		       if(tpl == 'addr'){
		    	  $("#payment_edit_action").html("<font>如需修改，请先保存收货人信息</font>");
			      $("#invoice_edit_action").html("<font>如需修改，请先保存收货人信息</font>"); 
		       }		       
		    }else{
		    	alert(data.msg);
		    	location.href= data.url;
		    }
		},'json');
 }

/**
 * 删除收货地址
 */
function delAdress(aid)
{
      if(!aid)
      {
    	alert("参数错误！");
    	return false;
      }
      
      if(confirm("你确定要删除改地址吗？"))
      {
    	  $.post('/flow/del-addr/addr_id/'+aid,function(data){
    		 if(data.status=1)
    		{
    		  if($("#add_item_"+aid).hasClass('active')){
    		    $("#address_id").val(0); 
    		  } 
    		 $("#add_item_"+aid).remove(); 
    		}else{
    		  alert(data.msg);
    		}		  
    	  },'json');  
      }
}

/**
 * 设置收货地址
 */
 function setAddress(aid,tpl)
 {
    	 if(!aid)
    	  {
    		alert("参数错误！");
    		return false;
    	 }	  
    	 
    	 tpl = tpl?tpl:'addr';
    	 
    	 $.post('/flow/set-addr/addr_id/'+aid,function(data){
    		 if(data.status=1)
    		 {
    			 if(tpl == 'addr'){
    				  $("#address_box").html(data.tips);	
    	    		  checkOrderStep();
    			 }    		 
    		 }else{
    		   alert(data.msg);
    		 }		  
    	  },'json');  
}

//添加或编辑 收货地址 
function check_post_address(tpl){	 
	tpl = tpl?tpl:'addr';
	if($.trim($('#consignee').val())==''){
		alert('请填写真实姓名！');
		$('#consignee').focus();
		return false;
	}	
	var province=$.trim($('#province').val());
	if(province=='' || /\D+/.test(province)){
		alert('请选择省份！');
		$('#province').focus();
		return false;
	}
	
	var city=$.trim($('#city').val());
	if(city=='' || /\D+/.test(city)){
		alert('请选择城市！');
		$('#city').focus();
		return false;
	}
	
	var area=$.trim($('#area').val());
	if(area=='' || /\D+/.test(area)){
	    if (area!=-1) {
    		alert('请选择地区！');
    		$('#area').focus();
    		return false;
        }
	}

	if($.trim($('#address').val())==''){
		alert('请填写详细地址！');
		$('#address').focus();
		return false;
	}
	
	if( $.trim($('#phone').val())==''  && $.trim($('#mobile').val())==''){
		alert('请填写电话号码或手机！');
		$('#mobile').focus();
		return false;
	}else{
		var phone = $.trim($('#phone_code').val())+'-'+$.trim($('#phone').val());
	    if($.trim($('#phone_ext').val())) phone=phone+'-'+$.trim($('#phone_ext').val()); 
	    
		if ($.trim($('#phone').val()) != '' && !Check.isTel(phone) ) {
			alert('请填写正确的电话号码！');
			$('#phone').focus();
			return false;
		}
		
		if ($.trim($('#mobile').val()) != '' && !Check.isMobile($.trim($('#mobile').val()))) {
			alert('请填写正确的手机号码！');
			$('#mobile').focus();
			return false;
		}
  }
	
	//异步提交表单
	var params = $("#addressFrom").serializeArray();
	$.post($("#addressFrom").attr('action'),params,
				function(data)
				{
			       if(data.status==1)
			    	  {		    	 
			    	    $("#address_box").html(data.tips);
			    	    if(tpl == 'addr')
			    	    {
			    	    	checkOrderStep();
			    	    }			    	   
			    	  }else{
			    		 alert(data.msg);
			    	  }
		   },'json');	
		 return false;
}

//地区选择
function getArea(id, flag){
    	var value=id.value;
    	var uri=filterUrl('/flow/list-area-by-json','area_id');
    	$(id).parent().children('select:last')[0].options.length = 1;
    	$(id).next('select')[0].options.length=1;
    	$.ajax({
    		url:uri,
    		data:{area_id:value},
    		dataType:'json',
    		success:function(msg){
    			var htmloption='';
    			$.each(msg,function(key,val){
    				htmloption+='<option value="'+val['area_id']+'" class="'+val['code']+'" title="'+val['code']+'">'+val['area_name']+'</option>';
    			})
    			if (flag == 1) {
    			    htmloption+='<option value="-1" class="" title="">其它区</option>';
    			}
    			$(id).next('select').append(htmloption);
    		}
    	})
}
    
    
//编辑支付方式    
function editPayment()
    {
        $.post('/flow/payment',function(data){
    		    if(data.status == 1)
    		    {    		    	
    		    	$("#payment_box").addClass('current').html(data.html);
    		    	$("#consignee_edit_action").html("<font>如需修改，请先保存支付方式</font>");
    			    $("#invoice_edit_action").html("<font>如需修改，请先保存支付方式</font>");
    		    }else{
    		    	alert(data.msg);
    		    	location.href= data.url;
    		    }
    	},'json');
}

//选择支付方式
function setPayment() {
    var pay_type =  $("#pay_type").val();
	if(!pay_type)
	{
	  alert('请选择支付方式');
	  return false;		
	}		
	$.post('/flow/set-payment/',{'pay_type':pay_type},function(data){
		       if(data.status==1)
		    	  {		    	   
		    	    $("#payment_box").html(data.tips).removeClass('current');
		    	    checkOrderStep();
		    	  }else{
		    	    alert(data.msg);
		       }
	 },'json');		
	return true;	
}  

//设置配送和发票
function setDelivery()
{
  var invoice_type = $(":radio[name='invoice_type'][checked]").val();
  var invoice_content = $(":radio[name='invoice_content'][checked]").length;
  
  if(invoice_type == 1)
  {
    if($("#invoice_person").val() == '' || $("#invoice_person").val() == '姓名' )
	{
    	  alert("请输入姓名");
    	  $("#invoice_person").focus();
		   return false;
	}
    if(invoice_content == 0)
    {
    	 alert("请选择发票内容");
    	 return false;
    }
    
  }else if(invoice_type == 2){	  
	 if($("#invoice_company").val() == '' || $("#invoice_company").val() == '输入单位全称' )
	 {
		   alert("请输入单位全称");
		   $("#invoice_company").focus();
		   return false;
	 }	 
	 if(invoice_content == 0)
	   {
	    	 alert("请选择发票内容");
	    	 return false;
	   }
  }

     //异步提交表单
	var params = $("#deliveryFrom").serializeArray();
	$.post($("#deliveryFrom").attr('action'),params,
			function(data)
			{
		       if(data.status==1)
		    	  {
		    	    $("#delivery_box").removeClass('current').html(data.tips);
		    	    checkOrderStep();
		    	  }else{
		    	    alert(data.msg);
		    	  }
	   },'json');		
	return true;	  
}

function editDelivery()
{
    $.post('/flow/delivery',function(data){
		 if(data.status == 1)
		    {    		    	
		    	$("#delivery_box").addClass('current').html(data.html);
		    	$("#consignee_edit_action").html("<font>如需修改，请先保存配送与发票信息</font>");
			    $("#payment_edit_action").html("<font>如需修改，请先保存配送与发票信息</font>");
		    }else{
		    	alert(data.msg);
		    	location.href= data.url;
		    }
	},'json');
}

/**
 * 
 * 发送验证短信
 */
function  sendSmsCode(){	
	  var  mobile=$("#v_mobile").val();
	  if(mobile == '')
		{
		   alert('请输入手机号');
		   return false;  
		}
	  
	  if( !Check.isMobile($.trim(mobile)) ){
		   alert('手机号格式不正确');
		   return false;
	  }
	  if($("#btnSend").attr('process') == 'send') return false;
     
	  var url = '/auth/send-check-sms/?t='+Math.random();	
		$.ajax({
		type:'post',
		url:url,
		data:{'mobile':mobile},
		timeout:1000*30,
		async : false,	    		
		beforeSend:function(){	
			 $("#btnSend").attr('process','send');
			 $("#btnSend").css('color','#9FA0A0').html("正在发送短信……");	
		},
		dataType : 'json',
		success:function(data){	    		
			if (data.status == 1)
			{				  
			  countDown();
			}else{	    			
			   alert(data.msg);
			   if(data.reflash==1) window.location.reload();			    
			}	 	    		
		},
		error:function(){	
		  $('#btnSend').css('color','#0066FF').html("重新发送");  
		}
	});
}

var sec = 120 ;  
function countDown(){                                
   sec--;  
   $('#btnSend').css('color','#9FA0A0').html("验证码过期时间还剩"+sec+"秒");  
   if(sec==1)  
   {  
 	  $('#btnSend').css('color','#0066FF').html("未收到短信,点击重新发送");  
 	  $("#btnSend").attr('process','end');
 	  sec = 120;
 	  return false;
   }  
   window.setTimeout("countDown()",1000);             
}    


function checkMobile()
{
	  var  mobile=$("#v_mobile").val();
	  var mobile_code = $("#mobile_code").val();
	  if(mobile == '')
		{
		   $("#mobile_msg").show().html("<i>请输入手机号!</i>")
		   return false;  
	  }
	  
	  if( !Check.isMobile($.trim(mobile)) ){
		   $("#mobile_msg").show().html("<i>手机号格式不正确!</i>")
		   return false;
	  }
	  
	  if(mobile_code == '')
	  {
		  $("#mobile_msg").show().html("<i>请输入收到的短信验证码!</i>")
		   return false;  
	  }
	  
	  $("#mobile_msg").show().html("<i>正在验证中……</i>");
	  var url = '/flow/check-mobile/?t='+Math.random();	
	  $.post(url,{'mobile':mobile,'code':mobile_code},function(data)
				{
			       if(data.status==1)
			       {
			    	   $("#mobile_box").removeClass('current').html(data.tips);  
			    	   checkOrderStep();
			    	}else{
			    	   $("#mobile_msg").show().html(data.msg);
			       }
		  },'json');		
	  
}

function getMobile()
{
	  var url = '/flow/get-mobile/?t='+Math.random();	
	  $.post(url,function(data)
				{
			       if(data.status==1)
			       {
			    	   $("#mobile_box").removeClass('current').html(data.tips);  
			    	   checkOrderStep();
			    	}else{
			    	   $("#mobile_msg").show().html(data.msg);
			       }
		  },'json');		
}

function editMobile()
{
    $.post('/flow/edit-mobile',function(data){
		 if(data.status == 1)
		    {    		    	
		    	$("#mobile_box").addClass('current').html(data.html);
		    	$("#consignee_edit_action").html("<font>如需修改，请先保存手机信息</font>");
			    $("#payment_edit_action").html("<font>如需修改，请先保存手机信息</font>");
			    $("#invoice_edit_action").html("<font>如需修改，请先保存手机信息</font>");
		    }else{
		    	alert(data.msg);
		    	location.href= data.url;
		    }
	},'json');
}

//使用账户余额
function checkPriceAccount(){
	var price = parseFloat($('#price_account').val());
	if (isNaN(price)){price = 0;}

	if(price == 0)
	{
	  alert("请输入使用金额！");	
	  $('#price_account').focus();
	  return false;
	}
	
	
	$.ajax({
		url: '/flow/check-price-account/price_account/' + price,
		beforeSend:function(){
			$('#price_account_button').attr('disabled',true);
			$('#price_account_button').attr('value','处理中');
		},
		success:function(data){
			$('#price_account_button').attr('disabled',false);
			$('#price_account_button').attr('value','使用');
			if (data == '' || data == 'error') {
			   alert('系统繁忙,请稍候再试!');
			} else if(data == 'isNegative') {
			   alert('输入的金额请不要小于0!');
			} else if(data == 'bigThanAccount') {
			   alert('输入的金额不能大于可用余额!');
			} else if(data == 'bigThanPayed') {
			   alert('输入的金额不能大于需支付金额');
			} else {
			   window.location.reload();
			}
		},
		error:function(){
			alert('系统繁忙,请稍候再试!');
			$('#price_account_button').attr('disabled',false);
			$('#price_account_button').attr('value','使用');
		}
	})
}

//积分抵用
function checkPricePoint(){
	if($.trim($('#price_point').val())==''){return;}
	var price = parseFloat($('#price_point').val());
	if (isNaN(price)){price = 0;}

	$.ajax({
		url: '/flow/check-price-point/price_point/' + price,
		beforeSend:function(){
			$('#price_point_button').attr('disabled',true);
			$('#price_point_button').attr('value','处理中');
		},
		success:function(data){
			$('#price_point_button').attr('disabled',false);
			$('#price_point_button').attr('value','使用');
			if (data == '' || data == 'error') {
			   alert('系统繁忙,请稍候再试!');
			} else if(data == 'useCard'){
				alert('卡和积分不能同时使用');
			} else if(data == 'isNegative') {
			   alert('输入的金额请不要小于0!');
			} else if(data == 'bigThan500') {
			   alert('输入的积分不能小于'+minPointToPrice);
			} else if(data == 'divisionWith100') {
			   alert('输入的积分必须是100的整数倍');
			} else if(data == 'bigThanPoint') {
			   alert('输入的积分不能大于可用积分');
			} else if(data == 'bigThanPayed') {
			   alert('输入的金额不能大于需商品总金额');
			} else {
			  window.location.reload();
			}
		},
		error:function(){
			alert('系统繁忙,请稍候再试!');
			$('#price_point_button').attr('disabled',false);
			$('#price_point_button').attr('value','使用');
		}
	})
}

//礼品卡绑定
function checkCard(cardSn, cardPassword, ob,isbind_coupon){

    if (isbind_coupon) {
        var cardSnValue       = cardSn;
        var cardPasswordValue = cardPassword;
    } else {
	    var cardSnValue = $.trim($('#'+cardSn).val());
        var cardPasswordValue = $.trim($('#'+cardPassword).val());        
    }
    var price = parseFloat($('#amount').html());
    var fare = parseFloat($("#priceLogistic").val());
    if(cardSnValue == '')
	{
	   alert("请输入卡号！");return false;
	}
    
    if(cardPasswordValue == '')
    {
    	 alert("请输入密码！");return false;
    }
  
	if(cardSnValue != '' && cardPasswordValue != '') {		 
		$.ajax({
			url:'/card/check-card/card_sn/' + cardSnValue + '/card_password/' + cardPasswordValue + '/price/' + price + '/fare/' + fare + '/r/' + Math.random(),
			dataType:'json',
			beforeSend:function(){
	            $(ob).html('处理中……');
			},
			success:function(data){
	            $(ob).html('确定使用');
				if (data == '' || data == 'error') {
				   alert('系统繁忙,请稍候再试!');
				} else if(data == 'usePoint') {
				   alert('对不起，已经使用积分，不能同时使用卡!');
				} else if(data == 'noMoney') {
				   alert('对不起，该卡已无有效金额!');
				} else if(data == 'cardUsed') {
				   alert('对不起，该卡已经使用过!');
				} else if(data == 'cardError') {
				   alert('卡号或密码错误!');
				} else if(data == 'cardExpired') {
				   alert('此卡已经过期!');
				} 
				else if(data == 'noStart') {
				   alert('对不起，请在此卡启用时间范围内使用!');
				} 
				else if(data == 'cardExists') {
				   alert('您已经使用卡了!');
				} else if(data == 'canNotUse') {
				   alert('对不起，您的订单不满足该卡的使用条件，暂不能使用!!');
				} else if(data == 'minAmount'){
					alert('对不起，您的订单金额不满足该卡的使用条件！');
			    } else if(data == 'offerLimit'){
					alert('对不起，该订单已启用活动，不能再使用礼券！');
				} else if(data == 'unionError') {
				   alert('对不起，您的来源不满足该卡的使用条件，不能使用!');
				} else if(data == 'exclusiveLimit') {
				   alert('对不起，购物车有0元商品，请先移除该商品再使用优惠券!');
				} else if(data == 'canNotUseWidthGift') {
				   alert('对不起，购物车内有赠品，请先移除赠品再使用优惠券!');
				} else if(data == 'onlyTuan') {
				   alert('对不起，该订单为团购订单，不能使用优惠券!');
				} else if(data == 'limitGoods') {
				   alert('对不起，该优惠券必须购买了指定商品才生效，暂不能使用!');
				}else {
				   window.location.reload();
				}
                console.log(data)
			},
			error:function(){
				alert('系统繁忙,请稍候再试!');
	            $(ob).html('确定使用');
			}
		})
	}
}

//礼品卡取现
function deleteCard(type){
	$.ajax({
		url: '/card/delete-card/type/' + type,
		success:function(data){
			if (data == '' || data == 'error') {
				alert('系统繁忙,请稍候再试!');
			} else if (data == 'ok') {
				window.location.reload();
			}
		},
		error:function(){
			alert('系统繁忙,请稍候再试!');
		}
	})
}

function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}
