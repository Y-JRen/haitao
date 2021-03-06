<script>
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
  <form id="searchForm" method="get">
  <select name="viewType" onchange="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')">
    <option value="position" {{if $param.viewType eq 'position'}}selected{{/if}}>按库位</option>
    <option value="product" {{if $param.viewType eq 'product'}}selected{{/if}}>按产品</option>
  </select>
  所属仓库：
  <select name="area" onchange="$('searchForm').submit()">
    {{foreach from=$areas item=item key=key}}
    {{if $param.area eq $key}}
    <option value="{{$key}}" {{if $param.area eq $key}}selected{{/if}}>{{$item}}</option>
    {{/if}}
    {{/foreach}}
  </select>
  所属库区：
  <select name="district_id">
    <option value="">请选择...</option>
    {{foreach from=$districts item=item key=key}}
    <option value="{{$key}}" {{if $param.district_id eq $key}}selected{{/if}}>{{$item}}</option>
    {{/foreach}}
  </select>
  库位状态：
  <select name="status">
	<option value="">请选择...</option>
	<option value="0" {{if $param.status eq '0'}}selected{{/if}}>正常</option>
	<option value="1" {{if $param.status eq '1'}}selected{{/if}}>冻结</option>
  </select>
  库位：<input type="text" name="position_no" value="{{$param.position_no}}" size="12">
  产品编码：<input type="text" name="product_sn" value="{{$param.product_sn}}" size="10">
  产品名称：<input type="text" name="product_name" value="{{$param.product_name}}" size="15">
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	{{if $param.viewType eq 'position'}}
	  <div class="title">库位列表 [<a href="/admin/stock-report/add-position">添加库位</a>]&nbsp;&nbsp;[<a href="/admin/stock-report/insert-batch-position">批量导入库位</a>]&nbsp;&nbsp;[<a href="/admin/stock-report/insert-batch-position-product">批量导入库位产品</a>]&nbsp;&nbsp;[<a href="/admin/stock-report/insert-clear-position">清空导入产品库位</a>]</div>
	{{else}}
	  <div class="title">产品列表</div>
	{{/if}}
	<div class="content">
<div style="padding:0 5px">
</div>
        {{if $param.viewType eq 'position'}}
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>仓库</td>
				<td>库区</td>
				<td>库位</td>
    			<td>状态</td>
				<td>添加时间</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$areas[$data.area]}}</td>
		    <td valign="top">{{$districts[$data.district_id]}}</td>
			<td valign="top">{{$data.position_no}}</td>
			<td>
			  {{if $data.status eq 0}}启用
			  {{else}}冻结
			  {{/if}}
			</td>
			<td valign="top">{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
			<td valign="top">
			  <a href="javascript:void(0);" onclick="openAllProductWin({{$data.position_id}})"> 对应产品</a> | 
			  <a href="/admin/stock-report/edit-position/id/{{$data.position_id}}">编辑</a> | 
			  <a href="javascript:fGo()" onclick="if (confirm('确定要删除吗？')) window.location = '/admin/stock-report/delete-position/id/{{$data.position_id}}';">删除</a>
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
		{{else}}
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>产品编码</td>
				<td>产品名称</td>
				<td>批次</td>
				<td>库区</td>
    			<td>对应库位</td>
                <td>操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$data.product_sn}}</td>
		    <td valign="top">{{$data.product_name}}</td>
			<td valign="top">{{$data.batch_no|default:'无批次'}}</td>
			<td valign="top">{{$data.district_name}}</td>
			<td valign="top">{{$data.position_no}}</td>
            <td valign="top"><a href="javascript:void(0);" onclick="openAllPositionWin({{$data.product_id}}, {{$data.batch_id|default:'0'}})"> 对应库位</a>  </td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
		{{/if}}
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>
<script>
function openAllProductWin(id)
{
	var product = win.createWindow("allProductWin", 300, 0, 610, 380);
	product.setText("选择库位对应产品");
	product.button("minmax1").hide();
	product.button("park").hide();
	product.denyResize();
	product.denyPark();
	product.setModal(true);
	product.attachURL("/admin/stock-report/select-all-product/id/" + id + '/url/' + base64encode('{{url}}'), true);
}

function openAllPositionWin(id, batch_id)
{
	var product = win.createWindow("allPositionWin", 300, 0, 610, 380);
	product.setText("选择产品对应库位");
	product.button("minmax1").hide();
	product.button("park").hide();
	product.denyResize();
	product.denyPark();
	product.setModal(true);
	product.attachURL("/admin/stock-report/select-all-position/id/" + id +'/batch_id/'+batch_id+'/url/' + base64encode('{{url}}'), true);
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
		    for (var j = 0;j <= 3; j++) {
			    tr.insertCell(j);
		    }
		    tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)"><input type="hidden" name="ids[]" value="'+id+'" ><input type="hidden" name="product_id[]" value="'+pinfo.product_id+'" >';
		    tr.cells[1].innerHTML = pinfo.product_sn;
		    tr.cells[2].innerHTML = pinfo.product_name;
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
				    box = box + '<input type="hidden" id="cost' + pinfo.batch[j].batch_id + '" value="' + pinfo.batch[j].cost + '">';
			    }
			    tr.cells[3].innerHTML = box;
		    }
		    else {
			    tr.cells[3].innerHTML = '无批次<select name="batch_id[]" style="display:none"><option value="0"></option></select>';
		    }
		    obj.appendChild(tr);
		}
	}
}
function addPositionRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
    
	var obj = $('list');
	for (i = 1; i < el.length; i++) {
        if (el[i].checked) {
		    var str  = el[i].value;
            var strs = str.split('_');
            var id   = strs[0];
            if ($(id) != null) {
                continue;
            }
		    var tr   = obj.insertRow(0);
		    tr.id    =  id;
		    for (var j = 0;j < 3; j++) {
			    tr.insertCell(j);
		    }
		    tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)"><input type="hidden" name="position_ids[]" value="'+id+'" >';
		    tr.cells[1].innerHTML = strs[1];
		    tr.cells[2].innerHTML = strs[2];
		    obj.appendChild(tr);
		}
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
}

function removeProduct(id)
{
    new Request({
        url:'/admin/stock-report/delete-product-position/id/' + id,
	    onSuccess:function(msg){
            $('line_' + id).value = '已删除';
            $('line_' + id).disabled = true;
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}

var base64encodechars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
var base64decodechars = new Array(
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
    52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
    -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
    15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
    -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
    41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);

function base64encode(str) {
    var out, i, len;
    var c1, c2, c3;
    len = str.length;
    i = 0;
    out = "";
    while (i < len) {
        c1 = str.charCodeAt(i++) & 0xff;
        if (i == len) {
            out += base64encodechars.charAt(c1 >> 2);
            out += base64encodechars.charAt((c1 & 0x3) << 4);
            out += "==";
            break;
        }
        c2 = str.charCodeAt(i++);
        if (i == len) {
            out += base64encodechars.charAt(c1 >> 2);
            out += base64encodechars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xf0) >> 4));
            out += base64encodechars.charAt((c2 & 0xf) << 2);
            out += "=";
            break;
        }
        c3 = str.charCodeAt(i++);
        out += base64encodechars.charAt(c1 >> 2);
        out += base64encodechars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xf0) >> 4));
        out += base64encodechars.charAt(((c2 & 0xf) << 2) | ((c3 & 0xc0) >> 6));
        out += base64encodechars.charAt(c3 & 0x3f);
    }
    return out;
}

</script>