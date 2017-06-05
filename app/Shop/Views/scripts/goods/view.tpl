{{$cnt.cnt1.pointshow}}
{{$cnt.cnt1.pointdetail}}
{{$cnt.cnt2.pointshow}}
{{$cnt.cnt2.pointdetail}}
{{if $datas}}
<div id="pagers" style="text-align:right; margin-top:10px;">{{$pageNav}}</div>
<table width="100%" cellpadding="6" cellspacing="0" style=" border-bottom:#ccc solid 1px; ">
  {{foreach from=$datas item=item}}
  <tr>
    <td colspan="2" style="background: #f2f2f2;padding:20px 10px; border-top:1px dashed #ccc">{{$item.content}}</td>
  </tr>
  <tr>
    <td height="15px"  style="background: #f2f2f2; border-bottom:3px solid #fff; padding:5px">评价等级：{{if $item.level==4}}很好{{elseif $item.level==3}}好{{elseif $item.level==2}}一般{{else}}差{{/if}}</td>
    <td align="right"  style="background: #f2f2f2; border-bottom:3px solid #fff">{{$item.user_name}}&nbsp;&nbsp;&nbsp;&nbsp;{{$item.add_time|date_format:'%Y-%m-%d %H:%M:%S'}}</td>
  </tr>
  {{if !empty($item.reply)}}
  <tr>
    <td colspan="2" valign="top" style="padding:5px;background:#e9e9e9"><span style="margin:10px; ">{{$item.reply}}</span></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="top" style="border-bottom:#fff solid 3px; background:#e9e9e9">{{$item.reply_time|date_format:'%Y-%m-%d %H:%M:%S'}}&nbsp;&nbsp;
	管理员: {{$item.admin}}&nbsp;&nbsp;回复</td>
  </tr>
  {{/if}}
  {{/foreach}}
</table>
<div id="pagers" style="text-align:right; margin-top:10px;">{{$pageNav}}</div>
{{/if}}