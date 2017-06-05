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
    </span>
    <span style="float:left;line-height:18px;">发货日期/退款发生日期：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="send_fromdate" id="send_fromdate" size="15" value="{{$param.send_fromdate}}" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="send_todate" id="send_todate" size="15" value="{{$param.send_todate}}" /></span>
    <input type="button" name="dosearch" value="按条件搜索" onclick="ajax_search($('searchForm'),'{{url param.todo=search}}','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>
</div>
{{/if}}

<div id="ajax_search">

<div class="title">财务应收款 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]</div>
<div class="content">
        <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 应收金额 = 有效单/刷单/分销单/预售单 收款金额<br>
	    　* 结算金额 = 财务结算总金额<br>
	    </font>
	    </div>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
	<thead>
	  <tr>
		<td>支付方式</td>
		<td>应收金额</td>
		<td>结算金额</td>
		<td>佣金</td>
      </tr>
	</thead>
	<tbody>
	  {{foreach from=$datas item=item key=key}}
	  <tr>
		<td>{{$payTypeList[$item.pay_type]}}</td>
		<td>{{$item.amount|default:0}}{{if $item.pay_type eq 'gift' && $item.pre_amount gt 0}} [预抵扣:{{$item.pre_amount}}]{{/if}}</td>
		<td>{{$item.settle_amount|default:0}}</td>
		<td>{{$item.commission|default:0}}</td>
	  </tr>
	  {{/foreach}}
	  {{if $total}}
	  <tr>
	    <td><b>合计</b></td>
	    <td><b>{{$total.amount}}</b></td>
	    <td><b>{{$total.settle_amount}}</b></td>
	    <td><b>{{$item.commission|default:0}}</b></td>
	  </tr>
	  {{/if}}
	</tbody>
  </table>
  {{if $returnDatas}}
  <br><br>
  <table cellpadding="0" cellspacing="0" border="0" class="table" style="width:300px">
	<thead>
	  <tr>
		<td>退款类型</td>
		<td>退款金额</td>
      </tr>
    </thead>
    <tbody>
	  {{foreach from=$returnDatas item=item key=key}}
	    <tr>
	      <td>{{$key}}</td>
	      <td>{{$item}}</td>
	    </tr>
	  {{/foreach}}
	  {{if $returnTotal}}
	  <tr>
	    <td><b>合计</b></td>
	    <td><b>{{$returnTotal}}</b></td>
	  </tr>
	  {{/if}}
    </tbody>
  </table>
  {{/if}}
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