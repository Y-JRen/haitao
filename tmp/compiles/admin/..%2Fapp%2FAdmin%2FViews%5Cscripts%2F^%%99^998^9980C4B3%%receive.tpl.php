<?php /* Smarty version 2.6.19, created on 2014-08-13 09:32:34
         compiled from logic-area-in-stock/receive.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'logic-area-in-stock/receive.tpl', 28, false),array('modifier', 'replace', 'logic-area-in-stock/receive.tpl', 48, false),)), $this); ?>
<style type="text/css">
.input-text-focus{
	background-color:#FFFC00 !important;
}
input[type="text"]:focus{
	background-color:#FFFC00;
}
</style>
<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_no" size="20" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
" />
<input type="hidden" name="logic_area" value="<?php echo $this->_tpl_vars['logic_area']; ?>
">
<input type="hidden" name="bill_type" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
">
<input type="hidden" name="item_no" value="<?php echo $this->_tpl_vars['data']['item_no']; ?>
">
<input type="hidden" name="parent_id" value="<?php echo $this->_tpl_vars['data']['parent_id']; ?>
">
<input type="hidden" name="remark" id="remark"  value="<?php echo $this->_tpl_vars['data']['remark']; ?>
">
<div class="title">入库单收货</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
<?php if ($this->_tpl_vars['data']['bill_type'] == 2): ?>(<?php if ($this->_tpl_vars['data']['purchase_type'] == 1): ?>经销<?php else: ?>代销<?php endif; ?>)<?php endif; ?></td>
      <td width="12%"><strong>单据编号</strong></td>
      <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
    </tr>
    <tr>
      <td><strong>制单日期</strong></td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
      <td><strong>制单人</strong></td>
      <td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td>&nbsp;<?php echo $this->_tpl_vars['data']['remark']; ?>
</td>
      <td><strong>条码扫描</strong></td>
      <td>
        <input type="text" name="barcode" id="barcode" size="20" onkeydown="inputBarCode()">
      </td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table" id="imageTable">
<?php $_from = $this->_tpl_vars['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['d']):
?>
  <?php if ($this->_tpl_vars['d']['images']): ?>
    <tr id="image_<?php echo $this->_tpl_vars['d']['ean_barcode']; ?>
" style="display:none">
      <td>
        <?php $_from = $this->_tpl_vars['d']['images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['image']):
?>
        <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['image'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
">&nbsp;&nbsp;&nbsp;
        <?php endforeach; endif; unset($_from); ?>
      </td>
    </tr>
  <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
<thead>
<tr>
    <td>序号</td>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
    <td>应收数量</td>
    <td>已收数量</td>
    <td>本次应收</td>
    <td>本次实收</td>
    </tr>
</thead>
<tbody>
	<?php $_from = $this->_tpl_vars['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['d']):
?>
	<tr style="background-color:red">
	<td><?php echo $this->_tpl_vars['key']+1; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['product_sn']; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['d']['goods_style']; ?>
</font>)</td>
	<td>
	  <?php if ($this->_tpl_vars['data']['bill_type'] == 1 || $this->_tpl_vars['data']['bill_type'] == 10): ?>
	    <?php if ($this->_tpl_vars['batchInfo']): ?>
	    <select name="new_batch_id[<?php echo $this->_tpl_vars['d']['product_id']; ?>
]">
	    <?php $_from = $this->_tpl_vars['batchInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
	    <option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']; ?>
</option>
	    <?php endforeach; endif; unset($_from); ?>
	    </select>
	    <?php else: ?>
	    无批次<input type="hidden" name="new_batch_id[<?php echo $this->_tpl_vars['d']['product_id']; ?>
]" value="0">
	    <?php endif; ?>
	  <?php else: ?>
	    <?php if ($this->_tpl_vars['d']['batch_no']): ?><?php echo $this->_tpl_vars['d']['batch_no']; ?>
<?php else: ?>无批次<?php endif; ?>
	  <?php endif; ?>
	  <input type="hidden" name="batch_id[<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
]" value="<?php if ($this->_tpl_vars['d']['batch_id']): ?><?php echo $this->_tpl_vars['d']['batch_id']; ?>
<?php else: ?>0<?php endif; ?>">
	</td>
	<td><?php echo $this->_tpl_vars['d']['p_plan_number']; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['p_real_number']; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['plan_number']; ?>
</td>
	<td>
	<div id="status<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
">
	<p id="operation_<?php echo $this->_tpl_vars['d']['ean_barcode']; ?>
" <?php if ($this->_tpl_vars['data']['bill_type'] == 2): ?>style="display:none"<?php endif; ?>>
	<select name="status[<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
][]">
	<?php $_from = $this->_tpl_vars['status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['sname']):
?>
	<?php if (! $this->_tpl_vars['d']['is_vitual'] || $this->_tpl_vars['key'] == 6 || $this->_tpl_vars['data']['bill_type'] != 1): ?>
	  <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['d']['status_id'] != 1 && $this->_tpl_vars['key'] == $this->_tpl_vars['d']['status_id']): ?>selected<?php elseif ($this->_tpl_vars['d']['status_id'] == 1 && $this->_tpl_vars['key'] == 2): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['sname']; ?>
</option>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	</select>
	<input type="hidden" name="old_status[<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
]" value="<?php echo $this->_tpl_vars['d']['status_id']; ?>
">
	<input type="hidden" name="price[<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
]" value="<?php echo $this->_tpl_vars['d']['shop_price']; ?>
">
	<input type="text" size="2" name="number[<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
][]" id="number<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
" <?php if ($this->_tpl_vars['d']['is_vitual']): ?>value="<?php echo $this->_tpl_vars['d']['plan_number']; ?>
" readonly<?php else: ?>value="0"<?php endif; ?> onchange="doSetNumber(this)"> <?php if (! $this->_tpl_vars['d']['is_vitual']): ?><a onclick="addRow(this,'status<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
')" href="javascript:fGo();">[ 增加 ]</a><?php endif; ?>
	<input type="hidden" name="plan_number[<?php echo $this->_tpl_vars['d']['product_id']; ?>
_<?php echo $this->_tpl_vars['d']['batch_id']; ?>
]" value="<?php echo $this->_tpl_vars['d']['plan_number']; ?>
">
	</p>
	</div>
	</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>

<div style="text-align:right;padding:10px 20px"><strong>本次应数合计：</strong><?php echo $this->_tpl_vars['data']['total_number']; ?>

&nbsp;&nbsp;&nbsp;&nbsp;
<strong>本次实数合计：</strong><input type="text" size="5" name="total_number" id="total_number" value="0" readonly>
</div>
</div>
<?php if ($this->_tpl_vars['data']['bill_type'] == 0): ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>入库单号</strong></td>
      <td><input type="text" name="paper_no" id="paper_no" style="width:70%"> (多个单号空格分割)</td>
    </tr>
</tbody>
</table>
<?php endif; ?>

<div class="submit" id="receiveButton" <?php if ($this->_tpl_vars['data']['bill_type'] == 2): ?>style="display:none"<?php endif; ?>>
<?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>



<table id="recipient_checkbox">
<tr>
  <td width="150"  height="30">是否填写运输单：</td>
  <td><input type="checkbox" id="check_recipient" name="check_recipient" value="1"   onclick="checkrecipient()" /></td>
</tr>
</table>


<div id="recipient_span" style="display:none">
<table cellpadding="0" cellspacing="0" border="0" width="80%" style="text-align:center;">
<tbody>
    <tr align="center">
	<td width="25%"></td>
	<td width="20%"><strong>物流公司</strong></td>
	<td width="20%">
	<select name="logistic_code" id="logistic_code" >
	    <option value="other" > 其他   </option>
		<option value="sf" > 顺丰   </option>
		<option value="zjs" > 宅急送    </option>
		<option value="ems" > EMS     </option>
	</select>
	</td>
      <td width="20%"><strong>物流单号</strong></td>
      <td><input type="text" name="logistic_no" id="logistic_no"  size="35" ></td>
    </tr>
</tbody>
</table>
</div>


<div id="remarkArea" style="text-align:center;display:none; margin-top: 20px;">
备注：<input type="text" name="new_remark" id="new_remark" size="100"><br><br>
</div>
<input type="button" name="dosubmit1" id="dosubmit1" value="收货" onclick="dosubmit()"/>
<?php endif; ?>
</div>
<div style="width:1px" id="fo" onkeydown="closeDiv()"></div>
</form>
<script  language="javascript">

function checkrecipient(){
	var check_recipient = $('check_recipient').checked;
	if(check_recipient=='1'){
		$('recipient_span').setStyle('display','inline-block');
	}else{
		$('recipient_span').setStyle('display','none');
	}
}

function dosubmit()
{
	<?php if ($this->_tpl_vars['financeLimit']): ?>
	alert('客服先开退款单，仓库才可以收货');
	return;
	<?php endif; ?>
	
	if(confirm('确认收货吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');
	}
}

function failed()
{
	$('dosubmit1').value = '收货';
	$('dosubmit1').disabled = false;
}

function doSetNumber(obj)
{
    var num = obj.value.trim();
    if (isNaN(num)){
        alert('请输入数字');
        obj.value = 0;
    }
    numberCount();
}

function numberCount()
{
	var objSels = $('table').getElements('input[id^=number]');
	var totalNumber = 0;
	for ( var i = 0; i < objSels.length; i ++ )
	{
		totalNumber += parseInt ( objSels[i].value );
	}
	$('total_number').value = totalNumber;
	
	if (<?php echo $this->_tpl_vars['data']['total_number']; ?>
 > totalNumber) {
	    $('remarkArea').style.display = '';
	}
	else {
	    $('remarkArea').style.display = 'none';
	}
}

function addRow(obj, div)
{
    var p = document.createElement("p");
	p.innerHTML = obj.parentNode.innerHTML;
	p.innerHTML = p.innerHTML.replace(/(.*)(addRow)(.*)(\[ )(增加)/i, "$1removeRow$3$4删除");
	var inputs = p.getElementsByTagName('input');
	inputs[2].value = 0;
	$(div).appendChild(p);
}

function removeRow(obj, div)
{
    $(div).removeChild(obj.parentNode);
}

function inputBarCode()
{
    var e = getEvent();
    var value = $('barcode').value;
    if (e.keyCode == 13 && value != '') {
        $('barcode').value = '';
        obj = $('operation_' + value);
        if (obj == null) {
            hint('error barcode', $('barcode'));
        }
        else {
            if (obj.style.display == 'none') {
                var inputs = obj.getElements('input');
                for (var i = 0; i < inputs.length; i++) {
                    if (inputs[i].name.substring(0, 11) == 'plan_number') {
                        var nn = document.getElementsByName('number' + inputs[i].name.substring(11, 20) + '[]');
                        nn[0].value = inputs[i].value;
                        break;
                    }
                }
                
                if ($('image_' + value) != null) {
                    hideImage();
                    $('image_' + value).style.display = '';
                }
                
                obj.style.display = '';
                $('receiveButton').style.display = '';
                numberCount();
            }
        }
    }
}

function getEvent()
{  
    if (document.all)   return window.event;    
    func = getEvent.caller;
    while(func != null) {
        var arg0 = func.arguments[0]; 
        if (arg0) { 
            if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {  
                return arg0; 
            } 
        } 
        func = func.caller; 
    }
    
    return null; 
}

function hint(msg, obj)
{
    if (msg == 'error barcode') {
        msg = '产品条码不存在!';
    }
    
    focusObject = obj;
    $('fo').focus();
    
    window.top.alertBox.init('msg="' + msg + '",url="",ms="","";');
}

function closeDiv()
{
    var e = getEvent();
    if (e.keyCode == 32) {
        window.top.alertBox.closeDiv();
        if (focusObject != null) {
            focusObject.focus();
        }
    }
    e.returnValue = false;
}

function hideImage()
{
    var trs = $('imageTable').getElements('tr');
    if (trs.length > 0) {
        for (var i = 0; i < trs.length; i++) {
            trs[i].style.display = 'none';
        }
    }
}

setTimeout(function(){$('barcode').focus();}, 500);
setTimeout(function(){numberCount()}, 500);

var focusObject = null;

</script>