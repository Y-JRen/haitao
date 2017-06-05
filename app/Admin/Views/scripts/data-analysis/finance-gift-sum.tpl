{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">开始日期从：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="todate" id="todate" size="15" value="{{$param.todate}}" /></span>
    卡号：<input type="text" name="card_sn" id="card_sn" value="{{$param.card_sn}}" size="15">
    状态：
    <select name="status">
      <option value="">请选择...</option>
      <option value="0" {{if $param.status eq "0"}}selected{{/if}}>有效</option>
	  <option value="1" {{if $param.status eq "1"}}selected{{/if}}>无效</option>
	  <option value="2" {{if $param.status eq "2"}}selected{{/if}}>未激活</option>
	</select>
    <input type="button" name="dosearch" value="按条件搜索" onclick="ajax_search($('searchForm'),'{{url param.todo=search}}','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">礼品卡汇总 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]</div>
<div class="content">
        <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 面值 = 其它是特殊面值，主要是呼叫中心10/24导入的卡<br>
	    　* 期初卡内余额 = 开始日期的卡内余额<br>
	    　* 本期售卡数 = 发货日期内的销售数量<br>
	    　* 本期卡内消费1 = 发货日期在选择日期内的抵扣卡金额<br>
	    　* 本期卡内消费2 = 发货日期在选择日期后的抵扣卡金额<br>
	    　* 卡内退货退回金额 = 发货后的退回卡内金额<br>
	    　* 期末卡内余额 = 结束日期的卡内余额<br>
	    </font>
	    </div>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
	<thead>
	  <tr>
	    <td>面值</td>
		<td>期初卡内余额</td>
		<td>本期售卡数</td>
		<td>预售面值</td>
		<td>预售总金额(激活)</td>
		<td>预售总金额(在途)</td>
		<td>本期卡内消费1</td>
		<td>本期卡内消费2</td>
		<td>卡内退货退回金额</td>
		<td>期末卡内余额</td>
      </tr>
	</thead>
	<tbody>
	  {{if $datas}}
	  {{foreach from=$datas item=item key=key}}
	  <tr>
	    <td>
	      {{if $key eq 'other'}}其它{{else}}{{$key}}{{/if}}
	    </td>
		<td>{{$item.from_price|default:0}}</td>
		<td>{{$item.count|default:0}}</td>
		<td>{{$item.card_amount|default:0}}</td>
		<td>{{$item.active_amount|default:0}}</td>
		<td>{{$item.plan_amount|default:0}}</td>
		<td>{{$item.use_amount1|default:0}}</td>
		<td>{{$item.use_amount2|default:0}}</td>
		<td>{{$item.return_amount|default:0}}</td>
		<td>{{$item.to_price|default:0}}</td>
	  </tr>
	  {{/foreach}}
	  {{if $total}}
	  <tr>
	    <td><b>合计</b></td>
	    <td><b>{{$total.from_price}}</b></td>
	    <td><b>{{$total.count}}</b></td>
	    <td><b>{{$total.card_amount}}</b></td>
	    <td><b>{{$total.active_amount}}</b></td>
	    <td><b>{{$total.plan_amount}}</b></td>
	    <td><b>{{$total.use_amount1}}</b></td>
	    <td><b>{{$total.use_amount2}}</b></td>
	    <td><b>{{$total.return_amount}}</b></td>
	    <td><b>{{$total.to_price}}</b></td>
	  </tr>
	  {{/if}}
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
        $('type').options.add(new Option('呼叫中心', 'call'{{if $param.type eq 'call'}}, true, true{{/if}}));
        $('type').options.add(new Option('客情', 'gift'{{if $param.type eq 'gift'}}, true, true{{/if}}));
        $('type').options.add(new Option('内购', 'internal'{{if $param.type eq 'internal'}}, true, true{{/if}}));
        $('type').options.add(new Option('其他', 'other'{{if $param.type eq 'other'}}, true, true{{/if}}));
    }
    else if (val == 'channel') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] == 'jiankang' || shop[0] == 'tuan' || shop[0] == 'credit')   continue;
            
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