<?php /* Smarty version 2.6.19, created on 2014-12-31 10:22:35
         compiled from cost/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'cost/list.tpl', 60, false),)), $this); ?>
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

<div class="line">
<span style="float:left;line-height:18px;">开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"   class="Wdate" onClick="WdatePicker()" /></span>
<span style="float:left;line-height:18px;">结束日期：<input type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
<select name="bill_status">
<option value="">选择单据状态</option>
<option value="0" <?php if ($this->_tpl_vars['param']['bill_status'] == '0'): ?>selected<?php endif; ?>>未审核</option>
<option value="1" <?php if ($this->_tpl_vars['param']['bill_status'] == '1'): ?>selected<?php endif; ?> >已审核</option>
<option value="2" <?php if ($this->_tpl_vars['param']['bill_status'] == '2'): ?>selected<?php endif; ?> >已拒绝</option>
</select>
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['admin_name']; ?>
" />
单据编号：<input type="text" name="bill_no" size="20" maxLength="20" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
" />
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
</div>
</form>
<?php endif; ?>
<div id="ajax_search">
<div class="title">商品管理 -&gt; <?php echo $this->_tpl_vars['area_name']; ?>
 -&gt; 商品成本管理 -&gt;调整成本查询</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>制单人</td>
            <td>制单日期</td>
            <td>单据状态</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['sid']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['bill_id']; ?>
"/></td>
        <td>
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'checkdetail','id'=>$this->_tpl_vars['data']['bill_id'],'flag'=>1,)));?>','ajax','查看单据',750,400)" value="查看">
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
        <td>
            <?php if ($this->_tpl_vars['data']['stauts'] == 0): ?>
            	未审核
            <?php elseif ($this->_tpl_vars['data']['stauts'] == 1): ?>
            	已审核
            <?php else: ?>
          		已拒绝
            <?php endif; ?>
        </td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
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