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
          <td width="300px"><b>产品名称</b></td>
          <td width="60px"><b>产品编号</b></td>
          <td width="40px"><b>数量</b></td>
          <td><b>操作</b></td>
        </tr>
      </thead>
    </table>
    <table cellpadding="0" cellspacing="0" border="5" class="table" id="area_0">
      {{foreach from=$product name=goods item=goods}}
      <input type="hidden" name="base_number" id="number_{{$goods.product_sn}}" value="{{$goods.number}}_{{$goods.goods_name}}_{{$goods.goods_style}}">
      <tr>
        <td width="300px">{{$goods.goods_name}} (<font color="#FF3333">{{$goods.goods_style}}</font>) </td>
        <td width="60px">{{$goods.product_sn}}*{{$goods.number}}</td>
        <td width="40px"><input type="text" name="number[]" id="number_{{$goods.product_sn}}" style="text-align:center;width:80%" value="{{$goods.number}}" onkeypress="return NumOnly(event)"><input type="hidden" name="product[]" value="0_{{$goods.product_sn}}"></td>
        {{if $smarty.foreach.goods.iteration eq 1}}
        <td rowspan="{{$product|@count}}"><input type="button" name="split" value="拆分" onclick="splitOrder()"></td>
        {{/if}}
      </tr>
      {{/foreach}}
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
      <thead>
        <tr>
          <td><b>新生成订单</b></td>
        </tr>
      </thead>
    </table>
    <div id="newArea">
      
    </div>
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
