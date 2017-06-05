<?php /* Smarty version 2.6.19, created on 2014-12-23 10:28:39
         compiled from help/index.tpl */ ?>
<div class="bread container">
	<ul><?php echo $this->_tpl_vars['ur_here']; ?>
</ul>
</div>
<!--breadcrumbs-->
<div class="support container">
 <div class="spleft">
 	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_library/footer_nav.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </div>
 <div class="spright">
 	<div class="spright-title">
 		<?php echo $this->_tpl_vars['info']['title']; ?>

 	</div>
 	<div class="spright-text"><?php echo $this->_tpl_vars['info']['content']; ?>
</div>
 </div>
</div><!--support-->
	
<script type="text/javascript">
	$(function(){
		$('.spleft dl:last').addClass('lastbotm');
	})
</script>