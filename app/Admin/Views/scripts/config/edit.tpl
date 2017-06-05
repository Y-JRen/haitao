<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data">
<div class="title">{{$title}}</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=index}}')">设置列表</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tbody>
    <tr>
        <td width="10%">变量名称 * </td>
        <td width="40%"><input type="text" name="name" id="name" msg="请填写变量名称" class="required" size="30" maxlength="40" value="{{$config.name}}" {{if $action eq 'edit'}}readonly{{else}}onchange="ajax_check('{{url param.action=check}}','name')"{{/if}} /></td>
        <td width="10%">变量显示 * </td>
        <td width="40%"><input type="text" name="title" msg="请填写变量显示" class="required" size="30" maxlength="80" value="{{$config.title}}" /></td>
    </tr>
    <tr>
        <td width="10%">变量类型</td>
        <td width="40%"><select name="type" onchange="showOptions(this.value)">{{html_options options=$typeOptions  selected=$config.type}}</select></td>
        <td width="10%">隶属分类</td>
        <td width="40%"><select name="parent_id">{{html_options options=$catOptions selected=$config.parent_id}}</select></td>
    </tr>
    <tr id="options" style="display:{{if $config.type neq 'radio' and $config.type neq 'checkbox' and $config.type neq 'select'}}none{{/if}}">
        <td width="10%">变量选项</td>
        <td colspan="3">
            <div id="type_options">
            {{if $config.type_options}}
                {{foreach from=$config.type_options name=options  key=name item=title}}
                    {{if $smarty.foreach.options.iteration eq 1}}
                        <p><a onclick="addOption(this,'type_options')" href="javascript:fGo();">[+]</a> 值 <input type="text" name="type_key[]" size="20" value="{{$name}}" /> 显示文字 <input type="text" name="type_value[]" size="20" value="{{$title}}" /></p>
                    {{else}}
                        <p><a onclick="removeOption(this,'type_options')" href="javascript:fGo();">[- ]</a> 值 <input type="text" name="type_key[]" size="20" value="{{$name}}" /> 显示文字 <input type="text" name="type_value[]" size="20" value="{{$title}}" /></p>
                    {{/if}}
                {{/foreach}}
            {{else}}
                <p><a onclick="addOption(this,'type_options')" href="javascript:fGo();">[+]</a> 值 <input type="text" name="type_key[]" size="20" /> 显示文字 <input type="text" name="type_value[]" size="20" /></p>
            {{/if}}
            </div>
        </td>
    </tr>
    <tr>
        <td width="10%">变量说明</td>
        <td colspan="3"><textarea name="notice" style="width:500px; height:50px">{{$config.notice}}</textarea></td>
    </tr>
    </tbody>
    </table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function showOptions(type)
{
    if (type == 'radio' || type == 'checkbox' || type == 'select') {
        $('options').style.display = '';
    } else {
        $('options').style.display = 'none';
    }
}

function addOption(obj, div)
{
	var p = document.createElement("p");
	p.innerHTML = obj.parentNode.innerHTML.replace(/(.*)(addOption)(.*)(\[)(\+)/i, "$1removeOption$3$4- ");
	$(div).appendChild(p);
}

function removeOption(obj, div)
{
    $(div).removeChild(obj.parentNode);
}
</script>