<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" onsubmit="return check();" action="/admin/message/index">
<div>
    <span style="float:left">开始日期：
        <input type="text"  value="{{$params.start_ts}}" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        截止日期：<input  type="text"  value="{{$params.end_ts}}" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">信息类型：
        <select name="type">
        <option value="">请选择</option>
        {{html_options options=$search_option.message_type selected=$params.type}}
        </select>
    </span>
</div>
<input type="submit" name="dosearch" value="查询" />
</div>
</form>
</div>
<div class="title">信息列表</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加站内信</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>信息ID</td>
            <td>信息类型</td>
            <th>发送给谁</th>
            <td>标题</td>
            <td>创建</td>
			<td>创建时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$infos item=info}}
        <tr>
            <td>{{$info.message_id}}</td>
            <td>{{$info.message_type}}</td>
            <td>{{$info.to_whos}}</td>
            <td>{{$info.title}}</td>
            <td>{{$info.created_by}}</td>
            <td>{{$info.created_ts}}</td>
            <td><a onclick="openDiv('/admin/message/view/message_id/{{$info.message_id}}','ajax','查看站内消息 ',750,350)" href="javascript:fGo()">查看</a></td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
