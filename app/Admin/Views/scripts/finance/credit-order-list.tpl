{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}" class="Wdate" onClick="WdatePicker()"/>
结束日期：<input type="text" name="todate" id="todate" size="15" value="{{$param.todate}}" class="Wdate"  onClick="WdatePicker()"/>
&nbsp;赊销商：
<select name="shop_id" id="shop_id" style="width:80px">
  <option value="">请选择...</option>
  {{foreach from=$shopDatas item=item}}
    <option value="{{$item.shop_id}}" {{if $param.shop_id eq $item.shop_id}}selected{{/if}}>{{$item.shop_name}}</option>
  {{/foreach}}
</select>
&nbsp;结款状态：
<select name="clear_pay" id="clear_pay">
  <option value="">请选择...</option>
  <option value="0" {{if $param.clear_pay eq '0'}}selected{{/if}}>未结款</option>
  <option value="1" {{if $param.clear_pay eq '1'}}selected{{/if}}>已结款</option>
</select>
<br><br>
订单号：<input type="text" name="batch_sn" size="20" maxLength="50" value="{{$param.batch_sn}}"/>
收货人：<input type="text" name="addr_consignee" size="20" maxLength="50" value="{{$param.addr_consignee}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">结款订单查询</div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>赊销商</td>
            <td>订单号</td>
            <td>收货人</td>
            <td>下单日期</td>
            <td>应收金额</td>
            <td>已收金额</td>
            <td>结算状态</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>
          {{if $data.clear_pay eq '0'}}
          <input type="button" onclick="openDiv('/admin/finance/clear-special/batch_sn/{{$data.batch_sn}}','ajax','特殊订单结款',400,200,true)" value="结款">
          {{/if}}
	      <input type="button" onclick="window.open('/admin/order/info/batch_sn/{{$data.batch_sn}}')" value="查看">
        </td>
        <td>{{$data.shop_name}}</td>
        <td>{{$data.batch_sn}}</td>
        <td>{{$data.addr_consignee}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
        <td>{{$data.price_order}}</td>
        <td>{{if $data.price_payed}}{{$data.price_payed}}{{else}}0{{/if}}</td>
        <td>
          {{if $data.clear_pay}}
            已结款
          {{else}}
            未结款
          {{/if}}
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
 </form>