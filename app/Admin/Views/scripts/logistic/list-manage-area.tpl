<div class="title">物流管理 -&gt; 物流公司管理</div>
<div class="content">
<div>
    <input type="button" value="地区列表" onclick="G('{{url param.action=list-area param.area_id=0}}')" />->
    <input type="button" value="顶级区域" onclick="G('{{url param.action=list-manage-area param.area_id=0}}')" />
    {{foreach from=$place key=count item=data}}
    {{if $data.area_id}}
    -> <input type="button" value="{{$data.area_name}}" onclick="G('{{url param.action=list-manage-area param.area_id=$data.area_id}}')" />
    {{/if}}
    {{/foreach}}
</div>

    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <tr>
        <td colspan=2>
            <form>
            <input type="hidden" name="parent_id" value="{{$areaID}}" />
            区域：<input type="text" size='10' name="area_name" />
            {{if $count==2}}
            区号：<input type="text" size='4' name="code" />
            邮编：<input type="text" size='10' name="zip" />
            {{/if}}
            <input type="button" value="添加" onclick="ajax_submit(this.form,'{{url param.action=add-area}}')" />
            </form>
        </td>
    </tr>
    {{foreach from=$area key=key item=data}}
    {{if ($key%2)==0}}<tr>{{/if}}
         <td>
            <form>
            <input type='hidden' name='area_id' value='{{$data.area_id}}'>
            <input type='text' size='10' name='area_name' value='{{$data.area_name}}'>
			区域差价运费：<input type="text" size='4' name="price" value='{{$data.price}}' />
            {{if $count==2}}
            <input type='text' size='4' name='code' value='{{$data.code}}'>
            <input type='text' size='6' name='zip' value='{{$data.zip}}'>
            {{/if}}
            <input type="button" value="编辑" onclick="ajax_submit(this.form,'{{url param.action=edit-area param.area_id=$data.area_id}}')" />
            <input type="button" value="删除" onclick="if(confirm('确定删除？')){G('{{url param.action=del-area param.area_id=$data.area_id}}');}" />
            {{if $count<2}}
            <input type="button" value="管理" onclick="G('{{url param.action=list-manage-area param.area_id=$data.area_id}}')" />
            {{/if}}
            </form>
        </td>
     {{if ($key%2)==1}}</tr>{{/if}}
    {{/foreach}}
</table>
</div>