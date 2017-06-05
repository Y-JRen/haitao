<?php /* Smarty version 2.6.19, created on 2014-08-13 09:32:20
         compiled from logic-area-in-stock/confirm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'logic-area-in-stock/confirm.tpl', 14, false),)), $this); ?>
<form name="myForm1" id="myForm1">
<div class="title">入库单确认</div>
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

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
<tr>
    <td>序号</td>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>应收数量</td>
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
</font>)</td>
	<td><?php if ($this->_tpl_vars['d']['batch_no']): ?><?php echo $this->_tpl_vars['d']['batch_no']; ?>
<?php else: ?>无批次<?php endif; ?><input type="hidden" name="batch_id[]" value="<?php if ($this->_tpl_vars['d']['batch_id']): ?><?php echo $this->_tpl_vars['d']['batch_id']; ?>
<?php else: ?>0<?php endif; ?>"></td>
	<td><?php echo $this->_tpl_vars['d']['plan_number']; ?>
</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
<div style="text-align:right;padding:10px 20px"><strong>数量合计：</strong><?php echo $this->_tpl_vars['data']['total_number']; ?>
</div>
</div>

<div class="submit">
<?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
<input type="button" onclick="window.open('<?php echo $this -> callViewHelper('url', array(array('action'=>'print','id'=>$this->_tpl_vars['data']['instock_id'],)));?>')" value="打印">
<input type="button" name="dosubmit1" value="确认" onclick="if(confirm('确认此单据吗？')){ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');}"/>
<?php endif; ?>
</div>

</form>