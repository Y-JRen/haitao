<?php /* Smarty version 2.6.19, created on 2014-08-20 18:01:44
         compiled from logic-area-out-stock/send-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'logic-area-out-stock/send-list.tpl', 17, false),array('modifier', 'date_format', 'logic-area-out-stock/send-list.tpl', 83, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm">
<div class="search">
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

商品编码：<input type="text" name="product_sn" size="12" maxLength="50" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['product_sn'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" />
商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" />
<div class="line">
<span style="float:left;line-height:18px;">开始日期：<input type="text" name="fromdate" id="fromdate" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['fromdate'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">结束日期：<input type="text" name="todate" id="todate" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['todate'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" class="Wdate" onClick="WdatePicker()" /></span>
<select name="bill_type">
<option value="">选择单据类型</option>
<?php $_from = $this->_tpl_vars['billType']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['bill_type'] == key): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<select name="bill_status">
  <option value="">选择单据状态</option>
  <option value="0">未审核</option>
  <option value="1">已审核</option>
  <option value="2">已拒绝</option>
  <option value="3">未确认</option>
  <option value="4">待发货</option>
  <option value="5">已发货</option>
</select>
</div>
<div class="line">
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['admin_name'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
"/>
单据编号：<input type="text" name="bill_no" size="20" maxLength="20" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['bill_no'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" />
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的出库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>yes,)));?>','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的出库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>no,)));?>','ajax_search')"/>

扫描单据编号：<input type="text" name="p_bill_no" size="20" maxLength="20" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['param']['p_bill_no'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
"  id="pfocus" onKeyDown="if(event.keyCode==13){openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'send',)));?>/bill_no/'+this.value,'ajax','查看单据',750,400)}"/>
</div>
</form>
<?php endif; ?>
<div id="ajax_search">
<div class="title">仓储管理 -&gt; <?php echo $this->_tpl_vars['area_name']; ?>
 -&gt; <?php echo $this->_tpl_vars['actions'][$this->_tpl_vars['action']]; ?>
</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
        	<td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>接收方</td>
            <td>单据类型</td>
            <td>制单人</td>
            <td>制单日期</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['outstock_id']; ?>
">
    	<td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['outstock_id']; ?>
"/></td>
        <td>
		  <input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['operates'][$this->_tpl_vars['action']],'id'=>$this->_tpl_vars['data']['outstock_id'],)));?>','ajax','发货操作',750,400)" value="发货">
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_no_str']; ?>
</td>
        <td><?php if ($this->_tpl_vars['areas'][$this->_tpl_vars['data']['recipient']]): ?><?php echo $this->_tpl_vars['areas'][$this->_tpl_vars['data']['recipient']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['recipient']; ?>
<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
<input type="hidden" name="info[<?php echo $this->_tpl_vars['data']['outstock_id']; ?>
][bill_type]" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
"></td>
        <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
</div>
</div>

<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
</div>