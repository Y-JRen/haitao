<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm1" id="myForm1">
<input type="hidden" name="logic_area" value="{{$logic_area}}" />
<div class="title">仓储管理 -&gt; {{$area_name}} -&gt; 入库单申请</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    {{if $logic_area > 20}}
     <tr>
      <td width="10%"><strong>代销仓</strong> * </td>
      <td>
      <select name="logic_area" id="logic_area" onchange="ajax_search($('myForm1'),'/admin/logic-area-in-stock/add','ajax_search')">
        {{foreach from=$areas key=key item=item}}
        {{if $key > 20}}
        <option value="{{$key}}" {{if $key eq $logic_area}}selected{{/if}}>{{$item}}</option>
        {{/if}}
        {{/foreach}}
      </select>
      </td>
    </tr>
    {{/if}}
    <tr>
      <td width="10%" ><strong>单据类型</strong> * </td>
      <td>
      <select name="bill_type" id="bill_type" msg="请选择单据类型" class="required" onchange="changeBillType(this.value)">
        {{if $billType}}
        {{foreach from=$billType key=key item=item}}
        <option value="{{$key}}">{{$item}}</option>
        {{/foreach}}
        {{/if}}
      </select>
      </td>
    </tr>
    <tr id="distributionArea" style="display:none">
      <td><strong>直供商</strong></td>
      <td>
        <select name="distribution_id" id="distribution_id">
          {{foreach from=$shopDatas item=shop}}
          {{if $shop.shop_type eq 'distribution'}}
          <option value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
          {{/if}}
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr id="purchaseTypeArea">
      <td><strong>采购类型</strong></td>
      <td>
        <input type="radio" name="purchase_type" value="1" checked>经销
        <input type="radio" name="purchase_type" value="2">代销
      </td>
    </tr>
    <tr id="recipientArea">
      <td><strong>渠道接收方</strong> * </td>
      <td><input name="recipient" /> (如：京东)</td>
    </tr>
    {{if !empty($supplier)}}
    <tr id="gyccon">
      <td><strong>供货商</strong> * </td>
      <td>
      <select name="supplier_id" msg="请选择供货商" class="required" id="supplier_id" onchange="changeSupplier(this.value)">
       <option value="">请选择</option>
      {{foreach from=$supplier item=s key=key }}
		  {{if $s.status==0}}
		 	 <option value="{{$s.supplier_id}}"  {{if $key eq '0'}} selected {{/if}} >{{$s.supplier_name}}</option>
		  {{/if}}
      {{/foreach}}
      </select>
      </td>
    </tr>
    {{/if}} 
    <tr id="deliveryDate">
      <td><strong>预计到货日期</strong> * </td>
      <td>
        <input type="text" name="delivery_date" id="delivery_date" size="15" class="Wdate" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr id="supplierInfo">
      <td><strong>供货商付款信息</strong></td>
      <td>
        银行：<input type="text" size="15" name="bank_name" id="bank_name" value="{{$bank.bank_name}}">&nbsp;
        银行帐户：<input type="text" size="20" name="bank_account" id="bank_account" value="{{$bank.bank_account}}">&nbsp;
        银行帐号：<input type="text" size="35" name="bank_sn" id="bank_sn" value="{{$bank.bank_sn}}">
      </td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td><textarea name="remark" style="width: 400px;height: 50px"></textarea></td>
    </tr>
    <tr>
      <td colspan="2">
      <input type="button" onclick="openDiv('{{url param.controller=product param.action=sel param.type=sel param.logic_area=$logic_area}}/sid/'+$('supplier_id').value,'ajax','查询商品',750,400);" value="查询添加商品">
      </td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table" >
<thead>
    <tr>
        <td>删除</td>
        <td>产品编码</td>
        <td>产品名称</td>
        <td>产品批次</td>
        <td>入库单价</td>
        <td>申请数量</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table>
</div>

<div class="submit">
  <input type="button" name="dosubmit1" id="dosubmit1" value="提交" onclick="dosubmit()"/> <input type="reset" name="reset" value="重置" />
</div>
</form>
<script language="JavaScript">


