<form name="myForm" id="myForm" action="{{url param.action=set-area-strategy}}" method="post">
<div class="title">编辑配送价格</div>
<div class="content">
<div>
<b>配送区域：</b>
    {{foreach from=$place key=count item=area}}
    {{$area.area_name}}
    {{/foreach}}
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tr>
        <td>物流公司</td>
        <td>优先级</td>
        <td>指定</td>
        <td>是否开启</td>
    </tr>
    <input type='hidden' name='area_id' value="{{$area.area_id}}">
    <input type='hidden' name='open_logistic_code' id='open_logistic_code'>
    {{foreach from=$logistic item=data}}
        <tr>
            <td>{{$data.name}}</td>
            <td><input type='text' size=3 name='strategy[{{$data.logistic_code}}][rank]'></td>
            <td>
            <input type='radio' name='use' onchange="$('open_logistic_code').value='{{$data.logistic_code}}';">
            </td>
            <td><input type='checkbox' name='strategy[{{$data.logistic_code}}][open]' value='1' checked='checked'></td>
        </tr>
    {{/foreach}}
    </table>
    <div>
    <input  type="submit"   name="submit" id="submit" value="编辑" />
    </div>
    
</div>
</form>

