<form name="myForm" id="myForm" action="{{url}}" method="post" >
<div class="title">在线支付货款结算</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td><strong>支付方式：</strong>{{if $payment_list[$bill.pay_type].name}}{{$payment_list[$bill.pay_type].name}}{{elseif $bill.pay_type eq 'bank'}}银行打款{{elseif $bill.pay_type eq 'cash'}}现金支付{{/if}}
      &nbsp;&nbsp;&nbsp;&nbsp;<strong>结算单号：</strong>{{$bill.clear_no}}
	  </td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>订单号</td>
        <td>支付方式</td>
        <td>结算金额</td>
        <td>佣金</td>
    </tr>
</thead>
<tbody id="list">
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.batch_sn}}</td>
        <td>{{$data.pay_name}}</td>
        <td>{{$data.price_payed-$data.commission}}</td>
        <td>{{$data.commission}}</td>
    </tr>
    {{/foreach}}
</tbody>
</table>
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
<input type="button" onclick="window.open('{{url param.action=print-clear param.id=$bill.id}}')" value="打印">
</div>
</form>