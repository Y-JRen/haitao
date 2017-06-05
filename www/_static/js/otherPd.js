var changeTime = null;
var changeLen = 0;
var changeIndex = 0;
$(document).ready(function(){
	$(".bakkuan_menu ul li a").click(function(){
		var id = $(this).attr("attrid");
		window.location.href="baokuan-"+id+".html";
	});
	changeLen = $("#adv_113_100_pager li").length;
	$("#adv_113_100_pager li").mouseover(function(){
		 changeIndex = $("#adv_113_100_pager li").index(this);
		 showPromImg(changeIndex);
		 clearInterval(changeTime);
		 changeTime = null;
	 });
	 $("#adv_113_100_pager li").mouseout(function(){
			goProm();
	 });
	 $("#adv_113_100 li a").hover(function(){
			clearInterval(changeTime);
			changeTime = null;
		},function(){goProm();});
	goProm();

	$(".submitmy").click(function(){
		var real_name = $.trim($("#fullname").val());
		var phone = $.trim($("#phone").val());
		var province=$.trim($('#province').val());
		var city=$.trim($('#city').val());
		var area=$.trim($('#area').val());
		var address = $.trim($("#address").val());
		var payment = $.trim($("#payment").val());
		var mobile = /^1[3|4|5|8][0-9]\d{8}$/;
 		var tel = /^[0-9]{3,4}-?[0-9]{7,9}$/;
		if(real_name == ""){
			zt_message("收货人姓名不能为空，请填写后再提交！");
			return false;
		}
		if(phone.indexOf("-")>0){
			if(phone.length == 0 || !tel.test(telephone)){
				zt_message("联系电话输入不正确！如果您输入的是固定电话其格式为：021-12345678.");
				return false;
			}
		}else{
			if(phone.length == 0 || !mobile.test(phone)){
				zt_message("联系电话输入不正确！如果您输入的为手机号码请确认输入是否有误！");
				return false;
			}
 		}
 		if(province=='' || /\D+/.test(province)){
			zt_message("请选择省份！");
			return false;
		}
		if(city=='' || /\D+/.test(city)){
			zt_message("请选择城市！");
			return false;
		}
		if(area=='' || /\D+/.test(area)){
			zt_message("请选择区域！");
			return false;
		}
		if(address == ""){
			zt_message("收货地址不能为空，请填写后再提交！");
			return false;
		}
		if(payment == ""){
			zt_message("请选择支付方式！");
			return false;
		}
		return true;
	});
	$("#description").click(function(){
		$(this).val("");
	});
	$('.prolist ul li').hover(
		function(){
			$(this).addClass('cur')
		},function(){
			$(this).removeClass('cur');
			}
	);
});

 $(function() {
       var len = $("#clearProductAdvbox img").length; //获取个数
       var len2 = $("#specialProductAdvbox img").length; //获取个数
       var len3 = $("#adventProductAdvbox img").length; //获取个数
       if(len==0){
             $("#clearProductAdvbox").hide();
         }
        if(len2==0){
            $("#specialProductAdvbox").hide();
        }
        if(len3==0){
            $("#adventProductAdvbox").hide();
        }
});





function goProm(){
	if(changeTime == null){
		changeTime = setInterval(function(){
			showPromImg(changeIndex);
			changeIndex ++;
			if(changeIndex == changeLen) changeIndex =0;
		},2000);
		showPromImg(changeIndex);
	}
}

function showPromImg(index_on){
		$("#adv_113_100_pager li").each(function(ind){
			if(ind==index_on){
				var tmp_num_on = "#adv_113_100 li:eq("+index_on+")";
				$(tmp_num_on).show();
				$(this).attr("class","activeSlide");
			}else{
				var tmp_banner = "#adv_113_100 li:eq("+ind+")";
				var tmp_num = "#adv_113_100_pager li:eq("+ind+")";
				$(tmp_banner).hide();
				$(tmp_num).removeClass("activeSlide");
			}
		});
}


 function allProduct(state){
        $("#giantCheap_menuStep").removeClass("step2 step3 step4");
        if (state == 'tab1') {
            $("#giantCheap_menuStep").addClass("step1");
            $("#specialProductDiv").show();
            $("#adventProductDiv").show();
            $("#clearProductDiv").show();
        }
    }
   function  specialProduct(state){
       $("#giantCheap_menuStep").removeClass("step1 step3 step4");
       if (state == 'tab2') {
           $("#giantCheap_menuStep").addClass("step2");
           $("#specialProductDiv").show();
           $("#adventProductDiv").hide();
           $("#clearProductDiv").hide();
       }
   }
   function adventProduct(state) {
       $("#giantCheap_menuStep").removeClass("step1 step2 step4");
       if (state == 'tab3') {
           $("#giantCheap_menuStep").addClass("step3");
           $("#adventProductDiv").show();
           $("#specialProductDiv").hide();
           $("#clearProductDiv").hide();
       }
   }
   function  clearProduct(state){
       $("#giantCheap_menuStep").removeClass("step1 step2 step3");
       if (state == 'tab4') {
           $("#giantCheap_menuStep").addClass("step4");
           $("#clearProductDiv").show();
           $("#specialProductDiv").hide();
           $("#adventProductDiv").hide();
       }
   }
var _t = encodeURI(document.title+'促销中心 最值得信赖的保健商城');
var _url = encodeURI(document.location);
var _site;
function openQQ() {
_site = 'http://www.yoye.cn';
var _u = 'http://v.t.qq.com/share/share.php?title=' + _t + '&url=' + _url + '&site=' + _site;
window.open(_u, '转播到腾讯微博', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no');
}
function openQZone() {
var _u = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + encodeURIComponent(document.location) + "&title=" + encodeURIComponent(document.title);
window.open(_u, '转播到QQ空间', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no');
}
function openSina() {
var _u = 'http://v.t.sina.com.cn/share/share.php?title=' + _t + '&url=' + _url;
window.open(_u, '转播到新浪微博', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no');
}
function openKaixin() {
var _u = 'http://www.kaixin001.com/repaste/share.php?rtitle=' + _t + '&rurl=' + _url;
window.open(_u, '转播到开心网', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no');
}
function openRenrRen() {
var _u = 'http://share.renren.com/share/buttonshare.do?title=' + _t + '&link=' + _url;
window.open(_u, '转播到人人网', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no');
}
function openWangyi() {
var _u = ' http://t.163.com/article/user/checkLogin.do?link=http://news.163.com&info=' + _t + _url;
window.open(_u, '转播到网易', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no');
}

