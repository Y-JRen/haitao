<?php /* Smarty version 2.6.19, created on 2014-12-17 14:43:18
         compiled from product/barcode.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'product/barcode.tpl', 36, false),array('modifier', 'default', 'product/barcode.tpl', 37, false),)), $this); ?>
<form name="searchForm" id="searchForm">
<div class="search">
产品编码：<input type="text" name="product_sn" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
国际码：<input type="text" name="ean_barcode" size="25" maxLength="50" value="<?php echo $this->_tpl_vars['param']['ean_barcode']; ?>
"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 产品列表       </div>
<div class="content">
<div class="sub_title">
  [ <a href="javascript:fGo()" onclick="G('/admin/product/barcode/type/1');">查看所有产品</a> ]  [ <a href="javascript:fGo()" onclick="G('/admin/product/barcode/type/2');">查看有库存产品</a> ] 
</div>
   <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
			<td>产品分类</td>
			<td>产品名称（规格）</td>
			<td>库存</td>
            <td>国际码</td>
            <td>长(cm)</td>
		    <td>宽(cm)</td>
			<td>高(cm)</td>
           <td>重量(kg)</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['data']['product_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
		<td><?php echo $this->_tpl_vars['data']['cat_name']; ?>
</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['product_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
 <font color="#FF0000">(<?php echo $this->_tpl_vars['data']['goods_style']; ?>
)</font></td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['real_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</font></td>
        <td>
		<input type="text" name="update" size="16" value="<?php echo $this->_tpl_vars['data']['ean_barcode']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['product_id']; ?>
,'ean_barcode',this.value)">
		</td>
        <td>
		<input type="text" name="update" size="4" value="<?php echo $this->_tpl_vars['data']['p_length']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['product_id']; ?>
,'p_length',this.value)">
		</td>
		<td>
		<input type="text" name="update" size="4" value="<?php echo $this->_tpl_vars['data']['p_width']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['product_id']; ?>
,'p_width',this.value)">

		</td>
		<td>
		<input type="text" name="update" size="4" value="<?php echo $this->_tpl_vars['data']['p_height']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['product_id']; ?>
,'p_height',this.value)">
		
		</td>
        <td>
		<input type="text" name="update" size="4" value="<?php echo $this->_tpl_vars['data']['p_weight']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['product_id']; ?>
,'p_weight',this.value)">
		</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>