{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="{{url param.action=member-daily}}">
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:100px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"  /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="{{$param.todate}}" class="Wdate" onClick="WdatePicker()"  /></span>
    <input type="radio" name="dateFormat" value="Y-m-d" {{if $param.dateFormat eq 'Y-m-d'}}checked{{/if}}>按天
    <input type="radio" name="dateFormat" value="Y-m"  {{if $param.dateFormat eq 'Y-m'}}checked{{/if}}>按月
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'{{url param.todo=search}}','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">会员数量列表   </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/haitaoadmin/images/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 下单会员数 = 正常单 前台下单/电话下单 会员数量<br>
	    　* 复购会员人数 = 正常单 前台下单/电话下单 每月下单超过一次的会员数量<br>
	    　* 订单复购率 = 复购会员人数 / 下单会员数<br>
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td >日期</td>
				<td><a href="{{url param.sortField=RegUserCount param.sortType=$sortType}}">新增注册会员数</a></td>
				<td><a href="{{url param.sortField=FrontRegUserCount param.sortType=$sortType}}">自然注册人数</a></td>
				<td><a href="{{url param.sortField=CPSRegUserCount param.sortType=$sortType}}">CPS注册人数</a></td>
				<td><a href="{{url param.sortField=UserOrderCount param.sortType=$sortType}}">下单会员数</a></td>
				<td><a href="{{url param.sortField=UserMoreOrderCount param.sortType=$sortType}}">复购会员人数</a></td>
				<td><a href="{{url param.sortField=MoreUserOrderRate param.sortType=$sortType}}">订单复购率</a></td>
			  </tr>
		</thead>
		<tbody>
		{{if $datas}}
		{{foreach from=$datas item=data}}
		<tr>
		  <td>{{$data.date}}</td>
		  <td>{{if $data.RegUserCount}}{{$data.RegUserCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.FrontRegUserCount}}{{$data.FrontRegUserCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.CPSRegUserCount}}{{$data.CPSRegUserCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.UserOrderCount}}{{$data.UserOrderCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.UserMoreOrderCount}}{{$data.UserMoreOrderCount}}{{else}}0{{/if}}</td>
		  <td>{{if $data.MoreUserOrderRate}}{{$data.MoreUserOrderRate}}{{else}}0{{/if}}%</td>
		</tr>
		{{/foreach}}
		<thead>
		<tr>
		  <td>合计</td>
		  <td>{{$totalData.RegUserCount}}</td>
		  <td>{{$totalData.FrontRegUserCount}}</td>
		  <td>{{$totalData.CPSRegUserCount}}</td>
		  <td>{{$totalData.UserOrderCount}}</td>
		  <td>{{$totalData.UserMoreOrderCount}}</td>
		  <td></td>
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