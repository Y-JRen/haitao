<?php /* Smarty version 2.6.19, created on 2014-08-18 15:10:31
         compiled from goods/edit-tag.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" enctype="multipart/form-data" />
<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_form">
    <tr>
      <td width="28%" height="45" align="right"><strong>标题</strong> * </td>
      <td width="72%">
        <label>
          <input type="text" name="title" />
        </label>
      (如：热卖)</td>
    </tr>
    <tr>
      <td height="45" align="right"><strong>标签</strong> * </td>
      <td><label>
        <input type="text" name="tag" />
      (如：hot )</label></td>
    </tr>
	
    <tr>
      <td height="45" align="right"><strong>类别</strong> * </td>
      <td>
	  <label>
		<select name="type" >
		<option value="goods" <?php if ($this->_tpl_vars['type'] == 'goods'): ?> selected <?php endif; ?>> 单个商品 </option>
	    <option value="groupgoods" <?php if ($this->_tpl_vars['type'] == 'groupgoods'): ?> selected <?php endif; ?>> 组合商品 </option>
		<option value="brand" <?php if ($this->_tpl_vars['type'] == 'brand'): ?> selected <?php endif; ?>> 品牌 </option>
		</select>
	  </label>
	  </td>
    </tr>
	<tr>
      <td height="50" colspan="2" align="center">
	  <div class="submit"><input type="submit" name="dosubmit1" id="dosubmit1" value="确定"/> <input type="reset" name="reset" value="重置" /></div> </td>
    </tr>
</table>
</form>