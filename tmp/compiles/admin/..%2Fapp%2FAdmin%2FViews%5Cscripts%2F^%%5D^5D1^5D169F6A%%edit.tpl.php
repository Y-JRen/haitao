<?php /* Smarty version 2.6.19, created on 2014-12-30 15:52:01
         compiled from admin-group/edit.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<div class="title"><?php echo $this->_tpl_vars['title']; ?>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'index',)));?>')">管理员组列表</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">管理员组名称 * </td>
<td><input type="text" name="group_name" size="20" maxlength="20" value="<?php echo $this->_tpl_vars['group']['group_name']; ?>
" msg="请填写管理员组名称" class="required" /></td>
</tr>
<tr>
<td>分仓</td>
<td>
<input type="radio" name="type" value="1" <?php if ($this->_tpl_vars['group']['type']): ?>checked<?php endif; ?>>是
<input type="radio" name="type" value="0" <?php if ($this->_tpl_vars['action'] == 'add' || ! $this->_tpl_vars['group']['type']): ?>checked<?php endif; ?>>否
</td>
</tr>
<tr>
<td>说明</td>
<td><textarea style="width: 400px;height: 50px" name="remark"><?php echo $this->_tpl_vars['group']['remark']; ?>
</textarea></td>
</tr>
<tr>
<td>权限</td>
<td colspan="3"><div class="tree_div" id="treeboxbox_tree" style="padding: 5px; width:98%; height: 400px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;">
    <table cellpadding="0" cellspacing="0" border="0" id="table">
    <tbody>
    <?php $_from = $this->_tpl_vars['menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="menu_<?php echo $this->_tpl_vars['data']['menu_id']; ?>
">
        <td style="padding-left:<?php echo $this->_tpl_vars['data']['step']*20; ?>
px;<?php if ($this->_tpl_vars['data']['step'] == 1): ?>padding-top:20px;color:red<?php endif; ?>">
        <?php if ($this->_tpl_vars['data']['leaf']): ?><input type="checkbox" name="group_menu[<?php echo $this->_tpl_vars['data']['menu_id']; ?>
]" value="<?php echo $this->_tpl_vars['data']['menu_path']; ?>
" <?php if ($this->_tpl_vars['menu'][$this->_tpl_vars['data']['menu_id']]): ?>checked<?php endif; ?> onclick="selectChildAll(this,<?php echo $this->_tpl_vars['data']['menu_id']; ?>
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
        <input type="checkbox" name="group_privilege[<?php echo $this->_tpl_vars['data']['menu_id']; ?>
][<?php echo $this->_tpl_vars['p']['privilege_id']; ?>
]" value="<?php echo $this->_tpl_vars['p']['privilege_id']; ?>
" <?php if ($this->_tpl_vars['privilege'][$this->_tpl_vars['data']['menu_id']][$this->_tpl_vars['p']['privilege_id']]): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['p']['title']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;
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
<input type="checkbox" value="" onclick="selectAll(this)">
全选/不选
</td>
</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function selectChildAll(obj, id)
{
	var div = 'privilege_'+id;
	var val = obj.checked;
	var checkbox = $(div).getElements('input[type=checkbox]');
	for(var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if(e.name != obj.name) {
			e.checked = obj.checked;
		}
	}
}
function selectAll(obj)
{
    var div = 'table';
	var val = obj.checked;
	var checkbox = $(div).getElements('input[type=checkbox]');
	for(var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if(e.name != obj.name) {
			e.checked = obj.checked;
		}
	}
}
</script>