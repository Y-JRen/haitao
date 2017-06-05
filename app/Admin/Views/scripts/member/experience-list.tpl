{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
		<form name="searchForm" id="searchForm">
		<span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="start_ts" id="start_ts" size="15" value="{{$params.start_ts}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">截止日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="end_ts" id="end_ts" size="15" value="{{$params.end_ts}}"  class="Wdate"  onClick="WdatePicker()"/></span>	
			<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="nick_name" value="{{$params.nick_name}}" size="15" />
			<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=experience-list param.do=search}}','ajax_search')"/>
		</form>
</div>
{{/if}}

<div id="ajax_search">
<div class="title">积分变动历史</div>
<div class="content">
     <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
			<td>用户昵称</td>
            <td>经验值变动时间</td>
            <td>经验值</td>
			<td>经验值变动</td>
            <td>变动原因</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$infos item=info}}
        <tr>
            <td>{{$info.member_id}}</td>
			<td>{{$info.nick_name}}</td>
			<td>{{$info.created_ts}}</td>
            <td>{{$info.experience_total}}</td>
            <td>{{$info.experience}}</td>
            <td>{{$info.remark}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>

</div>