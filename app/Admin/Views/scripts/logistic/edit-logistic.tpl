<form name="myForm" id="myForm"  method="post">
<input type="hidden" name="logistic_code" value="{{$logistic.logistic_code}}" />
<div class="title">编辑物流公司</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tr>
        <td width=100>快递公司别名</td>
        <td><input type="text" name="name" value="{{$logistic.name}}" /></td>
    </tr>
    <tr>
        <td>快递公司名</td>
        <td>
            <select name="code" disabled>
            {{foreach from=$logisticPlugin key=code item=label}}
            <option value={{$code}} {{if $code==$logistic.logistic_code}}selected{{/if}}>{{$label}}</option>
            {{/foreach}}
            </select>
        </td>
    </tr>
    <tr>
        <td>代收款费率</td>
        <td><input type="text" name="cod_rate" value="{{$logistic.cod_rate}}" /></td>
    </tr>
    <tr>
        <td>代收款最小费用</td>
        <td><input type="text" name="cod_min" value="{{$logistic.cod_min}}" /></td>
    </tr>
    <tr>
        <td>服务费</td>
        <td><input type="text" name="fee_service" value="{{$logistic.fee_service}}" /></td>
    </tr>
    <tr>
        <td>排序</td>
        <td><input type="text" name="sort" value="{{$logistic.sort}}" /></td>
    </tr>
    <tr>
        <td>启用</td>
        <td>
            <select name="open">
            <option value=0 {{if 0==$logistic.open}}selected{{/if}}>否</option>
            <option value=1 {{if 1==$logistic.open}}selected{{/if}}>是</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>连接地址</td>
        <td><input type="text" name="url" value="{{$logistic.url}}" /></td>
    </tr>
    <tr>
        <td>简介</td>
        <td><input type="text" name="brief" value="{{$logistic.brief}}" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>        
          
          
   
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="编辑" /> </div>
          
        </td>
    </tr>
</table>
</div>

</form>

