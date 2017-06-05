<?php /* Smarty version 2.6.19, created on 2014-08-20 18:01:19
         compiled from transport/confirm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'transport/confirm.tpl', 16, false),)), $this); ?>
<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_type" size="20" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
" />
<input type="hidden" name="bill_no" size="20" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" />
<input type="hidden" name="logistic_code" size="20" value="<?php echo $this->_tpl_vars['data']['logistic_code']; ?>
" />
<input type="hidden" name="logistic_no" size="20" value="<?php echo $this->_tpl_vars['data']['logistic_no']; ?>
" />
<div class="title">运输单确认</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td><?php echo $this->_tpl_vars['data']['bill_no_str']; ?>
</td>
      <td width="12%"><strong>制单日期</strong></td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
    </tr>
    <tr>
      <td><strong>配送方式</strong></td>
      <td><?php if ($this->_tpl_vars['data']['logistic_code'] != 'ems'): ?>快递<?php else: ?>EMS<?php endif; ?></td>
      <td><strong><?php if ($this->_tpl_vars['data']['is_cod']): ?>应收金额<?php else: ?>订单金额<?php endif; ?></strong></td>
      <td><?php echo $this->_tpl_vars['data']['amount']; ?>
</td>
      <td><strong>付款方式</strong></td>
      <td><?php if ($this->_tpl_vars['data']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
    </tr>
    <tr>
      <td><strong>重量</strong></td>
      <td><?php echo $this->_tpl_vars['data']['weight']; ?>
</td>
      <td><strong>体积</strong></td>
      <td><?php echo $this->_tpl_vars['data']['volume']; ?>
</td>
      <td><strong>承运商</strong></td>
      <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="5">&nbsp;<?php echo $this->_tpl_vars['data']['remark']; ?>
</td>
    </tr>
</tbody>
</table>

</div>

<div class="submit">
<?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
<input type="button" onclick="window.open('<?php echo $this -> callViewHelper('url', array(array('action'=>'print2','id'=>$this->_tpl_vars['data']['tid'],'is_cod'=>$this->_tpl_vars['data']['is_cod'],'logistic_code'=>$this->_tpl_vars['data']['logistic_code'],)));?>')" value="打印运输单">
<input type="button" name="dosubmit1" id="dosubmit1" value="确认" onclick="dosubmit()"/>
<?php endif; ?>
</div>
</form>
<script>
function dosubmit()
{
	if(confirm('确认吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');
	}
}

function failed()
{
	$('dosubmit1').value = '确认';
	$('dosubmit1').disabled = false;
}

</script>