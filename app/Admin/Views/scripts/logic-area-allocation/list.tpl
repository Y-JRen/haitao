{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
{{$catSelect}}
商品编码：<input type="text" name="product_sn" size="12" maxLength="50" value="{{$param.product_sn}}"/>
商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/>
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$fromdate}}"   class="Wdate" onClick="WdatePicker()" />
结束日期：<input type="text" name="todate" id="todate" size="15" value="{{$todate}}" class="Wdate" onClick="WdatePicker()" />
<div class="line">
<select name="bill_status">
<option value="">选择单据状态</option>
<option value="is_check_0">未审核</option>
<option value="is_check_1">已审核</option>
<option value="is_confirm_0">未确认</option>
<option value="is_confirm_1">已确认</option>
<option value="is_send_0">待发货</option>
<option value="is_send_1">已发货</option>
<option value="is_receive_0">待收货</option>
<option value="is_receive_1">已收货</option>
</select>
制单人：<input type="text" name="admin_name" size="10" maxLength="20" value=""/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="30" value=""/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="ajax_search(this.form,'{{url param.do=search param.is_lock=yes}}','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="ajax_search(this.form,'{{url param.do=search param.is_lock=no}}','ajax_search')"/>
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">仓储管理 -&gt; {{$area_name}} -&gt; 调拨单管理 -&gt;{{$actions.$action}}</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>制单人</td>
            <td>制单日期</td>
            <td>单据状态</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.aid}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.aid}}"/></td>
        <td>
			<input type="button" onclick="openDiv('{{url param.action=$operates.$action param.id=$data.aid}}','ajax','查看单据',750,400)" value="查看">
        </td>
        <td>{{$data.bill_no}}</td>
        <td>{{$data.admin_name}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
        <td>
            {{if $data.is_cancel==1}}
            待取消
            {{else}}
            {{$billStatus[$data.bill_status]}}
            {{/if}}
        </td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
<div class="page_nav">{{$pageNav}}</div>
</form>
</div>