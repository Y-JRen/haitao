{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">
      <select name="entry" id="entry" onchange="changeEntry(this.value)">
        <option value="">请选择...</option>
        <option value="self" {{if $param.entry eq 'self'}}selected{{/if}}>官网自营</option>
        <option value="call" {{if $param.entry eq 'call'}}selected{{/if}}>呼叫中心</option>
        <option value="channel" {{if $param.entry eq 'channel'}}selected{{/if}}>渠道店铺</option>
        <option value="distribution" {{if $param.entry eq 'distribution'}}selected{{/if}}>分销</option>
        <option value="new_distribution" {{if $param.entry eq 'new_distribution'}}selected{{/if}}>直供</option>
        <option value="tuan" {{if $param.entry eq 'tuan'}}selected{{/if}}>团购</option>
      </select>
      <select name="type" id="type">
        <option value="">请选择...</option>
      </select>
      &nbsp;&nbsp;
      <!--
      支付方式：
      <select name="pay_type" id="pay_type">
        <option value="">请选择...</option>
        <option value="ems" {{if $param.pay_type eq 'ems'}}selected{{/if}}>EMS</option>
        <option value="sf" {{if $param.pay_type eq 'sf'}}selected{{/if}}>顺丰</option>
        <option value="self" {{if $param.pay_type eq 'self'}}selected{{/if}}>自营</option>
        <option value="tenpay" {{if $param.pay_type eq 'tenpay'}}selected{{/if}}>财付通</option>
        <option value="phonepay" {{if $param.pay_type eq 'phonepay'}}selected{{/if}}>手机支付</option>
      </select>
      -->
	  &nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">发生日期从：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="todate" id="todate" size="15" value="{{$param.todate}}" /></span>
    <input type="button" name="dosearch" value="按条件搜索" onclick="ajax_search($('searchForm'),'{{url param.todo=search}}','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>
</div>
{{/if}}

<div id="ajax_search">

<div class="title">财务退款统计 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]</div>
<div class="content">
        <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 退货单数 = 已发货单 发生退货次数(同一订单可能发生多次退货)<br>
	    　* 总退款金额 = 已付款的退款单<br>
	    　* 退货成本 = 退货产品成本<br>
	    </font>
	    </div>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
	<thead>
	  <tr>
		<td>渠道</td>
		<td>退货单数</td>
		<td>总退款金额</td>
		<td>优惠补偿退款</td>
		<td>退货退款</td>
		<td>系统退款</td>
		<td>退货成本</td>
		<td>退货成本(未税)</td>
		<td>拒收金额</td>
		{{if $cols}}
		  {{foreach from=$cols item=col}}
		  <td>{{$col}}</td>
		  {{/foreach}}
		{{/if}}
      </tr>
	</thead>
	<tbody>
	  {{foreach from=$datas item=item key=key}}
	  <tr>
		<td>
		  {{if $key eq 'self'}}官网自营
	      {{elseif $key eq 'call_in'}}呼入
	      {{elseif $key eq 'call_out'}}呼出
	      {{elseif $key eq 'call_tq'}}咨询
	      {{elseif $key eq 'channel'}}渠道店铺
	      {{elseif $key eq 'distribution'}}分销
	      {{elseif $key eq 'tuan'}}团购
	      {{elseif $key eq 'jiankang'}}1健康
	      {{elseif $key eq 'employee'}}国药内购
          {{elseif $key eq 'call'}}呼叫中心
          {{elseif $key eq 'internal'}}内购
          {{elseif $key eq 'gift'}}客情
          {{elseif $key eq 'other'}}其他
          {{elseif $key eq 'batch_channel'}}购销
          {{elseif $key eq 'new_distribution'}}直供
          {{else}}{{$key}}
          {{/if}}
		</td>
		<td>{{$item.count|default:0}}</td>
		<td>{{$item.amount|default:0}}</td>
		<td>{{$item.external_amount|default:0}}</td>
		<td>{{$item.return_amount|default:0}}</td>
		<td>{{$item.auto_amount|default:0}}</td>
		<td>{{$item.cost_amount|default:0}}</td>
		<td>{{$item.no_tax_cost_amount|default:0}}</td>
		<td>{{$item.reject_amount|default:0}}</td>
		{{if $cols}}
		  {{foreach from=$cols item=col}}
		  <td>{{$item[$col]|default:0}}</td>
		  {{/foreach}}
		{{/if}}
	  </tr>
	  {{/foreach}}
	  {{if $total}}
	  <tr>
	    <td><b>合计</b></td>
	    <td><b>{{$total.count}}</b></td>
	    <td><b>{{$total.amount}}</b></td>
	    <td><b>{{$total.external_amount}}</b></td>
	    <td><b>{{$total.return_amount}}</b></td>
	    <td><b>{{$total.auto_amount}}</b></td>
	    <td><b>{{$total.cost_amount}}</b></td>
	    <td><b>{{$total.no_tax_cost_amount}}</b></td>
	    <td><b>{{$total.reject_amount}}</b></td>
	    {{if $cols}}
		  {{foreach from=$cols item=col}}
		  <td><b>{{$total[$col]}}</b></td>
		  {{/foreach}}
		{{/if}}
	  </tr>
	  {{/if}}
	</tbody>
  </table>
</div>
<div style="padding:0 5px;"></div>
</div>
<script>
function changeEntry(val)
{
    $('type').options.length = 0;
    $('type').options.add(new Option('请选择...', ''));
    if (val == 'self') {
        $('type').options.add(new Option('1健康', 'jiankang'{{if $param.type eq 'jiankang'}}, true, true{{/if}}));
        $('type').options.add(new Option('国药内购', 'employee'{{if $param.type eq 'employee'}}, true, true{{/if}}));
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

changeEntry($('entry').value);

</script>