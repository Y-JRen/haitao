{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>

<div class="search">
<form name="searchForm" id="searchForm">
<div>
<span style="float:left">注册日期从：
<input type="text"  value="{{$param.reg_fromdate}}" id="reg_fromdate"  name="reg_fromdate"   class="Wdate"   onClick="WdatePicker()" ></span>
<span style="float:left; margin-left:10px">截止到：<input  type="text"  value="{{$param.reg_todate}}" id="reg_todate"  name="reg_todate"   class="Wdate"   onClick="WdatePicker()" ></span>
<span style="float:left; margin-left:10px">最后登陆日期从：<input  type="text"  value="{{$param.log_fromdate}}" id="reg_todate"  name="log_fromdate"   class="Wdate"   onClick="WdatePicker()">
</span>
<span style="float:left; margin-left:10px">截止到：<input type="text"  value="{{$param.log_todate}}" id="reg_todate"  name="log_todate"   class="Wdate"   onClick="WdatePicker()" >
</span>
</div>
    <div style="clear:both; padding-top:5px">
    <span style="margin-left:5px; vertical-align:top">会员等级: </span>
    <select name="rank_id"><option value="">请选择</option>
        {{html_options options=$member_ranks selected=$param.rank_id}}}}
    </select>
<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="user_name"  value="{{$param.user_name}}"  size="15" />	
积分大于<input type="text" name="pointfrom"  value="{{$param.pointfrom}}"  size="10" /> 
小于<input type="text" name="pointto"  value="{{$param.pointto}}" size="10" />      
余额大于<input type="text" name="moneyfrom"  value="{{$param.moneyfrom}}"  size="10" /> 
小于<input type="text" name="moneyto"  value="{{$param.moneyto}}" size="10" />　
经验值大于<input type="text" name="experiencefrom"  value="{{$param.experiencefrom}}"  size="10" /> 
小于<input type="text" name="experienceto"  value="{{$param.experienceto}}" size="10" />　　 
<input type="button" name="dosearch" value="选择搜索" onclick="ajax_search(this.form,'{{url param.action=index param.do=search}}','ajax_search')"/>
  <!--[<a href="{{url param.action=export-user}}?{{$smarty.server.QUERY_STRING}}" target="_blank">导出用户信息</a>]
<input type="button" onclick="G('{{url param.action=exportmobile}}')" value="导出用户手机号码">-->
</div>
</form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title">会员管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加会员</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
            <td>会员名称</td>
			<td>积分</td>
			<td>余额</td>
            <td>经验值</td>
            <td>注册时间</td>
            <td>最后登陆时间</td>
			<td>登陆次数</td>
			<td>CPS</td>
			<td>推荐</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$member_list item=member name=member}}
        <tr id="ajax_list{{$member.user_id}}">
            <td><!--<input type="checkbox" name=ids value="{{$member.user_id}}" />-->{{$member.user_id}}</td>
            <td>{{$member.user_name|truncate:20:"..."}}</td>
			 <td>{{$member.point}}点</td>
			 <td>{{$member.money}}元</td>
             <td>{{$member.experience}}</td>
            <td>{{$member.add_time}}</td>
            <td>{{$member.last_login}}</td>
			<td>{{$member.login_count}}</td>
			<td>{{if $member.parent_id}}{{$member.parent_id}}|{{$member.parent_user_name|truncate:20:"..."}}{{/if}}</td>
			<td>{{if $member.tj_user_id}}{{$member.tj_user_id}}|{{$member.tj_user_name|truncate:20:"..."}}{{/if}}</td>
            <td id="ajax_status{{$member.user_id}}">{{$member.status}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=view param.id=$member.user_id}}')">查看</a> | 
                <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$member.user_id}}')">编辑</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
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
{{/if}}