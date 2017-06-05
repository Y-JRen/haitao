<table>
  <tr>
    <th width="150">订单号：</th>
    <td>{{$order.batch_sn}}</td>
  </tr>
  <tr>
  <tr>
    <th width="150">商品金额：</th>
    <td>￥{{$order.price_goods}}</td>
  </tr>
  <tr>
    <th>行邮税：</th>
    <td>￥{{$order.tax}}</td>
  </tr>
  <tr>
    <th>运费：</th>
    <td>￥{{$order.price_logistic}}</td>
  </tr>
  <tr>
    <th>订单金额：</th>
    <td>￥{{$order.price_order}}</td>
  </tr>
  <tr>
    <th>调整金额：</th>
    <td>￥{{$order.price_adjust}}</td>
  </tr>
  <tr>
    <th>已支付金额：</th>
    <td>￥{{$order.price_payed+$order.price_from_return}}</td>
  </tr>
  <tr>
    <th>帐户余额抵扣：</th>
    <td>￥{{$order.account_payed}}</td>
  </tr>
  <tr>
    <th>积分抵扣：</th>
    <td>￥{{$order.point_payed}}</td>
  </tr>
  <tr>
    <th>礼品卡抵扣：</th>
    <td>￥{{$order.gift_card_payed}}</td>
  </tr>
<tr>
  <th>是否满意不退货：</th>
  <td>{{if $order.is_fav eq 1}}<font color="#FF0000">是</font>{{else}}否{{/if}}</td>
</tr>
</table>
<br />
<table >
<tr bgcolor="#F0F1F2"><th width="150">收货人：</th>
<td>{{$order.addr_consignee}}</td>
</tr>
<tr><th>联系电话：</th>
<td colspan="2">{{$order.addr_tel}}</td></tr>
<tr bgcolor="#F0F1F2"><th>手机：</th>
<td colspan="2">{{$order.addr_mobile}}</td></tr>

<tr bgcolor="#F0F1F2"><th>收货地址：</th>
<td colspan="2">{{$order.addr_address}}</td></tr>
<tr><th>邮政编码：</th>
<td colspan="2">{{$order.addr_zip}}</td></tr>
</table>
<br />
<div class="title">退换货开单</div>
<form id="myform1">
<div class="content">
 <table cellpadding="0" cellspacing="0" border="0" class="table">
<tr>
<th align="left">商品名称</th>
<th align="left">商品编号</th>
<th align="left">商品销售均价</th>
<th align="left">销售价</th>
<th align="left">行邮税</th>
<th align="left">商品数量</th>
<th align="left">总金额</th>
<th align="left">可用库存</th>
<th align="left">退货数量</th>
</tr>
{{foreach from=$product item=item}}
{{if $item.offers_type != 'minus' && ($item.product_id > 0 || $item.type == 5)}}
<tr>
<td>{{$item.goods_name}}</td>
<td>{{$item.product_sn}}</td>
<td>{{$item.eq_price}}</td>
<td>{{$item.sale_price}}</td>
<td>{{$item.tax}}</td>
<td>{{$item.blance}}</td>
<td>{{$item.amount}}</td>
<td>{{$item.able_number}}</td>
<td>
{{if $item.product_id }}
<input type="hidden" id="priceReturn_{{$item.order_batch_goods_id}}" name="priceReturn" />
<input id="old" type="hidden" size="3" value="{{$item.number}}"/>
<input id="new" type="text" name="return[{{$item.order_batch_goods_id}}][number]" value="0" size="3" onchange="editReturnNum(this, '{{$item.blance}}', '{{$item.order_batch_goods_id}}', '{{$item.eq_price+$item.tax}}');" /><input type="hidden" name="return[{{$item.order_batch_goods_id}}][former_number]" value="{{$item.number}}" />
{{/if}}</td>
</tr>
{{if  $item.child}}
	{{foreach from=$item.child item=a}}
	{{if $a.product_id > 0}}
	<tr>
	<td style="padding-left:40px">{{$a.goods_name}}</td>
	<td>{{$a.product_sn}}</td>
	<td>{{$a.eq_price}}</td>
	<td>{{$a.sale_price}}</td>
	<td>{{$a.blance}}</td>
	<td>{{$a.amount}}</td>
	<td>{{$a.able_number}}</td>
	<td>
	{{if $a.product_id }}
	<input type="hidden" id="priceReturn_{{$a.order_batch_goods_id}}" name="priceReturn" />
	<input id="old" type="hidden" size="3" value="{{$a.number}}"/>
	<input id="new" type="text" name="return[{{$a.order_batch_goods_id}}][number]" value="0" size="3" onchange="editReturnNum(this, '{{$a.blance}}', '{{$a.order_batch_goods_id}}', '{{$a.eq_price+$a.tax}}');" />
	{{/if}}	</td>
	</tr>
	{{/if}}
	{{/foreach}}
{{/if}}
{{/if}}
{{/foreach}}
</table>
{{if $product && $order.type ne 16}}
<br />
<table>
<tr>
<td><input type="button" onclick="setWindow();" value="新增商品 " name="do"/></td>
</tr>
</table>
{{/if}}

