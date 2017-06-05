<form name="myForm1" id="myForm1">
<input type="hidden" name="transport[bill_type]" size="20" value="2" />
<input type="hidden" name="transport[logistic_status]" size="20" value="1" />
<div class="title">新增派送任务</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
	<tr>
	<td width="10%">收货人姓名</td>
	<td><input type="text" name="transport[consignee]" size="20" maxlength="40" value="" class="required" msg="请填写收货人姓名"/></td>
	</tr>
	<tr>
	<td width="10%">派送区域</td>
	<td>
	<select name="transport[province_id]" onchange="getArea(this)">
	    <option value="">请选择省</option>
		{{html_options options=$province}}
	</select>
	<select name="transport[city_id]" onchange="getArea(this)">
	    <option value="">请选择市</option>
		{{html_options options=$city}}
	</select>
	<select name="transport[area_id]" class="required" msg="请选择省市区" id="area_id">
	    <option value="">请选择区</option>
		{{html_options options=$area}}
	</select>
	</td>
	</tr>
	<tr>
	<td width="10%">详细地址</td>
	<td><input type="text" name="transport[address]" size="50" maxlength="100" value="" class="required" msg="请填写详细地址" id="address" /></td>
	</tr>
	<tr>
	<td width="10%">电话</td>
	<td><input type="text" name="transport[tel]" size="20" maxlength="40" value="" /></td>
	</tr>
	<tr>
	<td width="10%">手机</td>
	<td><input type="text" name="transport[mobile]" size="20" maxlength="20" value="" /></td>
	</tr>
    <tr>
      <td width="10%">发货信息</td>
      <td>
      代收货款：<select name="transport[is_cod]" id="is_cod"><option value="0">否</option><option value="1">是</option></select>
      发货件数：<input type="text" name="transport[number]" size="4" maxlength="6" value="1"  class="required number" msg="请填写发货件数" />
      重量：<input type="text" name="transport[weight]" size="6" maxlength="6" value="" id="weight" class="required number" msg="请填写重量" /> kg
      体积：<input type="text" name="transport[volume]" size="6" maxlength="6" value="" /> m&sup3;
      </td>
    </tr>
    <tr>
      <td width="10%">商品信息</td>
      <td>
      商品数量：<input type="text" name="transport[goods_number]" size="4" maxlength="4" value="" class="required number" msg="请填写商品数量" />
      订单金额：<input type="text" name="transport[amount]" size="6" maxlength="6" value="" id ="amount" class="required number" msg="请填写商品总价" /> 元
      </td>
    </tr>
	<tr>
	<td width="10%">物流派单</td>
	<td><span id="showList"></span> <input type="hidden" name="logistic_code" id="logistic_code" value=""><input type="button" value="匹配物流公司" onclick="getLogistic()" /></td>
	</tr>
    <tr>
    <td width="10%">运单号</td>
	<td><input type="text" name="transport[logistic_no]" id="logistic_no" size="40" maxlength="50" value="" class="required" msg="请填写运单号" /></td>
	</tr>
    <tr>
      <td width="10%">备注</td>
      <td><textarea name="transport[remark]" style="width: 400px;height: 50px"></textarea></td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="button" name="dosubmit1" id="dosubmit1" value="提交" onclick="dosubmit()"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function dosubmit()
{
	if(!$('logistic')){alert('请匹配物流公司');return false;}
	if($('logistic_no').value==''){alert('请填写运单号');return false;}
    var no = $('logistic_no').value.trim();
    var r = JSON.decode($('logistic').value);
	var code = r.logistic_code;
	var len = no.length;
	var cod = $('is_cod').value;
	var result = false;
	if (code=='zjs'){
	     if (len==10){
	         if ((cod==0 && no.substr(0, 1) != '6') || (cod==1 && no.substr(0, 1) == '6')) {result = true;}
	     }
	}else if (code=='sf'){
	     if (len==12){
	         if (no.substr(0, 3) == '513') {result = true;}
	     }
	}else if (code=='st'){
	     if (len==12){
	         if (no.substr(0, 3) != '513') {result = true;}
	     }
	     if (no == '自提' || no == 'zt') {result = true;}
	}else if (code=='ems'){
	     if (len==13){
	         if ((cod==0 && no.substr(0, 2) != 'EC' && no.substr(0, 4) != '316A') || (cod==1 && no.substr(0, 2) == 'EC')) {result = true;}
	     }
	}else if (code=='jldt'){
	     if (len==13){
	         if (no.substr(0, 4) == '316A') {result = true;}
	     }
	}else if (code=='zt'){
	 	  result = true;

	}else if (code=='yt'){
	     result = true;
	}
	 result = true;
	if (result == false){
		alert('运单号码校验错误,请检查使用的快递面单是否正确!');
		$('logistic_no').value='';
		$('logistic_no').focus();
		return false;
	}
	if(confirm('确认提交申请吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = 'disabled';
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed()
{
	$('dosubmit1').value = '提交';
	$('dosubmit1').disabled = false;
}

function getLogistic()
{
    var area_id = $('area_id').value;
    var address = $('address').value;
    var amount = $('amount').value;
    var weight = $('weight').value;
    var is_cod = $('is_cod').value;
    new Request({
        url: '{{url param.action=get-logistic}}/is_cod/' + is_cod,
        method: 'post',
        data: 'area_id='+area_id+'&address='+address+'&amount='+amount+'&weight='+weight,
        onRequest: loading,
        onSuccess:function(data){
	        if (data != '') {
	            $('showList').innerHTML = data;
	            
	        }
            loadSucess();
        }
    }).send();
}

function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    new Request({
        url: '/admin/member/area/id/' + value,
        onRequest: loading,
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
	        }
            loadSucess();
        }
    }).send();
}
</script>