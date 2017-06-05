<?php /* Smarty version 2.6.19, created on 2014-09-01 09:21:40
         compiled from email-template/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'email-template/edit.tpl', 24, false),)), $this); ?>
<?php if ($this->_tpl_vars['template']['type']): ?>
    <?php $this->assign('type', $this->_tpl_vars['template']['type']); ?>
<?php else: ?>
    <?php $this->assign('type', '0'); ?>
<?php endif; ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<div class="title"><?php echo $this->_tpl_vars['title']; ?>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'index',)));?>')">邮件模板列表</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="30%">邮件模板名称 * </td>
<td><input type="text" name="name" id="name" size="20" maxlength="40" value="<?php echo $this->_tpl_vars['template']['name']; ?>
" msg="请填写邮件模板名称" class="required" onchange="ajax_check('<?php echo $this -> callViewHelper('url', array(array('action'=>'check',)));?>','name')" /></td>
</tr>
<tr>
<td width="30%">邮件主题 * </td>
<td><input type="text" name="title" size="35" maxlength="80" value="<?php echo $this->_tpl_vars['template']['title']; ?>
" msg="请填写邮件主题" class="required" /></td>
</tr>
<tr>
<td width="30%">邮件类型 </td>
<td><?php echo smarty_function_html_radios(array('name' => 'type','options' => $this->_tpl_vars['typeOptions'],'checked' => $this->_tpl_vars['type'],'separator' => ""), $this);?>
</td>
</tr>
<tr>
<td>邮件模板内容</td>
<td><textarea style="width: 500px;height: 160px" name="value"><?php echo $this->_tpl_vars['template']['value']; ?>
</textarea></td>
</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>