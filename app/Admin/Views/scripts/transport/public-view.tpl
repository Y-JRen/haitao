<div class="title">查看详情</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>{{$data.bill_no}} {{if $data.bill_type==1}}<a href="javascript:fGo()" onclick="openDiv('{{url param.controller=order param.action=public-view param.batch_sn=$data.bill_no}}','ajax','[{{$data.bill_no}}]订单详情',750,450,true)"><font color="red"><b>[ 查看订单详情 ]</b></font></a>{{/if}}</td>
      <td width="12%"><strong>制单日期</strong></td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
    </tr>
    <tr>
      <td width="12%"><strong>付款方式</strong></td>
      <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
      <td width="12%"><strong>配送方式</strong></td>
      <td>{{if $data.logistic_code neq 'ems'}}快递{{else}}EMS{{/if}}</td>
      <td width="12%"><strong>订单金额</strong></td>
      <td>{{$data.amount}}</td>
    </tr>
    <tr>
      <td><strong>省份</strong></td>
      <td>{{$data.province}}</td>
      <td><strong>城市</strong></td>
      <td>{{$data.city}}</td>
      <td><strong>地区</strong></td>
      <td>{{$data.area}}</td>
    </tr>
    <tr>
      <td><strong>详细地址</strong></td>
      <td colspan="5">{{$data.address}}</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="5">&nbsp;{{$data.remark}}</td>
    </tr>
    <tr>
      <td><strong>物流公司</strong></td>
      <td>{{$data.logistic_name}}</td>
      <td><strong>运单号</strong></td>
      <td>{{$data.logistic_no}}</td>
      <td><strong>配送状态</strong></td>
      <td>{{$logisticStatus[$data.logistic_status]}}</td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tr>
      <td width="80"><strong>操作人</strong></td>
      <td width="150"><strong>维护时间</strong></td>
      <td width="80"><strong>维护状态</strong></td>
      <td><strong>维护说明</strong></td>
    </tr>
{{foreach from=$tracks item=t}}
    <tr>
      <td>{{$t.admin_name}}</td>
      <td>{{$t.op_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
      <td>{{$logisticStatus[$t.logistic_status]}}</td>
      <td>{{$t.remark}}</td>
    </tr>
{{/foreach}}
</table>
</div>

