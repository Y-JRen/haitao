<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
省份：<select name="province_id" onchange="getArea(this)">
    <option value="">请选择省</option>
	{{html_options options=$province}}
</select>
城市：<select name="city_id" onchange="getArea(this)">
    <option value="">请选择市</option>
	{{html_options options=$city}}
</select>
区县：<select name="area_id">
    <option value="">请选择区</option>
	{{html_options options=$area}}
</select>
<div class="line">
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	{{html_options options=$logisticList selected=$param.logistic_code}}
</select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option><option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option></select>
是否投诉：<select name="is_complain"><option value="">请选择</option><option value="0" {{if $param.is_complain eq '0'}}selected{{/if}}>否</option><option value="1" {{if $param.is_complain eq '1'}}selected{{/if}}>是</option></select>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	{{html_options options=$billType selected=$param.bill_type}}
</select>
配送状态：
<select name="logistic_status">
    <option value="">请选择</option>
	{{html_options options=$logisticStatus selected=$param.logistic_status}}
</select>
</div>
<div class="line">
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="{{$param.consignee}}"/>
运单号码：<input type="text" name="logistic_no" size="15" maxLength="50" value="{{$param.logistic_no}}"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="{{$param.bill_no}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>

</div>
</form>
<div class="title">配送管理 -&gt; 运输单跟踪</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
{{if $datas}}
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>物流公司</td>
            <td>运输单号</td>
            <td>付款方式</td>
            <td>省份</td>
            <td>城市</td>
            <td>区县</td>
            <td>配送状态</td>
            <td>单据编号</td>
            <td>单据类型</td>
            <td>发货日期</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.sid}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.tid}}"/></td>
        <td>
			<input type="button" onclick="openDiv('{{url param.action=track param.id=$data.tid}}','ajax','运输单跟踪',750,400)" value="查看">
            {{if $data.logistic_code eq 'ht' or  $data.logistic_code eq 'zjs' }}
            <input type="button" onclick="openDiv('/admin/transport/bill-track/logistic_code/{{$data.logistic_code}}/logistic_no/{{$data.logistic_no}}','ajax','运输单跟踪查询',500,350)" 
            value="运输单跟踪查询">
            {{/if}}
        </td>
        <td>{{$data.logistic_name}}</td>
        <td>
        {{if $data.logistic_code eq 'zjs'}}
        <a href="http://www.zjs.com.cn/ws_business/WS_Business_RequestQuery.aspx?gzdh={{$data.logistic_no}}" target="_blank" title="官网查询"><b>{{$data.logistic_no}}</b></a>
        {{elseif $data.logistic_code eq 'st'}}
        <a href="http://61.152.237.204:8081/query_result.asp?jdfwkey=lx6x82&wen={{$data.logistic_no}}" target="_blank" title="官网查询"><b>{{$data.logistic_no}}</b></a>
        {{elseif $data.logistic_code eq 'ht'}}
        <a href="http://www.htky365.com/" target="_blank" title="官网查询"><b>{{$data.logistic_no}}</b></a>
        {{elseif $data.logistic_code eq 'ems'}}
        <a href="http://www.ems.com.cn/qcgzOutQueryAction.do?reqCode=gotoSearch&mailNum={{$data.logistic_no}}" target="_blank" title="官网查询"><b>{{$data.logistic_no}}</b></a>
        {{elseif $data.logistic_code eq 'jldt'}}
        <a href="http://www.kerryeas.com/htdocs/cargotrackandtrace/express/client_enquiry_2a.html" target="_blank" title="官网查询"><b>{{$data.logistic_no}}</b></a>
        {{elseif $data.logistic_code eq 'sf'}}
        <a href="http://www.sf-express.com/" target="_blank" title="官网查询"><b>{{$data.logistic_no}}</b></a>
        {{else}}
        {{$data.logistic_no}}
        {{/if}}
        </td>
        <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
        <td>{{$data.province}}</td>
        <td>{{$data.city}}</td>
        <td>{{$data.area}}</td>
        <td>{{$logisticStatus[$data.logistic_status]}}</td>
        <td>{{$data.bill_no_str}}</td>
        <td>{{$billType[$data.bill_type]}}</td>
        <td>{{$data.send_time|date_format:"%Y-%m-%d"}}</td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
    {{/if}}
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>

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