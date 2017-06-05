<div class="title">查看礼券</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=log }}')">礼券发放记录</a> ]
</div>
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
{{elseif $data['card_type'] eq 5}}
组合商品抵扣卡
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
<td id="type012_3">绑定联盟ID</td>
<td id="type012_4">{{$data.parent_id}}&nbsp;&nbsp;是否按照券分成：{{if $data.is_affiliate eq 1}}是{{else}}否{{/if}}</td>
</tr>
<tr>
<td id="type12_3">购买指定商品</td>
<td id="type12_4">
{{if $goods_info}}
{{foreach from=$goods_info key=goods_id item=number}} 
  <div>
    商品名称：{{$goods_name[$goods_id]}}
  </div>
{{/foreach}}
{{/if}}
{{if $groupgoods_info}}
{{foreach from=$groupgoods_info key=group_id item=number}} 
  <div>
    组合商品名称：{{$groupgoods_name[$group_id]}}
  </div>
{{/foreach}}
{{/if}}
</td>
<td id="type3_3" style="display:none">绑定商品</td>
<td id="type3_4" style="display:none">
<div id="numArea">
  {{foreach from=$goods_info key=goods_sn item=number}} 
  <div>
    {{if $data.card_type eq 3}}SN{{elseif $data.card_type eq 5}}ID{{/if}}：{{$goods_sn}}
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
</form>
<script>
function showBlock() {
    var value = '{{$data.card_type}}';
    if (value == '3' || value == '5') {
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
        
        $('type12_3').style.display='none';
        $('type12_4').style.display='none';
        
        $('type3_2').float = 'right';
    }
    else {
        if ((value == '0') || (value == '1') || (value == '4')) {
            $('type12_3').style.display='';
            $('type12_4').style.display='';
        }
        else {
            $('type12_3').style.display='none';
            $('type12_4').style.display='none';
        }
        
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

showBlock();
</script>