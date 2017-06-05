<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form id="searchForm" method="get">
     <div>
        <span style="float:left;">下单开始日期：</span><span style="float:left;width:150px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left;margin-left:10px">下单结束日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
        <span style="float:left;margin-left:10px">发货开始日期：</span><span style="float:left;"><input type="text" name="logisticfromdate" id="logisticfromdate" size="11" value="{{$param.logisticfromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left;margin-left:10px">发货结束日期：</span>
        <span style="float:left;"><input type="text" name="logistictodate" id="logistictodate" size="11" value="{{$param.logistictodate}}"  class="Wdate" onClick="WdatePicker()"/>
        <input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value='' ;$('logisticfromdate').value='' ;$('logistictodate').value=''"/>
        </span>
    </div>

<div style="clear:both; padding-top:5px">
    商品编号：<input type="text" name="product_sn" size="6" maxLength="15" value="{{$param.product_sn}}">
    商品名称：<input type="text" name="goods_name" size="10" maxLength="30" value="{{$param.goods_name}}">
    联盟ID：<input type="text" name="parent_id" size="4" maxLength="15" value="{{$param.parent_id}}" />
    活动ID：<input type="text" name="offers_id" size="4" maxLength="15" value="{{$param.offers_id}}">
	物流公司：
	<select name="logistic_code">
	  <option value="">请选择</option>
	  {{html_options options=$logisticList selected=$param.logistic_code}}
	</select>
    下单类型:
    <select name="entry" id="entry" onchange="changeEntry(this.value)">
      <option value="">请选择...</option>
      <option value="b2c" {{if $param.entry eq 'b2c'}}selected{{/if}}>官网B2C</option>
      <!--<option value="channel" {{if $param.entry eq 'channel'}}selected{{/if}}>渠道运营</option>-->
      <!--<option value="call" {{if $param.entry eq 'call'}}selected{{/if}}>呼叫中心</option>-->
      <!--<option value="distribution" {{if $param.entry eq 'distribution'}}selected{{/if}}>渠道分销</option>-->
      <option value="other" {{if $param.entry eq 'other'}}selected{{/if}}>其它下单</option>
    </select>
    <select name="type" id="type" onchange="changeType(this.value)">
      <option value="">请选择...</option>
	</select>
	<select name="source" id="source">
      <option value="">请选择...</option>
      <option value="0" {{if $param.source eq '0'}}selected{{/if}}>后台下单</option>
      <option value="1" {{if $param.source eq '1'}}selected{{/if}}>会员下单</option>
      <option value="2" {{if $param.source eq '2'}}selected{{/if}}>电话下单</option>
      <option value="3" {{if $param.source eq '3'}}selected{{/if}}>匿名下单</option>
      <option value="4" {{if $param.source eq '4'}}selected{{/if}}>试用下单</option>
	</select>
    <br />
    订单ID：<input type="text" name="order_batch_id" size="6" maxLength="50" value="{{$param.order_batch_id}}">
    订单号：<input type="text" name="batch_sn" size="16" maxLength="50" value="{{$param.batch_sn}}">
    收货人名字：<input type="text" name="addr_consignee" size="8" maxLength="50" value="{{$param.addr_consignee}}">
    收货人电话：<input type="text" name="addr_mobile" size="8" maxLength="50" value="{{$param.addr_mobile}}">
    用户名：<input type="text" name="user_name" id="user_name" size="15" maxLength="50" value="{{$param.user_name}}">
    运单号：<input type="text" name="logistic_no" size="15" maxLength="50" value="{{$param.logistic_no}}">
    订单金额：<input type="text" name="price_order_from" size="4" maxLength="50" value="{{$param.price_order_from}}">
    - <input type="text" name="price_order_to" size="4" maxLength="50" value="{{$param.price_order_to}}">
    <br />
     订单状态:
    <select name="status">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status eq '0'}}selected{{/if}}>有效单</option>
    <option value="1" {{if $param.status eq '1'}}selected{{/if}}>取消单</option>
    <option value="2" {{if $param.status eq '2'}}selected{{/if}}>无效单</option>
    <!--<option value="3" {{if $param.status eq '3'}}selected{{/if}}>渠道刷单</option>-->
    <!--<option value="4" {{if $param.status eq '4'}}selected{{/if}}>分销单</option>-->
    <option value="5" {{if $param.status eq '5'}}selected{{/if}}>预售单</option>
    </select>
    支付方式:
    <select name="pay_type">
      <option value="">请选择...</option>
	  {{foreach from=$payment key=key item=tmp}}
      <option value="{{$key}}" {{if $param.pay_type eq $key}}selected{{/if}}>{{$tmp.name}}</option>
	  {{/foreach}}
	  <option value="cash" {{if $param.pay_type eq 'cash'}}selected{{/if}}>现金支付</option>
	  <option value="bank" {{if $param.pay_type eq 'bank'}}selected{{/if}}>银行打款</option>
	  <!--<option value="external" {{if $param.pay_type eq 'external'}}selected{{/if}}>渠道支付</option>-->
	  <option value="no_pay" {{if $param.pay_type eq 'no_pay'}}selected{{/if}}>无需支付</option>
	</select>
     支付状态:
    <select name="status_pay">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_pay eq '0'}}selected{{/if}}>未收款</option>
    <option value="1" {{if $param.status_pay eq '1'}}selected{{/if}}>未退款</option>
    <option value="2" {{if $param.status_pay eq '2'}}selected{{/if}}>已收款</option>
    <option value="3" {{if $param.status_pay eq '3'}}selected{{/if}}>部分收款</option>
    </select>
    配送状态:
    <select name="status_logistic">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_logistic eq '0'}}selected{{/if}}>未确认</option>
    <option value="1" {{if $param.status_logistic eq '1'}}selected{{/if}}>已确认</option>
    <option value="2" {{if $param.status_logistic eq '2'}}selected{{/if}}>待发货</option>
    <option value="3" {{if $param.status_logistic eq '3'}}selected{{/if}}>已发货未签收</option>
    <option value="4" {{if $param.status_logistic eq '4'}}selected{{/if}}>客户已签收</option>
    <option value="5" {{if $param.status_logistic eq '5'}}selected{{/if}}>客户拒收</option>
    <option value="6" {{if $param.status_logistic eq '6'}}selected{{/if}}>部分签收</option>
    </select>
    退换货状态:
    <select name="status_return">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_return eq '0'}}selected{{/if}}>正常单</option>
    <option value="1" {{if $param.status_return eq '1'}}selected{{/if}}>退货单</option>
    </select>
    赠送人：
    <input type="text" name="giftbywho" value="{{$param.giftbywho}}" size="8"/>
    <br />
	是否开票:
    <select name="invoice_type">
    <option value="">请选择...</option>
    <option value="0" {{if $param.invoice_type eq '0'}}selected{{/if}}>不需要</option>
    <option value="1" {{if $param.invoice_type eq '1'}}selected{{/if}}>需要</option>
    </select>
    店铺：
    <select name="shop_id" id="shop_id">
      <option value="">请选择...</option>
      {{foreach from=$shopDatas item=shop}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
      {{/foreach}}
    </select>
    <input type="checkbox" name="only_show_gift" value="1" {{if $param.only_show_gift}}checked{{/if}}/>只显示礼品卡
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search param.page=1}}','ajax_search')"/>
    <input type="button" name="dosearch" value="所有被我锁定的订单" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search param.is_lock=yes}}','ajax_search')"/>
    <input type="button" name="dosearch" value="所有没有锁定的订单" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search param.is_lock=no}}','ajax_search')"/>
    <input type="button" name="dosearch" value="所有挂起的订单" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search param.hang=1}}','ajax_search')"/>
    </div>	
    </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">订单查询列表 {{if $auth.group_id eq 1 or $auth.group_id eq 11 or $auth.group_id eq 14 or $auth.admin_id eq 66 or $auth.admin_id eq 60}}[<a href="{{url param.todo=export}}" target="_blank">导出订单</a>]{{/if}}</div>
	<div class="content">
