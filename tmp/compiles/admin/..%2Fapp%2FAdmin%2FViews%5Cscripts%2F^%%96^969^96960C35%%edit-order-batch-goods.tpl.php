<?php /* Smarty version 2.6.19, created on 2015-06-23 15:52:23
         compiled from order/edit-order-batch-goods.tpl */ ?>
<script type="text/jscript" src="/haitaoadmin/scripts/jquery-1.4.2.min.js"/></script>   
<div class="title">编辑/删除订单商品</div>
<form id="myform1">
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
<tr>
<th align="left">商品名称</th>
<th align="left">商品编号</th>
<th align="left">商品销售均价</th>
<th align="left">销售价</th>
<th align="left">当前数量</th>
<th align="left">总金额</th>
<th align="left">可用库存</th>
<th align="left">修改后单品金额</th>
<th align="left">修改后行邮税</th>
<th align="left">修改后数量</th>
</tr>
<?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<tr <?php if ($this->_tpl_vars['item']['product_id']): ?>id="sid<?php echo $this->_tpl_vars['item']['product_id']; ?>
"<?php endif; ?>>
<td><?php echo $this->_tpl_vars['item']['goods_name']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['product_sn']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['eq_price']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['sale_price']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['number']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['amount']; ?>
</td>
<td><?php echo $this->_tpl_vars['item']['able_number']; ?>
</td>
<td>
<?php if ($this->_tpl_vars['item']['product_id'] || $this->_tpl_vars['item']['type'] = 5): ?>
<?php if (! $this->_tpl_vars['item']['offers_type']): ?>
<input type='text' class="sale_price" id="goods_<?php echo $this->_tpl_vars['a']['order_batch_goods_id']; ?>
" 
name='data[old][<?php echo $this->_tpl_vars['item']['order_batch_goods_id']; ?>
][sale_price]' size="6" 
 <?php if ($this->_tpl_vars['item']['card_type'] == 'gift' || $this->_tpl_vars['item']['card_type'] == 'coupon'): ?> readonly <?php endif; ?>  
 value="<?php echo $this->_tpl_vars['item']['sale_price']; ?>
" onchange="changePrice('<?php echo $this->_tpl_vars['item']['price_limit']; ?>
', this, '<?php echo $this->_tpl_vars['item']['sale_price']; ?>
')"/>
<?php endif; ?>
<?php endif; ?></td>
<td>
<?php if ($this->_tpl_vars['item']['product_id'] || $this->_tpl_vars['item']['type'] = 5): ?>
<?php if (! $this->_tpl_vars['item']['offers_type']): ?>
<input type='text' class="num" id="goods_<?php echo $this->_tpl_vars['a']['order_batch_goods_id']; ?>
" 
name='data[old][<?php echo $this->_tpl_vars['item']['order_batch_goods_id']; ?>
][tax]' size="6" 
 <?php if ($this->_tpl_vars['item']['card_type'] == 'gift' || $this->_tpl_vars['item']['card_type'] == 'coupon'): ?> readonly <?php endif; ?>  
 value="<?php echo $this->_tpl_vars['item']['tax']; ?>
" onchange=""/>
<?php endif; ?>
<?php endif; ?>
</td>
<td>
	<?php if ($this->_tpl_vars['item']['product_id'] || $this->_tpl_vars['item']['card_type'] == 'gift' || $this->_tpl_vars['item']['card_type'] == 'coupon' || $this->_tpl_vars['item']['type'] = 5): ?>
    <?php if (! $this->_tpl_vars['item']['offers_type']): ?>
	<input type='text' id="goods_<?php echo $this->_tpl_vars['a']['order_batch_goods_id']; ?>
