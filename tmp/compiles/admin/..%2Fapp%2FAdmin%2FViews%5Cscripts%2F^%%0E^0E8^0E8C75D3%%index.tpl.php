<?php /* Smarty version 2.6.19, created on 2014-08-29 15:49:39
         compiled from attribute/index.tpl */ ?>
<div class="title">属性管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加顶级属性</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>属性名称</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['data']['attr_id']; ?>
">
	        <td><input type="text" name="update" size="2" value="<?php echo $this->_tpl_vars['data']['attr_sort']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['attr_id']; ?>
,'attr_sort',this.value)"></td>
            <td><?php echo $this->_tpl_vars['data']['attr_id']; ?>
</td>
            <td style="padding-left:<?php echo $this->_tpl_vars['data']['step']*20; ?>
px;<?php if ($this->_tpl_vars['data']['step'] == 1): ?>font-weight:bold<?php endif; ?>"><?php echo $this->_tpl_vars['data']['attr_title']; ?>
</td>
            <td id="ajax_status<?php echo $this->_tpl_vars['data']['attr_id']; ?>
">
            <?php echo $this->_tpl_vars['data']['status']; ?>

            </td>
            <td>
            <?php if (! $this->_tpl_vars['data']['parent_id']): ?><a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add','id'=>$this->_tpl_vars['data']['attr_id'],)));?>')">添加</a> | <?php endif; ?>
            <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['data']['attr_id'],)));?>')">编辑</a> | 
            <a href="javascript:fGo()" onclick="if (confirm('确定要删除吗？')) G('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete','id'=>$this->_tpl_vars['data']['attr_id'],)));?>')">删除</a>
            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>