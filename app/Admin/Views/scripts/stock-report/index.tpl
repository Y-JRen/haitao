<form name="searchForm" id="searchForm" method="get">
<div class="search">
选择仓库：
{{foreach from=$areas key=key item=item}}
<input type="checkbox" name="logic_area" value="{{$key}}" {{if $param.logic_area.$key}}checked{{/if}} disabled>{{$item}}
{{/foreach}}
<br>
产品状态：<select name="p_status"><option value="0" {{if $param.p_status eq '0'}}selected{{/if}}>启用</option><option value="1" {{if $param.p_status eq '1'}}selected{{/if}}>冻结</option></select>
{{$catSelect}}
产品编码：<input type="text" name="product_sn" size="6" maxLength="20" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="{{$param.product_name}}"/>
产品批次：<input type="text" name="batch_no" size="20" maxLength="50" value="{{$param.batch_no}}"/>
<br>
选择库存状态：<select name="status_id" id="status_id">
<option value="">请选择</option>
{{html_options options=$status selected=$param.status_id}}
</select>
货位：<input type="text" name="local_sn" size="10" maxLength="50" value="{{$param.local_sn}}"/>
<select name="stock_number_type">
<option value="real_number" {{if $param.stock_number_type eq 'real_number'}}selected{{/if}}>实际库存</option>
<option value="able_number" {{if $param.stock_number_type eq 'able_number'}}selected{{/if}}>可用库存</option>
<option value="hold_number" {{if $param.stock_number_type eq 'hold_number'}}selected{{/if}}>占有库存</option>
<option value="wait_number" {{if $param.stock_number_type eq 'wait_number'}}selected{{/if}}>在途库存</option>
<option value="plan_number" {{if $param.stock_number_type eq 'plan_number'}}selected{{/if}}>计划结存</option>
</select>
<select name="stock_number_logic">
<option value="more" {{if $param.stock_number_logic eq 'more'}}selected{{/if}}>>=</option>
<option value="less" {{if $param.stock_number_logic eq 'less'}}selected{{/if}}><</option>
</select>
<input type="text" name="stock_number" size="10" value="{{$param.stock_number}}"/>
<input type="checkbox" name="showBatch" value="1" {{if $param.showBatch}}checked{{/if}}>显示批次
<input type="button" name="dosearch" id="dosearch" value="查询" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
<input type="button" onclick="doExport()" value="导出动态库存信息">
<br>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">库存管理 -&gt; 动态库存
</div>
<div class="content">
    {{if $datas}}
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品编码</td>
            <td width="200px">产品名称</td>
            <td>产品单位</td>
            {{if $param.showBatch}}<td>产品批次</td>{{/if}}
            <td>库存状态</td>
            <td>库位</td>
            <td>计划结存</td>
            <td>实际库存</td>
            <td>可用库存</td>
            <td>在途库存</td>
            <td>占有库存</td>
            <td>产品状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.stock_id}}">
        <td>{{$data.product_sn}}</td>
        <td>{{$data.product_name}} <font color="#FF0000">({{$data.goods_style}})</font></td>
         <td>{{$data.goods_units}}</td>
        {{if $param.showBatch}}<td>{{if $data.batch_no}}{{$data.batch_no}}{{else}}无批次{{/if}}</td>{{/if}}
        <td>{{$status[$data.status_id]}}</td>
        <td>{{$data.position_no}}</td>
        <td>{{$data.plan_number}}</td>
        <td>{{$data.real_number}}</td>
        <td>{{$data.able_number}}</td>
        <td><a href="javascript:;void(0)" onclick="window.open('/admin/stock-report/wait-stock-detail/product_id/{{$data.product_id}}{{if $param.showBatch}}/batch_id/{{if $data.batch_id}}{{$data.batch_id}}{{else}}0{{/if}}{{/if}}/status_id/{{$data.status_id}}', 'wait_stock_{{$data.product_id}}', 'height=520,width=800,toolbar=no,scrollbars=yes,resizable=yes')">{{$data.wait_number}}</a></td>
        <td><a href="javascript:;void(0)" onclick="window.open('/admin/stock-report/hold-stock-detail/product_id/{{$data.product_id}}{{if $param.showBatch}}/batch_id/{{if $data.batch_id}}{{$data.batch_id}}{{else}}0{{/if}}{{/if}}/status_id/{{$data.status_id}}', 'hold_stock_{{$data.product_id}}', 'height=520,width=800,toolbar=no,scrollbars=yes,resizable=yes')">{{$data.hold_number}}</a></td>
        <td>{{if $data.p_status}}<font color="red">冻结</font>{{else}}启用{{/if}}</td>
        <td><a href="javascript:;void(0)" onclick="window.open('/admin/stock-report/graph/product_id/{{$data.product_id}}', 'stock_graph_{{$data.product_id}}', 'height=520,width=800,toolbar=no')">月图表</a></td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
    <input type="button" onclick="doExport()" value="导出动态库存">
    {{/if}}
</div>

<div class="page_nav">{{$pageNav}}</div>
</form>

<script type="text/javascript">
function doExport()
{
    document.getElementById('searchForm').target='_blank';
    document.getElementById('searchForm').method='post';
    document.getElementById('searchForm').action='{{url}}/export/1';
    document.getElementById('searchForm').submit();
}
</script>