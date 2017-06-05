<?php /* Smarty version 2.6.19, created on 2015-01-05 16:50:33
         compiled from product/assemble-check-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'product/assemble-check-list.tpl', 6, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm">
<div class="search">

制单开始日期：<input type="text" name="start_ts" id="start_ts" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['start_ts'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
"  class="Wdate" onClick="WdatePicker()"/>
制单结束日期：<input type="text" name="end_ts" id="end_ts" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['end_ts'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" class="Wdate" onClick="WdatePicker()"/>
审核开始日期：<input type="text" name="audit_start_ts" id="audit_start_ts" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['audit_start_ts'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" class="Wdate" onClick="WdatePicker()"/>
审核结束日期：<input type="text" name="audit_end_ts" id="audit_end_ts" size="11" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['audit_end_ts'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" class="Wdate" onClick="WdatePicker()"/>
</div>
<div class="line">
<select name="type">
  <option value="">请选择类型</option>
  <?php $_from = $this->_tpl_vars['search_option']['assemble_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['type']):
?>
  <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['params']['type'] != '' && $this->_tpl_vars['params']['type'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['type']; ?>
</option>
  <?php endforeach; endif; unset($_from); ?>
</select>
组装单编号：<input type="text" name="assemble_sn" size="20" maxLength="20" value="<?php echo $this->_tpl_vars['params']['assemble_sn']; ?>
"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('search'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的出库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('search'=>'search1','lock'=>1,)));?>','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的出库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('search'=>'search1','lock'=>0,)));?>','ajax_search')"/>
</div>
</form>
<?php endif; ?>
<div id="ajax_search">
<div class="title">组装开单管理  -&gt; 组装开单审核</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"lock-assemble",)));?>/is_lock/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"lock-assemble",)));?>/is_lock/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单号</td>
            <td>类型</td>
            <td>状态</td>
            <td>生成时间</td>
            <td>审核时间</td>
            <td>备注</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['info']['assemble_id']; ?>
">
            <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['info']['assemble_id']; ?>
"/></td>
            <td>
                <input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>"assemble-check",'assemble_id'=>$this->_tpl_vars['info']['assemble_id'],)));?>','ajax','查看单据',750,400)" value="查看">
            </td>
            <td><?php echo $this->_tpl_vars['info']['assemble_sn']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['type_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['status_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['created_ts']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['audit_ts']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['remark']; ?>
</td>
            <td><?php if ($this->_tpl_vars['info']['locked_by'] != ''): ?>已被<font color="red"><?php echo $this->_tpl_vars['info']['locked_by']; ?>
</font>锁定<?php else: ?>未锁定<?php endif; ?></td>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"lock-assemble",)));?>/is_lock/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"lock-assemble",)));?>/is_lock/0','Gurl(\'refresh\',\'ajax_search\')')"></div>

<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
</div>