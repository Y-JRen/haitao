<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">商品留言管理</div>
<div class="content">
<div class="search">
    <form id="searchForm">
<div style="clear:both; padding-top:5px">
    <span style="float:left;line-height:18px;">开始日期：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">结束日期：</span>
    <span style="float:left;width:100px;line-height:18px;"><input  type="text" name="todate" id="todate" size="12" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
    审核状态：
    <select name="status">
	  <option value="">请选择</option>
	  <option value="0" {{if $param.status eq '0'}}selected{{/if}}>未审核</option>
	  <option value="1" {{if $param.status eq '1'}}selected{{/if}}>已审核</option>
	  <option value="2" {{if $param.status eq '2'}}selected{{/if}}>已拒绝</option>
	</select>
	热点评论：
	<select name="is_hot">
	  <option value="">请选择</option>
	  <option value="1" {{if $param.is_hot eq '1'}}selected{{/if}}>是</option>
	  <option value="0" {{if $param.is_hot eq '0'}}selected{{/if}}>否</option>
	</select>
	商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/>
    <input type="submit" name="dosearch" value=" 搜 索 "/>
    </div>	
    </form>
</div>
<form name="myForm" id="myForm">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            {{if $type eq 1}}
            <td>评价</td>
            {{/if}}
            <td>用户</td>
            <td>商品名称</td>
            {{if $type eq 1}}
			<td>热点</td>
            {{/if}}
            <td width="200px">留言内容</td>
            <td>留言时间</td>
			<td>是否审核</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$data item=item}}
    <tr id="ajax_list{{$item.msg_id}}">
        <td><input type="checkbox" name="ids[]" value="{{$item.goods_msg_id}}"/></td>
        {{if $type eq 1}}
        <td>外 观：{{$item.cnt1}}<br>口感：{{$item.cnt2}}</td>
        {{/if}}
        <td>{{$item.user_name|truncate:16:"..."}}</td>
        <td><a href="javascript:fGo()" onclick="window.open('http://www.1jiankang.com/goods/show/id/{{$item.goods_id}}')">{{$item.goods_name}}</a></td>
		 {{if $type eq 1}} <td >{{if $item.is_hot eq 1}} <font color="#FF3300">是 </font>{{else }}否{{/if}}</td>  {{/if}}
        <td><textarea rows="3" cols="23" style="width:200px; height:80px;">{{$item.content}}</textarea></td>
        <td>{{$item.add_time}}<br>IP:{{$item.ip}}</td>
        <td>{{if $item.status==1}}已通过{{elseif $item.status==2}}已拒绝{{else}}<font color="red">未审核</font>{{/if}}<br>{{if !empty($item.reply)}}已回复{{else}}<font color="red">未回复</font>{{/if}}</td>
       <td>
		<a href="javascript:fGo()" onclick="G('{{url param.action=goodsreplyform param.id=$item.goods_msg_id}}')">审核回复</a> | 
		<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delgoods}}','{{$item.goods_msg_id}}','{{url param.action=listgoods}}')">删除</a>
	</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="已审核" onclick="ajax_submit(this.form, '{{url param.action=check-goods-msg}}/val/1')">
<input type="button" value="已拒绝" onclick="ajax_submit(this.form, '{{url param.action=check-goods-msg}}/val/2')">
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
