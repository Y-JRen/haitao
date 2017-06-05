<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<br>
  <table width="100%" cellpadding="0" cellspacing="2"  border="0">
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>出库单号</b></td>
          <td>{{$payment.bill_no}}</td>
        </tr>
  </table>
  <br>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
      <td>商品名称</td>
      <td>商品编码</td>
      <td>出库单价</td>
      <td>数量</td>
    </tr>
    </thead>
    {{foreach from=$datas item=product}}
    <tr>
      <td>{{$product.goods_name}}</td>
      <td>{{$product.product_sn}}</td>
      <td>{{$product.shop_price}}</td>
      <td>{{$product.number}}</td>
    </tr>
    {{/foreach}}
  </table>
<br>
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr bgcolor="#F0F1F2">
    <td width="80">　<b>供应商</b></td>
    <td width="80">{{$payment.supplier_name}}</td>
    <td width="80">　<b>应收金额</b></td>
    <td width="80">{{$payment.amount}}</td>
    <td width="80">　<b>实收金额</b></td>
    <td>{{$payment.real_amount}}</td>
  </tr>
</table>
<br>
<form id="myform" name="myform">
{{if $amount > 0}}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr>
    <td width="160">
      　收款金额　　 <input type="text" name="amount" id="amount" value="{{$amount}}" size="6">
    </td>
    <td width="300">
      　备注 <input type="text" name="memo" id="memo" style="width:70%">
    </td>
    <td>
      <input type="button" name="submit" value="收款" onclick="receive()">
    </td> 
  </tr>
</table>
{{/if}}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr>
    <td width="160">
      　寄出发票金额 <input type="text" name="invoice_amount" id="invoice_amount" size="6">
    </td>
    <td width="300">
      　备注 <input type="text" name="invoice_memo" id="invoice_memo" style="width:70%">
    </td>
    <td>
      <input type="button" name="submit" value="添加" onclick="invoice()">
    </td> 
  </tr>
</table>
</form>
{{if $payment.history}}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
 <tr>
   <td>{{$payment.history}}</td>
 </tr>
</table>
{{/if}}
<script language="JavaScript">
function receive()
{
    if (!confirm('确认要收款吗？'))   return false;
    
    var amount = $('amount').value;
    if (amount > {{$amount}}) {
        alert('收款金额不能大于应收金额');
        return false;
    }
    
    ajax_submit($('myForm'), '{{url}}/amount/' + amount + '/memo/' + encodeURI($('memo').value));
}

function invoice()
{
    if (!confirm('确认要添加一张发票吗？'))   return false;
    
    var invoice_amount = $('invoice_amount').value;
    if (!invoice_amount) {
        alert('请填写发票金额');
        return false;
    }
    
    ajax_submit($('myForm'), '{{url}}/invoice_amount/' + invoice_amount + '/memo/' + encodeURI($('invoice_memo').value));
}
</script>
