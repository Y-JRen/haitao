//商品-1
function selNumLess(num){
	var old_number=$('#buy_number_'+num).val();
	number = Number(old_number) - 1;
	if(number < 1) {return;}
	if(changeNumber(num,number,old_number)){
		$('#buy_number_'+num).val(number);
	}
}

//商品+1
function selNumAdd(num, max_num){
	var old_number=$('#buy_number_'+num).val();
	number = Number(old_number) + 1;
	if(number>max_num){alert('请输入1-'+max_num+'间的整数');return;}
	if(changeNumber(num,number,old_number)){
		$('#buy_number_'+num).val(number);
	}
}
//更改购物车商品数量
function changeNumber(num,number,old_num){
	if(number>0){
		if(number == old_num) {
			return false;
		}
		$.ajax({
			url:'/goods/check',
			data:{id:num,number:number,check_cart:1},
			type:'get',
			success:function(msg){
				if (msg != 'ok'){
					alert(msg);
					window.location.replace('/flow/index');
					return false;
				}else{
					changeCartNumber(num,number);
				}
			}
		})
		return;
	}else{
		alert('购买数量限制 1-20 件');
		$('#buy_number_'+num).val(old_num);
	}
}
//
function changeCartNumber(product_id,number){
	$.ajax({
		url:'/flow/change',
		data:{product_id:product_id,number:number},
		type:'get',
		success:function(msg){
		    if (msg) {
		        alert(msg);
		        return false;
		    }
			window.location.replace('/flow/index');
		},
		error:function(){
			alert('error');
			window.location.replace('/flow/index');
			return false;
		}
	})
}

function confirmMsg()
{
	return confirm('您确定要删除此商品?');
}


function getCity(id)
{
	var value=id.value;
	$(id).next().empty();
	$(id).next().append('<option value="">请选择城市</option>');
	$(id).next().next().empty();
	$(id).next().next().append('<option value="">请选择区域</option>');
	$.ajax({
		url:'/flow/area',
		data:{id:value},
		dataType:'json',
		success:function(msg){
			var htmloption='';
			$.each(msg,function(key,val){
				htmloption+='<option value="'+key+'" code="'+val.code+'">'+val.area_name+'</option>';
			})
			$(id).next().append(htmloption);
		}
	})
}

function editAddress()
{
	var pattern=/^1[30|31|32|33|34|35|36|37|38|39|50|51|52|57|58|59|88|87|82|83|55|56|86|85|45|53|89]\d{9}$/;
	var pattern1=/^\d{7,8}$/;
	
	var address_id = $("#ad_id").val() ? $("#ad_id").val() : 0;
	var consignee = $('#consignee').val();
	var province_id = $('#province_id').val();
	var city_id = $('#city_id').val();
	var area_id = $('#area_id').val();
	var address = $('#address').val();
	var mobile = $('#mobile').val();
	var tel = $('#tel').val();
	var area_code = $('#area_code').val();
	var tel_branch = $('#tel_branch').val();
	var zip = $('#postalcode').val();
	var eng_address = $('#eng_address').val();
	if(consignee.length < 2){
		alert('请填写收货人!');
		return false;
	}else if(province_id == 0){
		alert('请选择省份!');
		return false;
	}else if(city_id == 0){
		alert('请选择城市!');
		return false;
	}else if(area_id == 0){
		alert('请选择区域!');
		return false;
	}else if(address.length < 3){
		alert('请填写收货人地址!');
		return false;
	}else if(mobile && !pattern.test(mobile)){
		alert('手机号码错误!');
		return false;
	}else if(tel && !pattern1.test(tel))
	{
		alert('请填写正确的电话号码!');
		return false;
	}else if(tel.length < 1 && mobile.length < 1)
	{
		alert('手机和电话必须任意填写一项!');
		return false;
	}else if(zip.length<2){
		alert('请填写邮政编码');
		return false;
	}
	else{
		var data = 'address_id='+encodeURI(address_id)+'&consignee='+encodeURI(consignee)+'&province_id='+encodeURI(province_id)+'&city_id='+encodeURI(city_id)+'&address='+encodeURI(address);
		    data = data + '&area_id='+encodeURI(area_id)+'&mobile='+encodeURI(mobile)+'&phone_no='+encodeURI(tel)+'&phone_qh='+encodeURI(area_code)+'&phone_fj='+encodeURI(tel_branch)+'&zip='+encodeURI(zip)+'&eng_address='+encodeURI(eng_address);
		$.ajax({
			type : "post",
			url:'/flow/edit-area',
			data:data,// 你的formid
			dataType:'text',
			success:function(msg){
				if('success' == msg){
					location.href='/flow/fillin';
				}else{
					alert(msg);
				}
			}
		});
	}
	
}
function getAreaCode()
{
	var v = $('#area_id').val();
	var cd = $('#area_id option[value="'+v+'"]').attr('code');
	$('#area_code').val(cd);
}


