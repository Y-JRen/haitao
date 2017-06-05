{{if !$param.job}}
    <div id="source_select" style="padding:10px">
    <form name="searchForm" id="searchForm" method="POST" action="{{url}}" enctype="multipart/form-data" target="upload_file_frame">
    <input type="hidden" name="type" value="{{$param.type}}">
    导入文件：<input type="file" name="import_file" size="40">
    <input type="submit" name="doimport" value="导入"/>
    </form>
    导入格式：XLS文件(2003版)，第1列：运单号　第2列：金额　第3列：佣金。数据从第2行开始　<a href="/haitaoadmin/images/settlement.xls"><下载模板></a><br>
    输出错误说明：金额<font color="red">红色</font>代表金额与系统中的金额不匹配，运单号<font color="red">红色</font>代表运单号不存在，运单号<font color="blue">蓝色</font>代表当前选择的类型不符
    <iframe name="upload_file_frame" style="display:none;"></iframe>

<div id="ajax_search_order">
    <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td width=10>  <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('ajax_search_order'),'ids',this);showTotalAmount();"/> </td>
                <td>物流公司</td>
                <td>单据编号</td>
                <td>单据类型</td>
                <td>运单号</td>
                <td>配送状态</td>
                <td>结算金额</td>
                <td>佣金</td>
                <td style="display:none"></td>
              </tr>
        </thead>
        <tbody id="transport_list"></tbody>
        </table>
        <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
    </div>
    <div style="float:right" id="totalAmount">实际返还金额：<font color="blue"><b>0.00</b></font></div>
        </div>
{{/if}}
<script>
function insertRow2(i, logistic_no, binfo, clear_amount, commission)
{
	var obj = $('transport_list');
	var tr = obj.insertRow(0);
	tr.id = 'ajax_list_' + i;

	if (binfo) {
	    var info = JSON.decode(binfo);
	    if (info.cod_status == 1)   clear_pay = '已结算';
	    else    clear_pay = '未结算';
    }
    
    for (var j = 0;j <= 8; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[8].style.display='none';
	
	if (binfo) {
	    if (info.cod_status == 1) {
	        tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
        	tr.cells[1].innerHTML = '<font color="999999">' + info.logistic_name + '</font>'; 
        	tr.cells[2].innerHTML = '<font color="999999">' + info.bill_no + '</font>';
        	tr.cells[3].innerHTML = '<font color="999999">' + info.bill_type + '</font>';
        	tr.cells[4].innerHTML = '<font color="999999">' + info.logistic_no + '</font>';
        	tr.cells[5].innerHTML = '<font color="999999">' + info.logistic_status + '</font>';
        	tr.cells[7].innerHTML = '<font color="999999">' + commission + '</font>';
        	
        	if (clear_amount) {
        	    
            }
        	else {
        	    clear_amount = info.clear_amount;
        	}
        	clear_amount = clear_amount - commission;
	        tr.cells[6].innerHTML = '<font color="999999">' + clear_amount + '</font>';
            tr.cells[8].innerHTML = clear_amount;
	    }
	    else {
	        info.commission = commission;
	        info.clear_amount = info.clear_amount - commission;
	        info.clear_amount = info.clear_amount.toFixed(2);
	        binfo = JSON.encode(info);
	        if (('{{$param.sub_code}}' == 'jiankang' && info.shop_id == 1) || ('{{$param.sub_code}}' == 'in_call' && info.type == 10) || ('{{$param.sub_code}}' == 'out_call' && info.type == 11) || ('{{$param.sub_code}}' == 'tq_call' && info.type == 12) || ('{{$param.sub_code}}' == 'employee' && info.shop_id == 3) || ('{{$param.sub_code}}' == 'dangdang' && info.shop_id == 551) || ('{{$param.sub_code}}' == 'baidu' && info.shop_id == 98) || ('{{$param.sub_code}}' == 'other' && info.shop_id != 1 && info.shop_id != 2 && info.shop_id != 3 && info.shop_id != 51 && info.shop_id != 98)) {
	            tr.cells[0].innerHTML = '<input type="checkbox" name="ids[]" value="' + info.tid + '" onclick="showTotalAmount();"><input type="hidden" id="pinfo' + info.tid + '" value=\'' + binfo + '\'>';
	            tr.cells[4].innerHTML = info.logistic_no;
	        }
	        else {
	            tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
	            tr.cells[4].innerHTML = '<font color="blue">' + info.logistic_no + '</font>';
	        }
        	tr.cells[1].innerHTML = info.logistic_name; 
        	tr.cells[2].innerHTML = info.bill_no;
        	tr.cells[3].innerHTML = info.bill_type;
        	
        	tr.cells[5].innerHTML = info.logistic_status;
        	tr.cells[7].innerHTML = commission;
        	if (clear_amount) {
        	    clear_amount = clear_amount - commission;
        	    tr.cells[6].innerHTML = '<font color="red">' + clear_amount + '</font> / ' + info.clear_amount;
        	}
        	else {
        	    tr.cells[6].innerHTML = info.clear_amount;
        	}
        	tr.cells[8].innerHTML = info.clear_amount;
	        
	    }
	}
	else {
	    tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
    	tr.cells[1].innerHTML = '';
    	tr.cells[2].innerHTML = '';
    	tr.cells[3].innerHTML = '';
    	tr.cells[4].innerHTML = '<font color="red">' + logistic_no + '</font>'; 
    	tr.cells[5].innerHTML = '';
    	tr.cells[6].innerHTML = '<font color="red">' + clear_amount + '</font>';
    	tr.cells[7].innerHTML = '<font color="red">' + commission + '</font>';
    	tr.cells[8].innerHTML = '0';
	}
	
	obj.appendChild(tr);
}
function delAllRow()
{
    var obj = $('transport_list');
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
    $('totalAmount').innerHTML = '实际返还金额：<font color="blue"><b>' + amount.toFixed(2); + '</b></font>';
}
</script>