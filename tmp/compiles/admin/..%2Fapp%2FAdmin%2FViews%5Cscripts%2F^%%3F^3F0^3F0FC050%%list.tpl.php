<?php /* Smarty version 2.6.19, created on 2014-08-21 14:31:31
         compiled from transport/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'transport/list.tpl', 10, false),array('modifier', 'date_format', 'transport/list.tpl', 87, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<input type="hidden" name="export" value="0" id="export" />
<div class="search">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['billType'],'selected' => $this->_tpl_vars['param']['bill_type']), $this);?>

</select>
配送状态：
<select name="logistic_status">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticStatus'],'selected' => $this->_tpl_vars['param']['logistic_status']), $this);?>

</select>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => $this->_tpl_vars['param']['logistic_code']), $this);?>

</select>
是否投诉：<select name="is_complain"><option value="">请选择</option><option value="0" <?php if ($this->_tpl_vars['param']['is_complain'] == '0'): ?>selected<?php endif; ?>>否</option><option value="1" <?php if ($this->_tpl_vars['param']['is_complain'] == '1'): ?>selected<?php endif; ?>>是</option></select>
<div class="line">
店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shop']):
?>
      <option value="<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
" <?php if ($this->_tpl_vars['shop']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['shop']['shop_name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
  </select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" <?php if ($this->_tpl_vars['param']['is_cod'] == '0'): ?>selected<?php endif; ?>>非货到付款</option><option value="1" <?php if ($this->_tpl_vars['param']['is_cod'] == '1'): ?>selected<?php endif; ?>>货到付款</option></select>
收货人：<input type="text" name="consignee" size="6" maxLength="20" value="<?php echo $this->_tpl_vars['param']['consignee']; ?>
"/>
运单号码：<input type="text" name="logistic_no" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['logistic_no']; ?>
"/>
单据编号：<input type="text" name="bill_no" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
验证码：<input type="text" name="validate_sn" size="5" maxLength="5" value="<?php echo $this->_tpl_vars['param']['validate_sn']; ?>
"/>
<input type="button" name="dosearch" value="查询" onclick="doExport(0)" />
<input type="reset" name="reset" value="清除"><input type="button" onclick="doExport(1)" value="导出">
</div>
</div>
</form>
<div id="ajax_search">
<div class="title">配送管理 -&gt; <?php echo $this->_tpl_vars['actions'][$this->_tpl_vars['action']]; ?>
</div>
<form name="myForm" id="myForm">
<div class="content">
	<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
	</div>
    <?php if ($this->_tpl_vars['datas']): ?>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30"></td>
            <td>操作</td>
            <td>店铺</td>
            <td>单据编号</td>
            <td>运单号码</td>
            <td>收货人</td>
            <td>单据类型</td>
            <td>付款方式</td>
            <td>承运商</td>
            <td>发货日期</td>
            <td>配送状态</td>
            <td>运费</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['tid']; ?>
">
    	<td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['tid']; ?>
"/></td>
        <td>
	<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'view','id'=>$this->_tpl_vars['data']['tid'],)));?>','ajax','查看单据')" value="查看">
        </td>
        <td><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['bill_no_str']; ?>

            <?php if ($this->_tpl_vars['data']['is_cancel'] == 1): ?>
            （待取消）
            <?php elseif ($this->_tpl_vars['data']['is_cancel'] == 2): ?>
            （已取消）
            <?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['logistic_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['consignee']; ?>
</td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
<input type="hidden" name="info[<?php echo $this->_tpl_vars['data']['tid']; ?>
][bill_type]" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
"></td>
        <td><?php if ($this->_tpl_vars['data']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['send_time']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['send_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['logisticStatus'][$this->_tpl_vars['data']['logistic_status']]; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['logistic_price']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
    <input type="button" onclick="window.open('<?php echo $this -> callViewHelper('url', array(array('action'=>'export',)));?>'+location.search)" value="导出数据">
    <?php endif; ?>
    <div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
	</div>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
</div>

<script>
function doExport(export_id)
{
    $("export").value = export_id;
    $("searchForm").submit();
}
</script>