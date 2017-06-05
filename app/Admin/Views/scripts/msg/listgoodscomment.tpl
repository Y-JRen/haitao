<div id="goods_comment">
<div class="title">商品评论列表</div>
<div class="content">

    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>评价</td>
            <td>用户</td>
			<td>热点评论</td>
            <td width="200px">留言内容</td>
            <td>留言时间</td>
			<td>是否审核</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$data item=item}}
    <tr id="ajax_list{{$item.msg_id}}">
        <td>外 观：{{$item.cnt1}}<br>舒适度：{{$item.cnt2}}</td>
        <td>{{$item.user_name|truncate:12:"..."}}</td>
		       <td >{{if $item.is_hot eq 1}} <font color="#FF3300">是 </font>{{else }}否{{/if}}</td>
        <td><textarea rows="3" cols="39" style="width:300px; height:80px;">{{$item.content}}</textarea></td>
        <td>{{$item.add_time}}<br>IP:{{$item.ip}}</td>
        <td>{{if $item.status==1}}已通过{{elseif $item.status==2}}已拒绝{{else}}<font color="red">未审核</font>{{/if}}<br>{{if !empty($item.reply)}}已回复{{else}}<font color="red">未回复</font>{{/if}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div class="page_nav">{{$pageNav}}</div>
</div>
