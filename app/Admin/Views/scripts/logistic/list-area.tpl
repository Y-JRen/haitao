<div class="search">
<form method="get" action="{{url param.action=list-area}}">
    <table>
        <tr>
            <td width=50>区号</td>
            <td width=200><input type="input" name="code" value="{{$code}}"></td>
            <td width=50>邮编</td>
            <td width=200><input type="input" name="zip" value="{{$zip}}"></td>
            <td width=50>地区</td>
            <td>
                <select name="province_id" onchange="getArea(this)">
                    <option value="">请选择省份...</option>
                    {{foreach from=$province item=p}}
                    <option value="{{$p.area_id}}" {{if $p.area_id==$provinceID}}selected{{/if}}>{{$p.area_name}}</option>
                    {{/foreach}}            
                </select>
                <select name="city_id" onchange="getArea(this)">
                    <option value="">请选择城市...</option>
                   {{if $province}}
                    {{foreach from=$city item=c}}
                    <option value="{{$c.area_id}}" {{if $c.area_id==$cityID}}selected{{/if}}>{{$c.area_name}}</option>
                    {{/foreach}}            
                    {{/if}}
                </select>
                <select name="area_id">
                    <option value="">请选择地区...</option>
                    {{if $city}}
                    {{foreach from=$area item=a}}
                    <option value="{{$a.area_id}}" {{if $a.area_id==$areaID}}selected{{/if}}>{{$a.area_name}}</option>
                    {{/foreach}}            
                    {{/if}}
                </select>
            </td>
            <td>
                <input type="submit" value="搜索"/>
            </td>
        </tr>
    </table>
</form>
</div>

<div id="ajax_search">
<div class="title">物流管理 -&gt; 配送地区管理</div>
<div class="content">
<form name="upForm" id="upForm" action="{{url param.action=import-logistic-area}}" method="post" enctype="multipart/form-data"  target="ifrmSubmit">
    <input type="hidden" name="submit" value="submit" />
    <input type="file" name="logistic" />
    <input type="submit" value="导入邮编和区号">
</form>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td width=100>国家</td>
        <td width=150>省份</td>
        <td width=150>城市</td>
        <td width=150>区县</td>
        <td width=100>区号</td>
        <td>邮政编码</td>
    </tr>
    </thead>
    <tbody>
    {{foreach from=$areaList item=data}}
    <tr>
        <td>{{$data.country}}</td>
        <td>{{$data.province}}</td>
        <td>{{$data.city}}</td>
        <td>{{$data.area}}</td>
        <td>{{$data.code}}</td>
        <td>{{$data.zip}}</td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<div>
    <input type="button" value="管理" onclick="G('{{url param.action=list-manage-area}}')" />
</div>

</div>

<script>
function getArea(obj)
{
    var areaID = obj.value;
    var select = obj.getNext();
    var url = filterUrl('{{url param.action=list-area-by-json}}','area_id');
	new Request({
            url: url + '/area_id/' + areaID,
			onSuccess: function(json){
                select.options.length = 1;
                if (!obj.getPrevious()) {
                    select.getNext().options.length = 1;
                }
                if (json != '') {
                    data = JSON.decode(json);
                    $each(data, function(item, index){
                        var option = document.createElement("OPTION");
                        option.value = item.area_id;
                        option.text  = item.area_name;
                        select.options.add(option);
                    });
                }
            },
			onFailure: function(){
				alert('error');
			}
		}).send();
}
</script>
