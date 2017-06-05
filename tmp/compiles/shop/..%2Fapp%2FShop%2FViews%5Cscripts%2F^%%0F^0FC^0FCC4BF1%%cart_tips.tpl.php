<?php /* Smarty version 2.6.19, created on 2014-12-10 14:29:47
         compiled from _library/cart_tips.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', '_library/cart_tips.tpl', 11, false),array('modifier', 'cut_str', '_library/cart_tips.tpl', 13, false),array('modifier', 'number_format', '_library/cart_tips.tpl', 28, false),)), $this); ?>
﻿<div class="cart">
	<a href="/flow/index">我的购物车(<?php echo $this->_tpl_vars['number']; ?>
)</a>
	<span class="caret"></span>
</div>
<ul class="cartul hidden">
	
	<div class="cart_pro">
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
		<?php if ($this->_tpl_vars['goods']['onsale'] == 0): ?>
		<li>
			<img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
">
			<span>
			<a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html"><?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['goods_name'])) ? $this->_run_mod_handler('cut_str', true, $_tmp, 20) : smarty_modifier_cut_str($_tmp, 20)); ?>
</a><br>
			<i>规格: <?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</i>
			</span>
			<p>
			￥<?php echo $this->_tpl_vars['goods']['price']; ?>
 * <?php echo $this->_tpl_vars['goods']['number']; ?>

			<br>
			<a href="" onclick="delCartGoods(<?php echo $this->_tpl_vars['goods']['product_id']; ?>
,<?php echo $this->_tpl_vars['goods']['number']; ?>
,'top');return false;">删除</a>
			</p>
		</li>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	
	</div>
		<li class="cart-last">
			<p class="zongji"> <strong>共<?php echo $this->_tpl_vars['number']; ?>
件商品</strong> <b>总计:</b>
			￥<?php echo ((is_array($_tmp=$this->_tpl_vars['amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>

			</p>
			<p>
			<button onclick="goToPay()">进入购物车</button>
			</p>
		</li>
</ul>