<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', '', '');
</script>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
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
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=view param.id=$member.user_id}}')">查看信息</a> ]
</div>
<div id="show_tab_page_1" style="display:block">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">会员名称 * </td>
<td width="40%"><input type="text" name="user_name" id="user_name" msg="请填写会员名称" class="required limitlen" min="3" max="30" size="20" maxlength="30" value="{{$member.user_name}}" {{if $action eq 'edit'}}readonly {{/if}} /><span id="tip_user_name" class="errorMessage">请输入3-30个字符</span></td>
<td width="10%">会员组 * </td>
<td width="40%">
<select name="rank_id" onchange="chgrank(this.value)">
{{html_options options=$memberRanks selected=$member.rank_id}}
</select>
享受折扣：<input type="text" name="discount" id="discount" size="4" maxlength="4" value="{{if $action eq 'edit'}}{{$member.discount}}{{else}}1{{/if}}" readonly/>
</td>
</tr>
<tr>
<td>密码{{if $action eq 'add'}} * {{/if}}</td>
<td><input type="password" name="password" size="20" maxlength="20" {{if $action eq 'add'}}msg="请填写会员密码" class="required"{{/if}} /></td>
<td>重复密码{{if $action eq 'add'}} * {{/if}}</td>
<td><input type="password" name="confirm_password" size="20" maxlength="20" {{if $action eq 'add'}}msg="请填写重复密码" class="required equal" to="password"{{/if}} />{{if $action eq 'edit'}} {{$changePassword}}{{/if}}</td>
</tr>
<tr>
{{if $action eq 'add'}}
<td width="10%"></td>
<td width="40%">
</td>
{{else}}
<td width="10%">昵称</td>
<td width="40%">{{$member.nick_name}}<!--<input type="text" name="nick_name" size="20" maxlength="40" value="{{$member.nick_name}}" />--></td>
{{/if}}
<td width="10%">真实姓名</td>
<td width="40%"><input type="text" name="real_name" size="20" maxlength="40" value="{{$member.real_name}}" /></td>
</tr>
<tr>
<td width="10%">生日</td>
<td width="40%">
{{if $member.birthday}}
	{{html_select_date field_array=birthday time=$member.birthday month_format=%m field_order=YMD start_year=-100 reverse_years=true year_empty="" month_empty="" day_empty=""}}
{{/if}}
</td>
<td width="10%">性别</td>
<td width="40%">{{html_radios name="sex" options=$sexRadios checked=$member.sex separator=""}}</td>
</tr>
<tr>
<td width="10%">Email</td>
<td width="40%"><input type="text" name="email" size="20" maxlength="50" value="{{$member.email}}" /></td>
<td width="10%">手机</td>
<td colspan="3"><input type="text" name="mobile" size="20" maxlength="40" value="{{$member.mobile}}" /></td>
</tr>
<tr>
<td width="10%">MSN</td>
<td width="40%"><input type="text" name="msn" size="20" maxlength="50" value="{{$member.msn}}" /></td>
<td width="10%">QQ</td>
<td width="40%"><input type="text" name="qq" size="20" maxlength="20" value="{{$member.qq}}" /></td>
</tr>
<tr>
<td width="10%">办公室电话</td>
<td width="40%"><input type="text" name="office_phone" size="20" maxlength="40" value="{{$member.office_phone}}" /></td>
<td width="10%">住宅电话</td>
<td width="40%"><input type="text" name="home_phone" size="20" maxlength="40" value="{{$member.home_phone}}" /></td>
</tr>
{{if $member.user_id}}
<tr>
<td width="10%">账户余额</td>
<td width="40%"><span id="moneyValue">{{$member.money}}</span> [ <a href="javascript:fGo()" onclick="openWinList('money')">历史记录</a> ] [ <a href="javascript:fGo()" onclick="openWin('money')">变动</a> ]</td>
<td width="10%">积分</td>
<td width="40%"><span id="pointValue">{{$member.point}}</span> [ <a href="javascript:fGo()" onclick="openWinList('point')">历史记录</a> ] [ <a href="javascript:fGo()" onclick="openWin('point')">变动</a> ]</td>
</tr>
<tr>
<td width="10%">经验值</td>
<td width="40%"><span id="experienceValue">{{$member.experience}}</span> [ <a href="javascript:fGo()" onclick="openWinList('experience')">历史记录</a> ] [ <a href="javascript:fGo()" onclick="openWin('experience')">变动</a> ]</td>
<td width="10%">&nbsp;</td>
<td width="40%">&nbsp;</td>
</tr>
{{/if}}
</tbody>
</table>
</div>
<div id="show_tab_page_2" style="display:none">
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="table_form" class="table_form">
<input type="hidden" name="address_number" id="address_number" value="5" />
{{if $memberAddress}}
{{foreach from=$memberAddress item=address name=address}}
<tbody>
<tr>
<td width="10%">配送区域</td>
<td width="40%">
<select name="address[province][]" onchange="getArea(this)">
    <option value="">请选择省</option>
	{{html_options options=$province selected=$address.province_id}}
