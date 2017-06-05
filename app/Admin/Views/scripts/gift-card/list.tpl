{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form name="searchForm" id="searchForm">
    <div>
        <span style="float:left">使用日期从：<input type="text" name="add_time_from" id="add_time_from" size="11" value="{{$param.add_time_from}}"  class="Wdate" onClick="WdatePicker()" /></span>
        <span style="float:left; margin-left:10px">截止到：<input type="text" name="add_time_end" id="add_time_end" size="11" value="{{$param.add_time_end}}" class="Wdate"   onClick="WdatePicker()" /></span>
        <span style="margin-left:5px; vertical-align:top">
        状态: 
        <select name="status">
          <option value="">请选择</option>
          <option value="0" {{if $param.status eq '0'}}selected{{/if}}>有效</option>
          <option value="1" {{if $param.status eq '1'}}selected{{/if}}>无效</option>
          <option value="2" {{if $param.status eq '2'}}selected{{/if}}>未激活</option>
        </select>
        </span>
        <span style="margin-left:5px; vertical-align:top">价格: <input type="text" name="card_price" value="{{$param.card_price}}" size="6" /></span>
        <span style="margin-left:5px; vertical-align:top">卡号: <input type="text" name="card_sn" value="{{$param.card_sn}}" size="11" /></span>
        <span style="margin-left:5px; vertical-align:top">用户名: <input type="text" name="user_name" value="{{$param.user_name}}" size="20" /></span>
        <span style="margin-left:5px; vertical-align:top"><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/></span>
    </div>
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">礼品卡列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>礼品卡类型</td>
            <td>礼品卡价格</td>
            <td>卡号</td>
            <td>生成时间</td>
            <td>占用金额</td>
            <td>余额</td>
            <td>最后使用用户</td>
            <td>最后使用时间</td>
			<td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$cardList item=card}}
        <tr id="ajax_list{{$history.card_id}}">
            <td>{{$card.card_id}}</td>
            <td>{{$card.card_type}}</td>
            <td>{{$card.card_price}}</td>
            <td>{{$card.card_sn}}</td>
            <td>{{$card.add_time}}</td>
            <td>{{$card.occupy_price}}</td>
            <td>{{$card.card_real_price}}</td>
            <td>{{$card.user_name}}</td>
            <td>{{$card.using_time}}</td>
			  <td>{{if $card.status eq '0' }}有效{{elseif $card.status eq '1'}}无效{{elseif $card.status eq '2'}}未激活{{/if}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=use-log param.card_sn=$card.card_sn}}')">查看使用历史</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</div>