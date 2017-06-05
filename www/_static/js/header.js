function getGoodsHistory() {
		$.ajax({
			url : '/index/history-goods/r/' + Math.random(),
			type : 'get',
			success : function(data) {
				$('#goodsHistory').html(data);
			}
		});
	}

function emptyGoodsHistory() {
		document.cookie = "history=";
		$.ajax({
			url : '/index/empty-history-goods/r/' + Math.random(),
			type : 'get',
			success : function(data) {
				$('#goodsHistory').html(data);
			}
		});
	}

function getFavorite() {
		$.ajax({
			url : '/index/favorite/r/' + Math.random(),
			type : 'get',
			success : function(data) {
				$('#favorite').html(data);
			}
		});
}

var myCartDialog = null;
function getCart(type) {
	type?type:'top';
	
	if(type != 'top') myCartDialog = $.dialog({id:'cart_tips',width:480,title:'加入购物车提示',padding:'5px 5px',follw:$("div.buyBox"), fixed:true, lock:true});
	
	$.get('/index/cart/r/'+Math.random(),{'type':type},function(data){
		$("#topCartNum").html(data.number);
		if(type == 'top')
		{
		  $('#mycart').html(data.html);
		}else{		 
		  if(data.number==0){
			myCartDialog.close();
			$.dialog.alert(data.html).time(2000);
		  }else{
			 myCartDialog.content(data.html);	
		  }		  
		} 
	},'json');			
}



function delCartGoods(product_id, number,type) {
		$.ajax({
			url : '/index/del-cart-goods/product_id/' + product_id + '/number/' + number + '/r/' + Math.random(),
			type : 'get',
			success : function(data) {
				getCart(type)
			}
		});
	}

function indexAddCart(sn) {
		var number = 1;
		var productSn = sn;
		$.ajax({
			url : '/goods/check',
			data : {
				product_sn : productSn,
				number : number
			},
			type : 'get',
			success : function(msg) {
				if (msg != '') {
					alert(msg);
				} else {
					window.location.replace('/flow/buy/product_sn/' + productSn + '/number/' + number);
				}
			}
		})
	}

function delCartPackageById(boxid, expire,type) {
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
			$.cookie('p', packageCookie, {
				path : "/",
				expires : expire
			});
		}
		$('#package_del_id_' + boxid).remove();
		getCart(type);
	}

	// 头部购物车弹窗 > 组合商品 > 删除
	//删除组合商品
function delGroupGoods(g_id,type) {
		if (g_id < 1) {
			alert('参数错误');
			return;
		}
		$.ajax({
			url : '/group-goods/del',
			data : {
				group_id : g_id
			},
			type : 'post',
			success : function(msg) {
				if (msg != '') {
					alert(msg);
				} else {
					$('#del_id_' + g_id).remove();
					getCart(type);
				}
			},
			error : function() {
				alert('网络繁忙，请稍后重试');
			}
		});
}

getCart('top');
$(function(){
	$("#cart").hover(function() {	
		getCart('top');
		$('#mycart').show();	
	}, function() {
		$('#mycart').hide()
	});

    //页面初始化加载	
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


//updata 登录状态
var query = window.location.search;
if ((query != null)) {
	var d = new Date();
	var posturl = "/auth/js-auth-state/" + query + '&t=' + d.getTime();
}
loadAuthData(posturl);	

