<form>
<input type='hidden' name='logistic_code' value='{{$logisticCode}}'>
<input type='hidden' name='province_id' value='{{$provinceID}}'>
<input type='hidden' name='city_id' value='{{$cityID}}'>
<input type='hidden' name='area_id' value='{{$areaID}}'>
<input type="hidden" name="submit" value="submit" />
<div class="title">编辑配送价格</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tr>
        <td width=120>物流公司</td>
        <td>
		    {{$logistic.name}}
        </td>
    </tr>
    <tr>
        <td>区域</td>
        <td>
		    {{$logisticArea.province}} - {{$logisticArea.city}} - {{$logisticArea.area}}
        </td>
    </tr>
    {{foreach from=$logisticAreaPrice item=data}}
        <tr>
            <td>{{$data.min}}&lt;X&lt;={{$data.max}}</td>
            <td>
                <input type='text' size="6" name='price[{{$data.logistic_area_price_id}}]' value='{{$data.price}}'>元
            </td>
        </tr>
    {{/foreach}}
    <tr>
        <td>&nbsp;</td>
        <td><input type="button" value="编辑" onclick="ajax_submit(this.form,'{{url param.action=edit-logistic-area-price}}')" /></td>
    </tr>
    </table>
</div>
</form>