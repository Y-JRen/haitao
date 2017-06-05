<?php /* Smarty version 2.6.19, created on 2014-08-20 18:01:49
         compiled from logic-area-out-stock/send.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'logic-area-out-stock/send.tpl', 27, false),)), $this); ?>
<form name="myForm1" id="myForm1">
<input type="hidden" name="outstock_id" size="20" value="<?php echo $this->_tpl_vars['data']['outstock_id']; ?>
" />
<input type="hidden" name="bill_no" size="20" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" />
<input type="hidden" name="logic_area" value="<?php echo $this->_tpl_vars['logic_area']; ?>
">
<input type="hidden" name="bill_type" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
">
<input type="hidden" name="item_no" value="<?php echo $this->_tpl_vars['data']['item_no']; ?>
">
<input type="hidden" name="bill_name" value="<?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
">
<input type="hidden" name="logistic_code" value="<?php echo $this->_tpl_vars['data']['transport']['logistic_code']; ?>
" />
<input type="hidden" name="weight" value="<?php echo $this->_tpl_vars['data']['transport']['weight']; ?>
" />
<input type="hidden" name="recipient" value="<?php echo $this->_tpl_vars['data']['recipient']; ?>
" />
<input type="hidden" name="barcode" id="barcode">
<input type="hidden" name="remark" id="remark"  value="<?php echo $this->_tpl_vars['data']['remark']; ?>
">
<input type="hidden" name="toback" id="toback" value="" />

<div class="title">出库单发货</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
    </tr>
    <tr>
      <td><strong>制单日期</strong></td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
      <td><strong>制单人</strong></td>
      <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="3">&nbsp;<?php echo $this->_tpl_vars['data']['remark']; ?>
</td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
<tr>
    <td>序号</td>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>状态</td>
    <td>应发数量</td>
    </tr>
</thead>
<tbody>
	<?php $_from = $this->_tpl_vars['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['d']):
?>
	<tr>
	<td><?php echo $this->_tpl_vars['key']+1; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['product_sn']; ?>
<input type="hidden" name="product_id[]" value="<?php echo $this->_tpl_vars['d']['product_id']; ?>
"></td>
	<td><?php echo $this->_tpl_vars['d']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['d']['goods_style']; ?>
</font>)</td>
	<td><?php if ($this->_tpl_vars['d']['batch_no']): ?><?php echo $this->_tpl_vars['d']['batch_no']; ?>
<?php else: ?>无批次<?php endif; ?><input type="hidden" name="batch_id[]" value="<?php if ($this->_tpl_vars['d']['batch_id']): ?><?php echo $this->_tpl_vars['d']['batch_id']; ?>
<?php else: ?>0<?php endif; ?>"></td>
	<td><?php echo $this->_tpl_vars['status'][$this->_tpl_vars['d']['status_id']]; ?>
<input type="hidden" name="status[]" value="<?php echo $this->_tpl_vars['d']['status_id']; ?>
"></td>
	<td><?php echo $this->_tpl_vars['d']['number']; ?>
<input type="hidden" name="number[]" value="<?php echo $this->_tpl_vars['d']['number']; ?>
"></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
<div style="text-align:right;padding:10px 20px"><strong>应发合计：</strong><?php echo $this->_tpl_vars['data']['total_number']; ?>
<input type="hidden" name="total_number" id="total_number" value="<?php echo $this->_tpl_vars['data']['total_number']; ?>
"></div>

<?php if ($this->_tpl_vars['data']['transport']['logistic_name'] && ( $this->_tpl_vars['data']['bill_type'] == 1 || $this->_tpl_vars['data']['bill_type'] == 10 )): ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="table">
<tbody>
    <tr>
      <td width="10%"><strong>物流公司</strong></td>
      <td><?php echo $this->_tpl_vars['data']['transport']['logistic_name']; ?>

      </td>
    </tr>
    <tr>
      <td width="10%"><strong>付款方式</strong></td>
      <td><?php if ($this->_tpl_vars['data']['transport']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?>
      </td>
    </tr>
    <tr>
      <td width="10%"><strong>称重</strong></td>
      <td><input type="text" name="deliver_weigh" id="deliver_weigh" size="30" maxlength="30" value="<?php echo $this->_tpl_vars['data']['deliver_weigh']; ?>
" /> *KG</td>
    </tr>
    <tr>
      <td width="10%"><strong>运单号</strong></td>
      <td><input type="text" name="logistic_no" id="logistic_no" size="40" maxlength="50" value="<?php echo $this->_tpl_vars['data']['logistic_no']; ?>
" /></td>
    </tr>
</tbody>
</table>
<?php endif; ?>

</div>

<div class="submit">
<?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
<input type="button" name="dosubmit1" id="dosubmit1" value="发货" onclick="dosubmit()"/>
<?php if ($this->_tpl_vars['data']['bill_type'] == 1): ?>
<input type="button" name="doback" id="doback" value="返回打印" onclick="back()"/>
<?php endif; ?>
<?php endif; ?>
</div>
</form>
<script language="JavaScript">
function dosubmit()
{
	<?php if ($this->_tpl_vars['data']['bill_type'] == 1): ?>
		var no = $('logistic_no').value.trim();
		var code = '<?php echo $this->_tpl_vars['data']['transport']['logistic_code']; ?>
';
		var len = no.length;
		var cod = '<?php echo $this->_tpl_vars['data']['transport']['is_cod']; ?>
';
		var result = false;
		if(no==''){alert('请填写运单号!');return false;}
		if (code=='zjs'){
			 if (len==10){
				 if ((cod==0 && no.substr(0, 1) != '6') || (cod==1 && no.substr(0, 1) == '6')) {result = true;}
			 }
		}else if (code=='sf'){
			 if (len==12){
				 if (no.substr(0, 3) == '513') {result = true;}
			 }
		}else if (code=='st'){
			 if (len==12){
				 if (no.substr(0, 3) != '513') {result = true;}
			 }
			 if (no == '自提' || no == 'zt') {result = true;}
		}else if (code=='ems'){
			 if (len==13){
				 if ((cod==0 && no.substr(0, 2) != 'EC' && no.substr(0, 4) != '316A') || (cod==1 && no.substr(0, 2) == 'EC')) {result = true;}
			 }
		}else if (code=='jldt'){
			 if (len==13){
				 if (no.substr(0, 4) == '316A') {result = true;}
			 }
		}
		/*
		if (result == false){
			alert('运单号码校验错误,请检查使用的快递面单是否正确!');
			$('logistic_no').value='';
			$('logistic_no').focus();
			return false;
		}
		*/
	<?php endif; ?>
	
	if(confirm('确认发货吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');
	}
}

function back()
{
    if(confirm('确认返回吗？')){
        $('toback').value = 1;
        ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');
    }
}

function failed()
{
	$('dosubmit1').value = '发货';
	$('dosubmit1').disabled = false;
}

</script>