<?php /* Smarty version 2.6.19, created on 2014-08-01 13:47:19
         compiled from product/price-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'product/price-list.tpl', 51, false),array('modifier', 'default', 'product/price-list.tpl', 57, false),)), $this); ?>
<form name="searchForm" id="searchForm">
<div class="search">

<?php echo $this->_tpl_vars['catSelect']; ?>

状态：
<select name="p_status">
  <option value="" selected>请选择</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['p_status'] == '0'): ?>selected<?php endif; ?>>正常</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['p_status'] == '1'): ?>selected<?php endif; ?>>冻结</option>
</select>
产品编码：<input type="text" name="product_sn" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="product_name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
货位：<input type="text" name="local_sn" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['local_sn']; ?>
"/>
限价：<input type="checkbox" name="price_limit" value="1" <?php if ($this->_tpl_vars['param']['price_limit'] == '1'): ?>checked='true'<?php endif; ?>/>
<br>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
<input type="button" name="dosearch2" value="所有被我锁定的产品" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('is_lock'=>yes,)));?>','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的产品" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('is_lock'=>no,)));?>','ajax_search')"/>
<input type="button" onclick="window.open('/admin/product/export-price'+location.search)" value="导出产品成本">
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 成本管理</div>
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td width="30"></td>
            <td>产品ID</td>
            <td width="80px">产品编码</td>
            <td width="200px">产品名称</td>
            <td>系统分类</td>
            <td>建议销售价</td>
            <td>(移动)成本</td>
            <td>(采购)成本</td>
            <td>发票税率</td>
            <td>真实库存</td>
            <td>状态</td>
            <td>是否锁定</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['product_id']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['product_id']; ?>
"/></td>
        <td><?php echo $this->_tpl_vars['data']['product_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['product_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<font color="#FF0000">(<?php echo $this->_tpl_vars['data']['goods_style']; ?>
)</font></td>
        <td><?php echo $this->_tpl_vars['data']['cat_name']; ?>
</td>
        <td <?php if ($this->_tpl_vars['data']['suggest_price'] < $this->_tpl_vars['data']['price_limit']): ?> style="color:#ff0000"<?php endif; ?>><?php echo $this->_tpl_vars['data']['suggest_price']; ?>
</td>
		<td><?php echo $this->_tpl_vars['data']['cost']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['purchase_cost']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['invoice_tax_rate']; ?>
</td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['real_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
        <td>
          <?php if ($this->_tpl_vars['data']['p_status'] == '0'): ?>正常
          <?php else: ?><font color="red">冻结</font>
          <?php endif; ?>
        </td>
        <td><?php if ($this->_tpl_vars['data']['p_lock_name']): ?>被<font color="red"><?php echo $this->_tpl_vars['data']['p_lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
        <td>
	      <a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>"cost-edit",'id'=>$this->_tpl_vars['data']['product_id'],)));?>','ajax','产品成本修改');">
	      <?php if ($this->_tpl_vars['data']['p_lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>编辑<?php else: ?>查看<?php endif; ?></a>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>