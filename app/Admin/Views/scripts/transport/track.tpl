<form name="myForm1" id="myForm1">
<input type="hidden" name="logistic_code" size="20" value="{{$data.logistic_code}}" />
<input type="hidden" name="bill_type" size="20" value="{{$data.bill_type}}" />
<input type="hidden" name="area_id" size="20" value="{{$data.area_id}}" />
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<input type="hidden" name="logistic_no" size="20" value="{{$data.logistic_no}}" />
<div class="title">运输单跟踪</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="12%"><strong>单据编号</strong></td>
      <td>
        {{foreach from=$data.bill_no_array key=bill_no item=batch_sn}}
          {{$bill_no}} {{if $data.bill_type==1}}<a href="javascript:fGo()" onclick="openDiv('{{url param.controller=order param.action=public-view param.batch_sn=$batch_sn}}','ajax','[{{$bill_no}}]订单详情',750,450,true)"><font color="red"><b>[ 查看订单详情 ]</b></font></a>{{/if}}<br>
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
 		<div id="addinfo2_{{$data.bill_no}}" style="position:absolute; margin-top:62px;  ">
         	<span style="width:95px;height:30px;margin-right:70px;"><strong>省份</strong></span>
            <span style="width:95px;height:30px;margin-right:70px;">{{$data.province}}</span>
            <span style="width:95px;height:30px;margin-right:55px;"><strong>城市</strong></span>
            <span style="width:95px;height:30px;margin-right:240px;">{{$data.city}}</span>
            <span style="width:95px;height:30px;margin-right:60px;"><strong>地区</strong></span>
            <span style="width:95px;height:30px;">{{$data.area}}</span>
        </div>
		<div id="addinfo_{{$data.bill_no}}" style="position:absolute; margin-top:92px;">
         	<span style="width:95px;height:30px;margin-right:30px;"><strong>详细地址</strong></span>
     		<span>{{$data.address}}</span>
        </div>

    <tr >
      <td colspan="6">&nbsp;
      		
      </td>
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
      <td>{{$logisticStatus[$data.logistic_status]}} 

      {{if $data.lock_name eq $auth.admin_name and $data.logistic_status<2}}
      <!--<input type="button" name="dosubmit1" value="重新派单" onclick="openDiv('{{url param.action=reassign}}','ajax','重新派单')"/>-->
      {{/if}}

      </td>
    </tr>
</tbody>
</table>

</div>

{{if $data.lock_name eq $auth.admin_name and $data.logistic_status>=1}}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
	 <tr>
	   <td><strong>维护说明</strong></td>
	   <td>
	     <textarea name="remark" style="width: 400px;height: 50px"></textarea>
	   </td>
	 </tr>
</tbody>
</table>

<div class="submit">
{{foreach from=$logisticStatus item=item key=key}}
{{if $key>0 && $key<4}}
<input type="button" name="dosubmit{{$key}}" value="{{$item}}" onclick="if(confirm('确认维护成[{{$item}}]吗？')){ajax_submit($('myForm1'),'{{url param.logistic_status=$key}}');}"/>
 {{/if}}
{{/foreach}}
</div>
{{/if}}

<div style="margin:10px;border:1px solid #ccc;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr><td width="50%"><strong>跟踪信息</strong></td></tr>
<tr><td valign="top">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="80">操作人</td>
      <td width="150">维护时间</td>
      <td width="80">维护状态</td>
      <td>维护说明</td>
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
</td></tr>
</table>
</div>

</form>