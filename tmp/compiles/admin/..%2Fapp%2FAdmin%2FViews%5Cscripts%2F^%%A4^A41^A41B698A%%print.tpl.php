<?php /* Smarty version 2.6.19, created on 2014-09-02 11:13:28
         compiled from logic-area-out-stock/print.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'logic-area-out-stock/print.tpl', 53, false),array('modifier', 'default', 'logic-area-out-stock/print.tpl', 90, false),)), $this); ?>
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
<div style="position:absolute;right:0px;top:70px;z-index:10;width:7cm;overflow:hidden"><?php echo $this->_tpl_vars['data']['barcode']; ?>
</div>
<div style="position:relative;left:0px;top:0px;"><img src="/haitaoadmin/images/out.jpg"> </div>
<img src="/haitaoadmin/images/print_title.jpg">
<h2><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['datas']['0']['bill']['bill_type']]; ?>
　　<img src="/admin/transport/barcode/no/<?php echo $this->_tpl_vars['datas']['0']['bill']['bill_no']; ?>
"></h2>
<br><br>
<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">

<table cellpadding="10" cellspacing="10" border="0" width="100%">
  <tr>
    <td width="50%">
      <h4>单据编号：<?php echo $this->_tpl_vars['datas']['0']['bill']['bill_no']; ?>
</h4>
    </td>
    <td>
      收货日期：<?php if ($this->_tpl_vars['datas']['0']['bill']['finish_time']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['datas']['0']['bill']['finish_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
<?php endif; ?>
    </td>
  </tr>
  <tr>
    <td>
      供应商：<?php echo $this->_tpl_vars['datas']['0']['bill']['supplier_name']; ?>

    </td>
    <td>
      打印时间：<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>

    </td>
  </tr>
  <tr>
    <td>
      发货区域：<?php echo $this->_tpl_vars['area_name']; ?>

    </td>
    <td>
      物流运单号：
    </td>
  </tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table_print" align="center">
<thead>
<tr>
    <td>商品编码</td>
    <td>商品名称</td>
    <td>状态</td>
	<td>货架位</td>
    <td>发货数量</td>
    </tr>
</thead>
<tbody>
<?php $_from = $this->_tpl_vars['datas']['0']['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['d']):
?>
<tr>
<td><?php echo $this->_tpl_vars['d']['product_sn']; ?>
</td>
<td><?php echo $this->_tpl_vars['d']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['d']['goods_style']; ?>
</font>) </td>
<td><?php echo $this->_tpl_vars['status'][$this->_tpl_vars['d']['status_id']]; ?>
</td>
<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['d']['position_no'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</td>
<td><?php echo $this->_tpl_vars['d']['number']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr>
<td colspan="8" align="center">
<strong>商品数量合计：</strong><?php echo $this->_tpl_vars['datas']['0']['total_number']; ?>

</td>
</tr>
</tbody>
</table>
<br>
<div>
备注：<?php echo $this->_tpl_vars['datas']['0']['bill']['remark']; ?>

</div>
<br><br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
  <td width="50%">收货人：</td>
  <td>仓库经理：</td>
</tr>
</table>
</div>

</body>
</html>