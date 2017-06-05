<?php /* Smarty version 2.6.19, created on 2014-08-12 14:10:22
         compiled from admin/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'admin/edit.tpl', 26, false),array('modifier', 'default', 'admin/edit.tpl', 152, false),)), $this); ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<div class="title"><?php echo $this->_tpl_vars['title']; ?>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'index',)));?>')">管理员列表</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">管理员名称 * </td>
<td width="40%"><input type="text" name="admin_name" id="admin_name" msg="请填写管理员名称" class="required limitlen" min="4" max="15" size="15" maxlength="20" value="<?php echo $this->_tpl_vars['admin']['admin_name']; ?>
" <?php if ($this->_tpl_vars['action'] == 'edit'): ?>readonly<?php else: ?>onchange="ajax_check('<?php echo $this -> callViewHelper('url', array(array('action'=>'check',)));?>','admin_name')"<?php endif; ?> /><span id="tip_admin_name" class="errorMessage">请输入4-15个字符</span></td>
<td width="10%">真实姓名 * </td>
<td width="40%"><input type="text" name="real_name" msg="请填写真实姓名" class="required" size="15" maxlength="20" value="<?php echo $this->_tpl_vars['admin']['real_name']; ?>
" /></td>
</tr>
<tr>
<td>管理员密码<?php if ($this->_tpl_vars['action'] == 'add'): ?> * <?php endif; ?></td>
<td><input type="password" name="password" size="15" maxlength="20" <?php if ($this->_tpl_vars['action'] == 'add'): ?>msg="请填写管理员密码" class="required" <?php endif; ?>/></td>
<td>重复密码<?php if ($this->_tpl_vars['action'] == 'add'): ?> * <?php endif; ?></td>
<td><input type="password" name="confirm_password" size="15" maxlength="40" <?php if ($this->_tpl_vars['action'] == 'add'): ?>msg="请填写重复密码" class="required equal" to="password" <?php endif; ?>/><?php if ($this->_tpl_vars['action'] == 'edit'): ?> <?php echo $this->_tpl_vars['changePassword']; ?>
<?php endif; ?></td>
</tr>
<tr>
<td>管理员组</td>
<td>
<select name="group_id" id="group_id" msg="请选择管理员组" class="required" onchange="getPrivilege(this.value)">
<option value="">请选择</option>
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['groupIds'],'selected' => $this->_tpl_vars['admin']['group_id']), $this);?>

</select>
</td>
<td width="10%"><span id="lid_area1">所属仓库</span></td>
<td width="40%">
<span id="lid_area2">
<?php $_from = $this->_tpl_vars['areaList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['data'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['data']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['areaID'] => $this->_tpl_vars['areaName']):
        $this->_foreach['data']['iteration']++;
?>
<input type="radio" name="lid" value="<?php echo $this->_tpl_vars['areaID']; ?>
" <?php if ($this->_tpl_vars['admin']['lid'] == $this->_tpl_vars['areaID'] || ( $this->_tpl_vars['action'] == 'add' && $this->_foreach['data']['iteration'] == 1 )): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['areaName']; ?>

<?php endforeach; endif; unset($_from); ?>
</span>
</td>
</tr>
<tr>
<td>Email</td>
<td colspan="3"><input type="text" name="email" size="25" maxlength="25" value="<?php echo $this->_tpl_vars['admin']['email']; ?>
"/></td>
</tr>
<tr>
<td>权限</td>
<td colspan="3" id="privilege">
<?php if ($this->_tpl_vars['action'] == 'edit'): ?>
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
        <?php if ($this->_tpl_vars['group_privilege'][$this->_tpl_vars['data']['menu_id']][$this->_tpl_vars['p']['privilege_id']]): ?>
        <input type="checkbox" name="privilege[<?php echo $this->_tpl_vars['p']['privilege_id']; ?>
]" value="<?php echo $this->_tpl_vars['p']['privilege_id']; ?>
" <?php if ($this->_tpl_vars['privilege'][$this->_tpl_vars['p']['privilege_id']]): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['p']['title']; ?>
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
<input type="checkbox" value="" onclick="selectAll(this)">
全选/不选
<?php else: ?>
请选择管理员组
<?php endif; ?>
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
function getPrivilege(id){

    setLidArea(id);
    
    url = '<?php echo $this -> callViewHelper('url', array(array('action'=>'privilege',)));?>';
    url = filterUrl(url, 'gid');
    new Request({
        url: url + '/gid/' + id,
        onRequest: loading,
        onSuccess:function(data){
        $('privilege').innerHTML = data;
        loadSucess();
        }
    }).send();
}
function setLidArea(id)
{
    url = '<?php echo $this -> callViewHelper('url', array(array('action'=>'get',)));?>';
    url = filterUrl(url, 'gid');
    new Request({
        url: url + '/gid/' + id,
        onRequest: loading,
        onSuccess:function(data){
            if (data) {
                var info = JSON.decode(data);
                if (info.type == 1) {
                    $('lid_area1').style.display = '';
                    $('lid_area2').style.display = '';
                }
                else {
                    $('lid_area1').style.display = 'none';
                    $('lid_area2').style.display = 'none';
                }
            }
            else {
                $('lid_area1').style.display = 'none';
                $('lid_area2').style.display = 'none';
            }
            loadSucess();
        }
    }).send();
}
setLidArea(<?php echo ((is_array($_tmp=@$this->_tpl_vars['admin']['group_id'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
);
</script>