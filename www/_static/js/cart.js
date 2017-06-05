/**
 * 结算验证
 * @returns {Boolean}
 */
function checkBuyWay(){
	if(checkLogin()){
		window.location.href="/flow/order";
		return false;
	}else{
	  change_verify('verify_img','shopLogin');
	  $("div.mask").css('height',$(document).height());	 
	  var top = ($(document).height()/2)-300;
	  $("#fastLogin_box div.popbox2").css('top',top);
	  $("#fastLogin_box").fadeIn();
	}
}

/*检查是否登录 return bool*/
function checkLogin(){
	var s=false;
	$.ajax({
		url:'/flow/check-login',
		type:'post',
		dataType:'json',
		success:function(msg){
			if(msg.status=='yes'){s=true;}
		},
		async:false
	})
	return s;
}


//form检查&登录验证
function  fastLogin() {
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
			    	    location.href='/flow/order'; //结算
			    	  }else{
			    		 change_verify('verify_img','shopLogin');
			    	    $("#msg_box").show().html(data.msg); 
			    	  }
		   },'json');	
		
		return false;
}

//加入购物车
function addRecommendProductsCart(productSn){
	var number = 1;
	$.ajax({
		url:'/goods/check',
		data:{product_sn:productSn,number:number},
		type:'get',
		success:function(msg){
			if (msg != ''){
				alert(msg);
				window.location.replace(jumpurl);
			}else{
				window.location.replace('/flow/buy/product_sn/'+productSn+'/number/'+number);
			}
		}
	})
}

//商品-1
function selNumLess(num){
	var old_number=$('#buy_number_'+num).val();
	number = Number(old_number) - 1;
	if(number < 1) {return;}
	$('#buy_number_'+num).val(number);
	changeNumber(num,number,old_number);
}

//商品+1
function selNumAdd(num, max_num){
	var old_number=$('#buy_number_'+num).val();
	number = Number(old_number) + 1;
	if(number>max_num){alert('请输入1-'+max_num+'间的整数');return;}
	$('#buy_number_'+num).val(number);
	changeNumber(num,number,old_number);
}

//手动输入商品数量
//num为input.id后缀，defaultNumber原来的数字
function setGoodsNumber(num,defaultNumber,max_num){
	defaultNumber=Number(defaultNumber);
	var numNow=$('#buy_number_'+num).val();
	numNow=Number(numNow);
	if(numNow<1 || numNow>max_num){$('#buy_number_'+num).val(defaultNumber);alert('请输入1-'+max_num+'间的整数');return;}
	changeNumber(num,numNow,defaultNumber);
}

//更改购物车商品数量
function changeNumber(num,number,old_num){
	if(number>0){
		if(number == old_num) {
			return false;
		}
		num1 = num.substring(0, num.indexOf('_'));
        num2 = num.substring(num.indexOf('_') + 1, num1.length + 2);
		if (num2 != '') {
            if ((num2 == '0') && (document.getElementById('buy_number_'+num1+'_1') != null)) {

                number = parseInt($('#buy_number_'+num1+'_1').val()) + number;
            }
            else if ((num2 == '1') && (document.getElementById('buy_number_'+num1+'_0') != null)) {
                number = parseInt($('#buy_number_'+num1+'_0').val()) + number;
            }
        }
		$.ajax({
			url:'/goods/check',
			data:{id:num1,number:number},
			type:'get',
			success:function(msg){
				if (msg != ''){
					alert(msg);
					window.location.replace(jumpurl);
				}else{
					changeCartNumber(num1,number);
				}
			}
		})
		return;

	}else{
		alert('购买数量限制 1-20 件');
		$('#buy_number_'+num).val(old_num);
	}
}
//
function changeCartNumber(product_id,number){
	$.ajax({
		url:'/flow/change',
		data:{product_id:product_id,number:number},
		type:'get',
		success:function(msg){
		    if (msg) {
		        alert(msg);
		        return;
		    }
			window.location.replace('/flow/index');
		},
		error:function(){
			alert('error');
		}
	})
}

