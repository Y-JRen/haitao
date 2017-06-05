<?php /* Smarty version 2.6.19, created on 2014-07-30 10:22:29
         compiled from goods/img.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'goods/img.tpl', 8, false),)), $this); ?>
<form name="upForm" id="upForm" action="<?php echo $this -> callViewHelper('url', array());?>" method="post" enctype="multipart/form-data" target="ifrmSubmit">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>标准图片</strong></td>
      <td width="88%">
        <?php if ($this->_tpl_vars['data']['goods_img'] != ''): ?>
        <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" border="0" width="50"><br>
        <?php endif; ?>
        <input type="file" name="goods_img" msg="请上传商品图片"/>
    </tr>
    <tr>
      <td width="12%"><strong>广告展示图</strong></td>
      <td>
        <?php if ($this->_tpl_vars['data']['goods_arr_img'] != ''): ?>
        <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_arr_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" border="0" width="50"><br>
        <?php endif; ?>
        <input type="file" name="goods_arr_img" msg="请上传商品图片"/>
    </tr>
    <?php if ($this->_tpl_vars['img_url']): ?>	
	<tr>
      <td><strong>细节图片</strong></td>
      <td><?php if (! empty ( $this->_tpl_vars['img_url'] )): ?><ul id="showimgs">
      <?php $_from = $this->_tpl_vars['img_url']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
      <li id="ajax_list<?php echo $this->_tpl_vars['r']['img_id']; ?>
">
      <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['img_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0"><br>
      <?php $this->assign('img_id', $this->_tpl_vars['r']['img_id']); ?>
      <input type="checkbox" name="img_ids[]" value="<?php echo $this->_tpl_vars['r']['img_id']; ?>
" <?php if ($this->_tpl_vars['img_ids'][$this->_tpl_vars['img_id']]): ?>checked<?php endif; ?>>
       </li>
      <?php endforeach; endif; unset($_from); ?>
      </ul><?php endif; ?>
	  </td>
    </tr>
    <?php endif; ?>
    <!--
    <tr>
      <td><strong>展示图片</strong></td>
      <td><?php if (! empty ( $this->_tpl_vars['img_ext_url'] )): ?><ul id="showimgs">
      <?php $_from = $this->_tpl_vars['img_ext_url']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
      <li id="ajax_list<?php echo $this->_tpl_vars['r']['img_id']; ?>
">
      <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['img_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0"><br>
      <?php $this->assign('img_id', $this->_tpl_vars['r']['img_id']); ?>
      <input type="checkbox" name="img_ids[]" value="<?php echo $this->_tpl_vars['r']['img_id']; ?>
" <?php if ($this->_tpl_vars['img_ids'][$this->_tpl_vars['img_id']]): ?>checked<?php endif; ?>>
       </li>
      <?php endforeach; endif; unset($_from); ?>
      </ul><?php endif; ?>
      </td>
    </tr>
    -->
</tbody>
</table>
<div style="margin:0 auto;padding:10px;">
<input type="submit" name="dosubmit1" id="dosubmit1" value="保存">
</div>
</form>