<div class="tree_div" id="treeboxbox_tree" style="padding: 5px; width:98%; height: 400px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;">
    <table cellpadding="0" cellspacing="0" border="0" id="table">
    <tbody>
    {{foreach from=$menus item=data}}
    <tr id="menu_{{$data.menu_id}}">
        <td style="padding-left:{{$data.step*20}}px;{{if $data.step==1}}padding-top:20px;color:red{{/if}}">
        {{if $data.leaf}}<input type="checkbox" name="menu[{{$data.menu_id}}]" value="{{$data.menu_path}}" checked onclick="selectChildAll(this,{{$data.menu_id}})">{{/if}}
        <b>{{$data.menu_title}}<b>
    {{if $data.leaf && $data.privilege}}
    <table style="margin-left:20px;border:1px solid #ccc;width:95%">
    <tr id="privilege_{{$data.menu_id}}">
        <td style="background:#f2f2f2;padding:3px">
        {{foreach from=$data.privilege item=p key=key}}
        {{if $group_privilege[$data.menu_id][$p.privilege_id]}}
        <input type="checkbox" name="privilege[{{$p.privilege_id}}]" value="{{$p.privilege_id}}" checked> {{$p.title}}&nbsp;&nbsp;&nbsp;&nbsp;
        {{/if}}
        {{/foreach}}
        </td>
    </tr>
    </table>
    {{/if}}
    </td></tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<br>
<input type="checkbox" value="" onclick="selectAll(this)" checked>
全选/不选
