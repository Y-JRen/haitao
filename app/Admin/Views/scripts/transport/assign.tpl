<form name="myForm1" id="myForm1">
<input type="hidden" name="bill_type" size="20" value="{{$data.bill_type}}" />
<input type="hidden" name="bill_no" size="20" value="{{$data.bill_no}}" />
<div class="title">物流派单</div>
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
      <td width="12%"></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>制单日期</strong></td>
      <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
      <td><strong>制单人</strong></td>
      <td>{{$data.admin_name}}</td>
      <td></td>
      <td></td>
    </tr>
    <tr >
      <td colspan="6">&nbsp;
      		
      </td>
    </tr>
      <tr id="adddiv_{{$data.bill_no}}" >
      <td colspan="6">
      	
      </td>
    </tr>
 		<div id="addinfo2_{{$data.bill_no}}" style="position:absolute; margin-top:62px;  ">
         	<span style="width:95px;height:30px;margin-right:70px;"><strong>省份</strong></span>
            <span style="width:95px;height:30px;margin-right:55px;">{{$data.province}}</span>
            <span style="width:95px;height:30px;margin-right:55px;"><strong>城市</strong></span>
            <span style="width:95px;height:30px;margin-right:225px;">{{$data.city}}</span>
            <span style="width:95px;height:30px;margin-right:30px;"><strong>地区</strong></span>
            <span style="width:95px;height:30px;">{{$data.area}}</span>
        </div>
		<div id="addinfo_{{$data.bill_no}}" style="position:absolute; margin-top:92px; ">
         	<span style="width:95px;height:30px;margin-right:30px;"><strong>详细地址</strong></span>
     		<span>{{$data.address}}</span>
        </div>

    <tr >
      <td colspan="6">&nbsp;
      		
      </td>
    </tr>
    <tr>
      <td><strong>配送方式</strong></td>
      <td>{{if $data.logistic_code neq 'ems'}}快递{{else}}EMS{{/if}}</td>
      <td><strong>{{if $data.is_cod}}应收金额{{else}}订单金额{{/if}}</strong></td>
      <td>{{$data.amount}}</td>
      <td><strong>商品数量</strong></td>
      <td>{{$data.goods_number}}</td>
    </tr>
    <tr>
      <td><strong>备注</strong></td>
      <td colspan="5">&nbsp;{{$data.remark}}</td>
    </tr>
    <tr>
      <td><strong>重量</strong></td>
      <td>{{$data.weight}} </td>
      <td><strong>体积</strong></td>
      <td>{{$data.volume}}</td>
      <td><strong>件数</strong></td>
      <td><input type="text" name="number" size="6" maxlength="6" value="1" /></td>
    </tr>
    <tr>
      <td><strong>付款方式</strong></td>
      <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
      <td><strong>承运商</strong></td>
      <td colspan="3">
      <select name="logistic">
		{{$logisticList}}
	  </select>
	  </td>
    </tr>
</tbody>
</table>

</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name}}
<input type="button" name="dosubmit1" id="dosubmit1" value="确认派单" onclick="dosubmit()"/>
{{/if}}
</div>
</form>
<script>
function dosubmit()
{
	if(confirm('确认派单吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed()
{
	$('dosubmit1').value = '确认派单';
	$('dosubmit1').disabled = false;
}

</script>