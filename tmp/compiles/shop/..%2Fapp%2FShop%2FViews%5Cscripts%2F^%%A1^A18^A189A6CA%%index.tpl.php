<?php /* Smarty version 2.6.19, created on 2014-12-30 16:57:03
         compiled from flow/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'flow/index.tpl', 31, false),array('modifier', 'cut_str', 'flow/index.tpl', 32, false),array('modifier', 'number_format', 'flow/index.tpl', 84, false),array('function', 'math', 'flow/index.tpl', 41, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="allSort container">
 <div class="mycart-title">
 	<h2>我的购物车</h2>
 	<?php if ($this->_tpl_vars['auth']['user_id'] == ''): ?>
 	<p>现在 <button onclick="gotoLogin()">登录</button> 购物车中的商品将被永久保存</p>
 	<?php endif; ?>
 </div>
 <div class="mycart-cont border2">
   <table>
   	  <tr class="mycarttop">
   	   <td class="mycart_fistrtd"><span>商品</span></td>
   	   <td>商品单价</td>
   	   <td>行邮税</td>
   	   <td>购买数量</td>
   	   <td>小计</td>
   	   <td>操作</td>
   	  </tr>
   	  <?php if (( $this->_tpl_vars['data']['hongkong'] )): ?>
   	  <tr class="warehouse">
   	   <td> <p><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/flagbg.png">香港仓库</p></td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	  </tr>
   	  <?php $_from = $this->_tpl_vars['data']['hongkong']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
   	  <tr class="cartname <?php if ($this->_tpl_vars['goods']['onsale'] == 1): ?>cart_offshelf<?php endif; ?>">
   	   <td class="mycart_fistrtd">
            <p><?php if ($this->_tpl_vars['goods']['onsale'] == 1): ?><i></i><?php endif; ?><a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"></a></p> 
             <p><a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html" title='<?php echo $this->_tpl_vars['goods']['goods_name']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['goods_name'])) ? $this->_run_mod_handler('cut_str', true, $_tmp, 30) : smarty_modifier_cut_str($_tmp, 30)); ?>
</a><br><span>规格:<?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</span></p>
         </td>
   	   <td><?php echo $this->_tpl_vars['goods']['shop_price']; ?>
</td>
   	   <td><?php echo $this->_tpl_vars['goods']['tax']; ?>
</td>
   	   <td>
   	   		<input type="button" value="-" class="cart1" <?php if ($this->_tpl_vars['goods']['onsale'] == 0): ?>onclick="selNumLess('<?php echo $this->_tpl_vars['goods']['product_id']; ?>
')"<?php endif; ?>>
   	   		<input type="text" class="carttext" value="<?php echo $this->_tpl_vars['goods']['number']; ?>
" id="buy_number_<?php echo $this->_tpl_vars['goods']['product_id']; ?>
" readonly="readonly">
   	   		<input type="button" value="+" class="cart2" <?php if ($this->_tpl_vars['goods']['onsale'] == 0): ?>onclick="selNumAdd('<?php echo $this->_tpl_vars['goods']['product_id']; ?>
')"<?php endif; ?>>
   	   	</td>
   	   <td><?php echo smarty_function_math(array('equation' => "(x + y) * z",'x' => $this->_tpl_vars['goods']['shop_price'],'y' => $this->_tpl_vars['goods']['tax'],'z' => $this->_tpl_vars['goods']['number'],'format' => "%0.2f"), $this);?>
</td>
   	   <td> <font onclick="favGoods(this,'<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
')">收藏</font> | <a href="/flow/del?product_id=<?php echo $this->_tpl_vars['goods']['product_id']; ?>
" onclick="return confirmMsg();">删除</a></td>
   	  </tr>
   	  <?php endforeach; endif; unset($_from); ?>
   	  <?php endif; ?>
   	  <?php if (( $this->_tpl_vars['data']['japanese'] )): ?>
   	  <tr class="warehouse">
   	   <td><p><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/flag_jp.png">日本仓库</p></td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	  </tr>
   	  <?php $_from = $this->_tpl_vars['data']['japanese']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
   	  <tr class="cartname <?php if ($this->_tpl_vars['goods']['onsale'] == 1): ?>cart_offshelf<?php endif; ?>">
   	   <td class="mycart_fistrtd">
          <p><?php if ($this->_tpl_vars['goods']['onsale'] == 1): ?><i></i><?php endif; ?><a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"></a></p>
             <p><a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html" title='<?php echo $this->_tpl_vars['goods']['goods_name']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['goods_name'])) ? $this->_run_mod_handler('cut_str', true, $_tmp, 30) : smarty_modifier_cut_str($_tmp, 30)); ?>
</a><br><span>规格:<?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</span></p>
   	   </td>
   	   <td><?php echo $this->_tpl_vars['goods']['shop_price']; ?>
</td>
   	   <td><?php echo $this->_tpl_vars['goods']['tax']; ?>
</td>
   	   <td>
	   	   <input type="button" value="-" class="cart1"  <?php if ($this->_tpl_vars['goods']['onsale'] == 0): ?>onclick="selNumLess('<?php echo $this->_tpl_vars['goods']['product_id']; ?>
')"<?php endif; ?>>
	   	   <input type="text" class="carttext" value="<?php echo $this->_tpl_vars['goods']['number']; ?>
"  onchange='changeNumber(<?php echo $this->_tpl_vars['goods']['product_id']; ?>
,this.value,<?php echo $this->_tpl_vars['goods']['number']; ?>
)' id="buy_number_<?php echo $this->_tpl_vars['goods']['product_id']; ?>
">
	   	   <input type="button" value="+" class="cart2"  <?php if ($this->_tpl_vars['goods']['onsale'] == 0): ?>onclick="selNumAdd('<?php echo $this->_tpl_vars['goods']['product_id']; ?>
')"<?php endif; ?>></td>
   	   <td><?php echo smarty_function_math(array('equation' => "(x + y) * z",'x' => $this->_tpl_vars['goods']['shop_price'],'y' => $this->_tpl_vars['goods']['tax'],'z' => $this->_tpl_vars['goods']['number'],'format' => "%0.2f"), $this);?>
</td>
   	   <td><font onclick="favGoods(this,'<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
')">收藏</font> | <a href="/flow/del?product_id=<?php echo $this->_tpl_vars['goods']['product_id']; ?>
" onclick="return confirmMsg();">删除</a></td>
   	  </tr>
   	  <?php endforeach; endif; unset($_from); ?>
   	  <?php endif; ?>
       
   	  <tr class="mycartbottom">
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	   <td> </td>
   	  </tr>
   </table>
 </div>
    <div class="cartprice">
   	<p>已选<span><?php echo $this->_tpl_vars['number']; ?>
</span>件商品,商品总额：<span><strong>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['shop_price'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</strong></span>元</p>
   	<p>行邮税：<span><strong>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['oldTax'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</strong></span>元</p>
   	<p>快递费：<span><strong>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['shipping_fee'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</strong></span>元</p>
   	<?php if ($this->_tpl_vars['disTax']): ?>
   	<p>行邮税减免（行邮税低于50元）：<span><strong>-￥<?php echo ((is_array($_tmp=$this->_tpl_vars['disTax'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</strong></span>元</p>
   	<?php endif; ?>
   	<hr>
   	<p>合计总额 ：<span><strong>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</strong></span>元</p>
   	<p><button id="overbuy" class="buyBtn <?php if ($this->_tpl_vars['err'] == 1): ?>shopping<?php else: ?>unshopping<?php endif; ?>" onclick="<?php if ($this->_tpl_vars['err'] == 1): ?>ftogoOrer()<?php else: ?>javascript:alert('<?php echo $this->_tpl_vars['alert']; ?>
');<?php endif; ?>">去结算 ></button><button id="gobuy" type="button" class="buyBtn" onClick="fngobuy()">继续购物</button></p>
   </div>
</div><!--container-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script>
function gotoLogin()
{
	location.href="/login.html";
}
//继续购物
function fngobuy()
{
	location.href="/"
}
//结账
function ftogoOrer()
{
	location.href="/flow/order";
}
</script>