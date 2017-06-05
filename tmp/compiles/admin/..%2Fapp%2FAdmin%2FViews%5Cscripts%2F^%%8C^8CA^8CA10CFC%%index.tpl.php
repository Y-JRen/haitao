<?php /* Smarty version 2.6.19, created on 2014-08-01 10:06:31
         compiled from stock-report/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'stock-report/index.tpl', 16, false),)), $this); ?>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
选择仓库：
<?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<input type="checkbox" name="logic_area" value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['logic_area'][$this->_tpl_vars['key']]): ?>checked<?php endif; ?> disabled><?php echo $this->_tpl_vars['item']; ?>

<?php endforeach; endif; unset($_from); ?>
<br>
产品状态：<select name="p_status"><option value="0" <?php if ($this->_tpl_vars['param']['p_status'] == '0'): ?>selected<?php endif; ?>>启用</option><option value="1" <?php if ($this->_tpl_vars['param']['p_status'] == '1'): ?>selected<?php endif; ?>>冻结</option></select>
<?php echo $this->_tpl_vars['catSelect']; ?>

产品编码：<input type="text" name="product_sn" size="6" maxLength="20" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
产品批次：<input type="text" name="batch_no" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['batch_no']; ?>
"/>
<br>
选择库存状态：<select name="status_id" id="status_id">
<option value="">请选择</option>
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['status'],'selected' => $this->_tpl_vars['param']['status_id']), $this);?>

</select>
货位：<input type="text" name="local_sn" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['local_sn']; ?>
"/>
<select name="stock_number_type">
<option value="real_number" <?php if ($this->_tpl_vars['param']['stock_number_type'] == 'real_number'): ?>selected<?php endif; ?>>实际库存</option>
<option value="able_number" <?php if ($this->_tpl_vars['param']['stock_number_type'] == 'able_number'): ?>selected<?php endif; ?>>可用库存</option>
<option value="hold_number" <?php if ($this->_tpl_vars['param']['stock_number_type'] == 'hold_number'): ?>selected<?php endif; ?>>占有库存</option>
<option value="wait_number" <?php if ($this->_tpl_vars['param']['stock_number_type'] == 'wait_number'): ?>selected<?php endif; ?>>在途库存</option>
<option value="plan_number" <?php if ($this->_tpl_vars['param']['stock_number_type'] == 'plan_number'): ?>selected<?php endif; ?>>计划结存</option>
</select>
<select name="stock_number_logic">
<option value="more" <?php if ($this->_tpl_vars['param']['stock_number_logic'] == 'more'): ?>selected<?php endif; ?>>>=</option>
<option value="less" <?php if ($this->_tpl_vars['param']['stock_number_logic'] == 'less'): ?>selected<?php endif; ?>><</option>
</select>
<input type="text" name="stock_number" size="10" value="<?php echo $this->_tpl_vars['param']['stock_number']; ?>
"/>
<input type="checkbox" name="showBatch" value="1" <?php if ($this->_tpl_vars['param']['showBatch']): ?>checked<?php endif; ?>>显示批次
<input type="button" name="dosearch" id="dosearch" value="查询" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
<input type="button" onclick="doExport()" value="导出动态库存信息">
<br>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">库存管理 -&gt; 动态库存
</div>
<div class="content">
    <?php if ($this->_tpl_vars['datas']): ?>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品编码</td>
            <td width="200px">产品名称</td>
            <td>产品单位</td>
            <?php if ($this->_tpl_vars['param']['showBatch']): ?><td>产品批次</td><?php endif; ?>
            <td>库存状态</td>
            <td>库位</td>
            <td>计划结存</td>
            <td>实际库存</td>
            <td>可用库存</td>
            <td>在途库存</td>
            <td>占有库存</td>
            <td>产品状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['stock_id']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
 <font color="#FF0000">(<?php echo $this->_tpl_vars['data']['goods_style']; ?>
)</font></td>
         <td><?php echo $this->_tpl_vars['data']['goods_units']; ?>
</td>
        <?php if ($this->_tpl_vars['param']['showBatch']): ?><td><?php if ($this->_tpl_vars['data']['batch_no']): ?><?php echo $this->_tpl_vars['data']['batch_no']; ?>
<?php else: ?>无批次<?php endif; ?></td><?php endif; ?>
        <td><?php echo $this->_tpl_vars['status'][$this->_tpl_vars['data']['status_id']]; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['position_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['plan_number']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['real_number']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['able_number']; ?>
</td>
        <td><a href="javascript:;void(0)" onclick="window.open('/admin/stock-report/wait-stock-detail/product_id/<?php echo $this->_tpl_vars['data']['product_id']; ?>
<?php if ($this->_tpl_vars['param']['showBatch']): ?>/batch_id/<?php if ($this->_tpl_vars['data']['batch_id']): ?><?php echo $this->_tpl_vars['data']['batch_id']; ?>
<?php else: ?>0<?php endif; ?><?php endif; ?>/status_id/<?php echo $this->_tpl_vars['data']['status_id']; ?>
', 'wait_stock_<?php echo $this->_tpl_vars['data']['product_id']; ?>
', 'height=520,width=800,toolbar=no,scrollbars=yes,resizable=yes')"><?php echo $this->_tpl_vars['data']['wait_number']; ?>
</a></td>
        <td><a href="javascript:;void(0)" onclick="window.open('/admin/stock-report/hold-stock-detail/product_id/<?php echo $this->_tpl_vars['data']['product_id']; ?>
<?php if ($this->_tpl_vars['param']['showBatch']): ?>/batch_id/<?php if ($this->_tpl_vars['data']['batch_id']): ?><?php echo $this->_tpl_vars['data']['batch_id']; ?>
<?php else: ?>0<?php endif; ?><?php endif; ?>/status_id/<?php echo $this->_tpl_vars['data']['status_id']; ?>
', 'hold_stock_<?php echo $this->_tpl_vars['data']['product_id']; ?>
', 'height=520,width=800,toolbar=no,scrollbars=yes,resizable=yes')"><?php echo $this->_tpl_vars['data']['hold_number']; ?>
</a></td>
        <td><?php if ($this->_tpl_vars['data']['p_status']): ?><font color="red">冻结</font><?php else: ?>启用<?php endif; ?></td>
        <td><a href="javascript:;void(0)" onclick="window.open('/admin/stock-report/graph/product_id/<?php echo $this->_tpl_vars['data']['product_id']; ?>
', 'stock_graph_<?php echo $this->_tpl_vars['data']['product_id']; ?>
', 'height=520,width=800,toolbar=no')">月图表</a></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
    <input type="button" onclick="doExport()" value="导出动态库存">
    <?php endif; ?>
</div>

<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>

<script type="text/javascript">
function doExport()
{
    document.getElementById('searchForm').target='_blank';
    document.getElementById('searchForm').method='post';
    document.getElementById('searchForm').action='<?php echo $this -> callViewHelper('url', array());?>/export/1';
    document.getElementById('searchForm').submit();
}
</script>