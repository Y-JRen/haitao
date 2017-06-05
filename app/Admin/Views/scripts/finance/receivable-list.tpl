{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
生成日期：<input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"/>
- <input type="text" name="todate" id="todate" size="12" value="{{$param.todate}}" class="Wdate"  onClick="WdatePicker()"/>
发货日期：<input type="text" name="send_fromdate" id="send_fromdate" size="12" value="{{$param.send_fromdate}}" class="Wdate" onClick="WdatePicker()"/>
- <input type="text" name="send_todate" id="send_todate" size="12" value="{{$param.send_todate}}" class="Wdate"  onClick="WdatePicker()"/>
支付类型：
<select name="receivable_type" id="receivable_type" style="width:80px" onchange="changeType(this.value)">
  <option value="">请选择...</option>
  <option value="1" {{if $param.receivable_type eq 1}}selected{{/if}}>在线支付</option>
  <option value="2" {{if $param.receivable_type eq 2}}selected{{/if}}>后台支付</option>
  <option value="5" {{if $param.receivable_type eq 5}}selected{{/if}}>抵扣支付</option>
  <option value="6" {{if $param.receivable_type eq 6}}selected{{/if}}>换货支付</option>
</select>
<select name="pay_type" id="pay_type">
</select>
<br><br>
渠道：<select name="entry" id="entry" onchange="changeEntry(this.value)">
  <option value="">请选择...</option>
  <option value="self" {{if $param.entry eq 'self'}}selected{{/if}}>官网自营</option>
</select>
<select name="type" id="type">
  <option value="">请选择...</option>
</select>
结款状态：
<select name="clear_pay" id="clear_pay">
  <option value="">请选择...</option>
  <option value="0" {{if $param.clear_pay eq '0'}}selected{{/if}}>未结款</option>
  <option value="1" {{if $param.clear_pay eq '1'}}selected{{/if}}>已结款</option>
</select>
单据编号：<input type="text" name="batch_sn" size="20" maxLength="50" value="{{$param.batch_sn}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
<input type="button" onclick="if ($('pay_type').value == ''){alert('请先选择支付类型');return;}openDiv('/admin/finance/receivable-clear/pay_type/'+$('pay_type').value,'ajax','批量结款',780,400);" value="批量结款">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">应收款查询</div>
<form name="myForm" id="myForm">
<div class="content">
    <div style="text-align:right;">
      <b>应收金额：{{$total.amount}}&nbsp;&nbsp;已结金额：{{$total.settle_amount}}&nbsp;&nbsp;佣金：{{$total.commission}}&nbsp;&nbsp;差异：{{$total.diff}}</b>&nbsp;&nbsp;<br><br>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>渠道</td>
            <td>单据编号</td>
            <td>支付方式</td>
            <td>应收金额</td>
            <td>已结金额</td>
            <td>佣金</td>
            <td>生成日期</td>
            <td>发货日期</td>
            <td>结算日期</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>
	      <input type="button" onclick="window.open('/admin/order/info/batch_sn/{{$data.batch_sn}}')" value="查看">
	      {{if $data.settle_time eq 0}}
	        <input type="button" onclick="openDiv('/admin/finance/receivable-single-clear/id/{{$data.id}}','ajax','应收款结款',300,150,true)" value="结款">
	      {{/if}}
        </td>
        <td>
          {{if $data.shop_name}}{{$data.shop_name}}
          {{elseif $data.type eq 14}}{{$data.addr_consignee}}
          {{elseif $data.user_name eq 'gift'}}客情
          {{elseif $data.user_name eq 'other'}}其它
          {{elseif $data.user_name eq 'internal'}}内购
          {{elseif $data.status eq 4}}
            {{foreach from=$areas key=areaID item=areaName}}
            {{if $areaID eq $distributionUsername[$data.user_name]}}{{$areaName}}{{/if}}
            {{/foreach}}
          {{/if}}
        </td>
        <td>
          {{if $data.pay_type eq 'sf' || $data.pay_type eq 'ems'}}{{$data.logistic_no}}
          {{elseif $data.pay_type eq 'external' || $data.pay_type eq 'externalself'}}{{$data.external_order_sn}}
          {{else}}{{$data.batch_sn}}
          {{/if}}
        </td>
        <td>
          {{if $data.pay_type eq 'alipay'}}支付宝
          {{elseif $data.pay_type eq 'phonepay'}}手机支付
          {{elseif $data.pay_type eq 'tenpay'}}财付通
          {{elseif $data.pay_type eq 'bankcomm'}}交通银行
          {{elseif $data.pay_type eq 'bank'}}银行打款
          {{elseif $data.pay_type eq 'cash'}}现金支付
          {{elseif $data.pay_type eq 'credit'}}赊销支付
          {{elseif $data.pay_type eq 'distribution'}}直供支付
          {{elseif $data.pay_type eq 'external'}}渠道支付
          {{elseif $data.pay_type eq 'externalself'}}渠道代发货支付
          {{elseif $data.pay_type eq 'sf'}}顺丰
          {{elseif $data.pay_type eq 'ems'}}EMS
          {{elseif $data.pay_type eq 'gift'}}礼品卡
          {{elseif $data.pay_type eq 'point'}}积分
          {{elseif $data.pay_type eq 'account'}}账户余额
          {{elseif $data.pay_type eq 'exchange'}}换货支付
          {{elseif $data.pay_type eq 'easipay'}}东方支付
          {{/if}}
        </td>
        <td>{{$data.amount}}</td>
        <td>{{$data.settle_amount}}</td>
        <td>{{$data.commission}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
        <td>{{$data.logistic_time|date_format:"%Y-%m-%d"}}</td>
        <td>{{if $data.settle_time}}{{$data.settle_time|date_format:"%Y-%m-%d"}}{{/if}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
<script>
function changeType(val)
{
    $('pay_type').options.length = 0;
    if (val == 1) {
        $('pay_type').options.add(new Option('东方支付', 'easipay'{{if $param.pay_type eq 'easipay'}}, true, true{{/if}}));
      
    }
    else if (val == 2) {
        $('pay_type').options.add(new Option('银行打款', 'bank'{{if $param.pay_type eq 'bank'}}, true, true{{/if}}));
        $('pay_type').options.add(new Option('现金支付', 'cash'{{if $param.pay_type eq 'cash'}}, true, true{{/if}}));
    }
    else if (val == 3) {
        $('pay_type').options.add(new Option('渠道支付', 'external'{{if $param.pay_type eq 'external'}}, true, true{{/if}}));
        $('pay_type').options.add(new Option('渠道代发货支付', 'externalself'{{if $param.pay_type eq 'externalself'}}, true, true{{/if}}));
    }
    else if (val == 4) {
        $('pay_type').options.add(new Option('顺丰', 'sf'{{if $param.pay_type eq 'sf'}}, true, true{{/if}}));
        $('pay_type').options.add(new Option('EMS', 'ems'{{if $param.pay_type eq 'ems'}}, true, true{{/if}}));
    }
    else if (val == 5) {
        $('pay_type').options.add(new Option('礼品卡', 'gift'{{if $param.pay_type eq 'gift'}}, true, true{{/if}}));
        $('pay_type').options.add(new Option('账户余额', 'account'{{if $param.pay_type eq 'account'}}, true, true{{/if}}));
    }
    else if (val == 6) {
        $('pay_type').options.add(new Option('换货支付', 'exchange'{{if $param.pay_type eq 'exchange'}}, true, true{{/if}}));
    }
    else {
        $('pay_type').options.add(new Option('请选择...', ''));
    }
}

function changeEntry(val)
{
    $('type').options.length = 0;
    $('type').options.add(new Option('请选择...', ''));
    if (val == 'self') {
        $('type').options.add(new Option('海淘网', 'haitao'{{if $param.type eq 'haitao'}}, true, true{{/if}}));
        //$('type').options.add(new Option('国药内购', 'employee'{{if $param.type eq 'employee'}}, true, true{{/if}}));
        $('type').options.add(new Option('客情', 'gift'{{if $param.type eq 'gift'}}, true, true{{/if}}));
        $('type').options.add(new Option('内购', 'internal'{{if $param.type eq 'internal'}}, true, true{{/if}}));
        $('type').options.add(new Option('其他', 'other'{{if $param.type eq 'other'}}, true, true{{/if}}));
    }
    else if (val == 'call') {
        $('type').options.add(new Option('呼入', 'call_in'{{if $param.type eq 'call_in'}}, true, true{{/if}}));
        $('type').options.add(new Option('呼出', 'call_out'{{if $param.type eq 'call_out'}}, true, true{{/if}}));
        $('type').options.add(new Option('咨询', 'call_tq'{{if $param.type eq 'call_tq'}}, true, true{{/if}}));
    }
    else if (val == 'channel') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] == 'jiankang' || shop[0] == 'tuan' || shop[0] == 'credit' || shop[0] == 'distribution')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
    }
    else if (val == 'distribution') {
        $('type').options.add(new Option('购销', 'batch_channel'{{if $param.type eq 'batch_channel'}}, true, true{{/if}}));
        {{foreach from=$areas item=item key=key}}
          {{if $key > 20}}
          $('type').options.add(new Option('{{$item}}', '{{$distributionArea[$key]}}'{{if $param.type eq $distributionArea[$key]}}, true, true{{/if}}));
          {{/if}}
        {{/foreach}}
    }
    else if (val == 'new_distribution') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] != 'distribution')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
    }
    else if (val == 'tuan') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] != 'tuan' && shop[0] != 'credit')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
    }
}


var type = '{{$param.type}}';
var shopData = new Array();
{{foreach from=$shopDatas item=shop name=shop}}
{{assign var="index" value=$smarty.foreach.shop.iteration-1}}
shopData[{{$index}}] = '{{$shop.shop_type}}_{{$shop.shop_id}}_{{$shop.shop_name}}';
{{/foreach}}

changeType($('receivable_type').value);
changeEntry($('entry').value);

</script>