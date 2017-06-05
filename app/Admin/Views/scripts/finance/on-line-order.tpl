{{if !$param.job}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
    <div class="search">
    <form name="searchForm" id="searchForm" action="{{url}}">
    <div style="clear:both; padding-top:5px">
    结算日期：<input type="text" name="clear_fromdate" id="clear_fromdate" size="12" value="{{$param.clear_fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
    - <input  type="text" name="clear_todate" id="clear_todate" size="12" value="{{$param.clear_todate}}"  class="Wdate"  onClick="WdatePicker()"/>
    发货日期：<input type="text" name="send_fromdate" id="send_fromdate" size="12" value="{{$param.send_fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
    - <input  type="text" name="send_todate" id="send_todate" size="12" value="{{$param.send_todate}}"  class="Wdate"  onClick="WdatePicker()"/>
    下单类型:
    <select name="entry" id="entry" onchange="changeEntry(this.value)">
      <option value="">请选择...</option>
      <option value="b2c" {{if $param.entry eq 'b2c'}}selected{{/if}}>官网B2C</option>
      <option value="channel" {{if $param.entry eq 'channel'}}selected{{/if}}>渠道运营</option>
      <option value="call" {{if $param.entry eq 'call'}}selected{{/if}}>呼叫中心</option>
      <option value="distribution" {{if $param.entry eq 'distribution'}}selected{{/if}}>渠道分销</option>
      <option value="other" {{if $param.entry eq 'other'}}selected{{/if}}>其它下单</option>
    </select>
    <select name="type" id="type" onchange="changeType(this.value)">
      <option value="">请选择...</option>
	</select>
    店铺：
    <select name="shop_id" id="shop_id">
      <option value="">请选择...</option>
      {{foreach from=$shopDatas item=shop}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
      {{/foreach}}
    </select>
	<input type="hidden" name="user_name" id="user_name" size="20" maxLength="50" value="{{$param.user_name}}">
	<br><br>
	订单状态:
    <select name="status">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status eq '0'}}selected{{/if}}>有效单</option>
    <option value="4" {{if $param.status eq '4'}}selected{{/if}}>分销单</option>
    <option value="5" {{if $param.status eq '5'}}selected{{/if}}>预售单</option>
    </select>
    支付方式：<select name="pay_type" id="pay_type">
              <option value="" >请选择 </option>
                {{foreach from=$payment_list item=payment }}
                  {{if $payment.pay_type ne 'external' && $payment.pay_type ne 'cod'}}
                  <option value="{{$payment.pay_type}}" {{if $param.pay_type eq $payment.pay_type}}selected{{/if}}>  {{$payment.name}} </option>
                  {{/if}}
                {{/foreach}}
                <option value="bank" {{if $param.pay_type eq 'bank'}}selected{{/if}}>银行打款</option>
                <option value="cash" {{if $param.pay_type eq 'cash'}}selected{{/if}}>现金支付</option>
            </select>
        订单号：<input type="text" name="batch_sn" size="16" maxLength="50" value="{{$param.batch_sn}}">
        配送状态:
        <select name="status_logistic">
        <option value="">请选择...</option>
        <option value="0" {{if $param.status_logistic eq '0'}}selected{{/if}}>未确认</option>
        <option value="1" {{if $param.status_logistic eq '1'}}selected{{/if}}>已确认[待收款]</option>
        <option value="2" {{if $param.status_logistic eq '2'}}selected{{/if}}>待发货</option>
        <option value="3" {{if $param.status_logistic eq '3'}}selected{{/if}}>已发货未签收</option>
        <option value="4" {{if $param.status_logistic eq '4'}}selected{{/if}}>客户已签收</option>
        </select>
        <!--
        退换货状态:
        <select name="status_return">
        <option value="">请选择...</option>
        <option value="0" {{if $param.status_return eq '0'}}selected{{/if}}>正常单</option>
        <option value="1" {{if $param.status_return eq '1'}}selected{{/if}}>退货单</option>
        </select>
        -->
        结算状态:
        <select name="clear_pay">
        <option value="">请选择...</option>
        <option value="0" {{if $param.clear_pay eq '0'}}selected{{/if}}>未结算</option>
        <option value="1" {{if $param.clear_pay eq '1'}}selected{{/if}}>已结算</option>
        </select>
        
        <input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
        </div>	
        </form>
    </div>
	<div class="title">在线付款订单查询 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]</div>
{{/if}}

<div id="ajax_search">
    {{if !empty($datas)}}
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td>操作</td>
                <td >订单号</td>
                <td>支付方式</td>
                <td>下单时间</td>
                <td>发货时间</td>
                <td>金额</td>
                <td>佣金</td>
                <td>结算状态</td>
              </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=item}}
        <tr id="ajax_list{{$item.order_batch_id}}">
            <td valign="top">
              <input type="button" onclick="window.open('/admin/order/info/batch_sn/{{$item.batch_sn}}')" value="查看">
            </td>
            <td valign="top">{{$item.batch_sn}}</td>
            <td valign="top">{{$item.pay_name}}</td>
            <td valign="top">{{$item.add_time|date_format:"%y-%m-%d %H:%M:%S"}}</td>
            <td valign="top">{{$item.logistic_time|date_format:"%y-%m-%d %H:%M:%S"}}</td>
            <td valign="top">{{$item.price_payed}}</td>
            <td valign="top">{{$item.commission}}</td>
            <td valign="top">{{if $item.clear_pay eq 1}}已结算{{else}}未结算{{/if}}</td>
          </tr>
        {{/foreach}}
        </tbody>
        </table>
        
        <div class="page_nav">{{$pageNav}}</div>
    {{/if}}
    </div>

<script>
function changeEntry(val)
{
    $('type').options.length = 0;
    $('type').options.add(new Option('请选择...', ''));
    if (val == 'b2c') {
        $('type').options.add(new Option('官网下单', '0'{{if $param.type eq '0' && $param.user_name ne 'yumi_jiankang' && $param.user_name ne 'xinjing_jiankang'}}, true, true{{/if}}));
        $('type').options.add(new Option('玉米网下单', '0'{{if $param.type eq '0' && $param.user_name eq 'yumi_jiankang'}}, true, true{{/if}}));
        $('type').options.add(new Option('信景下单', '0'{{if $param.type eq '0' && $param.user_name eq 'xinjing_jiankang'}}, true, true{{/if}}));
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