//弹窗
var wind = null;
function openWin(id, pid, bid)
{
	wind =  $.dialog({id:'sel_gift_goods',width:510,title:'选择赠品',content:'正在加载，请稍候……',padding:'4px 4px',fixed:true, lock:true});
	$.get('/offers/select-goods/id/' + id + '/pid/' + pid + '/bid/' + bid + '/cname/allGift/r/' + Math.random(), function(html){
		wind.content(html);
	});

}
function openGroupWin(id, pid, bid)
{
	wind =  $.dialog({id:'sel_gift_goods',width:510,title:'选择赠品',content:'正在加载，请稍候……',padding:'4px 4px',follw:$("div.buyBox"), fixed:true, lock:true});
	$.get('/offers/select-group-goods/id/' + id + '/pid/' + pid + '/bid/' + bid +'/cname/allGiftGroup/r/' + Math.random(), function(html){
		wind.content(html);
	});
}

function openWinForOrderGift(id, pid, bid, index, offer_id)
{
    wind =  $.dialog({id:'sel_gift_goods',width:510,title:'选择赠品',content:'正在加载，请稍候……',padding:'4px 4px',follw:$("div.buyBox"), fixed:true, lock:true});
	$.get('/offers/select-goods/id/' + id + '/pid/' + pid + '/bid/' + bid + '/index/' + index + '/offer_id/' + offer_id + '/cname/allGift/r/' + Math.random(), function(html){
		wind.content(html);
	});
}

function openGroupWinForOrderGift(id, pid, bid, index, offer_id)
{
    wind =  $.dialog({id:'sel_gift_goods',width:510,title:'选择赠品',content:'正在加载，请稍候……',padding:'4px 4px',follw:$("div.buyBox"), fixed:true, lock:true});
	$.get('/offers/select-group-goods/id/' + id + '/pid/' + pid + '/bid/' + bid + '/index/' + index + '/offer_id/' + offer_id + '/cname/allGroup/r/' + Math.random(), function(html){
		wind.content(html);
	});
}
//$选择赠品
function checkPackageGoods(button, product_id, boxid, expire, index, offer_id){
	$.ajax({
		url:'/offers/get-product',
		data:{product_id:product_id},
		//dataType:'json',
		beforeSend:function(){
			$('#'+button).html('处理中');
		},
		success:function(msg){
			$('#'+button).html('选择');
			if ( msg != ''){
			    if ( msg == 'error') {
			        alert('系统繁忙,请稍候再试!');
			    }
			    else if(msg == 'outofstock') {
				    alert('对不起，库存不足');
			    }
			    else {
    			    var data = eval("("+msg+")");//json解码
    				addPackageGoods(product_id, boxid, data, expire, index, offer_id);
    			}
			}
		}
	})
}

//$选择赠品
function checkPackageGroupGoods(button, group_id, boxid, expire, index, offer_id){
	$.ajax({
		url:'/offers/get-group-goods',
		data:{group_id:group_id},
		//dataType:'json',
		beforeSend:function(){
			$('#'+button).attr('disabled',true);
			$('#'+button).attr('value','处理中');
		},
		success:function(msg){
			$('#'+button).attr('disabled',false);
			$('#'+button).attr('value','选择');
			if ( msg != ''){
			    if ( msg == 'error') {
			        alert('系统繁忙,请稍候再试!');
			    }
			    else if(msg == 'outofstock') {
				    alert('对不起，库存不足');
			    }
			    else {
    			    var data = eval("("+msg+")");//json解码
    				addPackageGoods(group_id, boxid, data, expire, index, offer_id);
    			}
			}
		}
	})
}