<div style="padding:0 5px">
	<div style="float:left;width:500px;">
		<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
		<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="超级锁定" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="超级解锁" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/0','Gurl(\'refresh\')')">
	</div>
	<div style="float:right;"><b>订单总金额：￥{{$totalPriceOrder}}{{if $auth.group_id eq 1 || $auth.group_id eq 11}}&nbsp;&nbsp;&nbsp;财务总金额：￥{{$totalBalanceAmount}}{{if $totalVitualAmount}}&nbsp;&nbsp;&nbsp;礼品卡总金额：￥{{$totalVitualAmount}}{{/if}}{{/if}}</b></div>
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td width=10></td>
				<td width="40">操作</td>
				<td>ID</td>
				<td width="120">订单号</td>
                <td>下单时间</td>
                <td>发货时间</td>
				<td width="350">订单商品</td>
				<td width="60">收货人</td>
				<td>金额</td>
                <td>运费</td>
				<td>支付方式</td>
				<td>锁定状态</td>
                <td>申报状态</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.order_batch_id}}">
			<td valign="top"><input type='checkbox' name="ids[]" value="{{$item.batch_sn}}"></td>
			<td valign="top">
			{{if $item.lock_name==$auth.admin_name}}
			<input type="button" onclick="G('/admin/order/info/batch_sn/{{$item.batch_sn}}')" value="修改">
			{{else}}
			<input type="button" onclick="G('/admin/order/info/batch_sn/{{$item.batch_sn}}')" value="查看">
			{{/if}}</td>
			<td valign="top">{{$item.order_batch_id}}<br />{{$item.shop_name}}</td>
			<td valign="top">
			{{$item.batch_sn}}<br />
			{{$item.status}}  {{$item.status_pay}} {{$item.status_logistic}}  {{$item.status_return}}   <br />
			{{if $item.union_id}}<font color="red">联盟ID:</font>{{$item.union_id}}<br />
            联盟参数{{$item.parent_param|truncate:12:""}}
            {{/if}}
			{{if $item.hang}}<font color="red">已被{{$item.hang_admin_name}}挂起</font><br />{{/if}}
			{{if $item.status_back==1}}<font color="red">已申请取消</font><br />{{/if}}
			{{if $item.status_back==2}}<font color="red">已申请返回</font>{{/if}}
			</td>
             <td valign="top">{{$item.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
              <td valign="top">{{$item.logistic_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td valign="top">
				{{foreach from=$product item=goods}}
					{{if $goods.batch_sn==$item.batch_sn}}
						{{$goods.goods_name}} (<font color="#FF3333">{{$goods.goods_style}}</font>)  
                         <font color="#336633">{{$goods.product_sn}} </font>
						 数量：{{$goods.number}} 
						 <br />
					{{/if}}
				{{/foreach}}
			</td>
			<td valign="top">{{$item.addr_consignee}}</td>
			<td valign="top">
			{{if $item.blance>0}}应收：{{$item.blance}}<br />{{/if}}
			{{if $item.price_payed+$item.account_payed+$item.point_payed+$item.gift_card_payed+$item.price_from_return>0}}已收：{{$item.price_payed+$item.account_payed+$item.point_payed+$item.gift_card_payed+$item.price_from_return}}<br />{{/if}}
			{{if $item.blance<0}}应退：{{$item.blance|replace:"-":""}}<br />{{/if}}
			</td>
            <td valign="top">{{$item.price_logistic}}</td>
			<td valign="top">{{$item.pay_name}}</td>
			<td valign="top">{{if $item.lock_name}}<font color="red">被{{$item.lock_name}}锁定</font>{{else}}未锁定{{/if}}</td>
            <td>
            {{if $item.status_declare eq 0}}未申报
            {{elseif $item.status_declare eq 1}}已申报，未审核
            {{elseif $item.status_declare eq 2}}已审核
            {{elseif $item.status_declare eq 3}}审核失败
            {{else}}未知{{/if}}
            </td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
		<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
		<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="超级锁定" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="超级解锁" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/0','Gurl(\'refresh\')')">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>
<script>
function changeEntry(val)
{
    $('type').options.length = 0;
    $('type').options.add(new Option('请选择...', ''));
    $('source').options.length = 0;
    $('source').options.add(new Option('请选择...', ''));
    $('source').options.add(new Option('后台下单', '0'{{if $param.source eq '0'}}, true, true{{/if}}));
    if (val == 'b2c') {
        $('type').options.add(new Option('官网下单', '0'{{if $param.type eq '0' && $param.user_name ne 'yumi_jiankang' && $param.user_name ne 'xinjing_jiankang'}}, true, true{{/if}}));
        //$('type').options.add(new Option('玉米网下单', '0'{{if $param.type eq '0' && $param.user_name eq 'yumi_jiankang'}}, true, true{{/if}}));
        //$('type').options.add(new Option('信景下单', '0'{{if $param.type eq '0' && $param.user_name eq 'xinjing_jiankang'}}, true, true{{/if}}));
        
        $('source').options.add(new Option('会员下单', '1'{{if $param.source eq '1'}}, true, true{{/if}}));
        $('source').options.add(new Option('电话下单', '2'{{if $param.source eq '2'}}, true, true{{/if}}));
        $('source').options.add(new Option('匿名下单', '3'{{if $param.source eq '3'}}, true, true{{/if}}));
        $('source').options.add(new Option('试用下单', '4'{{if $param.source eq '4'}}, true, true{{/if}}));
        
        $('shop_id').options[1].selected = true;
    }
    else if (val == 'call') {
        $('type').options.add(new Option('呼入下单', '10'{{if $param.type eq '10'}}, true, true{{/if}}));
        $('type').options.add(new Option('呼出下单', '11'{{if $param.type eq '11'}}, true, true{{/if}}));
        $('type').options.add(new Option('咨询下单', '12'{{if $param.type eq '12'}}, true, true{{/if}}));
        
        $('source').options.add(new Option('会员下单', '1'{{if $param.source eq '1'}}, true, true{{/if}}));
        $('source').options.add(new Option('电话下单', '2'{{if $param.source eq '2'}}, true, true{{/if}}));
        $('source').options.add(new Option('匿名下单', '3'{{if $param.source eq '3'}}, true, true{{/if}}));
        
        $('shop_id').options[0].selected = true;
    }
    else if (val == 'channel') {
        $('type').options.add(new Option('渠道下单', '13'{{if $param.type eq '13'}}, true, true{{/if}}));
        $('type').options.add(new Option('渠道补单', '14'{{if $param.type eq '14' && $param.user_name ne 'batch_channel' && $param.user_name ne 'credit_channel'}}, true, true{{/if}}));
        $('type').options.add(new Option('购销下单', '14'{{if $param.type eq '14' && $param.user_name eq 'batch_channel'}}, true, true{{/if}}));
        $('type').options.add(new Option('赊销下单', '14'{{if $param.type eq '14' && $param.user_name eq 'credit_channel'}}, true, true{{/if}}));
        $('type').options.add(new Option('直供下单', '16'{{if $param.type eq '16' && $param.user_name eq 'distribution_channel'}}, true, true{{/if}}));
        $('shop_id').options[0].selected = true;
    }
    else if (val == 'distribution') {
        {{foreach from=$areas item=item key=key}}
          {{if $key > 20}}
          $('type').options.add(new Option('{{$item}}', '18'{{if $param.type eq '18' && $param.user_name eq $distributionArea[$key]}}, true, true{{/if}}));
          {{/if}}
        {{/foreach}}
        $('shop_id').options[0].selected = true;
    }
    else if (val == 'other') {
        $('type').options.add(new Option('赠送下单', '5'{{if $param.type eq '5'}}, true, true{{/if}}));
        $('type').options.add(new Option('其它下单', '15'{{if $param.type eq '15'}}, true, true{{/if}}));
        $('type').options.add(new Option('内购下单', '7'{{if $param.type eq '7'}}, true, true{{/if}}));
        $('shop_id').options[0].selected = true;
    }
    else {
        $('source').options.add(new Option('会员下单', '1'{{if $param.source eq '1'}}, true, true{{/if}}));
        $('source').options.add(new Option('电话下单', '2'{{if $param.source eq '2'}}, true, true{{/if}}));
        $('source').options.add(new Option('匿名下单', '3'{{if $param.source eq '3'}}, true, true{{/if}}));
        $('source').options.add(new Option('试用下单', '4'{{if $param.source eq '4'}}, true, true{{/if}}));
        //$('shop_id').options[0].selected = true;
    }
    
    changeType($('type').value);
}

function changeType(type)
{
    $('user_name').value = '';
    if (type == '14') {
        var text = $('type').options[$('type').selectedIndex].text;
        if (text == '购销下单') {
            $('user_name').value = 'batch_channel';
        }
        else if (text == '赊销下单') {
            $('user_name').value = 'credit_channel';
        }
    }
    else if (type == '16') {
        $('user_name').value = 'distribution_channel';
    }
    else if (type == '0') {
        var text = $('type').options[$('type').selectedIndex].text;
        if (text == '玉米网下单') {
            $('user_name').value = 'yumi_jiankang';
        }
        else  if (text == '信景下单') {
            $('user_name').value = 'xinjing_jiankang';
        }
    }
    else if (type == '18') {
        var text = $('type').options[$('type').selectedIndex].text;
        for (i = 0; 4 < distributionName.length; i++) {
            if (text == distributionName[i]) {
               $('user_name').value = distributionUsername[i];
               break;
            }
        }
    }
    else if (type == '') {
        if ($('entry').value == 'channel') {
            $('user_name').value = 'channel';
        }
        else if ($('entry').value == 'distribution') {
            $('user_name').value = '_distribution';
        }
    }
}

var distributionName = new Array();
var distributionUsername = new Array();
{{foreach from=$areas item=item key=key}}
{{if $key > 20}}
distributionName.push('{{$item}}');
distributionUsername.push('{{$distributionArea[$key]}}');
{{/if}}
{{/foreach}}

changeEntry($('entry').value);

</script>