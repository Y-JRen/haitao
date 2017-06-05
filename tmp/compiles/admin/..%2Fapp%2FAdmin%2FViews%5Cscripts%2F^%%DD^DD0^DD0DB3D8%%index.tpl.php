<?php /* Smarty version 2.6.19, created on 2014-08-01 10:04:51
         compiled from product/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'product/index.tpl', 67, false),array('modifier', 'stripslashes', 'product/index.tpl', 71, false),)), $this); ?>
<form name="searchForm" id="searchForm">
<div class="search">
系统分类：<?php echo $this->_tpl_vars['catSelect']; ?>

状态：
<select name="p_status">
  <option value="" selected>请选择</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['p_status'] == '0'): ?>selected<?php endif; ?>>正常</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['p_status'] == '1'): ?>selected<?php endif; ?>>冻结</option>
</select>
虚拟商品：
<select name="is_vitual">
  <option value="" selected>请选择</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['is_vitual'] == '1'): ?>selected<?php endif; ?>>是</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['is_vitual'] == '0'): ?>selected<?php endif; ?>>否</option>
</select>
礼品卡：
<select name="is_gift_card">
  <option value="" selected>请选择</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['is_gift_card'] == '1'): ?>selected<?php endif; ?>>是</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['is_gift_card'] == '0'): ?>selected<?php endif; ?>>否</option>
</select>
产品编码：<input type="text" name="product_sn" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
<br>
货位：<input type="text" name="local_sn" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['local_sn']; ?>
"/>
国际码：<input type="text" name="ean_barcode" size="25" maxLength="50" value="<?php echo $this->_tpl_vars['param']['ean_barcode']; ?>
"/>
<input type="checkbox" name="product_img" value="1" <?php if ($this->_tpl_vars['param']['product_img']): ?>checked<?php endif; ?>> 图标未上传
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">   <input type="button" onclick="window.open('/admin/product/export'+location.search)" value="导出商品资料">
<br>
<input type="button" name="dosearch2" value="所有被我锁定的产品" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('is_lock'=>yes,)));?>','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的产品" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('is_lock'=>no,)));?>','ajax_search')"/>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 产品列表</div>
<div class="content">
<!--
<div class="sub_title">
  [ <a href="javascript:fGo()" onclick="G('/admin/product/add');">添加新产品</a> ] 
</div>
-->
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td width="10px"></td>
            <td>产品主图</td>
            <td>产品ID</td>
            <td  width="60px">产品编码</td>
            <!--<td>国际码</td>-->
            <td width="260px">产品名称（规格）</td>
            <td>系统分类</td>
            <td>供应商</td>
            <!--<td>长宽高(cm)</td>-->
            <!--<td>重量(kg)</td>-->
            <td>状态</td>
            <td>是否锁定</td>
            <td>可超卖库存</td>
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
        <td><?php if ($this->_tpl_vars['data']['product_img']): ?><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['product_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" width="35"><?php else: ?><font color="red" size="3">未上传</font><?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['product_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
        <!-- <td><?php echo $this->_tpl_vars['data']['ean_barcode']; ?>
</td>-->
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['product_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<font color="#FF0000"> (<?php echo $this->_tpl_vars['data']['goods_style']; ?>
) </font></td>
        <td><?php echo $this->_tpl_vars['data']['cat_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['supplier']; ?>
</td>
        <!--<td>长：<?php echo $this->_tpl_vars['data']['p_length']; ?>
<br>宽：<?php echo $this->_tpl_vars['data']['p_width']; ?>
<br>高：<?php echo $this->_tpl_vars['data']['p_height']; ?>
</td>-->
        <!--<td><?php echo $this->_tpl_vars['data']['p_weight']; ?>
</td>-->
        <td id="ajax_status<?php echo $this->_tpl_vars['data']['product_id']; ?>
"><?php echo $this->_tpl_vars['data']['status']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['p_lock_name']): ?>被<font color="red"><?php echo $this->_tpl_vars['data']['p_lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
        <td>
            <input type="text" name="adjust_num" size="4" id="adjust_num" value="<?php echo $this->_tpl_vars['data']['adjust_num']; ?>
" style="text-align:center;"  onchange="js_ajax_update(this, '<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['product_id']; ?>
,'adjust_num',this.value, '<?php echo $this->_tpl_vars['data']['stock_able_number']; ?>
')" />
        </td>
        <td>
	      <a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['data']['product_id'],)));?>','ajax','产品修改' ,850,400 );"><?php if ($this->_tpl_vars['data']['p_lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>编辑<?php else: ?>查看<?php endif; ?></a>
	      <a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'image','id'=>$this->_tpl_vars['data']['product_id'],'product_sn'=>$this->_tpl_vars['data']['product_sn'],)));?>','ajax','图片管理 <?php echo $this->_tpl_vars['data']['product_name']; ?>
');"><?php if ($this->_tpl_vars['data']['p_lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>上传图片<?php else: ?>查看图片<?php endif; ?></a>
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

<script>
    
    function js_ajax_update(obj, url, id, field, val, stock_able_number)
    {
        if (parseInt(stock_able_number) < 0) {
            if (isNaN(val) || val == '' || parseInt(val) < Math.abs(parseInt(stock_able_number))) {
                val = Math.abs(parseInt(stock_able_number));
            }
        } else if (parseInt(stock_able_number) > 0) {
            if (isNaN(val) || val == '' || parseInt(val) < 0) {
                val = 0;
            }
        }
        obj.value = val;
        ajax_update(url, id, field, val);
    }

</script>