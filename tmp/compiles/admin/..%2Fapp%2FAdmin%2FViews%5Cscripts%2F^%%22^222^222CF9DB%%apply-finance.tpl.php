<?php /* Smarty version 2.6.19, created on 2014-08-21 10:14:58
         compiled from finance/apply-finance.tpl */ ?>
<div class="title">退款开单</div>
<div class="content">

<form id="bank_submit">
	<table cellpadding="0" cellspacing="0" border="0" class="table"  style="width:80%">
    	<tr>
          <td width="90" align="right"><strong>退款类型：</strong></td>
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
        <td width="90" align="right"><strong>店铺：</strong></td>
		<td height="30" colspan="2" ><select name="shop_id" id="shop_id">
                <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shop']):
?>
                <option value="<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
" ><?php echo $this->_tpl_vars['shop']['shop_name']; ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
                <option value="0" >其它下单</option>
                </select>
          </td>
	  </tr>
    	<tr>
        <td width="90" align="right"><strong>退款单号：</strong></td>
			<td height="30" colspan="2" ><label><input type="text" name="batch_sn" id="batch_sn" value="" /></label> (请填写官网订单号)</td> 
	  </tr>
    	<tr>
        <td width="90" align="right"><strong>应退款金额：</strong></td>
		  <td height="30" colspan="2" ><label><input type="text" name="pay" id="pay" value="" /></label></td>
	  </tr>
        
		<tr><td width="90" height="30" align="right"><strong>
		  <label>
		    <input type="radio" name="bank[type]" id="finance_bank_1" value="1" />
		    银行转账</label>
		</strong></td>
		  <td width="839" height="30">
			开户行名称&nbsp;&nbsp;<input name="bank[bank]" type="text" id="finance_bank" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            帐号&nbsp;&nbsp;<input name="bank[account]" type="text" id="finance_account" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            开户名&nbsp;&nbsp;<input name="bank[user]" type="text" id="finance_user" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </td>
	  </tr>
		<tr>
			<td width="90" height="30" align="right"><strong>
			  <label>
			    <input type="radio" name="bank[type]" id="finance_bank_2" value="2" />
			    邮局汇款</label>
			</strong></td>
		  <td height="30">
          汇款地址&nbsp;&nbsp;<input name="bank[address]" type="text" id="finance_address" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          邮编&nbsp;&nbsp;<input name="bank[zip]" type="text" id="finance_zip" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          姓名&nbsp;&nbsp;<input name="bank[name]" type="text" id="finance_name" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </td>
	  </tr>
      <tr align="right">
			<td height="30"  width="90" align="left" ><label><input type="radio" name="bank[type]" id="finance_bank_5" value="5" /><strong>支付宝</strong></label></td>
            <td>账号&nbsp;&nbsp;<input name="bank[alipay_account]" type="text" id="alipay_account" /></td>
	  </tr>
	  <tr align="right">
			<td height="30"  width="90" align="left" ><label><input type="radio" name="bank[type]" id="finance_bank_6" value="6" /><strong>虚拟</strong></label></td>
            <td>&nbsp;</td>
	  </tr>
      <tr align="right">
		<td height="30"  width="90" align="left" ><label><input type="radio" name="bank[type]" id="finance_bank_4" value="4" checked/><strong>其他</strong></label></td>
	</tr>
	<tr>
		<td width="90" align="right" valign="top"><strong>退款备注</strong></td>
		<td><textarea name="bank[note]" cols="65" rows="5" id="finance_note"></textarea></td>
	</tr>
	</table>
	<input type="hidden" name="submit" value="1" />
	<input type="button" value="退款" onclick="confirmedBank('退款', $('bank_submit'), '<?php echo $this -> callViewHelper('url', array(array('action'=>"apply-finance",)));?>')" />
</form>
<div>

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
		if ($('finance_bank_4').checked==true) {
		    flag = true;
		}
		if ($('finance_bank_6').checked==true) {
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