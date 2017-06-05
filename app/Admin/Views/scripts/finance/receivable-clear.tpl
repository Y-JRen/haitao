{{if !$param.job}}
    <div id="source_select" style="padding:10px">
    <form name="searchForm" id="searchForm" method="POST" action="{{url}}" enctype="multipart/form-data" target="upload_file_frame">
    <input type="hidden" name="pay_type" value="{{$param.pay_type}}">
    支付类型：<b>
          {{if $param.pay_type eq 'alipay'}}支付宝
          {{elseif $param.pay_type eq 'phonepay'}}手机支付
          {{elseif $param.pay_type eq 'tenpay'}}财付通
          {{elseif $param.pay_type eq 'bankcomm'}}交通银行
          {{elseif $param.pay_type eq 'bank'}}银行打款
          {{elseif $param.pay_type eq 'cash'}}现金支付
          {{elseif $param.pay_type eq 'credit'}}赊销支付
          {{elseif $param.pay_type eq 'distribution'}}直供支付
          {{elseif $param.pay_type eq 'external'}}渠道支付
          {{elseif $param.pay_type eq 'externalself'}}渠道代发货支付
          {{elseif $param.pay_type eq 'sf'}}顺丰
          {{elseif $param.pay_type eq 'ems'}}EMS
          {{elseif $param.pay_type eq 'gift'}}礼品卡
          {{elseif $param.pay_type eq 'point'}}积分
          {{elseif $param.pay_type eq 'account'}}账户余额
          {{elseif $param.pay_type eq 'exchange'}}换货支付
          {{elseif $param.pay_type eq 'easipay'}}东方支付
          {{/if}}</b><br>
    导入文件：<input type="file" name="import_file" size="40">
    <input type="submit" name="doimport" value="导入"/>
    </form>
    导入格式：XLS文件(2003/2007版)，第1列：单据编号　第2列：实收金额　第3列：佣金。数据从第2行开始　<a href="/haitaoadmin/images/settlement.xls"><下载模板></a><br>
    输出错误说明：金额<font color="red">红色</font>代表金额与系统中的金额不匹配，单据编号<font color="red">红色</font>代表单据编号不存在
    <iframe name="upload_file_frame" style="display:none;"></iframe>
    </div>
{{/if}}
<form name="myForm1" id="myForm1" method="POST" action="{{url}}">
<div id="ajax_search_order">
<table cellpadding="0" cellspacing="0" border="0" class="table">
  <thead>
    <tr>
      <td width=10>  <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('ajax_search_order'),'ids',this);showTotalAmount();"/> </td>
      <td>ID</td>
      <td>渠道</td>
      <td>单据编号</td>
      <td>生成日期</td>
      <td>结算金额</td>
      <td>佣金</td>
      <td>支付方式</td>
      <td>结算状态</td>
      <td style="display:none"></td>
    </tr>
  </thead>
  <tbody id="order_list"></tbody>
</table>
</div>
<div style="float:right" id="totalAmount">实收金额：<font color="blue"><b>0.00</b></font></div>
<div style="text-align:center">
<input type="button" name="settle" id="settle" value="结算" style="display:none" onclick="ajax_submit($('myForm1'),'{{url}}')" />
</div>
<div style="text-align:center;width:400px;">
<br><br>
<table cellpadding="0" cellspacing="0" border="0" class="table">
  <thead>
    <tr>
      <td>渠道</td>
      <td>结算金额</td>
      <td>佣金</td>
    </tr>
  </thead>
  <tbody id="sum_list"></tbody>
