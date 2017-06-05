<?php /* Smarty version 2.6.19, created on 2014-12-19 14:58:16
         compiled from page/new.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'page/new.tpl', 51, false),)), $this); ?>

<div class="theme_container newgoods">
 <div class="theme_title png_bg">最新上架的100款商品，海外正品及时送达！</div>
	<div class="theme_catalog border2">
       <div class="cat_attr">
           <div class="attrs">
	           <div class="attrs_key">分类:</div>
	           <div class="attrs_values v-fold">
			        <ul>
			            <?php $_from = $this->_tpl_vars['filter_cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<?php if ($this->_tpl_vars['k'] == 0): ?><p><?php else: ?><li><?php endif; ?><a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" class="<?php if ($this->_tpl_vars['k'] == 0): ?>allbg<?php endif; ?> <?php if ($this->_tpl_vars['v']['is_c']): ?>attrs_current<?php endif; ?>" ><?php echo $this->_tpl_vars['v']['cat_name']; ?>
</a><?php if ($this->_tpl_vars['k'] == 0): ?></p><?php else: ?></li><?php endif; ?>
					    <?php endforeach; endif; unset($_from); ?>
			      	</ul>
		      	</div>
           </div>
       </div>
       <div class="divide clearfix"></div>
       <div class="brand_attr">
           <div class="attrs">
	           <div class="attrs_key">品牌:</div>
	           <div class="attrs_values v-fold">
			        <ul>
			            <?php $_from = $this->_tpl_vars['filter_brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<?php if ($this->_tpl_vars['k'] == 0): ?><p><?php else: ?><li><?php endif; ?><a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" class="<?php if ($this->_tpl_vars['k'] == 0): ?>allbg<?php endif; ?> <?php if ($this->_tpl_vars['v']['is_c']): ?>attrs_current<?php endif; ?>" ><?php echo $this->_tpl_vars['v']['brand_name']; ?>
</a><?php if ($this->_tpl_vars['k'] == 0): ?></p><?php else: ?></li><?php endif; ?>
					    <?php endforeach; endif; unset($_from); ?>
			      	</ul>
		      	</div>
           </div>
       </div>
       <div class="clearfix"></div> 	
	</div>
	<div class="md border2">
		<div class="sort"><strong>排序</strong>
		<?php $_from = $this->_tpl_vars['sortList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		  	<a href="<?php echo $this->_tpl_vars['v']['url']; ?>
">
		  		<em><?php echo $this->_tpl_vars['v']['sortname']; ?>
</em>
		  		<?php if ($this->_tpl_vars['v']['value'] != 0): ?><i class="<?php echo $this->_tpl_vars['v']['sortclass']; ?>
"></i><?php endif; ?>
		  	</a>
		  	<?php endforeach; endif; unset($_from); ?>
		</div>
		<?php echo $this->_tpl_vars['pageNav1']; ?>

     </div><!--md-->

	<div class="cplist">
			<?php if ($this->_tpl_vars['goodsData'] == null): ?>
			<span style='color:#900'>没有符合您条件的商品</span>
			<?php else: ?>
			<?php $_from = $this->_tpl_vars['goodsData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
			<div class="cpone">
		       <div class="hot-pro mainP">
				<p class="img border1"><a href="/goods-<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
"  height='280'></a><p>
				<p class="text"><a href="/goods-<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
</a></p>
				<p class="text"><span class="span1">品牌：<?php echo $this->_tpl_vars['v']['brand_name']; ?>
</span><span class="span3"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo $this->_tpl_vars['v']['region_imgurl']; ?>
" width=30 height=20></span><span class="span2">来自<?php echo $this->_tpl_vars['v']['region_name']; ?>
</span></p>
		       </div>
		       <div class="cpCart"><strong>￥<?php echo $this->_tpl_vars['v']['price']; ?>
</strong><button onclick="javascript:location.href='/goods-<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html'">立即去购买</button></div>
			</div>
			<?php if (( $this->_tpl_vars['k']+1 ) % 4 != 0): ?>
			<div class="fenge"></div>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			
	</div><!--cplist-->
	<div class="clearfix"></div>
	<div class="footer-page">
		<?php echo $this->_tpl_vars['pageNav']; ?>

	</div>
</div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
	function moreExpandValue(t) {
	    $(t).hide().next().show();
	    $(t).parent().addClass('v-unfold');
	}
	function lessExpandValue(t) {
	    $(t).parent().removeClass('v-unfold');
	    $(t).hide().prev().show();
	}
</script>
</body>
</html>

