<form name="upForm" id="upForm" action="{{url}}" method="post" enctype="multipart/form-data" target="ifrmSubmit">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>标准图片</strong></td>
      <td width="88%">
        {{if $data.goods_img!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_180_180.'}}" border="0" width="50"><br>
        {{/if}}
        <input type="file" name="goods_img" msg="请上传商品图片"/>
    </tr>
    <tr>
      <td width="12%"><strong>广告展示图</strong></td>
      <td>
        {{if $data.goods_arr_img!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.goods_arr_img|replace:'.':'_180_180.'}}" border="0" width="50"><br>
        {{/if}}
        <input type="file" name="goods_arr_img" msg="请上传商品图片"/>
    </tr>
    {{if $img_url}}	
	<tr>
      <td><strong>细节图片</strong></td>
      <td>{{if !empty($img_url)}}<ul id="showimgs">
      {{foreach from=$img_url item=r}}
      <li id="ajax_list{{$r.img_id}}">
      <img src="{{$imgBaseUrl}}/{{$r.img_url|replace:'.':'_60_60.'}}" border="0"><br>
      {{assign var="img_id" value=$r.img_id}}
      <input type="checkbox" name="img_ids[]" value="{{$r.img_id}}" {{if $img_ids.$img_id}}checked{{/if}}>
       </li>
      {{/foreach}}
      </ul>{{/if}}
	  </td>
    </tr>
    {{/if}}
    <!--
    <tr>
      <td><strong>展示图片</strong></td>
      <td>{{if !empty($img_ext_url)}}<ul id="showimgs">
      {{foreach from=$img_ext_url item=r}}
      <li id="ajax_list{{$r.img_id}}">
      <img src="{{$imgBaseUrl}}/{{$r.img_url|replace:'.':'_60_60.'}}" border="0"><br>
      {{assign var="img_id" value=$r.img_id}}
      <input type="checkbox" name="img_ids[]" value="{{$r.img_id}}" {{if $img_ids.$img_id}}checked{{/if}}>
       </li>
      {{/foreach}}
      </ul>{{/if}}
      </td>
    </tr>
    -->
</tbody>
</table>
<div style="margin:0 auto;padding:10px;">
<input type="submit" name="dosubmit1" id="dosubmit1" value="保存">
</div>
</form>