function dosubmit()
{
	if(confirm('确认提交申请吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = 'disabled';
		ajax_submit($('myForm1'),'{{url}}');
	}
}
function failed()
{
	$('dosubmit1').value = '提交';
	$('dosubmit1').disabled = false;
}

function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			var str = $('pinfo' + el[i].value).value;
			var pinfo = JSON.decode(str);
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
		    for (var j = 0;j <= 5; j++) {
			    tr.insertCell(j);
		    }
		    tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)"><input type="hidden" name="ids[]" value="'+id+'" ><input type="hidden" name="product_id[]" value="'+pinfo.product_id+'" >';
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
				    box = box + '<input type="hidden" id="purchase_cost' + pinfo.batch[j].batch_id + '" value="' + pinfo.batch[j].purchase_cost + '">';
			    }
			    tr.cells[3].innerHTML = box;
			    pinfo.price = batch.purchase_cost;
		    }
		    else {
			    tr.cells[3].innerHTML = '无批次<select name="batch_id[]" style="display:none"><option value="0"></option></select>';
			    pinfo.price = pinfo.purchase_cost;
		    }
		    if (document.getElementById('bill_type').value == '2' || document.getElementById('bill_type').value == '5' || document.getElementById('bill_type').value == '14' || document.getElementById('bill_type').value == '16' || document.getElementById('bill_type').value == '17') {
			    tr.cells[4].innerHTML = '<input type="text" size="6" name="price[]" id="price' + id + '" value="' + pinfo.price + '" class="required" msg="不能为空" onchange="caluAmount()"/>';
		    }
		    else {
			    tr.cells[4].innerHTML = pinfo.cost + '<input type="hidden" name="price[]" id="price' + id + '" value="'+pinfo.cost+'" >';
		    }
		    tr.cells[5].innerHTML = '<input type="text" size="6" name="number[]" value="10" class="required" msg="不能为空" onblur="checkNum(this)"  onchange="caluAmount()"/>';
		    obj.appendChild(tr);
		}
	}
	
	//caluAmount();
}
function removeRow(id)
{
	$('sid' + id).destroy();
	
	//caluAmount();
}
function checkNum(obj, num)
{
	if (parseInt(obj.value) == 0 || isNaN(obj.value)) {
	    alert('请填写正整数');
	    obj.value = 0;
	    return false;
	}
}
function changeSupplier(supplier_id)
{
    if ($('bill_type').value == '2') {
        new Request({url: '/admin/logic-area-in-stock/get-supplier/supplier_id/' + supplier_id,
                    method:'get' ,
                    evalScripts:true,
                    onSuccess: function(responseText) {
                        if (responseText == '') return false;
                        var pinfo = JSON.decode(responseText);
                        $('bank_name').value = pinfo.bank_name;
                        $('bank_account').value = pinfo.bank_account;
                        $('bank_sn').value = pinfo.bank_sn;
                    }
        }).send();
    }
}
function changeBillType(bill_type)
{
    if (bill_type == 2 || bill_type == 5) {
        $('supplierInfo').style.display = "";
        $('deliveryDate').style.display = "";
        $('purchaseTypeArea').style.display = "";
        changeSupplier($('supplier_id').value);
        $('gyccon').style.display="";
    }
    else {     
        if(bill_type == 3){
   		 $('gyccon').style.display="";
   		}else{
   	     $('gyccon').style.display="none";
   	 	 $('supplierInfo').style.display = "none";
     	 $('deliveryDate').style.display = "none";
      	$('purchaseTypeArea').style.display = "none";
      	$('supplier_id').value = "";
   		}    
    }
    
    if (bill_type == 5) {
        $('recipientArea').style.display = "";
    }
    else {
        $('recipientArea').style.display = "none";
    }
    
    if (bill_type == 16) {
        $('distributionArea').style.display = "";
    }
    else {
        $('distributionArea').style.display = "none";
    }
}

function caluAmount()
{
    return false;
    
    var amount = 0;
    
    for (var i = 0; i < document.getElementsByName('price[]').length; i++) {
        amount += document.getElementsByName('price[]')[i].value * document.getElementsByName('number[]')[i].value;
    }
    
    $('amount').value = amount;
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
    $('price' + product_id + batch_id).value = $('purchase_cost' + obj.value).value;
    $('price' + product_id + batch_id).id = 'price' + product_id + obj.value;
}

changeBillType($('bill_type').value);

</script>