<br />
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>删除</td>
        <td>商品编码</td>
        <td>商品名称</td>
        <td>单价</td>
        <td>最低限价</td>
        <td>可用库存</td>
        <td>销售价格</td>
        <td>行邮税</td>
        <td>数量</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table>

<br />
{{if $product}}
<table>
<tr style="display:none">
  <td width="150">是否质量问题：</td>
  <td><input type="checkbox" id="quality" value="1" onchange="setBlance()" /></td>
</tr>
<tr>
  <td id="blance_title">客户{{if $order.price_pay-$order.price_payed-$order.account_payed-$order.point_payed-$order.gift_card_payed ge 0}}需支付{{else}}应退款{{/if}}：</td>
  <td id="blance">
    {{$order.price_pay-$order.price_payed-$order.account_payed-$order.point_payed-$order.gift_card_payed|default:'0'}}
  </td>
</tr>
</table>
<div id="return_price_logistic_html" style="display:none">
<table>
<tr>
  <td width="150">是否退运费：</td>
  <td>
    <input type="checkbox" name="return_price_logistic" id="return_price_logistic" value="1" onclick="setBlance(1)">
  </td>
</tr>
</table>

</div>
<div id="price_logistic_html_true"></div>
<table>
{{if $order.type ne 16}}
<tr>
  <td width="150">调整金额：</td>
  <td>
  <input type=text id="priceAdjust" name='price_adjust_return' size="3" onkeyup="setBlance(1);"> 
   [负数：优惠减少金额]  [正数：订单需另增加的金额]</td>
</tr>
<tr>
  <td>调整金额备注：</td>
  <td>
  <input id="note_adjust_return" type=text name='note_adjust_return' size="60">  </td>
</tr>
{{/if}}
<tr>
  <td>退换货客服备注：</td>
  <td>
  <textarea id="note_staff" type=text name='note_staff' style="width: 300px;height: 50px" ></textarea> 
   </td>
</tr>
{{if $order.type ne 16}}
<tr>
  <td>修改价格理由：</td>
  <td><textarea name="note"></textarea>
    <font color="#FF0000">如果修改了新增商品的价格请填写修改理由</font></td>
</tr>
{{if $order.status_logistic eq 5}}
<tr>
  <td>物流丢件：</td>
  <td><input type="checkbox" name="is_lost" value="1">
    <font color="#FF0000">仓库虚拟入库，做盘亏处理，该操作不可逆，请自重！</font></td>
</tr>
{{/if}}
{{elseif $detail.other.price_must_pay > 0 && $order.status_logistic ne 5}}
<tr>
  <td>不发生退款：</td>
  <td><input type="checkbox" name="not_to_settlement" value="1" checked>
    <font color="#FF0000">如果该笔退货金额需要财务退款，请去除此选项</font></td>
