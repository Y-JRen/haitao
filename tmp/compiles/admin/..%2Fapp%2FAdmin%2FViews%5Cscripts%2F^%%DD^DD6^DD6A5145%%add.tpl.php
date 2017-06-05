<?php /* Smarty version 2.6.19, created on 2014-12-31 10:22:30
         compiled from cost/add.tpl */ ?>
<form name="myForm1" id="myForm1">
<input type="hidden" name="logic_area" size="20" value="<?php echo $this->_tpl_vars['logic_area']; ?>
" />
<div class="title">商品管理 -&gt; 成本控制 -&gt; 调整成本申请</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
   
    <tr>
      <td width="10%"><strong>说明</strong></td>
      <td><textarea name="remark" id="remark" style="width: 400px;height: 50px"></textarea></td>
    </tr>
    <tr>
      <td colspan="2">
      <input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('controller'=>'product','action'=>'sel','type'=>'sel_cost','logic_area'=>$this->_tpl_vars['logic_area'],)));?>','ajax','查询商品',750,400);" value="查询添加商品">
      </td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td width="15%">删除</td>
        <td width="15%">产品编码</td>
        <td width="40%">产品名称</td>
        <td width="10%">产品批次</td>
        <td width="10%">当前成本</td>
        <td width="10%">调整成本</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table>
</div>
<div class="submit"><input type="button" name="dosubmit1" id="dosubmit1" value="提交" onclick="dosubmit()"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function dosubmit()
{
	if ($('remark').value == '') {
	    alert('说明不能为空！');
	    return false;
	}
	if(confirm('确认提交申请吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = 'disabled';
		ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');
	}
}

function failed()
{
	$('dosubmit1').value = '提交';
	$('dosubmit1').disabled = false;
}

var status="<?php echo $this->_tpl_vars['status']; ?>
";
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			var str = $('pinfo' + el[i].value).value;
			var pinfo = JSON.decode(str);
			if (pinfo.batch && pinfo.batch[0].batch_id == 0) {
			    pinfo.batch = '';
			}
			if (pinfo.batch) {
			    var batch = '';
			    for (j = 0; j < pinfo.batch.length; j++) {
			        if ($('sid' + pinfo.product_id + pinfo.batch[j].batch_id)) {
			            continue;
			        }
			        batch = pinfo.batch[j];
			        break;
			    }
			    if (batch == '') {
			        continue;
			    }
			    batch_id = batch.batch_id;
		    }
		    else {
		        batch_id = 0;
		        if ($('sid' + pinfo.product_id + batch_id)) {
				    continue;
			    }
		    }
		    
		    var id = pinfo.product_id + batch_id;
		    var tr = obj.insertRow(0);
		    tr.id = 'sid' + id;
		    for (var j = 0; j <= 5; j++) {
			    tr.insertCell(j);
		    }
		    
		    tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)"><input type="hidden" name="ids[]" value="'+pinfo.product_id+'" ><input type="hidden" name="status_id[]" value="'+pinfo.status_id+'" >';
		    tr.cells[1].innerHTML = pinfo.product_sn;
		    tr.cells[2].innerHTML = pinfo.product_name + ' <font color="red">(' + pinfo.goods_style + ')</font>';
		    
		    if (pinfo.batch) {
			    var box = '<select name="batch_id[]" id="box' + batch_id + '" onchange="changeBatch(' + pinfo.product_id + ', this)">';
			    for (j = 0; j < pinfo.batch.length; j++) {
			        if (pinfo.batch[j].batch_id == batch_id) {
			            selected = 'selected';
			        }
			        else {
			            selected = '';
			        }
				    box = box + '<option value="' + pinfo.batch[j].batch_id + '" ' + selected + '>' + pinfo.batch[j].batch_no + '</option>';
			    }
			    box = box + "</select>";
			    for (j = 0; j < pinfo.batch.length; j++) {
				    box = box + '<input type="hidden" id="stock_number' + pinfo.batch[j].batch_id + '" value="' + pinfo.batch[j].able_number + '">';
			    }
                
			    tr.cells[3].innerHTML = box;
			    pinfo.stock_number = batch.able_number;
		    }
		    else {
			    tr.cells[3].innerHTML = '无批次<select name="batch_id[]" style="display:none"><option value="0"></option></select>';
		    }
		    
		    tr.cells[4].innerHTML = '<input type="hidden" name="cost[]" value="'+ pinfo.cost +'"/><span>'+ pinfo.cost +'</span>';
		    tr.cells[5].innerHTML = '<input type="text" size="6" name="coston[]" id="number' + batch_id + '" value="'+ pinfo.cost +'" class="required" msg="不能为空" onblur="checkNum(this, ' + pinfo.stock_number + ')"/>';
		    obj.appendChild(tr);
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
	    obj.value = 0.00;
	    return false;
	}
	if (parseInt(obj.value) > parseInt(num)) {
	    alert('数量不能大于'+num);
	    obj.value = 0;
	    return false;
	}
}

function changeBatch(product_id, obj)
{
    var batch_id = obj.id.substring(3);
    
    if ($('sid' + product_id + obj.value)) {
        alert('已经有该批次，无法选择!');
        
        for (i = 0; i < obj.length; i++) {
            if (obj.options[i].value == batch_id) {
                obj.options[i].selected = true;
                break;
            }
        }
        return false;
    }
    
    obj.id = 'box' + obj.value;
    $('sid' + product_id + batch_id).id = 'sid' + product_id + obj.value;
    $('show_number' + product_id + batch_id).innerHTML = $('stock_number' + obj.value).value + '<input type="hidden" name="stock_number[]" value="' + $('stock_number' + obj.value).value + '">';
    $('show_number' + product_id + batch_id).id = 'show_number' + product_id + obj.value;
    $('number' + batch_id).value = 0;
    $('number' + batch_id).id = 'number' + obj.value;
}

</script>