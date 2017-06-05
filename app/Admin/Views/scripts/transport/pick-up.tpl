<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" method="get">
开始日期：<input type="text" name="date" id="date" size="12" value="{{$param.date}}"  class="Wdate" onClick="WdatePicker()"/>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	{{html_options options=$logisticList selected=$param.logistic_code}}
</select>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="{{$param.consignee}}"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="{{$param.bill_no}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
</form>
<div class="title">配送管理 -&gt; 当天物流公司取件</div>
<form name="myForm" id="myForm">
<div class="content">
	<div style="padding:0 5px;float:right;"><h3>总件数：{{$total}}</h3></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>单据编号</td>
            <td>店铺</td>
            <td>地区</td>
            <td>收货人</td>
            <td>付款方式</td>
            <td>物流公司</td>
            <td>物流单号</td>
            <td>发货时间</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.tid}}">
        <td>{{$data.bill_no_str}}</td>
        <td>{{$data.shop_name}}</td>
        <td>{{$data.province}}{{$data.city}}{{$data.area}}</td>
        <td>{{$data.consignee}}</td>
        <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
        <td>{{$data.logistic_name}}</td>
        <td>{{$data.logistic_no}}</td>
        <td>{{$data.send_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
	    </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
</form>
