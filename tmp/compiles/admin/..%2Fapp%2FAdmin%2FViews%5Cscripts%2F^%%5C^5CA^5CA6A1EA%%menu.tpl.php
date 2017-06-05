<?php /* Smarty version 2.6.19, created on 2014-07-23 10:53:06
         compiled from index/menu.tpl */ ?>
<script type="text/javascript">
d=new dTree("d");
<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['r']):
?>
<?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['pid']): ?>
d.add(<?php echo $this->_tpl_vars['r']['menu_id']; ?>
, -1, ' <?php echo $this->_tpl_vars['r']['menu_title']; ?>
');
<?php else: ?>
d.add(<?php echo $this->_tpl_vars['r']['menu_id']; ?>
, <?php echo $this->_tpl_vars['r']['parent_id']; ?>
, '<?php echo $this->_tpl_vars['r']['menu_title']; ?>
', '<?php echo $this->_tpl_vars['r']['url']; ?>
', '', '', '', '', '<?php if ($this->_tpl_vars['r']['is_open'] == 0): ?>true<?php endif; ?>');
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
$('menu_iframe').innerHTML = d;
</script>