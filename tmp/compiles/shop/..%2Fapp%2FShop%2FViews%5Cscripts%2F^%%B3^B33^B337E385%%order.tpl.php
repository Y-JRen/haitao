<?php /* Smarty version 2.6.19, created on 2014-12-30 16:57:14
         compiled from flow/order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'flow/order.tpl', 59, false),array('modifier', 'cut_str', 'flow/order.tpl', 59, false),array('modifier', 'number_format', 'flow/order.tpl', 98, false),array('function', 'math', 'flow/order.tpl', 64, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<form action="/flow/add-order" method="Post">
<div class="allSort container">
 <div class="mycart-title">
 	<h2>填写并核对订单信息</h2>
 </div>
 <div class="getinfo">
  <h3 class="infoh3">收货人信息</h3>
  <div class="checktop">
    <p><b>收货人信息</b><a href="/flow/fillin">[修改]</a></p>
    <p><b><?php echo $this->_tpl_vars['address']['consignee']; ?>
</b> <?php if (( $this->_tpl_vars['address']['mobile'] )): ?><?php echo $this->_tpl_vars['address']['mobile']; ?>
<?php else: ?><?php echo $this->_tpl_vars['address']['phone']; ?>
<?php endif; ?></a></p>
    <p><span><?php echo $this->_tpl_vars['address']['province_msg']['area_name']; ?>
&nbsp;<?php echo $this->_tpl_vars['address']['city_msg']['area_name']; ?>
&nbsp;<?php echo $this->_tpl_vars['address']['area_msg']['area_name']; ?>
&nbsp;<?php echo $this->_tpl_vars['address']['address']; ?>
</span></p>
  </div>
 </div>
  <div class="payinfo">
	   <h3 class="infoh3">支付方式</h3>
	   <p><b>在线支付</b></p>
	   <img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/payactive.png"><br>
  </div>
  <div class="transfer">
       <h3 class="infoh3">配送与发票</h3>
       <div class="ways checkway">
         <b>配送方式</b><br>
           <p><span>顺丰速递 </span>
           </p>
       </div>
       <!-- 
       <div class="ways checkway">
         <b>发票信息</b><br>
         <p>单位: 江苏省南京市XXXX科技有限公司</p>
         <p>单内容: 内容1</p>
       </div>
        -->
  </div>
 <div class="mycart-cont border2">
  <h3 class="infoh4">商品清单<span style='width:900px;display:inline-block;'><?php if ($this->_tpl_vars['jnum'] && $this->_tpl_vars['hnum']): ?>由于您选购的商品分别属于香港仓库和日本仓库，我们将分两个快递包裹为您送达，同时将向您收取两个包裹的快递费用，请知悉！<?php endif; ?></span><a href="/flow">返回修改购物车</a></h3>
    <table>
   	  <tr class="mycarttop mycarttop2">
   	   <td><span>商品</span></td>
   	   <td>商品单价</td>
   	   <td>行邮税</td>
   	   <td>购买数量</td>
   	   <td>小计</td>
   	   <td>操作</td>
   	  </tr>
   	  <?php if ($this->_tpl_vars['hnum']): ?>
   	  <tr class="warehouse">
   	   <td> <p><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/flagbg.png">香港仓库</p></td>
   	   <td></td>
   	   <td></td>
   	   <td></td>
   	   <td></td>
   	   <td></td>
   	  </tr>
   	  <?php $_from = $this->_tpl_vars['data']['hongkong']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
   	  <?php if ($this->_tpl_vars['goods']['onsale'] != 1): ?>
   	  <tr class="cartname">
   	   <td class="mycart_fistrtd">
   	       <p><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"><a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html"><?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['goods_name'])) ? $this->_run_mod_handler('cut_str', true, $_tmp, 30) : smarty_modifier_cut_str($_tmp, 30)); ?>
</a><br><span>规格:<?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</span></p>
   	   </td>
   	   <td><?php echo $this->_tpl_vars['goods']['shop_price']; ?>
</td>
   	   <td><?php echo $this->_tpl_vars['goods']['tax']; ?>
</td>
   	   <td><?php echo $this->_tpl_vars['goods']['number']; ?>
</td>
   	   <td><?php echo smarty_function_math(array('equation' => "(x + y) * z",'x' => $this->_tpl_vars['goods']['shop_price'],'y' => $this->_tpl_vars['goods']['tax'],'z' => $this->_tpl_vars['goods']['number'],'format' => "%0.2f"), $this);?>
</td>
   	   <td> <font onclick="favGoods(this,'<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
')">收藏</font></td>
   	  </tr>
   	  <?php endif; ?>
   	  <?php endforeach; endif; unset($_from); ?>
   	  <?php endif; ?>
   	  <?php if ($this->_tpl_vars['jnum']): ?>
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
   	  <?php if ($this->_tpl_vars['goods']['onsale'] != 1): ?>
   	  <tr class="cartname">
   	   <td class="mycart_fistrtd">
           <p><a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"></a></p>
           <p><a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html"><?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['goods_name'])) ? $this->_run_mod_handler('cut_str', true, $_tmp, 30) : smarty_modifier_cut_str($_tmp, 30)); ?>
</a><br><span>规格:<?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</span></p>
   	   </td>
   	   <td><?php echo $this->_tpl_vars['goods']['shop_price']; ?>
</td>
   	   <td><?php echo $this->_tpl_vars['goods']['tax']; ?>
</td>
   	   <td><?php echo $this->_tpl_vars['goods']['number']; ?>
</td>
   	   <td><?php echo smarty_function_math(array('equation' => "(x + y) * z",'x' => $this->_tpl_vars['goods']['shop_price'],'y' => $this->_tpl_vars['goods']['tax'],'z' => $this->_tpl_vars['goods']['number'],'format' => "%0.2f"), $this);?>
</td>
   	   <td><font onclick="favGoods(this,'<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
')">收藏</font></td>
   	  </tr>
   	  <?php endif; ?>
   	  <?php endforeach; endif; unset($_from); ?>
   	  <?php endif; ?>
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
   	<p>合计总额：<span><strong>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</strong></span>元</p>
   	<p><input type="submit" id="overbuy" class='shopping buyBtn' value="提交订单" /></p>
   </div>
   <div class="remark">
   	添加备注<br>
   	<textarea cols="40" rows="6" name="order_note" id="order_note"><?php echo $this->_tpl_vars['order_note']; ?>
</textarea>
   </div>
</div><!--container-->
</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script>
$(function(){
	$('#order_note').focus(function (){if(this.value == '输入订单备注内容，限500字'){$(this).val('');}});
	$('#order_note').blur(function (){if(this.value == ''){$(this).val('输入订单备注内容，限500字');}});
})
</script>