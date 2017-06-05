<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">添加分类</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>名称</strong> * </td>
      <td><input type="text" name='cat[cat_name]' value='{{$cat.cat_name}}' size="30" msg="请填写名称" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>类别</strong> * </td>
      <td><select name='cat[parent_id]'>
		<option value=''>--顶级目录--</option>
		{{foreach from=$catTree key=cat_id item=item}}
		<option {{if $item.num}}disabled="disabled"{{/if}} value={{$cat_id}} style="padding-left:{{$item.step*20-20}}px" {{if $catID==$cat_id}}selected="selected"{{/if}}>{{$item.cat_name}}{{if $item.num}} [{{$item.num}}]{{/if}}</option>
		{{/foreach}}
	</select></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta标题</strong> * </td>
      <td><input type="text" name="cat[meta_title]" size="30" value="{{$cat.meta_title}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta关键字</strong> * </td>
      <td><input type="text" name="cat[meta_keywords]" size="30" value="{{$cat.meta_keywords}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta描述</strong> * </td>
      <td><textarea name="cat[meta_description]" rows="3" cols="39" id="meta_description" style="width:330px; height:45px;">{{$cat.meta_description}}</textarea></td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>