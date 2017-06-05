{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form id="searchForm">
    添加日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
    - <input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
    &nbsp;&nbsp;
    审核日期：<input type="text" name="check_fromdate" id="check_fromdate" size="15" value="{{$param.check_fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
    - <input  type="text" name="check_todate" id="check_todate" size="15" value="{{$param.check_todate}}"  class="Wdate"  onClick="WdatePicker()"/>
<div style="clear:both; padding-top:5px">	
        店铺：
        <select name="shop_id" id="shop_id">
        　<option value="">请选择...</option>
          {{foreach from=$shopDatas item=shop}}
             <option value="{{$shop.shop_id}}" {{if $param.shop_id eq $shop.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
          {{/foreach}}
        </select>
     
     订单编号：<input type="text" name="item_no" size="20" maxLength="50" value="{{$param.item_no}}">
     退款方式:
    <select name="bank_type">
    <option value="">请选择...</option>
    <option value="1" {{if $param.bank_type eq '1'}}selected{{/if}}>银行转账</option>
    <option value="2" {{if $param.bank_type eq '2'}}selected{{/if}}>邮政汇款</option>
    <option value="3" {{if $param.bank_type eq '3'}}selected{{/if}}>帐户余额</option>
    <option value="5" {{if $param.bank_type eq '5'}}selected{{/if}}>支付宝</option>
    <option value="6" {{if $param.bank_type eq '6'}}selected{{/if}}>虚拟</option>
    <option value="4" {{if $param.bank_type eq '4'}}selected{{/if}}>其他</option>
    </select>
     单据状态:
    <select name="status">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status eq '0'}}selected{{/if}}>待收货</option>
    <option value="1" {{if $param.status eq '1'}}selected{{/if}}>未付款</option>
    <option value="2" {{if $param.status eq '2'}}selected{{/if}}>已付款</option>
    <option value="3" {{if $param.status eq '3'}}selected{{/if}}>无效[财务设置]</option>
    <option value="4" {{if $param.status eq '4'}}selected{{/if}}>无效[系统设置]</option>
    </select>
    订单状态:
    <select name="order_status">
    <option value="">请选择...</option>
    <option value="0" {{if $param.order_status eq '0'}}selected{{/if}}>有郊单</option>
    <option value="1" {{if $param.order_status eq '1'}}selected{{/if}}>取消单</option>
    <option value="2" {{if $param.order_status eq '2'}}selected{{/if}}>无效单</option>
    </select>
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
    </div>	
    </form>
</div>
<div id="ajax_search">
{{/if}}

<div class="title">应退款列表 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>] </div>
<div style="float:right;top:10px"><br><b>退款金额:{{$total.pay}}&nbsp;&nbsp;&nbsp;积分金额:{{$total.point}}&nbsp;&nbsp;&nbsp;账户余额金额:{{$total.account}}&nbsp;&nbsp;&nbsp;礼品卡金额:{{$total.gift}}</b></div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>店铺</td>
				<td>订单号</td>
                <td>退款类型</td>
				<td>退款金额</td>
				<td>退款方式</td>
				<!--<td>退运费</td>-->
				<td>积分金额</td>
				<td>账户余额金额</td>
				<td>礼品卡金额</td>
				<td>添加日期</td>
				<td>审核日期</td>
				<td>操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.finance_id}}">
		    <td>{{$item.shop_name}}</td>
			<td>
			  <!--
			  <span style="cursor:pointer" onclick="openDiv('{{url param.action=info param.batch_sn=$item.item_no param.finance_id=$item.finance_id}}','ajax','查看订单',750,400)"><span>
			  -->
			  {{$item.item_no}}{{if $item.external_order_sn}}<br>{{$item.external_order_sn}}{{/if}}
			</td>
            <td>{{if $item.way eq 4}}代收货款变更{{elseif $item.way eq 5}}直供结算金额变更{{elseif $item.way eq 6}}物流公司变更{{else}}{{if $item.item eq '1'}}退货退款{{else}}优惠补偿{{/if}}{{/if}}</td>
			<td>￥{{$item.pay|replace:"-":""}}<br><font color="red"><b>{{if $item.type eq 1}}自营{{elseif $item.type eq 0}}系统退款{{else}}{{if $item.way eq 1}}中间平台{{else}}我方账户{{/if}}{{/if}}</font></b></td>
			<td>
			{{if $item.bank_type == 1}}
			银行转账
			{{elseif $item.bank_type == 2}}
			邮局汇款
			{{elseif $item.bank_type == 3}}
			帐户余额
			{{elseif $item.bank_type == 4}}
			其他
			{{elseif $item.bank_type == 5}}
			支付宝
			{{elseif $item.bank_type == 6}}
			虚拟
			{{/if}}			</td>
			<!--<td>{{$item.logistic|replace:"-":""}}</td>-->
			<td>{{$item.point|replace:"-":""}}</td>
			<td>{{$item.account|replace:"-":""}}</td>
			<td>{{$item.gift|replace:"-":""}}</td>
			<td>{{$item.add_time}}</td>
			<td>{{$item.check_time}}</td>
			<td>
			<form id="myform1">
			<input type="hidden" name="finance_id" value="{{$item.finance_id}}" />
            {{if $item.item eq '1' }}
			<input type="button" value="查看订单" onclick="openDiv('{{url param.action=order param.batch_sn=$item.item_no}}','ajax','查看订单',750,400)" />
            {{/if}}
            
			{{if $item.bank_type==1 || $item.bank_type==2}}
			<input type="button" value="查看帐户" onclick="openDiv('/admin/finance/bank/finance_id/{{$item.finance_id}}','ajax','查看帐户',750,400)" />
			{{/if}}
			<input type="button" value="查看备注" onclick="openDiv('/admin/finance/note/finance_id/{{$item.finance_id}}','ajax','查看备注',750,400)" />
            
            {{if $item.item eq '1' }}
			<input type="button" value="打印" onclick="window.open('{{url param.action=print param.batch_sn=$item.item_no param.finance_id=$item.finance_id}}');">
            {{/if}}
            
			{{if $item.status==0}}
				<input type="button" value="待收货" disabled="disabled">
			{{elseif $item.status==1}}
				<input type="button" value="付款" onclick="confirmed('付款', $('myform1'), '{{url param.action=pass param.mod=pay param.finance_id=$item.finance_id}}')" />
				<input type="button" value="无效" onclick="confirmed('无效', $('myform1'), '{{url param.action=invalid param.mod=pay param.finance_id=$item.finance_id}}')" />
			{{elseif $item.status==2}}
				<input type="button" value="已付款" disabled="disabled">
			{{elseif $item.status==3}}
				<input type="button" value="已无效[财务设置]" disabled="disabled">
			{{elseif $item.status==4}}
				<input type="button" value="已无效[系统设置]" disabled="disabled">
			{{/if}}
			</form></td>
		  </tr>
		{{/foreach}}
		</table>
	</div>
		<div class="page_nav">{{$pageNav}}</div>
</div>	
<script>
		
	function confirmed(str, obj, url) {
		if (confirm('确认执行 "' + str + '" 操作？')) {
			ajax_submit(obj, url);
		}
	}
</script>
