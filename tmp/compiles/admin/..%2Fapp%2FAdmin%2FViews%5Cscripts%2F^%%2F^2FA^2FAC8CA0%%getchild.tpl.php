<?php /* Smarty version 2.6.19, created on 2014-12-30 15:51:17
         compiled from menu/getchild.tpl */ ?>
<?php if (! empty ( $this->_tpl_vars['menus'] )): ?>
<select name="sel_menu_id" onchange="if(this.value&gt;0){ajax_get_child('<?php echo $this -> callViewHelper('url', array(array('action'=>'getchild',)));?>',this.value);this.disabled=true;}">
      <?php if ($this->_tpl_vars['pid'] == 0): ?>
      <option value="0" selected>设为顶部菜单</option>
      <?php else: ?>
      <option value="-1" selected>请选择</option>
      <?php endif; ?>
      <?php $_from = $this->_tpl_vars['menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
      <option value="<?php echo $this->_tpl_vars['menu']['menu_id']; ?>
"><?php echo $this->_tpl_vars['menu']['menu_title']; ?>
</option>
      <?php endforeach; endif; unset($_from); ?>
</select>
<?php endif; ?>