<?php /* Smarty version 2.6.19, created on 2016-12-21 10:33:04
         compiled from ad/edit-adboard.tpl */ ?>
<div class="title">编辑广告位</div>
<form  name="myForm" id="myForm"  action="/admin/ad/edit-adboard" method="post" onsubmit="return check_post()">
<div class="content">
<table width="100%" cellspacing="1" cellpadding="2" class="table_form">
<tbody><tr><th width="80">广告位名称 :</th><td><input type="text" size="30"  value="<?php echo $this->_tpl_vars['adboardInfo']['name']; ?>
"  class="input-text" id="name" name="name">
<div id="nameTip" class="onShow">请输入广告位名称</div></td></tr>
<tr><th width="80">广告位类型 :</th><td>
<select name="tpl">
<?php $_from = $this->_tpl_vars['tplOptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['adboardInfo']['tpl']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select></td></tr>

<tr><th>广告位尺寸 :</th>
<td>宽 : <input type="text"  value="<?php echo $this->_tpl_vars['adboardInfo']['width']; ?>
"  size="6" class="input-text" id="width" name="width"> px&nbsp;&nbsp;&nbsp;&nbsp;
高 : <input type="text"  value="<?php echo $this->_tpl_vars['adboardInfo']['height']; ?>
" size="6" class="input-text" id="height" name="height"> px</td></tr>
<tr><th>广告位说明 :</th><td><textarea name="description" id="description" class="input-textarea" cols="45" rows="4"><?php echo $this->_tpl_vars['adboardInfo']['description']; ?>
</textarea></td></tr>

<tr><th>是否启用 :</th><td>
<label><input type="radio" <?php if ($this->_tpl_vars['adboardInfo']['status'] == 1): ?>checked=""<?php endif; ?> value="1" name="status">是</label>&nbsp;&nbsp;
<label><input type="radio" <?php if ($this->_tpl_vars['adboardInfo']['status'] == 0): ?>checked=""<?php endif; ?>  value="0" name="status">否</label></td></tr></tbody></table>
</div>

<div class="submit">
<input type="hidden" value="<?php echo $this->_tpl_vars['adboardInfo']['id']; ?>
" name="id" />
<input type="hidden" value="submitted" name="submitted" />
<input type="submit" value="确定" id="dosubmit" name="dosubmit"> <input type="reset" value="重置" name="reset"></div>
</form>
<script>
function check_post()
{
	 if($("name").value	== '')
	 {
	    alert("请输入广告位名称！");
	    $("name").focus();
	    return false;
	 }
	 return true;
}
</script>