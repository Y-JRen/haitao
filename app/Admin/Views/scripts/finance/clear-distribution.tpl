<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<div style="height:400px;overflow:scroll;">
<br>
  <table width="100%" cellpadding="0" cellspacing="2"  border="0">
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>订单号</b></td>
          <td>{{$data.batch_sn}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>应结金额</b></td>
          <td>{{$data.amount}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>已结金额</b></td>
          <td>{{$data.settle_amount}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>促销金额</b></td>
          <td>{{$data.promotion_amount}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>扣点金额</b></td>
          <td>{{$data.point_amount}}</td>
        </tr>
  </table>
  <br>
<form id="myform2" name="myform2">
<table width="95%" cellpadding="0" cellspacing="2" border="0" align="center" id="productTable">
  <tr bgcolor="#F0F1F2">
    <td>产品编码</td>
    <td>产品名称</td>
    <td>单价</td>
    <td>应结数量</td>
    <td>已结数量</td>
    <td>结款数量</td>
    <td>退款数量</td>
  <tr>
  {{foreach from=$productData item=data}}
    <tr>
      <td>{{$data.product_sn}}</td>
      <td>{{$data.goods_name}}</td>
      <td>{{$data.sale_price}}<input type="hidden" name="price[{{$data.product_id}}]" id="price[{{$data.product_id}}]" value="{{$data.sale_price}}"></td>
      <td>{{$data.number}}{{if $reduceProductNumber[$data.product_id]}} - {{$reduceProductNumber[$data.product_id]}}{{/if}}</td>
      <td>{{$productNumber[$data.product_id]|default:0}}</td>
      <td>
        {{if $data.number > $productNumber[$data.product_id]}}
          <input type="text" size="5" name="number[{{$data.product_id}}]" value="0" onblur="checkNumber(this, {{if $reduceProductNumber[$data.product_id]}}{{$data.number-$reduceProductNumber[$data.product_id]}}{{else}}{{$data.number}}{{/if}}, {{$productNumber[$data.product_id]|default:0}})">
        {{/if}}
      </td>
      <td>
        {{if $productNumber[$data.product_id] > 0}}
          <input type="text" size="5" name="return_amount_number[{{$data.product_id}}]" value="0" onblur="checkReturnAmountNumber(this, {{$productNumber[$data.product_id]}})">
        {{/if}}
      </td>
    </tr>
  {{/foreach}}
</table>
<br>
<table width="95%" cellpadding="0" cellspacing="2"  border="0" align="center">
  <tr>
    <td width="140">
      促销金额：<input type="text" name="promotion_amount" id="promotion_amount" value="0" size="5">
    </td>
    <td width="140">
      扣点金额：<input type="text" name="point_amount" id="point_amount" value="0" size="5">
    </td>
    <td width="140">
      结款金额：<span id="sumSettleAmount">0</span>
    </td>
    <td width="140">
      退款金额：<span id="sumReturnAmount">0</span>
    </td>
    <td>
      <input type="button" name="submit" value="结款" onclick="recieve()">
    </td> 
  </tr>
</table>
{{if $details}}
<br>
<table width="95%" cellpadding="0" cellspacing="2"  border="0" align="center">
  <tr bgcolor="#F0F1F2">
    <td width="16%">结算日期</td>
    <td>结算商品</td>
    <td width="16%">结算金额</td>
    <td width="16%">促销金额</td>
    <td width="16%">扣点金额</td>
    <td width="16%">结算人</td>
  </tr>
  {{foreach from=$details item=detail}}
  {{if $detail.type eq 1}}
  <tr>
    <td>{{$detail.add_time|date_format:"%Y-%m-%d"}}</td>
    <td>
      {{foreach from=$detail.detail item=number key=productID}}
        {{$productInfo[$productID]}} * {{$number}}<br>
      {{/foreach}}
    </td>
    <td>{{$detail.amount}}</td>
    <td>{{$detail.promotion_amount}}</td>
    <td>{{$detail.point_amount}}</td>
    <td>{{$detail.admin_name}}</td>
  </tr>
  {{/if}}
  {{/foreach}}
</table>
<br>
<table width="95%" cellpadding="0" cellspacing="2"  border="0" align="center">
  <tr bgcolor="#F0F1F2">
    <td width="16%">退货日期</td>
    <td>退货商品</td>
    <td width="16%">扣减结算金额</td>
    <td width="16%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
    <td width="16%">退货人</td>
  </tr>
  {{foreach from=$details item=detail}}
  {{if $detail.type eq 2}}
  <tr>
    <td>{{$detail.add_time|date_format:"%Y-%m-%d"}}</td>
    <td>
      {{foreach from=$detail.detail item=number key=productID}}
        {{$productInfo[$productID]}} * {{$number}}<br>
      {{/foreach}}
    </td>
    <td>{{$detail.amount}}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>{{$detail.admin_name}}</td>
  </tr>
  {{/if}}
  {{/foreach}}
</table>
{{/if}}
</form>
</div>
<script language="JavaScript">
function recieve()
{
    if (!confirm('确认要结款吗？'))   return false;
    ajax_submit($('myform2'), '{{url}}');
}

function checkNumber(obj, number, settle_number)
{
    if (obj.value == 0 || obj.value == '') {
    
    }
    else {
        if (obj.value > number - settle_number) {
            alert('结款数量不能大于未结数量!');
            obj.value = 0;
        }
    }
    
    sumAmount();
}

function checkReturnAmountNumber(obj, number)
{
    if (obj.value == 0 || obj.value == '') {
    
    }
    else {
        if (obj.value > number) {
            alert('退款数量不能大于已结数量!');
            obj.value = 0;
        }
    }
    
    sumAmount();
}

function sumAmount()
{
    var sumSettleAmount = 0;
    var sumReturnAmount = 0;
    var inputs = $('productTable').getElements('input[type=text]');
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].name.substring(0, 6) == 'number') {
            sumSettleAmount = sumSettleAmount + inputs[i].value * $(inputs[i].name.replace('number', 'price')).value;
        }
        else if (inputs[i].name.substring(0, 6) == 'return') {
            sumReturnAmount = sumReturnAmount + inputs[i].value * $(inputs[i].name.replace('return_amount_number', 'price')).value;
        }
    }
    
    $('sumSettleAmount').innerHTML = sumSettleAmount;
    $('sumReturnAmount').innerHTML = sumReturnAmount;
}
</script>
