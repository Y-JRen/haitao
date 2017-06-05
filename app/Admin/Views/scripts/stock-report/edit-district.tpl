<form name="myForm" id="myForm" action="{{url}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑库区{{else}}添加库区{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>库区名称</strong> * </td>
      <td><input type="text" name="district_name" id="district_name" size="20" value="{{$data.district_name}}" msg="请填写库区名称" class="required" /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>库区编号</strong> * </td>
      <td><input type="text" name="district_no" id="district_no" size="10" value="{{$data.district_no}}" msg="请填写库区编号" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>所属仓库</strong> * </td>
      <td>
        <select name="area">
          {{foreach from=$areas item=item key=key}}
          {{if $area eq $key}}
          <option value="{{$key}}" {{if $data.area eq $key}}selected{{/if}}>{{$item}}</option>
          {{/if}}
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr> 
      <td><strong>备注</strong></td>
      <td>
		<input type="text" name="memo" id="memo" size="50" value="{{$data.memo}}"/>
	  </td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="status" value="0" {{if $data.status==0 && $action eq 'edit'}}checked{{/if}}/> 是
	   <input type="radio" name="status" value="1" {{if $data.status==1 or $action eq 'add'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定"/> <input type="reset" name="reset" value="重置" /></div>
</form>
