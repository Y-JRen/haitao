<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
  <form id="searchForm" method="get">
  补货单状态：
  <select name="status">
    <option value="0" {{if $param.status eq '0'}}selected{{/if}}>未确认</option>
  </select>
  产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="{{$param.product_name}}">
  产品编码：<input type="text" name="product_sn" size="10" maxLength="10" value="{{$param.product_sn}}">
  供应商：
  <select name="supplier_id" onchange="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')">
    <option value="">请选择...</option>
  {{if $supplierData}}
  {{foreach from=$supplierData item=data}}
    <option value="{{$data.supplier_id}}" {{if $param.supplier_id eq $data.supplier_id}}selected{{/if}}>{{$data.supplier_name}}</option>
  {{/foreach}}
  {{/if}}
  </select>
  
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">补货单开单列表</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/></td>
				<td>产品编码</td>
				<td>产品名称</td>
				<td>总需求数量</td>
				<td>自动需求数量</td>
				<td>手工需求数量</td>
				<td>添加时间</td>
				<td>更新时间</td>
				<td>状态</td>
				<td>操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td>
		      <input type="checkbox" name="ids[]" value="{{$data.replenishment_id}}">
		    </td>
		    <td valign="top">{{$data.product_sn}}</td>
		    <td valign="top">{{$data.product_name}}</td>
		    <td valign="top" id="require_number_{{$data.replenishment_id}}">{{$data.require_number|default:0}}</td>
			<td valign="top" id="auto_number_{{$data.replenishment_id}}">{{$data.auto_number|default:0}}</td>
			<td valign="top">
			  <input type="text" id="manual_number_{{$data.replenishment_id}}" value="{{$data.manual_number|default:0}}" size="2" style="text-align:center">
			  <a href="javascript:void(0);" onclick="changeNumber({{$data.replenishment_id}}, $('manual_number_{{$data.replenishment_id}}').value)">调整</a>
			</td>
			<td valign="top">{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
			<td valign="top">{{$data.update_time|date_format:"%Y-%m-%d"}}</td>
			<td valign="top">
			  {{if $data.status eq 0}}未确认
			  {{elseif $data.status eq 1}}已申请
			  {{elseif $data.status eq 2}}已审核
			  {{elseif $data.status eq 3}}已收货
			  {{elseif $data.status eq 4}}已完成
			  {{elseif $data.status eq 9}}已取消
			  {{/if}}
			</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="openDiv('/admin/replenishment/view/id/{{$data.replenishment_id}}','ajax','补货单详情',750,400);">详请</a>
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div style="text-align:center">
	  <input type="button" onclick="openDiv('/admin/product/sel/logic_area/1/type/sel','ajax','查询商品',750,400);" value="手工添加补货商品">
	  <input type="button" name="apply" value="补货开单" onclick="check(this.form)">
	</div>
</form>
<form name="addForm" id="addForm" action="/admin/replenishment/add-product" method="post" target="ifrmSubmit">
<input type="hidden" name="ids" id="ids" value="">
</form>
<script>
function check(tag)
{
    var checkbox = tag.getElements('input[type=checkbox]');
    var ids = '';
	for(var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if (e.checked) {
		    if (e.value == 'on')    continue;
		    ids = ids + e.value + ',';
		}
	}
	if (ids == '') {
	    alert('没有选择单据!');
        return false;
	}
	
	{{if !$param.supplier_id}}
	alert('请先选择供应商!');
    return false;
	{{/if}}
	
	ids = ids.substring(0, ids.length - 1);
	openDiv('/admin/replenishment/new/supplier_id/{{$param.supplier_id}}/ids/' + ids,'ajax','补货入库单',750,400);
}
function checkNum(obj, num)
{
	if (parseInt(obj.value) == 0 || isNaN(obj.value)) {
	    alert('请填写正整数');
	    obj.value = 0;
	    return false;
	}
}
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	var ids = '';
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
			var str = $('pinfo' + el[i].value).value;
			var pinfo = JSON.decode(str);
            ids = ids + pinfo.product_id + '|';
        }
    }
    if (ids != '') {
        ids = ids.substring(0, ids.length - 1);
        $('ids').value = ids;
        $('addForm').submit();
    }
}
function changeNumber(id, number) 
{
    new Request({url: '/admin/replenishment/change-product-number/id/' + id + '/number/' + number,
                method:'get' ,
                onSuccess: function(responseText) {
                    if (responseText == 'ok') {
                        $('require_number_' + id).innerHTML =  parseInt($('auto_number_' + id).innerHTML) + parseInt(number);
                        alert('调整成功!');
                    }
                    else {
                        alert('调整失败!');
                    }
                }
    }).send();
}
</script>