<div style="height:400px;">
<form name="myForm2" id="myForm2">
<input type="hidden" name="batch_sn" value="{{$order.batch_sn}}">
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td width="30px"><br><br><br></td>
<td width="80px"><b>订单号：</b></td>
<td>
  {{$order.batch_sn}}<br>
</td>
</tr>
<tr>
<td></td>
<td><b>收货地址：</b></td>
<td>{{$order.addr_province}} {{$order.addr_city}} {{$order.addr_area}} {{$order.addr_address}}</td>
</tr>
<tr>
<td></td>
<td><b>收货人：</b></td>
<td>{{$order.addr_consignee}} {{$order.addr_mobile}} {{$order.addr_tel}}</td>
</tr>
<td></td>
<td><b>付款方式：</b></td>
<td>{{if $order.pay_type eq 'cod'}}货到付款{{else}}非货到付款{{/if}}</td>
</tr>
</table>
<br>
<div style="overflow:scroll;height: 190px;">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="30px">&nbsp;</td>
  <td>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
      <thead>
        <tr>
          <td width="300px"><b>产品名称</b></td>
          <td width="60px"><b>产品编号</b></td>
          <td width="40px"><b>数量</b></td>
        </tr>
      </thead>
    </table>
    <table cellpadding="0" cellspacing="0" border="5" class="table" id="area_0">
      {{foreach from=$product name=goods item=goods}}
      <input type="hidden" name="base_number" id="number_{{$goods.product_sn}}" value="{{$goods.number}}_{{$goods.goods_name}}_{{$goods.goods_style}}">
      <tr>
        <td width="300px">{{$goods.goods_name}} (<font color="#FF3333">{{$goods.goods_style}}</font>) </td>
        <td width="60px">{{$goods.product_sn}}</td>
        <td width="40px">{{$goods.number}}</td>
      </tr>
      {{/foreach}}
    </table>
  </td>
</tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="30px">&nbsp;</td>
  <td>
    <br>
    <input type="button" name="submit" value="配货" onclick="ajax_submit($('myForm2'), '{{url}}')">
  </td>
</tr>
</table>
</form>
</div>
