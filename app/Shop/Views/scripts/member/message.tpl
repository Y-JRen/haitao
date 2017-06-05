<div class="member">

    {{include file="member/menu.tpl"}}
  <div class="memberright">
	<div style="margin-top:10px;"><img src="{{$imgBaseUrl}}/images/shop/member_message.png"></div>

            <form action="{{url param.action=$action}}" method="post" name="formMsg" id="formMsg" onsubmit="return messageSubmit()" target="ifrmSubmit">
                <table width="100%" cellspacing="0" cellpadding="0" border="0"  class="table_re">
                    <tr>
                        <th>留言类型</th>
                        <td height="40">{{html_radios name="msg_type" options=$msgType checked=0 separator=" "}}</td>
                    </tr>
                    <tr>
                        <th>留言内容</th>
                        <td height="160"><textarea name="msg_content" cols="50" rows="4" wrap="virtual"></textarea></td>
                    </tr>
                    <tr><td>&nbsp;</td>
                        <td height="50"><input type="submit" name="dosubmit" id="dosubmit" value="提交留言" class="buttons2"/></td>
                    </tr>
                </table>
            </form>
    <div id='message'>
            {{if $messageInfo}}
            <div class="remind-txt text-left"><strong>历史留言</strong></div>
            <table width="754" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    {{foreach from=$messageInfo item=message}}
                    <tr>
                        <td style="background: #f2f2f2;padding:6px 10px;border-top:1px solid #fff;border-bottom:1px solid #dfdfdf;" align="left">{{$message.content}}</td>
                    </tr>
                    <tr>
                        <td height="20px"  style="background: #f9f9f9;border-top:1px solid #fff;border-bottom:1px solid #ccc;">{{if $type eq 'shop'}}<div style="float: left">留言类型：{{$message.type}}</div>{{/if}}<div style="float: right;color:#ff8400;">{{$message.add_time|date_format:'%Y-%m-%d %H:%M:%S'}}&nbsp;</div></td>
                    </tr>
                    {{if !empty($message.reply)}}
                    <tr>
                        <td valign="top" style="padding:5px;background:#e9e9e9" align="left"><span style="margin:10px; ">{{$message.reply}}</span></td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" style="border-bottom:#fff solid 3px; background:#e9e9e9"><div style="float: right">{{$message.reply_time|date_format:'%Y-%m-%d %H:%M:%S'}}&nbsp;&nbsp;管理员: {{$message.admin}}&nbsp;&nbsp;回复&nbsp;</div></td>
                    </tr>
                    {{/if}}
                    {{/foreach}}
                </tbody>
            </table>
            <div class="page_nav" style="padding-top:10px">{{$pageNav}}</div>
            {{/if}}
{{if !$inner}}
<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script>
function messageSubmit(){
	var content=$.trim($('#formMsg textarea').val());
	if(content=='' || content.length>255){
		alert('请输入留言内容\n留言内容必须在255个字以内!');
		return false;
	}
	$('#dosubmit').attr('value','提交中..');
	$('#dosubmit').attr('disabled',true);
}
</script>
{{/if}}

  </div>
</div>

</div>












