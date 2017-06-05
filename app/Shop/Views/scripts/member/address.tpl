<div class="memberCenter">
    {{include file="member/menu.tpl"}}
        <div class="mcContentRight">
            <div class="adrTitle">
                <p>您已填写<span>{{$addressNum}}</span>个收货地址，还可以添加<span>{{$nextNum}}</span>个</p>
                {{if ($nextNum > 0)}}
                <div><img src="{{$imgBaseUr}}/public/images/addAdr_btn.png" onclick="addAddress()"/></div>
                {{/if}}
                <br class="clearfix"/>
            </div>
            {{if $memberAddress}}
            {{foreach from=$memberAddress item=address name=address}}
            <div class="adrList">
                <div class="adrList_header">
                    <p>{{$address.consignee}}</p>
                    <ul>
                        <li class="morenIcon">
                            <div class="icon {{if $address.is_default eq 1}}current{{/if}}" aid="{{$address.address_id}}"></div>
                            <p>设为默认收货地址</p>
                            <br class="clearfix"/>
                        </li>
                        <li class="adrHeaderBtn" onclick="editAddress('{{$address.address_id}}')"><img src="{{$imgBaseUr}}/public/images/bianjie_btn.png"/></li>
                        {{if ($addressNum > 1)}}
                        <li class="adrHeaderBtn" onclick="alertMsg('#pop_sure','{{$address.address_id}}','delete')"><img src="{{$imgBaseUr}}/public/images/adrDel_btn.png"/></li>
                        {{/if}}
                        <br class="clearfix"/>
                    </ul>
                    <br class="clearfix"/>
                </div>
                <ul class="adrList_content">
                    <li>
                        <div class="adrName">收货人：</div>
                        <div class="adrMain">{{$address.consignee}}</div>
                        <br class="clearfix"/>
                    </li>
                    <li>
                        <div class="adrName">所在地区：</div>
                        <div class="adrMain">{{$address.province_msg.area_name}}&nbsp;{{$address.city_msg.area_name}}&nbsp;{{$address.area_msg.area_name}}</div>
                        <br class="clearfix"/>
                    </li>
                    <li>
                        <div class="adrName">详细地址：</div>
                        <div class="adrMain">{{$address.address}}</div>
                        <br class="clearfix"/>
                    </li>
                    <li>
                        <div class="adrName">英文地址：</div>
                        <div class="adrMain">{{$address.eng_address}}</div>
                        <br class="clearfix"/>
                    </li>
                    <li>
                        <div class="adrName">电话号码：</div>
                        <div class="adrMain">{{$address.phone}}</div>
                        <br class="clearfix"/>
                    </li>
                    <li>
                        <div class="adrName">手机号码：</div>
                        <div class="adrMain">{{$address.mobile}}</div>
                        <br class="clearfix"/>
                    </li>
                </ul>
            </div>
            {{/foreach}}
            {{/if}}
           
        </div>
        <br class="clearfix"/>
        <!-- 修改地址弹窗 -->
        <div class="pop_revise_adr" id="pop_revise_adr">
            <form action="/member/address" id="yourformid">
            
            </form>
        </div>
        <div class="pop_sure" id="pop_sure" aid="">
            <div class="pop_close_btn" onclick="closeMsg('#pop_sure')"></div>
            <div class="pop_sure_btn" onclick="deleteAddress()"></div>
            <div class="pop_no_btn" onclick="closeMsg('#pop_sure')"></div>
        </div>
    </div>
</div>
<div id="onload_cover"></div>
<div id="nothing" style='display:none'>
<div class="pop_close_btn" onclick="closeMsg('#pop_revise_adr')"></div>
            <div class="pop_name pop_name01"><span>* </span>收货人姓名：</div>
            <div class="pop_input01"><input type="text" name="consignee" id="consignee"/></div>
            <div class="pop_name pop_name02"><span>* </span>配送区域：</div>
            <div class="pop_select">
                <select name="province_id" id="province_id" onchange="getCity(this)">
                      <option value="">请选择省</option>
                      {{html_options options=$province}}
                </select>
                <select name="city_id" id="city_id" onchange="getArea(this)">
                    <option value="">请选择市</option>           
                </select>
                <select name="area_id" id="area_id" onchange="getAreaCode()">
                    <option value="">请选择区</option>
                </select>            
            </div>
            <div class="pop_name pop_name03"><span>* </span>详细地址：</div>
            <div class="pop_input02"><input type="text" name="address" id="address" size="30" maxlength="100"/></div>
            <div class="pop_name pop_name_last"><span> </span>英文地址：</div>
            <div class="pop_input_last"><input type="text" name="eng_address" id="eng_address" size="30" maxlength="100"/></div>
            <div class="pop_name pop_nameCode"><span>*</span>邮政编码：</div>
        	<div class="pop_inputCode"><input type="text" name="zip" id="postalcode" ></div>
            <div class="pop_name pop_name04"><span>* </span>联系电话：</div>
            <div class="pop_input03">手机或者固话任填一项</div>
            <div class="pop_name pop_name05"><span>* </span>手机：</div>
            <div class="pop_input01 pop_input04"><input type="text" name="mobile" id="mobile" size="25" maxlength="20" value=""/></div>
            <div class="pop_name pop_name06">固话：</div>
            <div class="pop_input05"><input type="text" name="phone_qh" id="phone_qh"/></div>
            <div class="pop_input05 pop_input06"><input type="text" name="phone_no" id="phone_no"/></div>
            <div class="pop_input05 pop_input07"><input type="text" name="phone_fj"/></div>
            <input type="hidden" name="address_id" value="" />
            <div class="pop_baocun_btn"><img src="{{$imgBaseUr}}/public/images/pop_baocun_btn.png" onclick="addressSubmit()"/></div>
