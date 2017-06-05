<?php /* Smarty version 2.6.19, created on 2016-12-21 10:54:31
         compiled from D:%5Cwamp%5Cwww%5Chaitao%5Clib%5CWidget%5CAdvertWidget%5Chtml%5Cindex-focus.html */ ?>
<!--首焦图-->
<div id="scrollDiv">
	<ul id="subScrollDiv">
		<?php $_from = $this->_tpl_vars['adlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
		<li>
			<a target="_blank"  href="<?php echo $this->_tpl_vars['item']['url']; ?>
">
				<img alt="<?php echo $this->_tpl_vars['item']['desc']; ?>
" title="<?php echo $this->_tpl_vars['item']['desc']; ?>
"  src="<?php echo $this->_tpl_vars['item']['content']; ?>
" width='950' hegiht='312'/>
			</a>
		</li>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
</div>