" name='data[old][<?php echo $this->_tpl_vars['item']['order_batch_goods_id']; ?>
][number]' value="<?php echo $this->_tpl_vars['item']['number']; ?>
"
	onkeyup="
	if(this.value==0){
		this.setStyle('background', '#ff0000');
	}else{
		this.setStyle('background', '#ffffff');
	}
	<?php if ($this->_tpl_vars['item']['offers_type'] != 'fixed-package' && $this->_tpl_vars['item']['offers_type'] != 'choose-package'): ?>
		check(this, '<?php echo $this->_tpl_vars['item']['able_number']; ?>
', '<?php echo $this->_tpl_vars['item']['number']; ?>
');
		<?php if ($this->_tpl_vars['item']['child']['0']['offers_type'] == 'buy-gift'): ?>
			$('goods_<?php echo $this->_tpl_vars['item']['child']['0']['order_batch_goods_id']; ?>
').value = this.value;
		<?php endif; ?>
	<?php else: ?>
		if (this.value>1) {
			this.value = 1;
		} else if (this.value<0) {
			this.value = 0;
		}
		<?php $_from = $this->_tpl_vars['item']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
			$('goods_<?php echo $this->_tpl_vars['tmp']['order_batch_goods_id']; ?>
').value = this.value;
		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
	" size="3"><input type="hidden" name="data[old][<?php echo $this->_tpl_vars['item']['order_batch_goods_id']; ?>
][former_number]" value="<?php echo $this->_tpl_vars['item']['number']; ?>
" />
	<?php endif; ?><?php endif; ?></td>
</tr>
<?php if ($this->_tpl_vars['item']['child']): ?>
	<?php $_from = $this->_tpl_vars['item']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
	<tr>
	<td style="padding-left:40px"><?php echo $this->_tpl_vars['a']['goods_name']; ?>
</td>
	<td><?php echo $this->_tpl_vars['a']['product_sn']; ?>
</td>
	<td><?php echo $this->_tpl_vars['a']['eq_price']; ?>
</td>
	<td><?php echo $this->_tpl_vars['a']['sale_price']; ?>
</td>
    <td></td>
	<td><?php echo $this->_tpl_vars['a']['number']; ?>
</td>
	<td><?php echo $this->_tpl_vars['a']['amount']; ?>
</td>
	<td><?php echo $this->_tpl_vars['a']['able_number']; ?>
</td>
	<td>&nbsp;</td>
	<td>
      <?php if ($this->_tpl_vars['a']['type'] != 5 && $this->_tpl_vars['a']['type'] != 1): ?>
		<?php if ($this->_tpl_vars['a']['product_id']): ?>
			<?php if ($this->_tpl_vars['a']['offers_type'] == 'fixed-package' && $this->_tpl_vars['a']['offers_type'] != 'choose-package'): ?>
			<input type='text' id="goods_<?php echo $this->_tpl_vars['a']['order_batch_goods_id']; ?>
" name='data[old][<?php echo $this->_tpl_vars['a']['order_batch_goods_id']; ?>
][number]' value="<?php echo $this->_tpl_vars['a']['number']; ?>
" onkeyup="check(this, '<?php echo $this->_tpl_vars['a']['able_number']; ?>
', '<?php echo $this->_tpl_vars['a']['number']; ?>
')"  size="3">
			<?php else: ?>
			<input type='text' id="goods_<?php echo $this->_tpl_vars['a']['order_batch_goods_id']; ?>
" name='data[old][<?php echo $this->_tpl_vars['a']['order_batch_goods_id']; ?>
][number]' value="<?php echo $this->_tpl_vars['a']['number']; ?>
" onkeyup="check(this, '<?php echo $this->_tpl_vars['a']['able_number']; ?>
', '<?php echo $this->_tpl_vars['a']['number']; ?>
')"  size="3">
			<?php endif; ?>
		<?php endif; ?>
      <?php endif; ?>
      </td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</table>
<br />
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>删除</td>
        <td>商品编码</td>
        <td>商品名称</td>
        <td>单品价格</td>
        <td>最低限价</td>
        <td>可用库存</td>
        <td>修改后单品价格</td>
        <td>行邮税(单个)</td>
        <td>数量</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table><br />

<table>
	<tr>
		<td>
        <input type="button" onclick="openDiv('/admin/product/sel/type/sel_stock/logic_area/<?php echo $this->_tpl_vars['lid']; ?>
/shop_id/<?php echo $this->_tpl_vars['order_info']['shop_id']; ?>
/add_time/<?php echo $this->_tpl_vars['order_info']['add_time']; ?>
','ajax','选择换货商品',750,400);" value=" 添加商品 " name="do"/>
        </td>
        <td></td>
	</tr>
	<tr>
		<td></td><td>&nbsp;</td>
	</tr>
	<tr>
		<td>修改价格理由：</td><td><textarea name="note" id="note"></textarea> <font color="#FF0000">如果修改了商品价格请填写修改理由</font></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type='hidden' name='type' value="submit">

			<input type="button" value="确定" onclick="checkForm();" />
			<input type="button" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"not-confirm-info",)));?>')" value=" 返回订单页 " name="do"/>
		</td>
	</tr>
