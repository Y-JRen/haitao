<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
发货开始日期：<input  class="Wdate" onClick="WdatePicker()" type="text" name="fromdate" id="fromdate" size="11" value="{{$param.fromdate}}"/>
结束日期：<input  class="Wdate" onClick="WdatePicker()" type="text" name="todate" id="todate" size="11" value="{{$param.todate}}"/>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
    <option value="sf" {{if $param.logistic_code eq 'sf'}}selected{{/if}}>顺丰</option>
    <option value="ems" {{if $param.logistic_code eq 'ems'}}selected{{/if}}>EMS</option>
</select>
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
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	{{html_options options=$billType}}
</select>
<br />
部门：
<select name="sub_code" id="sub_code">
  <option value="">请选择</option>
  <option value="jiankang" {{if $param.sub_code eq 'jiankang'}}selected{{/if}}>1健康</option>
  <option value="call" {{if $param.sub_code eq 'call'}}selected{{/if}}>呼叫中心</option>
  <option value="other" {{if $param.sub_code eq 'other'}}selected{{/if}}>其它</option>
</select>
配送状态：
<select name="logistic_status">
    <option value="">请选择</option>
	{{html_options options=$logisticStatus selected=$param.logistic_status}}
</select>
结算状态：<select name="cod_status"><option value="">请选择</option><option value="1">已结算</option><option value="0">未结算</option></select>

运单号码：<input type="text" name="logistic_no" size="15" maxLength="50" value="{{$param.logistic_no}}"/>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="{{$param.consignee}}"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="{{$param.bill_no}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
</form>
<div class="title">代收货款管理 -&gt; 代收货款变更</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="float:left;width:600px;"><input type="checkbox" onclick="checkall($('myForm'),'ids',this)" title="全选/全不选" name="chkall"> <input type="button" onclick="ajax_submit(this.form, '/admin/cod/lock-transport/val/1','Gurl(\'refresh\',\'ajax_search\')')" value="锁定"> <input type="button" onclick="ajax_submit(this.form, '/admin/cod/lock-transport/val/0','Gurl(\'refresh\',\'ajax_search\')')" value="解锁"></div>
<div style="float:right;">
    <b>总金额：{{$sum}}</b>
    &nbsp;&nbsp;&nbsp;
    </div>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>单据类型</td>
            <td>物流公司</td>
            <td>金额</td>
            <td>佣金</td>
            <td>结算状态</td>
            <td>运单号</td>
            <td>配送状态</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.tid}}">
    	<td><input type="checkbox" name="ids[]" value="{{$data.tid}}"/></td>
        <td>
			<input type="button" onclick="openDiv('{{url param.action=view-cod param.id=$data.tid}}','ajax','查看单据')" value="查看">
        </td>
        <td>{{$data.bill_no}}</td>
        <td>{{$billType[$data.bill_type]}}</td>
        <td>{{$data.logistic_name}}</td>
        <td>{{$data.amount+$data.change_amount}}</td>
        <td>{{$data.logistic_price_cod|default:'0.00'}}</td>
        <td>{{if $data.cod_status==1}}已{{else}}未{{/if}}结算</td>
        <td>{{$data.logistic_no}}</td>
        <td>{{$logisticStatus[$data.logistic_status]}}</td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
<div style="padding:0 5px;"><input type="checkbox" onclick="checkall($('myForm'),'ids',this)" title="全选/全不选" name="chkall"> <input type="button" onclick="ajax_submit(this.form, '/admin/cod/lock-transport/val/1','Gurl(\'refresh\',\'ajax_search\')')" value="锁定"> <input type="button" onclick="ajax_submit(this.form, '/admin/cod/lock-transport/val/0','Gurl(\'refresh\',\'ajax_search\')')" value="解锁">
</div>
</div>
</div>
</form>
<div class="page_nav">{{$pageNav}}</div>
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