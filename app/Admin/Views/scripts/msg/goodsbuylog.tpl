<form name="myForm1" id="myForm1">
<div class="search">
    该商品评论个数：{{$commentcnt}}，购买总记录：<input type="text" size="5" name="buy_total" value="{{$info.buy_total}}"> 
    <font color="red">为增强信息可信性，请参考评论个数填写。</font><br>
    最后更新时间：{{$info.update_time|date_format:"%Y-%m-%d %H:%M:%S"}}
</div>


    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>序号</td>
            <td>用户名称</td>
            <td>会员等级</td>
			<td>购买时间</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$data item=item key=key}}
    <tr id="ajax_list{{$item.msg_id}}">
        <td>{{$key+1}}</td>
        <td><input type="text" size="30" name="buy_log[{{$key}}][]" value="{{$item.user_name}}"></td>
        <td><input type="text" size="15" name="buy_log[{{$key}}][]" value="{{$item.rank_name}}"></td>
        <td><input type="text" size="18" name="buy_log[{{$key}}][]" value="{{$item.add_time}}"></td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
<div class="submit"><input type="button" name="dosubmit1" id="dosubmit1" value="提交" onclick="dosubmit()"/>
<input type="reset" name="reset" value="重置" /></div>
</form>

<script language="JavaScript">
function dosubmit()
{
    if(confirm('确认内容无误吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}
</script>