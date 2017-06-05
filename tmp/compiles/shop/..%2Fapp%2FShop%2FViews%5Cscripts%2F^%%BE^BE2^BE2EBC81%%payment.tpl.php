<?php /* Smarty version 2.6.19, created on 2014-12-17 14:10:25
         compiled from flow/payment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'flow/payment.tpl', 21, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="allSort container">
 <div class="mycart-title">
 	<h2>成功提交订单</h2>
 </div>
 <div class="getinfo successinfo">
   <div class="success-flag"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/success.png"></div>
   <div class="success-main">
     <h3>订单已提交，请尽快付款，以便我们尽快处理订单！</h3>
     <p><?php if ($this->_tpl_vars['hongkong'] && $this->_tpl_vars['japanese']): ?>由于您购买的商品分两个包裹送达，为便于对订单信息跟踪和管理，我们把您的订单拆分成两个订单。<?php endif; ?></p>
     <table>
       <tr>
         <td><b>仓库</b></td>
         <td><b>订单号</b></td>
         <td><b>订单金额</b></td>
       </tr>
       <?php if (( $this->_tpl_vars['hongkong'] )): ?>
       <tr>
         <td>香港仓库</td>
         <td><?php echo $this->_tpl_vars['hongkong']['order_sn']; ?>
</td>
         <td>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['hongkong']['account'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</td>
       </tr>
       <?php endif; ?>
       <?php if (( $this->_tpl_vars['japanese'] )): ?>
       <tr>
         <td>日本仓库</td>
         <td><?php echo $this->_tpl_vars['japanese']['order_sn']; ?>
</td>
         <td>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['japanese']['account'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</td>
       </tr>
       <?php endif; ?>
     </table>
     <p><b>应付总额:</b><span><?php echo ((is_array($_tmp=$this->_tpl_vars['account'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</span>元</p>
     <p><i>支付方式:</i><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/pay2.png"></p>
     <p class="clearfix" style="margin-bottom:10px;"></p>
     <span id="overbuy"><?php echo $this->_tpl_vars['pay_info_J']; ?>
</span>
   </div>
 </div>
</div><!--container-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>