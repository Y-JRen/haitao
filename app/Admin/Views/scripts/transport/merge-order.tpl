<div style="height:400px;overflow:scroll;">
<form action="/admin/transport/merge-order" name="myForm" id="myForm">
<input type="hidden" name="ids" value="{{$ids}}">
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td width="30px"><br><br><br></td>
<td width="80px"><b>合并订单号：</b></td>
<td>
{{foreach from=$datas item=order}}
  {{$order.batch_sn}}<br>
{{/foreach}}
</td>
</tr>
<tr>
<td></td>
<td><b>收货地址：</b></td>
<td>{{$data.addr_province}} {{$data.addr_city}} {{$data.addr_area}} {{$data.addr_address}}</td>
</tr>
<tr>
<td></td>
<td><b>收货人：</b></td>
<td>{{$data.addr_consignee}} {{$data.addr_mobile}} {{$data.addr_tel}}</td>
</tr>
</table>
<br>
<div style="overflow:scroll;height: 220px;">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="30px">&nbsp;</td>
  <td>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
      <thead>
        <tr>
          <td><b>产品名称</b></td>
          <td><b>产品编号</b></td>
          <td><b>数量</b></td>
        </tr>
      </thead>
      <tbody>
        {{foreach from=$product item=goods}}
        <tr>
          <td>{{$goods.goods_name}} (<font color="#FF3333">{{$goods.goods_style}}</font>) </td>
          <td>{{$goods.product_sn}}</td>
          <td>{{$goods.number}}</td>
        </tr>
        {{/foreach}}
      </tbody>
    </table>
  </td>
</tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="30px">&nbsp;</td>
  <td><br><input type="button" name="submit" value="配货" onclick="ajax_submit($('myForm'), '{{url}}')"><br><br></td>
</tr>
</table>
</form>
</div>
