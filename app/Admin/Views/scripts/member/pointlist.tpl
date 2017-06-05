{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
		<form name="searchForm" id="searchForm">
		<span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">截止日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>	
			<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="nick_name" value="{{$param.nick_name}}" size="15" />
			<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=pointlist param.do=search}}','ajax_search')"/>
		</form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title"> [ <a href="javascript:fGo()" onclick="G('{{url param.action=pointlist}}')">积分变动历史</a> ]    |        [ <a href="javascript:fGo()" onclick="G('{{url param.action=point-frequency}}')">积分变动频率查询</a> ] (此查询为近30天内的记录) </div>
<div class="content">
     <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
			<td>用户名</td>
			<td>用户昵称</td>
            <td>积分变动时间</td>
            <td>积分</td>
			<td>积分变动</td>
            <td>变动原因</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$pointlist item=point name=point}}
        <tr id="ajax_list{{$member.user_id}}">
            <td>{{$point.member_id}}</td>
			<td>{{$point.user_name}}</td>
			<td>{{$point.nick_name}}</td>
			<td>{{$point.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td>{{$point.point_total}}</td>
            <td>{{$point.point}}</td>
            <td>{{$point.note}}</td>
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
