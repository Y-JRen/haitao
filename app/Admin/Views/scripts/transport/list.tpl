<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<input type="hidden" name="export" value="0" id="export" />
<div class="search">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
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
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	{{html_options options=$logisticList selected=$param.logistic_code}}
</select>
是否投诉：<select name="is_complain"><option value="">请选择</option><option value="0" {{if $param.is_complain eq '0'}}selected{{/if}}>否</option><option value="1" {{if $param.is_complain eq '1'}}selected{{/if}}>是</option></select>
<div class="line">
店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=shop}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
    {{/foreach}}
  </select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option><option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option></select>
收货人：<input type="text" name="consignee" size="6" maxLength="20" value="{{$param.consignee}}"/>
运单号码：<input type="text" name="logistic_no" size="10" maxLength="50" value="{{$param.logistic_no}}"/>
单据编号：<input type="text" name="bill_no" size="15" maxLength="50" value="{{$param.bill_no}}"/>
验证码：<input type="text" name="validate_sn" size="5" maxLength="5" value="{{$param.validate_sn}}"/>
<input type="button" name="dosearch" value="查询" onclick="doExport(0)" />
<input type="reset" name="reset" value="清除"><input type="button" onclick="doExport(1)" value="导出">
</div>
</div>
</form>
<div id="ajax_search">
<div class="title">配送管理 -&gt; {{$actions.$action}}</div>
<form name="myForm" id="myForm">
<div class="content">
	<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
	</div>
    {{if $datas}}
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30"></td>
            <td>操作</td>
            <td>店铺</td>
            <td>单据编号</td>
            <td>运单号码</td>
            <td>收货人</td>
            <td>单据类型</td>
            <td>付款方式</td>
            <td>承运商</td>
            <td>发货日期</td>
            <td>配送状态</td>
            <td>运费</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.tid}}">
    	<td><input type="checkbox" name="ids[]" value="{{$data.tid}}"/></td>
        <td>
	<input type="button" onclick="openDiv('{{url param.action=view param.id=$data.tid}}','ajax','查看单据')" value="查看">
        </td>
        <td>{{$data.shop_name}}</td>
        <td>{{$data.bill_no_str}}
            {{if $data.is_cancel==1}}
            （待取消）
            {{elseif $data.is_cancel==2}}
            （已取消）
            {{/if}}</td>
        <td>{{$data.logistic_no}}</td>
        <td>{{$data.consignee}}</td>
        <td>{{$billType[$data.bill_type]}}<input type="hidden" name="info[{{$data.tid}}][bill_type]" value="{{$data.bill_type}}"></td>
        <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
        <td>{{$data.logistic_name}}</td>
        <td>{{if $data.send_time}}{{$data.send_time|date_format:"%Y-%m-%d"}}{{/if}}</td>
        <td>{{$logisticStatus[$data.logistic_status]}}</td>
        <td>{{$data.logistic_price}}</td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
    <input type="button" onclick="window.open('{{url param.action=export}}'+location.search)" value="导出数据">
    {{/if}}
    <div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
	</div>
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
</div>

<script>
function doExport(export_id)
{
    $("export").value = export_id;
    $("searchForm").submit();
}
</script>