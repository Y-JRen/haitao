<?php /* Smarty version 2.6.19, created on 2014-09-03 15:55:15
         compiled from admin-group/index.tpl */ ?>
<div class="title">管理员组管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加管理员组</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>管理员组名称</td>
            <td>分仓</td>
            <td>说明</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['groupList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['group']['group_id']; ?>
">
            <td><?php echo $this->_tpl_vars['group']['group_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['group']['group_name']; ?>
</td>
            <td><?php if ($this->_tpl_vars['group']['type']): ?>是<?php else: ?>否<?php endif; ?></td>
            <td><?php echo $this->_tpl_vars['group']['remark']; ?>
</td>
            <td>
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['group']['group_id'],)));?>')">编辑</a>
                <a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete',)));?>','<?php echo $this->_tpl_vars['group']['group_id']; ?>
')">删除</a>
            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>