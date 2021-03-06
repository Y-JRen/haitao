<?php /* Smarty version 2.6.19, created on 2014-09-04 10:29:49
         compiled from logic-area-allocation/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'logic-area-allocation/list.tpl', 58, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
<?php echo $this->_tpl_vars['catSelect']; ?>

商品编码：<input type="text" name="product_sn" size="12" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['fromdate']; ?>
"   class="Wdate" onClick="WdatePicker()" />
结束日期：<input type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['todate']; ?>
" class="Wdate" onClick="WdatePicker()" />
<div class="line">
<select name="bill_status">
<option value="">选择单据状态</option>
<option value="is_check_0">未审核</option>
<option value="is_check_1">已审核</option>
<option value="is_confirm_0">未确认</option>
<option value="is_confirm_1">已确认</option>
<option value="is_send_0">待发货</option>
<option value="is_send_1">已发货</option>
<option value="is_receive_0">待收货</option>
<option value="is_receive_1">已收货</option>
</select>
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value=""/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="30" value=""/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>yes,)));?>','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>no,)));?>','ajax_search')"/>
</form>
</div>
<?php endif; ?>
<div id="ajax_search">
<div class="title">仓储管理 -&gt; <?php echo $this->_tpl_vars['area_name']; ?>
 -&gt; 调拨单管理 -&gt;<?php echo $this->_tpl_vars['actions'][$this->_tpl_vars['action']]; ?>
</div>
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
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['aid']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['aid']; ?>
"/></td>
        <td>
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['operates'][$this->_tpl_vars['action']],'id'=>$this->_tpl_vars['data']['aid'],)));?>','ajax','查看单据',750,400)" value="查看">
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
        <td>
            <?php if ($this->_tpl_vars['data']['is_cancel'] == 1): ?>
            待取消
            <?php else: ?>
            <?php echo $this->_tpl_vars['billStatus'][$this->_tpl_vars['data']['bill_status']]; ?>

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