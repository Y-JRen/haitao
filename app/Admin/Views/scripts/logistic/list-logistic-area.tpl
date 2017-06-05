<div class="search">
<form method="get" action="{{url param.action=list-logistic-area}}">
    <table>
        <tr>
            <td>地区</td>
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
                <select name="open">
                    <option value=''>是否开通...</option>
                    <option value='0' {{if $open==='0'}}selected{{/if}}>否</option>
                    <option value='1' {{if $open==='1'}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <select name="delivery">
                    <option value=''>能否上门派送...</option>
                    <option value='0' {{if $delivery==='0'}}selected{{/if}}>否</option>
                    <option value='1' {{if $delivery==='1'}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <select name="pickup">
                    <option value=''>能否上门取件...</option>
                    <option value='0' {{if $pickup==='0'}}selected{{/if}}>否</option>
                    <option value='1' {{if $pickup==='1'}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <select name="cod">
                    <option value=''>能否货到付款...</option>
                    <option value='0' {{if $cod==='0'}}selected{{/if}}>否</option>
                    <option value='1' {{if $cod==='1'}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <input type="submit" value="搜索" />
            </td>
        </tr>
    </table>
</form>
</div>

<div id="ajax_search">
<div class="title">物流管理 -&gt; 操作区域管理</div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td width=10></td>
        <td width=50>操作</td>
        <td width=100>物流公司</td>
        <td>省份</td>
        <td>城市</td>
        <td>区县</td>
        <td>是否开通</td>
        <td>是否上门派送</td>
        <td>是否上门取件</td>
        <td>是否代收货款</td>
        <td>锁定状态</td>
    </tr>
    </thead>
    <tbody>
    {{foreach from=$data item=tmp}}
    <tr>
        <td><input type='checkbox' name="ids[]" value="{{$tmp.logistic_area_id}}"></td>
        <td> <input type="button" value="查看" onclick="G('{{url param.action=edit-logistic-area param.logistic_area_id=$tmp.logistic_area_id}}')" /></td>
        <td>
            {{foreach from=$logisticPlugin key=code item=name}}
            {{if $tmp.logistic_code==$code}}{{$name}}{{/if}}
            {{/foreach}}
        </td>
        <td>{{$tmp.province}}</td>
        <td>{{$tmp.city}}</td>
        <td>{{$tmp.area}}</td>
        <td>{{if $tmp.open}}是{{else}}否{{/if}}</td>
        <td>{{if $tmp.delivery}}是{{else}}否{{/if}}</td>
        <td>{{if $tmp.pickup}}是{{else}}否{{/if}}</td>
        <td>{{if $tmp.cod}}是{{else}}否{{/if}}</td>
        <td>{{if $tmp.lock_name}}被<font color="red">{{$tmp.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
<div style="padding:0 5px;">
	<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
	
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
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

function update(area_strategy_id,code,name,value){
	var url = filterUrl('{{url param.action=update-area-strategy-by-ajax}}','id');
	new Request({
			url: url+'/area_strategy_id/'+area_strategy_id+'/code/'+code+'/name/'+name+'/value/'+value,
			method: 'get',
			evalScripts: true,
			onRequest: '',
			onSuccess: function(data){
                console.info(data);
			},
			onFailure: function(){
				alert('error');
			}
		}).send();
}
</script>
