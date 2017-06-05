{{if !$param.job}}
    <div id="source_select" style="padding:10px">
    <form name="searchForm" id="searchForm" method="POST" action="{{url}}" enctype="multipart/form-data" target="upload_file_frame">
    <input type="hidden" name="type" value="{{$param.type}}">
    导入文件：<input type="file" name="import_file" size="40">
    <input type="submit" name="doimport" value="导入"/>
    </form>
    导入格式：XLS文件(2003版)，第1列：单据号，第2列：运单号 数据从第2行开始　<a href="/haitaoadmin/images/order_logistics.xls"><下载模板></a><br>
    输出错误说明：运单号<font color="red">红色</font>代表单据号不存在
    <iframe name="upload_file_frame" style="display:none;"></iframe>

<div id="ajax_search_order">
        <form name="myForm1" id="myForm1" action="/admin/transport/fill-import-no" method="post" target="ifrmSubmit">
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td>物流公司</td>
                <td>单据编号</td>
                <td>运单号</td>
              </tr>
        </thead>
        <tbody id="transport_list"></tbody>
        <tr id="submitButton" style="display:none;">
          <td align="center" colspan="3"><input type="submit" name="dofill" value="批量填充"></td>
        </tr>
        </table>
        </form>
    </div>
        </div>
{{/if}}
<script>
function insertRow(i, bill_no, binfo, logistic_no)
{
	var obj = $('transport_list');
	var tr = obj.insertRow(0);
	tr.id = 'ajax_list_' + i;

	if ( binfo ) {
	    var info = JSON.decode(binfo);
    }
    
    for (var j = 0;j <= 2; j++) {
	  	 tr.insertCell(j);
	}
	
	if ( binfo ) {
    	tr.cells[0].innerHTML = info.logistic_name +  '<input type="hidden" name="ids[]" value="' + info.tid + '">';
    	tr.cells[1].innerHTML = bill_no;
    	tr.cells[2].innerHTML = logistic_no + '<input type="hidden" name="no[]" value="' + logistic_no + '">';
    	
    	$('submitButton').style.display = '';
	}
	else {
    	tr.cells[0].innerHTML = '';
    	tr.cells[1].innerHTML = '<font color="red">' + bill_no + '</font>'; 
    	tr.cells[2].innerHTML = '<font color="red">' + logistic_no + '</font>'; 
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
    
    $('submitButton').style.display = 'none';
}

</script>