//添加赠品
function addPackageGoods(productid, boxid, data, expire, index, offer_id){
	$('#gift_name_'+boxid+offer_id+index).html(data.goods_name);
	if (data.goods_img) {
	    $('#gift_img_'+boxid+offer_id+index).attr('src',img_url+'/' + data.goods_img.replace('.', '_60_60.'));
	}
    $('#gift_'+boxid+offer_id+index).val(data.product_id);
	$('#buy_number_gift_'+boxid+offer_id+index).val(1);
	$('#gift_number_'+boxid+offer_id+index).html(1);
	if (boxid == 'order_gift') {
		$('#change_price_tmp'+offer_id+index).html($('#gift_price'+offer_id+index).val());
		$('#total_price_tmp'+offer_id+index).html($('#gift_price'+offer_id+index).val());
		var offset = 0;
	    var arr = new Array();
		if ($.cookie('order_gift') == null) {		 
		    for (i = 0; i <= index; i++) {
		        arr[i] = '';
		    }
		    order_gift_arr = new Array();
		}
		else {
		    var order_gift_arr = $.cookie('order_gift').split(']::[');
		    for (i = 0; i < order_gift_arr.length; i++) {
		        temparr = order_gift_arr[i].split('-');
		        if (temparr[0] == offer_id) {
		            arr = temparr[1].split(':');
		            offset = i;
		            break;
		        }
		    }
		    if (arr.length == 0) {
    		    var arr = new Array();
    		    for (i = 0; i < index; i++) {
    		        arr[i] = '';
    		    }
    		    offset = order_gift_arr.length;
    		}
		}
		arr[index] = data.product_id;
		order_gift_arr[offset] = '' + offer_id + '-' + arr.join(':');
		$.cookie('order_gift', order_gift_arr.join(']::['), {path: "/", expires: expire});
	} else if (boxid == 'order_buy_gift') {
		$.cookie('order_buy_gift', data.product_id, {path: "/", expires: expire});
	} else {
		var packageCookie = $.cookie('gift');
		if (packageCookie) {
			packageCookie = packageCookie.replace(/^,(.*),$/, "$1");
			packageCookieArray = packageCookie.split(',');
			for (var i = 0; i < packageCookieArray.length; i++) {
				if (packageCookieArray[i].split(':')[0] == boxid) {
					packageCookieArray.splice(i, 1);
					break;
				}
			}
			packageCookieArray.push(boxid + ':' + data.product_id + ':' + productid);
			packageCookie = packageCookieArray.join(',');
		} else {
			packageCookie = boxid + ':' + data.product_id + ':' + productid;
		}
		$.cookie('gift', packageCookie, {path: "/", expires: expire});
		window.location.reload();   //由于不清楚赠品的买赠价，刷新该页面
	}
    countCart();
    if(wind) wind.close();
}

//计算总数和价格
function countCart(){
    var total = 0;
	var amount = 0;
	var number = $("#cart_list input[id^='buy_number_']");
	for (var i = 0; i < number.length; i++) {
	    var num = number[i].value;
	    total += parseInt(num);
	}
    
	var price = $("#cart_list td[id^='change_price_']");
	for (var i = 0; i < price.length; i++) {
	    var reg = new RegExp('<span id=(.*groupbox.*)>(.*)</span>');
	    price[i].innerHTML = price[i].innerHTML.toLocaleLowerCase().replace(reg, "$2").replace(',',''); 	
	    var pri = parseFloat(price[i].innerHTML).toFixed(2);
		if(pri>0){
		  amount += parseFloat(pri);  
		}
	   
	}
    
	$('#total').html(total);
	$('#amount').html(amount.toFixed(2))
}


//
function delPackageGoods(boxid, expire){
    $('#buy_number_gift_'+boxid).val(0);
	$('#gift_number_'+boxid).html(0);

    //var packageCookie = Cookie.read('gift');
	var packageCookie = $.cookie('gift');

    if (packageCookie) {
        packageCookieArray = packageCookie.split(',');
        for (var i = 0; i < packageCookieArray.length; i++) {
			if (packageCookieArray[i].split(':')[0] == boxid) {
                //packageCookieArray.erase(packageCookieArray[i]);
				packageCookieArray.splice( i,1 );
                break;
            }
        }
        packageCookie = packageCookieArray.join(',');
        expire = ($.trim(packageCookie) == '') ? -1 : expire;
        //Cookie.write('gift', packageCookie, {path: "/", duration: expire});
		$.cookie('gift', packageCookie, {path: "/", expires: expire});
    }
    $('#gift_' + boxid).remove();
    countCart();
}

