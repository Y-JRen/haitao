<?php /* Smarty version 2.6.19, created on 2014-08-21 15:21:30
         compiled from logic-area-out-stock/pickorders.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'logic-area-out-stock/pickorders.tpl', 99, false),array('modifier', 'date_format', 'logic-area-out-stock/pickorders.tpl', 108, false),)), $this); ?>
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
    text-align: left;
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

<div style="position:relative;text-align:center;padding:5px;">
<div style="position:relative;left:0px;top:0px;"><img src="/haitaoadmin/images/pick.jpg"> </div>
<img src="/haitaoadmin/images/print_title.jpg">
<h2>产品销售拣货单</h2>
<br><br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品规格</td>
    <!--<td>产品批次</td>-->
    <td>数量</td>
    <td>货位</td>
    <!--<td>相关单号</td>-->
    </tr>
</thead>
<tbody>
<?php $_from = $this->_tpl_vars['pickGoods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pickGoods1']):
?>
<?php $_from = $this->_tpl_vars['pickGoods1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['d']):
?>
<tr>
<td><?php echo $this->_tpl_vars['d']['product_sn']; ?>
</td>
<td><?php echo $this->_tpl_vars['d']['goods_name']; ?>
</td>
<td><?php echo $this->_tpl_vars['d']['goods_style']; ?>
</td>
<!--<td><?php if ($this->_tpl_vars['key']): ?><?php echo $this->_tpl_vars['key']; ?>
<?php else: ?>无批次<?php endif; ?></td>-->
<td><?php echo $this->_tpl_vars['d']['number']; ?>
</td>
<td><?php if ($this->_tpl_vars['d']['local_sn']): ?><?php echo $this->_tpl_vars['d']['local_sn']; ?>
<?php else: ?>&nbsp;<?php endif; ?></td>
<!--<td><?php echo $this->_tpl_vars['d']['order']; ?>
</td>-->
</tr>
<?php endforeach; endif; unset($_from); ?>
<?php endforeach; endif; unset($_from); ?>
<tr>
<td colspan="3" style="text-align:right;">
包裹总件数
</td>
<td><?php echo $this->_tpl_vars['total_number']; ?>
</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
<!--
<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
<td>订单编号</td>
<td>送货方式</td>
<td>物流公司</td>
<td>物流单号</td>
<td>发票抬头</td>
<td>发票内容</td>
<td>开票金额</td>
</tr>
</thead>
<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<tr>
  <td><?php echo $this->_tpl_vars['data']['order']['order_sn']; ?>
</td>
  <td><?php if ($this->_tpl_vars['data']['order']['pay_type'] == 'cod'): ?>货到付款<?php else: ?>款到发货<?php endif; ?></td>
  <td><?php echo $this->_tpl_vars['data']['order']['logistic_name']; ?>
</td>
  <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['bill']['logistic_no'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</td>
  <td><?php if ($this->_tpl_vars['data']['order']['invoice_type']): ?><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['order']['invoice'])) ? $this->_run_mod_handler('default', true, $_tmp, '个人') : smarty_modifier_default($_tmp, '个人')); ?>
<?php else: ?>&nbsp;<?php endif; ?></td>
  <td><?php if ($this->_tpl_vars['data']['order']['invoice_type']): ?><?php echo $this->_tpl_vars['data']['order']['invoice_content']; ?>
<?php else: ?>&nbsp;<?php endif; ?></td>
  <td><?php if ($this->_tpl_vars['data']['order']['invoice_type']): ?><?php echo $this->_tpl_vars['data']['order']['price_order']; ?>
<?php else: ?>&nbsp;<?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
-->
<div style="text-align:left;padding:30px 0;"><br>
拣货人：________________　　　　　　QC：________________　　　　　　打印时间：<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>

</div>

<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
<td>订单编号</td>
<td>送货方式</td>
<td>物流公司</td>
<td>物流单号</td>
<td>产品信息</td>
</tr>
</thead>
<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<tr>
  <td><?php echo $this->_tpl_vars['data']['order']['order_sn']; ?>
</td>
  <td><?php if ($this->_tpl_vars['data']['order']['pay_type'] == 'cod'): ?>货到付款<?php else: ?>款到发货<?php endif; ?></td>
  <td><?php echo $this->_tpl_vars['data']['order']['logistic_name']; ?>
</td>
  <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['bill']['logistic_no'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</td>
  <td>
  <?php $_from = $this->_tpl_vars['data']['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    【<?php echo $this->_tpl_vars['v']['product_sn']; ?>
】<?php echo $this->_tpl_vars['v']['product_name']; ?>
(<?php echo $this->_tpl_vars['v']['goods_style']; ?>
) 【<?php echo $this->_tpl_vars['v']['number']; ?>
】 <br>
  <?php endforeach; endif; unset($_from); ?>
  </td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>

</div>
</body>
</html>