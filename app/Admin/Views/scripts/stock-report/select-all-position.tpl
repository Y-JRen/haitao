<form name="productForm" id="positionForm" method="post" action="{{url}}">
<input type="hidden" name="product_id" value="{{$product_id}}" />
<input type="hidden" name="batch_id" value="{{$batch_id}}" />
<input type="hidden" name="url" value="{{$url}}">
<div style="height: 30px; float:left; padding: 0 20px 0 0; margin: 0px"><input type="button" onclick="openDiv('{{url param.controller=stock-report param.action=sel-position param.logic_area=1}}','ajax','查询库位',750,400);" value="添加库位" /></div>
<div style="height: 30px; float:right; padding: 0 20px 0 0; margin: 0px"><input type="button" onclick="$('positionForm').submit()" value="设置并关闭" /></div>
<div style="height: 330px; overflow: auto; clear: both">
<table cellpadding="0" cellspacing="0" border="0" class="table" >
<thead>
  <tr>
    <td>操作</td>
    <td>库区</td>
    <td>库位</td>
  </tr>
</thead>
<tbody id="list">
{{foreach from=$infos item=info}}
<tr id="{{$info.position_id}}">
  <td><input type="button" value="删除" id="line_{{$info.id}}" onclick="removeProduct({{$info.id}})"></td>
  <td>{{$info.district_name}}</td>
  <td>{{$info.position_no}}</td>
</tr>
{{/foreach}}
</tbody>
</table>
</div>
</form>