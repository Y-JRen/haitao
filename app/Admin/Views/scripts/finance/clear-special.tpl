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
          <td width="100">　<b>订单号</b></td>
          <td>{{$order.batch_sn}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>应收金额</b></td>
          <td>{{$order.price_pay}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>已收金额</b></td>
          <td>{{if $order.price_payed}}{{$order.price_payed}}{{else}}0{{/if}}</td>
        </tr>
  </table>
  <br>
<form id="myform" name="myform">
{{if $order.clear_pay eq '0'}}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr>
    <td width="160">
      　结款金额　　 <input type="text" name="amount" id="amount" value="{{$order.need_pay}}" size="6" {{if $order.user_name eq 'batch_channel'}}readonly{{/if}}>
    </td>
    <td>
      <input type="button" name="submit" value="结款" onclick="recieve()">
    </td> 
  </tr>
</table>
{{/if}}
</form>
<script language="JavaScript">
function recieve()
{
    if (!confirm('确认要收款吗？'))   return false;
    
    var amount = $('amount').value;
    if (amount > {{$order.need_pay}}) {
        alert('结款金额不能大于应收金额');
        return false;
    }
    
    ajax_submit($('myForm'), '{{url}}/amount/' + amount);
}
</script>