//
function delPackageGoodsOrderGift(index, offer_name, offer_id)
{
    $('#buy_number_gift_order_gift'+offer_id+index).val(0);
	$('#gift_name_order_gift'+offer_id+index).html('<font color="#FF3333">未选择赠品('+offer_name+')</font>');
	//$('#goods_price_tmp'+offer_id+index).html(0);
	$('#change_price_tmp'+offer_id+index).html(0);
	$('#total_price_tmp'+offer_id+index).html(0);

	$('#gift_img_order_gift'+offer_id+index).attr('src', static_url+'/images/package_no_product.gif');

    if ($.cookie('order_gift')) {
        var offset = 0;
        var arr = new Array();
        var order_gift_arr = $.cookie('order_gift').split(']::[');
        for (i = 0; i < order_gift_arr.length; i++) {
		    temparr = order_gift_arr[i].split('-');
		    if (temparr[0] == offer_id) {
		        arr = temparr[1].split(':');
		        offset = i;
		        break;
		    }
	    }
	    if (arr.length > 0) {
	        arr[index] = '';
	        var isEmpty = true;
	        for (i = 0; i < arr.length; i++) {
	            if ( arr[i] != '' ) {
	                isEmpty = false;
	                break;
	            }
	        }
	        if ( isEmpty ) {
	            order_gift_arr[offset] = '';
	            var isEmpty = true;
	            for (i = 0; i < order_gift_arr.length; i++) {
	                if ( order_gift_arr[i] != '' ) {
    	                isEmpty = false;
    	                break;
    	            }
	            }
	            if ( isEmpty ) {
	                order_gift_arr = new Array();
	            }
	        }
	        else    order_gift_arr[offset] = '' + offer_id + '-' + arr.join(':');
	        $.cookie('order_gift', order_gift_arr.join(']::['), {path: "/", expires:30});
	    }
    }
    countCart();
}

function delPackageGoodsOrderBuyGift()
{
    $('#buy_number_gift_order_buy_gift').val(0);
	$('#gift_number_order_buy_gift').html(0);

    //var packageCookie = Cookie.read('order_buy_gift');
	var packageCookie = $.cookie('order_buy_gift');

    if (packageCookie) {
        //Cookie.write('order_buy_gift', '', {path: "/", duration: -1});
		$.cookie('order_buy_gift', '', {path: "/", expires: -1});
    }
    $('#order_buy_gift').remove();
    countCart();
}

function delPackageById(boxid, expire)
{
    //var packageCookie = Cookie.read('p');
	var packageCookie = $.cookie('p');
    if (packageCookie) {
        packageCookieArray = packageCookie.split('|');
        for (var i = 0; i < packageCookieArray.length; i++) {
            if ((i + 1) == boxid) {
				packageCookieArray.splice(i, 1);
                break;
            }
        }
        packageCookie = packageCookieArray.join('|');
        expire = ($.trim(packageCookie) == '') ? -1 : expire;
        //Cookie.write('p', packageCookie, {path: "/", duration: expire});
		$.cookie('p', packageCookie, {path: "/", expires: expire});
		//alert($.cookie('p'));return;
    }
    window.location.reload();
}

/*start:组合商品JS*/
//组合商品+1
function GGNumAdd(g_id){
	if(g_id<1){alert('参数错误');return;}
	var number=parseInt($.trim($('#GG_buy_number_'+g_id).val()));
	if(number>20){alert('购买数量不能超过20个');return;}
	number=number+1;
	checkGroup(g_id,number);
}
//组合商品-1
function GGNumLess(g_id){
	if(g_id<1){alert('参数错误');return;}
	var number=parseInt($.trim($('#GG_buy_number_'+g_id).val()));
	if(number<2){return;}
	number=number-1;
	checkGroup(g_id,number);
}
//手动输入购买数字
function setGGNumber(id,defaultNumber){
	id=Number(id);
	var numNow=$('#GG_buy_number_'+id).val();
	numNow=Number(numNow);
	defaultNumber=Number(defaultNumber);
	if(numNow>20 || numNow<1){$('#GG_buy_number_'+id).val(defaultNumber);alert('请输入1-20间的整数');return;}
	if(numNow==defaultNumber){return;}
	checkGroup(id,numNow);
}
//检查组合商品的库存 && 加入购物车
function checkGroup(g_id,num){
	if(g_id<1 || num>20 || num<1){alert('请输入1-20间的整数');return;}
	$.ajax({
		url:'/group-goods/check',
		data:{group_id:g_id,number:num},
		type:'post',
		success:function(msg){
			if (msg != ''){
				alert(msg);
			}else{
				window.location.replace('/flow/index/');
			}
		},
		error:function(){
			alert('网络繁忙，请稍后重试');
		}
	});
}
//删除组合商品
function delGroupGoods(g_id){
	if(g_id<1){alert('参数错误');return;}
	$.ajax({
		url:'/group-goods/del',
		data:{group_id:g_id},
		type:'post',
		success:function(msg){
			if (msg != ''){
				alert(msg);
			}else{
				window.location.replace('/flow/index/');
			}
		},
		error:function(){
			alert('网络繁忙，请稍后重试');
		}
	});
}
/*end:组合商品JS*/