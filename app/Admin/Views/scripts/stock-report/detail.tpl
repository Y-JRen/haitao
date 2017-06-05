<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/stock-report/detail/">
<div class="search">
<span style="float:left;width:115px;line-height:18px;">
  <input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
</span>
<span style="float:left;width:10px;line-height:18px;">
  -
</span>
<span style="float:left;width:115px;line-height:18px;">
  <input type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate" onClick="WdatePicker()"/>
</span>
选择仓库
<select name="logic_area">
{{foreach from=$areas key=key item=item}}
{{if $param.logic_area eq $key}}
<option value="{{$key}}" {{if $param.logic_area eq $key}}selected{{/if}}>{{$item}}</option>
{{/if}}
{{/foreach}}
</select>
选择库存状态
<select name="status_id">
<option value="">全部状态</option>
{{foreach from=$status key=key item=item}}
<option value="{{$key}}" {{if $param.status_id eq $key}}selected{{/if}}>{{$item}}</option>
{{/foreach}}
</select>
<br><br>
产品编码：<input type="text" name="product_sn" size="8" maxLength="50" value="{{$param.product_sn}}"/>
产品批次：<input type="text" name="batch_no" size="15" maxLength="50" value="{{$param.batch_no}}"/>
<input type="submit" name="dosearch" value="查询" onclick="return check()"/>
<input type="reset" name="reset" value="清除">
</div>
</form>

<div class="title">库存管理 -&gt; 明细报表
</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品名称</td>
            <!--<td>产品批次</td>-->
            <td>库存状态</td>
            <td>当前结存</td>
            <td>变更数字</td>
            <td>成本</td>
            <td>单据类型</td>
            <td>单据编号</td>
            <td>变更时间</td>
            <td>操作人</td>
        </tr>
    </thead>
    <tbody>
    {{if $datas}}
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.product_id}}</td>
        <td>{{$data.product_sn}}</td>
        <td>{{$data.product_name}}<font color="#FF0000">({{$data.goods_style}})</font></td>
        <!--<td>{{if $data.batch_no}}{{$data.batch_no}}{{else}}无批次{{/if}}</td>-->
        <td>{{$status[$data.status_id]}}</td>
        <td>{{$data.stock}}{{if $data.error}}<font color="red">{{$data.error}}</font>{{/if}}</td>
        <td>{{$data.number}}</td>
        <td>{{$data.price}}</td>
        <td>
          {{if $data.type eq 'outstock'}}
          {{$outTypes[$data.bill_type]}}
          {{elseif $data.type eq 'instock'}}
          {{$inTypes[$data.bill_type]}}
          {{elseif $data.type eq 'outstatus'}}
          状态调整出库单
          {{elseif $data.type eq 'instatus'}}
          状态调整入库单
          {{elseif $data.type eq 'outallocation'}}
          调拨出库单
          {{elseif $data.type eq 'inallocation'}}
          调拨入库单
          {{/if}}
        </td>
        <td>{{$data.bill_no}}</td>
        <td>{{$data.finish_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
        <td>{{$data.admin_name}}</td>
    </tr>
    {{/foreach}}
    <tr>
      <td>合计</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <!--<td>&nbsp;</td>-->
      <td>&nbsp;</td>
      <td>{{$totalData.stock}}</td>
      <td>{{$totalData.number}}</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    {{/if}}
    </tbody>
    </table>
    <br>
    {{if $initStockData}}
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <!--<td>产品批次</td>-->
            <td>库存状态</td>
            <td>当前结存</td>
            <td>变更数字</td>
            <td>初始时间</td>
            <td>操作人</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$initStockData item=data}}
    <tr>
      <!--<td>{{if $data.batch_no}}{{$data.batch_no}}{{else}}无批次{{/if}}</td>-->
      <td>{{$status[$data.status_id]}}</td>
      <td>{{$data.stock_number}}</td>
      <td>{{$data.number}}</td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
      <td>{{$data.admin_name}}</td>
    </tr>
    {{/foreach}}
    {{/if}}
    </tbody>
    </table>
</div>
<script type="text/javascript">
function check()
{
    if ($('fromdate').value == '') {
        alert('日期不能为空!');
        return false;
    }
    if ($('product_sn').value == '' && $('batch_no').value == '') {
        alert('产品编码和产品批次必须输入一个!');
        return false;
    }
    
    return true;
}
</script>