<div class="title" >查看详情</div>
<div class="content" style="position:relative;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" >
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td width="34%">
        {{foreach from=$data.bill_no_array key=bill_no item=batch_sn}}
          {{$bill_no}} {{if $data.bill_type==1}}<a href="javascript:fGo()" onclick="openDiv('{{url param.controller=order param.action=public-view param.batch_sn=$batch_sn}}','ajax','[{{$bill_no}}]订单详情',750,450,true)"><font color="red"><b>[ 查订单详情 ]</b></font></a>{{/if}}<br>
        {{/foreach}}
      </td>
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
    <tr >
 		<div id="addinfo2_{{$data.bill_no}}" style="position:absolute; margin-top:62px; ">
         	<span style="width:95px;height:30px;margin-right:70px;"><strong>省份</strong></span>
            <span style="width:95px;height:30px;margin-right:90px;">{{$data.province}}</span>
            <span style="width:95px;height:30px;margin-right:55px;"><strong>城市</strong></span>
            <span style="width:95px;height:30px;margin-right:150px;">{{$data.city}}</span>
            <span style="width:95px;height:30px;margin-right:60px;"><strong>地区</strong></span>
            <span style="width:95px;height:30px;">{{$data.area}}</span>
        </div>
		<div id="addinfo_{{$data.bill_no}}" style="position:absolute; margin-top:92px;">
         	<span style="width:95px;height:30px;margin-right:30px;"><strong>详细地址</strong></span>
     		<span>{{$data.address}}</span>
        </div>
	</tr>
    <tr >
      <td colspan="6">&nbsp;
      		
      </td>
    </tr>
    
    {{if $data.validate_sn}}
    <tr>
      <td width="80"><strong>验证码</strong></td>
      <td colspan="5" width="280">{{$data.validate_sn}}</td>
    </tr>
    {{/if}}
    <tr>
      <td width="80"><strong>备注</strong></td>
      <td colspan="5" width="280">&nbsp;{{$data.remark}}</td>
    </tr>
    <tr>
      <td  width="80"><strong>物流公司</strong></td>
      <td  width="80">{{$data.logistic_name}}</td>
      <td  width="80"><strong>运单号</strong></td>
      <td  width="80">{{$data.logistic_no}}</td>
      <td  width="80"><strong>配送状态</strong></td>
      <td  width="80">{{$logisticStatus[$data.logistic_status]}}</td>
    </tr>
</tbody>
</table>

{{if $op}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
<tr>
    <td>操作时间</td>
    <td>操作人</td>
    <td>操作内容</td>
    <td>备注</td>
    </tr>
</thead>
<tbody>
	{{foreach from=$op item=d}}
	<tr>
	<td width="150">{{$d.op_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
	<td>{{$d.admin_name}}</td>
	<td>{{if $d.op_type=='assign'}}
	物流派单
	{{elseif $d.op_type=='confirm'}}
	运输单确认
	{{elseif $d.op_type=='prepare'}}
	仓库配库
	{{/if}}
	{{$d.item_value}}</td>
	<td>{{$d.remark}}</td>
	</tr>
	{{/foreach}}
</tbody>
</table>
{{/if}}

{{if $tracks}}
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

{{/if}}
</div>

<div class="submit">
<input type="button" onclick="window.open('{{url param.action=print2 param.id=$data.tid param.is_cod=$data.is_cod param.logistic_code=$data.logistic_code}}')" value="打印运输单">
{{if $data.bill_type eq 1}}
<input type="button" onclick="window.open('/admin/logic-area-out-stock/print/bill_no/{{$data.bill_no}}')" value="打印销售单">
{{/if}}
</div>