</table>
</div>
</form>
<script>
function insertRow2(i, batch_sn, oinfo, amount, commission)
{
	var obj = $('order_list');
	var tr = obj.insertRow(0);
	tr.id = 'ajax_list_' + i;
	
	if (oinfo) {
	    var info = JSON.decode(oinfo);
	    if (info.settle_time > 0)   clear_pay = '已结算';
	    else    clear_pay = '未结算';

	    if (info.type == 10) {
	        info.shop_name = '呼叫中心呼入';
	    }
	    else if (info.type == 11) {
	        info.shop_name = '呼叫中心呼出';
	    }
	    else if (info.type == 12) {
	        info.shop_name = '呼叫中心咨询';
	    }
	    else if (info.type == 14) {
	        info.shop_name = info.addr_consignee;
	    }
	    else if (info.user_name == 'gift') {
	        info.shop_name = '客情';
	    }
	    else if (info.user_name == 'other') {
	        info.shop_name = '其它';
	    }
	    else if (info.user_name == 'internal') {
	        info.shop_name = '内购';
	    }else{
	    	info.shop_name = '海淘网';
	    }
    }

    for (var j = 0;j <= 9; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[9].style.display = 'none';
	
	if (oinfo) {
        if (info.pay_type == 'alipay')              info.pay_name = '支付宝';
        else if (info.pay_type == 'easipay')		 info.pay_name = '东方支付';
        else if (info.pay_type == 'phonepay')       info.pay_name = '手机支付';
        else if (info.pay_type == 'tenpay')         info.pay_name = '财付通';
        else if (info.pay_type == 'bankcomm')       info.pay_name = '交通银行';
        else if (info.pay_type == 'bank')           info.pay_name = '银行打款';
        else if (info.pay_type == 'cash')           info.pay_name = '现金支付';
        else if (info.pay_type == 'credit')         info.pay_name = '赊销支付';
        else if (info.pay_type == 'distribution')   info.pay_name = '直供支付';
        else if (info.pay_type == 'external')       info.pay_name = '渠道支付';
        else if (info.pay_type == 'externalself')   info.pay_name = '渠道代发货支付';
        else if (info.pay_type == 'sf')             info.pay_name = '顺丰';
        else if (info.pay_type == 'ems')            info.pay_name = 'EMS';
        else if (info.pay_type == 'gift')           info.pay_name = '礼品卡';
        else if (info.pay_type == 'point')          info.pay_name = '积分';
        else if (info.pay_type == 'account')        info.pay_name = '账户余额';
        else if (info.pay_type == 'exchange')       info.pay_name = '换货支付';
        
	    if (info.settle_time > 0) {
	        tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
    	    tr.cells[1].innerHTML = '<font color="999999">' + info.id + '</font>';
    	    tr.cells[2].innerHTML = '<font color="999999">' + info.shop_name + '</font>';
        	tr.cells[3].innerHTML = '<font color="999999">' + info.batch_sn + '</font>';
        	tr.cells[4].innerHTML = '<font color="999999">' + info.add_time + '</font>';
        	tr.cells[6].innerHTML = '<font color="999999">' + info.commission + '</font>';
        	tr.cells[7].innerHTML = '<font color="999999">' + info.pay_name + '</font>';
        	tr.cells[8].innerHTML = '<font color="999999">' + clear_pay + '</font>';
	    
	        if (amount) {
        	    
            }
        	else {
        	    amount = info.amount;
        	}
        	amount = amount - commission;
	        tr.cells[5].innerHTML = '<font color="999999">' + amount + '</font>';
            tr.cells[9].innerHTML = amount;
	    }
	    else {
            info.commission = commission;
	        info.amount = info.amount - commission;
	        info.amount = info.amount.toFixed(2);
            tr.cells[1].innerHTML = info.id;
            tr.cells[2].innerHTML = info.shop_name;
            tr.cells[3].innerHTML = info.batch_sn;
        	tr.cells[4].innerHTML = info.add_time;
        	tr.cells[6].innerHTML = commission;
        	tr.cells[7].innerHTML = info.pay_name;
        	tr.cells[8].innerHTML = clear_pay;
        	if (amount) {
        	    amount = amount - commission;
        	    tr.cells[5].innerHTML = '<font color="red">' + amount + '</font> / ' + info.amount;
        	    tr.cells[9].innerHTML = amount;
        	}
        	else {
        	    tr.cells[5].innerHTML = info.amount;
        	    tr.cells[9].innerHTML = info.amount;
        	}
        	
        	info.real_amount = tr.cells[9].innerHTML;
        	info.real_commission = commission;
        	oinfo = JSON.encode(info);
        	tr.cells[0].innerHTML = '<input type="checkbox" name="ids[]" value="' + info.id + '" onclick="showTotalAmount();"><input type="hidden" id="oinfo' + info.id + '" name="oinfo' + info.id + '" value=\'' + oinfo + '\'>';
        }
	}
	else {
	    amount = amount - commission;
	    tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
    	tr.cells[1].innerHTML = '';
    	tr.cells[2].innerHTML = '';
    	tr.cells[3].innerHTML = '<font color="red">' + batch_sn + '</font>'; 
    	tr.cells[4].innerHTML = '';
    	tr.cells[5].innerHTML = '<font color="red">' + amount + '</font>';
    	tr.cells[6].innerHTML = '<font color="red">' + commission + '</font>';
    	tr.cells[7].innerHTML = '';
    	tr.cells[8].innerHTML = '';
    	tr.cells[9].innerHTML = '0';
	}
	
	obj.appendChild(tr);
}
function insertSum(i, key, amount, commission)
{
    var obj = $('sum_list');
	var tr = obj.insertRow(0);
	tr.id = 'ajax_sum_list_' + i;
    
    for (var j = 0;j <= 2; j++) {
	  	 tr.insertCell(j);
	}
	
	tr.cells[0].innerHTML = key;
	tr.cells[1].innerHTML = amount;
	tr.cells[2].innerHTML = commission;
	obj.appendChild(tr);
}
function delAllRow(name, list)
{
    var obj = $(name);
    var length = obj.rows.length;
    for (i = 0; i < length; i++) {
        $(list + '_' + i).destroy();
    }
}
function showTotalAmount()
{
    var el = $('ajax_search_order').getElements('input[type=checkbox]');
    var amount = 0;
    var checked = false;
    for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			index = i - 1;
			amount += parseFloat($('ajax_list_' + index).cells[9].innerHTML);
			checked = true;
        }
    }
    $('totalAmount').innerHTML = '实收金额：<font color="blue"><b>' + amount.toFixed(2); + '</b></font>&nbsp;&nbsp;';
    
    if (checked) {
        $('settle').style.display = '';
    }
    else {
        $('settle').style.display = 'none';
    }
}
</script>