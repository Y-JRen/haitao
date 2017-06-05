<form name="myForm1" id="myForm1">
<div class="title">调拨单确认</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>单据编号</strong> * </td>
      <td>{{$data.bill_no}}</td>
      <td width="10%"></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>调出区域</strong> * </td>
      <td>{{$areas[$data.from_lid]}}</td>
      <td><strong>调入区域</strong> * </td>
      <td>{{$areas[$data.to_lid]}}</td>
    </tr>
    <tr>
      <td><strong>制单日期</strong> * </td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong> * </td>
      <td>{{$data.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="3">&nbsp;{{$data.remark}}</td>
    </tr>
    <tr>
      <td><strong>商品明细</strong> * </td>
      <td valign="top" colspan="3">
      </td>
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
    <td>状态</td>
    <td>调拨数量</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$details item=d key=key}}
	<tr>
	<td>{{$key+1}}</td>
	<td>{{$d.product_sn}}</td>
	<td>{{$d.goods_name}}</td>
	<td>{{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}</td>
	<td>{{$status[$d.status_id]}}</td>
	<td>{{$d.number}}</td>
	</tr>
	{{/foreach}}
</tbody>
</table>
<div style="text-align:right;padding:10px 20px"><strong>数量合计：</strong>{{$data.total_number}}</div>

</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name}}
<input type="button" onclick="window.open('{{url param.action=print param.id=$data.aid}}')" value="打印">
<input type="button" name="dosubmit1" value="确认" onclick="if(confirm('确认此单据吗？')){ajax_submit($('myForm1'),'{{url}}');}"/>
{{else}}
<input type="button" onclick="Gurl();" value="返回">
{{/if}}
</div>
</form>