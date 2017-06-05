
{{if !$param.do}}
<form name="searchForm" id="searchForm">
<div class="search">
管理员：<input type="text" name="admin_name" size="12" maxLength="50" value="{{$param.admin_name}}"/>
ip：<input type="text" name="login_ip" size="12" maxLength="50" value="{{$param.login_ip}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
</div>
</div>
</form>
{{/if}}



<div class="title">管理员登录日志</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td >ID</td>
            <td >用户名</td>
            <td>登录时间</td>
            <td>登录IP</td>
			<td>登录地点</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.try_id}}">
			<td>{{$data.log_id}}</td>
			<td>{{$data.admin_name}}</td>
			<td>{{$data.login_time}}</td>
		    <td>{{$data.login_ip}}</td>
            <td>{{$data.ip_address}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
   <div class="page_nav">{{$pageNav}}</div>
</div>