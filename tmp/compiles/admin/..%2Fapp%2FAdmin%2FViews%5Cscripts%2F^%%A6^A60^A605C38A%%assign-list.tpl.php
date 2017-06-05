<?php /* Smarty version 2.6.19, created on 2014-08-20 18:00:49
         compiled from transport/assign-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'transport/assign-list.tpl', 9, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" method="get">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['billType'],'selected' => $this->_tpl_vars['param']['bill_type']), $this);?>

</select>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => $this->_tpl_vars['param']['logistic_code']), $this);?>

</select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" <?php if ($this->_tpl_vars['param']['is_cod'] == '0'): ?>selected<?php endif; ?>>非货到付款</option><option value="1" <?php if ($this->_tpl_vars['param']['is_cod'] == '1'): ?>selected<?php endif; ?>>货到付款</option></select>
匹配类型：<select name="search_mod"><option value="">请选择</option><option value="排除法" <?php if ($this->_tpl_vars['param']['search_mod'] == '排除法'): ?>selected<?php endif; ?>>排除法</option><option value="匹配法" <?php if ($this->_tpl_vars['param']['search_mod'] == '匹配法'): ?>selected<?php endif; ?>>匹配法</option></select>
<div class="line">
店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shop']):
?>
      <option value="<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
" <?php if ($this->_tpl_vars['shop']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['shop']['shop_name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
  </select>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['consignee']; ?>
"/>
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['admin_name']; ?>
"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('is_lock'=>yes,)));?>'+location.search)"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('is_lock'=>no,)));?>'+location.search)"/>
</div>
</form>
<div class="title">配送管理 -&gt; 物流派单</div>
<form name="myForm" id="myForm">
<div class="content">
	<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="确认派单" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'assigns',)));?>','Gurl(\'refresh\')')">
	<input type="button" value="返回配货" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"prepare-return",)));?>','Gurl(\'refresh\')')">
	</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>店铺</td>
            <td>单据类型</td>
            <!--<td>重量(kg)</td>-->
            <td>地区</td>
            <td>付款方式</td>
            <td>配送方式</td>
            <td>是否锁定</td>
            <td>件数</td>
            <td>承运商</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['tid']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['tid']; ?>
"/></td>
        <td>
	      <input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'assign','id'=>$this->_tpl_vars['data']['tid'],)));?>','ajax','查看单据')" value="查看">
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_no_str']; ?>
<?php if ($this->_tpl_vars['data']['remark']): ?><br><b><?php echo $this->_tpl_vars['data']['remark']; ?>
</b><?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
        <!--<td><?php echo $this->_tpl_vars['data']['weight']; ?>
</td>-->
        <td><?php echo $this->_tpl_vars['data']['province']; ?>
<?php echo $this->_tpl_vars['data']['city']; ?>
<?php echo $this->_tpl_vars['data']['area']; ?>
(<?php echo $this->_tpl_vars['data']['address']; ?>
)</td>
        <td><?php if ($this->_tpl_vars['data']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['data']['logistic_code'] != 'ems'): ?>快递<?php else: ?>EMS<?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
        <td><input type="text" name="info[<?php echo $this->_tpl_vars['data']['tid']; ?>
][number]" size="6" maxlength="6" value="1" /></td>
        <td><input type="hidden" name="info[<?php echo $this->_tpl_vars['data']['tid']; ?>
][bill_type]" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
">
        <input type="hidden" name="info[<?php echo $this->_tpl_vars['data']['tid']; ?>
][bill_no]" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
">
        <select name="info[<?php echo $this->_tpl_vars['data']['tid']; ?>
][logistic]">
			<?php echo $this->_tpl_vars['data']['logisticList']; ?>

		   </select>
	    </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="确认派单" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'assigns',)));?>','Gurl(\'refresh\')')">
<input type="button" value="返回配货" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"prepare-return",)));?>','Gurl(\'refresh\')')">
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>