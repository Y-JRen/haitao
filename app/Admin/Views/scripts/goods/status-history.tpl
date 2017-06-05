<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<form id="myform">
<br>
  <table width="100%" border="0">
        <tr bgcolor="#F0F1F2">
          <td width="100">　商品名称：</td>
          <td>{{$goods.goods_name}}</td>
        </tr>
  </table>
  <br>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
      <td>操作状态</td>
      <td>备注</td>
      <td>管理员</td>
      <td>操作时间</td>
    </tr>
    </thead>
    {{if $history}}
    {{foreach from=$history item=item}}
    <tr>
      <td>{{$item.status}}</td>
      <td>{{$item.remark}}</td>
      <td>{{$item.admin_name}}</td>
      <td>{{$item.op_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
    </tr>
    {{/foreach}}
    {{/if}}
  </table>
<br>
