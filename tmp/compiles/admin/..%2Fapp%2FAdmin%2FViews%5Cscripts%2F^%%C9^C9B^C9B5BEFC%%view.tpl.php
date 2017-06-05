<?php /* Smarty version 2.6.19, created on 2014-12-19 16:39:47
         compiled from logic-area-out-stock/view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'logic-area-out-stock/view.tpl', 13, false),)), $this); ?>
<div class="title">查看详情</div>
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
      <td><strong>称重</strong></td>
      <td>&nbsp;<?php echo $this->_tpl_vars['data']['weight']; ?>
</td>
      <td><strong>备注</strong></td>
      <td>&nbsp;<?php echo $this->_tpl_vars['data']['remark']; ?>
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
    <td>应发数量</td>
	<td>成本<?php if ($this->_tpl_vars['data']['bill_type'] == 2): ?>/退货金额<?php endif; ?></td>
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
<?php else: ?>无批次<?php endif; ?></td>
    <td><?php echo $this->_tpl_vars['d']['number']; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['p_cost']; ?>
<?php if ($this->_tpl_vars['data']['bill_type'] == 2): ?>/<?php echo $this->_tpl_vars['d']['shop_price']; ?>
<?php endif; ?></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
<div style="text-align:right;padding:10px 20px">
  <?php if ($this->_tpl_vars['data']['bill_type'] == '2'): ?>
  <strong>退款金额：</strong><?php echo $this->_tpl_vars['data']['amount']; ?>

  <?php endif; ?>
  <strong>数量合计：</strong><?php echo $this->_tpl_vars['data']['total_number']; ?>

</div>

<?php if ($this->_tpl_vars['op_cancel']): ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%" style="color:red"><strong>申请取消</strong></td>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td width="12%"><strong>申请日期</strong></td>
      <td width="20%"><?php echo ((is_array($_tmp=$this->_tpl_vars['op_cancel']['op_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
      <td width="12%"><strong>申请人</strong></td>
      <td><?php echo $this->_tpl_vars['op_cancel']['admin_name']; ?>
</td>
    </tr>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="3"><?php echo $this->_tpl_vars['op_cancel']['remark']; ?>
</td>
    </tr>
</tbody>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['op_cancel_check']): ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%" style="color:red"><strong>审核意见</strong></td>
      <td colspan="3"><?php echo $this->_tpl_vars['op_cancel_check']['item_value']; ?>
</td>
    </tr>
    <tr>
      <td width="12%"><strong>审核日期</strong></td>
      <td width="20%"><?php echo ((is_array($_tmp=$this->_tpl_vars['op_cancel_check']['op_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
      <td width="12%"><strong>审核人</strong></td>
      <td><?php echo $this->_tpl_vars['op_cancel_check']['admin_name']; ?>
</td>
    </tr>
    <?php if ($this->_tpl_vars['receive']): ?>
    <tr>
      <td><strong>应收款金额</strong></td>
      <td colspan="3"><?php echo $this->_tpl_vars['receive']['amount']; ?>
</td>
    </tr>
    <?php endif; ?>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="3"><?php echo $this->_tpl_vars['op_cancel_check']['remark']; ?>
</td>
    </tr>
</tbody>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['op_check']): ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%" style="color:red"><strong>审核意见</strong></td>
      <td colspan="3"><?php echo $this->_tpl_vars['op_check']['item_value']; ?>
</td>
    </tr>
    <tr>
      <td width="12%"><strong>审核日期</strong></td>
      <td width="20%"><?php echo ((is_array($_tmp=$this->_tpl_vars['op_check']['op_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
      <td width="12%"><strong>审核人</strong></td>
      <td><?php echo $this->_tpl_vars['op_check']['admin_name']; ?>
</td>
    </tr>
    <?php if ($this->_tpl_vars['receive']): ?>
    <tr>
      <td><strong>应收款金额</strong></td>
      <td colspan="3"><?php echo $this->_tpl_vars['receive']['amount']; ?>
</td>
    </tr>
    <?php endif; ?>
    <tr>
      <td><strong>说明</strong></td>
      <td colspan="3"><?php echo $this->_tpl_vars['op_check']['remark']; ?>
</td>
    </tr>
</tbody>
</table>
<?php endif; ?>

</div>

<div class="submit">
<input type="button" onclick="window.open('<?php echo $this -> callViewHelper('url', array(array('action'=>'print','id'=>$this->_tpl_vars['data']['outstock_id'],)));?>')" value="打印">
<?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
<?php if ($this->_tpl_vars['data']['is_cancel'] == 0 && ( $this->_tpl_vars['data']['bill_status'] == 3 || $this->_tpl_vars['data']['bill_status'] == 4 )): ?>
<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'cancel','id'=>$this->_tpl_vars['data']['outstock_id'],)));?>','ajax','申请取消',400,200)" value="申请取消">
<?php endif; ?>
<?php endif; ?>
</div>