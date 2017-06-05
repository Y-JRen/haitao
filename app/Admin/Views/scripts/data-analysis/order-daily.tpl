{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url param.action=order-daily}}">
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
    <span style="float:left;line-height:18px;">下单日期从：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="todate" id="todate" size="12" value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">付款日期从：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="pay_fromdate" id="pay_fromdate" size="12" value="{{$param.pay_fromdate}}" class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="pay_todate" id="pay_todate" size="12" value="{{$param.pay_todate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <input type="radio" name="dateFormat" value="Y-m-d" {{if $param.dateFormat eq 'Y-m-d'}}checked{{/if}}>按天
    <input type="radio" name="dateFormat" value="Y-m"  {{if $param.dateFormat eq 'Y-m'}}checked{{/if}}>按月
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    <br>
    收货地址(省)：
    <input type="checkbox" name="chkprovinceall" title="全选/全不选" onclick="checkprovinceall(this)"/>全选/全不选
    {{foreach from=$provinceData item=province_id key=province_name name=province}}
    {{if $province_id ne 3880}}<input type="checkbox" name="province" value="{{$province_id}}" {{if $param.province.$province_id}}checked{{/if}}>{{$province_name}}{{/if}}
    {{if $smarty.foreach.province.iteration eq 16}}<br>　　　　　　　　　　　　　　&nbsp;&nbsp;{{/if}}
    {{/foreach}}
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">订单列表 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>] </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 总单数 = 所有订单数量 包含取消单/刷单/分销单<br>
	    　* 订单总金额 = 有效订单总金额 + 退款金额<br>
	    　* 有效单数 = 正常单/分销单 订单数量<br>
	    　* 有效订单总金额 = 正常单/分销单 订单总金额<br>
	    　* 运费金额 = 正常单/不发货单 运费总金额<br>
	    　* 每单平均金额 = 有效订单总金额 / 有效单数<br>
	    　* 发货单数 = 正常单/不发货单 已发货 订单数量<br>
	    　* 退货单数 = 所有订单 退货单数量<br>
	    　* 退款金额 = 所有订单 退款总金额
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td ><a href="{{url param.sortField=date param.sortType=$sortType}}">日期</a></td>
				<td >店铺</td>
				<td ><a href="{{url param.sortField=TotalCount param.sortType=$sortType}}">总单数</a></td>
				<td ><a href="{{url param.sortField=TotalAmount param.sortType=$sortType}}">订单总金额</a></td>
				<td ><a href="{{url param.sortField=ValidCount param.sortType=$sortType}}">有效单数</a></td>
				<td ><a href="{{url param.sortField=Amount param.sortType=$sortType}}">有效订单总金额</a></td>
				<td ><a href="{{url param.sortField=LogisticAmount param.sortType=$sortType}}">运费金额</a></td>
				<td ><a href="{{url param.sortField=AvgAmount param.sortType=$sortType}}">每单平均金额</a></td>
				<td ><a href="{{url param.sortField=SentCount param.sortType=$sortType}}">发货单数</a></td>
				<td ><a href="{{url param.sortField=ReturnCount param.sortType=$sortType}}">退货单数</a></td>
				<td ><a href="{{url param.sortField=ReturnAmount param.sortType=$sortType}}">退款金额</a></td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>{{$data.date}}</td>
		  <td>
		    {{if $data.shop_name}}{{$data.shop_name}}
		    {{else}}
		      {{if $param.entry eq 'channel'}}渠道运营
		      {{elseif $param.entry eq 'call'}}呼叫中心
		      {{else}}其它下单
		      {{/if}}
		    {{/if}}
		  </td>
		  <td>{{if $data.TotalCount}}{{$data.TotalCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.TotalAmount}}{{$data.TotalAmount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.ValidCount}}{{$data.ValidCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.Amount}}{{$data.Amount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.LogisticAmount}}{{$data.LogisticAmount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.AvgAmount}}{{$data.AvgAmount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.SentCount}}{{$data.SentCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.ReturnCount}}{{$data.ReturnCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.ReturnAmount}}{{$data.ReturnAmount}}{{else}}0{{/if}}</td>
		</tr>
		{{/foreach}}
		<thead>
		<tr>
		  <td>合计</td>
		  <td></td>
		  <td >{{$totalData.TotalCount}}</td>
		  <td >{{$totalData.TotalAmount}}</td>
		  <td >{{$totalData.ValidCount}}</td>
		  <td >{{$totalData.Amount}}</td>
		  <td >{{$totalData.LogisticAmount}}</td>
		  <td >{{$totalData.AvgAmount}}</td>
		  <td >{{$totalData.SentCount}}</td>
		  <td >{{$totalData.ReturnCount}}</td>
		  <td >{{$totalData.ReturnAmount}}</td>
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

function checkprovinceall(current)
{
    var province = document.getElementsByName('province');
    for ( i = 0; i < province.length; i++ ) {
        province[i].checked = current.checked;
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