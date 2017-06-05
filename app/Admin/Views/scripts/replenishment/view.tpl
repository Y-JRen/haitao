<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="common">
<tbody>
    <tr> 
      <td width="10%"><strong>产品编码</strong></td>
      <td>{{$data.product_sn}}</td>
    </tr>
    <tr> 
      <td><strong>产品名称</strong></td>
      <td>{{$data.product_name}}</td>
    </tr>
    <tr> 
      <td><strong>请求数量</strong></td>
      <td>{{$data.require_number}}</td>
    </tr>
    <tr> 
      <td><strong>收货数量</strong></td>
      <td>{{$data.receive_number}}</td>
    </tr>
</tody>
</table>
<div id="ajax_search">
{{if !empty($details)}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>订单号</td>
        <td>店铺</td>
        <td>产品数量</td>
        <td>订单状态</td>
        <td>业务状态</td>
    </tr>
</thead>
<tbody>
{{foreach from=$details item=order}}
<tr>
    <td>{{if $order.type eq '2'}} {{$order.batch_sn}}{{else}}{{$order.external_order_sn}}{{/if}}</td>
    <td>{{$order.shop_name}}</td>
    <td>{{$order.number}}</td>
    <td>
      {{if $order.type eq '2'}}
          {{if $order.status eq '0'}}正常单
          {{elseif $order.status eq 1}}取消单
          {{elseif $order.status eq 2}}无效单
          {{elseif $order.status eq 3}}渠道刷单
          {{elseif $order.status eq 4}}不发货订单
          {{elseif $order.status eq 5}}预售订单
          {{/if}}
      {{else}}
          {{if $order.shop_order_status eq 2}}待发货
          {{elseif $order.shop_order_status eq 3}}待确认收货
          {{elseif $order.shop_order_status eq 10}}已完成
          {{elseif $order.shop_order_status eq 11}}已取消
          {{elseif $order.shop_order_status eq 12}}其它
          {{/if}}
      {{/if}}
    </td>
    <td>
    {{if $order.type eq '2'}}
    {{if $order.status_logistic eq 0}}未确认
    {{elseif $order.status_logistic eq 1}}待收款
    {{elseif $order.status_logistic eq 2}}待发货
    {{elseif $order.status_logistic eq 3}}已发货
    {{elseif $order.status_logistic eq 4}}已签收
    {{elseif $order.status_logistic eq 5}}已拒收
    {{/if}}
    {{else}}
    {{if $order.status_business eq 0}}未审核
    {{elseif $order.status_business eq 1}}审核通过
    {{elseif $order.status_business eq 2}}已打印
    {{elseif $order.status_business eq 4}}直接第3方物流发货
    {{elseif $order.status_business eq 9}}审核不通过
    {{/if}}
    {{/if}}
    </td>
</tr>
{{/foreach}}
</tbody>
</table>
{{/if}}
</div>
<!--
{{if !$data.status}}
<br>
<div style="text-align:center">
<form name="myForm1" id="myForm1">
<input type="button" name="confirm" value="确认" onclick="ajax_submit($('myForm1'),'{{url}}');">
</form>
</div>
{{/if}}
-->