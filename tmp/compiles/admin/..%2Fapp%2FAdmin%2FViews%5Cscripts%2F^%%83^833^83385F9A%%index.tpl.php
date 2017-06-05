<?php /* Smarty version 2.6.19, created on 2014-12-08 16:15:44
         compiled from index/index.tpl */ ?>
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
    <?php $_from = $this->_tpl_vars['menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
        <li>
            <a href="javascript:fGo();" onclick="goMenu(<?php echo $this->_tpl_vars['menu']['menu_id']; ?>
);" id="index_menu-<?php echo $this->_tpl_vars['menu']['menu_id']; ?>
"><?php echo $this->_tpl_vars['menu']['menu_title']; ?>
</a>
        </li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
  </span>
   <span class="index_tips">※提示：为提高办公效率，推荐使用Google Chrome浏览器</span>
   <span id="index_header_loading">
     <!--<img align="absmiddle" src="/haitaoadmin/images/loading.gif"/>
     <span id="spnMsg">数据加载中..</span>-->
   </span>
   <span class="index_tips-right">您好：<?php echo $this->_tpl_vars['admin']['real_name']; ?>
【<a href="javascript:fGo();" onclick="window.location.replace('/admin/auth/logout');"> 退出</a> 】【<a href="/" target="_blank">官网</a>】【 <a href="javascript:fGo();" onclick="G('/admin/index/info');">
查看系统信息</a>】【 <a href="javascript:fGo();" onclick="G('/admin/index/clean-cache');">清空缓存</a>】</span>
  <div class="index_menu">
      <span><a href="javascript:fGo();" onclick="Gurl('backward')">后退</a><img src="/haitaoadmin/images/toward.gif" width="19" height="13" /></span>
      <span><a href="javascript:fGo();" onclick="Gurl('forward')">前进</a><img src="/haitaoadmin/images/backward.gif" width="19" height="13" /> </span>
      <span><a href="javascript:fGo();" onclick="Gurl('refresh')" alt="刷新" />刷新</a><img src="/haitaoadmin/images/fresh.gif" width="16" height="15" /></span> </div>
  <span class="index_menu-text" id="countdown"></span>
  <div class="index_menu-right">
    <span><img src="/haitaoadmin/images/down.gif" width="18" height="15" /><?php if ($this->_tpl_vars['switchAreaID']): ?><a href="javascript:fGo();" onclick="switchLid()" title="切换到<?php echo $this->_tpl_vars['switchAreaName']; ?>
" id="areaTitle"><b><?php echo $this->_tpl_vars['currentAreaName']; ?>
</b></a><?php else: ?><a href="javascript:fGo();"><b><?php echo $this->_tpl_vars['currentAreaName']; ?>
</b></a><?php endif; ?></span>
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
    goMenu(<?php echo $this->_tpl_vars['init']; ?>
);
}
var switchAreaID = '<?php echo $this->_tpl_vars['switchAreaID']; ?>
';
var areaInfo = new Array();
areaInfo.push('');
<?php $_from = $this->_tpl_vars['areaInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['areaID'] => $this->_tpl_vars['areaName']):
?>
areaInfo.push('<?php echo $this->_tpl_vars['areaName']; ?>
');
<?php endforeach; endif; unset($_from); ?>
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