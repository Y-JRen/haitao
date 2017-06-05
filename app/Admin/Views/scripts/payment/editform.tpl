<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<input type='hidden' name='payment[id]' value='{{$payment.id}}'>
<div class="title">编辑支付方式</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>支付方式名称</strong> * </td>
      <td><input type="text" name='payment[name]' value='{{$payment.name}}' size="30" msg="请填写支付方式名称" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>支付费率</strong> * </td>
      <td><input type="text" name="payment[fee]" size="30" value="{{$payment.fee}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>支付介绍</strong> * </td>
      <td><textarea name="payment[dsc]" rows="3" cols="39" style="width:330px; height:45px;">{{$payment.dsc}}</textarea></td>
    </tr>
	
    <tr>
      <td width="10%"><strong>图片标识</strong> * </td>
      <td><input type="text" name="payment[img_url]" size="30" value="{{$payment.img_url}}" /></td>
    </tr>
    <tr> 
      <td><strong>类型</strong> * </td>
      <td>
	   <input type="radio" name="payment[is_bank]" value="2" {{if $payment.is_bank eq 2}}  checked  {{/if}} /> 银行直连
	   <input type="radio" name="payment[is_bank]" value="1" {{if $payment.is_bank eq 1}}  checked  {{/if}} /> 支付网关银行
	   <input type="radio" name="payment[is_bank]" value="0"   {{if $payment.is_bank eq 0}}  checked  {{/if}} /> 支付网关
	  </td>
    </tr>	
    <tr>
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="payment[status]" value="0" {{if $payment.status==0}}checked{{/if}}/> 启用
	   <input type="radio" name="payment[status]" value="1" {{if $payment.status==1}}checked{{/if}}/> 未启用
	  </td>
    </tr>
</tbody>
</table>
</div>
<div id='payment_cfg'>{{$config}}</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function listPaymentCfg(obj){
    new Request({
        url: '{{url param.action=getplugin}}'+'/payment/'+obj.value,
        onRequest: loading,
        onSuccess:function(data){
            $('payment_cfg').set('html', data);
            loadSucess();
        }
    }).send();
}
</script>