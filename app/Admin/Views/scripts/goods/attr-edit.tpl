<div>
<form name="myForm2" id="myForm2">
<input type="hidden" name="product_id" value="{{$product.product_id}}">
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td width="10px">&nbsp;</td>
<td>产品名称：{{$product.product_name}}</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>产品编码：{{$product.product_sn}}</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
{{foreach from=$attrInfo item=mainAttr}}
<tr>
<td>&nbsp;</td>
<td><input type="checkbox" id="main1_{{$mainAttr.attr_id}}" value="1" onclick="clearAttrCheck1('{{$mainAttr.attr_id}}', this.checked)" {{if $parentAttrIDInfo[$mainAttr.attr_id]}}checked{{/if}}> <b>{{$mainAttr.attr_title}}</b></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>
&nbsp;&nbsp;&nbsp;
{{foreach from=$mainAttr.detail item=attr}}
<input type="radio" name="attrID1_{{$mainAttr.attr_id}}[]" value="{{$attr.attr_id}}" onclick="$('main1_{{$mainAttr.attr_id}}').checked = true" {{if $attrIDInfo[$attr.attr_id]}}checked{{/if}}>&nbsp;{{$attr.attr_title}}&nbsp;&nbsp;
{{/foreach}}
</td>
</tr>
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

</script>