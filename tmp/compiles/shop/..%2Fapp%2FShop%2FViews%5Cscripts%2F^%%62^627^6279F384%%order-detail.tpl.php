<?php /* Smarty version 2.6.19, created on 2015-01-16 17:55:03
         compiled from member/order-detail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'member/order-detail.tpl', 74, false),)), $this); ?>
<div class="memberCenter">
		<div class="topNav"><a href="/">首页</a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href="/member">会员中心</a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href="javascript:void(0);">订单详情</a></div>
		<div class="mcContent">
			<div class="detailTitle">
				<ul class="dd_msg">
					<li class="dd_msg01">订单编号：<?php echo $this->_tpl_vars['order']['order_sn']; ?>
</li>
					<li class="dd_msg02">订单状态：<?php echo $this->_tpl_vars['order']['deal_status']; ?>
</li>
					<li class="dd_msg03">付款状态：<?php echo $this->_tpl_vars['order']['status_pay_label']; ?>
</li>
					<br class="clearfix"/>
				</ul>
				
			</div>
			<div class="detailTitle">
				<ul class="dd_msg">
					<li class="dd_msg01">订单信息</li>
					<br class="clearfix"/>
				</ul>
				<ul class="detailMsg">
					<li>
						<p class="first">收货人信息</p>
						<p>收货人：<?php echo $this->_tpl_vars['order']['addr_province']; ?>
<?php echo $this->_tpl_vars['order']['addr_city']; ?>
<?php echo $this->_tpl_vars['order']['addr_area']; ?>
<?php echo $this->_tpl_vars['order']['addr_consignee']; ?>
</p>
						<p>地　址：<?php echo $this->_tpl_vars['order']['addr_address']; ?>
</p>
						<p>邮　编：<?php echo $this->_tpl_vars['order']['zip']; ?>
</p>
						<p>手　机：<?php echo $this->_tpl_vars['order']['addr_mobile']; ?>
</p>
						<p>电　话：<?php echo $this->_tpl_vars['order']['addr_tel']; ?>
</p>
					</li>
					<li class="sendMsg">
						<p class="first">配送</p>
						<p>配送方式：顺丰速递</p>
						<p>运&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;费：<?php echo $this->_tpl_vars['order']['price_logistic']; ?>
 元</p>
						<?php if ($this->_tpl_vars['order']['logistic_no'] != null): ?>
						<p>快递单号：<?php echo $this->_tpl_vars['order']['logistic_no']; ?>
</p>
						<?php endif; ?>
					</li>
					<li class="last">
						<p class="first">支付方式</p>
						<p>支付方式：在线支付</p>
						<p>支付平台：东方支付</p>
					</li>
				</ul>
			</div>
			<div class="detailTitle last">
				<ul class="dd_msg">
					<li class="dd_msg01">商品清单</li>
					<br class="clearfix"/>
				</ul>
				<div class="table">
					<table>
						<tr class="first">
							<td class="first" width="835" height="36">商品</td>
							<td width="112">单价</td>
							<td width="112">数量</td>
							<td class="last" width="128">小计</td>
						</tr>
						<?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
						<tr>
							<td class="first" height="76">
								
								<p><a href='/goods-<?php echo $this->_tpl_vars['item']['goods_id']; ?>
.html'><?php echo $this->_tpl_vars['item']['goods_name']; ?>
</a></p>
								<br class="clearfix"/>
							</td>
							<td><?php echo $this->_tpl_vars['item']['sale_price']; ?>
</td>
							<td><?php echo $this->_tpl_vars['item']['number']; ?>
</td>
							<td class="last">￥<?php echo $this->_tpl_vars['item']['amount']; ?>
</td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
						  
					</table>
				</div>
			</div>
			<div class="totalMoney">
				<div>
					<p class="total_p1">商品总额：</p>
					<p class="total_p2">￥<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['price_goods'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
 <span>元</span></p>
					<br class="clearfix"/>
				</div>
				<div class="borderBottom">
					<p class="total_p1">运费：</p>
					<p class="total_p2">￥<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['price_logistic'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
 <span>元</span></p>
					<br class="clearfix"/>
				</div>
				<div class="borderBottom">
					<p class="total_p1">行邮税：</p>
					<p class="total_p2">￥<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['tax'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
 <span>元</span></p>
					<br class="clearfix"/>
				</div>
				<div>
					<p class="total_p1">订单总额：</p>
					<p class="total_p2">￥<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['price_pay'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
 <span>元</span></p>
					<br class="clearfix"/>
				</div>
				<?php if (( $this->_tpl_vars['order']['price_pay'] - $this->_tpl_vars['order']['price_payed'] ) > 0 && $this->_tpl_vars['order']['status'] == 0): ?>
				<div class="wantPayBtn">
					<?php echo $this->_tpl_vars['paymentButton']; ?>
	
				</div>
				<?php endif; ?>
			</div>
			<br class="clearfix"/>
		</div>
	</div>