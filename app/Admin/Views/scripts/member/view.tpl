<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', '', '');
</script>
<div class="title">{{$title}}</div>
<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_current">基本信息</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">收货地址</li>
	</ul>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=index}}')">会员列表</a> ]
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$member.user_id}}')">编辑信息</a> ]
</div>
<div id="show_tab_page_1" style="display:block">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">会员名称</td>
<td width="40%">{{$member.user_name}} [{{$member.rank}}]</td>
<td width="10%">会员组</td>
<td width="40%">{{$member.rank_name}}</td>
</tr>
<tr>
<td width="10%">昵称</td>
<td width="40%">{{$member.nick_name}}</td>
<td width="10%">真实姓名</td>
<td width="40%">{{$member.real_name}}</td>
</tr>
<tr>
<td width="10%">生日</td>
<td width="40%">{{$member.birthday}}</td>
<td width="10%">性别</td>
<td width="40%">{{$member.sex}}</td>
</tr>
<tr>
<td width="10%">Email</td>
<td width="40%">{{$member.email}}</td>
<td width="10%">手机</td>
<td colspan="3">{{$member.office_phone}}</td>
</tr>
<tr>
<td width="10%">MSN</td>
<td width="40%">{{$member.msn}}</td>
<td width="10%">QQ</td>
<td width="40%">{{$member.qq}}</td>
</tr>
<tr>
<td width="10%">办公室电话</td>
<td width="40%">{{$member.office_phone}}</td>
<td width="10%">住宅电话</td>
<td width="40%">{{$member.home_phone}}</td>
</tr>
<tr>
<td width="10%">账户余额</td>
<td width="40%"><span id="moneyValue">{{$member.money}}</span> [ <a href="javascript:fGo()" onclick="openWinList('money')">历史记录</a> ]</td>
<td width="10%">积分</td>
<td width="40%"><span id="pointValue">{{$member.point}}</span> [ <a href="javascript:fGo()" onclick="openWinList('point')">历史记录</a> ]</td>
</tr>
</tbody>
</table>
</div>
<div id="show_tab_page_2" style="display:none">
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="table_form" class="table_form">
<tbody>
{{foreach from=$memberAddress item=address name=address}}
<tr>
<td width="10%">配送区域</td>
<td width="40%">{{$address.province_msg.area_name}} {{$address.city_msg.area_name}} {{$address.area_msg.area_name}}</td>
<td width="10%">收货人姓名</td>
<td width="40%">{{$address.consignee}}</td>
</tr>
<tr>
<td width="10%">详细地址</td>
<td width="40%">{{$address.address}}</td>
<td width="10%">邮政编码</td>
<td width="40%">{{$address.zip}}</td>
</tr>
<tr>
<td width="10%">电话</td>
<td width="40%">{{$address.phone}}</td>
<td width="10%">手机</td>
<td width="40%">{{$address.mobile}}</td>
</tr>
<tr>
<td width="10%">电子邮件地址</td>
<td width="40%">{{$address.email}}</td>
<td width="10%">传真</td>
<td width="40%">{{$address.fax}}</td>
</tr>
<tr>
<td colspan="4" style="border-bottom: 2px dotted #ccc; text-align: center"></tr>
{{/foreach}}
</tbody>
</table>
</div>
</div>
<script>
function openWinList(type)
{
    var url = '{{url param.action=account-list param.id=$member.member_id param.type=}}' + type;
	win = new dhtmlXWindows();
	win.setImagePath("./scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
	
	var account = win.createWindow("accountWin", 15, 50, 900, 600);
	account.setText("账户变动历史记录");
	account.button("minmax1").hide();
	account.button("park").hide();
	account.denyResize();
	account.denyPark();
	account.setModal(true);
	account.attachURL(url, true);
}

function closeWin()
{
    win.window("accountWin").close();
}
</script>