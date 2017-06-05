{{if !$param.job}}
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
	
{{$catSelect}}
{{if $showStatus neq 'false'}}
商品状态：
<select name="status_id">
    <option value="">请选择</option>
	{{html_options options=$status selected=2}}
</select>
{{/if}}
商品编码：<input type="text" name="product_sn" size="12" maxLength="50" value="" onkeydown="input()" />
商品名称：<input type="text" name="product_name" id="product_name" size="28" maxLength="50" value="" onkeydown="input()" /><br>
价格区间：<input type="text" name="fromprice" size="5" maxLength="10" value=""/> - <input type="text" name="toprice" size="5" maxLength="10" value=""/>
<input type="hidden" name="sid" value="{{$param.sid}}">
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
<br>
{{if !$justOne}}
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('{{$param.close_type}}');" type="button" value="添加并关闭"></p>
{{/if}}
{{/if}}
<div id="ajax_search">
{{if !empty($datas)}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        {{if !$justOne}}
        <td><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('source_select'),'ids',this)"/></td>
        {{/if}}
        <td>商品编码</td>
        <td>商品名称</td>
        {{if !$hidePrice}}
        <td>采购价</td>
        {{/if}}
        {{if $showStatus neq 'false'}}
        <td>状态</td>
        {{/if}}
        {{if $param.type eq 'sel_status' || $param.type eq 'sel_stock'}}<td>可用库存</td>{{/if}}
        <td>上架状态</td>
        {{if $justOne}}<td>操作</td>{{/if}}
    </tr>
</thead>
<tbody>
{{foreach from=$datas item=data}}
<tr id="ajax_list{{$data.product_id}}">
    {{if !$justOne}}
    <td><input type="checkbox" name="ids[]" value="{{$data.product_id}}"/>
    {{/if}}
    <input type="hidden" id="pinfo{{$data.product_id}}" value='{{$data.pinfo}}'>
    </td>
    <td>{{$data.product_sn}}</td>
    <td>{{$data.product_name}} (<font color="#FF3333">{{$data.goods_style}}</font>)</td>
    {{if !$hidePrice}}
    <td>{{if $data.purchase_cost}}{{$data.purchase_cost}}{{else}}{{$data.cost}}{{/if}}</td>
    {{/if}}
    {{if $showStatus neq 'false'}}
    <td>{{$data.status_name}}</td>
    {{/if}}
    {{if $param.type eq 'sel_status' || $param.type eq 'sel_stock'}}<td>{{$data.able_number}}</td>{{/if}}
    <td>{{if $data.p_status eq 1 }} 下架 {{else}} 上架  {{/if}}  </td>
    {{if $justOne}}
    <td><input type="button" name="choose" value="选择" onclick="addRow('pinfo{{$data.product_id}}');alertBox.closeDiv();"></td>
    {{/if}}
</tr>
{{/foreach}}
</tbody>
</table>
<div class="page_nav">{{$pageNav}}</div>
{{/if}}
{{if !$param.job}}
</div>
<br>
{{if !$justOne}}
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv('{{$param.close_type}}');" type="button" value="添加并关闭"></p>
{{/if}}
{{/if}}
</div>

<script>
function input()
{
    var e = getEvent();
    if (e.keyCode == 13) {
        ajax_search($('searchForm'),'{{url param.job=search}}','ajax_search');
    }
}

function getEvent()
{  
    if (document.all)   return window.event;    
    func = getEvent.caller;
    while(func != null) {
        var arg0 = func.arguments[0]; 
        if (arg0) { 
            if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {  
                return arg0; 
            } 
        } 
        func = func.caller; 
    }
    
    return null; 
}
</script>