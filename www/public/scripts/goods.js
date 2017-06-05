/** 加入购物车*/
function addCart(goods_sn,type,number) {
	       if(!number){
	    	 number = $('#buy_number').val();  
	       }
	       number>0 ? number : 1 ;
		   var productSn = goods_sn;
			//第一次ajsx(验库存)
			$.ajax({
				url : '/goods/check',
				data : {
					product_sn : productSn,
					number : number
				},
				type : 'get',
				success : function(msg) {
					
					if (msg != 'ok') {
						alert(msg);
						window.location.reload();
					} else {
						
						//第二次ajax（加cookie）
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
									alert("加入购物车成功！");
									getCart('top');
								}								
								
							}
						});
					}
				}
			});
}


