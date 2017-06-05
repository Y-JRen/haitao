{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url param.action=goods-daily}}">
    <span style="float:left;line-height:18px;">
      <select name="shop_id">
        <option value="">请选择店铺...</option>
        {{foreach from=$shopDatas item=shop}}
        <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
        {{/foreach}}
      </select>
	  &nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">下单开始日期：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">下单结束日期：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <br><br>
    <select name="cat_id">
        <option value="">请选择商品大类...</option>
        <option value="1" {{if $param.cat_id eq '1'}}selected{{/if}}>保健品</option>
        <option value="2" {{if $param.cat_id eq '2'}}selected{{/if}}>保健食品</option>
      </select>
    产品名称：<input name="product_name" type="text"  size="18" value="{{$param.product_name}}"/>
    产品编号：<input name="product_sn" type="text"  size="8" value="{{$param.product_sn}}"/>
    <input type="button" name="dosearch" value="开始统计" onclick="if (document.getElementById('fromdate').value == '') {alert('下单开始日期必须选择!');return;}ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">产品购买统计  [<a href="{{url param.todo=export}}" target="_blank">导出信息</a>] </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 订单总金额 = 包含该产品的有效订单总金额 各类店铺(不包括内部下单)<br>
	    　* 产品销售总金额 = 该商品的销售总金额<br>
	    　* 产品总单数 = 包含该产品的订单总数<br>
	    　* 单一购买数 = 只包含该产品的订单总数<br>
	    　* 连带购买总单数 = 订单中既有该产品又有其它产品的订单数<br>
	    　* 复购单数 = 在时间区间内购买单数大于1（包含时间范围之前购买判定）<br>
	    　* 流失会员数 = 在时间区间前(下单开始日期)有过购买，但时间区间范围内没有购买的用户数<br>
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>产品编号</td>
				<td>产品名称</td>
				<td>订单总金额</td>
				<td>产品销售总金额</td>
				<td>产品总单数</td>
				<td>单一购买数</td>
				<td>连带购买总单数</td>
				<td>复购单数</td>
				<td>流失会员数</td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>{{$data.product_sn}}</td>
		  <td>{{$data.product_name}}</td>
		  <td>{{if $data.Amount}}{{$data.Amount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.ProductAmount}}{{$data.ProductAmount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.OrderCount1}}{{$data.OrderCount1}}{{else}}0{{/if}}</td>
		  <td>{{if $data.OrderCount2}}{{$data.OrderCount2}}{{else}}0{{/if}}</td>
		  <td>{{if $data.OrderCount3}}{{$data.OrderCount3}}{{else}}0{{/if}}</td>
		  <td>{{if $data.OrderCount4}}{{$data.OrderCount4}}{{else}}0{{/if}}</td>
		  <td>{{if $data.UserCount}}{{$data.UserCount}}{{else}}0{{/if}}</td>
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