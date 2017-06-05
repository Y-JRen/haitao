<?php /* Smarty version 2.6.19, created on 2014-08-13 09:32:10
         compiled from logic-area-in-stock/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'logic-area-in-stock/list.tpl', 20, false),array('modifier', 'date_format', 'logic-area-in-stock/list.tpl', 107, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
<?php if ($this->_tpl_vars['logic_area'] > 20): ?>
代销仓：
<select name="logic_area" id="logic_area" onchange="ajax_search($('searchForm'), '<?php echo $this -> callViewHelper('url', array());?>', 'ajax_search')">
  <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
    <?php if ($this->_tpl_vars['key'] > 20): ?>
    <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['logic_area']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</select>
&nbsp;&nbsp;
<?php endif; ?>
<?php echo $this->_tpl_vars['catSelect']; ?>

商品编码：<input type="text" name="product_sn" size="12" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
<div class="line">
制单开始日期：<input type="text" name="fromdate" id="fromdate" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['fromdate'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
"  class="Wdate" onClick="WdatePicker()"/>
制单结束日期：<input type="text" name="todate" id="todate" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['todate'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" class="Wdate" onClick="WdatePicker()"/>
入库开始日期：<input type="text" name="fromdate2" id="fromdate2" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['fromdate2'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" class="Wdate" onClick="WdatePicker()"/>
入库结束日期：<input type="text" name="todate2" id="todate2" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['todate2'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" class="Wdate" onClick="WdatePicker()"/>
</div>
<div class="line">
<select name="bill_type">
<option value="">选择单据类型</option>
  <?php if ($this->_tpl_vars['billType']): ?>
  <?php $_from = $this->_tpl_vars['billType']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['bill_type'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
</select>
<select name="bill_status">
  <option value="">选择单据状态</option>
  <?php if ($this->_tpl_vars['billStatus']): ?>
  <?php $_from = $this->_tpl_vars['billStatus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['bill_status'] != '' && $this->_tpl_vars['param']['bill_status'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
</select>

 供货商:  <select name="supplier_id" msg="请选择供货商" class="required" id="supplier_id" >
             <option value="">请选择...</option>
		  <?php $_from = $this->_tpl_vars['supplier']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s']):
?>
			  <option value="<?php echo $this->_tpl_vars['s']['supplier_id']; ?>
"    <?php if ($this->_tpl_vars['param']['supplier_id'] == $this->_tpl_vars['s']['supplier_id']): ?>selected<?php endif; ?>    ><?php echo $this->_tpl_vars['s']['supplier_name']; ?>
</option>
		  <?php endforeach; endif; unset($_from); ?>
		  </select>

制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['admin_name']; ?>
"/>
单据编号：<input type="text" name="bill_no" size="20" maxLength="20" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
接收者：<input type="text" name="recipient" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['recipient']; ?>
"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>yes,)));?>','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>no,)));?>','ajax_search')"/>
</div>
</form>
<?php endif; ?>
<div id="ajax_search">
<div class="title">仓储管理 -&gt; <?php echo $this->_tpl_vars['area_name']; ?>
 -&gt; <?php echo $this->_tpl_vars['actions'][$this->_tpl_vars['action']]; ?>
</div>
<form name="myForm" id="myForm">
<div class="content">
   <?php if ($this->_tpl_vars['auth']['group_id'] == 1 || $this->_tpl_vars['auth']['group_id'] == 3 || $this->_tpl_vars['auth']['group_id'] == 10 || $this->_tpl_vars['auth']['group_id'] == 11): ?>
    <div style="text-align:right;">
    <b>总金额：<?php echo $this->_tpl_vars['sum']['amount']; ?>
&nbsp;&nbsp;&nbsp;总金额(未税)：<?php echo $this->_tpl_vars['sum']['no_tax_amount']; ?>
&nbsp;&nbsp;&nbsp;实收金额：<?php echo $this->_tpl_vars['sum']['real_amount']; ?>
&nbsp;&nbsp;&nbsp;实收金额(未税)：<?php echo $this->_tpl_vars['sum']['real_no_tax_amount']; ?>
&nbsp;&nbsp;&nbsp;</b>
    </div>
    <?php endif; ?>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>单据类型</td>
			<td>供应商</td>
            <td>接收方</td>
            <td>制单人</td>
            <td>制单日期</td>
            <td>入库日期</td>
            <td>单据状态</td>
            <td>是否锁定</td>
            <td>备注</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['instock_id']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['instock_id']; ?>
"/></td>
        <td>
			<?php if ($this->_tpl_vars['action'] == 'setover-list'): ?>
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'setover','id'=>$this->_tpl_vars['data']['instock_id'],)));?>','ajax','查看单据',750,400)" value="查看">
			<?php else: ?>
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['operates'][$this->_tpl_vars['action']],'id'=>$this->_tpl_vars['data']['instock_id'],)));?>','ajax','查看单据',850,450)" value="查看">
			<?php endif; ?>
        </td>
        <td>
          <?php echo $this->_tpl_vars['data']['bill_no']; ?>

          <?php if ($this->_tpl_vars['data']['item_no']): ?><br>(<?php echo $this->_tpl_vars['data']['item_no']; ?>
)<?php endif; ?>
        </td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
<?php if ($this->_tpl_vars['data']['bill_type'] == 2): ?>(<?php if ($this->_tpl_vars['data']['purchase_type'] == 1): ?>经销<?php else: ?>代销<?php endif; ?>)<?php endif; ?></td>
	    <td><?php echo $this->_tpl_vars['data']['supplier_name']; ?>
</td>
        <td><?php if ($this->_tpl_vars['areas'][$this->_tpl_vars['data']['recipient']]): ?><?php echo $this->_tpl_vars['areas'][$this->_tpl_vars['data']['recipient']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['recipient']; ?>
<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['finish_time']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['finish_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
<?php endif; ?></td>
        <td>
            <?php if ($this->_tpl_vars['data']['is_cancel'] == 1): ?>
            待取消
            <?php else: ?>
            <?php echo $this->_tpl_vars['billStatus'][$this->_tpl_vars['data']['bill_status']]; ?>

            <?php endif; ?>
        </td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
        <td> <textarea name="" cols="18" rows="2"> <?php echo $this->_tpl_vars['data']['remark']; ?>
 </textarea></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>

<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
</div>