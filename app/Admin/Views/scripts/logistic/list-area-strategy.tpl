<div class="search">
<form method="get" action="{{url param.action=list-area-strategy}}">
    <table>
        <tr>
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
<div class="title">物流管理 -&gt; 配送策略管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td width=150>省份</td>
        <td width=150>城市</td>
        <td width=150>区县</td>
        <td width=150>物流公司</td>
        <td width=150>优先级</td>
        <td width=150>指定</td>
        <td width=150>开启</td>
    </tr>
    </thead>
    <tbody>
    {{foreach from=$strategy item=data}}
    <tr>
        <td>{{$data.province}}</td>
        <td>{{$data.city}}</td>
        <td>{{$data.area}}</td>
        <td>
            {{if $data.strategy}}
            {{foreach from=$data.strategy key=code_1 item=tmp}}
            {{foreach from=$logisticPlugin key=code_2 item=label}}
            {{if $code_1==$code_2}}{{$label}}{{/if}}
            {{/foreach}}<br>
            {{/foreach}}
            {{/if}}
        </td>
        <td>
            {{if $data.strategy}}
            {{foreach from=$data.strategy key=code_1 item=tmp}}
            <input type='text' size=3 value='{{$tmp.rank}}' 
            onchange="update({{$data.area_id}},'{{$code_1}}','rank',this.value)"><br>
            {{/foreach}}
            {{/if}}
        </td>
        <td>
            {{if $data.strategy}}
            {{foreach from=$data.strategy key=code_1 item=tmp}}
            <input type='radio' name='radio_{{$data.area_id}}' value=1 {{if $tmp.use}}checked="checked"{{/if}}
            onchange="update({{$data.area_id}},'{{$code_1}}','use',this.value)"><br>
            {{/foreach}}
            {{/if}}
        </td>
        <td>
            {{if $data.strategy}}
            {{foreach from=$data.strategy key=code_1 item=tmp}}
            <input type='checkbox' value=1 {{if $tmp.open}}checked='checked'{{/if}}
            onchange="if(this.checked){var value=1;}else{var value=0}update({{$data.area_id}},'{{$code_1}}','open',value)"><br>
            {{/foreach}}
            {{/if}}
        </td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<div>
    <input type="button" value="管理" onclick="G('{{url param.action=list-manage-area-strategy}}')" />
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

function update(area_id,code,name,value){
	var url = filterUrl('{{url param.action=update-area-strategy-by-ajax}}','id');
	new Request({
			url: url+'/area_id/'+area_id+'/code/'+code+'/name/'+name+'/value/'+value,
			method: 'get',
			evalScripts: true,
			onRequest: '',
			onSuccess: function(data){
			},
			onFailure: function(){
				alert('error');
			}
		}).send();
}
</script>
