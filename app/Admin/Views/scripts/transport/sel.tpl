{{if !$param.job}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">结束日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
单据类型
<select name="bill_type">
    <option value="">请选择</option>
	{{html_options options=$billType}}
</select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option><option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option></select>
    <br style="clear:both;" />
物流公司：
<select name="logistic_code">
    {{foreach from=$logisticList key=key item=data}}
      {{if $key eq 'sf' || $key eq 'ems' || $param.type ne 'clear-cod'}}
      <option value="{{$key}}" {{if $param['logistic_code'] eq $key}}selected{{/if}}>{{$data}}</option>
      {{/if}}
    {{/foreach}}
</select>
{{if $param.type eq 'clear-cod'}}
<select name="sub_code">
<option value="jiankang" {{if $param.sub_code eq 'jiankang'}}selected{{/if}}>1健康</option>
<option value="in_call" {{if $param.sub_code eq 'in_call'}}selected{{/if}}>呼入</option>
<option value="out_call" {{if $param.sub_code eq 'out_call'}}selected{{/if}}>呼出</option>
<option value="tq_call" {{if $param.sub_code eq 'tq_call'}}selected{{/if}}>咨询</option>
<option value="employee" {{if $param.sub_code eq 'employee'}}selected{{/if}}>国药内购</option>
<option value="dangdang" {{if $param.sub_code eq 'dangdang'}}selected{{/if}}>当当网</option>
<option value="baidu" {{if $param.sub_code eq 'baidu'}}selected{{/if}}>百度微购</option>
<option value="other" {{if $param.sub_code eq 'other'}}selected{{/if}}>其它</option>
</select>
{{/if}}
运单号码：<input type="text" name="logistic_no" size="20" maxLength="50" value=""/>
收货人：<input type="text" name="consignee" size="20" maxLength="50" value=""/><br>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value=""/>
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
<br>
<p><input onclick="addRow();" type="button" value=" 添加 "> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
{{/if}}
<div id="ajax_search">
{{if !empty($datas)}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td width="30"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('source_select'),'ids',this)"/></td>
        <td>物流公司</td>
        <td>订单号</td>
        <td>单据编号</td>
        <td>单据类型</td>
        <td>运单号</td>
        <td>配送状态</td>
        <td>金额</td>
        <td>结算状态</td>
    </tr>
</thead>
<tbody>
{{foreach from=$datas item=data}}
<tr id="ajax_list{{$data.tid}}">
    <td>
    {{if $param.type eq 'clear' && $data.logistic_price}}
    {{elseif $param.type eq 'clear-cod' && $data.cod_status}}
    {{else}}
    <input type="checkbox" name="ids[]" value="{{$data.tid}}"/>
    <input type="hidden" id="pinfo{{$data.tid}}" value='{{$data.info}}'>
    {{/if}}
    </td>
    <td>{{$data.logistic_name}}</td>
    <td>{{$data.bill_no}}</td>
     <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
    <td>{{$data.bill_type}}</td>
    <td>{{$data.logistic_no}}</td>
    <td>{{$data.logistic_status}}</td>
    <td>{{$data.amount+$data.change_amount}}</td>
    {{if $param.type eq 'clear'}}
      <td>{{if $data.logistic_price}}已结算{{else}}未结算{{/if}}</td>
    {{else}}
      <td>{{if $data.cod_status}}已结算{{else}}未结算{{/if}}</td>
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
<p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
{{/if}}
</div>