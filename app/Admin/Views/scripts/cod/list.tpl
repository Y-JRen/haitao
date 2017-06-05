<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	{{html_options options=$logisticList selected=$param.logistic_code}}
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
<br>
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
<select name="is_check">
<option value="">选择单据状态</option>
<option value="0" {{if $param.is_check eq '0'}}selected{{/if}}>未审核</option>
<option value="1" {{if $param.is_check eq '1'}}selected{{/if}}>已审核</option>
<option value="2" {{if $param.is_check eq '2'}}selected{{/if}}>已拒绝</option>
</select>


运单号码：<input type="text" name="logistic_no" size="15" maxLength="50" value="{{$param.logistic_no}}"/>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="{{$param.consignee}}"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="{{$param.bill_no}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
<br>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="G('{{url param.is_lock=yes}}'+location.search)"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="G('{{url param.is_lock=no}}'+location.search)"/>
</div>
</form>
<div class="title">代收货款变更管理 -&gt; {{$actions.$action}}</div>
<form name="myForm" id="myForm">

<div class="content">

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
</div>

    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>单据类型</td>
            <td>物流公司</td>
            <td>原订单金额</td>
            <td>变更后金额</td>
            <td>审核状态</td>
            <td>运单号</td>
            <td>配送状态</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.cid}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.cid}}"/></td>
        <td>
			<input type="button" onclick="openDiv('{{url param.action=$operates.$action param.id=$data.cid}}','ajax','查看单据')" value="查看">
        </td>
        <td>{{$data.bill_no}}</td>
        <td>{{$billType[$data.bill_type]}}</td>
        <td>{{$data.logistic_name}}</td>
        <td>{{$data.amount}}</td>
        <td>{{if $data.is_check==1}}{{$data.amount+$data.change_amount}}{{else}}{{$data.amount+$data.tmp_amount}}{{/if}}</td>
        <td>{{if $data.is_check==1}}已审核{{elseif $data.is_check==2}}已拒绝{{else}}未审核{{/if}}</td>
        <td>{{$data.logistic_no}}</td>
        <td>{{$logisticStatus[$data.logistic_status]}}</td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')">
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