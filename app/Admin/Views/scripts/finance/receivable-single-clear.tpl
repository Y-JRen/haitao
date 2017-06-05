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
          <td width="100">　<b>单据编号</b></td>
          <td>
            {{if $data.pay_type eq 'sf' || $data.pay_type eq 'ems'}}{{$data.logistic_no}}
            {{elseif $data.pay_type eq 'external' || $data.pay_type eq 'externalself'}}{{$data.external_order_sn}}
            {{else}}{{$data.batch_sn}}
            {{/if}}
          </td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>应收金额</b></td>
          <td>{{$data.amount}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>实收金额</b></td>
          <td>{{$data.settle_amount}}</td>
        </tr>
  </table>
  <br>
<form id="myform" name="myform">
{{if $data.amount-$data.settle_amount > 0}}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr>
    <td width="160">
      　结款金额 <input type="text" name="settle_amount" id="settle_amount" value="{{$data.amount-$data.settle_amount}}" size="6">
    </td>
    <td>
      佣金 <input type="text" name="commission" id="commission" value="0" size="6">      
    </td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center"><input type="button" name="submit" value="结款" onclick="recieve()"></td>
  </tr>
</table>
{{/if}}
</form>
<script language="JavaScript">
function recieve()
{
    if (!confirm('确认要收款吗？'))   return false;
    
    var settle_amount = $('settle_amount').value;
    var commission = $('commission').value;
    if (parseFloat(settle_amount) + parseFloat(commission) > {{$data.amount-$data.settle_amount}}) {
        alert('结款金额和佣金不能大于应收金额');
        return false;
    }
    
    ajax_submit($('myForm'), '{{url}}/settle_amount/' + settle_amount + '/commission/' + commission);
}
</script>