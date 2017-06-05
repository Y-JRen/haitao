<div class="member">
    {{include file="member/menu.tpl"}}
  <div class="memberright"> 
	<div style="margin-top:11px;"><img src="{{$imgBaseUrl}}/images/shop/member_note.png"></div>
    <div class="ordertype">
        <a href="/member/inside-message/read/0" {{if $params.read eq 0}} class="sel" {{/if}}>未读</a>
        <a href="/member/inside-message/read/1" {{if $params.read eq 1}} class="sel" {{/if}}>已读</a>
    </div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
        <thead>
            <tr>
                <th>类型</th>
                <th align="left">标题</th>
                <th>发送时间</th>
            </tr>
        </thead>
        <tbody >
            {{foreach from=$infos item=info}}
            <tr>
                <td>{{$info.message_type}}</td>
                <td class="title" message_id="{{$info.message_id}}" align="left" read="{{$info.is_read}}">{{$info.title}}</td>
                <td>{{$info.created_ts}}</td>
            </tr>
            {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
    <div style="display:none;" id="message_content">
        <table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
            <tr>
                <td width="200">标题：</td>
                <td id="list_title" style="text-align:left"></td>
            </tr>
            <tr>
                <td>内容：</td>
                <td id="list_content" style="text-align:left"></td>
            </tr>
        </table>
    </div>
  </div>
</div>

<script>
    $(function(){
        $(".title").css({'color':'#0000ff', 'text-decoration': 'underline', 'cursor': 'pointer'});
        $(".title").click(function(){
            var message_id = parseInt($(this).attr('message_id'));
            var read       = $(this).attr('read');
            var obj        = $(this);
            if (message_id < 1) {
                alert('信息ID不正确');
                return false;
            }

            $.ajax({
				url : '/member/view-message/message_id/' + message_id +'/read/'+read,
				success : function(data) {
                    data = $.parseJSON(data);
                    if (data.success == 'false') {
                        alert(data.message);
                        return false;
                    } else {
                        obj.attr('read', 1);
                        var info = data.data;
                        $("#list_title").html(info.title);
                        $("#list_content").html(info.content);
                        $("#message_content").css({'display': 'block'});
                    }
                }
                
                
            })
        })
    })
</script>