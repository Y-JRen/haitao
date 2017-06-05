<form id="myform1">
<select name='pay_type'>
{{foreach from=$payment item=item}}
    <option value={{$item.pay_type}} {{if $pay_type==$item.pay_type}}selected="selected"{{/if}}>{{$item.name}}</option>
{{/foreach}}
</select>
<input type="button" value="确定" onclick="ajax_submit($('myform1'),'{{url param.action=edit-payment}}')" />
<input type="button" onclick="G('{{url param.action=not-confirm-info}}')" value=" 返回订单页 " name="do"/>
</form>
