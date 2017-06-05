<style type="text/css">
.input-text-focus{
	background-color:#FFFC00 !important;
}
input[type="text"]:focus{
	background-color:#FFFC00;
}
</style>
<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<input type="hidden" name="logic_area" value="{{$logic_area}}">
<input type="hidden" name="bill_type" value="{{$data.bill_type}}">
<input type="hidden" name="item_no" value="{{$data.item_no}}">
<input type="hidden" name="parent_id" value="{{$data.parent_id}}">
<input type="hidden" name="remark" id="remark"  value="{{$data.remark}}">
<div class="title">入库单收货</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}{{if $data.bill_type eq 2}}({{if $data.purchase_type eq 1}}经销{{else}}代销{{/if}}){{/if}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>{{$data.bill_no}}</td>
    </tr>
    <tr>
      <td><strong>制单日期</strong></td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong></td>
      <td>{{$data.admin_name}}</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td>&nbsp;{{$data.remark}}</td>
      <td><strong>条码扫描</strong></td>
      <td>
        <input type="text" name="barcode" id="barcode" size="20" onkeydown="inputBarCode()">
      </td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table" id="imageTable">
{{foreach from=$details item=d}}
  {{if $d.images}}
    <tr id="image_{{$d.ean_barcode}}" style="display:none">
      <td>
        {{foreach from=$d.images item=image}}
        <img src="{{$imgBaseUrl}}/{{$image|replace:'.':'_180_180.'}}">&nbsp;&nbsp;&nbsp;
        {{/foreach}}
      </td>
    </tr>
  {{/if}}
{{/foreach}}
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
	{{foreach from=$details item=d key=key}}
	<tr style="background-color:red">
	<td>{{$key+1}}</td>
	<td>{{$d.product_sn}}</td>
	<td>{{$d.goods_name}} (<font color="#FF3333">{{$d.goods_style}}</font>)</td>
	<td>
	  {{if $data.bill_type eq 1 || $data.bill_type eq 10}}
	    {{if $batchInfo}}
	    <select name="new_batch_id[{{$d.product_id}}]">
	    {{foreach from=$batchInfo key=key item=item}}
	    <option value="{{$key}}">{{$item}}</option>
	    {{/foreach}}
	    </select>
	    {{else}}
	    无批次<input type="hidden" name="new_batch_id[{{$d.product_id}}]" value="0">
	    {{/if}}
	  {{else}}
	    {{if $d.batch_no}}{{$d.batch_no}}{{else}}无批次{{/if}}
	  {{/if}}
	  <input type="hidden" name="batch_id[{{$d.product_id}}_{{$d.batch_id}}]" value="{{if $d.batch_id}}{{$d.batch_id}}{{else}}0{{/if}}">
	</td>
	<td>{{$d.p_plan_number}}</td>
	<td>{{$d.p_real_number}}</td>
	<td>{{$d.plan_number}}</td>
	<td>
	<div id="status{{$d.product_id}}_{{$d.batch_id}}">
	<p id="operation_{{$d.ean_barcode}}" {{if $data.bill_type eq 2}}style="display:none"{{/if}}>
	<select name="status[{{$d.product_id}}_{{$d.batch_id}}][]">
	{{foreach from=$status item=sname key=key}}
	{{if !$d.is_vitual || $key eq 6 || $data.bill_type ne 1}}
	  <option value="{{$key}}" {{if $d.status_id ne 1 && $key eq $d.status_id}}selected{{elseif $d.status_id eq 1 && $key eq 2}}selected{{/if}}>{{$sname}}</option>
	{{/if}}
	{{/foreach}}
	</select>
	<input type="hidden" name="old_status[{{$d.product_id}}_{{$d.batch_id}}]" value="{{$d.status_id}}">
	<input type="hidden" name="price[{{$d.product_id}}_{{$d.batch_id}}]" value="{{$d.shop_price}}">
	<input type="text" size="2" name="number[{{$d.product_id}}_{{$d.batch_id}}][]" id="number{{$d.product_id}}_{{$d.batch_id}}" {{if $d.is_vitual}}value="{{$d.plan_number}}" readonly{{else}}value="0"{{/if}} onchange="doSetNumber(this)"> {{if !$d.is_vitual}}<a onclick="addRow(this,'status{{$d.product_id}}_{{$d.batch_id}}')" href="javascript:fGo();">[ 增加 ]</a>{{/if}}
	<input type="hidden" name="plan_number[{{$d.product_id}}_{{$d.batch_id}}]" value="{{$d.plan_number}}">
	</p>
	</div>
	</td>
	</tr>
	{{/foreach}}
</tbody>
</table>

<div style="text-align:right;padding:10px 20px"><strong>本次应数合计：</strong>{{$data.total_number}}
&nbsp;&nbsp;&nbsp;&nbsp;
<strong>本次实数合计：</strong><input type="text" size="5" name="total_number" id="total_number" value="0" readonly>
</div>
</div>
{{if $data.bill_type eq 0}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>入库单号</strong></td>
      <td><input type="text" name="paper_no" id="paper_no" style="width:70%"> (多个单号空格分割)</td>
    </tr>
</tbody>
</table>
{{/if}}

<div class="submit" id="receiveButton" {{if $data.bill_type eq 2}}style="display:none"{{/if}}>
{{if $data.lock_name eq $auth.admin_name}}



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
{{/if}}
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
	{{if $financeLimit}}
	alert('客服先开退款单，仓库才可以收货');
	return;
	{{/if}}
	
	if(confirm('确认收货吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
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
	
	if ({{$data.total_number}} > totalNumber) {
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