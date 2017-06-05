{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
<span style="float:left;line-height:18px;">结束日期：<input class="Wdate" onClick="WdatePicker()" type="text" name="todate" id="todate" size="11" value="{{$param.todate}}"/></span>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	{{html_options options=$logisticList}}
</select>
<br>
运单号码：<input type="text" name="logistic_no" size="20" maxLength="50" value="{{$param.logistic_no}}"/>
收货人：<input type="text" name="consignee" size="10" maxLength="50" value="{{$param.consignee}}"/>
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value="{{$param.admin_name}}"/>
结算单号：<input type="text" name="clear_no" size="20" maxLength="50" value="{{$param.clear_no}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">

</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">代收货款管理 -&gt; 结算单查询</div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>操作</td>
            <td>物流公司</td>
            <td>结算单</td>
            <td>实际返款</td>
            <td>佣金</td>
            <td>调整金额</td>
            <td>结算日期</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.id}}">
        <td>
			<input type="button" onclick="openDiv('{{url param.action=view-clear param.id=$data.id }}','ajax','查看单据')" value="查看">
        </td>
        <td>{{$logisticList[$data.logistic_code]}}</td>
        <td>{{$data.clear_no}}</td>
        <td>{{$data.real_amount}}</td>
        <td>{{$data.commission}}</td>
        <td>{{$data.adjust_amount}}</td>
        <td>{{$data.clear_time|date_format:"%Y-%m-%d"}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>

 </form>
<script language="JavaScript">
function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    new Request({
        url: '/admin/member/area/id/' + value,
        onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
                    option.value = index;
                    option.text  = item;
                    select.options.add(option);
	            });
	        }
            loadSucess();
        }
    }).send();
}
</script>