</tr>
{{/if}}
</table>
{{/if}}
<div id="bank"></div>
<table>
<tr>
<td></td>
<td>
{{if $product}}
<input type="button" value="确定" onclick="this.disabled=false;if (check_form($('myform1'))) {ajax_submit($('myform1'),'{{url param.action=return-product param.type=1}}');}else{this.disabled=false;}" />
<input type='hidden' name='type' value="submit">
{{/if}}
<input type="button" onclick="G('{{url param.action=info}}')" value=" 返回订单页 " name="do"/></td>
</tr>
</table>
</div>
</form>
<input type="hidden" id="opendiv" />
<div id="return_back_price_logistic_htm_tmp" style="display:none;">
<table>
<tr>
  <td width="150">退还客户寄回来的运费：</td>
  <td>
  <input type="text" size="3" id="return_back_price_logistic" name="return_back_price_logistic" onkeyup="setBlance(1);"/>
  [负数：需退客户运费] [正数：需收取客户运费]
  </td>
</tr>
</table>
</div>
<div id="price_logistic_htm_tmp" style="display:none;">
<table>
<tr>
  <td width="150">新单运费：</td>
  <td>
  <input type="text" size="3" id="price_logistic" name="price_logistic" value="0" onkeyup="setBlance(1);"/>
  </td>
</tr>
</table>
</div>

