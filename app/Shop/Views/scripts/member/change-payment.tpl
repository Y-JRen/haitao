<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{if $page_title}} {{$page_title}} - 国药集团1健康商城  {{else}} 国药电商 -专业的健康品商城，正品保证，保健品，健康食品，让每个人更健康一点 {{/if}}</title> 
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="Keywords" content="{{if $page_keyword}}{{$page_keyword}}{{else}}国药电商 ,保健品,保健食品,品牌保健品,网上保健品商城,网上买保健品，胶原蛋白，螺旋藻，阿胶{{/if}}" />
<meta name="Description" content="{{if $page_description}}{{$page_description}}{{else}}国药电商 -专业的健康品商城，为消费者提供保健品，健康食品，品牌保健品，胶原蛋白，螺旋藻，阿胶等健康产品，绝对正品保证，支持货到付款，30天退换货保障！{{/if}}" />
<link type="image/x-icon" href="{{$_static_}}/images/home.ico" rel="Shortcut Icon">
<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,cart.css&v={{$sys_version}}.css" rel="stylesheet" />
<script>var site_url='{{$_static_}}'; var jumpurl= '{{$url}}';</script>
<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,common.js&v={{$sys_version}}.js" ></script>
</head>
<body>
<div class="content">
	<div class="flow_step">
    	<span class="logo"><img src="{{$_static_}}/images/cart/logo.jpg" width="225" height="101" /></span>
        <div class="pay_state"><img src="{{$_static_}}/images/cart/pay_state01.jpg" width="508" height="43" /></div>
    </div>
  	<div class="title_cart">
   	  <h2>选择支付方式</h2>
    </div>
 
  <div class="order_info  pay_other" >
    <!--选择支付方式 begin---->
    <div class="pay_method" id="otherpay_box">      
   <form action="/member/change-payment/" id="frmUpdata" method="post"> 
    	<h2>支付方式</h2>
       	<div class="pay_bank">
       	  <h3>在线支付<span>（即时到帐，支持大多数银行卡，付款成功后将立即安排发货）</span></h3>
       	  <div class=" pay_bank_edit">
       	{{if $payment.list}}  
          
          {{foreach from=$payment.list key=key  item=bank_list}}
                        <li pay_type="{{$bank_list.pay_type}}"  {{if $payType==$bank_list.pay_type}}class="active"{{/if}}>                          
							<img src="{{$bank_list.img_url}}" title="{{$bank_list.name}}"/> 
							  <p>{{$bank_list.name}}</p>
						</li>   
						{{/foreach}}  
         
          {{/if}}
        
         {{if $payment.bank_list}}          
          {{foreach from=$payment.bank_list key=key  item=bank_list}}
           <li pay_type="{{$bank_list.pay_type}}" {{if $payType==$bank_list.pay_type}}class="active"{{/if}}>
		   <img  src="{{$bank_list.img_url}}"  title="{{$bank_list.name}}"/> 
		     <p>{{$bank_list.name}}</p>
		</li>						
		{{/foreach}}  
         {{/if}}  
         </div>        
          </div>
 <div class="daofu">
      {{if $payment.cod}} 
       <h3><b>货到付款</b><span>（送货上门后再收款，支持现金、POS机刷卡）</span></h3>	
    	<ul>
    	 <li pay_type="{{$payment.cod.pay_type}}" {{if $payType==$payment.cod.pay_type}}class="active"{{/if}}>
    	  <img  src="{{$payment.cod.img_url}}"  title="{{$payment.cod.name}}"/> 
    	    <p>{{$payment.cod.name}}</p>
    	 </li>
    	 </ul>	
    	{{/if}}		
    	   
	 <input id="pay_type" type="hidden" name="pay_type" value="{{$payType}}"/>
	 <input type="hidden" name="batch_sn" value="{{$order.batch_sn}}"  />	
	 <input type="hidden" name="submitted" value="change_payment"  />
    <a href="javascript:;" onclick="setPayment()"><img src="{{$_static_}}/images/cart/btn_sure.jpg"></a>
 </div>           
  
  <!--选择支付方式 end---->
   </form>   </div>
  </div>
  
<div id="payed-box" style="display:none"> 
</div>

</div> 
<script>
$(function(){	
	//支付方式 筛选
	   $("#otherpay_box li").click(function(){
			$("#otherpay_box li").removeClass("active")
			$(this).removeClass("current").addClass("active");
			var pay_type=$(this).attr('pay_type');
			$("#pay_type").val(pay_type);
		})
		
		$("#otherpay_box li").hover(function(){
			if($(this).attr('class') != 'active') $(this).addClass("current");
		},function(){
			if($(this).attr('class') != 'active') $(this).removeClass("current");
		});
	});

function setPayment()
{
   var pay_type = $("#pay_type").val(); //支付方式
   if(!pay_type)
   {
	  alert("请选择支付方式");
	  return  false;
   } 
   
   var params = $("#frmUpdata").serializeArray();
	$.post($("#frmUpdata").attr('action'),params,function(data){
		       if(data.status==1)
		    	  {		    	  
		    	    location.href=data.link;
		    	  }else{
		    	    alert(data.msg);
		         }
	 },'json');		
	return true;
   
}
</script>
{{include file="flow_footer.tpl"}}
</body></html>
