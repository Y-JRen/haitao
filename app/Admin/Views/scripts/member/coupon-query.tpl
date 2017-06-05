{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/member/coupon-query">
<span style="margin-left:5px;">卡号: </span><input type="text" name="card_sn" value="{{$param.card_sn}}" size="15" />
<span style="margin-left:5px;">密码: </span><input type="text" name="card_password" value="{{$param.card_password}}" size="15" />
<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=coupon-query param.do=search}}','ajax_search')"/>
</form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title">优惠券信息查询 </div>
{{if $error}}
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td align="center">
<font color="red" size="3">
{{if $error eq 'no_card'}}卡号或密码错误！
{{elseif $error eq 'no_card_log'}}找不到开卡信息！
{{elseif $error eq 'no_card_sn'}}卡号必须输入！
{{/if}}
</font>
</td>
</tr>
</table>
</div>
{{/if}}
{{if $data}}
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">礼券类型 * </td>
<td width="40%">
{{if $data['card_type'] eq 0}}
常规卡
{{elseif $data['card_type'] eq 1}}
非常规卡
{{elseif $data['card_type'] eq 2}}
绑定商品卡
{{elseif $data['card_type'] eq 3}}
商品抵扣卡
{{elseif $data['card_type'] eq 4}}
订单金额折扣卡
{{/if}}
</td>
<td width="10%">是否可重复使用 * </td>
<td width="40%">
{{if $data['is_repeat'] eq 0}}
否
{{else}}
是
{{/if}}
</td>
</tr>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%" id="type012_1">礼券价格 * </td>
<td width="10%" id="type3_1" style="display:none">抵扣方式 * </td>
<td width="10%" id="type4_1" style="display:none">订单折扣 * </td>
<td id="type012_2">{{$data.card_price}}</td>
<td id="type3_2" style="display:none;">
  <span style="float:left">
  {{if $data.card_price eq '0.00'}}
  商品全额
  {{else}}
  商品金额抵扣　价格：{{$data.card_price}}
  {{/if}}
  运费减免：{{$data.freight}}
  </span>
</td>
<td width="10%">生成数量 * </td>
<td width="40%">{{$data.number}}</td>
</tr>
<tr>
<td>截止日期 * </td>
<td><span style="float:left;width:150px;">{{$data.end_date}}</span></td>
<td id="type012_3">绑定ID</td>
<td id="type3_3" style="display:none">绑定商品</td>
<td id="type012_4">{{$data.parent_id}}&nbsp;&nbsp;是否按照券分成：{{if $data.is_affiliate eq 1}}是{{else}}否{{/if}}</td>
<td id="type3_4" style="display:none">
<div id="numArea">
  {{foreach from=$goods_info key=goods_sn item=number}} 
  <div>
    SN：{{$goods_sn}}
    名称：{{$goods_name[$goods_sn]}}
    数量：{{$number}}
  </div>
  {{/foreach}}
</div>
</td>
</tr>
<tr id="amount_tr" style="display:none">
<td>最低订单价格</td>
<td colspan="3">{{$data.min_amount}}</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">备注</td>
<td colspan="3">{{$data.note}}</td>
</tr>
</tbody>
</table>  
</div>
{{/if}}

{{if $couponMember}}
<div class="title">会员信息 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">会员名称</td>
<td width="40%">{{$couponMember.user_name}}</td>
<td width="10%">会员ID</td>
<td>{{$couponMember.user_id}}</td>
</tr>
<tr>
<td>绑定时间</td>
<td>{{$couponMember.add_time}}</td>
<td>状态</td>
<td>{{if $couponMember.status}}已使用{{else}}未使用     <a href="javascript:fGo()" onclick="setCoupon('{{$couponMember.card_sn}}')">设置为无效</a>    {{/if}}</td>
</table>
</div>
{{/if}}

{{if $orderInfo}}
<div class="title">订单信息 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">订单号</td>
<td width="40%"><a href="/admin/order/info/batch_sn/{{$orderInfo.batch_sn}}">{{$orderInfo.batch_sn}}</a></td>
<td width="10%">订单金额</td>
<td>{{$orderInfo.price_pay}}</td>
</tr>
<tr>
<td>订单状态</td>
<td>{{if $orderInfo.status eq 0}}正常单{{elseif $orderInfo.status eq 1}}取消单{{elseif $orderInfo.status eq 2}}无效单{{/if}}</td>
<td>联系方式</td>
<td>{{$orderInfo.addr_consignee}} {{$orderInfo.addr_mobile}}</td>
</table>
</div>
{{/if}}

{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
</div>
{{/if}}

<script>
function showBlock() {
    var value = '{{$data.card_type}}';
    if (value == '3') {
        $('type3_1').style.display='';
        $('type3_2').style.display='';
        $('type012_1').style.display='none';
        $('type4_1').style.display='none';
        $('type012_2').style.display='none';
        $('type012_3').style.display='none';
        $('type012_4').style.display='none';
        
        $('type3_3').style.display='';
        $('type3_4').style.display='';
        $('amount_tr').style.display='';
        $('type3_2').float = 'right';
    }
    else {
        if ((value == '1') || (value == '4')) {
            $('amount_tr').style.display='';
        }
        else {
            $('amount_tr').style.display='none';
        }
        $('type012_1').style.display='';
        $('type012_2').style.display='';
        $('type012_3').style.display='';
        $('type012_4').style.display='';
        $('type3_1').style.display='none';
        $('type3_2').style.display='none';
        $('type3_3').style.display='none';
        $('type3_4').style.display='none';
        
        if (value == '4') {
            $('type012_1').style.display='none';
            $('type4_1').style.display='';
        }
        else {
            $('type012_1').style.display='';
            $('type4_1').style.display='none';
        }
    }
}
function showDeductionPrice()
{
    if ($('deductionType').value == 2) {
        $('deduction_price_area').style.display = 'block';
    }
    else {
        $('deduction_price_area').style.display = 'none';
        $('card_price').value = '0';
    }
}

{{if $data}}showBlock();{{/if}}
</script>


<script type="text/javascript">
function setCoupon(card_sn){
	if(confirm("确认要对该券号做已经使用操作吗？")){
		new Request({
			url:'/admin/member/set-coupon/card_sn/'+card_sn,
			method:'get',
			onSuccess:function(data){
				if(data=='ok'){
					alert("操作成功");
				}else{
					alert(data+"操作失败，请稍后重试");
				}
			},
			onFailure:function(){
				alert("网路繁忙，请稍后重试");
			}
		}).send();
	}
}
</script>