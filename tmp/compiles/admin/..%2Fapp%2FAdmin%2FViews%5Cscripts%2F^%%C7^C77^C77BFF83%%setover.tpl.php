<?php /* Smarty version 2.6.19, created on 2014-12-19 17:54:30
         compiled from logic-area-in-stock/setover.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'logic-area-in-stock/setover.tpl', 19, false),)), $this); ?>
<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_no" size="20" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" />
<input type="hidden" name="logic_area" value="<?php echo $this->_tpl_vars['logic_area']; ?>
">
<input type="hidden" name="bill_type" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
">
<input type="hidden" name="item_no" value="<?php echo $this->_tpl_vars['data']['item_no']; ?>
">
<input type="hidden" name="parent_id" value="<?php echo $this->_tpl_vars['data']['parent_id']; ?>
">
<div class="title">入库单结束</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
<?php if ($this->_tpl_vars['data']['bill_type'] == 2): ?>(<?php if ($this->_tpl_vars['data']['purchase_type'] == 1): ?>经销<?php else: ?>代销<?php endif; ?>)<?php endif; ?></td>
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

<table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
<thead>
<tr>
    <td>序号</td>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>本次应收</td>
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
</td>
	<td><?php echo $this->_tpl_vars['d']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['d']['goods_style']; ?>
</font>) </td>
	<td><?php if ($this->_tpl_vars['d']['batch_no']): ?><?php echo $this->_tpl_vars['d']['batch_no']; ?>
<?php else: ?>无批次<?php endif; ?><input type="hidden" name="batch_id[]" value="<?php if ($this->_tpl_vars['d']['batch_id']): ?><?php echo $this->_tpl_vars['d']['batch_id']; ?>
<?php else: ?>0<?php endif; ?>"></td>
	<td><?php echo $this->_tpl_vars['d']['plan_number']; ?>
<input type="hidden" name="plan_number[]" value="<?php echo $this->_tpl_vars['d']['plan_number']; ?>
"><input type="hidden" name="product_id[]" value="<?php echo $this->_tpl_vars['d']['product_id']; ?>
"><input type="hidden" name="status_id[]" value="<?php echo $this->_tpl_vars['d']['status_id']; ?>
"><input type="hidden" name="shop_price[]" value="<?php echo $this->_tpl_vars['d']['shop_price']; ?>
"></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>

</div>

<div class="submit">
<?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
<?php if ($this->_tpl_vars['data']['parent_id']): ?>
<input type="button" name="dosubmit2" value="强制完成" onclick="if(confirm('确认强制完成吗？')){ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');}"/>
<?php endif; ?>
<?php endif; ?>
</div>
</form>