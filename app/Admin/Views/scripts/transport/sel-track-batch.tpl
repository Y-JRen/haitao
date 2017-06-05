{{if !$param.job}}
    <div id="source_select" style="padding:10px">
    <form name="searchForm" id="searchForm" method="POST" action="{{url}}" enctype="multipart/form-data" target="upload_file_frame">
    物流公司：
    <select name="logistic_code">
	  {{html_options options=$logisticList}}
    </select>
    <input type="hidden" name="type" value="{{$param.type}}">
    导入文件：<input type="file" name="import_file" size="40">
    <input type="submit" name="doimport" value="导入"/>
    </form>
    导入格式：XLS文件(2003/2007版)，第1列：运单号。数据从第2行开始　<a href="/haitaoadmin/images/track.xls"><下载模板></a><br>
    输出错误说明：运单号<font color="red">红色</font>代表运单号不存在
    <iframe name="upload_file_frame" style="display:none;"></iframe>

<div id="ajax_search_order">
    <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td width=10>  <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('ajax_search_order'),'ids',this);"/> </td>
                <td>物流公司</td>
                <td>单据编号</td>
                <td>单据类型</td>
                <td>运单号</td>
                <td>配送状态</td>
                <td>结算金额</td>
              </tr>
        </thead>
        <tbody id="transport_list"></tbody>
        </table>
        <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
    </div>
        </div>
{{/if}}
<script>
function insertRow2(i, logistic_no, binfo)
{
	var obj = $('transport_list');
	var tr = obj.insertRow(0);
	tr.id = 'ajax_list_' + i;

	if ( binfo ) {
	    var info = JSON.decode(binfo);
    }
    
    for (var j = 0;j <= 6; j++) {
	  	 tr.insertCell(j);
	}
	
	if ( binfo ) {
	    tr.cells[0].innerHTML = '<input type="checkbox" name="ids[]" value="' + info.tid + '" onclick="showTotalAmount();"><input type="hidden" id="pinfo' + info.tid + '" value=\'' + binfo + '\'>';
    	tr.cells[1].innerHTML = info.logistic_name; 
    	tr.cells[2].innerHTML = info.bill_no;
    	tr.cells[3].innerHTML = info.bill_type;
    	tr.cells[4].innerHTML = info.logistic_no;
    	tr.cells[5].innerHTML = info.logistic_status;
    	tr.cells[6].innerHTML = info.clear_amount;
	}
	else {
	    tr.cells[0].innerHTML = '<input type="checkbox" style="display:none">';
    	tr.cells[1].innerHTML = '';
    	tr.cells[2].innerHTML = '';
    	tr.cells[3].innerHTML = '';
    	tr.cells[4].innerHTML = '<font color="red">' + logistic_no + '</font>'; 
    	tr.cells[5].innerHTML = '';
    	tr.cells[6].innerHTML = '';
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

</script>