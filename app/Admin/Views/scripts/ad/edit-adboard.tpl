<div class="title">编辑广告位</div>
<form  name="myForm" id="myForm"  action="/admin/ad/edit-adboard" method="post" onsubmit="return check_post()">
<div class="content">
<table width="100%" cellspacing="1" cellpadding="2" class="table_form">
<tbody><tr><th width="80">广告位名称 :</th><td><input type="text" size="30"  value="{{$adboardInfo.name}}"  class="input-text" id="name" name="name">
<div id="nameTip" class="onShow">请输入广告位名称</div></td></tr>
<tr><th width="80">广告位类型 :</th><td>
<select name="tpl">
{{foreach from=$tplOptions item=item key=key}}
<option value="{{$key}}" {{if $key eq $adboardInfo.tpl}}selected{{/if}}>{{$item.name}}</option>
{{/foreach}}
</select></td></tr>

<tr><th>广告位尺寸 :</th>
<td>宽 : <input type="text"  value="{{$adboardInfo.width}}"  size="6" class="input-text" id="width" name="width"> px&nbsp;&nbsp;&nbsp;&nbsp;
高 : <input type="text"  value="{{$adboardInfo.height}}" size="6" class="input-text" id="height" name="height"> px</td></tr>
<tr><th>广告位说明 :</th><td><textarea name="description" id="description" class="input-textarea" cols="45" rows="4">{{$adboardInfo.description}}</textarea></td></tr>

<tr><th>是否启用 :</th><td>
<label><input type="radio" {{if $adboardInfo.status eq 1}}checked=""{{/if}} value="1" name="status">是</label>&nbsp;&nbsp;
<label><input type="radio" {{if $adboardInfo.status eq 0}}checked=""{{/if}}  value="0" name="status">否</label></td></tr></tbody></table>
</div>

<div class="submit">
<input type="hidden" value="{{$adboardInfo.id}}" name="id" />
<input type="hidden" value="submitted" name="submitted" />
<input type="submit" value="确定" id="dosubmit" name="dosubmit"> <input type="reset" value="重置" name="reset"></div>
</form>
<script>
function check_post()
{
	 if($("name").value	== '')
	 {
	    alert("请输入广告位名称！");
	    $("name").focus();
	    return false;
	 }
	 return true;
}
</script>