</table>
</form>

<script language="JavaScript">
var sale_price_arr=new Array();
$.noConflict();
var arrs=jQuery('.sale_price');
for(var i=0;i<arrs.length;i++){
	sale_price_arr[i]=arrs[i].value;
}

function checkForm(){
	var arrs_now=jQuery('.sale_price');
	var isFlase=false;
	for(var i=0;i<arrs_now.length;i++){
		if(sale_price_arr[i]!=arrs_now[i].value){
			isFlase=true;
			break;
		}
	}
	var str_val=(jQuery('#note').val()).Trim();
	if(isFlase && str_val==""){
		alert("请填写修改价格的理由！");
		jQuery('#note').focus();
	}else{
		ajax_submit($('myform1'),'<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-order-batch-goods",)));?>');
	}
}
String.prototype.Trim = function()
{
	return this.replace(/(^\s*)|(\s*$)/g, "");
}
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++)
	{
		if (el[i].checked) {
			var id = el[i].value;
			var str = $('pinfo' + id).value;
			var pinfo = JSON.decode(str);
			if ($('sid' + pinfo.product_id)) {
				continue;
			}
			else {
			    var tr = obj.insertRow(0);
			    tr.id = 'sid' + pinfo.product_id;
                var limit_price_str = pinfo.price_limit == 0 ? '无限制' : pinfo.price_limit;
			    for (var j = 0;j <= 8; j++) {
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.product_id +')"><input type="hidden" name="product_id[]" value="'+pinfo.product_id+'" >';
				tr.cells[1].innerHTML = pinfo.product_sn;
				tr.cells[2].innerHTML = pinfo.product_name;
				tr.cells[3].innerHTML = pinfo.price;
                tr.cells[4].innerHTML = limit_price_str;
				tr.cells[5].innerHTML = pinfo.able_number;
				tr.cells[6].innerHTML = '<input type="text" size="6" name="data[new]['+ pinfo.product_id +'][sale_price]" value="'+ pinfo.price +'" onchange="changePrice('+pinfo.price_limit+', this, '+pinfo.price+')" />';
				tr.cells[7].innerHTML = '<input type="text" size="6" name="data[new]['+ pinfo.product_id +'][tax]" value="'+ pinfo.tax +'" msg="不能为空" />';
				tr.cells[8].innerHTML = '<input type="text" size="3" name="data[new]['+ pinfo.product_id +'][number]" value="1" class="required" msg="不能为空" onkeyup="check(this, \''+pinfo.able_number+'\')"/>';

				if (pinfo.price_seg) {
				    tr.cells[3].innerHTML = tr.cells[3].innerHTML + " <a onmouseover=showTip(window.event,'price_seg_"+pinfo.product_id+"') onmouseout=closeTip('price_seg_"+pinfo.product_id+"')>(多数量价格)</a><div id=price_seg_"+pinfo.product_id+" style=display:none;background-color:#DDDDDD>"+pinfo.price_seg+"</div>";
				}

				obj.appendChild(tr);
			}
		}
	}
}

function removeRow(id)
{
	$('sid' + id).destroy();
}


function check(obj, num, usedNum)
{
	if (!num) num = 0;
	if (!usedNum) usedNum = 0;
    /*if (parseInt(obj.value) < 0 || isNaN(obj.value)) {
	    alert('请填写正整数');
	    obj.value = num;
	    return false;
	}*/
    /*if (parseInt(obj.value) > (parseInt(num) + parseInt(usedNum))) {
        alert('数量不能大于'+(parseInt(num) + parseInt(usedNum)));
	    obj.value = num;
	    return false;
    }*/

    if (parseInt(num) < 0 || isNaN(obj.value) || parseInt(obj.value) < 0) {
        obj.value = 0;
        return false;
    } else if (parseInt(obj.value) > (parseInt(num) + parseInt(usedNum))) {
        alert('数量不能大于'+(parseInt(num) + parseInt(usedNum)));
	    obj.value = num;
	    return false;
    }
}
function showTip(e,id)
{
    e = e||window.event;
    var div1 = document.getElementById(id);
    div1.style.display="";
    div1.style.left=e.clientX+10;
    div1.style.top=e.clientY+5;
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