<?php /* Smarty version 2.6.19, created on 2014-08-21 14:23:04
         compiled from transport/assign.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'transport/assign.tpl', 22, false),)), $this); ?>
<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_type" size="20" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
" />
<input type="hidden" name="bill_no" size="20" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" />
<div class="title">物流派单</div>
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
      <td width="12%"></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>制单日期</strong></td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
      <td><strong>制单人</strong></td>
      <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
      <td></td>
      <td></td>
    </tr>
    <tr >
      <td colspan="6">&nbsp;
      		
      </td>
    </tr>
      <tr id="adddiv_<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" >
      <td colspan="6">
      	
      </td>
    </tr>
 		<div id="addinfo2_<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" style="position:absolute; margin-top:62px;  ">
         	<span style="width:95px;height:30px;margin-right:70px;"><strong>省份</strong></span>
            <span style="width:95px;height:30px;margin-right:55px;"><?php echo $this->_tpl_vars['data']['province']; ?>
</span>
            <span style="width:95px;height:30px;margin-right:55px;"><strong>城市</strong></span>
            <span style="width:95px;height:30px;margin-right:225px;"><?php echo $this->_tpl_vars['data']['city']; ?>
</span>
            <span style="width:95px;height:30px;margin-right:30px;"><strong>地区</strong></span>
            <span style="width:95px;height:30px;"><?php echo $this->_tpl_vars['data']['area']; ?>
</span>
        </div>
		<div id="addinfo_<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" style="position:absolute; margin-top:92px; ">
         	<span style="width:95px;height:30px;margin-right:30px;"><strong>详细地址</strong></span>
     		<span><?php echo $this->_tpl_vars['data']['address']; ?>
</span>
        </div>

    <tr >
      <td colspan="6">&nbsp;
      		
      </td>
    </tr>
    <tr>
      <td><strong>配送方式</strong></td>
      <td><?php if ($this->_tpl_vars['data']['logistic_code'] != 'ems'): ?>快递<?php else: ?>EMS<?php endif; ?></td>
      <td><strong><?php if ($this->_tpl_vars['data']['is_cod']): ?>应收金额<?php else: ?>订单金额<?php endif; ?></strong></td>
      <td><?php echo $this->_tpl_vars['data']['amount']; ?>
</td>
      <td><strong>商品数量</strong></td>
      <td><?php echo $this->_tpl_vars['data']['goods_number']; ?>
</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="5">&nbsp;<?php echo $this->_tpl_vars['data']['remark']; ?>
</td>
    </tr>
    <tr>
      <td><strong>重量</strong></td>
      <td><?php echo $this->_tpl_vars['data']['weight']; ?>
 </td>
      <td><strong>体积</strong></td>
      <td><?php echo $this->_tpl_vars['data']['volume']; ?>
</td>
      <td><strong>件数</strong></td>
      <td><input type="text" name="number" size="6" maxlength="6" value="1" /></td>
    </tr>
    <tr>
      <td><strong>付款方式</strong></td>
      <td><?php if ($this->_tpl_vars['data']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
      <td><strong>承运商</strong></td>
      <td colspan="3">
      <select name="logistic">
		<?php echo $this->_tpl_vars['logisticList']; ?>

	  </select>
	  </td>
    </tr>
</tbody>
</table>

</div>

<div class="submit">
<?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
<input type="button" name="dosubmit1" id="dosubmit1" value="确认派单" onclick="dosubmit()"/>
<?php endif; ?>
</div>
</form>
<script>
function dosubmit()
{
	if(confirm('确认派单吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');
	}
}

function failed()
{
	$('dosubmit1').value = '确认派单';
	$('dosubmit1').disabled = false;
}

</script>