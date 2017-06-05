<?php /* Smarty version 2.6.19, created on 2015-04-30 11:06:20
         compiled from category/sel-attr.tpl */ ?>
<div>
<form name="myForm2" id="myForm2">
<input type="hidden" name="cat_id" value="<?php echo $this->_tpl_vars['category']['cat_id']; ?>
">
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td width="20px"><br><br><br></td>
<td><b><font color="red"><?php echo $this->_tpl_vars['category']['cat_name']; ?>
</font> 选择下列顶级属性：</b></td>
<td>
</td>
</tr>
<?php $_from = $this->_tpl_vars['attrData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attrID'] => $this->_tpl_vars['data']):
?>
<tr>
<td>&nbsp;</td>
<td>
<input type="checkbox" name="attrID[]" value="<?php echo $this->_tpl_vars['attrID']; ?>
" <?php if ($this->_tpl_vars['catAttrInfo'][$this->_tpl_vars['attrID']]): ?>checked<?php endif; ?> onclick="checkAll(<?php echo $this->_tpl_vars['attrID']; ?>
, this.checked)"> <b><?php echo $this->_tpl_vars['data']['name']; ?>
</b>
</td>
</tr>
<?php if ($this->_tpl_vars['data']['detail']): ?>
<tr>
<td>&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php $_from = $this->_tpl_vars['data']['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['detail']):
?>
<input type="checkbox" name="subAttrID[<?php echo $this->_tpl_vars['attrID']; ?>
][]" value="<?php echo $this->_tpl_vars['detail']['attr_id']; ?>
" <?php if ($this->_tpl_vars['subAttrData'][$this->_tpl_vars['attrID']][$this->_tpl_vars['detail']['attr_id']]): ?>checked<?php endif; ?>>
<?php echo $this->_tpl_vars['detail']['attr_title']; ?>
&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?>
</td>
</tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="20px">&nbsp;</td>
  <td>
    <br>
    <input type="button" name="submit" value="保存" onclick="ajax_submit($('myForm2'), '<?php echo $this -> callViewHelper('url', array());?>')">
  </td>
</tr>
</table>
</form>
</div>
<script>
function checkAll(attrID, checked)
{
    var objects = document.getElementsByName('subAttrID[' + attrID + '][]')
    if (objects.length > 0) {
        for (var i = 0; i < objects.length; i++) {
            objects[i].checked = checked;
        }
    }
}
</script>