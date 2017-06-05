<?php /* Smarty version 2.6.19, created on 2014-12-30 16:00:06
         compiled from replenishment/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'replenishment/index.tpl', 51, false),)), $this); ?>
<div class="search">
  <form id="searchForm" method="get">
  补货单状态：
  <select name="status">
    <option value="">请选择...</option>
    <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>未确认</option>
    <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>已申请</option>
    <option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>已审核</option>
    <option value="3" <?php if ($this->_tpl_vars['param']['status'] == '3'): ?>selected<?php endif; ?>>已收货</option>
    <option value="4" <?php if ($this->_tpl_vars['param']['status'] == '4'): ?>selected<?php endif; ?>>已完成</option>
    <option value="9" <?php if ($this->_tpl_vars['param']['status'] == '9'): ?>selected<?php endif; ?>>已取消</option>
  </select>
  产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
">
  产品编码：<input type="text" name="product_sn" size="10" maxLength="10" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
">
  采购入库单号：<input type="text" name="bill_no" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
">
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">补货单列表</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>产品编码</td>
				<td>产品名称</td>
				<td>需求数量</td>
				<td>收货数量</td>
				<td>采购入库单号</td>
				<td>添加时间</td>
				<td>状态</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr >
		    <td valign="top"><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['product_name']; ?>
</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['require_number']; ?>
</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['receive_number']; ?>
</td>
			<td valign="top">
			<?php if ($this->_tpl_vars['billInfo'][$this->_tpl_vars['data']['replenishment_id']]): ?>
			  <?php $_from = $this->_tpl_vars['billInfo'][$this->_tpl_vars['data']['replenishment_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bill_no']):
?>
			  <a href="javascript:void(0);" onclick="openDiv('/admin/logic-area-in-stock/view/logic_area/1/bill_no/<?php echo $this->_tpl_vars['bill_no']; ?>
','ajax','查看单据',800,400)"><?php echo $this->_tpl_vars['bill_no']; ?>
</a><br>
			  <?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			</td>
			<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
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
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>