<?php /* Smarty version 2.6.19, created on 2014-12-19 16:40:23
         compiled from stock-report/detail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'stock-report/detail.tpl', 84, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/stock-report/detail/">
<div class="search">
<span style="float:left;width:115px;line-height:18px;">
  <input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
</span>
<span style="float:left;width:10px;line-height:18px;">
  -
</span>
<span style="float:left;width:115px;line-height:18px;">
  <input type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
</span>
选择仓库
<select name="logic_area">
<?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['param']['logic_area'] == $this->_tpl_vars['key']): ?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['logic_area'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</select>
选择库存状态
<select name="status_id">
<option value="">全部状态</option>
<?php $_from = $this->_tpl_vars['status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['status_id'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<br><br>
产品编码：<input type="text" name="product_sn" size="8" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品批次：<input type="text" name="batch_no" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['batch_no']; ?>
"/>
<input type="submit" name="dosearch" value="查询" onclick="return check()"/>
<input type="reset" name="reset" value="清除">
</div>
</form>

<div class="title">库存管理 -&gt; 明细报表
</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品名称</td>
            <!--<td>产品批次</td>-->
            <td>库存状态</td>
            <td>当前结存</td>
            <td>变更数字</td>
            <td>成本</td>
            <td>单据类型</td>
            <td>单据编号</td>
            <td>变更时间</td>
            <td>操作人</td>
        </tr>
    </thead>
    <tbody>
    <?php if ($this->_tpl_vars['datas']): ?>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['data']['product_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
<font color="#FF0000">(<?php echo $this->_tpl_vars['data']['goods_style']; ?>
)</font></td>
        <!--<td><?php if ($this->_tpl_vars['data']['batch_no']): ?><?php echo $this->_tpl_vars['data']['batch_no']; ?>
<?php else: ?>无批次<?php endif; ?></td>-->
        <td><?php echo $this->_tpl_vars['status'][$this->_tpl_vars['data']['status_id']]; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['stock']; ?>
<?php if ($this->_tpl_vars['data']['error']): ?><font color="red"><?php echo $this->_tpl_vars['data']['error']; ?>
</font><?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['number']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['price']; ?>
</td>
        <td>
          <?php if ($this->_tpl_vars['data']['type'] == 'outstock'): ?>
          <?php echo $this->_tpl_vars['outTypes'][$this->_tpl_vars['data']['bill_type']]; ?>

          <?php elseif ($this->_tpl_vars['data']['type'] == 'instock'): ?>
          <?php echo $this->_tpl_vars['inTypes'][$this->_tpl_vars['data']['bill_type']]; ?>

          <?php elseif ($this->_tpl_vars['data']['type'] == 'outstatus'): ?>
          状态调整出库单
          <?php elseif ($this->_tpl_vars['data']['type'] == 'instatus'): ?>
          状态调整入库单
          <?php elseif ($this->_tpl_vars['data']['type'] == 'outallocation'): ?>
          调拨出库单
          <?php elseif ($this->_tpl_vars['data']['type'] == 'inallocation'): ?>
          调拨入库单
          <?php endif; ?>
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['finish_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    <tr>
      <td>合计</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <!--<td>&nbsp;</td>-->
      <td>&nbsp;</td>
      <td><?php echo $this->_tpl_vars['totalData']['stock']; ?>
</td>
      <td><?php echo $this->_tpl_vars['totalData']['number']; ?>
</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php endif; ?>
    </tbody>
    </table>
    <br>
    <?php if ($this->_tpl_vars['initStockData']): ?>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <!--<td>产品批次</td>-->
            <td>库存状态</td>
            <td>当前结存</td>
            <td>变更数字</td>
            <td>初始时间</td>
            <td>操作人</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['initStockData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
      <!--<td><?php if ($this->_tpl_vars['data']['batch_no']): ?><?php echo $this->_tpl_vars['data']['batch_no']; ?>
<?php else: ?>无批次<?php endif; ?></td>-->
      <td><?php echo $this->_tpl_vars['status'][$this->_tpl_vars['data']['status_id']]; ?>
</td>
      <td><?php echo $this->_tpl_vars['data']['stock_number']; ?>
</td>
      <td><?php echo $this->_tpl_vars['data']['number']; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
      <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    </tbody>
    </table>
</div>
<script type="text/javascript">
function check()
{
    if ($('fromdate').value == '') {
        alert('日期不能为空!');
        return false;
    }
    if ($('product_sn').value == '' && $('batch_no').value == '') {
        alert('产品编码和产品批次必须输入一个!');
        return false;
    }
    
    return true;
}
</script>