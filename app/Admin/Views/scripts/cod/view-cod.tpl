<div class="title">查看详情</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>{{$data.bill_no}} {{if $data.bill_type==1}}<a href="javascript:fGo()" onclick="openDiv('{{url param.controller=order param.action=public-view param.batch_sn=$data.bill_no}}','ajax','[{{$data.bill_no}}]订单详情',750,450,true)"><font color="red"><b>[ 查看订单详情 ]</b></font></a>{{/if}}</td>
      <td width="12%"><strong>付款方式</strong></td>
      <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
    </tr>
    <tr>
      <td width="12%"><strong>订单金额</strong></td>
      <td>{{$data.amount}}</td>
      <td><strong>结算金额</strong></td>
      <td>{{$data.amount+$data.change_amount}}</td>
      <td><strong>结算状态</strong></td>
      <td>{{if $data.cod_status==1}}已{{else}}未{{/if}}结算</td>
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

</div>

<div class="submit">
{{if $data.is_cod==1 and $data.is_change==0 and $data.change_amount eq '0.00'}}
<input type="button" onclick="openDiv('{{url param.action=cod-change param.id=$data.tid}}','ajax','申请变更')" value="申请变更">
{{/if}}
</div>
