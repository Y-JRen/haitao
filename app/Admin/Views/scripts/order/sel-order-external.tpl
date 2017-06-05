{{if !$param.job}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
    <div id="source_select" style="padding:10px">
    <form name="searchForm" id="searchForm">
    <input type="hidden" name="type" value="{{$param.type}}">
    <div style="clear:both; padding-top:5px">
    <span style="float:left;line-height:18px;">开始日期：</span>
    <span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">结束日期：</span>
    <span style="float:left;width:230px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
    <br><br>
    当前店铺：<strong>{{$shop.shop_name}}</strong>
        &nbsp;&nbsp;&nbsp;
        订单号：<input type="text" name="order_sn" size="16" maxLength="50" value="{{$param.order_sn}}">
        {{if $param.shop_id eq 30}}
        支付流水号：<input type="text" name="payment_no" size="36" maxLength="50" value="{{$param.payment_no}}">
        {{/if}}
        <input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search_order')"/>
        </div>	
        </form>
    </div>
{{/if}}
<div id="ajax_search_order">
    {{if !empty($datas)}}
    <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td width=10>  <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('ajax_search_order'),'ids',this)"/> </td>
                <td>ID</td>
                <td >订单号</td>
                <td>下单时间</td>
                <td>发货时间</td>
                <td>金额</td>
                <td>结算状态</td>
              </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=item}}
        <tr id="ajax_list{{$item.order_id}}">
            <td valign="top">
              {{if !$item.is_settle}}
              <input type='checkbox' name="ids[]" value="{{$item.order_id}}">
              <input type="hidden" id="oinfo{{$item.order_id}}" value='{{$item.oinfo}}'> 
              {{/if}}
            </td>
            <td valign="top">{{$item.order_id}}</td>
            <td valign="top">{{$item.order_sn}}</td>
            <td valign="top">{{$item.order_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td valign="top">{{$item.logistic_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td valign="top">{{$item.amount}}</td>
            <td valign="top">{{if $item.is_settle eq 1}}已结算{{else}}未结算{{/if}}</td>
          </tr>
        {{/foreach}}
        </tbody>
        </table>
        <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
        <div class="page_nav">{{$pageNav}}</div>
    {{/if}}
    </div>