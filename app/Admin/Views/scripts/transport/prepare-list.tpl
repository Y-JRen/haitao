<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<script type="text/javascript">
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', createWin);
var win;
function createWin()
{
    win = new dhtmlXWindows();
    win.setImagePath("/scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
}
</script>
<div class="search">
<form name="searchForm" id="searchForm" method="get">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option><option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option></select>
<div class="line">
店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=shop}}
      {{if $shop.shop_type ne 'tuan'}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
收货人：<input type="text" name="addr_consignee" size="10" maxLength="20" value="{{$param.addr_consignee}}"/>
订单号：<input type="text" name="batch_sn" size="30" maxLength="50" value="{{$param.batch_sn}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的订单" onclick="G('{{url param.is_lock=yes}}'+location.search)"/>
<input type="button" name="dosearch3" value="所有没有锁定的订单" onclick="G('{{url param.is_lock=no}}'+location.search)"/>
</div>
</form>
<div class="title">配送管理 -&gt; 仓库配货</div>
<form name="myForm" id="myForm">
<div class="content">
	<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock-order}}/val/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock-order}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
	<!--<input type="button" value="合并选中订单" onclick="showMergeWin()">-->
	<input type="button" value="批量配货" onclick="ajax_submit(this.form, '{{url param.action=prepare}}','Gurl(\'refresh\')')">
	</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>订单号</td>
            <td>店铺</td>
            <td>下单时间</td>
            <td width="350">订单商品</td>
            <td width="60">收货人</td>
		    <td>金额</td>
            <!--<td>运费</td>-->
            <td>付款方式</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$data item=item}}
    <tr id="ajax_list{{$item.order_batch_id}}">
        <td><input type="checkbox" name="ids[]" value="{{$item.batch_sn}}"/></td>
        <td>
	      <input type="button" onclick="showOrderWin('{{$item.batch_sn}}')" value="操作">
        </td>
        <td>{{if $item.can_merge}}<b><font color="{{$item.order_color}}">{{$item.batch_sn}}</font></b>{{else}}{{$item.batch_sn}}{{/if}}<br /><b>{{$item.note_logistic}}</b></td>
        <td>{{$item.shop_name}}</td>
        <td>{{$item.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
        <td>
		  {{foreach from=$product item=goods}}
		    {{if $goods.batch_sn==$item.batch_sn}}
			  {{$goods.goods_name}} (<font color="#FF3333">{{$goods.goods_style}}</font>)  
              <font color="#336633">{{$goods.product_sn}}</font>*{{$goods.number}}<br />
		    {{/if}}
		  {{/foreach}}
		</td>
        <td>{{$item.addr_consignee}}</td>
        <td>{{$item.price_order}}</td>
        <!--<td>{{$item.price_logistic}}</td>-->
        <td>{{if $item.pay_type eq 'cod'}}货到付款{{else}}非货到付款{{/if}}</td>
        <td>{{if $item.lock_name}}<font color="red">被{{$item.lock_name}}锁定</font>{{else}}未锁定{{/if}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock-order}}/val/1','Gurl(\'refresh\',\'ajax_search\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock-order}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
<!--<input type="button" value="合并选中订单" onclick="showMergeWin()">-->
<input type="button" value="批量配货" onclick="ajax_submit(this.form, '{{url param.action=prepare}}','Gurl(\'refresh\')')">
</div>
</form>

<script type="text/javascript">
function showMergeWin()
{
	var control = document.getElementsByName('ids[]');
	var ids = '';
	var count = 0;
	for (var i = 0; i < control.length; i++) {
	    if (control[i].checked) {
	        ids += control[i].value + ',';
	        count++;
	    }
	}
	if (ids == '') {
	    alert('请先勾选要合并的订单!');
	    return;
	}
    if (count < 2) {
        alert('至少要勾选两个以上的订单!');
	    return;
    }
    
	var run = win.createWindow("mergeWindow", 200, 50, 500, 400);
	run.setText("合并订单");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/transport/merge-order/ids/" + ids, true);
}

function showSpliltWin(batch_sn)
{
	var run = win.createWindow("splitWindow", 200, 50, 600, 400);
	run.setText("拆分订单");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/transport/split-order/batch_sn/" + batch_sn, true);
}

function showOrderWin(batch_sn)
{
	var run = win.createWindow("orderWindow", 200, 50, 600, 400);
	run.setText("订单配货");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/transport/prepare-order/batch_sn/" + batch_sn, true);
}

var areaIndex = 0;
function splitOrder()
{
    var baseArray = getAllBaseSplitOrderGoods();
    var newArray = new Array();
    var index = 0;
    for (var i = 0; i < baseArray.length; i++) {
        tempArray = baseArray[i].split('_');
        product_sn = tempArray[0];
        base_number = tempArray[1];
        number = getCurrentSplitOrderGoodsNumber(product_sn);
        if (number > base_number) {
            alert('产品编码' + product_sn + '的数量不能大于总数量' + base_number + '!');
            return false;
        }
        if (number < base_number) {
            newArray[index] = product_sn + '_' + (base_number - number);
            index++;
        }
    }
    
    if (newArray.length == 0)   return false;
    
    areaIndex++;
    var content = '<table cellpadding="0" cellspacing="0" border="5" class="table" id="area_' + areaIndex + '">';
    for (i = 0; i < newArray.length; i++) {
        tempArray = newArray[i].split('_');
        product_sn = tempArray[0];
        number = tempArray[1];
        for (j = 0; j < baseArray.length; j++) {
            tempArray = baseArray[j].split('_');
            if (tempArray[0] == product_sn) {
                product_name = tempArray[2] + ' (<font color="red">' + tempArray[3] + '</font>)';
            }
        }
        content = content + '<tr>' +
                            '<td width="292px">' + product_name +'</td>' + 
                            '<td width="60px">' + product_sn + '</td>' + 
                            '<td width="40px" style="text-align:center">' + number + '<input type="hidden" name="number[]" id="number_' + product_sn + '" value="' + number + '"><input type="hidden" name="product[]" value="' + areaIndex + '_' + product_sn + '"></td>';
        if (i == 0) {
            content = content + '<td rowspan="' + newArray.length + '"><input type="button" value="删除" onclick="removeSplitOrderTable(' + areaIndex + ')"></td>';
        }
        content = content + '</tr>';
    }
    content = content + '</table><br>';
    var newArea = document.getElementById('newArea');
    newArea.innerHTML = newArea.innerHTML + content;
}

function getAllBaseSplitOrderGoods()
{
    var control = document.getElementsByName('base_number');
    var result = new Array();
    for (var i = 0; i < control.length; i++) {
        id = control[i].id;
        tempArray = id.split('_');
        result[i] = tempArray[1] + '_' + control[i].value;
    }
    
    return result;
}

function getCurrentSplitOrderGoodsNumber(product_sn)
{
    var control = document.getElementsByName('number[]');
    var number = 0;
    for (var i = 0; i < control.length; i++) {
        id = control[i].id;
        tempArray = id.split('_');
        if (product_sn == tempArray[1]) {
            number += parseInt(control[i].value);
        }
    }
    
    return number;
}

function removeSplitOrderTable(index)
{
    var table = document.getElementById('area_' + index);
    var base_table = document.getElementById('area_0');
    for (var i = 0; i < table.rows.length; i++) {
        row = table.rows[i];
        inputs = row.getElementsByTagName('input');
        tempArray = inputs[0].id.split('_');
        product_no = tempArray[1];
        number = parseInt(inputs[0].value);
        
        for (var j = 0; j < base_table.rows.length; j++) {
            base_row = base_table.rows[j];
            inputs = base_row.getElementsByTagName('input');
            tempArray = inputs[0].id.split('_');
            if (product_no == tempArray[1]) {
                inputs[0].value = parseInt(inputs[0].value) + number;
                break;
            }
        }
    }
    
    document.getElementById('newArea').removeChild(table);
}

function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}

</script>


