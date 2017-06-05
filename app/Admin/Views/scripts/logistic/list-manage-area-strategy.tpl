<div class="title">编辑配送策略</div>
<div class="content">
<div>
    <input type="button" value="配送策略列表" onclick="G('{{url param.action=list-area-strategy}}')" />->
    <input type="button" value="顶级区域" onclick="G('{{url param.action=list-manage-area-strategy param.area_id=0}}')" />
    {{foreach from=$place key=count item=data}}
    {{if $data.area_id}}
    -> <input type="button" value="{{$data.area_name}}" onclick="G('{{url param.action=list-manage-area-strategy param.area_id=$data.area_id}}')" />
    {{/if}}
    {{/foreach}}
    <input type="button" value="策略" onclick="
    if(confirm('将修改（中国）下属所有地区的配送策略！')){
        G('{{url param.action=set-area-strategy param.area_id=$data.area_id}}');
    }" />
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    {{foreach from=$area key=key item=data}}
    {{if ($key%2)==0}}<tr>{{/if}}
         <td>
            {{$data.area_name}}
            {{if $count<2}}
            <input type="button" value="管理" onclick="G('{{url param.action=list-manage-area-strategy param.area_id=$data.area_id}}')" />
            {{else if $count==2}}
            <input type="button" value="策略" onclick="G('{{url param.action=set-area-strategy param.area_id=$data.area_id}}')" />
            {{/if}}
        </td>
     {{if ($key%2)==1}}</tr>{{/if}}
    {{/foreach}}
</table>
</div>