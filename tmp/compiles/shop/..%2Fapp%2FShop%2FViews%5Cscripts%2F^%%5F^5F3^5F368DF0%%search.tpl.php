<?php /* Smarty version 2.6.19, created on 2014-12-18 16:58:28
         compiled from goods/search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'goods/search.tpl', 122, false),)), $this); ?>

<div class="bread container">
	<ul>
		<?php echo $this->_tpl_vars['crumbs']; ?>

	</ul>
</div><!--breadcrumbs-->
<div class="catalog-Pro container">
	<div class="catalog-Pro-left">
		<dl class="catalog-Pro-nav border2">
		   <p class="catalog-Pro-title"><span class="catalog-Pro-titleSpan">全部分类:</span><span onclick="javascript:location.href='/gallery-0-0-0-0-1.html'">>></span></p>
		   <?php $_from = $this->_tpl_vars['cat_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		   <dt class="shensuo"><a href="/gallery-<?php echo $this->_tpl_vars['v']['cat_id']; ?>
-0-0-0-1.html"><?php echo $this->_tpl_vars['v']['cat_name']; ?>
</a></dt>
		   		<dd style='display:none;'>
		   		<?php $_from = $this->_tpl_vars['v']['sub']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vv']):
?>
		   		<a href="/gallery-<?php echo $this->_tpl_vars['vv']['cat_id']; ?>
-0-0-0-1.html"><?php echo $this->_tpl_vars['vv']['cat_name']; ?>
</a><br>
		   		<?php endforeach; endif; unset($_from); ?>
		   		</dd>
		   <?php endforeach; endif; unset($_from); ?>
			<script type="text/javascript">
			      $(document).ready(function(){
			           $.each($("dl>dt"), function(){
			                $(this).click(function(){
			                    $("dl>dd ").not($(this).next()).slideUp(0);
			                    $(this).next().slideToggle(0);
			                    $("dl>dt ").not($(this)).removeClass('active');
			                    $(this).toggleClass('active');
			                });
			           });
			      });
         </script>
		</dl>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_library/renqi.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	<div class="catalog-Pro-right">
		<div class="screening  border2">
           <div class="screentop">
             <p class="pleft">商品筛选<strong><?php echo $this->_tpl_vars['keywords']; ?>
</strong></p>
             <p class="pright">
             	<?php echo $this->_tpl_vars['nav']; ?>

               
             </p>
           </div>
           <div class="brand_attr">
           		
           		<div class="attrs">
		           <div class="attrs_key">类别:</div>
		           <div class="attrs_values v-fold">
				        <ul class="f-first">
				            <?php $_from = $this->_tpl_vars['filter_cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
								<?php if ($this->_tpl_vars['k'] == 0): ?><p><?php else: ?><li><?php endif; ?><a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" class="<?php if ($this->_tpl_vars['k'] == 0): ?>allbg<?php endif; ?> <?php if ($this->_tpl_vars['v']['is_c']): ?>attrs_current<?php endif; ?>" ><?php echo $this->_tpl_vars['v']['cat_name']; ?>
</a><?php if ($this->_tpl_vars['k'] == 0): ?></p><?php else: ?></li><?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
				      		
				      	</ul>
				      	<?php if (count ( $this->_tpl_vars['filter_cat'] ) > 10): ?>
				      	<div option="more" onclick="moreExpandValue(this)" class="attrs_option"><span class="moreV png_bg">更多</span></div>
				      	<div option="less" onclick="lessExpandValue(this)" class="attrs_option hidden"><span class="moreV png_bg">收起</span></div>
				      	<?php endif; ?>
			      	</div>
	           </div>
	           
	           <div class="attrs">
		           <div class="attrs_key">品牌:</div>
		           <div class="attrs_values v-fold">
				        <ul class="f-first">
				            <?php $_from = $this->_tpl_vars['filter_brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
								<?php if ($this->_tpl_vars['k'] == 0): ?><p><?php else: ?><li><?php endif; ?><a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" class="<?php if ($this->_tpl_vars['k'] == 0): ?>allbg<?php endif; ?> <?php if ($this->_tpl_vars['v']['is_c']): ?>attrs_current<?php endif; ?>" ><?php echo $this->_tpl_vars['v']['brand_name']; ?>
</a><?php if ($this->_tpl_vars['k'] == 0): ?><p><?php else: ?></li><?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
				      	</ul>
				      	<?php if (( count ( $this->_tpl_vars['filter_brand'] ) ) > 10): ?>
				      	<div option="more" onclick="moreExpandValue(this)" class="attrs_option"><span class="moreV png_bg">更多</span></div>
				      	<div option="less" onclick="lessExpandValue(this)" class="attrs_option hidden"><span class="moreV png_bg">收起</span></div>
				      	<?php endif; ?>
			      	</div>
	           </div>
	           
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
           </div>
           <div class="clearfix"></div>
           
           <div class="attrs">
	           <div class="attrs_key">价格:</div>
	           <div class="attrs_values">
			        <ul class="jiagelist">
			        	<?php $_from = $this->_tpl_vars['filter_price']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
							<?php if ($this->_tpl_vars['k'] == 0): ?><p><?php else: ?><li><?php endif; ?><a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" class="<?php if ($this->_tpl_vars['k'] == 0): ?>allbg<?php endif; ?> <?php if ($this->_tpl_vars['v']['is_c']): ?>attrs_current<?php endif; ?>"><?php echo $this->_tpl_vars['v']['price_name']; ?>
</a><?php if ($this->_tpl_vars['k'] == 0): ?></p><?php else: ?></li><?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
			        	
			      	</ul>
		      	</div>
           </div>
           
           <div class="clearfix"></div>
		</div><!--screening -->
		<div class="screening md border2">
		  <div class="sort">
		  	<strong>排序</strong>
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
    foreach ($_from as $this->_tpl_vars['v']):
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
			<div class="fenge"></div>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
		<div class="footer-page">
		<?php echo $this->_tpl_vars['pageNav']; ?>

		</div>
	</div>
</div><!--container-->