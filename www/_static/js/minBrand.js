$(document).ready(function(){
	$("#show-filter").click(function(){
		if($("#filtercontent").css("display")=="none"){
			$("#show-filter").html("<strong>-</strong> 收起");
			$("#filtercontent").css("display","block");
		}else{
			$("#show-filter").html("<strong>+</strong> 展示");
			$("#filtercontent").css("display","none");
		}
	});
	//畅销排行榜
	 $(".hot-product .product-list ul li").mouseover(function(){
		$(".hot-product .product-list ul li .product-seq-box").attr("class","product-seq-box-unshow");
		$(".hot-product .product-list ul li .img_60_60").attr("class","display");
		$(".hot-product .product-list ul li .product-name").attr("class","product-name-unshow");
		$(".hot-product .product-list ul li .product-price").attr("class","display");
		$(this).children('p').each(function(ind){
			if(ind==0)
			{
				$(this).attr("class","product-seq-box");
			}
			else if(ind==1)
			{
				$(this).attr("class","img_60_60");
			}
			else if(ind==2)
			{
				$(this).attr("class","product-name");
			}
			else if(ind==3)
			{
				$(this).attr("class","product-price");
			}
		});
	 })
	  $(".new-product .product-list ul li").mouseover(function(){
		$(".new-product .product-list ul li .product-seq-box").attr("class","product-seq-box-unshow");
		$(".new-product .product-list ul li .img_60_60").attr("class","display");
		$(".new-product .product-list ul li .product-name").attr("class","product-name-unshow");
		$(".new-product .product-list ul li .product-price").attr("class","display");
		$(this).children('p').each(function(ind){
			if(ind==0)
			{
				$(this).attr("class","product-seq-box");
			}
			else if(ind==1)
			{
				$(this).attr("class","img_60_60");
			}
			else if(ind==2)
			{
				$(this).attr("class","product-name");
			}
			else if(ind==3)
			{
				$(this).attr("class","product-price");
			}
		});
	 })
});