</div>
<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>

<script type="text/javascript">
function isMobile(phone)
{
	var pattern=/^1[30|31|32|33|34|35|36|37|38|39|50|51|52|57|58|59|88|87|82|83|55|56|86|85|45|53|89]\d{9}$/;
	return pattern.test(phone)? true :  false;
}
//验证表单
function addressSubmit()
{
   var consignee = $('#consignee').val();
   var province = $('#province_id').val();
   var city = $('#city_id').val();
   var area = $('#area_id').val();
   var address = $('#address').val();
   var mb = $('#mobile').val();
   var tel = $('#phone_no').val();
	if(consignee.length < 1){
		alert('请填写收货人!');
		return false;
	}
	else if(province == '0'){
		alert('请选择省份!');
		return false;
	}else if(city == '0'){
		alert('请选择城市!');
		return false;
	}else if(area == '0'){
		alert('请选择区域!');
		return false;
	}else if(address.length<2){
		alert('请填写详细收货地址!');
		return false;
	} else if(mb.length > 0 && !isMobile(mb)){
    	alert('请填写正确的手机号码!');
    	return false;
    } else if(tel.length < 6 && mb.length < 1){
		alert('联系电话必须填一项!');
		return false;
    }else{
	    //数据提交
	    $.ajax({
			type : "POST",
			cache : false,
			url : '/member/address',
			data:$('#yourformid').serialize(),// 你的formid
            async: false,
			success : function(msg) {
				if('success' == msg){
					location.href='/member/address';
				}else{
					alert(msg);
				}
			}
		});
    } 
}

// 6.18
$(function(){
    $('.icon').each(function(i){
        $(this).click(function(){
            $('.icon').removeClass('current');
            $(this).addClass('current');;
            $.ajax({
            	type : 'GET',
        		url:'/member/address',
        		data:'type=default&aid='+$(this).attr('aid'),
        		dataType:'html',
        		success:function(msg){
        			//if('ok' == msg){location.href='/member/address';}
        		}
        	})
            
        })
    })
})


function addAddress()
{
	var data = $("#nothing").html();
	$('#yourformid').html(data);
	alertMsg('#pop_revise_adr');
	
}


function alertMsg(obj,aid,tp){
    var obj=$(obj);  
    var ch=parseInt(document.documentElement.scrollHeight);
    var topOpt=$(window).scrollTop()+200;
    if(aid && 'delete' == tp)
    {
		$('#pop_sure').attr('aid', aid);
    }
    if(aid && 'delete' != tp){
    	editAddress(aid);
    }
    $('#onload_cover').css('height', ch).show(); 
    obj.css('top',topOpt).fadeIn();
}

function editAddress(aid)
{
	$.ajax({
		url:'/member/get-address',
		data:'aid='+aid,
		dataType:'html',
		success:function(msg){
			$('#yourformid').html(msg);
			alertMsg('#pop_revise_adr');
		}
	})
}

function deleteAddress()
{
	var aid = $('#pop_sure').attr('aid');
	if(aid){
		$.ajax({
        	type : 'GET',
    		url:'/member/address',
    		data:'type=delete&aid='+aid,
    		dataType:'html',
    		success:function(msg){
    			if('ok' == msg){location.href='/member/address';}
    		}
    	})
	}
}


function closeMsg(obj){
    var obj=$(obj);
    obj.fadeOut();
    $('#onload_cover').hide(); 
}
function getCity(id)
{
	var value=id.value;
	$(id).next().empty();
	$(id).next().append('<option value="">请选择市</option>');
	$(id).next().next().empty();
	$(id).next().next().append('<option value="">请选择区</option>');
	$.ajax({
		url:'{{url param.action=area}}',
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


//联动
function getArea(id){
	
	var value=id.value;
	$(id).parent().children('select:last')[0].options.length = 1;
	$(id).next('select')[0].options.length=1;
	$.ajax({
		url:'/member/area',
		data:{id:value},
		dataType:'json',
		success:function(msg){
			var htmloption='';
			$.each(msg,function(key,val){
				htmloption+='<option value="'+key+'" code="'+val.code+'">'+val.area_name+'</option>';
			})
			$(id).next('select').append(htmloption);
		}
	})
}

function getAreaCode()
{
	var v = $('#area_id').val();
	var cd = $('#area_id option[value="'+v+'"]').attr('code');
	
	$('#phone_qh').val(cd);
}
</script>