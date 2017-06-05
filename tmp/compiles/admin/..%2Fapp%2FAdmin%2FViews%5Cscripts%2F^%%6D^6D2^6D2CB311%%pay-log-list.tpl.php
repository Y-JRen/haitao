<?php /* Smarty version 2.6.19, created on 2014-09-01 14:37:23
         compiled from finance/pay-log-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'finance/pay-log-list.tpl', 41, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>

<div class="title">支付LOG查看</div>
<div class="search">
<form name="searchForm" id="searchForm" >
 开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>

支付方式：<select name="pay_type" id="pay_type">
          <option value="" >请选择 </option>
            <?php $_from = $this->_tpl_vars['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payment']):
?>
             <option value="<?php echo $this->_tpl_vars['payment']['pay_type']; ?>
" <?php if ($this->_tpl_vars['param']['pay_type'] == $this->_tpl_vars['payment']['pay_type']): ?>selected<?php endif; ?>>  <?php echo $this->_tpl_vars['payment']['name']; ?>
 </option>
            <?php endforeach; endif; unset($_from); ?>
		</select>
订单号码：<input type="text" name="batch_sn" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['batch_sn']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
</form>
</div>


<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td >支付流水 ID</td>
            <td >订单号</td>
            <td>支付代码</td>
            <td>支付方式</td>
            <td>支付金额</td>
            <td>支付时间</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['data']['supplier_id']; ?>
">
            <td><?php echo $this->_tpl_vars['data']['pay_log_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['data']['batch_sn']; ?>
</td>
            <td><?php echo $this->_tpl_vars['data']['pay_type']; ?>
</td>
            <td><?php echo $this->_tpl_vars['payment_list'][$this->_tpl_vars['data']['pay_type']]['name']; ?>
 </td>
            <td><?php echo $this->_tpl_vars['data']['pay']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>