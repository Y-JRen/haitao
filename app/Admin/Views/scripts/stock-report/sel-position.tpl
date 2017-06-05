{{if $params.job neq 'search'}}
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
库位号：<input type="text" name="position_no" size="12" maxLength="50" value="" onkeydown="input()" />
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
<br>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addPositionRow();alertBox.closeDiv('{{$param.close_type}}');" type="button" value="添加并关闭"></p>

{{/if}}
<div id="ajax_search">
{{if !empty($infos)}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('source_select'),'ids',this)"/></td>
        <td>库区</td>
        <td>库位</td>
    </tr>
</thead>
<tbody>
{{foreach from=$infos item=info}}
<tr id="ajax_list{{$info.position_id}}">
    <td><input type="checkbox" name="ids[]" value="{{$info.position_id}}_{{$info.district_name}}_{{$info.position_no}}"/>
    </td>
    <td>{{$info.district_name}}</td>
    <td>{{$info.position_no}}</td>
   
</tr>
{{/foreach}}
</tbody>
</table>
<div class="page_nav">{{$pageNav}}</div>
{{/if}}
</div>
<br>
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addPositionRow();alertBox.closeDiv('{{$param.close_type}}');" type="button" value="添加并关闭"></p>
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