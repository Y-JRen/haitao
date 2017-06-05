<form name="myForm1" id="myForm1" method="post" action="{{url}}">
<input type="hidden" name="bill_type" value="{{$data.bill_type}}">
<input type="hidden" name="bill_no" value="{{$data.bill_no}}">
<div class="title">运输单变更</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="20%"><strong>单据类型</strong></td>
      <td>{{$billType[$data.bill_type]}}</td>
      <td width="20%"><strong>单据编号</strong></td>
      <td>
        {{foreach from=$data.bill_no_array key=bill_no item=batch_sn}}
          {{$bill_no}}<br>
        {{/foreach}}
      </td>
    </tr>
    <tr>
      <td><strong>物流公司</strong></td>
      <td>
        <select name="new_logistic_code">
          {{foreach from=$logisticList item=logistic}}
          <option value="{{$logistic.logistic_code}}|{{$logistic.logistic_name}}|" {{if $data.logistic_code eq $logistic.logistic_code}}selected{{/if}}>{{$logistic.logistic_name}}</option>
	      {{/foreach}}
	      <option value="ems|EMS" {{if $data.logistic_code eq 'ems'}}selected{{/if}}>EMS</option>
	      <option value="st|申通" {{if $data.logistic_code eq 'st'}}selected{{/if}}>申通</option>
		</select>
      </td>
      <td><strong>运输单号</strong></td>
      <td><input type="text" name="new_logistic_no" id="new_logistic_no" size="20" value="{{$data.logistic_no}}"></td>
    </tr>
    {{if $data.lock_name eq $auth.admin_name}}
    <tr>
      <td></td>
      <td>
        <input type="button" name="dosubmit1" value="修改" onclick="if (document.getElementById('new_logistic_no').value ==''){alert('运输单号不能为空');return false;}ajax_submit($('myForm1'),'{{url}}');"/>
      </td>
    </tr>
    {{/if}}
</tbody>
</table>
</div>
</form>
