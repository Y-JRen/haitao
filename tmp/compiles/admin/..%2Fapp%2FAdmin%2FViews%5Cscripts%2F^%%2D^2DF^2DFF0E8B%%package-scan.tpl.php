<?php /* Smarty version 2.6.19, created on 2014-08-21 14:28:35
         compiled from transport/package-scan.tpl */ ?>
<style type="text/css">
.input-text-focus{
	background-color:#FFFC00 !important;
}
input[type="text"]:focus{
	background-color:#FFFC00;
}
</style>
<div class="title">配送管理 -&gt; 打包发货扫描</div>
<div class="content">
<form id="myform">
<div style="border-bottom:1px solid #CCC;">
<table width="100%" style="border-right:1px solid #CCC;">
<tr bgcolor="#F0F1F2">
  <th width="15%" height="30">运输单/单据条码：</th>
  <td width="35%" height="30">
    <input type="text" name="logistic_no" id="logistic_no" size="25" onkeydown="inputLogisticNo()">
  </td>
  <th width="15%" height="30">称重：</th>
  <td height="30">
    <input type="text" name="weight" id="weight" size="6" onkeydown="inputWeight()">
  </td>
</tr>
<tr>
  <th height="30">出库类型：</th>
  <td height="30" id="bill_type"></td>
  <th height="30">单据编号：</th>
  <td height="30" id="bill_no"></td>
</tr>
<tr bgcolor="#F0F1F2">
  <th height="30">制单日期：</th>
  <td height="30" id="add_time" colspan="3"></td>
  </td>
</tr>
</table>
<br><br>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
  <tr>
    <td><b>产品名称</b></td>
    <td><b>产品规格</b></td>
    <td><b>产品编号</b></td>
    <td><b>产品条码</b></td>
    <td><b>产品数量</b></td>
  </tr>
</thead>
<tbody id="list"></tbody>
</table>
</div>
</div>
<div style="width:1px" id="fo" onkeydown="closeDiv()"></div>
<div style="text-align:center">
<input type="button" value="清空" onclick="emptyBill(2)">
<input type="button" id="sendButton" value="发货" onclick="doSend()" disabled>
</div>

<script>
function inputLogisticNo()
{
    var e = getEvent();
    var value = $('logistic_no').value;
    if (e.keyCode == 13 && value != '') {
        new Request({
            url:'/admin/transport/get-scan-bill/logistic_no/' + value,
    	    onSuccess:function(msg){
                if (msg.substring(0, 5) == 'error') {
                    hint(msg, $('logistic_no'));
                    emptyBill(2);
                    return false;
                }
                createBillInfo(msg);
    		},
    		onError:function() {
    			alert("网络繁忙，请稍后重试");
    		}
	    }).send();
    }
}

function inputWeight()
{
    var e = getEvent();
    var value = $('weight').value;
    if (e.keyCode == 13 && value != '') {
        doSend();
    }
}

function hint(msg, obj)
{
    if (msg == 'error empty logistic no') {
        msg = '销售单的运输单号为空!';
    }
    else if (msg == 'error bill no') {
        msg = '单据编号不存在!';
    }
    else if (msg == 'error send') {
        msg = '发货失败!';
    }
    else if (msg == 'error return money') {
        msg = '销售单发生退款!';
    }
    else if (msg == 'error change status3') {
        msg = '销售单状态改变为已发货，不能扫描!';
    }
    else if (msg == 'error change status10') {
        msg = '销售单状态改变为已收货，不能扫描!';
    }
    else if (msg == 'error change status11') {
        msg = '销售单状态改变为已取消，不能扫描!';
    }
    else if (msg == 'error change status12') {
        msg = '销售单状态改变为冻结，不能扫描!';
    }
    else if (msg == 'error barcode') {
        msg = '产品条码不存在!';
    }
    else if (msg == 'error more number') {
        msg = '产品数量超出!';
    }
    else if (msg == 'error wrong logistic no') {
        msg = '运输单号不匹配!';
    }
    else if (msg == 'error scan status1') {
        msg = '该单据已通过产品扫描!';
    }
    else if (msg == 'error scan status2') {
        msg = '该单据未通过产品扫描!';
    }
    
    focusObject = obj;
    $('fo').focus();
    
    window.top.alertBox.init('msg="' + msg + '",url="",ms="","";');
}

function createBillInfo(data)
{
    emptyBill(1);
    
    var data = JSON.decode(data);
    bill = data;
    
    $('bill_type').innerHTML = data.data.bill_type + ' ' + data.data.shop_name;
    $('add_time').innerHTML = data.data.add_time;
    
    var a = data.data.bill_no.split(':');
    
    $('bill_no').innerHTML = data.data.bill_no + '<input type="hidden" id="order_bill_no" value="' + data.data.bill_no + '">';
    
    var obj = $('list');
    
    for (var i = 0; i < data.detail.length; i++) {
        var tr = obj.insertRow(0);
        var detail = data.detail[i];
        tr.id = 'sid' + detail.id;
    	for (var j = 0; j <= 4; j++) {
            tr.insertCell(j);
        }
        if (detail.ean_barcode == '' || detail.ean_barcode == null) {
            detail.ean_barcode = detail.product_sn;
        }
        tr.cells[0].innerHTML = detail.product_name;
        tr.cells[1].innerHTML = detail.goods_style;
        tr.cells[2].innerHTML = detail.product_sn;
        tr.cells[3].innerHTML = detail.ean_barcode;
        tr.cells[4].innerHTML = detail.number;
    }
    
    $('weight').focus();
}

function doSend()
{
    $('sendButton').disabled = false;
    
    new Request({
        url:'/admin/transport/send-scan-bill/bill_no/' + $('order_bill_no').value + '/weight/' + $('weight').value,
        onSuccess:function(msg){
            if (msg.substring(0, 5) == 'error') {
                hint(msg, null);
                return false;
            }
            else {
                emptyBill(2);
                $('logistic_no').focus();
            }
        },
        onError:function() {
    	    alert("网络繁忙，请稍后重试");
        }
    }).send();
}

function emptyBill(flag)
{
    if (flag == 2) {
        $('logistic_no').value = '';
    }
    $('bill_type').innerHTML = '';
    $('add_time').innerHTML = '';
    $('bill_no').innerHTML = '';
    $('weight').value = '';
    
    var obj = $('list');
    var length = obj.rows.length;
    for (i = 0; i < length; i++) {
        obj.deleteRow(0);
    }
    
    $('sendButton').disabled = true;
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


$('logistic_no').focus();

var bill = null;

var focusObject = null;

</script>