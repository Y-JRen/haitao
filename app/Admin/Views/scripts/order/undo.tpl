<div class="title">订单商品恢复</div>
<form id="myform1">
<table cellpadding="0" cellspacing="0" border="0" class="table">
<tr>
<th align="left">商品名称</th>
<th align="left">商品编号</th>
<th align="left">销售价</th>
<th align="left">数量</th>
<th align="left">总金额</th>
<th align="left">可用库存</th>
<th align="left">修改后单品金额</th>
<th align="left">修改后数量</th>
</tr>
{{foreach from=$product item=item}}
<tr {{if $item.product_id}}id="sid{{$item.product_id}}"{{/if}}>
<td>{{$item.goods_name}}</td>
<td>{{$item.product_sn}}</td>
<td>{{$item.sale_price}}</td>
<td>{{$item.number}}</td>
<td>{{$item.amount}}</td>
<td>{{$item.able_number}}</td>
<td>
{{if $item.product_id || $item.offers_type=='fixed-package' || $item.offers_type=='choose-package'}}
<input type='text' id="goods_{{$a.order_batch_goods_id}}" name='data[old][{{$item.order_batch_goods_id}}][sale_price]' size="6" value="{{$item.sale_price}}" readonly />
{{/if}}
</td>
<td>
	{{if $item.product_id || $item.offers_type=='fixed-package' || $item.offers_type=='choose-package'}}
	<input type='text' id="goods_{{$a.order_batch_goods_id}}" name='data[old][{{$item.order_batch_goods_id}}][number]' value="{{$item.number}}" readonly
	onkeyup="
	if(this.value==0){
		this.setStyle('background', '#ff0000');
	}else{
		this.setStyle('background', '#ffffff');
	}
	{{if $item.offers_type!='fixed-package' && $item.offers_type!='choose-package'}}
		check(this, '{{$item.able_number}}');
		{{if $item.child.0.offers_type=='buy-gift'}}
			$('goods_{{$item.child.0.order_batch_goods_id}}').value = this.value;
		{{/if}}
	{{else}}
		if (this.value>1) {
			this.value = 1;
		} else if (this.value<0) {
			this.value = 0;
		}
		{{foreach from=$item.child item=tmp}}
			$('goods_{{$tmp.order_batch_goods_id}}').value = this.value;
		{{/foreach}}
	{{/if}}
	" size="3">
	{{/if}}</td>
</tr>
{{if  $item.child}}
	{{foreach from=$item.child item=a}}
	<tr>
	<td style="padding-left:40px">{{$a.goods_name}}</td>
	<td>{{$a.product_sn}}</td>
	<td>{{$a.sale_price}}</td>
	<td>{{$a.number}}</td>
	<td>{{$a.amount}}</td>
	<td>{{$a.able_number}}</td>
	<td>&nbsp;</td>
	<td>
		{{if $a.product_id}}
			{{if $a.offers_type=='fixed-package' && $a.offers_type!='choose-package'}}
			<input type='text' id="goods_{{$a.order_batch_goods_id}}" name='data[old][{{$a.order_batch_goods_id}}][number]' value="{{$a.number}}" onkeyup="check(this, '{{$a.able_number}}')"  size="3" readonly>
			{{else}}
			<input type='text' id="goods_{{$a.order_batch_goods_id}}" name='data[old][{{$a.order_batch_goods_id}}][number]' value="{{$a.number}}" onkeyup="check(this, '{{$a.able_number}}')"  size="3" readonly>
			{{/if}}
		{{/if}}	</td>
	</tr>
	{{/foreach}}
{{/if}}
{{/foreach}}
</table>
<br />
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>删除</td>
        <td>商品编码</td>
        <td>商品名称</td>
        <td>单品价格</td>
        <td>可用库存</td>
        <td>修改后单品价格</td>
        <td>数量</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table><br />
<table>
    <!--
	<tr>
		<td><input type="button" onclick="openDiv('/admin/product/sel/','ajax','选择换货商品',750,400);" value=" 添加商品 " name="do"/></td><td></td>
	</tr>
	<tr>
		<td>修改价格理由：</td><td><textarea name="note" id="note"></textarea> <font color="#FF0000">如果修改了商品价格请填写修改理由</font></td>
	</tr>
	-->
	<tr>
		<td></td>
		<td>
			<input type='hidden' name='type' value="submit">
			
			<input type="button" value="确定" onclick="ajax_submit($('myform1'),'{{url param.action=undo}}')" />
			<input type="button" onclick="G('{{url param.action=info}}')" value=" 返回订单页 " name="do"/>
		</td>
	</tr>
</table>

</div>
</form>
<script language="JavaScript">

function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++)
	{
		if (el[i].checked)
		{
			var id = el[i].value;
			var str = $('pinfo' + id).value;
			var pinfo = JSON.decode(str);
			if ($('sid' + pinfo.product_id))
			{
				continue;
			}
			else
			{
			    var tr = obj.insertRow(0);
			    tr.id = 'sid' + pinfo.product_id;
			    for (var j = 0;j <= 9; j++)
				{
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.product_id +')"><input type="hidden" name="product_id[]" value="'+pinfo.product_id+'" >';
				tr.cells[1].innerHTML = pinfo.product_sn;
				tr.cells[2].innerHTML = pinfo.goods_name; 
				tr.cells[3].innerHTML = pinfo.price;
				tr.cells[4].innerHTML = pinfo.able_number;
				tr.cells[5].innerHTML = '<input type="text" size="6" name="data[new]['+ pinfo.product_id +'][sale_price]" value="'+ pinfo.price +'"/>';
				tr.cells[6].innerHTML = '<input type="text" size="3" name="data[new]['+ pinfo.product_id +'][number]" value="1" class="required" msg="不能为空" onkeyup="check(this, \''+pinfo.able_number+'\')"/>';
				obj.appendChild(tr);
			}
		}
	}
}
function removeRow(id)
{
	$('sid' + id).destroy();
}


function check(obj, able_number) 
{
	able_number = parseInt(able_number);
	var v = obj.value
	
    if (parseInt(v) < 0 || isNaN(v)) {
	    alert('请填写正整数');
	    obj.value = able_number;
	    return false;
	}
	
	if (v > able_number) {
		alert('修改数量不能大于可用库存');
		obj.value = able_number;
		return false;
	}
}

</script>
