<?php /* Smarty version 2.6.19, created on 2014-09-03 10:10:07
         compiled from stock-report/graph.tpl */ ?>
<script language="javascript" type="text/javascript" src="/scripts/jquery.js"></script>
<script language="javascript" type="text/javascript" src="/scripts/flot/excanvas.min.js"></script>
<script language="javascript" type="text/javascript" src="/scripts/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="/scripts/flot/jquery.flot.stack.js"></script>
<link type="text/css" rel="stylesheet" href="/styles/99vk/body_test.css" />
<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<form id="myform" name="myform" mothod="get" action="<?php echo $this -> callViewHelper('url', array());?>">
<br>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr bgcolor="#F0F1F2">
    <td width="100">　产品名称：</td>
    <td width="200"><?php echo $this->_tpl_vars['product']['product_name']; ?>
</td>
    <td width="100">　产品编码：</td>
    <td><?php echo $this->_tpl_vars['product']['product_sn']; ?>
</td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#F0F1F2">
    <td width="100">　选择月份：</td>
    <td width="200">
      <select name="year" id="year" onchange="document.getElementById('myform').submit()">
		 <option value="2015" <?php if ($this->_tpl_vars['param']['year'] == '2015'): ?>selected<?php endif; ?>>2015</option>
		 <option value="2014" <?php if ($this->_tpl_vars['param']['year'] == '2014'): ?>selected<?php endif; ?>>2014</option>
		 <option value="2013" <?php if ($this->_tpl_vars['param']['year'] == '2013'): ?>selected<?php endif; ?>>2013</option>
      </select>
      <select name="month" id="month" onchange="document.getElementById('myform').submit()">
        <option value="01" <?php if ($this->_tpl_vars['param']['month'] == '01'): ?>selected<?php endif; ?>>01</option>
        <option value="02" <?php if ($this->_tpl_vars['param']['month'] == '02'): ?>selected<?php endif; ?>>02</option>
        <option value="03" <?php if ($this->_tpl_vars['param']['month'] == '03'): ?>selected<?php endif; ?>>03</option>
        <option value="04" <?php if ($this->_tpl_vars['param']['month'] == '04'): ?>selected<?php endif; ?>>04</option>
        <option value="05" <?php if ($this->_tpl_vars['param']['month'] == '05'): ?>selected<?php endif; ?>>05</option>
        <option value="06" <?php if ($this->_tpl_vars['param']['month'] == '06'): ?>selected<?php endif; ?>>06</option>
        <option value="07" <?php if ($this->_tpl_vars['param']['month'] == '07'): ?>selected<?php endif; ?>>07</option>
        <option value="08" <?php if ($this->_tpl_vars['param']['month'] == '08'): ?>selected<?php endif; ?>>08</option>
        <option value="09" <?php if ($this->_tpl_vars['param']['month'] == '09'): ?>selected<?php endif; ?>>09</option>
        <option value="10" <?php if ($this->_tpl_vars['param']['month'] == '10'): ?>selected<?php endif; ?>>10</option>
        <option value="11" <?php if ($this->_tpl_vars['param']['month'] == '11'): ?>selected<?php endif; ?>>11</option>
        <option value="12" <?php if ($this->_tpl_vars['param']['month'] == '12'): ?>selected<?php endif; ?>>12</option>
      </select>
    </td>
    <td width="100">　库存类型：</td>
    <td>
      <select name="stock_type" id="stock_type" onchange="document.getElementById('myform').submit()">
        <option value="">全部</option>
        <option value="out_stock" <?php if ($this->_tpl_vars['param']['stock_type'] == 'out_stock'): ?>selected<?php endif; ?>>出库</option>
        <option value="in_stock" <?php if ($this->_tpl_vars['param']['stock_type'] == 'in_stock'): ?>selected<?php endif; ?>>入库</option>
      </select>
      <?php if ($this->_tpl_vars['param']['stock_type'] == 'out_stock'): ?>
        <select name="bill_type" onchange="document.getElementById('myform').submit()">
          <option value="">请选择单据类型</option>
        <?php $_from = $this->_tpl_vars['outTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type'] => $this->_tpl_vars['type_name']):
?>
          <option value="<?php echo $this->_tpl_vars['type']; ?>
" <?php if ($this->_tpl_vars['param']['bill_type'] == $this->_tpl_vars['type']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['type_name']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
        </select>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['param']['stock_type'] == 'in_stock'): ?>
        <select name="bill_type" onchange="document.getElementById('myform').submit()">
          <option value="">请选择单据类型</option>
        <?php $_from = $this->_tpl_vars['inTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type'] => $this->_tpl_vars['type_name']):
?>
          <option value="<?php echo $this->_tpl_vars['type']; ?>
" <?php if ($this->_tpl_vars['param']['bill_type'] == $this->_tpl_vars['type']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['type_name']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
        </select>
      <?php endif; ?>
    </td>
  </tr>
</table>
</form>
<br>
<table width="100%" border="0">
<tr>
<td width="300">&nbsp;</td>
<td id="hint">&nbsp;</td>
</tr>
</table>
<br>
<div style="width:780px;height:400px;" id="placeholder"></div>

<script type="text/javascript">
$(function () {
    var d1 = [];
    var d2 = [];
    <?php if ($this->_tpl_vars['outStockData']): ?>
    <?php $_from = $this->_tpl_vars['outStockData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['data'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['data']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['data']['iteration']++;
?>
        d1.push([<?php echo $this->_foreach['data']['iteration']; ?>
, <?php echo $this->_tpl_vars['data']; ?>
]);
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    
    <?php if ($this->_tpl_vars['inStockData']): ?>
    <?php $_from = $this->_tpl_vars['inStockData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['data'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['data']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['data']['iteration']++;
?>
        d2.push([<?php echo $this->_foreach['data']['iteration']; ?>
, <?php echo $this->_tpl_vars['data']; ?>
]);
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    
    var ticks = [];
    var days = <?php echo $this->_tpl_vars['days']; ?>
;
    for (i = 1; i <= days; i++) {
        ticks.push([i,i]);
    }
    
    $.plot($("#placeholder"), [
        {
            label: <?php if ($this->_tpl_vars['outStockData']): ?>"出库数量"<?php else: ?>"入库数量"<?php endif; ?>,
            color: <?php if ($this->_tpl_vars['outStockData']): ?>"#edc240"<?php else: ?>"#afd8f8"<?php endif; ?>,
            data: <?php if ($this->_tpl_vars['outStockData']): ?>d1<?php else: ?>d2<?php endif; ?>,
            lines: { show: true },
            points: { show: true }
        }
        <?php if ($this->_tpl_vars['outStockData'] && $this->_tpl_vars['inStockData']): ?>
        ,
        {
            color: "#afd8f8",
            label: "入库数量",
            data: d2,
            lines: { show: true },
            points: { show: true }
        }
        <?php endif; ?>
        ],
        {
        xaxis: {
                ticks: ticks
               },
        grid: { hoverable: true, clickable: true }
        }
    );
    
    $("#placeholder").bind("plotclick", function (event, pos, item) {
        if (item) {
            document.getElementById('hint').innerHTML = '日期：<?php echo $this->_tpl_vars['param']['year']; ?>
-<?php echo $this->_tpl_vars['param']['month']; ?>
-' + item.datapoint[0] + '&nbsp;&nbsp;' + item.series.label + '：' + item.datapoint[1];
        }
    });
});
</script>