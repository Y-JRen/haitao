<?php /* Smarty version 2.6.19, created on 2014-09-01 09:21:35
         compiled from email-template/index.tpl */ ?>
<div class="title">邮件模板</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加邮件模板</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>邮件模板名称</td>
            <td>邮件主题</td>
            <td>邮件类型</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['templateList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['template']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['template']['template_id']; ?>
">
            <td><?php echo $this->_tpl_vars['template']['template_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['template']['name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['template']['title']; ?>
</td>
            <td><?php echo $this->_tpl_vars['template']['type']; ?>
</td>
            <td>
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['template']['template_id'],)));?>')">编辑</a>
                <a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete',)));?>','<?php echo $this->_tpl_vars['template']['template_id']; ?>
')">删除</a>
            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>