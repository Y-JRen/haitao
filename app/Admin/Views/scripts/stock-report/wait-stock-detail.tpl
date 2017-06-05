<form name="searchForm" id="searchForm" method="post" action="/admin/stock-report/wait-stock-detail">
<input type="hidden" name="product_id" value="{{$param.product_id}}">
<input type="hidden" name="batch_id" value="{{$param.batch_id}}">
<div class="search">
产品编码 {{$product.product_sn}}
产品名称 {{$product.product_name}}
&nbsp;&nbsp;
选择仓库
<select name="logic_area" onchange="$('searchForm').submit()">
{{foreach from=$areas key=key item=item}}
{{if $param.logic_area eq $key}}
<option value="{{$key}}" {{if $param.logic_area eq $key}}selected{{/if}}>{{$item}}</option>
{{/if}}
{{/foreach}}
</select>
选择库存状态
<select name="status_id" onchange="$('searchForm').submit()">
{{foreach from=$status key=key item=item}}
<option value="{{$key}}" {{if $param.status_id eq $key}}selected{{/if}}>{{$item}}</option>
{{/foreach}}
</select>
<br>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">库存管理 -&gt; 在途库存明细
</div>
<div class="content">
    {{if $datas}}
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
      <tr>
        <td>单据类型</td>
        <td>单据编号</td>
        <td>在途库存</td>
      </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
      <td>
        {{if $data.bill_type eq 'inStatus'}}产品状态更改
        {{elseif $data.bill_type eq 'inAllocation'}}调拨单
        {{else}}{{$inTypes[$data.bill_type]}}
        {{/if}}
      </td>
      <td>{{$data.bill_no}}</td>
      <td>{{$data.number}}</td>
    </tr>
    {{/foreach}}
    <tr>
      <td>合计</td>
      <td>&nbsp;</td>
      <td>{{$total}}</td>
    </tr>
    </tbody>
    </table>
    {{/if}}
</div>
</form>
