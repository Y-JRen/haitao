<?php /* Smarty version 2.6.19, created on 2014-08-13 09:31:45
         compiled from product/sel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'product/sel.tpl', 10, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['job']): ?>
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
	
<?php echo $this->_tpl_vars['catSelect']; ?>

<?php if ($this->_tpl_vars['showStatus'] != 'false'): ?>
商品状态：
<select name="status_id">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['status'],'selected' => 2), $this);?>

</select>
<?php endif; ?>
商品编码：<input type="text" name="product_sn" size="12" maxLength="50" value="" onkeydown="input()" />
商品名称：<input type="text" name="product_name" id="product_name" size="28" maxLength="50" value="" onkeydown="input()" /><br>
价格区间：<input type="text" name="fromprice" size="5" maxLength="10" value=""/> - <input type="text" name="toprice" size="5" maxLength="10" value=""/>
<input type="hidden" name="sid" value="<?php echo $this->_tpl_vars['param']['sid']; ?>
">
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('job'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
<br>
<?php if (! $this->_tpl_vars['justOne']): ?>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('<?php echo $this->_tpl_vars['param']['close_type']; ?>
');" type="button" value="添加并关闭"></p>
<?php endif; ?>
<?php endif; ?>
<div id="ajax_search">
<?php if (! empty ( $this->_tpl_vars['datas'] )): ?>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <?php if (! $this->_tpl_vars['justOne']): ?>
        <td><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('source_select'),'ids',this)"/></td>
        <?php endif; ?>
        <td>商品编码</td>
        <td>商品名称</td>
        <?php if (! $this->_tpl_vars['hidePrice']): ?>
        <td>采购价</td>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['showStatus'] != 'false'): ?>
        <td>状态</td>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['param']['type'] == 'sel_status' || $this->_tpl_vars['param']['type'] == 'sel_stock'): ?><td>可用库存</td><?php endif; ?>
        <td>上架状态</td>
        <?php if ($this->_tpl_vars['justOne']): ?><td>操作</td><?php endif; ?>
    </tr>
</thead>
<tbody>
<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<tr id="ajax_list<?php echo $this->_tpl_vars['data']['product_id']; ?>
">
    <?php if (! $this->_tpl_vars['justOne']): ?>
    <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['product_id']; ?>
"/>
    <?php endif; ?>
    <input type="hidden" id="pinfo<?php echo $this->_tpl_vars['data']['product_id']; ?>
" value='<?php echo $this->_tpl_vars['data']['pinfo']; ?>
'>
    </td>
    <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
    <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['data']['goods_style']; ?>
</font>)</td>
    <?php if (! $this->_tpl_vars['hidePrice']): ?>
    <td><?php if ($this->_tpl_vars['data']['purchase_cost']): ?><?php echo $this->_tpl_vars['data']['purchase_cost']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['cost']; ?>
<?php endif; ?></td>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['showStatus'] != 'false'): ?>
    <td><?php echo $this->_tpl_vars['data']['status_name']; ?>
</td>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['param']['type'] == 'sel_status' || $this->_tpl_vars['param']['type'] == 'sel_stock'): ?><td><?php echo $this->_tpl_vars['data']['able_number']; ?>
</td><?php endif; ?>
    <td><?php if ($this->_tpl_vars['data']['p_status'] == 1): ?> 下架 <?php else: ?> 上架  <?php endif; ?>  </td>
    <?php if ($this->_tpl_vars['justOne']): ?>
    <td><input type="button" name="choose" value="选择" onclick="addRow('pinfo<?php echo $this->_tpl_vars['data']['product_id']; ?>
');alertBox.closeDiv();"></td>
    <?php endif; ?>
</tr>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
<?php endif; ?>
<?php if (! $this->_tpl_vars['param']['job']): ?>
</div>
<br>
<?php if (! $this->_tpl_vars['justOne']): ?>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('<?php echo $this->_tpl_vars['param']['close_type']; ?>
');" type="button" value="添加并关闭"></p>
<?php endif; ?>
<?php endif; ?>
</div>

<script>
function input()
{
    var e = getEvent();
    if (e.keyCode == 13) {
        ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('job'=>'search',)));?>','ajax_search');
    }
}

function getEvent()
{  
    if (document.all)   return window.event;    
    func = getEvent.caller;
    while(func != null) {
        var arg0 = func.arguments[0]; 
        if (arg0) { 
            if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {  
                return arg0; 
            } 
        } 
        func = func.caller; 
    }
    
    return null; 
}
</script>