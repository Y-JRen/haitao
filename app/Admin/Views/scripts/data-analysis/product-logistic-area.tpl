{{if !$param.do}}

<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url param.action=logistic-delivery}}">
    <span style="float:left;line-height:18px;">
      <select name="shop_id">
        <option value="">请选择店铺...</option>
        {{foreach from=$shopDatas item=shop}}
        <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
        {{/foreach}}
        <option value="0" {{if $param.shop_id eq '0'}}selected{{/if}}>内部订单</option>
      </select>&nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">发货日期从：</span><span style="float:left;width:100px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:120px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()" /></span>
    产品名称：<input name="product_name" id="product_name" type="text"  size="18" value="{{$param.product_name}}"/>
    产品编号：<input name="product_sn" id="product_sn" type="text"  size="8" value="{{$param.product_sn}}"/>
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    <br>
    地区：
    <select name="province_id">
      <option value="">请选择地区...</option>
      {{foreach from=$provinceData item=province_id key=province_name}}
        <option value="{{$province_id}}" {{if $param.province_id eq $province_id}}selected{{/if}}>{{$province_name}}</option>
      {{/foreach}}
    </select>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">产品发货区域列表   </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>产品编号</td>
			    <td>产品名称</td>
				<td>地区</td>
				<td>发货单数</td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>{{$data.product_sn}}</td>
		  <td>{{$data.goods_name}}</td>
		  <td>{{$data.area_name}}</td>
		  <td>{{if $data.count}}{{$data.count}}{{else}}0{{/if}}</td>
		</tr>
		{{/foreach}}
		<thead>
		<tr>
		  <td>合计</td>
		  <td></td>
		  <td></td>
		  <td>{{$totalData.count}}</td>
		</tr>
		</thead>
		{{/if}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
</div>	

<script type="text/javascript">

</script>