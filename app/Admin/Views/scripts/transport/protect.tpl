<form name="myForm1" id="myForm1">
<input type="hidden" name="logistic_code" size="20" value="{{$data.logistic_code}}" />
<input type="hidden" name="area_id" size="20" value="{{$data.area_id}}" />
<div class="title">关键字维护</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
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
      <td><strong>物流公司</strong></td>
      <td>{{$data.logistic_name}}</td>
      <td><strong>匹配类型</strong></td>
      <td>{{$data.search_mod}}</td>
      <td><strong>配送状态</strong></td>
      <td>{{$logisticStatus[$data.logistic_status]}}</td>
    </tr>
    <tr>
      <td><strong>可操作区域关键字</strong></td>
      <td colspan="5"><textarea name="delivery_keyword" style="width: 400px;height: 50px">{{$logistic.delivery_keyword}}</textarea></td>
    </tr>
    <tr>
      <td><strong>不可操作区域关键字</strong></td>
      <td colspan="5"><textarea name="non_delivery_keyword" style="width: 400px;height: 50px">{{$logistic.non_delivery_keyword}}</textarea></td>
    </tr>
</tbody>
</table>

</div>

<div class="submit">
{{if $data.lock_name eq $auth.admin_name and is_protect==0}}
<input type="button" name="dosubmit1" value="保存" onclick="if(confirm('确认保存吗？')){ajax_submit($('myForm1'),'{{url}}');}"/>
{{/if}}
</div>
</form>