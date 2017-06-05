<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<input type="hidden" name="logic_area" value="{{$logic_area}}">
<input type="hidden" name="bill_type" value="{{$data.bill_type}}">
<input type="hidden" name="item_no" value="{{$data.item_no}}">
<input type="hidden" name="parent_id" value="{{$data.parent_id}}">
<div class="title">入库单结束</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}{{if $data.bill_type eq 2}}({{if $data.purchase_type eq 1}}经销{{else}}代销{{/if}}){{/if}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>{{$data.bill_no}}</td>
    </tr>
    <tr>
      <td><strong>制单日期</strong></td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong></td>
      <td>{{$data.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="3">&nbsp;{{$data.remark}}</td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
<thead>
<tr>
    <td>序号</td>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>本次应收</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$details item=d key=key}}
	<tr>
	<td>{{$key+1}}</td>
	<td>{{$d.product_sn}}</td>
	<td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>) </td>
	<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}<input type="hidden" name="batch_id[]" value="{{if $d.batch_id}}{{$d.batch_id}}{{else}}0{{/if}}"></td>
	<td>{{$d.plan_number}}<input type="hidden" name="plan_number[]" value="{{$d.plan_number}}"><input type="hidden" name="product_id[]" value="{{$d.product_id}}"><input type="hidden" name="status_id[]" value="{{$d.status_id}}"><input type="hidden" name="shop_price[]" value="{{$d.shop_price}}"></td>
	</tr>
	{{/foreach}}
</tbody>
</table>

</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name}}
{{if $data.parent_id}}
<input type="button" name="dosubmit2" value="强制完成" onclick="if(confirm('确认强制完成吗？')){ajax_submit($('myForm1'),'{{url}}');}"/>
{{/if}}
{{/if}}
</div>
</form>