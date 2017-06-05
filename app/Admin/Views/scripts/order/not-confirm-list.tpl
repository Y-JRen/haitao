<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form id="searchForm">
    <span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">结束日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
    支付状态:
    <select name="status_pay">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_pay eq '0'}}selected{{/if}}>未收款</option>
    <option value="1" {{if $param.status_pay eq '1'}}selected{{/if}}>未退款</option>
    <option value="2" {{if $param.status_pay eq '2'}}selected{{/if}}>已收款</option>
    <option value="3" {{if $param.status_pay eq '3'}}selected{{/if}}>部分收款</option>
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
	<br style="clear:both;"/>
    ID：<input type="text" name="order_batch_id" size="20" maxLength="50" value="{{$param.order_batch_id}}">
    订单号：<input type="text" name="batch_sn" size="20" maxLength="50" value="{{$param.batch_sn}}">
	用户名：<input type="text" name="user_name" id="user_name" size="20" maxLength="50" value="{{$param.user_name}}">
	收货人名字：<input type="text" name="addr_consignee" size="20" maxLength="50" value="{{$param.addr_consignee}}">
	<br />
	最小金额：<input type="text" name="min_price" size="20" maxLength="50" value="{{$param.min_price}}">
	最大金额：<input type="text" name="max_price" size="20" maxLength="50" value="{{$param.max_price}}">
	店铺：
    <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=shop}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
    {{/foreach}}
    </select>
    渠道订单号：
    <input type="text" name="external_order_sn" value="{{$param.external_order_sn}}" />
    限价：<input type="checkbox" name="price_limit" value="1" {{if $param.price_limit eq '1'}}checked='true'{{/if}}/>
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
	<br />
	<input type="button" name="dosearch" value="所有被我锁定的订单" onclick="ajax_search($('searchForm'),'{{url param.do=search param.is_lock=yes}}','ajax_search')"/>
	<input type="button" name="dosearch" value="所有没有锁定的订单" onclick="ajax_search($('searchForm'),'{{url param.do=search param.is_lock=no}}','ajax_search')"/>
    <!-- <input type="button" name="dosearch" value="所有挂起的订单" onclick="ajax_search($('searchForm'),'{{url param.do=search param.hang=1}}','ajax_search')"/>-->
    </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">未确认订单列表</div>
	<div class="content">
<div style="padding:0 5px">
	<div style="float:left;width:600px;">
		<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
		<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="超级锁定" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="超级解锁" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="批量取消订单" onclick="if (confirm('确认执行批量取消订单操作？')) {ajax_submit(this.form, '{{url param.action=not-confirm-batch-cancel}}','Gurl(\'refresh\')');}">
        <!--<input type="button" value="处理满意无需退货（超过40天）" onclick="dealCompleteOrder()">-->
	</div>
	<div style="float:right;"><b>订单总金额：￥{{$totalPriceOrder}}</b></div>
