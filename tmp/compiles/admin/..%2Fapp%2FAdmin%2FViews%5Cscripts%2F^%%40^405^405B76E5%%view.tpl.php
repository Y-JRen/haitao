<?php /* Smarty version 2.6.19, created on 2014-08-21 14:31:37
         compiled from transport/view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'transport/view.tpl', 15, false),)), $this); ?>
<div class="title" >查看详情</div>
<div class="content" style="position:relative;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" >
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td width="34%">
        <?php $_from = $this->_tpl_vars['data']['bill_no_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bill_no'] => $this->_tpl_vars['batch_sn']):
?>
          <?php echo $this->_tpl_vars['bill_no']; ?>
 <?php if ($this->_tpl_vars['data']['bill_type'] == 1): ?><a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('controller'=>'order','action'=>"public-view",'batch_sn'=>$this->_tpl_vars['batch_sn'],)));?>','ajax','[<?php echo $this->_tpl_vars['bill_no']; ?>
]订单详情',750,450,true)"><font color="red"><b>[ 查订单详情 ]</b></font></a><?php endif; ?><br>
        <?php endforeach; endif; unset($_from); ?>
      </td>
      <td width="12%"><strong>制单日期</strong></td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
    </tr>
    <tr>
      <td width="12%"><strong>付款方式</strong></td>
      <td><?php if ($this->_tpl_vars['data']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
      <td width="12%"><strong>配送方式</strong></td>
      <td><?php if ($this->_tpl_vars['data']['logistic_code'] != 'ems'): ?>快递<?php else: ?>EMS<?php endif; ?></td>
      <td width="12%"><strong>订单金额</strong></td>
      <td><?php echo $this->_tpl_vars['data']['amount']; ?>
</td>
    </tr>
    <tr >
 		<div id="addinfo2_<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" style="position:absolute; margin-top:62px; ">
         	<span style="width:95px;height:30px;margin-right:70px;"><strong>省份</strong></span>
            <span style="width:95px;height:30px;margin-right:90px;"><?php echo $this->_tpl_vars['data']['province']; ?>
</span>
            <span style="width:95px;height:30px;margin-right:55px;"><strong>城市</strong></span>
            <span style="width:95px;height:30px;margin-right:150px;"><?php echo $this->_tpl_vars['data']['city']; ?>
</span>
            <span style="width:95px;height:30px;margin-right:60px;"><strong>地区</strong></span>
            <span style="width:95px;height:30px;"><?php echo $this->_tpl_vars['data']['area']; ?>
</span>
        </div>
		<div id="addinfo_<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" style="position:absolute; margin-top:92px;">
         	<span style="width:95px;height:30px;margin-right:30px;"><strong>详细地址</strong></span>
     		<span><?php echo $this->_tpl_vars['data']['address']; ?>
</span>
        </div>
	</tr>
    <tr >
      <td colspan="6">&nbsp;
      		
      </td>
    </tr>
    
    <?php if ($this->_tpl_vars['data']['validate_sn']): ?>
    <tr>
      <td width="80"><strong>验证码</strong></td>
      <td colspan="5" width="280"><?php echo $this->_tpl_vars['data']['validate_sn']; ?>
</td>
    </tr>
    <?php endif; ?>
    <tr>
      <td width="80"><strong>备注</strong></td>
      <td colspan="5" width="280">&nbsp;<?php echo $this->_tpl_vars['data']['remark']; ?>
</td>
    </tr>
    <tr>
      <td  width="80"><strong>物流公司</strong></td>
      <td  width="80"><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
      <td  width="80"><strong>运单号</strong></td>
      <td  width="80"><?php echo $this->_tpl_vars['data']['logistic_no']; ?>
</td>
      <td  width="80"><strong>配送状态</strong></td>
      <td  width="80"><?php echo $this->_tpl_vars['logisticStatus'][$this->_tpl_vars['data']['logistic_status']]; ?>
</td>
    </tr>
</tbody>
</table>

<?php if ($this->_tpl_vars['op']): ?>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
<tr>
    <td>操作时间</td>
    <td>操作人</td>
    <td>操作内容</td>
    <td>备注</td>
    </tr>
</thead>
<tbody>
	<?php $_from = $this->_tpl_vars['op']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['d']):
?>
	<tr>
	<td width="150"><?php echo ((is_array($_tmp=$this->_tpl_vars['d']['op_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['admin_name']; ?>
</td>
	<td><?php if ($this->_tpl_vars['d']['op_type'] == 'assign'): ?>
	物流派单
	<?php elseif ($this->_tpl_vars['d']['op_type'] == 'confirm'): ?>
	运输单确认
	<?php elseif ($this->_tpl_vars['d']['op_type'] == 'prepare'): ?>
	仓库配库
	<?php endif; ?>
	<?php echo $this->_tpl_vars['d']['item_value']; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['remark']; ?>
</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['tracks']): ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tr>
      <td width="80"><strong>操作人</strong></td>
      <td width="150"><strong>维护时间</strong></td>
      <td width="80"><strong>维护状态</strong></td>
      <td><strong>维护说明</strong></td>
    </tr>
<?php $_from = $this->_tpl_vars['tracks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
    <tr>
      <td><?php echo $this->_tpl_vars['t']['admin_name']; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['t']['op_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
      <td><?php echo $this->_tpl_vars['logisticStatus'][$this->_tpl_vars['t']['logistic_status']]; ?>
</td>
      <td><?php echo $this->_tpl_vars['t']['remark']; ?>
</td>
    </tr>
<?php endforeach; endif; unset($_from); ?>
</table>

<?php endif; ?>
</div>

<div class="submit">
<input type="button" onclick="window.open('<?php echo $this -> callViewHelper('url', array(array('action'=>'print2','id'=>$this->_tpl_vars['data']['tid'],'is_cod'=>$this->_tpl_vars['data']['is_cod'],'logistic_code'=>$this->_tpl_vars['data']['logistic_code'],)));?>')" value="打印运输单">
<?php if ($this->_tpl_vars['data']['bill_type'] == 1): ?>
<input type="button" onclick="window.open('/admin/logic-area-out-stock/print/bill_no/<?php echo $this->_tpl_vars['data']['bill_no']; ?>
')" value="打印销售单">
<?php endif; ?>
</div>