</select>
<select name="address[city][]" onchange="getArea(this)">
    <option value="">请选择市</option>
	{{html_options options=$address.city_option selected=$address.city_id}}
</select>
<select name="address[area][]">
    <option value="">请选择区</option>
	{{html_options options=$address.area_option selected=$address.area_id}}
</select>
</td>
<td width="10%">收货人姓名</td>
<td width="40%"><input type="text" name="address[consignee][]" size="20" maxlength="40" value="{{$address.consignee}}" /></td>
</tr>
<tr>
<td width="10%">详细地址</td>
<td width="40%"><input type="text" name="address[address][]" size="20" maxlength="100" value="{{$address.address}}" /></td>
<td width="10%">邮政编码</td>
<td width="40%"><input type="text" name="address[zip][]" size="20" maxlength="20" value="{{$address.zip}}" /></td>
</tr>
<tr>
<td width="10%">电话</td>
<td width="40%"><input type="text" name="address[phone][]" size="20" maxlength="40" value="{{$address.phone}}" /></td>
<td width="10%">手机</td>
<td width="40%"><input type="text" name="address[mobile][]" size="20" maxlength="20" value="{{$address.mobile}}" /></td>
</tr>
<tr>
<td width="10%">电子邮件地址</td>
<td width="40%"><input type="text" name="address[email][]" size="20" maxlength="50" value="{{$address.email}}" /></td>
<td width="10%">传真</td>
<td width="40%"><input type="text" name="address[fax][]" size="20" maxlength="40" value="{{$address.fax}}" /></td>
</tr>
<tr>
<td colspan="4" style="border-bottom: 2px dotted #ccc; text-align: center">
<input type="hidden" name="address[address_id][]" value="{{$address.address_id}}" />
<input type="button" name="add" value="添加" onclick="addAddress(this)" />
 <input type="button" name="delete" value="删除" onclick="removeAddress(this)" />
</td>
</tr>
</tbody>
{{/foreach}}
{{else}}
<tbody>
<tr>
<td width="10%">配送区域</td>
<td width="40%">
<select name="address[province][]" onchange="getArea(this)">
    <option value="0">请选择省</option>
	{{html_options options=$province}}
</select>
<select name="address[city][]" onchange="getArea(this)">
    <option value="0">请选择市</option>
	{{html_options options=$city}}
</select>
<select name="address[area][]">
    <option value="0">请选择区</option>
	{{html_options options=$area}}
</select>
</td>
<td width="10%">收货人姓名</td>
<td width="40%"><input type="text" name="address[consignee][]" size="20" maxlength="40" value="" /></td>
</tr>
<tr>
<td width="10%">详细地址</td>
<td width="40%"><input type="text" name="address[address][]" size="40" maxlength="100" value="" /></td>
<td width="10%">邮政编码</td>
<td width="40%"><input type="text" name="address[zip][]" size="20" maxlength="20" value="" /></td>
</tr>
<tr>
<td width="10%">电话</td>
<td width="40%"><input type="text" name="address[phone][]" size="20" maxlength="40" value="" /></td>
<td width="10%">手机</td>
<td width="40%"><input type="text" name="address[mobile][]" size="20" maxlength="20" value="" /></td>
</tr>
<tr>
<td width="10%">电子邮件地址</td>
<td width="40%"><input type="text" name="address[email][]" size="20" maxlength="50" value="" /></td>
<td width="10%">传真</td>
<td width="40%"><input type="text" name="address[fax][]" size="20" maxlength="40" value="" /></td>
</tr>
<tr>
<td colspan="4" style="border-bottom: 2px dotted #ccc; text-align: center">
<input type="hidden" name="address[address_id][]" value="" />
<input type="button" name="add" value=" 添加 " onclick="addAddress(this)" />
 <input type="button" name="delete" value=" 删除 " onclick="removeAddress(this)" />
