{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
<span style="float:left;line-height:18px;">下单开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">下单结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
&nbsp;结款类型：<select name="type" id="type" style="width:80px">
             <option value="1" {{if $param.type eq '1'}}selected{{/if}}>支付网关</option>
             <option value="2" {{if $param.type eq '2'}}selected{{/if}}>代收货款</option>
             <option value="3" {{if $param.type eq '3'}}selected{{/if}}>渠道店铺</option>
		</select>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">结款统计</div>
<div class="content">
    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　{{if $param.type eq 1}}* 条件 = 官网B2C/ 前台下单/非会员下单 已付款/未退款
	      {{elseif $param.type eq 2}}* 条件 = 内部下单/官网B2C 前台下单/电话下单/非会员下单 已发货/已签收 货到付款
	      {{elseif $param.type eq 3}}* 条件 = 渠道店铺 有效单/渠道刷单 已发货/已签收/拒收 
	      {{/if}}
	    </font>
	    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>
            {{if $param.type eq 1}}
            支付网关
            {{elseif $param.type eq 2}}
            物流公司
            {{elseif $param.type eq 3}}
            店铺
            {{/if}}
            </td>
            <td>总单数</td>
            <td>已结款单数</td>
            <td>未结款单数</td>
            <td>总金额</td>
            <td>已结款金额</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.name}}</td>
        <td>{{if $data.count}}{{$data.count}}{{else}}0{{/if}}</td>
        <td>{{if $data.count1}}{{$data.count1}}{{else}}0{{/if}}</td>
        <td>{{if $data.count2}}{{$data.count2}}{{else}}0{{/if}}</td>
        <td>{{if $data.amount}}{{$data.amount}}{{else}}0{{/if}}</td>
        <td>{{if $data.done_amount}}{{$data.done_amount}}{{else}}0{{/if}}</td>
    </tr>
    {{/foreach}}
    <tr>
        <td>合计</td>
        <td>{{$total.count}}</td>
        <td>{{$total.count1}}</td>
        <td>{{$total.count1}}</td>
        <td>{{$total.amount}}</td>
        <td>{{$total.done_amount}}</td>
    </tr>
    </tbody>
    </table>
</div>
