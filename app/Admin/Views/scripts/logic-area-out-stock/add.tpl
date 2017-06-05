<form name="myForm1" id="myForm1">
<input type="hidden" name="logic_area" size="20" value="{{$logic_area}}" />
<div class="title">仓储管理 -&gt; {{$area_name}} -&gt; 出库单申请</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    {{if $logic_area > 20}}
     <tr>
      <td width="10%"><strong>代销仓</strong> * </td>
      <td>
      <select name="logic_area" id="logic_area" onchange="ajax_search($('myForm1'),'/admin/logic-area-out-stock/add','ajax_search')">
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
      <td width="10%"><strong>单据类型 </strong> * </td>
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
    {{if $logic_area <= 10}}
    <tr style="display:none" id="recipientArea">
      <td><strong>接收方</strong> * </td>
      <td> 
        <!--<input name="recipient" value=""/> (如：京东)-->
        <select name="recipient">
        {{foreach from=$areas key=key item=item}}
          {{if $key > 20}}
          <option value="{{$key}}">{{$item}}</option>
          {{/if}}
        {{/foreach}}
        </select>
      </td>
    </tr>
    {{/if}}
    {{if !empty($supplier)}}
    <tr id="gyccon">
      <td><strong>供货商</strong> </td>
      <td>
      <select name="supplier_id" msg="请选择供货商" class="required" id="supplier_id">
	  <option value="">请选择...</option>
      {{foreach from=$supplier item=s}}
      {{if $s.status==0}}
      <option value="{{$s.supplier_id}}">{{$s.supplier_name}}</option>
      {{/if}}
      {{/foreach}}
      </select>
      </td>
    </tr>
    {{/if}} 
    <tr>
      <td><strong>备注</strong> * </td>
      <td style="font-size:16px"><textarea name="remark" style="width: 400px;height: 50px" msg="请填写备注" class="required"></textarea></td>
    </tr>
    <tr>
      <td colspan="2">
      <input type="button" onclick="openDiv('{{url param.controller=product param.action=sel param.type=sel_stock param.logic_area=$logic_area}}/sid/'+$('supplier_id').value,'ajax','查询商品',750,400);" value="查询添加商品">
      {{if $logic_area eq '1' && $type eq 2}}<input type="button" onclick="openDiv('/admin/goods/sel/type/2/','ajax','查询组合商品',750,450,true,'sel');" value="查询添加组合商品">{{/if}}
      </td>
    </tr>
</tbody>
</table>
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
<tbody id="list"></tbody>
</table>
<!--
<table id="transport_checkbox">
<tr>
  <td width="150"  height="30">是否填写运输单：</td>
  <td><input type="checkbox" id="check_transport" name="check_transport" value="1"   onclick="checktransport()" /></td>
</tr>
</table>
-->

<div id="recipient_span" style="display:none">
    <div class="title">请填写运输单信息</div>
        <input type="hidden" name="transport[bill_type]" size="20" value="2" />
        <input type="hidden" name="transport[logistic_status]" size="20" value="1" />
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
        <tbody>
            <tr>
            <td width="10%">收货人姓名</td>
            <td><input type="text" name="transport[consignee]" size="20" maxlength="40" value="" class="required" msg="请填写收货人姓名"/></td>
            </tr>
            <tr>
            <td width="10%">派送区域</td>
            <td>
            <select name="transport[province_id]" onchange="getArea(this)">
                <option value="">请选择省</option>
                {{html_options options=$province}}
            </select>
            <select name="transport[city_id]" onchange="getArea(this)">
                <option value="">请选择市</option>
                {{html_options options=$city}}
            </select>
            <select name="transport[area_id]" class="required" msg="请选择省市区" id="area_id">
                <option value="">请选择区</option>
                {{html_options options=$area}}
            </select>
            </td>
            </tr>
            <tr>
            <td width="10%">详细地址</td>
            <td><input type="text" name="transport[address]" size="50" maxlength="100" value="" class="required" msg="请填写详细地址" id="address" /></td>
            </tr>
            <tr>
            <td width="10%">电话</td>
            <td><input type="text" name="transport[tel]" size="20" maxlength="40" value="" /></td>
            </tr>
            <tr>
            <td width="10%">手机</td>
            <td><input type="text" name="transport[mobile]" size="20" maxlength="20" value="" /></td>
            </tr>
            <tr>
              <td width="10%">发货信息</td>
              <td>
              代收货款：<select name="transport[is_cod]" id="is_cod"><option value="0">否</option><option value="1">是</option></select>
              发货件数：<input type="text" name="transport[number]" size="4" maxlength="6" value="1"  class="required number" msg="请填写发货件数" />
              重量：<input type="text" name="transport[weight]" size="6" maxlength="6" value="" id="weight" class="required number" msg="请填写重量" /> kg
              体积：<input type="text" name="transport[volume]" size="6" maxlength="6" value="" /> m&sup3;
              </td>
            </tr>
            <tr>
              <td width="10%">商品信息</td>
              <td>
              商品数量：<input type="text" name="transport[goods_number]" size="4" maxlength="4" value="" class="required number" msg="请填写商品数量" />
              订单金额：<input type="text" name="transport[amount]" size="6" maxlength="6" value="" id ="amount" class="required number" msg="请填写商品总价" /> 元
              </td>
            </tr>
            <tr>
            <td width="10%">物流派单</td>
            <td><span id="showList"></span> <input type="hidden" name="logistic_code" id="logistic_code" value="">
			<input type="button" value="匹配物流公司" onclick="getLogistic()" /></td>
            </tr>
            <tr>
            <td width="10%">运单号</td>
            <td><input type="text" name="transport[logistic_no]" id="logistic_no" size="40" maxlength="50" value="" class="required" msg="请填写运单号" /></td>
            </tr>
            <tr>
              <td width="10%">物流备注</td>
              <td><textarea name="transport[remark]" style="width: 400px;height: 50px"></textarea></td>
            </tr>
        </tbody>
       </table>
   
