<?php /* Smarty version 2.6.19, created on 2014-08-18 15:16:49
         compiled from goods/sel.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['job']): ?>
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
<?php if (! $this->_tpl_vars['param']['type']): ?>
排序：<select name="sort">
  <option value=""  >选择排序</option>
  <option value="2"  <?php if ($this->_tpl_vars['param']['sort'] == '2'): ?>selected<?php endif; ?> >畅销</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['sort'] == '1'): ?>selected<?php endif; ?>>新品</option>
  <option value="3" <?php if ($this->_tpl_vars['param']['sort'] == '3'): ?>selected<?php endif; ?>>价格低到高</option>
  <option value="4" <?php if ($this->_tpl_vars['param']['sort'] == '4'): ?>selected<?php endif; ?> >价格高到低</option>
</select>
<br>
<?php endif; ?>
商品编码：<input type="text" name="goods_sn" size="10" maxLength="10" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
"/>
商品名称：<input type="text" name="goods_name" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
<?php if (! $this->_tpl_vars['param']['type']): ?><?php echo $this->_tpl_vars['catSelect']; ?>
价格区间：<input type="text" name="fromprice" size="5" maxLength="10" value="<?php echo $this->_tpl_vars['param']['fromprice']; ?>
"/> - <input type="text" name="toprice" size="5" maxLength="10" value="<?php echo $this->_tpl_vars['param']['toprice']; ?>
"/><?php endif; ?>
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php if ($this->_tpl_vars['param']['type'] == '2'): ?><?php echo $this -> callViewHelper('url', array(array('job'=>'group','type'=>2,)));?><?php else: ?>
<?php echo $this -> callViewHelper('url', array(array('job'=>'search',)));?><?php endif; ?>','ajax_search_goods')"/>
<input type="reset" name="reset" value="清除">
</form>
<br>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('<?php echo $this->_tpl_vars['param']['close_type']; ?>
');" type="button" value="添加并关闭"></p>
<?php endif; ?>
<div id="ajax_search_goods">
<?php if (! empty ( $this->_tpl_vars['datas'] )): ?>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td width="30"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('source_select'),'ids',this)"/></td>
         <td>商品ID</td>
         <td>商品编码</td>
        <td>商品名称</td>
        <td>现价</td>
        <td>状态</td>
		<?php if (! $this->_tpl_vars['param']['type']): ?><td>销量</td><?php endif; ?>
		<td>库存</td>
    </tr>
</thead>
<tbody>
<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
<tr id="ajax_list<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
    <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['goods_id']; ?>
"/>
    <input type="hidden" id="ginfo<?php echo $this->_tpl_vars['data']['goods_id']; ?>
" value='<?php echo $this->_tpl_vars['data']['ginfo']; ?>
'>
    </td>
    <td><?php echo $this->_tpl_vars['data']['goods_id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['data']['goods_sn']; ?>
</td>
    <td><?php echo $this->_tpl_vars['data']['goods_name']; ?>
(<font color="#FF3333"><?php echo $this->_tpl_vars['data']['goods_style']; ?>
</font>)</td>
    <td><?php echo $this->_tpl_vars['data']['price']; ?>
</td>
    <td><?php echo $this->_tpl_vars['data']['goods_status']; ?>
</td>
	<?php if (! $this->_tpl_vars['param']['type']): ?><td><?php echo $this->_tpl_vars['data']['sort_sale']; ?>
</td><?php endif; ?>
	<td><?php echo $this->_tpl_vars['data']['store']; ?>
</td>
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
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('<?php echo $this->_tpl_vars['param']['close_type']; ?>
');" type="button" value="添加并关闭"></p>
<?php endif; ?>
</div>