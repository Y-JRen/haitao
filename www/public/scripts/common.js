$(document).ready(function(){
/**我的购物车**/
$("#cart1").hover(
  function(){
			   $(this).find(".cartul").removeClass("hidden").addClass("appear");
			 },
  function(){
			   $(this).find(".cartul").removeClass("appear").addClass("hidden");
			}
);
/**allSort-list2**/
$(".allSort-list").hover(
	function(){
			      $(this).find(".show").removeClass("hidden").addClass("appear");
			  },
	function(){
			      $(this).find(".show").removeClass("appear").addClass("hidden");
			   }
);

$("#keyword_hd").focus(function(){
	if($(this).val()=='搜索海淘网 关键字'){
		$(this).val("");
	}
});
$("#keyword_hd").blur(function(){
	if($.trim($(this).val())==""){
		$(this).val('搜索海淘网 关键字');
	}
})

});
/**选项卡**/
function setTab(name,cursel,n){ 
  for(i=1;i<=n;i++){ 
  var menu=document.getElementById(name+i); 
  var con=document.getElementById("con_"+name+"_"+i); 
  menu.className=i==cursel?"hover":""; 
  con.style.display=i==cursel?"block":"none"; 
  } 
}   
 
/**
 * 转化大小字母
 * @param obj
 */
function parseUpperCase(obj) {
	obj.value = obj.value.toUpperCase();
}

function fGo(){};
/**
 * filterUrl url过滤
 *
 * @param    string    url   请求地址
 * @param    string    key   过滤参数
 */
function filterUrl(url,key)
{
    var re = new RegExp("(.*)(\/"+key+"\/)([^\/]*)", "i");
    url = url.replace(re, "$1");
	return url;
}

/**
 * ajax获取数据
 *
 * @param    string    url
 * @param    string    div
 * @return   void
 */
function loadAuthData(url)
{
	$.ajax({
		url:url,
		type:'get',
		success:function(data){
			$('#user_login_span').html(data);
		}
	})
}
/**刷新验证码*/
function change_verify(id,type)
{
  var img_url = '/auth/auth-image/space/'+type+'/code/'+Math.random();
  $("#"+id).attr('src',img_url);
  return;
}

function goToPay()
{
	location.href='/flow/index';
}

/**
 * 收藏商品
 */
function favGoods(ob,gid)
{
  if(!gid)
  {
	  $.dialog.alert('<div style="width:150px;text-align:center;">参数错误！</div>'); return false;
  }
  
  $.post('/goods/favorite/goodsid/'+gid,function(data){
   if(data.status==1){
	  alert('收藏成功！');
   }else{
	   alert(data.msg); 
   }	  
  },'json');
}
/**
 * 搜索
 */
function search_sbmit()
{
	if($("#keyword_hd").val()==""||$("#keyword_hd").val()=="搜索海淘网 关键字"){
		alert("请输入关键字");
		return false;
	}else{
		$("form[id='form_hd']").submit();
	}
}