<script language="JavaScript">
function editReturnNum(obj,ableNum,orderBatchGoodsID,price)
{
	var expand = 'expand_'+orderBatchGoodsID;
	var num = parseInt(obj.value);
	
	if (num > ableNum) {
		alert('退货数量不能大于可用数量');
		obj.value = ableNum;
		return false;
	} else if (num < 0) {
		alert('退货数量不能为负数');
		obj.value = 0;
		return false;
	}
	
	{{if $order.status_logistic == 5}}
    if (num != ableNum) {
		alert('拒收单退货数量必须等于商品总数量');
		obj.value = ableNum;
		return false;
	}
    {{/if}}

	var p = $(obj).getParent().getParent();
	if (num && !$(expand)) {
	    {{if $order.type ne 16}}
		var tr = new Element('tr',{id:'expand_'+orderBatchGoodsID,display:'block'});
		var td = new Element('td', {colspan:9});
		td.inject(tr);
		tr.inject(p, 'after');
		td.innerHTML += setreason(orderBatchGoodsID);
		{{/if}}
	} else if (!num) {
		obj.value = 0;
		$(expand).destroy();
	}
	var tmp = price * num;
	if (!tmp) tmp = 0;
	$('priceReturn_'+orderBatchGoodsID).value = tmp;
	setBlance();
}
function setreason(id)
{
	var html = '';
	
	html += '<table cellpadding="0" cellspacing="0">';
	html += '<tr>';
	{{foreach from=$reason item=data}}
	html += '<td><label><input name="return['+id+'][reason][{{$data.reason_id}}]" value="{{$data.reason_id}}" type="checkbox" onchange="{{if $data.reason_id==1}}setOtherReason(\''+id+'\');{{/if}}" />{{$data.label}}</label></td>';
	{{/foreach}}
	html += '</tr>';
	html += '<tr>';
	html += '<td colspan="4" id="other_reason_'+id+'"></td>';
	html += '</tr>';
	html += '</table>';

	return html;
}
function setOtherReason(id) {
	if ($('other_reason_'+id).innerHTML) {
		$('other_reason_'+id).innerHTML = '';
	} else {
		$('other_reason_'+id).innerHTML = '备注：<input name="return['+id+'][reason][other]" type="text" />';
	}
	
}
function setWindow(){
	var status = parseInt('{{$order.status_logistic}}');
	if (!status) status = 0;
	var payType = '{{$order.pay_type}}';
	//if (status==5 && payType=='cod') return false;//代收款拒收 
	{{if  $site_type}}  
		openDiv('/admin/product/sel/type/sel_stock/site_type/{{$site_type}}/shop_id/{{$order_info.shop_id}}/add_time/{{$order_info.add_time}}','ajax','选择换货商品',750,400);
	{{else}} 
		openDiv('/admin/product/sel/type/sel_stock/shop_id/{{$order_info.shop_id}}/add_time/{{$order_info.add_time}}','ajax','选择换货商品',750,400);
	{{/if}}
}
function addRow(){
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			var id = el[i].value;
			var str = $('pinfo' + id).value;
			var pinfo = JSON.decode(str);
			if ($('sid' + pinfo.product_id)) {
				continue;
			} else {
				var tr = obj.insertRow(0);
				tr.id = 'sid' + pinfo.product_id;
                var limit_price_str = pinfo.price_limit == 0 ? '无限价' : pinfo.price_limit;
				for (var j = 0;j <= 8; j++) {
					 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.product_id +')">';
				tr.cells[0].innerHTML+= '<input type="hidden" name="product_id[]" value="'+pinfo.product_id+'" >';
				tr.cells[1].innerHTML = pinfo.product_sn;
				tr.cells[2].innerHTML = pinfo.product_name; 
				tr.cells[3].innerHTML = pinfo.price;
                tr.cells[4].innerHTML = limit_price_str;
				tr.cells[5].innerHTML = pinfo.able_number;
				tr.cells[6].innerHTML = '<input type="hidden" id="priceChange_'+ pinfo.product_id +'" name="priceChange" value="'+eval(Number(pinfo.price)+Number(pinfo.tax))+'" ><input type="text" size="6" id="productPrice_'+ pinfo.product_id +'" name="change['+ pinfo.product_id +'][sale_price]" onkeyup="editChangePrice(\''+ pinfo.product_id +'\',this);" value="'+pinfo.price+'" onchange="changePrice('+pinfo.price_limit+', this, '+pinfo.price+')"/>';
				tr.cells[7].innerHTML = '<input type="text" readonly="readonly" name="change['+ pinfo.product_id +'][tax]" value="'+pinfo.tax+'" id="tax_'+pinfo.product_id+'" />';
				tr.cells[8].innerHTML = '<input id="productNumber_'+ pinfo.product_id +'" type="text" size="6" name="change['+ pinfo.product_id +'][number]" value="1" class="required" msg="不能为空" onkeyup="editChangeNum(\''+ pinfo.product_id +'\', this, '+pinfo.able_number+');"/>';
				
				if (pinfo.price_seg) {
				    tr.cells[3].innerHTML = tr.cells[3].innerHTML + " <a onmouseover=showTip(window.event,'price_seg_"+pinfo.product_id+"') onmouseout=closeTip('price_seg_"+pinfo.product_id+"')>(多数量价格)</a><div id=price_seg_"+pinfo.product_id+" style=display:none;background-color:#DDDDDD>"+pinfo.price_seg+"</div>";
				}
				
				obj.appendChild(tr);
			}
		}
	}
	
	setBlance();
	
}
function removeRow(id){
	$('sid' + id).destroy();
	setBlance();
}
function editChangeNum(productID,obj,ableNum){
	var num = parseInt(obj.value);
	var price = parseFloat($('productPrice_'+productID).value);
	var tax = parseFloat($("tax_"+productID).value);
	
	if (num > ableNum) {
		alert('换货数量不能大于最大库存数量');
		obj.value = ableNum;
		return false;
	} else if (num < 0) {
		alert('换货数量不能为负数');
		obj.value = 0;
		return false;
	}
	
	var tmp = (price+tax) * num;
	if (!tmp) tmp = 0;
	$('priceChange_'+productID).value = tmp;
	setBlance();
}
function editChangePrice(productID,obj){
	var num = parseInt($('productNumber_'+productID).value);
	var price = parseFloat(obj.value);
	var tax = parseFloat($("tax_"+productID).value);
	var tmp = (price+tax) * num;
	if (!tmp) tmp = 0;
	$('priceChange_'+productID).value = tmp;
	setBlance();
}
function setBlance(noSetLogistic) {
	var blance = 0;
    //代收款拒收 开始
	var status = parseInt('{{$order.status_logistic}}');
	if (!status) status = 0;
	var payType = '{{$order.pay_type}}';
    //代收款拒收 结束
    
    //整单退 开始
	var tmp_c = $('myform1').getElements('input[name^=change]');
	var c=false;
	for (var i=0; i<tmp_c.length; i++) {
		tmp = parseInt(tmp_c[i].value);
		if(tmp) c = true;
	}
	var o = $('myform1').getElements('input[id=old]');
	var n = $('myform1').getElements('input[id=new]');
	var pkg = true;
	for (var i=0; i<o.length; i++) {
		if (o[i].value != n[i].value) {
			pkg = false;
		}
	}
    //整单退 结束
	{{if $order.price_logistic > 0 && $order.price_payed+$order.account_payed+$order.point_payed+$order.gift_card_payed > 0}}
	if (pkg) {
	    if ($('return_price_logistic_html').style.display == 'none') {
	        $('return_price_logistic_html').style.display = '';
	        $('return_price_logistic').checked = true;
	    }
	}
	else {
	    $('return_price_logistic_html').style.display = 'none';
	    $('return_price_logistic').checked = false;
	}
	{{/if}}
	if (status==5 && payType=='cod'){//代收款拒收 
		return false;
	}
	else {
		var priceReturn = document.getElementsByName('priceReturn');//退货商品
		var priceChange = document.getElementsByName('priceChange');//换货商品
        
		var tmp = 0;
		if (priceReturn) {
		    if (pkg == true) {
		        blance = -{{$order.price_payed+$order.account_payed+$order.point_payed+$order.gift_card_payed|default:0}};
		        {{if $order.price_logistic > 0}}
		        if (!$('return_price_logistic').checked) {
		            if (blance < 0 && (blance * -1) > {{$order.price_logistic|default:0}}) {
		                blance = blance + {{$order.price_logistic|default:0}};
		            }
		        }
		        {{/if}}
		    }
		    else {
    			for (var i=0; i<priceReturn.length; i++) {
    				if (priceReturn[i].value) {
    					tmp = parseFloat(parseFloat(priceReturn[i].value).toFixed(2));
    					if(!tmp) tmp = 0;				
    					blance -= tmp;
    					blance = parseFloat(blance.toFixed(2));
    				}	
    			}
            }
		}
		if (priceChange) {
			for (var i=0; i<priceChange.length; i++) {
				if (priceChange[i].value) {
					tmp = parseFloat(parseFloat(priceChange[i].value).toFixed(2));
					if(!tmp) tmp = 0;
					blance += tmp;
					blance = parseFloat(blance.toFixed(2));
				}	
			}
		}
		var tmp_c = $('myform1').getElements('input[name^=change]');
		var tmpChange,temp;
		for (var i=0; i<tmp_c.length; i++) {
			temp = parseInt(tmp_c[i].value);
			if(temp) tmpChange = true;
		}
	}
	var priceAdjust = $('priceAdjust');//调整金额
	var return_back_price_logistic = $('return_back_price_logistic');
	var price_logistic = $('price_logistic');
	if (priceAdjust && priceAdjust.value) {
		tmp = parseFloat(parseFloat(priceAdjust.value).toFixed(2));
		if(!tmp) tmp = 0;
		blance += tmp;
		blance = parseFloat(blance.toFixed(2));
	}
	if (return_back_price_logistic && return_back_price_logistic.value) {
		tmp = parseFloat(parseFloat(return_back_price_logistic.value).toFixed(2));
		if(!tmp) tmp = 0;
		blance += tmp;
		blance = parseFloat(blance.toFixed(2));
	}
	if (price_logistic && price_logistic.value) {
		tmp = parseFloat(parseFloat(price_logistic.value).toFixed(2));
		if(!tmp) tmp = 0;
		blance += tmp;
		blance = parseFloat(blance.toFixed(2));
	}
	blance = blance + {{$order.price_pay-$order.price_payed-$order.account_payed-$order.point_payed-$order.gift_card_payed|default:0}};
	$('blance').innerHTML = blance;
	if (blance >= 0) {
	    $('blance_title').innerHTML = '客户需支付：';
	}
	else {
	    $('blance_title').innerHTML = '客户应退款：';
	}
	if (!noSetLogistic) setLogistic();
}

