{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url param.action=goods-daily}}">
    <span style="float:left;line-height:18px;">
      <select name="entry" id="entry" onchange="changeEntry(this.value)">
        <option value="">请选择...</option>
        <option value="self" {{if $param.entry eq 'self'}}selected{{/if}}>官网自营</option>
        <option value="call" {{if $param.entry eq 'call'}}selected{{/if}}>呼叫中心</option>
        <option value="channel" {{if $param.entry eq 'channel'}}selected{{/if}}>渠道店铺</option>
        <option value="distribution" {{if $param.entry eq 'distribution'}}selected{{/if}}>分销</option>
        <option value="tuan" {{if $param.entry eq 'tuan'}}selected{{/if}}>团购</option>
      </select>
      <select name="type" id="type">
        <option value="">请选择...</option>
      </select>
	  &nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">发货日期从：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <br><br>
    <select name="supplier_id">
      <option value="">请选择供应商...</option>
      {{foreach from=$supplierData item=supplier}}
        <option value="{{$supplier.supplier_id}}" {{if $supplier.supplier_id eq $param.supplier_id}}selected{{/if}}>{{$supplier.supplier_name}}</option>
      {{/foreach}}
   </select>
    产品名称：<input name="product_name" type="text"  size="18" value="{{$param.product_name}}"/>
    产品编号：<input name="product_sn" type="text"  size="8" value="{{$param.product_sn}}"/>
    <input type="radio" name="group" value="0" {{if !$param.group}}checked{{/if}}>不按日期
    <input type="radio" name="group" value="1" {{if $param.group eq 1}}checked{{/if}}>按月
    <input type="radio" name="group" value="2" {{if $param.group eq 2}}checked{{/if}}>按日
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    [<a href="{{url param.action=goods-daily-export}}?{{$smarty.server.QUERY_STRING}}" target="_blank">导出查询结果</a>]
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">销售商品列表   </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 发货数量 = 正常单/分销单 已发货 商品总数量<br>
	    　* 退货数量 = 正常单/分销单 已发货 退货商品总数量<br>
	    　* 销售总金额 = 发货产品金额 - 退货产品金额<br>
	    　* 平均售价 = 销售总金额 / (发货数量 - 退货数量)<br>
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    {{if $param.group}}<td>日期</td>{{/if}}
				<td>产品编号</td>
				<td>产品名称</td>
				<td>平均售价</td>
				<td><a href="{{url param.sortField=OutStockCount param.sortType=$sortType}}">发货数量</a></td>
				<td><a href="{{url param.sortField=ReturnCount param.sortType=$sortType}}">退货数量(发货中的退货)</a></td>
				<!--<td><a href="{{url param.sortField=RealOutStockCount param.sortType=$sortType}}">实际发货数量</a></td>-->
				<td><a href="{{url param.sortField=TotalCost param.sortType=$sortType}}">总成本</a></td>
				<td><a href="{{url param.sortField=TotalNoTaxCost param.sortType=$sortType}}">总成本(未税)</a></td>
				<td><a href="{{url param.sortField=BenefitAmount param.sortType=$sortType}}">毛利</a></td>
				<td><a href="{{url param.sortField=BenefitRate param.sortType=$sortType}}">毛利率</a></td>
				<td><a href="{{url param.sortField=TotalAmount param.sortType=$sortType}}">销售总金额</a></td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  {{if $param.group}}<td>{{$data.date}}</td>{{/if}}
		  <td>{{$data.product_sn}}</td>
		  <td>{{$data.product_name}}</td>
		  <td>{{$data.AveragePrice}}</td>
		  <td>{{if $data.OutStockCount}}{{$data.OutStockCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.ReturnCount}}{{$data.ReturnCount}}{{else}}0{{/if}}</td>
		  <!--<td>{{if $data.RealOutStockCount}}{{$data.RealOutStockCount}}{{else}}0{{/if}}</td>-->
		  <td>{{if $data.TotalCost}}{{$data.TotalCost}}{{else}}0{{/if}}</td>
		  <td>{{if $data.TotalNoTaxCost}}{{$data.TotalNoTaxCost}}{{else}}0{{/if}}</td>
		  <td>{{if $data.BenefitAmount}}{{$data.BenefitAmount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.BenefitRate}}{{$data.BenefitRate}}%{{else}}{{/if}}</td>
		  <td>{{if $data.TotalAmount}}{{$data.TotalAmount}}{{else}}0{{/if}}</td>
		</tr>
		{{/foreach}}
		<thead>
		<tr>
		  <td>合计</td>
		  {{if $param.group}}<td>*</td>{{/if}}
		  <td>*</td>
		  <td>{{$totalData.AveragePrice}}</td>
		  <td>{{$totalData.OutStockCount}}</td>
		  <td>{{$totalData.ReturnCount}}</td>
		  <!--<td>{{$totalData.RealOutStockCount}}</td>-->
		  <td>{{$totalData.TotalCost}}</td>
		  <td>{{$totalData.TotalNoTaxCost}}</td>
		  <td>{{$totalData.BenefitAmount}}</td>
		  <td>{{if $totalData.BenefitRate}}{{$totalData.BenefitRate}}%{{/if}}</td>
		  <td>{{$totalData.TotalAmount}}</td>
	    </tr>
		</thead>
		{{/if}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
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