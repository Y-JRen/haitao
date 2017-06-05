{{if !$param.job}}
    <div id="source_select" style="padding:10px">
    <form name="searchForm" id="searchForm" method="POST" action="{{url}}" enctype="multipart/form-data" target="upload_file_frame">
    <input type="hidden" name="type" value="{{$param.type}}">
    <input type="hidden" name="shop_id" value="{{$param.shop_id}}">
    当前店铺：<strong>{{$shop.shop_name}}</strong>
        <br>
    导入文件：<input type="file" name="import_file" size="40">
    <input type="submit" name="doimport" value="导入"/>
    </form>
    导入格式：XLS文件(2003/2007版)，第1列：{{if $param.shop_id eq 30}}支付流水号{{else}}订单号{{/if}}　第2列：结算金额　第3列：佣金。数据从第2行开始　<a href="/haitaoadmin/images/settlement.xls"><下载模板></a><br>
    输出错误说明：金额<font color="red">红色</font>代表金额与系统中的金额不匹配，订单<font color="red">红色</font>代表订单号不存在
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
                <td>订单号{{if $param.shop_id eq 30}}/支付流水号{{/if}}</td>
                <td>下单时间</td>
                <td>发货时间</td>
                <td>结算金额</td>
                <td>佣金</td>
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
function insertRow2(i, order_sn, oinfo, amount, commission)
{
	var obj = $('order_list');
	var tr = obj.insertRow(0);
	tr.id = 'ajax_list_' + i;
	
	if (oinfo) {
	    var info = JSON.decode(oinfo);
	    if (info.is_settle == 1)   is_settle = '已结算';
	    else    is_settle = '未结算';
    }
    
    for (var j = 0;j <= 8; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[8].style.display='none';
    
	if (oinfo) {
	    if (info.is_settle == 1) {
	        tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
	        tr.cells[1].innerHTML = '<font color="999999">' + info.order_id + '</font>';
        	tr.cells[2].innerHTML = '<font color="999999">' + info.order_sn + {{if $param.shop_id eq 30}}'/' + info.payment_no + {{/if}}'</font>'; 
        	tr.cells[3].innerHTML = '<font color="999999">' + info.order_time + '</font>';
        	tr.cells[4].innerHTML = '<font color="999999">' + info.logistic_time + '</font>';
        	tr.cells[6].innerHTML = '<font color="999999">' + commission + '</font>';
        	tr.cells[7].innerHTML = '<font color="999999">' + is_settle + '</font>';
        	
        	if (amount) {
        	    
        	}
        	else {
        	    amount = info.amount;
        	}
        	amount = amount - commission;
        	
        	tr.cells[5].innerHTML = '<font color="999999">' + amount+ '</font>';
            tr.cells[8].innerHTML = amount;
	    }
	    else {
	        info.commission = commission;
	        info.amount = info.amount - commission;
	        info.amount = info.amount.toFixed(2);
	        oinfo = JSON.encode(info);
	        tr.cells[0].innerHTML = '<input type="checkbox" name="ids[]" value="' + info.order_id + '" onclick="showTotalAmount();"><input type="hidden" id="oinfo' + info.order_id + '" value=\'' + oinfo + '\'>';
	        tr.cells[1].innerHTML = info.order_id;
        	tr.cells[2].innerHTML = info.order_sn{{if $param.shop_id eq 30}} + '/' + info.payment_no{{/if}}; 
        	tr.cells[3].innerHTML = info.order_time;
        	tr.cells[4].innerHTML = info.logistic_time;
        	tr.cells[6].innerHTML = commission;
        	tr.cells[7].innerHTML = is_settle;
        	
        	if (amount) {
        	    amount = amount - commission;
        	    amount = amount.toFixed(2);
        	    tr.cells[5].innerHTML = '<font color="red">' + amount + '</font> / ' + info.amount;
        	}
        	else {
        	    tr.cells[5].innerHTML = info.amount;
        	}
        	tr.cells[8].innerHTML = info.amount;
	    }
	}
	else {
	    amount = amount - commission;
	    amount = amount.toFixed(2);
	    tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
    	tr.cells[1].innerHTML = '';
    	tr.cells[2].innerHTML = '<font color="red">' + order_sn + '</font>'; 
    	tr.cells[3].innerHTML = '';
    	tr.cells[4].innerHTML = '';
    	tr.cells[5].innerHTML = '<font color="red">' + amount + '</font>';
    	tr.cells[6].innerHTML = '<font color="red">' + commission + '</font>';
    	tr.cells[7].innerHTML = '';
    	tr.cells[8].innerHTML = '0';
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
			amount += parseFloat($('ajax_list_' + index).cells[8].innerHTML);
        }
    }
    $('totalAmount').innerHTML = '实际返款金额：<font color="blue"><b>' + amount.toFixed(2); + '</b></font>';
}
</script>