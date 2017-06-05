<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">{{if $action eq 'add'}}添加新批次{{else}}编辑批次{{/if}}</div>
<div class="content">
<form name="myForm" id="myForm" action="{{url}}" method="post">
<table cellpadding="0" cellspacing="0" border="0" class="table">
<tbody>
  {{if $action eq 'edit'}}
  <tr>
    <td><strong>批次号</strong> *</td>
    <td>{{$data.batch_no}}</td>
  </tr>
  <tr>
    <td><strong>条形码</strong> *</td>
    <td>{{$data.barcode}}</td>
  </tr>
  {{/if}}
  <tr>
    <td width="12%"><strong>产品名称</strong> *</td>
    <td>
      <input type="text" name="product_name" id="product_name" size="40" value="{{$data.product_name}}" readonly/>
      <input type="hidden" name="product_id" id="product_id" value="{{$data.product_id}}">
      {{if $action eq 'add'}}
      <input type="button" onclick="openDiv('/admin/product/sel/justOne/1/returnStock/1','ajax','选择产品',750,400);" value="选择产品">
      {{/if}}
    </td>
  </tr>
  <tr>
    <td><strong>产品编号</strong> *</td>
    <td>
      <input type="text" name="product_sn" id="product_sn" size="8" value="{{$data.product_sn}}" readonly/>
      <span id="note"></span>
    </td>
  </tr>
  <tr>
    <td><strong>产品规格</strong> *</td>
    <td><input type="text" id="goods_style" size="15" value="{{$data.goods_style}}" readonly/></td>
  </tr>
  <tr>
    <td><strong>供应商</strong> *</td>
    <td id="supplierBox">
      <select name="supplier_id" id="supplier_id">
        {{foreach from=$supplierData item=s}}
 	    <option value="{{$s.supplier_id}}" {{if $data.supplier_id eq $s.supplier_id}}selected{{/if}}>{{$s.supplier_name}}</option>
        {{/foreach}}
      </select>
    </td>
  </tr>
  <tr>
    <td><strong>进货价</strong></td>
    <td><input type="text" name="cost" id="cost" size="5" value="{{if $data.cost}}{{$data.cost}}{{else}}0{{/if}}"/></td>
  </tr>
  <tr>
    <td><strong>未税价</strong></td>
    <td><input type="text" name="cost_tax" id="cost_tax" size="5" value="{{if $data.cost_tax}}{{$data.cost_tax}}{{else}}0{{/if}}"/></td>
  </tr>
  <tr>
    <td><strong>发票税率</strong></td>
    <td><input type="text" name="invoice_tax_rate" id="invoice_tax_rate" size="5" value="{{if $data.invoice_tax_rate}}{{$data.invoice_tax_rate}}{{else}}0{{/if}}"/></td>
  </tr>
  <tr>
    <td><strong>生产日期</strong></td>
    <td><input type="text" name="product_date" id="product_date" size="15" value="{{$data.product_date|date_format:"%Y-%m-%d"}}" class="Wdate" onClick="WdatePicker()"/></td>
  </tr>
  <tr>
    <td><strong>有效日期</strong></td>
    <td><input type="text" name="expire_date" id="expire_date" size="15" value="{{$data.expire_date|date_format:"%Y-%m-%d"}}" class="Wdate" onClick="WdatePicker()"/></td>
  </tr>
  <tr> 
    <td><strong>是否启用</strong> *</td>
    <td>
	 <input type="radio" name="status" value="0" {{if $data.status eq '0' or $action eq 'add'}}checked{{/if}}/> 是
	 <input type="radio" name="status" value="1" {{if $data.status eq '1'}}checked{{/if}}/> 否
	</td>
  </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" onclick="return check()"/> <input type="reset" name="reset" value="重置" /></div>
<script language="JavaScript">
function addRow(id)
{
	var value = document.getElementById(id).value;
	var pinfo = JSON.decode(value);
	
	document.getElementById('product_name').value = pinfo.product_name;
	document.getElementById('product_sn').value = pinfo.product_sn;
	document.getElementById('product_id').value = pinfo.product_id;
	document.getElementById('goods_style').value = pinfo.goods_style;

	if (pinfo.productBatch == 2) {
	    document.getElementById('note').innerHTML = '<font color="red">该产品没有创建过批次，请先为当前库存创建一个默认批次！</font>';
	}
	else {
	     document.getElementById('note').innerHTML = '';
	}
	
	createSupplierBox(pinfo.product_id, 0);
}
function check()
{
    if (document.getElementById('product_id').value == '') {
        alert('请先选择产品!');
        return false;
    }
    
    if (document.getElementById('supplier_id').value == '') {
        alert('请先维护该产品的供应商!');
        return false;
    }
    
    return true;
}

function createSupplierBox(product_id, supplier_id)
{
    new Request({url: '/admin/product/supplier-box/product_id/' + product_id + '/supplier_id/' + supplier_id,
                 method:'get',
                 evalScripts:true,
                 onSuccess: function(responseText) {
                     document.getElementById('supplierBox').innerHTML = responseText;
                 }
        }).send();
}

{{if $action eq 'edit'}}
createSupplierBox({{$data.product_id}}, {{$data.supplier_id}});
{{/if}}

</script>