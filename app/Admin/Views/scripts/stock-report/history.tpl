<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
<span style="float:left;line-height:18px;">日期选择：</span>
<span style="float:left;width:115px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">-&nbsp;&nbsp;</span>
<span style="float:left;width:120px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
选择仓库：
{{foreach from=$areas key=key item=item}}
<input type="checkbox" name="logic_area" value="{{$key}}" {{if $param.logic_area.$key}}checked{{/if}} disabled>{{$item}}
{{/foreach}}
&nbsp;&nbsp;&nbsp;
<br><br>
产品状态：<select name="p_status"><option value="0" {{if $param.p_status eq '0'}}selected{{/if}}>启用</option><option value="1" {{if $param.p_status eq '1'}}selected{{/if}}>冻结</option></select>
产品编码：<input type="text" name="product_sn" size="6" maxLength="20" value="{{$param.product_sn}}"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="{{$param.product_name}}"/>
选择库存状态：<select name="status_id" id="status_id">
<option value="">请选择</option>
{{html_options options=$status selected=$param.status_id}}
</select>
<input type="checkbox" name="onlyChangeRecord" value="1" {{if $param.onlyChangeRecord}}checked{{/if}}>只显示数量发生变化的记录
<input type="button" name="dosearch" id="dosearch" value="查询" onclick="check()"/>
<input type="reset" name="reset" value="清除">
<a href="{{url param.todo=export}}" target="_blank">[导出数据]</a>
<br>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">库存管理 -&gt; 历史库存报表 
</div>
<div class="content">
    {{if $datas}}
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品编码</td>
            <td width="350px">产品名称</td>
			
	{{if $viewcost eq '1'}}
            <td>成本价</td>
            <td>未税价</td>
	     <td>建议零售价</td>
	{{/if}}
			
            {{if $param.status_id}}<td>库存状态</td>{{/if}}
            <td>期初库存</td>
            <td>期末库存</td>
            <td>正常入库数量</td>
            <td>状态更改入库数量</td>
            <td>调拨入库数量</td>
            <td>正常出库数量</td>
            <td>状态更改出库数量</td>
            <td>调拨出库数量</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.product_sn}}</td>
        <td>{{$data.product_name}} <font color="#FF0000">({{$data.goods_style}})</font></td>
		{{if $viewcost eq '1'}}
                  <td>{{$data.cost}}</td>
                  <td>{{$data.cost_tax}}</td>
                  <td>{{$data.suggest_price}}</td>
		{{/if}}
		
        {{if $param.status_id}}<td>{{$status[$param.status_id]}}</td>{{/if}}
        <td>{{$data.start_stock_number|default:0}}</td>
        <td>{{$data.end_stock_number|default:0}}</td>
        <td>{{$data.in_stock_number|default:0}}</td>
        <td>{{$data.in_status_number|default:0}}</td>
        <td>{{$data.in_allocation_number|default:0}}</td>
        <td>{{$data.out_stock_number|default:0}}</td>
        <td>{{$data.out_status_number|default:0}}</td>
        <td>{{$data.out_allocation_number|default:0}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
    {{/if}}
</div>
</form>

<script type="text/javascript">
function check()
{
    if ($('fromdate').value < '2012-12-01') {
        alert('开始日期不能小于2012-12-01');
        return false;
    }
    
    ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search');
}

</script>