</div>

		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td width=10></td>
				<td width="40">操作</td>
				<td>ID</td>
                <td>店铺</td>
				<td width="120">订单号</td>
                <td>下单时间</td>
				<td width="350">订单商品</td>
				<td width="60">收货人</td>
				<td>金额</td>
				<td>支付方式</td>
				<td>锁定状态</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.order_batch_id}}">
			<td valign="top"><input type='checkbox' name="ids[]" value="{{$item.batch_sn}}"></td>
			<td valign="top">
			{{if $item.lock_name==$auth}}
			<input type="button" onclick="G('/admin/order/not-confirm-info/batch_sn/{{$item.batch_sn}}')" value="修改">
			{{else}}
			<input type="button" onclick="G('/admin/order/not-confirm-info/batch_sn/{{$item.batch_sn}}')" value="查看">
			{{/if}}</td>
			<td valign="top">{{$item.order_batch_id}}</td>
            <td valign="top">{{$item.shop_name}}</td>
			<td valign="top" {{if $item.audit_status eq '1'}}style="color:#ff0000;"{{/if}}>
			{{$item.batch_sn}}
             <br />
			{{$item.status}}  {{$item.status_pay}} {{$item.status_logistic}}  {{$item.status_return}}   <br />
			{{if $item.hang}}<br /><font color="red">已被{{$item.hang_admin_name}}挂起</font>{{/if}}   
			{{if $item.status_back==1}}<font color="red">已申请取消</font><br />{{/if}}
			{{if $item.status_back==2}}<font color="red">已申请返回</font>{{/if}}
			</td>
            <td valign="top">{{$item.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>  
			<td valign="top">
				{{foreach from=$product item=goods}}
					{{if $goods.batch_sn==$item.batch_sn}}
						  {{$goods.goods_name}} (<font color="#FF3333">{{$goods.goods_style}}</font>)  
                         {{if $replenishment_infos[$item.order_batch_id][$goods.product_sn]}}<font color="{{$replenishment_infos[$item.order_batch_id][$goods.product_sn]}}">{{$goods.product_sn}}</font>{{else}}<font color="#336633">{{$goods.product_sn}} </font>{{/if}}<br />
					{{/if}}
				{{/foreach}}
			</td>
			<td valign="top">{{$item.addr_consignee}}</td>
			<td valign="top">
			{{if $item.blance>0}}应收：{{$item.blance}}<br />{{/if}}
			{{if $item.price_payed+$item.account_payed+$item.point_payed+$item.gift_card_payed+$item.price_from_return>0}}已收：{{$item.price_payed+$item.account_payed+$item.point_payed+$item.gift_card_payed+$item.price_from_return}}<br />{{/if}}
			{{if $item.blance<0}}应退：{{$item.blance|replace:"-":""}}<br />{{/if}}
			</td>
			<td valign="top">{{$item.pay_name}}</td>
			<td valign="top">{{if $item.lock_name}}<font color="red">被{{$item.lock_name}}锁定</font>{{else}}未锁定{{/if}}</td>
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
		<input type="button" value="批量取消订单" onclick="if (confirm('确认执行批量取消订单操作？')) {ajax_submit(this.form, '{{url param.action=not-confirm-batch-cancel}}','Gurl(\'refresh\')');}">
        <input type="button" value="处理满意无需退货（超过40天）" onclick="dealCompleteOrder()">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>
<script type="text/javascript">
function dealCompleteOrder(){
	new Request({
		url:'/admin/order/deal-complete-order',
		onRequest:function(){;},
		onSuccess:function(msg){
			if(msg=='ok'){
				alert('操作成功！');
			}else{
				alert('操作失败，请稍后重试。');
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试。');
		}
	}).send();
}
function changeEntry(val)
{
    $('type').options.length = 0;
    $('type').options.add(new Option('请选择...', ''));
    if (val == 'b2c') {
        $('type').options.add(new Option('官网下单', '0'{{if $param.type eq '0' && $param.user_name ne 'yumi_jiankang' && $param.user_name ne 'xinjing_jiankang'}}, true, true{{/if}}));
        //$('type').options.add(new Option('玉米网下单', '0'{{if $param.type eq '0' && $param.user_name eq 'yumi_jiankang'}}, true, true{{/if}}));
        //$('type').options.add(new Option('信景下单', '0'{{if $param.type eq '0' && $param.user_name eq 'xinjing_jiankang'}}, true, true{{/if}}));
        $('shop_id').options[1].selected = true;
    }
    else if (val == 'call') {
        $('type').options.add(new Option('呼入下单', '10'{{if $param.type eq '10'}}, true, true{{/if}}));
        $('type').options.add(new Option('呼出下单', '11'{{if $param.type eq '11'}}, true, true{{/if}}));
        $('type').options.add(new Option('咨询下单', '12'{{if $param.type eq '12'}}, true, true{{/if}}));
        $('shop_id').options[0].selected = true;
    }
    else if (val == 'channel') {
        $('type').options.add(new Option('渠道下单', '13'{{if $param.type eq '13'}}, true, true{{/if}}));
        $('type').options.add(new Option('渠道补单', '14'{{if $param.type eq '14' && $param.user_name ne 'batch_channel' && $param.user_name ne 'credit_channel'}}, true, true{{/if}}));
        $('type').options.add(new Option('购销下单', '14'{{if $param.type eq '14' && $param.user_name eq 'batch_channel'}}, true, true{{/if}}));
        $('type').options.add(new Option('赊销下单', '14'{{if $param.type eq '14' && $param.user_name eq 'credit_channel'}}, true, true{{/if}}));
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
        if ($('entry').value == 'channel' || $('entry').value == 'distribution') {
            $('user_name').value = $('entry').value;
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