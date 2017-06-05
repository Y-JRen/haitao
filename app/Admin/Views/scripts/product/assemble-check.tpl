<form name="myForm1" id="myForm1">
<div class="title">组装开单管理  -&gt; 组装开单审核</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
        <td width="15%"><strong>组装单号：</strong></td>
        <td>{{$info.assemble_sn}}</td>
    </tr>
    <tr>
        <td width="10%"><strong>类型：</strong> </td>
        <td>
            {{$info.type_name}}
        </td>
    </tr>
    <tr>
      <td width="10%" colspan="2"><strong>组装出库产品 </strong> </td>
    </tr>
    <tr>
        <td colspan="2">
            <table cellpadding="0" cellspacing="0" border="0" class="table">
                <thead>
                    <tr>
                        <td>产品编码</td>
                        <td>产品名称</td>
                        <td>成本价</td>
                        <td>出库数量</td>
                    </tr>
                </thead>
                <tbody>
                    {{foreach from=$assemble_details item=val}}
                    <tr>
                        <td>{{$val.product_sn}}</td>
                        <td>{{$val.product_name}}</td>
                        <td>{{$val.cost}}</td>
                        <td>{{$val.number}}</td>
                    </tr>
                    {{/foreach}}
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
      <td width="10%" colspan="2"><strong>组装入库产品 </strong> </td>
    </tr>
    <tr>
        <td colspan="2">
            <table cellpadding="0" cellspacing="0" border="0" class="table">
                <thead>
                    <tr>
                        <td>产品编码</td>
                        <td>产品名称</td>
                        <td>成本价</td>
                        <td>入库数量</td>
                    </tr>
                </thead>
                <tbody>
                    {{foreach from=$assemble_finished_details item=val}}
                    <tr>
                        <td>{{$val.product_sn}}</td>
                        <td>{{$val.product_name}}</td>
                        <td>{{$val.cost}}</td>
                        <td>{{$val.number}}</td>
                    </tr>
                    {{/foreach}}
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
      <td><strong>备注：</strong> </td>
      <td>{{$info.remark}}</td>
    </tr>
    {{if $info.lock eq 'yes' && $query_type eq ''}}
    <tr>
      <td><strong>备注：</strong> </td>
      <td style="font-size:16px"><textarea name="remark" id="remark" style="width: 400px;height: 50px" msg="请填写备注" class="required"></textarea></td>
    </tr>
    {{/if}}
</tbody>
</table>
{{if $info.lock eq 'yes' && $query_type eq ''}}
<div class="submit">
  <input type="hidden" name="is_check" id="is_check" value="0" />
  <input type="hidden" name="assemble_id" value="{$info.assemble_id}" />
  <input type="button" name="dosubmit1" id="dosubmit1" value="同意" onclick="dosubmit(1)"/>
  <input type="button" name="dosubmit1" id="dosubmit1" value="拒绝" onclick="dosubmit(2)"/>
</div>
{{/if}}
</form>
<script language="JavaScript">
function dosubmit(is_check)
{
	if(confirm('确认提交申请吗？')){
		$("is_check").value = is_check;
		ajax_submit($('myForm1'),'{{url}}');
	}
}
</script>