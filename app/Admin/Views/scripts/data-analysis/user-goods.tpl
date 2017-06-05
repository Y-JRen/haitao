{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">
    <select name="shop_id">
        <option value="">请选择店铺...</option>
        {{foreach from=$shopDatas item=shop}}
        <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
        {{/foreach}}
        <option value="0" {{if $param.shop_id eq '0'}}selected{{/if}}>内部订单</option>
    </select>&nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">选择日期从：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="todate" id="todate" size="15" value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()" /></span>
    &nbsp;&nbsp;
    订单状态:<select name="status">
			<option value="1" {{if $param.status=="1"}}selected{{/if}}>有效单</option>
			<option value="2" {{if $param.status=="2"}}selected{{/if}}>取消单/无效单</option>
			</select>
    <br><br>
    商品编码：<input name="goods_sn" id="goods_sn" type="text"  size="10" value="{{$param.goods_sn}}" />
    商品名称：<input name="goods_name" id="goods_name" type="text"  size="18" value="{{$param.goods_name}}" />

<input type="button" name="dosearch" value="按条件搜索" onclick="if (document.getElementById('goods_sn').value == '' && document.getElementById('goods_name').value == ''){alert('必须输入搜索的商品信息!');return false;} ajax_search($('searchForm'),'{{url param.todo=search}}','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">客户购买记录 [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>] </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td >姓名</td>
				<!--<td >手机</td>-->
				<!--<td >固话</td>-->
				<td >渠道站点来源</td>
				<td >购买产品</td>
				<td >购买次数</td>
				<td >购买数量</td>
				<td >购买总金额</td>
				<td >首次购买时间</td>
				<td >最近购买时间</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$userGoodslist item=item}}
		<tr>
			<td>{{$item.addr_consignee}} </td>
			<!--<td>{{$item.addr_mobile}} </td>-->
			<!--<td>{{$item.addr_tel}} </td>-->
			<td>{{$item.shop_name}} </td>
			<td>{{$item.goods_name}} </td>
			<td>{{$item.order_count}} </td>
			<td>{{$item.goods_number}} </td>
			<td>{{$item.amount}} </td>
			<td>{{$item.add_time|date_format:"%Y-%m-%d %H:%M"}} </td>
			<td>{{$item.last_time|date_format:"%Y-%m-%d %H:%M"}} </td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>	