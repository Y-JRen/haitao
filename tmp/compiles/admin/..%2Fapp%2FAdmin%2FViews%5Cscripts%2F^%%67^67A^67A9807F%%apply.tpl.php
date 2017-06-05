<?php /* Smarty version 2.6.19, created on 2014-12-30 15:58:09
         compiled from replenishment/apply.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'replenishment/apply.tpl', 51, false),array('modifier', 'date_format', 'replenishment/apply.tpl', 57, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
  <form id="searchForm" method="get">
  补货单状态：
  <select name="status">
    <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>未确认</option>
  </select>
  产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
">
  产品编码：<input type="text" name="product_sn" size="10" maxLength="10" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
">
  供应商：
  <select name="supplier_id" onchange="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')">
    <option value="">请选择...</option>
  <?php if ($this->_tpl_vars['supplierData']): ?>
  <?php $_from = $this->_tpl_vars['supplierData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <option value="<?php echo $this->_tpl_vars['data']['supplier_id']; ?>
" <?php if ($this->_tpl_vars['param']['supplier_id'] == $this->_tpl_vars['data']['supplier_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['data']['supplier_name']; ?>
</option>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  </select>
  
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">补货单开单列表</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/></td>
				<td>产品编码</td>
				<td>产品名称</td>
				<td>总需求数量</td>
				<td>自动需求数量</td>
				<td>手工需求数量</td>
				<td>添加时间</td>
				<td>更新时间</td>
				<td>状态</td>
				<td>操作</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr >
		    <td>
		      <input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['replenishment_id']; ?>
">
		    </td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['product_name']; ?>
</td>
		    <td valign="top" id="require_number_<?php echo $this->_tpl_vars['data']['replenishment_id']; ?>
"><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['require_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
			<td valign="top" id="auto_number_<?php echo $this->_tpl_vars['data']['replenishment_id']; ?>
"><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['auto_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
			<td valign="top">
			  <input type="text" id="manual_number_<?php echo $this->_tpl_vars['data']['replenishment_id']; ?>
" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['manual_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" size="2" style="text-align:center">
			  <a href="javascript:void(0);" onclick="changeNumber(<?php echo $this->_tpl_vars['data']['replenishment_id']; ?>
, $('manual_number_<?php echo $this->_tpl_vars['data']['replenishment_id']; ?>
').value)">调整</a>
			</td>
			<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
			<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['update_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['status'] == 0): ?>未确认
			  <?php elseif ($this->_tpl_vars['data']['status'] == 1): ?>已申请
			  <?php elseif ($this->_tpl_vars['data']['status'] == 2): ?>已审核
			  <?php elseif ($this->_tpl_vars['data']['status'] == 3): ?>已收货
			  <?php elseif ($this->_tpl_vars['data']['status'] == 4): ?>已完成
			  <?php elseif ($this->_tpl_vars['data']['status'] == 9): ?>已取消
			  <?php endif; ?>
			</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="openDiv('/admin/replenishment/view/id/<?php echo $this->_tpl_vars['data']['replenishment_id']; ?>
','ajax','补货单详情',750,400);">详请</a>
			</td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div style="text-align:center">
	  <input type="button" onclick="openDiv('/admin/product/sel/logic_area/1/type/sel','ajax','查询商品',750,400);" value="手工添加补货商品">
	  <input type="button" name="apply" value="补货开单" onclick="check(this.form)">
	</div>
</form>
<form name="addForm" id="addForm" action="/admin/replenishment/add-product" method="post" target="ifrmSubmit">
<input type="hidden" name="ids" id="ids" value="">
</form>
<script>
function check(tag)
{
    var checkbox = tag.getElements('input[type=checkbox]');
    var ids = '';
	for(var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if (e.checked) {
		    if (e.value == 'on')    continue;
		    ids = ids + e.value + ',';
		}
	}
	if (ids == '') {
	    alert('没有选择单据!');
        return false;
	}
	
	<?php if (! $this->_tpl_vars['param']['supplier_id']): ?>
	alert('请先选择供应商!');
    return false;
	<?php endif; ?>
	
	ids = ids.substring(0, ids.length - 1);
	openDiv('/admin/replenishment/new/supplier_id/<?php echo $this->_tpl_vars['param']['supplier_id']; ?>
/ids/' + ids,'ajax','补货入库单',750,400);
}
function checkNum(obj, num)
{
	if (parseInt(obj.value) == 0 || isNaN(obj.value)) {
	    alert('请填写正整数');
	    obj.value = 0;
	    return false;
	}
}
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	var ids = '';
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			var str = $('pinfo' + el[i].value).value;
			var pinfo = JSON.decode(str);
            ids = ids + pinfo.product_id + '|';
        }
    }
    if (ids != '') {
        ids = ids.substring(0, ids.length - 1);
        $('ids').value = ids;
        $('addForm').submit();
    }
}
function changeNumber(id, number) 
{
    new Request({url: '/admin/replenishment/change-product-number/id/' + id + '/number/' + number,
                method:'get' ,
                onSuccess: function(responseText) {
                    if (responseText == 'ok') {
                        $('require_number_' + id).innerHTML =  parseInt($('auto_number_' + id).innerHTML) + parseInt(number);
                        alert('调整成功!');
                    }
                    else {
                        alert('调整失败!');
                    }
                }
    }).send();
}
</script>