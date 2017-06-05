<?php /* Smarty version 2.6.19, created on 2014-12-18 17:12:51
         compiled from stock-report/wait-stock-detail.tpl */ ?>
<form name="searchForm" id="searchForm" method="post" action="/admin/stock-report/wait-stock-detail">
<input type="hidden" name="product_id" value="<?php echo $this->_tpl_vars['param']['product_id']; ?>
">
<input type="hidden" name="batch_id" value="<?php echo $this->_tpl_vars['param']['batch_id']; ?>
">
<div class="search">
产品编码 <?php echo $this->_tpl_vars['product']['product_sn']; ?>

产品名称 <?php echo $this->_tpl_vars['product']['product_name']; ?>

&nbsp;&nbsp;
选择仓库
<select name="logic_area" onchange="$('searchForm').submit()">
<?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['param']['logic_area'] == $this->_tpl_vars['key']): ?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['logic_area'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</select>
选择库存状态
<select name="status_id" onchange="$('searchForm').submit()">
<?php $_from = $this->_tpl_vars['status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['status_id'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<br>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">库存管理 -&gt; 在途库存明细
</div>
<div class="content">
    <?php if ($this->_tpl_vars['datas']): ?>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
      <tr>
        <td>单据类型</td>
        <td>单据编号</td>
        <td>在途库存</td>
      </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
      <td>
        <?php if ($this->_tpl_vars['data']['bill_type'] == 'inStatus'): ?>产品状态更改
        <?php elseif ($this->_tpl_vars['data']['bill_type'] == 'inAllocation'): ?>调拨单
        <?php else: ?><?php echo $this->_tpl_vars['inTypes'][$this->_tpl_vars['data']['bill_type']]; ?>

        <?php endif; ?>
      </td>
      <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
      <td><?php echo $this->_tpl_vars['data']['number']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    <tr>
      <td>合计</td>
      <td>&nbsp;</td>
      <td><?php echo $this->_tpl_vars['total']; ?>
</td>
    </tr>
    </tbody>
    </table>
    <?php endif; ?>
</div>
</form>