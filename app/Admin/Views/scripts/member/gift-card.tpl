{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
		<form name="searchForm" id="searchForm">
			<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="user_name" value="{{$param.user_name}}" size="15" />
			
			卡号 <input type="text" name="card_sn" value="{{$param.card_sn}}" size="15" />
			
			<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=gift-card param.do=search}}','ajax_search')"/>
		</form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title">会员礼品卡信息查询  </div>
<div class="content">
     <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
		    <td>用户ID</td>
			<td>用户名</td>
			<td>卡号</td>
			<td>面值</td>
			<td>剩余金额</td>
			<td>生成时间</td>
			<td>结束日期</td>
			<td>使用时间</td>
			<td>是否过期</td>
			<td>状态</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$gift_card_list item=list name=list}}
        <tr >
            <td>{{$list.user_id}}</td>
			<td>{{$list.user_name}}</td>
			<td>{{$list.card_sn}}</td>
			<td>{{$list.card_price}}</td>
			<td>{{$list.card_real_price}}</td>
			<td>{{$list.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td>{{$list.end_date}}</td>
            <td>{{$list.using_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td>{{if $curtime > $list.end_date}}<font color="#FF0000">已过期</font>{{else}}<font color="#009900">未过期</font>{{/if}}</td>
            <td>
              {{if $list.status eq 0}}
                <font color="#009900">有效</font>
              {{elseif $list.status eq 1}}
                <font color="#FF0000">无效</font>
              {{elseif $list.status eq 2}}
                <font color="#FF0000">未激活</font>
              {{/if}}
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
