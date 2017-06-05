<?php /* Smarty version 2.6.19, created on 2014-12-17 14:42:57
         compiled from stock-report/district-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'stock-report/district-list.tpl', 47, false),)), $this); ?>
<div class="search">
  <form id="searchForm" method="get">
  所属仓库：
  <select name="area">
    <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
    <?php if ($this->_tpl_vars['param']['area'] == $this->_tpl_vars['key']): ?>
    <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['area'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </select>
  库区状态：
	<select name="status">
		<option value="">请选择...</option>
		<option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>正常</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>冻结</option>
	</select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">库区列表 [<a href="/admin/stock-report/add-district">添加库区</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>库区名称</td>
				<td>库区编号</td>
				<td>所属仓库</td>
    			<td>状态</td>
				<td>添加时间</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr >
		    <td valign="top"><?php echo $this->_tpl_vars['data']['district_name']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['district_no']; ?>
</td>
			<td valign="top"><?php echo $this->_tpl_vars['areas'][$this->_tpl_vars['data']['area']]; ?>
</td>
			<td>
			  <?php if ($this->_tpl_vars['data']['status'] == 0): ?>启用
			  <?php else: ?>冻结
			  <?php endif; ?>
			</td>
			<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-district",'id'=>$this->_tpl_vars['data']['district_id'],)));?>')">编辑</a>
			</td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>