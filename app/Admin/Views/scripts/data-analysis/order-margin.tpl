{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url param.action=goods-daily}}">
    <span style="float:left;line-height:18px;">下单日期从：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">发货日期从：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="send_fromdate" id="send_fromdate" size="12" value="{{$param.send_fromdate}}" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="send_todate" id="send_todate" size="12"  value="{{$param.send_todate}}" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <br><br>
    <select name="shop_id">
        <option value="">请选择店铺...</option>
        {{foreach from=$shopDatas item=shop}}
        <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
        {{/foreach}}
        <option value="0" {{if $param.shop_id eq '0'}}selected{{/if}}>内部订单</option>
    </select>&nbsp;&nbsp;
    <select name="supplier_id">
      <option value="">请选择供应商...</option>
      {{foreach from=$supplierData item=supplier}}
        <option value="{{$supplier.supplier_id}}" {{if $supplier.supplier_id eq $param.supplier_id}}selected{{/if}}>{{$supplier.supplier_name}}</option>
      {{/foreach}}
   </select>
    产品名称：<input name="product_name" type="text"  size="18" value="{{$param.product_name}}"/>
    产品编号：<input name="product_sn" type="text"  size="8" value="{{$param.product_sn}}"/>
    订单号：<input name="batch_sn" type="text"  size="20" value="{{$param.batch_sn}}"/>
    毛利率小于：<input name="rate" type="text"  style="width:20px" value="{{$param.rate}}"/>%
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">订单商品列表 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>]  </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 毛利率 = (订单金额-产品成本)/订单金额
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>订单号</td>
				<td>店铺</td>
				<td>下单时间</td>
				<td>支付方式</td>
				<td>发货时间</td>
				<td>订单金额</td>
				<td>产品成本</td>
				<td>毛利率</td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td><a href="/admin/order/info/batch_sn/{{$data.batch_sn}}" target="_blank">{{$data.batch_sn}}</a></td>
		  <td>{{$data.shop_name}}</td>
		  <td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
		  <td>{{if $data.pay_type eq 'cod'}}货到付款{{else}}款到发货{{/if}}</td>
		  <td>{{if $data.logistic_time}}{{$data.logistic_time|date_format:"%Y-%m-%d %H:%M:%S"}}{{/if}}</td>
		  <td>{{$data.price_order}}</td>
		  <td>{{$data.cost}}</td>
		  <td>{{$data.benefit_rate}}%</td>
		</tr>
		{{/foreach}}
		{{/if}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>	