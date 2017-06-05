<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑状态{{else}}添加状态{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="10%"><strong>状态名称</strong> * </td>
      <td><input type="text" name="name" size="30" value="{{$data.name}}" msg="请填写状态名称" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>备注</strong></td>
      <td><textarea style="width: 400px;height: 50px" name="remark">{{$data.remark}}</textarea></td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="disabled" value="0" {{if $data.disabled==0 && $action eq 'edit'}}checked{{/if}}/> 是
	   <input type="radio" name="disabled" value="1" {{if $data.disabled==1 or $action eq 'add'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>