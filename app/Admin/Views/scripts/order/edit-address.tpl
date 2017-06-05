<form id="myform1">
<input type="hidden" name="order_sn" value="{{$data.order_sn}}" />
<input type="hidden" name="type" value="submit" />
<input type="hidden" name="order_step" value="{$order_step}" />
<table>
	<tr><td>收货人</td><td><input type='text' name='addr_consignee' value='{{$data.addr_consignee}}'></td></tr>
	<tr>
		<td>地区</td>
		<td>
			<select name="addr_province_id" onchange="getArea(this)">
				<option value="">请选择省</option>
				{{html_options options=$data.addr_province_option selected=$data.addr_province_id}}
			</select>
			<select name="addr_city_id" onchange="getArea(this)">
				<option value="">请选择市</option>
				{{html_options options=$data.addr_city_option selected=$data.addr_city_id}}
			</select>
			<select name="addr_area_id" id="addr_area_id">
				<option value="">请选择区</option>
				{{html_options options=$data.addr_area_option selected=$data.addr_area_id}}
			</select>
		</td>
	</tr>
	<tr><td>收货地址</td><td><input type='text' size="100" name='addr_address' value='{{$data.addr_address}}'></td></tr>
	<tr><td>英文地址</td><td><input type='text' size="100" name='addr_eng_address' value='{{$data.addr_eng_address}}'></td></tr>
	<tr><td>电话</td><td><input type='text' name='addr_tel' value='{{$data.addr_tel}}'></td></tr>
	<tr><td>手机</td><td><input type='text' name='addr_mobile' value='{{$data.addr_mobile}}'></td></tr>
	{{if $data.sms_no}}
	<tr><td>短信接收号码</td><td><input type='text' name='sms_no' value='{{$data.sms_no}}'></td></tr>
	{{/if}}
	<tr><td>邮箱</td><td><input type='text' name='addr_email' value='{{$data.addr_email}}'></td></tr>
	<tr>
		<td></td>
		<td>
			<input type="button" value="确定" onclick="if($('addr_area_id').value==''){alert('请选择省市区！');return false;} ajax_submit($('myform1'),'{{url param.action=edit-address}}')" />
			<input type="button" onclick="G('{{url param.action=not-confirm-info}}')" value=" 返回订单页 " name="do"/>		
		</td>
	</tr>
</table>

</form>
<script>

function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    var parent = $(id).getParent();
    var last = parent.getLast();
    last.options.length = 1;

    new Request({
        url: '{{url param.action=area}}/parent_id/' + value,
        //onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
					option.value = index;
					option.text  = item;
                    select.options.add(option);
	            });
	            if (select.name == 'addr_area_id') {
    	            var option = document.createElement("OPTION");
    			    option.value = -1;
    			    option.text  = '其它区';
                    select.options.add(option);
                }
	        }
            //loadSucess();
        }
    }).send();
}

</script>
