<form name="myForm1" id="myForm1">
<div class="title">组装开单管理  -&gt; 组装开单申请</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>类型 </strong> * </td>
      <td>
      <select name="type" id="type" msg="请选择类型" class="required" onchange="change_type()">
          <option value="1">拆装</option>
          <option value="2">组装</option>
      </select>
      </td>
    </tr>
    <tr>
      <td width="10%" colspan="2"><strong>选择组装出库产品 </strong> </td>
    </tr>
    
    <tr>
      <td colspan="2">
      <input type="button" onclick="openwindow(1)" value="查询添加商品">
      </td>
    </tr>
    <tr>
        <td colspan="2">
            <table cellpadding="0" cellspacing="0" border="0" class="table">
                <thead>
                    <tr>
                        <td>删除</td>
                        <td>产品编码</td>
                        <td>产品名称</td>
                        <td>产品批次</td>
                        <td>状态</td>
                        <td>可用库存</td>
                        <td>出库单价</td>
                        <td>申请数量</td>
                    </tr>
                </thead>
                <tbody id="list1"></tbody>
            </table>
        </td>
    </tr>
    <tr>
      <td width="10%" colspan="2"><strong>选择组装入库产品 </strong> </td>
    </tr>
    
    <tr>
      <td colspan="2">
      <input type="button" onclick="openwindow(2)" value="查询添加商品">
      </td>
    </tr>
    <tr>
        <td colspan="2">
            <table cellpadding="0" cellspacing="0" border="0" class="table">
                <thead>
                    <tr>
                        <td>删除</td>
                        <td>产品编码</td>
                        <td>产品名称</td>
                        <td>产品批次</td>
                        <td>状态</td>
                        <td>可用库存</td>
                        <td>成本权重</td>
                        <td>申请数量</td>
                    </tr>
                </thead>
                <tbody id="list2"></tbody>
            </table>
        </td>
    </tr>
    <tr>
      <td><strong>备注</strong> * </td>
      <td style="font-size:16px"><textarea name="remark" style="width: 400px;height: 50px" msg="请填写备注" class="required"></textarea></td>
    </tr>
</tbody>
</table>

<div class="submit">
  <input type="button" name="dosubmit1" id="dosubmit1" value="提交" onclick="dosubmit()"/> 
  <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">

var bill_type = 0;
function dosubmit()
{
	if(confirm('确认提交申请吗？')){
		//$('dosubmit1').value = '处理中';
		//$('dosubmit1').disabled = 'disabled';
		ajax_submit($('myForm1'),'/admin/product/assemble-apply');
	}
}

function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');

	var obj    = bill_type == 1 ? $("list1") : $('list2');
    var pre_id = bill_type == 1 ? 'out' : 'in';
	var type   = $('type').value;
    var number = 0;
    for (i = 1; i< el.length; i++) {
        if (el[i].checked) {
            number ++;
        }    
    }

    if (type == '1' && bill_type == 1) {
        if (number > 1 || number > 0 && $("list1").getElements('tr').length > 0) {
            alert('您选择的是拆单，只能选择一个出库产品');
            return false;
        }
    } else if (type == '2' &&  bill_type == 2) {
        if (number > 1 || number > 0 && $("list2").getElements('tr').length > 0) {
            alert('您选择的是装单，只能选择一个入库产品');
            return false;
        }
    }

	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
            
		    if ($('pinfo' + el[i].value) != null) {
		        var str = $('pinfo' + el[i].value).value;
		        
		        var pinfo = JSON.decode(str);
    			if (pinfo.batch && pinfo.batch[0].batch_id == 0) {
    			    pinfo.batch = '';
    			}
    			
                batch_id = 0;
                if ($(pre_id + 'sid' + pinfo.product_id + batch_id)) {
                    continue;
                }

    		    var id = pinfo.product_id + batch_id;
    		    var tr = obj.insertRow(0);
    		    tr.id = pre_id + 'sid' + id;
    		    for (var j = 0; j <= 7; j++) {
    			    tr.insertCell(j);
    		    }
                
    			tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeTuirow(this)"><input type="hidden" name="'+pre_id+'ids[]" value="'+pinfo.product_id+'"><input type="hidden" name="'+pre_id+'status_id[]" value="'+pinfo.status_id+'"><input type="hidden" name="product_type[]" value="product">';
    			tr.cells[1].innerHTML = pinfo.product_sn;
    			tr.cells[2].innerHTML = pinfo.product_name + ' <font color="red">(' + pinfo.goods_style + ')</font>';
    
                tr.cells[3].innerHTML = '无批次<select name="'+pre_id+'batch_id[]" style="display:none"><option value="0"></option></select>';
                pinfo.price = pinfo.purchase_cost;
    			tr.cells[4].innerHTML = pinfo.status_name + '<input type="hidden" name="'+pre_id+'status[]" id="status' + id + '" value="'+pinfo.status_id+'" >';
    			if (bill_type == 2) {
                    tr.cells[5].innerHTML = '<span id="show_number' + id + '">未知<input type="hidden" name="'+pre_id+'stock_number[]" value="0"></span>';
    			} else {
                    tr.cells[5].innerHTML = '<span id="show_number' + id + '">' + pinfo.stock_number + '<input type="hidden" name="'+pre_id+'stock_number[]" value="' + pinfo.stock_number + '"></span>';
                }
 
    			tr.cells[6].innerHTML = pinfo.cost + '<input type="hidden" name="'+pre_id+'price[]" id="price' + id + '" value="'+pinfo.cost+'" >';
                if (bill_type == 2) {
                    tr.cells[7].innerHTML = '<input type="text" size="6" name="'+pre_id+'number[]" id="number' + batch_id + '" value="0" class="required" msg="不能为空"/>';
                } else {
                    tr.cells[7].innerHTML = '<input type="text" size="6" name="'+pre_id+'number[]" id="number' + batch_id + '" value="0" class="required" msg="不能为空" onblur="checkNum(this, ' + pinfo.stock_number + ')" />';
    			}
                obj.appendChild(tr);
		    } else {
		        return false;
		    }
		}
	}
}

function removeRow(id)
{
	$('sid' + id).destroy();
}
function checkNum(obj, num)
{
	var batch_id = obj.id.substring(6);
	if (batch_id != '0') {
	    num = $('stock_number' + batch_id).value;
	}
	if (parseInt(obj.value) < 0 || isNaN(obj.value)) {
	    alert('请填写正整数');
	    obj.value = 0;
	    return false;
	}
	if (parseInt(obj.value) > parseInt(num)) {
	    alert('数量不能大于'+num);
	    obj.value = 0;
	    return false;
	}
}


function openwindow(value) 
{
    if (value == '1') {
        openDiv('{{url param.controller=product param.action=sel param.type=sel_status param.logic_area=1}}','ajax','查询商品',750,400);
    } else if (value == '2') {
        openDiv('{{url param.controller=product param.action=sel param.type=sel param.logic_area=1}}','ajax','查询商品',750,400);
    }
    bill_type = value;
}

function removeTuirow(obj)
{
    obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
    caluAmount();
}

function change_type()
{
    $("list1").innerHTML='';
    $("list2").innerHTML='';
}

</script>