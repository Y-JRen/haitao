<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>

<div class="title">支付LOG查看</div>
<div class="search">
<form name="searchForm" id="searchForm" >
 开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>

支付方式：<select name="pay_type" id="pay_type">
          <option value="" >请选择 </option>
            {{foreach from=$payment_list item=payment }}
             <option value="{{$payment.pay_type}}" {{if $param.pay_type eq $payment.pay_type}}selected{{/if}}>  {{$payment.name}} </option>
            {{/foreach}}
		</select>
订单号码：<input type="text" name="batch_sn" size="20" maxLength="50" value="{{$param.batch_sn}}"/>
<input type="submit" name="dosearch" value="查询"/>
</form>
</div>


<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td >支付流水 ID</td>
            <td >订单号</td>
            <td>支付代码</td>
            <td>支付方式</td>
            <td>支付金额</td>
            <td>支付时间</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.supplier_id}}">
            <td>{{$data.pay_log_id}}</td>
            <td>{{$data.batch_sn}}</td>
            <td>{{$data.pay_type}}</td>
            <td>{{$payment_list[$data.pay_type].name}} </td>
            <td>{{$data.pay}}</td>
            <td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>