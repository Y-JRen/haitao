<form id="bank_submit" target="ifrmSubmit">
<div class="content">
	<table>
	    <tr><td><b>退款类型：</b></label></td>
			<td height="30" colspan="2" >
		    <input type="radio" name="type" value="1" checked onclick="changeType(this.value)">自营
		    <!--<input type="radio" name="type" value="2" onclick="changeType(this.value)">渠道-->
		  </td>
		</tr>
		<tr id="wayArea" style="display:none">
          <td align="right"><strong>退款方式：</strong></td>
		  <td height="30" colspan="2" >
		    <input type="radio" name="way" value="1" checked>中间平台
		    <input type="radio" name="way" value="2">我方账户
		  </td>
	    </tr>
		<tr>
			<th colspan="6" align="left"><label><input type="radio" name="bank[type]" id="finance_bank_1" value="1" />银行转账</label></th>
		</tr>
		<tr><td>开户行名称</td>
			<td><input name="bank[bank]" type="text" id="finance_bank" /></td>
			<td>帐号</td>
			<td><input name="bank[account]" type="text" id="finance_account" /></td>
			<td>开户名</td>
			<td><input name="bank[user]" type="text" id="finance_user" /></td>
		</tr>
		<tr>
			<th colspan="6" align="left"><label><input type="radio" name="bank[type]" id="finance_bank_2" value="2" />邮局汇款</label></th>
		</tr>
		<tr>
			<td>汇款地址</td>
			<td><input name="bank[address]" type="text" id="finance_address" /></td>
			<td>邮编</td>
			<td><input name="bank[zip]" type="text" id="finance_zip" /></td>
			<td>姓名</td>
			<td><input name="bank[name]" type="text" id="finance_name" /></td>
		</tr>
		{{if $order.user_id gt 10 && $finance.price_return_money gt 0}}
		<tr>
			<th colspan="6" align="left"><label><input type="radio" name="bank[type]" id="finance_bank_3" value="3" />帐户余额</label></th>
		</tr>
		{{/if}}
		<tr>
			<th colspan="6" align="left"><label><input type="radio" name="bank[type]" id="finance_bank_5" value="5" />支付宝</label></th>
		</tr>
        <tr>
			<td>账号</td>
			<td><input name="bank[alipay_account]" type="text" id="alipay_account" /></td>
		</tr>
		<tr>
			<th colspan="6" align="left"><label><input type="radio" name="bank[type]" id="finance_bank_4" value="4" checked/>其他</label></th>
		</tr>
		<tr>
			<th valign="top">退款备注</th>
			<td colspan="5"><textarea name="bank[note]" id="finance_note"></textarea></td>
		</tr>
	</table>
	<input type="hidden" name="submit" value="1" />
	<input type="hidden" name="jump" value="{{$jump}}" />
	<input type="hidden" name="mod" value="{{$mod}}" />
	<input type="hidden" name="batch_sn" value="{{$batchSN}}" />
	<input type="button" value="退款" onclick="confirmedBank('退款', $('bank_submit'), '{{url param.action=finance}}')" />
<div>

{{if $jump=='invalid' || $jump=='confirm-cancel' || $jump=='invalid' || $jump=='to-be-shipping-cancel'}}
	<strong>应退款：￥{{$finance.price_return_all_money}}</strong><br />
	{{if $finance.price_return_all_gift}}礼品卡：￥{{$finance.price_return_all_gift}}<br />{{/if}}
	{{if $finance.price_return_all_point}}积分：￥{{$finance.price_return_all_point}}<br />{{/if}}
	{{if $finance.price_return_all_account}}账户余额：￥{{$finance.price_return_all_account}}<br />{{/if}}
{{else}}
    {{if $order.type eq 16}}
	  <strong>应退款：￥</strong>&nbsp;<input type="text" name="returnMoney" id="returnMoney" size="10" value="{{$order.price_payed}}"><br />
	{{else}}
	  <strong>应退款：￥{{$finance.price_return_money}}</strong><br />
	  {{if $finance.price_return_gift}}礼品卡：￥{{$finance.price_return_gift}}<br />{{/if}}
	  {{if $finance.price_return_point}}积分：￥{{$finance.price_return_point}}<br />{{/if}}
	  {{if $finance.price_return_account}}账户余额：￥{{$finance.price_return_account}}<br />{{/if}}
    {{/if}}
{{/if}}
</form>

<script>
		
function confirmedBank(str, obj, url) {
	if (checkBank()) {
		if (confirm('确认执行 "' + str + '" 操作？')) {
			ajax_submit(obj, url);
		}
	} else {
		return false;
	}
}

function checkBank() {
		var flag = false;
		if ($('finance_bank_1').checked==true) {
			if ($('finance_bank').value == '') {
				alert('开行名不能为空称');
				return false;
			} else if ($('finance_account').value == '') {
				alert('帐号不能为空');
				return false;
			} else if ($('finance_user').value == '') {
				alert('开户名不能为空');
				return false;
			}
			flag = true;
		}
		if ($('finance_bank_2').checked==true) {
			if ($('finance_address').value == '') {
				alert('汇款地不能为空址');
				return false;
			} else if ($('finance_zip').value == '') {
				alert('邮编不能为空');
				return false;
			} else if ($('finance_name').value == '') {
				alert('姓名不能为空');
				return false;
			}
			flag = true;
		}
		if ($('finance_bank_5').checked==true) {
		    if ($('alipay_account').value == '') {
		        alert('支付宝账号不能为空');
				return false;
		    }
		    flag = true;
		}
		{{if $order.user_id gt 10 && $finance.price_return_money gt 0}}
		if ($('finance_bank_3').checked==true) {
		    flag = true;
		}
		{{/if}}
		if ($('finance_bank_4').checked==true) {
		    flag = true;
		}
		if (!flag) {
			alert('请选择退款方式');
			return false;
		}
		if ($('finance_note').value == '') {
			alert('退款备注不能为空');
			return false;
		}
		return true;
}
function changeType(type)
{
    if (type == 1) {
        $('wayArea').style.display = 'none';
    }
    else {
        $('wayArea').style.display = '';
    }
}
</script>
