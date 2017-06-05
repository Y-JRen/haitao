<?php /* Smarty version 2.6.19, created on 2014-08-12 14:13:54
         compiled from admin/privilege.tpl */ ?>
<div class="tree_div" id="treeboxbox_tree" style="padding: 5px; width:98%; height: 400px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;">
    <table cellpadding="0" cellspacing="0" border="0" id="table">
    <tbody>
    <?php $_from = $this->_tpl_vars['menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="menu_<?php echo $this->_tpl_vars['data']['menu_id']; ?>
">
        <td style="padding-left:<?php echo $this->_tpl_vars['data']['step']*20; ?>
px;<?php if ($this->_tpl_vars['data']['step'] == 1): ?>padding-top:20px;color:red<?php endif; ?>">
        <?php if ($this->_tpl_vars['data']['leaf']): ?><input type="checkbox" name="menu[<?php echo $this->_tpl_vars['data']['menu_id']; ?>
]" value="<?php echo $this->_tpl_vars['data']['menu_path']; ?>
" checked onclick="selectChildAll(this,<?php echo $this->_tpl_vars['data']['menu_id']; ?>
)"><?php endif; ?>
        <b><?php echo $this->_tpl_vars['data']['menu_title']; ?>
<b>
    <?php if ($this->_tpl_vars['data']['leaf'] && $this->_tpl_vars['data']['privilege']): ?>
    <table style="margin-left:20px;border:1px solid #ccc;width:95%">
    <tr id="privilege_<?php echo $this->_tpl_vars['data']['menu_id']; ?>
">
        <td style="background:#f2f2f2;padding:3px">
        <?php $_from = $this->_tpl_vars['data']['privilege']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['p']):
?>
        <?php if ($this->_tpl_vars['group_privilege'][$this->_tpl_vars['data']['menu_id']][$this->_tpl_vars['p']['privilege_id']]): ?>
        <input type="checkbox" name="privilege[<?php echo $this->_tpl_vars['p']['privilege_id']; ?>
]" value="<?php echo $this->_tpl_vars['p']['privilege_id']; ?>
" checked> <?php echo $this->_tpl_vars['p']['title']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        </td>
    </tr>
    </table>
    <?php endif; ?>
    </td></tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<br>
<input type="checkbox" value="" onclick="selectAll(this)" checked>
全选/不选