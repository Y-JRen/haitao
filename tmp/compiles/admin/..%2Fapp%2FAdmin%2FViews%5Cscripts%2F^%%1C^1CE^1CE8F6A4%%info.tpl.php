<?php /* Smarty version 2.6.19, created on 2014-07-23 10:53:05
         compiled from index/info.tpl */ ?>
<div class="title">系统信息</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>服务器信息</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['systemMessage']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['message'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['message']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['message']):
        $this->_foreach['message']['iteration']++;
?>
            <?php if ($this->_tpl_vars['key'] % 2 == 0): ?>
            <tr>
            <?php endif; ?>
                <td width="20%" style="padding-left: 5px"><?php echo $this->_tpl_vars['message']['title']; ?>
</td>
                <td width="30%" <?php if ($this->_tpl_vars['key'] % 2 == 0): ?>style="border-right:1px solid #eeeeee"<?php endif; ?>><?php echo $this->_tpl_vars['message']['value']; ?>
</td>
        <?php endforeach; endif; unset($_from); ?>
        <?php if ($this->_foreach['message']['total'] % 2 != 0): ?>
           <td width="20%"></td>
           <td width="30%"></td>
        <?php endif; ?>
        </tbody>
    </table>
</div>