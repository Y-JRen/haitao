{{if !$param.job}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
    <div id="source_select" style="padding:10px">
    <form name="searchForm" id="searchForm">
    <div style="clear:both; padding-top:5px">
    
    <span style="float:left;line-height:18px;">开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$fromdate}}"   class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">结束日期：<input type="text" name="todate" id="todate" size="15" value="{{$todate}}" class="Wdate" onClick="WdatePicker()" /></span>
    
    支付方式：<select name="pay_type" id="pay_type">
                {{foreach from=$payment_list item=payment }}
                 {{if $param.pay_type eq $payment.pay_type}}
                 <option value="{{$payment.pay_type}}">  {{$payment.name}} </option>
                 {{/if}}
                {{/foreach}}
                {{if $param.pay_type eq 'bank'}}
                  <option value="bank">银行打款</option>
                {{/if}}
                {{if $param.pay_type eq 'cash'}}
                  <option value="cash">现金支付</option>
                {{/if}}
            </select>
            {{if $param.pay_type eq 'alipay'}}
            <select name="sub_pay_type" id="sub_pay_type">
              {{if $param.sub_pay_type eq 'jiankang'}}
		        <option value="jiankang">1健康</option>
		      {{/if}}
		      {{if $param.sub_pay_type eq 'call'}}
		        <option value="call">呼叫中心</option>
		      {{/if}}
		      {{if $param.sub_pay_type eq 'employee'}}
		        <option value="employee">国药内购</option>
		      {{/if}}
		    </select>
		    {{/if}}
        订单号：<input type="text" name="batch_sn" size="16" maxLength="50" value="{{$param.batch_sn}}"><br>
        配送状态:
        <select name="status_logistic">
        <option value="">请选择...</option>
        <option value="0" {{if $param.status_logistic eq '0'}}selected{{/if}}>未确认</option>
        <option value="1" {{if $param.status_logistic eq '1'}}selected{{/if}}>已确认[待收款]</option>
        <option value="2" {{if $param.status_logistic eq '2'}}selected{{/if}}>待发货</option>
        <option value="3" {{if $param.status_logistic eq '3'}}selected{{/if}}>已发货未签收</option>
        <option value="4" {{if $param.status_logistic eq '4'}}selected{{/if}}>客户已签收</option>
        </select>
        退换货状态:
        <select name="status_return">
        <option value="">请选择...</option>
        <option value="0" {{if $param.status_return eq '0'}}selected{{/if}}>正常单</option>
        <option value="1" {{if $param.status_return eq '1'}}selected{{/if}}>退货单</option>
        </select>
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
                <td>支付方式</td>
                <td>结算状态</td>
              </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=item}}
        <tr id="ajax_list{{$item.order_batch_id}}">
            <td valign="top">
              {{if $item.clear_pay eq 0}}
              <input type='checkbox' name="ids[]" value="{{$item.order_batch_id}}">      
              <input type="hidden" id="oinfo{{$item.order_batch_id}}" value='{{$item.oinfo}}'>
              {{/if}}
            </td>
            <td valign="top">{{$item.order_batch_id}}</td>
            <td valign="top">{{$item.batch_sn}}</td>
            <td valign="top">{{$item.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
             <td valign="top">{{$item.logistic_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td valign="top">{{$item.price_payed}}</td>
            <td valign="top">{{$item.pay_name}}</td>
            <td valign="top">{{if $item.clear_pay eq 1}}已结算{{else}}未结算{{/if}}</td>
          </tr>
        {{/foreach}}
        </tbody>
        </table>
        <p><input onclick="addRow();" type="button" value="添加"> <input onclick="addRow();alertBox.closeDiv();" type="button" value="添加并关闭"></p>
        <div class="page_nav">{{$pageNav}}</div>
    {{/if}}
    </div>