
/** 加入购物车*/
function addCart(goods_sn,type,number) {
	       if(!number){
	    	 number = $('#buy_number').val();  
	       }
	       number>0 ? number : 1 ;
		   var productSn = goods_sn;
			//第一次ajsx
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
						window.location.reload();
					} else {
						//第二次ajax
						$.ajax({
							url : '/flow/actbuy/product_sn/' + productSn + '/number/' + number,
							type : 'get',
							success : function(msg) {
								if (msg != '') {
									alert(msg);
									return;
								}
								if(type == 'buy_now'){
									location.href="/flow/index";
									return false;
								}else{
									getCart('tips');
								}								
								
							}
						});
					}
				}
			})
}
		
function addGroupCart(g_id,type,number) {
			var tmp = parseInt(g_id);
			 if(!number){
			     number = $('#buy_number').val();  
			  }
			 number>0 ? number : 1 ;
			$.ajax({
				url : '/group-goods/check',
				data : {group_id:tmp,number:number},
				type : 'post',
				success : function(msg) {
					if (msg != '') {
						alert(msg);
						return;
					} 
					
					if(type == 'buy_now'){
						location.href="/flow/index";
						return false;
					}else{			
						getCart('tips');					
					}
				}
			});
			
}
		


//加载评论信息
function getCommentList(gid,page) {			
	$.ajax({
		url : '/goods/comment',
		data : {id : gid,page : page},
		type : 'get',
		success : function(msg) {
			$('#comment_list').html(msg);
		}
	})
}

//加载咨询信息
function getZxList(gid) {			
	$.ajax({
		url : '/goods/consultation',
		data : {id : gid},
		type : 'get',
		success : function(msg) {
			$('#consultation_list').html(msg);
		}
	})
}
//到货提醒
function goods_notice()
{
	popOutBox($("#goods_notice_box").html(),'订阅到货通知',363);
}
//提交到货通知
function check_notice()
{
  var account = $.trim($("#account").val());
  if(account == ''){
	 alert("请输入邮箱或手机号码！");
	 $("#account").focus();
	 return false;
  }
  if(!Check.isMobile(account) &&  !Check.isEmail(account))
  {
	 alert("请输入正确格式的邮箱或手机！");
	 $("#account").focus();
	 return false;  
  }
  
	var params = $("#frm_notice").serializeArray();
	$.post($("#frm_notice").attr('action'),params,
		 function(data)
		 {		 
		   closePopOutBox();
		   alert(data.msg);
	     },'json');		
	
	 return false;
  
}

//定位
function calculateOffset(field, attr)
{
	var offset = 0;
	while(field) {
		offset += field[attr];
		field = field.offsetParent;
	}
	return offset;
}

function setHistory(goods_id)
{
	$.get('/goods/set-history-goods/goods_id/'+goods_id,function(data){
		$("#historyBox").html(data.html);
	},'json');		
}