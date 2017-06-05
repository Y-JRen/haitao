{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/member/coupon">
<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="user_name" value="{{$param.user_name}}" size="15" />
<select name="card_type" onchange="searchForm.submit()">
<option value="">按礼券类型查询</option>
<option value="0" {{if $param.card_type eq '0'}}selected{{/if}}>常规卡</option>
<option value="1" {{if $param.card_type eq '1'}}selected{{/if}}>非常规卡</option>
<option value="2" {{if $param.card_type eq '2'}}selected{{/if}}>绑定商品卡</option>
<option value="3" {{if $param.card_type eq '3'}}selected{{/if}}>商品抵扣卡</option>
<option value="4" {{if $param.card_type eq '4'}}selected{{/if}}>订单金额抵扣卡</option>
</select>
&nbsp;&nbsp;
<select name="status" onchange="searchForm.submit()">
<option value="">按使用状态</option>
<option value="1" {{if $param.status eq '1'}}selected{{/if}}>可使用</option>
<option value="2" {{if $param.status eq '2'}}selected{{/if}}>已使用/无效</option>
<option value="3" {{if $param.status eq '3'}}selected{{/if}}>已经过期</option>
</select>
<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.action=coupon param.do=search}}','ajax_search')"/>
</form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title">会员礼金券信息查询 </div>
<div class="content">
     <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
			<td>用户名</td>
			<td>优惠券类型</td>
			<td>卡号</td>
            <td>金额</td>
            <td>使用状态</td>
			<td>有效期至</td>
           
        </tr>
        </thead>
        <tbody>
        {{foreach from=$coupon_list item=list name=list}}
        <tr id="ajax_list{{$member.user_id}}">
            <td>{{$list.user_id}}</td>
			<td>{{$list.user_name}}</td>
			<td>
			{{if $list.card_type == 0}}常规卡
			{{elseif $list.card_type == 1}}非常规卡
			{{elseif $list.card_type == 2}}绑定商品卡
			{{elseif $list.card_type == 3}}商品抵扣卡
			{{elseif $list.card_type == 4}}订单金额抵扣卡
			{{/if}}
			</td>
			<td><a href="/admin/coupon/view-log/id/{{$list.log_id}}">{{$list.card_sn}}</a></td>
			<td>{{$list.card_price}}</td>
            <td>  
              {{if $list.status eq 1}}已使用/无效
              {{elseif $curtime > $list.end_date}}<font color="#FF6600"> 已经过期 </font>
              {{else}}<span class="highlight">可使用</span>
              {{/if}}
            </td>
            <td>{{$list.end_date}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>

{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
</div>
<script>
function multiDelete()
{
    checked = multiCheck($('table'),'ids',$('doDelete'));
    if (checked != '') {
        reallydelete('{{url param.action=delete}}', checked);
    }
}
</script>
{{/if}}
