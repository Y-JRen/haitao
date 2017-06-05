<?php /* Smarty version 2.6.19, created on 2014-12-19 16:40:11
         compiled from stock-report/history.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'stock-report/history.tpl', 19, false),array('modifier', 'default', 'stock-report/history.tpl', 68, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
<span style="float:left;line-height:18px;">日期选择：</span>
<span style="float:left;width:115px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">-&nbsp;&nbsp;</span>
<span style="float:left;width:120px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/></span>
选择仓库：
<?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<input type="checkbox" name="logic_area" value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['logic_area'][$this->_tpl_vars['key']]): ?>checked<?php endif; ?> disabled><?php echo $this->_tpl_vars['item']; ?>

<?php endforeach; endif; unset($_from); ?>
&nbsp;&nbsp;&nbsp;
<br><br>
产品状态：<select name="p_status"><option value="0" <?php if ($this->_tpl_vars['param']['p_status'] == '0'): ?>selected<?php endif; ?>>启用</option><option value="1" <?php if ($this->_tpl_vars['param']['p_status'] == '1'): ?>selected<?php endif; ?>>冻结</option></select>
产品编码：<input type="text" name="product_sn" size="6" maxLength="20" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
选择库存状态：<select name="status_id" id="status_id">
<option value="">请选择</option>
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['status'],'selected' => $this->_tpl_vars['param']['status_id']), $this);?>

</select>
<input type="checkbox" name="onlyChangeRecord" value="1" <?php if ($this->_tpl_vars['param']['onlyChangeRecord']): ?>checked<?php endif; ?>>只显示数量发生变化的记录
<input type="button" name="dosearch" id="dosearch" value="查询" onclick="check()"/>
<input type="reset" name="reset" value="清除">
<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">[导出数据]</a>
<br>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">库存管理 -&gt; 历史库存报表 
</div>
<div class="content">
    <?php if ($this->_tpl_vars['datas']): ?>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品编码</td>
            <td width="350px">产品名称</td>
			
	<?php if ($this->_tpl_vars['viewcost'] == '1'): ?>
            <td>成本价</td>
            <td>未税价</td>
	     <td>建议零售价</td>
	<?php endif; ?>
			
            <?php if ($this->_tpl_vars['param']['status_id']): ?><td>库存状态</td><?php endif; ?>
            <td>期初库存</td>
            <td>期末库存</td>
            <td>正常入库数量</td>
            <td>状态更改入库数量</td>
            <td>调拨入库数量</td>
            <td>正常出库数量</td>
            <td>状态更改出库数量</td>
            <td>调拨出库数量</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
 <font color="#FF0000">(<?php echo $this->_tpl_vars['data']['goods_style']; ?>
)</font></td>
		<?php if ($this->_tpl_vars['viewcost'] == '1'): ?>
                  <td><?php echo $this->_tpl_vars['data']['cost']; ?>
</td>
                  <td><?php echo $this->_tpl_vars['data']['cost_tax']; ?>
</td>
                  <td><?php echo $this->_tpl_vars['data']['suggest_price']; ?>
</td>
		<?php endif; ?>
		
        <?php if ($this->_tpl_vars['param']['status_id']): ?><td><?php echo $this->_tpl_vars['status'][$this->_tpl_vars['param']['status_id']]; ?>
</td><?php endif; ?>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['start_stock_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['end_stock_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['in_stock_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['in_status_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['in_allocation_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['out_stock_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['out_status_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['out_allocation_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
    <?php endif; ?>
</div>
</form>

<script type="text/javascript">
function check()
{
    if ($('fromdate').value < '2012-12-01') {
        alert('开始日期不能小于2012-12-01');
        return false;
    }
    
    ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search');
}

</script>