</td>
</tr>
</tbody>
{{/if}}
</table>
</div>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    new Request({
        url: '/admin/member/area/id/' + value,
        onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
                    option.value = index;
                    option.text  = item;
                    select.options.add(option);
	            });
	        }
            loadSucess();
        }
    }).send();
}

function addAddress(node)
{
    var tb = $('table_form');
    
    if ($('address_number') && tb.getElements('tbody').length >= $('address_number').value.toInt()) {
        alert('您最多只能有' + $('address_number').value.toInt() + '个收货地址!');
        return false;
    }
    
    var node = $(node);
    var tbody = node.getParent('tbody');
    var newNode = tbody.clone().inject(tb, 'bottom');
    newNode.getElements('input[type=text]').set('value', '');
    var select = newNode.getElements('select');
    
    for (var i = 0; i < select.length; i++)
    {
        select[i].options[0].selected=true;
    }
    
    newNode.getElement('input[type=hidden]') && newNode.getElement('input[type=hidden]').destroy();
    newNode.getElement('input[name=add]').setProperty('onclick', 'fGo()');
    newNode.getElement('input[name=delete]').setProperty('onclick', 'fGo()');
    select.setProperty('onchange', 'fGo()');
    
    newNode.getElement('input[name=add]').addEvent('click', function(event){
        addAddress(event.target);
    });
    newNode.getElement('input[name=delete]').addEvent('click', function(event){
        removeAddress(event.target);
    });
    
    for (var i = 0; i < select.length; i++)
    {
        if (select[i].name.contains("[area]") != true) {
            select[i].addEvent('change', function(event){
                getArea(event.target);
            });
        }
    }
}

function removeAddress(node)
{
    var node = $(node);
    var tbody = node.getParent('tbody');
    if (tbody && (tbody.getAllPrevious().length > 0 || tbody.getAllNext().length > 0)) {
        tbody.destroy();
    }
}

var win;
function openWin(type)
{
    var url = '{{url param.action=account param.id=$member.member_id param.type=}}' + type;
	win = new dhtmlXWindows();
	win.setImagePath("./scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
	
	var account = win.createWindow("accountWin", 300, 150, 600, 240);
	account.setText("账户变动");
	account.button("minmax1").hide();
	account.button("park").hide();
	account.denyResize();
	account.denyPark();
	account.setModal(true);
	account.attachURL(url, true);
}

function openWinList(type)
{
    var url = '{{url param.action=account-list param.id=$member.member_id param.type=}}' + type;
	win = new dhtmlXWindows();
	win.setImagePath("./scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
	
	var account = win.createWindow("accountWin", 300, 150, 600, 350);
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

function submitAccountForm(accountform, type)
{
    var value = accountform.accountValue.value;
    
    if (type == 'money') {
        var reg = /^[\d|\.|,]+$/;
        if(!reg.test(value)) {
            top.alertBox.init("msg='请正确填写信息!'");
            return false;
        }
    } else if (type == 'point') {
        var reg = /\D+/;
        if (value == "" || reg.test(value)) {
            top.alertBox.init("msg='请正确填写信息!'");
            return false;
        }
    }
    
    if (accountform.accountValue.value == '' || accountform.note.value == '') {
        top.alertBox.init("msg='请填写必填项!'");
        return false;
    }
    return true;
}

function chgrank(rank)
{
    new Request({
        url: '/admin/member/chgrank/id/' + rank,
        onRequest: loading,
        onSuccess:function(data){
	        if (data != '') {
	            $('discount').value = data;
	        }
            loadSucess();
        }
    }).send();
}
</script>