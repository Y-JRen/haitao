{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
    <span style="float:left">注册日期从：<input type="text" name="reg_fromdate" id="reg_fromdate" size="11" value=""   class="Wdate"   onClick="WdatePicker()"/></span>
    <span style="float:left; margin-left:10px">截止到：<input type="text" name="reg_todate" id="reg_todate" size="11" value=""  class="Wdate"   onClick="WdatePicker()"/></span>
    <span style="float:left; margin-left:10px">最后登陆日期从：<input type="text" name="log_fromdate" id="log_fromdate" size="11" value=""  class="Wdate"   onClick="WdatePicker()"/></span>
    <span style="float:left; margin-left:10px">截止到：<input type="text" name="log_todate" id="log_todate" size="11" value=""  class="Wdate"   onClick="WdatePicker()"/></span>
    <br><br><br>
    会员等级: 
    <select name="rank_id">
        {{html_options options=$member_ranks}}
    </select>
    会员名或昵称: </span><input type="text" name="user_name" value="" size="15" />
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=phone param.do=search}}','ajax_search')"/>
</form>
<form name="myForm" id="myForm" action="{{url param.action=quick-reg}}" method="post">
    <div style="clear:both; padding-top:5px">
    会员名: <input type="text" name="user_name" id="user_name" value="" size="15" msg="请填写会员名" class="required" />
    <input type="submit" name="dosubmit" value="快速注册"/>
    </div>
</div>
</form>
{{/if}}
<div id="ajax_search">
<div class="title">会员管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加会员</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>会员名称</td>
            <td>手机</td>
            <td>注册时间</td>
            <td>最后登陆时间</td>
            <td>状态</td>
            <td>操作</td>
            <td>电话下单</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$member_list item=member name=member}}
        <tr id="ajax_list{{$member.user_id}}">
            <td>{{$member.user_id}}</td>
            <td>{{$member.user_name|truncate:20:"..."}}</td>
            <td>{{$member.mobile}}</td>
            <td>{{$member.add_time}}</td>
            <td>{{$member.last_login}}</td>
            <td id="ajax_status{{$member.user_id}}">{{$member.status}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=view param.id=$member.user_id}}')">查看</a> | 
                <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$member.user_id}}')">编辑</a>
            </td>
            <td>
             <a href="http://www.1jiankang.com/shop/auth/mix-login/code/{{$member.auth_code}}/operator_id/{{$operator_id}}" target="_blank">电话下单</a> 
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
<div class="page_nav">{{$pageNav}}</div>
</div>
<script>
function multiDelete()
{
    checked = multiCheck($('table'),'ids',$('doDelete'));
    if (checked != '') {
        reallydelete('{{url param.action=delete}}', checked);
    }
}
</script>
</div>