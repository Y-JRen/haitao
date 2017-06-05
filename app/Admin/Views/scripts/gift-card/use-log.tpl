{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form name="searchForm" id="searchForm">
    <div>
        <span style="float:left">使用日期：<input type="text" name="add_time_from" id="add_time_from" size="11" value="{{$param.add_time_from}}" class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left; margin-left:10px"> - <input type="text" name="add_time_end" id="add_time_end" size="11" value="{{$param.add_time_end}}" class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left; margin-left:20px"">发货日期：<input type="text" name="send_time_from" id="send_time_from" size="11" value="{{$param.send_time_from}}" class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left; margin-left:10px"> - <input type="text" name="send_time_end" id="send_time_end" size="11" value="{{$param.send_time_end}}" class="Wdate" onClick="WdatePicker()"/></span>
        <br><br>
        卡号: <input type="text" name="card_sn" value="{{$param.card_sn}}" size="15" />
        面值: <input type="text" name="card_price" value="{{$param.card_price}}" size="6" />
        订单号: <input type="text" name="batch_sn" value="{{$param.batch_sn}}" size="20" />
        用户名: <input type="text" name="user_name" value="{{$param.user_name}}" size="20" />
        <input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
    </div>
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">礼品卡使用记录</div>
<div class="content">
    <div class="sub_title" style="text-align:right;">
      <b>总消费金额：{{$total.price}}</b>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>礼品卡类型</td>
            <td>卡号</td>
			<td>面值</td>
            <td>使用金额</td>
            <td>使用用户</td>
            <td>使用时间</td>
            <td>订单号</td>
            <td>发货时间</td>
            <td>订单状态</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$cardList item=card}}
        <tr id="ajax_list{{$history.card_id}}">
            <td>{{$card.log_id}}</td>
            <td>{{$card.card_type}}</td>
            <td>{{$card.card_sn}}</td>
			<td>{{$card.card_price}}</td>
            <td>{{$card.price}}</td>
            <td>{{$card.user_name}}</td>
            <td>{{$card.add_time}}</td>
            <td>{{$card.batch_sn}}</td>
            <td>{{$card.logistic_time}}</td>
            <td>
              {{if $card.status_logistic eq 0}}未确认
              {{elseif $card.status_logistic eq 1}}未收款
              {{elseif $card.status_logistic eq 2}}待发货
              {{elseif $card.status_logistic eq 3}}在途
              {{elseif $card.status_logistic eq 4}}发货已签收
              {{elseif $card.status_logistic eq 5}}发货已拒收
              {{/if}}
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</div>