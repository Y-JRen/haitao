<form name="myForm1" id="myForm1">
<div class="title">入库单确认</div>
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

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
<tr>
    <td>序号</td>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>应收数量</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$details item=d key=key}}
	<tr>
	<td>{{$key+1}}</td>
	<td>{{$d.product_sn}}</td>
	<td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>)</td>
	<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}<input type="hidden" name="batch_id[]" value="{{if $d.batch_id}}{{$d.batch_id}}{{else}}0{{/if}}"></td>
	<td>{{$d.plan_number}}</td>
	</tr>
	{{/foreach}}
</tbody>
</table>
<div style="text-align:right;padding:10px 20px"><strong>数量合计：</strong>{{$data.total_number}}</div>
</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name}}
<input type="button" onclick="window.open('{{url param.action=print param.id=$data.instock_id}}')" value="打印">
<input type="button" name="dosubmit1" value="确认" onclick="if(confirm('确认此单据吗？')){ajax_submit($('myForm1'),'{{url}}');}"/>
{{/if}}
</div>

</form>