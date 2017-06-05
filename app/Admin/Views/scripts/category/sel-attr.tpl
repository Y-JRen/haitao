<div>
<form name="myForm2" id="myForm2">
<input type="hidden" name="cat_id" value="{{$category.cat_id}}">
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td width="20px"><br><br><br></td>
<td><b><font color="red">{{$category.cat_name}}</font> 选择下列顶级属性：</b></td>
<td>
</td>
</tr>
{{foreach from=$attrData key=attrID item=data}}
<tr>
<td>&nbsp;</td>
<td>
<input type="checkbox" name="attrID[]" value="{{$attrID}}" {{if $catAttrInfo[$attrID]}}checked{{/if}} onclick="checkAll({{$attrID}}, this.checked)"> <b>{{$data.name}}</b>
</td>
</tr>
{{if $data.detail}}
<tr>
<td>&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
{{foreach from=$data.detail item=detail}}
<input type="checkbox" name="subAttrID[{{$attrID}}][]" value="{{$detail.attr_id}}" {{if $subAttrData[$attrID][$detail.attr_id]}}checked{{/if}}>
{{$detail.attr_title}}&nbsp;&nbsp;
{{/foreach}}
</td>
</tr>
{{/if}}
{{/foreach}}
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="20px">&nbsp;</td>
  <td>
    <br>
    <input type="button" name="submit" value="保存" onclick="ajax_submit($('myForm2'), '{{url}}')">
  </td>
</tr>
</table>
</form>
</div>
<script>
function checkAll(attrID, checked)
{
    var objects = document.getElementsByName('subAttrID[' + attrID + '][]')
    if (objects.length > 0) {
        for (var i = 0; i < objects.length; i++) {
            objects[i].checked = checked;
        }
    }
}
</script>