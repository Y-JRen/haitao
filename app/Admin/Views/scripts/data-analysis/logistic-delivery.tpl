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
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()" /></span>
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

<div class="title">物流单数量列表   </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>物流公司</td>
				<td><a href="{{url param.sortField=TotalCount param.sortType=$sortType}}">总单数</a></td>
				<td><a href="{{url param.sortField=SignCount param.sortType=$sortType}}">已签收</a></td>
				<td><a href="{{url param.sortField=NotSignCount param.sortType=$sortType}}">未签收</a></td>
				<td><a href="{{url param.sortField=RefuseCount param.sortType=$sortType}}">拒收</a></td>
				<td><a href="{{url param.sortField=MatchCount param.sortType=$sortType}}">匹配总单数</a></td>
				<td><a href="{{url param.sortField=TotalPrice param.sortType=$sortType}}">总运费</a></td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>{{$data.logistic_name}}</td>
		  <td>{{if $data.TotalCount}}{{$data.TotalCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.SignCount}}{{$data.SignCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.NotSignCount}}{{$data.NotSignCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.RefuseCount}}{{$data.RefuseCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.MatchCount}}{{$data.MatchCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.TotalPrice}}{{$data.TotalPrice}}{{else}}0{{/if}}</td>
		</tr>
		{{/foreach}}
		<thead>
		<tr>
		  <td>合计</td>
		  <td>{{$totalData.TotalCount}}</td>
		  <td>{{$totalData.SignCount}}</td>
		  <td>{{$totalData.NotSignCount}}</td>
		  <td>{{$totalData.RefuseCount}}</td>
		  <td>{{$totalData.MatchCount}}</td>
		  <td>{{$totalData.TotalPrice}}</td>
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

<script type="text/javascript">
function checkprovinceall(current)
{
    var province = document.getElementsByName('province');
    for ( i = 0; i < province.length; i++ ) {
        province[i].checked = current.checked;
    }
}
</script>