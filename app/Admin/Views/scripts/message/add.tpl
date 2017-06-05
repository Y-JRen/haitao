<form name="myForm" id="myForm" action="/admin/message/add" method="post" onsubmit="return check();">
<div class="title"><a href="/admin/message/index">站内信列表</a>&nbsp;&nbsp;<a href="/admin/message/add">添加站内信</a></div>

<div class="content">

<div id="show_tab_page_1" style="display:block">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
        <tbody>
            <tr>
                <td width="10%">类型 * </td>
                <td width="40%">
                    <select name="type" id="type">
                    <option value="">请选择</option>
                    {{html_options options=$search_option.message_type}}
                    </select>
                </td>
            </tr>
            <tr>
                <td width="10%">发送给 * </td>
                <td width="40%">
                    <select name="to_who" id="to_who">
                    <option value="">请选择</option>
                    {{html_options options=$search_option.message_to_who}}
                    </select>
                </td>
            </tr>
            <tr>
                <td width="10%">标题 * </td>
                <td width="40%">
                    <input type="text" name="title" id="title" msg="请填写标题" class="required limitlen" min="3" max="50" size="20" maxlength="40" />
                    <span id="tip_title" class="errorMessage">请输入3-50个字符</span>
                </td>
            </tr>
            <tr>
                <td width="10%">内容 * </td>
                <td width="40%">
                    <textarea name="content" id="content" msg="请输入内容" class="required limitlen" min="3" max="200" cols="60" rows="10"></textarea>
                    <span id="tip_content" class="errorMessage">请输入3-255个字符</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function check()
{
    if ($("type").value == '') {
        alert('请选择类型');
        return false;
    }

    if ($("to_who").value == '') {
        alert('请选择发送给谁');
        return false;
    }
}
</script>