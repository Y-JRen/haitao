<?php /* Smarty version 2.6.19, created on 2014-12-22 17:27:15
         compiled from transport/pick-up.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'transport/pick-up.tpl', 8, false),array('modifier', 'date_format', 'transport/pick-up.tpl', 43, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" method="get">
开始日期：<input type="text" name="date" id="date" size="12" value="<?php echo $this->_tpl_vars['param']['date']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => $this->_tpl_vars['param']['logistic_code']), $this);?>

</select>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['consignee']; ?>
"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
</form>
<div class="title">配送管理 -&gt; 当天物流公司取件</div>
<form name="myForm" id="myForm">
<div class="content">
	<div style="padding:0 5px;float:right;"><h3>总件数：<?php echo $this->_tpl_vars['total']; ?>
</h3></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>单据编号</td>
            <td>店铺</td>
            <td>地区</td>
            <td>收货人</td>
            <td>付款方式</td>
            <td>物流公司</td>
            <td>物流单号</td>
            <td>发货时间</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['tid']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['bill_no_str']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['province']; ?>
<?php echo $this->_tpl_vars['data']['city']; ?>
<?php echo $this->_tpl_vars['data']['area']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['consignee']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['logistic_no']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['send_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
	    </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
</form>