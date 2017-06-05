<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<input type="hidden" name="attr_path" value="{{$data.attr_path}}">
{{if $action eq 'add'}}
<input type="hidden" name="parent_id" value="{{$data.parent_id}}">
{{/if}}
<div class="title">{{if $action eq 'edit'}}编辑属性{{else}}添加属性{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    {{if $action eq 'add' and $data.parent_id>0}}
    <tr>
      <td width="10%"><strong>上级属性</strong></td>
      <td><font color="red">{{$data.parent_name}}</font></td>
    </tr>
    {{/if}}
    <tr> 
      <td width="10%"><strong>属性名称</strong> * </td>
      <td>
      {{if $action eq 'add' and $data.cat_id>0}}
       <select name="attr_title" onchange="$('attr_key').value=this.value.split(',')[0];$('attr_value').value=this.value.split(',')[1];">
	      <option value="-1" selected>请选择</option>
	      {{foreach from=$datas item=item}}
	      <option value="{{$item.attr_key}},{{$item.attr_value}}">{{$item.attr_title}}</option>
	      {{/foreach}}
	   </select>
      {{else}}
      <input type="text" name="attr_title" size="30" value="{{$data.attr_title}}" msg="请填写属性名称" class="required" />
      {{/if}}
      </td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="attr_status" value="0" {{if $data.attr_status==0}}checked{{/if}}/> 是
	   <input type="radio" name="attr_status" value="1" {{if $data.attr_status==1}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>