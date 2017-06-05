<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">商品分类品牌</div>
<div class="content">
<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'brand_ids',this)"/>  全选/全不选 
<table cellpadding="0" cellspacing="0" border="0" class="table">
<tbody>
<tr> 	
{{foreach from=$brandDatas key=key item=brands }}
		{{if  $key%6 eq 0}} </tr>	<tr>  			<td> <input type='checkbox' name="brand_ids[]" value="{{$brands.brand_id}}"   {{if $brands.is_check eq 1}}  checked {{/if}} > </td>
		<td>品牌名：{{$brands.brand_name}} </td> {{else}}   
		
					<td> <input type='checkbox' name="brand_ids[]" value="{{$brands.brand_id}}"   {{if $brands.is_check eq 1}}  checked {{/if}} > </td>
		<td>品牌名：{{$brands.brand_name}} </td>
		{{/if}}
{{/foreach}}
</tr>	
</tbody>
</table>
<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'brand_ids',this)"/>  全选/全不选 
</div>
<div class="submit">
<input type="hidden" name="cat_id" value="{{$cat.cat_id}}" />
<input type="submit" name="dosubmit" id="dosubmit" value="确定" />
<input type="reset" name="reset" value="重置" /></div>
</form>