</div>

</div>
<div class="submit">
  <!--<span id="totalAmount">退货应收款总金额：<input type="text" name="amount" id="tuihuo_amount" value="0.00"  size="6">&nbsp;&nbsp;</span>-->
  <input type="button" name="dosubmit1" id="dosubmit1" value="提交" onclick="dosubmit()"/> 
  <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function dosubmit()
{
/*
	if ($('bill_type').value == 2) {
	    if ($('amount').value =='') {
	        alert('总金额必须填写');
	        return false;
	    }
	}
	
	var check_transport = $('check_transport').checked;
	if(check_transport=='1'){
		if(!$('logistic')){alert('请匹配物流公司');return false;}
	}
	
*/
	if(confirm('确认提交申请吗？')){
		//$('dosubmit1').value = '处理中';
		//$('dosubmit1').disabled = 'disabled';
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
		    if ($('pinfo' + el[i].value) != null) {
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
    		    for (var j = 0; j <= 7; j++) {
    			    tr.insertCell(j);
    		    }
                
    			tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeTuirow(this)"><input type="hidden" name="ids[]" value="'+pinfo.product_id+'"><input type="hidden" name="status_id[]" value="'+pinfo.status_id+'"><input type="hidden" name="product_type[]" value="product">';
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
    				    box = box + '<input type="hidden" id="purchase_cost' + pinfo.batch[j].batch_id + '" value="' + pinfo.batch[j].purchase_cost + '">' 
    				              + '<input type="hidden" id="stock_number' + pinfo.batch[j].batch_id + '" value="' + pinfo.batch[j].able_number + '">';
    			    }
                    
    			    tr.cells[3].innerHTML = box;
    			    pinfo.price = batch.purchase_cost;
    			    pinfo.stock_number = batch.able_number;
    		    }
    		    else {
    			    tr.cells[3].innerHTML = '无批次<select name="batch_id[]" style="display:none"><option value="0"></option></select>';
    			    pinfo.price = pinfo.purchase_cost;
    		    }
    			tr.cells[4].innerHTML = pinfo.status_name;
    			//tr.cells[5].innerHTML = '<span id="show_number' + id + '">' + pinfo.stock_number + '<input type="hidden" name="stock_number[]" value="' + pinfo.stock_number + '"></span>';
    			tr.cells[5].innerHTML = '<span id="show_number' + id + '">' + pinfo.able_number + '<input type="hidden" name="stock_number[]" value="' + pinfo.able_number + '"></span>';
    			
    			if (document.getElementById('bill_type').value == '2') {
    			    tr.cells[6].innerHTML = '<input type="text" size="6" name="price[]" id="price' + id + '" value="' + pinfo.price + '" class="required" msg="不能为空" onchange="caluAmount()"/>';
    			}
    			else {
    			    tr.cells[6].innerHTML = pinfo.cost + '<input type="hidden" name="price[]" id="price' + id + '" value="'+pinfo.cost+'" >';
    			}
    			//tr.cells[7].innerHTML = '<input type="text" size="6" name="number[]" id="number' + batch_id + '" value="0" class="required" msg="不能为空" onblur="checkNum(this, ' + pinfo.stock_number + ')" onchange="caluAmount()"/>';
    			tr.cells[7].innerHTML = '<input type="text" size="6" name="number[]" id="number' + batch_id + '" value="0" class="required" msg="不能为空" onblur="checkNum(this, ' + pinfo.able_number + ')" onchange="caluAmount()"/>';
    			obj.appendChild(tr);
		    }
		    else if ($('ginfo' + el[i].value) != null) {
		        var str = $('ginfo' + el[i].value).value;
		        
		        var ginfo = JSON.decode(str);
			    if ($('gid' + el[i].value)) {
			        continue;
			    }
			    
    		    var tr = obj.insertRow(0);
    		    tr.id = 'gid' + el[i].value;
    		    for (var j = 0; j <= 7; j++) {
    			    tr.insertCell(j);
    		    }
    			tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeTuirow(this)"><input type="hidden" name="ids[]" value="' + el[i].value + '"><input type="hidden" name="status_id[]" value="2"><input type="hidden" name="product_type[]" value="groupgoods" >';
    			tr.cells[1].innerHTML = ginfo.goods_sn;
    			tr.cells[2].innerHTML = ginfo.goods_name;
    			tr.cells[3].innerHTML = '无批次<select name="batch_id[]" style="display:none"><option value="0"></option></select>';
			    tr.cells[4].innerHTML = '正常';
			    tr.cells[5].innerHTML = 'N/A<input type="hidden" name="stock_number[]" value="99999">';
			    tr.cells[6].innerHTML = ginfo.price + '<input type="hidden" name="price[]" value="'+ginfo.price+'" onchange="caluAmount()">';
			    tr.cells[7].innerHTML = '<input type="text" size="6" name="number[]" id="number' + '0' + '" value="0" class="required" msg="不能为空" onblur="checkNum(this, ' + 99999 + ')" onchange="caluAmount()"/>';
    			obj.appendChild(tr);
		    }
		    else {
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
function changeBillType(bill_type)
{
    if (bill_type == 2) {
        //$('totalAmount').style.display = "";
        $('gyccon').style.display="";
    }
    else {
       // $("totalAmount").style.display='none';
    	if(bill_type == 3){
    		 $('gyccon').style.display="";
    	}else{
    		 //$('totalAmount').style.display = "none";
    	     $('gyccon').style.display="none";
    	     $('supplier_id').value = "";
    	}       
    }
    
    {{if $logic_area <= 10}}
    if (bill_type == 10) {
        $('recipientArea').style.display = "";
        //$('transport_checkbox').style.display = "";
    }
    else {
        $('recipientArea').style.display = "none";
        //$('transport_checkbox').style.display = "none";
        $('recipient_span').style.display = "none";
    }
    {{/if}}
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
    $('show_number' + product_id + batch_id).innerHTML = $('stock_number' + obj.value).value + '<input type="hidden" name="stock_number[]" value="' + $('stock_number' + obj.value).value + '">';
    $('show_number' + product_id + batch_id).id = 'show_number' + product_id + obj.value;
    $('number' + batch_id).value = 0;
    $('number' + batch_id).id = 'number' + obj.value;
}

function checktransport(){
	var check_transport = $('check_transport').checked;
	if(check_transport=='1'){
		$('recipient_span').setStyle('display','inline-block');
	}else{
		$('recipient_span').setStyle('display','none');
	}
}
function getLogistic()
{
    var area_id = $('area_id').value;
    var address = $('address').value;
    var amount = $('amount').value;
    var weight = $('weight').value;
    var is_cod = $('is_cod').value;
    new Request({
        url: '/admin/transport/get-logistic/is_cod/' + is_cod,
        method: 'post',
        data: 'area_id='+area_id+'&address='+address+'&amount='+amount+'&weight='+weight,
        onRequest: loading,
        onSuccess:function(data){
	        if (data != '') {
	            $('showList').innerHTML = data;
	            
	        }
            loadSucess();
        }
    }).send();
}
function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    new Request({
        url: '/admin/member/area/id/' + value,
        onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
                    option.value = index;
                    option.text  = item;
                    select.options.add(option);
	            });
	        }
            loadSucess();
        }
    }).send();
}
changeBillType($('bill_type').value);

function caluAmount()
{
    return;
    var amount = 0;
    for (var i = 0; i < document.getElementsByName('price[]').length; i++) {
        amount += document.getElementsByName('price[]')[i].value * document.getElementsByName('number[]')[i].value;
    }

    document.getElementById("tuihuo_amount").value = amount;
}

function removeTuirow(obj)
{
    obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
    caluAmount();
}
</script>