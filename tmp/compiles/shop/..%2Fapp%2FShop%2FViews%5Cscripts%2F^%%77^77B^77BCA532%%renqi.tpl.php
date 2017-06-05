<?php /* Smarty version 2.6.19, created on 2014-12-17 10:01:26
         compiled from _library/renqi.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', '_library/renqi.tpl', 5, false),)), $this); ?>
<p class="title-hot border2">人气商品</p>
<?php $_from = $this->_tpl_vars['renqi']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>

   <div class="hot-pro">
	<p class="img border1"><a href="/goods-<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
"></a><p>
	<p class="text"><a href="/goods-<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
</a></p>
	<p class="text"><span class="span1">品牌：<?php echo $this->_tpl_vars['v']['brand_name']; ?>
</span><span class="span3" style="margin-right:3px;"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo $this->_tpl_vars['v']['region_imgurl']; ?>
" width=30 height=20></span><span class="span2">来自<?php echo $this->_tpl_vars['v']['region_name']; ?>
</span></p>
</div>
<?php endforeach; endif; unset($_from); ?>