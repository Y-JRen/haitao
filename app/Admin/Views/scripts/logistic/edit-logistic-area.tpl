<form name="myForm" id="myForm" action="{{url param.action=edit-logistic-area}}" method="post">
<input type="hidden" name="logistic_area_id" value="{{$data.logistic_area_id}}" />
<div class="title">编辑操作区域</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tr>
        <td width=120>物流公司</td>
        <td>
		    {{foreach from=$logisticPlugin key=code item=label}}
		    {{if $data.logistic_code==$code}}{{$label}}{{/if}}
		    {{/foreach}}
        </td>
    </tr>
    <tr>
        <td>区域</td>
        <td>
		    {{$data.province}}
		    {{$data.city}}
		    {{$data.area}}
        </td>
    </tr>
    <tr>
        <td>是否开通</td>
        <td>
            <select name="open">
                <option value='0' {{if $data.open==0}}selected="selected"{{/if}}>否</option>
                <option value='1' {{if $data.open==1}}selected="selected"{{/if}}>是</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>能否上门派送</td>
        <td>
            <select name="delivery">
                <option value='0' {{if $data.delivery==0}}selected="selected"{{/if}}>否</option>
                <option value='1' {{if $data.delivery==1}}selected="selected"{{/if}}>是</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>能否上门取件</td>
        <td>
            <select name="pickup">
                <option value='0' {{if $data.pickup==0}}selected="selected"{{/if}}>否</option>
                <option value='1' {{if $data.pickup==1}}selected="selected"{{/if}}>是</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>能否货到付款</td>
        <td>
            <select name="cod">
                <option value='0' {{if $data.cod==0}}selected="selected"{{/if}}>否</option>
                <option value='1' {{if $data.cod==1}}selected="selected"{{/if}}>是</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>可操作区域关键字</td>
        <td><textarea name="delivery_keyword" style="width:450px;height:100px;">{{$data.delivery_keyword}}</textarea></td>
    </tr>
    <tr>
        <td>不可操作区域关键字</td>
        <td><textarea name="non_delivery_keyword"style="width:450px;height:100px;">{{$data.non_delivery_keyword}}</textarea></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>

          {{if $admin == $data.lock_name}}
            <div class="submit">
            <input  type="submit"   name="dosubmit1" id="dosubmit1" value="编辑" />
            </div>
          {{/if}}
            
            
        </td>
    </tr>
</table>
</div>

</form>

