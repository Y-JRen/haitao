<?php /* Smarty version 2.6.19, created on 2014-12-23 15:16:31
         compiled from goods/gallery.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'in_array', 'goods/gallery.tpl', 118, false),array('modifier', 'replace', 'goods/gallery.tpl', 133, false),)), $this); ?>

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
		   <dt class="shensuo png_bg <?php if ($this->_tpl_vars['v']['cat_id'] == $this->_tpl_vars['showCat']): ?>active<?php endif; ?>"><a href="/gallery-<?php echo $this->_tpl_vars['v']['cat_id']; ?>
-0-0-0-1.html"><?php echo $this->_tpl_vars['v']['cat_name']; ?>
</a></dt>
		   		<?php if ($this->_tpl_vars['v']['cat_id'] == $this->_tpl_vars['showCat']): ?>
		   		<dd style='display:block;'>
		   		<?php else: ?>
		   		<dd style='display:none;'>
		   		<?php endif; ?>
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
			 <p class="pleft">商品分类<strong><?php if ($this->_tpl_vars['catname']): ?><?php echo $this->_tpl_vars['catname']; ?>
<?php else: ?>全部类别<?php endif; ?></strong></p>
			 <p class="pright">
			 	<?php echo $this->_tpl_vars['nav']; ?>

			   
			 </p>
			</div>
			<?php if ($this->_tpl_vars['cat']): ?>
			<div class="cat_attr">
				<div class="attrs">
			       <div class="attrs_key">类别:</div>
			       <div class="attrs_values v-fold">
				        <ul class="f-first">
				            <p><a href="/"  class="allbg attrs_current">全部</a></p>
				            <?php $_from = $this->_tpl_vars['cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
				            <li><a href="/gallery-<?php echo $this->_tpl_vars['v']['cat_id']; ?>
-<?php echo $this->_tpl_vars['brand_id']; ?>
-<?php echo $this->_tpl_vars['price']; ?>
-<?php echo $this->_tpl_vars['sort']; ?>
-1.html"><?php echo $this->_tpl_vars['v']['cat_name']; ?>
</a></li>
				            <?php endforeach; endif; unset($_from); ?>
				      		
				      	</ul>
				      	<?php if (count ( $this->_tpl_vars['cat'] ) > 10): ?>
				      	<div option="more" onclick="moreExpandValue(this)" class="attrs_option"><span class="moreV png_bg">更多</span></div>
				      	<div option="less" onclick="lessExpandValue(this)" class="attrs_option hidden"><span class="moreV png_bg">收起</span></div>
				      	<?php endif; ?>
			      	</div>
			   </div>
			</div>
			<?php endif; ?>
			<div class="divide clearfix"></div>
            <?php if ($this->_tpl_vars['brand']): ?>
			<div class="brand_attr">
			   <div class="attrs">
			       <div class="attrs_key">品牌:</div>
			       <div class="attrs_values v-fold">
				        <ul class="f-first">
				            <p><a href="/"  class="allbg attrs_current">全部</a></p>
				            <?php $_from = $this->_tpl_vars['brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
				            <li><a href="/gallery-<?php echo $this->_tpl_vars['cat_id']; ?>
-<?php echo $this->_tpl_vars['v']['brand_id']; ?>
-<?php echo $this->_tpl_vars['price']; ?>
-<?php echo $this->_tpl_vars['sort']; ?>
-1.html"><?php echo $this->_tpl_vars['v']['brand_name']; ?>
</a></li>
				            <?php endforeach; endif; unset($_from); ?>
				      		
				      	</ul>
				      	<?php if (count ( $this->_tpl_vars['brand'] ) > 10): ?>
				      	<div option="more" onclick="moreExpandValue(this)" class="attrs_option"><span class="moreV png_bg">更多</span></div>
				      	<div option="less" onclick="lessExpandValue(this)" class="attrs_option hidden"><span class="moreV png_bg">收起</span></div>
				      	<?php endif; ?>
			      	</div>
			   </div>
		   </div>
           <?php endif; ?>
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
           
           <div class="divide clearfix"></div>
           <?php if ($this->_tpl_vars['priceList']): ?>
           <div class="attrs">
	           <div class="attrs_key">价格:</div>
	           <div class="attrs_values">
			        <ul class="jiagelist">
			        	<?php $_from = $this->_tpl_vars['priceList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
			        	<?php if ($this->_tpl_vars['k'] == 0): ?><p><?php else: ?><li><?php endif; ?><a <?php if ($this->_tpl_vars['v']['price_value'] == 0): ?>class="allbg attrs_current"<?php endif; ?> href="/gallery-<?php echo $this->_tpl_vars['cat_id']; ?>
-<?php echo $this->_tpl_vars['brand_id']; ?>
-<?php echo $this->_tpl_vars['v']['price_value']; ?>
-<?php echo $this->_tpl_vars['sort']; ?>
-1.html"><?php echo $this->_tpl_vars['v']['price_name']; ?>
</a><?php if ($this->_tpl_vars['k'] == 0): ?></p><?php else: ?></li><?php endif; ?>
			        	<?php endforeach; endif; unset($_from); ?>
			      	</ul>
		      	</div>
           </div>
           <?php endif; ?>
           <div class="clearfix"></div>
		</div><!--screening -->
		<div class="screening md border2">
		  <div class="sort">
		  	<strong>排序</strong>
		  	<?php $_from = $this->_tpl_vars['sortList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		  	<a href="/gallery-<?php echo $this->_tpl_vars['cat_id']; ?>
-<?php echo $this->_tpl_vars['brand_id']; ?>
-<?php echo $this->_tpl_vars['price']; ?>
-<?php if ($this->_tpl_vars['v']['value'] == 0): ?><?php echo $this->_tpl_vars['v']['sorttype']['0']; ?>
<?php elseif (((is_array($_tmp=$this->_tpl_vars['sort'])) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['v']['sorttype']) : in_array($_tmp, $this->_tpl_vars['v']['sorttype'])) && ( $this->_tpl_vars['sort']%2 ) == 0): ?><?php echo $this->_tpl_vars['v']['sorttype']['0']; ?>
<?php else: ?><?php echo $this->_tpl_vars['v']['sorttype']['1']; ?>
<?php endif; ?>-1.html">
		  		<em><?php echo $this->_tpl_vars['v']['sortname']; ?>
</em>
		  		<?php if ($this->_tpl_vars['v']['value'] != 0): ?><i class="png_bg"  style="background-position:<?php if (((is_array($_tmp=$this->_tpl_vars['sort'])) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['v']['sorttype']) : in_array($_tmp, $this->_tpl_vars['v']['sorttype'])) && ( $this->_tpl_vars['sort']%2 ) == 0): ?>6px -243px<?php else: ?>-25px -245px<?php endif; ?>"></i><?php endif; ?>
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
				<p class="text" style="height:56px;"><a href="/goods-<?php echo $this->_tpl_vars['v']['goods_id']; ?>
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