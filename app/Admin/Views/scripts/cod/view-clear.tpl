<form name="myForm" id="myForm" action="{{url}}" method="post" >
<div class="title">代收货款结算</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td><strong>物流公司：</strong>{{$logisticList[$bill.logistic_code]}}
      &nbsp;&nbsp;&nbsp;&nbsp;<strong>结算单号：</strong>{{$bill.clear_no}}
	  </td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>单据编号</td>
        <td>单据类型</td>
        <td>运单号</td>
        <td>结算金额</td>
        <td>佣金</td>
    </tr>
</thead>
<tbody id="list">
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.bill_no}}</td>
        <td>{{$billType[$data.bill_type]}}</td>
        <td>{{$data.logistic_no}}</td>
        <td>{{if $data.amount+$data.change_amount-$data.logistic_price_cod > 0}}{{$data.amount+$data.change_amount-$data.logistic_price_cod}}{{else}}0{{/if}}</td>
        <td>{{$data.logistic_price_cod}}</td>
    </tr>
    {{/foreach}}
</tbody>
</table>
</div>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td><strong>实际返款金额</strong></td>
      <td>{{$bill.real_amount}}</td>
    </tr>
    <tr>
      <td><strong>佣金</strong></td>
      <td>{{$bill.commission}}</td>
    </tr>
    <tr>
      <td width="15%"><strong>调整金额</strong></td>
      <td>{{$bill.adjust_amount}}</td>
    </tr>
    <tr>
      <td><strong>调整说明</strong></td>
      <td>{{$bill.adjust_remark}}</td>
    </tr>
</tbody>
</table>
</div>

<div class="submit">
<input type="button" onclick="window.open('{{url param.action=print param.id=$bill.id}}')" value="打印">
</div>
</form>