{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
发货日期：<input type="text" name="fromdate" id="fromdate" size="12" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
- <input  type="text" name="todate" id="todate" size="12" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
结算日期：<input type="text" name="settle_fromdate" id="settle_fromdate" size="12" value="{{$param.settle_fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
- <input  type="text" name="settle_todate" id="settle_todate" size="12" value="{{$param.settle_todate}}"  class="Wdate"  onClick="WdatePicker()"/>
<br><br>
结算状态：
<select name="status" id="write_off">
  <option value="">请选择...</option>
  <option value="1" {{if $param.status eq '1'}}selected{{/if}}>未结算</option>
  <option value="2" {{if $param.status eq '2'}}selected{{/if}}>部分结算</option>
  <option value="3" {{if $param.status eq '3'}}selected{{/if}}>已结算</option>
</select>
直供商：
  <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=shop}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
    {{/foreach}}
  </select>
订单号码：<input type="text" name="batch_sn" size="20" maxLength="50" value="{{$param.batch_sn}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">直供订单列表</div>
<div style="float:right;margin-top:10px;margin-right:10px"><b>应结金额：{{$total.amount}}&nbsp;&nbsp;已结金额：{{$total.settle_amount}}&nbsp;&nbsp;促销金额：{{$total.promotion_amount}}&nbsp;&nbsp;扣点金额：{{$total.point_amount}}</b></div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>订单号</td>
            <td>直供商</td>
            <td>订单金额</td>
            <td>应结金额</td>
            <td>已结金额</td>
            <td>促销金额</td>
            <td>扣点金额</td>
            <td>发货日期</td>
            <td>结算日期</td>
            <td>结算状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.batch_sn}}</td>
        <td>{{$data.shop_name}}</td>
        <td>{{$data.balance_amount}}</td>
        <td>{{$data.amount}}</td>
        <td>{{$data.settle_amount}}</td>
        <td>{{$data.promotion_amount}}</td>
        <td>{{$data.point_amount}}</td>
        <td>{{$data.logistic_time|date_format:"%Y-%m-%d"}}</td>
        <td>{{if $data.settle_time}}{{$data.settle_time|date_format:"%Y-%m-%d"}}{{/if}}</td>
        <td>
          {{if $data.settle_amount <= 0}}未结算
          {{elseif $data.settle_amount > 0 && $data.amount > $data.settle_amount+$data.promotion_amount+$data.point_amount}}部分结算
          {{elseif $data.amount eq $data.settle_amount+$data.promotion_amount+$data.point_amount}}已结算
          {{/if}}
        </td>
        <td>
          <input type="button" onclick="openDiv('/admin/finance/clear-distribution/batch_sn/{{$data.batch_sn}}','ajax','直供订单结款',800,500,true)" value="结款">
	      <input type="button" onclick="window.open('/admin/order/info/batch_sn/{{$data.batch_sn}}')" value="查看">
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