function setLogistic()
{
	var status = parseInt('{{$order.status_logistic}}');
	if (!status) status = 0;
	var payType = '{{$order.pay_type}}';
	if (status==5 && payType=='cod') return false;//代收款拒收 
	var statusReturn = parseInt('{{$order.status_return}}');
	if (!statusReturn) statusReturn = 0;
	var parentBatchSN = '{{$order.parent_batch_sn}}';//换货产生新单
	if (parentBatchSN) statusReturn = 1;

	var tmp_r = $('myform1').getElements('input[name^=return]');
	var tmp_c = $('myform1').getElements('input[name^=change]');
	var tmp, r, c;
	for (var i=0; i<tmp_r.length; i++) {
		tmp = parseInt(tmp_r[i].value);
		if(tmp) r = true;
	}
	for (var i=0; i<tmp_c.length; i++) {
		tmp = parseInt(tmp_c[i].value);
		if(tmp) c = true;
	}
	var pay = parseFloat('{{$order.price_pay}}');
	
	if (c) {//换货
	    if ($('price_logistic_html_true').innerHTML == '') {
	        $('price_logistic_html_true').innerHTML = $('price_logistic_htm_tmp').innerHTML;
	    }
	}
	else {
	    $('price_logistic_html_true').innerHTML = '';
	}
}
function check_form(obj) {
	if ($('note_staff').value == '') {
		alert('客服备注不能为空');
		return false;
	}
	if ($('myform1').getElement('input[name=return_back_price_logistic]')) {
		var tmp_2 = parseFloat($('myform1').getElement('input[name=return_back_price_logistic]').value);
		if (isNaN(tmp_2)) {
			alert('退还客户寄回来的运费 不能为空');
			return false;
		}
	}
	if ($('myform1').getElement('input[name=price_logistic]')) {
		var tmp_3 = parseFloat($('myform1').getElement('input[name=price_logistic]').value);
		if (isNaN(tmp_3)) {
			alert('新单快递费 不能为空');
			return false;
		}
	}
	if ($('priceAdjust')) {
    	var price_adjust_return = parseFloat($('priceAdjust').value);
    	var note_adjust_return = $('note_adjust_return').value;
    	if (price_adjust_return && !note_adjust_return) {
    			alert('调整金额备注必须填写');
    			return false;
    	}
    }
	if (obj.bank_tmp && obj.account_tmp && obj.user_tmp && obj.address_tmp && obj.zip_tmp && obj.name_tmp) {
		var both = false;
		if ($('bank_1').checked==true) {
			if (obj.bank_tmp.value == '') {
				alert('开行名不能为空称');
				return false;
			} else if (obj.account_tmp.value == '') {
				alert('帐号不能为空');
				return false;
			} else if (obj.user_tmp.value == '') {
				alert('开户名不能为空');
				return false;
			}
			both = true;
		}
		if ($('bank_2').checked==true) {
			if (obj.address_tmp.value == '') {
				alert('汇款地不能为空址');
				return false;
			} else if (obj.zip_tmp.value == '') {
				alert('邮编不能为空');
				return false;
			} else if (obj.name_tmp.value == '') {
				alert('姓名不能为空');
				return false;
			}
			both = true;
		}
		if ($('bank_3').checked==true) {
			both = true;
		}
		if ($('bank_4').checked==true) {
			both = true;
		}
		if (!both) {
			alert('银行转账 和 邮政汇款 和 帐户余额 和 其他 必须选择一个');
			return false;
		}
		if (obj.note_tmp.value == '') {
			alert('退款备注不能为空');
			return false;
		}
	}
//alert('请选择需要退换货的商品');
//alert('请选择退换货理由');
	if (!confirm('确认执行退换货开单操作？')) {
		return false;
	}
	return true;
}
function showTip(e,id) 
{
    e = e||window.event;
    var div1 = document.getElementById(id);
    div1.style.display="";
    div1.style.left=e.clientX+10;
    div1.style.top=e.clientX+5;
    div1.style.position="absolute";
}
function closeTip(id) 
{ 
    var div1 = document.getElementById(id); 
    div1.style.display="none"; 
}

function changePrice(price_limit ,obj, old_price)
{
    if (isNaN(obj.value)) {
        alert('价格不正确');
        obj.value=old_price;
        obj.focus();
    }
    /*if (parseFloat(price_limit) > 0) {
        if (parseFloat(obj.value) < parseFloat(price_limit)) {
            alert('价格不能小于最低限价');
            obj.value=old_price;
            obj.focus();
            return false;
        }
    }*/
}
</script>
