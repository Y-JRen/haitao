<?php /* Smarty version 2.6.19, created on 2014-09-01 14:38:25
         compiled from order/public-view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'order/public-view.tpl', 144, false),)), $this); ?>

<style type="text/css">

.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<table>
<tr bgcolor="#F0F1F2">
  <th width="150">单据编号：</th>
  <td><?php echo $this->_tpl_vars['order']['batch_sn']; ?>
</td>
</tr>
<tr><th>下单日期：</th>
<td><?php echo $this->_tpl_vars['order']['add_time']; ?>
</td>
</tr>
<tr><th>订单状态：</th>
<td>
<?php if ($this->_tpl_vars['order']['status_logistic'] == 0): ?>
未确认订单
<?php elseif ($this->_tpl_vars['order']['status_logistic'] == 1): ?>
待收款订单
<?php elseif ($this->_tpl_vars['order']['status_logistic'] == 2): ?>
待发货订单
<?php elseif ($this->_tpl_vars['order']['status_logistic'] == 3): ?>
已发货订单
<?php elseif ($this->_tpl_vars['order']['status_logistic'] == 4): ?>
客户已签收订单
<?php elseif ($this->_tpl_vars['order']['status_logistic'] == 5): ?>
拒收
<?php endif; ?></td>
</tr>
<tr bgcolor="#F0F1F2"><th>用户名称：</th>
<td><?php echo $this->_tpl_vars['order']['user_name']; ?>
</td></tr>
<tr >
  <th>是否电话订单：</th>
  <td><?php if ($this->_tpl_vars['order']['is_tel']): ?>是<?php else: ?>否<?php endif; ?></td>
</tr>
<tr bgcolor="#F0F1F2">
  <th>是否接受回访：</th>
  <td><?php if ($this->_tpl_vars['order']['is_visit']): ?>是<?php else: ?>否<?php endif; ?></td>
</tr>
<tr>
  <th>是否满意不退货：</th>
  <td><?php if ($this->_tpl_vars['order']['is_fav'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
</tr>
</table>
<br>

<table class="mytable" >
<tr  bgcolor="#F0F1F2"><th width="150">收货人：</th>
<td><?php echo $this->_tpl_vars['order']['addr_consignee']; ?>
</td>
</tr>
<tr><th>联系电话：</th>
<td colspan="2"><?php echo $this->_tpl_vars['order']['addr_tel']; ?>
</td></tr>
<tr bgcolor="#F0F1F2"><th>手机：</th>
<td colspan="2"><?php echo $this->_tpl_vars['order']['addr_mobile']; ?>
</td></tr>

<tr bgcolor="#F0F1F2">
  <th>地区：</th>
  <td colspan="2"><?php echo $this->_tpl_vars['order']['addr_province']; ?>
 <?php echo $this->_tpl_vars['order']['addr_city']; ?>
 <?php echo $this->_tpl_vars['order']['addr_area']; ?>
</td>
</tr>
<tr bgcolor="#F0F1F2"><th>收货地址：</th>
<td colspan="2"><?php echo $this->_tpl_vars['order']['addr_address']; ?>
</td></tr>
<tr><th>邮政编码：</th>
<td colspan="2"><?php echo $this->_tpl_vars['order']['addr_zip']; ?>
</td></tr>
</table>


<br>
<table>
<tr bgcolor="#F0F1F2"><th width="150">付款方式：</th>
<td><?php echo $this->_tpl_vars['order']['pay_name']; ?>
</td>
</tr>
</table>
<br>
<table>
<tr bgcolor="#F0F1F2"><th width="150">配送方式：</th>
<td><?php echo $this->_tpl_vars['order']['logistic_name']; ?>
</td>
</tr>
</table>
<br>
<table class="mytable">
  <tr>
    <th width="150">商品名称</th>
    <th>商品编号</th>
    <th>销售价</th>
    <th>数量</th>
    <th>已退数量</th>
    <th>总金额</th>
  </tr>
  <?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['item']['goods_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['product_sn']; ?>
</td>
    <td>￥<?php echo $this->_tpl_vars['item']['sale_price']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['number']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['return_number']; ?>
</td>
    <td>￥<?php echo $this->_tpl_vars['item']['amount']; ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['item']['child']): ?>
  <?php $_from = $this->_tpl_vars['item']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
  <tr>
    <td style="padding-left:40px"><?php echo $this->_tpl_vars['a']['goods_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['a']['product_sn']; ?>
</td>
    <td>￥<?php echo $this->_tpl_vars['a']['sale_price']; ?>
</td>
    <td><?php echo $this->_tpl_vars['a']['number']; ?>
</td>
    <td><?php echo $this->_tpl_vars['a']['return_number']; ?>
</td>
    <td>￥<?php echo $this->_tpl_vars['a']['amount']; ?>
</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</table>
<br>
<table >
	<tr>
		<th width="150">商品总金额：</th>
		<td>￥<?php echo $this->_tpl_vars['order']['price_goods']; ?>
</td>
	</tr>
	<tr>
		<th width="150">商品总行邮税：</th>
		<td>￥<?php echo $this->_tpl_vars['order']['tax']; ?>
</td>
	</tr>
	<tr bgcolor="#F0F1F2">
		<th>运输费：</th>
		<td>￥<?php echo $this->_tpl_vars['order']['price_logistic']; ?>
</td>
	</tr>
	<tr>
		<th>订单总金额：</th>
		<td>￥<?php echo $this->_tpl_vars['order']['price_order']; ?>
</td>
	</tr>
	<tr bgcolor="#F0F1F2">
		<th>调整金额：</th>
		<td>￥<?php echo $this->_tpl_vars['order']['price_adjust']; ?>
</td>
	<tr bgcolor="#F0F1F2">
		<th>已支付金额：</th>
		<td>￥<?php echo $this->_tpl_vars['order']['price_payed']+$this->_tpl_vars['order']['price_from_return']; ?>
</td>
	</tr>
<?php if ($this->_tpl_vars['blance'] < 0): ?>
	<tr>
		<th>需退款金额：</th>
		<td>￥<?php echo ((is_array($_tmp=$this->_tpl_vars['blance'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", "") : smarty_modifier_replace($_tmp, "-", "")); ?>
</td>
	</tr>
<?php elseif ($this->_tpl_vars['blance'] > 0): ?>
	<tr>
		<th>需支付金额：</th>
		<td>￥<?php echo $this->_tpl_vars['blance']; ?>
</td>
	</tr>
<?php endif; ?>

</table>
<?php if ($this->_tpl_vars['noteStaff']): ?>
<br>
<table width=100%>
<tr>
<th width="150">客服</th>
<th width="550">客服备注内容</th>
<th>客服备注日期</th>
</tr>
<?php $_from = $this->_tpl_vars['noteStaff']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<tr>
<td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
<td>
<?php echo $this->_tpl_vars['data']['content']; ?>

</td>
<td><?php echo $this->_tpl_vars['data']['date']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br>
<?php endif; ?>
<table>
<tr>
<th width="150">物流打印备注：</th>
<td><?php echo $this->_tpl_vars['order']['note_print']; ?>
</td>
</tr>
<tr>
<th>物流部门备注：</th>
<td><?php echo $this->_tpl_vars['order']['note_logistic']; ?>
</td>
</tr>
<tr><th>开票单位名称：</th><td><?php echo $this->_tpl_vars['order']['invoice']; ?>
</td></tr>
</table>
<br>
<table>
<tr>
<th width="150">订单留言：</th>
<td><?php echo $this->_tpl_vars['order']['note']; ?>
</td>
</tr>
</table>
<br />
<?php if ($this->_tpl_vars['logs']): ?>
<br>
<table width=100%>
<tr>
<th width="150">操作者</th>
<th width="200">操作时间</th>
<th>操作信息</th>
</tr>
<?php $_from = $this->_tpl_vars['logs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<tr>
<td><?php echo $this->_tpl_vars['item']['admin_name']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['add_time']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['title']; ?>
 <?php if ($this->_tpl_vars['item']['note']): ?>[<?php echo $this->_tpl_vars['item']['note']; ?>
]<?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>