<?php /* Smarty version 2.6.19, created on 2014-08-20 18:02:57
         compiled from transport/track.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'transport/track.tpl', 21, false),)), $this); ?>
<form name="myForm1" id="myForm1">
<input type="hidden" name="logistic_code" size="20" value="<?php echo $this->_tpl_vars['data']['logistic_code']; ?>
" />
<input type="hidden" name="bill_type" size="20" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
" />
<input type="hidden" name="area_id" size="20" value="<?php echo $this->_tpl_vars['data']['area_id']; ?>
" />
<input type="hidden" name="bill_no" size="20" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" />
<input type="hidden" name="logistic_no" size="20" value="<?php echo $this->_tpl_vars['data']['logistic_no']; ?>
" />
<div class="title">运输单跟踪</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>
        <?php $_from = $this->_tpl_vars['data']['bill_no_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bill_no'] => $this->_tpl_vars['batch_sn']):
?>
          <?php echo $this->_tpl_vars['bill_no']; ?>
 <?php if ($this->_tpl_vars['data']['bill_type'] == 1): ?><a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('controller'=>'order','action'=>"public-view",'batch_sn'=>$this->_tpl_vars['batch_sn'],)));?>','ajax','[<?php echo $this->_tpl_vars['bill_no']; ?>
]订单详情',750,450,true)"><font color="red"><b>[ 查看订单详情 ]</b></font></a><?php endif; ?><br>
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
 		<div id="addinfo2_<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" style="position:absolute; margin-top:62px;  ">
         	<span style="width:95px;height:30px;margin-right:70px;"><strong>省份</strong></span>
            <span style="width:95px;height:30px;margin-right:70px;"><?php echo $this->_tpl_vars['data']['province']; ?>
</span>
            <span style="width:95px;height:30px;margin-right:55px;"><strong>城市</strong></span>
            <span style="width:95px;height:30px;margin-right:240px;"><?php echo $this->_tpl_vars['data']['city']; ?>
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

    <tr >
      <td colspan="6">&nbsp;
      		
      </td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="5">&nbsp;<?php echo $this->_tpl_vars['data']['remark']; ?>
</td>
    </tr>
    <tr>
      <td><strong>物流公司</strong></td>
      <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
      <td><strong>运单号</strong></td>
      <td><?php echo $this->_tpl_vars['data']['logistic_no']; ?>
</td>
      <td><strong>配送状态</strong></td>
      <td><?php echo $this->_tpl_vars['logisticStatus'][$this->_tpl_vars['data']['logistic_status']]; ?>
 

      <?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name'] && $this->_tpl_vars['data']['logistic_status'] < 2): ?>
      <!--<input type="button" name="dosubmit1" value="重新派单" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'reassign',)));?>','ajax','重新派单')"/>-->
      <?php endif; ?>

      </td>
    </tr>
</tbody>
</table>

</div>

<?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name'] && $this->_tpl_vars['data']['logistic_status'] >= 1): ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
	 <tr>
	   <td><strong>维护说明</strong></td>
	   <td>
	     <textarea name="remark" style="width: 400px;height: 50px"></textarea>
	   </td>
	 </tr>
</tbody>
</table>

<div class="submit">
<?php $_from = $this->_tpl_vars['logisticStatus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['key'] > 0 && $this->_tpl_vars['key'] < 4): ?>
<input type="button" name="dosubmit<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['item']; ?>
" onclick="if(confirm('确认维护成[<?php echo $this->_tpl_vars['item']; ?>
]吗？')){ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array(array('logistic_status'=>$this->_tpl_vars['key'],)));?>');}"/>
 <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>

<div style="margin:10px;border:1px solid #ccc;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr><td width="50%"><strong>跟踪信息</strong></td></tr>
<tr><td valign="top">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="80">操作人</td>
      <td width="150">维护时间</td>
      <td width="80">维护状态</td>
      <td>维护说明</td>
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
</td></tr>
</table>
</div>

</form>