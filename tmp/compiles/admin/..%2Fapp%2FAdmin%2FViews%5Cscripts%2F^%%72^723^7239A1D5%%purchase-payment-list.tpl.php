<?php /* Smarty version 2.6.19, created on 2014-12-22 16:51:52
         compiled from finance/purchase-payment-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'finance/purchase-payment-list.tpl', 75, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
<span style="float:left;line-height:18px;">入库开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">入库结束日期：<input type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/></span>
&nbsp;供货商：<select name="supplier_id" msg="请选择供货商" class="required">
                <option value="">请选择...</option>
                <?php $_from = $this->_tpl_vars['supplier']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s']):
?>
		        <?php if ($this->_tpl_vars['s']['status'] == 0): ?>
		 	    <option value="<?php echo $this->_tpl_vars['s']['supplier_id']; ?>
" <?php if ($this->_tpl_vars['param']['supplier_id'] == $this->_tpl_vars['s']['supplier_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['s']['supplier_name']; ?>
</option>
		        <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
              </select>
<br><br>
&nbsp;付款状态：
<select name="status" id="status">
  <option value="">请选择...</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>未付款</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>部分付款</option>
  <option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>已付款</option>
</select>
&nbsp;发票状态：
<select name="invoice" id="invoice">
  <option value="">请选择...</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['invoice'] == '0'): ?>selected<?php endif; ?>>未收</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['invoice'] == '1'): ?>selected<?php endif; ?>>已收</option>
</select>
系统单号：<input type="text" name="bill_no" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
<input type="checkbox" name="bySupplier" value=1" <?php if ($this->_tpl_vars['param']['bySupplier']): ?>checked<?php endif; ?>>按供应商排序
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
<?php endif; ?>
<div id="ajax_search">
<div class="title">结款订单查询</div>
<form name="myForm" id="myForm">
<div class="content">
    <div style="float:right;">
      <b>应付总金额：<?php echo $this->_tpl_vars['total']['amount']; ?>
&nbsp;&nbsp;&nbsp;应付总金额(不含税)：<?php echo $this->_tpl_vars['no_tax_sum']; ?>
</b>
      &nbsp;&nbsp;&nbsp;
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>系统单号</td>
            <td>单据类型</td>
            <td>供应商</td>
            <td>应付金额</td>
            <td>应付金额(不含税)</td>
            <td>实付金额</td>
            <td>未付金额</td>
            <td>已收发票金额</td>
            <td>入库日期</td>
            <td>付款状态</td>
            <td>发票</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['id']; ?>
">
        <td>
	      <input type="button" onclick="openDiv('/admin/finance/purchase-payment/id/<?php echo $this->_tpl_vars['data']['id']; ?>
','ajax','<?php if ($this->_tpl_vars['data']['status'] == 2): ?>查看<?php else: ?>付款<?php endif; ?>',700,400,true)" value="<?php if ($this->_tpl_vars['data']['status'] == 2): ?>查看<?php else: ?>付款<?php endif; ?>">
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
<?php if ($this->_tpl_vars['data']['bill_type'] == 2): ?>(<?php if ($this->_tpl_vars['data']['purchase_type'] == 1): ?>经销<?php else: ?>代销<?php endif; ?>)<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['supplier_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['amount']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['no_tax_amount']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['real_amount']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['left_amount']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['invoice_amount']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
        <td>
          <?php if ($this->_tpl_vars['data']['status'] == '0'): ?>未付款
          <?php elseif ($this->_tpl_vars['data']['status'] == '1'): ?>部分付款
          <?php elseif ($this->_tpl_vars['data']['status'] == '2'): ?>已付款
          <?php endif; ?>
        </td>
        <td id="invoice_<?php echo $this->_tpl_vars['data']['bill_no']; ?>
">
          <?php if ($this->_tpl_vars['data']['invoice'] == '0'): ?><a href="javascript:;void(0)" onclick="changeInvoice('<?php echo $this->_tpl_vars['data']['bill_no']; ?>
')">未收</a>
          <?php elseif ($this->_tpl_vars['data']['invoice'] == '1'): ?>已收
          <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
 </form>
<script language="JavaScript">
function changeInvoice(bill_no)
{
    new Request({url: '/admin/finance/purchase-change-invoice/bill_no/' + bill_no + '/type/1',
                method:'get' ,
                evalScripts:true,
                onSuccess: function(responseText) {
                    $('invoice_' + bill_no).innerHTML = responseText;
                }
    }).send();
}
</script>