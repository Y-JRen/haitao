<?php /* Smarty version 2.6.19, created on 2014-12-18 17:58:47
         compiled from member/order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'member/order.tpl', 43, false),)), $this); ?>
<div class="memberCenter">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<div class="mcContentRight">
			<div class="rightTitle">
				<div class="leftNav">
				
					<p class="leftNav01">事务提醒：</p>
					<p class="leftNav02"><a href="/member/order">全部（<span><?php echo $this->_tpl_vars['allorder']; ?>
</span>）</a></p>
					<p class="leftNav02"><a href="/member/order/ordertype/4">待支付订单（<span><?php echo $this->_tpl_vars['nopay']; ?>
</span>）</a></p>
					<p class="leftNav02"><a href="/member/order/ordertype/6">等待收货（<span><?php echo $this->_tpl_vars['send']; ?>
</span>）</a></p>
					<p class="leftNav02"><a href="/member/order/ordertype/7">交易完成（<span><?php echo $this->_tpl_vars['okorder']; ?>
</span>）</a></p>
					<br class="clearfix"/>
					
				</div>
				<div class="rightNav">
				    <form action="/member/order" method="get" onsubmit="return FormSubmit();">
					
					<div class="rightNav02"><input name="sn" id="ordersn" type="text" value="订单编号"/></div>
					<div class="rightNav03"><input type="submit" style="background:url('/public/images/cx_btn.png');width:49px;height:24px;" value=" "/></div>
					<br class="clearfix"/>
					</form>
				</div>
				<br class="clearfix"/>
			</div>
			<div class="orderList">
				<table>
					<tr class="first">
						<!-- <td class="allBtn" width="73">全选</td> -->
						<td width="152">订单编号</td>
						<td width="233">商品</td>
						<td width="108">金额</td>
						<td width="108">收货人</td>
						<td width="109">时间</td>
						<td width="108">状态</td>
						<td width="123">操作</td>
					</tr>
					<?php $_from = $this->_tpl_vars['orderInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order']):
?>
					<tr>
						<!-- <td class="checkBox"><input type="checkbox"/></td> -->
						<td><?php echo $this->_tpl_vars['order']['batch_sn']; ?>
</td>
						<td class="picImg">
						    <?php $_from = $this->_tpl_vars['order']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
							<div><a href='/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html' title='<?php echo $this->_tpl_vars['goods']['product_name']; ?>
'><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" width=46; height=46;/></a></div>
							<?php endforeach; endif; unset($_from); ?>
							
							<br class="clearfix"/>
						</td>
						<td class="price">￥<?php echo $this->_tpl_vars['order']['price_order']; ?>
</td>
						<td><?php echo $this->_tpl_vars['order']['addr_consignee']; ?>
</td>
						<td class="time">
							<p><?php echo $this->_tpl_vars['order']['add_time']; ?>
</p>
						</td>
						<td><?php echo $this->_tpl_vars['order']['deal_status']; ?>
</td>
						<td class="caozuo">
							<div><a href="/member/order-detail?batch_sn=<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
">查看</a><?php if ($this->_tpl_vars['order']['price_payed'] == 0 && $this->_tpl_vars['order']['status'] == 0 && $this->_tpl_vars['order']['status_logistic'] == 0): ?>&nbsp;&nbsp;<a href='/member/cancel-order/batch_sn/<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
' onclick='return canelOrder();'>取消订单</a><?php endif; ?></div>
							<!--  
							<div class="payBtn"><a href="#"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/payBtn.png"/></a></div>
							-->
						</td>
					</tr>
					<?php endforeach; endif; unset($_from); ?>
				</table>
			</div>
			<div class="orderBottom">
				
				<div class="page">
					<?php echo $this->_tpl_vars['pageNav']; ?>

				</div>
				<br class="clearfix"/>
			</div>
		</div>
		<br class="clearfix"/>
	</div>
</div>
<script>
$(function(){	
	 $('#ordersn').focus(function(){
			if($(this).val() == '订单编号')
				$(this).val('');
		});
		
		$('#ordersn').blur(function(){
			if($(this).val() == '')
				$(this).val('订单编号');
		});
})

function FormSubmit(){
	if($("#ordersn").val() == '订单编号'){
		alert('订单号不能为空！');
		return false;
	}else{
		return true;
	}
}

function canelOrder(){
	if(confirm('确定要取消订单吗~？')){
		return true;
	}else{
		return false;
	}
}
</script>