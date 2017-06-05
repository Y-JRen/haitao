{{if !$param.do}}
<form name="searchForm" id="searchForm">
<div class="search">
<div class="line">
<select name="type">
<option value="">选择留言类型</option>
<option value="0"   {{if $param.type eq '0'}}selected{{/if}} >留言</option>
<option value="1" {{if $param.type eq '1'}}selected{{/if}} >投诉</option>
<option value="2" {{if $param.type eq '2'}}selected{{/if}} >询问</option>
<option value="3" {{if $param.type eq '3'}}selected{{/if}} > 售后</option>
<option value="4" {{if $param.type eq '4'}}selected{{/if}} >求购</option>
<option value="5" {{if $param.type eq '5'}}selected{{/if}} >留言板</option>
<option value="7" {{if $param.type eq '7'}}selected{{/if}} >专家问答</option>
</select>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
</div>
</div>
</form>
{{/if}}

<div id="ajax_search">
<div class="title">站点留言管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>留言类型</td>
            <td>用户</td>
            <td>留言内容</td>
            <td>留言时间</td>
            <td>是否热门</td>
			<td>是否审核</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$data item=item}}
    <tr id="ajax_list{{$item.msg_id}}">
        <td>{{$item.type}}</td>
        <td><a href="/admin/member/view/id/{{$item.user_id}}" target="_blank">{{$item.user_name}}</a><br>联系：{{$item.contact}}<br>Email:{{$item.email}}<br>{{if $item.order_count}}<a href="/admin/order/list/dosearch/search/user_name/{{$item.user_name}}" target="_blank">{{$item.order_count}}个订单</a>{{/if}}</td>
        <td><textarea rows="5" cols="39" style="width:300px; height:80px;">{{$item.content}}</textarea></td>
        <td>{{$item.add_time}}<br>IP:{{$item.ip}}</td>
        <td>{{if $item.is_hot}}<b style="color:#F00">热门</b>{{else}}<b>否</b>{{/if}}</td>
        <td>{{if $item.status==1}}已通过{{elseif $item.status==2}}已拒绝{{else}}<font color="red">未审核</font>{{/if}}<br>{{if !empty($item.reply)}}已回复{{else}}<font color="red">未回复</font>{{/if}}</td>
       <td>
		<a href="javascript:fGo()" onclick="G('{{url param.action=sitereplyform param.id=$item.msg_id}}')">审核回复</a> | 
		<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delsite}}','{{$item.msg_id}}','{{url param.action=listsite}}')">删除</a>
	</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</div>