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
          <td width="100">　<b>系统单号</b></td>
          <td>{{$payment.bill_no}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>入库单号</b></td>
          <td>{{$payment.paper_no}}</td>
        </tr>
  </table>
  <br>
  <form id="myformpay" name="myformpay" >
  <input type="hidden" name="instock_id" value="{{$payment.instock_id}}" />
  <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
      <td>商品名称</td>
      <td>商品编码</td>
      <td>入库单价</td>
      <td>状态</td>
      <td>数量</td>
      <td>结款数量</td>
      <td>抵扣发票数量</td>
    </tr>
    </thead>
    {{foreach from=$datas item=product}}
    <input type="hidden" name="product_id[]"  value="{{$product.product_id}}"/>
    <tr>
      <td>{{$product.goods_name}}</td>
      <td>{{$product.product_sn}}</td>
      <td>{{$product.shop_price}} <input type="hidden" name="shop_price[]" id="shop_price_{{$product.product_sn}}" value="{{$product.shop_price}}"/></td>
      <td>{{$statusConfig[$product.status_id]}}</td>
      <td>{{$product.number}} <input type="hidden" id="shop_number_{{$product.product_sn}}" value="{{$product.number}}"/></td>
      <td><input type="text" name="prod_pay_num[]" value="{{if empty($product.prod_pay_num)}}0{{else}}{{$product.prod_pay_num}}{{/if}}" size="5" onblur="payOpt(this.value,'{{$product.product_sn}}')" id="prod_pay_num_{{$product.product_sn}}"/><input type="hidden" id="prod_pay_num_comp_{{$product.product_sn}}" value="{{if empty($product.prod_pay_num)}}0{{else}}{{$product.prod_pay_num}}{{/if}}"/></td>
      <td><input type="text" name="invoice_num[]" value="{{if empty($product.invoice_num)}}0{{else}}{{$product.invoice_num}}{{/if}}" size="5" onblur="payInv(this.value,'{{$product.product_sn}}')" id="invoice_num_{{$product.product_sn}}"/><input type="hidden"  id="invoice_num_comp_{{$product.product_sn}}" value="{{if empty($product.invoice_num)}}0{{else}}{{$product.invoice_num}}{{/if}}"/></td>
    </tr>
    {{/foreach}}
  </table>
<br>
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr bgcolor="#F0F1F2">
    <td width="80">　<b>供应商</b></td>
    <td width="80">{{$payment.supplier_name}}</td>
    <td width="80">　<b>应付金额</b></td>
    <td width="80">{{$payment.amount}}<input type="hidden" name="mixamount" value="{{$payment.amount}}"/></td>
    <td width="80">　<b>实付金额</b></td>
    <td>{{$payment.real_amount}}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>　<b>银行</b></td>
    <td>{{$payment.bank_name}}</td>
    <td>　<b>银行账户</b></td>
    <td>{{$payment.bank_account}}</td>
    <td>　<b>银行账号</b></td>
    <td>{{$payment.bank_sn}}</td>
</table>
<br>

{{if $amount > 0}}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr>
    <td width="160">
      　付款金额　　 <input type="text" name="amount" id="amount" value="{{$payment.real_amount}}" size="6" readonly="readonly">
    </td>
    <td width="300">
      　备注 <input type="text" name="memo" id="memo" style="width:70%">
    </td>
    <td>
      
    </td> 
  </tr>
</table>
{{/if}}
{{if $invoice_flag eq 2 }}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr>
    <td width="160">
      　收到发票金额 <input type="text" name="invoice_amount" id="invoice_amount" value="{{$payment.invoice_amount}}" size="6" readonly="readonly">
    </td>
    <td width="300">
      　备注 <input type="text" name="invoice_memo" id="invoice_memo" style="width:70%">
    </td>
    <td>
     <input type="button" name="submit" value="提交数据" onclick="payment()">
    </td> 
  </tr>
</table>
{{/if}}
</form>
{{if $payment.history}}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
 <tr>
   <td>{{$payment.history}}</td>
 </tr>
</table>
{{/if}}
<script language="JavaScript">
function payOpt(val,prosn){
	if(isNaN(val)){
		alert("收款数量只能为数字!");
		$('prod_pay_num_'+prosn).value = 0;
		return false;
	}
	var max_num = $('shop_number_'+prosn).value;
	
	if(parseInt(val) > parseInt(max_num)){
		alert("收款数量不能大于入库数量!");
		$('prod_pay_num_'+prosn).value = parseInt(max_num);
		return false;
	}
	var prod_pay_num_comp = $('prod_pay_num_comp_'+prosn).value;
	if(parseInt(val) < parseInt(prod_pay_num_comp)){
		alert("收款数量不能小于已收款数量,已收款数量为【"+parseInt(prod_pay_num_comp)+"】");
		$('prod_pay_num_'+prosn).value = parseInt(prod_pay_num_comp);
		return false;
	}
	oEle = document.getElementsByName('prod_pay_num[]');
	oprice = document.getElementsByName('shop_price[]');
	var cout_num = 0;
	for(var i = 0;i<oEle.length;i++){
		
		var tmp = parseInt(oEle[i].value);
		var price = parseFloat(oprice[i].value);
		cout_num = cout_num+tmp*price;
	}
	$('amount').value=cout_num;
}
function payInv(val,prosn){
	if(isNaN(val)){
		alert("发票数量只能为数字!");
		$('invoice_num_'+prosn).value = 0;
		return false;
	}
	var max_num = $('shop_number_'+prosn).value;
	if(parseInt(val) > parseInt(max_num)){
		alert("发票数量不能大于入库数量!");
		$('invoice_num_'+prosn).value = parseInt(max_num);
		return false;
	}
	var invoice_num_comp = $('invoice_num_comp_'+prosn).value;
	if(parseInt(val) < parseInt(invoice_num_comp)){
		alert("收款发票数量不能小于已收款发票数量,已收款发票数量为【"+parseInt(invoice_num_comp)+"】");
		$('invoice_num_'+prosn).value = parseInt(invoice_num_comp);
		return false;
	}
	oEle = document.getElementsByName('invoice_num[]');
	oprice = document.getElementsByName('shop_price[]');
	var cout_num = 0;
	for(var i = 0;i<oEle.length;i++){		
		var tmp = parseInt(oEle[i].value);
		var price = parseFloat(oprice[i].value);
		cout_num = cout_num+tmp*price;
	}
	$('invoice_amount').value=cout_num;
}
function payment()
{
    if (!confirm('确认要付款吗？'))   return false;
    ajax_submit($('myformpay'),'{{url}}');

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
