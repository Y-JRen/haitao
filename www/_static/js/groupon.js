$(function(){
	$('.clock').each(function(){
		var $this = $(this)
		$this.YMCountDown("剩余时间");
	});
	$("#tuan_buy").click(function(){
		var flag = $("#valNO").val();
		if(flag == ""){
			$("#xj_login").show();
		}else{
			var gid = $("#tuanid").val();
			if(!isNaN(gid)){
				var onnum =  parseInt($("#canBuy").val());
				window.location.href="tuantlement"+gid+"-"+onnum+".html";
			}
		}
	 });
	$(".closeDiv").click(function(){
		$("#xj_login").hide();
	});
	//购物车加减计算
	$(".reduce").click(function(){
		compNum(1);
	});
	$(".add").click(function(){
			compNum(2);
	});
	$("#canBuy").blur(function(){
			compNum(3);
	});
});
//购物车计算
function compNum(type){
	var onnum =  parseInt($("#canBuy").val());
	var minnum =  parseInt($("#minNum").val());
	var maxnum =  parseInt($("#maxNum").val());
	if(isNaN(onnum)){
		$("#canBuy").val(minnum);
	}
	if(type == 1) onnum--;
	if(type == 2) onnum++;
	if(onnum < minnum) {
		$("#canBuy").val(minnum);
		zt_message("提示：此商品购买数量最低"+minnum+"件！");
	}else if(onnum > maxnum){
		$("#canBuy").val(maxnum);
		zt_message("提示：此商品购买数量最多"+maxnum+"件！");
	} else{
		$("#canBuy").val(onnum);
	}

}
 function changeTuan(type){
	 var len = $(".sub_tuan .con").length;
	 var i = 0;
	$(".sub_tuan .con").each(function(ind){
		if($(this).css("display") == "block"){
			i = ind;
		}
		$(this).hide();
	});
	if(type==2){
		i++ ;
		if(i >= len) i=0;

	}else{
		if(i<0) i=len;
		i-- ;
	}
	$(".sub_tuan .con").eq(i).show();
  }

 var _t = encodeURI(document.title+'团购中心 最值得信赖的保健商城');
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