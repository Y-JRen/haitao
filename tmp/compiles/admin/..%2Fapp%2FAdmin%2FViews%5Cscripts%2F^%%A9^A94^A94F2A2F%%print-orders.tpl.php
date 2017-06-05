<?php /* Smarty version 2.6.19, created on 2014-08-21 14:23:52
         compiled from logic-area-out-stock/print-orders.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count_commas', 'logic-area-out-stock/print-orders.tpl', 49, false),array('modifier', 'truncate', 'logic-area-out-stock/print-orders.tpl', 68, false),array('modifier', 'default', 'logic-area-out-stock/print-orders.tpl', 96, false),array('modifier', 'string_format', 'logic-area-out-stock/print-orders.tpl', 96, false),array('modifier', 'count', 'logic-area-out-stock/print-orders.tpl', 233, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body {
    margin: 0;
    color: #000;
}
table, td, div {
    font: normal 12px  Verdana, "Times New Roman", Times, serif;
}
div {
    margin: 0 auto;
    width: 700px;
}
.table_print {
    clear: both;
    border-right: 1px solid #333;
    border-bottom: 1px solid #333;
    text-700px: left;
    width: 700px;
}
.table_print td {
    padding: 2px;
    color: #333;
    background: #fff;
    border-top: 1px solid #333;
    border-left: 1px solid #333;
    line-height: 150%;
}
.item {
    text-align:right;
    font-weight:bold;
}
</style>
</head>
<body>
<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['data'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['data']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['data']['iteration']++;
?>
<div style="position:relative;text-align:center;padding:5px;">
<img src="/haitaoadmin/images/print_title.jpg">
<h2>
<?php if ($this->_tpl_vars['data']['order']['shop_id'] == 62 || $this->_tpl_vars['data']['order']['shop_id'] == 11): ?>发　货　单<?php else: ?>销　售　单<?php endif; ?>
<?php if ($this->_tpl_vars['data']['bill']['bill_no_array']): ?>
<?php $_from = $this->_tpl_vars['data']['bill']['bill_no_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<br><br><img src="/admin/transport/barcode/no/<?php echo $this->_tpl_vars['item']; ?>
">
<?php endforeach; endif; unset($_from); ?>
<?php else: ?>
<img src="/admin/transport/barcode/no/<?php if (((is_array($_tmp=$this->_tpl_vars['data']['bill']['bill_no'])) ? $this->_run_mod_handler('count_commas', true, $_tmp, ',') : smarty_modifier_count_commas($_tmp, ',')) == 0): ?><?php echo $this->_tpl_vars['data']['bill']['bill_no']; ?>
<?php else: ?>OID<?php echo $this->_tpl_vars['data']['bill']['outstock_id']; ?>
<?php endif; ?>">
<?php endif; ?>
</h2>
<br><br>
<br><br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
  <tr>
    <td width="50%" style="text-align:left;">
      送货方式：<?php if ($this->_tpl_vars['data']['order']['pay_type'] == 'cod'): ?><b>货到付款</b>   <?php if ($this->_tpl_vars['data']['order']['shop_id'] != 11): ?> 应付货款：<b><?php echo $this->_tpl_vars['data']['bill']['transport']['amount']+$this->_tpl_vars['data']['bill']['transport']['change_amount']; ?>
</b>元 <?php endif; ?>  <?php else: ?><b>款到发货</b> <?php if ($this->_tpl_vars['data']['order']['shop_id'] != 62 && $this->_tpl_vars['data']['order']['shop_id'] != 11): ?>已付货款：<b><?php echo $this->_tpl_vars['data']['order']['price_order']; ?>
</b>元<?php endif; ?><?php endif; ?>
    </td>
    <td style="text-align:right;">
      订购渠道：<?php if (! $this->_tpl_vars['data']['order']['shop_id']): ?>官网<?php else: ?><?php echo $this->_tpl_vars['shopInfo'][$this->_tpl_vars['data']['order']['shop_id']]; ?>
<?php endif; ?>
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流公司：<?php echo $this->_tpl_vars['data']['order']['logistic_name']; ?>

    </td>
    <td style="text-align:right;">
      订单编号：<b><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['bill']['bill_no'])) ? $this->_run_mod_handler('truncate', true, $_tmp, '15', "") : smarty_modifier_truncate($_tmp, '15', "")); ?>
</b>
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流单号：<?php echo $this->_tpl_vars['data']['bill']['logistic_no']; ?>

    </td>
    <?php if ($this->_tpl_vars['data']['order']['external_order_sn']): ?>
    <td style="text-align:right;">
      渠道单号：<?php echo $this->_tpl_vars['data']['order']['external_order_sn']; ?>

    </td>
    <?php endif; ?>
  </tr>
</table>
<br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
  <tr>
    <td style="text-align:left;">
      收货人姓名：<?php echo $this->_tpl_vars['data']['order']['addr_consignee']; ?>
　　　　　　电话：<?php echo $this->_tpl_vars['data']['order']['addr_tel']; ?>
　　　　　　手机：<?php echo $this->_tpl_vars['data']['order']['addr_mobile']; ?>

    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      地址：<?php echo $this->_tpl_vars['data']['order']['addr_province']; ?>
 <?php echo $this->_tpl_vars['data']['order']['addr_city']; ?>
 <?php echo $this->_tpl_vars['data']['order']['addr_area']; ?>
 <?php echo $this->_tpl_vars['data']['order']['addr_address']; ?>

    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      是否开票：<?php if ($this->_tpl_vars['data']['order']['invoice_type']): ?>是　　　　　发票抬头：<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['order']['invoice'])) ? $this->_run_mod_handler('default', true, $_tmp, '个人') : smarty_modifier_default($_tmp, '个人')); ?>
　　　　　发票内容：<?php echo $this->_tpl_vars['data']['order']['invoice_content']; ?>
　　　　　开票金额：<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order']['price_pay']-$this->_tpl_vars['data']['order']['point_payed']-$this->_tpl_vars['data']['order']['account_payed']-$this->_tpl_vars['data']['order']['gift_card_payed']-$this->_tpl_vars['data']['order']['price_logistic'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>
<?php else: ?>否<?php endif; ?>
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流备注：<?php echo $this->_tpl_vars['data']['order']['note_logistic']; ?>

    </td>
  </tr>
</table>
<br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr><td style="text-align:left;">本次发货情况：</td></tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
    <td>商品编码</td>
    <td>商品名称</td>
    <td>商品规格</td>
    <?php if ($this->_tpl_vars['data']['order']['shop_id'] != 11 && $this->_tpl_vars['data']['order']['shop_id'] != 62): ?>
    <td>单价</td>
    <?php endif; ?>
    <td>数量</td>
    <?php if ($this->_tpl_vars['data']['order']['shop_id'] != 62 && $this->_tpl_vars['data']['order']['shop_id'] != 65 && $this->_tpl_vars['data']['order']['shop_id'] != 11): ?><td >小计</td><?php endif; ?>
    </tr>
</thead>
<tbody>
<?php $_from = $this->_tpl_vars['data']['order']['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['d']):
?>
<?php if ($this->_tpl_vars['d']['product_id'] > 0): ?>
<tr>
<td><?php echo $this->_tpl_vars['d']['product_sn']; ?>
</td>
<td><?php echo $this->_tpl_vars['d']['goods_name']; ?>
</td>
<td><?php echo $this->_tpl_vars['d']['goods_style']; ?>
&nbsp;</td>
 <?php if ($this->_tpl_vars['data']['order']['shop_id'] != 11 && $this->_tpl_vars['data']['order']['shop_id'] != 62): ?>
<td><?php echo $this->_tpl_vars['d']['sale_price']; ?>
</td>
 <?php endif; ?>

<td><?php echo $this->_tpl_vars['d']['number']; ?>
</td>
<?php if ($this->_tpl_vars['data']['order']['shop_id'] != 62 && $this->_tpl_vars['data']['order']['shop_id'] != 65 && $this->_tpl_vars['data']['order']['shop_id'] != 11): ?>
<td>
  <?php if ($this->_tpl_vars['d']['group']): ?>
    <?php echo ((is_array($_tmp=$this->_tpl_vars['d']['sum_price']-$this->_tpl_vars['d']['discount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>
(套组)
  <?php else: ?>
    <?php echo ((is_array($_tmp=$this->_tpl_vars['d']['sale_price']*$this->_tpl_vars['d']['number'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>

  <?php endif; ?>
</td>
<?php endif; ?>
</tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['data']['order']['shop_id'] != 62 && $this->_tpl_vars['data']['order']['shop_id'] != 65 && $this->_tpl_vars['data']['order']['shop_id'] != 11): ?>
<tr>
<td colspan="5" style="text-align:right;">
商品金额总计：
</td>
<td>
<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order']['price_goods'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>

</td>
</tr>
<?php if ($this->_tpl_vars['data']['order']['price_adjust'] < 0 || $this->_tpl_vars['data']['order']['discount'] < 0): ?>
<tr>
<td colspan="5" style="text-align:right;">
折扣：
</td>
<td>
<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order']['price_adjust']+$this->_tpl_vars['data']['order']['discount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>

</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']['order']['tax'] > 0): ?>
<tr>
<td colspan="5" style="text-align:right;">
商品行邮税总计：
</td>
<td>
<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order']['tax'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>

</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']['order']['price_logistic'] > 0): ?>
<tr>
<td colspan="5" style="text-align:right;">
运费：
</td>
<td>
<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order']['price_logistic'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>

</td>
</tr>
<?php endif; ?>
<tr>
<td colspan="5" style="text-align:right;">
订单金额总计：
</td>
<td>
<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order']['price_order'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>

</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']['order']['gift_card_margin']): ?>
<tr>
<td colspan="5" style="text-align:right;">
礼品卡预抵扣：
</td>
<td>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order']['gift_card_margin'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>

</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']['order']['shop_id'] != 11 && $this->_tpl_vars['data']['order']['shop_id'] != 62): ?>
<tr>
<td colspan="5" style="text-align:right;">
<?php if ($this->_tpl_vars['data']['order']['gift_card_payed'] > 0): ?>
礼品卡抵扣：-<?php echo $this->_tpl_vars['data']['order']['gift_card_payed']; ?>
&nbsp;
<?php endif; ?>
<?php if ($this->_tpl_vars['data']['order']['account_payed'] > 0): ?>
账户余额抵扣：-<?php echo $this->_tpl_vars['data']['order']['account_payed']; ?>
&nbsp;
<?php endif; ?>
<?php if ($this->_tpl_vars['data']['order']['point_payed'] > 0): ?>
积分抵扣：-<?php echo $this->_tpl_vars['data']['order']['point_payed']; ?>
&nbsp;
<?php endif; ?>
&nbsp;&nbsp;&nbsp;&nbsp;应付金额：
</td>
<td>
<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order']['price_pay']-$this->_tpl_vars['data']['order']['gift_card_payed']-$this->_tpl_vars['data']['order']['account_payed']-$this->_tpl_vars['data']['order']['point_payed'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>

</td>
</tr>
<?php endif; ?>

</tbody>
</table>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr><td style="font-style:italic">感谢您在国药集团1健康商城购物，如需退换货请填写背面的退换货单并拨打客服热线400-603-3883。我们期待您的再次光临</td></tr>
</table>
<p>&nbsp;</p>  <p>&nbsp;</p>


</div>
<?php if (count($this->_tpl_vars['datas']) != $this->_foreach['data']['iteration']): ?>
<div style="PAGE-BREAK-AFTER:always"></div>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</body>
</html>