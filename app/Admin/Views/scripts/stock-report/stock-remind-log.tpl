<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" onsubmit="return check();" action="/admin/stock-report/stock-remind-log">
<div>
    <span style="float:left">开始日期：
        <input type="text"  value="{{$params.start_ts}}" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        截止日期：<input  type="text"  value="{{$params.end_ts}}" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span> 产品编码：
        <input type="text" value="{{$params.product_sn}}" maxlength="10" size="10" name="product_sn">
    </span>
    <span> 单据编号：
        <input type="text" value="{{$params.batch_sn}}" name="batch_sn">
    </span>
    <input type="submit" name="dosearch" value="查询" />
</div>
</div>
</form>
</div>
<div class="title">库存预警日志</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品名称</td>
            <td>单据编号</td>
            <th>需求量</th>
            <td>可用量</td>
            <td>创建时间</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$infos item=info}}
        <tr>
            <td>{{$info.product_id}}</td>
            <td>{{$info.product_sn}}</td>
            <td>{{$info.product_name}}</td>
            <td>{{$info.batch_sn}}</td>
            <td>{{$info.need_number}}</td>
            <td>{{$info.able_number}}</td>
            <td>{{$info.created_ts}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
