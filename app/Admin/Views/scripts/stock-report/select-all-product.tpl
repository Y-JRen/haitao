<form name="productForm" id="productForm" method="post" action="{{url}}">
<input type="hidden" name="position_id" value="{{$position_id}}">
<input type="hidden" name="url" value="{{$url}}">
<div style="height: 30px; float:left; padding: 0 20px 0 0; margin: 0px"><input type="button" onclick="openDiv('{{url param.controller=product param.action=sel param.type=sel param.logic_area=1}}','ajax','查询商品',750,400);" value="添加产品" /></div>
<div style="height: 30px; float:right; padding: 0 20px 0 0; margin: 0px"><input type="button" onclick="$('productForm').submit()" value="设置并关闭" /></div>
<div style="height: 330px; overflow: auto; clear: both">
<table cellpadding="0" cellspacing="0" border="0" class="table" >
<thead>
  <tr>
    <td>操作</td>
    <td>产品编码</td>
    <td>产品名称</td>
    <td>产品批次</td>
  </tr>
</thead>
<tbody id="list">
{{foreach from=$datas item=data}}
<tr>
  <td><input type="button" value="删除" id="line_{{$data.id}}" onclick="removeProduct({{$data.id}})"></td>
  <td>{{$data.product_sn}}</td>
  <td>{{$data.product_name}}</td>
  <td>{{$data.batch_no|default:'无批次'}}</td>
</tr>
{{/foreach}}
</tbody>
</table>
</div>
</form>