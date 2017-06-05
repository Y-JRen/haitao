<div class="title">商店参数设置</div>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data" />
<div class="title" style="height:25px;">
	<ul id="show_tab">
	{{foreach name=cate from=$cate key=key item=title}}
	   <li onclick="show_tab({{$key}})" id="show_tab_nav_{{$key}}" class="{{if $key eq 1}}bg_nav_current{{else}}bg_nav{{/if}}">{{$title}}</li>
    {{/foreach}}
	</ul>
</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加设置</a> ]
    </div>
    {{foreach name=cate from=$cate key=key item=title}}
    <div id="show_tab_page_{{$key}}" style="display:{{if $key eq 1}}block{{else}}none{{/if}}">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
        <tbody>
        {{foreach name=optionForm from=$optionFrom.$key key=id item=option}}
            <tr id="ajax_list{{$id}}">
                <td width="30%" class="desc">
                    <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$id}}')" title="编辑设置"><img src="/haitaoadmin/images/edit.png" border="0" /></a> 
                    <a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delete}}','{{$id}}')" title="删除设置"><img src="/haitaoadmin/images/delete.png" /></a> 
                    {{if $option.notice}}
                        <a href="javascript:fGo()" onclick="showNotice('notice_{{$id}}')" title="点击此处查看提示信息"><img src="/haitaoadmin/images/notice.gif" width="16" height="16" border="0" alt="点击此处查看提示信息" /></a> 
                    {{/if}}
                    {{$option.title}}</td>
                <td width="70%">
                    {{$option.option}}
                    {{if $option.notice}}
                        <br /><span class="notice" id="notice_{{$id}}">{{$option.notice}}</span>
                    {{/if}}
                </td>
            </tr>
        {{/foreach}}
        </tbody>
        </table>
    </div>
    {{/foreach}}
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function showNotice(id)
{
    var obj = $(id);

    if (obj) {
        if (obj.style.display != "block") {
            obj.style.display = "block";
        } else {
            obj.style.display = "none";
        }
    }
}
</script>