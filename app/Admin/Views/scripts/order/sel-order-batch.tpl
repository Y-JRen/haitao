{{if !$param.job}}
    <div id="source_select" style="padding:10px">
    <form name="searchForm" id="searchForm" method="POST" action="{{url}}" enctype="multipart/form-data" target="upload_file_frame">
    <input type="hidden" name="type" value="{{$param.type}}">
    <input type="hidden" name="logistic_code" value="{{$param.logistic_code}}">
    导入文件：<input type="file" name="import_file" size="40">
    <input type="submit" name="doimport" value="导入"/>
    </form>
    导入格式：XLS文件(2003/2007版)，第1列：订单号　第2列：金额　第3列：佣金。数据从第2行开始　<a href="/haitaoadmin/images/settlement.xls"><下载模板></a><br>
    输出错误说明：金额<font color="red">红色</font>代表金额与系统中的金额不匹配，订单<font color="red">红色</font>代表订单号不存在，订单号<font color="blue">蓝色</font>代表当前选择的支付宝子类型不符
    <iframe name="upload_file_frame" style="display:none;"></iframe>
    </div>
{{/if}}
<div id="ajax_search_order">
    <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td width=10>  <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('ajax_search_order'),'ids',this);showTotalAmount();"/> </td>
                <td>ID</td>
                <td >订单号</td>
                <td>下单时间</td>
                <td>发货时间</td>
                <td>结算金额</td>
                <td>佣金</td>
                <td>支付方式</td>
                <td>结算状态</td>
                <td style="display:none"></td>
              </tr>
        </thead>
        <tbody id="order_list"></tbody>
        </table>
        <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
    </div>
    <div style="float:right" id="totalAmount">实际返还金额：<font color="blue"><b>0.00</b></font></div>
<script>
function insertRow2(i, batch_sn, oinfo, price_payed, commission)
{
	var obj = $('order_list');
	var tr = obj.insertRow(0);
	tr.id = 'ajax_list_' + i;
	
	if (oinfo) {
	    var info = JSON.decode(oinfo);
	    if (info.clear_pay == 1)   clear_pay = '已结算';
	    else    clear_pay = '未结算';
    }

    for (var j = 0;j <= 9; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[9].style.display='none';
	
	if (oinfo) {
	    if (info.clear_pay == 1) {
	        tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
    	    tr.cells[1].innerHTML = '<font color="999999">' + info.order_batch_id + '</font>';
        	tr.cells[2].innerHTML = '<font color="999999">' + info.batch_sn + '</font>';
        	tr.cells[3].innerHTML = '<font color="999999">' + info.add_time + '</font>';
        	tr.cells[4].innerHTML = '<font color="999999">' + info.logistic_time + '</font>';
        	tr.cells[6].innerHTML = '<font color="999999">' + commission + '</font>';
        	tr.cells[7].innerHTML = '<font color="999999">' + info.pay_name + '</font>';
        	tr.cells[8].innerHTML = '<font color="999999">' + clear_pay + '</font>';
	    
	        if (price_payed) {
        	    
            }
        	else {
        	    price_payed = info.price_payed;
        	}
        	price_payed = price_payed - commission;
	        tr.cells[5].innerHTML = '<font color="999999">' + price_payed + '</font>';
            tr.cells[9].innerHTML = price_payed;
	    }
	    else {
            info.commission = commission;
	        info.price_payed = info.price_payed - commission;
	        info.price_payed = info.price_payed.toFixed(2);
	        oinfo = JSON.encode(info);
	        if ('{{$param.pay_type}}' != 'alipay' || ('{{$param.sub_pay_type}}' == 'jiankang' && info.shop_id == 1) || ('{{$param.sub_pay_type}}' == 'call' && info.shop_id == 2) || ('{{$param.sub_pay_type}}' == 'employee' && info.shop_id == 3)) {
        	    tr.cells[0].innerHTML = '<input type="checkbox" name="ids[]" value="' + info.order_batch_id + '" onclick="showTotalAmount();"><input type="hidden" id="oinfo' + info.order_batch_id + '" value=\'' + oinfo + '\'>';
        	    tr.cells[2].innerHTML = info.batch_sn;
        	}
        	else {
        	    tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
        	    tr.cells[2].innerHTML = '<font color="blue">' + info.batch_sn + '</font>';
        	}
    	    tr.cells[1].innerHTML = info.order_batch_id;
        	tr.cells[3].innerHTML = info.add_time;
        	tr.cells[4].innerHTML = info.logistic_time;
        	tr.cells[6].innerHTML = commission;
        	tr.cells[7].innerHTML = info.pay_name;
        	tr.cells[8].innerHTML = clear_pay;
        	if (price_payed) {
        	    price_payed = price_payed - commission;
        	    tr.cells[5].innerHTML = '<font color="red">' + price_payed + '</font> / ' + info.price_payed;
        	}
        	else {
        	    tr.cells[5].innerHTML = info.price_payed;
        	}
        	tr.cells[9].innerHTML = info.price_payed;
        }
	}
	else {
	    price_payed = price_payed - commission;
	    tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
    	tr.cells[1].innerHTML = '';
    	tr.cells[2].innerHTML = '<font color="red">' + batch_sn + '</font>'; 
    	tr.cells[3].innerHTML = '';
    	tr.cells[4].innerHTML = '';
    	tr.cells[5].innerHTML = '<font color="red">' + price_payed + '</font>';
    	tr.cells[6].innerHTML = '<font color="red">' + commission + '</font>';
    	tr.cells[7].innerHTML = '';
    	tr.cells[8].innerHTML = '';
    	tr.cells[9].innerHTML = '0';
	}
	
	obj.appendChild(tr);
}
function delAllRow()
{
    var obj = $('order_list');
    var length = obj.rows.length;
    for (i = 0; i < length; i++) {
        $('ajax_list_' + i).destroy();
    }
}
function showTotalAmount()
{
    var el = $('ajax_search_order').getElements('input[type=checkbox]');
    var amount = 0;
    for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			index = i - 1;
			amount += parseFloat($('ajax_list_' + index).cells[9].innerHTML);
        }
    }
    $('totalAmount').innerHTML = '实际返还金额：<font color="blue"><b>' + amount.toFixed(2); + '</b></font>';
}
</script>