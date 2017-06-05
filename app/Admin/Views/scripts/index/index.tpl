<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>国人海淘网--后台管理系统</title>
<link href="/haitaoadmin/styles/index.css" rel="stylesheet" type="text/css" />
<link href="/haitaoadmin/images/alertImg/alertbox.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/haitaoadmin/scripts/mootools.js"></script>
<script language="javascript" src="/haitaoadmin/scripts/mootools-more.js"></script>
<script language="javascript" src="/haitaoadmin/scripts/alertbox.js"></script>
<script language="javascript" src="/haitaoadmin/scripts/common.js"></script>
<script language="javascript" src="/haitaoadmin/scripts/dtree.js"></script>
<script language="javascript" type="text/javascript">
function ConfirmClose() {window.event.returnValue = '  --- 来自管理后台的提醒！';}
</script>
<script type="text/javascript" language="javascript">
function menu(){
$$(".menu_box").addEvents({
		mouseover:function(){
			$$(".menu_box ul").setStyle("display","block");
		},
		mouseout:function(){
			$$(".menu_box ul").setStyle("display","none");
		}
	})
}
</script>
</head>
<body>
<input id="index_Gfocus" type="text" size="1" maxlength="1" style="position: absolute; left: -1000px; top: -1000px;" />
<div class="index_head"> <span class="index_head-logo"><img src="/haitaoadmin/images/logo.jpg" width="254" height="52" /></span><span>
  <ul class="index_head-nav" id="index_header_menu">
    {{foreach from=$menus item=menu}}
        <li>
            <a href="javascript:fGo();" onclick="goMenu({{$menu.menu_id}});" id="index_menu-{{$menu.menu_id}}">{{$menu.menu_title}}</a>
        </li>
    {{/foreach}}
  </ul>
  </span>
   <span class="index_tips">※提示：为提高办公效率，推荐使用Google Chrome浏览器</span>
   <span id="index_header_loading">
     <!--<img align="absmiddle" src="/haitaoadmin/images/loading.gif"/>
     <span id="spnMsg">数据加载中..</span>-->
   </span>
   <span class="index_tips-right">您好：{{$admin.real_name}}【<a href="javascript:fGo();" onclick="window.location.replace('/admin/auth/logout');"> 退出</a> 】【<a href="/" target="_blank">官网</a>】【 <a href="javascript:fGo();" onclick="G('/admin/index/info');">
查看系统信息</a>】【 <a href="javascript:fGo();" onclick="G('/admin/index/clean-cache');">清空缓存</a>】</span>
  <div class="index_menu">
      <span><a href="javascript:fGo();" onclick="Gurl('backward')">后退</a><img src="/haitaoadmin/images/toward.gif" width="19" height="13" /></span>
      <span><a href="javascript:fGo();" onclick="Gurl('forward')">前进</a><img src="/haitaoadmin/images/backward.gif" width="19" height="13" /> </span>
      <span><a href="javascript:fGo();" onclick="Gurl('refresh')" alt="刷新" />刷新</a><img src="/haitaoadmin/images/fresh.gif" width="16" height="15" /></span> </div>
  <span class="index_menu-text" id="countdown"></span>
  <div class="index_menu-right">
    <span><img src="/haitaoadmin/images/down.gif" width="18" height="15" />{{if $switchAreaID}}<a href="javascript:fGo();" onclick="switchLid()" title="切换到{{$switchAreaName}}" id="areaTitle"><b>{{$currentAreaName}}</b></a>{{else}}<a href="javascript:fGo();"><b>{{$currentAreaName}}</b></a>{{/if}}</span>
    <span><img src="/haitaoadmin/images/email.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="openDiv('/admin/index/send-email','ajax','发送系统邮件',480,260,true,'sel');">发送邮件</a></span>
    <!-- <span><img src="/haitaoadmin/images/letter.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="openDiv('/admin/index/sendmsg','ajax','发送手机短信',480,180,true,'sel');">发送短信</a></span> -->
    <span><img src="/haitaoadmin/images/keyword.gif" width="18" height="15" /><a href="javascript:fGo();" onclick="openDiv('/admin/admin/change-password','ajax','修改个人密码',480,180,true,'sel');">修改密码</a></span>
   </div>
   </div>
<div id="index_admin_left">
  <div class="index_inner">
	<div id="menu_iframe"></div>
  </div>
</div>
<div id="index_admin_right">
  <div class="index_inner">
    <iframe id="index_main_iframe" src="/admin/index/info/" frameborder="0" name="main_iframe"></iframe>
  </div>
</div>
<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="index_ifrmSubmit"></iframe>
<script language="JavaScript">
var countdown=1440;//倒计时的时间（秒）
var myTimer=setInterval("ShowCountdown('countdown')",1000);
window.onload = function(){
    goMenu({{$init}});
}
var switchAreaID = '{{$switchAreaID}}';
var areaInfo = new Array();
areaInfo.push('');
{{foreach from=$areaInfo item=areaName key=areaID}}
areaInfo.push('{{$areaName}}');
{{/foreach}}
function switchLid()
{
    lid = switchAreaID;
    new Request({url: '/admin/index/switch/lid/' + lid,
                method:'get',
                evalScripts:true,
                onSuccess: function(responseText) {
                    if (responseText = 'ok') {
                        $('areaTitle').innerHTML = '<b>' + areaInfo[lid] + '</b>';
                        if (lid == 1) {
                            switchAreaID = 2;
                        }
                        else if (lid == 2) {
                            switchAreaID = 1;
                        }
                        $('areaTitle').title = '切换到' + areaInfo[switchAreaID];
                        Gurl('refresh');
                    }
                }
    }).send();
}
</script>
</body>
</html>
